<?php
include_once "../../config/config.inc.php";

class auction{
private $curl;

function __construct(){
include_once "curl_class.php";
$this->curl = new curl_class();
}



function getWinnerOfAuction(){
		global $CONFIG;
		$params = array();
		$params['method']  = "getAuctionWinner";
$url = $CONFIG['BADGE_API']."index.php?".http_build_query($params);
$resp_txt = $this->curl->get($url);
print_r(($resp_txt));


}





}


$class = new auction;

$class->getWinnerOfAuction();

die();

?>
