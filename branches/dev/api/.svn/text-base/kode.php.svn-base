<?php
//session_start();
include_once "bootstraps.php";
//include_once "functions.php";
include_once "../engines/Utility/PHPExcelWrapper.php";
if($_REQUEST['amount']!=NULL){
	$req = http_build_query($_REQUEST);
	$url=$service_uri.'?method=generate_code&'.$req;
	$resp = file_get_contents($url);
	//print $url;
}
    $channel = array('','Baliho','Magazine','POG','Poster','Tent Card','Digital Banners','Rich Media Banners','SBA','DST','LAMP');
	$rs = json_decode($resp);
	
	if(is_array($rs->data)):
	$no=1;
	$nn=0;
	$results = array();
	foreach($rs->data as $data):
		if($_REQUEST['type']=="1"){$reusable="yes";}else{$reusable="no";}
		$results[$nn]['no'] = $no;
		$results[$nn]['kode'] = $data;
		$results[$nn]['tier'] = $_REQUEST['tier'];
		$results[$nn]['channel'] = $channel[$_REQUEST['channel']];;
		$results[$nn]['location'] = $_REQUEST['city'];
		$results[$nn]['description'] = $_REQUEST['event'];
		$results[$nn]['reusable'] = $reusable;
		$results[$nn]['start_date'] = $_REQUEST['startDate'];
		$results[$nn]['expire_date'] = $_REQUEST['expireDate'];
		$nn++;$no++;
	endforeach;endif;
	 if($_REQUEST['xls']=="1"){
		$xls = new PHPExcelWrapper();
		//if($_SESSION['results']!=NULL){
		//	$results = $_SESSION['results'];
		//	$_SESSION['results']=NULL;
		//}
		$xls->setHeader(array('no','kode','tier','channel','location','description','reusable','start','expire'));
		$xls->getExcel($results,"REDEEM CODE");
		die();
	}
	?>
<html>
<head>
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.15.custom.css" />
<link rel="stylesheet" href="css/cpp.css" />
<script src="js/jquery-1.6.2.min.js" type="text/javascript" language="javascript"></script>
<script src="js/jquery-ui-1.8.15.custom.min.js" type="text/javascript" language="javascript"></script>
<script>
	$(function() {
		$( "#datepicker" ).datepicker({dateFormat: 'yy-mm-dd'});
		$( "#datepicker2" ).datepicker({dateFormat: 'yy-mm-dd'});
	});
</script>


</head>
<body>
<div id="wrapper">
<form action="kode.php" method="get">
<table>
<tr><td>

<label>Amount to Generate : </label></td><td>
<input type="text" name="amount" value="" size="30"/>
</td></tr>
<tr><td>
<label>Channel : </label></td><td>
<select name="channel" id="channel">
  <option value="1">Baliho</option>
  <option value="2">Magazine</option>
  <option value="3">POG</option>
  <option value="4">Poster</option>
  <option value="5">Tent Card</option>
  <option value="6">Digital banners</option>
  <option value="7">Rich Media Banners</option>
  <option value="8">SBA</option>
  <option value="9">DST</option>
<option value="10">LAMP</option>
</select>

</td></tr>
<tr><td>
<label>Tier : </label></td><td>
<label for="tier"></label>
<select name="tier" id="tier">
  <option value="1">Low</option>
  <option value="2">Medium</option>
  <option value="3">High</option>
</select>
</td></tr>
<tr><td>
<label>City/Location* : </label></td><td>
<input type="text" name="city" value="" size="30"/>
</td></tr>
<tr><td>
<label>Description* : </label></td><td>
<textarea name="event" cols="50" rows="10"></textarea>
</td></tr>
<tr><td>
<label>Reusable ? </label></td><td>
<label for="type"></label>
<select name="type" id="type">
  <option value="0">No</option>
  <option value="1">Yes</option>
</select>
</td></tr>
<tr><td>
<label>Start Date* : </label></td><td>
<input id="datepicker" type="text" name="startDate">
</td></tr>
<tr><td>
<label>Expiration Date* : </label></td><td>
<input id="datepicker2" type="text" name="expireDate">
</td></tr>
<tr><td>
<label>Wildcard ? : </label></td><td>
<select name="wildcard" id="wildcard">
  <option value="0">No</option>
  <option value="1">Yes</option>
</select>
</td></tr>

<tr><td>
<label>Export to Excel ? : </label></td><td>
<select name="xls" id="xls">
  <option value="0">No</option>
  <option value="1" selected="selected">Yes</option>
</select>
</td></tr>

</table>
<div>
<input type="submit" name="Generate" value="Generate"/>
</div>
</form>
<div class="box">

  <p>&nbsp;</p>
  <table width="800px" border="1" cellspacing="0" cellpadding="0">
    <tr>
      <td width=""><strong>No</strong>.</td>
      <td width=""><strong>Kode</strong></td>
       <td width=""><strong>Tier</strong></td>
      <td width=""><strong>Channel</strong></td>
      <td width=""><strong>City / Location</strong></td>
      <td width=""><strong>Event</strong></td>
      <td width=""><strong>Reusable</strong></td>
      <td width=""><strong>Start Date</strong></td>
       <td width=""><strong>Expiration Date</strong></td>
    </tr>
    <?php
    $channel = array('','Baliho','Magazine','POG','Poster','Tent Card','Digital Banners','Rich Media Banners','SBA','DST');
	$rs = json_decode($resp);
	
	if(is_array($rs->data)):
	$no=1;
	$nn=0;
	$results = array();
	foreach($rs->data as $data):
		if($_REQUEST['type']=="1"){$reusable="yes";}else{$reusable="no";}
		$results[$nn]['no'] = $no;
		$results[$nn]['kode'] = $data;
		$results[$nn]['tier'] = $_REQUEST['tier'];
		$results[$nn]['channel'] = $channel[$_REQUEST['channel']];;
		$results[$nn]['location'] = $_REQUEST['city'];
		$results[$nn]['description'] = $_REQUEST['event'];
		$results[$nn]['reusable'] = $reusable;
		$results[$nn]['start_date'] = $_REQUEST['startDate'];
		$results[$nn]['expire_date'] = $_REQUEST['expireDate'];
		$nn++;
		
	?>
    <tr>
      <td><?php print $no;?></td>
      <td><?php print $data;?></td>
      <td><?php print $_REQUEST['tier'];?></td>
      <td><?php print $channel[$_REQUEST['channel']];?></td>
      <td><?php print $_REQUEST['city'];?></td>
      <td><?php print $_REQUEST['event'];?></td>
      <td><?php if($_REQUEST['type']=="1"){print "yes";}else{print "no";} ?></td>
      <td><?php print $_REQUEST['startDate'];?></td>
      <td><?php print $_REQUEST['expireDate'];?></td>
    </tr>
    <?php
	$no++;
	endforeach;
	//if($_SESSION['results']==NULL){
	//	$_SESSION['results'] = $results;
	//}
	endif;
	 if($_REQUEST['xls']=="1"){
		$xls = new PHPExcelWrapper();
		//if($_SESSION['results']!=NULL){
		//	$results = $_SESSION['results'];
		//	$_SESSION['results']=NULL;
		//}
		$xls->setHeader(array('no','kode','tier','channel','location','description','reusable','start','expire'));
		$xls->getExcel($results,"REDEEM CODE");
		die();
	}
	$rr = http_build_query($_REQUEST);
	print "<a href='kode.php?".$rr."&xls=1'>Export to Excel</a>";
	?>
  </table>
</div>
</body>
</html>