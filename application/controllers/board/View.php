<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller {
	 
	function __construct()
    {      
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/board/View_model');
		$this->load->model('/com/Pop_empSearch_model');
		$this->load->model('/com/Pop_pmsSearch_model');
    } 
	
	function index()
    {   
		if($_GET){
			$c_id = $_GET['c_id'];
			$data['list'] = $this->getData($c_id);
			$data['empList'] = $this->Pop_empSearch_model->getEmpList($this->uri->segment(1),$c_id);
			$data['pmsList'] = $this->Pop_pmsSearch_model->getPmsList($this->uri->segment(1),$c_id);
			$data['fileList'] = $this->getFileList('board',$c_id);
			$data['replyList'] = $this->getReplyList($c_id);
			$data['replyFileList'] = $this->getFileList('reply_board',$c_id);
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
		$this->load->view('/board/public/left');
		$this->load->view('/board/View',$data);
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	/* 데이터 가져오기 */
	public function getData($c_id){
		$result = $this->View_model->getData($c_id);
		return $result;
	}
	
	/* 파일 가져오기 */
	public function getFileList($plm_detail_type,$c_id){
		$result = $this->View_model->getFileList($plm_detail_type,$c_id);
		return $result;
	}
	
	/* 댓글 가져오기 */
	public function getReplyList($c_id){
		$result = $this->View_model->getReplyList($c_id);
		return $result;
	}
	
}
