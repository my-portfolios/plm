<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/pdm/Main_model');
		$this->load->model('/com/Pop_empSearch_model');
        
    } 
	
	function index()
    {   
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/pdm/public/left');
		$this->load->view('/pdm/Main');
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/* 리스트 불러오기 */
	function getChildren(){
		
		$searchData = $this->input->post();
		
		$result = $this->Main_model->getChildren($searchData);
		
		echo json_encode($result);
		
	}
	
	/* 선택삭제 */
	function delete(){
		
		$searchData = $this->input->post();
		$result;
		if(isset($searchData['chkArr'])){
			
			$chkArr = explode(',',$searchData['chkArr']);
			
			for( $i = 0; $i < count($chkArr) ; $i++ ){
				$result 		= $this->Main_model->delete($chkArr[$i]);
				$result_emp 	= $this->Pop_empSearch_model->delyn_emp($this->uri->segment(1), $chkArr[$i],"'Y'");
				$result_keyword = $this->Main_model->delete_pdm_keyword($chkArr[$i]);
			}
			
		}
		
		echo json_encode($result);
	}
	
	/* 영구삭제 */
	function remove(){
		
		$searchData = $this->input->post();
		$result;
		if(isset($searchData['chkArr'])){
			
			$chkArr = explode(',',$searchData['chkArr']);
			
			for( $i = 0; $i < count($chkArr) ; $i++ ){
				
				$tempFileName = $this->Main_model->getDataFix('PLM_PDM_FILE','PF_FILE_TEMP_NM','PF_ID',$chkArr[$i])->PF_FILE_TEMP_NM;//table이름,컬럼명,키컬럼명,ID
				if(file_exists('./uploads/'.$tempFileName) ){
					unlink('./uploads/'.$tempFileName);
				}
				
				$result 		= $this->Main_model->remove($chkArr[$i]);
				$result_emp 	= $this->Pop_empSearch_model->remove_emp($this->uri->segment(1), $chkArr[$i]);
				$result_keyword = $this->Main_model->remove_pdm_keyword($chkArr[$i]);
				
			}
			
		}
		echo json_encode($tempFileName);
	}
	
	/* 복원 */
	function bokwon(){
		
		$searchData = $this->input->post();
		$result;
		if(isset($searchData['chkArr'])){
			
			$chkArr = explode(',',$searchData['chkArr']);
			
			for( $i = 0; $i < count($chkArr) ; $i++ ){
				$result 		= $this->Main_model->bokwon($chkArr[$i]);
				$result_emp 	= $this->Pop_empSearch_model->delyn_emp($this->uri->segment(1), $chkArr[$i],'null');
				$result_keyword = $this->Main_model->bokwon_pdm_keyword($chkArr[$i]);
			}
			
		}
		
		echo json_encode($result);
	}
	
	/* 검색 */
	function search(){
		
		$searchData = $this->input->post();
		
		$result = $this->Main_model->search($searchData);
		
		echo json_encode($result);
	
	}
	
}
