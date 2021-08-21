<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pop_folder extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->model('/pdm/Pop_folder_model');
    } 
	
	function index()
    {   
		
    }
	
	/* 폴더추가 */
	public function addFolder(){
		
		$searchData = $this->input->post();
		
		$new_pfd_id = $this->Pop_folder_model->get_new_pfd_id();
		
		$result = $this->Pop_folder_model->addFolder($searchData,$new_pfd_id);
		
		echo json_encode($new_pfd_id);
		
	}
	
	/* 이름변경 */
	public function updateFolder(){
		
		$searchData = $this->input->post();
		
		$result = $this->Pop_folder_model->updateFolder($searchData);
		
		echo json_encode($result);
		
	}
	
	/* 폴더삭제 */
	public function deleteFolder(){
		
		$searchData = $this->input->post();
		$dels = "'";
		$dels .= implode("','",$searchData['delArr'][0]);
		$dels .= "'";
		$result = $this->Pop_folder_model->deleteFolder($dels);
		
		echo json_encode($result);
		
	}
	
	/* 폴더 이동 */
	public function moveFolder(){
		
		$searchData = $this->input->post();

		$result = $this->Pop_folder_model->moveFolder($searchData);
		
		echo json_encode($result);
	}
	
	/* 하위 파일 있는지 체크 */
	public function chkChildFile(){
		
		$searchData = $this->input->post();

		$result = $this->Pop_folder_model->chkChildFile($searchData);
		
		echo json_encode($result);
		
	}
}
