<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Upload_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 데이터 가져오기 */
	public function getData($pf_id){
		
		$sql_0= " select PLM_DETAIL_TYPE from PLM_FILE_LIST where PF_ID = '".$pf_id."' AND PLM_TYPE = 'rm' ";
		
		$query_0 = $this->db->query($sql_0);
		
		if ($query_0->num_rows() > 0){
		   $plm_detail_type = $query_0->row()->PLM_DETAIL_TYPE; 
		}else{
			$plm_detail_type = '';
		}
		$sql_view_id = null;
		// 해당 글 바로가기 에 쓸 id 가져오는건뎅 , 댓글에서 등록한 파일이랑 요구사항에서 등록한 파일이랑 가져오는 테이블이 다름 !! 
		if($plm_detail_type == 'reply'){
			$sql_view_id = ",( select b.PARENT_ID from PLM_REPLY b where b.PLM_TYPE = a.PF_INIT_ID_TYPE and b.REPLY_ID = (select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) ) VIEW_ID";
		}else if($plm_detail_type == 'normal'){
			$sql_view_id = ",( select b.PR_ID from PLM_RM b where b.PR_ID = ( select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) ) VIEW_ID";
		}else{
			$sql_view_id = "";
		}
		
		$sql = "select  a.* ".$sql_view_id."
				from PLM_PDM_FILE a
				where a.PF_ID = '".$pf_id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}
	
	/* pf_id 새로 따기 */
	public function get_new_pf_id(){
		
		$sql_1 = " select concat( 'PF_' ,ifnull( max( cast(substr(pf_id , 4) as unsigned) ), 0 )+1 ) as new_pf_id from PLM_PDM_FILE ";
		$query_1 = $this->db->query($sql_1);
		
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_pf_id = $row->new_pf_id;
		}
		return $new_pf_id;
	}
	
	/* 업로드 */
	public function upload($pfd_id,$pf_nm,$pp_id,$pf_cont,$pf_path,$new_pf_id,$tmp_name,$file_name,$file_size,$ext,$pf_init_id_type){
		
		$sql = " insert into PLM_PDM_FILE 
				(    PF_ID 
					,PFD_ID
					,PF_NM
					,PP_ID
					,PF_CONT
					,PF_PATH
					,PF_FILE_REAL_NM
					,PF_FILE_TEMP_NM
					,PF_FILE_SIZE
					,PF_FILE_EXT
					,PF_INIT_ID_TYPE
					,PF_NOW_ID_TYPE
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				)values(
					'".$new_pf_id."'
					,'".$pfd_id."'
					,'".$pf_nm."'
					,'".$pp_id."'
					,'".$pf_cont."'
					,'".$pf_path."'
					,'".$file_name."'
					,'".$tmp_name."'
					,'".$file_size."'
					,'".$ext."'
					,'".$pf_init_id_type."'
					,'".$pf_init_id_type."'
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
	
	/* 이력 가져오기 */
	public function getVersionList($pf_id){
		
		$sql = " select * from PLM_PDM_FILE_VERSION where PF_ID = '".$pf_id."' order by PFV_ID desc ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 파일까지 수정 */
	public function update($searchData,$tmp_name,$file_name,$file_size,$ext){
		
		$sql = " update PLM_PDM_FILE
				 set PFD_ID 			= '".$searchData['PFD_ID']."'
					,PF_NM 				= '".$searchData['PF_NM']."'
					,PP_ID 				= '".$searchData['PP_ID']."'
					,PF_PATH			= '".$searchData['PF_PATH']."'
					,PF_CONT 			= '".$searchData['PF_CONT']."'";
					
		if($file_name != 'N'){	
		
			$sql .=",PF_FILE_REAL_NM 	= '".$file_name."'
					,PF_FILE_TEMP_NM 	= '".$tmp_name."'
					,PF_FILE_SIZE 		= '".$file_size."'
					,PF_FILE_EXT 		= '".$ext."'";
		}
			
		$sql .=",UPD_ID				= '".$_SESSION['userid']."'
				,UPD_DT				= sysdate()
				,UPD_IP 			= '".$_SERVER['REMOTE_ADDR']."'
			where PF_ID = '".$searchData['PF_ID']."'
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
	
	/* 담당자 이력 생성 */
	public function insertEmpVersion($pf_id){
		
		$sql_0 = " select * from PLM_EMP_LIST where PLM_TYPE = '".$this->uri->segment(1)."' and PARENT_ID = '".$pf_id."' ";
		
		$query_0 = $this->db->query($sql_0);
		
		foreach ($query_0->result() as $row)
		{
				
			$sql = " insert into PLM_PDM_EMP_VERSION 
				(
					 PF_ID
					,PE_EMP_ID
					,INS_ID
					,INS_DT
					,INS_IP
				)
			 values( 
					 '".$row->PARENT_ID."'
					,'".$row->EMP_ID."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
			 )
			";
		
			$query = $this->db->query($sql);
				
		}
		
		return $query_0;
		
	}
	
	/* 담당자 이력 생성 */
	public function insertKeywordVersion($pf_id){
		
		$sql_0 = " select * from PLM_PDM_KEYWORD where PF_ID = '".$pf_id."' ";
		
		$query_0 = $this->db->query($sql_0);
		
		foreach ($query_0->result() as $row)
		{
				
			$sql = " insert into PLM_PDM_KEYWORD_VERSION 
				(
					 PF_ID
					,PK_NM
					,INS_ID
					,INS_DT
					,INS_IP
				)
			 values( 
					 '".$row->PF_ID."'
					,'".$row->PK_NM."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
			 )
			";
		
			$query = $this->db->query($sql);
				
		}
		
		return $query_0;
		
	}
	
	/* 키워드 삭제 */
	public function del_keyword($pf_id){
		
		$sql = " delete from PLM_PDM_KEYWORD where PF_ID = '".$pf_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($pf_id){
		
		$sql = " select *
				from PLM_PDM_KEYWORD 
				where PF_ID = '".$pf_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
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

}
?>