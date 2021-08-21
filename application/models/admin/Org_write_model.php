<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Org_write_model extends CI_Model{
	
	function pageReturn(){ return 'Org'; } //def,PLM_TYPE
	function defListTable(){ return 'PLM_ORG'; }
	function defListId(){ return 'ORG_ID'; }
	function defSeqId(){ return 'ORG_'; }
	
  function __construct(){
     parent::__construct();
	}
	
	/* id 새로 따기 */
	public function get_new_id(){
		$sql_1 = " select concat( '".$this->defSeqId()."' ,ifnull( max( cast(substr(".$this->defListId()." , 5) as unsigned) ), 0 )+1 ) as new_id from ".$this->defListTable()." ";
		$query_1 = $this->db->query($sql_1);
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_id = $row->new_id;
		}
		return $new_id;
	}
	
	/* 데이터 가져오기 ( 추가추정 ) */
	public function getData($id){
		$sql = "select 
					ORG_ID
					,ORG_NM
					,ORG_YN
					,ORG_DATA
					,DATE_FORMAT(INS_DT,'%Y-%m-%d') AS INS_DT
				from ".$this->defListTable()." 
				where ".$this->defListId()." = '".$id."'";
		
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	/* 파일 가져오기 */
	public function getFileList($id){
		
		$sql = " select A.FILELIST_ID
						,A.PARENT_ID AS PR_ID
						,( SELECT PF_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_NM
						,( SELECT PF_PATH FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_PATH
						,( SELECT PF_FILE_REAL_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_REAL_NM
						,( SELECT PF_FILE_TEMP_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_TEMP_NM
						,( SELECT PF_FILE_SIZE FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_SIZE
						,( SELECT PF_FILE_EXT FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_EXT
				from PLM_FILE_LIST A 
				where A.PLM_TYPE = '".strtolower($this->pageReturn())."'
				AND A.PARENT_ID = '".$id."'
				AND A.PLM_DETAIL_TYPE = 'normal'";

		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	public function get_pf_ids_pr_id($pr_id){
		
		$sql = " SELECT PF_ID FROM PLM_FILE_LIST WHERE PARENT_ID = '".$pr_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 등록 ( 추가추정 ) */
	public function insert($new_id,$searchData){
		
		// ' escape
		$searchData['ORG_NM'] = addslashes($searchData['ORG_NM']);
		
		if($searchData['ORG_YN'] == 'Y'){
			$sql_u = " update ".$this->defListTable()."
					 set ORG_YN			= 'N'
					";				
			$query_u = $this->db->query($sql_u);
		}
		
		$sql = " insert into ".$this->defListTable()."
				(    ORG_ID 
					,ORG_NM
					,ORG_YN
					,ORG_DATA
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$new_id."'
					,'".$searchData['ORG_NM']."'
					,'".$searchData['ORG_YN']."'
					,'".$searchData['ORG_DATA']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
			";
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	
	/* 수정 ( 추가추정 ) */
	public function update($searchData){
		if($searchData['ORG_YN'] == 'Y'){
			$sql_u = " update ".$this->defListTable()."
					 set ORG_YN			= 'N'
					";		
			$query_u = $this->db->query($sql_u);
		}
		$sql = " update PLM_ORG 
				 set ORG_NM			= '".$searchData['ORG_NM']."'
				 	,ORG_YN = '".$searchData['ORG_YN']."'
					,ORG_DATA = '".$searchData['ORG_DATA']."'
					,UPD_ID				= '".$_SESSION['userid']."'
					,UPD_DT				= sysdate()
				 where ORG_ID 			= '".$searchData['ORG_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	//pdm 내용 수정
	public function update_pdm($searchData){
		
		$sql = " update PLM_PDM_FILE
				 set PF_NM		= '".$searchData['ORG_NM']."'
					,PF_CONT	= '".$searchData['ORG_DATA']."'
					,UPD_ID		= '".$_SESSION['userid']."'
					,UPD_DT		= sysdate()
					,UPD_IP 	= '".$_SERVER['REMOTE_ADDR']."'
				 where PF_ID IN ( SELECT B.PF_ID FROM PLM_FILE_LIST B WHERE B.PARENT_ID = '".$searchData[$this->defListId()]."' AND B.PLM_DETAIL_TYPE = 'normal' )
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