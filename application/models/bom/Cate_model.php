<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Cate_model extends CI_Model{
	
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageType(){ return 'Cate'; }
	function defListTable(){ return 'PLM_BOM_CATE'; }
	function defListId(){ return 'BC_ID'; }
	function defSeqId(){ return 'CATE_'; }
	function defListQuery(){ 	
		return '(select count(*) from PLM_FA where PLM_TYPE = "'.$this->pageType().'" and FA_ID = a.BC_ID and FA_USER = "'.$this->session->userdata('userid').'" ) as FA_CNT,BC_ID,BC_NM,(select PE_NM from PLM_EMP where PE_ID = a.INS_ID) as INS_ID,INS_DT';
	}
	
  function __construct(){
      parent::__construct();
	}
	
	/* id 새로 따기 */
	public function get_new_id(){
		$sql_1 = " select concat( '".$this->defSeqId()."' ,ifnull( max( cast(substr(".$this->defListId()." , 6) as unsigned) ), 0 )+1 ) as new_id from ".$this->defListTable()." ";
		$query_1 = $this->db->query($sql_1);
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_id = $row->new_id;
		}
		return $new_id;
	}
	
	/* 카테고리 복사 */
	public function copy_cate($new_id,$copy_id){
		$sql = " insert into PLM_BOM_CATE (BC_ID, BC_NM, BC_CONT, INS_ID, INS_DT, UPD_ID, UPD_DT) 
					 SELECT '".$new_id."',concat(BC_NM,'".iconv("CP949","UTF-8",'_복사본')."'), BC_CONT, '".$_SESSION['userid']."', sysdate(), '".$_SESSION['userid']."',  sysdate() 
					 FROM PLM_BOM_CATE 
					 WHERE BC_ID = '".$copy_id."'
				";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/* 카테고리 dtl 복사 */
	public function copy_cate_dtl($new_id,$copy_id){
		$sql = " insert into PLM_BOM_CATE_DTL (BC_ID, BP_ID, INS_ID, INS_DT, UPD_ID, UPD_DT) 
					 SELECT '".$new_id."',BP_ID, '".$_SESSION['userid']."', sysdate(), '".$_SESSION['userid']."',  sysdate()
					 FROM PLM_BOM_CATE_DTL
					 WHERE BC_ID = '".$copy_id."'
				";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/* 즐찾 */
	function faYn($FA_TYPE,$FA_VAL,$FA_USER){
		
		$this->db->where('PLM_TYPE', $FA_TYPE);
		$this->db->where('FA_ID', $FA_VAL);
		$this->db->where('FA_USER', $FA_USER);
		$this->db->from('PLM_FA');
		$cnt = $this->db->count_all_results();
		
		if($cnt > 0){
			$this->db->delete('PLM_FA', array('PLM_TYPE' => $FA_TYPE,'FA_ID' => $FA_VAL,'FA_USER' => $FA_USER)); 
		}else{
			$data = array(
		   'PLM_TYPE' => $FA_TYPE ,
		   'FA_ID' => $FA_VAL,
		   'FA_USER' => $FA_USER
			);
			$this->db->insert('PLM_FA', $data); 
		}
	}
	
	/* pf_id 가져오기 */
	function get_pf_ids($id){
		$sql = " select PF_ID 
				from PLM_FILE_LIST 
				where PLM_TYPE = '".strtolower($this->pageType())."'
				AND PARENT_ID = '".$id."' 
				AND PLM_DETAIL_TYPE = 'normal' ";
		$q = $this->db->query($sql);
		return $q->result();
	}
	
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE){
		
		/*sub 쿼리*/
  	$this->db->select('FA_ID');
  	$this->db->where("FA_USER",$FA_USER);
		$this->db->where("PLM_TYPE",$FA_TYPE);
  	$this->db->from('PLM_FA');
  	$sub_query = $this->db->get_compiled_select();

    $this->db->select($this->defListQuery());
    $this->db->limit($limit);
    if($where != NULL)
        $this->db->where($where,NULL,FALSE);
      	
        if($FA_SORT_STAR == 'true'){
        	$this->db->where($this->defListId()." IN ($sub_query)");
      	}
      	
	    $this->db->order_by($sidx,$sord);
	    $query = $this->db->get($this->defListTable().' a',$limit,$start);
		
    return $query->result();
  }
  
  /*삭제*/
	function p_del($ARR){
		//리스트 삭제
		$this->db->delete($this->defListTable(), array($this->defListId() => $ARR)); 
		//파일삭제
		$this->db->delete('PLM_FILE_LIST', array('PARENT_ID' => $ARR)); 
		/* 즐찾삭제*/
		$this->db->delete('PLM_FA', array('FA_ID' => $ARR)); 
		/*부품디테일 삭제*/
		$this->db->delete('PLM_BOM_CATE_DTL', array('BC_ID' => $ARR));
		
		/*제품카테고리 삭제*/
		$this->db->delete('PLM_BOM_PDT_CATE', array('BC_ID' => $ARR)); 
		
		/*카테고리 삭제시 프로그램매핑 정보도 삭제*/
		$this->db->delete('PLM_BOM_PMS', array('BPMS_GBN_ID' => $ARR)); 
		
	}
	
	/* 키워드 영구삭제 */
	public function remove_pdm_keyword($pf_id){
		$sql = " delete from PLM_PDM_KEYWORD 
				where PF_ID = '".$pf_id."'
				";
		$query = $this->db->query($sql);
		return $query;
	}
	/* pdm 삭제 */
	public function pdm_remove($pf_id){
		$sql = " delete from PLM_PDM_FILE 
				 where PF_ID = '".$pf_id."'
				";
		$query = $this->db->query($sql);
		/* 즐찾은 그냥삭제*/
		$this->db->delete('PLM_FA', array('PLM_TYPE' => 'pdm2','FA_ID' => $pf_id)); 
		return $query;
	}
	
}
?>