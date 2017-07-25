<?php

include_once "bootstraps.php";
include_once "common.php";
include_once "marlboro_connection/DailyReporting.php";

//error_reporting(E_ALL);

$arg_arr = arguments($argv);
if (is_null($arg_arr['options']['tanggal'] )) {
    $yesterday = date("Y-m-d", time() - 86400);
} else {
    $tanggal = $arg_arr['options']['tanggal'];
    $tanggal_arr = preg_split("/-/", $tanggal);

    if (isValidDate($tanggal_arr[0], $tanggal_arr[1], $tanggal_arr[2])) {
        $yesterday = $tanggal_arr[0] . "-" . $tanggal_arr[1] . "-" . $tanggal_arr[2];
    } else {
        die("format 'tanggal' tidak valid");
    }
}

$log = new Logger("marlboro_connection_daily_bot");
$log->logger_namespace("daily_bot");
$log->verbose(true);
$log->info("starting");

date_default_timezone_set('Asia/Jakarta');

$yesterday_ts = strtotime($yesterday);

print $yesterday . " " . $yesterday_ts . "\n";

$verbose = false;

$conn = open_db(0);
$rep = new DailyReporting($conn, $log);
$rep->date($yesterday);
$rep->date_ts($yesterday_ts);
$rep->run($verbose);


?>