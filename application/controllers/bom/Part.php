<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Part extends CI_Controller {

	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageSeg(){ return 'bom'; } //seg 1
	function pageType(){ return 'Part'; }
	function pageModel(){ return 'Part_model'; }
	function pageWriteModel(){ return 'Part_write_model'; } // model
	
	function __construct()
	{
		parent::__construct();
    
		$this->load->helper('url');
		$this->load->model('/'.$this->pageSeg().'/'.$this->pageModel(),$this->pageModel());
		$this->load->model('/Common_model');
        $this->load->library('Excel');	
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
				//파일삭제 시작(파일부터 삭제해야됨)
				//기본 파일삭제
				$pf_ids = $this->{$this->pageModel()}->get_pf_ids($i);
				$query = $this->{$this->pageModel()}->p_del($i);
				$this->remove_file($pf_ids);
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
    
		$query = $this->{$this->pageModel()}->getAllData('0','0',$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE); 
		$count = count($query);
		
    if( $count > 0 ) {
        $total_pages = ceil($count/$limit);    
    } else {
        $total_pages = 0;
    }
		$responce = new stdClass();
    if ($page > $total_pages) 
      $page=$total_pages;
	    $query = $this->{$this->pageModel()}->getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE); 
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
	    $i=0;
	    foreach($query as $row) {
			$responce->rows[$i]['id']=$row->BP_ID;
	        $responce->rows[$i]['cell']=array($row->BP_ID,$row->BP_NM,$row->BP_STD,$row->BP_MTR,'BP_ASC',$row->BP_CONT,$row->INS_ID,$row->INS_DT,$row->FA_CNT);
	        $i++;
	    }
   	 echo json_encode($responce);
	}

	function loadFileList(){

		$id = $this->input->get('id');
		$responce = new stdClass();

		$i = 0;
		$fileList=$this->{$this->pageModel()}->getFileList($id);
		if( $fileList != null ){
			foreach( $fileList as $data ){
				$responce->rows[$i]=array($data->FILELIST_ID,$data->PF_FILE_EXT,$data->PF_FILE_TEMP_NM,$data->PF_FILE_REAL_NM,$data->FILELIST_ID);
				$i++;
			}
		}
		
		echo json_encode($responce);
	}

	function loadCompList(){

		$id = $this->input->get('id');
		$responce = new stdClass();

		$i = 0;
		$compList=$this->{$this->pageModel()}->getCompList($id);
		if( $compList != null ){
			foreach( $compList as $data ){
				$responce->rows[$i]=array($data->BP_ID,$data->PC_ID,$data->PC_NM);
				$i++;
			}
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

	function fetch()
    {
        $data = $this->{$this->pageModel()}->select();
        $output = '
            <h3 align="center">Total Data - '.$data->num_rows().'</h3>
            <table class="table table-striped table-bordered">
            <tr>
            <th>부품이름</th>
            <th>규격</th>
            <th>재질</th>
            <th>상세내용</th>
            </tr>
        ';
        foreach($data->result() as $row)
        {
            $output .= '
                <tr>
                <td>'.$row->BP_NM.'</td>
                <td>'.$row->BP_STD.'</td>
                <td>'.$row->BP_MTR.'</td>
                <td>'.$row->BP_CONT.'</td>
                </tr>
            ';
        }
        $output .= '</table>';
        echo $output;
    }

    function import()
    {

        if(isset($_FILES["file"]["name"]))
        {
			$userid = $this->input->get('userid');
        	$date = date('Y-m-d H:i:s');
			$new_id	= $this->{$this->pageModel()}->get_new_id();	// 새 id 따기
			$new_id_arr = explode("_",$new_id);
			$new_id_pre = $new_id_arr[0];
			$new_id_num = $new_id_arr[1];
			

            $path = $_FILES["file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
				$highestColumn = $worksheet->getHighestColumn();
				
				//양식에 맞는지 확인s - 첫줄에 칼럼이름이 입력되어 있어야 함.
				$BP_NM = $object->getActiveSheet()->getCellByColumnAndRow(0, 1)->getValue();
				$BP_STD = $object->getActiveSheet()->getCellByColumnAndRow(1, 1)->getValue();
				$BP_MTR = $object->getActiveSheet()->getCellByColumnAndRow(2, 1)->getValue();
				$BP_CONT = $object->getActiveSheet()->getCellByColumnAndRow(3, 1)->getValue();

				if(!(strpos($BP_NM, "BP_NM") !== false && strpos($BP_STD, "BP_STD") !== false && strpos($BP_MTR, "BP_MTR") !== false && strpos($BP_CONT, "BP_CONT") !== false)) {
					echo ("0");
					exit;
				}
				//양식에 맞는지 확인e

                for($row=2; $row<=$highestRow; $row++)
                {
                    
                    $BP_NM = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $BP_STD = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $BP_MTR = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $BP_CONT = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $data[] = array(
                    'BP_ID'  => $new_id_pre."_".($new_id_num++),
                    'BP_NM'  => $BP_NM,
                    'BP_STD'   => $BP_STD,
                    'BP_MTR'    => $BP_MTR,
                    'BP_CONT'   => $BP_CONT,
                    'INS_ID'   => $userid,
                    'INS_DT'   => $date
                    );
                }
            }
            $this->{$this->pageModel()}->excel_insert($data);
            echo 'Data Imported successfully';
        } 
    }
	
}