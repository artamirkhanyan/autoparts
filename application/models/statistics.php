<?php

class Statistics extends CI_Model {
    
    public function topTenDetails($limit = 10){
        
        $q = $this->db->query('SELECT part_number, sum(count) as totcount '
                . 'FROM `order_items` group by `part_number` order by totcount DESC LIMIT '.$limit);
        
        return $q->result();
    }
    
    
    public function topTenArm($limit = 10){
        $shops = $this->getShops();
        if(count($shops) > 0){
            $shops = join("', '", $shops);
        }else{
            return array();
        }
        
        $q = $this->db->query("SELECT part_number, sum(count) as totcount FROM `order_items` WHERE `from` IN ('".$shops."') group by `part_number` order by totcount DESC LIMIT $limit");
        
        return $q->result();
        
    }
    
    public function topTenBarma($limit = 10){
        $shops = $this->getShops();
        if(count($shops) > 0){
            $shops = join("', '", $shops);
        }else{
            $shops = "''";
        }
        
        $q = $this->db->query("SELECT part_number, sum(count) as totcount FROM `order_items` WHERE `from` NOT IN ('".$shops."') group by `part_number` order by totcount DESC LIMIT $limit");
        
        return $q->result();
    }
    
    public function topTenCustomers(){
        
        $q = $this->db->query('SELECT count(`orders`.`id`) as orderscount, sum(`orders`.`total_price`) as totalPrice, `users`.f_name, `users`.l_name, `users`.phone '
                . 'FROM `orders` LEFT JOIN `users` ON (`orders`.`user_id` = `users`.`id`) '
                . 'WHERE `orders`.`user_id` IS NOT NULL group by `users`.`id` order by orderscount DESC LIMIT 10');
        
        return $q->result();
        
    }

    public function getShops(){
        $this->db->select('shop');
        $q = $this->db->get('shops');
        
        $data = array();
        foreach($q->result_array() as $shop){
            $data[] = $shop['shop'];
        }
        return $data;
    }
    
    public function getPayedTotal($from='0', $to='NOW()'){
        $q = $this->db->query("SELECT count(id) as totCount, sum(total_price)  as totPrice FROM orders WHERE (order_date >= '$from' AND order_date <= '$to') AND order_payed='1'");
        
        return $q->row();
    }
    
    public function getOriginalTotal($from='0', $to='NOW()'){
        $q = $this->db->query("SELECT sum((first_price*count)) as totOrigin FROM `order_items` LEFT JOIN orders ON (orders.id = order_items.order_id) where orders.order_payed='1' AND (orders.order_date >= '$from' AND orders.order_date <= '$to')");
        
        return $q->row();
    }
    
    public function getDetailsTotalArm($from='0', $to='NOW()'){
        
        $shops = $this->getShops();
        if(count($shops) > 0){
            $shops = join("', '", $shops);
        }else{
            return array();
        }
        
        $q = $this->db->query("SELECT sum(order_items.count) as totCount, sum((order_items.first_price*order_items.count)) as totOrigin FROM `order_items` LEFT JOIN orders ON (orders.id = order_items.order_id) where orders.order_payed='1' AND (orders.order_date >= '$from' AND orders.order_date <= '$to') AND order_items.from IN ('".$shops."')");
        
        return $q->row();
    }
    
    public function getDetailsTotalBarma($from='0', $to='NOW()'){
        
        $shops = $this->getShops();
        if(count($shops) > 0){
            $shops = join("', '", $shops);
        }else{
            $shops = "''";
        }
        
        $q = $this->db->query("SELECT sum(order_items.count) as totCount, sum((order_items.first_price*order_items.count)) as totOrigin FROM `order_items` LEFT JOIN orders ON (orders.id = order_items.order_id) where orders.order_payed='1' AND (orders.order_date >= '$from' AND orders.order_date <= '$to') AND order_items.from NOT IN ('".$shops."')");
        
        return $q->row();
    }
}
