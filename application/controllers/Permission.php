<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Controller{
    
    function __construct(){
        parent::__construct();
    }
    
    public function index(){
	    $this->load->view('/public/header');
			$this->load->view('Permission_view');
			$this->load->view('/public/foot');
    }
}
