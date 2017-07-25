<?php
class editprofil extends App{
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
		if($CONFIG['LOCAL_DEVELOPMENT']){
			sendRedirect("https://staging-marlboro-id.es-dm.com/templates/updateprofilestart.aspx?promoref=1&id=".$_SESSION['mop_token']);
			//sendRedirect("https://staging-a-mild-id.es-dm.com/Templates/updateprofilestart.aspx?id=".$_SESSION['mop_token']."&amp;PromoRef=1");
			//sendRedirect("https://login.amild.com/templates/UpdateProfileStart.aspx?id=".$_SESSION['mop_token']."&amp;PromoRef=1");
		}else{
			//sendRedirect("https://login.amild.com/templates/UpdateProfileStart.aspx?id=".$_SESSION['mop_token']."&amp;PromoRef=1");
			sendRedirect("https://login.marlboro.co.id/templates/updateprofilestart.aspx?promoref=1&id=".$_SESSION['mop_token']);
		}
		exit();
		
	}
}
