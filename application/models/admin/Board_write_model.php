<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Board_write_model extends CI_Model{
	
	function pageReturn(){ return 'Board'; } //def,PLM_TYPE
	function defListTable(){ return 'PLM_BOARD'; }
	function defListId(){ return 'BOARD_ID'; }
	function defSeqId(){ return 'BD_'; }
	
  function __construct(){
     parent::__construct();
	}
	
	/* id 새로 따기 */
	public function get_new_id(){
		$sql_1 = " select concat( '".$this->defSeqId()."' ,ifnull( max( cast(substr(".$this->defListId()." , 4) as unsigned) ), 0 )+1 ) as new_id from ".$this->defListTable()." ";
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
					 BOARD_ID
					,BOARD_TITLE
					,BOARD_AUTH
					,BOARD_READ_AUTH
					,BOARD_WRITE_AUTH
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
		$searchData['BOARD_TITLE'] = addslashes($searchData['BOARD_TITLE']);

		$sql = " insert into ".$this->defListTable()."
				(    BOARD_ID
					,BOARD_TITLE
					,BOARD_AUTH
					,BOARD_READ_AUTH
					,BOARD_WRITE_AUTH
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$new_id."'
					,'".$searchData['BOARD_TITLE']."'
					,'".$searchData['BOARD_AUTH']."'
					,'".$searchData['BOARD_READ_AUTH']."'
					,'".$searchData['BOARD_WRITE_AUTH']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
			";
		$query = $this->db->query($sql);
		return $query;
		
	}
	
	/* 수정 */
	public function update($searchData){
		
		$sql = " update ".$this->defListTable()." 
				 set BOARD_TITLE		= '".$searchData['BOARD_TITLE']."'
					,BOARD_AUTH			= '".$searchData['BOARD_AUTH']."'
					,BOARD_READ_AUTH	= '".$searchData['BOARD_READ_AUTH']."'
					,BOARD_WRITE_AUTH	= '".$searchData['BOARD_WRITE_AUTH']."'
					,UPD_ID				= '".$_SESSION['userid']."'
					,UPD_DT				= sysdate()
				 where BOARD_ID 			= '".$searchData['BOARD_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	//pdm 내용 수정
	public function update_pdm($searchData){
		
		$sql = " update PLM_PDM_FILE
				 set PF_NM		= '".$searchData['BP_NM']."'
					,PF_CONT	= '".$searchData['BP_CONT']."'
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