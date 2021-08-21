<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Part_model extends CI_Model{
	
	/*
	페이지 변수 function으로 사용 : 
	$this->{$this->pageType()}->function();
	$this->pageType();
	*/
	function pageType(){ return 'Part'; }
	function defListTable(){ return 'PLM_BOM_PART'; }
	function defListId(){ return 'BP_ID'; }
	function defSeqId(){ return 'PART_'; }
	function defListQuery(){ 	
		return '(select count(*) from PLM_FA where PLM_TYPE = "part" and FA_ID = a.BP_ID and FA_USER = "'.$this->session->userdata('userid').'" ) as FA_CNT,BP_ID,BP_NM,BP_STD,BP_MTR,BP_CONT,(select PE_NM from PLM_EMP where PE_ID = a.INS_ID) as INS_ID,INS_DT';
	}
	
  function __construct(){
      parent::__construct();
	}

	public function get_new_id(){
		$sql_1 = " select concat( '".$this->defSeqId()."' ,ifnull( max( cast(substr(".$this->defListId()." , 6) as unsigned) ), 0 )+1 ) as new_id from ".$this->defListTable()." ";
		$query_1 = $this->db->query($sql_1);
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_id = $row->new_id;
		}
		return $new_id;
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
	
	/* pf_id 가져오기 */
	function get_pf_ids($id){
		$sql = " select PF_ID 
				from PLM_FILE_LIST 
				where PLM_TYPE = '".strtolower($this->pageType())."'
				AND PARENT_ID = '".$id."' 
				AND PLM_DETAIL_TYPE = 'bom_part' ";
		$q = $this->db->query($sql);
		return $q->result();
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

  /* 파일 가져오기 */
	public function getFileList($id){
		
		$sql = " select A.FILELIST_ID
						,A.PARENT_ID AS PR_ID
						,( SELECT PF_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_NM
						,( SELECT PF_PATH FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_PATH
						,( SELECT PF_FILE_REAL_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_REAL_NM
						,( SELECT PF_FILE_TEMP_NM FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_TEMP_NM
						,( SELECT PF_FILE_SIZE FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_SIZE
						,( SELECT PF_FILE_EXT FROM PLM_PDM_FILE WHERE PF_ID = A.PF_ID ) AS PF_FILE_EXT
				from PLM_FILE_LIST A 
				where A.PLM_TYPE = '".strtolower($this->pageType())."'
				AND A.PARENT_ID = '".$id."'
				AND A.PLM_DETAIL_TYPE = 'bom_part'";

		$query = $this->db->query($sql);
		
		return $query->result();
		
	}

	/* 거래처 가져오기 */
	public function getCompList($id){
		
		$sql = "SELECT a.PARENT_ID AS BP_ID, 
		a.PC_ID AS PC_ID,
		(SELECT PC_NM FROM PLM_COMP WHERE PC_ID = a.PC_ID) AS PC_NM 
		 FROM PLM_COMP_LIST a 
		 WHERE a.PARENT_ID = '".$id."'";

		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
  
  /*삭제*/
	function p_del($ARR){
		//리스트 삭제
		$this->db->delete($this->defListTable(), array($this->defListId() => $ARR)); 
		//파일삭제
		$this->db->delete('PLM_FILE_LIST', array('PARENT_ID' => $ARR)); 
		/* 즐찾삭제*/
		$this->db->delete('PLM_FA', array('FA_ID' => $ARR)); 
		
		/*부품삭제시 연관된 부품 모두 삭제*/
		$this->db->delete('PLM_BOM_CATE_DTL', array('BP_ID' => $ARR)); 
		
		/*프로그램 부품삭제*/
		$this->db->delete('PLM_BOM_PDT_PART', array('BP_ID' => $ARR)); 

		/*부품 수량 테이블 삭제*/
		$this->db->delete('PLM_BOM_PDT_CNT', array('BPA_GBN_ID' => $ARR)); 

		/*거래처 테이블 삭제*/
		$this->db->delete('PLM_COMP_LIST', array('PARENT_ID' => $ARR)); 
		
		/*부품삭제시 프로그램매핑 정보도 삭제*/
		$this->db->delete('PLM_BOM_PMS', array('BPMS_GBN_ID' => $ARR)); 
		
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

	function select()
    {
        $this->db->order_by('BP_ID', 'DESC');
        $query = $this->db->get($this->defListTable());
        return $query;
    }

    function excel_insert($data)
    {
        $this->db->insert_batch($this->defListTable(), $data);
    }
	
}
?>