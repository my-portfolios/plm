<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->model('/pdm2/Upload_model');
		$this->load->model('/com/Pop_empSearch_model');
		$this->load->model('/com/Pop_pmsSearch_model');
    } 
	
	function index()
    {   
		
		if($_GET['id'] != 'NEW'){
			$pf_id = $_GET['id'];
			$data['list'] = $this->getData($pf_id);
			$data['empList'] = $this->Pop_empSearch_model->getEmpList('pdm2',$pf_id);
			$data['pmsList'] = $this->Pop_pmsSearch_model->getPmsLIst('pdm2',$pf_id);
			$data['keywordList'] = $this->getKeywordList($pf_id);
			$data['versionList'] = $this->getVersionList($pf_id);
		}else{
			$data['list'] = null;
			$data['empList'] = null;
			$data['pmsList'] = null;
			$data['keywordList'] = null;
			$data['versionList'] = null;
		}

		$this->load->view('/pdm2/Upload',$data);
    }
	
	/* 데이터 가져오기 */
	public function getData($pf_id){
		$result = $this->Upload_model->getData($pf_id);
		return $result;
	}
	
	/* 이력 가져오기 */
	public function getVersionList($pf_id){
		$result = $this->Upload_model->getVersionList($pf_id);
		return $result;
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($pf_id){
		$result = $this->Upload_model->getKeywordList($pf_id);
		return $result;
	}
	
	/*썸네일생성*/
	public function thumb($tmp_name){
		$config2['image_library'] = 'gd2';
		$config2['source_image']	= './uploads/'.$tmp_name;
		$config2['create_thumb'] = TRUE;
		$config2['maintain_ratio'] = TRUE;
		$config2['width']	= 34;
		$config2['height']	= 40;
		$this->load->library('image_lib', $config2); 
		$this->image_lib->resize();
		if ( ! $this->image_lib->resize())
		{
		    $this->image_lib->display_errors('<p>썸네일이 정상적으로 저장되지 않았습니다. 다시 한번 더 시도해주세요.', '</p>');
		}
	}
		
	/* 업로드 */
	public function upload(){
		
		$searchData = $this->input->post();

		//$searchData['PF_PATH'] = ($searchData['PF_PATH']=='' || $searchData['PF_PATH']==null) ? "PDM" : $searchData['PF_PATH'];
		
		$file_size = $_FILES['PF_FILE']['size'];
		
		$file_name_old = $_FILES['PF_FILE']['name'];
		
		$ext = strtolower(substr(strrchr($file_name_old, '.'), 1)); 
		$file_name = explode(".",$file_name_old)[0] . '.' . $ext; 
		
		$tmp_name = str_replace( '/' , '', $_FILES['PF_FILE']['tmp_name'].'.'.$ext  );
		
		/* 파일 업로드 시작 */
		$config1['upload_path'] 		= './uploads/';
		$config1['allowed_types'] 	= '*';
		$config1['file_ext_tolower'] = TRUE;
		$config1['file_name'] 		= $tmp_name;	/* 업로드 할 파일명 변경 */
		
		$this->upload->initialize($config1);
		
		if($_FILES){
			
			if($_FILES['PF_FILE']['error'] <= 0){
				
				if ( ! $this->upload->do_upload('PF_FILE') ){
					$error = array('error' => $this->upload->display_errors());
					redirect('pdm2/Main');
				}	
				
			}
			
		}
		//썸네일생성
		$this->thumb($tmp_name);

		/* 파일 업로드 끝 */
		
		$new_pf_id 	= $this->Upload_model->get_new_pf_id();	/* 새 id 따기 */
		$result 	= $this->Upload_model->upload($searchData['PFD_ID'],$searchData['PF_NM'],$searchData['PP_ID'],$searchData['PF_CONT'],$searchData['PF_PATH'],$new_pf_id,$tmp_name,$file_name,$file_size,$ext,'pdm2');
		
		/* 담당자 등록 시작 */
		$empArr = $this->input->post("PF_EMP");
		$empArrNm = $this->input->post("PF_EMP_NM");
		
		//if(isset($empArr)){
			
			for( $i = 0; $i < count($empArr) ; $i++ ){
				
				$result1 = $this->Pop_empSearch_model->insert_emp('pdm2', $new_pf_id , $empArr[$i], $empArrNm[$i]);
			}
			
		//}
		
		/* 담당자 등록 끝 */
		
		/* 키워드 등록 시작 */
		
		if(isset($searchData['PF_KEYWORD'])){
			
			$keyArr = explode(',',$searchData['PF_KEYWORD']);
			
			for( $i = 0; $i < count($keyArr) ; $i++ ){
				$result = $this->Upload_model->insert_keyword($new_pf_id,$keyArr[$i]);
			}
			
		}
		/* 키워드 등록 끝 */
		
		/* 프로젝트 등록 시작 */
		
		$pmsArr 	= $this->input->post("PF_PMS");
		$pmsArrNm 	= $this->input->post("PF_PMS_NM");
		
		for( $i = 0; $i < count($pmsArr) ; $i++ ){
			
			$this->Pop_pmsSearch_model->insert_pms('pdm2', $new_pf_id , $pmsArr[$i]);
			
		}
		
		/* 프로젝트 등록 끝 */
		
		$this->session->set_flashdata('pfd_id',$searchData['PFD_ID']);
		redirect('pdm2/Main');
		
	}
	
	
	/* 수정 */
	public function update(){
		
		$searchData = $this->input->post();
		
		if($searchData['PF_ID'] != ''){
			
			$this->Upload_model->insertFileVersion($searchData['PF_ID']);
			$this->Upload_model->insertEmpVersion($searchData['PF_ID']);
			$this->Upload_model->insertKeywordVersion($searchData['PF_ID']);
		}	
		
		if($_FILES['PF_FILE']['name'] != ''){
			
			$file_size = $_FILES['PF_FILE']['size'];
			
			$file_name_old = $_FILES['PF_FILE']['name'];
			
			$ext = strtolower(substr(strrchr($file_name_old, '.'), 1)); 
			$file_name = explode(".",$file_name_old)[0] . '.' . $ext; 
			
			$tmp_name = str_replace( '/' , '', $_FILES['PF_FILE']['tmp_name'].'.'.$ext  );
			
			$config['upload_path'] 		= './uploads/';
			$config['allowed_types'] 	= '*';
			$config['file_ext_tolower'] = TRUE;
			$config['file_name'] 		= $tmp_name;	
			
			$this->upload->initialize($config);
			
			if($_FILES['PF_FILE']['error'] <= 0){

				if ( ! $this->upload->do_upload('PF_FILE') ){
					$error = array('error' => $this->upload->display_errors());
					redirect('pdm2/Main');
				}	
			
			}
			
			//썸네일생성
			$this->thumb($tmp_name);
			
			$result = $this->Upload_model->update($searchData,$tmp_name,$file_name,$file_size,$ext);
			
		}else{
			$result = $this->Upload_model->update($searchData,'N','N','N','N');
		}
		
		/* 담당자 등록 시작 */
		$empArr 	= $this->input->post("PF_EMP");
		$empArrNm 	= $this->input->post("PF_EMP_NM");
		
		//if(isset($empArr)){
			//$result0 = $this->Upload_model->del_emp($searchData['PF_ID']);	/* 담당자 삭제 */
			$result0 = $this->Pop_empSearch_model->remove_emp('pdm2',$searchData['PF_ID']);	/* 담당자 삭제 */
			if($result0){
				for( $i = 0; $i < count($empArr) ; $i++ ){
					$result1 = $this->Pop_empSearch_model->insert_emp('pdm2', $searchData['PF_ID'] , $empArr[$i] , $empArrNm[$i]);
				}
			}
		//}
		
		/* 담당자 등록 끝 */
		
		/* 프로젝트 등록 시작 */
		
		$pmsArr = $this->input->post("PF_PMS");
		$pmsArrNm = $this->input->post("PF_PMS_NM");

		$result0 = $this->Pop_pmsSearch_model->remove_pms('pdm2',$searchData['PF_ID']);	/* 프로젝트 삭제 */
		if($result0){
			for( $i = 0; $i < count($pmsArr) ; $i++ ){
				$this->Pop_pmsSearch_model->insert_pms('pdm2', $searchData['PF_ID'] , $pmsArr[$i] );
			}
		}
		
		/* 프로젝트 등록 끝 */
		
		/* 키워드 등록 시작 */
		
		if(isset($searchData['PF_KEYWORD'])){
			
			$result0 = $this->Upload_model->del_keyword($searchData['PF_ID']);	/* 키워드 삭제 */
			
			$keyArr = explode(',',$searchData['PF_KEYWORD']);
			if($result0){
				for( $i = 0; $i < count($keyArr) ; $i++ ){
					$result = $this->Upload_model->insert_keyword($searchData['PF_ID'],$keyArr[$i]);
				}
			}
			
		}
		/* 키워드 등록 끝 */
		$this->session->set_flashdata('pfd_id',$searchData['PFD_ID']);
		redirect('pdm2/Main');
		
	}
	
}
