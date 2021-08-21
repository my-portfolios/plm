<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reply extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
		$this->load->model('/Common_model');
        $this->load->model('/com/Reply_model');
		$this->load->model('/com/Pop_empSearch_model');
		$this->load->model('/com/Pop_pmsSearch_model');
		$this->load->model('/pdm2/Upload_model');/* 업로드 */
		$this->load->model('/pdm2/Main_model');
    } 
	
	function index()
    {	
	
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
	
	/* 신규 댓글 작성 */
	public function save(){
		
		$searchData = $this->input->post();
		$pf_id_arr = [];
		
		$reply_id = $this->Reply_model->get_new_reply_id();	
		
		$result = $this->Reply_model->reply_save($reply_id,$searchData);	//댓글저장
		
		if($_FILES['REPLY_FILE']['name']){
			$pf_id_arr = $this->pdm_file_upload($_FILES,$searchData);	//upload 하고 새로 등록된 pf_id return받기
		}
		
		for($i = 0; $i < count($pf_id_arr); $i++){
			$this->Common_model->insert_file_list($reply_id,$pf_id_arr[$i],$searchData['PLM_TYPE'],'reply_'.$searchData['PLM_TYPE']);
		}
		/*
		if($searchData['URI'] == 'board'){
			$url = 'board/View?board='.$searchData['PLM_TYPE'].'&board_id='.$searchData['BOARD_ID'].'&conts_id='.$searchData['PARENT_ID'];
		}else{
			$url = $searchData['PLM_TYPE'].'/View?id='.$searchData['PARENT_ID'];
		}
		*/
		if($searchData['URI'] == 'board'){
			$url = 'board/View?id='.$searchData['CONTS_ID'].'&c_id='.$searchData['PARENT_ID'];
		}else{
			$url = $searchData['PLM_TYPE'].'/View?id='.$searchData['PARENT_ID'];
		}
		//$url = $searchData['PLM_TYPE'].'/View?id='.$searchData['PARENT_ID'];
		redirect($url);
	}
		
	/* 댓글 수정 */
	public function upd(){
		
		$searchData = $this->input->post();
		
		$pf_id_arr = [];
		
		if($_FILES['REPLY_FILE']['name']){
			$pf_id_arr = $this->pdm_file_upload($_FILES,$searchData);	//upload 하고 새로 등록된 pf_id return받기
		}
		
		$this->fn_file_remove($searchData['REPLY_FILE_DEL']);	//선택한 파일 + 파일정보 삭제
		
		$result = $this->Reply_model->reply_update($searchData);
		
		for($i = 0; $i < count($pf_id_arr); $i++){
			$this->Common_model->insert_file_list($searchData['REPLY_ID'],$pf_id_arr[$i],$searchData['PLM_TYPE'],'reply_'.$searchData['PLM_TYPE']); // 요구사항 첨부파일 등록 
		}
		/*
		if($searchData['URI'] == 'board'){
			$url = 'board/View?board='.$searchData['PLM_TYPE'].'&board_id='.$searchData['BOARD_ID'].'&conts_id='.$searchData['PARENT_ID'];
		}else{
			$url = $searchData['PLM_TYPE'].'/View?id='.$searchData['PARENT_ID'];
		}
		*/
		if($searchData['URI'] == 'board'){
			$url = 'board/View?id='.$searchData['CONTS_ID'].'&c_id='.$searchData['PARENT_ID'];
		}else{
			$url = $searchData['PLM_TYPE'].'/View?id='.$searchData['PARENT_ID'];
		}
		//$url = $searchData['PLM_TYPE'].'/View?id='.$searchData['PARENT_ID'];
		redirect($url);
	}
	
	/* 댓글 삭제 */
	public function delete(){
		
		$searchData = $this->input->post();
		
		$filelist_ids = $this->Reply_model->get_fileLists($searchData['reply_id'],$searchData['plm_type']);
		$file_del_arr = [];
		foreach($filelist_ids as $row) {
	        array_push($file_del_arr,$row->FILELIST_ID);
	    }
		$this->fn_file_remove($file_del_arr);
		$result = $this->Reply_model->reply_delete($searchData);
	//	$result1 = $this->Reply_model->reply_file_delete($searchData['reply_id'],$searchData['plm_type']);
		
		echo json_encode($result);
		
	}
	
	function fn_file_remove($file_del_arr){
		/* 선택된 파일 삭제 시작 */
		for($i = 0; $i < count($file_del_arr); $i++){
			
			$pf_id = $this->Reply_model->get_pf_id($file_del_arr[$i]);
				
			$this->Common_model->remove_pdm_file_info($pf_id);	
			
		//	$pf_id = $this->Common_model->get_pf_id_from_file_list($file_del_arr[$i]);
			//pdm 삭제
			
			$this->Pop_empSearch_model->remove_emp('pdm2', $pf_id);
			$this->Pop_pmsSearch_model->remove_pms('pdm2', $pf_id);
			
		}
		/* 선택된 파일 삭제 끝 */
	}
	
	function pdm_file_upload($uploaded_files,$searchData){
		
		$pf_id_arr = [];
		$uploaded_file_count = count($_FILES['REPLY_FILE']['name']);
		
		for($i=0; $i<$uploaded_file_count; $i++) { 
		
			if($uploaded_files['REPLY_FILE']['name'][$i] == null) continue;
			
			unset($_FILES);
			
			$_FILES['REPLY_FILE']['name']      = $uploaded_files['REPLY_FILE']['name'][$i];
			$_FILES['REPLY_FILE']['type']      = $uploaded_files['REPLY_FILE']['type'][$i];
			$_FILES['REPLY_FILE']['tmp_name']  = $uploaded_files['REPLY_FILE']['tmp_name'][$i];
			$_FILES['REPLY_FILE']['error']     = $uploaded_files['REPLY_FILE']['error'][$i];
			$_FILES['REPLY_FILE']['size']      = $uploaded_files['REPLY_FILE']['size'][$i];
			
			$file_name_old = $_FILES['REPLY_FILE']['name'];
			
			$ext = strtolower(substr(strrchr($file_name_old, '.'), 1)); 
			$file_name = explode(".",$file_name_old)[0] . '.' . $ext; 
			
			$tmp_name = str_replace( '/' , '', $_FILES['REPLY_FILE']['tmp_name'].'.'.$ext  );
			
			$upload_config = Array(
				'upload_path' 	=> './uploads/',
				'allowed_types' => '*',
				'file_ext_tolower' => TRUE,
				'file_name'	=> $tmp_name	// 업로드 할 파일명 변경
			);

			$this->upload->initialize($upload_config);
			
			if($_FILES['REPLY_FILE']['error'] <= 0){
				if( ! $this->upload->do_upload('REPLY_FILE')) {
					echo $this->upload->display_errors();
				}
			}
			
			$this->thumb($tmp_name);
			
			/* 파일 내용 저장 시작 */
			$new_pf_id 	= $this->Upload_model->get_new_pf_id();		/* 새 id 따기 */
			array_push($pf_id_arr,$new_pf_id);
			$this->Upload_model->upload(null,$searchData['TITLE'],'',$searchData['REPLY_CONT'],null,$new_pf_id,$tmp_name,$file_name,$_FILES['REPLY_FILE']['size'],$ext,$searchData['PLM_TYPE']);	// 업로드 등록 
			/* 파일 내용 저장 끝 */
			
			$this->Reply_model->insert_pms($searchData['PARENT_ID'],$new_pf_id);
			$this->Reply_model->insert_emp($searchData['PARENT_ID'],$new_pf_id);
	//		$this->Reply_model->insert_keyword($new_pf_id,'요구사항관리');
			$this->Reply_model->insert_keyword($new_pf_id,'댓글');
			$this->Reply_model->insert_keyword($new_pf_id,$searchData['TITLE']);
		}
		return $pf_id_arr;
	}

}
