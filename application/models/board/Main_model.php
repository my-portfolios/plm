<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Main_model extends CI_Model{
	
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageType(){ return 'Board'; }
	function defListTable(){ return 'PLM_BOARD_CONTENTS'; }
	function defListId(){ return 'CONTS_ID'; }
	function defListQuery(){ 	
		return "(select count(*) from PLM_FA where PLM_TYPE = 'board' and FA_ID = a.CONTS_ID and FA_USER = '".$this->session->userdata('userid')."' ) as FA_CNT,
			a.CONTS_ID, a.PARENT_ID, a.CONTS_TITLE,
			a.INS_ID, (select b.PE_NM from PLM_EMP b where b.PE_ID = a.INS_ID) as INS_NM,
			a.UPD_ID, a.UPD_DT";
	}
	
	function __construct(){
      parent::__construct();
	}
	
	function chkInsId($conts_id){
		$sql = "select INS_ID from ".$this->defListTable()." where CONTS_ID = '".$conts_id."'";
		$query = $this->db->query($sql);
		return $query->row()->INS_ID;
	}
	
	//게시판 리스트 불러오기
	function getBoardList(){
		$sql = " select a.BOARD_ID, a.BOARD_TITLE, a.INS_ID, a.INS_DT
				 from PLM_BOARD a
				 where 1=1
				 and (
					   ( a.BOARD_AUTH = 3 and '".$this->session->userdata('userauth')."' = 'admin' )
					or ( a.BOARD_AUTH = 2 and '".$this->session->userdata('userauth')."' in ('admin','emp') )
					or ( a.BOARD_AUTH = 1 and '".$this->session->userdata('userauth')."' in ('admin','emp','user') )
				)
				 order by a.BOARD_ID
				";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	//첫 게시판 아이디
	function getFirstBoard(){
		$sql = " select a.BOARD_ID 
				from PLM_BOARD a 
				where 1=1
				and (
					   ( a.BOARD_AUTH = 3 and '".$this->session->userdata('userauth')."' = 'admin' )
					or ( a.BOARD_AUTH = 2 and '".$this->session->userdata('userauth')."' in ('admin','emp') )
					or ( a.BOARD_AUTH = 1 and '".$this->session->userdata('userauth')."' in ('admin','emp','user') )
				)
				order by a.BOARD_ID 
				limit 1 
				";
		$query = $this->db->query($sql);
		return $query->row()->BOARD_ID;
	}
	
	/* pf_id 가져오기 */
	function get_pf_ids($id){
		$sql = " select PF_ID 
				from PLM_FILE_LIST 
				where PLM_TYPE = '".$this->uri->segment(1)."'
				AND PARENT_ID = '".$id."' 
				AND PLM_DETAIL_TYPE = 'board' ";
		$q = $this->db->query($sql);
		return $q->result();
	}
	function get_reply_pf_ids($pr_id){
		$sql = " SELECT A.PF_ID
				 FROM PLM_FILE_LIST A
				 WHERE A.PARENT_ID IN ( SELECT B.REPLY_ID FROM PLM_REPLY B WHERE B.PARENT_ID = '".$pr_id."' )
				 AND A.PLM_DETAIL_TYPE = 'reply_board' ";
		$q = $this->db->query($sql);
		return $q->result();
	}
	
	/* 즐찾 */
	function faYn($FA_TYPE,$FA_VAL,$FA_USER){
		
		$this->db->where('PLM_TYPE', $FA_TYPE);
		$this->db->where('FA_ID', $FA_VAL);
		$this->db->where('FA_USER', $FA_USER);
		$this->db->from('PLM_FA');
		$cnt = $this->db->count_all_results();
		
		if($cnt > 0){
			$this->db->delete('PLM_FA', array('PLM_TYPE' => $FA_TYPE,'FA_ID' => $FA_VAL,'FA_USER' => $FA_USER)); 
		}else{
			$data = array(
			   'PLM_TYPE' => $FA_TYPE ,
			   'FA_ID' => $FA_VAL,
			   'FA_USER' => $FA_USER
			);
			$this->db->insert('PLM_FA', $data); 
		}
	}
	
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE,$board_id){
		/*sub 쿼리*/
		$this->db->select('FA_ID');
		$this->db->where("FA_USER",$FA_USER);
		$this->db->where("PLM_TYPE",$FA_TYPE);
		$this->db->from('PLM_FA');
		$sub_query = $this->db->get_compiled_select();

		$this->db->select($this->defListQuery());
		$this->db->limit($limit);
		if($where != NULL)
		$this->db->where($where,NULL,FALSE);
		
		$this->db->where("ifnull(CONTS_DEL_YN,'N')","N");
		$this->db->where("PARENT_ID",$board_id);
		if($FA_SORT_STAR == 'true'){
			$this->db->where($this->defListId()." IN ($sub_query)");
		}
		
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->defListTable().' a',$limit,$start);
			
		return $query->result();
	}
  
	/*삭제*/
	function p_del($ARR){
		
		//리스트 삭제
		/*
		$this->db->set('CONTS_DEL_YN','Y');
		$this->db->where(array($this->defListId() => $ARR));
		$this->db->update($this->defListTable());
		*/
		$this->db->delete($this->defListTable(), array($this->defListId() => $ARR)); 
		
		//파일삭제
		$this->db->delete('PLM_FILE_LIST', array('PARENT_ID' => $ARR)); 
		
		//리플파일삭제	
		$this->db->where("PLM_DETAIL_TYPE = 'reply_board' AND PARENT_ID IN ( select a.REPLY_ID from PLM_REPLY a where a.PARENT_ID = '".$ARR."' )"); 
		$this->db->delete('PLM_FILE_LIST'); 
		
		//리플삭제
		$this->db->delete('PLM_REPLY', array('PARENT_ID' => $ARR)); 
		
		//프로젝트 리스트 삭제
		$this->db->delete('PLM_PMS_LIST', array('PARENT_ID' => $ARR)); 
		
		//담당자 리스트 삭제
		$this->db->delete('PLM_EMP_LIST', array('PARENT_ID' => $ARR)); 
		
		// 즐찾삭제
		$this->db->delete('PLM_FA', array('FA_ID' => $ARR)); 
		
	}
	
	/* 키워드 영구삭제 */
	public function remove_pdm_keyword($pf_id){
		$sql = " delete from PLM_PDM_KEYWORD 
				where PF_ID = '".$pf_id."'
				";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/* pdm 삭제 */
	public function pdm_remove($pf_id){
		$sql = " delete from PLM_PDM_FILE 
				 where PF_ID = '".$pf_id."'
				";
		$query = $this->db->query($sql);
		/* 즐찾은 그냥삭제*/
		$this->db->delete('PLM_FA', array('PLM_TYPE' => 'pdm2','FA_ID' => $pf_id)); 
		return $query;
	}
	
}
?>