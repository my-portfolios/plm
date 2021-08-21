<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Mapping_model extends CI_Model{
	
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageType(){ return 'Mapping'; }
	function defListTable(){ return 'PLM_PMS'; }
	function defListId(){ return 'PP_ID'; }
	function defListQuery(){ 	
		return 'PP_ID,PP_NM,INS_ID,INS_DT';
	}
	
  function __construct(){
      parent::__construct();
	}
	
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where){
		
		$this->db->select($this->defListQuery());
		$this->db->limit($limit);
		if($where != NULL)
		$this->db->where($where,NULL,FALSE);
		$this->db->where("ifnull(PP_DEL_YN,'N')","N");
		
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->defListTable().' a',$limit,$start);
		
		return $query->result();
		
	}
	
	/*매핑정보 삭제*/
	function del_mapping($ARR){
		$this->db->delete('PLM_BOM_PMS', array('PP_ID' => $ARR)); 
	}
  
}
?>