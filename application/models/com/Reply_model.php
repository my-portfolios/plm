<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Reply_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 댓글아이디 새로 따기 */
	public function get_new_reply_id(){
		
		$sql = "select (ifnull( max(reply_id) ,0 ) +1) as new_reply_id from PLM_REPLY";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0){
		   $row = $query->row(); 
		   $new_reply_id = $row->new_reply_id;
		}
		return $new_reply_id;
	}
	
	/* 댓글 수정 */
	public function reply_update($searchData){
		
		$sql = " update PLM_REPLY 
				set  REPLY_CONT 	= '".$searchData['REPLY_CONT']."'
					,UPD_ID		= '".$_SESSION['userid']."'
					,UPD_DT		= sysdate()
					,UPD_IP 	= '".$_SERVER['REMOTE_ADDR']."'
				where PLM_TYPE 	= '".$searchData['PLM_TYPE']."'
				and REPLY_ID 		= '".$searchData['REPLY_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 신규 댓글 작성 */
	public function reply_save($reply_id,$searchData){
		
		$sql = " insert into PLM_REPLY
				 (
					 REPLY_ID
					,PARENT_ID
					,PLM_TYPE
					,REPLY_CONT
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				 )values(
					 '".$reply_id."'
					,'".$searchData['PARENT_ID']."'
					,'".$searchData['PLM_TYPE']."'
					,'".$searchData['REPLY_CONT']."'
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
	
	
	public function insert_pms($parent_id,$pf_id){
		
		$sql = " insert into PLM_PMS_LIST 
					( 	 PLM_TYPE
						,PARENT_ID
						,PP_ID
						,INS_ID
						,INS_DT	
						,INS_IP
						,UPD_ID
						,UPD_DT
						,UPD_IP
					)
					select 'pdm2' 
						, '".$pf_id."' 
						, PP_ID 
						, '".$_SESSION['userid']."' 
						, sysdate() 
						, '".$_SERVER['REMOTE_ADDR']."'
						, '".$_SESSION['userid']."' 
						, sysdate() 
						, '".$_SERVER['REMOTE_ADDR']."'
					from PLM_PMS_LIST
					where PARENT_ID = '".$parent_id."'
				
				";
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	public function insert_emp($parent_id,$pf_id){
		
		$sql = " insert into PLM_EMP_LIST 
					( 	 PLM_TYPE
						,PARENT_ID
						,EMP_ID
						,EMP_NM
						,INS_ID
						,INS_DT	
						,INS_IP
						,UPD_ID
						,UPD_DT
						,UPD_IP
					)
					select 'pdm2' 
						, '".$pf_id."' 
						, EMP_ID
						, EMP_NM
						, '".$_SESSION['userid']."' 
						, sysdate() 
						, '".$_SERVER['REMOTE_ADDR']."'
						, '".$_SESSION['userid']."' 
						, sysdate() 
						, '".$_SERVER['REMOTE_ADDR']."'
					from PLM_EMP_LIST
					where PARENT_ID = '".$parent_id."'
				
				";
		$query = $this->db->query($sql);
		
		return $query;
	}
		
	/* 키워드 등록 */
	public function insert_keyword($pf_id,$keyword){
		
		$sql = " insert into PLM_PDM_KEYWORD 
				 (
					PF_ID
					,PK_NM
					,INS_ID
					,INS_DT
					,INS_IP
				 )values(
					 '".$pf_id."'
					,'".$keyword."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
				 )
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	
	/* 댓글 삭제 */
	public function reply_delete($searchData){
		
		$sql = " delete from PLM_REPLY
					where REPLY_ID = '".$searchData['reply_id']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	public function get_fileLists($reply_id,$plm_type){
		
		$sql = " SELECT FILELIST_ID FROM PLM_FILE_LIST WHERE PARENT_ID = '".$reply_id."' AND PLM_DETAIL_TYPE = 'reply_".$plm_type."'";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 댓글 첨부파일 삭제 */
	public function reply_file_delete($reply_id,$plm_type){
		$sql = "delete from PLM_FILE_LIST
				where PARENT_ID = '".$reply_id."'
				AND PLM_TYPE = '".$plm_type."'
				AND PLM_DETAIL_TYPE = 'reply'
				";
		
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	/* 댓글 첨부파일 삭제 */
	public function delete_file($reply_file_id){
		
		$sql = "delete from PLM_FILE_LIST
				where FILELIST_ID = '".$reply_file_id."'";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	public function get_pf_id($id){
		
		$sql = "select a.PF_ID 
				from PLM_PDM_FILE a
				where a.PF_ID = ( select b.PF_ID from PLM_FILE_LIST b where FILELIST_ID = '".$id."' )
				";
		
		$query = $this->db->query($sql);
		
		return $query->row()->PF_ID;
		
	}
}
?>