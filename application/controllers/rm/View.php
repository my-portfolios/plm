<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/rm/View_model');
		$this->load->model('/com/Pop_empSearch_model');
		$this->load->model('/com/Pop_pmsSearch_model');
    } 
	
	function index()
    {   
		if($_GET){
			$pr_id = $_GET['id'];
			$data['list'] = $this->getData($pr_id);
			$data['empList'] = $this->Pop_empSearch_model->getEmpList($this->uri->segment(1),$pr_id);
			$data['pmsList'] = $this->Pop_pmsSearch_model->getPmsList($this->uri->segment(1),$pr_id);
			$data['fileList'] = $this->getFileList('normal',$pr_id);
			$data['replyList'] = $this->getReplyList($pr_id);
			$data['replyFileList'] = $this->getFileList('reply_rm',$pr_id);
		}else{
			$data['list'] = null;
			$data['empList'] = null;
			$data['pmsList'] = null;
			$data['fileList'] = null;
			$data['replyList'] = null;
			$data['replyFileList'] = null;
		}
	
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/rm/public/left');
		$this->load->view('/rm/View',$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/* 데이터 가져오기 */
	public function getData($pr_id){
		$result = $this->View_model->getData($pr_id);
		return $result;
	}
	
	/* 파일 가져오기 */
	public function getFileList($plm_detail_type,$pr_id){
		$result = $this->View_model->getFileList($plm_detail_type,$pr_id);
		return $result;
	}
	
	/* 댓글 가져오기 */
	public function getReplyList($pr_id){
		$result = $this->View_model->getReplyList($pr_id);
		return $result;
	}
	
	/* 댓글 첨부파일 가져오기 
	public function getReplyFileList($pr_id){
		$result = $this->View_model->getReplyFileList($pr_id);
		return $result;
	}
	*/
	/* 진행상태 변경 */
	public function pr_status_upd(){
		
		$searchData = $this->input->post();
		
		$result = $this->View_model->pr_status_upd($searchData);
		
		echo json_encode($result);
		
	}
	
}
