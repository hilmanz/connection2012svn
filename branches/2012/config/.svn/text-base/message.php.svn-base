<?php

$message['tradeBadge'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> has traded with {$trader}";
$message['tradeBadgeBox'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> wants to trade {$need_name} badge with  {$with_name} badge";
$message['highestBid'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> is the highest bid now, on {$auction_name} auction";
$message['unlockBadge'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> has unlocked {$badgeName} badge!";
$message['freeBagdeAct'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> just got the {$badgeName} badge!";
$message['freeBagdeActExisting'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> just got the {$badgeName} and the {$extrabadgeName} badges!";
$message['winAuction'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> has win {$auction_name} auction";
$message['newUser'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> is now a new connection from  {$cityName} ";
$message['successRedeem'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a> just redeem  {$merchandise_item} ";
$message['afterNYCabHuntBadge'] = "<a class=\"popup_profile\" href=\"#\" >".$username."</a>  just unlocked Yellow Cab Badge From Yellow Cab Hunt Event !";

//inbox
$message['freeBagde'] = "Welcome aboard!. We have just added a badge to get your collection started. Continue your journey by unlocking more badges.";
$message['freeBagdeExisting'] = "Welcome back!. As an existing user, you get the privilege of having 2 special badges as a head start!";
$message['inbox']['unlockBadge'] = "You have been successfully added {$badgeName} badge to your collection. You are one step closer! Continue your journey by unlocking more badges.";
$message['inbox']['tradeBadge'] = " You have  traded  {$with_name} for {$need_name}  . Continue trading to complete all set!";
$message['inbox']['tradeBadgeUser2'] = "Your trade has been accepted by {$trader}  . How many different badges do you have? Continue trading.";
$message['inbox']['tradeBadgeBox'] = "You've placed {$need_name} on trade box for {$with_name} badge . Continue trading to complete all set ";
// $message['inbox']['tradeBadgeBox'] = "test trade box user";
$message['inbox']['highestBid'] = " Well done! You have bid for  {$auction_name} . Keep track of the auction and be ready to place higher bid when someone outbid you.Remember, the highest bidder by the closing time of the auction will be the lucky one who wins the item. So get your badges ready!";
$message['inbox']['losingBid'] = " {$highest_name} placed the highest bid for the {$auction_name} . change your bidding strategy in the next auction. Remember, the more badges you have, the greater your chances.";
$message['inbox']['winAuction'] = " Well done! You had the highest bid in the auction of {$auction_name} . Soon we will be sending you the {$auction_name}";
$message['inbox']['afterGamesBadge'] = "You got a badge for completing a level in {$game_name} . You are one step closer! Continue your journey by unlocking more badges! The More you collect, the more opportunities you will have to obtain exclusive merchandise";
$message['inbox']['successRedeem'] = "You have successfully redeemed your badges for {$merchandise_item} . Shortly we will be shipping this the address you specified when you registered";
$message['inbox']['afterNYCabHuntBadge'] = "You have earned the exclusive badge from the Yellow Cab Hunt. Read the clues and catch all the Yellow Cabs at the right locations to unlock more exclusive badges";



//email message

//header mail
$mailBaseURL = "https://www.marlboro.co.id/email/";
$mailContentheader ='
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>MARLBORO CONNECTION</title>
</head>

<body bgcolor="#555555">
<table width="700" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td background="'.$mailBaseURL.'bg.jpg" style=" height:420px;" height="420" valign="top">
	  	<div style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:16px; color:#666; padding:0 40px; width:300px; height:350px; position:relative;">
';
//footermail
$mailContentfooter ='
<a href="http://www.marlboro.co.id" target="_blank" style="color:#ed1106; text-decoration:none; position:absolute; display:block; width:200px; height:50px; right:-322px; bottom:0;">&nbsp;</a>
        </div>
    </td>
  </tr>
  <tr>
    <td style="height:75px" height="75">
	<img src="'.$mailBaseURL.'hw.jpg">
	</td>
  </tr>
</table>
</body>
</html>
';




//mail message
$mail['winAuction'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username}, </h1>
That was close - well done! You had the highest bid in one of the exclusive auctions.<br />
Soon we will be sending you the {$auction_name} <br />
log on to <a href=".BASEURL.">www.marlboro.co.id</a> and find out what hot items other connection are bidding for. <br />
Remember the more badges you obtain, the greater the chance of claiming additional prizes. <br />
Besure to get the clues on  <a href=".BASEURL.">www.marlboro.co.id</a> and find out where to find more badges!
".$mailContentfooter."
";

$mail['loseAuction'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$losername},</h1>
That was close! <br />
At the close of the auction, {$username} placed the highest bid for the {$auction_name}. <br />
Will you change your bidding strategy in the next auction? <br />
Remember, the more badges you have, the greater your chance. <br />
Some badges like the yellow cab badges are worth much more in the auction too! <br />
log on to <a href=".BASEURL.">www.marlboro.co.id</a> and get clues on how to increase your badge collection. 
".$mailContentfooter."
";


$mail['unlockBadge'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
You have successfully added another badge to your collection.<br />
You are one step closer! Continue your journey by unlocking more badges<br />
Log on to <a href=".BASEURL.">www.marlboro.co.id</a> to get the clues and find more badges!
".$mailContentfooter."
";

$mail['freeBagde'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
Welcome aboard - the journey has begun. <br />
We have just added a badge to get your collection started. <br />
Log on to <a href=".BASEURL.">www.marlboro.co.id</a> to seewhich badge you obtained! <br />
Marlboro Lights Connections offers you the chance to experience an unforgettable ten day trip to Berlin, Istanbul and New York <br />
Continue your journey by unlocking more badges. Go to  <a href=".BASEURL.">www.marlboro.co.id</a> to find out more.
".$mailContentfooter."
";

$mail['freeBagdeExisting'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
Welcome back - your  journey continues! . <br />
Explore art and design in Berlin, experience luxury in istanbul and party hard in the best roof-top clubs in New York. <br />
As a member of the Marlboro community, we have added to 2 special badges to give you a head start! <br />
Continue your journey by unlocking more badges. Go to  <a href=".BASEURL.">www.marlboro.co.id</a> to find out more.
".$mailContentfooter."
";

$mail['after3GamesBadge'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
Great archivement! <br />
You got 3 badges for completing all 3 levels in {$game_name}. <br />
You are one step closer! Continue your journey by unlocking more badges<br />
Go to  <a href=".BASEURL.">www.marlboro.co.id</a>, find clues to see more ways you can find codes and badges!<br />
The more you collect, the more opportunities you will have to obtain exclusive merchandise
".$mailContentfooter."
";


$mail['tradeBadgeUser2'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
Congratulations! <br />
You've traded {$with_name} for {$need_name}  badge <br />
Continue trading and log on to <a href=".BASEURL.">www.marlboro.co.id</a>,to find clues to get even more badges in order to complete your set! <br />
".$mailContentfooter."
";

$mail['tradeBadge'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
Your trade has been accepted by {$trader} . <br />
How many different badges do you have? <br />
Continue trading and log on to <a href=".BASEURL.">www.marlboro.co.id</a>, to find clues to get even more badges!<br />
Will you be one of our finalists and get the chance to explore art and design in Berlin, <br />
experience luxury in Istanbul, and party hard in the best roof-top clubs in New York.
".$mailContentfooter."
";

$mail['tradeBadgeBox'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
You've traded {$with_name} for {$need_name} badge <br />
We will notity you once you've found your match <br />
Continue trading and log on to <a href=".BASEURL.">www.marlboro.co.id</a>, to find clues to get even more badges in order to complete your set!<br />
".$mailContentfooter."
";


$mail['afterNYCabHuntBadge'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
Well done on your determination! <br />
You have unlocked the exclusive badge by spotting the Special Yellow Cab and made it through the challenges.<br />
Follow the trail of clues on <a href=".BASEURL.">www.marlboro.co.id</a> and catch all the Yellow Cabs at the right locations to unlock more exclusive badges. <br />
Happy Hunting!
".$mailContentfooter."
";



$mail['successRedeem'] = "
".$mailContentheader."
<h1 style='font-family:Arial Black, Gadget, sans-serif; font-size:44px; color:#ed1106; margin:50px 0 30px 0;'> Hi {$username},</h1>
With exclusively designed {$merchandise_item} you'll feel even closer to Berlin, istanbul and New York! <br />
Shortly we will be shipping this to the  address you specified when you registered.<br />
{$address}<br />
while we deliver it to you, why not continue to search out more badges on <a href=".BASEURL.">www.marlboro.co.id</a> and add to your collection! <br />
Complete all badges and you may be one of our finalist. <br />
Who knows, maybe next time we may be delivering something even more unique!
".$mailContentfooter."
";



?>
