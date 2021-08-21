<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
		$this->load->model('/Common_model');
        $this->load->model('/pdm2/Main_model');
        $this->load->helper("file");
    } 
    
	function test1(){
		$this->Main_model->test1();
	}
	
	function index()
    {   
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/pdm2/public/left');
		$this->load->view('/pdm2/Main');
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot'); 

		$this->Main_model->init();
    }
    
	function fa_check(){//사용안함
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
	
	//pf_id 들의 작성ID 가 sessionID랑 같은지 확인
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
		if(isset($_POST['DEL_ARR']) && $_POST['DEL_ARR'] !='' ){//삭제있으면
			$ARR = $this->input->post('DEL_ARR');
			$USER = $this->input->post('UPD_ID');
			foreach ($ARR as $i){
				$this->Main_model->del($i,$USER);
			}
		}
		
		if(isset($_POST['REMOVE_ARR']) && $_POST['REMOVE_ARR'] !='' ){//영구삭제있으면
			$this->load->model('/com/Pop_empSearch_model');
			$this->load->model('/com/Pop_pmsSearch_model');
			$ARR = $this->input->post('REMOVE_ARR');
			foreach ($ARR as $i){
				
				$this->Common_model->remove_pdm_file_info($i);
				$this->Pop_empSearch_model->remove_emp($this->uri->segment(1), $i);
				$this->Pop_pmsSearch_model->remove_pms($this->uri->segment(1), $i);
			}
		}
		
		if(isset($_POST['BOKWON_ARR']) && $_POST['BOKWON_ARR'] !='' ){//복원있으면
			$ARR = $this->input->post('BOKWON_ARR');
			foreach ($ARR as $i){
				$this->Main_model->bokwon($i);
			}
		}
		
		if(isset($_POST['TAB'])){//탭이있으면
			$TAB = $_POST['TAB'];
		}else{
			$TAB = '1';	//진행중
		}
		
		if(isset($_POST['treeid'])){//트리선택있으면
			$treeid = $_POST['treeid'];
		}else{
			$treeid = 'PLM';	//없으면
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
		$searchString = isset($_POST['searchString']) ? urldecode($_POST['searchString']) : false;
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

        $where = "$searchField $ops '$searchString' "; 
		
    }
	
    if(!$sidx) 
        $sidx =1;
    
		$query = $this->Main_model->getAllData('0','0',$sidx,$sord,$where,$FA_SORT_STAR,$TAB,$FA_USER,$FA_TYPE,$treeid,$_search1,$docType); 
		$count = count($query);    		
    if( $count > 0 ) {
        $total_pages = ceil($count/$limit);    
    } else {
        $total_pages = 0;
    }
		$responce = new stdClass();
    if ($page > $total_pages) 
      $page=$total_pages;
	    $query = $this->Main_model->getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$TAB,$FA_USER,$FA_TYPE,$treeid,$_search1,$docType); 
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
	    $i=0;
	    foreach($query as $row) {
	        $responce->rows[$i]['id']=$row->PF_ID;
	        $responce->rows[$i]['cell']=array($row->PF_ID,$row->PFD_ID,$row->PF_NM,$row->PC_NM,$row->PF_PATH,$row->PF_FILE_SIZE,$row->PF_FILE_EXT,$row->INS_NM,$row->INS_DT,$row->UPD_ID,$row->UPD_DT,$row->PF_FILE_TEMP_NM,$row->PF_FILE_REAL_NM,$row->KEYWORD,$row->FA_CNT);
	        $i++;
	    }
   	 echo json_encode($responce);
	}
	
	
	
}
