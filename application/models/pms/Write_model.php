<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Write_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	/* pp_id 새로 따기 */
	public function get_new_pp_id(){
		
		$sql_1 = " select concat( 'PP_' ,ifnull( max( cast(substr(pp_id , 4) as unsigned) ), 0 )+1 ) as new_pp_id from PLM_PMS ";
		$query_1 = $this->db->query($sql_1);
		
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_pp_id = $row->new_pp_id;
		}
		return $new_pp_id;
	}
	
	/* 데이터 가져오기 */
	public function getData($pp_id){
		
		$sql = "select 
					 PP_ID
					,PP_NM
					,DATE_FORMAT(PP_ST_DAT,'%Y-%m-%d') AS PP_ST_DAT
					,DATE_FORMAT(PP_ED_DAT,'%Y-%m-%d') AS PP_ED_DAT
					,PP_CONT
					,PP_STATUS
					,INS_ID
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = INS_ID ) AS INS_NM
				from PLM_PMS where PP_ID = '".$pp_id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}

	/* 파일 가져오기 */
	public function getFileList($pp_id){
		
		$sql = " select A.FILELIST_ID
						,A.PARENT_ID AS PP_ID
						,( SELECT PF_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_NM
						,( SELECT PF_PATH FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_PATH
						,( SELECT PF_FILE_REAL_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_REAL_NM
						,( SELECT PF_FILE_TEMP_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_TEMP_NM
						,( SELECT PF_FILE_SIZE FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_SIZE
						,( SELECT PF_FILE_EXT FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_EXT
				from PLM_FILE_LIST A 
				where A.PLM_TYPE = '".$this->uri->segment(1)."'
				AND A.PARENT_ID = '".$pp_id."'
				AND A.PLM_DETAIL_TYPE = 'normal'";

		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	public function get_pf_ids_pp_id($pp_id){
		
		$sql = " SELECT PF_ID FROM PLM_FILE_LIST WHERE PARENT_ID = '".$pp_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($pp_id){
		
		$sql = " select *
				from PLM_KEYWORD 
				where 1=1
				and PLM_TYPE = '".$this->uri->segment(1)."'
				and PARENT_ID = '".$pp_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 키워드 등록 */
	public function insert_keyword($pp_id,$keyword){
		
		$sql = " insert into PLM_KEYWORD 
				 (
					 PLM_TYPE
					,PARENT_ID
					,PK_NM
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				 )values(
					 '".$this->uri->segment(1)."'
					,'".$pp_id."'
					,'".$keyword."'
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
	
	/* 프로젝트 등록 */
	public function insert_pms($new_pp_id,$searchData){
		
		$sql = " insert into PLM_PMS 
				(    PP_ID
					,PP_NM
					,PP_ST_DAT
					,PP_ED_DAT
					,PP_CONT
					,PP_STATUS
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				)values(
					 '".$new_pp_id."'
					,'".$searchData['PP_NM']."'
					,''
					,''
					,'".$searchData['PP_CONT']."'
					,'1'
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
	
	/* 일정 저장 */
	function insert_projectSc($new_pp_id,$obj){
		$sql = " insert into PLM_PMS_WBS (
					 PP_ID
					,EMP_LIST
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
					'".$new_pp_id."' ";
					/*
		$sql .= "
					,'".$obj->canAdd."'
					,'".$obj->canAddIssue."'
					,'".$obj->canDelete."'
					,'".$obj->canWrite."'
					,'".$obj->code."'
					,'".$obj->collapsed."'
					,'".$obj->depends."'
					,'".$obj->description."'
					,'".$obj->duration."'
					,'".$obj->end."'
					,'".$obj->endIsMilestone."'
					,'".$obj->hasChild."'
					,'".$obj->id."'
					,'".$obj->level."'
					,'".$obj->name."'
					,'".$obj->progress."'
					,'".$obj->progressByWorklog."'
					,'".$obj->relevance."'
					,'".$obj->start."'
					,'".$obj->startIsMilestone."'
					,'".$obj->status."'
				";
					*/
				if(($obj->empList))					{ $sql .= " ,'".$obj->empList."' "; }			else{ $sql .= " ,null "; }
				if(($obj->canAdd))					{ $sql .= " ,'".$obj->canAdd."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->canAddIssue))		{ $sql .= " ,'".$obj->canAddIssue."' "; }		else{ $sql .= " ,null "; }
				if(isset($obj->canDelete))			{ $sql .= " ,'".$obj->canDelete."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->canWrite))			{ $sql .= " ,'".$obj->canWrite."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->code))				{ $sql .= " ,'".$obj->code."' "; }				else{ $sql .= " ,null "; }
				if(isset($obj->collapsed))			{ $sql .= " ,'".$obj->collapsed."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->depends))			{ $sql .= " ,'".$obj->depends."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->description))		{ $sql .= " ,'".$obj->description."' "; }		else{ $sql .= " ,null "; }
				if(isset($obj->duration))			{ $sql .= " ,'".$obj->duration."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->end))				{ $sql .= " ,'".$obj->end."' "; }				else{ $sql .= " ,null "; }
				if(isset($obj->endIsMilestone))		{ $sql .= " ,'".$obj->endIsMilestone."' "; }	else{ $sql .= " ,null "; }
				if(isset($obj->hasChild))			{ $sql .= " ,'".$obj->hasChild."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->id))					{ $sql .= " ,'".$obj->id."' "; }				else{ $sql .= " ,null "; }
				if(isset($obj->level))				{ $sql .= " ,'".$obj->level."' "; }				else{ $sql .= " ,null "; }
				if(isset($obj->name))				{ $sql .= " ,'".$obj->name."' "; }				else{ $sql .= " ,null "; }
				if(isset($obj->progress))			{ $sql .= " ,'".$obj->progress."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->progressByWorklog))	{ $sql .= " ,'".$obj->progressByWorklog."' "; }	else{ $sql .= " ,null "; }
				if(isset($obj->relevance))			{ $sql .= " ,'".$obj->relevance."' "; }			else{ $sql .= " ,null "; }
				if(isset($obj->start))				{ $sql .= " ,'".$obj->start."' "; }				else{ $sql .= " ,null "; }
				if(isset($obj->startIsMilestone))	{ $sql .= " ,'".$obj->startIsMilestone."' "; }	else{ $sql .= " ,null "; }
				if(isset($obj->status))				{ $sql .= " ,'".$obj->status."' "; }			else{ $sql .= " ,null "; }
				
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
	
	/* 프로젝트 수정 */
	public function update_pms($searchData){
		
		$sql = " update PLM_PMS 
				 set PP_NM		= '".$searchData['PP_NM']."'
					,PP_CONT	= '".$searchData['PP_CONT']."'
					,UPD_ID		= '".$_SESSION['userid']."'
					,UPD_DT		= sysdate()
					,UPD_IP 	= '".$_SERVER['REMOTE_ADDR']."'
				 where PP_ID = '".$searchData['PP_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 키워드 삭제 */
	public function del_keyword($pp_id){
		
		$sql = " delete from PLM_KEYWORD
				 where 1=1
				 and PLM_TYPE = '".$this->uri->segment(1)."'
				 and PARENT_ID = '".$pp_id."' 
				";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	public function del_projectSc($pp_id){
		
		$sql = " delete from PLM_PMS_WBS
				 where 1=1
				 and PP_ID = '".$pp_id."'
				";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}

	//pdm 내용 수정
	public function update_pdm($searchData){
		
		$sql = " update PLM_PDM_FILE
				 set PF_NM		= '".$searchData['PR_TITLE']."'
					,PF_CONT	= '".$searchData['PR_CONT']."'
					,UPD_ID		= '".$_SESSION['userid']."'
					,UPD_DT		= sysdate()
					,UPD_IP 	= '".$_SERVER['REMOTE_ADDR']."'
				 where PF_ID IN ( SELECT B.PF_ID FROM PLM_FILE_LIST B WHERE B.PARENT_ID = '".$searchData['PR_ID']."' AND B.PLM_DETAIL_TYPE = 'normal' )
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	public function get_pf_file_temp_nm($prf_id){
		
		$sql = "select a.PF_FILE_TEMP_NM 
				from PLM_PDM_FILE a
				where a.PF_ID = ( select b.PF_ID from PLM_FILE_LIST b where b.FILELIST_ID = '".$prf_id."' )
				";
		
		$query = $this->db->query($sql);
		
		return $query->row()->PF_FILE_TEMP_NM;
		
	}
	
}

?>