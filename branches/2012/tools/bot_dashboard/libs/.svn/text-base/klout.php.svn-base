<?php
function get_klout_data($rs,$api_key){
	$str_ids = "";
	$n=0;
	foreach($rs as $tw){
		if($n>0){
			$str_ids.=",";
		}
		$str_ids.= htmlspecialchars_decode(mysql_escape_string(trim($tw['twitter_id'])));
		$n++;
	}
	$uri = "http://api.klout.com/1/users/show.json?key=".$api_key."&users=".$str_ids;
	$response = get_url($uri);
	if($response[1]['http_code']==200){
		return json_decode($response[0]);
	}
	//return $str_ids;
}
?>