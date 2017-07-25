<?php
global $APP_PATH;
include_once "helper/SessionHelper.php";
include_once "helper/trackingHelper.php";
class App extends Application{
	
	var $Request;
	var $View;
	var $_mainLayout="";
	var $session;
	var $user;
	var $_tracking;
	var $strBlocked = 'Wait for your Verification! once you\'ve verified you can input the code and unlock a badge';
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
	}
	
	function setVar(){
		$this->session = new SessionHelper('SocialNetwork');
	}
	
	/**
	 * 
	 * @todo tolong di tweak lagi expired_timenya.
	 */
	function main(){
		global $CONFIG;
		if(@$_SESSION['login']!=true){
		// print_r($_SESSION);exit;
			sendRedirect($CONFIG['MOP_URL_LOGIN']);
			die();
		}
		
					$str = $this->run();
						
					$this->assign('universalmsg',$universal);
						
					$this->assign('meta',$this->View->toString(APPLICATION . "/meta.html"));
					$this->assign('header',$this->View->toString(APPLICATION . "/header.html"));
					$this->assign('footer',$this->View->toString(APPLICATION . "/footer.html"));
					$this->assign('mainContent',$str);
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
					
						
						return $content->$act();
					}else{
	
					
						return $content->home();
					}
				}else{
				
					
					return $content->home();
				}
			}
		}else{
			
			require_once 'modules/home.php';
			$content = new home($this->Request);
			return $content->main();
		}
	}

}
