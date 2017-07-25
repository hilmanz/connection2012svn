<?php
global $APP_PATH;

include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $APP_PATH.'marlboro/helper/ImagesHelper.php';
class myprofile extends App{
	var $Request;
	var $View;
	var $user;
	var $badgeHelper;
	var $ImagesHelper;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		
		$this->badgeHelper = new BadgeHelper('badge_api');
		$this->ImagesHelper = new ImagesHelper();
		$this->user = $this->getUserInfo();
	}
	
	function home(){
		if($this->Request->getParam('id'))
		{
			$id = $this->Request->getParam('id') ;
			$this->user = $this->getOtherUserInfo($id);
		}
		if($this->user['last_name']=="Array"){
			$this->user['last_name'] = "";
		}
		$this->View->assign('name',$this->user['name'].' '.$this->user['last_name']);
		
		if(intval($this->user['n_status'] ) <=0 ) $allowChangePhoto = false;
		else $allowChangePhoto = true;
		$this->View->assign('allowChangePhoto',$allowChangePhoto);
		
		$this->open(0);
		$qry="SELECT city FROM mop_city_lookup WHERE id='".$this->user['city']."'";
		$rs=$this->fetch($qry);
		$this->close();
		
		$this->View->assign('kota',$rs['city']);
		$sex = ($this->user['sex'] == 'F') ? 'Female' : 'Male';
		$this->View->assign('sex', $sex);
		$this->View->assign('age', $this->birthday($this->user['birthday']));
		$this->View->assign('date', date('l jS \of F Y ',strtotime($this->user['register_date'])));
		
		$rand1 = rand(1111111111,9999999999);
		$rand2 = rand(111111111,999999999);
		$desc = $this->user['sex']." < ".strtoupper($this->user['last_name'])." < < ".strtoupper($this->user['name'])." < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < <br /> M".$rand1." < 0IND".$rand2." < < < < < < < < < < < < < < < < < < < < < < < <";
		$this->View->assign('description', $desc);
		
		$img = $this->user['img'];
		
		if($img!=null)
		{
	
			if(!file_exists("avatar/".$img)){
				if ($this->user['sex']== 'F' )$img = "avatar/avatar-woman.jpg";
				else $img = "avatar/avatar-man.jpg";
			}
			
		}else {
			if ($this->user['sex']== 'F' )$img = "avatar/avatar-woman.jpg";
				else $img = "avatar/avatar-man.jpg";
		}


		$res = json_decode($this->badgeHelper->get_user_actual_badges($this->user['register_id']));
		$allBadges = json_decode($this->badgeHelper->getAllBadgeDetail());
		// print_r($allBadges);exit;
		
		$badge=array();
		foreach($allBadges->data as $key => $val){
			$badge[$key] = array('badge_id'=>$val->badgeid,'total'=>0,'description'=>$val->description,'image'=>$val->badgeurl,'badgetitle'=>$val->badgetitle);
				foreach($res->data as $k){
					if($val->badgeid == $k->badge_id){
						$badge[$key] = array('badge_id'=>$val->badgeid,'total'=>$k->total,'description'=>$val->description,'image'=>$val->badgeurl,'badgetitle'=>$val->badgetitle);
						$totalBadge += $k->total*$val->badge_value;
					}
					
				}
			}
			
		//winning auction case
	
		$winningAuction = json_decode($this->badgeHelper->getAuctionWinnerById($this->user['id']));
			// print_r('<pre>');print_r($badge);
			// exit;
		// print_r('<pre>');print_r($allBadges);exit;
		if($this->user['login_count']<=1) {
		
						$this->open(0);
						$qry = "SELECT * FROM ".SCHEMA_CONNECTION.".social_tradenews WHERE user_id='".$this->user['register_id']."' AND activity_values like '%_%' LIMIT 1";
						$rs=$this->fetch($qry);
						$this->close();
		
		$this->View->assign('idNews', $rs);
// print_r($rs);		
		}
		
		$this->View->assign('winningAuction', $winningAuction->data);
		$this->View->assign('badge', $badge);		
		$this->View->assign('totalBadge', $totalBadge);
		$this->View->assign('avatar', "avatar/".$img);
		$this->View->assign('avatar_med', "avatar/thumb/medium_".$img);
		$this->View->assign('avatar_sm', "avatar/thumb/small_".$img);
		$this->log("page","myprofile");// log
		return $this->View->toString(APPLICATION.'/myprofile.html');
	}
	

	function upload(){
		$name = $_FILES['avatar']['name'];
	
		if($name!=""){
			$path = "avatar/";
			$newimg = sha1("Ymd").$name;
			
			if( $_FILES['avatar']['size'] > 250000) $data['message']= "The file size of your photo should not be bigger than 250kb.";
			else {
				if( $_FILES['avatar']['type'] == 'image/jpg' || $_FILES['avatar']['type'] == 'image/jpeg' ||  $_FILES['avatar']['type'] == 'image/png' ){
					if(move_uploaded_file($_FILES['avatar']['tmp_name'], $path.$newimg)){
					
						$resultMedium = $this->ImagesHelper->image_resize($path.$newimg,$path."thumb/medium_".$newimg,80,120,1);
						$resultSmall = $this->ImagesHelper->image_resize($path.$newimg,$path."thumb/small_".$newimg,40,90,1);
						if($resultMedium['status']==0) copy($path.$newimg,$path."thumb/medium_".$newimg);
						if($resultSmall['status']==0) copy($path.$newimg,$path."thumb/small_".$newimg);
						// print_r($resultMedium['status']);exit;
						$this->open(0);
						$qry = "UPDATE social_member SET img='".$newimg."' WHERE id='".$this->user['id']."'";
						$rs=$this->query($qry);
						$this->close();
					
						$this->log("page","update-avatar");// log
						$data['image'] = $path."thumb/medium_".$newimg;
						$data['message'] = "upload photo success";
					
					}else 	$data['message'] = "Failed to update your photo profile, please try again.";
				}else $data['message'] = "Failed to upload photo. Make sure your file formats is JPG, JPEG or PNG.";
				
			}
		}else	$data['message'] = "Failed to update your photo profile, please try again.";
		
		header('Content-type: application/json');
		// print_r(json_encode($_FILES['avatar']['type']));exit;//52619 250000
		print_r(json_encode($data));exit;
	}

	function messages(){
	$message = json_decode($this->badgeHelper->getMessageByUserId($this->user['register_id']));
	foreach( $message->data as $key => $val){
	 $message->data[$key]->message_teaser = substr($val->message_text,0,strpos($val->message_text,'.'));
	
	}
	$this->View->assign('message', $message->data);
	$this->log("page","messages");// log
	// print_r($res);exit;
	return $this->View->toString(APPLICATION.'/messages.html');
	}
	
	function messageDetail(){
		$id= $this->Request->getPost('id');
		$user_id= $this->user['register_id'];
	
		// print_r($id);exit;
		$msg=$this->badgeHelper->getMessageByUserIdAndMessageId($user_id,$id);
	
		header('Content-type: application/json');
		print_r($msg);exit;

	}
	
	function deleteMessage(){
		$id= $this->Request->getPost('id');
		$user_id= $this->user['register_id'];

		// print_r($id);exit;
		$msg=$this->badgeHelper->getDeleteMessageByUserIdAndMessageId($user_id,$id);
	
		header('Content-type: application/json');
		print_r($msg);exit;
	}
	
	function profilePopup(){
		
		
		$id = $this->Request->getPost('id') ;
		$register_id = $this->Request->getPost('register_id') ;
	
		if($register_id !='' || $id !='' )
		{
			
			if($register_id!='') $qry="SELECT * FROM ".SCHEMA_CONNECTION.".social_member WHERE register_id='".$register_id."' LIMIT 1";
			else $qry="SELECT * FROM ".SCHEMA_CONNECTION.".social_member WHERE id='".$id."' LIMIT 1";
			$this->open(0);
			$rs=$this->fetch($qry);
			$this->close();
			$user = $rs;
		}
		
		if($user['last_name']=="Array"){
			$user['last_name'] = "";
		}
		// print_r($user);exit;
		$this->open(0);
		$qry="SELECT city FROM mop_city_lookup WHERE id='".$user['city']."'";
		$rs=$this->fetch($qry);
			if($register_id!='') {
				$qry="SELECT count(*) as total FROM ".SCHEMA_CODE.".tbl_trade_log WHERE buyer=".$register_id."  OR	seller=".$register_id."";
				$tradingWith=$this->fetch($qry);
				
			}
		$this->close();
		if(!$tradingWith) $tradingWith = 0;
		else $tradingWith = $tradingWith['total'] ;
		$sex = ($user['sex'] == 'F') ? 'Female' : 'Male';
	
		$rand1 = rand(1111111111,9999999999);
		$rand2 = rand(111111111,999999999);
		$desc = $user['sex']." < ".strtoupper($user['last_name'])." < < ".strtoupper($user['name'])." < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < <br /> M".$rand1." < 0IND".$rand2." < < < < < < < < < < < < < < < < < < < < < < < <";
				
		$img = $user['img'];
		if($img!=null)
		{
	
			if(!file_exists("avatar/".$img)){
				if ($user['sex']== 'F' )$img = "avatar/avatar-woman.jpg";
				else $img = "avatar/avatar-man.jpg";
			}
			
		}else {
			if ($user['sex']== 'F' )$img = "avatar/avatar-woman.jpg";
				else $img = "avatar/avatar-man.jpg";
		}

		$res = json_decode($this->badgeHelper->get_user_actual_badges($user['register_id']));
		$allBadges = json_decode($this->badgeHelper->getAllBadgeDetail());
		$winningAuction = json_decode($this->badgeHelper->getAuctionWinnerById($user['id']));
		
		$badge=array();
		foreach($allBadges->data as $key => $val){
			$badge[$key] = array('badge_id'=>$val->badgeid,'total'=>0,'description'=>$val->description,'image'=>$val->badgeurl,'badgetitle'=>$val->badgetitle);
				foreach($res->data as $k){
					if($val->badgeid == $k->badge_id){
						$badge[$key] = array('badge_id'=>$val->badgeid,'total'=>$k->total,'description'=>$val->description,'image'=>$val->badgeurl);
						$totalBadge += $k->total*$val->badge_value;
					}
					
				}
			}
		
		
		// $data['description']= $desc;
		$data['sex']= $sex;
		$data['kota']=$rs['city'];
		$data['age']= $this->birthday($user['birthday']);
		$data['date']= date('l jS \of F Y',strtotime($user['register_date']));
		$data['name']=$user['name'].' '.$user['last_name'];
		$data['badge']= $badge;
		$data['totalBadge']= $totalBadge;
		$data['avatar']= "avatar/".$img;
		$data['avatar_med']= "avatar/thumb/medium_".$img;
		$data['avatar_sm']= "avatar/thumb/small_".$img;
		$data['auctionwin'] =$winningAuction->data;
		$data['totalTrade'] =$tradingWith;
		
		header('Content-type: application/json');
		print_r(json_encode($data));exit;
	
	}
	
	function updateProfile(){
		global $CONFIG;
		$this->log('page','update_profile');
		// https://login.marlboro.co.id/templates/UpdateProfileStart.aspx?id=<<SessionID>>&promoref=<<PromoRefkey>>
		sendRedirect($CONFIG['MOP_LANDING_URL']."/templates/UpdateProfileStart.aspx?id=".$_SESSION['MOP_SESSION']['SessionID']."&promoref=1");
		return $this->View->showMessage('Please wait while loading...','#');		
		
	}
	
	
	function m001ax(){
		$message = json_decode($this->badgeHelper->getMessageCount($this->user['register_id']));
		$inbox = $message->data[0]->total;
		header('Content-type: application/json');
		print_r(json_encode($inbox));exit;

	}
	
}