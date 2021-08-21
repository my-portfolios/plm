<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Org_view_model extends CI_Model{
	
	function defListTable(){ return 'PLM_ORG'; }
	
  function __construct(){
     parent::__construct();
	}
	
	/* 데이터 가져오기 ( 추가추정 ) */
	public function getData($id){
		if($id != ''){
			$wheres = " where ORG_ID = '".$id."'";
		}else{
			$wheres = " where ORG_YN = 'Y'";
		}
		$sql = "select 
					ORG_ID
					,ORG_NM
					,ORG_YN
					,ORG_DATA
					,DATE_FORMAT(INS_DT,'%Y-%m-%d') AS INS_DT
				from ".$this->defListTable().$wheres;
		
		$query = $this->db->query($sql);
		return $query->row();
	}
	
}

?>