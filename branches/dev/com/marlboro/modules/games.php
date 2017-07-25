<?php
class games extends App{
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
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		//top 100
		$sql = "SELECT r.*,m.name FROM user_rank r LEFT JOIN social_member m ON m.register_id=r.user_id ORDER BY r.rank LIMIT 100;";
		//user rank
		$sql2 = "SELECT rank FROM marlboro_connect.user_rank WHERE user_id=".$this->user['register_id']." LIMIT 1;";
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$myrank = $this->fetch($sql2);
		$this->close();
		
		$this->View->assign('rank',$rs);
		$this->View->assign('myrank',intval($myrank['rank']));
		$this->View->assign('register_id',$this->user['register_id']);
		$this->View->assign('session_id',session_id());
		return $this->View->toString(APPLICATION.'/games.html');
	}
	function berlin_wall(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		return $this->View->toString(APPLICATION.'/games-berlin-wall.html');
	}
	function dj(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		return $this->View->toString(APPLICATION.'/games-dj.html');
	}
	function yacht(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		return $this->View->toString(APPLICATION.'/games-yacht.html');
	}
	function art_museum(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		return $this->View->toString(APPLICATION.'/games-art-museum.html');
	}
}
