<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Group_model extends CI_Model{
	
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageType(){ return 'Group'; }
	function defListTable(){ return 'PLM_GROUP'; }
	function defListId(){ return 'PG_ID'; }
	function defListQuery(){ 	
		return "(select count(*) from PLM_FA where PLM_TYPE = '".$this->pageType()."' and FA_ID = a.PG_ID and FA_USER = '".$this->session->userdata('userid')."' ) as FA_CNT,
			PG_ID,PG_NM,PG_TEL,INS_ID,INS_DT";
	}
	
	function __construct(){
      parent::__construct();
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
		if($where != NULL) $this->db->where($where,NULL,FALSE);
		$this->db->where("ifnull(PG_DEL_YN,'N') = 'N' OR PG_DEL_YN=''");
		if($FA_SORT_STAR == 'true'){
			$this->db->where($this->defListId()." IN ($sub_query)");
		}
		//$this->db->from('PLM_GROUP');
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->defListTable().' a',$limit,$start);
			
		return $query->result();
	}

	/* 리스트 불러오기 */
	function getGroupList(){
		
		$this->db->select('PG_ID,PG_NM');
		$query = $this->db->get($this->defListTable());
			
		return $query->result();
	}

	/* 그룹삭제 */
	function p_del($ARR){
		
		//리스트 삭제
		$this->db->set('PG_DEL_YN','Y');
		$this->db->where(array($this->defListId() => $ARR));
		$this->db->update($this->defListTable());
		
		//$this->db->delete($this->defListTable(), array($this->defListId() => $ARR)); 
		 
		// 즐찾삭제
		$this->db->delete('PLM_FA', array('FA_ID' => $ARR)); 
		
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
	
}
?>