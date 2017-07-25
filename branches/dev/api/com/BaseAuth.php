<?php 
define('BANNED_TIME',1);
include_once "BadgeModel.php";
class BaseAuth{
	function __construct(){
		
	}
	function authenticated(){
		global $CONFIG;
		
		//$api_key = mysql_escape_string($api_key);
		$api_key = $_REQUEST['api_key'];
		
		
		//print $api_key;
		$realm = "MBC_API";
		if (!isset($_SERVER['PHP_AUTH_USER'])) {
			header('WWW-Authenticate: Basic  realm="'.$realm.'",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
			header('HTTP/1.0 401 Unauthorized');
			exit;
		} else {
			
			$username = mysql_escape_string($_SERVER['PHP_AUTH_USER']);
			$password = md5(mysql_escape_string($_SERVER['PHP_AUTH_PW']));
			return $this->checkCredential($username, $password, $api_key);
		}
		
	}
	function checkCredential($username,$password,$api_key){
		global $CREDENTIALS;
		//print $api_key."==".$CREDENTIALS[$username]['api_key']."\n";
		//print $password."==".$CREDENTIALS[$username]['password']."\n";
		//print ($api_key==$CREDENTIALS[$username]['api_key'])."\n";
		//strcmp($str1, $str2);
		//print strcmp($api_key,$CREDENTIALS[$username]['api_key'])."\n";
		if(strcmp($api_key,$CREDENTIALS[$username]['api_key'])==0&&(strcmp($password,$CREDENTIALS[$username]['password'])==0)){
			$arr['status']=1;
		}else{
			$arr['status']=0;
			header('WWW-Authenticate: Basic  realm="'.$realm.'",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');
			header('HTTP/1.0 401 Unauthorized'); 
			print "access denied";
			exit;
		}
		//var_dump($arr);
		return $arr;
	}
}
?>