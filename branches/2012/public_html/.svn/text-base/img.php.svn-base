<?php 
//include_once "../config/config.inc.php";
include_once "common_track.php";
include_once $APP_PATH."MOP/MOPClient_2.php";

$mop = new MopClient($req);

$params = unserialize(urldecode64($_REQUEST['r']));

$sessId = $params['sessId'];
$PageRef = $params['PageRef'];
$ActivityName = $params['ActivityName'];
$ActivityValue = $params['ActivityValue'];
$CPMOO = $params['CPMOO'];
$user = $params['user'];
$handler = $params['handler'];
session_id($handler);
session_start();


$fp = fopen("../logs/track.log","a+");
$str = "";
foreach($_SESSION as $n=>$v){
$str.= $v."\n";
}
$str.="------------------------\n";
fwrite($fp,$str,strlen($str));
fclose($fp);
$sessId=$mop->checkReferral($sessId);
$sess=$mop->track($sessId,$PageRef,$ActivityName,$ActivityValue,$CPMOO,$user);
if( $sess['Result'] < 0 || $sess['Result'] == '99' || $sess['Result'] == '' ){
	session_destroy();
	sendRedirect('index.php');
	exit;
}else{
	$_SESSION['mop_token']=$sess['SessionID'];
}

//render image
header('content-type:image/gif');
readfile('track.gif');
exit();
?>
