<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Write extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/business/Write_model');
		$this->load->model('/pdm/Upload_model');/* 업로드 */
		$this->load->model('/com/Pop_empSearch_model');
        $this->check_isvalidated();
		$this->load->library('upload');
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
			$data['list'] 		= $this->Write_model->getData($pb_id);
			$data['empList'] 	= $this->Pop_empSearch_model->getEmpList($this->uri->segment(1),$pb_id);
			$data['fileList'] 	= $this->Write_model->getFileList($pb_id);
		}else{
			$data['list'] 		= null;
			$data['empList'] 	= null;
			$data['fileList'] 	= null;
		}
		
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/business/public/left');
		$this->load->view('/business/Write',$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/* 저장 */
	public function save(){
		
		$searchData = $this->input->post();
		
		$pf_id_arr = [];
		
		/* 파일 업로드 */
		if($_FILES['PF_FILE']['name']){
			$pf_id_arr = $this->pdm_file_upload($_FILES,$searchData);	//upload 하고 새로 등록된 pf_id return받기
		}
		
		/* 게시판 저장 */
		$new_pb_id	= $this->Write_model->get_new_pb_id();	// 게시판 새 id 따기
		
		$this->Write_model->insert_business($new_pb_id,$searchData);	// 등록 
		for($i = 0; $i < count($pf_id_arr); $i++){
			$this->Write_model->insert_business_file($new_pb_id,$pf_id_arr[$i]); // 첨부파일 등록 
		}
		
		/* 담당자 등록 */
		$empArr = $this->input->post("PF_EMP");
		$empArrNm = $this->input->post("PF_EMP_NM");
		for( $i = 0; $i < count($empArr) ; $i++ ){
			$this->Pop_empSearch_model->insert_emp($this->uri->segment(1), $new_pb_id , $empArr[$i], $empArrNm[$i]);
		}
		
		redirect('business/Main');		
	}
	
	/* 수정 */
	public function upd(){
		
		$searchData = $this->input->post();
		
		$pf_id_arr = [];
		/* 파일 업로드 */
		if($_FILES['PF_FILE']['name']){
			$pf_id_arr = $this->pdm_file_upload($_FILES,$searchData);	//upload 하고 새로 등록된 pf_id return받기
		}
		
		/* 선택된 파일 삭제 시작 */
		$file_del_arr = $searchData['FILE_DEL'];
		for($i = 0; $i < count($file_del_arr); $i++){
		//	$this->Write_model->delete_file($file_del_arr[$i]);
		}
		/* 선택된 파일 삭제 끝 */
		
		/* 요구사항 시작 */
		
		$pb_id = $searchData['PB_ID'];	/* 요구사항 번호 */
		$result_business		= $this->Write_model->update_business($searchData);	/* 요구사항 수정 */
		for($i = 0; $i < count($pf_id_arr); $i++){
			$result_file	= $this->Write_model->insert_business_file($searchData['PB_ID'],$pf_id_arr[$i]); /* 요구사항 첨부파일 등록 */
		}
		
		/* 요구사항 끝 */
		
		/* 담당자 등록 시작 */
		
		$result_del_emp = $this->Pop_empSearch_model->remove_emp($this->uri->segment(1),$searchData['PB_ID']);	/* 담당자 삭제 */
		
		if($result_del_emp){
			
			$empArr = $this->input->post("PF_EMP");
			$empArrNm = $this->input->post("PF_EMP_NM");

			for( $i = 0; $i < count($empArr) ; $i++ ){
				$this->Pop_empSearch_model->insert_emp($this->uri->segment(1), $searchData['PB_ID'] , $empArr[$i], $empArrNm[$i]);
			}
			
		}
		
		/* 담당자 등록 끝 */
		
		redirect('business/Main');
		
	}
	
	function pdm_file_upload($uploaded_files,$searchData){
		
		$pf_id_arr = [];
		
		$uploaded_file_count = count($_FILES['PF_FILE']['name']);
			
		for($i=0; $i<$uploaded_file_count; $i++) { 
		
			if($uploaded_files['PF_FILE']['name'][$i] == null) continue;
		
			unset($_FILES);

			$_FILES['PF_FILE']['name']      = $uploaded_files['PF_FILE']['name'][$i];
			$_FILES['PF_FILE']['type']      = $uploaded_files['PF_FILE']['type'][$i];
			$_FILES['PF_FILE']['tmp_name']  = $uploaded_files['PF_FILE']['tmp_name'][$i];
			$_FILES['PF_FILE']['error']     = $uploaded_files['PF_FILE']['error'][$i];
			$_FILES['PF_FILE']['size']      = $uploaded_files['PF_FILE']['size'][$i];
			
			
			$file_name_old = $_FILES['PF_FILE']['name'];
	
			$ext = strtolower(substr(strrchr($file_name_old, '.'), 1)); 
			$file_name = explode(".",$file_name_old)[0] . '.' . $ext; 
			
			$tmp_name = str_replace( '/' , '', $_FILES['PF_FILE']['tmp_name'].'.'.$ext  );
			
			$upload_config = Array(
				'upload_path' 	=> './uploads/',
				'allowed_types' => '*',
				'file_ext_tolower' => TRUE,
				'file_name'	=> $tmp_name	// 업로드 할 파일명 변경
			);

			$this->upload->initialize($upload_config);
			
			if($_FILES['PF_FILE']['error'] <= 0){
				if( ! $this->upload->do_upload('PF_FILE')) {
					echo $this->upload->display_errors();
				}
			}
			
			/* 파일 내용 저장 시작 */
		
			$new_pf_id 	= $this->Upload_model->get_new_pf_id();		// 새 id 따기 
			
			$this->Upload_model->upload(null,$searchData['PB_TITLE'].'('.$i.')','',$searchData['PB_CONT'],null,$new_pf_id,$tmp_name,$file_name ,$_FILES['PF_FILE']['size'],$ext,$this->uri->segment(1));	// 업로드 등록 
			
			$this->Upload_model->insert_keyword($new_pf_id,'게시글');	 // 키워드 등록
			array_push($pf_id_arr,$new_pf_id);
			
			$empArr = $this->input->post("PF_EMP");
			$empArrNm = $this->input->post("PF_EMP_NM");
			
			for( $j = 0; $j < count($empArr) ; $j++ ){
				$this->Pop_empSearch_model->insert_emp('pdm', $new_pf_id , $empArr[$j], $empArrNm[$j]);
			}
			
			/* 파일 내용 저장 끝 */
		}
		
	}
	
	
}
