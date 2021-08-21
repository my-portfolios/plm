<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/business/View_model');
		$this->load->model('/com/Pop_empSearch_model');
        $this->check_isvalidated();
    } 
	private function check_isvalidated(){	
		if(! $this->session->userdata('validated')){
			redirect('Login');
		}
	}
	
	function index()
    {   
		
		if($_GET){
			$pb_id = $_GET['id'];
			$data['list'] = $this->getData($pb_id);
			$data['empList'] = $this->Pop_empSearch_model->getEmpList($this->uri->segment(1),$pb_id);
			$data['fileList'] = $this->getFileList($pb_id);
		}else{
			$data['list'] = null;
			$data['empList'] = null;
			$data['fileList'] = null;
		}
	
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/business/public/left');
		$this->load->view('/business/View',$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/* 데이터 가져오기 */
	public function getData($pb_id){
		$result = $this->View_model->getData($pb_id);
		return $result;
	}
	
	/* 파일 가져오기 */
	public function getFileList($pb_id){
		$result = $this->View_model->getFileList($pb_id);
		return $result;
	}
	
}
