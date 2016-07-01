<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {
    
    private $_TOKEN;
    private $_RAND_KEY = 'd2f5g62d1d1d5f6g3g0r1r5y6y21';
    public $bankLang;
    public function __construct() {
        parent::__construct();  
        
        $this->load->driver('cache');

        if (!$this->session->userdata('announcement'))
        {
            $this->load->model('announcement');
            $result = $this->announcement->getAnnouncement();
            $set = !empty($result) ? $result['text'] : 'empty';
            $this->session->set_userdata('announcement', $set);
        }
        
        $this->_TOKEN = md5($this->_RAND_KEY);
        
        if(!$this->session->userdata('lang') || $this->session->userdata('lang')=='armenian'){
            $this->lang->load('site', 'armenian');
            $this->bankLang = 'am';
        }else{
            $this->lang->load('site', 'russian');
            $this->bankLang = 'ru';
        }
        
        
        $this->load->library('layout');
        $this->layout->setLayout('layout/user_layout');        
    }
    
    public function index(){
        $data['pTitle'] = $this->lang->line('welcome');
        $this->layout->view('welcome_message', $data);
    }

    public function part($part_num = NULL){
        if(empty($this->input->get('num'))){
            if($part_num){ //part number is not empty
                echo 'Part Number is: '.$part_num;               
            }else{
                echo 'Part Number is empty';
            }
        }else{            
             $this->session->set_userdata('search_key', $this->input->get('num'));
             $this->search();
        }
    }
    
    public function search() {
        $data['search_result'] = FALSE;
        
        //Load csvimport lib for getting headers for search result table headers
        $this->load->library('csvimport');
        //Save csv file headers into the $data
        $data['headers'] = $this->csvimport->headers_template;

        //Check if the search was clicked
        if(strlen($this->input->get('parts_search')) >= 3 || strlen($this->session->userdata('search_key')) >= 3){            
            //Load autoparts model
            $this->load->model('autoparts');
            if($this->input->get('parts_search')){
                //Get search key value
                $search_key = htmlspecialchars(trim($this->input->get('parts_search')));
                //Save search_key in session for refreshing page
                $this->session->set_userdata('search_key', $search_key);                 
            }else{
                $search_key = $this->session->userdata('search_key');
            }
            
            if(!$this->session->userdata('rate')){
                $this->autoparts->getExchangeRate();           
            } 
            
            //Reset current_part_weight and current_part_weight_isSet
            if($this->session->userdata('current_part_weight'))
                $this->session->unset_userdata('current_part_weight');           
            
            //Find parts from db by search key            
            $data['search_result'] = $this->autoparts->selectByPartNumber($search_key);                                                            

            if($srcbarma = $this->searchInBarma($search_key)){
                $this->mergeSearchResults($data['search_result'], $srcbarma);
            }                            
            
            //Меняем все веса со значениями '-' на 1.00
            //Записываем значение самого большого веса в сессию (current_part_weight)
            $this->fixWeightZeroValue($data['search_result']);
            
            //Save search result in session
            if($this->session->userdata('current_search_result')){
                $this->session->unset_userdata('current_search_result');
            }
            $this->session->set_userdata('current_search_result', $data['search_result']);  
            
            //Get price koefficients
            $data['koefficients'] = $this->autoparts->getKoefficient();
        }else{  
           //Was not searched
        }
        $this->layout->view('search', $data);
    }  
    
    private function rateConvertForCart(Array &$arr){
        
        $this->load->model('autoparts');
        if(!$this->session->userdata('rate')){
            $this->autoparts->getExchangeRate();   
        }        
        $rate = (float)$this->session->userdata('rate');
        foreach ($arr as $key => $value) {
            if(!is_array($value))
                continue;
            foreach ($value as $key1 => $value1) {
                $arr[$key][$key1]['price'] = number_format((float) ( (float)($value1['price'])*$rate ), 2, '.', '');
            }            
        }
    }
    
    private function getBarmaLogin(){
        $this->load->model('admin_auth');
        return $this->admin_auth->getBaramaAuth();
    }
    
    private function searchInBarma($part_number){
        //echo '<meta charset="UTF-8">';
        $options = array( 
            'soap_version'    => SOAP_1_1, 
            'exceptions'      => true, 
            'trace'           => 1, 
            'wdsl_local_copy' => true
            );
                
        try {            
            $client = new SoapClient("http://barmaparts.com/api/trade?wsdl", $options);
        } catch (Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }
        
        $auth = $this->getBarmaLogin();
        
        $userData['email']    = $auth->barmalogin;
        $userData['password'] = $auth->barmapass;       
        
        $request['article']         = $part_number;
        $request['findSubstitutes'] = true;
                
        try {
            /*OLD VERSION*/
//            $response = $client->getPriceList($userData, $request); 
//            //var_dump($response);exit;
//            return $response;
            
            
            /* NEW VERSION */
            
            $response = $client->getCatalogList($userData, $request);
			
            $emiratsOriginal = array();
            $emiratsAnalog = array();
            $russiaAnalog = array();
            $russiaOriginal = array();

            $original = array();
            $analog = array();
            $fullOriginal = array();
            $fullAnalog = array();

            
            if(is_array($response->arrayCatalog) && isset($response->arrayCatalog[0])){
                
                foreach ($response->arrayCatalog as $value){
					$original = array();
					$analog = array();
                    if(isset($value->priceListAnalog->arrayPrice)){
                            $analog = $value->priceListAnalog->arrayPrice;
                            if(!is_array($analog)){
                                    $analog = array($analog);
                            }
                            if(preg_match("/РОССИЯ/", $value->catalogName)){
                                $analog = $this->markRussia($analog);
                            }
                    }
                    if(isset($value->priceListOriginal->arrayPrice)){
                            $original = $value->priceListOriginal->arrayPrice;
                            if(!is_array($original)){
                                    $original = array($original);
                            }
                            if(preg_match("/РОССИЯ/", $value->catalogName)){
                                $original = $this->markRussia($original);
                            }
                    }
                    
                    $fullOriginal = array_merge($fullOriginal, $original);
                    $fullAnalog = array_merge($fullAnalog, $analog);
                }
                
                
                $original = $fullOriginal;
                $analog = $fullAnalog;

            }else{
                if(isset($response->arrayCatalog->priceListOriginal->arrayPrice)){
                        if(is_array($response->arrayCatalog->priceListOriginal->arrayPrice)){
                                $original = $response->arrayCatalog->priceListOriginal->arrayPrice;
                        }else{
                                $original = array($response->arrayCatalog->priceListOriginal->arrayPrice);
                        }
                        if(preg_match("/РОССИЯ/", $response->arrayCatalog->catalogName)){
                                $original = $this->markRussia($original);
                        }
                }

                if(isset($response->arrayCatalog->priceListAnalog->arrayPrice)){
                        if(is_array($response->arrayCatalog->priceListAnalog->arrayPrice)){
                                $analog = $response->arrayCatalog->priceListAnalog->arrayPrice;
                        }else{
                                $analog = array($response->arrayCatalog->priceListAnalog->arrayPrice);
                        }
                        if(preg_match("/РОССИЯ/", $response->arrayCatalog->catalogName)){
                                $analog = $this->markRussia($analog);
                        }
                }
            }
            
            return $this->maxWaight(array_merge($original, $this->markAnalog($analog)));
            
            
            /* END NEW VERSION */
            
            
        } catch (Exception $e) {
           // echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }
        
    }
    
    private function maxWaight($details){
        $waight = [];
        foreach($details as $detail){
            if(is_string($detail->detailNum)){
                $detailNum = strtolower($detail->detailNum);
            }else{
                $detailNum = $detail->detailNum;
            }
            if(isset($detail->weight) && $detail->weight > 0){
                if(isset($waight[$detailNum])){
                    $waight[$detailNum] = max($waight[$detailNum], $detail->weight);
                }else{
                    $waight[$detailNum] = $detail->weight;
                }
            }else{
                $waight[$detailNum] = isset($waight[$detailNum]) ? $waight[$detailNum] : 0;
            }
        }
            
        $this->load->model('autoparts');
        foreach ($waight as $num => $weig){
            //if($weig <= 0){
                $newWeig = $this->autoparts->weightByNumber($num);
                if(isset($newWeig['weight'])){
                    $waight[$num] = number_format( (double)$newWeig['weight'], 3, '.', '');
                }
            //}
        }
        //var_dump($waight);exit;
        foreach($details as $key => $detail){
            if(is_string($detail->detailNum)){
                $detailNum = strtolower($detail->detailNum);
            }else{
                $detailNum = $detail->detailNum;
            }
            //if(!isset($detail->weight) || $detail->weight < 0.0001){
                $details[$key]->weight = $waight[$detailNum];
            //}
            
        }
        
        return $details;
    }
    
    private function markAnalog($details){
        if(count($details) < 1)
            return $details;
        
        foreach($details as $key => $val){
            $details[$key]->analog = true;
        }
        return $details;
    }
    
    private function markRussia($details){
        if(count($details) < 1)
            return $details;
        
        foreach($details as $key => $val){
            $details[$key]->catalog = 'russia';
        }
        return $details;
    }
    
    private function fixWeightZeroValue(Array &$arr){
        foreach ($arr as $key => $value) {
            if($arr[$key]['inventory'] == '0' || $arr[$key]['inventory'] == '>100'){
                unset($arr[$key]);
                continue;
            }
            
            continue;
            
            $tmp_weight = &$arr[$key]['weight'];
            
            //var_dump($tmp_weight);
            if(!$this->session->userdata('current_part_weight')){
                $this->session->set_userdata('current_part_weight', $tmp_weight);
                $arr[$key]['new_waight'] = $tmp_weight;
            }
            else{
                if($this->session->userdata('current_part_weight') < $tmp_weight){
                    $this->session->set_userdata('current_part_weight', $tmp_weight);
                    $arr[$key]['new_waight'] = $tmp_weight;
                }
            }

        }
        $this->session->set_userdata('current_part_weight_isSet', true);
    }    
    
    private function mergeSearchResults(Array &$arr1, $arr2){
        foreach ($arr2 as $key => $value) {
            if(count($value) > 1){
                foreach ($value as $key1 => $value1) {
                    $tmp_array =  array();
                    $tmp_array['id']          = $value1->priceId;
                    $tmp_array['part_number'] = $value1->detailNum;
                    $tmp_array['description'] = (isset($value1->detailName))?$value1->detailName:"";
                    $tmp_array['makerName']   = $value1->makerName;
                    $tmp_array['price']       = $value1->price;
                    $tmp_array['inventory']   = $value1->quantity;
                    if( isset($value1->weight) && (float)$value1->weight == 0 || !isset($value1->weight)){
                        $tmp_array['weight'] = number_format (0, 3, ".", ",");
                    }
                    else 
                        $tmp_array['weight'] = number_format ((float)$value1->weight, 3, ".", ",");

                    $tmp_array['delivery']    = $value1->delivery;
                    $tmp_array['from']        = $value1->region;
                    $tmp_array['category']    = 'barma';
                    $tmp_array['catalog']      = isset($value1->catalog) ? 'russia' : false;
                    $tmp_array['analog']      = isset($value1->analog) ? true : false;

                    $arr1[] = $tmp_array;            
                }
            }else{
                $tmp_array =  array();
                    $tmp_array['id']          = $value->priceId;
                    $tmp_array['part_number'] = $value->detailNum;
                    $tmp_array['description'] = (isset($value->detailName))?$value->detailName:"";
                    $tmp_array['makerName']   = $value->makerName;
                    $tmp_array['price']       = $value->price;
                    $tmp_array['inventory']   = $value->quantity;
                    if( isset($value->weight) && (float)$value->weight == 0 || !isset($value->weight)){
                        $tmp_array['weight'] = number_format (0, 3, ".", ",");
                    }
                    else 
                        $tmp_array['weight'] = number_format ((float)$value->weight, 3, ".", ",");

                    $tmp_array['delivery']    = $value->delivery;
                    $tmp_array['from']        = $value->region;
                    $tmp_array['category']    = 'barma';
                    $tmp_array['catalog']     = isset($value->catalog) ? 'russia' : false;
                    $tmp_array['analog']      = isset($value->analog) ? true : false;

                    $arr1[] = $tmp_array;  
            }
        } 
    }
    
    private function addItemToBarmaCart(Array $partDetails, $quantity){
        return true;
//        echo '<meta charset="UTF-8">';
//        $options = array( 
//            'soap_version'    => SOAP_1_1, 
//            'exceptions'      => true, 
//            'trace'           => 1, 
//            'wdsl_local_copy' => true
//            );
//
//        $client = new SoapClient("http://barmaparts.com/api/trade?wsdl", $options);
//        $userData['email']    = 'surenkhudoyan@mail.ru';
//        $userData['password'] = 'madagaskar';        
//        
//        $part['priceId']         = $partDetails['id'];
//        $part['detailNum']       = $partDetails['part_number'];
//        $part['makerName']       = $partDetails['makerName'];
//        $part['quantity']        = (int)$quantity;
//        $part['price']           = $partDetails['price'];
//        $part['reference']       = '';
//                
//        try {
//            $response = $client->addToBasket($userData, $part);
//            return true;            
//        } catch (Exception $e) {
//            //echo 'Caught exception: ',  $e->getMessage(), "\n";
//        }
    }

    public function addToCart(){
        //If user_cart doesn't exist we create it                      
        if(empty($this->session->userdata('user_cart'))){
            
            //Array for parts id and count were added into the cart
            $parts = array();
            //Array for the parts in cart from ower db 
            $parts['owndb'] = array();
            //Array for the parts in cart from barma.com db 
            $parts['externaldb'] = array();
            
            $this->session->set_userdata('user_cart', $parts);
        }
        
        $current_items_in_cart = $this->session->userdata('user_cart');

        $part_id_category = $this->input->post('part_id');
        $part_id       = explode('_', $part_id_category)[0];
        $part_category = explode('_', $part_id_category)[1];
        
        $part_count = $this->input->post('part_count');
        
        if(array_key_exists($part_id, $current_items_in_cart)){
            return false;
        }else{
            if($part_category == 'barma'){
                //Полная информация о детали из сессии в который мы до этого сохранили поиск
                $added_part_details = $this->getBarmaPartDetailsFromSessionForCart( ( array($part_id => $part_id))); 
                //Добавляем в массив данных о детали новое данное кол-во выбранного товара
                $added_part_details[$part_id]['part_count'] = $part_count;

                $current_items_in_cart['externaldb'][$part_id.date('His')] =  $added_part_details[$part_id];
            }else{
                 //Полная информация о детали из сессии в который мы до этого сохранили поиск
                $added_part_details = $this->getBarmaPartDetailsFromSessionForCart( ( array($part_id => $part_id)));
               
                //Добавляем в массив данных о детали новое данное кол-во выбранного товара
                $added_part_details[$part_id]['part_count'] = $part_count;
                
                $current_items_in_cart['owndb'][$part_id] =  $added_part_details[$part_id]; 
            }            
        }                       
        $this->session->set_userdata('user_cart',$current_items_in_cart); 
        
        $parts_count_in_cart = count($current_items_in_cart['owndb']) + count($current_items_in_cart['externaldb']);
        echo $parts_count_in_cart;
    }
    
    public function editCartItemCount(){
        
        $part_id    = $this->input->post('part_id');
        $part_count = $this->input->post('part_count');
        
        $cart = $this->session->userdata('user_cart');
        
        
        if(array_key_exists($part_id, $cart['owndb'])){
            $cart['owndb'][$part_id]['part_count'] = $part_count;
            $this->session->set_userdata('user_cart', $cart);
        }elseif(array_key_exists($part_id, $cart['externaldb'])){
            $cart['externaldb'][$part_id]['part_count'] = $part_count;
            $this->session->set_userdata('user_cart', $cart);
        }
        
        echo 1;
    }


    public function clearCart(){
        if($this->session->userdata('user_cart')){
            $this->session->unset_userdata('user_cart');
        }
        echo 1;
    }

    public function cart(){        
        $this->load->library("csvimport"); 
        $data['pTitle'] = $this->lang->line('basket');
        
        $data['headers'] = $this->csvimport->headers_template; 
        $data['headers']['count'] = 'Кол-во';
        $data['headers']['action'] = 'Удалить';
        
        $this->load->model('autoparts');

        //Get full info about each item in cart      
        $data['search_result']['owndb'] = $this->session->userdata('user_cart')['owndb'];                
        $data['search_result']['externaldb'] = $this->session->userdata('user_cart')['externaldb'];
        
//        var_dump($data['search_result']);

        //Get price koefficients
        $data['koefficients'] = $this->autoparts->getKoefficient();
        
        $this->layout->view('cart' ,$data);
    }
    
    /**
     * $key -> part_id
     * $value -> part_count 
     * @return array
     */
    private function getPartDetailsFromBarmaForCart($array_of_partid){ 
        $tmp_arr = array();
        if(!$array_of_partid)
            return $tmp_arr;
        
        $searched_results = $this->session->userdata('current_search_result');
        $parts_id = array_keys($array_of_partid);
        
        foreach ($searched_results as $key => $value) {
            if(in_array($value['id'], $parts_id)){
                $tmp_arr[] = $value;
            }
        }
        return $tmp_arr;
    }
    
    /**
     * $key -> part_id
     * $value -> part_count 
     * @return array
     */
    private function getBarmaPartDetailsFromSessionForCart($array_of_partid){ 
        $tmp_arr = array();
        
        if(!$array_of_partid)
            return $tmp_arr;
        
        $searched_results = $this->session->userdata('current_search_result');
        $parts_id = array_keys($array_of_partid);
        
        foreach ($searched_results as $key => $value) {
            if(in_array($value['id'], $parts_id)){
                $tmp_arr[$value['id']] = $value;
            }
        }
        return $tmp_arr;
    } 
    
    public function currency($valuta){
        if($valuta == 'usd'){
            $this->session->set_userdata('currency', 'usd');
        }else{
            $this->session->set_userdata('currency', 'amd');
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    
    public function deleteFromCart(){
        $item_id    = $this->input->post('item_id');
        $cart_items = $this->session->userdata('user_cart');        
        
        if(array_key_exists($item_id, $cart_items['owndb']) ){
            unset($cart_items['owndb'][$item_id]);
        }else{
            unset($cart_items['externaldb'][$item_id]);
        }
        $this->session->unset_userdata('user_cart');
        $this->session->set_userdata('user_cart', $cart_items);
        
    }
    
    public function buyCart(){
        if(!$this->session->userdata('user_cart')){
            redirect('/');
        }
        $data['error'] = FALSE;
        $data['validation'] = array(
            'order_id' => '',
            'f_name'   => '',
            'l_name'   => '',
            'email'    => '',
            'phone'    => '',
            'address'  => ''
        );                
        
        if($this->input->post('buy_submit')){
            
            $result = $this->buyDataValidator($data); // return array or empty array
            
            if($result){
                $this->load->model('orders');
                $orderId = $this->orders->addOrderDetails($data['validation']);
                if(is_numeric($orderId)){
                    $this->session->set_userdata('flashOrder', $orderId);
                    redirect(site_url('/checkout'));
                }
                redirect(site_url('/cart'));
            }else{
                $data['error'] = $this->lang->line('reg_error'); 
            }
        }
        if($this->session->userdata('user_id')){
            $this->load->model('users');
            $result = $this->users->getUserInfoById($this->session->userdata('user_id')['id']);
                      
            $data['info']['f_name']   = $result['f_name'];
            $data['info']['l_name']   = $result['l_name'];
            $data['info']['email']    = $result['email'];
            $data['info']['address']  = $result['address'];
            $data['info']['phone']    = $result['phone'];
            
        }
        $this->layout->view('buy_cart', $data);
                
    }
    
    private function buyDataValidator(&$data){
        $f_name   = $this->input->post('f_name');
        $l_name   = $this->input->post('l_name');
        $email    = $this->input->post('email');
        $phone    = $this->input->post('phone');
        $address  = $this->input->post('address');
        
        $f_name  = htmlspecialchars($f_name);
        $l_name  = htmlspecialchars($l_name);
        $email   = htmlspecialchars($email);
        $phone   = htmlspecialchars($phone);
        $address = htmlspecialchars($address);
        
        //For outputing the errors
        $data['validation'] = '';
        //First and Last name a-zA-z validation
        if (!preg_match("/^[a-zA-Z]*$/",$f_name) || $f_name=='') {
            $data['validation']['f_name'] = $this->lang->line('f_name_error'); 
        }else{
            $data['validation']['f_name'] = "";
        }
        if (!preg_match("/^[a-zA-Z -]*$/",$l_name) || $l_name=='') {
            $data['validation']['l_name'] = $this->lang->line('l_name_error');
        }else{
            $data['validation']['l_name'] = "";
        }
        //Adress validation
        if (!preg_match("/^[a-zA-Z0-9 -.\\\\]*$/",$address) || $address=='' || !$address) {
            $data['validation']['address'] = $this->lang->line('address_error');
        }else{
            $data['validation']['address'] = "";
        } 
        //Phone validation
        if (!preg_match("/^[0-9]*$/",$phone) || $phone=='' || !$phone || strlen($phone) != 9) {
            $data['validation']['phone'] = $this->lang->line('phone_error');
        }else{
            $data['validation']['phone'] = "";
        } 
        //Email address validation
        if($email !=''){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                //Invalid email adress
                $data['validation']['email'] = $this->lang->line('email_error');
            }else{
                $data['validation']['email'] = "";
            }   
        }else{
            $data['validation']['email'] = "";
        }
        
        //If validation array is empty
        if(!empty(join(array_values($data['validation'])))){
            return false;
        }else{
            
            //Get all items id with count in cart from session
            $cart = $this->session->userdata('user_cart'); 
        
            if($this->session->userdata('user_cart')){
                $this->session->unset_userdata('user_cart');
            }

            $order_id = $this->createOrder($cart);
            
            $data['validation']['order_id'] = $order_id;            
            $data['validation']['f_name']  = $f_name;
            $data['validation']['l_name']  = $l_name;
            $data['validation']['email']  = $email;
            $data['validation']['address'] = $address;
            $data['validation']['phone']   = $phone;
            
            return true;
        }        
    }
    
    /**
     * @user_id
     * @username
     * @card_id
     * @transaction_id
     * @items_count
     * @total_price
     */
    private function createOrder($cart){
        
        $data = array();        
        $data['user_id']        = NULL;
        $data['username']       = 'guest';
        $data['card_id']        = '';
        $data['transaction_id'] = '';
        $data['items_count']    = 0;
        $data['total_price']    = 0;
        $data['discount']       = 0;
        $data['rate']           = $this->session->userdata('rate');
        
        $this->load->model('autoparts');
        $this->load->model('orders');        
        
        if($this->session->userdata('user_id')){
            $user_data = $this->session->userdata('user_id');
//            var_dump($user_data);exit;
            $data['user_id'] = $user_data['id'];
            $data['username'] = $user_data['email'];
        }
        
        $koefficients = $this->autoparts->getKoefficient();
                
        $items = array();
        
        if(!empty($cart['owndb'])){
           
            foreach ($cart['owndb'] as $key => $value) {
                $tmp_data         = $this->autoparts->selectByPartId($key);
                $tmp_price        = $tmp_data['price']; 
                $tmp_first_price  = $tmp_data['price']; 
                $currency         = isset($tmp_data['currency']) ? $tmp_data['currency'] : false;
                $tmp_from         = $tmp_data['from'];
                $tmp_weight       = $tmp_data['weight'];
                $tmp_count        = (int)$value['part_count'];
                
                
                
                if($currency == 'amd'){
                    $tmp_price = $tmp_first_price = $tmp_price/$this->session->userdata('rate');
                }
                
                if($tmp_from == 'armenia'){
                    $tmp_price = calculateTotalPriceForArmenia($tmp_price, $tmp_weight, $koefficients, 1, 'usd', 'cart', $this->session->userdata('user_id'));
                }else{
                    $tmp_price = calculateTotalPrice($tmp_price, $tmp_weight, $koefficients, 1, 'usd', 'cart', $this->session->userdata('user_id'));
                }
                
                
                $tmp_price = (float)number_format($tmp_price, 2, '.', '');
                
                $item_info = array();
                $item_info['part_number'] = $tmp_data['part_number'];
                $item_info['description'] = $tmp_data['description'];
                $item_info['makerName']   = $tmp_data['category'];
                $item_info['from']        = $tmp_data['shop'];
                $item_info['price']       = $tmp_price;
                $item_info['first_price'] = $tmp_first_price;
                $item_info['count']       = $tmp_count;
                $item_info['delivery']    = "2 - 6";
                $item_info['status']      = 'Ok';
                if($this->session->userdata('user_id')){
                    $item_info['discount'] = (float)$koefficients['arm_discount'];
                }
                
                
                $items[] = $item_info;
                $data['total_price']  += $tmp_price * $tmp_count;
                $data['items_count']  += $tmp_count;
            }
        }
                
        //------------------External-------------------------
                
        if(!empty($cart['externaldb'])){
            $array = $cart['externaldb'];//$this->getBarmaPartDetailsFromSessionForCart($cart['externaldb']);
            
            foreach ($array as $key => $value) {                                
                $tmp_data = $value;
                $status   = 'OK';

                
                $tmp_price        = $tmp_data['price']; 
                $tmp_first_price  = $tmp_data['price']; 
                $tmp_from         = $tmp_data['from'];
                $tmp_weight       = $tmp_data['weight'];
                $tmp_count        = (int)$tmp_data['part_count'];//(int)$cart['externaldb'][$key];

                if($tmp_from == 'armenia'){
                    $tmp_price = calculateTotalPriceForArmenia($tmp_price, $tmp_weight, $koefficients, 1, 'usd' ,'cart', $this->session->userdata('user_id'));
                
                    
                }else{
                    
                    if(isset($tmp_data['catalog']) && $tmp_data['catalog'] === 'russia'){
                        $koefficients['russian_weight'] = $koefficients['weight_russia'];
                    }else{
                        $koefficients['russian_weight'] = FALSE;
                    }
                    
                    $tmp_price = calculateTotalPrice($tmp_price, $tmp_weight, $koefficients, 1, 'usd', 'cart', $this->session->userdata('user_id'));
                    
                }
                
                
                $tmp_price = (float)number_format($tmp_price, 2, '.', '');
                
                $barmaStartDelivery = (int)$tmp_data['delivery'] + (int)$koefficients['delivery'];
                $barmaEndDelivery = $barmaStartDelivery+4;
                
                $item_info = array();
                $item_info['part_number'] = $tmp_data['part_number'];
                $item_info['description'] = $tmp_data['description'];
                $item_info['makerName']   = $tmp_data['makerName'];
                $item_info['from']        = $tmp_data['from'];
                $item_info['price']       = $tmp_price;
                $item_info['first_price'] = $tmp_first_price;
                $item_info['count']       = $tmp_count;
                $item_info['delivery']    = $barmaStartDelivery." - ".$barmaEndDelivery;
                $item_info['status']       = $status;
                if($this->session->userdata('user_id')){
                    $item_info['discount'] = (float)$koefficients['barma_discount'];
                }
                
                $items[] = $item_info;
                
                $data['total_price']  += $tmp_price * $tmp_count;
                $data['items_count']  += $tmp_count;
            
            }
           
        }
        
        //------------------External-------------------------
        
        
        $order_id = $this->orders->insertNewOrder($data);
        
        $this->createOrderItem($order_id, $items);
        
        return $order_id;
    }
    
    private function createOrderItem($order_id, $items){
        $this->load->model('autoparts');
        $this->load->model('orders');        

        foreach ($items as $key => $value) {
            $data = array();
            $data['order_id']    = $order_id;
            $data['part_number'] = $value['part_number'];
            $data['description'] = $value['description'];
            $data['makerName']   = $value['makerName'];
            $data['from']        = $value['from'];
            $data['price']       = $value['price'];
            $data['first_price'] = $value['first_price'];
            $data['count']       = $value['count'];
            $data['delivery']    = $value['delivery'];
            $data['status']      = $value['status'];
            $data['discount']    = $value['discount'] ? $value['discount'] : 0;
            
            $this->orders->insertNewOrderItem($data);
        }
    }    
   
    public function orders(){
        $data['orders'] = FALSE;
        $data['error'] = "";
        $data['pTitle'] = $this->lang->line('orders');
        //Был ли клин на кнопку Поиск
        if($this->input->post('order_search_submit')){
            //Если был клик то проверяем зарегестрирован или нет данный пользователь
            
            if($this->session->userdata('user_id')){
                $order_id = $this->input->post('order_search_id');
                if(!is_numeric($order_id)){
                    $data['error'] = $this->lang->line('orders_search_format_error');
                    $this->layout->view('myOrders', $data);
                    return;
                }
                $this->load->model('users');
                $data['orders'] = $this->users->getOrderById($order_id, $this->session->userdata('user_id')['id']);

                $this->layout->view('myOrders', $data);
            }else{
                $phone    = $this->input->post('order_search_phone');
                $order_id = $this->input->post('order_search_id');
                if(!is_numeric($order_id) || !is_numeric($phone)){
                    $data['error'] = $this->lang->line('orders_search_format_error');
                    $this->layout->view('myOrders', $data);
                    return;
                }
                
                $this->load->model('users');
                $response = $this->users->gerUserOrdersByOrderIdPhone($phone, $order_id);

                if(isset($response['error'])){
                    $data['error'] = $response['error'];
                }else{
                    $data['orders'] = $response;
                }
                
                $this->layout->view('myOrders', $data);
            }
        }else{
            if($this->session->userdata('user_id')){
                $user_id  = $this->session->userdata('user_id');
                $user_id = $user_id['id'];
                
                $this->load->model('users');
                $data['orders'] = $this->users->gerUserOrdersById($user_id);

                $this->layout->view('myOrders', $data);
            }else{
                $this->layout->view('myOrders', $data);
            }            
        }
    }
    
    public function catalog(){
        $data['pTitle'] = $this->lang->line('catalog');
        $this->layout->view('catalog', $data);
    }
    
    public function aboutUs(){
        $data['pTitle'] = $this->lang->line('about');
        $this->layout->view('pages/aboutus', $data);
    }
    
    public function delivery(){
        $data['pTitle'] = $this->lang->line('delivery');
        $this->layout->view('pages/delivery', $data);
    }
    
    public function method(){
        $data['pTitle'] = $this->lang->line('method');
        $this->layout->view('pages/method', $data);
    }
    
    public function contacts(){
        $data['pTitle'] = $this->lang->line('contact');
        $this->layout->view('pages/contacts', $data);
    }
    
    
    public function checkout(){
        $data['error'] = FALSE;
        if(!$this->session->userdata('flashOrder')){
           redirect('/cart');
        }
        $orderID = (int)$this->session->userdata('flashOrder');
        
        $this->load->model('users');
        $orderDetails = $this->users->gerUserOrdersByOrderId($orderID);
        
        if(empty($orderDetails)){
            redirect('/cart');
        }
        
        $price2pay = $orderDetails[$orderID]['total_price']*$orderDetails[$orderID]['rate'];
        
        $price2pay = number_format((double) $price2pay, 2, '.', '');
        
        $data['order_id'] = $orderID;
        $data['orderDetails'] = $orderDetails;
        $data['price2pay'] = $price2pay;
        
        $this->session->set_userdata('flashAmount', $price2pay);
        /*
         * FOR TESTING ONLY
         */
        //$price2pay = 1.01;
        /*-----------------*/
        
        $form_url = 'https://epay.arca.am/svpg/Merchant2Rbs';
        $data_to_post = array(
            'MERCHANTNUMBER' => urlencode('34006543       34536543'),
            'ORDERNUMBER' => $orderID,
            'AMOUNT' => (int)($price2pay*100),
            'BACKURL' => urlencode(base_url('bankReturn')),
            '$ORDERDESCRIPTION' => 'Payment for order #'.$orderID,
            'LANGUAGE' => 'RU',//$this->bankLang,
            'DEPOSITFLAG' => 1,
            'MERCHANTPASSWD' => 'lazY2k',
            'MODE' => 1
        );
        $fields_string='';
        foreach($data_to_post as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');        
        
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL, $form_url);
        curl_setopt($curl,CURLOPT_POST, count($data_to_post));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

        $result = curl_exec($curl);
        //$info = curl_getinfo($curl);
        curl_close($curl);
        
        
        if (strpos($result,'System error') !== false ||  strpos($result, 'odernumber')!== false) {
            $data['error'] = $result;
            $this->layout->view('checkout', $data);
            return;
        }
        
        
        $this->load->model('orders');
        $this->orders->addTransactionId($orderID, trim($result));
        
        $data['result'] = $result;
        $data['lang'] = $this->bankLang;
        $this->layout->view('checkout', $data);
    }
    
    public function bankReturn(){
        
        $respons = $this->input->get();
        
        if(empty($respons) || !$this->session->userdata('flashOrder')){
            redirect('/');
        }
        $data['success'] = FALSE;
        $orderId = (int)$this->session->userdata('flashOrder');
        $amount = $this->session->userdata('flashAmount');
        $this->session->unset_userdata('flashOrder');
        $this->session->unset_userdata('flashAmount');
        $this->load->model('orders');
        
        if($respons['ACTION_CODE'] == '000' && $respons['ANSWER'] == 'PrimaryCode="0" SecondaryCode="0"'){
            $order = $this->orders->setAsPayed(trim($respons['MDORDER']));
            $this->orders->sendSMS2Owner($order['id'], $amount);
            $data['success'] = TRUE;
            $data['order'] = $order;
        }else{
            //$this->orders->deleteOrderBytransferId(trim($respons['MDORDER']));
            $data['success'] = FALSE;
        }
        
        $this->layout->view('bankReturn', $data);
        
        /* success respone example
        array (size=6)
            'MDORDER' => string '66101-104-46-54101-10166-11081-6612-79-552272_p1' (length=48)
            'ANSWER' => string 'PrimaryCode="0" SecondaryCode="0"' (length=33)
            'CAVV' => string 'AAABBzNxiQAAAAAAAnGJAAAAAAA=' (length=28)
            'XID' => string 'MDAwMDAwMDE0MzE4MTQ5NjEyODI=' (length=28)
            'ECI' => string '05' (length=2)
            'ACTION_CODE' => string '000' (length=3)
        
        */
        
        /* error url
            http://avtomaser.dev/bankReturn?MDORDER=65-5841-74-555-61406134601540-49-34-125_p1&ANSWER=PrimaryCode%3D%2234%22+SecondaryCode%3D%221014%22&CAVV=null&XID=null&ECI=06&ACTION_CODE=005
        */
    }
    
    public function _info(){
    	//$this->load->model('orders');
    	//$this->orders->sendSMS2Owner(1236, '410522.64');
        phpinfo();
    }
	
    public function sendRequest(){
        if(empty($this->input->post())){
            redirect('/');
        }
        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $phone = $this->input->post('phone');
        $vincode = $this->input->post('vincode');
        $model = $this->input->post('model');
        $year = $this->input->post('year');
        $message = $this->input->post('message');
        
        if(!empty($name) && !empty($vincode) && !empty($model) && !empty($year) && !empty($message) && (!empty($phone) || !empty($email))){
            $this->load->model('users');
			$this->users->sendreqemail($name, $email, $phone, $vincode, $model, $year, $message);
            echo json_encode(array('error'=>false, 'mess'=>$this->lang->line('emailSent')));
        }else{
            echo json_encode(array('error'=>true, 'mess'=>$this->lang->line('reg_error')));
        }
    }
    
    public function get404(){
        
        $this->layout->view('pages/get404');
    }
    
    
}