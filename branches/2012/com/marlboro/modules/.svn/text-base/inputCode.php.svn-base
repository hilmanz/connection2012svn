<?php
global $APP_PATH,$ENGINE_PATH;
include_once $APP_PATH.'marlboro/helper/codeHelper.php';
include_once $APP_PATH.'marlboro/helper/newsHelper.php';
include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $ENGINE_PATH."Utility/Mailer.php";
class inputCode extends App{
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
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$act = $this->Request->getParam('act');
		if($act!='') return $this->$act();
		else {
		$this->log("page","inputCode");
		return $this->View->toString(APPLICATION.'/input-codes.html');
		}
		
	}
	
	function submit(){
	
		//echo json_encode(array('status'=>9));
		//exit;
		// strict-in lagi.. cek DB klo tanggal masih kurang dari 24 jam.. ga boleh isi
		
		if(!$_COOKIE['DISABLE_INPUT_CODE']){
			$_code = $_POST['code'];
			$_captcha = $_POST['captcha'];
			$_valid = (md5($_captcha) == $_SESSION['mrlbCaptchaSimple']) ? true : false;
			
			$_SESSION['mrlbCaptchaSimple'] = "bed" . rand(00000000,99999999) . "bed";
			
			if($_code != '' && $_captcha != '' && $_valid){
				$res=json_decode($this->codeHelper->inputCodeSuccess($_code));
				if(intval($res->status) == 1){
					setcookie("COUNT_INPUT_CODE", "", time()-3600);
					
					// global $CONFIG;
					// if($CONFIG['enable_news']){
						// $this->newsHelper->unlockBadge($res->data->badge->name,$res->data->badge->id);
					// }
					$data =  json_encode($res);
				}else{
					setcookie("COUNT_INPUT_CODE", intval($_COOKIE['COUNT_INPUT_CODE'])+1, time() + (1*60) );
					if( $_COOKIE['COUNT_INPUT_CODE'] >= 5 ){
						setcookie("DISABLE_INPUT_CODE", true, time() + (1*3600*24) );
						setcookie("COUNT_INPUT_CODE", "", time()-3600);
					}
					$data =  json_encode($res);
				}
			}else{
				//JIKA CODE/CAPTCHA SALAH
				$data =  json_encode(array('status'=>444,'message'=>'Please enter the right verification numbers before hitting submit'));
			}
		}else{
			//JIKA USER DI BANNED
			$data =  json_encode(array('status'=>666,'message'=>'you are banned'));
		}
		
		$data = json_decode($data);
		if($data->status==1) {
			$msg = $data->message;
			$badgeDetail = json_decode($this->badgeHelper->get_badge_detail($res->data->badge->id));
			// print_r('<pre>');print_r($badgeDetail);exit;
			$this->View->assign('badge',true);
			$this->View->assign('badgeName',$badgeDetail->data->name);
			$this->View->assign('badgeDescription',$badgeDetail->data->description);
			$this->View->assign('badgeUrl',$badgeDetail->data->img);
			$this->log("redeem_badges",$res->data->badge->id);
		}else {		
		$this->View->assign('dontgetbadgeTitle', 'Sorry, Failed to get the Badge'); 
		$this->View->assign('dontgetbadgeDesc', $data->message); 
		}
	
		// sendRedirect(BASEURL.'index.php?page=inputCode');
		return $this->View->toString(APPLICATION.'/input-code-with-popup.html');
		
	}
	
}