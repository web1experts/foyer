<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teamcontroller extends CI_Controller {

	function __construct() {
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
	public function index() {
		$teams_data = $this->super_dbmodel->get_sort_data("teams", "*", array('id' => 'desc'));
		$users_data = $this->super_dbmodel->get_sort_data("register", "user_id,user_fname,user_lname,user_role", array('user_id' => 'desc'));
		$company_data = $this->super_dbmodel->get_sort_data_where("company", "cmp_id,cmp_text,cmp_nick_title", array('cmp_text' => 'ASC'), array('type'=>'page'));
		$graphics = $this->super_dbmodel->get_datawith_limit('graphics', '*', 25, 0);
		$subtabs = $this->super_dbmodel->get_where_datatable_order("subtabs", "*", array("assign_id"=> NULL), array("subtab_text"=> 'asc'));

		$this->load->view('admin/header', array('site_header' => 'Team'));
		$this->load->view('admin/sidebar', array('site_menu' => 'teams', 'inner_active_menu' => 'inner_active_team'));
		$this->load->view('admin/teams/teams.php', array('teams_data' => $teams_data, 'users_data' => $users_data, 'company_data' => $company_data, "subtabs"=> $subtabs));

		$this->load->view('admin/footer', ['graphics' => $graphics, 'total_graphics' => $this->super_dbmodel->count_data('graphics', 'id', "NULL")]);
	}


	public function data_table_teams() {
		$table_name = "teams";
		$column_order = array(null, 'name', 'team_desc', 'logo', 'created_at');
		$column_search = array('name', 'nick_title', 'team_desc');
		$order = array('id ' => 'desc');
		$where_notin = array("type"=> "subtab");

		$where = array();

		$data = $row = array();
		$i = $_POST['start'];
		$teamData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

		foreach ($teamData as $team) {
			$i++;
			$created = date('M d, Y', strtotime($team->created_at));

			$catalogue_img = "";

			if (!empty($team->graphic_id)) {
				$graphicsDetail = $this->super_dbmodel->get_where_single_data('graphics', "*", array('id' => $team->graphic_id));

				if(isset($graphicsDetail->thumb)){
					$user_uploaded_img = $graphicsDetail->thumb;
					$team_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
				} else {
					$no_img = base_url('assets/images/slider_blank.png');
					$team_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
				}
				/* echo "<pre>";
				print_r($graphicsDetail);
				die; */
			} else {
				$no_img = base_url('assets/images/slider_blank.png');
				$team_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
			}
			/* if ($team->logo == "") {
			} else {
				$user_uploaded_img = $team->thumb;
				$team_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
			} */


			//Company


			if (!empty($team->company_id) && $team->company_id != '0') {
				$catalogue_data = $this->super_dbmodel->get_wherein_data("company", "cmp_id,cmp_text,cmp_nick_title", "cmp_id", $team->company_id);
				$company_array = array();
				foreach ($catalogue_data as $cats) {
					$cname = $cats['cmp_nick_title'];
					$company_url = base_url("/admin/companies");
					$company_array[] = "<a href='javascript::void(0)' class='team_applied btn btn-sm btn-info' title='$cname'>$cname</a>";
				}
				$company_nm = implode(' ', $company_array);
			} else {
				$company_nm = "-N/A-";
			}

			$userassign_url = base_url('admin/assign-team/') . "?team=" . $team->id . "&tb=teams";

			$usernm = "<a href='$userassign_url' class='team_applied btn btn-sm btn-info' title='Assign User'>View </a>";


			$team_status = "";
			$edit_data = "editdata_" . $team->id;

			//team
			$team_nm = $team->id;
			$explode_ids = explode(', ', $team_nm);

			$delete_data = "deletedata_" . $team->id;
			$team_status .= "<a class='tack_data_$team->id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='teams' data-key='id' data-value='$team->id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";



			$wherecmp=array(
                "assign_id" => $team->id,
                "assign_type" => "team"
            );

            $subtabDetails = $this->super_dbmodel->get_where_datatable_order("subtabs", "*", $wherecmp, array("subtab_text"=> "asc"));

            if(isset($subtabDetails) && !empty($subtabDetails)){
                $sb_ar=array();
                foreach($subtabDetails as $subtabs){
                    $sb_ar[]="<a href='".base_url('/admin/subtabs')."' class='text-info text-purple' title='".$subtabs['subtab_text']."' id='$team->id' data-id='$team->id'>".$subtabs['subtab_text']."</a>";
                }

                $team_tabs = "<div class='subtabs'><strong>Subtabs: </strong>".implode(", ",$sb_ar)."</div>";

            } else {
                $team_tabs = "<a target='_blank' href='" . base_url('/admin/tabs/bookmarks') . "?p_id=" . base64_encode($team->id) . "&tab=team' class='btn btn-warning btn-sm' title='tabs' data-trigger='hover' id='$team->id' data-id='$team->id'>Edit</a>&nbsp;";
            }

			





			$team_status .= "<a href='javascript:void(0)' class='tack_data_$team->id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='teams' data-key='id' data-value='$team->id'><i class='fa fa-times'></i></a>";

			$data[] = array($i, $team_img, $team->name, $team->nick_title, $team->team_desc, $company_nm, $usernm, $team_tabs,  $team_status);
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




	public function save_team() {
		extract($_REQUEST);
		$user_data = $this->session->userdata('login_auth');
		$thumb_image_path   = FILE_UPLOAD_PATH . '/graphics/thumbnail/';
		$image_path   = FILE_UPLOAD_PATH . '/graphics/';

		// $icon_path = $this->input->post('icon_path');
		// $icon_thumb = $this->input->post('icon_thumb');
		$graphic_id = $this->input->post('graphic_id');


		$icon_array = array();
		$insert_array = array(
			'name' => $team_text,
			'nick_title' => $nick_title,
			/*'user_ids' => $user_data->user_id,*/
			'team_desc' => $slider_desc,
			'company_id' => $cmp_id
		);

		if ($graphic_id != "") {
			$icon_array = array(
				// 'logo' => !empty($icon_path)?$icon_path:'',
				//'thumb' => !empty($icon_thumb)?$icon_thumb:'',
				'graphic_id' => $graphic_id
			);
		}
		$final_array = array_merge($insert_array, $icon_array);

		if ($this->super_insertmodel->insert_data("teams", $final_array)) {
			$result = array('success_status' => 'Team added successfully');
		} else {
			$result = array('error_status' => 'Failed to insert Team data');
		}

		echo json_encode($result);
		die();
	}



	public function resizeImage($filename, $origianl_path, $thumb_path) {
		$source_path = $origianl_path;
		$target_path = $thumb_path;
		$config_manip = array(
			'image_library' => 'gd2',
			'source_image' => $source_path,
			'new_image' => $target_path,
			'maintain_ratio' => TRUE,
			'create_thumb' => TRUE,
			'thumb_marker' => '_thumb',
			'width' => 150,
			'height' => 150
		);

		$this->load->library('image_lib', $config_manip);
		if (!$this->image_lib->resize()) {
			echo $this->image_lib->display_errors();
		}
		$this->image_lib->clear();
	}


	public function edit_team() {
		extract($_REQUEST);

		$edit_result = $this->super_dbmodel->get_where_single_data($tablenm, "*", array($table_key => $team_id));		
		$edit_result->thumbnail = getGraphicsThumb($edit_result->graphic_id);		
		
		$team_id=$edit_result->id;
        $subtabs = $this->super_dbmodel->get_where_datatable_order("subtabs", "*", array("assign_id"=> NULL), array("subtab_text"=> 'asc'));

        if(isset($subtabs) && !empty($subtabs)){
            $allsub_ar=array();

            foreach($subtabs as $sbtabs){
                $allsub_ar[]=array(
                    "subtab_id" => $sbtabs['subtab_id'],
                    "subtab_text" => $sbtabs['subtab_text'],
                );
            }
            $edit_result->subtabs = "yes";
            $edit_result->subtabs_data = $allsub_ar;
        } else {
            $edit_result->subtabs = "no";
        }

        $wherecmp=array(
            "assign_id" => $team_id,
            "assign_type" => "team"
        );

        $subtabDetails = $this->super_dbmodel->get_where_datatable_order("subtabs", "*", $wherecmp, array("subtab_text"=> "asc"));

        if(isset($subtabDetails) && !empty($subtabDetails)){
            $sb_ar=array();
            foreach($subtabDetails as $subtabs){
                $sb_ar[]=array(
                    "subtab_id" => $subtabs['subtab_id'],
                    "subtab_text" => $subtabs['subtab_text'],
                );
            }
            $edit_result->sbtabs ="yes";
            $edit_result->sbtabs_data = $sb_ar;
        } else {
            $edit_result->sbtabs ="no";
        }
        
        echo json_encode($edit_result);
        die();
	}

	public function update_team() {
		extract($_REQUEST);
		$team_text = $this->input->post('team_text');
		$nick_title = $this->input->post('nick_title');
		$edit_id = $this->input->post('edit_id');
		//$user_ids = (isset($users)) ? implode(", ", $users) : '';

		// $icon_path = $this->input->post('icon_path');
		// $icon_thumb = $this->input->post('icon_thumb');
		$graphic_id = $this->input->post('graphic_id');

		$icon_array = array();
		$update_array = array(
			'name' => $team_text,
			'nick_title' => $nick_title,
			'company_id' => $cmp_id,
			'team_desc' => $slider_desc
		);

		if ($graphic_id != "") {
			$icon_array = array(
				// 'logo' => !empty($icon_path)?$icon_path:'',
				//'thumb' => !empty($icon_thumb)?$icon_thumb:'',
				'graphic_id' => $graphic_id
			);
		}

		$final_array = array_merge($update_array, $icon_array);

		$where = array(
			'id' => $edit_id
		);


		//Delete subtab into subtable table
        $delcmp_subtabs=array(
            "assign_type" => NULL,
            "assign_id" => NULL
        );

        $this->super_insertmodel->update_table_data(array("assign_id"=> $edit_id), $delcmp_subtabs, "subtabs");
        
        $delcmp=array(
            "subtabs" => 1,
            "subtabs_parent_ids" => NULL
        );
        $this->super_insertmodel->update_table_data(array("subtabs_parent_ids"=> $edit_id), $delcmp, "teams");
        //End Delete subtab into subtable table


        if(isset($assign_subtabs) && !empty($assign_subtabs)){
	        //Assign Subtab into subtable

	        $result=$this->super_dbmodel->get_wherein_data("subtabs", "*", "subtab_id", @$assign_subtabs);
	        $cmp_arr=array();
	        foreach($result as $cmpids):
	            $cmp_arr[]=$cmpids['team_id'];
	        endforeach;

	        $cmp_subtabs=array(
	            "assign_type" => "team",
	            "assign_id" => $edit_id
	        );

	        $this->super_insertmodel->update_table_data_wherein("subtabs", $cmp_subtabs, "subtab_id", @$assign_subtabs);
	        $cmptb_arr=array(
	            "subtabs" => 2,
	            "subtabs_parent_ids"=> $edit_id
	        );
	        $this->super_insertmodel->update_table_data_wherein("teams", $cmptb_arr, "id", $cmp_arr);
	        //End Assign Subtab into subtable
	    }



		if ($this->super_insertmodel->update_table_data($where, $final_array, 'teams')) {
			$result = array('success_status' => 'Team updated successfully');
		} else {
			$result = array('error_status' => 'Failed to update Team data');
		}
		echo json_encode($result);
		die();
	}



	//Catagories
	public function catagory() {
		$order = array(
			'cat_id' => 'desc'
		);
		$catagories_data = $this->super_dbmodel->get_sort_data("catagories", "*", $order);
		$this->load->view('admin/header', array('site_header' => 'Catalogue'));
		$this->load->view('admin/sidebar', array('site_menu' => 'catalogue', 'inner_active_menu' => 'inner_active_cat'));
		$this->load->view('admin/catagories/catagory.php', array('catagories_data' => $catagories_data));
		$this->load->view('admin/footer');
	}

	public function data_table_catagory() {
		$table_name = "catagories";
		$column_order = array(null, 'cat_name', 'created_date');
		$column_search = array('cat_name');
		$order = array('cat_id' => 'desc');
		$where_notin = array();

		$where = array();

		$data = $row = array();
		$i = $_POST['start'];
		$memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

		foreach ($memData as $member) {
			$i++;
			$created = date('M d, Y H:i:s', strtotime($member->created_at));

			$member_status = "";
			$edit_data = "editdata_" . $member->cat_id;
			$delete_data = "deletedata_" . $member->cat_id;
			$member_status .= "<a class='tack_data_$member->cat_id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='catagories' data-key='cat_id' data-value='$member->cat_id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";
			//$member_status .="<a href='javascript:void(0)' class='tack_data btn btn-warning btn-sm user_action' title='Unfollow' data-trigger='hover' id='$member->tl_screen_name' data-id='$member->tl_twitter_id'><i class='fas fa-thumbs-down'></i></a>&nbsp;";

			$member_status .= "<a href='javascript:void(0)' class='tack_data_$member->cat_id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='catagories' data-key='cat_id' data-value='$member->cat_id'><i class='fa fa-times'></i></a>";

			$data[] = array($i, $member->cat_name, $created, $member_status);
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



	public function save_catagory() {
		$cat_text = $this->input->post('cat_text');
		$insert_array = array(
			'cat_name' => $cat_text
		);
		if ($this->super_insertmodel->insert_data("catagories", $insert_array)) {
			$result = array('success_status' => 'Catalogue added successfully');
		} else {
			$result = array('error_status' => 'Failed to insert Catalogue data');
		}
		echo json_encode($result);
		die();
	}



	public function update_catagory() {
		$cat_text = $this->input->post('cat_text');
		$edit_id = $this->input->post('edit_id');
		$update_array = array(
			'cat_name' => $cat_text
		);

		$where = array(
			'cat_id' => $edit_id
		);

		if ($this->super_insertmodel->update_table_data($where, $update_array, 'catagories')) {
			$result = array('success_status' => 'Catalogue updated successfully');
		} else {
			$result = array('error_status' => 'Failed to update Catalogue data');
		}
		echo json_encode($result);
		die();
	}





	//Brands
	public function brands() {
		$order = array(
			'brand_id' => 'desc'
		);
		$brands_data = $this->super_dbmodel->get_sort_data("brands", "*", $order);
		$this->load->view('admin/header', array('site_header' => 'Catalogue'));
		$this->load->view('admin/sidebar', array('site_menu' => 'catalogue', 'inner_active_menu' => 'inner_active_brand'));
		$this->load->view('admin/brands/brands.php', array('brands_data' => $brands_data));
		$this->load->view('admin/footer');
	}

	public function data_table_brands() {
		$table_name = "brands";
		$column_order = array(null, 'brand_name', 'created_date');
		$column_search = array('brand_name');
		$order = array('brand_id' => 'desc');
		$where_notin = array();

		$where = array();

		$data = $row = array();
		$i = $_POST['start'];
		$memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

		foreach ($memData as $member) {
			$i++;
			$created = date('M d, Y H:i:s', strtotime($member->created_at));

			$member_status = "";
			$edit_data = "editdata_" . $member->brand_id;
			$delete_data = "deletedata_" . $member->brand_id;
			$member_status .= "<a class='tack_data_$member->brand_id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='brands' data-key='brand_id' data-value='$member->brand_id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";
			//$member_status .="<a href='javascript:void(0)' class='tack_data btn btn-warning btn-sm user_action' title='Unfollow' data-trigger='hover' id='$member->tl_screen_name' data-id='$member->tl_twitter_id'><i class='fas fa-thumbs-down'></i></a>&nbsp;";

			$member_status .= "<a href='javascript:void(0)' class='tack_data_$member->brand_id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='brands' data-key='brand_id' data-value='$member->brand_id'><i class='fa fa-times'></i></a>";

			$data[] = array($i, $member->brand_name, $created, $member_status);
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



	public function save_brand() {
		$brand_name = $this->input->post('brand_name');
		$insert_array = array(
			'brand_name' => $brand_name
		);
		if ($this->super_insertmodel->insert_data("brands", $insert_array)) {
			$result = array('success_status' => 'Catalogue added successfully');
		} else {
			$result = array('error_status' => 'Failed to insert Catalogue data');
		}
		echo json_encode($result);
		die();
	}



	public function update_brand() {
		$brand_name = $this->input->post('brand_name');
		$edit_id = $this->input->post('edit_id');
		$update_array = array(
			'brand_name' => $brand_name
		);

		$where = array(
			'brand_id' => $edit_id
		);

		if ($this->super_insertmodel->update_table_data($where, $update_array, 'brands')) {
			$result = array('success_status' => 'Catalogue updated successfully');
		} else {
			$result = array('error_status' => 'Failed to update Catalogue data');
		}
		echo json_encode($result);
		die();
	}
}
