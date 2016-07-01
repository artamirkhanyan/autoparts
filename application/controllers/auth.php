<?php

class Auth extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        if(!$this->session->userdata('lang') || $this->session->userdata('lang')=='armenian'){
			$this->config->set_item('language', 'armenian');
            $this->lang->load('site', 'armenian');
        }else{
			$this->config->set_item('language', 'russian');
            $this->lang->load('site', 'russian');
        }
        
        $this->load->library('layout');
        $this->layout->setLayout('layout/user_layout');                
    }
    
    public function login(){
        $data['error'] = FALSE;
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if($this->form_validation->run() == FALSE) {            
            //Validation error show the same login page
        }else{ 
            if($this->_loginDataValidator()){
                $userdata = $this->_loginDataValidator();
                if($userdata['is_active'] == 1){
                    $this->session->unset_userdata('user_cart');
                    //Save user uniq data in the session
                    $this->session->set_userdata('user_id',$userdata);
                    redirect('/');
                }else{
                    $data['error'] = $this->lang->line('notactive');
                }
                
            }else{
                $data['error'] = $this->lang->line('login_error');
            }            
        }          
        $this->layout->view('user_login', $data);
    }
    
    public function _loginDataValidator() {         
        $email    = $this->input->post('email');
        $password = $this->input->post('password');
        
        $email   = htmlspecialchars($email);
        if(!is_numeric($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        
        $this->load->model('user_auth');

        $login_result = $this->user_auth->login($email, $password);

        if($login_result){
            return $login_result;
        }else{
            return false;
        }
    }
    
    public function LogOut(){
        $this->session->unset_userdata('user_cart');
        $this->session->unset_userdata('user_id');

        redirect('/');
    }
    
    /**
     * 
     */
    public function registration(){
        $data['error'] = FALSE;
        $data['validation'] = array(
            'f_name'   => '',
            'l_name'   => '',
            'email'    => '',
            'phone'    => '',
            'address'  => '',
            'password' => '',
            'password_repeat'=>''
        );
        
        if($this->input->post('reg_submit')){
            $inserted_user_id_email = $this->_registrationDataValidator($data); // return array or empty array
            if(!empty($inserted_user_id_email)){
                if($inserted_user_id_email['is_active'] == 1){
                    $this->session->set_userdata('user_id',$inserted_user_id_email);
                    redirect('/');
                }else{
                    $data['error'] = $this->lang->line('notactive');
                }
            }else{
                $data['error'] = $this->lang->line('reg_error'); 
            }
        }
        $this->layout->view('user_registration', $data);
    }
    
    public function _registrationDataValidator(&$data){
        $f_name   = $this->input->post('f_name');
        $l_name   = $this->input->post('l_name');
        $email    = $this->input->post('email');
        $phone    = $this->input->post('phone');
        $address  = $this->input->post('address');
        $password = $this->input->post('password');
        $password_repeat = $this->input->post('password_repeat');
        
        $f_name  = htmlspecialchars($f_name);
        $l_name  = htmlspecialchars($l_name);
        $email   = htmlspecialchars($email);
        $phone   = htmlspecialchars($phone);
        $address = htmlspecialchars($address);
        
        //For outputing the errors
        $data['validation'] = '';
        //First and Last name a-zA-z validation
        if (!preg_match("/^[a-zA-Z]*$/",$f_name) || $f_name=='' || !$f_name) {
            $data['validation']['f_name'] = $this->lang->line('f_name_error'); 
        }else{
            $data['validation']['f_name'] = "";
        }
        if (!preg_match("/^[a-zA-Z -]*$/",$l_name) || $l_name=='' || !$l_name) {
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
        //Password length validation min 6
        if(strlen($password) < 6){
            //Invalid password length
            $data['validation']['password'] = $this->lang->line('pass_error');
        }else{
            $data['validation']['password'] = "";
        }        
        //Password validation
        if($password !== $password_repeat){
            $data['validation']['password_repeat'] = $this->lang->line('pass_repeat_error');
        }else{
            $data['validation']['password_repeat'] = "";
        }
        
        //If validation array is empty
        if(!empty(join(array_values($data['validation'])))){
            return false;
        }

        $this->load->model('user_auth');
        
        $result = $this->user_auth->registration($f_name, $l_name, $email, $phone, $address, $password);
        if(is_array($result)){
            return $result;
        }else{            
            switch ($result) {
                case 'email':
                    $data['validation']['email'] = $this->lang->line('email_used');
                    break;
                case 'phone':
                    $data['validation']['phone'] = $this->lang->line('phone_used');
                    break;
                case 'both':
                    $data['validation']['phone'] = $this->lang->line('phone_used');
                    $data['validation']['email'] = $this->lang->line('email_used');
                    break;
            }            
            return false;
        }
                
    }
    
    /*******************************Buy data Validation*********************************************/
    
    public function buyDataValidator(&$data){
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
        if (!preg_match("/^[a-zA-Z]*$/",$f_name)) {
            $data['validation']['f_name'] = $this->lang->line('f_name_error'); 
        }else{
            $data['validation']['f_name'] = "";
        }
        if (!preg_match("/^[a-zA-Z -]*$/",$l_name)) {
            $data['validation']['l_name'] = $this->lang->line('l_name_error');
        }else{
            $data['validation']['l_name'] = "";
        }
        //Adress validation
        if (!preg_match("/^[a-zA-Z0-9 -.\\\\]*$/",$address)) {
            $data['validation']['address'] = $this->lang->line('address_error');
        }else{
            $data['validation']['address'] = "";
        } 
        //Phone validation
        if (!preg_match("/^[0-9]*$/",$phone)) {
            $data['validation']['phone'] = $this->lang->line('phone_error');
        }else{
            $data['validation']['phone'] = "";
        } 
        //Email address validation
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            //Invalid email adress
            $data['validation']['email'] = $this->lang->line('email_error');
        }else{
            $data['validation']['email'] = "";
        }                
        
        //If validation array is empty
        if(!empty(join(array_values($data['validation'])))){
            return false;
        }

//        $this->load->model('user_auth');
//        
//        $result = $this->user_auth->registration($f_name, $l_name, $email, $phone, $address);
//        if(is_array($result)){
//            return $result;
//        }else{            
//            switch ($result) {
//                case 'email':
//                    $data['validation']['email'] = $this->lang->line('email_used');
//                    break;
//                case 'phone':
//                    $data['validation']['phone'] = $this->lang->line('phone_used');
//                    break;
//                case 'both':
//                    $data['validation']['phone'] = $this->lang->line('phone_used');
//                    $data['validation']['email'] = $this->lang->line('email_used');
//                    break;
//            }            
//            return false;
//        }
                
    }
    
    public function createNewPassword($email){
        $this->load->helper('string');
        $new_pass = strtolower(random_string('alnum', 8));
        
        $this->load->model('user_auth');
        $result = $this->user_auth->passwordChange($email, $new_pass);
        if($result){
            $text = "Вот ваш новый пароль: ". $new_pass;
            $this->sendNewPassword($email, $text, $new_pass);
        }else{
            //Error
        }
    }
    
    public function sendNewPassword($email, $text, $password){
    	if(is_numeric($email)){
    	     $message = 'YerevanMotors.com - Dzer nor cackagirn e:  '.$password;
    	     $number = substr($email, 1);
    	     $number = '+374'.$number;
    	     
             sendSMS($number, $message);
            
        }else{
	        $this->load->library('email');
	
	        $this->email->from('info@yerevanmotors.com', 'Администрация');
	        $this->email->to($email); 
	
	        $this->email->subject('Восстановление пароля');
	        $this->email->message($text);	
	
	        $this->email->send();
	}        
    }
    
    public function passRecovery(){
        //array with password recovery response
        $data['error'] = FALSE;
        
        if($this->input->post('pass_rec_submit')){
            //Go db and check this email
            $email = $this->input->post('pass_rec_email');
            
            if(!$email || trim($email) == ''){
                $data['error'] = $this->lang->line('invalidphoneemail');
                $this->layout->view('password_recovery', $data);
            }else{
                $this->load->model('user_auth');
                $result = $this->user_auth->passwordRecover($email);

                if($result){
                    
                    $last = $this->user_auth->getlastRecover($email);
                    
                    if($last){
                        $this->createNewPassword($email);
                        $data['error'] = $this->lang->line('passSent');
                    }else{
                        $data['error'] = $this->lang->line('onetime');
                    }
                    
                }else{
                    $data['error'] = $this->lang->line('invalidphoneemail');
                }
                $this->layout->view('password_recovery', $data);
            }
        }
        else{
            $this->layout->view('password_recovery', $data);
        }               
    }
}