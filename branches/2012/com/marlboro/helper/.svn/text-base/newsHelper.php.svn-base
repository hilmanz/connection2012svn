<?php
include_once "../engines/Gummy.php";
include_once "../engines/functions.php";
include_once '../com/Application.php';

class newsHelper extends Application{
	var $regid;
	var $user;
	
	function __construct($register_id){
		$this->regid=$register_id;
		$this->getUserProfile();
	}
	
	function getUserProfile(){
		$qry = "SELECT m.*,c.city cityName FROM social_member m LEFT JOIN mop_city_lookup c ON m.city=c.id WHERE m.register_id='".$this->regid."' LIMIT 1";
		$this->open(0);
		$rs = $this->fetch($qry);
		$this->close();
		$this->user=$rs;
	}
	
	function getUserProfileForPublic($otherRegid){
		$qry = "SELECT	name,img,register_id FROM social_member WHERE register_id='".$otherRegid."' LIMIT 1";
		
		$this->open(0);
		$rs = $this->fetch($qry);
		$this->close();
		return $rs;
	}
	
	function messageForUser($regid=NULL,$subject,$message){
	
		if($regid==NULL) return false;
		$qry = "INSERT INTO social_message (message_to,message_from,message_date,message_subject,message_text)
				VALUES
							('{$regid}','0',NOW(),'{$subject}','{$message}');";
							$this->open(0);
							$this->query($qry);
							$this->close();
	}
	
	function activityNews($regid=NULL,$message,$activity_values=null){
	
		if($regid==NULL) return false;
		if($activity_values==NULL) $activity_values = 0;
		$qry = "INSERT INTO social_tradenews (tradenews_date,tradenews_content,user_id,activity_values) VALUES (NOW(),'{$message}',".$regid.",'{$activity_values}');";
		$this->open(0);
		$this->query($qry);
		$this->close();
	}
	
	function unlockBadge($badgeName,$badgeId){
		if( $badgeName != '' && intval($badgeId) > 0){
			//$msg = $this->user['name']." has unlocked \"".$badgeName."\" badge";
			
			//ini pake link
			$username = $this->user['name'];
			include_once '../config/message.php';
			$msg = $message['unlockBadge'];
			$this->activityNews($this->regid,$msg);
			//exit;
			
		}
	}
	
	function unlockBadgeMobile($badgeName,$badgeId){
		if( $badgeName != '' && intval($badgeId) > 0){
			//$msg = $this->user['name']." has unlocked \"".$badgeName."\" badge";
			$qry = "SELECT * FROM social_member WHERE register_id='".$this->regid."';";
			$this->open(0);
			$rs = $this->fetch($qry);
				$this->close();
			//ini pake link
			$username = $rs['name'];
			include_once '../config/message.php';
			$msg = $message['unlockBadge'];
			$this->activityNews($this->regid,$msg);
		
		
		}
	}
	
	function trade($need,$with,$auction_id){
			
		$dbcode = SCHEMA_CODE;
		$this->open(0);
		//badge name
		$qry="SELECT `name`,id FROM $dbcode.badge_catalog WHERE id='$need';";
		$rs = $this->fetch($qry);
		$need_name = $rs['name'];
		$need_id = $rs['id'];
		
		
		$qry="SELECT `name`,id FROM $dbcode.badge_catalog WHERE id='$with';";
		$rs = $this->fetch($qry);
		$with_name = $rs['name'];
		$with_id = $rs['id'];
		
		// echo mysql_error();
		// echo $with_name.' - '.$with_id.'<hr />';
		
		$qry="SELECT id,name,register_id,email FROM social_member WHERE register_id=(SELECT user_id FROM $dbcode.auction_post WHERE id='$auction_id');";
		$rs = $this->fetch($qry);
		$this->close();
		$trader = $this->user['name'];
		$traderId = $this->user['register_id'];
		$trader_id = $rs['register_id'];
		
		$activity_value = $with_id.'_'.$need_id;
		$fromEmail = 'connection2012-NoReply@marlboro.co.id';
			
		//seller - boxer				
		$toEmail =  $rs['email'];
		$username = $rs['name'];
		include_once '../config/message.php';
		$msg = $message['tradeBadge'];
		// return $msg;
		$this->activityNews($rs['register_id'],$msg,$activity_value);
		
		$msg = $message['inbox']['tradeBadge'];
		$subject = "YOU\'VE GOT A MATCH";
		$this->messageForUser($trader_id,$subject,$msg);
		
		//mail
		$msg = $mail['tradeBadge'];//bukan ini
		$data['mail'] = $this->sendGlobalMail($toEmail,$fromEmail,$msg);
		
		//buyer - user		
		$username =  $trader;
		$msg = $message['inbox']['tradeBadge'];
		$subject = "YOU\'VE TRADED YOUR BADGE";		
		$this->messageForUser($this->regid,$subject,$msg);
				
		$toEmail =  $rs['email'];
		$msg = $mail['tradeBadgeUser2'];
		$data['mail2'] = $this->sendGlobalMail($toEmail,$fromEmail,$msg);
	}
	
	
	function tradeBox($need,$with){
		//echo 'masuk news <hr/>';
		
		$dbcode = SCHEMA_CODE;
		$this->open(0);
		//badge name
		$qry="SELECT `name`,id FROM $dbcode.badge_catalog WHERE id='$need';";
		$rs = $this->fetch($qry);
		$need_name = $rs['name'];
		$need_id = $rs['id'];
		
		//echo mysql_error();
		//echo $need_name.' - '.$need_id.'<hr />';
		//exit;
		
		$qry="SELECT `name`,id FROM $dbcode.badge_catalog WHERE id='$with';";
		$rs = $this->fetch($qry);
		$this->close();
		$with_name = $rs['name'];
		$with_id = $rs['id'];
		
		
		$activity_value = $with_id.'_'.$need_id;
		$toEmail =  $this->user['email'];
		$fromEmail = 'connection2012-NoReply@marlboro.co.id';
		$username = $this->user['name'];
		include_once '../config/message.php';
		//activity news
		$msg = $message['tradeBadgeBox'];
		$this->activityNews($this->regid,$msg,$activity_value);
				
		//inbox news
		$msg = $message['inbox']['tradeBadgeBox'];
		$subject = "YOU\'VE TRADED YOUR BADGE";
		$this->messageForUser($this->regid,$subject,$msg);
		$msg = $mail['tradeBadgeBox'];
		$data['mail'] = $this->sendGlobalMail($toEmail,$fromEmail,$msg);
	
		
	}
	
	function gotHighestBid($auction_id,$outbid=false,$useridOutbid=0){
			
		$username = $this->user['name'];
		$sql = "SELECT item_name FROM ".SCHEMA_CONNECTION.".social_auction WHERE id={$auction_id} AND n_status=1 LIMIT 1";
		$this->open(0);
		$auctionData = $this->fetch($sql);
		$this->close();
		$auction_name = $auctionData['item_name'];
		$highest_name = $this->user['name'];
		include_once '../config/message.php';
		
		//activity news
		$msg = $message['highestBid'];
		//$this->activityNews($this->regid,$msg);
		
		
		//inbox news
		$msg = $message['inbox']['highestBid'];		
		$subject = "You have placed your bid";
		//$this->messageForUser($this->regid,$subject,$msg);
		
		if($outbid){
					if($useridOutbid!=0){
						$sql = "
							SELECT * FROM  ".SCHEMA_CONNECTION.".social_member WHERE id={$useridOutbid} AND n_status=1 LIMIT 1
							";
							$this->open(0);
							$qSocialMember =  $this->fetch($sql);
							$this->close();
						//highest_name
						//auction_name						
						$subject = 'auction outbid notification';				
						$msg = $message['inbox']['losingBid'];
						//$this->messageForUser($qSocialMember['register_id'],$subject,$msg);
					}
		
		}
		
	}
	
	
	
	function mobileGetUpdate($limit=9999){
		$que="SELECT * FROM social_tradenews a INNER JOIN social_member b
			  ON a.user_id = b.register_id 
			  ORDER BY tradenews_date DESC LIMIT $limit";
		$this->open(0);
		$rs=$this->fetch($que,1);
		$this->close();
		
		$num = count($rs);
		$data = array();
		for($i=0;$i<$num;$i++){
			$date = $this->ago(strtotime($rs[$i]['tradenews_date']));
			$data[$i]['time'] = $date;
			$data[$i]['id'] = $rs[$i]['tradenews_id'];
			$data[$i]['user'] = $rs[$i]['name'];
			$data[$i]['description'] = strip_tags($rs[$i]['tradenews_content']);
		}
		
		/*
		$arr[0] = array("id"=>"1001",
					  "user"=>"Annisa",
					  "description"=>htmlspecialchars("Lorem's & foo ipsum dolor sit amet"),
					  "time"=>"1 hour ago"
					 );
		*/
		
		return $data;
	}
	
	function newUser(){
	
		$username = $this->user['name'];
		$cityName = $this->user['cityName'];
		include_once '../config/message.php';
		$msg = $message['newUser'];
		$this->activityNews($this->regid,$msg);
	}
	
	function editAvatar(){
		$sex = ($this->user['sex'] == 'M')? 'His' : 'Her';
		$msg = "{$this->user['name']} has just updated {$sex} profile pic!";
		$this->activityNews($this->regid,$msg);
	}
	
	function ago($time)
	{
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");

	   $now = time();

		   $difference     = $now - $time;
		   $tense         = "ago";

	   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		   $difference /= $lengths[$j];
	   }

	   $difference = round($difference);

	   if($difference != 1) {
		   $periods[$j].= "s";
	   }
		
		if($j > 2){
			return date("d/m", $time);
		}else{
			return "$difference $periods[$j] ago ";
		}
	}
	
	function sendGlobalMail($to,$from,$msg){
		
		GLOBAL $ENGINE_PATH;
		require_once $ENGINE_PATH."Utility/Mailer.php";
		// print_r($ENGINE_PATH."Utility/Mailer.php");exit;
		// if(file_exists($ENGINE_PATH."Utility/Mailer.php"))echo 'ada';exit;
		$mail = new Mailer();
		// $mail->setDefaultHeaders();
		// $mail->setSender($player);
		$mail->setRecipient($to);
		// $mail->setRecipient('bummi@kana.co.id');
		$mail->setSubject('CONNECTIONS Notification');
		$mail->setMessage($msg);
		$result = 	$mail->send();
		if($result) return array('message'=>'success send mail');
		else return array('message'=>'error mail setting');
	}
}