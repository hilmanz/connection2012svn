<?php 
include_once "../../../config/config.inc.php";
include_once "../../../engines/functions.php";
$mop = $_SESSION['mop_profile2'];
$user['ConsumerID'] = $mop['UserProfile']['ConsumerID'];
$user['RegistrationID'] = $mop['UserProfile']['RegistrationID'];
$user['CityID'] = $mop['UserProfile']['CityID'];
		
session_start();
$session_id = $_SESSION['mop_sess_id'];

$game_id=3;

if($game_id==1){
	$code='GAME_BERLIN_WALL';
}elseif($game_id==2){
	$code='GAME_DJ';
}elseif($game_id==3){
	$code='GAME_YACHT';
}elseif($game_id==4){
	$code='GAME_ART_MUSEUM';
}else{
	$code='';
}
$params = array('sessId'=>$session_id,
				"PageRef"=>"0","ActivityName"=>"Game",
				"ActivityValue"=>$game_id." - INTERVAL",
				"CPMOO"=>$CPMOO[$code],
				"user"=>$user,
			"handler"=>session_id());
$r = urlencode64(serialize($params));
$url = $CONFIG['BASE_URL']."ping.php?r=".$r;

?>

<script language=javascript>
var i=self.setInterval("check()",1000*15);
document.write("<img src='<?php print $url?>'>");
function check()
{
	document.location='refresh.php';	
}
</script>