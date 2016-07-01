<?php

class Announcement extends CI_Model{
    public function deleteAnnouncement(){
        $this->db->where('id IS NOT NULL', null, false);
        $this->db->delete('announcement');
    }
    
    public function addAnnouncement($text){
        $this->deleteAnnouncement();
        
        $this->db->set('text', $text);
        return $this->db->insert('announcement');  
    }
    
    public function getAnnouncement(){
        $result = $this->db->get('announcement', 1);
        return $result->row_array();
    }
}
