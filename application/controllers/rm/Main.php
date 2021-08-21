<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	 
	function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/rm/Main_model','Main_model');
		$this->load->model('/com/Pop_empSearch_model');
		$this->load->model('/com/Pop_pmsSearch_model');
		$this->load->model('/Common_model');
	//	$this->load->model('/pdm2/Main_model','pdm2_main_model');
		$this->load->library('upload');
    } 
	
	function index()
    {   
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/rm/public/left');
		$this->load->view('/rm/Main');
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
		
	function fa_check(){
		if(isset($_POST['FA_CHECK'])){
			if($_POST['FA_CHECK'] != ''){
			$FA_CHECK = $_POST['FA_CHECK'];
			$FA_TYPE = $_POST['FA_TYPE'];
			$FA_USER = $_POST['FA_USER'];
			$query = $this->Main_model->fa_check($FA_CHECK,$FA_TYPE,$FA_USER); 
			echo json_encode($query);
			}
		}
	}
	
	//pr_id 들의 작성ID 가 sessionID랑 같은지 확인
	function chkInsId(){
		$result = true;
		$ids = $this->input->post('ids');
		foreach ($ids as $i){
			$ins_id = $this->Main_model->chkInsId($i); 
			if($ins_id != $this->session->userdata('userid')){
				$result = false;
			}
		}
		echo json_encode($result);
	}
	
	function loadData(){
		if(isset($_POST['FA_YN'])){//즐찾추가
			if($_POST['FA_YN'] != ''){
			$FA_TYPE = $_POST['FA_TYPE'];
			$FA_VAL = $_POST['FA_VAL'];
			$FA_USER = $_POST['FA_USER'];
			$query = $this->Main_model->faYn($FA_TYPE,$FA_VAL,$FA_USER); 
			}
		}
		if(isset($_POST['DEL_ARR']) && $_POST['DEL_ARR'] != ''){//삭제있으면
			$ARR = $this->input->post('DEL_ARR');
			
			foreach ($ARR as $i){
				
				$query = $this->Main_model->del($i,$_SESSION['userid'],"Y");
				
			}
		}
		
		if(isset($_POST['REMOVE_ARR']) && $_POST['REMOVE_ARR'] != ''){//영구삭제있으면
			$ARR = $this->input->post('REMOVE_ARR');
			
			foreach ($ARR as $i){
				//파일삭제 시작(파일부터 삭제해야됨)
				//기본 파일삭제
				//댓글도삭제해야할듯	
				$pf_ids = $this->Main_model->get_pf_ids($i);
				$reply_pf_ids = $this->Main_model->get_reply_pf_ids($i);
				
				//파일삭제 끝
				$query = $this->Main_model->p_del($i);
				
				$this->remove_file($pf_ids);
				
				$this->remove_file($reply_pf_ids);
			}
		}
		
		if(isset($_POST['BOKWON_ARR']) && $_POST['BOKWON_ARR'] != ''){//복원이면
			$ARR = $this->input->post('BOKWON_ARR');
			
			foreach ($ARR as $i){
				$query = $this->Main_model->del($i,$_SESSION['userid'],null);
			}
		}
		
		if(isset($_POST['TAB'])){//탭이있으면
			$TAB = $_POST['TAB'];
		}else{
			$TAB = '1';	//접수완료
		}
		
		$FA_SORT_STAR = '';
		$FA_USER = '';
		$FA_TYPE = '';

		if(isset($_POST['FA_SORT_STAR'])){//즐겨찾기면
			if($_POST['FA_SORT_STAR'] == 'true'){
				$FA_SORT_STAR = $_POST['FA_SORT_STAR'];
				$FA_USER = $_POST['FA_USER'];
				$FA_TYPE = $_POST['FA_TYPE'];
			}
		}

    $page = isset($_POST['page'])?$_POST['page']:1; 
    $limit = isset($_POST['rows'])?$_POST['rows']:10; 
    $sidx = isset($_POST['sidx'])?$_POST['sidx']:'UPD_DT'; 
    $sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
    $start = $limit*$page - $limit; 
    $start = ($start<0)?0:$start; 

    $where = ""; 
    $searchField = isset($_POST['searchField']) ? $_POST['searchField'] : false;
    $searchOper = isset($_POST['searchOper']) ? $_POST['searchOper']: false;
    $searchString = urldecode( isset($_POST['searchString']) ? $_POST['searchString'] : false );
    $docType = isset($_POST['docType']) ? $_POST['docType'] : false;

		$_search = isset($_POST['_search']) ? $_POST['_search'] : 'false';
		$_search1 = isset($_POST['_search1']) ? $_POST['_search1'] : 'false';

    if ($_search == 'true' || $_search1 == 'true') {
        $ops = array(
        'eq'=>'=', 
        'ne'=>'<>',
        'lt'=>'<', 
        'le'=>'<=',
        'gt'=>'>', 
        'ge'=>'>=',
        'bw'=>'LIKE',	
        'bn'=>'NOT LIKE',
        'in'=>'LIKE', 
        'ni'=>'NOT LIKE', 
        'ew'=>'LIKE', 
        'en'=>'NOT LIKE', 
        'cn'=>'LIKE', 
        'nc'=>'NOT LIKE' 
        );
        foreach ($ops as $key=>$value){
            if ($searchOper==$key) {
                $ops = $value;
            }
        }
        if($searchOper == 'eq' ) $searchString = $searchString;
        if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
        if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
        if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';
		
		if($searchField == 'INS_NM'){
			$where = " INS_ID IN (select PE_ID from PLM_EMP where PE_NM like '%".urldecode($searchString)."%') ";
		}else if($searchField == 'PC_NM'){
			$where = " ( 
							select group_concat(a.PC_NM separator ',')
							from PLM_COMP a
							where a.PC_ID in (
													select b.PC_ID
													from PLM_COMP_LIST b
													where b.PLM_TYPE = 'pms'
													and b.PARENT_ID in (
																				select c.PP_ID
																				from PLM_PMS_LIST c
																				where c.PP_ID = b.PARENT_ID
																				and c.PLM_TYPE = '".$this->uri->segment(1)."'
																				and c.PARENT_ID = a.PR_ID
																				)
												)
						 ) LIKE '%".urldecode($searchString)."%' ";
		}else{
			$where = "$searchField $ops '$searchString' "; 
		}
		
    }
	
    if(!$sidx) 
        $sidx =1;
    
		$query = $this->Main_model->getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$TAB,$FA_USER,$FA_TYPE,$docType); 
		$count = count($query);
		
    if( $count > 0 ) {
        $total_pages = ceil($count/$limit);    
    } else {
        $total_pages = 0;
    }
		$responce = new stdClass();
    if ($page > $total_pages) 
      $page=$total_pages;
	    
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
	    $i=0;
	    foreach($query as $row) {
	        $responce->rows[$i]['id']=$row->PR_ID;
	        $responce->rows[$i]['cell']=array($row->PR_ID,$row->PR_TITLE,$row->PC_NM,$row->PR_HOPE_END_DAT,$row->PR_STATUS,$row->INS_ID,$row->INS_NM,$row->UPD_DT,$row->FA_CNT);
	        $i++;
	    }
   	 echo json_encode($responce);
	}
	
	function remove_file($pf_ids){
		
		foreach ($pf_ids as $r)
		{
			
			$this->Pop_empSearch_model->remove_emp('pdm2', $r->PF_ID);
			$this->Pop_pmsSearch_model->remove_pms('pdm2', $r->PF_ID);
			$this->Main_model->remove_pdm_keyword($r->PF_ID);
			
			$this->Common_model->remove_files($r->PF_ID);
			
			$this->Main_model->pdm_remove($r->PF_ID);
		}
	}
	
}
