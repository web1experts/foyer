<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logoutcontroller extends CI_Controller {

	function __construct() {
    	parent::__construct();
    	$this->load->library('session');
    	$this->load->model('super_insertmodel');
    	$this->load->model('super_dbmodel');
  	}

	public function index()	{	
		$user_data = $this->session->userdata('login_auth');
		$this->super_insertmodel->update_data(['user_id'=>$user_data->user_id],['remember_token'=>'']);	
		$this->session->unset_userdata('login_auth');
		unset($_COOKIE['remember_me_token']); 
		redirect('/');		
	}
}
