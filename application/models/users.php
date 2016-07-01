<?php

class Users extends CI_Model{
    
    public function getUsers(){
        $this->db->select();
        $result = $this->db->get('users');
        return $result->result_array();
    }
    
    public function gerUserOrdersById($user_id){
        $this->db->select('orders.*, order_items.*, order_details.*');
        $this->db->from('orders');
        $this->db->join('order_items', 'orders.id = order_items.order_id', 'left');
        $this->db->join('order_details', 'orders.id = order_details.order_id', 'left');
        
        $this->db->where('orders.user_id', $user_id);
        $this->db->order_by("orders.order_date", "desc");
        $query = $this->db->get();
        
        $result = $query->result_array();
        
        if(empty($result)){
            return $result;
        }
        $shaped_result = array();
        
        foreach ($result as $row)
        {
            $id = $row['order_id'];

            // Add a new result row for A if we have not come across this key before
            if (!array_key_exists($id, $shaped_result))
            {
                $shaped_result[$id] = array(
                    'order_id'     => $id, 
                    'card_id'      => $row['card_id'], 
                    'items_count'  => $row['items_count'], 
                    'total_price'  => $row['total_price'], 
                    'discount'     => $row['discount'], 
                    'order_date'   => $row['order_date'],
                    'order_status' => $row['order_status'],
                    'rate'         => $row['rate'],
                    'payed'        => $row['order_payed'],
                    'f_name'       => $row['f_name'],
                    'l_name'       => $row['l_name'],
                    'email'        => $row['email'],
                    'phone'        => $row['phone'],
                    'address'      => $row['address'],
                    'items'        => array()
                );
            }

            if ($row['part_number'] != null)
            {
                // Push B item onto sub array
                $shaped_result[$id]['items'][] = array(
                    'part_number' => $row['part_number'],
                    'description' => $row['description'],
                    'part_maker'  => $row['part_maker'],
                    'price'       => $row['price'],
                    'first_price' => $row['first_price'],
                    'count'       => $row['count'],
                    'from'         => $row['from'], 
                    'rate'        => $row['rate']
                );
            }
        }
        return $shaped_result;
        
    }
    
    public function gerUserOrdersByOrderId($order_id, $user_id=false){
        
        $this->db->select('orders.items_count, orders.total_price, orders.card_id, '
                        . 'orders.discount, orders.order_date, orders.order_status, orders.rate, order_items.*');
        $this->db->from('orders');
        $this->db->join('order_items', 'orders.id = order_items.order_id', 'left');
        if($user_id){
            $this->db->where('orders.user_id', $user_id);
        }
        $this->db->where('orders.id', $order_id);
        $this->db->order_by("orders.order_date", "desc");
        $query = $this->db->get();
        
        $result = $query->result_array();
        $shaped_result = array();
        
        foreach ($result as $row)
        {
            $id = $row['order_id'];

            // Add a new result row for A if we have not come across this key before
            if (!array_key_exists($id, $shaped_result))
            {
                $shaped_result[$id] = array(
                    'order_id'     => $id, 
                    'card_id'      => $row['card_id'], 
                    'items_count'  => $row['items_count'], 
                    'total_price'  => $row['total_price'], 
                    'discount'     => $row['discount'], 
                    'order_date'   => $row['order_date'],
                    'order_status' => $row['order_status'],
                    'rate'         => $row['rate'],
                    'items'        => array()
                );
            }

            if ($row['part_number'] != null)
            {
                // Push B item onto sub array
                $shaped_result[$id]['items'][] = array(
                    'part_number' => $row['part_number'],
                    'description' => $row['description'],
                    'part_maker'  => $row['part_maker'],
                    'price'       => $row['price'],
                    'first_price' => $row['first_price'],
                    'count'       => $row['count'],
                    'delivery'    => $row['delivery'],
                    'rate'        => $row['rate']
                );
            }
        }
        return $shaped_result;
        
    }
    
    public function gerUserOrdersByOrderIdPhone($phone, $order_id){
        $data['content'] = FALSE;
        $data['error']   = FALSE;
        
        //Check does exist user with such phone number
        $this->db->select('order_id, phone');
        $this->db->where('phone', $phone);
        $this->db->where('order_id', $order_id);
        $result = $this->db->get('order_details');
        
        if($result->num_rows() != 0){
            $rOrderId = $result->row_array()['order_id'];        
        }else{
            //No user with such phone in db
            $data['error'] = $this->lang->line('orders_search_input_error');
            return $data;
        }
        
        //Go to the orders table and check does this order exist with such user_id
        $this->db->select('id');
        $this->db->where('id', $rOrderId);
        $result1 = $this->db->get('orders');
        
        if($result1->num_rows() == 0){
            //No order with such order_id in db
            $data['error'] = $this->lang->line('orders_search_input_error');
            return $data;
        }
        
        //Go to the order_items and get order items with such order_id
        $result2 = $this->getOrderById($order_id);;//$this->gerUserOrdersByOrderId($order_id);
        return $result2;
    }
    
    public function getUserInfoById($user_id){
        $this->db->select();
        $this->db->where('id', $user_id);
        $result = $this->db->get('users');
        
        return $result->row_array();
    }
    
    public function getOrderById($order_id, $user_id = false){
        $this->db->select('orders.*, order_items.*, order_details.*');
        $this->db->from('orders');
        $this->db->join('order_items', 'orders.id = order_items.order_id', 'left');
        $this->db->join('order_details', 'orders.id = order_details.order_id', 'left');
        if($user_id){
            $this->db->where('orders.user_id', $user_id);
        }
        $this->db->where('orders.id', $order_id);
        $this->db->order_by("orders.order_date", "desc");
        $query = $this->db->get();
        
        $result = $query->result_array();
        
        if(empty($result)){
            return $result;
        }
        $shaped_result = array();
        
        foreach ($result as $row)
        {
            $id = $row['order_id'];

            // Add a new result row for A if we have not come across this key before
            if (!array_key_exists($id, $shaped_result))
            {
                $shaped_result[$id] = array(
                    'order_id'     => $id, 
                    'card_id'      => $row['card_id'], 
                    'items_count'  => $row['items_count'], 
                    'total_price'  => $row['total_price'], 
                    'discount'     => $row['discount'], 
                    'order_date'   => $row['order_date'],
                    'order_status' => $row['order_status'],
                    'rate'         => $row['rate'],
                    'payed'        => $row['order_payed'],
                    'f_name'       => $row['f_name'],
                    'l_name'       => $row['l_name'],
                    'email'        => $row['email'],
                    'phone'        => $row['phone'],
                    'address'      => $row['address'],
                    'items'        => array()
                );
            }

            if ($row['part_number'] != null)
            {
                // Push B item onto sub array
                $shaped_result[$id]['items'][] = array(
                    'part_number' => $row['part_number'],
                    'description' => $row['description'],
                    'part_maker'  => $row['part_maker'],
                    'price'       => $row['price'],
                    'first_price' => $row['first_price'],
                    'count'       => $row['count'],
                    'delivery'       => $row['delivery'],
                    'from'         => $row['from'], 
                    'rate'        => $row['rate']
                );
            }
        }
        return $shaped_result;
    }
	
	public function sendreqemail($name, $email, $phone, $vincode, $model, $year, $mess){
		$to = "info@yerevanmotors.com";

		$subject = 'Message from yerevanmotors.com';
		
		$headers = "From: yerevanmotors\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		
		
		$message = '<html><body>';
		$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
		$message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . strip_tags($name) . "</td></tr>";
		$message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($email) . "</td></tr>";
		$message .= "<tr><td><strong>Phone number:</strong> </td><td>" . strip_tags($phone) . "</td></tr>";
		$message .= "<tr><td><strong>VIN code:</strong> </td><td>" . strip_tags($vincode) . "</td></tr>";
		$message .= "<tr><td><strong>Model:</strong> </td><td>" . strip_tags($model) . "</td></tr>";
		$message .= "<tr><td><strong>Year:</strong> </td><td>" . strip_tags($year) . "</td></tr>";
		
		$curText = htmlentities($mess);           
		if (($curText) != '') {
			$message .= "<tr><td><strong>Message:</strong> </td><td>" . $curText . "</td></tr>";
		}
		$message .= "</table>";
		$message .= "</body></html>";
    	
        mail($to, $subject, $message, $headers);
		
	}
    
    
}