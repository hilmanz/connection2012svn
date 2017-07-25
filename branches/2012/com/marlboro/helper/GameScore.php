<?php
include_once "../../../api/common_remote.php";
include_once "../../../config/config.remote.php";
class GameScore extends SQLData{
	function __construct(){
		
	}
	function save_score($user_id,$game_id,$level,$score){
		$qry = "INSERT INTO social_game
					(user_id,game_id,level,score,last_submit)
					VALUES
					('$user_id','$game_id','$level','$score',NOW());";
		$this->open(0);
		if($this->query($qry)){
			$res = true;
		}else{
			$res = false;
		}
		$this->close();
		return $res;
	}
	function get_scores_by_game_id($user_id,$game_id){
		global $CONFIG;
		$qry = "SELECT * FROM social_game WHERE user_id='".$user_id."' AND game_id='".$game_id."' 
				ORDER BY level DESC LIMIT 1";
		
		$this->open(0);
		$rs = $this->fetch($qry);
		
		$this->close();
		
		if( count($rs) > 0){
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
}