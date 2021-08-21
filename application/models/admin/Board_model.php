<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Board_model extends CI_Model{
	
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageType(){ return 'Board'; }
	function defListTable(){ return 'PLM_BOARD'; }
	function defListId(){ return 'BOARD_ID'; }
	function defListQuery(){ 	
		return '(select count(*) from PLM_FA where PLM_TYPE = "board" and FA_ID = a.BOARD_ID and FA_USER = "'.$this->session->userdata('userid').'" ) as FA_CNT,BOARD_ID,BOARD_TITLE,BOARD_AUTH,BOARD_READ_AUTH,BOARD_WRITE_AUTH,INS_DT,(select PE_NM from PLM_EMP where PE_ID = a.INS_ID) as INS_ID';
	}
	
	function __construct(){
		parent::__construct();
	}
	
	/* 해당 게시판에 게시글 있는지 확인 */
	function contsChk($board_id){
		$sql = "select count(*) cnt from PLM_BOARD_CONTENTS where PARENT_ID = '".$board_id."'";
		$query = $this->db->query($sql);
		return $query->row()->cnt;
	}
	
	/* pf_id 가져오기 */
	function get_pf_ids($id){
		$sql = " select PF_ID 
				from PLM_FILE_LIST 
				where PLM_TYPE = '".strtolower($this->pageType())."'
				AND PARENT_ID = '".$id."' 
				AND PLM_DETAIL_TYPE = 'normal' ";
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
	function getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE){
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
		$this->db->delete($this->defListTable(), array($this->defListId() => $ARR)); 
		//파일삭제
		$this->db->delete('PLM_FILE_LIST', array('PARENT_ID' => $ARR)); 
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