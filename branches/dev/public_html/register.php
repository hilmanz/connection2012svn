<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH. APPLICATION ."/helper/RegisterHelper.php";

$view = new BasicView();
$app = new RegisterHelper($req);
print $app->main();