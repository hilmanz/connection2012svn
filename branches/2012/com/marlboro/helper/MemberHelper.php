<?php
include_once("codeHelper.php");
include_once("newsHelper.php");
class MemberHelper extends Application{
	var $Request;
	var $View;
	var $_mainLayout="";
	var $code;
	var $news;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function update_login_time($user_id){
		$sql = "UPDATE social_member SET last_login = NOW()
				WHERE id=".intval($user_id);
		
		$this->open(0);
		$rs = $this->query($sql);
		$this->close();
		return $rs;
	}
	/**
	 * sync mop data with our data
	 * @param object
	 * @return json
	 */
	function sync_mop($profile){
		
		$member = $this->getProfileByMop($profile["UserProfile"]["RegistrationID"]);
		if($member['n_status']==9){return null;}
		if($member['register_id']!=$profile["UserProfile"]["RegistrationID"]){
			
			//insert data
			if($this->insert_data_from_mop($profile)){
				
				$member = $this->getProfileByMop($profile["UserProfile"]["RegistrationID"]);
			}else{
				
				print mysql_error();
			}
		}else{
			//Data di social_member selalu update sesuai dengan MOP
			$this->update_data_from_mop($profile);
		}
		return json_encode($member);
	}
	function insert_data_from_mop($profile){
		
		$pro = $profile["UserProfile"];
		
		$birth = explode(' ',$pro['DateOfBirth']);
		$birthday = explode('/',$birth[0]);
		$birthday = $birthday[2].'-'.$birthday[0].'-'.$birthday[1]; 
		$streetName = is_array($pro['StreetName']) ? '' : $pro['StreetName'];
		$mobilePhone = is_array($pro['MobilePhone']) ? '' : $pro['MobilePhone'];		
		
		$avatar = ( strtolower($pro['Gender']) == 'm' )? 'images/avatar-man.jpg' : 'images/avatar-woman.jpg';
		
		//$pro['AVType'] = 0; //testing 
		$n_status = ($pro['AVType'] == 1 || $pro['AVType'] == 3)? 1 : 0;
		
		$last_login = '0000-00-00';
		$freeBadge = false;
		if($n_status == 1){
			$last_login = "NOW()";
			$freeBadge = true;
		}
		
		$sql = "INSERT INTO 
				social_member (register_id,NAME,email,register_date,username,city,sex,birthday,img,last_name,StreetName,MobilePhone,last_login,n_status,login_count)
				VALUES ('".$pro['RegistrationID']."','".$pro['FirstName']."','".$pro['Email']."',NOW(),'".$pro['Login']."','".$pro['CityID']."','".$pro['Gender']."','".$birthday."','$avatar','".$pro['LastName']."','".$streetName."','".$mobilePhone."',$last_login,'$n_status','1')
				ON DUPLICATE KEY UPDATE 
				register_id=VALUES(register_id)
				;";
		
		$this->open(0);
		$rs = $this->query($sql);
		$this->close();
		
		/*
		if($freeBadge){
			//GET FREE BADGE
			$this->code = new codeHelper($pro['RegistrationID']);
			$this->code->getFreeBadge($pro['Email']);
			//$this->code->getFree4Badge($pro['Email']);
			//================
		}
		*/
		
		$this->news = new newsHelper($pro['RegistrationID']);
		$this->news->newUser();
		
		return $rs;
	}
	
	function update_data_from_mop($profile){
		$pro = $profile["UserProfile"];
		
		$this->open(0);
		$sql = "SELECT n_status,last_login,login_count FROM social_member WHERE register_id='".$pro['RegistrationID']."' LIMIT 1;";
		$rs = $this->fetch($sql);
		$this->close();
		
		$n_status = intval($rs['n_status']);
		$last_login = "'0000-00-00'";
		$login_count = intval($rs['login_count']) + 1;
		
		if( intval($rs['n_status']) == 0 && (intval($pro['AVType']) == 1 || intval($pro['AVType']) == 3) ){
			$n_status = 1;
			$last_login = "NOW()";
			
			/*
			//GET FREE BADGE
			$this->code = new codeHelper($pro['RegistrationID']);
			$this->code->getFreeBadge($pro['Email']);
			//$this->code->getFree4Badge($pro['Email']);
			//================
			*/
		}
		
		if(intval($rs['n_status']) == 1){
			$last_login = "NOW()";
		}
		
		$birth = explode(' ',$pro['DateOfBirth']);
		$birthday = explode('/',$birth[0]);
		$birthday = $birthday[2].'-'.$birthday[0].'-'.$birthday[1]; 
		$streetName = is_array($pro['StreetName']) ? '' : $pro['StreetName'];
		$mobilePhone = is_array($pro['MobilePhone']) ? '' : $pro['MobilePhone'];
		
		//test no verified member
		//$n_status = 0;
		
		$sql = "UPDATE social_member SET 
				name='".$pro['FirstName']."',
				last_name='".$pro['LastName']."',
				email='".$pro['Email']."',
				username='".$pro['Login']."',
				city='".$pro['CityID']."',
				sex='".$pro['Gender']."',
				birthday='".$birthday."',
				StreetName='".$streetName."',
				MobilePhone='".$mobilePhone."',
				last_login=$last_login,
				n_status='$n_status',
				login_count='$login_count'
				WHERE
				register_id='".$pro['RegistrationID']."'";
		
		$this->open(0);
		$rs = $this->query($sql);
		
		//echo $sql.'<hr/>';
		//echo mysql_error();exit;
		
		$this->close();
		return $rs;
	}
	
	function getProfileByMop($register_id){
		$this->open(0);
		$sql = "SELECT * FROM social_member WHERE register_id='".$register_id."' LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
	function getProfile($id){
		$this->open(0);
		//$sql = "SELECT * FROM social_member WHERE id='".$id."' LIMIT 1";
		$sql = "SELECT m.*,c.city cityName FROM social_member m LEFT JOIN mop_city_lookup c ON m.city=c.id WHERE m.id='".$id."' LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}
	
	function getOtherProfile($id){
		$this->open(0);
		//$sql = "SELECT * FROM social_member WHERE id='".$id."' LIMIT 1";
		$sql = "SELECT m.*,c.city cityName FROM social_member m LEFT JOIN mop_city_lookup c ON m.city=c.id WHERE m.register_id='".$id."' LIMIT 1";
		$rs = $this->fetch($sql);
		$this->close();
		return $rs;
	}  
	
	function MemberList($user_id,$start,$total=20){
		
		$this->mainLayout('Social/member_list.html');
		$sql = "SELECT a.*,c.user_id FROM social_member a 
				LEFT JOIN(
				SELECT b.user_id, b.friend_id AS fid FROM social_network b WHERE b.user_id = ".$user_id."
				) AS c
				ON a.id = c.fid
				WHERE a.id<>".$user_id;
		$sql2 = "SELECT COUNT(id) as total FROM social_member";
		$this->open();
		$list = $this->fetch($sql,1);
		$rs = $this->fetch($sql2);
		$this->close();
		//paging
		$paging = new Paginate();
		$this->assign("list",$list);
		
		$this->assign("pages",$paging->generate($start, $total, $rs['total']));
		//$this->getList("SELECT * FROM social_member WHERE id <> ".$user_id." ORDER BY name",$start,$total,"?members=1");
		return $this;
	}
	function AddFriend($user_id,$friend_id){
		$ok = false;
		if(eregi("([0-9]+)",$user_id)&&eregi("([0-9]+)",$friend_id)){
			$this->open();
			
				$sql = "INSERT IGNORE INTO social_network(user_id,friend_id) VALUES($user_id,$friend_id)";
				
				$q = $this->query($sql);
				if($q){
					$sql = "INSERT IGNORE INTO social_network(user_id,friend_id) VALUES($friend_id,$user_id)";
					$q = $this->query($sql);
					if($q){
						$ok = true;	
					}else{
						$sql = "DELETE FROM social_network WHERE (user_id=$user_id AND friend_id=$friend_id) OR (user_id=$friend_id AND friend_id=$user_id)";
						$this->query($sql);
					}
				}
			
			$this->close();
		}else{
			$ok = true;
		}
		
		return $ok;
	}
	function RemoveFriend($user_id,$friend_id){
		$this->open();
		$sql = "DELETE FROM social_network WHERE (user_id=$user_id AND friend_id=$friend_id) OR (user_id=$friend_id AND friend_id=$user_id)";
		$q = $this->query($sql);
		$this->close();
		return $q;
	}
	function isFriend($user_id,$friend_id){
		
		$sql = "SELECT * FROM social_network WHERE user_id = ".$user_id." AND friend_id=".$friend_id." LIMIT 1";
		if($user_id!=null||$user_id>0&&$friend_id>0){
			$this->open();
			$rs = $this->fetch($sql);
			$this->close();
			if($user_id==$rs['user_id']&&$friend_id==$rs['friend_id']){
				return true;
			}
		}
	}
	function getUserUrl($user_id,$name){
		return "<a href='users.php?u=".$user_id."'>".$name."</a>";
	}
	function getBookmarks($user_id){
		$sql = "SELECT * FROM social_bookmark WHERE user_id=".$user_id." LIMIT 30";
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		return $rs;
	}
	function addBookmark($user_id,$name,$url){
		$sql = "INSERT INTO `sba`.`social_bookmark`
            (
             `user_id`,
             `bookmark_url`,
             `bookmark_name`)
			VALUES (
			        ".$user_id.",
			        '".$url."',
			        '".$name."')";
		$this->open(0);
		$rs = $this->query($sql);
		$this->close();
		return $rs;
	}
	
	function birthday($birthday){
		$birth = explode(' ',$birthday);
		list($month,$day,$year) = explode("/",$birth[0]);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		  $year_diff--;
		return $year_diff;
	}
}
?>
