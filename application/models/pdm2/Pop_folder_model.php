<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_folder_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	/* 새 PFD_ID 따기 */
	public function get_new_pfd_id(){
		$sql = " select concat( 'PFD_' ,ifnull( max( cast(substr(pfd_id , 5) as unsigned) ), 0 )+1 ) as new_pfd_id from PLM_PDM_FOLDER ";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0){
		   $row = $query->row(); 
		   $new_pfd_id = $row->new_pfd_id;
		}
		return $new_pfd_id;
	}
	
	/* 폴더 추가 */
	public function addFolder($searchData,$new_pfd_id){
		
		$sql = " insert into PLM_PDM_FOLDER
				  ( PFD_ID 
					,PFD_PARENT_ID
					,PFD_NM
					,INS_ID
					,INS_DT
					,INS_IP
				  )values(
					'".$new_pfd_id."'
					,'".$searchData['parent_id']."'
					,'".$searchData['text']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
				  )";
				  
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	/* 이름변경 */
	public function updateFolder($searchData){
		
		$sql = " update PLM_PDM_FOLDER
				 set PFD_NM = '".$searchData['text']."'
					,UPD_ID = '".$_SESSION['userid']."'
					,UPD_DT = sysdate()
					,UPD_IP = '".$_SERVER['REMOTE_ADDR']."'
				 where PFD_ID = '".$searchData['pfd_id'][0]."'
				 ";
				 
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	
	/* 폴더삭제 */
	public function deleteFolder($dels){
		
		$sql = " delete from PLM_PDM_FOLDER
				where PFD_ID in ( ".$dels." ) 
				";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 폴더이동 */
	public function moveFolder($searchData){
		
		$sql = " update PLM_PDM_FOLDER
				 set PFD_PARENT_ID = '".$searchData['parent_id']."' 
					,UPD_ID = '".$_SESSION['userid']."'
					,UPD_DT = sysdate()
					,UPD_IP = '".$_SERVER['REMOTE_ADDR']."'
				where PFD_ID = '".$searchData['id'][0]."'
				";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 하위에 파일이 있는지 확인 */
	public function chkChildFile($searchData){
		
		$sql = " select count(*) cnt
				from PLM_PDM_FILE
				where ifnull(PF_DEL_YN,'N') != 'Y'
				and PFD_ID = '".$searchData['parent_id']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}
	
}
?>