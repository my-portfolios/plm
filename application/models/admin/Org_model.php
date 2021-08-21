<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Org_model extends CI_Model{
	
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageType(){ return 'Org'; }
	function defListTable(){ return 'PLM_ORG'; }
	function defListId(){ return 'ORG_ID'; }
	function defListQuery(){ 	
		return '(select count(*) from PLM_FA where PLM_TYPE = "'.$this->pageType().'" and FA_ID = a.ORG_ID and FA_USER = "'.$this->session->userdata('userid').'" ) as FA_CNT,ORG_ID,ORG_NM,ORG_DATA,ORG_YN,(select PE_NM from PLM_EMP where PE_ID = a.INS_ID) as INS_ID,INS_DT';
	}
	
	function __construct(){
		parent::__construct();
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