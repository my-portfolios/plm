<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BomView extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/pms/BomView_model');
    } 
	
	function index()
    {   
	/*
		if($_GET){
			$pp_id 					= $_GET['id'];
			$data['list'] 			= $this->getData($pp_id);
		}else{
			$data['list'] 			= null;
		}
		*/
		$this->load->view('/public/header');
		//$this->load->view('/public/top');
		//$this->load->view('/pms/public/left');
		$this->load->view('/pms/bomView');
		//$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	
	function getData(){
		
		$pp_id = $this->input->post('id');
		$page = isset($_POST['page'])?$_POST['page']:1; 
		$limit = isset($_POST['rows'])?$_POST['rows']:10; 
		$sidx = isset($_POST['sidx'])?$_POST['sidx']:'PART_NM'; 
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
    }
		

    if(!$sidx) 
        $sidx =1;
    
		$query = $this->BomView_model->getData('0','9999999999',$sidx,$sord,$pp_id,$where); 
		$count = count($query);
		if( $count > 0 ) {
			$total_pages = ceil($count/$limit);    
		} else {
			$total_pages = 0;
		}
		$responce = new stdClass();
		if ($page > $total_pages) $page=$total_pages;
			$query = $this->BomView_model->getData($start,$limit,$sidx,$sord,$pp_id,$where); 
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
		
	    $i=0;
	    foreach($query as $row) {
	        $responce->rows[$i]['id']=$row->PART_ID;
	        $responce->rows[$i]['cell']=array($row->PART_ID,$row->PART_NM,$row->BP_STD,$row->BP_MTR,$row->BCD_AMT,$row->GUBUN,$row->BPD_ID,$row->BPD_NM);
	        $i++;
	    }
		echo json_encode($responce);
	}
}
