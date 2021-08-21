<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Format_model extends CI_Model{
	
	/*
	������ ���� function���� ��� : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageType(){ return 'PF_ID'; }
	function defListTable(){ return 'PLM_FORMAT'; }
	function defListId(){ return 'PF_ID'; }
	function defListQuery(){ 	
		return '(select count(*) from PLM_FA where PLM_TYPE = "format" and FA_ID = a.PF_ID and FA_USER = "'.$this->session->userdata('userid').'" ) as FA_CNT,PF_ID,PF_NM,PF_CONT,a.INS_ID,(select b.PE_NM from PLM_EMP b where a.INS_ID = b.PE_ID ) as INS_NM,INS_DT';
	}
	
	function __construct(){
		parent::__construct();
	}
	
	/* pf_id �������� */
	function get_pf_ids($id){
		$sql = " select PF_ID 
				from PLM_FILE_LIST 
				where PLM_TYPE = '".strtolower($this->pageType())."'
				AND PARENT_ID = '".$id."' 
				AND PLM_DETAIL_TYPE = 'normal' ";
		$q = $this->db->query($sql);
		return $q->result();
	}
	
	/* ��ã */
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
	
	/* ����Ʈ �ҷ����� */
	function getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE){
		/*sub ����*/
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
  
	/*����*/
	function p_del($ARR){
		
		//����Ʈ ����
		$this->db->delete($this->defListTable(), array($this->defListId() => $ARR)); 
		//���ϻ���
		$this->db->delete('PLM_FILE_LIST', array('PARENT_ID' => $ARR)); 
		// ��ã����
		$this->db->delete('PLM_FA', array('FA_ID' => $ARR)); 
		
	}
	
	/* Ű���� �������� */
	public function remove_pdm_keyword($pf_id){
		$sql = " delete from PLM_PDM_KEYWORD 
				where PF_ID = '".$pf_id."'
				";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/* pdm ���� */
	public function pdm_remove($pf_id){
		$sql = " delete from PLM_PDM_FILE 
				 where PF_ID = '".$pf_id."'
				";
		$query = $this->db->query($sql);
		/* ��ã�� �׳ɻ���*/
		$this->db->delete('PLM_FA', array('PLM_TYPE' => 'pdm2','FA_ID' => $pf_id)); 
		return $query;
	}
	
}
?>