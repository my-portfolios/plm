<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{
	
    function is_logged_in ()
    {
		$CI =& get_instance();

		$CI->load->helper('url');
		$CI->load->library('session');
		
		if(! $CI->session->userdata('validated')){
			redirect('Login');
		}
		
		$seg1 = $CI->uri->segment(1);
		if($CI->uri->segment(1) == 'bom'){
			redirect('/');
		}
		
        // Change this to your actual "am I logged in?" logic
        return $CI->uri->segment(1);
    }

}