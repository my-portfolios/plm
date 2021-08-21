<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WbsView extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/pms/WbsView_model');
		$this->load->model('/com/Pop_empSearch_model');
    } 
	
	function index()
    {   
	
		if($_GET){
			$pp_id 					= $_GET['id'];
			$data['list'] 			= $this->getData($pp_id);
			$data['empList'] 		= $this->Pop_empSearch_model->getEmpList($this->uri->segment(1),$pp_id);
			$data['keywordList'] 	= $this->getKeywordList($pp_id);
		}else{
			$data['list'] 			= null;
			$data['empList'] 		= null;
			$data['keywordList'] 	= null;
		}
		
		$this->load->view('/public/header');
		//$this->load->view('/public/top');
		//$this->load->view('/pms/public/left');
		$this->load->view('/pms/WbsView',$data);
		//$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/* 데이터 가져오기 */
	public function getData($pp_id){
		$result = $this->WbsView_model->getData($pp_id);
		return $result;
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($pp_id){
		$result = $this->WbsView_model->getKeywordList($pp_id);
		return $result;
	}
	
}
