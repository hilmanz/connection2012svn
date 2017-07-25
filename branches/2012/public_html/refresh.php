<?php 
// die();
include_once "../config/config.inc.php";
include_once "../engines/functions.php";
$mop = $_SESSION['mop_profile2'];
$user['ConsumerID'] = $mop['UserProfile']['ConsumerID'];
$user['RegistrationID'] = $mop['UserProfile']['RegistrationID'];
$user['CityID'] = $mop['UserProfile']['CityID'];
		
session_start();
$session_id = $_SESSION['mop_sess_id'];


$params = array('sessId'=>$session_id,
		"user"=>$user,
		"handler"=>session_id());
$r = urlencode64(serialize($params));
$url = $CONFIG['BASE_URL']."ping.php?r=".$r;

?>

<script language=javascript>
var i=self.setInterval("check()",1000*30);
document.write("<img src='<?php print $url?>'>");
function check()
{
	document.location='refresh.php';	
}
</script>
