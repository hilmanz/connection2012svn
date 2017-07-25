<?php
class activityReportHelper extends Application{

	var $Request;
	var $userID;
	
	function __construct($req,$userID){
	$this->Request = $req;
	$this->userID = $userID;
	}
	
	
	function log($param=NULL,$id=NULL,$expLog=FALSE){
	
		if($param!=NULL){
		$actionID=0;
		$userID = $this->userID;
		$actionValue = NULL;
		
		$sql ="SELECT id FROM tbl_activity_actions WHERE activityName LIKE '".$param."' ";
		$this->open();
		$qData = $this->fetch($sql);
		$this->close();
		if($qData) 	$actionID = $qData['id'];
		$qData=NULL;
		if($id!=NULL) $actionValue = $id;
			
		
		$sql = "
		INSERT IGNORE INTO tbl_activity_log 
		(id,user_id,date_ts,date_time,action_id,action_value) 
		VALUES 
		(NULL,".$userID.",".strtotime(date('Y-m-d H:i:s')).",'".date('Y-m-d H:i:s')."',".$actionID.",'".$actionValue."')
		";
	
		
		//activity log : id 	user_id 	date_ts 	date_time 	action_id 	action_value
		if($userID!=0  && $actionID!=0 ){
		$this->open();
		$this->query($sql);
		$this->close();
		
		}
		
		}
		
		if($expLog==TRUE){
				// 1 	login
				// 2 	article
				// 3 	vote
				// 4 	race
				// 5 	purchase_parts
				// 6 	redeem_merchandise
				// 7 	page
				// 8 	refer friend
				// 9 	update profile
				// 10 	logout
				
		$score = array(
		1 => 1,
		2 => 1,
		3 => 1,
		4 => 5,
		5 => 2,
		6 => 2,
		7 => 1,
		8 => 1,
		9 => 1,
		10 => 0,
		11 => 1,
		12 => 1,
		13 => 1,
		14 => 1,
		15 => 1,
		16 => 1
	
		);
		$actScore = $score[$actionID];
		if($actionID==4){
		$arrCon = explode('_',$actionValue);
		if($arrCon){
			if($arrCon[0]=='lose') $actScore = 2;
		}
		}
		
		$sql = "
		INSERT  IGNORE INTO tbl_exp_point 
		(id,user_id,date_time_ts,date_time,activity_id,score) 
		VALUES 
		(NULL,".$userID.",".strtotime(date('Y-m-d H:i:s')).",'".date('Y-m-d H:i:s')."',".$actionID.",".intval($actScore).")
		";
	
		if($userID!=0  && $actionID!=0 ){
		$this->open();
		$this->query($sql);
		$this->close();
		
		}
		}
		
		
		
	}
	
	
	function activityTime(){
		$userID = $this->userID;
		$countTime =0;
		$sql = "
		SELECT login_count FROM kana_member 
		WHERE 
		id = ".$userID." LIMIT 1";
		$this->open();
		$qData = $this->fetch($sql);
		$this->close();
		$token_this_day = sha1($userID.$qData['login_count'].date('Ymd'));
		$qData = NULL;
		if($userID!=''){
		$sql = "
		SELECT ping_time_ts FROM tbl_activity_time 
		WHERE 
		user_id = ".$userID." and 
		session_token='".$token_this_day."' 
		ORDER BY ping_time_ts DESC LIMIT 1";
		$this->open();
		$qData = $this->fetch($sql);
		$this->close();
		if($qData){
		$countTime = intval(strtotime(date('Y-m-d H:i:s'))) - $qData['ping_time_ts'];
		
		}else  $countTime = 15;
		
		
		// print_r($sql);
		$qData = NULL;
		if( $countTime >= 15){
		
		//activity time : id 	user_id 	ping_time 	ping_time_ts 	session_token a unique session token
		$sql = "
		INSERT INTO tbl_activity_time 
		(id, user_id,ping_time,ping_time_ts,session_token) 
		VALUES 
		(NULL,".$userID.",'".date('Y-m-d H:i:s')."',".strtotime(date('Y-m-d H:i:s')).",'".$token_this_day."')
		";
		// echo $sql;
		// echo $sql;exit;
		$this->open();
		$this->query($sql);
		$this->close();
		}
		}
		
	}
	
	
	function promo_ref($regID,$refID){
	//activity time : id 	user_id 	ping_time 	ping_time_ts 	session_token a unique session token
		$sql = "
		INSERT INTO referral 
		(refID, regID) 
		VALUES 
		('{$refID}',{$regID})
		";
		// echo $sql;
		// echo $sql;exit;
		$this->open();
		$this->query($sql);
		$this->close();
	
	}
	
	
}
?>
