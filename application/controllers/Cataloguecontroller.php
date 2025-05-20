<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cataloguecontroller extends CI_Controller
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
        $catalogue_data = $this->super_dbmodel->get_sort_data("catalogue", "*", array('id' => 'desc'));
        $categories_data = $this->super_dbmodel->get_sort_data("catagories", "*", array('cat_id' => 'desc'));
        $brands_data = $this->super_dbmodel->get_sort_data("brands", "*", array('brand_id' => 'desc'));
        $this->load->view('admin/header', array('site_header' => 'Catalogue'));
        $this->load->view('admin/sidebar', array('site_menu' => 'catalogue', 'inner_active_menu' => 'inner_active_catalogue'));
        $this->load->view('admin/catalogue/catalogue.php', array('catalogue_data' => $catalogue_data, 'cats' => $categories_data, 'brands' => $brands_data));

        $this->load->view('admin/footer');
    }


    public function data_table_catalogue()
    {
        $table_name = "catalogue";
        $column_order = array(null, 'catalog_title', 'catalog_thumb', 'catalog_image', 'created_date');
        $column_search = array('catalog_title');
        $order = array('id ' => 'desc');
        $where_notin = array();

        $where = array();

        $data = $row = array();
        $i = $_POST['start'];
        $memData = $this->data_table->getRows($_POST, $table_name, $column_order, $column_search, $order, $where_notin, $where);

        foreach ($memData as $member) {
            $i++;
            $created = date('M d, Y H:i:s', strtotime($member->created_date));

            $catalogue_img = "";
            if ($member->catalog_image == "") {
                $no_img = base_url('assets/images/slider_blank.png');
                $catalogue_img = "<img height='70px' width='90' src='" . $no_img . "'/>";
            } else {
                $user_uploaded_img = base_url('assets/admin/upload/catalogues/thumbnail/') . $member->catalog_thumb;
                $catalogue_img = "<img height='70px' width='90' src='" . $user_uploaded_img . "'/>";
            }


            $member_status = "";
            $edit_data = "editdata_" . $member->id;

            //category
            $cats_nm = $member->cat_id;
            $explode_ids = explode(', ', $cats_nm);
            $catalogue_data = $this->super_dbmodel->get_wherein_data("catagories", "cat_name", "cat_id", $explode_ids);
            $cats_array = array();
            foreach ($catalogue_data as $cats) {
                $cats_array[] = $cats['cat_name'];
            }

            $catnm = implode(', ', $cats_array);


            //Brands
            $brands_nm = $member->brand_id;
            $brands_ids = explode(', ', $brands_nm);
            $brands_data = $this->super_dbmodel->get_wherein_data("brands", "brand_name", "brand_id", $brands_ids);
            $brands_array = array();
            foreach ($brands_data as $brnds) {
                $brands_array[] = $brnds['brand_name'];
            }
            $brandsnm = implode(', ', $brands_array);

            //product_url
            $prd_link = "-N/A-";
            if ($member->catalogue_link != "") {
                $prd_url = $member->catalogue_link;
                $prd_link = "<a class='btn btn-sm btn-warning' href='" . $prd_url . "'><i class='fa fa-search'></i></a>";
            }

            $prd_price = "-N/A-";
            if ($member->catalogue_price != "") {
                $prd_price = $member->catalogue_price;
            }

            //print_r($brands_data);

            $delete_data = "deletedata_" . $member->id;
            $member_status .= "<a class='tack_data_$member->id btn btn-primary btn-sm user_action' href='javascript:void(0)' id='$edit_data' title='Edit' data-trigger='hover' data-id='catalogue' data-key='id' data-value='$member->id'><i class='fa fa-pencil-alt'></i></a>&nbsp;";
            //$member_status .="<a href='javascript:void(0)' class='tack_data btn btn-warning btn-sm user_action' title='Unfollow' data-trigger='hover' id='$member->tl_screen_name' data-id='$member->tl_twitter_id'><i class='fas fa-thumbs-down'></i></a>&nbsp;";

            $member_status .= "<a href='javascript:void(0)' class='tack_data_$member->id  btn btn-danger btn-sm user_action' title='Delete' data-trigger='hover' id='$delete_data' data-id='catalogue' data-key='id' data-value='$member->id'><i class='fa fa-times'></i></a>";

            $data[] = array($i, $catalogue_img, $member->catalog_title, $catnm, $brandsnm, $prd_link, $prd_price, $created, $member_status);
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




    public function save_catalogue()
    {
        extract($_REQUEST);
        /*echo "<pre>";
        print_r($_REQUEST);
        die();*/
        $brands_ids = implode(", ", $brands);
        $cat_ids = implode(", ", $cats);

        $thumb_image_path   = FILE_UPLOAD_PATH . '/catalogues/thumbnail/';
        $image_path   = FILE_UPLOAD_PATH . '/catalogues/';
        $image_nm = "catalogue_image";

        //Upload Helper
        $upload_image = Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path);

        if (isset($upload_image['error'])) {
            $result = array('error_status' => $upload_image['error']);
        } else {
            $original_nm = $upload_image['original_nm'];
            $thumb_nm = $upload_image['thumb_nm'];
            $insert_array = array(
                'catalog_title' => $catalogue_text,
                'cat_id'        => $cat_ids,
                'brand_id'      => $brands_ids,
                'catalogue_link' => $catalogue_url,
                'catalog_image' => $original_nm,
                'catalog_thumb' => $thumb_nm,
                'catalogue_price' => $catalogue_price
            );
            if ($this->super_insertmodel->insert_data("catalogue", $insert_array)) {
                $result = array('success_status' => 'Catalogue added successfully');
            } else {
                $result = array('error_status' => 'Failed to insert Catalogue data');
            }
        }

        echo json_encode($result);
        die();
    }



    public function resizeImage($filename, $origianl_path, $thumb_path)
    {
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


    public function edit_catalogue()
    {
        extract($_REQUEST);
        $edit_result = $this->super_dbmodel->get_where_single_data($tablenm, "*", array($table_key => $catalogue_id));
        echo json_encode($edit_result);
    }



    /*public function edit_addons_catalogue(){
        extract($_REQUEST);        
        $edit_result=$this->super_dbmodel->get_where_single_data($tablenm, "*", array($table_key=>$catalogue_id));
        
        $send_array=array(
            'catalog' => $edit_result,
            'brands'  => $brands_data,
            'cats'    => $categories_data
        );
        //echo json_encode($send_array);
    }*/



    public function update_catalogue()
    {
        extract($_REQUEST);
        /*echo "<pre>";
        print_r($_REQUEST);
        die();*/

        $brands_ids = implode(", ", $brands);
        $cat_ids = implode(", ", $cats);

        $catalogue_text = $this->input->post('catalogue_text');
        $edit_id = $this->input->post('edit_id');
        $thumb_image_path   = FILE_UPLOAD_PATH . '/catalogues/thumbnail/';
        $image_path   = FILE_UPLOAD_PATH . '/catalogues/';
        $image_nm = "catalogue_image";

        if ($_FILES['catalogue_image']['name'] != "") {

            //Upload Helper        
            $upload_image = Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path);

            if (isset($upload_image['error'])) {
                $result = array('error_status' => $upload_image['error']);
                die();
            } else {
                $original_nm = $upload_image['original_nm'];
                $thumb_nm = $upload_image['thumb_nm'];
                $update_array = array(
                    'catalog_title' => $catalogue_text,
                    'cat_id'        => $cat_ids,
                    'brand_id'      => $brands_ids,
                    'catalogue_price' => $catalogue_price,
                    'catalogue_link' => $catalogue_url,
                    'catalog_image' => $original_nm,
                    'catalog_thumb' => $thumb_nm
                );
            }
        } else {
            $update_array = array(
                'catalog_title' => $catalogue_text,
                'cat_id'        => $cat_ids,
                'brand_id'      => $brands_ids,
                'catalogue_link' => $catalogue_url,
                'catalogue_price' => $catalogue_price
            );
        }

        $where = array(
            'id' => $edit_id
        );

        if ($this->super_insertmodel->update_table_data($where, $update_array, 'catalogue')) {
            $result = array('success_status' => 'Catalogue updated successfully');
        } else {
            $result = array('error_status' => 'Failed to update Catalogue data');
        }
        echo json_encode($result);
        die();
    }



    //Catagories
    public function catagory()
    {
        $order = array(
            'cat_id' => 'desc'
        );
        $catagories_data = $this->super_dbmodel->get_sort_data("catagories", "*", $order);
        $this->load->view('admin/header', array('site_header' => 'Catalogue'));
        $this->load->view('admin/sidebar', array('site_menu' => 'catalogue', 'inner_active_menu' => 'inner_active_cat'));
        $this->load->view('admin/catagories/catagory.php', array('catagories_data' => $catagories_data));
        $this->load->view('admin/footer');
    }

    public function data_table_catagory()
    {
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



    public function save_catagory()
    {
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



    public function update_catagory()
    {
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
    public function brands()
    {
        $order = array(
            'brand_id' => 'desc'
        );
        $brands_data = $this->super_dbmodel->get_sort_data("brands", "*", $order);
        $this->load->view('admin/header', array('site_header' => 'Catalogue'));
        $this->load->view('admin/sidebar', array('site_menu' => 'catalogue', 'inner_active_menu' => 'inner_active_brand'));
        $this->load->view('admin/brands/brands.php', array('brands_data' => $brands_data));
        $this->load->view('admin/footer');
    }

    public function data_table_brands()
    {
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



    public function save_brand()
    {
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



    public function update_brand()
    {
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
