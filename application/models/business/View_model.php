<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 데이터 가져오기 */
	public function getData($pb_id){
		
		$sql = "select 
					 PB_ID
					,PB_TITLE
					,PB_CONT
					,INS_ID
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = INS_ID ) AS INS_NM
				from PLM_BOARD where PB_ID = '".$pb_id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}
	
	/* 파일 가져오기 */
	public function getFileList($pb_id){
		
		$sql = " select A.PBF_ID
						,A.PB_ID
						,( SELECT PF_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_NM
						,( SELECT PF_PATH FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_PATH
						,( SELECT PF_FILE_REAL_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_REAL_NM
						,( SELECT PF_FILE_TEMP_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_TEMP_NM
						,( SELECT PF_FILE_SIZE FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_SIZE
						,( SELECT PF_FILE_EXT FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_EXT
				from PLM_BOARD_FILE A
				where A.PB_ID = '".$pb_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
}

?>