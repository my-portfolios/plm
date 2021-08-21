<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WbsView_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	/* 데이터 가져오기 */
	public function getData($pp_id){
		
		$sql = "select 
					 PP_ID
					,PP_NM
					,DATE_FORMAT(PP_ST_DAT,'%Y-%m-%d') AS PP_ST_DAT
					,DATE_FORMAT(PP_ED_DAT,'%Y-%m-%d') AS PP_ED_DAT
					,PP_CONT
					,PP_STATUS
					,INS_ID
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = INS_ID ) AS INS_NM
				from PLM_PMS where PP_ID = '".$pp_id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($pp_id){
		
		$sql = " select *
				from PLM_KEYWORD 
				where 1=1
				and PLM_TYPE = '".$this->uri->segment(1)."'
				and PARENT_ID = '".$pp_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
}

?>