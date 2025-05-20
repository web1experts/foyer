<?php

function is_logged_in() {
    $CI = & get_instance();
    $user = $CI->session->userdata('login_auth');
    /*echo "<pre>";
    print_r($user);
    die;*/
    if (!isset($user)) {
        return false;
    } else {
        if ($user->user_role) {
            return true;
        } else {
            return true;
        }
    }
}

function check_admin() {
    $CI = & get_instance();
    $user = $CI->session->userdata('login_auth');    
    if (isset($user) && !empty($user)) {
        if($user->user_role==1){
            return true;
        }
    }
}

function rand_string( $length ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);
}

function get_the_title($id, $type){
    $CI = & get_instance();
    if($type=="company"){
        $where_key= "cmp_id";
        $search_title="cmp_text as title";
    } else {
        $where_key=="id";
        $search_title="name as title";
    }
    $title_result = $ci->super_dbmodel->get_where_single_data($type, $search_title, array($where_key => $id));
    return $title_result->title;
}




function getHybridConfig()
    {
        $config = array(

            'callback' => site_url('logincontroller/auth/'),

            'providers' => array(
                'Google' => array(
                    'enabled' => true,
                    'keys' => array(
                        'id' => '922231606374-3rds8m89kp5nsja298auia6ai0u27g0k.apps.googleusercontent.com',
                        'secret' => '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'
                    ) ,
                    'scope' => 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
                ) ,

                'Facebook' => array(
                    'enabled' => true,
                    'keys' => array(
                        'id' => '1500317803492251',
                        'secret' =>'c9a708c8a8b3240cde301f9581771ea2'
                    ) ,
                    'scope' => 'email, public_profile'
                ),

                'Twitter' => array(
                    'enabled' => false,
                    'keys' => array(
                        'key' => 'APP_KEY',
                        'secret' => 'APP_SECRET'
                    )
                )
            ) ,

            'hybrid_debug' => array(
                'debug_mode' => 'info', /* none, debug, info, error */
                'debug_file' => APPPATH . '/logs/log-' . date('Y-m-d') . '.php'
            )
        );

        return $config;
    }
?>