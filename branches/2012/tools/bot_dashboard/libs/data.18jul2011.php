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
	print mysql_error();
}
?>
