<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admincontroller extends CI_Controller {

	function __construct() {
    	parent::__construct();
        if (!is_logged_in() || !check_admin()) {
            redirect('/login');
        }
        $this->load->library("session");
        $this->load->model('data_table');
    	$this->load->model('super_dbmodel');
        $this->load->model('super_insertmodel');
  	}

	public function index()	{		
		$this->load->view('admin/header');
		$this->load->view('admin/sidebar', array('site_menu'=>'dashboard'));
		$this->load->view('admin/dashboard');
		$this->load->view('admin/footer');
	}

	public function prf_set() {
        $session_data = $this->session->userdata('login_auth');        
        $admin_id = $session_data->user_id;
        $prf_data = $this->super_dbmodel->get_where_data("register", "*", array('user_id' => $admin_id));

        $this->load->view('admin/header', array('site_header' => 'Profile Setting'));
		$this->load->view('admin/sidebar', array('site_menu'=>'prf_set'));
        $this->load->view('admin/prf_set', array('prf_data' => $prf_data));
        $this->load->view('admin/footer');
    }


    public function update_profile() {
        $session_data = $this->session->userdata('login_auth');
        $admin_id=$session_data->user_id;        
        $admin_email=$this->input->post('admin_email');
        $user_fname = $this->input->post('admin_fname');
        $user_lname = $this->input->post('admin_lname');
        $data_pwd = $this->input->post('admin_pwd');
        $new_pass = 0;

        $get_array= $image_array=array();

        if ($data_pwd != "") {
            $get_array = array(
                'user_fname' => $user_fname,
                'user_lname' => $user_lname,
                'user_pass' => md5($data_pwd)
            );
        } else {
            $get_array = array(
                'user_fname' => $user_fname,
                'user_lname' => $user_lname
            );
        }

        $image_nm = "user_image";
        if($_FILES['user_image']['name']!=""){
            //Upload Helper  
            $thumb_image_path   = FILE_UPLOAD_PATH.'/users/thumbnail/';
            $image_path   = FILE_UPLOAD_PATH.'/users/';      
            $upload_image = Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path);

            if(isset($upload_image['error'])){
                $result=array('error_status'=>$upload_image['error']);
                die();
            } else {
                $original_nm=$upload_image['original_nm'];
                $thumb_nm=$upload_image['thumb_nm'];
                $image_array=array(                    
                    'user_img' => $original_nm,
                    'user_thumb_img' => $thumb_nm
                );                
            }
        }

        $update_array=array_merge($get_array, $image_array);
        
        $result=$this->super_dbmodel->update_data("register", $update_array, "user_id", $admin_id);
        if($result=="success") {
            $logout_url=base_url('/logout');
            $result=array("success_status"=> "Your profile has been updated, Please <a href='$logout_url'>Logout</a> to see all changes");
        } else  {
            $result=array("error_status"=> 'Changes already updated');
        }
        echo json_encode($result);
    }







    //User Section 
    public function users() {
        $session_data = $this->session->userdata('login_auth'); 
        $admin_id = $session_data->user_id;
        $order=array(
            'user_id'=>'desc'
        );
        $where=array(
            'user_id'=> $admin_id  
        );
        $users_data = $this->super_dbmodel->get_wherenot_datatable_order("register", "*", $where, $order);

        $this->load->view('admin/header', array('site_header' => 'Users'));
        $this->load->view('admin/sidebar', array('site_menu'=>'users'));
        $this->load->view('admin/users/users.php', array('users_data' => $users_data));
        $this->load->view('admin/footer');
    }


    public function data_table_users() {
        $session_data = $this->session->userdata('login_auth'); 
        $admin_id = $session_data->user_id;
        $table_name = "register";
        $column_order = array(null, 'user_img','user_fname', 'user_lname', 'user_email', 'created_date','last_login');
        $column_search = array('user_fname', 'user_lname', 'user_email');
        $order = array('user_fname' => 'asc');
        /*$where_notin = array(
            'user_id'=> 1  
        );*/

        $where_notin = array();

        $where=array();

        $data = $row = array();
        $i = $_POST['start'];
        $memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        foreach ($memData as $member) {
            $i++;
            $created = date('M d, Y H:i:s', strtotime($member->created_date));

            $user_img="";
            if($member->user_identify==1){
                $user_img="<img src='".$member->user_social_img."'/>";
            } else {
                if($member->user_img == ""){
                    $no_img= base_url('assets/images/blank_img.png');
                    $user_img="<img height='96px' src='".$no_img."'/>";
                } else {
                    $user_uploaded_img= base_url('assets/admin/upload/users/thumbnail/');
                    $user_img="<img height='96px' src='".$user_uploaded_img.$member->user_thumb_img."'/>";
                }
            }

            $member_status = "";
            $edit_data="editdata_".$member->user_id;
            $delete_data="deletedata_".$member->user_id;

            $status="";
            if($member->status==1){
                $status="<a href='javascript:void(0)' class='btn btn-sm btn-success'>Active</a>";
            } else {
                $status="<a href='javascript:void(0)' class='btn btn-sm btn-danger'>Deactive</a>";
            }
            

            $assign_team_url = "<a href='".base_url('admin/team-assign-to-user/') . "?user=" . $member->user_id . "&tb=register' class='btn btn-primary btn-sm'  title='Assign User'>View</a>";

            // $usernm = "<a href='$assign_team_url' class='team_applied btn btn-primary btn-sm' title='Assign User'>View </a>";


            $member_status .= "<a class='tack_data_$member->user_id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='register' data-key='user_id' data-value='$member->user_id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";
            $member_tabs ="<a target='_blank' href='".base_url('/admin/tabs/bookmarks')."?p_id=".base64_encode($member->user_id)."&tab=user' class='btn btn-warning btn-sm' title='tabs' data-trigger='hover' id='$member->user_id' data-id='$member->user_id'>View</a>&nbsp;";

            $member_status .= "<a href='javascript:void(0)' class='tack_data_$member->user_id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='register' data-key='user_id' data-value='$member->user_id'><i class='fa fa-times'></i></a>";
            if($member->user_identify === '1'){
                $lastlogin = '<span>'.date('Y-m-d h:i A',strtotime($member->last_login)).' <small>Google</small></span>';
            }else{
                $lastlogin = '<span>'.date('Y-m-d h:i A',strtotime($member->last_login)).' <small>Manual</small></span>';
            }
            $data[] = array($i, $user_img, ucfirst($member->user_fname)." ".ucfirst($member->user_lname), $member->user_email, $status,$member_tabs,$assign_team_url,$lastlogin,$member_status);
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



    public function edit_users(){
        extract($_REQUEST);

        $where=array(
            $table_key=>$catalogue_id
        );

        $edit_result=$this->super_dbmodel->get_where_single_data($tablenm, "*", $where);
        echo json_encode($edit_result);
    }




    public function update_user(){
        extract($_REQUEST);
        $edit_id=$this->input->post('edit_id');

        $where=array(
            'user_id'=> $edit_id
        );

        //find user
        $user = $this->super_dbmodel->get_where_data('register','*',$where);
        //print_r($_REQUEST);die;


        $thumb_image_path   = FILE_UPLOAD_PATH.'/users/thumbnail/';
        $image_path   = FILE_UPLOAD_PATH.'/users/';
        $image_nm = "user_image";
        if($_FILES['user_image']['name']!=""){
           // print_r($_FILES);die;
            //Upload Helper        
            $upload_image = Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path);

            if(isset($upload_image['error'])){
                $result=array('error_status'=>$upload_image['error']);
                die();
            } else {
                $original_nm=$upload_image['original_nm'];
                $thumb_nm=$upload_image['thumb_nm'];
                $update_array=array(
                    'user_fname' => $user_fname,                
                    'user_lname' => $user_lname,
                    'user_email' => $user_email,
                    'user_pass' => !empty($user_password)?md5($user_password): $user[0]['user_pass'],
                    'status' =>$user_status,
                    'user_img' => $original_nm,
                    'user_role' =>$user_role,
                    'user_thumb_img' => $thumb_nm
                );                
            }
        }  else {
            $update_array=array(
                'user_fname' => $user_fname,                
                'user_lname' => $user_lname,
                'user_email' => $user_email,
                'user_role' =>$user_role,
                'user_pass' => !empty($user_password)?md5($user_password): $user[0]['user_pass'],               
                'status' =>$user_status
            );  
        }

        

        if($this->super_insertmodel->update_table_data($where, $update_array,'register')){
            $result=array('success_status'=>'User updated successfully');
        } else {
            $result=array('error_status'=>'Failed to update User data');
        }        
        echo json_encode($result);         
        die();
    }


    public function usertabs(){
        $user_id = base64_decode($_GET['user_id']);
        $alltabs = gettabsbyuserid($user_id);
        $selected_user = $this->super_dbmodel->get_where_data_first('register','user_fname,user_lname',['user_id'=>$user_id]);

        $graphics = $this->super_dbmodel->get_datawith_limit('graphics','*',25, 0);
        $userdata = $this->super_dbmodel->get_where_single_data('register', '*', ['user_id'=>$user_id]);
        $this->load->view('admin/header', array('site_header' => "User's Tab"));
        $this->load->view('admin/sidebar', array('site_menu'=>'users'));
        $this->load->view('admin/users/tabs.php', array('alltabs' => $alltabs,'userdata'=>$userdata,'user_id'=>$user_id,'graphics'=>$graphics,'total_graphics'=>$this->super_dbmodel->count_data('graphics','id',"NULL"),'selected_user'=>$selected_user));
        $this->load->view('admin/footer');
    }


    public function add_user(){
        extract($_REQUEST);
       // print_r($_REQUEST);die;

        $thumb_image_path   = FILE_UPLOAD_PATH.'/users/thumbnail/';
        $image_path   = FILE_UPLOAD_PATH.'/users/';
        $image_nm = "user_image";

      /*  $insert_array=array(
                'user_fname' => $user_fname,                
                'user_lname' => $user_lname,
                'user_email' => $user_email,
                'status' =>$user_status,
                'user_role' => $user_role,
                'user_pass' => $user_password
            );  
        */


        if($_FILES['user_image']['name']!=""){
            //Upload Helper        
            $upload_image = Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path);

            if(isset($upload_image['error'])){
                $result=array('error_status'=>$upload_image['error']);
                die();
            } else {
                $original_nm=$upload_image['original_nm'];
                $thumb_nm=$upload_image['thumb_nm'];
                $insert_array=array(
                    'user_fname' => $user_fname,                
                    'user_lname' => $user_lname,
                    'user_email' => $user_email,
                    'status' =>$user_status,
                    'user_role' => $user_role,
                    'user_pass' => md5($user_password),
                    'user_img' => $original_nm,
                    'user_thumb_img' => $thumb_nm
            );           
            }
        }  else {
           $insert_array=array(
                'user_fname' => $user_fname,                
                'user_lname' => $user_lname,
                'user_email' => $user_email,
                'status' =>$user_status,
                'user_role' => $user_role,
                'user_pass' => md5($user_password)
            );  
             
        }



        if($this->super_insertmodel->insert_data('register',$insert_array)){
            $result=array('success_status'=>'User added successfully');
        } else {
            $result=array('error_status'=>'Failed to add user');
        }        
        echo json_encode($result);         
        die();
    }



    public function assign_bookmark_dataTable(){
        extract($_REQUEST);

        $table_name = "bookmarks";
        $column_order = array(null, 'name', 'logo', 'created_at');
        $column_search = array('name', 'url');
        $order = array('id ' => 'desc');
        $where_notin = array();

        $type_data=array();
        if($table_type=="company" || $table_type=="team"){
            $where_conditions=array($table_type."_id"=>$table_id);
            $type_data=$this->super_dbmodel->get_where_data('bookmarks', "*", $where_conditions);
        }


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
            $explode_ids = explode(', ', $bookmark_nm);


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

            /*echo "<pre>";
            print_r($type_data);
            die();*/
            
            if(isset($type_data) && !empty($type_data)){
                $search_column = array_column($type_data, 'id');
                if(in_array($bookmark->id, $search_column)){
                    $bookmark_status = "<a class='admin_action tack_data_$bookmark->id btn1 btn-danger btn-sm' href='javascript:void(0)' id='$edit_data' data-title='remove' data-type='$table_type' title='Click to un-assigned bookmark for $table_type' data-value='$bookmark_nm'>Unassigned</a>";
                } else {
                    $bookmark_status = "<a class='admin_action tack_data_ btn1 btn-success btn-sm' href='javascript:void(0)' id='$edit_data' data-id='$bookmark->user_id' data-title='add' data-type='$table_type' title='Click to assigned bookmark for $table_type' data-value='$bookmark_nm'>Assign</a>";
                }
            } else {
                $bookmark_status = "<a class='admin_action tack_data_ btn1 btn-success btn-sm' href='javascript:void(0)' id='$edit_data' data-id='$bookmark->user_id' data-title='add' data-type='tab_meta' title='Click to assigned bookmark for $table_type' data-value='$bookmark_nm'>Assign</a>";
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





    


    public function update_bookmark(){
        extract($_REQUEST);        
        
        if($type=="tabs"){
            $type="tab";
        } 

        $key="bookmarks_".$type."_".$active_tab_id;
        $bookmark_data= getset_meta($type."_meta", "get", $active_tab_id ,$key, $value = null);
        $final_bookmark= $final_meta=array();
        if(isset($bookmark_data) && !empty($bookmark_data)){  //Existing in Team Meta
            if($type_title=="remove"){
                foreach ($bookmark_data as $meta_bookmarks) {
                    if($meta_bookmarks!=$type_id){
                        $final_meta[]=$meta_bookmarks;
                    }
                }
            } else {
                array_push($bookmark_data, $type_id);
                $final_meta=$bookmark_data;
            }
        } else {   //Add in Team Meta
            $final_meta[]=$type_id;   
        }

        getset_meta($type."_meta", "update", $active_tab_id ,$key, $final_meta);
        
        $where=array("id"=> $type_id);

        $table_result=$this->super_dbmodel->get_where_data_first("bookmarks", "*", $where);

        $final_array=array();
        $typeid="$type"."_id";
        $get_type=$table_result->$typeid;
        
        if($type_title=="remove"){            
            $explode_ids=explode(", ", $get_type);
            foreach ($explode_ids as $exp) {
                if($active_tab_id!=$exp){
                    $final_array[]=$exp;
                }
            }

            if(count($final_array)==1){
                $final_string=$final_array[0];
            } else {
                $final_string=implode(", ", $final_array);
            }

            $msg= 'Bookmark has been unassigned successfully';
        } else {
            if($get_type!=""){
                $explode_ids=explode(", ", $get_type);
                array_push($explode_ids, $active_tab_id);
                $final_array=$explode_ids;
                if(count($final_array)==1){
                    $final_string=$final_array[0];
                } else {
                    $final_string=implode(", ", $final_array);
                } 
            } else {
                $final_string=$active_tab_id;
            }
            $msg= 'Bookmark has been assigned successfully';
        }


        $update_array=array($typeid=> $final_string);

        if($this->super_dbmodel->update_data("bookmarks", $update_array, "id", $type_id)){
            $result=array(
                'success'=> 200,
                'message'=> $msg
            );
        } else {
            $result=array(
                'error'=> 202,
                'message'=> "Failed to assign bookmark, Please try again later."
            );
        }
        

        echo json_encode($result);
        die();
    }



    public function assign_bookmark_dataTable_tabs(){
        extract($_REQUEST);

        $table_name = "bookmarks";
        $column_order = array(null, 'name', 'logo', 'created_at');
        $column_search = array('name', 'url');
        $order = array('id ' => 'desc');
        $where_notin = array();

        $type_data=array();


        if($table_type=="company" || $table_type=="team" || $table_type=="user"){
            $where_conditions=array($table_type."_id"=>$table_id);
            $order=array('id'=> 'desc');
            $type_data=$this->super_dbmodel->get_sort_where_data('bookmarks', "*", $order, $where_conditions);
        } else {
            $type="tab_meta";
            $key="bookmarks_tab_".$table_id;
            $type_data= getset_meta($type, "get", $table_id ,$key, $value = null);
        }




        


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
            $explode_ids = explode(', ', $bookmark_nm);


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

            $bn_nm= $bookmark->name;
            if(isset($type_data) && !empty($type_data)){
                if($table_type!="tabs"){
                    $search_column = array_column($type_data, 'id');
                } else {
                    $search_column = $type_data;
                }

                if(in_array($bookmark->id, $search_column)){
                    $bookmark_status = "<a class='admin_action tack_data_$bookmark->id btn1 btn-danger btn-sm' href='javascript:void(0)' id='$edit_data' data-title='remove' data-type='$table_type' data-nm='$bn_nm' title='Click to un-assigned $bn_nm bookmark for $table_type' data-value='$bookmark_nm'>Unassigned</a>";
                } else {
                    $bookmark_status = "<a class='admin_action tack_data_ btn1 btn-success btn-sm' href='javascript:void(0)' id='$edit_data' data-id='$bookmark->user_id' data-title='add' data-type='$table_type' data-nm='$bn_nm' title='Click to assigned $bn_nm bookmark for $table_type' data-value='$bookmark_nm'>Assign</a>";
                }                
            } else {
                $bookmark_status = "<a class='admin_action tack_data_ btn1 btn-success btn-sm' href='javascript:void(0)' id='$edit_data' data-id='$bookmark->user_id' data-title='add' data-type='$table_type' title='Click to assigned bookmark for $table_type' data-value='$bookmark_nm'>Assign</a>";
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


    //type_title="Add/Remove" and type=table name  //type_id=Tab ID
    function update_tab_metas($active_tab_id, $type_id, $type_title, $type){    
        $key="bookmarks_".$type."_".$active_tab_id;
        $bookmark_data= getset_meta($type."_meta", "get", $active_tab_id ,$key, $value = null);
        $final_bookmark= $final_meta=array();
        if(isset($bookmark_data) && !empty($bookmark_data)){  //Existing in Team Meta
            if($type_title=="remove"){
                foreach ($bookmark_data as $meta_bookmarks) {
                    if($meta_bookmarks!=$type_id){
                        $final_meta[]=$meta_bookmarks;
                    }
                }
                $msg= 'Bookmark has been unassigned successfully';
            } else {
                array_push($bookmark_data, $type_id);
                $final_meta=$bookmark_data;
                $msg= 'Bookmark has been assigned successfully';
            }
        } else {   //Add in Team Meta
            $final_meta[]=$type_id; 
            $msg= 'Bookmark has been assigned successfully';  
        }

        if(getset_meta($type."_meta", "update", $active_tab_id ,$key, $final_meta)){
            $result=array(
                'success'=> 200,
                'message'=> $msg
            );
        } else {
            $result=array(
                'error'=> 202,
                'message'=> "Failed to assign bookmark, Please try again later."
            );
        }

        return $result;
    }	

}
