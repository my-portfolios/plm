<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class BomView_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 리스트 불러오기 */
	function getData($start,$limit,$sidx,$sord,$pp_id,$where){
		if($where != NULL){
			$where = $where;
		}else{
			$where = '1=1';
		}
		$sql = "
				SELECT PART_ID, PART_NM, BP_STD, BP_MTR, BCD_AMT, GUBUN, BPD_ID, BPD_NM
				FROM (
					SELECT BB.PP_ID, PART_ID, PART_NM, BP_STD, BP_MTR, BCD_AMT, '제품' AS GUBUN, BPD_ID, concat(BPD_NM,'(',BPD_CD,')') as BPD_NM
					FROM (
						SELECT 
								 B.BP_ID AS PART_ID
								,(SELECT C.BP_NM FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS PART_NM
								,(SELECT C.BP_STD FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS BP_STD
								,(SELECT C.BP_MTR FROM PLM_BOM_PART C WHERE C.BP_ID = B.BP_ID) AS BP_MTR
								,ifnull((SELECT D.BPA_CNT FROM PLM_BOM_PDT_CNT D WHERE D.BPD_ID = A.BPD_ID AND D.BPA_GBN = A.BC_ID AND D.BPA_GBN_ID = B.BP_ID),1) AS BCD_AMT
								,A.BPD_ID
								,(SELECT B.BPD_CD FROM PLM_BOM_PDT B WHERE B.BPD_ID = A.BPD_ID) AS BPD_CD
								,(SELECT B.BPD_NM FROM PLM_BOM_PDT B WHERE B.BPD_ID = A.BPD_ID) AS BPD_NM
						FROM PLM_BOM_PDT_CATE A
							, PLM_BOM_CATE_DTL B
						WHERE A.BC_ID = B.BC_ID 
						UNION ALL 
						SELECT 
								A.BP_ID AS PART_ID
								,(SELECT B.BP_NM FROM PLM_BOM_PART B WHERE B.BP_ID = A.BP_ID) AS PART_NM
								,(SELECT B.BP_STD FROM PLM_BOM_PART B WHERE B.BP_ID = A.BP_ID) AS BP_STD
								,(SELECT B.BP_MTR FROM PLM_BOM_PART B WHERE B.BP_ID = A.BP_ID) AS BP_MTR
								,IFNULL((SELECT C.BPA_CNT FROM PLM_BOM_PDT_CNT C WHERE C.BPD_ID = A.BPD_ID AND C.BPA_GBN = 'part' AND C.BPA_GBN_ID = A.BP_ID),1) AS BP_AMT
								,A.BPD_ID
								,(SELECT B.BPD_CD FROM PLM_BOM_PDT B WHERE B.BPD_ID = A.BPD_ID) AS BPD_CD
								,(SELECT B.BPD_NM FROM PLM_BOM_PDT B WHERE B.BPD_ID = A.BPD_ID) AS BPD_NM
						FROM PLM_BOM_PDT_PART A 
					) AA
					,PLM_BOM_PMS BB
					WHERE BB.BPMS_GBN = 'PDT'
					AND BB.BPMS_GBN_ID = AA.BPD_ID
					UNION ALL
					SELECT BB.PP_ID, PART_ID, PART_NM, BP_STD, BP_MTR, BCD_AMT, GUBUN, BPD_ID, BPD_NM
					FROM (
						select 
							 (select BP_ID from PLM_BOM_PART where BP_ID = A.BP_ID ) as PART_ID
							,(select BP_NM from PLM_BOM_PART where BP_ID = A.BP_ID ) as PART_NM
							,(select BP_STD from PLM_BOM_PART where BP_ID = A.BP_ID ) as BP_STD
							,(select BP_MTR from PLM_BOM_PART where BP_ID = A.BP_ID ) as BP_MTR
							,1 as BCD_AMT
							,'카테고리' AS GUBUN
							,A.BC_ID AS BPD_ID
							,B.BC_NM AS BPD_NM
						from PLM_BOM_CATE_DTL A
							,PLM_BOM_CATE B
						WHERE A.BC_ID = B.BC_ID
					) AA
					,PLM_BOM_PMS BB
					WHERE BB.BPMS_GBN = 'CATE'
					AND BB.BPMS_GBN_ID = AA.BPD_ID
					UNION ALL
					SELECT
						 A.PP_ID
						,A.BPMS_GBN_ID AS PART_ID 
						,(select BP_NM from PLM_BOM_PART where BP_ID = A.BPMS_GBN_ID ) as PART_NM
						,(select BP_STD from PLM_BOM_PART where BP_ID = A.BPMS_GBN_ID ) as BP_STD
						,(select BP_MTR from PLM_BOM_PART where BP_ID = A.BPMS_GBN_ID ) as BP_MTR
						,IFNULL((select BPA_CNT from PLM_BOM_PDT_CNT where BPD_ID = A.PP_ID and BPA_GBN_ID = A.BPMS_GBN_ID),1) AS BCD_AMT
						,'부품' AS GUBUN
						,A.BPMS_GBN_ID AS BPD_ID
						,(select BP_NM from PLM_BOM_PART where BP_ID = A.BPMS_GBN_ID ) as BPD_NM
					FROM PLM_BOM_PMS A
					WHERE A.BPMS_GBN = 'PART'
				) Z
				WHERE Z.PP_ID = '".$pp_id."'
				and ".$where."
				ORDER BY ".$sidx." ".$sord."
				limit ".$start." , ".$limit."
			";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
}
?>