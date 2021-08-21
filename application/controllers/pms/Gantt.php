<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gantt extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->model('/pms/Gantt_model');
		$this->load->model('/com/Pop_empSearch_model');
    } 
	
	function index()
    {   
	//	$this->load->view('/public/header');
		$this->load->view('/pms/Gantt');    
	}
	
	/* 존재하는 id인지 체크 */
	function chkId()
	{
		$searchData = $this->input->post();
		
		$result	= $this->Gantt_model->chkId($searchData);
		
		echo json_encode($result);
		
	}
	
	/* 데이터 가져오기 */
	function getData()
	{
		$searchData = $this->input->post();
		
		$result	= $this->Gantt_model->getData($searchData);
		
		$responce = new stdClass();
		
		$i=0;
	    foreach($result as $row) {
			//작업자 시작
			$assigs = '[';
			if(isset($row->EMP_LIST)){
				$empArr = explode(',',$row->EMP_LIST);
				
				for($j = 0; $j < count($empArr); $j++){
					if($j == 0){
						$assigs .= '{';
					}else{
						$assigs .= ',{';
					}
					$assigs .= '"effort":0';
					$assigs .= ',"resourceId":"'.$empArr[$j].'"';
					$assigs .= ',"roleId":"aa"';
					$assigs .= ',"id":"'.$j.'"';
					$assigs .= '}';
				}
				
			}
			$assigs .= ']';
			//작업자 끝
			
	        $responce->tasks[$i]=array(
				 'assigs'=>json_decode($assigs)
				,'canAdd'=>$row->CAN_ADD === 'true'? true: false
				,'canAddIssue'=>$row->CAN_ADD_ISSUE === 'true'? true: false
				,'canDelete'=>$row->CAN_DELETE === 'true'? true: false
				,'canWrite'=>$row->CAN_WRITE === 'true'? true: false
				,'code'=>$row->CODE
				,'collapsed'=>$row->COLLAPSED === 'true'? true: false
				,'depends'=>$row->DEPENDS
				,'description'=>$row->DESCRIPTION
				,'duration'=>intval($row->DURATION)
				,'end'=>(int)$row->END
				,'endIsMilestone'=>$row->END_IS_MILESTONE === 'true'? true: false
				,'hasChild'=>$row->HAS_CHILD === 'true'? true: false
				,'id'=>$row->ID
				,'level'=>intval($row->LEVEL)
				,'name'=>$row->NAME
				,'progress'=>intval($row->PROGRESS)
				,'progressByWorklog'=>$row->PROGRESS_BY_WORKLOG === 'true'? true: false
				,'relevance'=>intval($row->RELEVANCE)
				,'start'=>(int)$row->START
				,'startIsMilestone'=>$row->START_IS_MILESTONE === 'true'? true: false
				,'status'=>$row->STATUS
				,'type'=>$row->TYPE
				,'typeId'=>$row->TYPE_ID
			);
	        $i++;
	    };				
		$responce->zoom = '1M';
		$responce->selectedRow = 0;
		$responce->deletedTaskIds = [];
		
		//resource	
		$emps = '[';
		if($searchData['PP_ID']){
		
			$empList =  $this->Pop_empSearch_model->getEmpList('pms',$searchData['PP_ID']);
			
			for($j = 0; $j < count($empList); $j++){
				if($j == 0){
					$emps .= '{';
				}else{
					$emps .= ',{';
				}
				$emps .= '"id":"'.$empList[$j]->EMP_ID.'"';
				$emps .= ',"name":"'.$empList[$j]->EMP_NM.'"';
				$emps .= '}';
			}
		}
		$emps .= ']';
			
		$responce->resources 		=json_decode($emps);
		$responce->canWrite 		= true;
		$responce->canDelete 		= true;
		$responce->canWriteOnParent = true;
		$responce->canAdd 			= true;
		
		if($i == 0){
			$responce->tasks = [];
		}

		echo json_encode($responce);
	}
	
	/* 저장 */
	function save()
	{
		$tasks = $this->input->post('tasks');
		$PP_ID = $this->input->post('PP_ID');
		
		$this->Gantt_model->del_projectSc($PP_ID);	// 프로젝트일정삭제
		
		for($i = 0; $i < count($tasks); $i++){
			$result = $this->Gantt_model->save($tasks[$i],$PP_ID);
		}
		
		echo json_encode($result);
	}
	
}
