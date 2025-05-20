<?php
require APPPATH . '/third_party/vendor/autoload.php';
use Hybridauth\Hybridauth;

class Validatecookie
{
   
    public function checkremembertoken(){
        if(!is_logged_in() && isset($_COOKIE['remember_me_token'])){
            $ci =& get_instance();
            $ci->load->library('session');
        	$ci->load->model('super_insertmodel');
        	$ci->load->model('super_dbmodel');
            $userdata = $ci->super_dbmodel->get_where_single_data("register", "remember_token", array('remember_token'=>$_COOKIE['remember_me_token']));
            if(!empty($userdata)){
                $ci->session->set_userdata('login_auth', $userdata);
                $ci->super_insertmodel->update_data(['user_id'=>$userdata->user_id],['last_login' => date('Y-m-d h:i:s')]); 
                redirect('/');
            }
        }
    }
}