<?php
include_once "common.php";
include_once $ENGINE_PATH."Utility/SessionManager.php";
include_once $APP_PATH."Social/SocialApp.php";
include_once $APP_PATH."Interaction/Interaction.php";

$tracker = new Interaction(&$req);
$tracker->doTrack(1,$_GET['uid'], 3, 2, "download.php?f=".$_GET['f']);

$speed = null; 
while (ob_get_level() > 0){ 
	ob_end_clean();         
}          

$filename = strtolower($_GET['f']); 
$exts = split("[/\\.]", $filename); 
$n = count($exts)-1; 
$exts = $exts[$n]; 

$size = sprintf('%u', filesize('contents/download/'.$_GET['f']));         
header('Expires: 0');         
header('Pragma: public');         
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');         
header('Content-Type: application/octet-stream');         
header('Content-Length: ' . $size);         
header('Content-Transfer-Encoding: binary');          
header('Content-disposition: attachment; filename=' . strtolower(str_replace(' ','-',$_GET['name'])) . '.' . $exts);
readfile('contents/download/' . $_GET['f']);
exit;
