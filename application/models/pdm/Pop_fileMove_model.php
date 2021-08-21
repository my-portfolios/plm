<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_fileMove_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	/* 선택이동 */
	public function move($pf_id , $pfd_id, $path){
		
		$sql = " update PLM_PDM_FILE 
					set PFD_ID = '".$pfd_id."'
					, PF_PATH = '".$path."'
					, PF_NOW_ID_TYPE = 'pdm'
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	/* 이력 생성 */
	public function insertFileVersion($pf_id){
		
		$sql = " insert into PLM_PDM_FILE_VERSION 
					(
						 PF_ID
						,PFD_ID
						,PF_NM
						,PP_ID
						,PF_CONT
						,PF_PATH
						,PF_FILE_REAL_NM
						,PF_FILE_TEMP_NM
						,PF_FILE_SIZE
						,PF_FILE_EXT
						,INS_ID
						,INS_DT
						,INS_IP
					)
				 values( 
						 (select PF_ID 			from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PFD_ID 		from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PF_NM 			from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PP_ID 			from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PF_CONT 		from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PF_PATH 		from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PF_FILE_REAL_NM from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PF_FILE_TEMP_NM from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PF_FILE_SIZE 	from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,(select PF_FILE_EXT 	from PLM_PDM_FILE where PF_ID = '".$pf_id."')
						,'".$_SESSION['userid']."'
						,sysdate()
						,'".$_SERVER['REMOTE_ADDR']."'
				 )
				";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
}
?>