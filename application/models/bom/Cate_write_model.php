<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cate_write_model extends CI_Model{
	
	function pageReturn(){ return 'Cate'; } //def,PLM_TYPE
	function defListTable(){ return 'PLM_BOM_CATE'; }
	function defListId(){ return 'BC_ID'; }
	function defSeqId(){ return 'CATE_'; }
	
  function __construct(){
     parent::__construct();
	}
	
	/* id 새로 따기 */
	public function get_new_id(){
		$sql_1 = " select concat( '".$this->defSeqId()."' ,ifnull( max( cast(substr(".$this->defListId()." , 6) as unsigned) ), 0 )+1 ) as new_id from ".$this->defListTable()." ";
		$query_1 = $this->db->query($sql_1);
		if ($query_1->num_rows() > 0){
		   $row = $query_1->row(); 
		   $new_id = $row->new_id;
		}
		return $new_id;
	}
	
	/* 데이터 가져오기 ( 추가추정 ) */
	public function getData($id){
		$sql = "select 
					BC_ID
					,BC_NM
					,BC_CONT
					,DATE_FORMAT(INS_DT,'%Y-%m-%d') AS INS_DT
				from ".$this->defListTable()." 
				where ".$this->defListId()." = '".$id."'";
		
		$query = $this->db->query($sql);
		return $query->row();
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
				where A.PLM_TYPE = '".strtolower($this->pageReturn())."'
				AND A.PARENT_ID = '".$id."'
				AND A.PLM_DETAIL_TYPE = 'normal'";

		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	public function get_pf_ids_pr_id($pr_id){
		
		$sql = " SELECT PF_ID FROM PLM_FILE_LIST WHERE PARENT_ID = '".$pr_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 등록 ( 추가추정 ) */
	public function insert($new_id,$searchData){
		$sql = " insert into ".$this->defListTable()."
				(    BC_ID 
					,BC_NM
					,BC_CONT
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$new_id."'
					,'".$searchData['BC_NM']."'
					,'".$searchData['BC_CONT']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
			";
		$query = $this->db->query($sql);
		return $query;
		
	}
	
	
	/* 부품등록&삭제 */
	public function del_part($BC_ID){
		$this->db->delete('PLM_BOM_CATE_DTL', array('BC_ID' => $BC_ID));
	}
	public function insert_part($BC_ID,$BP_IDS){
		$sql = " insert into PLM_BOM_CATE_DTL
				(     
					BC_ID
					,BP_ID
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					'".$BC_ID."'
					,'".$BP_IDS."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
			";
		$query = $this->db->query($sql);
		return $query;
	}
	/* 부품수량테이블 정리 */
	public function update_part_cnt($BC_ID){

		$sql1 = " insert into PLM_BOM_PDT_CNT 
				(
					 BPD_ID
					,BPA_GBN
					,BPA_GBN_ID
					,BPA_CNT
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)
				SELECT 
				(SELECT BPD_ID FROM PLM_BOM_PDT_CATE WHERE BC_ID='".$BC_ID."') AS BPD_ID,
				'".$BC_ID."' AS BPA_GBN,
				BP_ID,
				1 AS BPA_CNT,
				'".$_SESSION['userid']."' AS INS_ID,
				sysdate(),
				'".$_SESSION['userid']."' AS UDT_ID,
				sysdate()
				FROM PLM_BOM_CATE_DTL
				WHERE 1=1 and
				BC_ID='".$BC_ID."' and
				BP_ID NOT IN (SELECT BPA_GBN_ID FROM PLM_BOM_PDT_CNT WHERE BPA_GBN='".$BC_ID."')
			";
		$query1 = $this->db->query($sql1);

		$sql2 = " delete from PLM_BOM_PDT_CNT
			where 1=1
			and BPA_GBN='".$BC_ID."'
			and BPA_GBN_ID NOT IN (select BP_ID from PLM_BOM_CATE_DTL where BC_ID='".$BC_ID."')
			";
		$query2 = $this->db->query($sql2);

		return $query1*$query2;
	}
	
	
	/* 수정 ( 추가추정 ) */
	public function update($searchData){
		
		$sql = " update PLM_BOM_CATE 
				 set BC_NM			= '".$searchData['BC_NM']."'
					,BC_CONT = '".$searchData['BC_CONT']."'
					,UPD_ID				= '".$_SESSION['userid']."'
					,UPD_DT				= sysdate()
				 where BC_ID 			= '".$searchData['BC_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	//pdm 내용 수정
	public function update_pdm($searchData){
		
		$sql = " update PLM_PDM_FILE
				 set PF_NM		= '".$searchData['BP_NM']."'
					,PF_CONT	= '".$searchData['BP_CONT']."'
					,UPD_ID		= '".$_SESSION['userid']."'
					,UPD_DT		= sysdate()
					,UPD_IP 	= '".$_SERVER['REMOTE_ADDR']."'
				 where PF_ID IN ( SELECT B.PF_ID FROM PLM_FILE_LIST B WHERE B.PARENT_ID = '".$searchData[$this->defListId()]."' AND B.PLM_DETAIL_TYPE = 'normal' )
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
	
	/* 카테고리 부품 리스트 */
	function cateDtlTable(){ return 'PLM_BOM_CATE_DTL'; }
	function cateDtlQuery(){ return '
		(select BP_NM from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_NMS,
		(select BP_STD from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_STDS,
		(select BP_MTR from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_MTRS,
		(select BP_ID from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_IDS,
		BCD_ID,
		BC_ID,
		BCD_AMT,
		INS_DT'
		; 
	}
	
	function loadCateDtl($start,$limit,$sidx,$sord,$where,$detail){
    $this->db->select($this->cateDtlQuery());
    $this->db->limit($limit);
    if($where != NULL)
      $this->db->where($where,NULL,FALSE);
      $this->db->where('BC_ID',$detail);
	    $this->db->order_by($sidx,$sord);
	    $query = $this->db->get($this->cateDtlTable().' a',$limit,$start);
	    return $query->result();
  }
	
}

?>