<?php
//Company Controller

defined('BASEPATH') or exit('No direct script access allowed');

class Bannercontroller extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!is_logged_in() || !check_admin()) {
            redirect('/login');
        }

        $this->load->library("session");
        $this->load->model('data_table');
        $this->load->model('super_insertmodel');
        $this->load->model('super_dbmodel');
    }


    //User Section 
    public function company() {
        $session_lang = $this->session->userdata('site_lang');

        $lang = "english";
        /*if($lang != ""){
            $lang = $session_lang;
        }*/
        $order = array(
            'cmp_id' => 'desc'
        );

        $company_data = $this->super_dbmodel->get_sort_where_data("company", "*", $order, array('lang' => $lang));

        $subtabs = $this->super_dbmodel->get_where_datatable_order("subtabs", "*", array("assign_id"=> NULL), array("subtab_text"=> 'asc'));



        $graphics = $this->super_dbmodel->get_data_order_by('graphics', '*', 25, 0);

        $users = $this->super_dbmodel->get_sort_data("register", "user_id,user_fname,user_lname", array('user_id' => 'desc'));
        $this->load->view('admin/header', array('site_header' => 'Companies'));
        $this->load->view('admin/sidebar', array('site_menu' => 'companies'));
        $this->load->view('admin/company/all_company.php', array('users_data' => $company_data, 'users' => $users, 'graphics' => $graphics, "subtabs"=> $subtabs,'total_graphics' => $this->super_dbmodel->count_data('graphics', 'id', "NULL")));
        $this->load->view('admin/footer');
    }


    public function data_cmptable()
    {
        $table_name = "company";
        $column_order = array(null, 'cmp_thumb', 'cmp_text', 'cmp_nick_title', 'cmp_desc', 'created_date');
        $column_search = array('cmp_text', 'cmp_nick_title', 'cmp_desc');
        $order = array('cmp_id ' => 'desc');
        $where_notin = array('type'=> "subtab");

        $session_lang = $this->session->userdata('site_lang');
        $lang = "english";
        /*if($lang != ""){
            $lang = $session_lang;
        }*/
        $where = array('lang' => $lang);

        $data = $row = array();
        $i = $_POST['start'];
        $memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        /*echo $this->db->last_query();
        die();
        */

        foreach ($memData as $member) {
            $i++;
            $created = date('M d, Y H:i:s', strtotime($member->created_date));

            $user_img = "";


            if (!empty($member->graphic_id)) {
                $graphicsDetail = $this->super_dbmodel->get_where_single_data('graphics', "*", array('id' => $member->graphic_id));

                if(isset($graphicsDetail->thumb)){
                    $user_uploaded_img = $graphicsDetail->thumb;
                    $user_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
                } else {
                    $no_img = base_url('assets/images/slider_blank.png');
                    $user_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
                }
                
            } else {
                $no_img = base_url('assets/images/slider_blank.png');
                $user_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
            }


            $member_status = "";
            $edit_data = "editdata_" . $member->cmp_id;
            $delete_data = "deletedata_" . $member->cmp_id;

            $member_status .= "<a class='tack_data_$member->cmp_id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='company' data-key='cmp_id' data-value='$member->cmp_id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";

            //Users
            /*$user_ids=$member->user_id;
            if($user_ids!=""){
                $explode_ids=explode(', ', $user_ids);                
                $catalogue_data = $this->super_dbmodel->get_wherein_data("register", "user_fname,user_lname", "user_id", $explode_ids);
                $users_array=array();
                foreach ($catalogue_data as $cats) {                    
                    $username=$cats['user_fname']." ". $cats['user_lname'];
                    $users_url=base_url("/admin/users");
                    $users_array[]="<a href='$users_url' class='team_applied btn btn-sm btn-info' title='$username'>View User</a>";
                }
                $usernm=implode(' ', $users_array);
            } else {
                $usernm="-N/A-";
            }*/

            $userassign_url = base_url('admin/assign-user/') . "?company=" . $member->cmp_id . "&tb=company";
            $assign_team_url = base_url('admin/team-assign/') . "?company=" . $member->cmp_id . "&tb=company";

            $usernm = "<a href='$userassign_url' class='team_applied btn btn-sm btn-info' title='Assign User'>View </a>";

            // $team_tabs ="<a href='$assign_team_url' class='team_applied btn btn-sm btn-info' title='Assign User'>View </a>";

            $member_status .= "<a href='javascript:void(0)' class='tack_data_$member->cmp_id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='company' data-key='cmp_id' data-value='$member->cmp_id'><i class='fa fa-times'></i></a>";

            $wherecmp=array(
                "assign_id" => $member->cmp_id,
                "assign_type" => "company"
            );

            $subtabDetails = $this->super_dbmodel->get_where_datatable_order("subtabs", "*", $wherecmp, array("subtab_text"=> "asc"));

            if(isset($subtabDetails) && !empty($subtabDetails)){
                $sb_ar=array();
                foreach($subtabDetails as $subtabs){
                    $sb_ar[]="<a href='".base_url('/admin/subtabs')."' class='text-info text-purple' title='".$subtabs['subtab_text']."' id='$member->cmp_id' data-id='$member->cmp_id'>".$subtabs['subtab_text']."</a>";
                }

                $member_tabs = "<div class='subtabs'><strong>Subtabs: </strong>".implode(", ",$sb_ar)."</div>";

            } else {

                $member_tabs = "<a href='" . base_url('/admin/tabs/bookmarks') . "?p_id=" . base64_encode($member->cmp_id) . "&tab=company' target='_blank' class='btn btn-warning btn-sm' title='tabs' data-trigger='hover' id='$member->cmp_id' data-id='$member->cmp_id'>Edit</a>&nbsp;";
            }


            $data[] = array($i, $user_img, $member->cmp_text, $member->cmp_nick_title, $member->cmp_desc, $usernm, $member_tabs, $member_status);
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




    public function save_slider() {
        $session_lang = $this->session->userdata('site_lang');
        $lang = "english";
        /*if($lang != ""){
            $lang = $session_lang;
        }*/

        $user_data = $this->session->userdata('login_auth');
        extract($_REQUEST);

        //$user_ids= (isset($users))? implode(", ", $users): '';
        $slider_text = $this->input->post('slider_text');
        $cmp_nick_title = $this->input->post('cmp_nick_title');
        $slider_desc = $this->input->post('slider_desc');

        // $icon_path = $this->input->post('icon_path');
        // $icon_thumb = $this->input->post('icon_thumb');
        $graphic_id = $this->input->post('graphic_id');

        $icon_array = array();
        $insert_array = array(
            'lang'        => $lang,
            'cmp_text' => $slider_text,
            'cmp_nick_title' => $cmp_nick_title,
            'cmp_desc'  => $slider_desc
            /*'user_id'=>$user_data->user_id*/
        );

        if ($graphic_id != "") {
            $icon_array = array(
                'graphic_id' => $graphic_id
            );
        }

        $final_array = array_merge($insert_array, $icon_array);

        $cmp_id= $this->super_insertmodel->insert_data_with_id("company", $final_array);

        if(isset($assign_subtabs) && !empty($assign_subtabs)){
            //Assign subtab to company
            $cmp_subtabs=array(
                "assign_type" => "company",
                "assign_id" => $cmp_id
            );
            $this->super_insertmodel->update_table_data_wherein("subtabs", $cmp_subtabs, "subtab_id", $assign_subtabs);

            $result=$this->super_dbmodel->get_wherein_data("subtabs", "*", "subtab_id", $assign_subtabs);
            $cmp_arr=array();
            foreach($result as $cmpids):
                $cmp_arr[]=$cmpids['cmp_id'];
            endforeach;

            $cmptb_arr=array(
                "subtabs" => 2,
                "subtabs_parent_ids"=> $cmp_id
            );
            $this->super_insertmodel->update_table_data_wherein("company", $cmptb_arr, "cmp_id", $cmp_arr);
            //End subtab to company
        }

        if ($cmp_id!="") {
            $result = array('success_status' => 'Company added successfully');
        } else {
            $result = array('error_status' => 'Failed to insert comapny data');
        }
        echo json_encode($result);
        die();
    }



    public function resizeImage($filename, $origianl_path, $thumb_path) {
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



    public function edit_banner() {
        extract($_REQUEST);
        $where = array(
            $table_key => $catalogue_id
        );

        $edit_result = $this->super_dbmodel->get_where_single_data($tablenm, "*", $where);
        $edit_result->thumbnail = getGraphicsThumb($edit_result->graphic_id);  

        $cmp_id=$edit_result->cmp_id;

        
        $subtabs = $this->super_dbmodel->get_where_datatable_order("subtabs", "*", array("assign_id"=> NULL), array("subtab_text"=> 'asc'));

        if(isset($subtabs) && !empty($subtabs)){
            $allsub_ar=array();

            foreach($subtabs as $sbtabs){
                $allsub_ar[]=array(
                    "subtab_id" => $sbtabs['subtab_id'],
                    "subtab_text" => $sbtabs['subtab_text'],
                );
            }
            $edit_result->subtabs = "yes";
            $edit_result->subtabs_data = $allsub_ar;
        } else {
            $edit_result->subtabs = "no";
        }

        $wherecmp=array(
            "assign_id" => $cmp_id,
            "assign_type" => "company"
        );

        $subtabDetails = $this->super_dbmodel->get_where_datatable_order("subtabs", "*", $wherecmp, array("subtab_text"=> "asc"));

        if(isset($subtabDetails) && !empty($subtabDetails)){
            $sb_ar=array();
            foreach($subtabDetails as $subtabs){
                $sb_ar[]=array(
                    "subtab_id" => $subtabs['subtab_id'],
                    "subtab_text" => $subtabs['subtab_text'],
                );
            }
            $edit_result->sbtabs ="yes";
            $edit_result->sbtabs_data = $sb_ar;
        } else {
            $edit_result->sbtabs ="no";
        }
        
        echo json_encode($edit_result);
        die();
    }



    public function update_banner() {
        $session_lang = $this->session->userdata('site_lang');
        $lang = "english";
        
        extract($_REQUEST);


        $user_ids = (isset($users)) ? implode(", ", $users) : '';
        $slider_text = $this->input->post('banner_text');
        $cmp_nick_title = $this->input->post('cmp_nick_title');
        $slider_desc = $this->input->post('slider_desc');
        $edit_id = $this->input->post('edit_id');

        // $icon_path = $this->input->post('icon_path');
        // $icon_thumb = $this->input->post('icon_thumb');
        $graphic_id = $this->input->post('graphic_id');

        $icon_array = array();
        $update_array = array(
            'lang'        => $lang,
            'cmp_text' => $slider_text,
            'cmp_nick_title' => $cmp_nick_title,
            'cmp_desc'  => $slider_desc,
            //'user_id'=> $user_ids,
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
            'cmp_id' => $edit_id
        );


        //Delete subtab into subtable table
        $delcmp_subtabs=array(
            "assign_type" => NULL,
            "assign_id" => NULL
        );

        $this->super_insertmodel->update_table_data(array("assign_id"=> $edit_id), $delcmp_subtabs, "subtabs");
        
        $delcmp=array(
            "subtabs" => 1,
            "subtabs_parent_ids" => NULL
        );
        $this->super_insertmodel->update_table_data(array("subtabs_parent_ids"=> $edit_id), $delcmp,"company");
        //End Delete subtab into subtable table

        
        if(isset($assign_subtabs) && !empty($assign_subtabs)){
            //Assign Subtab into subtable

            $result=$this->super_dbmodel->get_wherein_data("subtabs", "*", "subtab_id", @$assign_subtabs);
            $cmp_arr=array();
            foreach($result as $cmpids):
                $cmp_arr[]=$cmpids['cmp_id'];
            endforeach;


            $cmp_subtabs=array(
                "assign_type" => "company",
                "assign_id" => $edit_id
            );

            $this->super_insertmodel->update_table_data_wherein("subtabs", $cmp_subtabs, "subtab_id", @$assign_subtabs);
            $cmptb_arr=array(
                "subtabs" => 2,
                "subtabs_parent_ids"=> $edit_id
            );
            $this->super_insertmodel->update_table_data_wherein("company", $cmptb_arr, "cmp_id", $cmp_arr);
            //End Assign Subtab into subtable
        }
        

        $this->super_insertmodel->update_table_data($where, $final_array, 'company');

        if ($this->super_insertmodel->update_table_data($where, $final_array, 'company')) {
            $result = array('success_status' => 'Company updated successfully');
        } else {
            $result = array('error_status' => 'Failed to update Catalogue data');
        }
        echo json_encode($result);
        die();
    }
}
