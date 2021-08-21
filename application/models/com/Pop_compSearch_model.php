<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_compSearch_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 리스트 maping */
	function getMapingData($id,$type){
		
		$this->db->select('a.PC_ID , (select b.PC_NM from PLM_COMP b where b.PC_ID = a.PC_ID) as PC_NM , (select b.PC_EMP_NM from PLM_COMP b where b.PC_ID = a.PC_ID) as PC_EMP_NM'); 
    	$this->db->where('a.PLM_TYPE',$type);
		$this->db->where('a.PARENT_ID',$id);
	  	$query = $this->db->get('PLM_COMP_LIST a');
		
		return $query->result();
		
	}
	
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where){

		$this->db->select('*');
		$this->db->limit($limit);
		if($where != NULL)
		
		$this->db->where($where,NULL,FALSE);
		$this->db->where('ifnull(PC_DEL_YN,"N")','N');
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get('PLM_COMP',$limit,$start);

		return $query->result();
		
	}
	
	/* 거래처 가져오기 */
	public function getCompList($plm_type, $parent_id){

		$sql = " select  A.PLM_TYPE
						,A.PARENT_ID
						,A.PC_ID
						, ( select B.PC_NM from PLM_COMP B where B.PC_ID = A.PC_ID ) as PC_NM
				from PLM_COMP_LIST  A
				where 1=1
				and A.PLM_TYPE = '".$plm_type."'
				and A.PARENT_ID = '".$parent_id."' 
				";

		$query = $this->db->query($sql);

		return $query->result();

	}
	
	
	/* 저장 */
	function insert_comp($plm_type,$parent_id,$pc_id){
		$sql = " insert into PLM_COMP_LIST 
				(
					 PLM_TYPE
					,PARENT_ID
					,PC_ID
					,INS_ID
					,INS_DT	
					,UPD_ID
					,UPD_DT
				)values(
					 '".$plm_type."'
					,'".$parent_id."'
					,'".$pc_id."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
				";

		$query = $this->db->query($sql);

		return $query;
	}
	
	/* 삭제 */
	function del_comp($plm_type,$parent_id){
		$sql = "delete from PLM_COMP_LIST
				where PLM_TYPE = '".$plm_type."'
				and PARENT_ID = '".$parent_id."'
				";
		$query = $this->db->query($sql);
		return $query;
	}
  
}
?>