<?php

class User_Auth extends CI_Model{
    
    public function login($email, $password) {       
        
        $this->db->select('id, email, f_name, l_name, is_active');
        if(is_numeric($email)){
            $this->db->where(array(
                "phone" => $email,
                "password" => md5($password)
            ));  
        }else{
            $this->db->where(array(
                "email" => $email,
                "password" => md5($password)
            ));  
        }
              
        $result = $this->db->get('users');        
        
        //Return founded user id
        if($result->num_rows != 0){
            $id = $result->row_array()['id'];
            //Update last_login and set as NOW
            $this->db->query("UPDATE `users` SET `last_login` = now() WHERE `id` =  '$id' ");                        
            return $result->row_array();           
        }else{
            return false;
        }
    }
    
    public function activateUser($user_id, $active){
        $this->db->where('id', (int)$user_id);
        $this->db->update('users', array('is_active' => (boolean)$active));
    }

    /**
     * 
     * @return inserted user id or false
     */
    public function registration($f_name, $l_name, $email, $phone, $address, $password){
        $data = array(
            'f_name'   => $f_name,
            'l_name'   => $l_name,
            'email'    => $email,
            'phone'    => $phone,
            'address'  => $address,
            'password' => md5($password)
        );
        
        $result = $this->emailPhoneValidation($email, $phone);
        switch ($result) {
            case 1:
                if($this->db->insert('users', $data)){
                    $this->db->select('id, email, f_name, l_name, is_active');
                    $this->db->where('id',$this->db->insert_id());
                    return $this->db->get('users')->row_array();
                }else{
                    return false;
                }
            case 'email':
                return 'email';
            case 'phone':
                return 'phone';
            case 'both':
                return 'both';
        }
        
    }
    
    public function emailPhoneValidation($email, $phone){
		
			
       if($email != ''){
       	    $this->db->where('email', $email);
            $result1 = $this->db->get('users');
       }
        
        
        $this->db->where('phone', $phone);
        $result2 = $this->db->get('users');
        
        $error = 0;
        
        if($email != ''){
        	if($result1->num_rows() != 0){
           		 $error += 1;
        	}
        }
        if($result2->num_rows() != 0 || $phone == '098792010' || $phone == '096792010' || $phone == '041792010'){
            $error += 2;
        }
        
        switch ($error) {
            case 0:
                return 1;
            case 1:
                return 'email';
            case 2:
                return 'phone';
            case 3:
                return 'both';
            default:
                break;
        }        
    }


    public function passwordChange($email, $password){
        $data = array(
            'password' => md5($password),
            'last_recover' => date('Y-m-d H:i:s'),
            );
        if(is_numeric($email)){
            $this->db->where('phone', $email);
        }else{
            $this->db->where('email', $email);
        }
                
        return $this->db->update('users', $data);
    }
    
    public function passwordRecover($email){
        $this->db->select('id');
        if(is_numeric($email) && strlen($email) == 9){
            $this->db->where('phone', $email);
        }elseif(filter_var($email, FILTER_VALIDATE_EMAIL)){
       	    $this->db->where('email', $email);
        }else{
            return false;
        }
        $result = $this->db->get('users');
        return $result->num_rows();        
    }
    
    public function getlastRecover($email){
        $this->db->select('last_recover');
        if(is_numeric($email) && strlen($email) == 9){
            $this->db->where('phone', $email);
        }else{
       	    $this->db->where('email', $email);
        }
        $result = $this->db->get('users');
        $last = $result->row_array(); 
        
        if(!$last['last_recover'] || $last['last_recover'] == NULL){
            return true;
        }
        
        $ts1 = strtotime(date('Y-m-d H:i:s'));
        $ts2 = strtotime(date($last['last_recover']));

        $seconds_diff = $ts1 - $ts2;

        if(floor($seconds_diff/3600/24) >= 1){
            return true;
        }
        
        return false;
        
    }
}