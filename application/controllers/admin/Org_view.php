<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Org_view extends CI_Controller {
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageSeg(){ return 'admin'; } //seg 1
	function pageType(){ return 'Org_view'; } //seg 2
	function pageModel(){ return 'Org_view_model'; } // model
		 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/'.$this->pageSeg().'/'.$this->pageModel());
				$this->load->model('/Common_model');
    } 
	
	/*Org_write 로딩*/
	function index()
    {   
    	if(isset($_GET['id'])){
    		$id = $_GET['id'];
    	}else{
    		$id = '';
    	}
			$data['list'] 		= $this->{$this->pageModel()}->getData($id);
			$data['popupYn'] 		= 'Y';
			$this->load->view('/public/header');
			$this->load->view('/public/userInfo',$data);
			$this->load->view('/'.$this->pageSeg().'/'.$this->pageType(),$data);
			$this->load->view('/public/foot');
    }
}
