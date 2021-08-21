<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Main_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 즐찾 체크 */
	function fa_check($FA_CHECK,$FA_TYPE,$FA_USER){
		$this->db->where('PLM_TYPE', $FA_TYPE);
		$this->db->where('FA_ID', $FA_CHECK);
		$this->db->where('FA_USER', $FA_USER);
		$this->db->from('PLM_FA');
		$cnt = $this->db->count_all_results();
		
		return $cnt;
	}
	
	function chkInsId($pp_id){
		$sql = "select INS_ID from PLM_PMS where PP_ID = '".$pp_id."'";
		$query = $this->db->query($sql);
		return $query->row()->INS_ID;
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
	function getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$FA_USER,$FA_TYPE,$docType){
		
		/*sub 쿼리*/
		
		$this->db->select('FA_ID');
		$this->db->where("FA_USER",$FA_USER);
		$this->db->where("PLM_TYPE",$FA_TYPE);
		$this->db->from('PLM_FA');
		$sub_query = $this->db->get_compiled_select();
		
		$this->db->select('PARENT_ID');
		$this->db->where("EMP_ID",$this->session->userdata('userid'));
		$this->db->where("PLM_TYPE",$this->uri->segment(1));
		$this->db->from('PLM_EMP_LIST');
		$sub_query1 = $this->db->get_compiled_select();

		$this->db->select("PP_ID,PP_NM,
					(select group_concat(b.PC_NM separator ',') from PLM_COMP b where  b.PC_ID in (select c.PC_ID from PLM_COMP_LIST c where c.PLM_TYPE = '".$this->uri->segment(1)."' and c.PARENT_ID = a.PP_ID )) as PC_NM,
					(select from_unixtime(substr(start,1,10))  from PLM_PMS_WBS where PP_ID = a.PP_ID and level = 0 limit 1) as PP_ST_DAT, (select from_unixtime(substr(end,1,10))  from PLM_PMS_WBS where PP_ID = a.PP_ID and level = 0 limit 1) as PP_ED_DAT,PP_CONT,PP_DEL_YN,INS_ID,(select PE_NM from PLM_EMP where PE_ID = a.INS_ID) as INS_NM,INS_DT,INS_IP,UPD_ID,UPD_DT,UPD_IP, (select count(*) from PLM_FA where PLM_TYPE = 'pms' and FA_ID = a.PP_ID and FA_USER = '".$this->session->userdata('userid')."' ) as FA_CNT ,( select progress from PLM_PMS_WBS where PP_ID = a.PP_ID and level = 0 limit 1 ) as PP_STATUS");
		
		$this->db->limit($limit);
		if($where != NULL)
			$this->db->where($where,NULL,FALSE);
			//$this->db->where("ifnull(PP_DEL_YN,'N')","N");
			
			//권한 (작성자 , 공유받은자)
			if($this->session->userdata('userauth') != 'admin'){
				$this->db->where("
					( a.INS_ID = '".$this->session->userdata('userid')."'
					or '".$this->session->userdata('userid')."' in (select b.emp_id from PLM_EMP_LIST b where b.PLM_TYPE = 'pms' and b.PARENT_ID = a.PP_ID and b.EMP_ID = '".$this->session->userdata('userid')."' ) )
				");
			}
			
			if($docType != 'trash'){ $this->db->where("ifnull(PP_DEL_YN,'N')","N"); }
			
			if($FA_SORT_STAR == 'true'){
				$this->db->where("PP_ID IN ($sub_query)");
			}
			
			if($docType != false){
			
				switch($docType){
					case "m":		//공유받은파일
						$this->db->where("PP_ID IN ($sub_query1)");
					break;
					case "trash":	//휴지통
						$this->db->where("ifnull(PP_DEL_YN,'N')","Y");
					break;
				}
				
			}

			$this->db->order_by($sidx,$sord);
			$query = $this->db->get('PLM_PMS a',$limit,$start);
		return $query->result();
	}
	
	/*삭제*/
	function del($ARR,$USER){
		
		$data = array(
		   'PP_DEL_YN' => 'Y',
		   'UPD_ID' => $USER,
		   'UPD_DT' => date('Y-m-d H:i:s')
		);

		$this->db->where('PP_ID', $ARR);
		$this->db->update('PLM_PMS', $data); 
		/* 즐찾은 그냥삭제 영구에서 지우자*/
		//$this->db->delete('PLM_FA', array('PLM_TYPE' => $this->uri->segment(1),'FA_USER' => $USER,'FA_ID' => $ARR)); 
	}
	
	/*상세 삭제
	function pms_dtl_del($ARR,$USER){
		
		$data = array(
		   'PPD_DEL_YN' => 'Y',
		   'UPD_ID' => $USER,
		   'UPD_DT' => date('Y-m-d H:i:s')
		);

		$this->db->where('PP_ID', $ARR);
		$this->db->update('PLM_PMS_WBS', $data); 
		
	}
	*/
	/* 키워드 삭제 
	function kw_del($ARR,$USER){
		
		$data = array(
		   'PK_DEL_YN' => 'Y',
		   'UPD_ID' => $USER,
		   'UPD_DT' => date('Y-m-d H:i:s')
		);
		
		$this->db->where('PLM_TYPE', $this->uri->segment(1));
		$this->db->where('PARENT_ID', $ARR);
		$this->db->update('PLM_KEYWORD', $data); 
	}
	*/
	
	
	
	/*복원*/
	function re_del($ARR,$USER){
		
		$data = array(
		   'PP_DEL_YN' => NULL,
		   'UPD_ID' => $USER,
		   'UPD_DT' => date('Y-m-d H:i:s')
		);

		$this->db->where('PP_ID', $ARR);
		$this->db->update('PLM_PMS', $data); 
	}
	
	/*상세 복원
	function pms_dtl_re_del($ARR,$USER){
		
		$data = array(
		   'PPD_DEL_YN' => NULL,
		   'UPD_ID' => $USER,
		   'UPD_DT' => date('Y-m-d H:i:s')
		);

		$this->db->where('PP_ID', $ARR);
		$this->db->update('PLM_PMS_WBS', $data); 
		
	}
	 */
	/* 키워드 복원 
	function kw_re_del($ARR,$USER){
		
		$data = array(
		   'PK_DEL_YN' => NULL,
		   'UPD_ID' => $USER,
		   'UPD_DT' => date('Y-m-d H:i:s')
		);
		
		$this->db->where('PLM_TYPE', $this->uri->segment(1));
		$this->db->where('PARENT_ID', $ARR);
		$this->db->update('PLM_KEYWORD', $data); 
		
	}
	*/
	
	
	
	/* 영구삭제 */
	public function remove($pf_id){
		
		$sql = " delete from PLM_PMS 
				 where PP_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	public function remove_pms_list($pf_id){
		
		$sql = " delete from PLM_PMS_LIST 
				 where PP_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 키워드 영구삭제 */
	public function remove_keyword($pf_id){
		
		$sql = " delete from PLM_KEYWORD 
				where PLM_TYPE = 'pms' 
				and PARENT_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	/* wbs 영구삭제 */
	public function remove_wbs($pf_id){
		
		$sql = " delete from PLM_PMS_WBS 
				where PP_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}

}
?>