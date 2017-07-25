<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";

/** DEVICE TRACKING **/
	include_once "deviceTrack.php";
	/** END **/
	/** MOBILE REDIRECTION **/
		include_once "../engines/Utility/Mobile_Detect.php";
		$detect = new Mobile_Detect();
		if ($detect->isMobile()) {
			header('Location: http://marlboro.co.id/conn2012mobile');
		}
	/** END **/


include_once $APP_PATH . APPLICATION . '/App.php';
include_once $APP_PATH."Interaction/Interaction.php";

//SEMENTARA BUAT MENCEGAH COUNTDOWN BUGs
if($_GET['skip'] == 1){
	$_SESSION['countdown'] = 1;
}
/*
if($_SESSION['countdown'] != 1){
	HEADER('Location:countdown.html');
	exit;
}
*/
//MOP
include_once $APP_PATH."MOP/MOPClient_2.php";
include_once $APP_PATH."MOP/MopTracker.php";
$mop_client = new MopClient($req);
$mop = new MopTracker();

$view = new BasicView();

if($CONFIG['LOCAL_DEVELOPMENT']){
	//gak pake mop
	$CONFIG['MOP_LANDING_URL'] = $CONFIG['MOP_DEBUG_URL'];
	
	$app = new App(&$req);
	$app->main();
	print $app;
	die();
}else{
	$app = new App(&$req);
	$app->main();
	print $app;
	die();
}
?>