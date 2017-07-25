<?php
function store_bulk($bulk,$serialized){
	global $DATABASE,$SCHEMA;
	$today = date("Y-m-d");
	$sql0 = "INSERT INTO ".$SCHEMA.".tbl_bulk(poll_date,raw_content,serialized_data)
		VALUES(NOW(),'".mysql_escape_string($bulk)."','".mysql_escape_string($serialized)."')";
	$conn = open_db(0);
	print "Store bulk data\n";
	mysql_query($sql0);
	close_db($conn);
}
function process_data(){
	global $DATABASE,$SCHEMA;
	$today = date("Y-m-d");
	
	print "Open Database\n";
	$conn = open_db(0);

	print "get unprocessed data\n";
	$sql = "SELECT id,serialized_data FROM ".$SCHEMA.".tbl_bulk
		 WHERE n_status=0 LIMIT 1";
	$q = mysql_query($sql,$conn);
	$fetch = mysql_fetch_assoc($q);
	mysql_free_result($q);
	
	print "flag content as being processed\n";
	//$sql = "UPDATE ".$SCHEMA.".tbl_bulk SET n_status=1 WHERE id=".intval($fetch['id']).""; 
	//mysql_query($sql,$conn);
	
	//$raw_content = $fetch['raw_content'];
	$data = unserialize($fetch['serialized_data']);
	var_dump($data['results']['_c']['entry'][0]['_c']);
	print "Close Database\n";
	close_db($conn);
}
function insert_raw_json($txt){
	global $conn,$DATABASE,$SCHEMA;
	$today = date("Y-m-d");
	$sql = "INSERT INTO ".$SCHEMA.".tbl_raw_json(retrieve_date,txt) VALUES(NOW(),'".mysql_escape_string($txt)."')";
	$q = mysql_query($sql,$conn);
}
function insert_raw_corporate_data($txt){
	global $conn,$DATABASE,$SCHEMA;
	$today = date("Y-m-d");
	$sql = "INSERT INTO ".$SCHEMA.".tbl_raw_corp(retrieve_date,txt) VALUES(NOW(),'".mysql_escape_string($txt)."')";
	
	$q = mysql_query($sql,$conn);
	
}
function save_twitter_data($id,$twitter_id,$json_txt){
	global $conn,$DATABASE,$SCHEMA_TWITTER;
	$o = json_decode($json_txt);
	
	if(sizeof($o->results)>0){
		write_log('twitter_search_db.log','info',"[".$twitter_id."]save twitter data");
		$mentions = array();
		foreach($o->results as $results){
			$new_mentions = get_mentions($results->text);
			if(is_array($new_mentions)){
				$mentions = array_merge($mentions,$new_mentions);
			}
		}
		if(sizeof($mentions)>0){
			
			//we got new mentions.. save the name now :P
			$bulk = array();
			foreach($mentions as $m){
				$bulk[$m] = 1;
			}
			if(save_name($mentions)){
				write_log('twitter_search_db.log','info',"[".$twitter_id."]adding ".sizeof($bulk)." new names ---> OK !");
				//print "adding ".sizeof($bulk)." new names ---> OK !\n";
			}else{
				write_log('twitter_search_db.log','error',"[".$twitter_id."]adding ".sizeof($bulk)." new names ---> FAILED !");
				//print "adding ".sizeof($bulk)." new names ---> FAILED !\n";
			}
			//-->
		}
		
		//only save the data into bulk table if the user have recent updates

		$sql = "INSERT INTO ".$SCHEMA_TWITTER.".tbl_twitter_bulks(smac_name_id,twitter_id,json_txt,added_time)
				VALUES(".$id.",'".mysql_escape_string($twitter_id)."','".mysql_escape_string($json_txt)."',NOW())";
		//print "\n\n".$sql."\n\n";
		$q = mysql_query($sql);
		if($q){
			write_log('twitter_search_db.log','info',"[".$twitter_id."]save bulk OK");
		}else{
			write_log('twitter_search_db.log','error',"[".$twitter_id."]save bulk FAILED");
		}
	}
	
	//update refresh url
	$sql = "UPDATE ".$SCHEMA_TWITTER.".tbl_smac_names 
			SET refresh_url='".mysql_escape_string($o->refresh_url)."' 
			WHERE id=".intval($id)."";

	$q = mysql_query($sql,$conn);
}
function save_name($mentions){
	global $conn,$DATABASE,$SCHEMA_TWITTER;
	$sql = "INSERT IGNORE INTO ".$SCHEMA_TWITTER.".tbl_smac_names(twitter_id,added_time,refresh_url,n_status) VALUES";
	$n=0;
	foreach($mentions as $mention){
		if($n>0){
			$sql.=",";
		}
		$sql.="('".mysql_escape_string($mention)."',NOW(),'',1)";
		$n=1;
	}
	$q = mysql_query($sql,$conn);
	return $q;
}
function get_mentions($txt){
	//$txt = "RT @gelo: woohooo !!! lirik @foobar @may @winners @masters @slavers ";
	preg_match_all('/\@[^\@^\:^\ ]*/',$txt,$matches);
	$mentions = array();
	foreach($matches[0] as $m){
		$mentions[] = str_replace("@","",trim($m));		
	}
	return $mentions;
}
function write_log($log_file,$type, $log_item) {
		if (is_array($log_item)) {
			foreach ($log_item AS $name=>$value) {
				$log_item_temp_arr[] = $name . ': ' . $value;
			}
			$log_line = date('Y-m-d H:i:s') . ' [' . ucfirst($type) . '] ' . implode('; ', $log_item_temp_arr);
		}
		else {
			$log_line = date('Y-m-d H:i:s') . ' [' . ucfirst($type) . '] ' . $log_item;
		}
		if ($type == 'stats') echo "\n";
		echo $log_line . "\n";
		$fh = fopen($log_file, 'a') or die("Can't open log file\n");
		fwrite($fh, $log_line . "\n");
		fclose($fh);
}
?>
