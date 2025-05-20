<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profilecontroller extends CI_Controller {
    public $result_header, $result_footer, $result_email, $result_page, $page_menus;
    function __construct() {        
        parent::__construct($result_header=null, $result_footer=null, $result_email=null, $result_page=null);
        if (!is_logged_in()) {
            redirect('/login');
        }
        
        $this->load->library("session");
        $this->load->model('data_table');
        $this->load->model('super_insertmodel');
        $this->load->model('super_dbmodel');
        $this->result_header = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'header'));
        $this->result_footer = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'footer'));
        $this->result_email = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'forgot_password'));
        $this->result_page = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'page_settings'));
        $this->page_menus  = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'menu_text'));
    }


    public function index(){

        $session_data = $this->session->userdata('login_auth');
        $where=array(
            'user_id'=> $session_data->user_id  
        );
        $prf_data = $this->super_dbmodel->get_where_single_data("register", "*", $where);
        $graphics = $this->super_dbmodel->get_datawith_where_limit('graphics','*',['user_id'=>$session_data->user_id],25, 0);
        $this->load->view('user/header', array('page_title'=>'Profile', 'meta_title'=>'profile' , 'page_activate'=>'Profile', 'meta_description'=>'Profile | Nahisa', 'result_header'=>$this->result_header, 'result_footer'=>$this->result_footer, 'result_fg'=> $this->result_email, 'result_page'=>$this->result_page, 'menus'=>$this->page_menus));
        $this->load->view('user/profile', array('prf_data'=> $prf_data));
        $this->load->view('user/footer', array('result_header'=>$this->result_header, 'result_footer'=>$this->result_footer, 'result_fg'=> $this->result_email, 'result_page'=>$this->result_page,'graphics'=>$graphics,'total_graphics'=>$this->super_dbmodel->count_data('graphics','user_id',$session_data->user_id)));
    }


    public function update_profile(){
        $session_data = $this->session->userdata('login_auth');
        $user_id = $session_data->user_id;
        extract($_REQUEST);

        $update_array=array(
            'user_fname' => $first_name,
            'user_lname' => $last_name,            
            'user_phone' => $user_phone,
            'label_visibility'=>isset($label_visibility) ? 1 : 0,
        );
        if(isset($password) && !empty($password)){
            $update_array['user_pass'] = md5($password);
        }
        $where=array(
            'user_id' => $user_id
        );

        if($this->super_insertmodel->update_data($where, $update_array)){
            $result=array('success_status'=>'Profile updated successfully');
        } else {
            $result=array('error_status'=>'Failed to update profile details');
        }
        echo json_encode($result);
    }

}