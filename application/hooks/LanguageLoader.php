<?php
class LanguageLoader
{
    function initialize() {
        $ci =& get_instance();
        $ci->load->helper('language');

        $siteLang = $ci->session->userdata('site_lang');
        
        if ($siteLang) {
            $ci->lang->load('site', $siteLang);
            $ci->lang->load('form_validation', $siteLang);
            //$ci->lang->load('db', $siteLang);
            $ci->lang->load('email', $siteLang);
        } else {              
            $ci->lang->load('site','english');
            $ci->lang->load('form_validation', 'english');
            //$ci->lang->load('db', 'english');
            $ci->lang->load('email', 'english');
        }
    }
}