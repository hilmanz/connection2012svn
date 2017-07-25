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
		//print_r( $this->user );exit;
		$this->View->assign('name', $this->user['name']);
		$this->View->assign('connection','97');
		return $this->View->toString(APPLICATION.'/home.html');
	}
}
