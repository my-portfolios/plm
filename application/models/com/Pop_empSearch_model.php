<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_empSearch_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 리스트 maping */
	function getMapingData($id,$type){
		
		$this->db->select('EMP_ID,( select PE_NM from PLM_EMP where PE_ID = EMP_ID ) EMP_NM,( select ETC2 from PLM_EMP where PE_ID = EMP_ID ) as ETC2,( select PE_TEL from PLM_EMP where PE_ID = EMP_ID ) as PE_TEL'); 
    	$this->db->where('PLM_TYPE',$type);
		$this->db->where('PARENT_ID',$id);
	  	$query = $this->db->get('PLM_EMP_LIST');
		
		return $query->result();
		
	}
  
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where){

		$this->db->select('PE_ID,PE_NM,ETC2,PE_TEL');
		$this->db->limit($limit);
		if($where != NULL)
		
		$this->db->where($where,NULL,FALSE);
		$this->db->where('ifnull(PE_DEL_YN,"N")','N');
		$this->db->where('PE_AUTH','emp');
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get('PLM_EMP',$limit,$start);

		return $query->result();
		
	}
  
  /* 담당자 등록 */
  public function insert_emp($plm_type, $parent_id , $emp_id , $emp_nm){
	  
	$sql = " insert into PLM_EMP_LIST 
			(
				 PLM_TYPE
				,PARENT_ID
				,EMP_ID
				,EMP_NM
				,INS_ID
				,INS_DT	
				,INS_IP
			)values(
				'".$plm_type."'
				,'".$parent_id."'
				,'".$emp_id."'
				,'".$emp_nm."'
				,'".$_SESSION['userid']."'
				,sysdate()
				,'".$_SERVER['REMOTE_ADDR']."'
			)
			";

	$query = $this->db->query($sql);

	return $query;
	
  }
  
	/* 담당자 가져오기 */
	public function getEmpList($plm_type, $parent_id){

		$sql = " select  PLM_TYPE
						,PARENT_ID
						,EMP_ID
						, ( select PE_NM from PLM_EMP where PE_ID = EMP_ID ) as EMP_NM
						, ( select ifnull(PE_DEL_YN,'N') from PLM_EMP where PE_ID = EMP_ID ) as DEL_YN
				from PLM_EMP_LIST  
				where 1=1
				and PLM_TYPE = '".$plm_type."'
				and PARENT_ID = '".$parent_id."' 
				";

		$query = $this->db->query($sql);

		return $query->result();

	}
	
	/* 담당자 영구삭제 */
	public function remove_emp($plm_type, $parent_id){
		
		$sql = " delete from PLM_EMP_LIST where PLM_TYPE = '".$plm_type."' and PARENT_ID = '".$parent_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	
	/* 담당자 삭제 및 복원 */
	public function delyn_emp($plm_type, $parent_id, $del_yn){
		
		$sql = " update PLM_EMP_LIST set DEL_YN = ".$del_yn." where PLM_TYPE = '".$plm_type."' and PARENT_ID = '".$parent_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
  
}
?>