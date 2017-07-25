<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class message extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'send' ){
			return $this->send();
		}else{
			return $this->searchEmail();
		}
	}
	function searchEmail(){
		$_email = $_GET['email'];
		if($_email != ''){
			$qry = "SELECT * FROM social_member WHERE email LIKE '%$_email%';";
			$rs = $this->fetch($qry,1);
			$this->View->assign('rs',$rs);
		}
		return $this->View->toString("marlboro/admin/message-search-email.html");
	}
	function send(){
		$_send = $_GET['send'];
		$_id = $_GET['id'];
		if($_send == 1){
			$_id = $_GET['id'];
			$_subject = $_GET['subject'];
			$_text = $_GET['text'];
			
			if($_id != '' && $_subject != '' && $_text != ''){		
				$qry = "INSERT INTO social_message (message_to,message_from,message_date,message_subject,message_text)
							VALUES
							('$_id','0',NOW(),'$_subject','$_text');";
				if($this->query($qry)){
					return $this->View->showMessage("Send Success","index.php?s=message");
				}else{
					return $this->View->showMessage("Send Failed","index.php?s=message");
				}
			}else{
				return $this->View->showMessage("Complete Form Please!","index.php?s=message");
			}
		}
		$this->View->assign('id',$_id);
		return $this->View->toString("marlboro/admin/message-send.html");
	}
}