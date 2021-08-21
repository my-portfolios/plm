<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_pmsSearch_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 리스트 maping */
	function getMapingData($id,$type){
		
		$this->db->select('PP_ID,( SELECT PP_NM FROM PLM_PMS WHERE PP_ID = A.PP_ID ) AS PP_NM'); 
    	$this->db->where('PLM_TYPE',$type);
		$this->db->where('PARENT_ID',$id);
	  	$query = $this->db->get('PLM_PMS_LIST A');
		
		return $query->result();
		
	}
  
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where){

		if($_SESSION['userauth'] == 'user'){
			if($where != NULL){
				$where = "and ".$where;
			}else{
				$where = '';
			}
			$compArr  = $_SESSION['comp'];
			$compArrR = explode(',',$compArr);
			foreach($compArrR as $i=>$v){
			    $compArrR[$i]=addslashes($v);
			}
			
			$sql = "select PP_ID,PP_NM from ( 
								select PP_ID,PP_NM,(SELECT pc_id FROM PLM_COMP_LIST WHERE PLM_TYPE = 'pms' and parent_id = pp_id and pc_id in ('".implode("','",$compArrR)."') ) as cmpid,PP_DEL_YN from PLM_PMS
							) PLM_PMS where cmpid is not null and ifnull(PP_DEL_YN,'N') !='Y' ".$where." order by ".$sidx." ".$sord." limit ".$start.", ".$limit;
			
			$query = $this->db->query($sql);
		}else{
			$this->db->select('PP_ID,PP_NM');
			$this->db->limit($limit);
			if($where != NULL)
			$this->db->where($where,NULL,FALSE);
			$this->db->where('ifnull(PP_DEL_YN,"N")','N');
			$this->db->order_by($sidx,$sord);
			$query = $this->db->get('PLM_PMS',$limit,$start);
		}
		return $query->result();
		
	}
  
   /* 담당자 등록 */
	public function insert_pms($plm_type, $parent_id , $pp_id){
	  
		$sql = " insert into PLM_PMS_LIST
				(
					 PLM_TYPE
					,PARENT_ID
					,PP_ID
					,INS_ID
					,INS_DT	
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				)values(
					'".$plm_type."'
					,'".$parent_id."'
					,'".$pp_id."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
				)
				";

		$query = $this->db->query($sql);

		return $query;

	}
  
	/* 프로젝트 가져오기 */
	public function getPmsList($plm_type, $parent_id){

		$sql = " select   a.PLM_TYPE
						, a.PARENT_ID
						, a.PP_ID
						, ( select PP_NM from PLM_PMS where a.PP_ID = PP_ID ) as PP_NM
						, ( select ifnull(PP_DEL_YN,'N') from PLM_PMS where a.PP_ID = PP_ID ) as DEL_YN
				from PLM_PMS_LIST a 
				where 1=1 
				and PLM_TYPE = '".$plm_type."' 
				and PARENT_ID = '".$parent_id."' 
				";

		$query = $this->db->query($sql);

		return $query->result();

	}
	
	/* 담당자 영구삭제 */
	public function remove_pms($plm_type, $parent_id){
		
		$sql = " delete from PLM_PMS_LIST where PLM_TYPE = '".$plm_type."' and PARENT_ID = '".$parent_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 담당자 삭제 및 복원 */
	public function delyn_pms($plm_type, $parent_id, $del_yn){
		
		$sql = " update PLM_PMS_LIST set DEL_YN = ".$del_yn." where PLM_TYPE = '".$plm_type."' and PARENT_ID = '".$parent_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
  
}
?>