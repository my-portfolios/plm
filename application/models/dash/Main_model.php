<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	public function chart_c(){
		if($_SESSION['userauth'] == 'user'){
			$compArr  = $_SESSION['comp'];
			$complike = "";
			if($compArr != ''){
				$compArrR = explode(',',$compArr);
				$c = 0;
				foreach($compArrR as $i=>$v){
					if($c == 0){
				  	$complike .= " and comp like '%".$v."%' ";
					}else{
						$complike .= " or comp like '%".$v."%' ";
					}
				  $c++;
				}
			}else{
				 	$complike = " and comp like '' ";
			}
			$sql = "
			select comp,count(receipt) as receipt,count(measure) as measure,count(progress) as progress,count(companion) as companion from (
			SELECT 
			( select group_concat(a.PC_ID separator ', ') from PLM_COMP a where a.PC_ID in ( select b.PC_ID from PLM_COMP_LIST b where b.PLM_TYPE = 'pms' and b.PARENT_ID in ( select c.PP_ID from PLM_PMS_LIST c where c.PP_ID = b.PARENT_ID and c.PLM_TYPE = 'rm' and c.PARENT_ID = a.PR_ID ) ) ) as comp
					,IF(PR_STATUS = '1', 1, null) as receipt 
					,IF(PR_STATUS = '2', 1, null) as measure
					,IF(PR_STATUS = '3', 1, null) as progress		
					,IF(PR_STATUS = '4', 1, null) as companion
			FROM 
			PLM_RM a 
			WHERE 1=1 
			and ifnull(PR_DEL_YN,'N') = 'N' ) PLM_RM where 1=1 ".$complike."
			";
		}else if($_SESSION['userauth'] == 'emp'){
			$sql = "
			SELECT 
					COUNT(IF(PR_STATUS = '1', 1, null)) as receipt 
					,COUNT(IF(PR_STATUS = '2', 1, null)) as measure
					,COUNT(IF(PR_STATUS = '3', 1, null)) as progress		
					,COUNT(IF(PR_STATUS = '4', 1, null)) as companion	
			FROM 
			PLM_RM a 
			WHERE 1=1 
			and ( a.INS_ID = '".$_SESSION['userid']."' or '".$_SESSION['userid']."' in (select b.emp_id from PLM_EMP_LIST b where b.PLM_TYPE = 'rm' and b.PARENT_ID = a.PR_ID and b.EMP_ID = '".$_SESSION['userid']."' ) ) 
			and ifnull(PR_DEL_YN,'N') = 'N'	
			";
		}else{
			$sql = "
			SELECT 
					COUNT(IF(PR_STATUS = '1', 1, null)) as receipt 
					,COUNT(IF(PR_STATUS = '2', 1, null)) as measure
					,COUNT(IF(PR_STATUS = '3', 1, null)) as progress		
					,COUNT(IF(PR_STATUS = '4', 1, null)) as companion	
			FROM 
			PLM_RM where 1=1
			and ifnull(PR_DEL_YN,'N') = 'N'
			";
		}
		
		
		
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	public function chart_b(){
		if($_SESSION['userauth'] == 'user'){
			$compArr  = $_SESSION['comp'];
			$complike = "";
			if($compArr != ''){
				$compArrR = explode(',',$compArr);
				foreach($compArrR as $i=>$v){
				  $complike .= " and comp like '%".$v."%' ";
				}
			}else{
				 	$complike .= " and comp like '' ";
			}
			
			$sql = "
			select * from(
			 	select PP_ID,PROGRESS,(select PP_NM from PLM_PMS where PP_ID = a.PP_ID and ifnull(PP_DEL_YN,'N') != 'Y') as PP_NM,(select GROUP_CONCAT(pc_id) from PLM_COMP_LIST where parent_id = a.PP_ID) as comp
				 from 
					PLM_PMS_WBS a
			) PLM_PMS_WBS where PP_NM is not null ".$complike." limit 5
			";
		}else if($_SESSION['userauth'] == 'emp'){
			$sql ="
			select * from(
			 	select (select INS_ID from PLM_PMS where PP_ID = a.PP_ID) as INSID, PP_ID,PROGRESS,(select PP_NM from PLM_PMS where PP_ID = a.PP_ID and ifnull(PP_DEL_YN,'N') != 'Y') as PP_NM
				 from 
					PLM_PMS_WBS a
			) PLM_PMS_WBS where PP_NM is not null
			and INSID = '".$_SESSION['userid']."'
			or '".$_SESSION['userid']."' in(select b.emp_id from PLM_EMP_LIST b where b.PLM_TYPE = 'pms' and b.PARENT_ID = PP_ID and b.EMP_ID = '".$_SESSION['userid']."')
			limit 5
			";
		}else{
		$sql = "
			select * from(
			select PP_ID,PROGRESS,(select PP_NM from PLM_PMS where PP_ID = a.PP_ID and ifnull(PP_DEL_YN,'N') != 'Y') as PP_NM from PLM_PMS_WBS a) PLM_PMS_WBS where PP_NM is not null limit 5
		";
		}
		$query = $this->db->query($sql);
		return $query->result();
	}
	
}
?>