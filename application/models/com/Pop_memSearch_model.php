<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_memSearch_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 리스트 maping */
	function getMapingData($id,$type){
		
		$this->db->select('*');
		$this->db->where('PG_ID',$id);
	  	$query = $this->db->get('PLM_EMP');
		
		return $query->result();
		
	}
	
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where){

		$this->db->select('*');
		$this->db->limit($limit);
		if($where != NULL) $this->db->where($where,NULL,FALSE);
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get('PLM_EMP',$limit,$start);
		return $query->result();
		
	}
	
	/* 그룹 가져오기 */
	public function getMemList($PG_ID){

        $sql = " select *
                from PLM_EMP
				where 1=1
				and PG_ID = '".$PG_ID."' 
				";

		$query = $this->db->query($sql);

		return $query->result();

	}
	
	
	/* 저장 */
	function insert_mem($PE_ID, $PG_ID){
		$sql = " update PLM_EMP 
				 set PG_ID		= '".$PG_ID."'
				 where PE_ID 	= '".$PE_ID."'
				";

		$query = $this->db->query($sql);

		return $query;
	}
	
	/* 삭제 */
	function del_mem($PG_ID){
		$sql = " update PLM_EMP 
				 set PG_ID		= ''
				 where PG_ID 	= '".$PG_ID."'
				";
		$query = $this->db->query($sql);
		return $query;
	}
  
}
?>