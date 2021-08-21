<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Write extends CI_Controller {

	function pageSeg(){ return 'pms'; } //seg 1
	function pageReturn($id=""){ return $id=="" ? 'Main' : 'Write?id='.$id; } //def,PLM_TYPE,return
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
		$this->load->model('/pms/Write_model');
		$this->load->model('/pdm2/Upload_model');/* 업로드 */
		$this->load->model('/pdm2/Main_model');
		$this->load->model('/pms/Gantt_model');
		$this->load->model('/com/Pop_empSearch_model');
		$this->load->model('/com/Pop_pmsSearch_model');
		$this->load->model('/com/Pop_compSearch_model');
		$this->load->model('/com/Pop_groupSearch_model');
		$this->load->model('/Common_model');
		$this->load->library('upload');
    } 
	
	function index()
    {   
		if($_GET){
			$pp_id 					= $_GET['id'];
			$data['list'] 			= $this->getData($pp_id);
			$data['empList'] 		= $this->Pop_empSearch_model->getEmpList($this->uri->segment(1),$pp_id);
			$data['keywordList'] 	= $this->getKeywordList($pp_id);
			$data['compList']		= $this->Pop_compSearch_model->getCompList('pms',$pp_id);
			$data['groupList']		= $this->Pop_groupSearch_model->getGroupList($pp_id);
			$data['fileList']		= $this->Write_model->getFileList($pp_id);
		}else{
			$data['list'] 			= null;
			$data['empList'] 		= null;
			$data['keywordList'] 	= null;
			$data['compList']		= null;
			$data['groupList']		= null;
			$data['fileList']		= null;
		}
		
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/pms/public/left');
		$this->load->view('/pms/Write',$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
	}
	
	/*썸네일생성*/
	function thumb($tmp_name){
		$config2['image_library'] = 'gd2';
		$config2['source_image']	= './uploads/'.$tmp_name;
		$config2['create_thumb'] = TRUE;
		$config2['maintain_ratio'] = TRUE;
		$config2['width']	= 34;
		$config2['height']	= 40;
		
		$this->load->library('image_lib', $config2); 
		$this->image_lib->initialize($config2);
		$this->image_lib->resize();
		if ( ! $this->image_lib->resize())
		{
			$this->image_lib->display_errors('<p>썸네일이 정상적으로 저장되지 않았습니다. 다시 한번 더 시도해주세요.', '</p>');
		}
	}
	
	/* 데이터 가져오기 */
	public function getData($pp_id){
		$result = $this->Write_model->getData($pp_id);
		return $result;
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($pp_id){
		$result = $this->Write_model->getKeywordList($pp_id);
		return $result;
	}
	
	/* 저장 */
	public function save(){
		
		$searchData = $this->input->post();

		$pf_id_arr = [];
		
		/* 파일 업로드 */
		if($_FILES['PF_FILE']['name']){
			$pf_id_arr = $this->pdm_file_upload($_FILES,$searchData);	//upload 하고 새로 등록된 pf_id return받기
		}
		
		/* 프로젝트 정보 저장 */
		$new_pp_id	= $this->Write_model->get_new_pp_id();	// 새 id 따기
		$this->Write_model->insert_pms($new_pp_id,$searchData);	// 프로젝트 등록 
		for($i = 0; $i < count($pf_id_arr); $i++){
			$this->Common_model->insert_file_list($new_pp_id,$pf_id_arr[$i],$this->uri->segment(1),'normal'); // 프로젝트 첨부파일 등록 
		}
		/* 프로젝트 정보 저장 끝 */
		
		/* 프로젝트 일정 저장 */
		if($new_pp_id != ''){		
		
			$this->Write_model->del_projectSc($new_pp_id);	// 프로젝트일정삭제
			
			$projectSc = json_decode($searchData['projectSc']);
			
			foreach($projectSc->tasks as $row) {
				$obj = new stdClass();
				$projectVal = array();
				foreach($row as $key => $val) {
					
					if(!is_array($val)){
						if(gettype($val)=='boolean'){
							if($val == true){
								$val = 'true';
							}else{
								$val = 'false';
							}
						}
						
						$obj->$key = $val;
					}else if(is_array($val) && $key == 'assigs'){	//작업자
						$empArr = [];
						foreach($val as $assigsArr){
							array_push($empArr , $assigsArr->resourceId);
						}
						$obj->empList = implode($empArr,',');
					}
						
				}
			//	echo var_dump($obj);
			//	exit;
				$this->Write_model->insert_projectSc($new_pp_id,$obj);
			}
			
		}else{
			exit;
		}
		/* 프로젝트 일정 저장 끝 */
		
		//거래처 저장
		$compArr = $this->input->post("PF_COMP");
		for( $i = 0; $i < count($compArr) ; $i++ ){
			$result1 = $this->Pop_compSearch_model->insert_comp($this->uri->segment(1), $new_pp_id , $compArr[$i]);
		}

		/* 그룹 등록 시작 */
		
		$groupArr = $this->input->post("PG_ID");

		for( $i = 0; $i < count($groupArr) ; $i++ ){
			$this->Pop_groupSearch_model->insert_group($this->uri->segment(1), $new_pp_id , $groupArr[$i]);
		}

		/* 그룹 등록 끝 */
		
		/* 담당자 등록 시작 */
		
		$empArr = $this->input->post("PF_EMP");
		$empArrNm = $this->input->post("PF_EMP_NM");

		for( $i = 0; $i < count($empArr) ; $i++ ){
			$this->Pop_empSearch_model->insert_emp($this->uri->segment(1), $new_pp_id , $empArr[$i], $empArrNm[$i]);
		}

		/* 담당자 등록 끝 */
		
		/* 키워드 등록 시작 */
		
		if(isset($searchData['PF_KEYWORD'])){
			
			$keyArr = explode(',',$searchData['PF_KEYWORD']);
			
			for( $i = 0; $i < count($keyArr) ; $i++ ){
				$result = $this->Write_model->insert_keyword($new_pp_id,$keyArr[$i]);
			}
			
		}
		/* 키워드 등록 끝 */

		redirect($this->uri->segment(1).'/Main');
		
	}
	
	/* 수정 */
	public function upd(){
		
		$searchData = $this->input->post();

		$pf_id_arr = [];
		
		/* 파일 업로드 */
		if($_FILES['PF_FILE']['name']){
			$pf_id_arr = $this->pdm_file_upload($_FILES,$searchData);	//upload 하고 새로 등록된 pf_id return받기
		}
		
		/* 선택된 파일 삭제 */
		if(isset($searchData['FILE_DEL'])){
			$this->fn_delete_file($searchData['FILE_DEL']);
		}
		
		$pp_id = $searchData['PP_ID'];	/* 프로젝트id */
		$result_pms		= $this->Write_model->update_pms($searchData);	/* 프로젝트 정보 수정 */

		$result_update_pdm = $this->fn_update_pdm($searchData);	// 포로젝트 변경내용을 pdm에도 적용
		
		if($result_update_pdm){
			for($i = 0; $i < count($pf_id_arr); $i++){
				$this->Common_model->insert_file_list($searchData['PP_ID'],$pf_id_arr[$i],$this->uri->segment(1),'normal'); // 프로젝트 첨부파일 등록 
			}
		}
		
		/* 프로젝트 일정 저장 */
		if($pp_id != ''){		
		
			$this->Write_model->del_projectSc($pp_id);	// 프로젝트일정삭제
			
			$projectSc = json_decode($searchData['projectSc']);
			
			//echo var_dump($projectSc);
			//exit;
			
			foreach($projectSc->tasks as $row) {
				$obj = new stdClass();
				foreach($row as $key => $val) {
					
					if(!is_array($val)){
						if(gettype($val)=='boolean'){
							if($val == true){
								$val = 'true';
							}else{
								$val = 'false';
							}
						}
						 
						$obj->$key = $val;
						
					}else if(is_array($val) && $key == 'assigs'){	//작업자
						$empArr = [];
						foreach($val as $assigsArr){
							array_push($empArr , $assigsArr->resourceId);
						}
						$obj->empList = implode($empArr,',');
					}
					
				}
			//	echo var_dump($obj);
			//	exit;
				$this->Write_model->insert_projectSc($pp_id,$obj);
			}
		}else{
			exit;
		}
		/* 프로젝트 일정 저장 끝 */
		
		/* 그룹 등록 시작 */
		$result_del_group = $this->Pop_groupSearch_model->remove_group($this->uri->segment(1),$pp_id);	/* 그룹 삭제 */
		
		if($result_del_group){
			
			$groupArr = $this->input->post("PG_ID");

			for( $i = 0; $i < count($groupArr) ; $i++ ){
				$this->Pop_groupSearch_model->insert_group($this->uri->segment(1), $pp_id , $groupArr[$i]);
			}
			
		}
		/* 그룹 등록 끝 */

		/* 담당자 등록 시작 */
		$result_del_emp = $this->Pop_empSearch_model->remove_emp($this->uri->segment(1),$pp_id);	/* 담당자 삭제 */
		
		if($result_del_emp){
			
			$empArr = $this->input->post("PF_EMP");
			$empArrNm = $this->input->post("PF_EMP_NM");

			for( $i = 0; $i < count($empArr) ; $i++ ){
				$this->Pop_empSearch_model->insert_emp($this->uri->segment(1), $pp_id , $empArr[$i], $empArrNm[$i]);
			}
			
		}
		/* 담당자 등록 끝 */
		
		//거래처 삭제 및 저장
		$compArr = $this->input->post("PF_COMP");
		$result0 = $this->Pop_compSearch_model->del_comp($this->uri->segment(1), $pp_id);
		for( $i = 0; $i < count($compArr) ; $i++ ){
			$result1 = $this->Pop_compSearch_model->insert_comp($this->uri->segment(1), $pp_id , $compArr[$i]);
		}
		
		/* 키워드 등록 시작 */
		
		if(isset($searchData['PF_KEYWORD'])){
			
			$result0 = $this->Write_model->del_keyword($pp_id);	/* 키워드 삭제 */
			
			$keyArr = explode(',',$searchData['PF_KEYWORD']);
			if($result0){
				for( $i = 0; $i < count($keyArr) ; $i++ ){
					$result = $this->Write_model->insert_keyword($pp_id,$keyArr[$i]);
				}
			}
			
		}
		/* 키워드 등록 끝 */
		
		redirect($this->uri->segment(1).'/Main');
		
	}

	//프로젝트 변경된 내용을 pdm에도 적용
	function fn_update_pdm($searchData){
		
		$this->Write_model->update_pdm($searchData); //pdm 내용 수정
		
		$pf_ids = $this->Write_model->get_pf_ids_pp_id($searchData['PP_ID']);
				
		$pmsArr 	= $searchData['PF_PMS'];
		$empArr = $this->input->post("PF_EMP");
		$empArrNm = $this->input->post("PF_EMP_NM");
		
		foreach($pf_ids as $row) {
	        
			//pms
			$result0 = $this->Pop_pmsSearch_model->remove_pms('pdm2',$row->PF_ID);	
			if($result0){
				for( $i = 0; $i < count($pmsArr) ; $i++ ){
					$this->Pop_pmsSearch_model->insert_pms('pdm2', $row->PF_ID , $pmsArr[$i] );
				}
			}
			//emp
			$result1 = $this->Pop_empSearch_model->remove_emp('pdm2',$row->PF_ID);
			for( $k = 0; $k < count($empArr) ; $k++ ){
				$this->Pop_empSearch_model->insert_emp('pdm2', $row->PF_ID , $empArr[$k], $empArrNm[$k]);
			}
			
	    }
		return true;
	}

	// 파일 업로드 + pdm등록 하고 등록하고 , 생긴 pf_id들 return
	function pdm_file_upload($uploaded_files,$searchData){
		
		$pf_id_arr = [];	//return 시킬 새 pf_id 들
		
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
			$tmp_name = '';
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
			
			$this->thumb($tmp_name);
			
			$new_pf_id 	= $this->Upload_model->get_new_pf_id();		// 새 id 따기 
			
			$this->Upload_model->upload(null,$searchData['PP_NM'].'('.$i.')','',$searchData['PP_CONT'],null,$new_pf_id,$tmp_name,$file_name ,$_FILES['PF_FILE']['size'],$ext,'pms');	// 업로드 등록 

			$this->Upload_model->insert_keyword($new_pf_id,'프로젝트관리');	 // 키워드 등록
			
			$pmsArr 	= $this->input->post("PF_PMS");
			$pmsArrNm 	= $this->input->post("PF_PMS_NM");
			
			for( $k = 0; $k < count($pmsArr) ; $k++ ){
				
				$this->Pop_pmsSearch_model->insert_pms('pdm2', $new_pf_id , $pmsArr[$k]);	//pdm 프로젝트 등록
				$this->Upload_model->insert_keyword($new_pf_id,$pmsArrNm[$k]);
				
			}
	
			array_push($pf_id_arr,$new_pf_id);
			$empArr = $this->input->post("PF_EMP");
			$empArrNm = $this->input->post("PF_EMP_NM");
			
			for( $j = 0; $j < count($empArr) ; $j++ ){
				$this->Pop_empSearch_model->insert_emp('pdm2', $new_pf_id , $empArr[$j], $empArrNm[$j]);	//pdm 공유자 등록
			}
			
			/* 파일 내용 저장 끝 */
		}
		return $pf_id_arr;
	}

	/* 한 파일만 삭제 */
	public function delete(){
		
		$file_del = $this->input->get('fileName');
		$ID = $this->input->get('id');

		$file_del_arr[0] = $file_del;

		if(!isset($file_del)) {
			echo("<script>alert('파일이 선택되지 않았거나 잘못된 접근입니다.');history.back();</script>");
			exit;
		}
		
		$this->fn_delete_file($file_del_arr);
		
		redirect($this->pageSeg().'/'.$this->pageReturn($ID));
	}
	
	//파일 삭제 (array)
	function fn_delete_file($file_del_arr){
		
		for($i = 0; $i < count($file_del_arr); $i++){
			
			$pf_file_temp_nm = $this->Write_model->get_pf_file_temp_nm($file_del_arr[$i]);
			
			$tempFileNameEx2 = explode (".", $pf_file_temp_nm);
			
			//기본파일
			if(file_exists('./uploads/'.$tempFileNameEx2[0].'.'.$tempFileNameEx2[1]) ){
				unlink('./uploads/'.$tempFileNameEx2[0].'.'.$tempFileNameEx2[1]);
			}
			//썸네일삭제
			if(file_exists('./uploads/'.$tempFileNameEx2[0].'_thumb.'.$tempFileNameEx2[1])){
				unlink('./uploads/'.$tempFileNameEx2[0].'_thumb.'.$tempFileNameEx2[1]);
			}
			
			$pf_id = $this->Common_model->get_pf_id_from_file_list($file_del_arr[$i]);
			//pdm 삭제
			$this->Main_model->remove($pf_id);
			$this->Pop_empSearch_model->remove_emp('pdm2', $pf_id);
			$this->Pop_pmsSearch_model->remove_pms('pdm2', $pf_id);
			$this->Main_model->remove_pdm_keyword($pf_id);
			
			$this->Common_model->delete_file_list($file_del_arr[$i]);	//rm 파일삭제
		}
		
	}
}
