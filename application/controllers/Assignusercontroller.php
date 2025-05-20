<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assignusercontroller extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!is_logged_in() || !check_admin()) {
			redirect('/login');
		}
		$this->load->library("session");
		$this->load->model('data_table');
		$this->load->model('super_dbmodel');
		$this->load->model('super_insertmodel');
	}

	public function index() {
		$session_data = $this->session->userdata('login_auth');
		$admin_id = $session_data->user_id;
		$order = array(
			'user_id' => 'desc'
		);
		$where = array(
			'user_id' => $admin_id
		);

		$users_data = $this->super_dbmodel->get_wherenot_datatable_order("register", "*", $where, $order);
		//print_r($users_data);die;
		$selected_company = $this->super_dbmodel->get_where_data_first('company','cmp_text',['cmp_id'=>$_REQUEST['company']]);
		$this->load->view('admin/header', array('site_header' => 'Company Users'));
		$this->load->view('admin/sidebar', array('site_menu' => 'companies'));
		$this->load->view('admin/assignusers/users.php', array('users_data' => $users_data,'selected_company'=>$selected_company));
		$this->load->view('admin/footer');
	}


	public function teamtocompany()
	{
		$session_data = $this->session->userdata('login_auth');
		$admin_id = $session_data->user_id;
		$order = array(
			'user_id' => 'desc'
		);
		$where = array(
			'user_id' => $admin_id
		);

		$users_data = $this->super_dbmodel->get_wherenot_datatable_order("register", "*", $where, $order);
		//print_r($users_data);die;
		$selected_company = $this->super_dbmodel->get_where_data_first('company','cmp_text',['cmp_id'=>$_REQUEST['company']]);
		$this->load->view('admin/header', array('site_header' => 'Company Users'));
		$this->load->view('admin/sidebar', array('site_menu' => 'companies'));
		$this->load->view('admin/assignusers/users.php', array('users_data' => $users_data,'selected_company'=>$selected_company));
		$this->load->view('admin/footer');
	}

	public function team()
	{
		$session_data = $this->session->userdata('login_auth');
		$admin_id = $session_data->user_id;
		$order = array(
			'user_id' => 'desc'
		);
		$where = array(
			'user_id' => $admin_id
		);
		$users_data = $this->super_dbmodel->get_wherenot_datatable_order("register", "*", $where, $order);
		//print_r($users_data);die;
		$selected_team = $this->super_dbmodel->get_where_data_first('teams','name',['id'=>$_REQUEST['team']]);
		$this->load->view('admin/header', array('site_header' => 'Team Users'));
		$this->load->view('admin/sidebar', array('site_menu' => 'teams'));
		$this->load->view('admin/assignusers/team.php', array('users_data' => $users_data,'selected_team'=>$selected_team));
		$this->load->view('admin/footer');
	}

	public function assignteam()
	{
		$session_data = $this->session->userdata('login_auth');
		$admin_id = $session_data->user_id;
		

		$teams_data = $this->super_dbmodel->count_data("teams", "*", "NULL");
		//print_r($users_data);die;
		$selected_company = $this->super_dbmodel->get_where_data_first('company','cmp_text',['cmp_id'=>$_REQUEST['company']]);
		$this->load->view('admin/header', array('site_header' => "Company's Teams"));
		$this->load->view('admin/sidebar', array('site_menu' => 'companies'));
		$this->load->view('admin/assignusers/teamTable.php', array('teams_data' => $teams_data,'selected_company'=>$selected_company));
		$this->load->view('admin/footer');
	}

	public function assignteamtousers()
	{
		$session_data = $this->session->userdata('login_auth');
		$admin_id = $session_data->user_id;
		

		$teams_data = $this->super_dbmodel->count_data("teams", "*", "NULL");
		//print_r($users_data);die;
		$selected_user = $this->super_dbmodel->get_where_data_first('register','user_fname,user_lname',['user_id'=>$_REQUEST['user']]);
		$this->load->view('admin/header', array('site_header' => "User's Teams"));
		$this->load->view('admin/sidebar', array('site_menu' => 'users'));
		$this->load->view('admin/assignusers/userteam', array('teams_data' => $teams_data,'selected_user'=>$selected_user));
		$this->load->view('admin/footer');
	}

	public function data_table_users()
	{

		$company_id = $_REQUEST['company_id'];
		$tb = $_REQUEST['tb'];
		if ($tb == "company") {
			$id = "cmp_id";
			$text="Company";
		}
		else if ($tb == "teams") {
			$id = "id";
			$update_col = "user_ids";
			$text="Team";
		} else {
			$id = "id";
		}

		$assigned_users = $this->super_dbmodel->get_where_single_data($tb, "*", array($id => $company_id));

		$assigned_userids = ($tb == "teams") ? $assigned_users->user_ids : $assigned_users->user_id;
		$exp_users = array();
		if (isset($assigned_userids)) {
			$exp_users = explode(',', $assigned_userids);
		}


		$session_data = $this->session->userdata('login_auth');
		$admin_id = $session_data->user_id;
		$table_name = "register";
		$column_order = array(null, 'user_img', 'user_fname', 'user_lname', 'user_email', 'created_date');
		$column_search = array('user_fname', 'user_lname', 'user_email');
		$order = array('user_fname' => 'asc');
// 		$where_notin = array(
// 			'user_id' => $admin_id
// 		);
        $where_notin = [];
		$where = array();

		$data = $row = array();
		$i = $_POST['start'];
		$memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

		foreach ($memData as $member) {
			$i++;
			$created = date('M d, Y H:i:s', strtotime($member->created_date));

			$user_img = "";
			if ($member->user_identify == 1) {
				$user_img = "<img src='" . $member->user_social_img . "'/>";
			} else {
				if ($member->user_img == "") {
					$no_img = base_url('assets/images/blank_img.png');
					$user_img = "<img height='96px' src='" . $no_img . "'/>";
				} else {
					$user_uploaded_img = base_url('assets/admin/upload/users/thumbnail/');
					$user_img = "<img height='96px' src='" . $user_uploaded_img . $member->user_thumb_img . "'/>";
				}
			}

			$member_status = "";
			$edit_data = "editdata_" . $member->user_id;
			$delete_data = "deletedata_" . $member->user_id;

			$status = "";
			if ($member->status == 1) {
				$status = "<a href='javascript:void(0)' class='btn btn-sm btn-success'>Active</a>";
			} else {
				$status = "<a href='javascript:void(0)' class='btn btn-sm btn-danger'>Deactive</a>";
			}

			$member_tabs = "<a href='".base_url('/admin/tabs/bookmarks')."?p_id=".base64_encode($member->user_id)."&tab=user' class='btn btn-warning btn-sm' title='tabs' data-trigger='hover' id='$member->user_id' data-id='$member->user_id'>View</a>&nbsp;";
			if (in_array($member->user_id, $exp_users)) {
				$member_status = "<a class='admin_action tack_data_$member->user_id btn1 btn-danger btn-sm' href='javascript:void(0)' id='$edit_data' data-title='remove' title='Click to un-assigned for company' data-value='$member->user_id'>Remove from $text</a>";
			} else {
				$member_status = "<a class='admin_action tack_data_$member->user_id btn1 btn-success btn-sm' href='javascript:void(0)' id='$edit_data' data-title='add' title='Click to assigned for company' data-value='$member->user_id'>Assign to $text</a>";
			}

			$data[] = array($i, $user_img, ucfirst($member->user_fname) . " " . ucfirst($member->user_lname), $member->user_email, $status, $member_tabs, $member_status);
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

	public function data_table_team()
	{	

		$table_name = "teams";
		$column_order = array(null, 'name', 'team_desc', 'logo', 'created_at');
		$column_search = array('name', 'nick_title', 'team_desc');
		$order = array('id ' => 'desc');
		$where_notin = array();

		$where = array();

		$data = $row = array();
		$i = $_POST['start'];
		$teamData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

		foreach ($teamData as $team) {
			$i++;
			$created = date('M d, Y', strtotime($team->created_at));
			$graphicsDetail = $this->super_dbmodel->get_where_single_data('graphics', "*", array('id' => $team->graphic_id));
			$catalogue_img = "";
			if (!empty($team->graphic_id)) {
				if(isset($graphicsDetail->thumb)){
					$user_uploaded_img = $graphicsDetail->thumb;
					$team_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
					
				} else {
					$no_img = base_url('assets/images/slider_blank.png');
					$team_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
				}
			} else {
				$no_img = base_url('assets/images/slider_blank.png');
				$team_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
			}

			$userassign_url = base_url('admin/assign-team/') . "?team=" . $team->id ."&tb=teams";

			$edit_data = "editdata_" . $team->id;
		
			
			$assigned_team = $this->super_dbmodel->get_where_single_data($_REQUEST['tb'], "*", array('cmp_id' => $_REQUEST['company_id']));
			// echo $assigned_team->team; echo $team->id;die;
			if(!empty($assigned_team->team) && strpos($assigned_team->team,$team->id ) !== false){
				$usernm = "<a class='admin_action tack_data_$assigned_team->cmp_id btn1 btn-danger btn-sm' href='javascript:void(0)' id='$edit_data' data-title='remove' data-id='$team->id' title='Click to un-assigned for company' data-value='$team->id'>Unassigned</a>";
			}else{
				$usernm = "<a class='admin_action tack_data_ btn1 btn-success btn-sm' href='javascript:void(0)' id='$edit_data' data-id='$team->id' data-title='add' title='Click to assigned for company' data-value='$team->id'>Assign</a>";
			}
			


			$team_status = "";

			//team
			$team_nm = $team->id;
			$explode_ids = explode(', ', $team_nm);

			$delete_data = "deletedata_" . $team->id;	
				

			$team_status .= "<a class='tack_data_$team->id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='teams' data-key='id' data-value='$team->id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";



			$team_tabs = "<a href='" . base_url('/admin/tabs/bookmarks') . "?p_id=" . base64_encode($team->id) . "&tab=team' class='btn btn-warning btn-sm' title='tabs' data-trigger='hover' id='$team->id' data-id='$team->id'>Edit</a>&nbsp;";
			
			$team_status .= "<a href='javascript:void(0)' class='tack_data_$team->id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='teams' data-key='id' data-value='$team->id'><i class='fa fa-times'></i></a>";

			$data[] = array($i, $team_img, $team->name, $team->nick_title, $team->team_desc, $usernm, $team_tabs,  $team_status);
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


	public function data_table_userteam()
	{	

		$table_name = "teams";
		$column_order = array(null, 'name', 'team_desc', 'logo', 'created_at');
		$column_search = array('name', 'nick_title', 'team_desc');
		$order = array('id ' => 'desc');
		$where_notin = array();

		$where = array();

		$data = $row = array();
		$i = $_POST['start'];
		$teamData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

		foreach ($teamData as $team) {
			$i++;
			$created = date('M d, Y', strtotime($team->created_at));

			$graphicsDetail = $this->super_dbmodel->get_where_single_data('graphics', "*", array('id' => $team->graphic_id));
			$catalogue_img = "";
			if (!empty($team->graphic_id)) {
				if(isset($graphicsDetail->thumb)){
					$user_uploaded_img = $graphicsDetail->thumb;
					$team_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
					
				} else {
					$no_img = base_url('assets/images/slider_blank.png');
					$team_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
				}
			} else {
				$no_img = base_url('assets/images/slider_blank.png');
				$team_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
			}


			$userassign_url = base_url('admin/assign-team/') . "?team=" . $team->id ."&tb=teams";

			$edit_data = "editdata_" . $team->id;
			
			$userids = explode(', ',$team->user_ids);
			
			if(!empty($userids) && in_array($_REQUEST['user_id'],$userids )){
				$usernm = "<a class='admin_action tack_data_$team->id btn1 btn-danger btn-sm' href='javascript:void(0)' id='$edit_data' data-title='remove' data-id='$team->id' title='Click to un-assigned for user' data-value='$team->id'>Unassigned</a>";
			}else{
				$usernm = "<a class='admin_action tack_data_ btn1 btn-success btn-sm' href='javascript:void(0)' id='$edit_data' data-id='$team->id' data-title='add' title='Click to assigned for user' data-value='$team->id'>Assign</a>";
			}
			


			$team_status = "";

			//team
			$team_nm = $team->id;
			$explode_ids = explode(', ', $team_nm);

			$delete_data = "deletedata_" . $team->id;	
				

			$team_status .= "<a class='tack_data_$team->id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='teams' data-key='id' data-value='$team->id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";



			$team_tabs = "<a href='" . base_url('/admin/tabs/bookmarks') . "?p_id=" . base64_encode($team->id) . "&tab=team' class='btn btn-warning btn-sm' title='tabs' data-trigger='hover' id='$team->id' data-id='$team->id'>Edit</a>&nbsp;";
			
			$team_status .= "<a href='javascript:void(0)' class='tack_data_$team->id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='teams' data-key='id' data-value='$team->id'><i class='fa fa-times'></i></a>";

			$data[] = array($i, $team_img, $team->name, $team->nick_title, $team->team_desc, $usernm, $team_tabs,  $team_status);
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

	//Assign User to Company and Team
	function update_assign() {
		extract($_REQUEST);

		if ($tb == "company") {
			$id = "cmp_id";
			$text="Company";
			$update_col = "user_id";
			$company_title=get_single_row_data("company", $company_id, "cmp_id");
			$type_name=$company_title->cmp_nick_title;
			$configure_tab_order="company&".$company_id."&".$type_name;
		}
		else if ($tb == "teams") {
			$id = "id";
			$text="Team";
			$update_col = "user_ids";
			$company_title=get_single_row_data("teams", $company_id, "id");
			$type_name=$company_title->nick_title;
			$configure_tab_order="team&".$company_id."&".$type_name;
		} else {
			$id = "id";
			$update_col = "user_ids";
		}
		
		$assigned_users = $this->super_dbmodel->get_where_single_data($tb, "*", array($id => $company_id));
	
		$assigned_userids = ($tb == "teams") ? $assigned_users->user_ids : $assigned_users->user_id;
		$exp_users = array();

		if (isset($assigned_userids) ) {
			$exp_users = array_filter(explode(', ', $assigned_userids));
		}


		$assigned_array = array();

		if ($type == "remove") {
			$assigned_array = array();
			foreach ($exp_users as $selected_users) {
				if ($selected_users != $user_id) {
					$assigned_array[] = $selected_users;
				}
			}
		} else {
			array_push($exp_users, $user_id);
			$assigned_array = $exp_users;
		}

		if(count($assigned_array)==1){
			$implode_assigned=$assigned_array[0];
		} else {
			$implode_assigned = implode(', ', $assigned_array);
		}

		$where = array($id => $company_id);
		//print_r($update_col);die;
		
		$final_array = array($update_col => $implode_assigned);

		/*echo $tb;
		echo "<pre>";
		print_r($final_array);
		die();*/

		$tab_order=getset_meta("user_meta","get",$user_id,"tabs_order");
		
		$tabs_array=array();

		if(isset($tab_order) && !empty($tab_order)){
			if(in_array($configure_tab_order, $tab_order) && $type == "remove"){
				foreach ($tab_order as $users_tabs) {
					if($users_tabs!=$configure_tab_order){
						$tabs_array[]=$users_tabs;
					}
				}
			} else {
				array_unshift($tab_order, $configure_tab_order);
				$tabs_array=$tab_order;
			}
			$tab_order=getset_meta("user_meta","update",$user_id,"tabs_order", $tabs_array);
		} 

		
		/*echo "<pre>";
		print_r($tabs_array);
		die();*/

		if ($this->super_insertmodel->update_table_data($where, $final_array, $tb)) {
			if ($type == "remove") {
				$result = array('success' => "User has been successfully removed from $text");
			} else {
				$result = array('success' => "User has been successfully assign to $text");
			}
		} else {
			$result = array('error' => 'Failed to user assign data');
		}


		echo json_encode($result);
		die();
	}

	public function add_team_to_company()
	{
		extract($_REQUEST);
        
		
		$assigned_team = $this->super_dbmodel->get_where_single_data($tb, "*", array("cmp_id" => $company_id));		
		if ($type == "remove") {		
			$exp_team = array_filter(explode(', ', $assigned_team->team));
			 
			foreach ($exp_team as $selected_team) {
				if ($selected_team != $team_id) {
					$team[] = $selected_team;
				}
			}
			$team = implode(', ', $team);
		} else {
			$team = $assigned_team->team.", ".$team_id;
		}

		$update_array=array(
            'team' => $team
        );

		if($result=$this->super_dbmodel->update_data("company", $update_array, "cmp_id", $company_id)){
			if ($type == "remove") {
				$result = array('success' => 'Team has been successfully un-assigned for this company ');
			} else {
				$result = array('success' => 'Team has been successfully assigned for this company ');
			}
			
        } else {
            $result = array('error' => 'Failed to save');
        }
		echo json_encode($result);
		die();
	}

	public function add_team_to_user()
	{
		extract($_REQUEST);
        
		
		$assigned_team = $this->super_dbmodel->get_where_single_data($tb, "*", array("id" => $team_id));		
		if ($type == "remove") {		
			$exp_user = array_filter(explode(', ', $assigned_team->user_ids));
			 
			foreach ($exp_user as $selected_user) {
				if ($selected_user != $user_id) {
					$users[] = $selected_user;
				}
			}
			$users = implode(', ', $users);
		} else {
			$users = $assigned_team->user_ids.", ".$user_id;
		}

		$update_array=array(
            'user_ids' => $users
        );

		if($result=$this->super_dbmodel->update_data("teams", $update_array, "id", $team_id)){
			if ($type == "remove") {
				$result = array('success' => 'Team has been successfully un-assigned for this user ');
			} else {
				$result = array('success' => 'Team has been successfully assigned for this user ');
			}
			
        } else {
            $result = array('error' => 'Failed to save');
        }
		echo json_encode($result);
		die();
	}
}