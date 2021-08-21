<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_write_model extends CI_Model{
	
	function pageReturn(){ return 'Group'; } //def,PLM_TYPE
	function defListTable(){ return 'PLM_GROUP'; }
	function defListId(){ return 'PG_ID'; }
	function defSeqId(){ return 'PG_'; }
	
  function __construct(){
     parent::__construct();
	}
	
	// id 새로 따기 
	public function get_new_id(){
		$sql_1 = " select concat( '".$this->defSeqId()."' ,ifnull( max( cast(substr(".$this->defListId()." , 4) as unsigned) ), 0 )+1 ) as new_id from ".$this->defListTable()." ";
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
					PG_ID
					,PG_NM
					,PG_TEL
					,DATE_FORMAT(INS_DT,'%Y-%m-%d') AS INS_DT
				from ".$this->defListTable()." 
				where ".$this->defListId()." = '".$id."'";
		
		$query = $this->db->query($sql);
		return $query->row();
	}

	
	/* 등록 ( 추가추정 ) */
	public function insert($new_id, $searchData){
		$sql = " insert into ".$this->defListTable()."
				(    PG_ID
					,PG_NM
					,PG_TEL
					,INS_ID
					,INS_DT
					,UPD_ID
					,UPD_DT
				)values(
					 '".$new_id."'
					,'".$searchData['PG_NM']."'
					,'".$searchData['PG_TEL']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SESSION['userid']."'
					,sysdate()
				)
			";
		$query = $this->db->query($sql);
		return $query;
		
	}
	
	/* 수정 */
	public function update($searchData){
		
		$sql = " update ".$this->defListTable()." 
				 set PG_NM		= '".$searchData['PG_NM']."'
					,PG_TEL		= '".$searchData['PG_TEL']."'
					,UPD_ID		= '".$_SESSION['userid']."'
					,UPD_DT		= sysdate()
				 where PG_ID 	= '".$searchData['PG_ID']."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
}

?>