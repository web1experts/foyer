<?php

function getset_meta($table, $method, $parent_id, $key, $value = null) {
    //get main CodeIgniter object
    $ci = & get_instance();
    //load databse library
    $ci->load->database();
    $ci->load->model('super_dbmodel');
    $ci->load->model('super_insertmodel');
    //get data from database

    $post_id = ($table == 'user_meta') ? 'user_id' : 'post_id';
    if ($method == 'insert' || $method == 'update') {
        $query = $ci->db->get_where($table, array($post_id => $parent_id, 'meta_key' => $key));

        if ($query->num_rows() > 0) {
            $where = array(
                ($table == 'user_meta') ? 'user_id' : 'post_id' => $parent_id,
                'meta_key' => $key
            );
            if(empty($value)){
                return true;
            }
            if ($ci->super_insertmodel->update_table_data($where, ['meta_value' => (is_array($value)) ? serialize($value) : $value], $table)) {
                return true;
            } else {
                return false;
            }
        } else {

            $insert_array = array(
                ($table == 'user_meta') ? 'user_id' : 'post_id' => $parent_id,
                'meta_key' => $key,
                'meta_value' => serialize($value)
            );
            if ($ci->super_insertmodel->insert_data($table, $insert_array)) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        $query = $ci->db->get_where($table, array($post_id => $parent_id, 'meta_key' => $key));
        if ($query->num_rows() > 0 && !empty($query->row())) {
            $data = @unserialize($query->row()->meta_value);
            if ($data !== false) {
                return (is_array($data)) ? array_filter($data) : $data;
            } else {
                return $query->row()->meta_value;
            }
        } else {
            return false;
        }
    }
}

function gettabsbyuserid($user_id) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->load->model('super_dbmodel');
    $ci->load->model('super_insertmodel');
    $userdata = $ci->super_dbmodel->get_where_single_data('register', '*', ['user_id' => $user_id]);
    $tabs_order = getset_meta('user_meta', 'select', $user_id, 'tabs_order', null);
    $bookmark_user_data = $explode_bookmark = $sorted_tabs_arr = array();
    $team_data = $ci->super_dbmodel->get_sort_data_like_all("teams", "*", array('user_ids' => $user_id), array('nick_title' => 'asc'));
    $cmp_data = $ci->super_dbmodel->get_sort_data("company", "*", array('cmp_nick_title' => 'asc'));
    $usertabs = $ci->super_dbmodel->get_where_data("tabs", "*", array('user_id' => $user_id));
    $customtab = [];

    if (isset($tabs_order) && !empty($tabs_order)) {
        $companyids = $team_ids = $tabsids = $companytab = $teamtab = array();
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
                $companytab = $ci->super_dbmodel->get_wherein_data("company", "cmp_id as id,cmp_nick_title as name", "cmp_id", $companyids);



                foreach ($companytab as $key => $csm) {
                    $companytab[$key]['type'] = 'company';
                }
            }

            if (isset($team_ids) && !empty($team_ids)) {
                $teamtab = $ci->super_dbmodel->get_wherein_data("teams", "id,nick_title as name", "id", $team_ids);

                foreach ($teamtab as $key => $csm) {
                    $teamtab[$key]['type'] = 'team';
                }
            }


            if (isset($tabsids) && !empty($tabsids)) {
                $customtab = $ci->super_dbmodel->get_wherein_data("tabs", "id, sub_title as name", "id", array_unique($tabsids));
                foreach ($customtab as $key => $csm) {
                    $customtab[$key]['type'] = 'tabs';
                }
            }
            $combined_arr = array_merge($companytab, $teamtab, $customtab, [0 => ['id' => $user_id, 'name' => @$userdata->user_fname . ' ' . @$userdata->user_lname, 'type' => 'user']]);

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
    }
    return array('company_tabs' => $cmp_data, 'team_tabs' => $team_data, 'sorted_data' => $sorted_tabs_arr, 'usertabs' => $usertabs);
}

function getsortedbookmarksbypost_id($type, $parent_id, $meta_key = '') {
    $ci = & get_instance();
    $ci->load->database();
    $ci->load->model('super_dbmodel');
    $ci->load->model('super_insertmodel');
    if ($type == 'company') {
        $table = 'company_meta';
        $col = 'company_id';
    } else {
        $table = 'team_meta';
        $col = 'team_id';
    }
    if (!empty($meta_key)) {
        $bookmarks = array();


        $bookmarks_order = getset_meta($table, 'select', $parent_id, $meta_key, null);
        if (isset($bookmarks_order) && !empty($bookmarks_order)) {
            $allbookmarks = $ci->super_dbmodel->get_wherein_data("bookmarks", "*", "id", $bookmarks_order);
            for ($i = 0; $i < count($bookmarks_order); $i++) {

                foreach ($allbookmarks as $k => $d) {
                    if ($bookmarks_order[$i] === $d['id']) {
                        $bookmarks[$i] = $allbookmarks[$k];
                    }
                    continue;
                }
            }
        } else {
            $bookmarks = $ci->super_dbmodel->get_sort_where_data("bookmarks", "*", ['id' => 'asc'], [$col => $parent_id]);
        }
    } else {
        $bookmarks = $ci->super_dbmodel->get_sort_where_data("bookmarks", "*", ['id' => 'asc'], [$col => $parent_id]);
    }

    return $bookmarks;
}

function get_single_row_data($table, $id, $primary_key) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->load->model('super_dbmodel');
    $userdata = $ci->super_dbmodel->get_where_single_data($table, '*', array($primary_key => $id));
    return $userdata;
}

function media_library($search = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->load->model('super_dbmodel');
    $user_data = $ci->session->userdata('login_auth');
    if (@$user_data->user_id != "") {
        $user_id = $user_data->user_id;
        $wherein = "1," . $user_id;
    } else {
        $wherein = "no";
    }
    $graphics = $ci->super_dbmodel->media_with_limit('graphics', '*', $wherein, $search, 12, 0);

    $count_media=$ci->super_dbmodel->media_all_users('graphics', '*', $wherein, $search);
    

    $result=array(
        'graphic'=> $graphics,
        'total_data'=> $count_media
    );
    return $result;
}

function media_bookmarks($search = null) {
    $ci = & get_instance();
    $ci->load->database();
    $ci->load->model('super_dbmodel');
    $user_data = $ci->session->userdata('login_auth');
    /*if (@$user_data->user_id != "") {
        $user_id = $user_data->user_id;
        $where = array(
            'user_id' => $user_id,
            'user_id' => 1
        );
        $order = array('id' => 'asc');
    } else {
        $where = array(
            'user_id' => 1
        );
    }*/
    $order = array('name' => 'asc');
    $where=array();

    $bookmarkData = $ci->super_dbmodel->ci_bookmark("bookmarks", "*", $order, $where, $search, 12, 0);
    $count_media=$ci->super_dbmodel->ci_bookmark_users("bookmarks", "*", $order, $where, $search);

    if(isset($bookmarkData) && !empty($bookmarkData)){
        foreach($bookmarkData as $k => $bookmark){
            $bookmarkData[$k]->thumb = getGraphicsThumb($bookmark->graphic_id);
            $bookmarkData[$k]->image = getGraphicsThumb($bookmark->graphic_id);
        }
    }

    $result=array(
        'bookmarkData'=> $bookmarkData,
        'total_data'=> $count_media
    );
    return $result;
}