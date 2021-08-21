<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Main_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	//저장된 외부메일정보 가져오기
	function getExMailList(){
		$sql = " SELECT MC_ID , MC_NM , EMP_ID , MC_HOST , MC_U_ID FROM PLM_MAIL_CONFIG WHERE EMP_ID = '".$_SESSION['userid']."' ";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function getMailConfigData($mc_id){
		$sql = " SELECT * FROM PLM_MAIL_CONFIG WHERE MC_ID = '".$mc_id."' ";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	function exMailDel($mc_id){
		$sql = " DELETE FROM PLM_MAIL_CONFIG WHERE MC_ID = '".$mc_id."' ";
		$query = $this->db->query($sql);
		return $query;
	}
	
}
?>