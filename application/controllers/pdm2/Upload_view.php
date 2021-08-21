<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_view extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
		$this->load->helper('download');
        $this->load->model('/pdm2/Upload_view_model');
		$this->load->model('/Common_model');
        $this->load->model('/com/Pop_empSearch_model');
		$this->load->model('/com/Pop_pmsSearch_model');
    } 
	
	function index()
    {   
		if($_GET){
			$pf_id = $_GET['id'];
			$data['list'] = $this->getData($pf_id);
			$data['empList'] = $this->Pop_empSearch_model->getEmpList('pdm2',$pf_id);
			$data['pmsList'] = $this->Pop_pmsSearch_model->getPmsList('pdm2',$pf_id);
			$data['keywordList'] = $this->getKeywordList($pf_id);
			$data['versionList'] = $this->getVersionList($pf_id);
		}else{
			$data['list'] 		= null;
			$data['empList'] 	= null;
			$data['pmsList'] 	= null;
			$data['keywordList'] = null;
			$data['versionList'] = null;
		}
		
		//$this->load->view('/public/header');
		//$this->load->view('/public/top');
		//$this->load->view('/pdm2/public/left');
		$this->load->view('/pdm2/Upload_view',$data);
		//$this->load->view('/public/bottom');
		//$this->load->view('/public/foot');
    }
	
	//삭제
	public function del(){
		
		$pf_id = $this->input->post('id');
		
		$del_yn = $this->Upload_view_model->chkDelYn($pf_id);
		
		if($del_yn == 'Y'){
			$result = $this->Common_model->remove_pdm_file_info($pf_id);
			$this->Pop_empSearch_model->remove_emp($this->uri->segment(1), $pf_id);
			$this->Pop_pmsSearch_model->remove_pms($this->uri->segment(1), $pf_id);
		}else if($del_yn == 'N'){
			$result = $this->Upload_view_model->del($pf_id);
		}
		
		echo json_encode($result);
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
	
	/* 담당자 가져오기 */
	public function getEmpList($pf_id){
		$result = $this->Upload_view_model->getEmpList($pf_id);
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
