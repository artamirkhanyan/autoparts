<?php

class Orders extends CI_Model{
    
    public function insertNewOrder($data){
        $this->db->set('user_id',        $data['user_id']);
        $this->db->set('username',       $data['username']);
        $this->db->set('card_id',        $data['card_id']);
        $this->db->set('items_count',    $data['items_count']);
        $this->db->set('total_price',    $data['total_price']);
        $this->db->set('discount',       0);
        $this->db->set('rate',           $data['rate']);
        $this->db->set('order_date',     date('Y-m-d H:i:s'));
        
        $this->db->insert('orders');
        return $this->db->insert_id();
    }
    
    public function insertNewOrderItem($data){
        $this->db->set('order_id',    $data['order_id']);
        $this->db->set('part_number', $data['part_number']);
        $this->db->set('description', $data['description']);
        $this->db->set('part_maker',  $data['makerName']);
        $this->db->set('from',        $data['from']);
        $this->db->set('price',       $data['price']);
        $this->db->set('first_price', $data['first_price']);
        $this->db->set('count',       $data['count']);
        $this->db->set('delivery',    $data['delivery']);
        $this->db->set('status',      $data['status']);
        $this->db->set('discount',    $data['discount'] ? $data['discount'] : 0);
        
        return $this->db->insert('order_items');
    }
    
    public function getAllOrders(){
        $this->db->select();
        $this->db->order_by('order_status asc, order_date desc'); 
        $result = $this->db->get('orders');
        return $result->result_array();
    }
    
    public function getOrderById($order_id){
        $this->db->select('orders.*, order_items.*, order_details.*');
        $this->db->from('orders');
        $this->db->join('order_items', 'orders.id = order_items.order_id', 'left');
        $this->db->join('order_details', 'orders.id = order_details.order_id', 'left');
        
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
                    'discount'    => $row['discount'],
                    'first_price' => $row['first_price'],
                    'count'       => $row['count'],
                    'delivery'    => $row['delivery'],
                    'from'        => $row['from'], 
                    'rate'        => $row['rate']
                );
            }
        }
        return $shaped_result;
    }
    
    public function changeOrderStatus($order_id, $order_status){
        $data = array(            
            'order_status' => $order_status
        );
        $this->db->where('id', $order_id);
        return $this->db->update('orders', $data); 
    }
    
    public function addTransactionId($order_id, $transferId){
        $data = array(            
            'transaction_id' => $transferId
        );
        $this->db->where('id', $order_id);
        return $this->db->update('orders', $data); 
    }
    
    public function setAsPayed($transferId){
        $data = array(            
            'order_payed' => '1'
        );
        $this->db->where('transaction_id', $transferId);
        if($this->db->update('orders', $data)){
            return $this->getByTransactionId($transferId);
        }
        return FALSE;
    }
    
    public function setAsPayedById($id){
        $data = array(            
            'order_payed' => '1'
        );
        $this->db->where('id', $id);
        if($this->db->update('orders', $data)){
            return true;
        }
        return FALSE;
    }
    
    public function sendSMS2Owner($orderId, $amount){
        
        $this->db->select('f_name, l_name, phone');
        $this->db->where('order_id', $orderId);
        $result = $this->db->get('order_details');
        $data = $result->row_array();
        if($data){
            $phone = '+374'.substr($data['phone'], 1);
            $message = "Hargeli ".$data['f_name']." ".$data['l_name'].", Duq vchareciq #".$orderId." patveri hamar ".$amount." dram: Shnorhakalutyun: Yerevanmotors.com";
        
            return sendSMS($phone, $message);
        }
        return FALSE;
    }

    public function getByTransactionId($transferId){
        
        $this->db->where('transaction_id', $transferId);
        $result = $this->db->get('orders'); 
        return $result->row_array();
    }
    
    public function deleteOrderBytransferId($transferId){
        $order = $this->getByTransactionId($transferId);
        if(!isset($order['id']))
            return false;
        
        $this->db->where('id', $order['id']);
        $this->db->delete('orders'); 
        
        $this->db->where('order_id', $order['id']);
        $this->db->delete('order_details');
        
        $this->db->where('order_id', $order['id']);
        $this->db->delete('order_items');
        
    }
    
    public function deleteOrderById($orderId){
        $this->db->where('id', $orderId);
        $order = $this->db->get('orders');
        $order = $order->row_array();
        if(!isset($order['id']))
            return false;
        
        $this->db->where('id', $order['id']);
        $this->db->delete('orders'); 
        
        $this->db->where('order_id', $order['id']);
        $this->db->delete('order_details');
        
        $this->db->where('order_id', $order['id']);
        $this->db->delete('order_items');
        
    }

    public function addOrderDetails($data){
        
        $this->db->set('order_id', $data['order_id']);
        $this->db->set('f_name',   $data['f_name']);
        $this->db->set('l_name',   $data['l_name']);
        $this->db->set('email',    $data['email']);
        $this->db->set('phone',    $data['phone']);
        $this->db->set('address',  $data['address']);
        $this->db->set('created_at',  date('Y-m-d H:i:s'));
        
        if($this->db->insert('order_details')){
            return $data['order_id'];
        }
        return false;
    }
}