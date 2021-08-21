<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Main_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	/* pf_id */
	function get_pf_ids($pr_id){
		
		$sql = " select PF_ID 
				from PLM_FILE_LIST 
				where PLM_TYPE = '".$this->uri->segment(1)."'
				AND PARENT_ID = '".$pr_id."' 
				AND PLM_DETAIL_TYPE = 'normal' ";
		$q = $this->db->query($sql);
		return $q->result();
	}
	
	function chkInsId($pr_id){
		$sql = "select INS_ID from PLM_RM where PR_ID = '".$pr_id."'";
		$query = $this->db->query($sql);
		return $query->row()->INS_ID;
	}
	
	function get_reply_pf_ids($pr_id){
		$sql = " SELECT A.PF_ID
				 FROM PLM_FILE_LIST A
				 WHERE A.PARENT_ID IN ( SELECT B.REPLY_ID FROM PLM_REPLY B WHERE B.PARENT_ID = '".$pr_id."' )
				 AND A.PLM_DETAIL_TYPE = 'reply_rm' ";
		$q = $this->db->query($sql);
		return $q->result();
	}
	
	/* 영구삭제 */
	public function pdm_remove($pf_id){
		
		$sql = " delete from PLM_PDM_FILE 
				 where PF_ID = '".$pf_id."'
				";
		
		$query = $this->db->query($sql);
		/* 즐찾은 그냥삭제*/
		$this->db->delete('PLM_FA', array('PLM_TYPE' => 'pdm2','FA_ID' => $pf_id)); 
		return $query;
	}
	
	/*삭제
	function pdm_del($pf_id,$USER){
		
		$data = array(
		   'PF_DEL_YN' => 'Y',
		   'UPD_ID' => $USER,
		   'UPD_DT' => date('Y-m-d H:i:s')
		);
		
		$this->db->where('PF_ID', $pf_id);
		$this->db->update('PLM_PDM_FILE', $data); 
		
	}
	*/
	/*삭제 & 복원*/
	function del($ARR,$USER,$REDEL){
		//리스트 삭제
		$data = array(
               'PR_DEL_YN' => $REDEL,
               'UPD_ID' => $USER,
               'UPD_DT' => date('Y-m-d H:i:s')
            );

		$this->db->where('PR_ID', $ARR);
		$this->db->update('PLM_RM', $data); 
	}
	
	/* 키워드 영구삭제 */
	public function remove_pdm_keyword($pf_id){
		
		$sql = " delete from PLM_PDM_KEYWORD 
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/*영구삭제*/
	function p_del($ARR){
		
		//리스트 삭제
		$this->db->delete('PLM_RM', array('PR_ID' => $ARR)); 
		
		//파일삭제
		$this->db->delete('PLM_FILE_LIST', array('PARENT_ID' => $ARR)); 
		
		//리플파일삭제	
		$this->db->where("PLM_DETAIL_TYPE = 'reply_rm' AND PARENT_ID IN ( select a.REPLY_ID from PLM_REPLY a where a.PARENT_ID = '".$ARR."' )"); 
		$this->db->delete('PLM_FILE_LIST'); 
		
		//리플삭제
		$this->db->delete('PLM_REPLY', array('PARENT_ID' => $ARR)); 
		
		//프로젝트 리스트 삭제
		$this->db->delete('PLM_PMS_LIST', array('PARENT_ID' => $ARR)); 
		
		//담당자 리스트 삭제
		$this->db->delete('PLM_EMP_LIST', array('PARENT_ID' => $ARR)); 
		
		/* 즐찾은 그냥삭제 영구에서 지우자*/
		$this->db->delete('PLM_FA', array('FA_ID' => $ARR)); 
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
	/* 즐찾 체크 */
	function fa_check($FA_CHECK,$FA_TYPE,$FA_USER){
		$this->db->where('PLM_TYPE', $FA_TYPE);
		$this->db->where('FA_ID', $FA_CHECK);
		$this->db->where('FA_USER', $FA_USER);
		$this->db->from('PLM_FA');
		$cnt = $this->db->count_all_results();
		
		return $cnt;
	}
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$TAB,$FA_USER,$FA_TYPE,$docType){
		
		/*sub 쿼리*/
		/*
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
		
    $this->db->select('(select count(*) from PLM_FA1 where PLM_TYPE = "rm" and FA_ID = a.PR_ID and FA_USER = "'.$this->session->userdata('userid').'" ) as FA_CNT,
						( 
							select group_concat(a.PC_NM separator ",")
							from PLM_COMP a
							where a.PC_ID in (
													select b.PC_ID
													from PLM_COMP_LIST b
													where b.PLM_TYPE = "pms"
													and b.PARENT_ID in (
																				select c.PP_ID
																				from PLM_PMS_LIST c
																				where c.PP_ID = b.PARENT_ID
																				and c.PLM_TYPE = "'.$this->uri->segment(1).'"
																				and c.PARENT_ID = a.PR_ID
																				)
												)
						 ) as PC_NM,
					PR_ID,PR_TITLE,PR_HOPE_END_DAT,PR_STATUS,PR_DEL_YN,INS_ID,(select PE_NM from PLM_EMP where PE_ID = a.INS_ID) as INS_NM,INS_DT,INS_IP,UPD_ID,UPD_DT,UPD_IP
					');
		
		
    $this->db->limit($limit);
    if($where != NULL)
        $this->db->where($where,NULL,FALSE);
      //$this->db->where("ifnull(PR_DEL_YN,'N')","N");
		
		//권한 (작성자 , 공유받은자)
		if($this->session->userdata('userauth') != 'admin'){
			$this->db->where("
				( a.INS_ID = '".$this->session->userdata('userid')."'
				or '".$this->session->userdata('userid')."' in (select b.emp_id from PLM_EMP_LIST b where b.PLM_TYPE = 'rm' and b.PARENT_ID = a.PR_ID and b.EMP_ID = '".$this->session->userdata('userid')."' ) )
			");
		}
		
	  
	   	if($docType != 'trash'){ $this->db->where("ifnull(PR_DEL_YN,'N')","N"); }
        if($TAB == '1'){
        	$this->db->where("PR_STATUS","1");
      	}else if($TAB == '2'){
      		$this->db->where("PR_STATUS","2");
      	}else if($TAB == '3'){
      		$this->db->where("PR_STATUS","3");
      	}else if($TAB == '4'){
      		$this->db->where("PR_STATUS","4");
      	}
      	
        if($FA_SORT_STAR == 'true'){
        	$this->db->where("PR_ID IN ($sub_query)");
      	}
		
		
      	
      	if($docType != false){
				
				switch($docType){
						case "m":		//공유받은파일
							$this->db->where("PR_ID IN ($sub_query1)");
						break;
						case "trash":	//휴지통
							$this->db->where("ifnull(PR_DEL_YN,'N')","Y");
						break;
					}
					
				}
			
			
				
	    $this->db->order_by($sidx,$sord);
	    */
	    //old
	    //$query = $this->db->get('PLM_RM a',$limit,$start);
	    //new
	    if($where != NULL)
	    	$where = 'and '.$where." ";
	    
	    if($this->session->userdata('userauth') == 'emp'){
	    	$notAdmin = " and ( a.INS_ID = '".$this->session->userdata('userid')."' or '".$this->session->userdata('userid')."' in (select b.emp_id from PLM_EMP_LIST b where b.PLM_TYPE = 'rm' and b.PARENT_ID = a.PR_ID and b.EMP_ID = '".$this->session->userdata('userid')."' ) ) ";
	    }else{
	    	$notAdmin = '';
	    }
	    
	    if($docType != 'trash'){
	    	$notTrash = " and ifnull(PR_DEL_YN,'N') = 'N' ";
	    }else{
	    	$notTrash = '';
	    }
	    if($FA_SORT_STAR == 'true'){
	    	$fa_in = ' and PR_ID IN(select FA_ID from PLM_FA where FA_USER = "'.$FA_USER.'" and PLM_TYPE = "'.$FA_TYPE.'") ';
	    }else{
	    	$fa_in = '';
	    }
	    if($docType != false){
				switch($docType){
						case "m":		//공유받은파일
							$doc = ' and PR_ID IN (select PARENT_ID from PLM_EMP_LIST where EMP_ID = "'.$this->session->userdata('userid').'" and PLM_TYPE = "'.$this->uri->segment(1).'") ';
						break;
						case "trash":	//휴지통
							$doc = " and ifnull(PR_DEL_YN,'N') = 'Y' ";
						break;
						default:
							$doc = '';
					}
			}else{
				$doc = '';
			}
			if($TAB == '1'){
      	$tabs = " AND PR_STATUS = '1' ";
    	}else if($TAB == '2'){
    		$tabs = " AND PR_STATUS = '2' ";
    	}else if($TAB == '3'){
    		$tabs = " AND PR_STATUS = '3' ";
    	}else if($TAB == '4'){
    		$tabs = " AND PR_STATUS = '4' ";
    	}else{
    		$tabs = '';
    	}
    	if($this->session->userdata('userauth') == 'user'){
    		$compArr  = $_SESSION['comp'];
				$complike = "";
				if($compArr != ''){
					$compArrR = explode(',',$compArr);
					$c = 0;
					foreach($compArrR as $i=>$v){
						if($c == 0){
					  	$complike .= " and comp like '%".$v."%' ";
						}else{
							$complike .= " or comp like '%".$v."%' ";
						}
					  $c++;
					}
				}else{
					 	$complike .= " and comp like '' ";
				}
    	}else{
    		$complike = '';
    	}
	    $sql_x = "
	    select * from (
	    SELECT 
			(select count(*) from PLM_FA where PLM_TYPE = 'rm' and FA_ID = a.PR_ID and FA_USER = '".$this->session->userdata('userid')."' ) as FA_CNT, 
			( select group_concat(a.PC_NM separator ', ') from PLM_COMP a where a.PC_ID in ( select b.PC_ID from PLM_COMP_LIST b where b.PLM_TYPE = 'pms' and b.PARENT_ID in ( select c.PP_ID from PLM_PMS_LIST c where c.PP_ID = b.PARENT_ID and c.PLM_TYPE = 'rm' and c.PARENT_ID = a.PR_ID ) ) ) as PC_NM, 
			( select group_concat(a.PC_ID separator ', ') from PLM_COMP a where a.PC_ID in ( select b.PC_ID from PLM_COMP_LIST b where b.PLM_TYPE = 'pms' and b.PARENT_ID in ( select c.PP_ID from PLM_PMS_LIST c where c.PP_ID = b.PARENT_ID and c.PLM_TYPE = 'rm' and c.PARENT_ID = a.PR_ID ) ) ) as comp,
			PR_ID, 
			PR_TITLE, 
			PR_HOPE_END_DAT, 
			PR_STATUS, 
			PR_DEL_YN, 
			INS_ID, 
			(select PE_NM from PLM_EMP where PE_ID = a.INS_ID) as INS_NM, 
			INS_DT, 
			INS_IP,
			 UPD_ID, 
			 UPD_DT, 
			 UPD_IP 
			 FROM PLM_RM a 
			 WHERE 1=1 
			 ".$where."
			 ".$notAdmin."
			 ".$notTrash." 
			 ".$fa_in."
			 ".$doc."
			 ".$tabs."
			 ORDER BY ".$sidx." ".$sord." 
			 LIMIT ".$start.", ".$limit."
			 ) PLM_RM
			 where 1=1
			 ".$complike."
	    ";
	    $query = $this->db->query($sql_x);
		
    return $query->result();
  }
	
}
?>