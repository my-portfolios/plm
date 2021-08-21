<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Upload_view_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	/* 데이터 가져오기 */
	public function getData($pf_id){
		
		$sql = "select * from PLM_PDM_FILE where PF_ID = '".$pf_id."'";
		
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