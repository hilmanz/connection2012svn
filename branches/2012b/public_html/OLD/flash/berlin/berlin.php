<?php
session_start();
$pro = $_SESSION['mop_profile2']["UserProfile"];
$n_status = ($pro['AVType'] == 1 || $pro['AVType'] == 3)? 1 : 0;
$user_id = $_REQUEST['user_id'];
if($n_status == 1){
?>
<script src="bin/js/swfobject.js"></script>
<script>
	var flashvars = {
		user_id:"<?php print $user_id;?>"
	};
	var params = {
		menu: "false",
		scale: "noScale",
		allowFullscreen: "true",
		allowScriptAccess: "always",
		bgcolor: "0x000000",
		wmode: "direct" // can cause issues with FP settings & webcam
	};
	var attributes = {
		id:"BerlinWall"
	};
	swfobject.embedSWF(
		"bin/BerlinWall.swf", 
		"altContent", "798", "564", "10.0.0", 
		"expressInstall.swf", 
		flashvars, params, attributes);
</script>
<table width="798" height="564" cellpadding="0" cellspacing="0" border="0">
    <tr>
    	<td valign="top">
            <div id="altContent">
                <h1>BerlinDJ</h1>
                <p><a href="http://www.adobe.com/go/getflashplayer">Get Adobe Flash player</a></p>
            </div>
    	</td>
    </tr>
</table>
<?php }else{ ?>

<?php } ?>
<iframe src="refresh.php" style="height:1px;"></iframe>