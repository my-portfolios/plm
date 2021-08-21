<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_groupSearch_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 리스트 maping */
	function getMapingData($id){
		
		$this->db->select("PG_ID,(select PG_NM from PLM_GROUP where PG_ID = a.PG_ID) AS PG_NM, (select PG_TEL from PLM_GROUP where PG_ID = a.PG_ID) AS PG_TEL");
		$this->db->where('PARENT_ID',$id);
	  	$query = $this->db->get('PLM_GROUP_LIST a');
        // $this->db->from('PLM_GROUP_LIST');
        // return $this->db->get_compiled_select();
		return $query->result();
		
	}
	
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where){
        
		$this->db->select("PG_ID, PG_NM, PG_TEL");
		$this->db->limit($limit);
		if($where != NULL) $this->db->where($where,NULL,FALSE);
		$this->db->order_by($sidx,$sord);
        $query = $this->db->get('PLM_GROUP',$limit,$start);
        // $this->db->from('PLM_GROUP');
        // return $this->db->get_compiled_select();
        return $query->result();
		
	}
	
	/* 그룹 가져오기 */
	public function getGroupList($PP_ID){

        $sql = " select 
                PG_ID,(select PG_NM from PLM_GROUP where PG_ID = a.PG_ID) AS PG_NM, (select PG_TEL from PLM_GROUP where PG_ID = a.PG_ID) AS PG_TEL
                from PLM_GROUP_LIST a
				where 1=1
				and PARENT_ID = '".$PP_ID."' 
				";

		$query = $this->db->query($sql);

		return $query->result();

	}
    
    /* 그룹 등록 */
  public function insert_group($plm_type, $parent_id , $group_id){
	  
	$sql = " insert into PLM_GROUP_LIST 
			(
				 PLM_TYPE
				,PARENT_ID
				,PG_ID
				,INS_ID
				,INS_DT	
				,INS_IP
			)values(
				'".$plm_type."'
				,'".$parent_id."'
				,'".$group_id."'
				,'".$_SESSION['userid']."'
				,sysdate()
				,'".$_SERVER['REMOTE_ADDR']."'
			)
			";

	$query = $this->db->query($sql);

	return $query;
	
  }
	
	/* 그룹 영구삭제 */
	public function remove_group($plm_type, $parent_id){
		
		$sql = " delete from PLM_GROUP_LIST where PLM_TYPE = '".$plm_type."' and PARENT_ID = '".$parent_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
  
}
?>