<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Gantt_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* id chk */
	function chkId($searchData){
		
		$sql = " select count(*) cnt 
				from PLM_PMS 
				where ifnull(PP_DEL_YN,'N') != 'Y'
				and PP_ID = '".$searchData['id']."'
			";
			
		$query = $this->db->query($sql);
		
		return $query->row();
	}
	
	/* 데이터 가져오기 */
	function getData($searchData){
		
		$sql = " select * 
				from PLM_PMS_WBS
				where PP_ID = '".$searchData['PP_ID']."'
				";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function del_projectSc($pp_id){
		
		$sql = " delete from PLM_PMS_WBS
				 where 1=1
				 and PP_ID = '".$pp_id."'
				";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 저장 */
	function save($data,$PP_ID){
		$sql = " insert into PLM_PMS_WBS (
					 PP_ID
					,CAN_ADD
					,CAN_ADD_ISSUE
					,CAN_DELETE
					,CAN_WRITE
					,CODE
					,COLLAPSED
					,DEPENDS
					,DESCRIPTION
					,DURATION
					,END
					,END_IS_MILESTONE
					,HAS_CHILD
					,ID
					,LEVEL
					,NAME
					,PROGRESS
					,PROGRESS_BY_WORKLOG
					,RELEVANCE
					,START
					,START_IS_MILESTONE
					,STATUS
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				) values(
					'".$PP_ID."' ";
				if(isset($data['canAdd']))				{ $sql .= " ,'".$data['canAdd']."' "; }				else{ $sql .= " ,null "; }
				if(isset($data['canAddIssue']))			{ $sql .= " ,'".$data['canAddIssue']."' "; }		else{ $sql .= " ,null "; }
				if(isset($data['canDelete']))			{ $sql .= " ,'".$data['canDelete']."' "; }			else{ $sql .= " ,null "; }
				if(isset($data['canWrite']))			{ $sql .= " ,'".$data['canWrite']."' "; }			else{ $sql .= " ,null "; }
				if(isset($data['code']))				{ $sql .= " ,'".$data['code']."' "; }				else{ $sql .= " ,null "; }
				if(isset($data['collapsed']))			{ $sql .= " ,'".$data['collapsed']."' "; }			else{ $sql .= " ,null "; }
				if(isset($data['depends']))				{ $sql .= " ,'".$data['depends']."' "; }			else{ $sql .= " ,null "; }
				if(isset($data['description']))			{ $sql .= " ,'".$data['description']."' "; }		else{ $sql .= " ,null "; }
				if(isset($data['duration']))			{ $sql .= " ,'".$data['duration']."' "; }			else{ $sql .= " ,null "; }
				if(isset($data['end']))					{ $sql .= " ,'".$data['end']."' "; }				else{ $sql .= " ,null "; }
				if(isset($data['endIsMilestone']))		{ $sql .= " ,'".$data['endIsMilestone']."' "; }		else{ $sql .= " ,null "; }
				if(isset($data['hasChild']))			{ $sql .= " ,'".$data['hasChild']."' "; }			else{ $sql .= " ,null "; }
				if(isset($data['id']))					{ $sql .= " ,'".$data['id']."' "; }					else{ $sql .= " ,null "; }
				if(isset($data['level']))				{ $sql .= " ,'".$data['level']."' "; }				else{ $sql .= " ,null "; }
				if(isset($data['name']))				{ $sql .= " ,'".$data['name']."' "; }				else{ $sql .= " ,null "; }
				if(isset($data['progress']))			{ $sql .= " ,'".$data['progress']."' "; }			else{ $sql .= " ,null "; }
				if(isset($data['progressByWorklog']))	{ $sql .= " ,'".$data['progressByWorklog']."' "; }	else{ $sql .= " ,null "; }
				if(isset($data['relevance']))			{ $sql .= " ,'".$data['relevance']."' "; }			else{ $sql .= " ,null "; }
				if(isset($data['start']))				{ $sql .= " ,'".$data['start']."' "; }				else{ $sql .= " ,null "; }
				if(isset($data['startIsMilestone']))	{ $sql .= " ,'".$data['startIsMilestone']."' "; }	else{ $sql .= " ,null "; }
				if(isset($data['status']))				{ $sql .= " ,'".$data['status']."' "; }				else{ $sql .= " ,null "; }
			$sql .= ",'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
				) ";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
}
?>