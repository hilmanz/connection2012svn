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
	
	function unlockBadge($badgeName,$badgeId){
		if( $badgeName != '' && intval($badgeId) > 0){
			//$msg = $this->user['name']." has unlocked \"".$badgeName."\" badge";
			
			//ini pake link
			$badgeLink = "<a href=\'javascript:void(0);\' onclick=\'javascript:showBadgePopup(\"".$badgeId."\");\' >\"".$badgeName."\"</a>";
			$msg = "<a href=\'javascript:void(0);\' onclick=\'javascript:showProfilePopup(\"".$this->regid."\");\'>".$this->user['name']."</a> has unlocked $badgeLink badge";
			
			$qry = "INSERT INTO social_tradenews (tradenews_date,tradenews_content,user_id) VALUES (NOW(),'$msg',".$this->regid.");";
			$this->open(0);
			$this->query($qry);
			//echo mysql_error();
			//exit;
			$this->close();
		}
	}
	function unlockBadgeMobile($badgeName,$badgeId){
		if( $badgeName != '' && intval($badgeId) > 0){
			//$msg = $this->user['name']." has unlocked \"".$badgeName."\" badge";
			$qry = "SELECT * FROM social_member WHERE register_id='".$this->regid."';";
			$this->open(0);
			$rs = $this->fetch($qry);
			
			//ini pake link
			$badgeLink = "<a href=\'javascript:void(0);\' onclick=\'javascript:showBadgePopup(\"".$badgeId."\");\' >\"".$badgeName."\"</a>";
			$msg = "<a href=\'javascript:void(0);\' onclick=\'javascript:showProfilePopup(\"".$this->regid."\");\'>".$rs['name']."</a> has unlocked $badgeLink badge";
			
			$qry = "INSERT INTO social_tradenews (tradenews_date,tradenews_content,user_id) VALUES (NOW(),'$msg',".$this->regid.");";
		
			$this->query($qry);
			//echo mysql_error();
			//exit;
			$this->close();
		}
	}
	function trade($need,$with,$auction_id){
		//echo 'masuk news <hr/>';
		global $CONFIG;
		$dbcode = $CONFIG['DB_CODE'];
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
		$with_name = $rs['name'];
		$with_id = $rs['id'];
		
		//echo mysql_error();
		//echo $with_name.' - '.$with_id.'<hr />';
		
		$qry="SELECT id,name,register_id FROM social_member WHERE register_id=(SELECT user_id FROM $dbcode.auction_post WHERE id='$auction_id');";
		$rs = $this->fetch($qry);
		$trader = $rs['name'];
		$traderId = $rs['register_id'];
		$trader_id = $rs['id'];
		
		//echo mysql_error();
		//echo $trader.' - '.$traderId.'<hr />';
		//exit;
		//$msg = "\"".$this->user['name']."\" has traded \"".$with_name."\" for \"".$need_name."\" with \"".$trader."\"";
		
		//ini pake link
		$withLink = "<a href=\'javascript:void(0);\' onclick=\'javascript:showBadgePopup(\"".$with_id."\",".$this->user['id'].");\' >".$with_name."</a>";
		$needLink = "<a href=\'javascript:void(0);\' onclick=\'javascript:showBadgePopup(\"".$need_id."\",$trader_id);\' >".$need_name."</a>";
		$msg = "<a href=\'javascript:void(0);\' onclick=\'javascript:showProfilePopup(\"".$this->regid."\");\'>\"".$this->user['name']."\"</a> has traded \"".$withLink."\" for \"".$needLink."\" with <a href=\'javascript:void(0);\' onclick=\'javascript:showProfilePopup(\"".$traderId."\");\'>\"".$trader."\"</a>";
		
		$qry = "INSERT INTO social_tradenews (tradenews_date,tradenews_content,user_id) VALUES (NOW(),'$msg',".$this->regid.");";
		$this->query($qry);
		
		$qry = "INSERT INTO social_message (message_to,message_from,message_date,message_subject,message_text)
							VALUES
							('$trader_id','0',NOW(),'Badge Trade','Your badge $with_name have been successful traded with $need_name by ".$this->user['name']."');";
		$this->query($qry);
		
		$this->close();
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
		$msg = "<a href=\'javascript:void(0);\' onclick=\'javascript:showProfilePopup(\"".$this->regid."\");\'>".$this->user['name']."</a> is now a new connection from ".$this->user['cityName']."!";
		$qry = "INSERT INTO social_tradenews (tradenews_date,tradenews_content,user_id) VALUES (NOW(),'$msg',".$this->regid.");";
		$this->open(0);
		$this->query($qry);
		$this->close();
	}
	
	function editAvatar(){
		$sex = ($this->user['sex'] == 'M')? 'His' : 'Her';
		$msg = "<a href=\'javascript:void(0);\' onclick=\'javascript:showProfilePopup(\"".$this->regid."\");\'>".$this->user['name']."</a> has just updated $sex profile pic!";
		$qry = "INSERT INTO social_tradenews (tradenews_date,tradenews_content,user_id) VALUES (NOW(),'$msg',".$this->regid.");";
		$this->open(0);
		$this->query($qry);
		$this->close();
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
}