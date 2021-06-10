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

  public function delete_stu($stu_id){
    $this->db->where("id", $stu_id);
    return $this->db->delete("students");
  }

  public function update_stu($stu_id,$stu_info){
    $this->db->where("id", $stu_id);
    return $this->db->update("students", $stu_info);
  }
}