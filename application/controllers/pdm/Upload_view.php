<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_view extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
		$this->load->helper('download');
        $this->load->model('/pdm/Upload_view_model');
		$this->load->model('/com/Pop_empSearch_model');
    } 
	
	function index()
    {   
		if($_GET){
			$pf_id = $_GET['id'];
			$data['list'] = $this->getData($pf_id);
			$data['empList'] = $this->Pop_empSearch_model->getEmpList($this->uri->segment(1),$pf_id);
			$data['keywordList'] = $this->getKeywordList($pf_id);
			$data['versionList'] = $this->getVersionList($pf_id);
		}else{
			$data['list'] = null;
			$data['empList'] = null;
			$data['keywordList'] = null;
			$data['versionList'] = null;
		}
		
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/pdm/public/left');
		$this->load->view('/pdm/Upload_view',$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/* 데이터 가져오기 */
	public function getData($pf_id){
		$result = $this->Upload_view_model->getData($pf_id);
		return $result;
	}
	
	/* 이력 가져오기 */
	public function getVersionList($pf_id){
		$result = $this->Upload_view_model->getVersionList($pf_id);
		return $result;
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($pf_id){
		$result = $this->Upload_view_model->getKeywordList($pf_id);
		return $result;
	}
	
	/* 파일 다운로드 */
	public function fileDownload(){
		
		$tempName = $this->input->get('tempName');
		$fileName = $this->input->get('fileName');
		
		$path = file_get_contents("./uploads/".$tempName);
		
		force_download($fileName, $path);
	}
	
}
