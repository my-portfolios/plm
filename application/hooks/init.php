<?php
class init  {
	
    private $CI;
 
    function __construct()
    {
        $this->CI =& get_instance();
 
        if(!isset($this->CI->session)){  //Check if session lib is loaded or not
			$this->CI->load->library('session');  //If not loaded, then load it here
        }
    }
 
 
    public function check_isvalidated(){
		
        $this->CI->load->library('session');
		$this->CI->load->helper('url');
		
        if(! $this->CI->session->userdata('validated')){
			if($this->CI->uri->segment(1) != 'Login' and $this->CI->uri->segment(2) != 'process' and $this->CI->uri->segment(1) != 'Ieerror'){
				redirect('Login', 'refresh');
			}else{
			}
		}
		
		//권한
		$seg1 = $this->CI->uri->segment(1);
		$seg2 = $this->CI->uri->segment(2);
		$auth = $this->CI->session->userdata('userauth');
		
		$seg1Arr = array('bom','pdm2','pms','admin');
		
		if (in_array($seg1, $seg1Arr) && ($auth == 'user') ) {
			if($seg2 != 'WbsView' && $seg2 != 'Gantt'){
				if($seg2 != 'Org_view'){
					redirect('Permission');
				}
			}
		}
		
		//admin(관리 탭)
		$seg2Arr = array('Format','Format_write','Org_view');
		
		if ( ($seg1 == 'admin') && !in_array($seg2, $seg2Arr) && ($auth != 'admin') ) {
			redirect('Permission');
		}
		
    }
	
}