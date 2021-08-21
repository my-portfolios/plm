<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	//삭제여부확인
	public function chkDelYn($pr_id){
		$sql = "select ifnull(PR_DEL_YN,'N') as PR_DEL_YN from PLM_RM where PR_ID = '".$pr_id."'";
		$query = $this->db->query($sql);
		return $query->row()->PR_DEL_YN;
	}
	
	//삭제
	public function del($pr_id){
		$data = array(
               'PR_DEL_YN' => 'Y',
               'UPD_ID' => $this->session->userdata('userid'),
               'UPD_DT' => date('Y-m-d H:i:s')
            );

		$this->db->where('PR_ID', $pr_id);
		$this->db->update('PLM_RM', $data); 
	}
	
	/* 데이터 가져오기 */
	public function getData($pr_id){
		
		$sql = "select 
					PR_ID
					,PP_ID
					,PR_TITLE
					,DATE_FORMAT(PR_HOPE_END_DAT,'%Y-%m-%d') AS PR_HOPE_END_DAT
					,PR_CONT
					,PR_STATUS
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = INS_ID ) AS INS_NM
					,PR_DEL_YN
				from PLM_RM 
				where PR_ID = '".$pr_id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}
	
	/* 담당자 가져오기
	public function getEmpList($pr_id){
		
		$sql = " select PRE_ID 
						,PR_ID
						,PE_EMP_ID
						, ( select PE_NM from PLM_EMP where PE_ID = PE_EMP_ID ) as PE_EMP_NM
				from PLM_RM_EMP  
				where PR_ID = '".$pr_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	 */
	/* 댓글 가져오기 */
	public function getReplyList($pr_id){
		
		$sql = " select A.REPLY_ID
						,A.PARENT_ID
						,A.REPLY_CONT
						,A.PLM_TYPE
						,A.INS_ID
						,A.INS_DT
						, ( select PE_NM from PLM_EMP where PE_ID = A.INS_ID ) as INS_NM
				from PLM_REPLY A
				where 1=1
				AND A.PLM_TYPE = 'rm'
				AND A.PARENT_ID = '".$pr_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 댓글 첨부파일 가져오기
	public function getReplyFileList($pr_id){
		$sql = " select  A.REPLY_FILE_ID
						,A.PF_ID
						,A.REPLY_ID
						,( SELECT PF_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_NM
						,( SELECT PF_PATH FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_PATH
						,( SELECT PF_FILE_REAL_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_REAL_NM
						,( SELECT PF_FILE_TEMP_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_TEMP_NM
						,( SELECT PF_FILE_SIZE FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_SIZE
						,( SELECT PF_FILE_EXT FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_EXT
				from PLM_REPLY_FILE A
				where 1=1
				and A.REPLY_ID IN ( SELECT REPLY_ID FROM PLM_REPLY WHERE PARENT_ID = '".$pr_id."' )
				";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	 */
	/* 파일 가져오기 */
	public function getFileList($plm_detail_type,$pr_id){
		
		$sql = " select A.FILELIST_ID 
					,A.PARENT_ID 
					,( SELECT PF_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_NM
					,( SELECT PF_PATH FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_PATH
					,( SELECT PF_FILE_REAL_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_REAL_NM
					,( SELECT PF_FILE_TEMP_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_TEMP_NM
					,( SELECT PF_FILE_SIZE FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_SIZE
					,( SELECT PF_FILE_EXT FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_EXT
			from PLM_FILE_LIST A
			where A.PLM_TYPE 	= '".$this->uri->segment(1)."'
			AND A.PLM_DETAIL_TYPE = '".$plm_detail_type."'";
			
		if($plm_detail_type == 'normal'){
			$sql .=" AND A.PARENT_ID = '".$pr_id."' ";
		}else if($plm_detail_type == 'reply_rm'){
			$sql .= " AND A.PARENT_ID IN ( SELECT B.REPLY_ID FROM PLM_REPLY B WHERE B.PARENT_ID = '".$pr_id."' AND B.PLM_TYPE = 'rm' ) ";
		}else{
			$sql .=" AND A.PARENT_ID = '".$pr_id."' ";
		}
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 진행상태 변경 */
	public function pr_status_upd($searchData){
		
		$sql = " update PLM_RM 
				 set PR_STATUS	= '".$searchData['PR_STATUS']."'
					,UPD_ID		= '".$_SESSION['userid']."'
					,UPD_DT		= sysdate()
					,UPD_IP 	= '".$_SERVER['REMOTE_ADDR']."'
				 where PR_ID 	= '".$searchData['PR_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
}

?>