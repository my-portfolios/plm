<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageSeg(){ return 'board'; } //seg 1
	function pageType(){ return 'Main'; }
	function pageModel(){ return 'Main_model'; }
	
	function __construct()
	{
		parent::__construct();
    
		$this->load->helper('url');
		$this->load->model('/'.$this->pageSeg().'/'.$this->pageModel(),$this->pageModel());
		$this->load->model('/board/Write_model');
		$this->load->model('/Common_model');	
		$this->load->library('upload');
	} 
	
	function index()
	{   
    	if(!isset($_GET['id'])){
			
			$board_id = $this->{$this->pageModel()}->getFirstBoard();
			redirect($this->pageSeg().'/'.$this->pageType().'?id='.$board_id);
		}else{
		
			$this->load->view('/public/header');
			$this->load->view('/public/top');
			$this->load->view('/'.$this->pageSeg().'/public/left');
			$this->load->view('/'.$this->pageSeg().'/'.$this->pageType());
			$this->load->view('/public/bottom');
			$this->load->view('/public/foot');
		}
	}
	
	//conts_id 들의 작성ID 가 sessionID랑 같은지 확인
	function chkInsId(){
		$result = true;
		$ids = $this->input->post('ids');
		foreach ($ids as $i){
			$ins_id =$this->{$this->pageModel()}->chkInsId($i); 
			if($ins_id != $this->session->userdata('userid')){
				$result = false;
			}
		}
		echo json_encode($result);
	}
	
	//게시판 불러오기
	function getBoardList(){
		$result = $this->{$this->pageModel()}->getBoardList(); 
		echo json_encode($result);
	}
		
	
	function loadData(){
		
		if(isset($_POST['REMOVE_ARR']) && $_POST['REMOVE_ARR'] != ''){//삭제있으면
			$ARR = $this->input->post('REMOVE_ARR');
			/*
			foreach ($ARR as $i){
				$pf_ids = $this->{$this->pageModel()}->get_pf_ids($i);
				$query = $this->{$this->pageModel()}->p_del($i);
				$this->remove_file($pf_ids);
			}*/
			
			
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
		
		/*즐겨찾기*/
		if(isset($_POST['FA_YN'])){//즐찾추가
			if($_POST['FA_YN'] != ''){
			$FA_TYPE = $_POST['FA_TYPE'];
			$FA_VAL = $_POST['FA_VAL'];
			$FA_USER = $_POST['FA_USER'];
			$query = $this->{$this->pageModel()}->faYn($FA_TYPE,$FA_VAL,$FA_USER); 
			}
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
		/*즐겨찾기 끝*/

		/*그리드*/
		$page = isset($_POST['page'])?$_POST['page']:1; 
		$limit = isset($_POST['rows'])?$_POST['rows']:10; 
		$sidx = isset($_POST['sidx'])?$_POST['sidx']:'INS_DT'; 
		$sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
		
		$board_id = $this->input->get('board_id');
		$where = ""; 
		$searchField = isset($_POST['searchField']) ? $_POST['searchField'] : false;
		$searchOper = isset($_POST['searchOper']) ? $_POST['searchOper']: false;
		$searchString = urldecode( isset($_POST['searchString']) ? $_POST['searchString'] : false );

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
		}else{
			$where = "$searchField $ops '$searchString' "; 
		}
    }
	
    if(!$sidx) 
        $sidx =1;
    
		$query = $this->{$this->pageModel()}->getAllData('0','0',$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE,$board_id); 
		$count = count($query);
		
    if( $count > 0 ) {
        $total_pages = ceil($count/$limit);    
    } else {
        $total_pages = 0;
    }
		$responce = new stdClass();
    if ($page > $total_pages) 
      $page=$total_pages;
	    $query = $this->{$this->pageModel()}->getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE,$board_id); 
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
	    $i=-1;
	    foreach($query as $row) {
			//공지 처리
			if($this->get_is_notice($row->PARENT_ID,$row->CONTS_ID)) {
				$row->BOARD_NOTICE = "Y";
				$i++;
			} else continue;
	        $responce->rows[$i]['cell']=array($row->CONTS_ID,$row->CONTS_TITLE,$row->INS_ID,$row->INS_NM,$row->UPD_ID,$row->UPD_DT,$row->FA_CNT,$row->BOARD_NOTICE);
	        $responce->rows[$i]['id']=$row->CONTS_ID;
		}
		foreach($query as $row) {
			//공지 처리
			if($this->get_is_notice($row->PARENT_ID,$row->CONTS_ID)) continue;
			else {
				$row->BOARD_NOTICE = "N";
				$i++;
			}
	        $responce->rows[$i]['cell']=array($row->CONTS_ID,$row->CONTS_TITLE,$row->INS_ID,$row->INS_NM,$row->UPD_ID,$row->UPD_DT,$row->FA_CNT,$row->BOARD_NOTICE);
	        $responce->rows[$i]['id']=$row->CONTS_ID;
	    }
   	 echo json_encode($responce);
	}
	
	function remove_file($pf_ids){
		foreach ($pf_ids as $r)
		{
			
			//$this->{$this->pageModel()}->remove_pdm_keyword($r->PF_ID);
			$this->Common_model->remove_pdm_file_info($r->PF_ID);
			//$this->{$this->pageModel()}->pdm_remove($r->PF_ID);
		}
	}
	
	function get_is_notice($board_id, $cont_id){
		
		$board_notice_str = $this->Write_model->get_board_notice_str($board_id);
		if(strpos($board_notice_str, $cont_id.",") !== false)  return true;
		
		return false;
	}

}
