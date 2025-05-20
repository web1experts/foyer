<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Super_dbmodel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_data($table, $columns) {
        $this->db->select($columns);
        $this->db->from($table);
        $query = $this->db->get();
        $this->db->last_query();
        $result_data = $query->result_array();
        return $result_data;
    }

    public function get_datawith_limit($table, $columns,$limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select($columns);
        $this->db->from($table)->order_by("name", "asc");
        $query = $this->db->get();
        $this->db->last_query();
        $result_data = $query->result();
        return $result_data;
    }
    
    public function media_with_limit($table, $columns, $wherein, $search, $limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select($columns);
        
        if($search!=""){
            $this->db->like('name', $search, "both");
        }
        if($wherein!="no"){
            $this->db->where_in("user_id", $wherein);
        }
        $this->db->order_by("name", "desc");
        $this->db->from($table);
        $query = $this->db->get();

        /*echo $this->db->last_query();
        die();*/
        $result_data = $query->result();
        return $result_data;
    }
    
    
    
    public function media_all_users($table, $columns, $wherein, $search=null) {
        $this->db->select($columns);
        $this->db->from($table)->order_by("name", "asc");
        if($search!=""){
            $this->db->like('name', $search, "both");
        }
        if($wherein!="no"){
            $this->db->where_in("user_id", $wherein);
        }        
        $query = $this->db->get();

        /*echo $this->db->last_query();
        die();*/

        $count = $query->num_rows();
        return $count;
    }

    public function ci_bookmark($table, $columns, $order, $where, $search, $limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select($columns);
        $this->db->from($table);
        if($search!=""){
            $this->db->like('name', $search, "both");
        }
        foreach ($where as $key => $value):                     
            $this->db->like($key, $value, "both");        
        endforeach;

        foreach ($order as $od => $ov):
            $this->db->order_by($od, $ov);
        endforeach;
        $query = $this->db->get();

        /*echo $this->db->last_query();
        die();*/
        
        $result_data = $query->result();
        return $result_data;
    }
    
    public function ci_bookmark_users($table, $columns, $order, $where, $search) {
        $this->db->select($columns);
        $this->db->from($table);
        if($search!=""){
            $this->db->like('name', $search, "both");
        }
        foreach ($where as $key => $value):                     
            $this->db->like($key, $value, "both");        
        endforeach;
        foreach ($order as $od => $ov):
            $this->db->order_by($od, $ov);
        endforeach;
        $query = $this->db->get();
        $count = $query->num_rows();
        return $count;
    }
    
    public function ci_bookmark_users_like($table, $columns, $where, $search_key, $search_value) {        
        $this->db->select($columns);
        $this->db->from($table);        
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach;
        //$this->db->like($search_key, $search_value, "both");
        $this->db->like($search_key, $search_value, "both");
        
        $query = $this->db->get();       
        

        $result_data = $query->row();
        $count = $query->num_rows();
        if($count==1){            
            $result=array(
                'count_bookmark' => $count,
                'data'=> $result_data
            );
        } else {
            $this->db->select($columns);
            $this->db->from($table);        
            foreach ($where as $key => $value):                     
                $this->db->where($key, $value);        
            endforeach;
            $query = $this->db->get();
            $this->db->last_query();
            $result_data = $query->row();            
            $result=array(
                'count_bookmark' => $count,
                'data'=> $result_data
            );
        }
        return $result;
    }
    
    
    public function ci_bookmark_users_like_before($table, $columns, $where, $search_key, $search_value) {        
        $this->db->select($columns);
        $this->db->from($table);        
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach;
        //$this->db->like($search_key, $search_value, "both");
        $this->db->like($search_key, $search_value, "before");
        
        $query = $this->db->get();       
        

        $result_data = $query->row();
        $count = $query->num_rows();
        if($count==1){            
            $result=array(
                'count_bookmark' => $count,
                'data'=> $result_data
            );
        } else {
            $this->db->select($columns);
            $this->db->from($table);        
            foreach ($where as $key => $value):                     
                $this->db->where($key, $value);        
            endforeach;
            $query = $this->db->get();
            $this->db->last_query();
            $result_data = $query->row();            
            $result=array(
                'count_bookmark' => $count,
                'data'=> $result_data
            );
        }
        return $result;
    }

    public function get_data_order_by($table, $columns,$limit, $start) {
      $this->db->limit($limit, $start);
        $this->db->select($columns);
        $this->db->from($table)->order_by("name", "asc");
        $query = $this->db->get();        
        $result_data = $query->result();
      // echo"<pre>"; print_r($result_data );die;
        return $result_data;
    }

    public function get_like_data_order_by($table, $columns, $limit, $start, $keyword) {
        $this->db->limit($limit, $start);
          $this->db->select('*');
          $this->db->from($table)->order_by("name", "asc")->where($columns." LIKE '%$keyword%'");
          $query = $this->db->get();          
          $result_data = $query->result();        
          return $result_data;
      }

    public function get_datawith_where_limit($table, $columns,$where,$limit, $start) {
        $this->db->limit($limit, $start);
        $this->db->select($columns);
        $this->db->from($table)->order_by("name", "asc");
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach; 
        $query = $this->db->get();
        $this->db->last_query();
        $result_data = $query->result();
        return $result_data;
    }



    public function get_datawith_where_limit_search($table, $columns, $limit, $start, $search) {
        $this->db->limit($limit, $start);
        $this->db->select($columns);
        if($search!=""){
            $this->db->like("name", $search, "both");
        }
        $this->db->from($table);
        $this->db->order_by("id", "desc");

        $query = $this->db->get();
        /*echo $this->db->last_query();
        die();*/

        $result_data = $query->result();
        return $result_data;
    }

    //Count All Data
    public function count_data_search($table, $key, $search) {
        if ($search!="") {
            $this->db->like("name", $search, "both");
        }
        $count_query = $this->db->get($table);
        $this->db->order_by("id", "desc");        
        $count = $count_query->num_rows();
        return $count;
    }
    
    public function get_user_meta($table, $ids) {
        $implode_data=implode(",", $ids);
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where_in('id', $ids);
        $this->db->order_by("FIELD (id, $implode_data)");
        $query = $this->db->get();
        //echo $this->db->last_query();
        //die();
        $result_data = $query->result_array();
        return $result_data;
    }
   
    public function get_data_chat($table, $columns) {
        $this->db->select($columns);
        $this->db->from($table);
        $query = $this->db->get();
        $this->db->last_query();
        $result_data = $query->result();
        return $result_data;
    }

    public function get_sort_data($table, $columns, $order) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($order as $od => $ov):
            $this->db->order_by($od, $ov);
        endforeach;
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        $result_data = $query->result_array();
        return $result_data;
    }

    public function get_sort_data_where($table, $columns, $order, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($order as $od => $ov):
            $this->db->order_by($od, $ov);
        endforeach;

        //Where
        foreach ($where as $wh_key => $wh_val):
            $this->db->like($wh_key, $wh_val, "both");
        endforeach;
        
        $query = $this->db->get();
        $this->db->last_query();
        $result_data = $query->result_array();
        return $result_data;
    }


    public function get_sort_data_like_all($table, $columns, $like, $order, $where=null) {
        $this->db->select($columns);
        $this->db->from($table);

        //Where
        if(isset($where) && !empty($where)){
            foreach ($like as $like_key => $like_val):
                $this->db->like($like_key, $like_val, "both");
            endforeach;
        }

        //Like
        foreach ($like as $like_key => $like_val):
            $this->db->like($like_key, $like_val, "both");
        endforeach;

        //Order
        foreach ($order as $od => $ov):
            $this->db->order_by($od, $ov);
        endforeach;
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();

        $result_data = $query->result_array();
        return $result_data;
    }


    public function get_sort_where_data($table, $columns, $order, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):                     
            $this->db->like($key, $value, 'both');        
        endforeach;

        foreach ($order as $od => $ov):
            $this->db->order_by($od, $ov);
        endforeach;
        $query = $this->db->get();

        /*echo $this->db->last_query();
        die();*/
        
        $result_data = $query->result_array();
        return $result_data;
    }



    /*public function get_where_data($table, $columns, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        $query = $this->db->get();
        $result_data = $query->result();
        return $result_data;
    }*/

    public function get_where_data($table, $columns, $where){
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach;        
        $query = $this->db->get();   
        /*echo $this->db->last_query();
        die(); */    
        $result_data=$query->result_array();
        return $result_data;
    }

    

    public function get_where_data_row($table, $columns, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        $result_query = $this->db->get();
        if ($result_query->num_rows() > 0) {
            return $result_query->num_rows();
        } else {
            return 0;
        }
    }

    public function get_where_data_row_wherenot($table, $columns, $where, $wherenot) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        foreach ($wherenot as $key => $value):
            $this->db->where($key . "!=", $value);
        endforeach;
        $result_query = $this->db->get();

        if ($result_query->num_rows() > 0) {
            return $result_query->num_rows();
        } else {
            return 0;
        }
    }

    public function get_where_data_simple_array($table, $columns, $where) {

        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        $query = $this->db->get();
        

        $result_data = $query->result_array();
        return $result_data;
    }

    public function get_wherenot_datatable_order($table, $columns, $where, $order) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key . "!=", $value);
        endforeach;

        foreach ($order as $key => $value):
            $this->db->order_by($key, $value);
        endforeach;
        $query = $this->db->get();
        $result_data = $query->result_array();
        return $result_data;
    }

    public function get_where_datatable_order($table, $columns, $where, $order) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;

        foreach ($order as $key => $value):
            $this->db->order_by($key, $value);
        endforeach;
        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        $result_data = $query->result_array();
        return $result_data;
    }

    public function get_where_single_data($table, $columns, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        $query = $this->db->get();
        $result_data = $query->row();
        return $result_data;
    }
    
    

    public function get_where_data_first($table, $columns, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        $query = $this->db->get();
        /*echo $this->db->last_query();
        die();*/
        $result_data = $query->row();
        return $result_data;
    }

    public function get_sort_where_data_like($table, $columns, $order, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):                     
            $this->db->where($key, $value);        
        endforeach;

        foreach ($order as $od => $ov):
            $this->db->order_by($od, $ov);
        endforeach;
        $query = $this->db->get();

        // echo $this->db->last_query();
        // die();
        
        $result_data = $query->result_array();
        return $result_data;
    }

    public function search_like_data($table, $column, $data, $condition) {
        $this->db->like($column, $data, $condition);
        $this->db->from($table);
        $query = $this->db->get();
        $total_rows = $query->num_rows();
        return $total_rows;
    }

    /*public function search_likes($table,$column,$keyword) {
        $result_data = $this->db->select('*')->from($table)->where($column." LIKE '%$keyword%'")->get()->result_array();;
        return $result_data;
    }*/


    public function search_likes($table,$column,$keyword, $where) {
        if($where!="no"){
            $result_data = $this->db->select('*')->from($table)->where_in('id', $where)->where($column." LIKE '%$keyword%'")->get()->result_array();
        } else {
            $result_data = $this->db->select('*')->from($table)->where($column." LIKE '%$keyword%'")->get()->result_array();
        }
        /*echo $this->db->last_query();
        die();*/
        
        return $result_data;
    }
    
    

    public function search_like_data_where_checked($table, $column, $data, $condition, $where) {
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        $this->db->like($column, $data, $condition);
        $this->db->from($table);
        $query = $this->db->get();
        $total_rows = $query->num_rows();
        return $total_rows;
    }


    public function search_like_where_checked_data($table, $column, $data, $condition) {
        $this->db->like($column, $data, $condition);
        $this->db->from($table);
        $query = $this->db->get();
        
        /*echo $this->db->last_query();
        die();*/

        $result_data = $query->row();
        return $result_data;
    }

    public function search_like_data_where($table, $column, $data, $condition, $where) {
        foreach ($where as $key => $value):
            $this->db->where($key . "!=", $value);
        endforeach;
        $this->db->like($column, $data, $condition);
        $this->db->from($table);
        $query = $this->db->get();
        $total_rows = $query->num_rows();
        return $total_rows;
    }

    public function get_wherein_data($table, $columns, $key, $wherein) {
        $this->db->select($columns);
        $this->db->from($table);
        $this->db->where_in($key, $wherein);
        $query = $this->db->get();
        $result_data = $query->result_array();
        return $result_data;
    }


    public function get_wherein_data_groupby($table, $columns, $key, $wherein) {
        $this->db->select($columns);
        $this->db->from($table);
        $this->db->where_in($key, $wherein);
        $this->db->group_by($columns);
        $query = $this->db->get();
        /*echo $this->db->last_query();
        die();*/

        $result_data = $query->result_array();
        return $result_data;
    }


    public function get_wherein_data_groupbywhere($table, $columns, $key, $wherein, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        $this->db->where_in($key, $wherein);
        $this->db->group_by($columns);
        $query = $this->db->get();
        /*echo $this->db->last_query();
        die();*/

        $result_data = $query->result_array();
        return $result_data;
    }


    public function get_wherein_count($table, $columns, $key, $wherein) {
        $this->db->select($columns);
        $this->db->from($table);
        $this->db->where_in($key, $wherein);
        $query = $this->db->get();
        $total_rows = $query->num_rows();
        return $total_rows;
    }

    public function count_where_data($table, $columns, $where) {
        $this->db->select($columns);
        $this->db->from($table);
        foreach ($where as $key => $value):
            $this->db->where($key, $value);
        endforeach;
        $query = $this->db->get();
        $total_rows = $query->num_rows();
        return $total_rows;
    }

    public function update_data($table_name, $updated_array, $data_key, $data_id) {
        $this->db->where($data_key, $data_id);
        $this->db->update($table_name, $updated_array);
        return ($this->db->affected_rows() > 0) ? "success" : "error";
    }

    public function delete_data($table_name, $data_key, $data_val) {
        $this->db->where($data_key, $data_val);
        $this->db->delete($table_name);
        return ($this->db->affected_rows() > 0) ? "success" : "error";
    }

    public function date_match_record($table) {    //Date Match count
        $this->db->select("*");
        $this->db->from($table);
        $current_date = date('Y-m-d');
        $this->db->where("DATE_FORMAT(created, '%Y-%m-%d')=", $current_date);
        $query = $this->db->get();
        $count = $query->num_rows();
        return $count;
    }

    public function date_match_record_data($table) {   //Date match Data
        $this->db->select("*");
        $this->db->from($table);
        $current_date = date('Y-m-d');
        $this->db->where("DATE_FORMAT(created, '%Y-%m-%d')=", $current_date);
        $query = $this->db->get();
        $myresult = $query->row();
        return $myresult->ad_id;
    }

    //Count All Data
    public function count_data($table, $key, $where) {
        if ($where != "NULL") {
            $this->db->where($key, $where);
        }
        $count_query = $this->db->get($table);
        // echo $this->db->last_query();
        // die();
        $count = $count_query->num_rows();
        return $count;
    }

    public function check_authenticate($email, $pass) {
        $this->db->where('user_email', $email);
        $this->db->where('user_pass', $pass);
        $this->db->where('status', 1);
        $result_query = $this->db->get('register');
        if ($result_query->num_rows() > 0) {
            return $result_query->result();
        } else {
            return 0;
        }
    }

    public function social_login($email) {
        $this->db->where('user_email', $email);
        $result_query = $this->db->get('register');
        if ($result_query->num_rows() > 0) {
            return $result_query->result();
        } else {
            return 0;
        }
    }

    public function getOneLanguage($myLang) {
        $this->db->select('*');
        $this->db->where('abbr', $myLang);
        $result = $this->db->get('languages');
        return $result->row_array();
    }
    

}
