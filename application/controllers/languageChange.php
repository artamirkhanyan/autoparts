<?php

class languageChange extends CI_Controller {
    
    public function setLang($lang){
        
        $this->load->library('user_agent');
        if ($this->agent->is_referral())
        {
            $url = $this->agent->referrer();
        }else{
            $url = '/';
        }       
        if($lang == 'armenian' || $lang == 'russian'){
            $this->session->set_userdata('lang', $lang);
            
        }
        redirect($url);
        
    }
}
