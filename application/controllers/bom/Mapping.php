<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapping extends CI_Controller {

	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageSeg(){ return 'bom'; } //seg 1
	function pageType(){ return 'Mapping'; }
	function pageModel(){ return 'Mapping_model'; }
	
	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->model('/'.$this->pageSeg().'/'.$this->pageModel(),$this->pageModel());
		$this->load->model('/Common_model');	
		$this->load->library('upload');
	} 
	
	function index()
	{   
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/'.$this->pageSeg().'/public/left');
		$this->load->view('/'.$this->pageSeg().'/'.$this->pageType());
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
	}
		
	
	function loadData(){
		
		if(isset($_POST['REMOVE_ARR']) && $_POST['REMOVE_ARR'] != ''){//삭제있으면
			$ARR = $this->input->post('REMOVE_ARR');
			foreach ($ARR as $i){
				$query = $this->{$this->pageModel()}->del_mapping($i);
			}
		}
		
		/*그리드*/
		$page = isset($_POST['page'])?$_POST['page']:1; 
		$limit = isset($_POST['rows'])?$_POST['rows']:10; 
		$sidx = isset($_POST['sidx'])?$_POST['sidx']:'INS_DT'; 
		$sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 

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
			$where = "$searchField $ops '$searchString' "; 
			/*
			if($searchField == 'PP_NM'){
				$where = " INS_ID IN (select PE_ID from PLM_EMP where PE_NM like '%".urldecode($searchString)."%') ";
			}else{
				
			}
			*/
		}
	
    if(!$sidx) 
        $sidx =1;
    
		$query = $this->{$this->pageModel()}->getAllData('0','0',$sidx,$sord,$where); 
		$count = count($query);
		
    if( $count > 0 ) {
        $total_pages = ceil($count/$limit);    
    } else {
        $total_pages = 0;
    }
		$responce = new stdClass();
    if ($page > $total_pages) 
		$page=$total_pages;
	    $query = $this->{$this->pageModel()}->getAllData($start,$limit,$sidx,$sord,$where); 
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
	    $i=0;
	    foreach($query as $row) {
	        $responce->rows[$i]['id']=$row->PP_ID;
	        $responce->rows[$i]['cell']=array($row->PP_ID,$row->PP_NM,$row->INS_DT);
	        $i++;
	    }
   	 echo json_encode($responce);
	}
	
}