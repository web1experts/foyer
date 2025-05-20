<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tabcontroller extends CI_Controller
{
	public $result_header, $result_footer, $result_email, $result_page, $page_menus;
	function __construct($result_header = null, $result_footer = null, $result_email = null, $result_page = null)
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->model('data_table');
		$this->load->model('super_dbmodel');
		$this->load->model('super_insertmodel');
		$this->result_header = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'header'));
		$this->result_footer = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'footer'));
		$this->result_email = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'forgot_password'));
		$this->result_page = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'page_settings'));
		$this->page_menus  = $this->super_dbmodel->get_where_single_data("settings", "*", array('meta_key' => 'menu_text'));
	}

	public function save_tab()
	{
		extract($_REQUEST);

		$user_data = $this->session->userdata('login_auth');
		if ($type == "tab") {
			$user_data = $this->session->userdata('login_auth');
			$user_id = (isset($user_id) && !empty($user_id)) ? $user_id : $user_data->user_id;
			$parent_id = ($tab_for == 'user') ? $user_id : $team_id;
			$insert_array = ['title' => $tab_text, 'sub_title' => $tab_subtext, ($tab_for == 'user') ? 'user_id' : 'team_id' => $parent_id];
			if ($last_id = $this->super_insertmodel->insert_data_with_id("tabs", $insert_array)) {
				$old_usertabs = getset_meta('user_meta', 'get', $user_id, 'tabs_order');
				if ($old_usertabs !== false) {
					array_push($old_usertabs, 'tabs&' . $last_id . '&' . $tab_text);
					getset_meta('user_meta', 'update', $user_id, 'tabs_order', $old_usertabs);
				}
				$result = array('success_status' => 'Tab added successfully', 'last_id' => $last_id);
			} else {
				$result = array('error_status' => 'Failed to insert Tab data');
			}
		} else {
			if (!is_uploaded_file($_FILES['bookmark_image']['tmp_name']) && empty($icon_thumb) && empty($icon_path)) {
				$result = array('error_status' => 'Logo is required');
				echo json_encode($result);
				die();
			}

			if (is_uploaded_file($_FILES['bookmark_image']['tmp_name'])) {
				$thumb_image_path   = FILE_UPLOAD_PATH . '/graphics/thumbnail/';
				$image_path   = FILE_UPLOAD_PATH . '/graphics/';
				$image_nm = "bookmark_image";

				//Upload Helper
				$upload_image = Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path);

				if (isset($upload_image['error'])) {
					$result = array('error_status' => $upload_image['error']);
				} else {
					$original_nm = $upload_image['original_nm'];
					$thumb_nm = $upload_image['thumb_nm'];
					$insert_array = array(
						'name' => $bookmark_text,
						'company_id' => $company_id,
						'team_id' => $team_id,
						'url' => $bookmark_url,
						'image' => base_url('/assets/admin/upload/graphics/' . $original_nm),
						'thumb' => base_url('/assets/admin/upload/graphics/thumbnail/' . $thumb_nm),
						'user_id' => $user_id,
						'tab_id' => $tab_id
					);
					if ($this->super_insertmodel->insert_data("bookmarks", $insert_array)) {
						$result = array('success_status' => 'Bookmark added successfully');
					} else {
						$result = array('error_status' => 'Failed to insert Bookmark data');
					}

					echo json_encode($result);
					die();
				}
			} else {
				$original_nm = $icon_path;
				$thumb_nm = $icon_thumb;
				$insert_array = array(
					'name' => $bookmark_text,
					'company_id' => $company_id,
					'team_id' => $team_id,
					'url' => $bookmark_url,
					'image' => $original_nm,
					'thumb' => $thumb_nm,
					'user_id' => $user_id,
					'tab_id' => $tab_id
				);
				if ($this->super_insertmodel->insert_data("bookmarks", $insert_array)) {
					$result = array('success_status' => 'Bookmark added successfully');
				} else {
					$result = array('error_status' => 'Failed to insert Bookmark data');
				}

				echo json_encode($result);
				die();
			}
		}

		echo json_encode($result);
		die();
	}

	public function savetab_order()
	{
		extract($_REQUEST);
		if (!isset($user_id)) {
			$user_data = $this->session->userdata('login_auth');
			$user_id = $user_data->user_id;
		}


		if (getset_meta('user_meta', 'insert', $user_id, 'tabs_order', $order)) {
			$result = array('success_status' => 'Tab order updated successfully');
		} else {
			$result = array('error_status' => 'Failed to save Tab order');
		}
		echo json_encode($result);
		die();
	}

	public function managetabs()
	{
		$user_data = $this->session->userdata('login_auth');
		$user_id = (!isset($_REQUEST['user_id'])) ? $user_data->user_id : $_REQUEST['user_id'];
		$tabs_data = $this->super_dbmodel->get_where_data("tabs", "*", array('user_id' => $user_id));
		$users_data = $this->super_dbmodel->get_sort_data("register", "user_id,user_fname,user_lname", array('user_id' => 'desc'));

		$this->load->view('user/header', array('page_title' => 'Profile', 'meta_title' => 'profile', 'page_activate' => 'Profile', 'meta_description' => 'Profile | Manage Tabs', 'result_header' => $this->result_header, 'result_footer' => $this->result_footer, 'result_fg' => $this->result_email, 'result_page' => $this->result_page, 'menus' => $this->page_menus));
		$this->load->view('user/tabs/tabs', array('tabs_data' => $tabs_data));
		$this->load->view('user/footer', array('result_header' => $this->result_header, 'result_footer' => $this->result_footer, 'result_fg' => $this->result_email, 'result_page' => $this->result_page));
	}

	public function data_table_tabs()
	{
		$table_name = "tabs";
		$column_order = array(null, 'title', 'sub_title', 'user_id');
		$column_search = array('title', 'sub_title');
		$order = array('id ' => 'desc');
		$where_notin = array();

		$where = array();

		$data = $row = array();
		$i = $_POST['start'];
		$tabsData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

		foreach ($tabsData as $tab) {
			$i++;


			$tab_status = "";
			$edit_data = "editdata_" . $tab->id;

			//Users

			// if($tab->user_id!=""){

			//     $catalogue_data = $this->super_dbmodel->get_where_data("register", "user_fname,user_lname", ["user_id"=>$tab->user_id]);
			//     $users_array=array();
			//     foreach ($catalogue_data as $cats) {                    
			//         $username=$cats['user_fname']." ". $cats['user_lname'];
			//         $users_url=base_url("/admin/users");
			//         $users_array[]="<a href='javascript::void(0)' class='tab_applied btn btn-sm btn-info' title='$username'>$username</a>";
			//     }
			//     $usernm=implode(' ', $users_array);
			// } else {
			//     $usernm="-N/A-";
			// }

			$delete_data = "deletedata_" . $tab->id;
			$tab_status = "<a class='tack_data_$tab->id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='tabs' data-key='id' data-value='$tab->id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";
			$tab_status .= "<a href='javascript:void(0)' class='tack_data_$tab->id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='tabs' data-key='id' data-value='$tab->id'><i class='fa fa-times'></i></a>";

			$data[] = array($i, $tab->title, $tab->sub_title, $tab_status);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->data_table->countAll($table_name, $column_order, $column_search, $order, $where_notin, $where),
			"recordsFiltered" => $this->data_table->countFiltered($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where),
			"data" => $data,
		);

		// Output to JSON format
		echo json_encode($output);
	}

	public function edit_tab()
	{
		extract($_REQUEST);
		$edit_result = $this->super_dbmodel->get_where_single_data($tablenm, "*", array($table_key => $tab_id));
		echo json_encode($edit_result);
	}

	public function get_visibility()
	{
		$user_data = $this->session->userdata('login_auth');
		$where = array(
			'user_id' => $user_data->user_id
		);

		$userData = $this->super_dbmodel->get_where_single_data('register', "label_visibility", $where);
		echo json_encode($userData);
	}

	public function search_data(){
		

		$bookmark_data = '';
		$user_data = $this->session->userdata('login_auth');
		$userid=$user_data->user_id;
		$where = array(
			'user_id' => $userid
		);
		$userData = $this->super_dbmodel->get_where_single_data('register', "label_visibility", $where);

		$final_bookarr= $bookmarks =array();


		//Company Meta
		$company_users = $this->super_dbmodel->search_likes('company', 'user_id', $userid, "no");		

		foreach($company_users as $cmp){
			$cmp_ids=$cmp['cmp_id'];
			$meta_key="bookmarks_company_$cmp_ids";
			$bookmarks[]=getset_meta("company_meta", "get", $cmp_ids ,$meta_key, $value = null);
		}		



		//Team Meta
		$teams_users = $this->super_dbmodel->search_likes('teams', 'user_ids', $userid, "no");

		

		foreach($teams_users as $teem):
			$teams_ids=$teem['id'];
			$meta_key="bookmarks_team_$teams_ids";
			//if($teams_ids==12){
				$bookmarks[]=getset_meta("team_meta", "get", $teams_ids ,$meta_key, $value = null);
			//}
		endforeach;

		





		//Tab Meta
		$tabs_users = $this->super_dbmodel->search_likes('tabs', 'user_id', $userid, "no");
		foreach($tabs_users as $tabs):
			$tabs_ids=$tabs['id'];

			$meta_key="bookmarks_tab_$tabs_ids";
			$bookmarks[]=getset_meta("tab_meta", "get", $teams_ids ,$meta_key, $value = null);
		endforeach;


		//User Meta
		$meta_key="bookmarks_user_$userid";
		$bookmarks[]=getset_meta("user_meta", "get", $userid ,$meta_key,$value = null);
		

		

		foreach($bookmarks as $bookmks){
			if(isset($bookmks) && !empty($bookmks)){
				array_unique($bookmks);
				foreach(@$bookmks as $bkmks){
					$final_array[]=$bkmks;
				}
			}
		}	
		

		/*echo "<pre>";
		print_r($final_array);
		die();*/

		$label_visual= $userData->label_visibility;

		$bookmarks = $this->super_dbmodel->search_likes('bookmarks', 'name', $_GET['keyword'], $final_array);

		/*echo "<pre>";
		print_r($bookmarks);
		die();*/

		/*$tabs = $this->super_dbmodel->search_likes('tabs', 'title', $_GET['keyword']);
		$companies = $this->super_dbmodel->search_likes('company', 'cmp_text', $_GET['keyword']);
		$teams = $this->super_dbmodel->search_likes('teams', 'name', $_GET['keyword']);
		$bookmark_data = '';
		$tabs_data = '';*/

		/*echo "<pre>";
		print_r($bookmarks);
		die();*/

		foreach ($bookmarks as $kay => $value) {
			$tbname=$mytabs =array();
			$graphic_id=$value['graphic_id'];
			$tabs_id=explode(", ", $value['tab_id']);

			
			$cmpids=explode(", ", $value['company_id']);

			$tmids=explode(", ", $value['team_id']);

			$usids=explode(", ", $value['user_id']);
			
			
			$where_img = array(
				'id' => $graphic_id
			);
			$graphic = $this->super_dbmodel->get_where_single_data('graphics', "*", $where_img);

			$tabs_nm = $this->super_dbmodel->get_wherein_data_groupbywhere("tabs", "title", "id", $tabs_id, $where);

			//Company
			$cmpnm = $this->super_dbmodel->get_wherein_data_groupby("company", "cmp_nick_title", "cmp_id", $cmpids);

			$tmnm = $this->super_dbmodel->get_wherein_data_groupby("teams", "nick_title", "id", $tmids);

			$ustbs = $this->super_dbmodel->get_wherein_data_groupbywhere("register", "user_fname, user_lname", "id", $usids, $where);



			$tbname=array();
			$mytabs="";

			if(isset($tabs_nm) && !empty($tabs_nm)){
				foreach($tabs_nm as $tbnm){
					$tbname[]=$tbnm['title'];
				}
				
			}


			if(isset($cmpnm) && !empty($cmpnm)){
				foreach($cmpnm as $cpnm){
					$tbname[]=$cpnm['cmp_nick_title'];
				}				
			}


			if(isset($tmnm) && !empty($tmnm)){
				foreach($tmnm as $tnm){
					$tbname[]=$tnm['nick_title'];
				}				
			}

			if(isset($ustbs) && !empty($ustbs)){
				foreach($ustbs as $utb){
					$tbname[]=$utb['user_fname']." ". $utb['user_lname'];
				}				
			}
			


			if(isset($tbname) && !empty($tbname)){
				$mytabs= implode(", ", $tbname);
			}

			$img = !empty($graphic->thumb) ? $graphic->thumb : "https://lanecdr.org/wp-content/uploads/2019/08/placeholder.png";
			
			if($label_visual==0){
				$bookmark_data .= "<div class='col-md-2' id=''><a target='_blank' href=' $value[url]'><img class='h-50' src='$img' alt='$value[name]'>";

				if(isset($tbname) && !empty($tbname)){
					$bookmark_data .="<p>".$value['name']."<span><strong>Tabs</strong>: ".$mytabs."</span></p>";
				} else {
					$bookmark_data .="<p>$value[name]</p>";
				}
				$bookmark_data .="</a></div>";
			} else {
				$bookmark_data .= "<div class='col-md-2'><a target='_blank' href=' $value[url]'><img class='h-50' src='$img' alt='$value[name]'></a></div>";
			}
		}
		

		if(!empty($bookmark_data)){
			$output = array(
				'bookmarks' => '<div class="row">' . $bookmark_data . '</div>'
			);
		} 
		echo json_encode($output);
	}


	public function update_tab()
	{
		extract($_REQUEST);
		$tab_text = $this->input->post('tab_text');
		$nick_title = $this->input->post('tab_subtext');
		$edit_id = $this->input->post('edit_id');

		$update_array = array(
			'title' => $tab_text,
			'sub_title' => $nick_title,
		);
		$where = array(
			'id' => $edit_id
		);

		if ($this->super_insertmodel->update_table_data($where, $update_array, 'tabs')) {
			$result = array('success_status' => 'Tab updated successfully');
		} else {
			$result = array('error_status' => 'Failed to update Tab data');
		}
		echo json_encode($result);
		die();
	}
}
