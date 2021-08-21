<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	 
	function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('/business/Main_model','Main_model');
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
		$this->load->view('/public/header');
		$this->load->view('/public/top');
		$this->load->view('/business/public/left');
		$this->load->view('/business/Main');
		$this->load->view('/public/bottom');
		$this->load->view('/public/foot');
    }
	
	function getExMailList(){
		$result = $this->Main_model->getExMailList();
		echo json_encode($result);
	}
	
	function exMailDel(){
		$mc_id = $this->input->post('mc_id');
		$result = $this->Main_model->exMailDel($mc_id);
		echo json_encode($result);
	}
	

  /*open*/
  
  function upperListEncode() { //convert mb_list_encodings() to uppercase
    $encodes=mb_list_encodings();
    foreach ($encodes as $encode) $tencode[]=strtoupper($encode);
    return $tencode;
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
  	
  public function getMailBox(){
		$mc_id = $this->input->post('mc_id');
		
		$result = $this->Main_model->getMailConfigData($mc_id);
		
		$hostname = "";
		$username = "";
		$password = "";
		
		if($result){
			$hostname = '{imap.'.$result->MC_HOST.':993/imap/ssl/novalidate-cert}INBOX';
			$username = $result->MC_U_ID;
			$password = $result->MC_U_PW;
		}else{
			//$hostname = '{imap.daum.net:993/imap/ssl/novalidate-cert}INBOX';
			//$username = 'jeungwoong1004';
			//$password = 'code140412##';
		}
		/* connect to gmail */
		
		//$hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';
		//$username = 'jeungwoong2@gmail.com';
		
		//$hostname = '{imap.naver.com:993/imap/ssl/novalidate-cert}INBOX';
		//$username = 'jeungwoong';
		
		
		
		$mbox = imap_open($hostname, $username, $password) or die("can't connect: " . imap_last_error());

		$MC = imap_check($mbox);
		
		/*grid*/
		$page = isset($_POST['page'])?$_POST['page']:1; 
		$limit = isset($_POST['rows'])?$_POST['rows']:30; 
		$sidx = isset($_POST['sidx'])?$_POST['sidx']:'DT'; 
		$sord = isset($_POST['sord'])?$_POST['sord']:'DESC';         
		$start = $limit*$page - $limit; 
		$start = ($start<0)?0:$start; 
    
    // Fetch an overview for all messages in INBOX
    
    $b = $start+1+$limit;
    if($b > $MC->Nmsgs){
    	$b = $MC->Nmsgs;
    }
		
    $mailLimit = ($start+1).':'.($b);
		$result = imap_fetch_overview($mbox,$mailLimit,0);
    $where = ""; 
    
    
    $searchField = isset($_POST['searchField']) ? $_POST['searchField'] : false;
    $searchOper = isset($_POST['searchOper']) ? $_POST['searchOper']: false;
    $searchString = isset($_POST['searchString']) ? urldecode($_POST['searchString']) : false;

		$_search = isset($_POST['_search']) ? $_POST['_search'] : 'false';
		$_search1 = isset($_POST['_search1']) ? $_POST['_search1'] : 'false';

    if ($_search == 'true' || $_search1 == 'true') {
        $ops = array(
        'eq'=>'=', 
        'ne'=>'<>',
        'lt'=>'<', 
        'le'=>'<=',
        'gt'=>'>', 
        'ge'=>'>=',
        'bw'=>'LIKE',	
        'bn'=>'NOT LIKE',
        'in'=>'LIKE', 
        'ni'=>'NOT LIKE', 
        'ew'=>'LIKE', 
        'en'=>'NOT LIKE', 
        'cn'=>'LIKE', 
        'nc'=>'NOT LIKE' 
        );
        foreach ($ops as $key=>$value){
            if ($searchOper==$key) {
                $ops = $value;
            }
        }
        if($searchOper == 'eq' ) $searchString = $searchString;
        if($searchOper == 'bw' || $searchOper == 'bn') $searchString .= '%';
        if($searchOper == 'ew' || $searchOper == 'en' ) $searchString = '%'.$searchString;
        if($searchOper == 'cn' || $searchOper == 'nc' || $searchOper == 'in' || $searchOper == 'ni') $searchString = '%'.$searchString.'%';

        $where = "$searchField $ops '$searchString' "; 

    }
		
		if(!$sidx) 
        $sidx =1;
		$count = $MC->Nmsgs;    		
    if( $count > 0 ) {
        $total_pages = ceil($count/$limit);    
    } else {
        $total_pages = 0;
    }
		$responce = new stdClass();
    if ($page > $total_pages) 
      $page=$total_pages;
	    
	    $responce->page = $page;
	    $responce->total = $total_pages;
	    $responce->records = $count;
	    $i=0;
	    foreach ($result as $overview) {
					
					$msgno = $this->decode($overview->msgno);
					$subjects = $this->decode($overview->subject);
					$from = $this->decode($overview->from);
					$date = $this->decode(date("Y-m-d H:i:s", strtotime($overview->date)));
					 
					$responce->rows[$i]['id']=$msgno;
					$responce->rows[$i]['cell']=array($msgno,$subjects,$from,$date);
			    
					$i++;
			}
	    
		imap_close($mbox);
		echo json_encode($responce);
		
  }
  
}
