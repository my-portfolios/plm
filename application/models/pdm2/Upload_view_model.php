<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Upload_view_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	//삭제여부확인
	public function chkDelYn($pf_id){
		$sql = "select ifnull(PF_DEL_YN,'N') as PF_DEL_YN from PLM_PDM_FILE where PF_ID = '".$pf_id."'";
		$query = $this->db->query($sql);
		return $query->row()->PF_DEL_YN;
	}
	
	//삭제
	public function del($pf_id){
		$data = array(
               'PF_DEL_YN' => 'Y',
               'UPD_ID' => $this->session->userdata('userid'),
               'UPD_DT' => date('Y-m-d H:i:s')
            );

		$this->db->where('PF_ID', $pf_id);
		$this->db->update('PLM_PDM_FILE', $data); 
	}
	
	/* 데이터 가져오기 */
	public function getData($pf_id){
		
		$sql_0= " select PLM_DETAIL_TYPE from PLM_FILE_LIST where PF_ID = '".$pf_id."' ";
		
		$query_0 = $this->db->query($sql_0);
		
		if ($query_0->num_rows() > 0){
		   $plm_detail_type = $query_0->row()->PLM_DETAIL_TYPE; 
		}else{
			$plm_detail_type = '';
		}
		$sql_view_id = null;
		// 해당 글 바로가기 에 쓸 id 가져오는건뎅 , 댓글에서 등록한 파일이랑 요구사항에서 등록한 파일이랑 가져오는 테이블이 다름 !! 
		if($plm_detail_type == 'reply_rm'){	//요구사항댓글
			$sql_view_id = ",( select b.PARENT_ID from PLM_REPLY b where b.PLM_TYPE = a.PF_INIT_ID_TYPE and b.REPLY_ID = (select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) ) VIEW_ID";
			$view_url = ", 'rm/View?id=' as VIEW_URL";
		}else if($plm_detail_type == 'reply_board'){	//게시글댓글
			$sql_view_id = ",( select b.PARENT_ID from PLM_REPLY b where b.PLM_TYPE = a.PF_INIT_ID_TYPE and b.REPLY_ID = (select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) ) VIEW_ID";
			$view_url = ", 'board/View?c_id=' as VIEW_URL";
		}else if($plm_detail_type == 'normal'){	//요구사항관리
			$sql_view_id = ",( select b.PR_ID from PLM_RM b where b.PR_ID = ( select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) ) VIEW_ID";
			$view_url = ", 'rm/View?id=' as VIEW_URL";
		}else if($plm_detail_type == 'bom_part'){	//bom관리
			$sql_view_id = ",( select b.BP_ID from PLM_BOM_PART b where b.BP_ID = ( select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) ) VIEW_ID";
			$view_url = ", 'bom/Part_write?id=' as VIEW_URL";
		}else if($plm_detail_type == 'bom_pdt'){	//bom관리
			$sql_view_id = ",( select b.BPD_ID from PLM_BOM_PDT b where b.BPD_ID = ( select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) ) VIEW_ID";
			$view_url = ", 'bom/Pdt_write?id=' as VIEW_URL";
		}else if($plm_detail_type == 'user'){	//유저관리
			$sql_view_id = ",( select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) VIEW_ID";
			$view_url = ", 'admin/User_write?id=' as VIEW_URL";
		}else if($plm_detail_type == 'board'){	//게시글
			$sql_view_id = ",( select c.PARENT_ID from PLM_FILE_LIST c where c.PF_ID = a.PF_ID ) VIEW_ID";
			$view_url = ", 'board/View?c_id=' as VIEW_URL";
		}else{
			$sql_view_id = "";
			$view_url = "";
		}
		
		$sql = "select  a.* ".$sql_view_id." " .$view_url."
				from PLM_PDM_FILE a
				where a.PF_ID = '".$pf_id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($pf_id){
		
		$sql = " select *
				from PLM_PDM_KEYWORD 
				where PF_ID = '".$pf_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* 이력 가져오기 */
	public function getVersionList($pf_id){
		
		$sql = " select * from PLM_PDM_FILE_VERSION where PF_ID = '".$pf_id."' order by PFV_ID desc ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
}
?>