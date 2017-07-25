<?php
class home extends App{
	var $Request;
	var $View;
	var $user;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->user = $this->getUserInfo();
	}
	
	function main(){
		if( $this->user['login_count'] >= 3 ) sendRedirect('index.php?page=updateclues');
		$user_name = $this->user['name']." ".$this->user['last_name'];
		$this->View->assign("loginCount", $this->user['login_count']);
		$this->View->assign("user_name",$user_name);
		$this->log("page","home");
		return $this->View->toString(APPLICATION.'/home.html');
	}
}
