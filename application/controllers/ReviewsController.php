<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reviewscontroller extends CI_Controller {

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
            'reviews_id'=>'desc'
        );
        $reviews_data = $this->super_dbmodel->get_sort_data("reviews", "*", $order);
        $this->load->view('admin/header', array('site_header' => 'All Reviews'));
        $this->load->view('admin/sidebar', array('site_menu'=>'reviews'));
        $this->load->view('admin/reviews/review.php', array('users_data' => $reviews_data));
        $this->load->view('admin/footer');
    }


    public function data_table_reviews() {
        $table_name = "reviews";
        $column_order = array(null, 'first_name','last_name', 'guest_email', 'rate', 'recomend_us','message','created_at');
        $column_search = array('first_name', 'last_name', 'guest_email', 'rate', 'message');
        $order = array('reviews_id' => 'desc');
        $where_notin = array();

        $where=array();

        $data = $row = array();
        $i = $_POST['start'];
        $memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        foreach ($memData as $member) {
            $i++;
            $created = date('M d, Y H:i:s', strtotime($member->created_at));
            
            $member_status = ""; 
            $edit_data="editdata_".$member->reviews_id;
            $delete_data="deletedata_".$member->reviews_id;

            $member_status .= "<a class='tack_data_$member->reviews_id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='reviews' data-key='reviews_id' data-value='$member->reviews_id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";
            //$member_status .="<a href='javascript:void(0)' class='tack_data btn btn-warning btn-sm user_action' title='Unfollow' data-trigger='hover' id='$member->tl_screen_name' data-id='$member->tl_twitter_id'><i class='fas fa-thumbs-down'></i></a>&nbsp;";

            $member_status .= "<a href='javascript:void(0)' class='tack_data_$member->reviews_id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='reviews' data-key='reviews_id' data-value='$member->reviews_id'><i class='fa fa-times'></i></a>";

            $data[] = array($i, $member->first_name." ".$member->last_name, $member->guest_email, $member->rate, $member->recomend_us, $member->message, $created, $member_status);
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




    public function contact_edit(){
        extract($_REQUEST);
        $where=array(
            $table_key=>$catalogue_id
        );

        $edit_result=$this->super_dbmodel->get_where_single_data($tablenm, "*", $where);
        echo json_encode($edit_result);
    }


    
    public function update_reviews(){
        extract($_REQUEST);
        $update_array=array(
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'guest_email'=> $guest_email,
            'rate'       => $rating_us,
            'recomend_us'=> $recomend_us,            
            'message'    => htmlentities($guest_messages)
        );
        
        $where=array('reviews_id'=>$edit_id);
        if($this->super_insertmodel->update_table_data($where, $update_array,'reviews')){
            $result=array('success_status'=>'Contact updated successfully');
        } else {
            $result=array('error_status'=>'Failed to update contact data');
        }        
        echo json_encode($result);         
        die();
    }

}