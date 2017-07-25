<?php
/*
 | Author : Babar
 | 07/06/2012
 */
global $APP_PATH,$ENGINE_PATH;
include_once $APP_PATH.'marlboro/helper/codeHelper.php';
include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $APP_PATH.'marlboro/helper/newsHelper.php';
include_once $ENGINE_PATH."Utility/Mailer.php";
class updateclues extends App{
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
	
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$act = str_replace("-", "", $this->Request->getParam('act'));
		if($act!='') return $this->$act();
		else return $this->news();
	}
	
	function news(){
		$this->log("page","news");
		$news=json_decode($this->badgeHelper->getNewsAndClues(10));
	// print_r($news->data);exit;
		$this->View->assign('news',$news->data);
		return $this->View->toString(APPLICATION.'/update-clues.html');
	}
	
	function activity(){
		$this->log("page","activity");
		$activity=json_decode($this->badgeHelper->getAllActivityUser(100));
		foreach($activity->data as $key => $val) {
			
			$activity->data[$key]->userDetail= $this->newsHelper->getUserProfileForPublic($val->user_id);
		}
	// print_r('<pre>');print_r($activity->data);exit;
		$this->View->assign('activity',$activity->data);
		return $this->View->toString(APPLICATION.'/connection-activity.html');
	}
	
	function newsDetail(){
		$id= $this->Request->getPost('id');
		$news=$this->badgeHelper->getNewsAndCluesByID($id);
		header('Content-type: application/json');
		print_r($news);exit;

	}
	
	
	function activityDetail(){
		$id= $this->Request->getPost('id');
		$activity=json_decode($this->badgeHelper->getAllActivityUserByID($id));

		foreach($activity->data as $key => $val){
			if($activity->data->activity_values!=0){
				$badges = explode('_',$activity->data->activity_values);
					// print_r($activity->data->activity_values);exit;
				if(! $badges) $badges = '';
				else {
					foreach($badges as $keyBadges => $valBadges){
						$badgeDetail = json_decode($this->badgeHelper->get_badge_detail($valBadges));
						$badges[$keyBadges] = $badgeDetail->data;
						$badgeDetail=null;
					}
				}
			}else $badges = '';
			$activity->data->badges = $badges;
			
		}
		header('Content-type: application/json');
	print_r(json_encode($activity));exit;

	}
	
	function inputCode(){
	
		//echo json_encode(array('status'=>9));
		//exit;
		// strict-in lagi.. cek DB klo tanggal masih kurang dari 24 jam.. ga boleh isi
		
		if(!$_COOKIE['DISABLE_INPUT_CODE']){
			$_code = $_POST['code'];
			$_captcha = $_POST['captcha'];
			$_valid = (md5($_captcha) == $_SESSION['mrlbCaptchaSimple']) ? true : false;
			
			$_SESSION['mrlbCaptchaSimple'] = "bed" . rand(00000000,99999999) . "bed";
			
			if($_code != ''&& $_captcha != '' && $_valid){
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
		
		$news=json_decode($this->badgeHelper->getNewsAndClues(10));
		$this->View->assign('news',$news->data);
		
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
	
		return $this->View->toString(APPLICATION.'/update-clues_popup_badge.html');
		
	}
}
?>