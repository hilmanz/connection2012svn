<?php 
define('BANNED_TIME',1);
include_once "BadgeModel.php";
class BadgeAPI{
	var $model;
	var $time_banned = 0; //10 minutes banned time
	var $retry = 0;
	var $next_allowed_time = 0;
	var $SCHEMA_CODE;
	var $SCHEMA_CONNECTION;
	var $dateHelper;
	function __construct(){
		// global $SCHEMA_CODE;
		$this->SCHEMA_CODE = SCHEMA_CODE;
		$this->SCHEMA_CONNECTION = SCHEMA_CONNECTION;
		if($this->next_allowed_time==0){
			$this->next_allowed_time = $_SESSION[sha1('next_allowed_time')];		
		}
		$this->time_banned = (60*BANNED_TIME);
		$this->model = new BadgeModel();
		
		require_once APP_PATH.APPLICATION."/helper/dateHelper.php";
		$this->dateHelper = new dateHelper();
	}
	function run(){
		$methodName = mysql_escape_string($_REQUEST['method']);
		print $this->execute($methodName);
	}
	function execute($methodName){
		if(method_exists($this, $methodName)){
			return $this->$methodName();
		}else{
			return "gak ada methodnya";
		}
	}
	function removeBadString($str){
	$cleanStr = htmlspecialchars(str_replace(array('/','\\'),'',stripslashes(strip_tags($str))));
	return $cleanStr;	
	}
	
	function check(){
		$_SESSION[sha1('retry')] = intval($_SESSION[sha1('retry')]);
		
		if($_SESSION[sha1('retry')]==3||$_SESSION[sha1('next_allowed_time')]>time()){
			if($_SESSION[sha1('next_allowed_time')]==0){
				$_SESSION[sha1('next_allowed_time')]=time()+(60*BANNED_TIME);
				
			}else{
				
				if($_SESSION[sha1('next_allowed_time')]<=time()){
					$_SESSION[sha1('next_allowed_time')]=0;
					$_SESSION[sha1('retry')] = 0;
				}
			}
			//print $_SESSION[sha1('next_allowed_time')]." vs ".time()."<br/>";
			//print "banned for ".BANNED_TIME." minutes";
			return $this->outputMessage(401,'You have tried 3 times, please wait for '.BANNED_TIME.' minutes and try again !',$data);
		}
		$kode = $_REQUEST['kode'];
		
		$kode = strtolower($kode);
		if(eregi("([0-9a-z]+)",$kode)&&strlen($kode)==8){
			$conn = open_db(0);
			$sql = "SELECT kode FROM ".$this->SCHEMA_CODE.".badge_code WHERE kode='".mysql_escape_string($kode)."' LIMIT 1";
			$q = mysql_query($sql,$conn);
			
			$f = mysql_fetch_assoc($q);			
			mysql_free_result($q);
			mysql_close($conn);
			if(strtolower($f['kode'])==strtolower($kode)){
				return $this->outputMessage(1,'Kode Cocok',$f);
			}else{
				$_SESSION[sha1('retry')]++;
				
				return $this->outputMessage(400,'This code is invalid, please input the correct code',$data);
			}
		}else{
			$_SESSION[sha1('retry')]++;
			return $this->outputMessage(400,'This code is invalid, please input the correct code',$data);
		}
	}
	
	function code_info(){
		$kode = $_REQUEST['kode'];
		$kode = strtolower($kode);
		if(eregi("([0-9a-z]+)",$kode)&&strlen($kode)==8){
			$conn = open_db(0);
			$sql = "SELECT kode,channel FROM ".$this->SCHEMA_CODE.".badge_code WHERE kode='".mysql_escape_string($kode)."' LIMIT 1";
			$q = mysql_query($sql,$conn);
			
			$f = mysql_fetch_assoc($q);			
			mysql_free_result($q);
			mysql_close($conn);
			if(strtolower($f['kode'])==strtolower($kode)){
				$detail = array();
				$detail['kode'] = $f['kode'];
				if($f['type']==1){
					$detail['type'] = "reusable";
				}else{
					$detail['type'] = "one time only";
				}
				
				$sql = "SELECT user_id, redeem_time FROM ".$this->SCHEMA_CODE.".badge_redeem WHERE kode='".mysql_escape_string($detail['kode'])."'";
				$conn = open_db(0);
				$q = mysql_query($sql,$conn);
				$redeemed = array();
				while($fetch = mysql_fetch_assoc($q)){
					$redeemed[] = $fetch['user_id'];
					$redeemed_time[] = $fetch['redeem_time'];
				}
				mysql_free_result($q);
				$detail['redeemed_by'] = $redeemed;
				$detail['redeemed_time'] = $redeemed_time;
				$detail['booked_by'] = array();
				
				//if($f['channel']==8||$f['channel']==9){
					$sql = "SELECT email FROM ".$this->SCHEMA_CONNECTION.".ipad_code_registration WHERE codeid='".mysql_escape_string($detail['kode'])."' LIMIT 1";
					
					$q = mysql_query($sql,$conn);
					
					$fetch = @mysql_fetch_assoc($q);
					if($fetch['email']!=null){
						$detail['booked_by'][] = $fetch['email'];
					}
					mysql_free_result($q);
					
					$sql = "SELECT email FROM ".$this->SCHEMA_CONNECTION.".ipad_code_quiz WHERE codeid='".mysql_escape_string($detail['kode'])."' LIMIT 1";
					$q = mysql_query($sql,$conn);
					$fetch = @mysql_fetch_assoc($q);
					if($fetch['email']!=null){
						$detail['booked_by'][] = $fetch['email'];
					}
					mysql_free_result($q);
				//}
				
				
				mysql_close($conn);
				return $this->outputMessage(1,'Kode Ditemukan.',$detail);
				
			}else{
				$_SESSION[sha1('retry')]++;
				return $this->outputMessage(400,'This code is invalid, please input the correct code',$data);
			}
		}else{
			$_SESSION[sha1('retry')]++;
			return $this->outputMessage(400,'This code is invalid, please input the correct code',$data);
		}
	}
	
	function outputMessage($statusCode,$message,$data){
		$arr = array("status"=>$statusCode,"message"=>$message,"data"=>$data);
		return json_encode($arr);
	}
	function redeem_code(){
		//$this->badge_cap_check();
		
		//$user_id = intval($user_id);
		$user_id = $_REQUEST['user_id'];
		$kode = $_REQUEST['kode'];
		$kode = $kode;
		$kode = mysql_escape_string($kode);
		
		if(eregi("([0-9]+)",$user_id)&&eregi("([0-9a-z]+)",$kode)&&strlen($kode)==8){
			//cek dulu apakah ini user yang berhak ?
			$conn = open_db(0);
			$sql = "SELECT register_id,n_status,email FROM ".$this->SCHEMA_CONNECTION.".social_member 
					WHERE register_id=".mysql_escape_string($user_id)." 
					";
			// print_r($sql);exit;
			$userinfo=fetch($sql, $conn);
			
			if($userinfo['n_status']==0){
				return $this->outputMessage(4,'Unverified User is disallowed to redeem the code',array($user_id,$kode));
			}
			//cek juga apakah kode ini ada di daftar registrasi Ipad
			$sql = "SELECT channel FROM ".$this->SCHEMA_CODE.".badge_code WHERE kode='".$kode."' LIMIT 1";
			$_cek = fetch($sql,$conn);
			close_db($conn);
			
			if($_cek['channel']==8||$_cek['channel']==9){
				$sql = "SELECT codeid,email FROM ".$this->SCHEMA_CONNECTION.".ipad_code_registration WHERE codeid='".$kode."' LIMIT 1";
				$conn = open_db(0);
				$ipad = fetch($sql,$conn);
				close_db($conn);
				
				if($userinfo['email']!=$ipad['email']&&strlen($ipad['codeid'])>0){
					return $this->outputMessage(0,'This code has been booked.',array($user_id,$kode));
				}
			}
			
			$data = $this->code_valid($user_id,$kode);
			
			if($data!=null){
				if($data['owned']=="1"){
					return $this->outputMessage(2,"Sorry , You can't enter the same code twice ",array($user_id,$kode));
				}else if($data['expired']=="1"){
					return $this->outputMessage(3,'Sorry, the code you have entered is expired',array($user_id,$kode));
				}else{
					return $this->getBadge($user_id,$kode,$data['tier']);
				}
			}else{
				return $this->outputMessage(0,'Invalid code, Please verify that you have entered the correct code.',array($user_id,$kode,$data));
			}
		}else{
			return $this->outputMessage(0,'You have typed in a wrong format, please input the correct code',array($user_id,$kode));
		}
	}
	function code_valid($user_id,$kode){
		$conn = open_db(0);
		//check if it's exist
		$sql = "SELECT COUNT(id) as total FROM ".$this->SCHEMA_CODE.".badge_redeem WHERE kode='".$kode."' LIMIT 1";
		$q = mysql_query($sql,$conn);
		
		$f = mysql_fetch_assoc($q);
		
		if($f['total']>0){
			$code_used = true;
		}else{
			$code_used = false;
		}
		
		if($f!=NULL){
		
			//check if it's owned by the user
			//check if it's exist
			$sql = "SELECT user_id FROM ".$this->SCHEMA_CODE.".badge_redeem WHERE kode='".$kode."' AND user_id=".$user_id." LIMIT 1";
			$q = mysql_query($sql,$conn);
			$f = mysql_fetch_assoc($q);
			
			
			if($f['user_id']==$user_id){
				$code_owned = true;
			}else{
				$code_owned = false;
			}
			//detail tentang kode
			$sql = "SELECT * FROM ".$this->SCHEMA_CODE.".badge_code WHERE kode='".$kode."' 
					AND start_date <= '".date("Y-m-d H:i:s")."'  
					LIMIT 1";
			// $sql = "SELECT * FROM ".$this->SCHEMA_CODE.".badge_code WHERE kode='".$kode."' 
					// AND start_date >= '".date("Y-m-d H:i:s")."'  AND end_date <= '".date("Y-m-d H:i:s")."' 
					// LIMIT 1";
			// print_r($sql);
			$q = mysql_query($sql,$conn);
			$kode_info = mysql_fetch_assoc($q);
			
			if($kode_info==null){
				//cek apakah kodenya valid tapi sudah expired ?
				$sql = "SELECT * FROM ".$this->SCHEMA_CODE.".badge_code WHERE kode='".$kode."' 
					AND end_date <= '".date("Y-m-d  H:i:s")."'
					LIMIT 1";
				$q = mysql_query($sql,$conn);
				$kode_info = mysql_fetch_assoc($q);
				if($kode_info){
					$kode_info['expired'] = 1;
				}
			}
			
			mysql_free_result($q);
			mysql_close($conn);
			if($kode_info){
				if($code_used&&!$code_owned&&$kode_info['type']==1){
					//print "1";
					return $kode_info;
				}else if($code_used&&$code_owned){
					//print "2";
					$kode_info['owned']=1;
					return $kode_info;
					//return $kode_info;
				}else if(!$code_used){
					//print "3";
					
					return $kode_info;
				}else{
					
					//code is used
					return null;
				}
			}
		}
	
		return null;
	}
	function get_badge_detail(){
		$badge_id = $_REQUEST['badge_id'];
		
		$sql = "SELECT a.id as badge_id,a.name,a.image as img,a.description,
				b.id as categoryID, 
				b.name as categoryName
				FROM ".$this->SCHEMA_CODE.".badge_catalog a
				INNER JOIN ".$this->SCHEMA_CODE.".badge_series b
				ON a.series_type = b.id
				WHERE a.id=".intval($badge_id)." 
				LIMIT 1";
		
		$conn = open_db(0);
		$rs = fetch($sql,$conn);
		mysql_close($conn);
		if($rs['badge_id']!=null){
			return $this->outputMessage(1,'Success',$rs);
		}else{
			return $this->outputMessage(-1,'Badge not found',array("badge_id"=>$badge_id));
		}
	}
	function getBadge($user_id,$kode,$tier){
		//check apakah kode ini masuk ke kode spesial.. yang bisa langsung redeem 1 badge.
		$conn = open_db(0);
		$sql = "SELECT * FROM ".$this->SCHEMA_CODE.".special_code WHERE kode='".mysql_escape_string($kode)."' LIMIT 1";
		
		$_cek = fetch($sql,$conn);
		if($_cek['badge_id']>0){
			$sql = "SELECT a.id,a.name,a.prob_rate,a.tier,a.series_type 
				FROM ".$this->SCHEMA_CODE.".badge_catalog a WHERE id=".$_cek['badge_id']." LIMIT 1";
			$rs = fetch_many($sql,$conn);
		}else{
			//change system.. no tier but type,, refferer table to badge_catalog_type_reference where type = tier
			$sql = "
				SELECT 
					badgeMaster.id,
					badgeMaster.name,
					badgeRef.prob_rate,
					badgeRef.type as tier,
					badgeMaster.series_type 
				FROM ".$this->SCHEMA_CODE.".badge_catalog badgeMaster
					INNER JOIN ".$this->SCHEMA_CODE.".badge_catalog_type_reference badgeRef ON badgeMaster.id=badgeRef.id
				WHERE 
					badgeRef.type = ".intval($tier)." 
					AND badgeRef.prob_rate > 0.0		
				";
			$rs = fetch_many($sql,$conn);
		}
		$qProfile = "SELECT * FROM ".$this->SCHEMA_CONNECTION.".social_member 
				WHERE register_id=".$user_id." LIMIT 1	";
		// print_r($sql);exit;
		$userinfo=fetch($qProfile, $conn);
		// print_r($sql);exit;
		if(sizeof($rs)){
			foreach($rs as $n=>$v){
				$rs[$n]['weight'] = $v['prob_rate']*rand(1,12);
			}
			$rs = array_sort($rs,'weight');
			$badge = $rs[0];
			
			//cek universal code
			if($badge['id']==13){
				
				//cek apakah kode universal masih tersedia?
				$tersedia = false;
				$sql = "SELECT * FROM
							(SELECT COUNT(*) AS total FROM ".$this->SCHEMA_CODE.".badge_redeem WHERE kode = '".mysql_escape_string($kode)."') a,
							(SELECT * FROM ".$this->SCHEMA_CODE.".universal_cap WHERE kode='".mysql_escape_string($kode)."') b;";
				$rs = fetch($sql,$conn);
				if(intval($rs['total']) < intval($rs['cap'])){
					$tersedia = true;
				}
				
				//cek apakah user sudah pernah dapat universal code
				$belumdapat =false;
				$sql = "SELECT COUNT(*) AS total FROM ".$this->SCHEMA_CODE.".badge_redeem WHERE kode='".mysql_escape_string($kode)."' AND user_id='".$user_id."';";
				$rs = fetch($sql,$conn);
				if(intval($rs['total']) == 0){
					$belumdapat = true;
				}
				
				if($tersedia && $belumdapat){
				
					return $this->outputMessage(9,'Universal Badge purchased successfully !',array("user_id"=>$user_id,"kode"=>$kode,"badge"=>$badge));
				
				}else{
					return $this->outputMessage(0,'This code is invalid, please input the correct code',array("user_id"=>$user_id,"kode"=>$kode,"badge"=>$badge));
				}
			}else{
				//insert the badge into user inventory
				if($this->purchase_badge($user_id,$kode,$badge)){
						
							$toEmail = $userinfo['email'];
							$fromEmail = 'connection2012-NoReply@marlboro.co.id';
							$subject = 'unlock badge';
							$username = $userinfo['name']; //username
							$badgeName = $badge['name']; //badge name
							
							include_once '../config/message.php';
							
							require_once APP_PATH.APPLICATION."/helper/newsHelper.php";
							$news = new newsHelper($user_id);
						
							$msg = $message['unlockBadge'];
							$news->activityNews($user_id,$msg,$badge['id']);
							$msg = $message['inbox']['unlockBadge'];
							$news->messageForUser($user_id,$subject,$msg);
							$msg = $mail['unlockBadge'];
							$emailthis = $this->sendGlobalMail($toEmail,$fromEmail,$msg);
				
					return $this->outputMessage(1,'Badge purchased successfully !',array("user_id"=>$user_id,"kode"=>$kode,"badge"=>$badge));
				}else{
					return $this->outputMessage(0,'Badge cannot be purchased.',array("user_id"=>$user_id,"kode"=>$kode,"badge"=>$badge));
				}
			}
		}else{
			return $this->outputMessage(-1,'Badge not found',array("user_id"=>$user_id,"kode"=>$kode,"badge"=>$badge));
		}
		mysql_close($conn);
	}
	function badge_cap_check(){
			$badge_id = 11;
			$sql = "SELECT COUNT(badge_id) as total FROM ".$this->SCHEMA_CODE.".badge_inventory WHERE badge_id=".$badge_id;
			$conn = open_db(0);
			$cek = fetch($sql,$conn);
			if($cek['total']>100){
				$sql = "UPDATE ".$this->SCHEMA_CODE.".badge_catalog SET prob_rate=0.0 WHERE id=".$badge_id;
				mysql_query($sql,$conn);
			}
			mysql_close($conn);
		
	}
	function purchase_badge($user_id,$kode,$badge){
		$conn = open_db(0);
		$sql = "INSERT INTO ".$this->SCHEMA_CODE.".`badge_redeem`
            (`user_id`,
             `redeem_time`,
             `kode`)
			VALUES (".$user_id.",
			        NOW(),
			        '".$kode."')";
		$q = mysql_query($sql,$conn);
		//print mysql_error();
		$insert_id = mysql_insert_id();
		
		//print mysql_error();
		if($insert_id >0){
			$sql = "INSERT INTO ".$this->SCHEMA_CODE.".`badge_inventory`
            (`user_id`,
             `redeem_time`,
             `badge_id`,redeem_id)
			VALUES (".$user_id.",
			        NOW(),
			        ".intval($badge['id']).",".intval($insert_id).")";
				$q = mysql_query($sql,$conn);
				//print mysql_error();
				$n_badge = mysql_insert_id();
		}
		mysql_close($conn);
		if($n_badge>0){
			return true;
		}
	}
	function get_direct_badge(){
		$user_id = $_REQUEST['user_id'];
		$badge_id = intval($_REQUEST['badge_id']);
		$kode = $_REQUEST['kode'];
		$kode = strtolower($kode);
		$kode = mysql_escape_string($kode);
		
		if(intval($badge_id)>0){
			$conn = open_db(0);
			$sql = "SELECT register_id,n_status,email FROM ".$this->SCHEMA_CONNECTION.".social_member 
					WHERE register_id=".mysql_escape_string($user_id)." 
					";
			$userinfo=fetch($sql, $conn);
			
			if($userinfo['n_status']==0){
				return $this->outputMessage(4,'Unverified User is disallowed to redeem the code',array($user_id,$kode));
			}
			
			$sql = "SELECT * FROM ".$this->SCHEMA_CODE.".special_code WHERE kode='".mysql_escape_string($kode)."' LIMIT 1";
			$_cek = fetch($sql,$conn);
			$sql = "SELECT id,name,image,description,tier,series_type 
					FROM ".$this->SCHEMA_CODE.".badge_catalog WHERE id=".$badge_id." LIMIT 1";
			$badge = fetch($sql,$conn);
			mysql_close($conn);
			if(strtolower($kode)==strtolower($_cek['kode'])){
				$data = $this->code_valid($user_id,$kode);
				if($data!=null){
					if($data['owned']=="1"){
						//return $this->outputMessage(2,'Badge is already redeemed',array($user_id,$kode));
						$rs = false;
					}else if($data['expired']=="1"){
						//return $this->outputMessage(3,'Badge is expired',array($user_id,$kode));
						$rs = false;
					}else{
						$rs = $this->purchase_badge($user_id,$kode,$badge);
					}
				}
			}
			if($rs){
				return $this->outputMessage(1,'Badge purchased successfully !',array("user_id"=>$user_id,"kode"=>$kode,"badge"=>$badge));
			}else{
				return $this->outputMessage(2,'The badge is not available',array("user_id"=>$user_id,"badge"=>$badge));
			}
		}else{
			return $this->outputMessage(0,'Badge not found',array("user_id"=>$user_id));
		}
	}
	
	function get_yellow_cabs_badge_for_mobile(){
		
		
		$badge_id = 12;		
		$kode = "YELLOWCABS";
		$conn = open_db(0);
		$sql = "
		SELECT 
		email,id 
		FROM ".SCHEMA_REPORT.".tbl_ipad_data_email_entry_and_yellow_cab 
		WHERE
		eventtype='YC01'
		AND 
		n_status = 0
		AND
		not exists (SELECT 1 FROM ".SCHEMA_REPORT.".tbl_report_user_got_yellow_cab WHERE id=ipad_id ) ";
		$yellowCabDataEmail=fetch_many($sql, $conn);
			
		if(intval($badge_id)>0 && $kode=="YELLOWCABS" ){
			if($yellowCabDataEmail){
				foreach($yellowCabDataEmail as $val){
					$data[]['email'] = $val['email'];
					$data[]['status'] =  $this->getYellowCabBadge($val['email'],$kode,$badge_id,$val['id']);
					
					$conn = open_db(0);
					$sql = "
						UPDATE
						".SCHEMA_REPORT.".tbl_ipad_data_email_entry_and_yellow_cab 
						SET n_status = 1
						WHERE id ={$val['id']}
					";
					
					$updateFlagTable = mysql_query($sql,$conn);
				}
			}else $data = 'there is no data user to get yellow cab';
			return print_r($data);
		}else{
			$conn = open_db(0);
			foreach($yellowCabDataEmail as $val){
			$sql = "
						UPDATE
						".SCHEMA_REPORT.".tbl_ipad_data_email_entry_and_yellow_cab 
						SET n_status = 1
						WHERE id ={$val['id']}
					";
					
					$updateFlagTable = mysql_query($sql,$conn);
			}
		return $this->outputMessage(0,'Badge not found',array("user_id"=>$user_id));
		}
		
	}
	
	
	function getYellowCabBadge($email,$kode,$badge_id,$ipad_id){
	
			$conn = open_db(0);
			
			$sql = "SELECT * FROM ".$this->SCHEMA_CONNECTION.".social_member 
					WHERE email='".mysql_escape_string($email)."'  AND register_id <> 0  LIMIT 1
					";
			$userinfo=fetch($sql, $conn);
				// print_r($sql);exit;
			if($userinfo){
	
									
				$user_id = $userinfo['register_id'];
				
				if($userinfo['n_status']==0){
					return $this->outputMessage(4,'Unverified User is disallowed to redeem the code',array($user_id,$kode));
				}
				$badge['id'] = $badge_id;
				$rs = $this->purchase_badge($user_id,$kode,$badge);
				
					if($rs){
								
								//tbl_report_user_got_yellow_cab
								$sql = "
									INSERT IGNORE INTO 
									".SCHEMA_REPORT.".tbl_report_user_got_yellow_cab (user_id ,ipad_id)
									VALUES 
									({$user_id},{$ipad_id})
								";
								
								$insert_data_to_report_yellow_cabs = mysql_query($sql,$conn);
								
								
								$username = $userinfo['name'];
								$user_id = $userinfo['register_id'];
								$subject = 'Yellow Cabs Hunt Notification';
								include_once '../config/message.php';
								require_once APP_PATH.APPLICATION."/helper/newsHelper.php";
								$news = new newsHelper($user_id);
								$getBadgeActivity = 12;
								$msg = $message['afterNYCabHuntBadge'];
								$news->activityNews($user_id,$msg,$getBadgeActivity);
								
								$msg = $message['inbox']['afterNYCabHuntBadge'];
								$news->messageForUser($user_id,$subject,$msg);
	
						return $this->outputMessage(1,'Badge purchased successfully !',array("user_id"=>$user_id,"kode"=>$kode,"badge"=>$badge,"emailthis"=>$emailthis));
					} else return $this->outputMessage(2,'The badge is not available',array("user_id"=>$user_id,"badge"=>$badge));
			}else return $this->outputMessage(4,'there is no user',array($user_id,$kode));
	
	
	}
	
	function generate_code(){
		//ini_set("memory_limit","32M");
		$conn = open_db(0);
		$mask_table1 = array('k','a','9','4','G','2','6','z');
		$mask_table2 = array('r','4','h','1','y','z','7','w');
		$mask_table3 = array('5','z','1','3','s','d','8','6','x');
		$mask_table4 = array('1','2','3','4','5','6','7','8','9',
								'a','b','c','d','e','f','g','h','j',
								'k','l','m','n','p','q','r','s','t',
								'u','v');
		$amount = intval($_REQUEST['amount']);
		$tier = intval($_REQUEST['tier']);
		$type = intval($_REQUEST['type']);
		$channel = intval($_REQUEST['channel']);
		$is_wildcard = intval($_REQUEST['wildcard']);
		$start_date = $_REQUEST['startDate'];
		$end_date = $_REQUEST['expireDate'];
		$location = $_REQUEST['city'];
		$description = $_REQUEST['event'];
		//cap amountnya		
		if($amount>10000){
			$amount=10000;
		}		
		$kode_left = $amount;
		$new_code = array();
		while($kode_left>0){
			$sql="INSERT IGNORE INTO 
				".$this->SCHEMA_CODE.".badge_code(
				kode,
				channel,
				tier,
				is_wildcard,
				is_used,
				type,
				generated_date,
				n_status,start_date,end_date,location,description)
				VALUES";
			
			$t=0;
		
			while($kode_left>0){
				//$rr = rand(0,2);
				//if($rr==1){
				//	$kode = $mask_table1[$tier].$mask_table1[$channel];
				//}elseif($rr==2){
				//	$kode = $mask_table2[$tier].$mask_table2[$channel];
				//}else{
				//	$kode = $mask_table3[$tier].$mask_table3[$channel];
				//}
				$n=0;
				$kode="";
				while($n<8){
					$kode.=$mask_table4[rand(0,sizeof($mask_table4)-1)];
					$n++;
				}
				if($t>0){
					$sql.=",";
				}
				$t=1;
				$sql.="('".strtoupper($kode)."',".$channel.",".$tier.",".$is_wildcard.",0,".$type.",NOW(),1,
						'".$start_date."','".$end_date."','".$location."','".$description."')";
				$kode_left--;
				array_push($new_code,strtoupper($kode));
				//$new_code[] = strtoupper($kode);
			}
			//print $sql;
			$q = mysql_query($sql,$conn);
			
			//print mysql_affected_rows()." code generated<br/>---<br/>";
			if(mysql_affected_rows()>0){
				$kode_left=$amount-mysql_affected_rows();
			}else{
				$kode_left = 0;
			}
		}
		mysql_close($conn);
		return $this->outputMessage(1,'Code Generation done',$new_code);
		//return $sql;
		//return "generate code nih !";
	}
	
	function inputCodeBadge(){
	// kode 	channel 	tier 	is_wildcard 	
	// is_used 	type 0->1 time only, 1->reusable	
	// generated_date 	n_status 0->unpublished,1->published	
	// location 	start_date 	end_date 	description
		$kode = $_REQUEST['kode'];
		$channel = intval($_REQUEST['channel']);
		$tier = intval($_REQUEST['tier']);
		$is_wildcard = 0;
		$is_used = 0;
		$generated_date = date("Y-m-d");
		$n_status = 1;
		$type = intval($_REQUEST['type']);
		$location = $_REQUEST['location'];
		$start_date = $_REQUEST['start_date'];
		$end_date = $_REQUEST['end_date'];
		$description = $_REQUEST['description'];
		
		$conn = open_db(0);
		$sql="
				REPLACE INTO 
				".$this->SCHEMA_CODE.".badge_code(
				kode,
				channel,
				tier,
				is_wildcard,
				is_used,
				type,
				generated_date,
				n_status,start_date,end_date,location,description)
				VALUES
				(
				'{$kode}',
				{$channel},
				{$tier},
				{$is_wildcard},
				{$is_used},
				{$type},
				'{$generated_date}',
				'{$n_status}',
				'{$start_date}','{$end_date}','{$location}','{$description}'
				
				)
				";
		
		$q = mysql_query($sql,$conn);
		if($q)	return $this->outputMessage(1,'Code Generation done',$sql);
		else return $this->outputMessage(0,'Code Generation Failed',$sql);	
		mysql_close($conn);
			return $this->outputMessage(-1,'Wrong Parameter',NULL);	
	}
	
	
	function getCodeBadge($start=0, $limit=50){
		
		$conn = open_db(0);
		$sql = "
				SELECT * FROM ".$this->SCHEMA_CODE.".badge_code 
				WHERE 	n_status = '1'
				ORDER BY generated_date DESC
				";
			
		$rs = fetch_many($sql, $conn);
		mysql_close($conn);
		if($rs) return $this->outputMessage(1,'SUCCESS',$rs);
		else  return $this->outputMessage(0,'FAILED',NULL);
	}
	
	function getCodeBadgeByKode(){
		$kode= $_REQUEST['kode'];
		if($kode<>'') $kodeGet = "AND kode = '{$kode}'";
		else $kodeGet = "";
		
		$conn = open_db(0);
		$sql = "
				SELECT * FROM ".$this->SCHEMA_CODE.".badge_code 
				WHERE 	n_status = '1'
				{$kodeGet}
				ORDER BY generated_date DESC
				LIMIT 1";
			
		$rs = fetch($sql, $conn);
		mysql_close($conn);
		if($rs) return $this->outputMessage(1,'SUCCESS',$rs);
		else  return $this->outputMessage(0,'FAILED',NULL);
	}
	
	function getCodeBadgeByKodeForDelete(){
		$kode= $_REQUEST['kode'];
		if($kode<>'') $kodeGet = " kode = '{$kode}'";
		else  return $this->outputMessage(0,'FAILED To DELETE',NULL);
		
		$conn = open_db(0);
		$sql = "
				DELETE FROM ".$this->SCHEMA_CODE.".badge_code 
				WHERE {$kodeGet} ";
			
		$q = mysql_query($sql,$conn);
		mysql_close($conn);
		return $this->outputMessage(1,'SUCCESS',$rs);

	}
	
	function get_actual_inventory($registerid=NULL){
	
		if($registerid!=NULL) $user_id= $registerid;
		else $user_id = $_REQUEST['user_id'];
		if($user_id==0) return $this->outputMessage(0,'FAILED',NULL);
		// $user_id = $_REQUEST['user_id'];
		if(eregi("([0-9]+)",$user_id)){
			$conn = open_db(0);
			$sql = "SELECT a.badge_id,COUNT(a.badge_id) as total,b.name as nameBadge,
					b.description as description,
					b.series_type as categoryID,b.image as img,c.name as categoryName
					FROM ".$this->SCHEMA_CODE.".badge_inventory a INNER JOIN
					".$this->SCHEMA_CODE.".badge_catalog b ON 
					a.badge_id = b.id
					INNER JOIN 
					".$this->SCHEMA_CODE.".badge_series c
					ON b.series_type = c.id
					WHERE a.user_id=".intval($user_id)." GROUP BY badge_id 
					LIMIT 100";
			
			$rs = fetch_many($sql, $conn);
			
			$sql = "SELECT with_id,COUNT(with_id) as total 
					FROM ".$this->SCHEMA_CODE.".auction_post 
					WHERE user_id=".intval($user_id)." AND n_status=0
					GROUP BY with_id";
			
			$auction = fetch_many($sql,$conn);
			
			$sql = "
					SELECT id,register_id
					FROM ".$this->SCHEMA_CONNECTION.".social_member 
					WHERE register_id={$user_id}
					LIMIT 1
					";
			$q = mysql_query($sql);
			$social = mysql_fetch_object($q);
			
			$sql = "
					SELECT sum(amount)as amount , badge_id 
					FROM ".$this->SCHEMA_CONNECTION.".social_auction_bid 
					WHERE 
					n_status in (1,2)
					AND user_id={$social->id}
					GROUP BY user_id,badge_id
					";
			
			$auction2012 = fetch_many($sql,$conn);
			
			$items = array();$arrAuction2012=array();
			if(sizeof($auction)>0){
				foreach($auction as $item=>$val){
					$items[$val['with_id']] = $val['total'];
				}
				
				foreach($rs as $n=>$val){
					$rs[$n]['total'] -= intval($items[$rs[$n]['badge_id']]);
				}
				
				
			}
			
			if(sizeof($auction2012)>0){
			
				foreach($auction2012 as $item=>$val){
					$arrAuction2012[$val['badge_id']] = $val['amount'];
				}
				
				
				foreach($rs as $n=>$val){
					$rs[$n]['total'] -= $arrAuction2012[$rs[$n]['badge_id']];
				}
				
				
			}
			mysql_close($conn);
			// print_r($rs);exit;
			return $this->outputMessage(1,'SUCCESS',$rs);
		}else{
			return $this->outputMessage(0,'FAILED',NULL);
		}
	}
	function get_inventory($userid=NULL){
	// $user_id = $_REQUEST['user_id'];
	// return $user_id;
		if($userid!=NULL) $user_id= $userid;
		else $user_id = $_REQUEST['user_id'];
		if(eregi("([0-9]+)",$user_id)){
			$conn = open_db(0);
			$sql = "SELECT a.badge_id,COUNT(a.badge_id) as total,b.name as description,
					b.series_type as categoryID,b.image as img,c.name as categoryName
					FROM ".$this->SCHEMA_CODE.".badge_inventory a 
					INNER JOIN
					".$this->SCHEMA_CODE.".badge_catalog b ON 
					a.badge_id = b.id
					INNER JOIN 
					".$this->SCHEMA_CODE.".badge_series c
					ON b.series_type = c.id
					WHERE a.user_id=".intval($user_id)." GROUP BY badge_id 
					LIMIT 100";
			
			$rs = fetch_many($sql, $conn);
			mysql_close($conn);
			return $this->outputMessage(1,'SUCCESS',$rs);
		}else{
			return $this->outputMessage(0,'FAILED',NULL);
		}
	}
	/**
	 * 
	 * Searching the badge list owned by a user id
	 */
	function search(){
		$tier = intval($_REQUEST['tier']);
		$type = intval($_REQUEST['type']);
		$user_id = $_REQUEST['user_id'];
		if(eregi("([0-9]+)",$user_id)){
			if($type!=0&&$tier!=0){
				$sql = "SELECT a.id AS inventory_id,a.badge_id,b.name,b.tier,b.series_type 
					FROM ".$this->SCHEMA_CODE.".badge_inventory a
					INNER JOIN ".$this->SCHEMA_CODE.".badge_catalog b
					ON a.badge_id = b.id
					WHERE a.user_id=".$user_id." AND b.series_type=".$type." AND b.tier=".$tier." LIMIT 100";
			}else if($type!=0){
				$sql = "SELECT a.id AS inventory_id,a.badge_id,b.name,b.tier,b.series_type 
					FROM ".$this->SCHEMA_CODE.".badge_inventory a
					INNER JOIN ".$this->SCHEMA_CODE.".badge_catalog b
					ON a.badge_id = b.id
					WHERE a.user_id=".$user_id." AND b.series_type=".$type." LIMIT 100";
			}else{
				$sql = "SELECT a.id AS inventory_id,a.badge_id,b.name,b.tier,b.series_type 
					FROM ".$this->SCHEMA_CODE.".badge_inventory a
					INNER JOIN ".$this->SCHEMA_CODE.".badge_catalog b
					ON a.badge_id = b.id
					WHERE a.user_id=".$user_id." LIMIT 100";
			}
			$conn = open_db(0);
			$rs = fetch_many($sql, $conn);
			mysql_close($conn);
			if(sizeof($rs)>0){
				return $this->outputMessage(1,'SUCCESS',$rs);
			}else{
				return $this->outputMessage(0,'NOT FOUND',NULL);
			}
		}else{
			return $this->outputMessage(-1,'Invalid User ID',NULL);
		}
	}
	function search_badge_owners(){
		$badge_id = $_REQUEST['badge_id'];
		$exclude_user_id = $_REQUEST['exclude_user_id'];
		if(eregi("([0-9]+)",$badge_id)&&eregi("([0-9]+)",$exclude_user_id)){
			$sql = "SELECT user_id,badge_id,COUNT(badge_id) as total 
					FROM ".$this->SCHEMA_CODE.".badge_inventory WHERE badge_id=".$badge_id." AND user_id <> ".$exclude_user_id." GROUP BY user_id
					LIMIT 100";
			$conn = open_db(0);
			$rs = fetch_many($sql,$conn);
			mysql_close($conn);
			if(sizeof($rs)>0){
				return $this->outputMessage(1,'SUCCESS',$rs);
			}else{
				return $this->outputMessage(0,'NOT FOUND',NULL);
			}
		}else{
			return $this->outputMessage(-1,'Invalid Badge Code',NULL);
		}
	}
	
	function auction_post(){
		$need_id = $_REQUEST['need_id'];
		$with_id = $_REQUEST['with_id'];
		$user_id = $_REQUEST['user_id'];
		
		//testing
		// $need_id = 3;
		// $with_id = 1;
		// $user_id = 354860;
		
		if(eregi("([0-9]+)",$need_id)&&eregi("([0-9]+)",$with_id)&&eregi("([0-9]+)",$user_id)){
		
			//check badge si user ini.. cukup ato nggak.. pake yang actual
			$inventory = json_decode($this->get_actual_inventory($user_id));
				foreach($inventory->data as $val){
					$inven[$val->badge_id]=$val->total;
					
				}
				// print_r('<pre>');print_r($inven);exit;
			$inventory =null;
			$total_owned_badge = $inven[$with_id];
			$inven = null;
				$conn = open_db(0);
			//check yang ada di auction ada berapa..
			// $sql = "SELECT user_id,with_id,COUNT(with_id) as total 
					// FROM ".$this->SCHEMA_CODE.".auction_post WHERE with_id=".$with_id." 
					// AND user_id = '".$user_id."' GROUP BY user_id
					// LIMIT 1";
			
			// $rs = fetch($sql,$conn);
			
			// $total_auctioned = $rs['total'];
			// print_r('<pre>');print_r($total_auctioned);exit;
			if($total_owned_badge  > 0){
				$sql = "INSERT INTO ".$this->SCHEMA_CODE.".auction_post(user_id,need_id,with_id,posted_date,closed_time,n_status)
					VALUES('".$user_id."','".$need_id."','".$with_id."',NOW(),NULL,0)";
			
				$rs = mysql_query($sql,$conn);
				mysql_close($conn);
				if($rs){
					return $this->outputMessage(1,'SUCCESS',null);
				}else{
					return $this->outputMessage(0,'Failed',NULL);
				}
			}else{
				mysql_close($conn);
				return $this->outputMessage(0,"You don't have enough badge",NULL);
			}
			
		}else{
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
		}
	}
	function search_auction(){
		$need_id = $_REQUEST['need_id']; //2
		$with_id = $_REQUEST['with_id']; //7
		$exclude_user_id = $_REQUEST['exclude_user_id']; //330486
		
		if(eregi("([0-9]+)",$need_id)&&eregi("([0-9]+)",$with_id)&&eregi("([0-9]+)",$exclude_user_id)){
			$conn = open_db(0);
			//cari auction
			$sql = "SELECT id as auction_id,user_id,need_id,with_id
					FROM ".$this->SCHEMA_CODE.".auction_post 
					WHERE with_id=".$need_id." AND need_id=".$with_id." 
					AND user_id <> '".$exclude_user_id."' AND n_status=0
					LIMIT 100";
			
			$rs = fetch_many($sql,$conn);
			mysql_close($conn);
			
			if(sizeof($rs)>0){
				return $this->outputMessage(1,'SUCCESS',$rs);
			}else{
				return $this->outputMessage(0,'Not found',$rs);
			}			
		}else{
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
		}
	}
	
	// redeem prize / merchadise
	function getMerchandiseList(){
			$user_id= $_REQUEST['user_id'];
			$conn = open_db(0);
				$sql = "
					SELECT id,item_name,prefix_name,img,img_small
					FROM ".$this->SCHEMA_CODE.".merchandise_items 
					WHERE n_status=1 ";
			$merchandise = fetch_many($sql,$conn);
			
			$allBadges = json_decode($this->getAllBadgeDetail());
			
			foreach($allBadges->data as $key => $val){
				$badges[$val->badgeid] =$val->badgeurl;
			}
			$allBadges= null;
			$inventory = json_decode($this->get_actual_inventory($user_id));
			foreach($inventory->data as $val){
					$inven[$val->badge_id]=$val->total;
					
			}
			$inventory =null;
			$conn = open_db(0);
			if($merchandise) {
			foreach($merchandise as $key => $val ){
			$sql = "
					SELECT badge_id,badge_amount
						FROM ".$this->SCHEMA_CODE.".merchandise_badge_required 
						WHERE merchandise_id={$val['id']} ";

					$arrNeedBadge = fetch_many($sql,$conn);
					foreach($arrNeedBadge as $valNeed){
						$needBadge[]= $valNeed['badge_id']."_".$valNeed['badge_amount'];	
						$needBadgeImage[$valNeed['badge_id']] = $badges[$valNeed['badge_id']];	
						if($inven[$valNeed['badge_id']] >=$valNeed['badge_amount'] )  $isEnough[] = true;
						else $isEnough[] = false;
					}
					
					if(in_array(false,$isEnough))$issuficient_badge = true;				
					else $issuficient_badge = false;
					
					$arrNeedBadge=null;
					
					$currentStock = $this->getMerchandiseActualStock($val['prefix_name']);
										
					$merchandise[$key]['needBadge'] = implode(',',$needBadge);
					$merchandise[$key]['needBadgeImage'] = $needBadgeImage;
					$merchandise[$key]['issuficient_badge'] = $issuficient_badge;
					$merchandise[$key]['currentStock'] = $currentStock;
					$needBadgeImage=null;
					$needBadge=null;
					$isEnough=null;
			}
			// print_r($inven);exit;
					
			// print_r('<pre>');print_r($merchandise );exit;
			mysql_close($conn);
			return $this->outputMessage(1, "getMerchandiseList", $merchandise);
			
			}else return $this->outputMessage(0, "Merchandise Not Found",null);
	}
	
	function getMerchandiseById($merchandiseid=null,$prefix_name=null){
		if($merchandiseid!=null) $merchandise_id = $merchandiseid;
		else $merchandise_id = $_REQUEST['merchandise_id'];
	
		$queryAdd = null;
		if($merchandise_id!='' || $merchandise_id!=null) $queryAdd = "AND id={$merchandise_id}";
		if($prefix_name!=null) $queryAdd = "AND prefix_name='{$prefix_name}'";
		if($queryAdd==null) return $this->outputMessage(-1,'Wrong given parameters',NULL);
			$conn = open_db(0);
			
			$sql = "
					SELECT id,item_name,prefix_name,img,img_small
					FROM ".$this->SCHEMA_CODE.".merchandise_items 
					WHERE n_status=1  
					{$queryAdd}
					LIMIT 1";
			$val = fetch($sql,$conn);
			
			// print_r('<pre>');print_r($sql);exit;
			if($val) {
					$sql = "
					SELECT badge_id,badge_amount 
						FROM ".$this->SCHEMA_CODE.".merchandise_badge_required 
						WHERE merchandise_id={$val['id']} ";

					$arrNeedBadge = fetch_many($sql,$conn);
					foreach($arrNeedBadge as $valNeed){
						$needBadge[$valNeed['badge_id']] = $valNeed['badge_amount'];
					}
					$arrNeedBadge=null;
					$val['needBadge'] =$needBadge;
					$needBadge=null;
			
				// print_r('<pre>');print_r($val);exit;
			mysql_close($conn);
			return $this->outputMessage(1, "getMerchandiseByID", $val);
			}else return $this->outputMessage(0, "Merchandise required Not Found",null);
	}
	
	function getMerchandiseActualStock($prefix_name=null){
		// $prefix_name ='berlin-prize-fd';
		if($prefix_name==null) return false;
	
			$conn = open_db(0);
			
			$sql = "SELECT count(*) as total FROM   ".$this->SCHEMA_CONNECTION.".social_redeem WHERE prize='{$prefix_name}' ";
			$redemeed = fetch($sql,$conn);
			
			$sql = "SELECT stock FROM   ".$this->SCHEMA_CODE.".merchandise_items WHERE  prefix_name='{$prefix_name}'";
			$merch = fetch($sql,$conn);
			
		
			if($redemeed) {
				$currentStock = $merch['stock'] - $redemeed['total'];
					// print_r('<pre>');print_r($currentStock);exit;
				return $currentStock;
			}else return false;
	}
	
	function badge_redeemed(){
		$prize = $_REQUEST['prize'];
		$user_id = $_REQUEST['user_id'];
		$street = $_REQUEST['street'];
		$complex = $_REQUEST['complex'];
		$province = $_REQUEST['province'];
		$city = $_REQUEST['city'];
		$phone = $_REQUEST['phone'];
		$mobile = $_REQUEST['mobile'];
		$tshirt_type = '';
		$tshirt_size = '';	
		
		//$badges = explode(",",$badges);
		//testing
		
		// $prize = mysql_escape_string('berlin-prize-fd');
		// $user_id = 52488;
		$currentStock = $this->getMerchandiseActualStock($prize);
		if($currentStock<=0) return $this->outputMessage(2, "
		All the prizes available have been redeemed. <br />
		Want more prizes? Use your badges to bid at the auctions! ", NULL);
		//get merchandise required badge
		$needBadge = json_decode($this->getMerchandiseById(null,$prize));
	
		
		if($needBadge->status>0){
			$needBadge = $needBadge->data->needBadge;
			$inventory = json_decode($this->get_actual_inventory($user_id));
				foreach($inventory->data as $val){
					$inven[$val->badge_id]=$val->total;
					
				}
					// print_r('<pre>');print_r($inventory);exit;		
			$inventory =null;
			//cek badge is enough
			foreach($needBadge as $key => $val){
				if($inven[$key]>=$val) $isEnough[] = true;
				else $isEnough[] = false;
				$badges[] = $key;
			}
				
			$badges = implode(',',$badges);
			$inven = null;	
			$needBadge= null;
			// print_r('<pre>');print_r($badges);exit;	
			
			$conn = open_db(0);
			
			
			if(in_array(false,$isEnough)){
				$issuficient_badge = true;				
			}else{
				
				$sql = "SELECT count(much) as total 
						FROM ".$this->SCHEMA_CODE.".merchandise_transaction
						WHERE user_id ='".$user_id."' AND prize='".mysql_escape_string($prize)."'  AND n_status <> 2";
				$hasSamePrize = fetch_many($sql,$conn);
				// print_r('<pre>');print_r($hasSamePrize[0]['total']);exit;	
				//cek how many prize you have , max is 2
				if($hasSamePrize[0]['total']<2){
					$much = $hasSamePrize[0]['total'] +1;
					$sql = "INSERT INTO ".$this->SCHEMA_CODE.".merchandise_transaction(user_id,request_date,prize,n_status,much)
									VALUES('".$user_id."',NOW(),'".mysql_escape_string($prize)."',0,{$much})";
					$q = mysql_query($sql,$conn);
					$transaction_id = mysql_insert_id($conn);
					$issuficient_badge = false;		
				
				}else return $this->outputMessage(2, "You have reached the maximum number of prizes you can redeem. <br />Thank You for your participation.", $badges);
			}
	
			$is_ok = false;
			if($transaction_id>0){
				$sql = "SELECT id,badge_id,user_id,redeem_id,redeem_time FROM ".$this->SCHEMA_CODE.".badge_inventory 
				WHERE user_id=".$user_id." AND 
				badge_id IN (".$badges.") GROUP by badge_id";
				
				$badges = fetch_many($sql,$conn);
				// print_r('<pre>');print_r($badges);exit;	
				foreach($badges as $badge){
					if($badge['id']!=null){
						$sql = "DELETE FROM ".$this->SCHEMA_CODE.".badge_inventory 
						WHERE id=".$badge['id']." AND user_id=".$user_id."";
						mysql_query($sql,$conn);
						$sql = "INSERT INTO ".$this->SCHEMA_CODE.".merchandise_redeem(user_id,redeemed_date,badge_id,prize,transaction_id,redeem_id,redeem_time)
								VALUES(".$user_id.",NOW(),".$badge['badge_id'].",'".$prize."',".$transaction_id.",".$badge['redeem_id'].",'".$badge['redeem_time']."')";
					
						mysql_query($sql,$conn);
					}
				}
				$is_ok =true;
			}
	
			if($is_ok){
				$sql = " INSERT IGNORE INTO ".$this->SCHEMA_CONNECTION.".social_redeem
				(register_id ,	street, 	complex ,	province ,	city ,	phone, 	mobile ,	prize, 	submit_date ,	n_status, 	transaction_id ,	tshirt_type ,	tshirt_size)
				VALUES
				({$user_id},'{$street}','{$complex}','{$province}','{$city}','{$phone}','{$mobile}','{$prize}',NOW(),'1','{$transaction_id}','{$tshirt_type}','{$tshirt_size}')
				";			
				mysql_query($sql,$conn);
					// print_r('<pre>');print_r($sql);exit;
				
					$sql = "
									SELECT * FROM  ".$this->SCHEMA_CONNECTION.".social_member WHERE id={$user_id} AND n_status=1 LIMIT 1
								";
			
					$qSocialMember = fetch($sql,$conn);
					$sql = "
										SELECT * FROM  ".$this->SCHEMA_CODE.".merchandise_items WHERE prefix_name='{$prize}' AND n_status=1 LIMIT 1
									";
			
					$merch = fetch($sql,$conn);
					$merchandiseItem = $merch['item_name'];
					$toEmail = $qSocialMember['email'];
					$fromEmail = 'connection2012-NoReply@marlboro.co.id';
					$subject = 'connections notification';
					$username = $qSocialMember['name'];
					include_once '../config/message.php';
					require_once APP_PATH.APPLICATION."/helper/newsHelper.php";
					$news = new newsHelper($user_id);
				
					$msg = $message['successRedeem'];			
					$news->activityNews($user_id,$msg,$getBadgeActivity);
					
					$msg = $message['inbox']['successRedeem'];
					$news->messageForUser($user_id,$subject,$msg);
					
					$msg = $mail['successRedeem'];			
					$data['mail'] = $this->sendGlobalMail($toEmail,$fromEmail,$msg);

					
				return $this->outputMessage(1, "the badge successfully redeemed with a merchandise", array("badges"=>$badges,"transaction_id"=>$transaction_id));
			}else{
				if($issuficient_badge){
					return $this->outputMessage(3, "Not enough Badges", $isEnough);
				}else{
					return $this->outputMessage(2, "cannot save the transaction", $badges);
				}
			}
		}else{
			return $this->outputMessage(0, "badge-merchandise redeemed is failed", $badges);
		}
			
	mysql_close($conn);
	}
	
	
	function cancel_redeem(){
		$user_id = mysql_escape_string($_REQUEST['user_id']);
		$transaction_id = mysql_escape_string($_REQUEST['transaction_id']);
		$conn = open_db(0);
		//get all badges
		$sql="SELECT * FROM ".$this->SCHEMA_CODE.".merchandise_redeem WHERE transaction_id=".$transaction_id."";
		$badges = fetch_many($sql, $conn);
		$sql = "INSERT INTO ".$this->SCHEMA_CODE.".badge_inventory(user_id,redeem_time,badge_id,redeem_id) VALUES ";
		$n=0;
		foreach($badges as $badge){
			if($n>0){
				$sql.=",";
			}
			$sql.="('".$user_id."','".$badge['redeem_time']."',".$badge['badge_id'].",".$badge['redeem_id'].")";
			$n=1;
		}
		$q = mysql_query($sql,$conn);
		
		if($q){
			$sql = "DELETE FROM ".$this->SCHEMA_CODE.".merchandise_redeem WHERE user_id='".$user_id."' AND transaction_id = '".$transaction_id."'";
			mysql_query($sql,$conn);
			$sql = "UPDATE ".$this->SCHEMA_CODE.".merchandise_transaction SET n_status=2 
					WHERE user_id='".$user_id."' AND id='".$transaction_id."'";
			mysql_query($sql,$conn);
			$is_ok = true;
		}
	
		mysql_close($conn);
		if($is_ok){
			return $this->outputMessage(1, "Badge successfully returned !", $badges);
		}else{
			return $this->outputMessage(0, "cannot return the badge, the badge might be already exist in inventory", $badges);
		}
	}
	function approve_redeem(){
		$user_id = mysql_escape_string($_REQUEST['user_id']);
		$transaction_id = mysql_escape_string($_REQUEST['transaction_id']);
		$conn = open_db(0);
		$sql="SELECT * FROM ".$this->SCHEMA_CODE.".merchandise_transaction 
			  WHERE id='".$transaction_id."'";
		
		$trans = fetch($sql, $conn);
		
		if($trans['id']==$transaction_id&&$trans['user_id']==$user_id){
			$sql = "UPDATE ".$this->SCHEMA_CODE.".merchandise_transaction SET n_status=1 
					WHERE user_id='".$user_id."' AND id='".$transaction_id."'";
			$q = mysql_query($sql,$conn);
		}
		mysql_close($conn);
		if($q){
			return $this->outputMessage(1, "Merchandise Approved !", array("transaction_id"=>$transaction_id,"user_id"=>$user_id));
		}else{
			return $this->outputMessage(0, "Error", array("transaction_id"=>$transaction_id,"user_id"=>$user_id));
		}
	}
	function trade(){
		$need_id = $_REQUEST['need_id'];
		$with_id = $_REQUEST['with_id'];
		$user_id = $_REQUEST['user_id'];//buyer
		$auction_id = $_REQUEST['auction_id'];
		
		//testing
		// $need_id = 3;
		// $with_id = 1;
		// $user_id = 354860;
		// $auction_id = 37;
					
		if(eregi("([0-9]+)",$need_id)&&eregi("([0-9]+)",$with_id)&&eregi("([0-9]+)",$user_id)&&eregi("([0-9]+)",$auction_id)){
			$conn = open_db(0);
			//lihat detail tradenya, dan pastikan statusnya belum diambil orang
			$sql = "SELECT id as auction_id,user_id,with_id,need_id 
					FROM ".$this->SCHEMA_CODE.".auction_post 
					WHERE id=".$auction_id." AND n_status=0
					LIMIT 1";
			$rs = fetch($sql,$conn);
			
			//$rs --> seller
			if($rs['auction_id']!=NULL){
				//search auction id from the user
				$sql = "SELECT id as auction_id,user_id,with_id,need_id 
					FROM ".$this->SCHEMA_CODE.".auction_post 
					WHERE user_id=".$user_id." AND n_status=0
					AND need_id='".$need_id."' AND with_id='".$with_id."'
					LIMIT 1";
				
				$rs2 = fetch($sql,$conn); 
				//rs2 == buyer
				if($rs2['auction_id']>0){
					//proses tradenya
					//pastikan si buyer masih punya trade bersangkutan
					$sql = "SELECT user_id,badge_id,COUNT(badge_id) as total 
						FROM ".$this->SCHEMA_CODE.".badge_inventory WHERE badge_id=".$rs['need_id']." 
						AND user_id = '".$user_id."' GROUP BY user_id
						LIMIT 1";
				
					$buyer = fetch($sql,$conn);
					//pastikan si seler jg punya cukup badge
					$sql = "SELECT user_id,badge_id,COUNT(badge_id) as total 
						FROM ".$this->SCHEMA_CODE.".badge_inventory WHERE badge_id=".$rs['with_id']." 
						AND user_id = '".$rs['user_id']."' GROUP BY user_id
						LIMIT 1";
					$seller = fetch($sql,$conn);
					
					if($buyer['total']>0&&$seller['total']>0){
						//yes.. we can do trade
						$sql = "SELECT id,user_id,badge_id
						FROM ".$this->SCHEMA_CODE.".badge_inventory WHERE badge_id=".$rs['need_id']." 
						AND user_id = '".$user_id."'
						LIMIT 1";
						$buyer_item = fetch($sql,$conn);
						
						$sql = "SELECT id,user_id,badge_id
						FROM ".$this->SCHEMA_CODE.".badge_inventory WHERE badge_id=".$rs['with_id']." 
						AND user_id = '".$rs['user_id']."'
						LIMIT 1";
						$seller_item = fetch($sql,$conn);
						
						//var_dump($buyer_item);
						
						//var_dump($seller_item);
						
						$sql="UPDATE ".$this->SCHEMA_CODE.".badge_inventory SET user_id=".$rs['user_id']."
							  WHERE id=".$buyer_item['id'];
						mysql_query($sql,$conn);
						
						$sql="UPDATE ".$this->SCHEMA_CODE.".badge_inventory SET user_id=".$user_id."
							  WHERE id=".$seller_item['id'];
						mysql_query($sql,$conn);
						
						//log transaction
						$sql = "INSERT IGNORE INTO ".$this->SCHEMA_CODE.".transaction_history(auction_id1,auction_id2,transaction_date)
								VALUES('".$rs2['auction_id']."','".$rs['auction_id']."',NOW())";
						mysql_query($sql,$conn);
						
						//update auctions
						$sql="UPDATE ".$this->SCHEMA_CODE.".auction_post SET n_status=1
							  WHERE id=".$rs['auction_id'];
						mysql_query($sql,$conn);
						
						$sql="UPDATE ".$this->SCHEMA_CODE.".auction_post SET n_status=1
							  WHERE id=".$rs2['auction_id'];
						mysql_query($sql,$conn);
						
						//log transaction
						$sql = "INSERT IGNORE INTO ".$this->SCHEMA_CODE.".tbl_trade_log(buyer,seller,date_time)
								VALUES(".$user_id.",".$rs['user_id'].",NOW())";
						mysql_query($sql,$conn);
						
						$output = $this->outputMessage(1,"Trade success",array($rs,$rs2));
					}else{
						$output = $this->outputMessage(0,"Insufficient badge to trade",array($rs,$rs2));
					}									
					//
				}else{
					$output = $this->outputMessage(0,"Your trade is already closed",$rs);
				}
			}else{
				$output = $this->outputMessage(0,"The item is already taken by somebody else",$rs);
			}
			mysql_close($conn);
			return $output;
		}else{
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
		}
	}
	
	
	function getUserID(){
			$email = $_REQUEST['email'];
			$regID = mysql_escape_string($_REQUEST['register_id']);
			$conn = open_db(0);
			$sql = "
			SELECT id as userid , register_id
			FROM ".$this->SCHEMA_CONNECTION.".social_member 
			WHERE email = '".mysql_escape_string($email)."' 
			LIMIT 1";
			$q = mysql_query($sql,$conn);
			if($q){
			//update status
				$sql = "UPDATE ".$this->SCHEMA_CONNECTION.".social_member SET n_status=1 , register_id='{$regID}' WHERE  email = '".mysql_escape_string($email)."' ";
				$updateRegisterID = mysql_query($sql,$conn);			
			}
			
			$f = mysql_fetch_object($q);	
			// if($f->register_id==0) return false;			
			mysql_free_result($q);
			mysql_close($conn);
			$jsonResult = json_encode($f);
			return $jsonResult;
			// return
	
	}
	
	
	function getProfile(){
			$userid = $_REQUEST['userid'];
			
			$profile = $this->getRegisterID($userid);
			
			$inventory = json_decode($this->get_actual_inventory($profile->register_id));
			$allBadges = json_decode($this->getAllBadgeDetail());
			$totalBadge = 0;	
			$badge=array();
			foreach($allBadges->data as $key => $val){
				$badge[$key] = array('badge_id'=>$val->badgeid,'total'=>0,'description'=>$val->description,'image'=>$val->badgeurl);
					foreach($inventory->data as $k){
						if($val->badgeid == $k->badge_id){
							$badge[$key] = array('badge_id'=>$val->badgeid,'total'=>$k->total,'description'=>$val->description,'image'=>$val->badgeurl);
							$totalBadge += $k->total*$val->badge_value;
						}
						
					}
				}
			$allBadges=null;
			// $totalBadge = count($inventory->data);
			
			$arrData = array();
			$arrData['userid'] = $userid;
			$arrData['registerid'] = $profile->register_id;
			$arrData['name'] = $profile->name;
			$arrData['fullname'] = $profile->fullname;
			if($profile->img) $arrData['photourl'] = BASEURL.'avatar/thumb/medium_'.$profile->img;
			else $arrData['photourl'] = BASEURL.'avatar/thumb/small_avatar/avatar-man.jpg';
			$arrData['sex'] = $profile->sex;
			$arrData['totalbadges'] =$totalBadge;
						
			foreach($inventory->data as $key => $val)
			{
				$arrData['listbadges'][$key]['badgeid'] = $val->badge_id;
				$arrData['listbadges'][$key]['badgecount'] = $val->total;		
				$arrData['listbadges'][$key]['badgeurl'] = $val->img;					
			}
			$inventory = null;
			$profile = null;
		
			return json_encode($arrData);
	
	}
	
	function getProfile_min(){
			$userid = $_REQUEST['userid'];
			
			$profile = $this->getRegisterID($userid);
				
			$arrData = array();
			$arrData['userid'] = $userid;
			$arrData['registerid'] = $profile->register_id;
			$arrData['name'] = $profile->name;
			$arrData['fullname'] = $profile->fullname;
			if($profile->img) $arrData['photourl'] = BASEURL.'avatar/thumb/medium_'.$profile->img;
			else $arrData['photourl'] = BASEURL.'avatar/thumb/small_avatar/avatar-man.jpg';
			
			$arrData['sex'] = $profile->sex;
			return json_encode($arrData);
	
	}
	
	function getInbox(){
						
			$userid = $_REQUEST['userid'];
			$conn = open_db(0);
			$sql = "
			SELECT message_id as id,message_text as message , message_date as posted_date 
			FROM ".$this->SCHEMA_CONNECTION.".social_message 
			WHERE 
			message_to = '".mysql_escape_string($userid)."' 
			AND message_status<>'2' 
			AND message_history<>'1' 
			ORDER BY posted_date 
			DESC LIMIT 10 ";
	
			$q = mysql_query($sql,$conn);
			$n=0;
				while($row = mysql_fetch_object($q)){
				
				$notification[$n]['id']= $row->id;
				$notification[$n]['detail']= $row->message;
				$notification[$n]['datetime'] = $this->dateHelper->datediff($row->posted_date);
				$n++;
				};			
				mysql_free_result($q);
			mysql_close($conn);
			
			$arrNotification['numrows'] = count($notification);
			$arrNotification['rows'] = $notification;
			$notification = null;
			// return $this->outputMessage(1,'getInbox',$arrNotification);
			return json_encode($arrNotification);
	
	}
	
	function getRegisterID($user_id=null){

		if($user_id==null) return false;
		$conn = open_db(0);
		$sql = "
		SELECT id, name, concat(name, ' ', last_name)  as fullname, img,register_id,sex
		FROM ".$this->SCHEMA_CONNECTION.".social_member 
		WHERE id = '".mysql_escape_string($user_id)."' 
		LIMIT 1";
		
		$q = mysql_query($sql,$conn);
		$profile = mysql_fetch_object($q);			
		mysql_free_result($q);
		mysql_close($conn);
		
		if($profile) return $profile;
		else return false;
	
	}
	
	
	function getRegisterIDForMobile(){

		$user_id = $_REQUEST['userid'];
		$conn = open_db(0);
		$sql = "
		SELECT id, name, concat(name, ' ', last_name)  as fullname, img,register_id 
		FROM ".$this->SCHEMA_CONNECTION.".social_member 
		WHERE id = '".mysql_escape_string($user_id)."' 
		LIMIT 1";
		
		$q = mysql_query($sql,$conn);
		$profile = mysql_fetch_object($q);			
		mysql_free_result($q);
		mysql_close($conn);
		
		if($profile) return json_encode($profile);
		else return null;
	
	}
	
	function getAllAuction(){
	
		//get auction
		$conn = open_db(0);
		$sql = "
		SELECT * 
		FROM ".$this->SCHEMA_CONNECTION.".social_auction 
		WHERE 
		n_status = 1 
		AND start_date <= NOW() 
		AND end_date >= NOW()";
		
		$auction = fetch_many($sql,$conn);			
		
		mysql_close($conn);
		// print_r($auction);
		$data['auction'] = $auction;
		return $this->outputMessage(1,'auction',$data);
	
	}
	
	function getAuctionByID($auctionid=NULL){
		if($auctionid==NULL)$auction_id = $_REQUEST['auction_id'];
		else $auction_id=$auctionid;
		// print_r($auctionid);exit;
		if($auction_id!=''){
		//get auction
		$conn = open_db(0);
		$sql = "
		SELECT * 
		FROM ".$this->SCHEMA_CONNECTION.".social_auction 
		WHERE n_status = 1 
		AND start_date <= NOW() 
		AND end_date >= NOW() 
		AND id={$auction_id} 
		LIMIT 1";
		$q = mysql_query($sql,$conn);
		$auction = mysql_fetch_object($q);			
		mysql_free_result($q);
		mysql_close($conn);
			//if found
				//check highest current auction -> function getHighestBid
				$highestBid = $this->getHighestBid($auction_id);
		
		$data['highestBid'] = $highestBid;
		$data['auction'] = $auction;
		return $this->outputMessage(1,'auction',$data);
		}else return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	}
	
	
	
	function placeBid(){
	//bid : badgeID-amount,nBadgeId-nAmount,nB-nA..
	$bid = $_REQUEST['bid'];
	$user_id = $_REQUEST['userid'];
	$auction_id = $_REQUEST['auctionid'];
	$sendMessageOutBid = false;
	//testing
	// $bid = '1-0,2-0,3-0,4-0,5-0,6-0,7-0,8-0,9-1,10-0,11-0,12-0';
	// $auction_id = 17;
	// $user_id = 7;
	
	if($bid!=''&&$user_id!=''&&$auction_id!=''){

	$allBadgeValue = json_decode($this->getAllBadgeDetail());
	
	foreach($allBadgeValue->data as $key => $val){
		$vocabBadge[$val->badgeid] = $val->badge_value;
	}

	//explode bid, count bid
	$arrBidraw = explode(',',$bid);
	foreach($arrBidraw as $val){
		$tempArrBid = explode('-',$val);
		$arrBid[$tempArrBid[0]] = $tempArrBid[1];
		$ownTotalBid += $tempArrBid[1] * $vocabBadge[$tempArrBid[0]];
		$bidInsertData[] = "({$user_id},{$auction_id},{$tempArrBid[0]},{$tempArrBid[1]},NOW())";
		}
			
		//get current badges amount
		$userBid = $this->getAuctionBidDataPerUser($user_id,$auction_id);
		//get redem prize badges
		//.. not doing this
		//check highest current auction -> function getHighestBid
		$highestBid = $this->getHighestBid($auction_id);
		
		if(array_key_exists('bid',$highestBid['data'])){
			foreach($highestBid['data']['bid'] as $key => $val){
				$valueEachBadgeHighest += $val * $vocabBadge[$key];
			}
		}else $valueEachBadgeHighest = $highestBid['total'];
		
		
		// print_r('<pre>');print_r($valueEachBadgeHighest);exit;
		$allBadgeValue = null;
		$highestBid['total'] = $valueEachBadgeHighest;
		$currentTotalBid = $ownTotalBid+$userBid['total'];
			// print_r('<pre>');print_r($userBid);exit;
		//if current bid st own bid
		if($highestBid['total'] < $currentTotalBid ){
			// get own bid on inventory
			$profile = $this->getRegisterID($user_id);
			
			$inventory = json_decode($this->get_actual_inventory($profile->register_id));
			
				foreach($inventory->data as $val){
					$inven[$val->badge_id]=$val->total;
				}
				
			//loop exploded bid to validate, checking own bid gte exploded bid
			
				foreach($arrBid as $key => $val){
					//current bid, all badges - ( bid in auction + redeem prize badges )
					$total = intval($inven[$key]);					
					if($arrBid[$key] > $total) $bidStat[] = false;
					else $bidStat[]=true;
					
				}
			
				//if all ok, 
				if(in_array(false,$bidStat)) return $this->outputMessage(0,'not Enough Bid , and dont cheat',NULL);
				//place bid to table social_auction_bid rows
				$strBidInsertData = implode(',',$bidInsertData);
				$conn = open_db(0);
					$sql = "
					INSERT IGNORE INTO ".$this->SCHEMA_CONNECTION.".social_auction_bid 
					(user_id,	auction_id,badge_id,  amount,date)
					VALUES
					{$strBidInsertData}
					";
				$q = mysql_query($sql,$conn);
					
					//give back the last highest bid
					if($q){
						if( array_key_exists('user_id',$highestBid['data'])) {
							$sql = "
								UPDATE ".$this->SCHEMA_CONNECTION.".social_auction_bid 
								set n_status = 0
								WHERE 
									user_id ={$highestBid['data']['user_id']} AND auction_id = {$auction_id}
							";
							if($highestBid['data']['user_id'] != $user_id){
								$q = mysql_query($sql,$conn);	
								$sendMessageOutBid = true;
							}
						}
					}else return $this->outputMessage(-1,'error databases status parameters',NULL);
					
				mysql_free_result($q);
				mysql_close($conn);
				$data['user_bid'] = $currentTotalBid;
				$data['latest_bid'] = $highestBid['total'];
			
					require_once APP_PATH.APPLICATION."/helper/newsHelper.php";
					$news = new newsHelper($profile->register_id);
					if($sendMessageOutBid)$news->gotHighestBid($auction_id,$sendMessageOutBid,$highestBid['data']['user_id']);
					else $news->gotHighestBid($auction_id);
			
				return $this->outputMessage(1,"Congrate you are highest bid {$currentTotalBid}, lastest bid is {$highestBid['total']}",NULL);
					
			
		}else  return $this->outputMessage(0,"not Enough Bid, highest bid {$highestBid['total']} your amount {$currentTotalBid} ",NULL);
		}else	return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	}
	
	
	function getAuctionLastWinner(){
		//get lastest winner
		$conn = open_db(0);
			$sql = "
			SELECT user_id,auction_id, bid_amount
			FROM ".$this->SCHEMA_CONNECTION.".social_auction_history 
			WHERE 
			n_status = 1 
			ORDER BY date_time DESC LIMIT 1
			";
		$q = mysql_query($sql,$conn);
		$lastWinner = mysql_fetch_object($q);
		mysql_close($conn);
		
		
		if($lastWinner){
		$auctionDetail = json_decode($this->getAuctionByIDForProfile($lastWinner->auction_id));
		$lastWinner->auction_detail = $auctionDetail->data->auction;
		// print_r($lastWinner->auction_detail);exit;
		return $this->outputMessage(1,"lastWinner",$lastWinner);
		}else return $this->outputMessage(1,"lastWinner",NULL);
	}
	
	function getAuctionAllWinner(){
		//get lastest winner
		$conn = open_db(0);
			$sql = "
			SELECT user_id,auction_id, bid_amount
			FROM ".$this->SCHEMA_CONNECTION.".social_auction_history 
			WHERE 
			n_status = 1 
			ORDER BY date_time DESC
			";
		// $q = mysql_query($sql,$conn);
		$lastWinner = fetch_many($sql,$conn);
	
		mysql_close($conn);
		
		// print_r($lastWinner);
		if($lastWinner){
		foreach($lastWinner as $key => $val){
			$auctionDetail = json_decode($this->getAuctionByIDForProfile($val['auction_id']));
			$lastWinner[$key]['auction_detail'] = $auctionDetail->data->auction;
			$auctionDetail = null;
		}
		return $this->outputMessage(1,"lastWinner",$lastWinner);
		}else return $this->outputMessage(1,"lastWinner",NULL);
	}
	
	
	function getAuctionByIDForProfile($auctionid=NULL){
		if($auctionid==NULL)$auction_id = $_REQUEST['auction_id'];
		else $auction_id=$auctionid;
		// print_r($auctionid);exit;
		if($auction_id!=''){
		//get auction
		$conn = open_db(0);
		$sql = "
		SELECT * 
		FROM ".$this->SCHEMA_CONNECTION.".social_auction 
		WHERE id={$auction_id} 
		LIMIT 1";
		$q = mysql_query($sql,$conn);
		$auction = mysql_fetch_object($q);			
		mysql_free_result($q);
		mysql_close($conn);
		$data['auction'] = $auction;
		return $this->outputMessage(1,'auction',$data);
		}else return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	}
	
	function getAuctionWinnerById(){
		$user_id = $_REQUEST['userid'];
		//get lastest winner
		$conn = open_db(0);
			$sql = "
			SELECT user_id,auction_id
			FROM ".$this->SCHEMA_CONNECTION.".social_auction_history 
			WHERE 
			user_id  = {$user_id}
			ORDER BY date_time DESC
			";
		$winner = fetch_many($sql,$conn);
		mysql_close($conn);
		
		
		if($winner){
			foreach($winner as $key => $val){
				$auctionDetail = json_decode($this->getAuctionByIDForProfile($val['auction_id']));
				$winner[$key]['auction_detail'] = $auctionDetail->data->auction;		
			}
			return $this->outputMessage(1,"winner",$winner);
		}else return $this->outputMessage(1,"winner",NULL);
	}
	
	function getAuctionWinner(){
		//check all existing auction where end date is expired,and status is open  auction
			$conn = open_db(0);
			$sql = "
				SELECT id,item_name FROM  ".$this->SCHEMA_CONNECTION.".social_auction WHERE end_date <= NOW() AND n_status=1
			";
			$qAuction = fetch_many($sql,$conn);
			
			//if expired
			if($qAuction) {
				$data[]='get auction id is closed ';
				//get badge value
				$allBadgeValue = json_decode($this->getAllBadgeDetail());
	
				foreach($allBadgeValue->data as $key => $val){
					$vocabBadge[$val->badgeid] = $val->badge_value;
				}

				foreach($qAuction as $val){
					//check winner from auction id
					
					$highestBid = $this->getHighestBid($val['id']);
					$data[]='get winner..';
					
					if(! array_key_exists('user_id',$highestBid['data'])) continue;
					$data[]['data'] = $highestBid['data'];
					// get winner, auction stat to 0
						$sql = "
						UPDATE ".$this->SCHEMA_CONNECTION.".social_auction SET n_status=0
						WHERE id={$val['id']} LIMIT 1
						";
						$conn = open_db(0);
						$q = mysql_query($sql,$conn);
					
					//winner badges lock it
						if($q){
							$data[]='locking process winner '.$highestBid['data']['user_id'].' badges';
							
							//multiple with value of badge
							foreach($highestBid['data']['bid'] as $keyHigh => $valHigh){
								$valueEachBadgeHighest += $valHigh * $vocabBadge[$keyHigh];
							}
							
							$sql = "
							UPDATE ".$this->SCHEMA_CONNECTION.".social_auction_bid 
							set n_status = 2
							WHERE 
								user_id ={$highestBid['data']['user_id']} AND auction_id = {$val['id']} AND n_status = 1
							";
							$q = mysql_query($sql,$conn);	
							if($q){
								$data[]=' lock winner '.$highestBid['data']['user_id'].' badges';
								//winner to social_auction_history
								$sql = "
									INSERT IGNORE INTO ".$this->SCHEMA_CONNECTION.".social_auction_history 
									(auction_id ,	user_id ,	bid_amount ,	date_time ,	n_status )
									VALUES
									({$val['id']},{$highestBid['data']['user_id']},{$valueEachBadgeHighest},NOW(),1)
									";
								$q = mysql_query($sql,$conn);
								if($q) {
								//dummy
								$sql = "
									SELECT * FROM  ".$this->SCHEMA_CONNECTION.".social_member WHERE id={$highestBid['data']['user_id']} AND n_status=1 LIMIT 1
								";
								
								$qSocialMember = fetch($sql,$conn);
								
								//activity and mail ini here
								// $auction_name = $val['item_name'];
								$toEmail = $qSocialMember['email'];
								$fromEmail = 'connection2012-NoReply@marlboro.co.id';
								$subject = 'auction info';
								$username = $qSocialMember['name'];
								$user_id = $qSocialMember['register_id'];
								$auction_name = $val['item_name'];
								
								include_once '../config/message.php';
								require_once APP_PATH.APPLICATION."/helper/newsHelper.php";
								$news = new newsHelper($user_id);
							
								$msg = $message['winAuction'];
								$news->activityNews($user_id,$msg);
								$msg = $message['inbox']['winAuction'];
								$news->messageForUser($user_id,$subject,$msg);
								$msg = $mail['winAuction'];
								$data['mail'] = $this->sendGlobalMail($toEmail,$fromEmail,$msg);
								
								
								$data[]=' winner with id '.$highestBid['data']['user_id'].' ,  success kick to social_auction_history';
								}
								else $data[]='failed to kick winner to social_auction_history.... next';
							}else $data[]='cannot lock winner '.$highestBid['data']['user_id'].' badges ';
						}else $data[] = 'cannot update auction id is closed ';
						
						$data[]='next Process..';
				}	
			}else $data[]='there is not closed auction';
			
			mysql_close($conn);			
			// print_r($mail);exit;
			print_r('<pre>');print_r($data);exit;
	}
	
	function getAuctionBidDataPerUser($user_id=null,$auction_id=null){
		if($user_id==null) return false;
		if($auction_id==null) return false;
		$conn = open_db(0);
		$sql = "
		SELECT sum(amount)as amount , badge_id 
		FROM ".$this->SCHEMA_CONNECTION.".social_auction_bid 
		WHERE 
		n_status = 1 
		AND auction_id={$auction_id} 
		AND user_id={$user_id}
		GROUP BY user_id,badge_id
		";
		
		$q = mysql_query($sql,$conn);
		
		if($q){
		$allBadgeValue = json_decode($this->getAllBadgeDetail());
	
		foreach($allBadgeValue->data as $key => $val){
			$vocabBadge[$val->badgeid] = $val->badge_value;
		}
		
			//check auction bid from this person
			while($row = mysql_fetch_object($q)){
				$bidUserData[$row->badge_id] =$row->amount;
				$totalAmount +=intval($row->amount) * $vocabBadge[$row->badge_id];
			}
		}
		mysql_free_result($q);
		mysql_close($conn);
		$data['total'] = $totalAmount;
		$data['data'] = $bidUserData;
		return $data;
	}
	
	function getHighestBid($auction_id=null){
		if($auction_id==null) return false;
		$conn = open_db(0);
		//check user id highest bid, multiple with badge value on catalog
		$sql = "
		SELECT SUM(amount*badge_value) as totalAmount, user_id
			FROM (
				SELECT amount, user_id, badge_id, badge_value
				FROM  {$this->SCHEMA_CONNECTION}.social_auction_bid aucbid
				INNER JOIN {$this->SCHEMA_CODE}.badge_catalog as badge ON badge.id=aucbid.badge_id
				WHERE 
				n_status = 1 
				AND amount <> 0
				AND auction_id={$auction_id}
			) as highestBid
		GROUP BY user_id 
		ORDER BY totalAmount DESC 
		LIMIT 1
		";
		// print_r($sql);exit;
		// return $sql;
		$q = mysql_query($sql,$conn);
		$highestUserID = mysql_fetch_object($q);	
		$user_id = $highestUserID->user_id;
		
		$sql = "
		SELECT sum(amount)as amount , badge_id ,user_id
		FROM ".$this->SCHEMA_CONNECTION.".social_auction_bid 
		WHERE 
		n_status = 1 
		AND auction_id={$auction_id} 
		AND user_id={$user_id}
		GROUP BY user_id,badge_id
		";
		
		$q = mysql_query($sql,$conn);
		
		//if found
		if($q){
			//check auction bid from this person
			while($row = mysql_fetch_object($q)){
				$highestBid['bid'][$row->badge_id] =$row->amount;
				$highestBid['user_id'] = $row->user_id;
				$minimalAmount +=$row->amount;
			}
		}else{
		//if not found
			//check minimal bid from this auction
				$sql = "SELECT minimal_bid FROM ".$this->SCHEMA_CONNECTION.".social_auction WHERE n_status = 1 AND id={$auction_id}";
				$q = mysql_query($sql,$conn);
				$highestBid = mysql_fetch_object($q);
				$minimalAmount =  $highestBid->minimal_bid;
		}		
		mysql_free_result($q);
		mysql_close($conn);
		$data['total'] = $minimalAmount;
		$data['data'] = $highestBid;
		return $data;
	}
	
	
	function getNewsAndClues($limit=10){
			$limit = $_REQUEST['limit'];
			$conn = open_db(0);
				$sql = "
					SELECT news_id,news_title,news_last_update,news_brief,news_content
					FROM ".$this->SCHEMA_CONNECTION.".social_news 
					WHERE news_status='1' AND news_category=1
					ORDER BY news_last_update DESC
					LIMIT {$limit}";
					
			$dataNewAndClues = fetch_many($sql,$conn);
			mysql_close($conn);
			// print_r($sql);exit;
			if($dataNewAndClues) {
				foreach($dataNewAndClues as $key => $val){
					$dataNewAndClues[$key]['news_last_update'] = $this->dateHelper->datediff($val['news_last_update']);
					$dataNewAndClues[$key]['news_true_date'] = $val['news_last_update'];
				}
			
			return $this->outputMessage(1,"getNewsAndClues",$dataNewAndClues);
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	
	}
	
	function getYellowCabHunting($limit=10){
			$limit = $_REQUEST['limit'];
			$conn = open_db(0);
				$sql = "
					SELECT *
					FROM ".$this->SCHEMA_CONNECTION.".social_news 
					WHERE news_status='1'  AND news_category=2
					ORDER BY news_last_update DESC
					LIMIT {$limit}";
					
			$dataHuntingCab = fetch_many($sql,$conn);
			mysql_close($conn);
			// print_r($sql);exit;
			if($dataHuntingCab) {
				foreach($dataHuntingCab as $key => $val){
					$dataHuntingCab[$key]['news_last_update'] = $this->dateHelper->datediff($val['news_last_update']);
					$dataHuntingCab[$key]['news_true_date'] = $val['news_last_update'];
				}
			
			return $this->outputMessage(1,"getYellowCabHunting",$dataHuntingCab);
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	
	}
		
	function getAllActivityUser($limit=10){
		$limit = 40;
		$conn = open_db(0);
			$sql = "
					SELECT *
					FROM ".$this->SCHEMA_CONNECTION.".social_tradenews 
					ORDER BY tradenews_date DESC
					LIMIT {$limit}";
			$activityAllUserData = fetch_many($sql,$conn);
			mysql_close($conn);
			
			if($activityAllUserData) {
				foreach($activityAllUserData as $key => $val){
					$activityAllUserData[$key]['tradenews_date'] = $this->dateHelper->datediff($val['tradenews_date']);
					$activityAllUserData[$key]['tradenews_true_date'] = $val['tradenews_date'];
					//$activityAllUserData[$key]['tradenews_content'] = $this->removeBadString($val['tradenews_content']);
				}
			// print_r($activityAllUserData);exit;	
			// echo $sql;exit;
			return $this->outputMessage(1,"getAllActivityUser",$activityAllUserData);
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	}
	
	function getMergeAllActivityNews($page=0,$limit=5){
		$page = $_REQUEST['page'];
		$limit = $_REQUEST['limit'];
		$limitSub = round($limit/2);
		if($page==1||$page==0 || $page==null) $page= 0;
		else {
		$page = ($page*$limit)-$limit;
		}
			$conn = open_db(0);
				$sql = "
					(SELECT news_id as id,news_content as content,news_last_update as date
					FROM ".$this->SCHEMA_CONNECTION.".social_news 
					WHERE news_status='1' 
					ORDER BY news_last_update DESC
					LIMIT {$limit})
					UNION
					(SELECT tradenews_id as id,	tradenews_content as content ,tradenews_date as date
					FROM ".$this->SCHEMA_CONNECTION.".social_tradenews 
					ORDER BY tradenews_date DESC
					LIMIT {$limit})
					ORDER BY date DESC LIMIT {$page},{$limit}
					";
		
		
			$dataNewAndActivity = fetch_many($sql,$conn);
			mysql_close($conn);
			if($dataNewAndActivity) {
			foreach($dataNewAndActivity as $key => $val){
			$dataNewAndActivity[$key]['content'] = $this->removeBadString($val['content']);
			}
			return $this->outputMessage(1,"dataNewAndActivity",$dataNewAndActivity);
			}
			return $this->outputMessage(-1,'Wrong given parameters',null);
		
	
	}
	

	function getNewsAndCluesById(){
	//news_id 	news_title 	news_published_date 	news_last_update 	news_brief 	news_content 	news_status 0 => block, 1 => publish	news_plaintext
			$id = $_REQUEST['id'];
			$conn = open_db(0);
				$sql = "
					SELECT news_id,news_title,news_last_update,news_brief,news_content
					FROM ".$this->SCHEMA_CONNECTION.".social_news 
					WHERE news_status='1'    AND news_category=1 AND news_id = {$id} 
					ORDER BY news_last_update DESC
					LIMIT 1";
					
			$dataNewAndClues = fetch($sql,$conn);
			mysql_close($conn);
			
			if($dataNewAndClues) 
			{
				$dataNewAndClues['news_last_update'] = $this->dateHelper->datediff($dataNewAndClues['news_last_update']);
				$dataNewAndClues['news_title'] = $this->removeBadString($dataNewAndClues['news_title']);
				$dataNewAndClues['news_content'] = $this->removeBadString($dataNewAndClues['news_content']);
				// print_r($dataNewAndClues);exit;
				return $this->outputMessage(1,"getNewsAndClues",$dataNewAndClues);
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	
	}
	
	function getYellowCabHuntingById(){
	
			$id = $_REQUEST['id'];
			$conn = open_db(0);
				$sql = "
					SELECT news_id,news_title,news_last_update,news_brief,news_content,images
					FROM ".$this->SCHEMA_CONNECTION.".social_news 
					WHERE news_status='1'  AND news_id = {$id}   AND news_category=2
					ORDER BY news_last_update DESC
					LIMIT 1";
					
			$dataHuntingCab = fetch($sql,$conn);
			mysql_close($conn);
			
			if($dataHuntingCab) 
			{
				$dataHuntingCab['news_last_update'] = $this->dateHelper->datediff($dataHuntingCab['news_last_update']);
				$dataHuntingCab['news_title'] = $this->removeBadString($dataHuntingCab['news_title']);
				$dataHuntingCab['news_content'] = $this->removeBadString($dataHuntingCab['news_content']);
				
				return $this->outputMessage(1,"getYellowCabHuntingById",$dataHuntingCab);
			}
			return $this->outputMessage(-1,'Wrong given parameters',null);
	
	
	}
	
	function getMessageByUserId($limit=30){
	
			$user_id = $_REQUEST['user_id'];
			$conn = open_db(0);
				$sql = "
					SELECT 	message_status,message_id 	,message_date,message_subject,message_text
					FROM ".$this->SCHEMA_CONNECTION.".social_message 
					WHERE message_history<>'1'  AND message_status <>'2'  AND message_to  = {$user_id}
					ORDER BY message_date DESC
					LIMIT {$limit}";
					
			$getMessageByUserId = fetch_many($sql,$conn);
			mysql_close($conn);
			
			if($getMessageByUserId) {
				foreach($getMessageByUserId as $key => $val){
					$getMessageByUserId[$key]['message_date'] = $this->dateHelper->datediff($val['message_date']);
				}
				return $this->outputMessage(1,"getMessageByUserId",$getMessageByUserId);
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	
	}
	
	
	function getMessageCount(){
	
			$user_id = $_REQUEST['user_id'];
			$conn = open_db(0);
				$sql = "
					SELECT 	count(*) as total
					FROM ".$this->SCHEMA_CONNECTION.".social_message 
					WHERE message_history<>'1' AND message_status ='0'  AND message_to  = {$user_id}
					ORDER BY message_date DESC";
					
			$getMessageByUserId = fetch_many($sql,$conn);
			mysql_close($conn);			
			if($getMessageByUserId) {
				return $this->outputMessage(1,"getMessageByUserId",$getMessageByUserId);
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	
	}
	
	
	function getMessageByUserIdAndMessageId($limit=30){
			
			$user_id = $_REQUEST['user_id'];
			$id = $_REQUEST['id'];
			$conn = open_db(0);
				$sql = "
					SELECT 	message_status,message_id 	,message_date,message_subject,message_text
					FROM ".$this->SCHEMA_CONNECTION.".social_message 
					WHERE message_history <> '1' AND message_status <> '2'  AND message_to  = {$user_id} AND message_id ={$id}
					ORDER BY message_date DESC
					LIMIT 1";
			// print_r($sql);exit;		
			$getMessageByUserId = fetch($sql,$conn);
			mysql_close($conn);
			
			if($getMessageByUserId) {
				$conn = open_db(0);
				$sql = "
					UPDATE 	".$this->SCHEMA_CONNECTION.".social_message 
					SET message_status = '1' 
					WHERE message_history='0' AND message_status <> '2'  AND message_id ={$id}
					LIMIT 1";
				mysql_query($sql,$conn);
				mysql_close($conn);
				$getMessageByUserId[$key]['message_date'] = $this->dateHelper->datediff($getMessageByUserId['message_date']);
				return $this->outputMessage(1,"getMessageByUserId",$getMessageByUserId);
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	
	}
	
	function getDeleteMessageByUserIdAndMessageId(){
	
			$user_id = $_REQUEST['user_id'];
			$id = $_REQUEST['id'];
			$conn = open_db(0);
				$sql = "
					UPDATE 	".$this->SCHEMA_CONNECTION.".social_message 
					SET message_status = '2' 
					WHERE message_history='0' AND message_status <> '2'  AND message_to  = {$user_id} AND message_id ={$id}
					LIMIT 1";
			// print_r($sql);exit;		
			$getDeleteMessageByUserIdAndMessageId = mysql_query($sql,$conn);
			mysql_close($conn);
			
			if($getDeleteMessageByUserIdAndMessageId) {
				return $this->outputMessage(1,"getDeleteMessageByUserIdAndMessageId","SUCCESS DELETED MESSAGE");
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	}
	
	function getAllActivityUserById(){
		$id = $_REQUEST['id'];
		$conn = open_db(0);
			$sql = "
					SELECT *
					FROM ".$this->SCHEMA_CONNECTION.".social_tradenews 
					WHERE tradenews_id = {$id}
					ORDER BY tradenews_date DESC
					LIMIT 1";
			$activityAllUserData = fetch($sql,$conn);
			mysql_close($conn);
			if($activityAllUserData) 
			{
				$activityAllUserData['tradenews_date'] = $this->dateHelper->datediff($activityAllUserData['tradenews_date']);
				$activityAllUserData['tradenews_content'] = $this->removeBadString($activityAllUserData['tradenews_content']);
				return $this->outputMessage(1,"getAllActivityUser",$activityAllUserData);
			}
			return $this->outputMessage(-1,'Wrong given parameters',NULL);
	
	}
	
	
	function getFirstBadge(){
		
		$user_id = $_REQUEST['register_id'];
		$kode = "FIRSTBADGE";
		
		if($user_id=='') return $this->outputMessage(-1,'Wrong given parameters',NULL);
		$conn = open_db(0);	
		
		//cek uda pernah dapet apa blom
		$sql ="SELECT count(id) as total FROM {$this->SCHEMA_CODE}.badge_redeem WHERE user_id ={$user_id} AND kode='{$kode}' LIMIT 1";
		$gotFirstBadge = fetch($sql,$conn);
		
		//klo uda out
		if($gotFirstBadge['total']>0) return $this->outputMessage(0,"getFirstBadge","you already have first badge");
		//klo belom in
		
		$sql = "
		SELECT * 
		FROM  ".$this->SCHEMA_CONNECTION.".social_member 
		WHERE register_id={$user_id} AND n_status=1 LIMIT 1
		";
		$qSocialMember = fetch($sql,$conn);
		//cek existing user bukan
		$sql ="SELECT email FROM ".SCHEMA_REPORT.".tbl_existing_user WHERE email='{$qSocialMember['email']}' LIMIT 1";
		$existingUser = fetch($sql,$conn);	
		
			//get free badge
			$badge_id = rand(1,10);
			$sql = "INSERT IGNORE INTO {$this->SCHEMA_CODE}.badge_redeem
					(user_id,redeem_time,kode)
					VALUES
					('".mysql_escape_string($user_id)."',NOW()
					,'".mysql_escape_string($kode)."');";
			$q = mysql_query($sql,$conn);
			
			if($q){
				$redeem_id = mysql_insert_id();
					
				$sql = "INSERT INTO {$this->SCHEMA_CODE}.badge_inventory(user_id,redeem_time,badge_id,redeem_id)
						VALUES('".$user_id."',NOW(),".$badge_id.",".$redeem_id.")";
				$q = mysql_query($sql,$conn);
				// print_r($sql);exit;
						if($q){
							//kirim infonya ke messages sama activity
							$qry="SELECT name,id FROM {$this->SCHEMA_CODE}.badge_catalog WHERE id='{$badge_id}';";
							$rs = fetch($qry,$conn);
							$badgeName = $rs['name'];
							//-->
							$res = true;
							$data = "you got badge";
							$getBadgeActivity = $badge_id;
						}else{
							$res = false;
							$data = "failed to inserting badge";
						}
			}else{
				$res = false;
				$data = "failed to redeem badge";
			}
			
		if($existingUser) {
			$extraBadge = $this->freeBadgeExistingUser($qSocialMember,$user_id,$kode);
			$extrabadgeName = $extraBadge['badgeName'];
			$extrabadgeid = $extraBadge['badgeid'];
			$getBadgeActivity = $badge_id.'_'.$extrabadgeid;
		}
					
		if($res){
					
					$toEmail = $qSocialMember['email'];
					$fromEmail = 'connection2012-NoReply@marlboro.co.id';
					$subject = 'connections notification';
					$username = $qSocialMember['name'];
					include_once '../config/message.php';
					require_once APP_PATH.APPLICATION."/helper/newsHelper.php";
					$news = new newsHelper($user_id);
					// extrabadgeName
					if($existingUser) $msg = $message['freeBagdeActExisting'];
					else $msg = $message['freeBagdeAct'];
					$news->activityNews($user_id,$msg,$getBadgeActivity);
					
					if($existingUser)  $msg = $message['freeBagdeExisting'];
					else $msg = $message['freeBagde'];
					$news->messageForUser($user_id,$subject,$msg);
					
					if($existingUser)  $msg = $mail['freeBagdeExisting'];
					else $msg = $mail['freeBagde'];
					$data['mail'] = $this->sendGlobalMail($toEmail,$fromEmail,$msg);
					
			}else return $this->outputMessage(0,"getFirstBadge",$data);
			
			mysql_close($conn);
		}
	
	function freeBadgeExistingUser($qSocialMember,$user_id){
			
			$kode = "EXTRABADGE";
			$conn = open_db(0);	
			//get free badge
			$badge_id = rand(1,10);
			$sql = "INSERT IGNORE INTO {$this->SCHEMA_CODE}.badge_redeem
					(user_id,redeem_time,kode)
					VALUES
					('".mysql_escape_string($user_id)."',NOW()
					,'".mysql_escape_string($kode)."');";
			$q = mysql_query($sql,$conn);
			
			if($q){
				$redeem_id = mysql_insert_id();
					
				$sql = "INSERT INTO {$this->SCHEMA_CODE}.badge_inventory(user_id,redeem_time,badge_id,redeem_id)
						VALUES('".$user_id."',NOW(),".$badge_id.",".$redeem_id.")";
				$q = mysql_query($sql,$conn);
				// print_r($sql);exit;
						if($q){
							//kirim infonya ke messages sama activity
							$qry="SELECT name,id FROM {$this->SCHEMA_CODE}.badge_catalog WHERE id='{$badge_id}';";
							$rs = fetch($qry,$conn);
							$badgeName = $rs['name'];
							//-->
							$res = true;
							$data = "you got badge";
						}else{
							$res = false;
							$data = "failed to inserting badge";
						}
			}else{
				$res = false;
				$data = "failed to redeem badge";
			}
			mysql_close($conn);
			return array('badgeName'=>$badgeName,'badgeid'=>$badge_id);
	
	
	}
	
	
	function getAllBadgeDetail(){
		$sql = "SELECT id as badgeid,name as badgetitle,image as badgeurl,description, badge_value
				FROM ".$this->SCHEMA_CODE.".badge_catalog ORDER BY CodeOrder ASC
				";
		
		$conn = open_db(0);
		$rs = fetch_many($sql,$conn);
		mysql_close($conn);
		if($rs!=null){
			return $this->outputMessage(1,'Success',$rs);
		}else{
			return $this->outputMessage(-1,'Badge not found',null);
		}
	}
	
	
	function sync_data(){
		
		$token = mysql_escape_string($_REQUEST['token']);
		$register_id = mysql_escape_string($_REQUEST['register_id']);
		$email = mysql_escape_string($_REQUEST['email']);
		$firstname = mysql_escape_string($_REQUEST['firstname']);
		$lastname = mysql_escape_string($_REQUEST['lastname']);
		$avtype = mysql_escape_string($_REQUEST['avtype']);
		
		if($token==''|| $register_id=='' || $email=='' ) return false;	
		$webToken = SHA1('connection2012'.SHA1($email).date('Y-m-d'));
		if($token!=$webToken) return false;
		if($avtype==1||$avtype==3){
			$status=1;
		}else{
			$status=0;
		}
				
		$sql = "INSERT INTO ".$this->SCHEMA_CONNECTION.".social_member(username,register_id,name,last_name,email,n_status,mobile_type)
				VALUES('{$email}','{$register_id}','{$firstname}','{$lastname}','{$email}','{$status}',2)
				ON DUPLICATE KEY UPDATE 
				register_id=VALUES(register_id)
				";
		
		$conn = open_db(0);
		$q = mysql_query($sql,$conn);
		
		if($q){
			
			//update status
			$sql = "UPDATE ".$this->SCHEMA_CONNECTION.".social_member SET n_status=1 WHERE email='{$email}'";
			$q = mysql_query($sql,$conn);
			//-->
		
		}
		$sql = "SELECT register_id FROM ".$this->SCHEMA_CONNECTION.".social_member WHERE email='{$email}' LIMIT 1";
		$rs = fetch($sql,$conn);
		mysql_close($conn);
		// print_r($rs);
		return $rs['register_id'];
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
?>
