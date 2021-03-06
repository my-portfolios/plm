<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MailBody extends CI_Controller {
	 
	function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/business/MailBody_model','MailBody_model');
		$this->load->model('/business/Pop_mailConfig_model');
		$this->load->library('upload');
        $this->check_isvalidated();
    } 
	
	private function check_isvalidated(){	
		if(! $this->session->userdata('validated')){
			redirect('Login');
		}
	}
	
	function index()
    {   
	
		$mc_id = $this->input->get('MC_ID');
		$msg_no = $this->input->get('MSG_NO');
		
		$mailConfigData = $this->Pop_mailConfig_model->getMailConfigData($mc_id);
		
		$hostname = '{imap.'.$mailConfigData->MC_HOST.':993/imap/ssl/novalidate-cert}INBOX';
		$username = $mailConfigData->MC_U_ID;
		$password = $mailConfigData->MC_U_PW;
		
		$mbox = imap_open($hostname, $username, $password) or die("can't connect: " . imap_last_error());
		$data['mailBody'] = $this->checkBodyStruct($mbox,$msg_no);
		
		
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/business/public/left');
		$this->load->view('/business/MailBody',$data,false);
		
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
		
    }
	
	function getMailBody(){
		
		$mc_id = $this->input->get('MC_ID');
		$msg_no = $this->input->get('MSG_NO');
		
		$mailConfigData = $this->Pop_mailConfig_model->getMailConfigData($mc_id);
		
		$hostname = '{imap.'.$mailConfigData->MC_HOST.':993/imap/ssl/novalidate-cert}INBOX';
		$username = $mailConfigData->MC_U_ID;
		$password = $mailConfigData->MC_U_PW;
		
		$mbox = imap_open($hostname, $username, $password) or die("can't connect: " . imap_last_error());
		
		$msg = $this->checkBodyStruct($mbox,$msg_no);
		//echo json_encode($msg);
		return $msg;
		/*
		$message = (imap_fetchbody($mbox,$msg_no,1.1)); 
        if($message == '' || $message == null)
        {
            $message = (imap_fetchbody($mbox,$msg_no,1));
        }
		
		echo json_encode(gettype($message));
		*/
	}
	
	function checkBodyStruct($mailstream , $MSG_NO){
		
		$struct = imap_fetchstructure($mailstream, $MSG_NO); 
		$type = $struct->subtype; 
		$val = "";
		//return $type;
		switch($type) { 
			case "PLAIN": // ??????????????? ?????? 
				echo str_replace("\n", "<br>", 
				imap_fetchbody($mailstream, $MSG_NO, "1")); 
			break; 
			case "HTML": // ??????????????? ?????? 
				echo str_replace("\n", "<br>", 
				imap_fetchbody($mailstream, $MSG_NO, "1")); 
			break; 
			case "MIXED": // ???????????? ?????? ?????? 
				for($i=0;$i<count($struct->parts);$i++) { 
					$part = $struct->parts[$i]; 
					$param = $part->parameters[0]; 
					//$file_name = Decode($param->value); 
					$file_name = $this->decode($param->value); 
					$mime = $part->subtype; // MIME ?????? ?????? ????????? ????????? ???????????????. 
					$encode = $part->encoding; // encoding 

					if($mime == "ALTERNATIVE") { 
						//$val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1)); 
						$val = imap_fetchbody($mailstream, $MSG_NO, "2"); 
						$val = base64_decode($val);	//????????? ?????????
					//	printOutLook($val); 
					} else { 
						$this->printbody($mailstream, $MSG_NO, $i, $encode, $mime, $file_name); 
					} 
				} 
			break; 
			case "ALTERNATIVE": // outlook html 
				for($i=0;$i<count($struct->parts);$i++) { 
					$part = $struct->parts[$i];
					$param = $part->parameters[0];
					//$file_name = Decode($param->value); 
					$file_name = $this->decode($param->value); 
					$mime = $part->subtype;
					$encode = $part->encoding;

					//if($mime == "HTML") { 	//????????? ??????
						$this->printbody($mailstream, $MSG_NO, $i, $encode, $mime, $file_name); 
					//}
				} 
			break; 
			case "RELATED": // outlook ????????? ????????? ?????? 
				for($i=0;$i<count($struct->parts);$i++) { 
					$part = $struct->parts[$i]; 
					$param = $part->parameters[0]; 
					//$file_name = Decode($param->value); 
					$file_name = $this->decode($param->value); 
					$mime = $part->subtype; // MIME ?????? 
					$encode = $part->encoding; // encoding 
					if($mime == "ALTERNATIVE") { 
						//$val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1)); 
						$val = imap_fetchbody($mailstream, $MSG_NO, "1"); 
					//	printOutLook($val); 
					} else { 
						$this->printbody($mailstream, $MSG_NO, $i, $encode, $mime, $file_name); 
					} 
				} 
			break; 
		} 
		return $val;
	}
	
	//imap ?????????
	function decode($string) {
    $tabChaine=imap_mime_header_decode($string);
    $texte='';
    for ($i=0; $i<count($tabChaine); $i++) {
        
        switch (strtoupper($tabChaine[$i]->charset)) { //convert charset to uppercase
            case 'UTF-8': $texte.= $tabChaine[$i]->text; //utf8 is ok
                break;
            case 'DEFAULT': $texte.= $tabChaine[$i]->text; //no convert
                break;
            default: if (in_array(strtoupper($tabChaine[$i]->charset),$this->upperListEncode())) //found in mb_list_encodings()
                        {$texte.= mb_convert_encoding($tabChaine[$i]->text,'UTF-8',$tabChaine[$i]->charset);}
                     else { //try to convert with iconv()
                          $ret = iconv($tabChaine[$i]->charset, "UTF-8", $tabChaine[$i]->text);    
                          if (!$ret) $texte.=$tabChaine[$i]->text;  //an error occurs (unknown charset) 
                          else $texte.=$ret;
                        }                    
                break;
            }
        }
        
    return $texte;    
    }
	
	function printbody($mailstream, $MSG_NO, $numpart, $encode, $mime, $file_name) { 

		$val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1)); 
		// ?????? ?????? part??? ????????? ?????? ?????????. 
		// ????????? ??????????????? ????????? $encode ??? ?????? ?????? ????????? decoding ????????????. 
		switch($encode) { 
			case 0: // 7bit 
			case 1: // 8bit 
				$val = imap_base64(imap_binary(imap_qprint(imap_8bit($val)))); 
			break; 
			case 2: // binary 
				$val = imap_base64(imap_binary($val)); 
			break; 
			case 3: // base64 
				$val = imap_base64($val); 
			break; 
			case 4: // quoted-print 
				$val = imap_base64(imap_binary(imap_qprint($val))); 
			break; 
			case 5: // other 
				$val = "???????????? Encoding ??????."; 
			exit; 
		} 
			// mime type ??? ?????? ???????????????. 
		switch($mime) { 
			case "PLAIN": 
				$val = str_replace("\n", "<br>", $val); 
			//	$val = imap_fetchbody($mailstream, $MSG_NO, "1"); 	//????????? ?????????
			break; 
			case "HTML": 
				$val = $val; 
			break; 
			default: 
			// ??????????????? ??????????????? ???????????? ??? ??? ?????? ????????? ?????? ?????????. 
			//echo "<br>??????: <a href="mail_down.php?MSG_NO=".$MSG_NO."&PART_NO=".$numpart."">".$file_name."</a>"; 
		} 
		return $val;
	} 

	
}
