<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdt_write_model extends CI_Model{
	
	function pageReturn(){ return 'Pdt'; } //def,PLM_TYPE
	function defListTable(){ return 'PLM_BOM_PDT'; }
	function defListId(){ return 'BPD_ID'; }
	function defSeqId(){ return 'PDT_'; }
	
  function __construct(){
     parent::__construct();
	}
	
	/* id 새로 따기 */
	public function get_new_id(){
		$sql_1 = " select concat( '".$this->defSeqId()."' ,ifnull( max( cast(substr(".$this->defListId()." , 5) as unsigned) ), 0 )+1 ) as new_id from ".$this->defListTable()." ";
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
					BPD_ID
					,BPD_CD
					,BPD_NM
					,BPD_CONT
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
				AND A.PLM_DETAIL_TYPE = 'bom_pdt'";

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
		
		// ' escape
		$searchData['BPD_NM'] = addslashes($searchData['BPD_NM']);

		$sql = " insert into ".$this->defListTable()."
				(    BPD_ID 
					,BPD_CD
					,BPD_NM
					,BPD_CONT
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$new_id."'
					,'".$searchData['BPD_CD']."'
					,'".$searchData['BPD_NM']."'
					,'".$searchData['BPD_CONT']."'
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
	public function del_part($BPD_ID){
		$this->db->delete('PLM_BOM_PDT_PART', array('BPD_ID' => $BPD_ID));
	}
	public function insert_part($BPD_ID,$BPDD_ID,$BP_IDS){
		$sql = " insert into PLM_BOM_PDT_PART
				(     
					BPD_ID
					,BP_ID
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$BPD_ID."'
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
	
	
	/* 카테고리 등록&삭제 */
	public function del_cate($BPD_ID){
		$this->db->delete('PLM_BOM_PDT_CATE', array('BPD_ID' => $BPD_ID));
	}
	public function insert_cate($BPD_ID,$BC_ID){
		$sql = " insert into PLM_BOM_PDT_CATE
				(     
					BPD_ID
					,BC_ID
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					'".$BPD_ID."'
					,'".$BC_ID."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
			";
		$query = $this->db->query($sql);
		return $query;
	}
	
	//부품 , 카테고리 수량 저장
	public function insert_cnt($bpd_id,$bpa_gbn,$bpa_gbn_id,$bpa_cnt){
		
		$sql = " insert into PLM_BOM_PDT_CNT 
				(
					 BPD_ID
					,BPA_GBN
					,BPA_GBN_ID
					,BPA_CNT
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$bpd_id."'
					,'".$bpa_gbn."'
					,'".$bpa_gbn_id."'
					,'".$bpa_cnt."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
			";
		$query = $this->db->query($sql);
		return $query;
	}
	
	//부품 , 카테고리 수량 삭제
	public function del_cnt($bpd_id){
		
		$sql = " delete from PLM_BOM_PDT_CNT where BPD_ID = '".$bpd_id."' ";
		$query = $this->db->query($sql);
		return $query;
		
	}
	
	/* 수정 ( 추가추정 ) */
	public function update($searchData){
		
		$sql = " update PLM_BOM_PDT 
				 set BPD_NM			= '".$searchData['BPD_NM']."'
				 	,BPD_CD = '".$searchData['BPD_CD']."'
					,BPD_CONT = '".$searchData['BPD_CONT']."'
					,UPD_ID				= '".$_SESSION['userid']."'
					,UPD_DT				= sysdate()
				 where BPD_ID 			= '".$searchData['BPD_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	//pdm 내용 수정
	public function update_pdm($searchData){
		
		$sql = " update PLM_PDM_FILE
				 set PF_NM		= '".$searchData['BPD_NM']."'
					,PF_CONT	= '".$searchData['BPD_CONT']."'
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
	function cateDtlTable(){ return 'PLM_BOM_PDT_PART'; }
	function cateDtlQuery(){ return '
		(select BP_NM from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_NMS,
		(select BP_STD from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_STDS,
		(select BP_MTR from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_MTRS,
		(select BP_ID from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_IDS,
		a.BPDD_ID,
		a.BPD_ID,
		ifnull((select b.BPA_CNT 
			from PLM_BOM_PDT_CNT b 
			where 1=1
			and b.BPD_ID = a.BPD_ID
			and b.BPA_GBN_ID = a.BP_ID
			and b.BPA_GBN = "part"
		),1) as BPD_AMT,
		INS_DT'
		; 
	}
	
	function loadCateDtl($start,$limit,$sidx,$sord,$where,$detail){
    $this->db->select($this->cateDtlQuery());
    $this->db->limit($limit);
    if($where != NULL)
      $this->db->where($where,NULL,FALSE);
      $this->db->where('BPD_ID',$detail);
	    $this->db->order_by($sidx,$sord);
	    $query = $this->db->get($this->cateDtlTable().' a',$limit,$start);
	    return $query->result();
  }
  
  /* 카테고리 리스트 */
	function cateTable(){ return 'PLM_BOM_PDT_CATE'; }
	function cateQuery(){ return '
		(select BC_NM from PLM_BOM_CATE where BC_ID = a.BC_ID ) as BC_NMS,
		BC_ID,
		INS_DT'
		; 
	}
	
	function loadCate($start,$limit,$sidx,$sord,$where,$detail){
    $this->db->select($this->cateQuery());
    $this->db->limit($limit);
    if($where != NULL)
      $this->db->where($where,NULL,FALSE);
      $this->db->where('BPD_ID',$detail);
	    $this->db->order_by($sidx,$sord);
	    $query = $this->db->get($this->cateTable().' a',$limit,$start);
	    return $query->result();
  }
  
	/* 카테고리 상세 부품 리스트_저장된게 없을경우 */
	function cateInDtlTable(){ return 'PLM_BOM_CATE_DTL'; }
	function cateInDtlQuery(){ return '
		A.BCD_ID as CATE_DTL_ID,
		(select BP_ID from PLM_BOM_PART where BP_ID = A.BP_ID ) as BP_ID,
		(select BP_NM from PLM_BOM_PART where BP_ID = A.BP_ID ) as BP_NM,
		(select BP_STD from PLM_BOM_PART where BP_ID = A.BP_ID ) as BP_STD,
		(select BP_MTR from PLM_BOM_PART where BP_ID = A.BP_ID ) as BP_MTR,
		0 as BCD_AMT,
		(select INS_DT from PLM_BOM_PART where BP_ID = A.BP_ID ) as INS_DT'
		; 
	}
	
	function loadInDtl_none($start,$limit,$sidx,$sord,$where,$detail){
		$this->db->select($this->cateInDtlQuery());
		$this->db->limit($limit);
		if($where != NULL)
		  $this->db->where($where,NULL,FALSE);
		  $this->db->where('BC_ID',$detail);
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->cateInDtlTable().' A',$limit,$start);
		return $query->result();
  }
   
   
   function loadInDtl($start,$limit,$sidx,$sord,$where,$detail,$pdt_id){
	   
	   $sql = "SELECT B.BCD_ID AS CATE_DTL_ID
					,B.BC_ID AS BC_ID
					,B.BP_ID AS BP_ID
					,(SELECT C.BP_NM FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS BP_NM
					,(SELECT C.BP_STD FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS BP_STD
					,(SELECT C.BP_MTR FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS BP_MTR
					,ifnull((SELECT D.BPA_CNT FROM PLM_BOM_PDT_CNT D WHERE D.BPD_ID = A.BPD_ID AND D.BPA_GBN = 'cate' AND D.BPA_GBN_ID = B.BCD_ID),0) AS BCD_AMT
					,A.INS_DT AS INS_DT
			FROM PLM_BOM_PDT_CATE A
				, PLM_BOM_CATE_DTL B
			WHERE A.BPD_ID = '".$pdt_id."'
			AND A.BC_ID = B.BC_ID
			AND B.BC_ID = '".$detail."'
		";
		
		$sql2 = "SELECT B.BP_ID AS CATE_DTL_ID
					,B.BC_ID AS BC_ID
					,B.BP_ID AS BP_ID
					,(SELECT C.BP_NM FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS BP_NM
					,(SELECT C.BP_STD FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS BP_STD
					,(SELECT C.BP_MTR FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS BP_MTR
					,ifnull((SELECT D.BPA_CNT FROM PLM_BOM_PDT_CNT D WHERE D.BPD_ID = A.BPD_ID AND D.BPA_GBN = '".$detail."' AND D.BPA_GBN_ID = B.BP_ID),1) AS BCD_AMT
					,A.INS_DT AS INS_DT
			FROM PLM_BOM_PDT_CATE A
				, PLM_BOM_CATE_DTL B
			WHERE A.BPD_ID = '".$pdt_id."'
			AND A.BC_ID = B.BC_ID
			AND B.BC_ID = '".$detail."'
		";
	   
	   $query = $this->db->query($sql2);
		return $query->result();
  }
	
}

?>