<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_formatSearch_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
	}
	
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where){
		
		$this->db->select('a.PF_ID , a.PF_NM, a.PF_CONT, a.INS_ID, (select b.PE_NM from PLM_EMP b where a.INS_ID = b.PE_ID) as INS_NM , a.INS_DT');
		$this->db->limit($limit);
		if($where != NULL)
		
		$this->db->where($where,NULL,FALSE);
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get('PLM_FORMAT a',$limit,$start);
		
		return $query->result();
		
	}
	
}
?>