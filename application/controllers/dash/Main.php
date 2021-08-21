<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	 
	function __construct()
    {
        parent::__construct();
        $this->load->helper('url');        
				$this->load->model('/dash/Main_model');
    } 
	
	function index()
    {   
    $data['chart_c'] = $this->chart_c();	
    $data['chart_b'] = $this->chart_b();
    
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/dash/public/left');
		$this->load->view('/dash/Main',$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
		
	//chart_c data
	function chart_c(){
		$query = $this->Main_model->chart_c();
		return $query;
	}
	//chart_b data
	function chart_b(){
		$query = $this->Main_model->chart_b();
		return $query;
	}
	
}
