<?php

class Shops extends CI_Model {
    
    public function addShop($shopName){
        $this->db->set('shop', $this->db->escape_str($shopName));
        return $this->db->insert('shops');        
    }
    
    public function getShops(){
        
        $this->db->select('shops.*, count(autoparts.shop) as partsCount');
        $this->db->from('shops');
        $this->db->join('autoparts', 'autoparts.shop = shops.shop', 'left');
        $this->db->order_by("shops.id", 'desc'); 
        $this->db->group_by("shops.shop"); 
        $result = $this->db->get();
        
        return $result->result_array();
    }
    
    public function delete($shopId){
        $this->db->where('id', $shopId);
        $this->db->delete('shops');
    }
    
    public function shopExist($shopName){
        $this->db->select();
        $this->db->where('shop', $shopName);
        $result = $this->db->get('shops');
        return $result->num_rows();
    }
    
    
}
