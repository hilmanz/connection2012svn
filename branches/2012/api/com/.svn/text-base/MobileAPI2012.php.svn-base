<?php 

define('BANNED_TIME',1);
class MobileAPI extends curl_class{
	var $model;
	var $time_banned = 0; //10 minutes banned time
	var $retry = 0;
	var $next_allowed_time = 0;
	var $urlAPI='';
	var $codeHelper;
	var $newsHelper;
	function __construct(){
		global $CONFIG;
		$this->urlAPI = $CONFIG['BADGE_API'];
	}
	
	function run(){
		
		$method = @$_REQUEST['method'];
		if(method_exists($this, $method)){
					$data = $this->$method();
					$this->toResponds($data);
		}else {
					$data = $this->noMethod();
					$this->toResponds($data);
		}
	
		
	}
	
	function toResponds($data=null){
		$respond = json_encode(array('status'=>0,'message'=>'wrong given parameter or method','data'=>null));
		if($data!=null) $respond = $data;
	
		//header('Content-type: application/json');
		print_r($respond);exit;


	}
		
	function noMethod(){
		$msg = "Please Input Right Method";
		return json_encode($msg);	
	}
	
	function getUserID(){
		$email = @$_REQUEST['email'];
		$register_id = @$_REQUEST['register_id'];
		return $this->get($this->urlAPI."index.php?method=getUserID&email={$email}&register_id={$register_id}");
	
	}
	
	
	function getProfile(){
		$userid = @$_REQUEST['userid'];
		return $this->get($this->urlAPI."index.php?method=getProfile&userid={$userid}");
	
	}
			
	function getInbox(){	
		$userid = @$_REQUEST['userid']; //must register id or conditinal 325603
		$profile = json_decode($this->get($this->urlAPI."index.php?method=getRegisterIDForMobile&userid={$userid}"));
		$userid = $profile->register_id;
		return $this->get($this->urlAPI."index.php?method=getInbox&userid={$userid}");
	
	}
	
	function getUpdates(){
		$page = @$_REQUEST['page'];
		$limit = 5;
		$res = json_decode($this->get($this->urlAPI."index.php?method=getMergeAllActivityNews&page={$page}&limit={$limit}")); 
		// print_r($res);exit;
		$data["numrows"]= count($res->data);
		foreach($res->data as $key => $val){
			$datas[$key]["id"]=$val->id;
			// $data[$key]["userid"]="";
			// $data[$key]["photourl"]="";
			$datas[$key]["detail"]=$val->content;
			$datas[$key]["datetime"]=$val->date;
		}
		$data["rows"] = $datas;
		return json_encode($data);
	
	}
	
	function getAllBadge(){
		$data = $this->get($this->urlAPI."index.php?method=getAllBadgeDetail"); 
		return $data;
	}
	
	function getListUserBadge(){
		$userid = @$_REQUEST['userid'];
		$profile = json_decode($this->get($this->urlAPI."index.php?method=getRegisterIDForMobile&userid={$userid}"));
		$userid = $profile->register_id;
		$res = json_decode($this->get($this->urlAPI."index.php?method=get_actual_inventory&user_id={$userid}")); 
		foreach($res->data as $key => $val){
			$data[$key]['badgeid'] = $val->badge_id;
			$data[$key]['badgetitle'] = $val->nameBadge;
			$data[$key]['badgecount'] = $val->total;
			$data[$key]['badgeurl'] = $val->img;
		}
		
		return json_encode($data);
	}
	
	function getDetailBadge(){
		$badgeid = @$_REQUEST['badgeid']; 
		$res = json_decode($this->get($this->urlAPI."index.php?method=get_badge_detail&badge_id={$badgeid}")); 
		// print_r($res);
			$data["badgeid"]= $res->data->badge_id;
			$data["badgetitle"] =$res->data->name;
			$data["detail"] =$res->data->description;
			$data["badgeurl"] =$res->data->img;
		return json_encode($data);
	}
	
	function redeemcode(){

		// strict-in lagi.. cek DB klo tanggal masih kurang dari 24 jam.. ga boleh isi
			$userid = $_REQUEST['userid']; //must register id or conditinal 325603
			$profile = json_decode($this->get($this->urlAPI."index.php?method=getRegisterIDForMobile&userid={$userid}"));
			$userid = $profile->register_id;
			$_code = $_REQUEST['codeid'];
			// echo $_code.'<br>';
			if($_code != ''){
			// echo 'dapet code <br>';
				$res=json_decode($this->inputCodeSuccess($userid,$_code));
				
				if(intval($res->status) == 1){
					// echo 'sukses dapet code <br>';
					global $CONFIG;
					// if($CONFIG['enable_news']){
						// $this->newsHelper->unlockBadge($res->data->badge->name,$res->data->badge->id);
					// }
					
					$datas["status"] = "OK";
					$datas["msg"] = "You Have Obtained {$res->data->badge->name} Badges";
					$datas["badgeid"]= $res->data->badge->id;
					$datas["badgetitle"] =$res->data->badge->name;
					$datas["badgeurl"] = "{$res->data->badge->id}.jpg";
					
					$data =  json_encode($datas);
				}else {
					// echo 'ga ada kode nya <br>';
					$data =  json_encode(array('status'=>0,'message'=>'please input right Code'));
				}
			}else{
				// echo 'ga dapet code <br>';
				$data =  json_encode(array('status'=>0,'message'=>'please fill the Code'));
			}
			// exit;
		return $data;
	}
	
	function inputCodeSuccess($user_id,$code)
	{
		$chck = array("method"=>"check","kode"=>$code);
		
		$check_response = $this->get($this->urlAPI."index.php?".http_build_query($chck));
		$o_resp = json_decode($check_response);
		
		if($o_resp->status=="1"){
			// echo 'ada kode nya <br>';
			$data = array("method"=>"redeem_code","user_id"=>$user_id,"kode"=>$code,"badge"=>$o_resp->data->badge);
			$response = $this->get($this->urlAPI."index.php?".http_build_query($data));
			// print_r($response);
			return $response;
		}
		
		return $check_response;
		
	}
	
	
	function sync_data(){
		
		$data['method'] = 'sync_data';
		$data['token'] = mysql_escape_string(@$_REQUEST['token']);
		$data['register_id'] = mysql_escape_string(@$_REQUEST['register_id']);
		$data['email'] = mysql_escape_string(@$_REQUEST['email']);
		$data['firstname'] = mysql_escape_string(@$_REQUEST['firstname']);
		$data['lastname'] = mysql_escape_string(@$_REQUEST['lastname']);
		$data['avtype'] = mysql_escape_string(@$_REQUEST['avtype']);
		// $data['token'] = SHA1('connection2012'.SHA1($data['register_id'].$data['email']).date('Y-m-d H:i'));
		
		$check_response = $this->get($this->urlAPI."index.php?".http_build_query($data));
		// print_r($check_response);exit;
		return $check_response;
	}
	
	
	function add_registration_ipad_data(){
	
		//check lastid in table
		$sql = "SELECT DISTINCT id FROM ".SCHEMA_REPORT.".tbl_ipad_data_registration ORDER BY id DESC LIMIT 1";
		$conn = open_db(0);
		
		// $qData = mysql_query($sql,$conn);
		$qData = fetch($sql,$conn);
		
		$lastid = $qData['id'];
		if(!$lastid) $lastid=0;
		// $lastid=0;
		$beterbeApiKey = 'marlboro_connect_kana_4pik3y_@CC355';
		$beterbeSecretKey = 'marlboro_connect_@ppsecr3T_K3Y';
		
		$token = sha1('getdatareg'.$beterbeSecretKey.$lastid.$beterbeApiKey);
		//give lastid
		// return IPADURL.'getdatareg/'.$token.'/'.$lastid;
		$data  = json_decode($this->get(IPADURL.'getdatareg/'.$token.'/'.$lastid));
			
		// print_r('<pre>');print_r($data);exit;
		if($data->numrows!=0){
			$data_gather_date_time = date('Y-m-d H:i:s');
			$data_gather_date_ts = time();
			$total = 1;
			$q = array();
				foreach($data->regdata as $value){
				
					$sql = "
					INSERT IGNORE INTO 
					".SCHEMA_REPORT.".tbl_ipad_data_registration 
					(id,email,survey_date,surveyor,data_gather_date_time,data_gather_date_ts,brandpref,nickname,birthdate,gender,firstname,lastname,entourageid,ba_name,ba_userid) 
					VALUES 
					(".intval($value->id).",'".$value->email."','".$value->survey_date."','".$value->ba_userid."','".$data_gather_date_time."','".$data_gather_date_ts."','".$value->brandpref."','".$value->nickname."','".$value->birthdate."','".$value->gender."','".$value->firstname."','".$value->lastname."','".$value->entourageid."','".$value->ba_name."','".$value->ba_userid."')
					";
					 mysql_query($sql,$conn);
					// print_r($sql);exit;
					$sql = "
					INSERT IGNORE INTO 
					".SCHEMA_CONNECTION.".social_member
					(email,n_status,mobile_type,name,birthday,sex,Brand1_ID,last_name) 
					VALUES 
					('".$value->email."',0,'3','".$value->nickname."','".$value->birthdate."','".$value->gender."','".$value->brandpref."','".$value->lastname."')
					";
					mysql_query($sql,$conn);
					
					
					$q[] = "id => ".intval($value->id);
					// $q['q'] = $sql;
					$total+=$total;
					if($total > 10) break;
				}
		}else $q = "all data sync";
		
		mysql_close($conn);
		return $q;
	}
	
	
	function add_email_entry_and_yellow_cab_ipad_data(){
	
		//check lastid in table
		$sql = "SELECT DISTINCT id FROM ".SCHEMA_REPORT.".tbl_ipad_data_email_entry_and_yellow_cab ORDER BY id DESC LIMIT 1";
		$conn = open_db(0);
		
		// $qData = mysql_query($sql,$conn);
		$qData = fetch($sql,$conn);
		
		$lastid = $qData['id'];
		if(!$lastid) $lastid=0;
		// $lastid=0;
		$beterbeApiKey = 'marlboro_connect_kana_4pik3y_@CC355';
		$beterbeSecretKey = 'marlboro_connect_@ppsecr3T_K3Y';
		
		$token = sha1('getemailentry'.$beterbeSecretKey.$lastid.$beterbeApiKey);
		//give lastid
		// return IPADURL.'getdatareg/'.$token.'/'.$lastid;
		$data  = json_decode($this->get(IPADURL.'getemailentry/'.$token.'/'.$lastid));
			
		// print_r('<pre>');print_r($data);exit;
		if($data->numrows!=0){
			$data_gather_date_time = date('Y-m-d H:i:s');
			$data_gather_date_ts = time();
			$total = 1;
			$q = array();
				foreach($data->emaildata as $value){
				
					$sql = "
					INSERT IGNORE INTO 
					".SCHEMA_REPORT.".tbl_ipad_data_email_entry_and_yellow_cab 
					(id,email,survey_date,data_gather_date_time,data_gather_date_ts,firstname,lastname,ba_name,ba_userid,eventtype) 
					VALUES 
					(".intval($value->id).",'".$value->email."','".$value->survey_date."','".$data_gather_date_time."','".$data_gather_date_ts."','".$value->firstname."','".$value->lastname."','".$value->ba_name."','".$value->ba_userid."','".$value->eventtype."')
					";
					 mysql_query($sql,$conn);
					// print_r($sql);exit;						
					
					$q[] = "id => ".intval($value->id);
					$total+=$total;
					if($total > 10) break;
				}
		}else $q = "all data sync";
		
		mysql_close($conn);
		return $q;
	}
	
	
	function getYellowCabsHuntWinnerEventbadges(){
			
		$data['method'] = 'get_yellow_cabs_badge_for_mobile';
		$check_response = $this->get($this->urlAPI."index.php?".http_build_query($data));

		return $check_response;
	}
	
}
?>
