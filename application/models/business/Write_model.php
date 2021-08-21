<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Write_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	/* pb_id 새로 따기 */
	public function get_new_pb_id(){
		
		$sql_1 = " select concat( 'PB_' ,ifnull( max( cast(substr(PB_ID , 4) as unsigned) ), 0 )+1 ) as new_pb_id from PLM_BOARD ";
		$query_1 = $this->db->query($sql_1);
		
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_pb_id = $row->new_pb_id;
		}
		return $new_pb_id;
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
		
		$sql = " select  A.PBF_ID
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
	
	/* 요구사항 파일 등록 */
	public function insert_business_file($new_pb_id,$new_pf_id){
		
		$sql = " insert into PLM_BOARD_FILE 
				(    PF_ID 
					,PB_ID
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				)values(
					 '".$new_pf_id."'
					,'".$new_pb_id."'
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
	
	/* 요구사항 등록 */
	public function insert_business($new_pb_id,$searchData){
		
		$sql = " insert into PLM_BOARD 
				(    PB_ID 
					,PB_TITLE
					,PB_CONT
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				)values(
					 '".$new_pb_id."'
					,'".$searchData['PB_TITLE']."'
					,'".$searchData['PB_CONT']."'
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
	public function update_business($searchData){
		
		$sql = " update PLM_BOARD 
				 set PB_TITLE			= '".$searchData['PB_TITLE']."'
					,PB_CONT			= '".$searchData['PB_CONT']."'
					,UPD_ID				= '".$_SESSION['userid']."'
					,UPD_DT				= sysdate()
					,UPD_IP 			= '".$_SERVER['REMOTE_ADDR']."'
				 where PB_ID = '".$searchData['PB_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
}

?>