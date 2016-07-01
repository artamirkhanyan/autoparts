<?php if( !defined("BASEPATH")) exit('No direct script access allowed');

class StatisticsCont extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
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
    
    public function topdetails(){
        
        $limit = 10;
        if($this->input->post('topcount')){
            $limit = (int)$this->input->post('topcount');
        }
        
        $data['top'] = $limit;
        $this->load->model('statistics');
        $data['topten'] = $this->statistics->topTenDetails($limit);
        
        $data['toptenArm'] = $this->statistics->topTenArm($limit);
        $data['toptenBarma'] = $this->statistics->topTenBarma($limit);
        
        $this->layout->view('topdetails', $data);
    }
    
    public function customers(){
        $this->load->model('statistics');
        
        $data['result'] = $this->statistics->topTenCustomers();
        $this->layout->view('topcustomers', $data);
    }
    
    public function finances(){
        $this->load->model('statistics');
        
        $data['from'] = FALSE;
        $data['to'] = FALSE;
        $from = '0';
        $to = 'NOW()';        
        if($this->input->post('count') && $this->input->post('date_from') !=='' && $this->input->post('date_to') !==''){
            $data['from'] = $from = $this->input->post('date_from');
            $data['to'] = $to = $this->input->post('date_to').' 23:59:00';
        }
        
        $data['payedOrders'] = $this->statistics->getPayedTotal($from, $to);
        $data['originOrders']= $this->statistics->getOriginalTotal($from, $to);
        
        $data['totalArm'] = $this->statistics->getDetailsTotalArm($from, $to);
        $data['totalBarma'] = $this->statistics->getDetailsTotalBarma($from, $to);
        
        $this->layout->view('finances', $data);
    }
            
}
