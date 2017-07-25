<?php
global $APP_PATH,$ENGINE_PATH;
include_once $APP_PATH.'marlboro/helper/codeHelper.php';
include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $APP_PATH.'marlboro/helper/newsHelper.php';
include_once $ENGINE_PATH."Utility/Mailer.php";
class yellowCabHunt extends App{
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
		$this->log("page","yellow_cab_hunting");
		$news=json_decode($this->badgeHelper->getYellowCabHunting(10));
	// print_r('<pre>');print_r($news->data);exit;
		$this->View->assign('news',$news->data);
		return $this->View->toString(APPLICATION.'/yellow-cab-hunt.html');
	}
	
	function huntingDetail(){
		$id= $this->Request->getPost('id');
		$news=json_decode($this->badgeHelper->getYellowCabHuntingById($id));
		
		$news->data->news_content = nl2br(html_entity_decode($news->data->news_content));
		$news= json_encode($news);
		header('Content-type: application/json');
		print_r($news);exit;

	}
	
	
	function activityDetail(){
		$id= $this->Request->getPost('id');
		$activity=$this->badgeHelper->getAllActivityUserByID($id);
		header('Content-type: application/json');
	print_r($activity);exit;

	}
	
	function inputCode(){
	
		//echo json_encode(array('status'=>9));
		//exit;
		// strict-in lagi.. cek DB klo tanggal masih kurang dari 24 jam.. ga boleh isi
		
		if(!$_COOKIE['DISABLE_INPUT_CODE']){
			$_code = $_POST['code'];
			// $_captcha = $_POST['captcha'];
			// $_valid = (md5($_captcha) == $_SESSION['mrlbCaptchaSimple']) ? true : false;
			
			// $_SESSION['mrlbCaptchaSimple'] = "bed" . rand(00000000,99999999) . "bed";
			
			if($_code != ''){
				$res=json_decode($this->codeHelper->inputCodeSuccess($_code));
				if(intval($res->status) == 1){
					setcookie("COUNT_INPUT_CODE", "", time()-3600);
					
					global $CONFIG;
					if($CONFIG['enable_news']){
						$this->newsHelper->unlockBadge($res->data->badge->name,$res->data->badge->id);
					}
					$data =  json_encode($res);
				}else{
					setcookie("COUNT_INPUT_CODE", intval($_COOKIE['COUNT_INPUT_CODE'])+1, time() + (1*60) );
					if( $_COOKIE['COUNT_INPUT_CODE'] >= 100 ){
						setcookie("DISABLE_INPUT_CODE", true, time() + (1*60) );
						setcookie("COUNT_INPUT_CODE", "", time()-3600);
					}
					$data =  json_encode($res);
				}
			}else{
				//JIKA CODE/CAPTCHA SALAH
				$data =  json_encode(array('status'=>444,'message'=>'wrong Code'));
			}
		}else{
			//JIKA USER DI BANNED
			$data =  json_encode(array('status'=>666,'message'=>'you are banned'));
		}
		
		$data = json_decode($data);
		if($data->status==1) $msg = $data->message;
		else $msg =  $data->message;
		if($data->status==1) $this->log("redeem_badges",$res->data->badge->id);
		// sendRedirect(BASEURL.'index.php?page=updateclues&act=news');
		return $this->View->showMessage($msg,'index.php?page=updateclues&act=news');
		
	}
}
?>