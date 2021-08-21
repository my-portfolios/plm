<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group_write extends CI_Controller {
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageSeg(){ return 'admin'; } //seg 1
	function pageType(){ return 'Group_write'; } //seg 2
	function pageModel(){ return 'Group_write_model'; } // model
	function pageReturn(){ return 'Group'; } //def,PLM_TYPE,return
	/*파일저장시*/
	function fileName(){ return 'PG_NM'; } //제목 name
	function fileCont(){ return 'PG_NM'; } //컨텐츠 name
	function fileKeyWord(){ return '그룹관리'; } //파일저장시 pdm 키워드이름
	
	function fileId(){ return 'PG_ID';}
	
		 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/'.$this->pageSeg().'/'.$this->pageModel());
		$this->load->model('/pdm2/Upload_model');/* 업로드 */
		$this->load->model('/pdm2/Main_model');
		$this->load->model('/com/Pop_empSearch_model');
		$this->load->model('/com/Pop_pmsSearch_model');	
		$this->load->model('/com/Pop_memSearch_model');			
		$this->load->model('/Common_model');
		$this->load->library('upload');
    } 
	
	/*User_write 로딩*/
	function index()
    {   
    	//수정이면
    	if(isset($_GET['id'])){
			$id = $_GET['id'];
			$data['list'] 		= $this->{$this->pageModel()}->getData($id);
			$data['memList']	= $this->Pop_memSearch_model->getMemList($id);
		}else{
			$new_id	= $this->{$this->pageModel()}->get_new_id();
			$data['list'] 		= $new_id;
			$data['memList']	= null;
		}
	
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/'.$this->pageSeg().'/public/left');
		$this->load->view('/'.$this->pageSeg().'/'.$this->pageType(),$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/*썸네일생성*/
	function thumb($tmp_name){
		$config2['image_library'] = 'gd2';
		$config2['source_image']	= './uploads/'.$tmp_name;
		$config2['create_thumb'] = TRUE;
		$config2['maintain_ratio'] = TRUE;
		//$config2['width']	= 70;
		$config2['height']	= 70;
		
		$this->load->library('image_lib', $config2); 
		$this->image_lib->initialize($config2);
		$this->image_lib->resize();
		if ( ! $this->image_lib->resize())
		{
			$this->image_lib->display_errors('<p>썸네일이 정상적으로 저장되지 않았습니다. 다시 한번 더 시도해주세요.', '</p>');
		}
	}
	
	/* 저장 */
	public function save(){
		$searchData = $this->input->post();

        $new_id	= $this->{$this->pageModel()}->get_new_id();

		/* 저장 */
		$this->{$this->pageModel()}->insert($new_id, $searchData);	// 등록 

		redirect($this->pageSeg().'/'.$this->pageReturn());
	}
	
	/* 수정 */
	public function upd(){
		
		$searchData = $this->input->post();

		//구성원 추가삭제
		$memArr = $this->input->post("PF_MEM");
		$result0 = $this->Pop_memSearch_model->del_mem($searchData['PG_ID']);
		for( $i = 0; $i < count($memArr) ; $i++ ){
			$result1 = $this->Pop_memSearch_model->insert_mem($memArr[$i], $searchData['PG_ID']);
		}

		/* 수정 */
		$this->{$this->pageModel()}->update($searchData);	

		redirect($this->pageSeg().'/'.$this->pageReturn());
	}

	/* 한 파일만 삭제 */
	public function delete(){
		
		$file_del = $this->input->get('fileName');
		$PE_ID = $this->input->get('id');

		$file_del_arr[0] = $file_del;

		if(!isset($file_del)) {
			echo("<script>alert('파일이 선택되지 않았거나 잘못된 접근입니다.');history.back();</script>");
			exit;
		}
		
		$this->fn_delete_file($file_del_arr);
		
		redirect($this->pageSeg().'/'.$this->pageReturn($PE_ID));
	}
	
	//파일 삭제 (array)
	function fn_delete_file($file_del_arr){
		
		for($i = 0; $i < count($file_del_arr); $i++){
			
			$pf_file_temp_nm = $this->{$this->pageModel()}->get_pf_file_temp_nm($file_del_arr[$i]);
			
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
			
			$this->Common_model->delete_file_list($file_del_arr[$i]);	//파일삭제
		}
		
	}
	
	//변경된 내용을 pdm에도 적용
	function fn_update_pdm($searchData){
		$this->{$this->pageModel()}->update_pdm($searchData); //pdm 내용 수정
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
			$this->Upload_model->upload(null,$searchData[$this->fileName()].'('.$i.')','',$searchData[$this->fileCont()],null,$new_pf_id,$tmp_name,$file_name ,$_FILES['PF_FILE']['size'],$ext,strtolower($this->pageReturn()));	// 업로드 등록 
			$this->Upload_model->insert_keyword($new_pf_id,$this->fileKeyWord());	 // 키워드 등록(위치)
			$this->Upload_model->insert_keyword($new_pf_id,$searchData['PE_NM']);	 // 키워드 등록(이름)
			/* 파일 내용 저장 끝 */
			array_push($pf_id_arr,$new_pf_id);
		}
		return $pf_id_arr;
	}
	
	
}
