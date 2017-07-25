<?php
global $APP_PATH;
include_once "helper/SessionHelper.php";
include_once "helper/MOPHelper.php";
include_once "helper/trackingHelper.php";
include_once $APP_PATH."/MOP/MOPClient_2.php";
include_once $APP_PATH."/MOP/MopTracker.php";
class App extends Application{
	
	var $Request;
	var $View;
	var $_mainLayout="";
	var $session;
	var $mop;
	var $user;
	var $_mopClient;
	var $_mopTracker;
	var $_tracking;
	var $strBlocked = 'Wait for your Verification! once you\'ve verified you can input the code and unlock a badge';
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function setVar(){
		$this->session = new SessionHelper('SocialNetwork');
		$this->mop = new MOPHelper(null);
		$this->_mopClient = new MOPClient(null);
		$this->_mopTracker = new MopTracker(null);
		$this->_tracking = new trackingHelper();
	}
	
	/**
	 * 
	 * @todo tolong di tweak lagi expired_timenya.
	 */
	function main(){
		//print session_id();exit;
		global $CONFIG;
		
		if( !$CONFIG['END_OFF_SITE'] )
		{
		
			//cek login
			if($this->GetSession()){
				
				//CEK APAKAH WEB SUDAH BERAKHIR?
				if( !$CONFIG['END_OFF_SITE'] )
				{
				
					global $REDIRECT;
					if( $_REQUEST['promoref'] !="" && $REDIRECT[intval($_REQUEST['promoref'])]!=NULL){
						sendRedirect($REDIRECT[intval($_REQUEST['promoref'])]);
						exit();
					}else{
						$str = $this->run();
						
						global $CONFIG;
						$this->assign('LOCAL_DEVELOPMENT',$CONFIG['LOCAL_DEVELOPMENT']);
						
						/* cek message */
						$u = $this->getUserInfo();
						$sql = "SELECT COUNT(*) total FROM social_message WHERE message_status='0' AND message_to=".$u['id'].";";
						$this->open(0);
						$rs = $this->fetch($sql);
						$this->close();
						$this->assign('msgnum',intval($rs['total']));
						$this->assign('name',$u['name']);
						
						//buat popup badge universal
						$universal = 0;
						if(intval($u['n_status']) == 1){
							if(!$_SESSION['popup-universal']){
								$_SESSION['popup-universal'] = true;
								$universal = 1;
							}
						}
						$this->assign('universalmsg',$universal);
						
						$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
						$this->assign('header',$this->View->toString(APPLICATION . "/header.html"));
						$this->assign('footer',$this->View->toString(APPLICATION . "/footer.html"));
						$this->assign('register_id',$_SESSION['mop_profile2']['UserProfile']['RegistrationID']);
						$this->assign('mainContent',$str);
						$this->mainLayout(APPLICATION . '/master.html');
						
					}
					
				}else{
				
					$this->mainLayout(APPLICATION . '/page/landing-over.html');
				
				}
			}else{
			
					//buat proses ajax
					if($_GET['ajax'] == 1){
						echo json_encode(array('status'=>0));
						exit;
					}
					global $CONFIG;
					
					if($_GET["ref"] != NULL || $_GET["ref"] != ''){
							$refcode = $_GET["ref"];
							$ref = mysql_escape_string($_GET["ref"]);
							$sql = "INSERT INTO refferal (ref,tgl) VALUES ('".$ref."',NOW());";
							$this->open(0);
							$this->query($sql);
							$this->close();
					}
					
					if($_GET["promoref"] != NULL || $_GET["promoref"] != ""){
						$promoref = $_GET["promoref"];
					}
					
					$this->assign('login_page',$CONFIG['MOP_LANDING_URL'].'?promoref='.$promoref.'&ref='.$refcode);
					$this->mainLayout(APPLICATION . '/page/landing.html');
				
			}
		}else{
			$this->mainLayout(APPLICATION . '/page/new-landing.html');
		}
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
					$this->trackingMop($page,'home');
					return $this->View->toString(APPLICATION.'/'.$page.'.html');
				}else{
					sendRedirect("index.php");
					die();
				}
			}else{
				//echo 'ada filenya';exit;
				require_once 'modules/'. $page.'.php';
				$content = new $page($this->Request);
				
				if( $act != '' ){
					if( method_exists($content, $act) ){
						
						$this->trackingMop($page,$act);
						
						return $content->$act();
					}else{
						$this->trackingMop($page,'home');
					
						return $content->home();
					}
				}else{
					$this->trackingMop($page,'home');
					
					return $content->home();
				}
			}
		}else{
			require_once 'modules/home.php';
			$content = new home($this->Request);
			$this->trackingMop('home','home');
			return $content->main();
		}
	}
	
	function GetSession(){
		global $CONFIG;
		if($CONFIG['LOCAL_DEVELOPMENT']){
			return $this->getNonMopSession();
		}else{
			return $this->getMOPSession();
		}
	}
	
	function getNonMopSession(){
		global $CONFIG,$reward;
		$mop_token = $this->param('id');
		
		if($mop_token==null){
			$mop_token = $_SESSION['mop_token'];
		}
		
		if($this->session->get('mop_profile')==NULL){
			//kalo gak ada mop token.. maka redirect ke mop login page.
			if(strlen($mop_token)==0){
				//sendRedirect($CONFIG['MOP_LANDING_URL']);
				//die();
				
				return false;
				
			}else{
				//echo $this->param('username').' - ',$this->param('password').'<br />';
				
				$mop_session_id = $this->_mopClient->CheckLogin($this->param('username'),$this->param('password'));
				$session_id = $this->_mopClient->checkReferral($mop_session_id);
				
				//echo $mop_session_id.' - ',$session_id.'<br />';
				//exit;
				
				if( $session_id == '' || $session_id < 0){
					//di akalin dulu, MOP nya 
					return false;
				}
				
				
				$profileMop = $this->_mopClient->GetProfile2(0,$session_id);
				$profile = $this->sync_profile($profileMop);
				
				if($profile=="" || $profile < 0){
					return false;
				}
				
				$this->session->set('mop_profile',urlencode64($profile));
				/*
				$this->session->set('mop_token',urlencode64('blalblsabkdbasldbasb'));
				$this->session->set('mop_profile2',urlencode64($profile));
				*/
				$_SESSION['mop_token'] = $session_id;
				$_SESSION['mop_profile2'] = $profileMop;
				
				//$_profile = json_decode($profile);
				//$user_id = $_profile->id;
				
				$this->trackingMop('login','home','BRANDED_WEBSITE_LOGIN');
				
				//set login timer
				$this->mop->setTimer();
				
				$u = $this->getUserInfo();
				if( intval($u['login_count']) <= 2 ){
					sendRedirect('index.php?page=about-marlboro-connections');
					exit;
				}
				
				//sendRedirect('index.php');
				sendRedirect($CONFIG['REDIRECT_PAGE__AFTER_LOGIN']);
				exit;
			}
		}else{
			
			//check mop log time
			if( !$this->mop->checkTimer() ){
				//echo 'waktu habis :'.$_SESSION['mop_token'];
				//exit;
				$session_id = $this->_mopClient->checkReferral($_SESSION['mop_token']);
				if( $session_id == '' || $session_id < 0){
					session_destroy();
					return false;
				}
				$profileMop = $this->_mopClient->GetProfile2(0,$session_id);
				$profile = $this->sync_profile($profileMop);
				
				if($profile=="" || $profile < 0){
					return false;
				}
				
				$this->session->set('mop_profile',urlencode64($profile));
				$_SESSION['mop_token'] = $session_id;
				$_SESSION['mop_profile2'] = $profileMop;
				
				//set login timer
				$this->mop->setTimer();
			}
			return true;
		}
	}
	function getMopSession(){
	global $CONFIG,$reward;
		$mop_token = $this->param('id');
		
		if($mop_token==null){
			$mop_token = $_SESSION['mop_token'];
		}
		
		if($this->session->get('mop_profile')==NULL){
			//kalo gak ada mop token.. maka redirect ke mop login page.
			if(strlen($mop_token)==0){
				//sendRedirect($CONFIG['MOP_LANDING_URL']);
				//die();
				
				return false;
				
			}else{
				
				$session_id = $this->_mopClient->checkReferral($mop_token);
				
				if( $session_id == '' || $session_id < 0){
					return false;
				}
				
				$profileMop = $this->_mopClient->GetProfile2(0,$session_id);
				$profile = $this->sync_profile($profileMop);
				
				if($profile=="" || $profile < 0){
					return false;
				}
				
				$this->session->set('mop_profile',urlencode64($profile));
				$_SESSION['mop_token'] = $session_id;
				$_SESSION['mop_profile2'] = $profileMop;
				
				$this->trackingMop('login','home','BRANDED_WEBSITE_LOGIN');
				
				//set login timer
				$this->mop->setTimer();
				
				$u = $this->getUserInfo();
				if( intval($u['login_count']) <= 2 ){
					sendRedirect('index.php?page=about-marlboro-connections');
					exit;
				}
				
				//sendRedirect('index.php');
				sendRedirect($CONFIG['REDIRECT_PAGE__AFTER_LOGIN']);
				exit;
			}
		}else{
			
			//check mop log time
			if( !$this->mop->checkTimer() ){
				$session_id = $this->_mopClient->checkReferral($_SESSION['mop_token']);
				if( $session_id == '' || $session_id < 0){
					session_destroy();
					return false;
				}
				$profileMop = $this->_mopClient->GetProfile2(0,$session_id);
				$profile = $this->sync_profile($profileMop);
				
				if($profile=="" || $profile < 0){
					return false;
				}
				$this->session->set('mop_profile',urlencode64($profile));
				$_SESSION['mop_token'] = $session_id;
				$_SESSION['mop_profile2'] = $profileMop;
				
				//set login timer
				$this->mop->setTimer();
			}
			
			return true;
		}
	}
	
	function UpdateLoginTime($user_id){
		include_once "helper/MemberHelper.php";
		$member = new MemberHelper(null);
		
		$rs =  $member->update_login_time($user_id);
		return $rs;
	}
	function sync_profile($mop_profile){
		include_once "helper/MemberHelper.php";
		$member = new MemberHelper(null);
		
		$rs =  $member->sync_mop($mop_profile);
		return $rs;
	}
	
	function getUserInfo(){
		//always get the latest data
		include_once "helper/MemberHelper.php";
		$profile = $this->getProfile();
		$member = new MemberHelper(null);
		
		//echo 'masuk '.$profile->id;exit;
		
		return $member->getProfile($profile->id);
	}
	function getOtherUserInfo($id){
		include_once "helper/MemberHelper.php";
		$member = new MemberHelper(null);
		
		//echo 'masuk '.$id;exit;
		
		$user = $member->getProfile($id);
		
		return $user;
	}
	function getProfile(){
		//echo $this->session->get('mop_profile');
		//echo json_decode(urldecode64($this->session->get('mop_profile')));
		//exit;
		return json_decode(urldecode64($this->session->get('mop_profile')));
	}
	
	function getMopProfile(){
		//$mop_token = $this->session->get('mop_token');
		$mop_token = $_SESSION['mop_token'];
		$profile = $this->_mopClient->GetProfile2(0,$mop_token);
		return $profile;
	}
	
	function trackingMop($page='',$menu='',$code='ReferralCode_Code1'){
		$session_id = $_SESSION['mop_token'];

		if( $session_id == '' ){
			return false;
		}
		
		global $CPMOO;
		$mop = $_SESSION['mop_profile2'];
		$user['ConsumerID'] = $mop['UserProfile']['ConsumerID'];
		$user['RegistrationID'] = $mop['UserProfile']['RegistrationID'];
		$user['CityID'] = $mop['UserProfile']['CityID'];
		
		//CPMOO MANUAL
		if($page=='games'){
			if($menu=='berlin_wall'){
				$code='GAME_BERLIN_WALL';
			}elseif($menu=='dj'){
				$code='GAME_DJ';
			}elseif($menu=='yacht'){
				$code='GAME_YACHT';
			}elseif($menu=='art_museum'){
				$code='GAME_ART_MUSEUM';
			}
		}elseif($page=='code'){
			$page='REDEEM BADGES';
			$code='REDEEM_BADGES';
			if($menu=='home'){
				$menu='INPUT CODE';
			}elseif($menu=='submit'){
				$menu='SUBMIT';
			}elseif($menu=='yourbadges'){
				$menu='SEE YOUR BADGES';
			}elseif($menu=='trade'){
				$menu='TRADE';
				$code='TRADING_BADGES';
			}elseif($menu=='prize'){
				$prize=$_GET['prize'];
				if($prize=='berlin-prize-brief'){
					$menu='BERLIN PRIZE BRIEF';
				}elseif($prize=='new-york-prize-brief-1'){
					$menu='NEW YORK PRIZE BRIEF 1';
				}elseif($prize=='new-york-prize-brief-2'){
					$menu='NEW YORK PRIZE BRIEF 2';
				}elseif($prize=='instanbul-prize-brief'){
					$menu='INSTANBUL PRIZE BRIEF';
				}
			}
		}elseif($page=='howtoplay'){
			$page='REDEEM BADGES';
			$menu='HOW TO PLAY';
		}else if($page=='referfriend'){
			$code='ReferralCode_Code1';
			$menu='Redirect_to_mop';
		}
		
		if($page != 'login'){
			$this->_mopTracker->track($session_id,"1",$page,$menu,$CPMOO[$code],$user);
			
			if($page=='games'){
				$berlin_wall_track = $this->_mopTracker->trackGame($session_id,"1",$page,'berlin_wall',$CPMOO['GAME_BERLIN_WALL'],$user);
				$this->assign('berlin_wall_track',$berlin_wall_track);
				$dj_track = $this->_mopTracker->trackGame($session_id,"1",$page,'dj',$CPMOO['GAME_DJ'],$user);
				$this->assign('dj_track',$dj_track);
				$yacht_track = $this->_mopTracker->trackGame($session_id,"1",$page,'yacht',$CPMOO['GAME_YACHT'],$user);
				$this->assign('yacht_track',$yacht_track);
				$art_museum_track = $this->_mopTracker->trackGame($session_id,"1",$page,'art_museum',$CPMOO['GAME_ART_MUSEUM'],$user);
				$this->assign('art_museum_track',$art_museum_track);
			}
			if($page=="referfriend"){
				//hack untuk tracking refer-friend
                print "<script>".$this->_mopTracker->getEmbedScript()."</script>";
                sleep(5);
            }
			$user = $this->getUserInfo();
			$this->_tracking->save($user['id'],$page.' => '.$menu);
			$this->assign('MOP_EMBED', $this->_mopTracker->getEmbedScript());
			
		}else{
			$sess = $this->_mopClient->track($session_id,"1", $page, $menu, $CPMOO[$code], $user);
			$this->_tracking->save($user['id'],$page.' => '.$menu);
			
			if( $sess['Result'] < 0 || $sess['Result'] == '99' || $sess['Result'] == '' ){
				session_destroy();
				sendRedirect('index.php');
				exit;
			}else{
				$_SESSION['mop_token']=$sess['SessionID'];
			}
		}
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
}
