<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	/* 데이터 가져오기 */
	public function getData($c_id){
		
		$sql = "select 
					 A.CONTS_ID
					,A.PARENT_ID
					,(SELECT B.BOARD_TITLE FROM PLM_BOARD B WHERE B.BOARD_ID = A.PARENT_ID ) AS BOARD_NM
					,A.CONTS_TITLE
					,A.CONTS_CONT
					,A.CONTS_DEL_YN
					,A.INS_ID
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = A.INS_ID ) AS INS_NM
					,INS_DT
					,UPD_DT
				from PLM_BOARD_CONTENTS A 
				where CONTS_ID = '".$c_id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
	}
	
	/* 댓글 가져오기 */
	public function getReplyList($c_id){
		
		$sql = " select A.REPLY_ID
						,A.PARENT_ID
						,A.REPLY_CONT
						,A.PLM_TYPE
						,A.INS_ID
						,A.INS_DT
						, ( select PE_NM from PLM_EMP where PE_ID = A.INS_ID ) as INS_NM
				from PLM_REPLY A
				where 1=1
				AND A.PLM_TYPE = 'board'
				AND A.PARENT_ID = '".$c_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 댓글 첨부파일 가져오기
	public function getReplyFileList($c_id){
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
				and A.REPLY_ID IN ( SELECT REPLY_ID FROM PLM_REPLY WHERE PARENT_ID = '".$c_id."' )
				";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	*/
	/* 파일 가져오기 */
	public function getFileList($plm_detail_type,$c_id){
		
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
			$sql .=" AND A.PARENT_ID = '".$c_id."' ";
		}else if($plm_detail_type == 'reply_board'){
			$sql .= " AND A.PARENT_ID IN ( SELECT B.REPLY_ID FROM PLM_REPLY B WHERE B.PARENT_ID = '".$c_id."' AND B.PLM_TYPE = 'board' ) ";
		}else{
			$sql .=" AND A.PARENT_ID = '".$c_id."' ";
		}
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
}

?>