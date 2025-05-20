<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bookmarkcontroller extends CI_Controller {
    public $result_header, $result_footer, $result_email, $result_page, $page_menus,$result_bookmark;
    function __construct($result_header=null, $result_footer=null, $result_email=null, $result_page=null) {
        parent::__construct();
        if (!is_logged_in() || !check_admin()) {
            redirect('/login');
        }
        $this->load->library("session");
        $this->load->model('data_table');
        $this->load->model('super_insertmodel');
        $this->load->model('super_dbmodel');
        $this->result_header = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'header'));
        $this->result_footer = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'footer'));
        $this->result_email = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'forgot_password'));
        $this->result_bookmark = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'bookmark_background'));
        $this->result_page = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'page_settings'));
        $this->page_menus  = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'menu_text'));
    }

    //User Section 
    public function index() {
        $bookmarks_data = $this->super_dbmodel->get_sort_data("bookmarks", "*", array('id' => 'desc'));
        $companies_data = $this->super_dbmodel->get_sort_data("company", "*", array('cmp_id' => 'desc'));
        $users_data = $this->super_dbmodel->get_sort_data("register", "user_id,user_fname,user_lname", array('user_id' => 'desc'));
        $teams_data = $this->super_dbmodel->get_sort_data("teams", "*", array('id' => 'desc'));
        $graphics = $this->super_dbmodel->get_datawith_limit('graphics', '*', 25, 0);

        $this->load->view('admin/header', array('site_header' => 'Bookmarks'));
        $this->load->view('admin/sidebar', array('site_menu' => 'bookmarks', 'inner_active_menu' => 'inner_active_bookmarks'));
        $this->load->view('admin/bookmarks/bookmarks.php', array('bookmarks_data' => $bookmarks_data, 'companies_data' => $companies_data, 'teams_data' => $teams_data, 'users_data' => $users_data));

        $this->load->view('admin/footer', ['graphics' => $graphics, 'total_graphics' => $this->super_dbmodel->count_data('graphics', 'id', "NULL")]);
    }

    

    public function add(Type $var = null) {
        $tab_id = base64_decode($_GET['p_id']);
        $user_data = $this->session->userdata('login_auth');
        $type = $_GET['tab'];
        switch ($type) {
            case 'company':
                $tablename = 'company';
                $wherecol = 'cmp_id';
                $cols = 'cmp_text as name';
                $user_id = $user_data->user_id;
                break;
            case 'team':
                $tablename = 'teams';
                $wherecol = 'id';
                $cols = 'name';
                $user_id = $user_data->user_id;
                break;
            case 'user':
                $tablename = 'register';
                $wherecol = 'user_id';
                $cols = 'user_fname,user_lname';
                $user_id = $tab_id;
                break;
            default:
                $tablename = 'company';
                $wherecol = 'cmp_id';
                $cols = 'cmp_text as name';
                $user_id = $user_data->user_id;
                break;
        }
        $team_data = '';
        $cmp_data = '';
        $selected_user_data = '';

        $companytab = $teamtab = $customtab = array();
        //Default Order of Company, Team and User Tabs and its data

        

        if ($user_data->user_role == "1" && $type == 'company') {
            $where = array(
                'cmp_id' => $tab_id
            );
            $cmp_data = $this->super_dbmodel->get_where_data($tablename, '*,cmp_thumb as thumb', $where);
        } else if ($user_data->user_role == "1" && $type == 'team') {

            $where = array(
                'id' => $tab_id
            );
            $team_data = $this->super_dbmodel->get_where_data($tablename, '*', $where);
        } else if ($user_data->user_role == "1" && $type == 'user') {
            $where = array(
                'user_id' => $tab_id
            );
            $selected_user_data = $this->super_dbmodel->get_where_data_first($tablename, '*', $where);
            $cmp_data = $this->super_dbmodel->get_sort_data_like_all("company", "*,cmp_thumb as thumb", ['user_id' => $user_id], array('cmp_nick_title' => 'asc'));
            $team_data = $this->super_dbmodel->get_sort_data_like_all("teams", "*", ['user_ids' => $user_id], array('nick_title' => 'asc'));
        }
        // $bookmarks_tab_order = getset_meta('user_meta', 'select', $user_id, 'tabs_order', null);
        if ($type == 'user') {
            $tabs_order = getset_meta('user_meta', 'get', @$user_id, 'tabs_order');
        }
        $bookmark_user_data = $explode_bookmark = $sorted_tabs_arr = array();

        if (isset($tabs_order) && !empty($tabs_order) && is_array($tabs_order)) {
            $companyids = $team_ids = $tabsids = array();
            for ($i = 0; $i < count($tabs_order); $i++) {
                $tabs = explode('&', $tabs_order[$i]);
                if (strtolower($tabs[0]) == 'company') {
                    array_push($companyids, $tabs[1]);
                } elseif (strtolower($tabs[0]) == 'team') {
                    array_push($team_ids, $tabs[1]);
                } elseif (strtolower($tabs[0]) == 'tabs') {
                    array_push($tabsids, $tabs[1]);
                } else {
                    $usertab = $i;
                }
            }
            if (isset($companyids) && !empty($companyids)) {
                $companytab = $this->super_dbmodel->get_wherein_data("company", "cmp_id as id,cmp_nick_title as name,graphic_id", "cmp_id", $companyids);

                foreach ($companytab as $key => $csm) {

                    $companytab[$key]['type'] = 'company';
                }
            }


            if (isset($team_ids) && !empty($team_ids)) {
                $teamtab = $this->super_dbmodel->get_wherein_data("teams", "id,nick_title as name,graphic_id", "id", $team_ids);

                foreach ($teamtab as $key => $csm) {
                    $teamtab[$key]['type'] = 'team';
                }
            }


            if (isset($tabsids) && !empty($tabsids)) {

                $customtab = $this->super_dbmodel->get_wherein_data("tabs", "id, sub_title as name", "id", array_unique($tabsids));

                foreach ($customtab as $key => $csm) {
                    $customtab[$key]['type'] = 'tabs';
                }
            }
            $usr_arr = (!empty($selected_user_data)) ? $selected_user_data : $user_data;
            $combined_arr = array_merge($companytab, $teamtab, $customtab, [0 => ['id' => $user_id, 'name' => @$usr_arr->user_fname . ' ' . @$usr_arr->user_lname, 'type' => 'user']]);

            for ($i = 0; $i < count($tabs_order); $i++) {
                $tabs = explode('&', $tabs_order[$i]);
                foreach ($combined_arr as $k => $d) {
                    if (strtolower($tabs[0]) === $d['type'] && $tabs[1] === $d['id']) {
                        $sorted_tabs_arr[$i] = $combined_arr[$k];
                    }
                    continue;
                }
            }

            // echo "<pre>";print_r($sorted_tabs_arr);die;

            $bookmark_first_tab = $tabs_order[0];
            $explode_bookmark = explode("&", $bookmark_first_tab);
            $type = $explode_bookmark[0];
            $type_id = $explode_bookmark[1];
            $meta_key = "bookmarks_" . $type . "_" . $type_id;

            $bookmark_user_data = $this->super_dbmodel->get_where_data_first("user_meta", "*", array('meta_key' => $meta_key));
        }



        if (isset($bookmark_user_data) && !empty($bookmark_user_data)) {
            $meta_value = unserialize($bookmark_user_data->meta_value);
            $bookmark_user_data = $this->super_dbmodel->get_user_meta("bookmarks", $meta_value);
        } else {
            if (isset($explode_bookmark) && !empty($explode_bookmark)) {
                $type = $explode_bookmark[0];
                if ($type == "company") {
                    $bookmark_user_data = array();
                    if (isset($cmp_data) && !empty($cmp_data)) {
                        $cmp_id = $cmp_data[0]['cmp_id'];
                        $bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("company_id" => $cmp_id));
                    }
                } else if ($type == "team") {
                    $bookmark_user_data = array();
                    if (isset($team_data) && !empty($team_data)) {
                        $team_id = $team_data[0]['id'];
                        $bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("team_id" => $team_id));
                    }
                } else if ($type == 'user') {
                    $bookmark_user_data = array();
                    $bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("user_id" => $user_id));
                }
            } else {
                $bookmark_user_data = array();
                // if (isset($cmp_data) && !empty($cmp_data)) {
                //     $cmp_id = $cmp_data[0]['cmp_id'];
                //     $bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("company_id" => $cmp_id));
                // }
                if ($_GET['tab'] == "company") {
                    $bookmark_user_data = array();
                    if (isset($cmp_data) && !empty($cmp_data)) {
                        $cmp_id = $cmp_data[0]['cmp_id'];
                        $bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("company_id" => $cmp_id));
                    }
                } else if ($_GET['tab'] == "team") {
                    $bookmark_user_data = array();
                    if (isset($team_data) && !empty($team_data)) {
                        $team_id = $team_data[0]['id'];
                        $bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("team_id" => $team_id));
                    }
                } else if ($_GET['tab'] == 'user') {
                    $bookmark_user_data = array();
                    $bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("user_id" => $user_id));
                }
            }
        }


        if($_GET['tab'] == 'user'){
            $usertabs = $this->super_dbmodel->get_sort_data_like_all("tabs", "*", array('user_id' => $user_id), array('sub_title' => 'asc'));
        }else{
            $usertabs = array();
        }
        $graphics = $this->super_dbmodel->get_datawith_limit('graphics', '*', 25, 0);

        $session_data = $this->session->userdata('login_auth');

        $result_bookmark = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'bookmark_background'));



        $this->load->view('user/header', array('page_title' => 'Home', 'page_activate' => 'home', 'result_header' => $this->result_header, 'cmp_data' => $cmp_data, 'team_data' => $team_data, 'menus' => $this->page_menus, 'sorted_tabs_arr' => $sorted_tabs_arr, 'usertabs' => $usertabs, 'selected_user_id' => $user_id));
        $this->load->view('user/index', array('bookmark_data' => $bookmark_user_data, 'graphics' => $graphics, 'total_graphics' => $this->super_dbmodel->count_data('graphics', 'id', "NULL")));

        $this->load->view('user/footer', array('result_header' => $this->result_header, 'result_footer' => $this->result_footer, 'result_fg' => $this->result_email, 'result_page' => $this->result_page, 'result_bookmark' => $result_bookmark));
    }

    public function data_table_bookmarks()
    {
        $table_name = "bookmarks";
        $column_order = array(null, 'name', 'logo', 'created_at');
        $column_search = array('name', 'url');
        $order = array('name ' => 'asc');
        $where_notin = array();

        $where = array();

        $data = $row = array();
        $i = $_POST['start'];
        $bookmarksData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        foreach ($bookmarksData as $bookmark) {
            $i++;
            $created = date('M d, Y', strtotime($bookmark->created_at));

            $bookmark_img = "";
            if ($bookmark->graphic_id == "") {
                $no_img = base_url('assets/images/slider_blank.png');
                $bookmark_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
            } else {
                $graphicsDetail = $this->super_dbmodel->get_where_single_data('graphics', "*", array('id' => $bookmark->graphic_id));

                $user_uploaded_img = (isset($graphicsDetail->thumb))?$graphicsDetail->thumb : base_url('assets/images/slider_blank.png');
                $bookmark_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
            }


            $bookmark_status = "";
            $edit_data = "editdata_" . $bookmark->id;

            //team
            $bookmark_nm = $bookmark->id;
            $explode_ids = explode(',', $bookmark_nm);

            $desc = $bookmark->comment;
            $comment = ($desc != "") ? $desc : '-N/A-';


            //Users
            $user_ids = $bookmark->user_id;
            if ($user_ids != "") {
                $explode_ids = explode(',', $user_ids);
                $catalogue_data = $this->super_dbmodel->get_wherein_data("register", "user_fname,user_lname", "user_id", $explode_ids);
                $users_array = array();
                foreach ($catalogue_data as $cats) {
                    $username = $cats['user_fname'] . " " . $cats['user_lname'];
                    $users_url = base_url("/admin/users");
                    $users_array[] = "<a href='$users_url' class='team_applied btn btn-sm btn-info' title='$username'>$username</a>";
                }
                $usernm = implode(' ', $users_array);
            } else {
                $usernm = "-N/A-";
            }


            //Company
            $cmp_ids = $bookmark->company_id;
            if ($cmp_ids != "") {
                $explode_cids = explode(',', $cmp_ids);
                $catalogue_data = $this->super_dbmodel->get_wherein_data("company", "cmp_text, cmp_nick_title", "cmp_id", $explode_cids);
                $cmp_array = array();
                foreach ($catalogue_data as $cats) {
                    $cmp_nick = $cats['cmp_nick_title'];
                    $cmp_title = $cats['cmp_text'];
                    $team_url = base_url("/admin/companies");
                    $cmp_array[] = "<a href='$team_url' class='team_applied btn btn-sm btn-info' title='$cmp_title'>$cmp_nick</a>";
                }
                $cmp_nm = implode(' ', $cmp_array);
            } else {
                $cmp_nm = "-N/A-";
            }


            //Team
            $team_ids = $bookmark->team_id;
            if ($team_ids != "") {
                $explode_cids = explode(',', $team_ids);

                $catalogue_data = $this->super_dbmodel->get_wherein_data("teams", "name, nick_title", "id", $explode_cids);
                $team_array = array();
                foreach ($catalogue_data as $cats) {
                    $team_nick = $cats['nick_title'];
                    $team_title = $cats['name'];
                    $team_url = base_url("/admin/teams");
                    $team_array[] = "<a href='$team_url' class='team_applied btn btn-sm btn-info' title='$team_title'>$team_nick</a>";
                }
                $team_nm = implode(' ', $team_array);
            } else {
                $team_nm = "-N/A-";
            }




            $delete_data = "deletedata_" . $bookmark->id;
            $bookmark_url=$bookmark->url;
            
            $bookmark_btn="<a target='_blank' class='btn btn-warning' href='$bookmark_url' title='$bookmark_url'>View</a>"; //$bookmark->url
            
            $bookmark_status .= "<a class='tack_data_$bookmark->id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='bookmarks' data-key='id' data-value='$bookmark->id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";

            $bookmark_status .= "<a href='javascript:void(0)' class='tack_data_$bookmark->id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='bookmarks' data-key='id' data-value='$bookmark->id'><i class='fa fa-times'></i></a>";

            $data[] = array($i, $bookmark_img, $bookmark->name, $bookmark_btn, $comment, $bookmark_status);
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->data_table->countAll($table_name, $column_order, $column_search, $order, $where_notin, $where),
            "recordsFiltered" => $this->data_table->countFiltered($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where),
            "data" => $data,
        );

        // Output to JSON format
        echo json_encode($output);
    }


    public function data_table()
    {
        $table_name = "bookmarks";
        $column_order = array(null, 'name', 'logo', 'created_at');
        $column_search = array('name', 'url');
        $order = array('id ' => 'desc');
        $where_notin = array();

        $where = array();

        $data = $row = array();
        $i = 0; //$_POST['start'];
        $bookmarksData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        foreach ($bookmarksData as $bookmark) {
            $i++;
            $created = date('M d, Y', strtotime($bookmark->created_at));

            $bookmark_img = "";
            if ($bookmark->image == "") {
                $no_img = base_url('assets/images/slider_blank.png');
                $bookmark_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
            } else {
                $user_uploaded_img = $bookmark->thumb;
                $bookmark_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
            }


            $bookmark_status = "";
            $edit_data = "editdata_" . $bookmark->id;

            //team
            $bookmark_nm = $bookmark->id;
            $explode_ids = explode(',', $bookmark_nm);


            //Users
            $user_ids = $bookmark->user_id;
            if ($user_ids != "") {
                $explode_ids = explode(', ', $user_ids);
                $catalogue_data = $this->super_dbmodel->get_wherein_data("register", "user_fname,user_lname", "user_id", $explode_ids);
                $users_array = array();
                foreach ($catalogue_data as $cats) {
                    $username = $cats['user_fname'] . " " . $cats['user_lname'];
                    $users_url = base_url("/admin/users");
                    $users_array[] = "<a href='$users_url' class='team_applied btn btn-sm btn-info' title='$username'>$username</a>";
                }
                $usernm = implode(' ', $users_array);
            } else {
                $usernm = "-N/A-";
            }


            $delete_data = "deletedata_" . $bookmark->id;

            $post_id = base64_decode(($_REQUEST['post_id']));

            $assigned_bookmarks = $this->super_dbmodel->get_where_single_data($_REQUEST['table'] . '_meta', "*", array('post_id' => $post_id));

            $existing_assigned_bookmarks = $this->super_dbmodel->get_where_data($_REQUEST['table'] . '_meta', '*', array('post_id' => $post_id));

            if (!empty($existing_assigned_bookmarks)) {
                $existingBookmark_id = unserialize($existing_assigned_bookmarks[0]['meta_value']);
            }
            $meta_key =   $table_name . "_" . $_REQUEST['table'] . "_" . $post_id;

            $existingMeta = getset_meta($_REQUEST['table'] . '_meta', 'get', $post_id, $meta_key);
            //echo"-------------".$bookmark->id;
            //print_r($assigned_bookmarks);
            //print_r($existingMeta);die;

            if (!empty($existingMeta) && (array_search($bookmark->id, $existingMeta)) !== false) {
                $bookmark_status = "<a class='admin_action tack_data_$assigned_bookmarks->id btn1 btn-danger btn-sm' href='javascript:void(0)' id='$edit_data' data-title='remove' data-id='$post_id' title='Click to un-assigned for company' data-value='$bookmark_nm'>Unassigned</a>";
            } else {
                $bookmark_status = "<a class='admin_action tack_data_ btn1 btn-success btn-sm' href='javascript:void(0)' id='$edit_data' data-id='$post_id' data-title='add' title='Click to assigned for company' data-value='$bookmark_nm'>Assign</a>";
            }





            $data[] = array($i, $bookmark_img, $bookmark->name, $bookmark->url,  $created, $bookmark_status);
        }

        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : '',
            "recordsTotal" => $this->data_table->countAll($table_name, $column_order, $column_search, $order, $where_notin, $where),
            "recordsFiltered" => $this->data_table->countFiltered($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where),
            "data" => $data,
        );

        // Output to JSON format
        echo json_encode($output);
    }



    public function save_bookmark()
    {
        extract($_REQUEST);
        $user_data = $this->session->userdata('login_auth');

        $company_ids = (isset($companies)) ? implode(", ", $companies) : '';
        $team_ids = (isset($teams)) ? implode(", ", $teams) : '';
        //$user_ids = (isset($users)) ? implode(", ", $users) : $user_data->user_id;        

        // $icon_path = $this->input->post('icon_path');
        // $icon_thumb = $this->input->post('icon_thumb');
        $graphic_id = $this->input->post('graphic_id');

        $icon_array = array();
        $insert_array = array(
            'name' => $bookmark_text,
            // 'company_id' => $company_ids,
            // 'team_id' => $team_ids,
            'comment' => $bookmark_comment,
            'url' => $bookmark_url
            /*'user_id' => $user_ids*/
        );

        if ($graphic_id != "") {
            $icon_array = array(
                // 'cmp_image' => !empty($icon_path)?$icon_path:'',
                //'cmp_thumb' => !empty($icon_thumb)?$icon_thumb:'',
                'graphic_id' => $graphic_id
            );
        }

     

        $final_array = array_merge($insert_array, $icon_array);

        if ($this->super_insertmodel->insert_data("bookmarks", $final_array)) {
            $result = array('success_status' => 'Bookmark added successfully');
        } else {
            $result = array('error_status' => 'Failed to insert Bookmark data');
        }

        echo json_encode($result);
        die();
    }
    public function save_meta_data()
    {
        extract($_REQUEST);
        //print_r($_REQUEST);
        $user_data = $this->session->userdata('login_auth');
        if ($datatype == 'company') {
            $meta_key = 'bookmarks_company_' . $parent_id;
            $table = 'company_meta';
            $updateColumn = 'company_id';
        } elseif ($datatype == 'team') {
            $meta_key = 'bookmarks_team_' . $parent_id;
            $table = 'team_meta';
            $updateColumn = 'team_id';
        } else {
            $meta_key = 'bookmarks_' . $datatype . '_' . $parent_id;
            $table = 'user_meta';
            $updateColumn = 'user_id';
        }

        //check if exist 
        $bookmark_array = array();
        $existingMeta = getset_meta($table, 'get', $parent_id, $meta_key);

        $bookmarkData  = $this->super_dbmodel->get_where_data('bookmarks', '*', array('id' => $bookmark_id));

        $where = array(
            'id' => $bookmark_id
        );

        if (isset($existingMeta) && !empty($existingMeta) && $type == "add") {

            $bookmark_array[] = $bookmark_id;

            //update bookmark table data    

            $update_array = array(
                $updateColumn => !empty($bookmarkData[0][$updateColumn]) ? $bookmarkData[0][$updateColumn] . "," . $parent_id : $parent_id
            );
            $this->super_insertmodel->update_table_data($where, $update_array, 'bookmarks');
            $bookmark_array = array_merge($existingMeta, $bookmark_array);
            $bookmark_array = array_unique($bookmark_array);
        }
        //action at remove bookmark
        else if ($type == "remove") {
            $key = array_search($bookmark_id, $existingMeta);
            if (($key = array_search($bookmark_id, $existingMeta)) !== false) {
                unset($existingMeta[$key]);
            }
            $bookmark_array = array_merge($existingMeta, $bookmark_array);

            //update Bookmark data 
            $update_array = array(
                $updateColumn => str_replace(", " . $parent_id, " ", $bookmarkData[0][$updateColumn])
            );
            $this->super_insertmodel->update_table_data($where, $update_array, 'bookmarks');
        } else {
            $bookmark_array[] = $bookmark_id;
        }

        if (getset_meta($table, 'update', $parent_id, $meta_key, $bookmark_array)) {
            $result = array('success_status' => 'Bookmark order updated successfully');
        } else {
            $result = array('error_status' => 'Failed to save Bookmark order');
        }

        echo json_encode($result);
        die();
    }

    public function resizeImage($filename, $origianl_path, $thumb_path)
    {
        $source_path = $origianl_path;
        $target_path = $thumb_path;
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => TRUE,
            'create_thumb' => TRUE,
            'thumb_marker' => '_thumb',
            'width' => 150,
            'height' => 150
        );

        $this->load->library('image_lib', $config_manip);
        if (!$this->image_lib->resize()) {
            echo $this->image_lib->display_errors();
        }
        $this->image_lib->clear();
    }


    public function edit_bookmark()
    {
        extract($_REQUEST);
        $edit_result = $this->super_dbmodel->get_where_single_data($tablenm, "*", array($table_key => $team_id));
        $edit_result->thumbnail = getGraphicsThumb($edit_result->graphic_id);        
        echo json_encode($edit_result);
    }

    public function update_bookmark()
    {
        extract($_REQUEST);
        $user_data = $this->session->userdata('login_auth');
        $bookmark_text = $this->input->post('bookmark_text');
        $bookmark_url = $this->input->post('bookmark_url');
        $company_ids = (isset($companies)) ? implode(", ", $companies) : '';
        $team_ids = (isset($teams)) ? implode(", ", $teams) : '';
        $user_ids = (isset($users)) ? implode(", ", $users) : $user_data->user_id;

        $edit_id = $this->input->post('edit_id');

        // $icon_path = $this->input->post('icon_path');
        // $icon_thumb = $this->input->post('icon_thumb');
        $graphic_id = $this->input->post('graphic_id');

        $icon_array = array();

        $update_array = array(
            'name' => $bookmark_text,
            'url'  => $bookmark_url,
            'comment' => $bookmark_comment
            //'company_id' => $company_ids,
            //'team_id' => $team_ids,
            //'user_id' => $user_ids
        );

        if ($graphic_id != "") {
            $icon_array = array(
                // 'cmp_image' => !empty($icon_path)?$icon_path:'',
                //'cmp_thumb' => !empty($icon_thumb)?$icon_thumb:'',
                'graphic_id' => $graphic_id
            );
        }


        $final_array = array_merge($update_array, $icon_array);

        $where = array(
            'id' => $edit_id
        );

        if ($this->super_insertmodel->update_table_data($where, $final_array, 'bookmarks')) {
            $result = array('success_status' => 'Bookmark updated successfully');
        } else {
            $result = array('error_status' => 'Failed to update Team data');
        }
        echo json_encode($result);
        die();
    }



    //Catagories
    public function catagory()
    {
        $order = array(
            'cat_id' => 'desc'
        );
        $catagories_data = $this->super_dbmodel->get_sort_data("catagories", "*", $order);
        $this->load->view('admin/header', array('site_header' => 'Catalogue'));
        $this->load->view('admin/sidebar', array('site_menu' => 'catalogue', 'inner_active_menu' => 'inner_active_cat'));
        $this->load->view('admin/catagories/catagory.php', array('catagories_data' => $catagories_data));
        $this->load->view('admin/footer');
    }

    public function data_table_catagory()
    {
        $table_name = "catagories";
        $column_order = array(null, 'cat_name', 'created_date');
        $column_search = array('cat_name');
        $order = array('cat_id' => 'desc');
        $where_notin = array();

        $where = array();

        $data = $row = array();
        $i = $_POST['start'];
        $memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        foreach ($memData as $member) {
            $i++;
            $created = date('M d, Y H:i:s', strtotime($member->created_at));

            $member_status = "";
            $edit_data = "editdata_" . $member->cat_id;
            $delete_data = "deletedata_" . $member->cat_id;
            $member_status .= "<a class='tack_data_$member->cat_id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='catagories' data-key='cat_id' data-value='$member->cat_id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";
            //$member_status .="<a href='javascript:void(0)' class='tack_data btn btn-warning btn-sm user_action' title='Unfollow' data-trigger='hover' id='$member->tl_screen_name' data-id='$member->tl_twitter_id'><i class='fas fa-thumbs-down'></i></a>&nbsp;";

            $member_status .= "<a href='javascript:void(0)' class='tack_data_$member->cat_id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='catagories' data-key='cat_id' data-value='$member->cat_id'><i class='fa fa-times'></i></a>";

            $data[] = array($i, $member->cat_name, $created, $member_status);
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->data_table->countAll($table_name, $column_order, $column_search, $order, $where_notin, $where),
            "recordsFiltered" => $this->data_table->countFiltered($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where),
            "data" => $data,
        );

        // Output to JSON format
        echo json_encode($output);
    }



    public function save_catagory()
    {
        $cat_text = $this->input->post('cat_text');
        $insert_array = array(
            'cat_name' => $cat_text
        );
        if ($this->super_insertmodel->insert_data("catagories", $insert_array)) {
            $result = array('success_status' => 'Catalogue added successfully');
        } else {
            $result = array('error_status' => 'Failed to insert Catalogue data');
        }
        echo json_encode($result);
        die();
    }



    public function update_catagory()
    {
        $cat_text = $this->input->post('cat_text');
        $edit_id = $this->input->post('edit_id');
        $update_array = array(
            'cat_name' => $cat_text
        );

        $where = array(
            'cat_id' => $edit_id
        );

        if ($this->super_insertmodel->update_table_data($where, $update_array, 'catagories')) {
            $result = array('success_status' => 'Catalogue updated successfully');
        } else {
            $result = array('error_status' => 'Failed to update Catalogue data');
        }
        echo json_encode($result);
        die();
    }





    //Brands
    public function brands()
    {
        $order = array(
            'brand_id' => 'desc'
        );
        $brands_data = $this->super_dbmodel->get_sort_data("brands", "*", $order);
        $this->load->view('admin/header', array('site_header' => 'Catalogue'));
        $this->load->view('admin/sidebar', array('site_menu' => 'catalogue', 'inner_active_menu' => 'inner_active_brand'));
        $this->load->view('admin/brands/brands.php', array('brands_data' => $brands_data));
        $this->load->view('admin/footer');
    }

    public function data_table_brands()
    {
        $table_name = "brands";
        $column_order = array(null, 'brand_name', 'created_date');
        $column_search = array('brand_name');
        $order = array('brand_id' => 'desc');
        $where_notin = array();

        $where = array();

        $data = $row = array();
        $i = $_POST['start'];
        $memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        foreach ($memData as $member) {
            $i++;
            $created = date('M d, Y H:i:s', strtotime($member->created_at));

            $member_status = "";
            $edit_data = "editdata_" . $member->brand_id;
            $delete_data = "deletedata_" . $member->brand_id;
            $member_status .= "<a class='tack_data_$member->brand_id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='brands' data-key='brand_id' data-value='$member->brand_id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";
            //$member_status .="<a href='javascript:void(0)' class='tack_data btn btn-warning btn-sm user_action' title='Unfollow' data-trigger='hover' id='$member->tl_screen_name' data-id='$member->tl_twitter_id'><i class='fas fa-thumbs-down'></i></a>&nbsp;";

            $member_status .= "<a href='javascript:void(0)' class='tack_data_$member->brand_id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='brands' data-key='brand_id' data-value='$member->brand_id'><i class='fa fa-times'></i></a>";

            $data[] = array($i, $member->brand_name, $created, $member_status);
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->data_table->countAll($table_name, $column_order, $column_search, $order, $where_notin, $where),
            "recordsFiltered" => $this->data_table->countFiltered($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where),
            "data" => $data,
        );

        // Output to JSON format
        echo json_encode($output);
    }



    public function save_brand()
    {
        $brand_name = $this->input->post('brand_name');
        $insert_array = array(
            'brand_name' => $brand_name
        );
        if ($this->super_insertmodel->insert_data("brands", $insert_array)) {
            $result = array('success_status' => 'Catalogue added successfully');
        } else {
            $result = array('error_status' => 'Failed to insert Catalogue data');
        }
        echo json_encode($result);
        die();
    }



    public function update_brand()
    {
        $brand_name = $this->input->post('brand_name');
        $edit_id = $this->input->post('edit_id');
        $update_array = array(
            'brand_name' => $brand_name
        );

        $where = array(
            'brand_id' => $edit_id
        );

        if ($this->super_insertmodel->update_table_data($where, $update_array, 'brands')) {
            $result = array('success_status' => 'Catalogue updated successfully');
        } else {
            $result = array('error_status' => 'Failed to update Catalogue data');
        }
        echo json_encode($result);
        die();
    }

    public function Bookmarksbytab()
    {
        $tab_id = base64_decode($_GET['p_id']);
        $type = $_GET['tab'];
        switch ($type) {
            case 'company':
                $tablename = 'company';
                $wherecol = 'cmp_id';
                $cols = 'cmp_text as name';
                break;
            case 'team':
                $tablename = 'teams';
                $wherecol = 'id';
                $cols = 'name';
                break;

            default:
                $tablename = 'company';
                $wherecol = 'cmp_id';
                $cols = 'cmp_text as name';
                break;
        }

        $selected_tab = $this->super_dbmodel->get_where_data_first($tablename, $cols, [$wherecol => base64_decode($_REQUEST['p_id'])]);
        $bookmarks = getsortedbookmarksbypost_id($type, $tab_id, 'bookmarks_' . $type . '_' . $tab_id);
        $this->load->view('admin/header', array('site_header' =>  $type . ' Bookmarks'));
        $this->load->view('admin/sidebar', array('site_menu' => $type));
        $this->load->view('admin/bookmarks/tab_bookmarks', array('bookmarks' => $bookmarks, 'parent_id' => $tab_id, 'type' => $type, 'selected_tab' => $selected_tab));
        $this->load->view('admin/footer');
    }

    public static function get_Title($table, $id)
    {
        $where = array("id" => $type_id);
        $table_result = $this->super_dbmodel->get_where_data_first($table, "*", $where);
        print_r($table_result);
        die;
    }
}
