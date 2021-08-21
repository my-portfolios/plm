<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Main_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	
	/* 리스트 불러오기 */
	public function getChildren($searchData){
		
		$sql = "SELECT PF_ID
						,PFD_ID
						,PF_NM
						,(select group_concat(PK_NM) from PLM_PDM_KEYWORD where PF_ID = t.PF_ID) as KEYWORD
						,PF_DEL_YN
						,PF_PATH
						,PP_ID
						,PF_CONT
						,PF_FILE_REAL_NM
						,PF_FILE_TEMP_NM
						,PF_FILE_SIZE
						,PF_FILE_EXT
						,INS_ID
						,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = t.INS_ID ) AS INS_NM
						,INS_DT
						,INS_IP
						,UPD_ID
						,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = t.UPD_ID ) AS UPD_NM
						,UPD_DT
						,UPD_IP
				FROM PLM_PDM_FILE t
				WHERE PFD_ID = '".$searchData['parent_id']."' 
				and ifnull(PF_DEL_YN,'N') = 'N'
				and ifnull(PF_NOW_ID_TYPE,'') = 'pdm'
				order by INS_DT DESC
				limit 20 offset ".($searchData['limit'])."
				";
				//limit 100 offset ".$searchData['limit']."
				//limit ".($searchData['limit']).", ".($searchData['limit']+1)."
			
		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
	
	/* */
	public function getDataFix($table,$column,$keyColumn,$id){
		
		$sql = "select ".$column." from ".$table." where ".$keyColumn." = '".$id."'";
		
		$query = $this->db->query($sql);
		
		return $query->row();
		
	}
	
	/* 선택복원 */
	public function bokwon($pf_id){
		
		$sql = " update PLM_PDM_FILE 
				set PF_DEL_YN = NULL
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 담당자 복원 */
	public function bokwon_pdm_emp($pf_id){
		
		$sql = " update PLM_PDM_EMP 
				set PE_DEL_YN = NULL
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 키워드 복원 */
	public function bokwon_pdm_keyword($pf_id){
		
		$sql = " update PLM_PDM_KEYWORD 
				set PK_DEL_YN = NULL
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 선택삭제 */
	public function delete($pf_id){
		
		$sql = " update PLM_PDM_FILE 
				set PF_DEL_YN = 'Y'
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	/* 담당자 삭제 */
	public function delete_pdm_emp($pf_id){
		
		$sql = " update PLM_PDM_EMP 
				set PE_DEL_YN = 'Y'
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 키워드 삭제 */
	public function delete_pdm_keyword($pf_id){
		
		$sql = " update PLM_PDM_KEYWORD 
				set PK_DEL_YN = 'Y'
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 영구삭제 */
	public function remove($pf_id){
		
		$sql = " delete from PLM_PDM_FILE 
				 where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
	}
	
	/* 담당자 영구삭제 */
	public function remove_pdm_emp($pf_id){
		
		$sql = " delete from PLM_PDM_EMP 
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 키워드 영구삭제 */
	public function remove_pdm_keyword($pf_id){
		
		$sql = " delete from PLM_PDM_KEYWORD 
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 검색 */
	public function search($searchData){
		if($searchData['setype'] == 'false'){
			$setype = ' WHERE 1=1 ';
		}else{
			$setype = " WHERE PFD_ID = '".$searchData['setype_local']."' ";
		}
		
		$q= '';
		$img = '"bmp","rle","dib","ico","jpeg","jpg","gif","png","tif","tiff","raw"';
		$mov = '"asf","avi","flv","mkv","mov","mpeg","mpg","ps","ts","tp","mts","m2ts","tod","mp4","m4v","3gp","skm","k3g","lmp4","rm","wmv","webm","ogv"';
		if(isset($searchData['detailYn']) and $searchData['detailYn'] == 'Y'){
			if(isset($searchData['docType'])){
				switch ($searchData['docType']) { 	
					case 'A'://전체 		  
					$q .= '';
					break; 	
					case 'M'://공유파일 		  
					$q .= ' and PF_ID in (SELECT PARENT_ID FROM PLM_EMP_LIST WHERE EMP_ID = "'.$this->session->userdata('userid').'")';
					break; 
					case 'IMAGE'://이미지
					$q .= ' and PF_FILE_EXT in ('.$img.') ';
					break;
					case 'MOVIE'://동영상
					$q .= ' and PF_FILE_EXT in ('.$mov.') ';
					break;
					case 'TXT'://문서
					$q .= ' and PF_FILE_EXT not in ('.$img.','.$mov.') ';
					break;
					case 'RM'://요구사항
					$q .= ' and PF_INIT_ID_TYPE = "rm" ';
					break;
					case 'TRASH'://휴지통
					$q .= ' and ifnull(PF_DEL_YN,"N") = "Y" ';
					break;
					default: 		  // default 
					$q .= '';
				}
			}
			if($searchData['sdate'] != '' and $searchData['edate'] != '' ){
				$q .= ' and INS_DT between "'.$searchData['sdate'].' 00:00:00" and "'.$searchData['edate'].' 23:59:59" ';
			}
			if($searchData['keyword'] != ''){
				$q .= ' and PF_ID in (SELECT PF_ID FROM PLM_PDM_KEYWORD WHERE PK_NM like "%'.$searchData['keyword'].'%")';
			}
		}
		$sql = "SELECT PF_ID
					,PFD_ID
					,PF_NM
					,(select group_concat(PK_NM) from PLM_PDM_KEYWORD where PF_ID = t.PF_ID) as KEYWORD
					,PF_DEL_YN
					,PF_PATH
					,PP_ID
					,PF_CONT
					,PF_FILE_REAL_NM
					,PF_FILE_TEMP_NM
					,PF_FILE_SIZE
					,PF_FILE_EXT
					,INS_ID
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = t.INS_ID ) AS INS_NM
					,INS_DT
					,INS_IP
					,UPD_ID
					,( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = t.UPD_ID ) AS UPD_NM
					,UPD_DT
					,UPD_IP
			FROM PLM_PDM_FILE t
			".$setype."
			".$q;
			
		if($searchData['docType'] != 'TRASH'){
			$sql .=	" and ifnull(PF_DEL_YN,'N') = 'N' ";
		}
				
		if($searchData['searchType'] == 'path'){			/* 분류 */
			$sql .= " and PF_PATH like '%".$searchData['searchText']."%' ";
		}else if($searchData['searchType'] == 'file_nm'){	/* 파일명 */
			$sql .= " and PF_NM like '%".$searchData['searchText']."%' ";
		}else if($searchData['searchType'] == 'file_ext'){	/* 종류,확장자 */
			$sql .= " and PF_FILE_EXT like '%".$searchData['searchText']."%' ";
		}else if($searchData['searchType'] == 'ins_id'){	/* 아이디 */
			$sql .= " and INS_ID like '%".$searchData['searchText']."%' ";
		}else if($searchData['searchType'] == 'ins_nm'){	/* 작성자 */
			$sql .= " and ( SELECT PE_NM FROM PLM_EMP WHERE PE_ID = t.INS_ID ) like '%".$searchData['searchText']."%' ";
		}
				
		$sql .=	"order by INS_DT DESC
				limit 20 offset ".($searchData['limit'])."
				";

		$query = $this->db->query($sql);
		
		return $query->result();
		
	}
}
?>