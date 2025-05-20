<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/* Set internal character encoding to UTF-8 */
mb_internal_encoding("UTF-8");

class Loader extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
    }

    /*
     * Load language javascript file
     */

    public function jsFile($file = null)
    {
        $contents = file_get_contents('.' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'language' . DIRECTORY_SEPARATOR . MY_LANGUAGE_FULL_NAME . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . $file);
        if (!$contents) {
            header('HTTP/1.1 404 Not Found');
            return;
        }
        header("Content-type: application/javascript; charset: UTF-8");
        echo $contents;
    }

    /*
     * Load css generated from administration -> styles
     */

    public function cssStyle()
    {
        $this->load->Model('admin/Home_admin_model');
        $style = $this->Home_admin_model->getValueStore('newStyle');
        if ($style == null) {
            $template = $this->template;
            $style = file_get_contents(VIEWS_DIR . $template . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'default-gradient.css');
            if (!$style) {
                header('HTTP/1.1 404 Not Found');
                return;
            }
        }
        header("Content-type: text/css; charset: UTF-8");
        echo $style;
    }

}
