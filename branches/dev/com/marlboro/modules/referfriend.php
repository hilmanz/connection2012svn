<?php
class referfriend extends App{
	var $Request;
	var $View;
	var $user;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->user = $this->getUserInfo();
	}
	function home(){
		global $CONFIG;
		sendRedirect($CONFIG['MOP_LANDING_URL']."/Templates/referfriends.aspx?id=".$_SESSION['mop_token']."&promoref=1");
		exit;
	}
}
