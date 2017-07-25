<?php
session_start();

$_SESSION['countdown'] = 1;

rename("index.html", "countdown.html");
HEADER('location:index.php');
exit;
?>