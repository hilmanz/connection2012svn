<?php
include_once "../../config/config.inc.php";

class mobile{
private $curl;

function __construct(){
include_once "curl_class.php";
$this->curl = new curl_class();
}



function getRegistrationData(){
		global $CONFIG;
		$params = array();
		$params['method']  = "add_registration_ipad_data";
$url = $CONFIG['BADGE_API']."mobile.php?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r(($resp_txt));


}

function getEmailEntryAndYellowCab(){
		global $CONFIG;
		$params = array();
		$params['method']  = "add_email_entry_and_yellow_cab_ipad_data";
$url = $CONFIG['BADGE_API']."mobile.php?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r(($resp_txt));


}

function get_yellow_cabs_badge_for_mobile(){

		global $CONFIG;
		$params = array();
		$params['method']  = "getYellowCabsHuntWinnerEventbadges";
		$url = $CONFIG['BADGE_API']."mobile.php?".http_build_query($params);
		$resp_txt = $this->curl->get($url);
		print_r(($resp_txt));


}
//SHA1(SHA1(SCHEMA_CONNECTION).$user_id.'YELLOWCABS'.date('Ymd'));


}


$class = new mobile;

$class->getRegistrationData();
$class->getEmailEntryAndYellowCab();
$class->get_yellow_cabs_badge_for_mobile();
die();

?>
