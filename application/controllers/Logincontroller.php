<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/third_party/vendor/autoload.php';
use Hybridauth\Hybridauth;

class Logincontroller extends CI_Controller {

	function __construct() {
    	parent::__construct();
    	if (is_logged_in()) {
    		// if(check_admin()) {
      //       	redirect('/');
      //   	} else {
        		redirect('/');
        	// }
        }
        $this->load->helper('cookie');
    	$this->load->library('session');
    	$this->load->model('super_insertmodel');
    	$this->load->model('super_dbmodel');
  	}


	public function index()	{	
        $login_data = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'bookmark_background'));	

        $login_image=array();
        if (isset($login_data->meta_value) && $login_data->meta_value != "") {
            $login_image = json_decode($login_data->meta_value);
        }        

		$this->load->view('header', array('site_title'=> 'Login', 'login_background'=> $login_image));
		$this->load->view('index');
		$this->load->view('footer');
	}


	public function check_authenticate() {

        $user_email = $this->input->post('user_email');
        $user_password = md5($this->input->post('user_password'));        
        $check_auth = $this->super_dbmodel->check_authenticate($user_email, $user_password);        
        if ($check_auth != 0) {
            $this->session->set_userdata('login_auth', $check_auth[0]);
            $this->super_insertmodel->update_data(['user_id'=>$check_auth[0]->user_id],['last_login' => date('Y-m-d h:i:s')]); 
           
            if($this->input->post('remember_me')){
                $c_val = bin2hex(random_bytes(20));
            //   if(setcookie('remember_me_token',$c_val,'1209600','/','foyer.haganrealty.com')){
            //         $this->super_insertmodel->update_data(['user_id'=>$check_auth[0]->user_id],['remember_token'=>$c_val]);
            //   }
            
            $cookie= array(
               'name'   => 'remember_me_token',
               'value'  => $c_val,
               'expire' => '1209600',
               'domain' =>'foyer.haganrealty.com',
               'path' => '/'
           );
            $this->input->set_cookie($cookie);
            $this->super_insertmodel->update_data(['user_id'=>$check_auth[0]->user_id],['remember_token'=>$c_val]);
              
            
            }
            redirect('/');
            
        } else {            
            $this->session->set_flashdata('login_error', "Username and password invalid, Please try again.");
			redirect('/login');
        }
    }

	public function register() {
		$this->load->view('header', array('site_title'=> 'Register'));
		$this->load->view('register');
		$this->load->view('footer');
	}



	public function save_register() {		
		$first_name=$this->input->post('first_name');
		$last_name=$this->input->post('last_name');
		$email=$this->input->post('email');
		$pass=$this->input->post('pass');	
		$created_date=date('Y-m-d H:i:s');
		$save_array=array(
			'user_fname'=> $first_name,
			'user_lname'=> $last_name,
			'user_email'=> $email,
			'user_pass'=> md5($pass),
			'created_date'=> $created_date 
		);

		$where=array(
			'user_email'=> $email,
            'status' => 1
		);
		$columns="*";

		$check=$this->super_dbmodel->count_where_data('register', $columns, $where);

		if($check==1){
			$this->session->set_flashdata('user_errors', "User id already exists, Please change your password");
			redirect('/forgot-password');
			die();
		}
        $url = base_url()."email-verify";
        $link = "<a href=".$url.">Click Here</a>";
        mail($email,"Your Activation link","Your Link is  : ".$link);
		$last_inserted_id= $this->super_insertmodel->insert_data('register', $save_array);
		if($last_inserted_id){			
			$this->session->set_flashdata('success', "Please login to continue");
			redirect('/login');
		} else {
			$this->session->set_flashdata('errors', "Please try after some time");
			redirect('/register');
		}
	}

	public function forgot_password(){
        $login_data = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key'=>'bookmark_background'));   

        $login_image=array();
        if (isset($login_data->meta_value) && $login_data->meta_value != "") {
            $login_image = json_decode($login_data->meta_value);
        }        

        $this->load->view('header', array('site_title'=> 'Forgot Password', 'login_background'=> $login_image));
        //$this->load->view('header', array('site_title'=> 'Forgot Password'));
        $this->load->view('forgot_password');
        $this->load->view('footer');
    }

	public function check_email() {
        $user_email = $this->input->post('user_email');
        $data = array(
            'user_email' => $user_email,
        );
        $check_auth = $this->super_insertmodel->check_email($user_email);

        
        if ($check_auth) {
            $cookie = array(
                'name' => 'forgot_val',
                'value' => 'forgot_pwd',
                'expire' => '3600'
            );
            $this->input->set_cookie($cookie);
            $this->session->set_flashdata('success', "Email has been sent in your email id.");               
            redirect('/login', 'refresh');
        } else {
        	$this->session->set_flashdata('errors', "Email id not matched, Please try with registerd email id");            
            redirect('/forgot-password', 'refresh');
        }        
    }

    public function change_password($user_email) {
        if ($user_email != "") {            
            if ($this->input->cookie('forgot_val', true)) {
                $decrypt_email = base64_decode(urldecode($user_email));                
                $this->load->view('header', array('site_title'=> 'Set New Password'));
				$this->load->view('change_password', array('user_id' => $decrypt_email));
				$this->load->view('footer');
            } else {
                echo "Your reset password link has been expired. Please click to <a href='" . base_url() . "'>".base_url()."</a> and try agian";
                die();
            }
        } else {
            $this->session->set_flashdata('twitter_errors', "Please try after some time");
            redirect('/login', 'refresh');
        }
    }

    public function setnew_pwd() {
    	$this->load->helper('cookie');
        $email_id=$this->input->post('user_email');
        $user_pwd=$this->input->post('user_password');
        $update_pwd=$this->super_insertmodel->update_password($email_id, $user_pwd);
        if($update_pwd){
        	delete_cookie("forgot_val");
            $this->session->set_flashdata('twitter_success', "Your password has been successfully changed.");
            redirect('/login', 'refresh');
        } else{            
            $this->session->set_flashdata('twitter_errors', "Please try after some time");
            redirect('/login', 'refresh');
        }
    }


	public function test(){
		//$columns="*";
		//$where=array('tw_id'=> '1125725777792720896', 'tw_name'=>'test');
		//$this->super_dbmodel->get_where_data('twitter_register', $columns, $where);
	}

    public function auth($provider = NULL) {

        $service = NULL;
        try {

            $hybrid = new Hybridauth(getHybridConfig());
            if(isset($provider) && in_array($provider, $hybrid->getProviders())) {
                $this->session->set_userdata('provider', $provider);
            }

            $provider = $this->session->userdata('provider');

            if ($provider) {
                $service = $hybrid->authenticate($provider);

                if ($service->isConnected()) {
                    $profile = $service->getUserProfile();
                    $contacts = $service->getUserContacts();
                    $service->disconnect();

                    $this->session->unset_userdata('provider');
                   // echo 'Name: ' . $profile->displayName;
                    /*echo "<pre>";
                    print_r($profile);
                    die;*/
                   //$password = rand_string(8);
                    $created_date=date('Y-m-d H:i:s');
                    $save_array=array(
                        'user_fname'=> $profile->firstName,
                        'user_lname'=> $profile->lastName,
                        'user_email'=> $profile->email,
                        //'user_pass' => md5($password),
                        'user_role'=> "2",
                        'user_identify'=>'1',
                        'user_token' =>$profile->identifier,
                        'user_social_img'=>$profile->photoURL,
                        'created_date'=> $created_date 
                    );



                    $where_status=array(
                        'status'=> 1,
                        'user_email'=> $profile->email
                    );
                    

                    $check_status=$this->super_dbmodel->count_where_data('register', "*", $where_status);

                    

                    if($check_status==1){

                        $where=array(
                            'user_email'=> $profile->email,
                        );
                        $columns="*";

                        $check=$this->super_dbmodel->count_where_data('register', $columns, $where);

                        if($check==1){
                            $userdata =$this->super_dbmodel->social_login($profile->email);
                             $this->super_insertmodel->update_data(['user_id'=>$userdata[0]->user_id],['last_login' => date('Y-m-d h:i:s')]); 
                            $this->session->set_userdata('login_auth', $userdata[0]);

                            if(isset($_REQUEST['remember'])){
                                $this->session->sess_expiration = 1209600;
                                $this->session->sess_expire_on_close = TRUE;
                            }

                            redirect('/login', 'refresh');
                        } else {

                           $last_inserted_id= $this->super_insertmodel->insert_data('register', $save_array);

                           $userdata =$this->super_dbmodel->social_login($profile->email);

                            $this->session->set_userdata('login_auth', $userdata[0]);

                           if($last_inserted_id){           
                                $this->session->set_flashdata('showmsg', "Please login to continue");
                                redirect('/login');
                            } else {
                                $this->session->set_flashdata('showmsg', "Please try after some time");
                                redirect('/login');
                            }
                        }
                    } else{
                        $this->session->set_flashdata('showmsg', 'Sorry! We couldn\'t authenticate your identity.');
                        redirect('/login');
                    }
                } else {
                    $this->session->set_flashdata('showmsg', 'Sorry! We couldn\'t authenticate your identity.');
                    redirect('/login');
                }
            }
        } catch(Exception $e) {
            if (isset($service) && $service->isConnected()) 
                $service->disconnect();

            $error = 'Sorry! We couldn\'t authenticate you.';
            $this->session->set_flashdata('showmsg', array('msg' => $error));
            $error .= '\nError Code: ' . $e->getCode();
            $error .= '\nError Message: ' . $e->getMessage();

            log_message('error', $error);
        }

        //redirect();
    }


    public function termOfUse() {
        $this->load->view('header', array('site_title'=> 'Term Of Use'));
        $this->load->view('term_of_use');
        $this->load->view('footer');
    }

    public function privacyPolicy() {
        $this->load->view('header', array('site_title'=> 'Privacy Policy'));
        $this->load->view('privacy_policy');
        $this->load->view('footer');
    }

    public function email_verify() {
        echo "test";
        die;
    }

    // public function checkremembertoken(){
    //     if(!is_logged_in() && isset($_COOKIE['remember_me_token'])){
            
    //         $userdata = $this->super_dbmodel->get_where_single_data("register", "remember_token", array('remember_token'=>$_COOKIE['remember_me_token']));
    //         if(!empty($userdata)){
    //             $this->session->set_userdata('login_auth', $userdata);
    //             $this->super_insertmodel->update_data(['user_id'=>$userdata->user_id],['last_login' => date('Y-m-d h:i:s')]); 
    //             redirect('/');
    //         }
    //     }
    // }

}
