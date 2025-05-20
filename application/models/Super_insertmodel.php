<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Super_insertmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert_data($table, $all_columns){
        if($this->db->insert($table, $all_columns)){
        	return true;
        } else {
        	return false;
        }
    }

    public function update_data($where, $update_array) {
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach; 
        
        $update_data = $this->db->update('register', $update_array);
        return $update_data;
    }


    public function update_table_data($where, $update_array, $table) {
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach; 
        
        $update_data = $this->db->update($table, $update_array);
        return $update_data;
    }
    
    public function update_table_data_bookmark($where, $update_array, $table) {
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach; 
        
        $update_data = $this->db->update($table, $update_array);
        return true;
    }


    public function update_table_data_wherein($table, $update_array, $wherekey, $whereinvalue) {
        $this->db->where_in($wherekey, $whereinvalue);
        $update_data = $this->db->update($table, $update_array);
        // echo $this->db->last_query();
        // die();
        return true;
    }

    public function delete_table_data($where, $table) {
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach;        
        $update_data = $this->db->delete($table);
        return $update_data;
    }

    public function insert_data_with_id($table, $all_columns){
        if($this->db->insert($table, $all_columns)){
        	$last_id = $this->db->insert_id();
        	return $last_id;
        } else {
        	return false;
        }
    }


    //Send email for forgot password
    public function check_email($user_email) {
        $this->load->library('email');
        $this->db->where('user_email', $user_email);
        $result_query = $this->db->get('register');
        if ($result_query->num_rows() > 0) {
            $result = $result_query->result();
            $user_email = $result[0]->user_email;
            $user_id = $result[0]->user_id;
            $encryot_mail = base64_encode($user_email);
            $email_url_encode = urlencode($encryot_mail);
            $forgot_pass_link = base_url('/change-password/') . $email_url_encode;


            $this->db->where('user_role', 1);
            $admin_qry = $this->db->get('register');
            $admin_data = $admin_qry->result();
            $admin_email = $admin_data[0]->user_email;
            $admin_name = $result[0]->user_fname." ". $result[0]->user_lname;
            
            $template_data['forgot_link'] = array(
                'forgot_link' => $forgot_pass_link,
                'admin_name' => $admin_name
            );

            $email_template = $this->load->view('email_template/forgot_email_template', $template_data, true);


            $config = array(
                'charset' => 'utf-8',
                'wordwrap' => TRUE,
                'mailtype' => 'html'
            );
            
            $this->email->initialize($config);
            //Send Email
            $this->email->from($admin_email, $admin_name);
            $this->email->to($user_email);
            $this->email->subject('Forgot Password By Bookmark');
            $this->email->message($email_template);
            $mail = $this->email->send();
            return $mail;
        } else {
            return 0;
        }
    }

    public function update_password($email_id, $update_array) {
        $this->db->where('user_email', $email_id);
        $update_data = $this->db->update('register', $update_array);
        return $update_data;
    }

     public function update_user_password($email_id, $user_pwd) {
        $this->db->where('user_email', $email_id);
        $update_array = array(
            'user_pass' => md5($user_pwd)
        );
        $update_data = $this->db->update('register', $update_array);
        return $update_data;
    }

    public function update_user_data($email_id, $update_array) {
        $this->db->where('user_email', $email_id);
       /* $update_array = array(
            'user_pass' => md5($user_pwd)
        );*/
        $update_data = $this->db->update('register', $update_array);
        return $update_data;
    }

}
