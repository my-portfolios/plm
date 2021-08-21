<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mapping_write extends CI_Controller {
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageSeg(){ return 'bom'; } //seg 1
	function pageType(){ return 'Mapping_write'; } //seg 2
	function pageModel(){ return 'Mapping_write_model'; } // model
	function pageReturn(){ return 'Mapping'; } //def,PLM_TYPE,return
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/'.$this->pageSeg().'/'.$this->pageModel());		
		$this->load->model('/Common_model');
    } 
	
	/*Mapping_write 로딩*/
	function index()
    {   
    	if(isset($_GET['id'])){
			$id = $_GET['id'];
			$data['list'] 		= $this->{$this->pageModel()}->getData($id);
		}else{
			$data['list'] 		= null;
		}
	
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/'.$this->pageSeg().'/public/left');
		$this->load->view('/'.$this->pageSeg().'/'.$this->pageType(),$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/* 저장 */
	public function save(){
		$searchData = $this->input->post();
		
		$this->{$this->pageModel()}->del_bom_pms($searchData['PP_ID']);

		//부품 정보 저장
		$this->{$this->pageModel()}->del_cnt($searchData['PP_ID']);
		for($i = 0; $i < count($searchData['BP_IDS']); $i++){
			$this->{$this->pageModel()}->insert_bom_pms($searchData['PP_ID'],'PART',$searchData['BP_IDS'][$i]);
			$this->{$this->pageModel()}->insert_cnt($searchData['PP_ID'],'part',$searchData['BP_IDS'][$i],$searchData['BCD_AMT'][$i]);	//부품 수량 저장
		}
		
		//카테고리 정보 저장
		for($i = 0; $i < count($searchData['BC_ID']); $i++){
			$this->{$this->pageModel()}->insert_bom_pms($searchData['PP_ID'],'CATE',$searchData['BC_ID'][$i]);
		}
		
		//제품 정보 저장
		for($i = 0; $i < count($searchData['BPD_ID']); $i++){
			$this->{$this->pageModel()}->insert_bom_pms($searchData['PP_ID'],'PDT',$searchData['BPD_ID'][$i]);
		}
		
		redirect($this->pageSeg().'/'.$this->pageReturn());
	}
	
	/* 수정 */
	public function upd(){
		
		// $searchData = $this->input->post();
		 
		// $this->{$this->pageModel()}->update($searchData);	
		 
		// // 변경내용을 pdm에도 적용
		// $result_update_pdm = $this->fn_update_pdm($searchData);	
		
		// // 첨부파일 등록 
		// if($result_update_pdm){
		// 	for($i = 0; $i < count($pf_id_arr); $i++){
		// 		$this->Common_model->insert_file_list($searchData[$this->fileId()],$pf_id_arr[$i],strtolower($this->pageReturn()),'normal'); 
		// 	}
		// }
	
		// //부품정보 저장
		// $this->{$this->pageModel()}->del_cnt($searchData['BPD_ID']);
		// $this->{$this->pageModel()}->del_part($searchData['BPD_ID']);
		// for($i = 0; $i < count($searchData['BP_IDS']); $i++){
		// 	$this->{$this->pageModel()}->insert_part($searchData['BPD_ID'],$searchData['BCD_ID'][$i],$searchData['BP_IDS'][$i],$searchData['BCD_AMT'][$i]);
		// 	$this->{$this->pageModel()}->insert_cnt($searchData['BPD_ID'],'part',$searchData['BP_IDS'][$i],$searchData['BCD_AMT'][$i]);	//부품 수량 저장
		// }
		
		// //카테고리 정보 저장
		// $this->{$this->pageModel()}->del_cate($searchData['BPD_ID']);
		// for($i = 0; $i < count($searchData['BC_ID']); $i++){
		// 	$this->{$this->pageModel()}->insert_cate($searchData['BPD_ID'],$searchData['BC_ID'][$i]);
		// }
		
		redirect($this->pageSeg().'/'.$this->pageReturn());
		
	}
	
	
	//제품 리스트 가져오기
	function loadPdt(){
		$detail = $this->input->get('detail');
		if($detail != ''){
			/*그리드*/
	    $page = isset($_POST['page'])?$_POST['page']:1; 
	    $limit = isset($_POST['rows'])?$_POST['rows']:10; 
	    $sidx = isset($_POST['sidx'])?$_POST['sidx']:'INS_DT'; 
	    $sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
	    $start = $limit*$page - $limit; 
	    $start = ($start<0)?0:$start; 
	    $where = ""; 
	    if(!$sidx) 
	        $sidx =1;
			$query = $this->{$this->pageModel()}->loadPdt('0','0',$sidx,$sord,$where,$detail); 
			$count = count($query);
			
	    if( $count > 0 ) {
	        $total_pages = ceil($count/$limit);    
	    } else {
	        $total_pages = 0;
	    }
			$responce = new stdClass();
	    if ($page > $total_pages) 
	      $page=$total_pages;
		  	$query = $this->{$this->pageModel()}->loadPdt($start,$limit,$sidx,$sord,$where,$detail);   
		    $responce->page = $page;
		    $responce->total = $total_pages;
		    $responce->records = $count;
		    $i=0;
		    foreach($query as $row) {
		        $responce->rows[$i]['id']=$row->BPD_ID;
		        $responce->rows[$i]['cell']=array($row->BPD_ID,$row->BPD_CD,$row->BPD_NM);
		        $i++;
		    }
	   	 echo json_encode($responce);
	   	 //echo json_encode($count);
			
			//grid_end
		}else{
			echo json_encode(null);
		}
	}
	
	//제품에 대한 카테고리 리스트 가져오리
	function loadPdtDtl(){
		$detail = $this->input->get('detail');
		if($detail != ''){
			/*그리드*/
	    $page = isset($_POST['page'])?$_POST['page']:1; 
	    $limit = isset($_POST['rows'])?$_POST['rows']:10; 
	    $sidx = isset($_POST['sidx'])?$_POST['sidx']:'INS_DT'; 
	    $sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
	    $start = $limit*$page - $limit; 
	    $start = ($start<0)?0:$start; 
	    $where = ""; 
	    if(!$sidx) 
	        $sidx =1;
			$query = $this->{$this->pageModel()}->loadPdtDtl('0','0',$sidx,$sord,$where,$detail); 
			$count = count($query);
			
	    if( $count > 0 ) {
	        $total_pages = ceil($count/$limit);    
	    } else {
	        $total_pages = 0;
	    }
			$responce = new stdClass();
	    if ($page > $total_pages) 
	      $page=$total_pages;
		  	$query = $this->{$this->pageModel()}->loadPdtDtl($start,$limit,$sidx,$sord,$where,$detail);   
		    $responce->page = $page;
		    $responce->total = $total_pages;
		    $responce->records = $count;
		    $i=0;
		    foreach($query as $row) {
		        $responce->rows[$i]['id']=$row->BC_ID;
		        $responce->rows[$i]['cell']=array($row->BC_ID,$row->GUBUN,$row->BC_NM,$row->BP_STD,$row->BP_MTR,$row->BP_AMT);
		        $i++;
		    }
	   	 echo json_encode($responce);
	   	 //echo json_encode($count);
			
			//grid_end
		}else{
			echo json_encode(null);
		}
	}
	
	function loadCateDtl(){
		$detail = $this->input->get('detail');
		if($detail != ''){
			/*그리드*/
	    $page = isset($_POST['page'])?$_POST['page']:1; 
	    $limit = isset($_POST['rows'])?$_POST['rows']:10; 
	    $sidx = isset($_POST['sidx'])?$_POST['sidx']:'INS_DT'; 
	    $sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
	    $start = $limit*$page - $limit; 
	    $start = ($start<0)?0:$start; 
	    $where = ""; 
	    if(!$sidx) 
	        $sidx =1;
			$query = $this->{$this->pageModel()}->loadCateDtl('0','0',$sidx,$sord,$where,$detail); 
			$count = count($query);
			
	    if( $count > 0 ) {
	        $total_pages = ceil($count/$limit);    
	    } else {
	        $total_pages = 0;
	    }
			$responce = new stdClass();
	    if ($page > $total_pages) 
	      $page=$total_pages;
		  	$query = $this->{$this->pageModel()}->loadCateDtl($start,$limit,$sidx,$sord,$where,$detail);   
		    $responce->page = $page;
		    $responce->total = $total_pages;
		    $responce->records = $count;
		    $i=0;
		    foreach($query as $row) {
		        $responce->rows[$i]['id']=$row->BP_IDS;
		        $responce->rows[$i]['cell']=array($row->BP_IDS,$row->BP_NMS,$row->BP_STDS,$row->BP_MTRS,$row->BCD_AMT);
		        $i++;
		    }
	   	 echo json_encode($responce);
	   	 //echo json_encode($count);
			
			//grid_end
		}else{
			echo json_encode(null);
		}
	}
	
	
	//카테고리 리스트 가져오기
	function loadCate(){
		$detail = $this->input->get('detail');
		if($detail != ''){
			/*그리드*/
	    $page = isset($_POST['page'])?$_POST['page']:1; 
	    $limit = isset($_POST['rows'])?$_POST['rows']:10; 
	    $sidx = isset($_POST['sidx'])?$_POST['sidx']:'INS_DT'; 
	    $sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
	    $start = $limit*$page - $limit; 
	    $start = ($start<0)?0:$start; 
	    $where = ""; 
	    if(!$sidx) 
	        $sidx =1;
			$query = $this->{$this->pageModel()}->loadCate('0','0',$sidx,$sord,$where,$detail); 
			$count = count($query);
			
	    if( $count > 0 ) {
	        $total_pages = ceil($count/$limit);    
	    } else {
	        $total_pages = 0;
	    }
			$responce = new stdClass();
	    if ($page > $total_pages) 
	      $page=$total_pages;
		  	$query = $this->{$this->pageModel()}->loadCate($start,$limit,$sidx,$sord,$where,$detail);   
		    $responce->page = $page;
		    $responce->total = $total_pages;
		    $responce->records = $count;
		    $i=0;
		    foreach($query as $row) {
		        $responce->rows[$i]['id']=$row->BC_ID;
		        $responce->rows[$i]['cell']=array($row->BC_ID,$row->BC_NMS,$row->INS_DT);
		        $i++;
		    }
	   	 echo json_encode($responce);
	   	 //echo json_encode($count);
			
			//grid_end
		}else{
			echo json_encode(null);
		}
	}
	
	//카테고리 내 상세 정보 가져오기
	function loadInDtl_cate(){
		
		$detail = $this->input->get('detail');
		if($detail != ''){
			/*그리드*/
	    $page = isset($_POST['page'])?$_POST['page']:1; 
	    $limit = isset($_POST['rows'])?$_POST['rows']:10; 
	    $sidx = isset($_POST['sidx'])?$_POST['sidx']:'INS_DT'; 
	    $sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
	    $start = $limit*$page - $limit; 
	    $start = ($start<0)?0:$start; 
	    $where = ""; 
	    if(!$sidx) 
	        $sidx =1;
			$query = $this->{$this->pageModel()}->loadInDtl_cate('0','0',$sidx,$sord,$where,$detail); 
			$count = count($query);
			
	    if( $count > 0 ) {
	        $total_pages = ceil($count/$limit);    
	    } else {
	        $total_pages = 0;
	    }
			$responce = new stdClass();
	    if ($page > $total_pages) 
	      $page=$total_pages;
		  	$query = $this->{$this->pageModel()}->loadInDtl_cate($start,$limit,$sidx,$sord,$where,$detail);   
		    $responce->page = $page;
		    $responce->total = $total_pages;
		    $responce->records = $count;
		    $i=0;
		    foreach($query as $row) {
		        $responce->rows[$i]['id']=$row->BP_ID;
		        $responce->rows[$i]['cell']=array($row->BP_ID,$row->BP_NM,$row->BP_STD,$row->BP_MTR,$row->BCD_AMT,$row->INS_DT);
		        $i++;
		    }
	   	 echo json_encode($responce);
	   	 //echo json_encode($count);
			
			//grid_end
		}else{
			echo json_encode(null);
		}
		
	}
	
	//제품 안 카테고리 내 상세 정보 가져오기
	function loadInDtl(){
		
		$detail = $this->input->get('detail');
		$pdt_id = $this->input->get('pdt_id');
		if($detail != ''){
			/*그리드*/
	    $page = isset($_POST['page'])?$_POST['page']:1; 
	    $limit = isset($_POST['rows'])?$_POST['rows']:10; 
	    $sidx = isset($_POST['sidx'])?$_POST['sidx']:'INS_DT'; 
	    $sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
	    $start = $limit*$page - $limit; 
	    $start = ($start<0)?0:$start; 
	    $where = ""; 
	    if(!$sidx) 
	        $sidx =1;
			$query = $this->{$this->pageModel()}->loadInDtl('0','0',$sidx,$sord,$where,$detail,$pdt_id); 
			$count = count($query);
			
	    if( $count > 0 ) {
	        $total_pages = ceil($count/$limit);    
	    } else {
	        $total_pages = 0;
	    }
			$responce = new stdClass();
	    if ($page > $total_pages) 
	      $page=$total_pages;
		  	$query = $this->{$this->pageModel()}->loadInDtl($start,$limit,$sidx,$sord,$where,$detail,$pdt_id);   
		    $responce->page = $page;
		    $responce->total = $total_pages;
		    $responce->records = $count;
		    $i=0;
		    foreach($query as $row) {
		        $responce->rows[$i]['id']=$row->BP_ID;
		        $responce->rows[$i]['cell']=array($row->BP_ID,$row->BP_NM,$row->BP_STD,$row->BP_MTR,$row->BCD_AMT,$row->INS_DT);
		        $i++;
		    }
	   	 echo json_encode($responce);
	   	 //echo json_encode($count);
			
			//grid_end
		}else{
			echo json_encode(null);
		}
		
	}
	
	
	
}
