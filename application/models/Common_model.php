<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Common_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	public function getTree($data){

		$sql = " select PFD_ID as 'id'
						, case when PFD_PARENT_ID = '0' then '#' else PFD_PARENT_ID end as 'parent'
						, PFD_NM as 'text'
						, 'folderImg' as 'icon'
				from PLM_PDM_FOLDER 
				where 1=1 
				and ifnull(PFD_DEL_YN,'N') = 'N'
				order by PFD_NM 
				";
				
        $query = $this->db->query($sql);
        return $query->result();
	}
	
	/* 키워드 등록 */
	public function insert_keyword($plm_type,$parent_id,$pk_nm){
		
		$sql = " insert into PLM_KEYWORD 
				 (
					 PLM_TYPE
					,PARENT_ID
					,PK_NM
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				 )values(
					 '".$plm_type."'
					,'".$parent_id."'
					,'".$pk_nm."'
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
	
	/* 키워드 삭제 */
	public function remove_keyword($parent_id){
		$sql = " delete from PLM_KEYWORD where PARENT_ID = '".$parent_id."' ";
		$query = $this->db->query($sql);
		return $query;
	}
	
	/*스킨변경*/
	public function skinChange($data){
		$this->db->where('PE_ID', $_SESSION['userid']);
		$this->db->update('PLM_EMP', array('ETC1' => $data['skin'])); 
	}
	
	/* 키워드 가져오기 */
	public function getKeywordList($parent_id,$plm_type){
		
		$sql = " select *
				from PLM_KEYWORD 
				where 1=1
				and PLM_TYPE = '".$plm_type."'
				and PARENT_ID = '".$parent_id."' ";
		
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	// pf_id 얻기 - filelist_id
	public function get_pf_id_from_file_list($filelist_id){
		
		$sql = " SELECT PF_ID FROM PLM_FILE_LIST WHERE FILELIST_ID = '".$filelist_id."' ";
		
		$query = $this->db->query($sql);
		
	    return $query->row()->PF_ID;
	}
	
	//pdm 파일 영구삭제 시 
	public function remove_pdm_file_info($pf_id){
		$this->remove_files($pf_id);
		
		$this->db->delete('PLM_PDM_FILE', array('PF_ID' => $pf_id)); 
		$this->db->delete('PLM_FA', array('PLM_TYPE' => 'pdm2','FA_ID' => $pf_id)); 
		$this->db->delete('PLM_PDM_KEYWORD', array('PF_ID' => $pf_id)); 
		$this->db->delete('PLM_PDM_EMP_VERSION', array('PF_ID' => $pf_id)); 
		$this->db->delete('PLM_PDM_FILE_VERSION', array('PF_ID' => $pf_id)); 
		$this->db->delete('PLM_PDM_KEYWORD_VERSION', array('PF_ID' => $pf_id)); 
		$this->db->delete('PLM_FILE_LIST', array('PF_ID' => $pf_id)); 
		
	}
	
	public function remove_files($pf_id){
		
		$tempFileName = $this->db->query('select PF_FILE_TEMP_NM from PLM_PDM_FILE where PF_ID = "'.$pf_id.'" ')->row()->PF_FILE_TEMP_NM;
		$tempFileNameEx = explode (".", $tempFileName);
		//버전 파일삭제
		$tempFileName2 = $this->db->query('select PF_FILE_TEMP_NM from PLM_PDM_FILE_VERSION where PF_ID = "'.$pf_id.'" ');
		
		//기본파일
		if(file_exists('./uploads/'.$tempFileNameEx[0].'.'.$tempFileNameEx[1]) ){
			unlink('./uploads/'.$tempFileNameEx[0].'.'.$tempFileNameEx[1]);
		}
		//썸네일삭제
		if(file_exists('./uploads/'.$tempFileNameEx[0].'_thumb.'.$tempFileNameEx[1])){
			unlink('./uploads/'.$tempFileNameEx[0].'_thumb.'.$tempFileNameEx[1]);
		}
		//버전파일
		if ($tempFileName2->num_rows() > 0)
		{
		   foreach ($tempFileName2->result() as $row)
		   {
				$tempFileNameEx2 = explode (".", $row->PF_FILE_TEMP_NM);
				//기본파일
				if(file_exists('./uploads/'.$tempFileNameEx2[0].'.'.$tempFileNameEx2[1]) ){
					unlink('./uploads/'.$tempFileNameEx2[0].'.'.$tempFileNameEx2[1]);
				}
				//썸네일삭제
				if(file_exists('./uploads/'.$tempFileNameEx2[0].'_thumb.'.$tempFileNameEx2[1])){
					unlink('./uploads/'.$tempFileNameEx2[0].'_thumb.'.$tempFileNameEx2[1]);
				}
		   }
		}
		
	}
	
	
	
	/* 파일 등록 */
	public function insert_file_list($parent_id,$new_pf_id,$plm_type,$plm_detail_type){
		
		$sql = " insert into PLM_FILE_LIST 
				(    PF_ID 
					,PARENT_ID
					,PLM_TYPE
					,PLM_DETAIL_TYPE
					,INS_ID
					,INS_DT
					,INS_IP
					,UPD_ID
					,UPD_DT
					,UPD_IP
				)values(
					 '".$new_pf_id."'
					,'".$parent_id."'
					,'".$plm_type."'
					,'".$plm_detail_type."'
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
	
	//파일리스트 삭제
	public function delete_file_list($filelist_id){
		
		$sql = " delete from PLM_FILE_LIST 
				 where FILELIST_ID = '".$filelist_id."' 
				";
				
		$query = $this->db->query($sql);
		return $query;
	}
	
	/*새글 24시간 이내*/
	public function new_data($table,$delyn){
		$sql = " SELECT count(*) as CNT FROM ".$table." WHERE ifnull(".$delyn.",'N') = 'N' and INS_DT > DATE_ADD(now(), INTERVAL -24 HOUR) ";
		$query = $this->db->query($sql);
		return $query->row()->CNT;
	}
	
	/*내 사진*/
	public function my_pic($data){
		$sql = " select * from PLM_PDM_FILE where PF_ID = (select PF_ID from PLM_FILE_LIST where PARENT_ID = '".$data['id']."' order by INS_DT asc limit 1) ";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	/*메세지 보내기*/
	public function msgPush($data){
		//유저 확인
		$sqlUserYn = 'select count(*) as CNT from PLM_EMP where PE_ID ="'.$data['R_ID'].'" and PE_DEL_YN is null or PE_DEL_YN != "Y" ';
		$queryUserYn = $this->db->query($sqlUserYn);

		if($queryUserYn->row()->CNT > 0 ){
			$sql = " INSERT INTO PLM_MSG (R_ID, S_ID, MSG, INS_DT,ETC1,ETC2,ETC3,ETC4,ETC5,ETC6,ETC7,ETC8,ETC9,ETC10)
								VALUES ('".$data['R_ID']."','".$data['S_ID']."','".$data['MSG']."',sysdate(),'','','','','','','','','','');";
			$query = $this->db->query($sql);
			
			$sql2 = " INSERT INTO PLM_MSG_SEND (R_ID, S_ID, MSG, INS_DT,ETC1,ETC2,ETC3,ETC4,ETC5,ETC6,ETC7,ETC8,ETC9,ETC10)
								VALUES ('".$data['R_ID']."','".$data['S_ID']."','".$data['MSG']."',sysdate(),'','','','','','','','','','');";
			$query2 = $this->db->query($sql2);
			
			return true;
		}else{
			return false;
		}
	}
	/*삭제된 유저인지 아닌지*/
	public function userYn($data){
		//유저 확인
		$sqlUserYn = 'select count(*) as CNT from PLM_EMP where PE_ID ="'.$data['id'].'" and PE_DEL_YN is null or PE_DEL_YN != "Y" ';
		$queryUserYn = $this->db->query($sqlUserYn);
		if($queryUserYn->row()->CNT > 0 ){
			return true;
		}else{
			return false;
		}
	}
	/*id로 이름가져오기*/
	public function getUserIdToNm($data){
		//유저 확인
		$sqlUserYn = 'select PE_NM,(select count(*) from PLM_EMP where PE_ID = a.PE_ID and a.PE_DEL_YN is null or a.PE_DEL_YN != "Y" ) as CNT from PLM_EMP a where PE_ID ="'.$data['id'].'" ';
		$queryUserYn = $this->db->query($sqlUserYn);
		return $queryUserYn->row()->PE_NM.'^'.$queryUserYn->row()->CNT;
	}
	/*메세지 확인여부 cnt */
	function msgViewYns(){
		$sql = "select count(*) as CNT from PLM_MSG where ETC2 = '' and R_ID = '".$_SESSION['userid']."'";
		$query = $this->db->query($sql);
		return $query->row()->CNT;
	}
	/*유저정보 가져오기*/
	function infoView($data){
		$sql = "select * from PLM_EMP where PE_ID = '".$data['id']."' ";
		$query = $this->db->query($sql);
		return $query->result();
	}
	/* 메시지 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where,$userData){	

    $this->db->select('*,(select PE_NM from PLM_EMP where PE_ID = a.S_ID) as S_ID_s');
    $this->db->limit($limit);
    if($where != NULL)
        $this->db->where($where,NULL,FALSE);
      
      $this->db->where('R_ID',$userData['id']);	
      if(isset($userData['viewYn']) && $userData['viewYn'] == 'Y'){
      	$this->db->where('id',$userData['msg_id']);		
      }
	    $this->db->order_by($sidx,$sord);
	    $query = $this->db->get('PLM_MSG a',$limit,$start);
		
    return $query->result();
  }
  /* 보낸 메시지 불러오기 */
	function getAllDataSend($start,$limit,$sidx,$sord,$where,$userData){	

    $this->db->select('*,(select PE_NM from PLM_EMP where PE_ID = a.R_ID) as R_ID_s');
    $this->db->limit($limit);
    if($where != NULL)
        $this->db->where($where,NULL,FALSE);
      
      $this->db->where('S_ID',$userData['id']);	
      if(isset($userData['viewYn']) && $userData['viewYn'] == 'Y'){
      	$this->db->where('id',$userData['msg_id']);		
      }
	    $this->db->order_by($sidx,$sord);
	    $query = $this->db->get('PLM_MSG_SEND a',$limit,$start);
		
    return $query->result();
  }

  /*선택 msg 삭제*/
	function msg_del($ARR){
		$this->db->delete('PLM_MSG', array('id' => $ARR)); 
	}
	/*선택 보낸msg 삭제*/
	function msg_del_send($ARR){
		$this->db->delete('PLM_MSG_SEND', array('id' => $ARR)); 
	}
	/*메세지 비우기*/
	function msg_all_del(){
		$this->db->delete('PLM_MSG', array('R_ID' => $_SESSION['userid'])); 
	}
	/*보낸 메세지 비우기*/
	function msg_all_del_send(){
		$this->db->delete('PLM_MSG_SEND', array('S_ID' => $_SESSION['userid'])); 
	}
	
	/*메세지 전체확인*/
	function msg_all_con(){
		$this->db->where('R_ID', $_SESSION['userid']);
		$this->db->update('PLM_MSG', array('ETC2' => 'Y')); 
	}
	
	/*메세지 확인*/
	function msg_view($data){
		$this->db->where('id', $data['msg_id']);
		$this->db->update('PLM_MSG', array('ETC2' => 'Y')); 
	}
	
}
?>