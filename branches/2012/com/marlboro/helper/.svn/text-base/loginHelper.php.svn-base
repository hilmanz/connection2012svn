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
		
		if($_POST['login']){
			if($this->goLogin()){
				$this->log('login','');
				sendRedirect('index.php');
				die();
			}
			if(!$ok){
				$this->assign("login_error","1");
			}
		}
		return $this->out(APPLICATION . '/login.html');
	}
	
	function goLogin($RegistrationID){
		$RegistrationID = mysql_escape_string(trim(strtolower($RegistrationID)));
		// $password = trim($_POST['password']);
		// $username = mysql_escape_string(trim(strtolower($_POST['username'])));
		// $password = trim($_POST['password']);
		
		$this->open(0);
		
		$sql = "SELECT * FROM social_member WHERE n_status=1 AND register_id='".$RegistrationID."' LIMIT 1";
		$rs = $this->fetch($sql);
		
		$this->close();
		// return 	$rs ;
		// $hash = sha1($password.$username.$rs['salt']);
		
		
		/* echo $sql.'<hr />';
		echo $hash.' - '.$rs['password'];
		exit; */
		
		// if($rs['username']==$username && $rs['password']==$hash){
			$id = $rs['id'];
			// Activity Tracking
			$this->activityTrack("login",$id);
			
			if(!$rs) $_SESSION['verified']=false;
			else {
			$_SESSION['verified']=true;
			
				foreach($rs as $key => $val){
					$rs2->$key = $val;
				
				}
			// print_r('<pre>');print_r($rs2);exit;
			$_SESSION['user']=$rs2;
			GLOBAL $APP_PATH;
			include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
			$API = new BadgeHelper('badge_api');
			$API->getFreeBadgeFirstLogin($_SESSION['user']->register_id);
			$API = null;
			}
			$_SESSION['login']=true;
			$this->login = true;
			// return true;
			// $this->add_stat_login($id);
			//$rs['register_id']= $rs['id'];
			//$rs['name'] = $rs['nama'];
			//$_SESSION['mop'] = urlencode64(json_encode($rs));
			// $this->session->set('user',urlencode64(json_encode($rs)));
			// $this->login = true;
			// $track->log('login');
			//activity track
			// require_once APP_PATH.APPLICATION."/helper/activityReportHelper.php";
			// $track = new activityReportHelper($this->Request,$id);
			// $track->log('login');
			// $track=null;
			return true;
		// }
		
	
	}
	
	
	function add_stat_login($user_id){
	
	$this->open(0);
	$sql ="UPDATE kana_member SET last_login=now(),login_count=login_count+1 WHERE id=".$user_id." LIMIT 1";
	$rs = $this->query($sql);
	$this->close();
	
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
