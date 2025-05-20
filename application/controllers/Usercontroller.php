<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usercontroller extends CI_Controller {

	function __construct() {
    	parent::__construct();        
    	$this->load->library('session');
    	$this->load->model('super_insertmodel');
    	$this->load->model('super_dbmodel');
  	}

	
    public function contact_form_submit(){
        extract($_REQUEST);
        $insert_array=array(
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'email'      => $email,
            'subject'    => $subject,
            'message'    => htmlentities($message)
        );

        if($this->super_insertmodel->insert_data("contact", $insert_array)){
            $where =array('user_role'=> 1);
            $admin_data=$this->super_dbmodel->get_where_single_data("register", "*", $where);
            $admin_email=$admin_data->user_email;
            $admin_name=$admin_data->user_fname." ". $admin_data->user_lname;
                  
            $template_data['forgot_link'] = array(            
                'admin_name' => $admin_name
            );

            $email_template = $this->load->view('email_template/forgot_email_template', $template_data, true);
            $config = array(
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'mailtype' => 'html'
            );
            try {
                $this->email->initialize($config);
                //Send Email
                $this->email->from('no-reply@99projects.in', $admin_name);
                $this->email->to($admin_email);
                $this->email->subject('Contact Form submited');
                $this->email->message($email_template);
                $this->email->send();
                $result=array("success"=> lang('contact_success'));
            }
            catch(Exception $e) {                
                $result=array("error"=>lang('contact_error'));
            }
        } else {
            $result=array("error"=>lang('contact_error'));
        }

        echo json_encode($result);
        die();
    }



    public function reviews_form_submit(){
        extract($_REQUEST);

        $insert_array=array(
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'guest_email'  => $guest_email,
            'rate'         => $rate,
            'recomend_us'  => $gridRadios,
            'message'      => htmlentities($message)
        );

        if($this->super_insertmodel->insert_data("reviews", $insert_array)){
            $result=array("success"=>lang('rating_success'));
        } else {
            $result=array("error"=>lang('rating_error'));
        }
        echo json_encode($result);
    }


    public function catalogue_filter(){
        extract($_REQUEST);
        $cats=$brnads=array();
        $popularity=$price_min=$price_max="";

        $this->db->select("*");
        $this->db->from('catalogue');

        if($_REQUEST['price_min']!=""){
            $price_min=$_REQUEST['price_min'];            
            $this->db->where('catalogue_price >=', $price_min);
        }

        if($_REQUEST['price_max']!=""){
            $price_max=$_REQUEST['price_max'];            
            $this->db->where('catalogue_price <=', $price_max);
        }

        if(isset($_REQUEST['cats'])){
            $cat_array=implode(", ", $_REQUEST['cats']);
            $this->db->like('cat_id', $cat_array, 'both');
        }

        if(isset($_REQUEST['brnads'])){
            $brnads_array=implode(", ", $_REQUEST['brnads']);
            $this->db->like('brand_id', $brnads_array, 'both');
        }

        $query = $this->db->get();
        /*echo $this->db->last_query();
        die();*/
        $result_data = $query->result();
        if(isset($result_data) && !empty($result_data)){            
            $filter_data=array("Success"=>"success", 'filter_data'=> $result_data);
        } else {
            $filter_data=array("Error"=>"No result found, Please try with new combinations");
        }
        echo json_encode($filter_data);
        die();
    }



    function close_account(){
        $session_data = $this->session->userdata('login_auth');        
        $user_id = $session_data->user_id;
        $update_array=array(
            'status' => 2
        );
        if($result=$this->super_dbmodel->update_data("register", $update_array, "user_id", $user_id)){
            $result=array("success"=> lang('closed_account_text'), 'trigger_url'=>base_url('/logout'));
        } else {
            $result=array("error"=> lang('failed_ajax_requirements'));
        }
        echo json_encode($result);
    }

    
}
