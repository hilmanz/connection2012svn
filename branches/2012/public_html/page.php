<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH."Social/SocialApp.php";
//include_once $APP_PATH."MOP/MOPClient.php";
//-->hanya untuk di dev

//--->
$view = new BasicView();
$app = new SocialApp(&$req);
$app->page();
print $app;

?>