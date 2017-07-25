<?php
class RegisterHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function main(){
		$req = $this->Request;
		if($req->getParam('add') == 1){
			$_username = $req->getParam('email');
			$_password = $req->getParam('password');
			$_email = $req->getParam('email');
			$_name = $req->getParam('name');
			$_type = $req->getParam('type');
			$_status = $req->getParam('status');
			
			//echo $_username.' - '.$_password.' - '.$_email.' - '.$_name.' - '.$_type;
			//exit;
			
			if( $_username != '' && $_password != '' && $_email != '' && $_name != '' && $_type != '' ){
				
				$salt = rand(1000,9999);
				$hash = sha1($_password.$_username.$salt);
				
				$this->open(0);
				$qry = "INSERT INTO dm_member (username,password,salt,email,nama,n_status,last_update) VALUES ('$_username','$hash','$salt','$_email','$_name','$_status',NOW())";
				
				if($this->query($qry)){
					$id = mysql_insert_id();
					$qry = "INSERT INTO social_member 
								(register_id,name,email,register_date,username,type,last_login)
								VALUES
								('$id','$_name','$_email',NOW(),'$_username','$_type',NOW())";
					if($this->query($qry)){
						return $this->View->showMessage("Success","?s=member");
					}else{
						$this->assign('msg','Error, please try again!');
						//echo mysql_error();
						//exit;
					}
				}else{
					$this->assign('msg','Error, please try again!');
					//echo mysql_error();
					//exit;
				}
				$this->close();
			}else{
				$this->assign('msg','Error, please fill all field!');
			}
		}
		return $this->out(APPLICATION . "/register.html");
	}
}