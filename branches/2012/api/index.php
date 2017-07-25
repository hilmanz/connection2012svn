<?php
include_once "bootstraps.php";
session_start();
$api = new BadgeAPI();
$api->run();
?>