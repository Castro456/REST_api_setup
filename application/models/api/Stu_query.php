<?php

class Stu_query extends CI_Model{
  public function get_data(){
    $this->db->select("*");
    $data = $this->db->get("students");
    return $data->result();
  }

  public function insert_data($data=array()){
    return $this->db->insert("students", $data);
  }
}