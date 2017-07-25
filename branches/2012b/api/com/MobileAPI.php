<?php 
define('BANNED_TIME',1);
include_once "BadgeModel.php";
include_once "../com/marlboro/helper/BadgeHelper.php";
include_once "../com/marlboro/helper/newsHelper.php";
class MobileAPI extends BadgeAPI{
	var $model;
	var $time_banned = 0; //10 minutes banned time
	var $retry = 0;
	var $next_allowed_time = 0;
	var $helper;
	var $news;
	function __construct(){
		parent::__construct();
		$this->helper = new BadgeHelper('badge');
		$this->news = new newsHelper(null);
	}
	
	function get_updates(){
		$user_id = $_REQUEST['UserId'];
		
		$limit = 20;
		$arr = $this->news->mobileGetUpdate($limit);
		/*
		$arr[0] = array("id"=>"1001",
					  "user"=>"Annisa",
					  "description"=>htmlspecialchars("Lorem's & foo ipsum dolor sit amet"),
					  "time"=>"1 hour ago"
					 );
		$arr[1] = array("id"=>"1002",
					  "user"=>"Roger",
					  "description"=>htmlspecialchars("Lorem ipsum dolor sit amet"),
					  "time"=>"2 hour ago"
					 );
					 
		$arr[2] = array("id"=>"1003",
					  "user"=>"Fany",
					  "description"=>htmlspecialchars("Lorem ipsum dolor sit amet"),
					  "time"=>"2 hour ago"
					 );
		*/
		$counts = sizeof($arr);
		$total_rows = 20;
		return $this->toResponse($_REQUEST['method'], $arr,$counts,$total_rows);
	}
	function get_badges(){
		$user_id = $_REQUEST['UserId'];
		$rs = $this->helper->get_user_badges($user_id);
		$o = json_decode($rs);
		if($o->status=="1"){
			$data = $o->data;
			$arr = array();
			foreach($data as $d){
				if($d->img==NULL){
					$d->img = "http://marlboro.co.id/static/64x64.png";
				}
				$arr[] = array("id"=>$d->badge_id,
					  "img"=>$d->img,
					  "count"=>$d->total,
					  "categoryID"=>$d->categoryID,
					  "categoryName"=>$d->categoryName,
					  "description"=>$d->description
					 );
			}
			$counts = sizeof($arr);
			return $this->toResponse($_REQUEST['method'], $arr,$counts);
		}else{
			return $this->toResponse($_REQUEST['method'], array("status"=>"0","message"=>"no data"));
		}
	}
	function get_badge_detail(){
		$badge_id = $_REQUEST['badge_id'];
		
		$rs = $this->helper->get_badge_detail($badge_id);
		$o = json_decode($rs);
	
		if($o->status=="1"){
			
			$d = $o->data;
			$data = array("id"=>$d->badge_id,
					"name"=>$d->name,
					  "img"=>$d->img,
					  "description"=>$d->description,
					  "categoryID"=>$d->categoryID,
					  "categoryName"=>$d->categoryName
					 );
					
			return $this->toResponse($_REQUEST['method'], array("status"=>"1","data"=>$data));
			
		}else{
			return $this->toResponse($_REQUEST['method'], array("status"=>"0","message"=>"no data"));
		}
	}
	
	function get_codes(){
		$str_code = $_POST['InputCode'];
		$user_id = $_POST['UserId']; //use mop RegistrationID
		$this->news->regid = $user_id;
		
		if($this->not_empty($str_code)&&$this->not_empty($user_id)){
			// redeem api call here
			//$arr = $this->redeem_code($user_id,$str_code);
			$resp = $this->helper->redeem_code($user_id,$str_code);
			
			$o = json_decode($resp);
			if($o->status=="1"){
				$arr['status']="1";
				$arr['message'] = "Badge redeemed successfully !";
				
				$this->news->unlockBadgeMobile($o->data->badge->name,$o->data->badge->id);
			}else if($o->status=="4"){
				$arr['status']="4";
				$arr['message'] = "you need to be verified member before redeemed the code.";
				
				$this->news->unlockBadgeMobile($o->data->badge->name,$o->data->badge->id);
			}else{
				$arr['status']="0";
				$arr['message'] = "Invalid Code or the code is already expired";
			}
			//-->
		}else{
			$arr['status'] = -1;
			$arr['message'] = "Invalid Input";
		}
		return $this->toResponse($_REQUEST['method'], $arr);
	}
	
	function redeem_code($user_id,$str_code){
		$user_id = mysql_escape_string($user_id);
		$str_code = mysql_escape_string($str_code);
		if(strcmp($str_code,"12345678")==0){
			$arr['status'] = 1;
			$arr['message'] = "Submit Code Success";
		}else{
			$arr['status'] = 0;
			$arr['message'] = "The code is invalid or already expired";
		}
		return $arr;
	}
	function code_info(){
		$kode = mysql_escape_string($_REQUEST['kode']);
		$rs = $this->helper->code_info($kode);
		return $rs;
	}
	function not_empty($str){
		if(is_string($str)){
			if(strlen($str)>0){
				return true;
			}
		}else{
			if($str!=NULL){
				return true;
			}
		}
	}
	function toResponse($method_name,$data,$counts=0,$total_rows=0,$next_url="",$previous_url=""){
		$arr[$method_name] = array();
		if($counts!=NULL){
			$arr[$method_name]['count'] = intval($counts);
		}
		if($total_rows!=NULL){
			$arr[$method_name]['total_rows'] = intval($total_rows);
		}
		if(strlen($next_url)>0){
			$arr[$method_name]['next_url'] = $next_url;
		}
		if(strlen($previous_url)>0){
			$arr[$method_name]['previous_url'] = $previous_url;
		}
		$arr[$method_name]['data'] = $data;
		return json_encode($arr);
	}
	
}
?>
