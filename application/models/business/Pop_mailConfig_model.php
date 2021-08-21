<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Pop_mailConfig_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	function getMailConfigData($mc_id){
		$sql = " SELECT * FROM PLM_MAIL_CONFIG WHERE MC_ID = '".$mc_id."' ";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	function save($searchData){
		$sql = "INSERT INTO PLM_MAIL_CONFIG
				 (
					 EMP_ID
					,MC_NM
					,MC_HOST
					,MC_U_ID
					,MC_U_PW
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				 )VALUES(
					'".$_SESSION['userid']."'
					,'".$searchData['MC_NM']."'
					,'".$searchData['MC_HOST']."'
					,'".$searchData['MC_U_ID']."'
					,'".$searchData['MC_U_PW']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
					,'".$_SESSION['userid']."'
					,sysdate()
					,'".$_SERVER['REMOTE_ADDR']."'
				 )
				";
		$query = $this->db->query($sql);
		return $query;
	}
	
}
?>