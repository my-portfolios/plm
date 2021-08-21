<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_bomSearch_model extends CI_Model{
	
    function __construct(){
        parent::__construct();
    
	}
	
	
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where){

		$this->db->select('*');
		$this->db->limit($limit);
		if($where != NULL)
		
		$this->db->where($where,NULL,FALSE);
		$this->db->order_by($sidx,$sord);
		$query = $this->db->get('PLM_BOM_PART',$limit,$start);

		return $query->result();
		
	}
  
}
?>