<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mapping_write_model extends CI_Model{
	
	function pageReturn(){ return 'Mapping'; } //def,PLM_TYPE
	function defListTable(){ return 'PLM_BOM_PMS'; }
	function defListId(){ return 'BPMS_ID'; }
	function defSeqId(){ return 'PP_'; }
	
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
					PP_ID
					,PP_NM
				from PLM_PMS 
				where PP_ID = '".$id."'";
		
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	function loadPdtDtl($start,$limit,$sidx,$sord,$where,$detail){
		
		$sql = "SELECT AA.BPD_ID , AA.BC_ID , AA.BC_NM , AA.GUBUN , AA.BP_STD , AA.BP_MTR , AA.BP_AMT
				FROM (
					SELECT A.BPD_ID
							,A.BC_ID AS BC_ID
							,(SELECT B.BC_NM FROM PLM_BOM_CATE B WHERE B.BC_ID = A.BC_ID) AS BC_NM
							,'카테고리' as GUBUN
							,'' AS BP_STD
							,'' AS BP_MTR
							,'' AS BP_AMT
					FROM PLM_BOM_PDT_CATE A
					UNION ALL
					SELECT A.BPD_ID
							,A.BP_ID AS BC_ID
							,(SELECT B.BP_NM FROM PLM_BOM_PART B WHERE B.BP_ID = A.BP_ID) AS BC_NM
							,'부품' as GUBUN
							,(SELECT B.BP_STD FROM PLM_BOM_PART B WHERE B.BP_ID = A.BP_ID) AS BP_STD
							,(SELECT B.BP_MTR FROM PLM_BOM_PART B WHERE B.BP_ID = A.BP_ID) AS BP_MTR
							,IFNULL((SELECT C.BPA_CNT FROM PLM_BOM_PDT_CNT C WHERE C.BPD_ID = A.BPD_ID AND C.BPA_GBN = 'part' AND C.BPA_GBN_ID = A.BP_ID),0) AS BP_AMT
					FROM PLM_BOM_PDT_PART A
				) AA
				WHERE AA.BPD_ID = '".$detail."' ";
		/*
		$this->db->select($this->pdtDtlQuery());
		$this->db->limit($limit);
		if($where != NULL)
		$this->db->where($where,NULL,FALSE);
		$this->db->where('a.BPD_ID',$detail);
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->pdtDtlTable().' a',$limit,$start);
		*/
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	/* 제품에 대한 카테고리 리스트 
	function pdtDtlTable(){ return 'PLM_BOM_PDT_CATE'; }
	function pdtDtlQuery(){ return '
		 a.BC_ID
		,(select b.BC_NM from PLM_BOM_CATE b where b.BC_ID = a.BC_ID) as BC_NM
		,a.INS_DT'
		; 
	}
	
	function loadPdtDtl($start,$limit,$sidx,$sord,$where,$detail){
		$this->db->select($this->pdtDtlQuery());
		$this->db->limit($limit);
		if($where != NULL)
		$this->db->where($where,NULL,FALSE);
		$this->db->where('a.BPD_ID',$detail);
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->pdtDtlTable().' a',$limit,$start);
		return $query->result();
	}
	*/
	
	/* 카테고리 부품 리스트 */
	function cateDtlTable(){ return 'PLM_BOM_PMS'; }
	function cateDtlQuery(){ return '
		(select BP_NM from PLM_BOM_PART where BP_ID = a.BPMS_GBN_ID ) as BP_NMS,
		(select BP_STD from PLM_BOM_PART where BP_ID = a.BPMS_GBN_ID ) as BP_STDS,
		(select BP_MTR from PLM_BOM_PART where BP_ID = a.BPMS_GBN_ID ) as BP_MTRS,
		(select BP_ID from PLM_BOM_PART where BP_ID = a.BPMS_GBN_ID ) as BP_IDS,
		ifnull((select b.BPA_CNT 
			from PLM_BOM_PDT_CNT b 
			where 1=1
			and b.BPD_ID = a.PP_ID
			and b.BPA_GBN_ID = a.BPMS_GBN_ID
			and b.BPA_GBN = "part"
		),1) as BCD_AMT,
		INS_DT'
		; 
	}
	
	function loadCateDtl($start,$limit,$sidx,$sord,$where,$detail){
		$this->db->select($this->cateDtlQuery());
		$this->db->limit($limit);
		if($where != NULL)
		$this->db->where($where,NULL,FALSE);
		$this->db->where('a.PP_ID',$detail);
		$this->db->where('a.BPMS_GBN','PART');
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->cateDtlTable().' a',$limit,$start);
		return $query->result();
	}
	
	/* 제품 리스트 */
	function pdtTable(){ return 'PLM_BOM_PMS'; }
	function pdtQuery(){ return '
		 PP_ID
		,BPMS_GBN_ID as BPD_ID
		,(select BPD_CD from PLM_BOM_PDT where BPD_ID = BPMS_GBN_ID ) as BPD_CD
		,(select BPD_NM from PLM_BOM_PDT where BPD_ID = BPMS_GBN_ID ) as BPD_NM
		'
		; 
	}
	
	function loadPdt($start,$limit,$sidx,$sord,$where,$detail){
		$this->db->select($this->pdtQuery());
		$this->db->limit($limit);
		if($where != NULL)
		$this->db->where($where,NULL,FALSE);
		$this->db->where('PP_ID',$detail);
		$this->db->where('BPMS_GBN','PDT');
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get($this->pdtTable().' a',$limit,$start);
		return $query->result();
	}
  
  /* 카테고리 리스트 */
	function cateTable(){ return 'PLM_BOM_PMS'; }
	function cateQuery(){ return '
		(select BC_NM from PLM_BOM_CATE where BC_ID = a.BPMS_GBN_ID ) as BC_NMS,
		a.BPMS_GBN_ID as BC_ID,
		INS_DT'
		; 
	}
	
	function loadCate($start,$limit,$sidx,$sord,$where,$detail){
    $this->db->select($this->cateQuery());
    $this->db->limit($limit);
    if($where != NULL)
      $this->db->where($where,NULL,FALSE);
      $this->db->where('a.PP_ID',$detail);
	  $this->db->where('a.BPMS_GBN','CATE');
	    $this->db->order_by($sidx,$sord);
	    $query = $this->db->get($this->cateTable().' a',$limit,$start);
	    return $query->result();
  }
  
  /* 카테고리 상세 부품 리스트 */
	function cateInDtlTable(){ return 'PLM_BOM_CATE_DTL'; }
	function cateInDtlQuery(){ return '
		(select BP_ID from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_ID,
		(select BP_NM from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_NM,
		(select BP_STD from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_STD,
		(select BP_MTR from PLM_BOM_PART where BP_ID = a.BP_ID ) as BP_MTR,
		(select BCD_AMT from PLM_BOM_PART where BP_ID = a.BP_ID ) as BCD_AMT,
		(select INS_DT from PLM_BOM_PART where BP_ID = a.BP_ID ) as INS_DT'
		; 
	}
	
	function loadInDtl_cate($start,$limit,$sidx,$sord,$where,$detail){
		$this->db->select($this->cateInDtlQuery());
		$this->db->limit($limit);
		if($where != NULL)
		  $this->db->where($where,NULL,FALSE);
		  $this->db->where('BC_ID',$detail);
			$this->db->order_by($sidx,$sord);
			$query = $this->db->get($this->cateInDtlTable().' a',$limit,$start);
			return $query->result();
	}
	
	/* 제품 안에 카테고리 상세 부품 리스트 */
	function loadInDtl($start,$limit,$sidx,$sord,$where,$detail,$pdt_id){
	   
	   $sql = "SELECT B.BP_ID AS CATE_DTL_ID
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
	   
	   $query = $this->db->query($sql);
		return $query->result();
  }
	
	public function del_bom_pms($PP_ID){
		$this->db->delete('PLM_BOM_PMS', array('PP_ID' => $PP_ID));
	}
	
	public function insert_bom_pms($PP_ID,$BPMS_GBN,$BPMS_GBN_ID){
		$sql = " insert into ".$this->defListTable()."
				(     
					 PP_ID
					,BPMS_GBN
					,BPMS_GBN_ID
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$PP_ID."'
					,'".$BPMS_GBN."'
					,'".$BPMS_GBN_ID."'
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
	
}

?>