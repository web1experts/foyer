<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settingscontroller extends CI_Controller
{

	function __construct()
	{
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
	public function index()
	{
		$result_header = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'header'));
		$result_footer = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'footer'));
		$result_bookmark = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'bookmark_background'));
		$result_email = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'forgot_password'));
		$result_page = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'page_settings'));
		$this->load->view('admin/header', array('site_header' => 'Settings'));
		$this->load->view('admin/sidebar', array('site_menu' => 'settings'));
		$this->load->view('admin/setting/settings.php', array('result_header' => $result_header, 'result_footer' => $result_footer, 'result_fg' => $result_email, 'result_page' => $result_page, 'result_bookmark' => $result_bookmark));
		$this->load->view('admin/footer');
	}


	public function insert_settings()
	{
		// echo "<pre>";
		// print_r($_REQUEST);
		// print_r($_FILES);
		// die;
		extract($_REQUEST);

		/*echo "<pre>";
        print_r($_REQUEST);
        die();*/

		$save_array = $inner_array = $file_array = $file_array1 = $array_merge = array();

		$type = $this->input->post('type');

		$thumb_image_path   = FILE_UPLOAD_PATH . '/settings/thumbnail/';
		$image_path   = FILE_UPLOAD_PATH . '/settings/';

		if ($type == "header") {
		    $logo = '';$fav_img = '';
			$update_id = $this->input->post('header_edit');
            if(isset($_FILES['logo_files'])){
    			$logo = $_FILES['logo_files']['tmp_name'];
            }
            if(isset($_FILES['favicon_files'])){
    			$fav_img = $_FILES['favicon_files']['tmp_name'];
            }

			foreach ($_REQUEST as $key => $value) {
				if ($key != "type" && $key != "header_edit") {
					$inner_array[$key] = $value;
					$array_merge = $inner_array;
				}
			}

			if ($logo != "") {
				$logo_nm = 'logo_files';
				$upload_image = Upload_imagewith_thumb($logo_nm, $image_path, $thumb_image_path);

				if (isset($upload_image['error'])) {
					$this->session->set_flashdata('setting_errors', $upload_image['error']);
					header('location:' . $_SERVER['HTTP_REFERER']);
					die();
				} else {
					$original_nm = $upload_image['original_nm'];
					$thumb_nm = $upload_image['thumb_nm'];

					$file_array = array(
						'logo_image' => $original_nm,
						'logo_thumb' => $thumb_nm
					);
					$array_merge = array_merge($inner_array, $file_array);
				}
			}

			if ($fav_img != "") {
				$fav_nm = 'favicon_files';
				$upload_thumb_image = Upload_imagewith_thumb($fav_nm, $image_path, $thumb_image_path);

				if (isset($upload_thumb_image['error'])) {
					$this->session->set_flashdata('setting_errors', $upload_thumb_image['error']);
					header('location:' . $_SERVER['HTTP_REFERER']);
					die();
				} else {
					$fav_nm = $upload_thumb_image['original_nm'];
					$favthumb_nm = $upload_thumb_image['thumb_nm'];

					$file_array1 = array(
						'fav_image' => $fav_nm,
						'fav_thumb' => $favthumb_nm
					);
					$array_merge = array_merge($array_merge, $file_array1);
				}
			}
			$save_array = $array_merge;
		} else if ($type == "footer") {
			$update_id = $this->input->post('footer_edit');
			foreach ($_REQUEST as $key => $value) {
				if ($key != "type" && $key != "footer_edit") {
					$inner_array[$key] = $value;
					$array_merge = $inner_array;
				}
			}

			$save_array = $array_merge;
		} else if ($type == "forgot_password") {
			$save_array = $_POST;
			$update_id = $_POST['fg_edit'];
		} else if ($type == "page_settings") {
			$save_array = $_POST;
			$update_id = $_POST['fg_edit'];
		} else if ($type == "bookmark_background") {
			if (isset($_POST['background_edit'])) {
				$update_id = $_POST['background_edit'];
			}


			$where = array(
				'user_id' => $user_data->user_id
			);

			if (isset($_POST['label_visibility'])) {
				$update_array = array(
					'label_visibility' => 1
				);
			} else {
				$update_array = array(
					'label_visibility' => 0
				);
			}
			$this->super_insertmodel->update_table_data($where, $update_array, 'register');

			if (is_uploaded_file($_FILES['favicon_files']['tmp_name'])) {
				$logo_nm = 'favicon_files';
				$upload_image = Upload_imagewith_thumb($logo_nm, $image_path, $thumb_image_path);

				if (isset($upload_image['error'])) {
					$this->session->set_flashdata('setting_errors', $upload_image['error']);
					header('location:' . $_SERVER['HTTP_REFERER']);
					die();
				} else {
					$original_nm = $upload_image['original_nm'];
					$thumb_nm = $upload_image['thumb_nm'];

					$save_array = array(
						'logo_image' => $original_nm,
						'logo_thumb' => $thumb_nm
					);
				}
			} else {
				$save_array = array(
					'logo_image' => $logo_image,
					'logo_thumb' => $logo_thumb
				);
			}



			if (is_uploaded_file($_FILES['login_file']['tmp_name'])) {
				$logo_nm = 'login_file';
				$upload_image = Upload_imagewith_thumb($logo_nm, $image_path, $thumb_image_path);

				if (isset($upload_image['error'])) {
					$this->session->set_flashdata('setting_errors', $upload_image['error']);
					header('location:' . $_SERVER['HTTP_REFERER']);
					die();
				} else {
					$original_nm = $upload_image['original_nm'];
					$thumb_nm = $upload_image['thumb_nm'];

					$loginimage_array = array(
						'login_image' => $original_nm,
						'login_thumb' => $thumb_nm
					);
				}
			} else {
				$loginimage_array = array(
					'login_image' => $login_image,
					'login_thumb' => $login_thumb
				);
			}
		}

		$bookmark_setting = (isset($loginimage_array)) ? array_merge($save_array, $loginimage_array): $save_array;



		$json_array = json_encode($bookmark_setting);
		if (isset($save_array) && !empty($save_array)) {
			$insert_array = array(
				'meta_key' => $type,
				'meta_value' => $json_array
			);

			if (isset($update_id)) {
				$update_sts = $this->super_dbmodel->update_data('settings', $insert_array, 'set_id', $update_id);
				$this->session->set_flashdata('setting_success', "Seting updated");
				header('location:' . $_SERVER['HTTP_REFERER']);
			} else {

				$last_inserted_id = $this->super_insertmodel->insert_data_with_id('settings', $insert_array);
				$this->session->set_flashdata('setting_success', "Seting saved");
				header('location:' . $_SERVER['HTTP_REFERER']);
			}
		} else {
			$this->session->set_flashdata('setting_errors', "Please change one or more fields");
			header('location:' . $_SERVER['HTTP_REFERER']);
		}
	}
}
