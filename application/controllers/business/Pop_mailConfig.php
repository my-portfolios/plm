<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pop_mailConfig extends CI_Controller {
	 
	function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/business/Pop_mailConfig_model');
        $this->check_isvalidated();
    } 
	
	private function check_isvalidated(){	
		if(! $this->session->userdata('validated')){
			redirect('Login');
		}
	}
	
	function index()
    {   
	
    }
	
	function getMailConfigData(){
		$mc_id = $this->input->post('mc_id');
		$result = $this->Pop_mailConfig_model->getMailConfigData($mc_id);
		echo json_encode($result);
	}
	
	function save(){
		
		$searchData = $this->input->post();
		
		$hostname = '{imap.'.$searchData['MC_HOST'].':993/imap/ssl/novalidate-cert}INBOX';
		$username = $searchData['MC_U_ID'];
		$password = $searchData['MC_U_PW'];
		/*
		$hostname = '{imap.daum.net:993/imap/ssl/novalidate-cert}INBOX';
		$username = 'jeungwoong1004';
		$password = 'code140412##';
		*/
		/*
		$hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
		$username = 'minimini4848@gmail.com';
		$password = 'Success!23';
		*/
		$mbox = imap_open($hostname, $username, $password) or die("can't connect: " . imap_last_error());

		$MC = imap_check($mbox);
		$msg = '';
		if($MC){
			$result = $this->Pop_mailConfig_model->save($searchData);
			if($result){
				$msg = '<script>alert("저장되었습니다.");</script>';
			}else{
				$msg = '<script>alert("오류.");</script>';
			}
		}else{
			$msg = '<script>alert("정보가 올바르지 않습니다.");</script>';
		}
		//echo $msg;
		redirect('business/Main');
		//echo var_dump($MC);
	}
}
