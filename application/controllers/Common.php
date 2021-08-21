<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();        
		$this->load->model('Common_model');
		
    } 
	
	
	function index()
    {   
		$this->load->helper('url');
    }
	
	/* 하이라키구조 전체 tree json */
	function getTreeJson(){
		$data = $this->input->post();
		if(!$data){
			$data = $this->input->get();
		}
		$query = $this->Common_model->getTree($data);
		
    echo json_encode($query);
	}
	
	/* 새글 카운트 */
	function getNewCnt(){
		$data = $this->input->post();
		$query = $this->Common_model->new_data($data['table'],$data['delyn']);
    echo json_encode($query);
	}
	
	/* 내사진 가져오기 */
	function getPic(){
		$data = $this->input->post();
		$query = $this->Common_model->my_pic($data);
    echo json_encode($query);
	}
	
	/* 메세지 보내기 */
	function msgPush(){
		$data = $this->input->post();
		$query = $this->Common_model->msgPush($data);
    echo json_encode($query);
	}
	
	/* 삭제된 유저인지 아닌지 */
	function userYn(){
		$data = $this->input->post();
		$query = $this->Common_model->userYn($data);
    echo json_encode($query);
	}
	
	/* 확인여부 */
	function msgViewYns(){
		$query = $this->Common_model->msgViewYns();
    echo json_encode($query);
	}
	/* id로 이름가져오기 */
	function getUserIdToNm(){
		$data = $this->input->post();
		$query = $this->Common_model->getUserIdToNm($data);
    echo json_encode($query);
	}
	/* 유저정보 가져오기 */
	function infoView(){
		$data = $this->input->post();
		$query = $this->Common_model->infoView($data);
    echo json_encode($query);
	}
	
	/*스킨변경*/
	function skinChange(){
		$data = $this->input->post();
		$query = $this->Common_model->skinChange($data);
    echo json_encode(true);
	}
	
	/*msg 리스트*/
	function msgList(){
		
		$userData = $this->input->post();
		
		if(isset($_POST['REMOVE_ARR']) && $_POST['REMOVE_ARR'] != ''){//삭제있으면
			$ARR = $this->input->post('REMOVE_ARR');
			foreach ($ARR as $i){
				$query = $this->Common_model->msg_del($i);
			}
		}
		
		if(isset($_POST['DEL_ALL']) && $_POST['DEL_ALL'] != ''){//비우기있으면
			$query = $this->Common_model->msg_all_del();
		}
		
		if(isset($_POST['viewYn']) && $_POST['viewYn'] != '' && $_POST['viewYn'] == 'Y'){//확인했으면
			$data = $this->input->post();
			$query = $this->Common_model->msg_view($data);
		}
		
		if(isset($_POST['CON_ALL']) && $_POST['CON_ALL'] != '' && $_POST['CON_ALL'] == 'Y'){//전체확인이면
			$query = $this->Common_model->msg_all_con();
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
	
			if($searchField == 'S_ID'){
				$where = " S_ID IN (select PE_ID from PLM_EMP where PE_NM like '%".urldecode($searchString)."%') ";
			}else{
				$where = "$searchField $ops '$searchString' "; 
			}
    }
	
    if(!$sidx) 
        $sidx =1;
    
		$query = $this->Common_model->getAllData('0','0',$sidx,$sord,$where,$userData); 
		$count = count($query);
		
    if( $count > 0 ) {
        $total_pages = ceil($count/$limit);    
    } else {
        $total_pages = 0;
    }
		$responce = new stdClass();
    if ($page > $total_pages) 
      $page=$total_pages;
	    $query = $this->Common_model->getAllData($start,$limit,$sidx,$sord,$where,$userData); 
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
	    $i=0;
	    foreach($query as $row) {
	        $responce->rows[$i]['id']=$row->id;
	        $responce->rows[$i]['cell']=array($row->id,$row->R_ID,$row->S_ID_s,$row->MSG,$row->INS_DT,$row->ETC2,$row->S_ID);
	        $i++;
	    }
   	 echo json_encode($responce);
	}
	
	
	/*msg 보낸 리스트*/
	function msgListSend(){
		
		$userData = $this->input->post();
		
		if(isset($_POST['REMOVE_ARR']) && $_POST['REMOVE_ARR'] != ''){//삭제있으면
			$ARR = $this->input->post('REMOVE_ARR');
			foreach ($ARR as $i){
				$query = $this->Common_model->msg_del_send($i);
			}
		}
		
		if(isset($_POST['DEL_ALL']) && $_POST['DEL_ALL'] != ''){//비우기있으면
			$query = $this->Common_model->msg_all_del_send();
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
	
			if($searchField == 'R_ID'){
				$where = " R_ID IN (select PE_ID from PLM_EMP where PE_NM like '%".urldecode($searchString)."%') ";
			}else{
				$where = "$searchField $ops '$searchString' "; 
			}
    }
	
    if(!$sidx) 
        $sidx =1;
    
		$query = $this->Common_model->getAllDataSend('0','0',$sidx,$sord,$where,$userData); 
		$count = count($query);
		
    if( $count > 0 ) {
        $total_pages = ceil($count/$limit);    
    } else {
        $total_pages = 0;
    }
		$responce = new stdClass();
    if ($page > $total_pages) 
      $page=$total_pages;
	    $query = $this->Common_model->getAllDataSend($start,$limit,$sidx,$sord,$where,$userData); 
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
	    $i=0;
	    foreach($query as $row) {
	        $responce->rows[$i]['id']=$row->id;
	        $responce->rows[$i]['cell']=array($row->id,$row->S_ID,$row->R_ID_s,$row->MSG,$row->INS_DT,$row->ETC2,$row->R_ID);
	        $i++;
	    }
   	 echo json_encode($responce);
	}

	
    
}
