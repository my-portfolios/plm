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
			case "PLAIN": // 일반텍스트 메일 
				echo str_replace("\n", "<br>", 
				imap_fetchbody($mailstream, $MSG_NO, "1")); 
			break; 
			case "HTML": // 일반텍스트 메일 
				echo str_replace("\n", "<br>", 
				imap_fetchbody($mailstream, $MSG_NO, "1")); 
			break; 
			case "MIXED": // 첨부파일 있는 메일 
				for($i=0;$i<count($struct->parts);$i++) { 
					$part = $struct->parts[$i]; 
					$param = $part->parameters[0]; 
					//$file_name = Decode($param->value); 
					$file_name = $this->decode($param->value); 
					$mime = $part->subtype; // MIME 타입 혹은 메일의 종류가 리턴됩니다. 
					$encode = $part->encoding; // encoding 

					if($mime == "ALTERNATIVE") { 
						//$val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1)); 
						$val = imap_fetchbody($mailstream, $MSG_NO, "2"); 
						$val = base64_decode($val);	//미니가 추가함
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

					//if($mime == "HTML") { 	//미니가 지움
						$this->printbody($mailstream, $MSG_NO, $i, $encode, $mime, $file_name); 
					//}
				} 
			break; 
			case "RELATED": // outlook 본문에 이미지 삽입 
				for($i=0;$i<count($struct->parts);$i++) { 
					$part = $struct->parts[$i]; 
					$param = $part->parameters[0]; 
					//$file_name = Decode($param->value); 
					$file_name = $this->decode($param->value); 
					$mime = $part->subtype; // MIME 타입 
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
	
	//imap 디코딩
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
		// 먼저 해당 part의 본문을 받아 옵니다. 
		// 그리고 인자값으로 넘어온 $encode 에 의해 먼저 본문을 decoding 해줍니다. 
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
				$val = "알수없는 Encoding 방식."; 
			exit; 
		} 
			// mime type 에 따라 출력합니다. 
		switch($mime) { 
			case "PLAIN": 
				$val = str_replace("\n", "<br>", $val); 
			//	$val = imap_fetchbody($mailstream, $MSG_NO, "1"); 	//미니가 추가함
			break; 
			case "HTML": 
				$val = $val; 
			break; 
			default: 
			// 첨부파일인 경우이므로 다운로드 할 수 있게 링크를 걸어 줍니다. 
			//echo "<br>첨부: <a href="mail_down.php?MSG_NO=".$MSG_NO."&PART_NO=".$numpart."">".$file_name."</a>"; 
		} 
		return $val;
	} 

	
}
