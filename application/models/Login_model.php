<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Login_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    public function validate(){
    	
        // grab user input
        
        $userid 	= $this->security->xss_clean($this->input->post('userid'));
        $password 	= $this->security->xss_clean($this->input->post('password'));
        
        // Prep the query
        $this->db->where('PE_ID', $userid);
        $this->db->where('PE_PWD', $password);
        $this->db->where('ifnull(PE_DEL_YN, "N") !=', "Y");
        
        // Run the query
        $query = $this->db->get('PLM_EMP');
        // Let's check if there are any results
        if($query->num_rows() == 1)
        {
        		$row  = $query->row();
        		//
        		$compData = "select group_concat(PC_ID) as COMPID from PLM_COMP_LIST WHERE PARENT_ID = '".$row->PE_ID."'";
        		$compDataRow = $this->db->query($compData);
        		$compDataRowQ = $compDataRow->row();
        		
        		$a = explode(",",$compDataRowQ->COMPID);
        		$b = implode("','", $a);
        		
        		$compNm = "select group_concat(PC_NM) as COMNM from PLM_COMP where PC_ID in('".$b."')";
        		$compNmDataRow = $this->db->query($compNm);
        		$compNmDataRowQ = $compNmDataRow->row();
        		
            // If there is a user, then create session data
            
            $data = array(
                    'userid' 	=> $row->PE_ID,
                    'username' 	=> $row->PE_NM,
					'userauth'	=> $row->PE_AUTH,
					'userskin'	=> $row->ETC1,
					'comp'			=> $compDataRowQ->COMPID,
					'compnm'			=> $compNmDataRowQ->COMNM,
                    'validated' => true
                    );
            $this->session->set_userdata($data);
            return true;
        }
        // If the previous process did not validate
        // then return false.
        return false;
    }
}
?>