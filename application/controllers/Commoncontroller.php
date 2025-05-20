<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commoncontroller extends CI_Controller {

	function __construct() {
    	parent::__construct();        
        if (!is_logged_in()) {
            redirect('/login');
        }

        $this->load->library("session");
        $this->load->model('data_table');
    	$this->load->model('super_insertmodel');
    	$this->load->model('super_dbmodel');
        $this->load->library('pagination');
  	}


    public function delete_row(){
        if (!is_logged_in() || !check_admin()) {
            $final_result=array('error'=> lang('You are not authenticated to perform this action.'));
        }
        extract($_REQUEST);
        
        if($table=="catagories" || $table=="brands"){
            $result=$this->super_dbmodel->search_like_data("catalogue", $table_id, $table_value, "both");
            if($result!=0){
                if($table=="catagories"){
                    $final_result=array('error'=> lang('associated_catalogue_cats'));
                } else {
                    $final_result=array('error'=> lang('associated_catalogue_brands'));
                }
            } 
        }elseif($table=="graphics"){
            $thumb_image_path   = FILE_UPLOAD_PATH.'/graphics/thumbnail/';
            $image_path   = FILE_UPLOAD_PATH.'/graphics/';
            $where=array($table_id=>$table_value);
            $delete_result=$this->super_insertmodel->delete_table_data($where, $table);
            if($delete_result){
                unlink($image_path.substr($path, strrpos($path, '/') + 1));
                unlink($thumb_image_path.substr($thumb, strrpos($thumb, '/') + 1));
                $final_result=array('success'=> lang('delete_success'));
            } else {
                $final_result=array('error'=> lang('failed_delete'));
            }
        }elseif($table == 'tabs'){
            $where=array($table_id=>$table_value);
            $this->super_insertmodel->delete_table_data(['tab_id'=>$table_value], 'bookmarks');
            $delete_result=$this->super_insertmodel->delete_table_data($where, $table);
            if($delete_result){
                $final_result=array('success'=> lang('delete_success'));
            } else {
                $final_result=array('error'=> lang('failed_delete'));
            }
        } else {
            $where=array($table_id=>$table_value);
            $delete_result=$this->super_insertmodel->delete_table_data($where, $table);
            if($delete_result){
                $final_result=array('success'=> lang('delete_success'));
            } else {
                $final_result=array('error'=> lang('failed_delete'));
            }
        }        
        echo json_encode($final_result);
    }


    public function clonedata(){
        extract($_REQUEST);
        $session_lang = $this->session->userdata('site_lang');
        $where=array("lang !="=> "english");
        $delete_result=$this->super_insertmodel->delete_table_data($where, $table);

        $order=array(
            'slider_id'=>'desc'            
        );

        $slider_data = $this->super_dbmodel->get_sort_where_data("sliders", "*", $order, array('lang' => 'english'));


        foreach ($slider_data as $key => $value) {
            $slider_data['lang'] = $session_lang;
            
        }
    }

    public function getallrows(){
        if($_GET['table'] == 'company'){
            $fields = 'cmp_id as id,cmp_text as name';
        }else if($_GET['table'] == 'teams'){
            $fields = 'id,name';
        }
        $data = $this->super_dbmodel->get_data($_GET['table'], $fields);
        echo json_encode(['result'=>$data]);
    }

    public function geticons(){
        extract($_REQUEST);
        $config = array();
        $config["base_url"] = base_url() . "/commoncontroller/geticons";
        $search_term = (isset($search))?$search:'';
        $config["per_page"] = 20;
        $config["uri_segment"] = 3;
        $config['enable_query_strings'] = TRUE;
        $config['reuse_query_string'] = true;
        $user_id = (isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id']))?$_REQUEST['user_id']:'';
        $page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
        if(!empty($user_id)){
            // $where_arr['user_id']=$user_id;
            // if(isset($_REQUEST['search'])){
            //     $where_arr['name']=$user_id;
            // }
            // $graphics = $this->super_dbmodel->get_datawith_where_limit('graphics','*',,$config["per_page"], $page);
            // $config["total_rows"] = $this->super_dbmodel->count_data('graphics','user_id',$user_id);


            $wherein = "1," . $user_id;
           
           }else{
                $wherein = 'no';
           }

            $graphics = $this->super_dbmodel->media_with_limit('graphics', '*', $wherein, $search_term, $config["per_page"], $page);

            $config["total_rows"] = $this->super_dbmodel->media_all_users('graphics', '*', $wherein, $search_term);

        
        $this->pagination->initialize($config);
        
        $paginate["links"] = $this->pagination->create_links();
    
        $this->load->view('common/icons',['graphics'=>$graphics,'paginate' =>$paginate,'user_id'=>$user_id,'search_term'=>$search_term]);
    }
}