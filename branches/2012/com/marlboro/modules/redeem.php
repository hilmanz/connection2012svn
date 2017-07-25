<?php
global $APP_PATH,$ENGINE_PATH;
include_once $APP_PATH.'marlboro/helper/codeHelper.php';
include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $ENGINE_PATH."Utility/Mailer.php";
class redeem extends App{
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
	function home()
	{
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$act = $this->Request->getParam('act');
		if($act!='') return $this->$act();
		else  return $this->getPrizeItems();
	}
	
	
	//redeem 2012
	function getPrizeItems(){
				
		$merchandiseList=json_decode($this->badgeHelper->getMerchandiseList($this->user['register_id']));
		// print_r('<pre>');print_r($merchandiseList->data);exit;
		$this->View->assign('merchandiseList',$merchandiseList->data);
		$this->log("page","redeem_merchandise");
		return $this->View->toString(APPLICATION.'/redeem.html');
	}
		
	function form(){
	
	if($this->Request->getParam('prizeid')==null) return $this->View->showMessage('please choose your prize','index.php?page=redeem');
	$data['merchandise_id'] = $this->Request->getParam('prizeid');
			
	$merchandise=json_decode($this->badgeHelper->getMerchandiseById($data));
	// print_r('<pre>');print_r($_SESSION['MOP_SESSION']['UserProfile']);
	$this->open(0);
	$qry="SELECT city FROM mop_city_lookup WHERE id='".$_SESSION['MOP_SESSION']['UserProfile']['CityID']."'";
	$rs=$this->fetch($qry);
	$this->close();
	foreach($_SESSION['MOP_SESSION']['UserProfile'] as $key => $val)
		{	
			// print_r($val);
			if(!is_array($val))$this->View->assign($key,$val);
			else $this->View->assign($key,'');
		}
	$this->View->assign('cityName',$rs['city']);
	$this->View->assign('merchandise',$merchandise->data);
	// print_r($this->user['register_id']);
	return $this->View->toString(APPLICATION.'/redeem-form.html');
	}
	
	
	function submit_form_redeem_prize(){
	
	if($this->Request->getPost('prefix_prize')==null && $this->Request->getPost('user_id') ==null ) {
			return $this->View->showMessage('please choose your prize','index.php?page=redeem');
		}
	$data['merchandise_id'] = $this->Request->getPost('merchandise_id');
	$data['prefix_prize'] = $this->Request->getPost('prefix_prize');
	$data['user_id'] = $this->user['register_id'];
	$data['street'] =  $this->Request->getPost('street');
	$data['complex'] =  $this->Request->getPost('complex');
	$data['province'] =  $this->Request->getPost('province');
	$data['city'] =  $this->Request->getPost('city');
	$data['phone'] =  $this->Request->getPost('phone');
	$data['mobile']=  $this->Request->getPost('mobile');
	// print_r($data);
	
	$result_submit_redeem =json_decode($this->badgeHelper->badge_redeemed($data));
	$this->View->assign('status',$result_submit_redeem->status);
	$this->View->assign('message',$result_submit_redeem->message);
	if($result_submit_redeem->status==1) $this->log("redeem_badges",$data['merchandise_id']);
	return $this->View->toString(APPLICATION.'/redeem-success.html');
	
	}
	
	//end redem prize 2012 script -----------------
	//redeem prize
	
	
}