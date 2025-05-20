<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data_table extends CI_Model {

    public function getRows($postData, $table_name, $column_order, $column_search, $order, $where_notin, $where) {
        $this->_get_datatables_query($postData, $table_name, $column_order, $column_search, $order, $where_notin, $where);
        if (isset($postData['length']) && $postData['length'] != -1) {
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();

        /*echo $this->db->last_query();
        die();*/

        return $query->result();
    }

    public function countAll($table_name, $column_order, $column_search, $order, $where_notin, $where) {
        $this->db->from($table_name);
        return $this->db->count_all_results();
    }

    public function countFiltered($postData, $table_name, $column_order, $column_search, $order, $where_notin, $where) {
        
        $this->_get_datatables_query($postData, $table_name, $column_order, $column_search, $order, $where_notin, $where);
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _get_datatables_query($postData, $table_name, $column_order, $column_search, $order, $where_notin, $where) {
        if (!empty($where_notin)) {            
            foreach ($where_notin as $key => $value):
                $this->db->where($key . "!=", $value);
            endforeach;            
        }
        if (!empty($where)) {
            foreach ($where as $key => $value):                
                $this->db->where($key, $value);
            endforeach;
        }


        $this->db->from($table_name);
        $i = 0;
        // loop searchable columns 
        foreach ($column_search as $item) {
            // if datatable send POST for search
            if (isset($postData['search']['value'])) {
                // first loop
                if ($i === 0) {
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                } else {
                    $this->db->or_like($item, $postData['search']['value']);
                }

                // last loop
                if (count($column_search) - 1 == $i) {
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }

        if (isset($postData['order'])) {
            $this->db->order_by($column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        } else if (isset($order)) {
            $order = $order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

}
