<?php
class GameService{
	public function getUserStats($user_id,$game_id=0){
	
		global $CONFIG;
		//print pwd();
		$this->log($user_id."-".$game_id);
		$qry = "SELECT * FROM ".SCHEMA_CONNECTION.".social_game WHERE user_id='".$user_id."' AND game_id='".$game_id."' 
				ORDER BY last_submit DESC LIMIT 1";
		$conn = open_db(0);
		$this->log('userStat - ' .$user_id );
		// $this->log($qry);

		$q = mysql_query($qry,$conn);
		$rs = mysql_fetch_assoc($q);
				// print_r($rs);exit;
		$sql = "INSERT INTO ".SCHEMA_CONNECTION.".game_track(tanggal,user_id,game_id)
				VALUES(NOW(),".mysql_escape_string($user_id).",".mysql_escape_string($game_id).")";
		$q = mysql_query($sql,$conn);
		mysql_close($conn);
		// $this->log($sql);
		
		if( $rs['user_id'] > 0){
			
			return $rs;
		}else{
			$rs = array();
			$rs['id'] = 0;
			$rs['user_id'] = 0;
			$rs['game_id'] = 0;
			$rs['level'] = 0;
			$rs['score'] = 0;
			$rs['last_submit'] = '0000-00-00';
			return $rs;
		}
	}
	private function log($msg){
		$str = date("Y-m-d H:i:s")." - ".$msg.PHP_EOL;
		  $fp = fopen("out/out.log","a+");
		fwrite($fp,$str,strlen($str));
		fclose($fp);
	}
	public function save_score($user_id,$game_id,$level,$score){
		global $CONFIG;
		$qry = "INSERT IGNORE INTO ".SCHEMA_CONNECTION.".social_game
					(user_id,game_id,level,score,last_submit)
					VALUES
					('".mysql_escape_string($user_id)."','".mysql_escape_string($game_id)."'
					,'".mysql_escape_string($level)."','".mysql_escape_string($score)."',NOW());";
		// $this->log($qry);
		$this->log('save_score - ' .$user_id );
		$conn = open_db(0);
		if(mysql_query($qry,$conn)){
			$res = true;
		}else{
			$res = false;
		}
		mysql_close($conn);
				
		return $res;
	}
	
	public function save_badge($user_id,$badge_id,$kode,$token=null,$level=0){
		global $CONFIG;
		$hashed_code = $kode;
		$this->log($user_id.'-'.$badge_id.'-'.$kode.'-'.$token);
		//validation
		//cek user is in member
		$getUser = $this->getUserNotShadow($user_id);
		if($getUser==false) return false;
		
		//forbid badge 11,12
		$arrForbidbadge = array(11,12);
		if(in_array($badge_id,$arrForbidbadge)) {
			$res = false;
			return $res;
		}
				//cek kode, must be GAMEBADGE		
		// if($kode != 'berlin1'){
			// $kode = $kode.$game_id;
			// if($kode != 'GAMEBADGE'.$game_id) return false;
			// if($kode != 'GAMEBADGE') return false;
		// }
		//$log->info("debug : ".json_encode($_SERVER['QUERY_STRING']));
		//ini harus di delete ketika berlin1 uda gw fix - duf
		// if($kode!='berlin1'){
			$decoded = unserialize(urldecode64($kode));
			$this->log(json_encode($decoded));
			$kode = $decoded['game_id'];
		// }
		$this->log("real game_id --> {$kode}");
		//badge caps per days
		$badgeCaps = $this->getCapsBadgePerdays($user_id,$kode);
		if($badgeCaps==false) return false;
		
		//cek token, must true
		if(strlen($kode) > 0){
			$tokenWeb = SHA1(SHA1(GAME_TOKEN).$user_id.$hashed_code);
		}
		$this->log('s->'.(SHA1(GAME_TOKEN)).$user_id.$hashed_code);
		$this->log("tokenweb : {$tokenWeb} - {$token}");
		if(strlen($tokenWeb) == 0 || strlen($token) == 0) return false;
		if($tokenWeb!=$token) return false;
		
		//cek apakah gameidnya valid ?
		if($kode!='berlin1'&&$kode!='BerlinLightsGM'&&$kode!='IstanbulChaserGM'&&$kode!="IstanbulCruiseGM"&&$kode!="NewYorkSkyLineGM"&&$kode!="NewYorkArtQuestGM"){
			return false;
		}
		$sql = "INSERT INTO ".SCHEMA_CODE.".badge_redeem
					(user_id,redeem_time,kode)
					VALUES
					('".mysql_escape_string($user_id)."',NOW()
					,'".mysql_escape_string($kode)."');";
		$this->log($sql);
		$conn = open_db(0);
		if(mysql_query($sql,$conn)){
		
			$redeem_id = mysql_insert_id();
			$sql = "INSERT INTO ".SCHEMA_CODE.".badge_inventory(user_id,redeem_time,badge_id,redeem_id)
					VALUES('".$user_id."',NOW(),".$badge_id.",".$redeem_id.")";
					if(mysql_query($sql,$conn)){
				
						$res = true;
						$badge_id=mysql_escape_string($badge_id);
						$user_id=mysql_escape_string($user_id);
					
						$sql = "SELECT * FROM ".SCHEMA_CODE.".badge_catalog WHERE id=".$badge_id;
						$q = mysql_query($sql,$conn);
						
						$badge = @mysql_fetch_assoc($q);
						
						$sql = "SELECT * FROM ".SCHEMA_CONNECTION.".social_member WHERE register_id=".$user_id;
						$q = mysql_query($sql,$conn);
						$user = @mysql_fetch_assoc($q);
						//save the news
						//ini pake link
						$activity_values = $badge_id;
						$msg = "<a class=\"popup_profile\" href=\"#\" >".$user['name']."</a> has unlocked the {$badge['name']} badge, after playing a game";
						$qry = "INSERT INTO ".SCHEMA_CONNECTION.".social_tradenews (tradenews_date,tradenews_content,user_id,activity_values) VALUES (NOW(),'".mysql_escape_string($msg)."',".$user_id.",'{$activity_values}');";
						mysql_query($qry,$conn);
						
				$this->log($sql);		
						
					}else{
						$res = false;
					}
		}else{
			$res = false;
		}
		
		mysql_close($conn);
		return $res;
	}
	
	public function getCapsBadgePerdays($user_id,$kode){
		
		//check got badge each date must 3 times
		$sql = "
		SELECT count(id) as total 
		FROM ".SCHEMA_CODE.".badge_redeem
		WHERE 
		user_id = {$user_id} 
		AND kode IN ('{$kode}')
		AND DATE(redeem_time) =  DATE(NOW()) 
		";
		$this->log($sql);
		$conn = open_db(0);
		$q = mysql_query($sql,$conn);
		mysql_close($conn);
		$totalGotBadgeThisDays = @mysql_fetch_assoc($q);
		$this->log($totalGotBadgeThisDays['total']);
		if($totalGotBadgeThisDays['total']>=3) return false;
		return true;
		
		}
		
	public function getUserNotShadow($user_id){
		
		//check got badge each date must 3 times
		$sql = "
		SELECT count(register_id) as total FROM ".SCHEMA_CONNECTION.".social_member WHERE register_id='".$user_id."' 
		LIMIT 1
		";
		
		$conn = open_db(0);
		$q = mysql_query($sql,$conn);
		mysql_close($conn);
		$getUser = @mysql_fetch_assoc($q);
		// print_r($getUser);
		if($getUser['total']>0) return true;
		return false;
		
		}	
		
}
?>
