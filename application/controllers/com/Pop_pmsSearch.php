<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pop_pmsSearch extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->model('/com/Pop_pmsSearch_model');
    } 
	
	function index()
    {   
		
    }
	
	/*jqgrid pdm*/
	public function searchGridModify(){
		
		$type 	= $this->input->get('type');
		$id 	= $this->input->get('id');
		
		$count = $this->db->count_all_results('PLM_PMS_LIST'); 
		
		$responce = new stdClass();
		$query = $this->Pop_pmsSearch_model->getMapingData($id,$type); 
		$responce->page = 1;
		$responce->total = 100;
		$responce->records = $count;
		$i=0;
		foreach($query as $row) {
			$responce->rows[$i]['id']=$row->PP_ID;
			$responce->rows[$i]['cell']=array($row->PP_ID,$row->PP_NM);
			$i++;
		}
		echo json_encode($responce);
	}
	
	/*jqgrid*/
	public function searchGrid(){
		
		$page = isset($_POST['page'])?$_POST['page']:1; 
		$limit = isset($_POST['rows'])?$_POST['rows']:10; 
		$sidx = isset($_POST['sidx'])?$_POST['sidx']:'PE_ID'; 
		$sord = isset($_POST['sord'])?$_POST['sord']:'';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 

		$where = ""; 
		$searchField = isset($_POST['searchField']) ? $_POST['searchField'] : false;
		$searchOper = isset($_POST['searchOper']) ? $_POST['searchOper']: false;
		$searchString = isset($_POST['searchString']) ? $_POST['searchString'] : false;

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
		
		$query = $this->Pop_pmsSearch_model->getAllData($start,$limit,$sidx,$sord,$where); 
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
			$responce->rows[$i]['id']=$row->PP_ID;
			$responce->rows[$i]['cell']=array($row->PP_ID,$row->PP_NM);
			$i++;
		}
		echo json_encode($responce);
	}
	
}
