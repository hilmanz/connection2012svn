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
		// print_r($_SESSION['MOP_SESSION']['SessionID']);exit;
		// https://login.marlboro.co.id/Templates/Referfriends.aspx/?promoref=<<PromoRefkey>>&id=<<SessionID>>
		$this->log('refer_friend');
		sendRedirect($CONFIG['MOP_LANDING_URL']."/Templates/Referfriends.aspx/?id=".$_SESSION['MOP_SESSION']['SessionID']."&promoref=1");
		exit;
	}
}
