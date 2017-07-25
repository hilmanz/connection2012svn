<?php
class GameService{
	public function getUserStats($user_id,$game_id){
		global $DATABASE,$SCHEMA_WEB;
		$qry = "SELECT * FROM ".$SCHEMA_WEB.".social_game WHERE user_id='".$user_id."' AND game_id='".$game_id."' 
				ORDER BY level DESC LIMIT 1";
		$conn = open_db(0);
		
		
		$q = mysql_query($qry,$conn);
		$rs = mysql_fetch_assoc($q);
		
		$sql = "INSERT INTO marlboro_connect.game_track(tanggal,user_id,game_id)
				VALUES(NOW(),".mysql_escape_string($user_id).",".mysql_escape_string($game_id).")";
		$q = mysql_query($sql,$conn);
		mysql_close($conn);
		
		
		
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
	public function save_score($user_id,$game_id,$level,$score){
		global $DATABASE,$SCHEMA_WEB;
		$qry = "INSERT INTO ".$SCHEMA_WEB.".social_game
					(user_id,game_id,level,score,last_submit)
					VALUES
					('".mysql_escape_string($user_id)."','".mysql_escape_string($game_id)."'
					,'".mysql_escape_string($level)."','".mysql_escape_string($score)."',NOW());";
		
		$conn = open_db(0);
		if(mysql_query($qry,$conn)){
			$res = true;
		}else{
			$res = false;
		}
		mysql_close($conn);
		return $res;
	}
	public function save_badge($user_id,$badge_id,$kode){
		global $DATABASE,$SCHEMA_CODE;
		$sql = "INSERT INTO ".$SCHEMA_CODE.".badge_redeem
					(user_id,redeem_time,kode)
					VALUES
					('".mysql_escape_string($user_id)."',NOW()
					,'".mysql_escape_string($kode)."');";
		
		$conn = open_db(0);
		if(mysql_query($sql,$conn)){
			$redeem_id = mysql_insert_id();
			$sql = "INSERT INTO ".$SCHEMA_CODE.".badge_inventory(user_id,redeem_time,badge_id,redeem_id)
					VALUES('".$user_id."',NOW(),".$badge_id.",".$redeem_id.")";
					if(mysql_query($sql,$conn)){
						$res = true;
						$badge_id=mysql_escape_string($badge_id);
						$user_id=mysql_escape_string($user_id);
						$sql = "SELECT * FROM marlboro_code.badge_catalog WHERE id=".$badge_id;
						$q = mysql_query($sql,$conn);
						
						$badge = @mysql_fetch_assoc($q);
						
						$sql = "SELECT * FROM marlboro_connect.social_member WHERE register_id=".$user_id;
						$q = mysql_query($sql,$conn);
						$user = @mysql_fetch_assoc($q);
						//save the news
						//ini pake link
						$badgeLink = "<a href=\'javascript:void(0);\' onclick=\'javascript:showBadgePopup(\"".$badge_id."\");\' >\"".$badge['name']."\"</a>";
						$msg = "<a href=\'javascript:void(0);\' onclick=\'javascript:showProfilePopup(\"".$user_id."\");\'>".$user['name']."</a> has unlocked $badgeLink badge";
			
						$qry = "INSERT INTO marlboro_connect.social_tradenews (tradenews_date,tradenews_content,user_id) VALUES (NOW(),'".mysql_escape_string($msg)."',".$user_id.");";
						mysql_query($qry,$conn);
						
						
						
						
					}else{
						$res = false;
					}
		}else{
			$res = false;
		}
		
		mysql_close($conn);
		return $res;
	}
}
?>