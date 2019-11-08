<?php

class Roles_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->main_table = 'roles';
        $this->primary_key = "id";
    }

    public function insert($data) {
        $this->db->insert($this->main_table, $data);
            return $this->db->insert_id();
    }
    public function Insert_reportData($data) {
            $this->db->insert('report', $data);
            return $this->db->insert_id();
    }

    public function Insert_adminData($data) {
        $this->db->insert($this->main_table, $data);
        return $this->db->insert_id();
    }

    public function getAnyData($extracond = "", $field = "", $orderby = "", $limit = "", $join_arr = array(),$wherein = False) 
    {
        if ($field == "") 
        {
            $field = "*";
        }

        $this->db->select($field, false);
        $this->db->from($this->main_table);

        if (is_array($join_arr) && count($join_arr) > 0) {
            foreach ($join_arr as $key => $val) {
                $this->db->join($val['table_name'], $val['cond'], $val['type']);
            }
        }
        if ($wherein) 
        {
            $this->db->where_in('id', $wherein);
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
        // echo $this->db->last_query();
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
         /*echo $this->db->last_query();
        die();*/
    }

    public function delete($where) {
        $this->db->where_in('id', $where);
        return $this->db->delete($this->main_table);
    }

    public function checkUser($email_id,$facebook_id){
        $query = "SELECT * FROM users WHERE email_id = '$email_id' OR facebook_id = '$facebook_id'";
        $res_default = $this->db->query($query)->num_rows();
        //echo $this->db->last_query();
        if($res_default > 0){
            return true;
        }
        else{
            return false;
        }
    }
    public function checkUserData($email_id,$facebook_id){
        $query = "SELECT * FROM users WHERE email_id = '$email_id' AND facebook_id = '$facebook_id'";
        $res_default = $this->db->query($query)->num_rows();
        if($res_default > 0){
            return $this->db->query($query)->result();
        }
        else{ 
            return false;
        }
    }

    public function insertFbData($data)
    {
        $this->db->insert($this->main_table, $data);
        return $this->db->insert_id();
    }
}

?>