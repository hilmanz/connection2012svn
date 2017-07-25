<?php
include_once "SessionHelper.php";
class loginHelper extends Application{
	
	var $Request;
	
	var $View;
	
	var $_mainLayout="";
	
	var $session;
	
	var $login = false;
	
	function __construct($req){
		
		$this->Request = $req;
		
		$this->View = new BasicView();
		
		$this->session = new SessionHelper(APPLICATION.'_Session');
		
		if( $this->session->get('user') ){
			
			$this->login = true;
		
		}
	
	}
	
	function checkLogin(){
		// print_r($this->login);exit;
		return $this->login;
	}
	
	function loginSession(){
		$ok = false;
		
		if($_POST['goLogin']==1){
			if($this->goLogin()){
		
				sendRedirect('index.php');
				die();
			}
			if(!$ok){
				$this->assign("login_error","1");
			}
		}
		return $this->out(APPLICATION . '/login.html');
	}
	
	function goLogin(){
		
		// $RegistrationID = mysql_escape_string(trim(strtolower($RegistrationID)));
		
		// $password = trim($_POST['password']);
		$username = mysql_escape_string(trim(strtolower($_POST['txtUserName'])));
		// $password = trim($_POST['password']);
		
		$this->open(0);
		
		$sql = "SELECT * FROM social_member WHERE n_status=1 AND username='".$username."' LIMIT 1";
		$rs = $this->fetch($sql,1);
		// print_r($this);exit;
		$this->close();
		if(!$rs) $_SESSION['verified']=false;
		else $_SESSION['verified']=true;
		
			$id = $rs['id'];
			$_SESSION['login']=true;
			
			$this->session->set('user',urlencode64(json_encode($rs)));
			$this->login = true;
			
			return true;
	
		
	
	}
	
	

	function getProfile(){
		
		$user = json_decode(urldecode64($this->session->get('user')));
		
		return $user;
	
	}
	
	// Babar 10/01/12 -> Activity Tracking
	function activityTrack($ket='',$uid=''){
		//echo "test";exit;
		if($uid==''){
		$userid = strip_tags($this->user->id);
		}
		else {$userid=$uid;}
		$p		= "login";
		$url 	= $_SERVER['QUERY_STRING'];
		$requri	= $_SERVER['REQUEST_URI'];
		$ip		= $_SERVER['REMOTE_ADDR'];
		$agent	= $_SERVER['HTTP_USER_AGENT'];
		
		if($userid!=0){
			$q = "INSERT INTO ".DB_PREFIX."_activity (user_id, time, url, request_uri, page, action, ip, user_agent, keterangan)
					VALUES ('".$userid."', NOW(), '".$url."', '".$requri."', '".$p."', '".$a."', '".$ip."', '".$agent."', '".$ket."')";
			$this->open(0);
			$this->query($q);
			$this->close();
			//echo mysql_error();exit;
		}
	}
	
}