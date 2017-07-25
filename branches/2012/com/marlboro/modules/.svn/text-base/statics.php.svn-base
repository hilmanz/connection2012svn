<?php
/*
 | Author : Babar
 | 07/06/2012
 */
global $APP_PATH,$ENGINE_PATH;
include_once $APP_PATH.'marlboro/helper/codeHelper.php';
include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $ENGINE_PATH."Utility/Mailer.php";
class statics extends App{
	var $Request;
	var $View;
	var $user;
	var $codeHelper;
	var $newsHelper;
	var $badgeHelper;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->user = $this->getUserInfo();
		$this->codeHelper = new codeHelper($this->user['register_id']);
		$this->newsHelper = new newsHelper($this->user['register_id']);
		$this->badgeHelper = new BadgeHelper('badge_api');
	}

	function home(){
		$act = str_replace("-", "", $this->Request->getParam('act'));
		if($act!='') return $this->$act();
		else sendRedirect(BASEURL."index.php");
	}

	function about(){
		//contoh penggunaan log
			// $this->log($param,$any_value);
			// login
			// article
			// vote
			// trade_badges
			// auction
			// redeem_merchandise
			// page
			// refer_friend
			// update_profile
			// logout
			// minigame1
			// minigame2
			// minigame3
			// minigame4
			// minigame5
			// minigame6
			// minigame7
			// minigame8
			// input_code
		
		//if param is 'page', then the value is the 'name of page'
		$this->log('page','about');
		
		return $this->View->toString(APPLICATION.'/about.html');
	}

	function prizes(){
		$this->log('page','prizes');
		return $this->View->toString(APPLICATION.'/prizes.html');
	}

	function howtoplay(){
		$this->log('page','how-to-play');
		return $this->View->toString(APPLICATION.'/howtoplay.html');
	}

	function tos(){
		$this->log('page','tos');
		return $this->View->toString(APPLICATION.'/tos.html');
	}
}
?>