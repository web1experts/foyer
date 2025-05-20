<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subtabscontroller extends CI_Controller {

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
	public function index() {
		$teams_data = $this->super_dbmodel->get_sort_data("teams", "*", array('id' => 'desc'));
		$users_data = $this->super_dbmodel->get_sort_data("register", "user_id,user_fname,user_lname,user_role", array('user_id' => 'desc'));
		$company_data = $this->super_dbmodel->get_sort_data("company", "cmp_id,cmp_text,cmp_nick_title", array('cmp_text' => 'ASC'));
		$graphics = $this->super_dbmodel->get_datawith_limit('graphics', '*', 25, 0);

		$this->load->view('admin/header', array('site_header' => 'Sub Tabs'));
		$this->load->view('admin/sidebar', array('site_menu' => 'subtabs', 'inner_active_menu' => 'inner_active_subtabs'));
		$this->load->view('admin/subtabs/subtabs.php', array('teams_data' => $teams_data, 'users_data' => $users_data, 'company_data' => $company_data));

		$this->load->view('admin/footer', ['graphics' => $graphics, 'total_graphics' => $this->super_dbmodel->count_data('graphics', 'id', "NULL")]);
	}


	public function data_subtabtable() {
        $table_name = "subtabs";
        $column_order = array(null, 'subtab_text', 'subtab_nick_title', 'subtab_desc');
        $column_search = array('subtab_text', 'subtab_nick_title', 'subtab_desc');
        $order = array('subtab_id ' => 'desc');
        $where_notin = array();
        $session_lang = $this->session->userdata('site_lang');
        $lang = "english";
        /*if($lang != ""){
            $lang = $session_lang;
        }*/
        $where = array('lang' => $lang);

        $data = $row = array();
        $i = $_POST['start'];
        $memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        /*echo $this->db->last_query();
        die();
        */

        foreach ($memData as $member) {
            $i++;
            $created = date('M d, Y H:i:s', strtotime($member->created_date));

            $user_img = "";


            if (!empty($member->graphic_id)) {
                $graphicsDetail = $this->super_dbmodel->get_where_single_data('graphics', "*", array('id' => $member->graphic_id));

                if(isset($graphicsDetail->thumb)){
                    $user_uploaded_img = $graphicsDetail->thumb;
                    $user_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
                } else {
                    $no_img = base_url('assets/images/slider_blank.png');
                    $user_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
                }
                
            } else {
                $no_img = base_url('assets/images/slider_blank.png');
                $user_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
            }


            $member_status = "";
            $edit_data = "editdata_" . $member->subtab_id;
            $delete_data = "deletedata_" . $member->subtab_id;

            $member_status .= "<a class='tack_data_$member->subtab_id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='subtabs' data-key='subtab_id' data-value='$member->subtab_id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";

            

            $userassign_url = base_url('admin/assign-user/') . "?company=" .$member->subtab_id . "&tb=company";
            $assign_team_url = base_url('admin/team-assign/') . "?company=".$member->subtab_id . "&tb=company";

            $usernm = "<a href='$userassign_url' class='team_applied btn btn-sm btn-info' title='Assign User'>View </a>";

            // $team_tabs ="<a href='$assign_team_url' class='team_applied btn btn-sm btn-info' title='Assign User'>View </a>";

            $member_status .= "<a href='javascript:void(0)' class='tack_data_$member->subtab_id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='subtabs' data-key='subtab_id' data-value='$member->subtab_id'><i class='fa fa-times'></i></a>";

            if($member->assign_type!=NULL){
            	$assign_id=$member->assign_id;

            	if($member->assign_type=="company"){
            		$tablenm="company";
            		//$where=array("cmp_id"=> $assign_id);
            		$table_id= $member->cmp_id;
            		$typeurl="company";
            	} else if($member->assign_type=="team") {
            		$tablenm="teams";
            		//$where=array("id"=> $assign_id);
            		$table_id= $member->team_id;
            		$typeurl="team";
            	}

            	

            	//$tab_url=base_url('/admin/tabs/bookmarks')."?p_id=".base64_encode($edit_result->table_id)."&tab=".$typeurl;
            	$tab_url=base_url('/admin/tabs/bookmarks')."?p_id=".base64_encode($table_id)."&tab=".$typeurl;

            	$member_tabs = "<a href='".$tab_url."' target='_blank' class='btn btn-warning btn-sm' title='tabs' data-trigger='hover' id='$member->subtab_id' data-id='$member->subtab_id'>Edit</a>&nbsp;";
            } else {
            	$member_tabs = "-N/A-";
            }

            $data[] = array($i, $user_img, $member->subtab_text, $member->subtab_nick_title, $member_tabs, $member_status);
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




	public function save_subtab() {
        $session_lang = $this->session->userdata('site_lang');
        $lang = "english";
        /*if($lang != ""){
            $lang = $session_lang;
        }*/

        $user_data = $this->session->userdata('login_auth');
        extract($_REQUEST);

        //$user_ids= (isset($users))? implode(", ", $users): '';
        $subtab_title = $this->input->post('subtab_title');
        $subtabsub_title = $this->input->post('subtabsub_title');
        $slider_desc = $this->input->post('slider_desc');

        // $icon_path = $this->input->post('icon_path');
        // $icon_thumb = $this->input->post('icon_thumb');
        $graphic_id = $this->input->post('graphic_id');



        $icon_array = array();
        $insert_array = array(
            'lang'        => $lang,
            'subtab_text' => $subtab_title,
            'subtab_nick_title' => $subtabsub_title
            /*'user_id'=>$user_data->user_id*/
        );

        $cmp_array = array(
            'lang'        => $lang,
            'cmp_text' => $subtab_title,
            'cmp_nick_title' => $subtabsub_title,
            'type' => 'subtab'
        );

        $team_array = array(
			'name' => $subtab_title,
			'nick_title' => $subtabsub_title,
			'company_id' => 1,
			'type' => 'subtab'
		);

        if ($graphic_id != "") {
            $icon_array = array(
                'graphic_id' => $graphic_id
            );
        }


        $semifinal_array = array_merge($insert_array, $icon_array);

        $cmp_final_array= array_merge($cmp_array, $icon_array);
        $team_final_array= array_merge($team_array, $icon_array);

        $cmp_id= $this->super_insertmodel->insert_data_with_id("company", $cmp_final_array);
        $team_id= $this->super_insertmodel->insert_data_with_id("teams", $team_final_array);

        $relation_array=array(
        	"cmp_id" => $cmp_id,
        	"team_id" => $team_id
        );

        $final_array = array_merge($semifinal_array, $relation_array);

        if ($this->super_insertmodel->insert_data("subtabs", $final_array)) {
            $result = array('success_status' => 'subtab added successfully');
        } else {
            $result = array('error_status' => 'Failed to insert subtab data');
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


	public function edit_subtab() {
        extract($_REQUEST);
        $where = array(
            $table_key => $catalogue_id
        );

        $edit_result = $this->super_dbmodel->get_where_single_data($tablenm, "*", $where);
        $edit_result->thumbnail = getGraphicsThumb($edit_result->graphic_id);        
        echo json_encode($edit_result);
    }

	public function update_subtab() {
        $session_lang = $this->session->userdata('site_lang');
        $lang = "english";
        /*if($lang != ""){
            $lang = $session_lang;
        }*/
        extract($_REQUEST);



        $user_ids = (isset($users)) ? implode(", ", $users) : '';
        $subtab_title = $this->input->post('subtab_title');
        $subtabsub_title = $this->input->post('subtabsub_title');
        $edit_id = $this->input->post('edit_id');

        // $icon_path = $this->input->post('icon_path');
        // $icon_thumb = $this->input->post('icon_thumb');
        $graphic_id = $this->input->post('graphic_id');

        $icon_array = array();
        $update_array = array(
            'lang'        => $lang,
            'subtab_text' => $subtab_title,
            'subtab_nick_title' => $subtabsub_title,
        );

        if ($graphic_id != "") {
            $icon_array = array(
                // 'cmp_image' => !empty($icon_path)?$icon_path:'',
                //'cmp_thumb' => !empty($icon_thumb)?$icon_thumb:'',
                'graphic_id' => $graphic_id
            );
        }



        $cmp_array = array(
            'cmp_text' => $subtab_title,
            'cmp_nick_title' => $subtabsub_title
        );

        $team_array = array(
			'name' => $subtab_title,
			'nick_title' => $subtabsub_title
		);

        $final_array = array_merge($update_array, $icon_array);


        $cmp_final_array= array_merge($cmp_array, $icon_array);
        $team_final_array= array_merge($team_array, $icon_array);

        $cmp_id= $this->super_insertmodel->update_table_data(array('cmp_id'=> $update_cmp_id), $cmp_final_array, 'company');

        $team_id= $this->super_insertmodel->update_table_data(array('id'=> $update_team_id), $team_final_array, 'teams');

        $where = array(
            'subtab_id' => $edit_id
        );

        if ($this->super_insertmodel->update_table_data($where, $final_array, 'subtabs')) {
            $result = array('success_status' => 'Subtab updated successfully');
        } else {
            $result = array('error_status' => 'Failed to update subtab data');
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
