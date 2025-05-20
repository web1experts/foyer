<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requestcontroller extends CI_Controller {

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
    public function index() {
        $order=array(
            'id'=>'desc'
        );
        $request_data = $this->super_dbmodel->get_sort_data("request_bookmark", "*", $order);
        $this->load->view('admin/header', array('site_header' => 'All Requests'));
        $this->load->view('admin/sidebar', array('site_menu'=>'requests'));
        $this->load->view('admin/requests/request.php', array('request_data' => $request_data));
        $this->load->view('admin/footer');
    }


    public function data_table_requests() {
        $table_name = "request_bookmark";
        $column_order = array(null, 'req_label','req_url', 'created_at');
        $column_search = array('req_label','req_url');
        $order = array('id  ' => 'desc');
        $where_notin = array();

        $where=array();

        $data = $row = array();
        $i = $_POST['start'];
        $memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        foreach ($memData as $member) {
            $i++;
            $created = date('M d, Y H:i:s', strtotime($member->created_at));

            
            $member_status = ""; 
            $edit_data="editdata_".$member->id;
            $delete_data="deletedata_".$member->id;

            
            


            //Users Results
            $user_id=$member->user_id;
            $user_result= $this->super_dbmodel->get_where_single_data("register", "*", array("user_id"=> $user_id));
            $user_nm=$user_result->user_fname." ".$user_result->user_lname;
            //End User Results

            //$member_status .= "<a class='tack_data_$member->id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='faqs' data-key='id' data-value='$member->id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";

            $member_status .= "<a href='javascript:void(0)' class='tack_data_$member->id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='request_bookmark' data-key='id' data-value='$member->id'><i class='fa fa-times'></i></a>";

            $data[] = array($i, $member->req_label, $member->req_url, $member->req_comment, $user_nm, $created, $member_status);
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




    public function faqs_edit(){
        extract($_REQUEST);
        $where=array(
            $table_key=>$catalogue_id
        );

        $edit_result=$this->super_dbmodel->get_where_single_data($tablenm, "*", $where);
        echo json_encode($edit_result);
    }


    public function save_faqs(){
        extract($_REQUEST);
        $insert_array=array(
            'faq_question' => htmlentities($faq_qst),
            'faq_answer'  => htmlentities($faq_ans)
        );

        if($this->super_insertmodel->insert_data("faqs", $insert_array)){
            $result=array('success_status'=>lang('faq_insert_success'));
        } else {
            $result=array('error_status'=>lang('faq_insert_error'));
        }

        echo json_encode($result);
    }


    
    public function update_faqs(){
        extract($_REQUEST);
        $update_array=array(
            'faq_question' => htmlentities($edit_faq_qst),
            'faq_answer'  => htmlentities($edit_faq_ans)
        );

        
        
        $where=array('faq_id'=>$edit_id);
        if($this->super_insertmodel->update_table_data($where, $update_array,'faqs')){
            $result=array('success_status'=> lang('faq_edit_success'));
        } else {
            $result=array('error_status'=>lang('faq_edit_error'));
        }        
        echo json_encode($result);         
        die();
    }

}