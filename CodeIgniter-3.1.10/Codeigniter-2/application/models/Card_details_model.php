<?php

class Card_details_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->main_table = 'demo_store_details';
        $this->primary_key = "id";
    }

    public function Insert_Data($data) {
        
        $this->db->insert($this->main_table, $data);
        return $this->db->insert_id();
    }

    public function getAnyData($extracond = "", $field = "", $orderby = "", $limit = "", $join_arr = array()) {
        if ($field == "") {
            $field = "*";
        }

        $this->db->select($field, false);
        $this->db->from($this->main_table);

        if (is_array($join_arr) && count($join_arr) > 0) {
            foreach ($join_arr as $key => $val) {
                $this->db->join($val['table_name'], $val['cond'], $val['type']);
            }
        }

        if ($extracond != "") {
            $this->db->where($extracond);
        }
        if ($orderby != "") {
            $this->db->order_by($orderby);
        }
        if ($limit != "") {
            list($offset, $limit) = @explode(",", $limit);
            $this->db->limit($offset, $limit);
        }

        $list_data = $this->db->get()->result();
        //  echo $this->db->last_query();
        // die();
        return $list_data;
    }

    public function update($data = array(), $where = "", $wherein = False) {
        if ($wherein) {
            $this->db->where_in($this->primary_key, $where);
        } else {
            $this->db->where($where);
        }
        return $this->db->update($this->main_table, $data);
    }

    public function delete($where) {
        $this->db->where_in('id', $where);
        return $this->db->delete($this->main_table);
    }

}

?>