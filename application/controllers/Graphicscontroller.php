<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Graphicscontroller extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!is_logged_in()) {
            redirect('/login');
        }
        $this->load->library("session");
        $this->load->library('pagination');
        $this->load->model('data_table');
        $this->load->model('super_dbmodel');
        $this->load->model('super_insertmodel');
    }

    public function index($page = 0) {
        extract($_REQUEST);
        if (!is_logged_in() || !check_admin()) {
            redirect('/login');
        }
        $this->load->view('admin/header');
        $this->load->view('admin/sidebar', array('site_menu' => 'graphics'));
        $config = array();
        $config["base_url"] = base_url() . "/admin/graphics";
        $config["total_rows"] = $this->super_dbmodel->count_data('graphics', 'id', "NULL");
        $config["per_page"] = 25;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $paginate["links"] = $this->pagination->create_links();

        if (isset($_REQUEST) && !empty($name)) {
            $where = array(
                'name' => '%' . $name . '%'
            );
            $graphics = $this->super_dbmodel->get_like_data_order_by('graphics', 'name', 25, 0, $name);
        } else {
            $graphics = $this->super_dbmodel->get_data_order_by('graphics', '*', 25, 0);
        }

        $this->load->view('admin/graphics/graphics', ['graphics' => $graphics, 'paginate' => $paginate]);
        $this->load->view('admin/footer');
    }

    public function save() {
        extract($_REQUEST);
        $user_data = $this->session->userdata('login_auth');
        $thumb_image_path = FILE_UPLOAD_PATH . '/graphics/thumbnail/';
        $image_path = FILE_UPLOAD_PATH . '/graphics/';
        $image_nm = 'graphic';

        //Upload Helper
        $upload_image = Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path);

        if (isset($upload_image['error'])) {
            $result = array('error_status' => $upload_image['error']);
        } else {
            $original_nm = $upload_image['original_nm'];
            $thumb_nm = $upload_image['thumb_nm'];
            $insert_array = array(
                'name' => $icon_name,
                'user_id' => $user_data->user_id,
                'path' => base_url('assets/admin/upload/graphics/' . $original_nm),
                'thumb' => base_url('assets/admin/upload/graphics/thumbnail/' . $thumb_nm),
            );
            if ($id = $this->super_insertmodel->insert_data_with_id("graphics", $insert_array)) {
                $result = array('success_status' => 'Graphics added successfully', 'id' => $id, 'path' => base_url('assets/admin/upload/graphics/' . $original_nm), 'thumb' => base_url('assets/admin/upload/graphics/thumbnail/' . $thumb_nm), 'icon_name' => $icon_name);
            } else {
                $result = array('error_status' => 'Failed to insert Graphics data');
            }
        }
        echo json_encode($result);
        die();
    }

    public function search() {
        extract($_REQUEST);

        $where = array(
            'name' => '%' . $text . '%'
        );
        $result = $this->super_dbmodel->search_likes("graphics", 'name', $text);
    }

    public function loadMoreGraphics() {
        $config = array();
        $config["base_url"] = base_url() . "/Graphicscontroller/loadMoreGraphics";
        $config["total_rows"] = $this->super_dbmodel->count_data('graphics', 'id', "NULL");
        $config["per_page"] = 1;

        $this->pagination->initialize($config);
        $page = ($_GET['page']) ? $_GET['page'] : 0;
        $graphics = $this->super_dbmodel->get_datawith_limit('graphics', '*', $config["per_page"], $page);
        echo json_encode(['result' => $graphics]);
        die;
    }

    public function update() {
        extract($_REQUEST);
        $edit_id = $this->input->post('edit_id');
        $where = array(
            'id' => $_REQUEST['id']
        );

        //find user
        $graphics = $this->super_dbmodel->get_where_data('graphics', '*', $where);


        $thumb_image_path = FILE_UPLOAD_PATH . '/graphics/thumbnail/';
        $image_path = FILE_UPLOAD_PATH . '/graphics/';
        $image_nm = "graphic_file";

        if ($_FILES['graphic_file']['name'] != "") {
            // print_r($_FILES);die;
            //Upload Helper        
            $upload_image = Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path);



            if (isset($upload_image['error'])) {
                $result = array('error_status' => $upload_image['error']);
                die();
            } else {
                $original_nm = $upload_image['original_nm'];
                $thumb_nm = $upload_image['thumb_nm'];
                $update_array = array(
                    'name' => $name,
                    'path' => base_url('assets/admin/upload/graphics/' . $original_nm),
                    'thumb' => base_url('assets/admin/upload/graphics/thumbnail/' . $thumb_nm),
                );
            }
        } else {
            $update_array = array(
                'name' => $name
            );
        }



        if ($this->super_insertmodel->update_table_data($where, $update_array, 'graphics')) {
            $result = array('success_status' => 'User updated successfully');
        } else {
            $result = array('error_status' => 'Failed to update User data');
        }
        echo json_encode($result);
        die();
    }

    public function media_search_field() {
        extract($_REQUEST);
        if ($search_type == "bookmark") {
            $user_media = media_bookmarks($search);

            $count_media = count($user_media['bookmarkData']);
            if ($count_media == 0) {
                $result = array(
                    'code' => '202',
                    'message' => "No Bookmark found %$search%"
                );
            } else {
                $result = array(
                    'code' => '200',
                    'count'=> $user_media['total_data'],
                    'search_type' => $search_type,
                    'bookmark' => $user_media['bookmarkData'],
                    'message' => "Data retrieve successfully related search $search"
                );
            }
        } else {
            $user_media = media_library($search);
            $count_media = $user_media['total_data'];
            if ($count_media == 0) {
                $result = array(
                    'code' => '202',
                    'message' => "No Media found %$search%"
                );
            } else {
                $result = array(
                    'code' => '200',
                    'count'=> $count_media,
                    'search_type' => $search_type,
                    'media' => $user_media['graphic'],
                    'message' => "Data retrieve successfully related search $search"
                );
            }
        }
        echo json_encode($result);
        die();
    }


    function media_search_field_load_more(){
        extract($_REQUEST);
        if($type=="media"){
            $nextOffset=$offset+12;
            $user_data = $this->session->userdata('login_auth');
            if (@$user_data->user_id != "") {
                $user_id = $user_data->user_id;
                $wherein = "1," . $user_id;
            } else {
                $wherein = "no";
            }

            $user_media = $this->super_dbmodel->media_with_limit('graphics', '*', $wherein, $search_data, $limit, $offset);

            $count_media = $this->super_dbmodel->media_all_users('graphics', '*', $wherein, $search_data);

            if ($count_media == 0) {
                $result = array(
                    'code' => '202',
                    'message' => "No Media found %$search_data%"
                );
            } else {
                $result = array(
                    'code' => '200',
                    'count'=> $count_media,
                    'search_type' => $type,
                    'data' => $user_media,
                    'offset' => $nextOffset,
                    'type'=> $type,
                    'message' => "Data retrieve successfully related search $search_data"
                );
            }
        } else {
            $nextOffset=$offset+12;
            $user_data = $this->session->userdata('login_auth');
            /*if (@$user_data->user_id != "") {
                $user_id = $user_data->user_id;
                $where = array(
                    'user_id' => $user_id,
                    'user_id' => 1
                );
                $order = array('id' => 'asc');
            } else {
                $where = array(
                    'user_id' => 1
                );
            }*/
            $order = array('name' => 'asc');
            $where=array();

            $user_media = $this->super_dbmodel->ci_bookmark("bookmarks", "*", $order, $where, $search_data, $limit, $offset);

            $count_media = $this->super_dbmodel->ci_bookmark_users("bookmarks", "*", $order, $where, $search_data);



            if ($count_media == 0) {
                $result = array(
                    'code' => '202',
                    'message' => "No Bookmark found %$search_data%"
                );
            } else {
                if(isset($user_media) && !empty($user_media)){
                    foreach($user_media as $k => $bookmark){
                        $user_media[$k]->thumb = getGraphicsThumb($bookmark->graphic_id);
                        $user_media[$k]->image = getGraphicsThumb($bookmark->graphic_id);
                    }
                }
                $result = array(
                    'code' => '200',
                    'count'=> $count_media,
                    'search_type' => $type,
                    'data' => $user_media,
                    'offset' => $nextOffset,
                    'type'=> $type,
                    'message' => "Data retrieve successfully related search $search_data"
                );
            }
        }
        echo json_encode($result);
        die();
    }

    function requestFormSave() {
        extract($_REQUEST);
        $user_data = $this->session->userdata('login_auth');
        if (@$user_data->user_id != "") {
            $user_id = $user_data->user_id;
        } else {
            $user_id = 1;
        }

        $insert_array = array(
            'req_label' => $bookmark_label,
            'req_url' => $bookmark_url,
            'req_comment' => $bookmark_comments,            
            'user_id' => $user_id
        );

        if ($this->super_insertmodel->insert_data('request_bookmark', $insert_array)) {
            $result = array(
                'code' => 200,
                'message' => 'Your requested has been added successfully'
            );
        } else {
            $result = array(
                'code' => 202,
                'message' => 'Failed to add your request'
            );
        }
        echo json_encode($result);
        die();
    }

    public function create_bookmark() {
        extract($_REQUEST);
        $user_data = $this->session->userdata('login_auth');

        if (!isset($user_id) && @$user_data->user_id != "") {
            $user_id = $user_data->user_id;
        } 
        // if($data_type=="tabs"){
        //     $data_type="tab";
        // }
        $type_id = $data_type . "_id";
        $now = date('Y-m-d H:i:s');
        $insert_array = array(
            'name' => $label,
            'url' => $url,
            // 'image' => $media_image,
            // 'thumb' => $media_thumb,
            ($data_type=="tabs")?"tab_id":$type_id => $$type_id,
            'graphic_id' => $media_id,
            'created_at' => $now,
            'updated_at' => $now
        );
//echo $key = "bookmarks_".$data_type."_" . $$type_id;die;
        $last_inserted_id = $this->super_insertmodel->insert_data_with_id('bookmarks', $insert_array);

        if ($last_inserted_id) {
            $table_nm = ($data_type=="tabs")?"tab_meta":$data_type . "_meta";
            $key = "bookmarks_".$data_type."_" . $$type_id;

            $meta_result = getset_meta($table_nm, "get", $$type_id, $key, $value = null);
            $finale_meta = array();
            if (isset($meta_result) && !empty($meta_result)) {
                array_push($meta_result, $last_inserted_id);
                $finale_meta = $meta_result;
                getset_meta($table_nm, "update", $$type_id, $key, $finale_meta);
            } 
            // else {
            //     $finale_meta[] = $last_inserted_id;
            // }

            

            $result = array(
                'code' => 200,
                'message' => 'Your bookmark has been added successfully'
            );
        } else {
            $result = array(
                'code' => 202,
                'message' => 'Failed to add your bookmark, Please try again later'
            );
        }
        echo json_encode($result);
        die();
    }

    public function assign_bookmark() {
        extract($_REQUEST);
        $where = array('id' => $bookmark_id);
        // if($type=="tabs"){
        //     $type="tab";
        // }
        $search_key = ($type=="tabs")?"tab_id":$type . "_id";
        $search_value = $type_id;
        $assign_id = $this->super_dbmodel->ci_bookmark_users_like_before("bookmarks", "*", $where, $search_key, $search_value);
        
        // if ($assign_id['count_bookmark'] == 1) {
        //     $result = array(
        //         'code' => 202,
        //         'message' => 'Bookmark already assigned to selected tab'
        //     );
        // } else {
            $bookmark_data = $assign_id['data'];
            $search_data = $bookmark_data->$search_key;
            $companies = array();
            
            if($search_data!=""){                
                $explode_comapny = explode(", ", $search_data); 
                array_push($explode_comapny, $type_id);
                $companies = $explode_comapny;           
            } else {
                $companies[] = $type_id;
            }

            $implode_data = implode(', ', array_unique($companies));


            $update_array = array(
                $search_key => $implode_data
            );
            $where = array(
                "id" => $bookmark_id
            );
            
            if($this->super_insertmodel->update_table_data_bookmark($where, $update_array, "bookmarks")){
                
                $table_nm = ($type=="tabs")?"tab_meta":$type . "_meta";
                $key = "bookmarks_".$type."_". $type_id;

                
                $meta_result = getset_meta($table_nm, "get", $type_id, $key, $value = null);

                $finale_meta = array();
                if (isset($meta_result) && !empty($meta_result)) {
                    array_push($meta_result, $bookmark_id);
                    $finale_meta = $meta_result;
                } else {
                    $finale_meta[] = $bookmark_id;
                }

                getset_meta($table_nm, "update", $type_id, $key, $finale_meta);
                
                $result = array(
                    'code' => 200,
                    'message' => 'Bookmark has been assigned successfully'
                );
            } else {
                $result = array(
                    'code' => 202,
                    'message' => 'There is something technical problem, Please try again later'
                );
            }
            
        // }
        echo json_encode($result);
        die();
    }



    public function unassign_bookmark(){
        extract($_REQUEST);

        // if($type=="tabs"){
        //     $type="tab";
        // }
        $table_nm=($type=="tabs")?"tab_meta":$type."_meta";
        $meta_key="bookmarks_".$type."_".$type_id;


        $where = array('id' => $bookmark_id);
        $search_key = ($type=="tabs")?"tab_id":$type . "_id";
        $search_value = $type_id;
        $assign_id = $this->super_dbmodel->ci_bookmark_users_like("bookmarks", "*", $where, $search_key, $search_value);

        



        if ($assign_id['count_bookmark'] == 1) {            
            $assign_data= $assign_id['data']->$search_key;
            $explode_assign=explode(", ", $assign_data);
            $final_array=array();
            foreach($explode_assign as $assigned):
                if($type_id!=$assigned){
                    $final_array[]=$assigned;
                }
            endforeach;
            $count=count($final_array);

            if($count==0){
                $update_array=array(
                    $search_key=> NULL
                );
            } else {
                $update_array=array(
                    $search_key=> implode(", ", $final_array)
                );
            } 

            $where = array("id" => $bookmark_id);
            if($this->super_insertmodel->update_table_data_bookmark($where, $update_array, "bookmarks")){

                $meta_result = getset_meta($table_nm, "get", $type_id, $meta_key, $value = null);
                $finale_meta = array();
                if (isset($meta_result) && !empty($meta_result)) {
                    foreach($meta_result as $results):
                        if($results!=$bookmark_id){
                            $finale_meta[]=$results;
                        }
                    endforeach;
                }

                $count=count($finale_meta);
                if($count==0){
                    $finale_meta=NULL;
                }
                getset_meta($table_nm, "update", $type_id, $meta_key, $finale_meta);
                $result = array(
                        'code' => 200,
                        'message' => 'Bookmark has been unassigned successfully'
                    );
            } else {
                $result = array(
                    'code' => 202,
                    'message' => 'There is something technical problem, Please try again later'
                );
            }
        }

        echo json_encode($result);
        die();
    }

}