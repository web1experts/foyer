<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookmarkuserscontroller extends CI_Controller {
	public $result_header, $result_footer, $result_email, $result_page, $page_menus, $result_bookmark;
	function __construct($result_header = null, $result_footer = null, $result_email = null, $result_page = null) {
		parent::__construct();
		if (!is_logged_in()) {
			redirect('/login');
		}
		$this->load->library('session');
		$this->load->model('super_dbmodel');

		$this->result_header = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'header'));
		$this->result_footer = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'footer'));
		$this->result_email = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'forgot_password'));
		$this->result_bookmark = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'bookmark_background'));
		$this->result_page = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'page_settings'));
		$this->page_menus  = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'menu_text'));
	}

	public function index() {

		// print_r($_REQUEST);die();
		$user_data = $this->session->userdata('login_auth');
		if (@$user_data->user_id != "") {
			$user_id = $user_data->user_id;
		} else {
			$user_id = "no";
		}


		//Default Order of Company, Team and User Tabs and its data

		if ($user_data->user_role == "1") {
			$cmp_data = $this->super_dbmodel->get_sort_data_where("company", "*,cmp_thumb as thumb,cmp_nick_title as name", array('cmp_nick_title' => 'asc'), array('type'=> 'page'));
			$team_data = $this->super_dbmodel->get_sort_data_where("teams", "*", array('nick_title' => 'asc'), array('type'=> 'page'));
			
		} else {
			
			$cmp_data = $this->super_dbmodel->get_sort_data_like_all("company", "*,cmp_nick_title as name,cmp_thumb as thumb", ['user_id' => $user_id], array('cmp_nick_title' => 'asc'), array('type'=> 'page'));
			$team_data = $this->super_dbmodel->get_sort_data_like_all("teams", "*", ['user_ids' => $user_id], array('nick_title' => 'asc'), array('type'=> 'page'));
		}



		$bookmarks_tab_order = getset_meta('user_meta', 'select', $user_data->user_id, 'tabs_order', null);


		$bookmark_user_data = $explode_bookmark = $sorted_tabs_arr = array();
		$companytab = $teamtab = $customtab = array();

		// echo "<pre>";
		// print_r($cmp_data);
		// die();


		if (isset($bookmarks_tab_order) && !empty($bookmarks_tab_order)) {
			$companyids = $team_ids = $tabsids = array();
			$tabs_order = getset_meta('user_meta', 'get', @$user_data->user_id, 'tabs_order');

			if (is_array($tabs_order) && !empty($tabs_order)) {
				for ($i = 0; $i < count($tabs_order); $i++) {
					$tabs = explode('&', $tabs_order[$i]);
					if (strtolower($tabs[0]) == 'company') {
						array_push($companyids, $tabs[1]);
					} elseif (strtolower($tabs[0]) == 'team') {
						array_push($team_ids, $tabs[1]);
					} elseif (strtolower($tabs[0]) == 'tabs') {
						array_push($tabsids, $tabs[1]);
					} else {
						$usertab = $i;
					}
				}
				if (isset($companyids) && !empty($companyids)) {
					$companytab = $this->super_dbmodel->get_wherein_data("company", "cmp_id as id,cmp_nick_title as name,cmp_thumb as thumb,graphic_id", "cmp_id", $companyids);

					foreach ($companytab as $key => $csm) {

						$companytab[$key]['type'] = 'company';
					}
				}


				if (isset($team_ids) && !empty($team_ids)) {
					$teamtab = $this->super_dbmodel->get_wherein_data("teams", "id,nick_title as name, thumb,graphic_id", "id", $team_ids);

					foreach ($teamtab as $key => $csm) {
						$teamtab[$key]['type'] = 'team';
					}
				}


				if (isset($tabsids) && !empty($tabsids)) {

					$customtab = $this->super_dbmodel->get_wherein_data("tabs", "id, sub_title as name", "id", array_unique($tabsids));

					foreach ($customtab as $key => $csm) {
						$customtab[$key]['type'] = 'tabs';
					}
				}

				$combined_arr = array_merge($companytab, $teamtab, $customtab, [0 => ['id' => $user_id, 'name' => @$user_data->user_fname . ' ' . @$user_data->user_lname, 'type' => 'user', 'thumb' => @$user_data->user_thumb_img]]);

				for ($i = 0; $i < count($tabs_order); $i++) {
					$tabs = explode('&', $tabs_order[$i]);
					foreach ($combined_arr as $k => $d) {
						if (strtolower($tabs[0]) === $d['type'] && $tabs[1] === $d['id']) {
							$sorted_tabs_arr[$i] = $combined_arr[$k];
						}
						continue;
					}
				}


				

			}

			//echo "<pre>";print_r($sorted_tabs_arr);die;
			$bookmark_first_tab = $bookmarks_tab_order[0];
			$explode_bookmark = explode("&", $bookmark_first_tab);
			$type = $explode_bookmark[0];
			$type_id = $explode_bookmark[1];
			$meta_key = "bookmarks_" . $type . "_" . $type_id;

			$bookmark_user_data = $this->super_dbmodel->get_where_data_first("user_meta", "*", array('meta_key' => $meta_key));
		}


		if (isset($bookmark_user_data) && !empty($bookmark_user_data)) {
			$meta_value = unserialize($bookmark_user_data->meta_value);
			$bookmark_user_data = $this->super_dbmodel->get_user_meta("bookmarks", $meta_value);
		} else {
			if (isset($explode_bookmark) && !empty($explode_bookmark)) {
				$type = $explode_bookmark[0];
				if ($type == "company") {
					$bookmark_user_data = array();
					if (isset($cmp_data) && !empty($cmp_data)) {
						$cmp_id = $cmp_data[0]['cmp_id'];


						$bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("company_id" => $cmp_id));
					}
				} else if ($type == "team") {
					$bookmark_user_data = array();
					if (isset($team_data) && !empty($team_data)) {
						$team_id = $team_data[0]['id'];
						$bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("team_id" => $team_id));
					}
				}
			} else {
				$bookmark_user_data = array();
				if (isset($cmp_data) && !empty($cmp_data)) {
					$cmp_id = $cmp_data[0]['cmp_id'];
					$bookmark_user_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("company_id" => $cmp_id));
				}
			}
		}

		/*echo "<pre>";
        print_r($bookmark_user_data);die();*/

		//$team_data = $this->super_dbmodel->get_sort_data_like_all("teams", "*", array('user_ids'=> $user_id),array('nick_title'=>'asc'));
		$usertabs = $this->super_dbmodel->get_sort_data_like_all("tabs", "*", array('user_id' => $user_id), array('sub_title' => 'asc'));
		$graphics = $this->super_dbmodel->get_datawith_limit('graphics', '*', 25, 0);

		$subtabs_result = $this->super_dbmodel->get_where_data_first("settings", "*", array('meta_key' => "subtabs"));



		$session_data = $this->session->userdata('login_auth');

		// echo "<pre>";
		// print_r($sorted_tabs_arr);
		// die();

		$this->load->view('user/header', array('page_title' => 'Foyer | Hagan Realty', 'page_activate' => 'home', 'result_header' => $this->result_header, 'cmp_data' => $cmp_data, 'team_data' => $team_data, 'menus' => $this->page_menus, 'sorted_tabs_arr' => $sorted_tabs_arr, 'usertabs' => $usertabs, "subtabs"=> $subtabs_result));

		$this->load->view('user/index', array('bookmark_data' => $bookmark_user_data, 'graphics' => $graphics, 'total_graphics' => $this->super_dbmodel->count_data('graphics', 'id', "NULL")));

		$this->load->view('user/footer', array('result_header' => $this->result_header, 'result_footer' => $this->result_footer, 'result_fg' => $this->result_email, 'result_page' => $this->result_page, 'result_bookmark' => $this->result_bookmark));
	}

	public function bookmark_ajax() {
		extract($_REQUEST);
		$user_data = $this->session->userdata('login_auth');

		$count_subtabs=0;
		//Exists Meta
		if ($type == 'company') {
			$meta_value = 'bookmarks_company_' . $type_id;
			$where = array(
				'post_id' => $type_id,
				'meta_key' => $meta_value
			);
			$table = 'company_meta';

			$subtb= "company";
			$subtb_id="cmp_id as id";

			$subtab_data = $this->super_dbmodel->get_sort_where_data_like($subtb, "*", array('cmp_nick_title' => 'asc'), array("subtabs_parent_ids" => $type_id));

			$count_subtabs=count($subtab_data);
		} elseif ($type == 'team') {
			$meta_value = 'bookmarks_team_' . $type_id;
			$where = array(
				'post_id' => $type_id,
				'meta_key' => $meta_value
			);
			$table = 'team_meta';

			$subtb= "teams";
			$subtb_id="id";

			$subtab_data = $this->super_dbmodel->get_sort_where_data_like($subtb, "*", array('nick_title' => 'asc'), array("subtabs_parent_ids" => $type_id));
			$count_subtabs=count($subtab_data);
		} else if ($type == 'tabs') {
			$meta_value = 'bookmarks_' . $type . '_' . $type_id;
			$table = 'tab_meta';
			$where = array(
				'post_id' => $type_id,
				'meta_key' => $meta_value
			);
		} else {
			$meta_value = 'bookmarks_' . $type . '_' . $type_id;
			$where = array(
				'user_id' => $user_data->user_id,
				'meta_key' => $meta_value
			);
			$table = 'user_meta';
		}

		if($count_subtabs==0) {

			$columns = "*";

			$metacount = $this->super_dbmodel->count_where_data($table, $columns, $where);


			if ($type == "company") {
				if ($metacount != 0) {
					$bookmarks_sort_id = getset_meta($table, 'select', $type_id, 'bookmarks_company_' . $type_id, $order = null);
					$bookmark_data = $this->super_dbmodel->get_user_meta("bookmarks", $bookmarks_sort_id);
					$message = "company meta data retrieve successfully";
				} else {
					$bookmark_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("company_id" => $type_id));
					$message = "company bookmark retrieve successfully";
				}
			} else if ($type == "team") {
				if ($metacount != 0) {
					$bookmarks_sort_id = getset_meta($table, 'display', $type_id, 'bookmarks_team_' . $type_id, $order = null);
					$bookmark_data = $this->super_dbmodel->get_user_meta("bookmarks", $bookmarks_sort_id);
					$message = "Team meta data retrieve successfully";
				} else {
					$bookmark_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("team_id" => $type_id));
					$message = "Team bookmark retrieve successfully";
				}
			} else if ($type == "user") {
				if ($metacount != 0) {
					$bookmarks_sort_id = getset_meta($table, 'display', $user_data->user_id, 'bookmarks_' . $type . '_' . $type_id, $order = null);
					$bookmark_data = $this->super_dbmodel->get_user_meta("bookmarks", $bookmarks_sort_id);
					$message = "user meta data retrieve successfully";
				} else {
					$bookmark_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("user_id" => $type_id));
					$message = "User bookmark retrieve successfully";
				}
			} else if ($type == "tabs") {
				if ($metacount != 0) {
					$bookmarks_sort_id = getset_meta($table, 'display', $type_id, 'bookmarks_' . $type . '_' . $type_id, $order = null);

					$bookmark_data = $this->super_dbmodel->get_user_meta("bookmarks", $bookmarks_sort_id);
					$message = "user meta data retrieve successfully";
				} else {
					$bookmark_data = $this->super_dbmodel->get_sort_where_data_like("bookmarks", "*", array('name' => 'asc'), array("tab_id" => $type_id));
					$message = "Tabs bookmark retrieve successfully";
				}
			}
		}


		if (isset($bookmark_data) && !empty($bookmark_data)) {
			foreach ($bookmark_data as $k => $bookmark) {
				$bookmark_data[$k]['thumbnail'] = getGraphicsThumb($bookmark['graphic_id']);
			}
			$result = array(
				'code' => 200,
				'data' => $bookmark_data,
				'message' => $message
			);
		} else if(isset($subtab_data) && !empty($subtab_data)){
			$subtabs_result = $this->super_dbmodel->get_where_data_first("settings", "*", array('meta_key' => "subtabs"));

			$subtab_status="show";
			if(isset($subtabs_result) && !empty($subtabs_result)){
				if($subtabs_result->meta_value=="yes"){
					$subtab_status="hide";
				}
			}

			$message = "Subtabs retrieve successfully";
			foreach ($subtab_data as $j => $subtabs) {
				$subtab_data[$j]['thumbnail'] = getGraphicsThumb($subtabs['graphic_id']);
			}
			$result = array(
				'code' => 201,
				'sub_status'=> $subtab_status,
				'data' => $subtab_data,
				'type' => $type,
				'type_id' => $type_id,
				'message' => $message
			);

		} else {
			$result = array(
				'code' => 202,
				'sub_status'=> $subtab_status,
				'message' => "There is no bookmark associated onclick tab."
			);
		}

		echo json_encode($result);
		die();
	}

	public function save_bookmark() {
		extract($_REQUEST);
		//echo "<pre>";print_r($_REQUEST);die;
		$user_data = $this->session->userdata('login_auth');
		if ($datatype == 'company') {
			$meta_key = 'bookmarks_company_' . $active;
			$table = 'company_meta';
			$parent_id = $active;
		} elseif ($datatype == 'team') {
			$meta_key = 'bookmarks_team_' . $active;
			$table = 'team_meta';
			$parent_id = $active;
		} else if ($datatype == 'tabs') {
			$meta_key = 'bookmarks_' . $datatype . '_' . $active;
			$table = 'tab_meta';
			$parent_id = $active;
		} else {
			$meta_key = 'bookmarks_' . $datatype . '_' . $active;
			$table = 'user_meta';
			$parent_id = $user_data->user_id;
		}
		if (getset_meta($table, 'update', $parent_id, $meta_key, $order)) {
			$result = array('success_status' => 'Bookmark order updated successfully');
		} else {
			$result = array('error_status' => 'Failed to save Bookmark order');
		}

		echo json_encode($result);
		die();
	}

	public function manage_subtabs(){
		extract($_REQUEST);
		$update_array=array(
			"meta_value"=> $status
		);
		$query_result=$this->super_dbmodel->update_data("settings", $update_array, "set_id", 7);
        if($query_result=="success") {
        	$result=array(
        		"code"=> 200,
        		"display"=> $status,
        		"message"=> "Subtab status has been updated successfully"
        	);
        } else {
        	$result=array(
        		"code" => 202,
        		"message" => "Subtab status has been failed"
        	);
        }

        echo json_encode($result);
        die();
	}
}
