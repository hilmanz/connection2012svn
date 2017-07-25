<?php
$CONFIG['LOG_DIR'] = "../logs/";
$GLOBAL_PATH = "../";
$APP_PATH = "../com/";
$ENGINE_PATH = "../engines/";
$WEBROOT = "../html/";

//DEFINE VARIABLE
define('APPLICATION','marlboro');        //set aplikasi yang digunakan
define('DB_PREFIX','rr');        //set DB prefix for frontend
define('BASEURL','http://localhost/connection2012svn/branches/dev/public_html/');        //set BASEURL frontend
define('BASEURL_ADMIN','https://admstaging.marlboro.co.id/');        //set BASEURL admin
define('APP_PATH',$APP_PATH);
define('SCHEMA_CONNECTION','marlboro_connect');
define('SCHEMA_CODE','marlboro_code');
//set database
$CONFIG['DEVELOPMENT'] = true;
if($CONFIG['DEVELOPMENT']){
        $CONFIG['DATABASE'][0]['HOST']             = "localhost";
        $CONFIG['DATABASE'][0]['USERNAME']         = "root";
        $CONFIG['DATABASE'][0]['PASSWORD']         = "coppermine";
        $CONFIG['DATABASE'][0]['DATABASE']         = "marlboro_connect";
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
$CONFIG['MOP_URL_LOGIN'] = 'http://localhost/connection2012svn/branches/dev/public_html/login.php';

$WIN_PENALTY = array(0.05,0.1,0.2);
// $GAME_API = "http://preview.kanadigital.com/redrush/api/";
$GAME_API = "http://localhost/connection2012svn/branches/dev/api/";

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

$CPMOO['ABOUT_REDRUSH'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB137",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['BUY_PARTS'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB135",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES_COOKING'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB128",
				"CPAOType"=>"R",
				"siteID"=>"206");

$CPMOO['GAMES_FIND_OBJECT'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB129",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES_PUZZLE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB130",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GAMES_SEGWAY'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB136",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['GET_POINT'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB131",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['HOW_TO_PLAY'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB138",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['INVITE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB090",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['MOBILE_ACCESS'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB119",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['MY_GARAGE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB146",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['NEWSFEED'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB141",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['OFFLINE_REGISTRATION'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB089",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['ONLINE_REGISTRATION'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB088",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['OUTMPOEMAIL'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB095",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['PRIZES'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB139",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['RACE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB133",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['RECENT_ACTIVITY'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB142",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['REDEEM_MERCHANDISE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB132",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['REFFERAL_CODE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB101",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['REGISTRATION_UPDATE_INFO'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB092",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['SUBMIT&REDEEM_SUCCESS'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB144",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['TERMS&CONDITION'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB140",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['THANKYOU'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB091",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['TOPUSER'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB145",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['VOTE'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB134",
				"CPAOType"=>"R",
				"siteID"=>"206");
				
$CPMOO['WEBSITE_ACTIVITIES'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB086",
				"CPAOType"=>"R",
				"siteID"=>"206");

$CPMOO['WEBSITE_LOGIN_ACTIVITY'] = array("WebSessionLanguage"=>"",
			   "Campaign"=>"ID12000423A11",
				"Phase"=>"PH01",
				"Audience"=>"A001",
				"MediaCategory"=>"OBW",
				"OfferCategory"=>"WEB",
				"OfferCode"=>"WEB083",
				"CPAOType"=>"R",
				"siteID"=>"206");
	
