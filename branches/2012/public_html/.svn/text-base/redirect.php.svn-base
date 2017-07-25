<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH."Interaction/Interaction.php";
$app = new Interaction(&$req);
//print $app->addTrack(1,45, 1, 2, "http://foobar2.ba-space.com");
$app->track();
//print $app;

$p = (urldecode64($req->getParam('r')));
sendRedirect($p);
die();
?>