<?php
global $APP_PATH;
include_once "helper/SessionHelper.php";
include_once $APP_PATH."/MOP/MOPClient_2.php";
include_once $APP_PATH."/MOP/MopTracker.php";
class App extends Application{
	
	var $Request;
	var $View;
	var $_mainLayout="";
	var $session;
	var $mop;
	var $user;
	var $strBlocked = 'Wait for your Verification! once you\'ve verified you can input the code and unlock a badge';
	var $loginHelper; 
	var $_widgetList = array();
	var $track ;
	var $mopClient;
	var $mopTracker;

	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function log($param=NULL,$id=0){
		// print_r($this->user);
		require_once "helper/activityReportHelper.php";
		$track = new activityReportHelper($this->Request,$this->user['id']);
		$track->log($param,$id);
	}
	
	
	function log_login($reg_id){
		//log klo ga ada id nya
		$sql ="SELECT id FROM social_member WHERE register_id={$reg_id} LIMIT 1";
		$this->open(0);
		$data = $this->fetch($sql);
		$this->close();
		require_once "helper/activityReportHelper.php";
		$track = new activityReportHelper($this->Request,$data['id']);
		$track->log('login',0);
	}
	
	function promo($id,$refid){
		require_once APP_PATH.APPLICATION."/helper/activityReportHelper.php";
		$promo = new activityReportHelper($this->Request,0);
		$data = $promo->promo_ref($id,$refid);
		// print_r($data);exit;
	}
	
	
	function setVar(){
		global $CONFIG;
		$this->session = new SessionHelper('SocialNetwork');
		$this->mopClient =  new MOPClient(null);
		$this->mopTracker = new MopTracker();
		
		if( $CONFIG['MOP'] ){
			
			$MOP_PROFILE = $this->mop();
			// if(file_exists(APP_PATH.APPLICATION."/helper/loginHelper.php")) echo 'ada' ;	
			include_once "helper/loginHelper.php";
			$this->loginHelper = new loginHelper();
			// print_r($_SESSION['MOP_SESSION']);exit;
			if($this->loginHelper->goLogin($MOP_PROFILE["UserProfile"]["RegistrationID"])){
				$this->login = $this->loginHelper->checkLogin();
				$this->user =  $this->loginHelper->getProfile();
				
			}else{
				$this->login=false;
			}
			
		}else{
		// print_r('masuk');exit;			
			include_once "helper/loginHelper.php";
			$this->loginHelper = new loginHelper();
			$this->user = $this->loginHelper->getProfile();
		}
		
		
	}
	
	/**
	 * 
	 * @todo tolong di tweak lagi expired_timenya.
	 */
	function main(){
		/* 
		 * Babar 12/01/12 
		 * User name, username, user id & user Login status
		 * Di assign ke template master
		 */
		global $CONFIG;
		if( ! isset($_SESSION['MOP_SESSION']) ){
				sendRedirect($CONFIG['MOP_URL_LOGIN']);
				exit;
		}
		// include_once "helper/loginHelper.php";
		// $this->loginHelper = new loginHelper();
	
		// $this->login = $this->loginHelper->checkLogin();
		// $this->user = $this->loginHelper->getProfile();
			
		$this->user =  $this->getUserInfo();
		 
		$userid = $this->user['id'];
		if($this->user->nickname!='')$user_name = $this->user['nickname'];
		else $user_name = $this->user['name'];
		$username = $this->user['username'];
		$avatar = $this->user['small_img'];
		
		if(intval($this->user['n_status']) <= 0) $verified = false;
		else $verified = true;
			// print_r($this->user);exit;
		$page = strtolower($this->Request->getParam('page'));
		$act = strtolower($this->Request->getParam('act'));
		if($page!=''){
			$this->assign('page',$page.$act);
		}
	
		$str = $this->run();
		$this->assign('mopurl',$CONFIG['MOP_LANDING_URL']);
		$this->assign('isLogin',$this->login);
		$this->assign('register_id',$_SESSION['mop_sess_id']);
		$this->assign('user_name',$user_name);
		$this->assign('user_id',$userid);
		$this->assign('username',$username);
		$this->assign('verified',$verified);
		$this->assign('avatar',$avatar);
		$this->assign('mainContent',$str);
		$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
		$this->assign('header',$this->View->toString(APPLICATION . "/header.html"));
		$this->assign('footer',$this->View->toString(APPLICATION . "/footer.html"));
		$this->mainLayout(APPLICATION . '/master.html');		
	}
	
	/*
	 *	Mengatur setiap paramater di alihkan ke class yang mengaturnya
	 *
	 *	Urutan paramater:
	 *	- page			(nama class) 
	 *	- act				(nama method)
	 *	- optional		(paramater selanjutnya optional, tergantung kebutuhan)
	 */
	function run(){
		global $APP_PATH;
		//echo 'test';exit;
		$page = $this->Request->getParam('page');
		$act = $this->Request->getParam('act');
		
		if( $page != '' ){
		
			if( !is_file( $APP_PATH . APPLICATION . '/modules/'. $page . '.php' ) ){
			
				//cek jika static page
				if( is_file( '../templates/'. APPLICATION . '/'. $page . '.html' ) ){
					
					//tracking MOP
					$this->mopTrack(array('session_id'=>$_SESSION['MOP_SESSION']["UserProfile"]["RegistrationID"],'page'=>$page,'act'=>NULL));
					return $this->View->toString(APPLICATION.'/'.$page.'.html');
				}else{
					sendRedirect("index.php");
					die();
				}
			}else{
				
				//echo 'ada filenya';exit;
				require_once $APP_PATH . APPLICATION . '/modules/'. $page . '.php' ;
				// echo $APP_PATH . APPLICATION . '/modules/'. $page . '.php' ;
					
				$content = new $page($this->Request);
			
				if( $act != '' ){
					if( method_exists($content, $act) ){
						
						$this->mopTrack(array('session_id'=>$_SESSION['MOP_SESSION']["UserProfile"]["RegistrationID"],'page'=>$page,'act'=>$act));
						
						return $content->$act();
					}else{
						$this->mopTrack(array('session_id'=>$_SESSION['MOP_SESSION']["UserProfile"]["RegistrationID"],'page'=>$page,'act'=>NULL));
					
						return $content->home();
					}
				}else{
					$this->mopTrack(array('session_id'=>$_SESSION['MOP_SESSION']["UserProfile"]["RegistrationID"],'page'=>$page,'act'=>NULL));
					
					return $content->home();
				}
			}
		}else{						
			if($this->user['login_count'] >= 3) {						
				require_once  $APP_PATH . APPLICATION . '/modules/updateclues.php' ;			
				$content = new updateclues($this->Request);		
				$this->assign('page','updateclues');				
				return $content->news();						
			}else{			
				require_once  $APP_PATH . APPLICATION . '/modules/home.php' ;
				$content = new home($this->Request);
				$this->mopTrack(array('session_id'=>$_SESSION['MOP_SESSION']["UserProfile"]["RegistrationID"],'page'=>'about','act'=>NULL));
				return $content->main();						
			}
		}
	}
	
	function mop(){
			global $CONFIG;
		
			if($_SESSION['mop_sess_id']=="-1"){
				session_destroy();
				$param['id'] = $_REQUEST['id'];
				$param['promoref'] = $_REQUEST['promoref'];
				sendRedirect($CONFIG['MOP_URL_LOGIN'].'?'.http_build_query($param));
				exit;
			}
			if(	isset ($_SESSION['MOP_SESSION'])) return $_SESSION['MOP_SESSION'];
		
				$session_mop= $this->mopClient->checkReferral($_REQUEST['id']);
			
				if($session_mop!=-1){
				
				$this->mopClient->setSession($session_mop);
				$MOP_SESSION = 	$this->mopClient->getSession();
				$MOP_PROFILE = $this->mopClient->GetProfile2(0,$MOP_SESSION);
					require_once "helper/MemberHelper.php";
					$member = new MemberHelper;
					$result = $member->sync_mop($MOP_PROFILE);
				if($result){
					$_SESSION['MOP_SESSION'] = $MOP_PROFILE;
					$this->log_login($MOP_PROFILE["UserProfile"]["RegistrationID"]);					
				}
			
				return $_SESSION['MOP_SESSION'];
					
			}else{
				session_destroy();
					
				if(isset($_REQUEST['refid']))	$this->promo($_REQUEST['id'],$_REQUEST['refid']);
				if(isset($_REQUEST['PromoRef']))	$this->promo($_REQUEST['id'],$_REQUEST['PromoRef']);
				
				if(! isset($_REQUEST['id']) ) {
				sendRedirect(BASEURL.'landing.html');
				exit;
			}
				
			
			if(! isset($_REQUEST['id']) ) {
				sendRedirect(BASEURL.'landing.html');
				exit;
			}
			
				$param['id'] = $_REQUEST['id'];
				$param['promoref'] = $_REQUEST['promoref'];
				sendRedirect($CONFIG['MOP_URL_LOGIN'].'?'.http_build_query($param));
				exit;
			}


	}
	
	function getUserInfo(){
		//always get the latest data
		include_once "helper/MemberHelper.php";
		$profile = $this->getProfile();
		$member = new MemberHelper(null);
		
		// echo 'masuk '.$profile;exit;
		
		return $member->getProfile($profile->id);
	}
	function getOtherUserInfo($id){
		include_once "helper/MemberHelper.php";
		$member = new MemberHelper(null);
		
		// echo 'masuk '.$id
		
		$user = $member->getOtherProfile($id);
		// print_r($user );exit;
		return $user;
	}
	function getProfile(){
		//echo $this->session->get('mop_profile');
		//echo json_decode(urldecode64($this->session->get('mop_profile')));
		//exit;
		return $_SESSION['user'];
	}
	
	function getMopProfile(){
		//$mop_token = $this->session->get('mop_token');
		$mop_token = $_SESSION['mop_token'];
		$profile = $this->_mopClient->GetProfile2(0,$mop_token);
		return $profile;
	}
	function birthday($birthday){
		$birth = explode(' ',$birthday);
		list($year,$month,$day) = explode("-",$birth[0]);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		  $year_diff--;
		return $year_diff;
	}
	
	function mopTrack($data){
		GLOBAL $CPMOO;
		if( $data['page']=='ajax') return false;
		if(! $data['page']) $data['page'] = 'website_login_activity';
		include_once "helper/mopReferrenceCodeHelper.php";
		// if(file_exists(APP_PATH.APPLICATION."/helper/mopReferrenceCodeHelper.php")) echo 'ada';
		// exit;
		$mopRef = new mopReferrenceCodeHelper;
	
		$code = $mopRef->get_code($data['page'],$data['act']);
		
		//$user = array('ConsumerID'=>$_SESSION['MOP_SESSION']['UserProfile']['ReferredConsumerID'],'RegistrationID'=>$_SESSION['MOP_SESSION']['UserProfile']['ReferredRegistrationId']);
		$user = $_SESSION['MOP_SESSION']['UserProfile'];
			// print_r($user);	
		if($code) $data['code'] = $code;
		else $data['code'] = 'WEBSITE_LOGIN_ACTIVITY';
	
		// print_r($CPMOO[$data['code']]);exit;
		//$result = $this->mopClient->track($data['session_id'],"1", mysql_escape_string(strip_tags($data['page'])), mysql_escape_string(strip_tags($data['act'])), $CPMOO[$data['code']], $user);
		$result = $this->mopTracker->track($_SESSION['mop_sess_id'],"1", mysql_escape_string(strip_tags($data['page'])), mysql_escape_string(strip_tags($data['act'])), $CPMOO[$data['code']], $user);
		// print_r($result);exit;
		
		$this->assign("MOP_EMBED",$this->mopTracker->getEmbedScript());
		// $this->log('login');

	}
	
}
