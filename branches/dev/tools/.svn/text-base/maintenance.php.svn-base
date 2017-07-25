<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db = "sba";

print "Maintenance ".date("Y-m-d H:i:s")."\n-----------------------\n";

//ambil daftar tanggal
$sql1 = "SELECT tgl 
		FROM 
		(SELECT date,STR_TO_DATE(date,'%d/%m/%Y') as tgl FROM tbl_ba_registrations) a 
		GROUP BY a.tgl";



$conn = mysql_connect($host,$user,$pass);
$q1 = mysql_db_query($db,$sql1);
$tgl = array();
while($fetch=mysql_fetch_assoc($q1)){
	array_push($tgl,$fetch['tgl']);
}

//reset current stats
print "Reset current data.........";
$sql1a = "DELETE FROM tbl_daily_registration";
$q1a = mysql_db_query($db,$sql1a);
if($q1a){
	print "OK\n";
}

foreach($tgl as $dd){
	print "Processing data on ".$dd.".........";
	$sql2 = "INSERT INTO tbl_daily_registration(user_id,tgl,amount) 
	        SELECT sba_id,tgl,COUNT(sba_id) as total FROM (SELECT sba_id,device_id,kota,date,
			STR_TO_DATE(b.date,'%d/%m/%Y') as tgl
			FROM `lookup_sba_device` a
			INNER JOIN
			tbl_ba_registrations b
			ON a.device_id = b.device) c
			WHERE tgl IN ('".$dd."')
			GROUP BY c.sba_id";
	$q2= mysql_db_query($db,$sql2);
	if($q2){
		print "SUCCESS\n";
	}else{
		print "FAILED\n";
	}
}
print "-------------------\n";
print "FINISHED !\n\n";
mysql_close($conn);
print "----------------------------\n\n";
?>