<?php
/**
* ADMINISTRATION PAGE
* @author Hapsoro Renaldy N <hapsoro.renaldy@winixmedia.com>
*/
include_once "common.php";
$view = new BasicView();
$user = new UserManager();
$session = new SessionManager("GM_ADMIN");

$_captcha =$req->getPost('captcha');

$_valid = (md5($_captcha) == $_SESSION['mrlbCaptchaSimple']) ? true : false;
$_SESSION['mrlbCaptchaSimple'] = "bed" . rand(00000000,99999999) . "bed";

if($_valid) { 

	if($user->check($req->getPost("username"),$req->getPost("password"))){
		$session->addVariable("username",$req->getPost("username"));
		$session->addVariable("isLogin","1");
		$session->addVariable("uid",$user->userID);
		sendRedirect("index.php");
	}else{
		sendRedirect("index.php?f=1");
	}

}else{
		sendRedirect("index.php?f=1");
	}
print $view->toString($MAIN_TEMPLATE);
?>