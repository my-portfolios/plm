<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    public function index($msg = NULL){
		
      $data['msg'] = $msg;
    
	    $this->load->view('/public/header');
			$this->load->view('Login_view', $data);//right
			$this->load->view('/public/foot');
    }
    
    public function process(){
        // Load the model
        $this->load->model('Login_model');
        // Validate the user can login
        $result = $this->Login_model->validate();
        // Now we verify the result
        if(! $result){
            
					$data['msg'] = '아이디 또는 비밀번호를 확인하여주세요.'; 
					$this->load->view('/public/header');
					$this->load->view('Login_view', $data);//right
					$this->load->view('/public/foot');
            
        }else{
            // If user did validate, 
            // Send them to members area 
            redirect('Welcome');
        }
    }
}
