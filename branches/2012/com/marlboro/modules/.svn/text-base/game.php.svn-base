<?php
global $APP_PATH,$ENGINE_PATH;
// include_once $APP_PATH.'marlboro/helper/codeHelper.php';
// include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';

class game extends App{
	var $Request;
	var $View;
	var $user;
	// var $codeHelper;
	var $newsHelper;
	// var $badgeHelper;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->user = $this->getUserInfo();
		// $this->codeHelper = new codeHelper($this->user['register_id']);
		$this->newsHelper = new newsHelper($this->user['register_id']);
		// $this->badgeHelper = new BadgeHelper('badge_api');
	}
	function home(){
		$act = $this->Request->getParam('act');
		if($act!='') return $this->$act();
		else return  $this->listGames();
	}
	
	
	function listGames(){
	
		return $this->View->toString(APPLICATION.'/game.html');
	}
	
	function berlin_light(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		$this->View->assign('user_id',$this->user['register_id']);
		$this->View->assign('minigame_name','berlin_popup_game');
		$this->log("minigame1","berlin_light");
		return $this->View->toString(APPLICATION.'/game.html');
	}

	function berlin_wall(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		$this->View->assign('user_id',$this->user['register_id']);
		$this->View->assign('minigame_name','berlin_wall_popup_game');
		$this->log("minigame2","berlin_wall");
		return $this->View->toString(APPLICATION.'/game.html');
	}
	
	function istanbul_chaser(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		$this->View->assign('user_id',$this->user['register_id']);
		$this->View->assign('minigame_name','istanbul_chaser_popup_game');
		$this->log("minigame3","istanbul_chaser");
		return $this->View->toString(APPLICATION.'/game.html');
	}
	
}