<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Main_model extends CI_Model{
    function __construct(){
        parent::__construct();
    
	}
	
	
	
	public function test1(){
		
		
		for($i = 0; $i < 150000 ; $i++){
		
			$sql = "INSERT INTO PLM_PDM_FILE (PF_ID, PFD_ID, PF_NM, PF_INIT_ID_TYPE, PF_NOW_ID_TYPE, PF_DEL_YN, PF_PATH, PP_ID, PF_CONT, PF_FILE_REAL_NM, PF_FILE_TEMP_NM, PF_FILE_SIZE, PF_FILE_EXT, INS_ID, INS_DT, INS_IP, UPD_ID, UPD_DT, UPD_IP) VALUES
		('PP_".$i."', 'PLM', '제목  ".$i."', 'pdm2', 'pdm2', NULL, '', 'P01', '<p>222</p>', '나도한글.jpg', 'tmpphpKU4X7I.jpg', '466700', 'jpg', 'admin', '2018-06-12 17:23:40', '192.168.24.117', NULL, NULL, NULL);";
			
			$query = $this->db->query($sql);
		
		}
	}

	//초기데이터 삽입
	public function init(){
		
		$query1 = $this->db->query('SELECT * FROM PLM_PDM_FOLDER');
		$numrows1 = $query1->num_rows();
		$query2 = $this->db->query("SELECT * FROM PLM_PDM_FOLDER WHERE PFD_PARENT_ID='#'");
		$numrows2 = $query2->num_rows();

		if($numrows1==0 && $numrows2==0) {
			$sql = "INSERT INTO PLM_PDM_FOLDER (PFD_ID, PFD_PARENT_ID, PFD_NM, PFD_DEL_YN, INS_ID, INS_DT, INS_IP, UPD_ID, UPD_DT, UPD_IP) VALUES ('PLM', '#', 'PDM', NULL, 'admin', NULL, '".$_SERVER['REMOTE_ADDR']."', NULL, NULL, NULL);";
			$query = $this->db->query($sql);
		}

	}
	
	
	function chkInsId($pf_id){
		$sql = "select INS_ID from PLM_PDM_FILE where PF_ID = '".$pf_id."'";
		$query = $this->db->query($sql);
		return $query->row()->INS_ID;
	}

	/*삭제*/
	function del($ARR,$USER){

		$data = array(
               'PF_DEL_YN' => 'Y',
               'UPD_ID' => $USER,
               'UPD_DT' => date('Y-m-d H:i:s')
            );

		$this->db->where('PF_ID', $ARR);
		$this->db->update('PLM_PDM_FILE', $data); 
	}
	
	/* 즐찾 */
	function faYn($FA_TYPE,$FA_VAL,$FA_USER){
		
		$this->db->where('PLM_TYPE', $FA_TYPE);
		$this->db->where('FA_ID', $FA_VAL);
		$this->db->where('FA_USER', $FA_USER);
		$this->db->from('PLM_FA');
		$cnt = $this->db->count_all_results();
		
		if($cnt > 0){
			$this->db->delete('PLM_FA', array('PLM_TYPE' => $FA_TYPE,'FA_ID' => $FA_VAL,'FA_USER' => $FA_USER)); 
		}else{
			$data = array(
		   'PLM_TYPE' => $FA_TYPE ,
		   'FA_ID' => $FA_VAL,
		   'FA_USER' => $FA_USER
			);
			$this->db->insert('PLM_FA', $data); 
		}
	}
	/* 즐찾 체크 */
	function fa_check($FA_CHECK,$FA_TYPE,$FA_USER){
		$this->db->where('PLM_TYPE', $FA_TYPE);
		$this->db->where('FA_ID', $FA_CHECK);
		$this->db->where('FA_USER', $FA_USER);
		$this->db->from('PLM_FA');
		$cnt = $this->db->count_all_results();
		
		return $cnt;
	}
	/* 리스트 불러오기 */
	function getAllData($start,$limit,$sidx,$sord,$where,$FA_SORT_STAR,$TAB,$FA_USER,$FA_TYPE,$treeid,$_search1,$docType){
		
		/*sub 쿼리*/

  	$this->db->select('FA_ID');
  	$this->db->where("FA_USER",$FA_USER);
		$this->db->where("PLM_TYPE",$FA_TYPE);
  	$this->db->from('PLM_FA');
  	$sub_query = $this->db->get_compiled_select();
	
	
	$this->db->select('PARENT_ID');
	$this->db->where("EMP_ID",$this->session->userdata('userid'));
	$this->db->where("PLM_TYPE",$this->uri->segment(1));
	$this->db->from('PLM_EMP_LIST');
	$sub_query1 = $this->db->get_compiled_select();
	
	
	if(isset($_POST['detailSearch']) && $_POST['detailSearch'] != 'n'){
		
		if($_POST['keyword'] !=''){
			$this->db->select('PF_ID');
			$this->db->like("PK_NM",urldecode($_POST['keyword']),'both');
			$this->db->from('PLM_PDM_KEYWORD');
			$sub_query_kw = $this->db->get_compiled_select();
		}
		if($_POST['insnm'] !=''){
			$this->db->select('PE_ID');
			$this->db->like("PE_NM",urldecode($_POST['insnm']),'both');
			$this->db->from('PLM_EMP');
			$sub_query_insnm = $this->db->get_compiled_select();
		}
		
	}
	
    $this->db->select('(select count(*) from PLM_FA where PLM_TYPE = "'.$this->uri->segment(1).'" and FA_ID = t.PF_ID and FA_USER = "'.$this->session->userdata('userid').'" ) as FA_CNT,
					( 
						select group_concat(a.PC_NM separator ",")
						from PLM_COMP a
						where a.PC_ID in (
									select b.PC_ID
									from PLM_COMP_LIST b
									where b.PLM_TYPE = "pms"
									and b.PARENT_ID in (
														select c.PP_ID
														from PLM_PMS_LIST c
														where c.PP_ID = b.PARENT_ID
														and c.PLM_TYPE = "'.$this->uri->segment(1).'"
														and c.PARENT_ID = t.PF_ID
														)
								)
					) PC_NM,
		t.PF_ID,t.PFD_ID,t.PF_NM,t.PF_PATH,t.PF_FILE_SIZE,t.PF_FILE_EXT,t.INS_ID,(select pe_nm from PLM_EMP where pe_id = t.ins_id ) as INS_NM,t.INS_DT,t.UPD_ID,t.UPD_DT,t.PF_FILE_TEMP_NM,t.PF_FILE_REAL_NM,(select group_concat(PK_NM) from PLM_PDM_KEYWORD where PF_ID = t.PF_ID) as KEYWORD');
    //$this->db->select('PF_ID,PFD_ID,PF_NM,PF_PATH,PF_FILE_SIZE,PF_FILE_EXT,INS_ID,(select pe_nm from PLM_EMP where pe_id = t.ins_id ) as INS_NM,INS_DT,UPD_ID,UPD_DT,PF_FILE_TEMP_NM,PF_FILE_REAL_NM,(select group_concat(PK_NM) from PLM_PDM_KEYWORD where PF_ID = t.PF_ID) as KEYWORD');
    $this->db->limit($limit);
    if($where != NULL)
        $this->db->where($where,NULL,FALSE);
        
		if($docType != 'trash'){ $this->db->where("ifnull(PF_DEL_YN,'N')","N"); }
	     //$this->db->where('PF_DEL_YN!=','Y');
	  
		//상세검색
		if(isset($_POST['detailSearch']) && $_POST['detailSearch'] != 'n'){
			
			$this->db->where("PF_CONT like '%".urldecode($_POST['doccont'])."%'");		//문서내용
			$this->db->where("PF_FILE_EXT like '%".urldecode($_POST['docext'])."%'");	//문서형식
			if($_POST['insnm'] !=''){
				$this->db->where("INS_ID IN ($sub_query_insnm)");	//작성자
			}
			if($_POST['pcnm'] !=''){
				$this->db->where("  ( 
								select group_concat(a.PC_NM separator ',')
								from PLM_COMP a
								where a.PC_ID in (
														select b.PC_ID
														from PLM_COMP_LIST b
														where b.PLM_TYPE = 'pms'
														and b.PARENT_ID in (
																					select c.PP_ID
																					from PLM_PMS_LIST c
																					where c.PP_ID = b.PARENT_ID
																					and c.PLM_TYPE = 'pdm2'
																					and c.PARENT_ID = t.PF_ID
																					)
													)
							 ) like '%".urldecode($_POST['pcnm'])."%' ");	//거래처
			}
			if($_POST['sdate'] != '' && $_POST['edate'] != ''){
				$this->db->where("date_format(INS_DT , '%Y-%m-%d') between date_format('".$_POST['sdate']."','%Y-%m-%d') and date_format('".$_POST['edate']."','%Y-%m-%d') ");	//올린기간
			}
			if($_POST['keyword'] != ''){
				$this->db->where("PF_ID IN ($sub_query_kw)");	//키워드
			}
		}
  
		if($docType == false || $docType == '') $this->db->where("PFD_ID",$treeid);
      	
        if($FA_SORT_STAR == 'true'){
        	$this->db->where("PF_ID IN ($sub_query)");
      	}
		
		$img = '"bmp","rle","dib","ico","jpeg","jpg","gif","png","tif","tiff","raw"';
		$mov = '"asf","avi","flv","mkv","mov","mpeg","mpg","ps","ts","tp","mts","m2ts","tod","mp4","m4v","3gp","skm","k3g","lmp4","rm","wmv","webm","ogv"';
		
		if($docType != false){
			
			switch($docType){
				case "m":		//공유받은파일
					$this->db->where("PF_ID IN ($sub_query1)");
				break;
				case "image":	//이미지
					$this->db->where('PF_FILE_EXT IN ('.$img.') ');
				break;
				case "movie":	//동영상
					$this->db->where('PF_FILE_EXT IN ('.$mov.') ');
				break;
				case "doc":		//문서
					$this->db->where('PF_FILE_EXT NOT IN ('.$img.','.$mov.') ');
				break;
				case "rm":		//요구사항관리
					$this->db->where("PF_INIT_ID_TYPE","rm");
				break;
				case "bom":		//요구사항관리
					$this->db->where("PF_INIT_ID_TYPE IN ('part','pdt')");
				break;
				case "trash":	//휴지통
					$this->db->where("ifnull(PF_DEL_YN,'N')","Y");
				break;
			}
			
		}
	    
	    if($sidx == 'PF_FILE_SIZE'){
	    	$this->db->order_by('CAST('.$sidx.' as UNSIGNED)', $sord);
	  	}else{
	  		$this->db->order_by($sidx,$sord);
	  	}
		//$this->db->order_by('INS_DT','DESC');
	    $query = $this->db->get('PLM_PDM_FILE t',$limit,$start);
		
    return $query->result();
  }
	
	/* 선택복원 */
	public function bokwon($pf_id){
		
		$sql = " update PLM_PDM_FILE 
				set PF_DEL_YN = NULL
					,UPD_ID	= '".$_SESSION['userid']."'
					,UPD_DT	= sysdate()
					,UPD_IP = '".$_SERVER['REMOTE_ADDR']."'
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
		/* 즐찾은 그냥삭제*/
		$this->db->delete('PLM_FA', array('PLM_TYPE' => 'pdm2','FA_ID' => $pf_id)); 
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
	
	/* 담당자 이력 영구삭제 */
	public function remove_emp_version($pf_id){
		
		$sql = " delete from PLM_PDM_EMP_VERSION 
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 파일 이력 영구삭제 */
	public function remove_file_version($pf_id){
		
		$sql = " delete from PLM_PDM_FILE_VERSION 
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 키워드 이력 영구삭제 */
	public function remove_keyword_version($pf_id){
		
		$sql = " delete from PLM_PDM_KEYWORD_VERSION 
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
	/* 파일list 영구삭제 */
	public function remove_file_list($pf_id){
		
		$sql = " delete from PLM_FILE_LIST 
				where PF_ID = '".$pf_id."'
				";
				
		$query = $this->db->query($sql);
		
		return $query;
		
	}
	
}
?>