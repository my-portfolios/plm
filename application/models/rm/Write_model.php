<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Write_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	/* pr_id 새로 따기 */
	public function get_new_pr_id(){
		
		$sql_1 = " select concat( 'PR_' ,ifnull( max( cast(substr(pr_id , 4) as unsigned) ), 0 )+1 ) as new_pr_id from PLM_RM ";
		$query_1 = $this->db->query($sql_1);
		
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_pr_id = $row->new_pr_id;
		}
		return $new_pr_id;
	}
	
	/* 데이터 가져오기 */
	public function getData($pr_id){
		
		$sql = "select 
					PR_ID
					,PR_TITLE
					,DATE_FORMAT(PR_HOPE_END_DAT,'%Y-%m-%d') AS PR_HOPE_END_DAT
					,PR_CONT
					,CASE WHEN PR_STATUS='1' THEN '접수완료' ELSE 'ㅎㅎ' END AS PR_STATUS
					,INS_ID
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = INS_ID ) AS INS_NM
				from PLM_RM 
				where PR_ID = '".$pr_id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}
	
	/* 파일 가져오기 */
	public function getFileList($pr_id){
		
		$sql = " select A.FILELIST_ID
						,A.PARENT_ID AS PR_ID
						,( SELECT PF_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_NM
						,( SELECT PF_PATH FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_PATH
						,( SELECT PF_FILE_REAL_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_REAL_NM
						,( SELECT PF_FILE_TEMP_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_TEMP_NM
						,( SELECT PF_FILE_SIZE FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_SIZE
						,( SELECT PF_FILE_EXT FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_EXT
				from PLM_FILE_LIST A 
				where A.PLM_TYPE = '".$this->uri->segment(1)."'
				AND A.PARENT_ID = '".$pr_id."'
				AND A.PLM_DETAIL_TYPE = 'normal'";

		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	public function get_pf_ids_pr_id($pr_id){
		
		$sql = " SELECT PF_ID FROM PLM_FILE_LIST WHERE PARENT_ID = '".$pr_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 요구사항 등록 */
	public function insert_rm($new_pr_id,$searchData){
		
		$sql = " insert into PLM_RM 
				(    PR_ID 
					,PR_TITLE
					,PR_HOPE_END_DAT
					,PR_CONT
					,PR_STATUS
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				)values(
					 '".$new_pr_id."'
					,'".$searchData['PR_TITLE']."'
					,'".$searchData['PR_HOPE_END_DAT']."'
					,'".$searchData['PR_CONT']."'
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
	
	/* 요구사항 수정 */
	public function update_rm($searchData){
		
		$sql = " update PLM_RM 
				 set PR_TITLE			= '".$searchData['PR_TITLE']."'
					,PR_HOPE_END_DAT	= '".$searchData['PR_HOPE_END_DAT']."'
					,PR_CONT			= '".$searchData['PR_CONT']."'
					,UPD_ID				= '".$_SESSION['userid']."'
					,UPD_DT				= sysdate()
					,UPD_IP 			= '".$_SERVER['REMOTE_ADDR']."'
				 where PR_ID 			= '".$searchData['PR_ID']."'
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