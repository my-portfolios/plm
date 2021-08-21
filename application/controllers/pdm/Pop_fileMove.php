<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pop_fileMove extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->model('/pdm/Pop_fileMove_model');
    } 
	
	function index()
    {   
		
    }
	
	public function move(){
		
		$searchData = $this->input->post();
		
		if(isset($searchData['chkArr'])){
			
			$chkArr = explode(',',$searchData['chkArr']);
			
			for( $i = 0; $i < count($chkArr) ; $i++ ){
				$result0 = $this->Pop_fileMove_model->insertFileVersion($chkArr[$i]);
				if($result0){
					$result = $this->Pop_fileMove_model->move($chkArr[$i],$searchData['parent_id'],$searchData['path']);
				}
			}
			
		}
		
		echo json_encode($result);
		
	}
}
