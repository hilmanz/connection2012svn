<?php
$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";

//DEFINE VARIABLE
define('APPLICATION','marlboro');        //set aplikasi yang digunakan
define('DB_PREFIX','rr');        //set DB prefix for frontend
define('BASEURL','http://localhost/connection2012svn/branches/2012/public_html/');        //set BASEURL frontend
define('BASEURL_ADMIN','https://admstaging.marlboro.co.id/');        //set BASEURL admin
define('APP_PATH',$APP_PATH);
define('SCHEMA_CONNECTION','connection');
define('SCHEMA_CODE','connection_code');
//set database
$CONFIG['DEVELOPMENT'] = true;
if($CONFIG['DEVELOPMENT']){
        $CONFIG['DATABASE'][0]['HOST']             = "localhost";
        $CONFIG['DATABASE'][0]['USERNAME']         = "sample";
        $CONFIG['DATABASE'][0]['PASSWORD']         = "sample";
        $CONFIG['DATABASE'][0]['DATABASE']         = "connection";
        error_reporting(0);
}else{
        $CONFIG['DATABASE'][0]['HOST']                                 = "";
        $CONFIG['DATABASE'][0]['USERNAME']         = "";
        $CONFIG['DATABASE'][0]['PASSWORD']         = "";
        $CONFIG['DATABASE'][0]['DATABASE']         = "";
}

/**
 * Email settings
 */
$CONFIG['EMAIL_FROM_DEFAULT'] = "redrush-noreply@marlboro.co.id";
$CONFIG['EMAIL_SMTP_HOST'] = "localhost";
$CONFIG['EMAIL_SMTP_PORT'] = 25;
$CONFIG['EMAIL_SMTP_USER'] = "";
$CONFIG['EMAIL_SMTP_PASSWORD'] = "";
$CONFIG['EMAIL_SMTP_SSL'] = 0;
/* DATETIME SET */
$timeZone = 'Asia/Jakarta';
date_default_timezone_set($timeZone);

/* SET MOP */
$CONFIG['MOP'] = false;
// $CONFIG['MOP_URL_LOGIN'] = 'https://staging-redrush-id.es-dm.com/Templates/LandingPage.aspx';
$CONFIG['MOP_URL_LOGIN'] = 'http://localhost/connection2012svn/branches/2012/public_html/login.php';

$WIN_PENALTY = array(0.05,0.1,0.2);
// $GAME_API = "http://preview.kanadigital.com/redrush/api/";
$GAME_API = "http://preview.kanadigital.com/connection2012/api/";

$MINIGAME_SCORES = array(0,30,40,50);

//this is hash for accessing RedRush Racing game API.
$REDRUSH_APIKEY = sha1("RedRushAPIKanaKana9i8u");

//this is hash for urlencode64 and urldecode64
$HASH_SECRET_KEY = sha1("RedRushRunner");



// MOP
//URL
// https://testing-marlboro-id.es-dm.com/dm.mopid.webservice/WebService.aspx
// LANDING
//https://testing-marlboro-id.es-dm.com/Templates/LandingPage.aspx

// $CONFIG['MOP_URL'] = "https://staging-redrush-id.es-dm.com/dm.mopid.webservice/centralwebservice.asmx";
$CONFIG['MOP_URL'] = "https://staging.marlboro.co.id/landing_splash.html";
$CONFIG['MOP_USER'] = "hosting\pmimopID";
$CONFIG['MOP_PWD'] = "Pm1jkd!";

$CPMOO['Auction'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB145",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES_ART_MUSEUM'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB121",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES_BERLIN_WALL'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB118",
				"CPAOType"=>"R",
				"siteID"=>"206");

$CPMOO['GAMES_DJ'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB119",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES_YACHT'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB120",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES1'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB146",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES2'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB147",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES3'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB148",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES4'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB149",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES5'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB150",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES6'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB151",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES7'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB152",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES8'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB153",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GET_BADGES'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB115",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['MOBILE_ACCESS'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB116",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['OFFLINE_REGISTRATION'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB089",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['ONLINE_REGISTRATION'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB088",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['OUTMOPDM'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB093",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['OUTMOPEMAIL'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB095",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['OUTMOPSMS'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB094",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['REDEEM_BADGES'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB113",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['REFERRAL_CODE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB101",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['REFFERED_CODE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB102",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['THANK_YOU'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB091",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['TRADING_BADGES'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB114",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['WEBSITE_LOGIN_ACTIVITY'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A12",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB083",
				"CPAOType"=>"R",
				"siteID"=>"206");
	