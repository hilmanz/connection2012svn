<?php
include_once "bootstraps.php";

if($_REQUEST['kode']!=NULL){
	$url=$service_uri.'?method=check&kode='.$_REQUEST['kode'];
	$url2=$service_uri.'?method=redeem_code&kode='.$_REQUEST['kode']."&user_id=".$_REQUEST['user_id'];
	$resp = file_get_contents($url);
	$resp2 = file_get_contents($url2);
	//print $url;
}
?>
<html>
<head></head>
<body>
<form action="test.php" method="get">
<div>
<label>User_ID : </label>
<input type="text" name="user_id" value="1" size="30"/>
<label>Kode : </label>
<input type="text" name="kode" value="" size="30"/>
</div>
<div>
<input type="submit" name="GO" value="GO"/>
</div>
</form>
<div style="border:1px solid #333333">
<?php 
//echo $resp;
$rs1 = json_decode($resp);
?>
<span><?php echo $rs1->message;?></span>
</div>
<br/>
<div style="border:1px solid #333333">
<?php 
//echo $resp2;
$rs2 = json_decode($resp2);
if($rs2->status==1):
?>
<span>Kode : <?php print $rs2->data->kode;?></span>
<h3>Badge Detail</h3>
<table width="500">
<tr><td>Name</td><td><?php print $rs2->data->badge->name;?></td></tr>
<tr><td>Tier</td><td><?php print $rs2->data->badge->tier;?></td></tr>
<tr><td>Probability Rate</td><td><?php print $rs2->data->badge->prob_rate;?></td></tr>
<tr><td>Series</td><td><?php if($rs2->data->badge->series_type==1):print "New York";elseif($rs2->data->badge->series_type==2):print "Berlin";else:print "Istanbul";endif;?></td></tr>
<tr><td>Weight</td><td><?php print $rs2->data->badge->weight;?></td></tr>
</table>
<?php else:?>
<?php print "Error : ".$rs2->message;?>
<?php 
endif;
?>
</div>
</body>
</html>
