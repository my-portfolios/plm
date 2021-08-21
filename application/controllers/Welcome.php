<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){
		parent::__construct();
	} 
	 
	public function index()
	{
		/*
		if($this->session->userdata('userauth') == 'user'){	//user
			redirect('/rm/Main');
		}else{						//emp,admin
			redirect('/pdm2/Main');
		}
		*/
		redirect('/dash/Main');
	}
	
  public function do_logout(){
      $this->session->sess_destroy();
      redirect('Login');
  }
}
