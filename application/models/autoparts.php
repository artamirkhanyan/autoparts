<?php

class Autoparts extends CI_Model{
    
    private $headersTemplate;
    
    public function setTemplateToCsvMap($headersTemplate){
        $this->headersTemplate = $headersTemplate;
    }
    
    public function insert($data){
        //Set parts category etc. BMW, Opel... as uploaded file name
        $category = $this->session->userdata('csv_name');
        //Get File name without extension
        $category = explode(".", $category)[0];               

        foreach ($data as $key => $value) {
            $i = 0;
            foreach ($value as $key1 => $value1) {
                $_key = array_keys($this->headersTemplate)[$i++];
                if($this->headersTemplate[$_key] == "Empty"){
                    $_value = "";
                }else{
                    $_value = $value[$this->headersTemplate[$_key]];
                }
                
                //Delete all quotes from $_value
                $_value = str_replace('"', "", $_value);
                $_value = str_replace("'", "", $_value);
                $_value = str_replace(",", "", $_value);
                
                if($_key == 'part_number'){
                    $_value = preg_replace('/[^\da-z]/i', '', $_value);
                }
                
                $this->db->set($_key, $_value);
            }

            $this->db->set("from", 'armenia');            
            //$this->db->set("category", $category); 
            $this->db->set("shop", $this->session->userdata('shop_name'));
            $this->db->set("currency", $this->session->userdata('currency')); 
            $this->db->insert('autoparts');                       
        }
    }
    
    public function insertNew($data){
        //Set parts category etc. BMW, Opel... as uploaded file name
        $category = $this->session->userdata('csv_name');
        //Get File name without extension
        $category = explode(".", $category)[0];     
        
        foreach ($data as $key => $value) {
            
            foreach ($this->headersTemplate as $field => $val) {
                
                if($val == "Empty"){
                    $val = "";
                }else{
                    $val = $value[$val];
                }
                
                //Delete all quotes from value
                $val = str_replace('"', "", $val);
                $val = str_replace("'", "", $val);
                $val = str_replace(",", "", $val);
                $val = str_replace(";", "", $val);
                
                if($field == 'part_number'){
                    $val = preg_replace('/[^\da-z]/i', '', $val);
                }
                
                $this->db->set($field, $val);
            }
            
            $this->db->set("from", 'armenia');
            $this->db->set("shop", $this->session->userdata('shop_name'));
            $this->db->set("currency", $this->session->userdata('currency')); 
            $this->db->insert('autoparts');                       
        }
    }
    
    public function delete($shopName){
        $this->db->where('shop', $shopName);
        $this->db->delete('autoparts');
    }
    
    public function selectByPartNumber($part_number){
        if($part_number == '')
            return array();
        
        $this->db->select();
        $this->db->where('part_number', $part_number);
		$this->db->or_where('part_number', preg_replace('/[^\da-z]/i', '', $part_number));
		
        $result = $this->db->get('autoparts');
        return $result->result_array();
    }
    
     public function selectByPartId($part_id){
        if($part_id == '')
            return array();
        
        $this->db->select();
        $this->db->where('id', $part_id);
		
        $result = $this->db->get('autoparts');
        return $result->row_array();
    }
    
    public function getPriceFromByPartId($part_id){
        if($part_id == '')
            return array();
        
        $this->db->select('price, from');
        $this->db->where('id', $part_id);
        $result = $this->db->get('autoparts');
        return $result->row_array();
    }
    
    public function selectFromCart($array_of_id_and_count){
        if(!$array_of_id_and_count)
            return array();
        
        $this->db->select();
        $this->db->where_in('id', array_keys($array_of_id_and_count));
        $result = $this->db->get('autoparts');
        return $result->result_array();
    }
    
    /**
     * 'price'
     * 'weight'
     * 'income'                
     * @return assoc array
     */
    public function getKoefficient(){
        $this->db->select();
        $result = $this->db->get('koefficient');
        return $result->row_array();
    }
    
    public function setKoefficient($data){
        $tmp_data = array();
        foreach ($data as $key => $value) {
            $tmp_data[$key] = $value;
        }
        $this->db->update('koefficient', $tmp_data);
    }
    
    public function weightByNumber($part_number){
        $this->db->select('weight');
        $this->db->where('part_number', $part_number);
        $result = $this->db->get('weights');
        return $result->row_array();
    }
    
    public function allWeights($limit, $ofset){
        $this->db->order_by('id', 'desc');
        $result = $this->db->get('weights', $limit, $ofset);
        return $result->result_array();
    }
    
    public function getWeightsCount(){
        return $this->db->count_all('weights');
    }
    
    public function searchWeight($key){
        $this->db->like('part_number', $key, 'after'); 
        $q = $this->db->get('weights');
        
        return $q->result_array();
    }
    
    public function addWeight($part_number, $weight){
        $part_number = trim($part_number);
        
        $this->db->where('part_number', $part_number);
        $this->db->delete('weights');
        //-------------------------------------------
        if($weight != 0){
            $this->db->insert('weights', array(
                'part_number' => $part_number,
                'weight' => $weight
            ));
        }
    }
    
    
    public function setExchangeRate($rate){
        
        return $this->db->update('koefficient', array('exchange_rate' => $rate));
        
    }
    
    public function getExchangeRate(){
        
        $this->db->select("exchange_rate");
        $result = $this->db->get('koefficient');
        $rate = $result->row_array();
        
        $rate = number_format((float) $rate['exchange_rate'], 2, '.', '');
            
        $this->session->set_userdata('rate', $rate);
        return $this->session->userdata('rate');
        
    }
    
    public function getCBAExchangeRate(){
        /*
        $options = array( 
            'soap_version'    => SOAP_1_1, 
            'exceptions'      => true, 
            'trace'           => 1, 
            'wdsl_local_copy' => true
            );
        
        try {
            $client = new SoapClient("http://api.cba.am/exchangerates.asmx?wsdl", $options);  
            $response = $client->ExchangeRatesLatestByISO(['ISO'=>'USD']);
            $rate = $response->ExchangeRatesLatestByISOResult->Rates->ExchangeRate->Rate;
            
            $rate = number_format((float) $rate, 2, '.', '');
            return $rate;
            
        } catch (Exception $e) {
            return false;
        }
         * 
         */
    }
}     