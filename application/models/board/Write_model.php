<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Write_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	public function getBoardList(){
		$sql = " select a.BOARD_ID, a.BOARD_TITLE
				 from PLM_BOARD a
				  where 1=1
					and (
						   ( a.BOARD_WRITE_AUTH = 3 and '".$this->session->userdata('userauth')."' = 'admin' )
						or ( a.BOARD_WRITE_AUTH = 2 and '".$this->session->userdata('userauth')."' in ('admin','emp') )
						or ( a.BOARD_WRITE_AUTH = 1 and '".$this->session->userdata('userauth')."' in ('admin','emp','user') )
					)
				";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	/* pr_id 새로 따기 */
	public function get_new_id(){
		
		$sql_1 = " select concat( 'CT_' ,ifnull( max( cast(substr(CONTS_ID , 4) as unsigned) ), 0 )+1 ) as new_id from PLM_BOARD_CONTENTS ";
		$query_1 = $this->db->query($sql_1);
		
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_id = $row->new_id;
		}
		return $new_id;
	}
	
	/* 데이터 가져오기 */
	public function getData($pr_id){
		
		$sql = "select 
					 CONTS_ID
					,PARENT_ID
					,CONTS_TITLE
					,CONTS_CONT
					,INS_ID
					,( SELECT BOARD_NOTICE FROM PLM_BOARD WHERE BOARD_ID = PARENT_ID ) AS BOARD_NOTICE
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = INS_ID ) AS INS_NM
				from PLM_BOARD_CONTENTS 
				where CONTS_ID = '".$pr_id."'";
		
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
				AND A.PLM_DETAIL_TYPE = 'board'";

		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	public function get_pf_ids_pr_id($pr_id){
		
		$sql = " SELECT PF_ID FROM PLM_FILE_LIST WHERE PARENT_ID = '".$pr_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 요구사항 등록 */
	public function insert_conts($new_id,$searchData){
		
		// ' escape
		$searchData['CONTS_TITLE'] = addslashes($searchData['CONTS_TITLE']); 

		//공지처리
		$this->board_notice_proc($searchData['PARENT_ID'], $new_id, $searchData['BOARD_NOTICE']);
		
		$sql = " insert into PLM_BOARD_CONTENTS 
				(    
					 CONTS_ID
					,PARENT_ID
					,CONTS_TITLE
					,CONTS_CONT
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$new_id."'
					,'".$searchData['PARENT_ID']."'
					,'".$searchData['CONTS_TITLE']."'
					,'".$searchData['CONTS_CONT']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
			";
			
		$query = $this->db->query($sql); 
		
		return $query;
		
	}
	
	/* 요구사항 수정 */
	public function update_conts($searchData){
		
		//공지처리
		$this->board_notice_proc($searchData['PARENT_ID'], $searchData['CONTS_ID'], $searchData['BOARD_NOTICE']); 
		
		$sql = " update PLM_BOARD_CONTENTS 
				 set PARENT_ID			= '".$searchData['PARENT_ID']."'
					,CONTS_TITLE		= '".$searchData['CONTS_TITLE']."'
					,CONTS_CONT			= '".$searchData['CONTS_CONT']."'
					,UPD_ID				= '".$_SESSION['userid']."'
					,UPD_DT				= sysdate()
				 where CONTS_ID 		= '".$searchData['CONTS_ID']."'
				";
				
		$query = $this->db->query($sql);

		return $query;
		
	}
	
	//pdm 내용 수정
	public function update_pdm($searchData){
		
		$sql = " update PLM_PDM_FILE
				 set PF_NM		= '".$searchData['CONTS_TITLE']."'
					,PF_CONT	= '".$searchData['CONTS_CONT']."'
					,UPD_ID		= '".$_SESSION['userid']."'
					,UPD_DT		= sysdate()
					,UPD_IP 	= '".$_SERVER['REMOTE_ADDR']."'
				 where PF_ID IN ( SELECT B.PF_ID FROM PLM_FILE_LIST B WHERE B.PARENT_ID = '".$searchData['CONTS_ID']."' AND B.PLM_DETAIL_TYPE = 'board' )
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

	//공지필드 가져오기
	function get_board_notice_str($board_id){
		
		$sql = "select BOARD_NOTICE 
				from PLM_BOARD
				where BOARD_ID = '".$board_id."'
				";
		
		$query = $this->db->query($sql);
		
		return $query->row()->BOARD_NOTICE;
		
	}

	//공지여부 판단
	/*
	public function get_is_notice($board_id, $cont_id){
		
		$board_notice_str = $this->Write_model->get_board_notice_str($board_id);
		if(strpos($board_notice_str, $cont_id.",") !== false)  return true;
		
		return false;
	}
	*/

	//공지처리
	function board_notice_proc($board_id, $cont_id, $BOARD_NOTICE='N'){
		
		$board_notice_str = $this -> get_board_notice_str($board_id);
		
		if($BOARD_NOTICE=='Y') $board_notice_str .= $cont_id.",";
		else $board_notice_str = str_replace($cont_id.",","",$board_notice_str);

		$sql = " update PLM_BOARD 
				 set BOARD_NOTICE			= '".$board_notice_str."'
				 where BOARD_ID 		= '".$board_id."'
				";
				
		$query = $this->db->query($sql);

		//$data = array('BOARD_NOTICE' => $board_notice_str);
		//$where = "BOARD_ID = '".$board_id."'";
		//$str = $this->db->update_string('PLM_BOARD', $data, $where);
	}
	
}

?>