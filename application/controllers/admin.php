<?php if( !defined("BASEPATH")) exit('No direct script access allowed');

class Admin extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        //We are not in /admin/index
        if(!empty($this->uri->segment(2))){
            if(!$this->session->userdata('admin_id')){
               redirect('/admin'); 
            }
           
            if(!$this->session->userdata('rate')){
               $this->load->model('autoparts'); 
               $this->autoparts->getExchangeRate();
            }
            
            if(!$this->session->userdata('lang') || $this->session->userdata('lang')=='armenian'){
                $this->lang->load('site', 'armenian');
            }else{
                $this->lang->load('site', 'russian');
            }
           
            $this->load->library('layout');
            $this->layout->setLayout('layout/admin_layout');
        }
    }
    
    public function index() {   
        
        $this->load->library('form_validation');
        $data['error'] = FALSE;
        $this->form_validation->set_rules('adminname', 'Username', 'required|alpha');
        $this->form_validation->set_rules('adminpassword', 'Password', 'required');
        
        if($this->form_validation->run() == FALSE) {            
            //echo validation_errors();
        }else{ 
            if($this->_loginDataValidator()){
                //Save userId in the Session
                $this->session->set_userdata('admin_id',$this->_loginDataValidator());
                redirect('admin/allOrders');
            }else{
                $data['error'] = "Սխալ հասցե կամ գաղտնաբառ:";
            }
            
        }  
        
        $this->load->view('login', $data);
    }
    
    public function _loginDataValidator() {         
        $username = $this->input->post('adminname');
        $password = $this->input->post('adminpassword');
        
        $this->load->model('admin_auth');

        if($this->admin_auth->login($username, $password)){
            return $this->admin_auth->login($username, $password);
        }else{
            return false;
        }
    }
    
    public function logOut() {
        $this->session->unset_userdata('admin_id');
        redirect('/admin');
    }
    
    
    public function rateConvert(Array &$arr){
        $this->load->model('autoparts');
        if(!$this->session->userdata('rate')){
            $this->autoparts->getExchangeRate();   
        }        
        $rate = (float)$this->session->userdata('rate');
        foreach ($arr as $key => $value) {
            $arr[$key]['price'] = number_format((float) ( (float)($value['price'])*$rate ), 2, '.', '');
        }
    }
    
    public function searchInBarma($part_number){
        echo '<meta charset="UTF-8">';
        $options = array( 
            'soap_version'    => SOAP_1_1, 
            'exceptions'      => true, 
            'trace'           => 1, 
            'wdsl_local_copy' => true
            );

        $client = new SoapClient("http://barmaparts.com/api/trade?wsdl", $options);
        //var_dump(get_class_methods($client));exit;
        $userData['email']    = 'surenkhudoyan@mail.ru';
        $userData['password'] = 'madagaskar';        
        
        $request['article']         = $part_number;
        $request['findSubstitutes'] = true;
        
        
        try {
            $response = $client->findDetail($userData, $request);
            //var_dump($response);
            return $response;
            
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    
    public function mergeSearchResults(Array &$arr1, $arr2){
        $tmp_array = array();
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
                        $tmp_array['weight'] = number_format (1, 3, ".", ",");
                    }
                    else 
                        $tmp_array['weight'] = number_format ((float)$value1->weight, 3, ".", ",");

                    $tmp_array['delivery']    = $value1->delivery;
                    $tmp_array['from']        = $value1->region;
                    $tmp_array['category']    = 'barma';

                    $arr1[] = $tmp_array;            
                }
            }
            else{
                $tmp_array =  array();
                $tmp_array['id']          = $value->priceId;
                $tmp_array['part_number'] = $value->detailNum;
                $tmp_array['description'] = (isset($value->detailName))?$value->detailName:"";
                $tmp_array['makerName']   = $value->makerName;
                $tmp_array['price']       = $value->price;
                $tmp_array['inventory']   = $value->quantity;
                if( isset($value->weight) && (float)$value->weight == 0 || !isset($value->weight)){
                    $tmp_array['weight'] = number_format (1, 3, ".", ",");
                }
                else 
                    $tmp_array['weight'] = number_format ((float)$value->weight, 3, ".", ",");

                $tmp_array['delivery']    = $value->delivery;
                $tmp_array['from']        = $value->region;
                $tmp_array['category']    = 'barma';

                $arr1[] = $tmp_array;  
            }
            
        }
    }
    
    public function addShop(){
        $this->load->model('shops');                                        
        
        $data['error'] = FALSE;
        if($this->input->post('addShop')){  
            $shopName = $this->input->post('shop_name');
            
            if(!preg_match("/^[a-zA-Z0-9 -._,]*$/",$shopName) || $shopName=='' || !$shopName) {
                $data['error'] = 'Խանութի անունը չի կարող պարունակել հատուկ սինվոլներ, բացի - . _ , :';
            }else{
                if($this->shops->shopExist($shopName)){
                    $data['error'] = 'Այս անունով խանութ արդեն ավելացված է:';
                }else{
                    $this->shops->addShop($shopName);   
                }
            }
        }
        
        $data['shops'] = $this->shops->getShops();
        
        $this->layout->view('addShop', $data);
    }
    
    public function deleteShop(){
        $this->load->model('shops');
        $this->load->model('autoparts');
        $this->shops->delete($this->input->post('shopId'));
        $this->autoparts->delete($this->input->post('shopName'));
    }

    public function upload() {
        //Get all shops from db
        $this->load->model('shops');
        $data['shops'] = $this->shops->getShops(); 
        $this->layout->view('upload', $data);
    }    

    public function csvUpload() {
        
        $data['error'] = FALSE;
        
        $uploadConf['upload_path'] = './public/csv/';
        $uploadConf['allowed_types'] = 'csv';                
        $this->load->library('upload', $uploadConf);        
        
       $uploadFilePath = "public/csv/".$this->input->post('upload_file');
       
        if($this->input->post('shop_name')){
            $shop_name = $this->input->post('shop_name');
        }else{
           redirect('admin/upload');           
        }
        
        if($this->input->post('currency')){
            $currency = $this->input->post('currency');
        }else{
           redirect('admin/upload');           
        }

       $this->session->set_userdata('shop_name', $shop_name);
       $this->session->set_userdata('currency', $currency);
       
       //Delete file with the same name as new uploaded file
        if (file_exists($uploadFilePath)) {
            unlink($uploadFilePath) or die('failed deleting: '.$uploadFilePath );
        }
        
        if( !$this->upload->do_upload()) {
            echo $this->upload->display_errors();
        }else {
            $csvFileName = $this->upload->data()['file_name'];
            $this->csvAnalyze($csvFileName, $this->input->post('shopCheckbox'));
        }              
    }
        
    public function csvAnalyze($fileName, $checkBox) {
        
        $data['template'] = FALSE;
        $this->load->library("csvimport");  

        $columns_header = $this->csvimport->get_array("public/csv/".$fileName, false, true, false, false ,true);
        $data['template'] = $this->csvimport->headers_template;
        $data['current_csv'] = $this->csvimport->my_column_headers;                
        
        //If template headers and user upload csv's headers have differance we go to csvDifference
        //else insert the data to the db
        //Write csv file name into the Session for csvDifference                        
        $this->session->set_userdata('csv_name', $fileName);
        
        if( !empty(array_diff_assoc(array_values($data['template']), array_values($data['current_csv']))) ){
            $this->session->set_userdata('checkobox', $checkBox);
            $this->layout->view('csvDifference',  $data);            
        }else{
            //var_dump($columns_header);
            //Load the model and set headers template
            $this->load->model('autoparts');
            $this->autoparts->setTemplateToCsvMap($data['template']);
            
            if($checkBox){
                $this->autoparts->insert($columns_header);
            }else{
                $this->autoparts->delete($this->session->userdata('shop_name'));
                $this->autoparts->insert($columns_header);
            }
            redirect('admin/upload');
        }
    }
    
    public function csvChangeApply() {
        if(!$this->session->userdata('csv_name'))
            redirect('admin/upload');
            
        $templateToCsvMap = $this->input->post();
        
        $this->load->library("csvimport");
        $columns_header = $this->csvimport->get_array("public/csv/".$this->session->userdata('csv_name'));        
        //Load the model and set headers template
        $this->load->model('autoparts');
        $this->autoparts->setTemplateToCsvMap($templateToCsvMap);
        if(!$this->session->userdata('checkobox')){
            $this->autoparts->delete($this->session->userdata('shop_name'));
        }
        $this->autoparts->insertNew($columns_header);
            echo 1;
    }
    
    public function securekoef(){
        $data['error'] = FALSE;
        if($this->input->post('secure')){
            if($this->input->post('securePass') == 'surenkhudoyan'){
                $this->session->set_userdata('flash_koef', 1);
                redirect('admin/setKoef');
            }else{
                $data['error'] = 'Գաղտնաբառը սխալ է:';
            }
        }
        
        $this->layout->view('adminpage/securekoef', $data); 
    }


    public function setKoef(){
        
        if(!$this->session->userdata('flash_koef') || $this->session->userdata('flash_koef') == 4){
                $this->session->unset_userdata('flash_koef');
                redirect('admin/securekoef');
        }else{
            $this->session->set_userdata('flash_koef', (int)$this->session->userdata('flash_koef')+1);
            if($this->input->post('change_koef')){
                
                $tmp_data = [];
                $tmp_data['price']      = htmlspecialchars($this->input->post('price'));
                $tmp_data['weight']     = htmlspecialchars($this->input->post('weight'));
                $tmp_data['weight_arm'] = htmlspecialchars($this->input->post('weight_arm'));
                
                $tmp_data['weight_russia'] = htmlspecialchars($this->input->post('weight_russia'));
                
                $tmp_data['delivery'] = htmlspecialchars($this->input->post('delivery'));
                $tmp_data['income']     = htmlspecialchars($this->input->post('income'));
                $tmp_data['income_arm']     = htmlspecialchars($this->input->post('income_arm'));

                $tmp_data['barma_discount'] = htmlspecialchars($this->input->post('barma_discount'));
                $tmp_data['arm_discount'] = htmlspecialchars($this->input->post('arm_discount'));
                
                $tmp_data['heavyBarma'] = $this->input->post('heavyBarma') ? 1 : 0;
                $tmp_data['heavyRussia'] = $this->input->post('heavyRussia') ? 1 : 0;

                //Unset all non numeric fields
                foreach ($tmp_data as $key => $value) {
                    if(!is_numeric($tmp_data[$key])){
                        unset($tmp_data[$key]);
                    }   
                }

                $this->load->model('autoparts');
                $this->autoparts->setKoefficient($tmp_data);
            }
            $this->load->model('autoparts');
            $data['koeficients'] = $this->autoparts->getKoefficient();
            $this->layout->view('koeficient', $data); 
        }
        
             
    } 
    
    public function currency($valuta){
        if($valuta == 'usd'){
            $this->session->set_userdata('currency', 'usd');
        }else{
            $this->session->set_userdata('currency', 'amd');
        }
//        header('Location: ' . $_SERVER['HTTP_REFERER']);
        redirect('admin/search');
        var_dump($this->session->userdata('currency'));
    }
    
    //Show all registrated users
    public function users(){
        $this->load->model('users');
        $data['users'] = $this->users->getUsers();
        
        $this->layout->view('users', $data);
    }
    
    public function userOrder($user_id){
        $this->load->model('users');
        $data['orders'] = $this->users->gerUserOrdersById($user_id);
        $this->layout->view('userOrder', $data);
        
    }
    
    public function allOrders($orderId = false){
        $this->load->model('orders');
        if($orderId && is_numeric($orderId)){
            
            if($this->input->post('markAsPayed')){
                $this->orders->setAsPayedById($orderId);
            }
            
            $order = $this->orders->getOrderById($orderId);
            
            $data['orders'] = $order;
            $this->layout->view('adminOreder', $data);
            
        }else{
            $data['orders'] = FALSE;
            $data['orders'] = $this->orders->getAllOrders();
            $this->layout->view('allOrders', $data);
        }
        
    }
    
    public function changeOrderStatus(){
        $order_id     = $this->input->post('order_id');
        $order_status = $this->input->post('order_status');
        
        $this->load->model('orders');
        return $this->orders->changeOrderStatus($order_id, $order_status);
    }
    
    public function deleteOrder($id){
        $this->load->model('orders');
        if($this->orders->deleteOrderById($id)){
            echo 1;
        }
        
    }
    
    public function announcement(){
        $this->load->model('announcement');
        if($this->input->post('addAnnouncement')){
            $text = trim($this->input->post('announcement'));
            $this->announcement->addAnnouncement($text);
            
        }elseif($this->input->post('deleteAnnouncement')){
            $this->announcement->deleteAnnouncement();
        }
        $result = $this->announcement->getAnnouncement();
        $data['text'] = !empty($result) ? $result['text'] : FALSE;
        $this->layout->view('adminpage/announcement', $data);
    }
    
    public function chagePass(){
        $data['message'] = FALSE;
        $data['message2'] = FALSE;
        if($this->input->post('changepass')){
            $pass = $this->input->post('pass');
            $pass2 = $this->input->post('pass2');
            if($pass2 != '' && $pass != '' && $pass == $pass2){
                $this->load->model('admin_auth');
                $this->admin_auth->changePass($pass);
                $data['message'] = 'Գաղտնաբառը փոխված է:';
            }else{
                $data['message'] = 'Գաղտնաբառը չի համընկնում կրկնության հետ:';
            }
        }else if($this->input->post('changebarmapass')){
            $barmalogin = trim($this->input->post('barmalogin'));
            $barmapass = $this->input->post('barmapass');
            if($barmalogin != '' && $barmapass != ''){
                $this->load->model('admin_auth');
                $this->admin_auth->changeBarmaPass($barmalogin, $barmapass);
                $data['message2'] = 'Web service-ի գաղտնաբառը և/կամ լոգինը փոխված է:';
            }else{
                $data['message2'] = 'Դատարկ է:';
            }
        }
        
        $this->layout->view('adminpage/changePass', $data);
    }
    
    public function activateUser($user_id){
        if(!is_numeric($user_id))
            return false;
        
        if($this->input->post('active') == 0 || $this->input->post('active') == 1){
            $this->load->model('user_auth');
            $this->user_auth->activateUser($user_id, $this->input->post('active'));
        }
    }
    
    public function weights($page = 0){
        $data['error'] = false;
        
        $this->load->model('autoparts');
        if($this->input->post('addWeight')){
            $weight = str_replace(',', '.', $this->input->post('weight'));
            
            $this->autoparts->addWeight($this->input->post('part_number'), $weight);
        }
        
        $count = $data['count'] = $this->autoparts->getWeightsCount();
        $limit = $data['limit'] = 40;
        $ofset = 0;
        
        if((int)$page > 1 && $count > $limit){
            $ofset = $limit*($page-1);
        }
        
        $data['result'] = $this->autoparts->allWeights($limit, $ofset);
        $this->layout->view('adminpage/weights', $data);
    }
    
    public function weightsSearch($key){
        if(isset($key) && !empty($key)){
            $this->load->model('autoparts');
            $result = $this->autoparts->searchWeight($key);
            echo json_encode($result);
            exit;
        }else{
            echo json_encode(['error']);
        }
        
    }
    
    public function exchange(){
        $data['error'] = false;
        $this->load->model('autoparts');
        
        if($this->input->post('rate')){
            
            if($this->input->post('exchangeRate') > 0){
                $this->autoparts->setExchangeRate($this->input->post('exchangeRate'));
            }else{
                 $data['error'] = 'error';
            }
            
        }
        
        $data['result'] = $this->autoparts->getExchangeRate();
        $this->layout->view('adminpage/exchange', $data);
    }


    //SELECT part_number, sum(count) as totcount FROM `order_items` group by `part_number` order by totcount DESC
            
}