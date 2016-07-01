<?php

class Admin_Auth extends CI_Model{
    
    public function login($username, $password) {       
        
        $this->db->select('id');
        $this->db->where(array(
            "userName" => $username,
            "password" => md5($password)
        ));        
        $result = $this->db->get('admin');
        //Return founded user id
        if($result->num_rows != 0){
            return $result->row()->id;           
        }else{
            return false;
        }
    }
    
    public function changePass($pass){
        $this->db->where('id', 1);
        $this->db->update('admin', array('password'=>md5($pass)));
    }
    
    public function changeBarmaPass($barmalogin, $barmapass){
        $this->db->where('id', 1);
        $this->db->update('admin', array('barmapass'=>$barmapass, 'barmalogin'=>$barmalogin));
    }
    
    public function getBaramaAuth(){
        $this->db->select('barmalogin, barmapass');
        $this->db->where('id', 1);
        $q = $this->db->get('admin');
        return $q->row();
    }
}
