<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class reporting extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		$xml = intval($this->Request->getParam('xml'));
		if( $act == 'content' ){
			return $this->Content();
		}elseif( $act == 'badges-redemption' ){
			return $this->badgesRedemption();
		}elseif( $act == 'xmlDSTData' ){
			return $this->dstData();
		}elseif( $act == 'xmlContentData' ){
			return $this->contentData();
		}elseif( $act == 'xmlSpreadData' ){
			return $this->spreadData();
		}elseif( $act == 'xmlChannelData' ){
			return $this->channelData();
		}elseif( $act == 'user' ){
			return $this->user();
		}elseif( $act == 'badges-trading' ){
			return $this->badgesTrading();
		}elseif( $act == 'xmldstredeem' ){
			return $this->xmlDSTRedeem();
		}elseif( $act == 'xmlperchannel' ){
			return $this->xmlPerChannel();
		}elseif( $act == 'website-activity' ){
			return $this->websiteActivity();
		}elseif( $act == 'xmlmerchandise' ){
			return $this->xmlMerchandise();
		}elseif( $act == 'xmldatagames' ){
			return $this->xmlDataGames();
		}elseif( $act == 'dataxmlhitpage' ){
			return $this->dataXmlHitPage();
		}elseif( $act == 'dataxmlcitylogin' ){
			return $this->dataXmlCityLogin();
		}elseif( $act == 'getCSV' ){
			return $this->getCSV();
		}else{
			if($xml==1){
				return $this->DashboardXML();
			}else{
				return $this->Dashboard();
			}
		}
		
	}

	function Dashboard(){
		
		//jumlah badge
		$sql = "SELECT SUM(a.total) total FROM (
					SELECT badge_id,b.name AS badgeName,COUNT(badge_id) AS total 
					FROM marlboro_code.badge_inventory a
					INNER JOIN marlboro_code.badge_catalog b
					ON a.badge_id = b.id
					GROUP BY b.id) a;";
		$rs = $this->fetch($sql);
		$total = $rs['total'];
		$sql = "SELECT badge_id,b.name AS badgeName,COUNT(badge_id) AS total 
					FROM marlboro_code.badge_inventory a
					INNER JOIN marlboro_code.badge_catalog b
					ON a.badge_id = b.id
					GROUP BY b.id;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$rs[$i]['persen'] = round(($rs[$i]['total'] / $total) * 100);
		}
		$this->View->assign('badge',$rs);
		
		
		//hit page
		/*
		$sql = "SELECT SUM(a.total) total FROM
					(SELECT page,COUNT(page) AS total 
					FROM social_tracking WHERE page NOT IN ('news => getlasttrade','news => getbadgeinfo','REDEEM BADGES => getmopprofile')
					GROUP BY page) a;";
		$rs = $this->fetch($sql);
		$total = $rs['total'];
		$sql = "SELECT page,COUNT(page) AS total 
					FROM social_tracking WHERE page NOT IN ('news => getlasttrade','news => getbadgeinfo','REDEEM BADGES => getmopprofile')
					GROUP BY page;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$rs[$i]['persen'] = round(($rs[$i]['total'] / $total) * 100);
			
			if($rs[$i]['page'] == 'about-marlboro-connections => home'){
				$rs[$i]['page'] = 'about marlboro';
			}
			
			$p = explode(' => ',$rs[$i]['page']);
			if($p[0] == 'REDEEM BADGES' && $p[1] != 'home'){
				$rs[$i]['page'] = $p[1];
			}
		}
		$this->View->assign('hitpage',$rs);
		*/
		
		//redeem channel
		$sql = "SELECT SUM(a.total_redeem) total FROM (
					SELECT channel_name,SUM(total) AS  total_redeem 
					FROM marlboro_code.report_redeem a
					INNER JOIN marlboro_code.badge_channel b
					ON a.channel = b.channel_id
					GROUP BY channel) a;";
		$rs = $this->fetch($sql);
		$total = $rs['total'];
		$sql = "SELECT channel_name,SUM(total) AS  total_redeem 
					FROM marlboro_code.report_redeem a
					INNER JOIN marlboro_code.badge_channel b
					ON a.channel = b.channel_id
					GROUP BY channel;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$rs[$i]['persen'] = round(($rs[$i]['total_redeem'] / $total) * 100);
		}
		$this->View->assign('redeemchannel',$rs);
		
		//print_r($rs);
		//exit;
		
		//login kota
		/*
		$sql = "SELECT SUM(total_login) total FROM (SELECT b.city,SUM(login_count) total_login 
					FROM social_member a
					INNER JOIN mop_city_lookup b
					ON a.city = b.id
					GROUP BY b.id) a;";
		$rs = $this->fetch($sql);
		$total = $rs['total'];
		$sql = "SELECT b.city,SUM(login_count) total_login 
						FROM social_member a
						INNER JOIN mop_city_lookup b
						ON a.city = b.id
						GROUP BY b.id;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$rs[$i]['persen'] = round(($rs[$i]['total_login'] / $total) * 100);
		}
		$this->View->assign('logincity',$rs);
		*/
		
		//TOTAL CODE GENERATED/REDEEMED
		$sql = "SELECT * FROM marlboro_code.report_overall_total;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('total_code_generated',$rs[0]['total']);
		$this->View->assign('total_code_redeemed',$rs[1]['total']);
		
		
		
		return $this->View->toString("marlboro/admin/dashboard.html");
	}
	
	function DashboardXML(){
		$start = $_GET['start'];
		$end = $_GET['end'];
		$sort =  $_GET['sort'];
		
		if( $start != '' ){
			$start = str_replace("/", "-", $start);
			//echo $start;exit;
		}
		if( $end != '' ){
			$end = str_replace("/", "-", $end);
		}
		
		$WHERE = ' 1 ';
		if($start != '' && $end != ''){
			$WHERE .= " AND tanggal BETWEEN '$start' AND '$end' ";
		}
		
		//print_r($sort);exit;
		
		$sql = "SELECT DISTINCT(tanggal) tgl FROM marlboro_code.report_dashboard_daily WHERE $WHERE";
		$rs = $this->fetch($sql,1);
		$xml = '<chart>';
		$xml .= '<categories>';
		foreach($rs as $k => $v){
			$xml .= '<item>'.$v['tgl'].'</item>';
		}
		$xml .= '</categories>';
		
		$num = count($sort);
		for($i=0;$i<$num;$i++){
			$sql = "SELECT a.tgl,b.total FROM (SELECT DISTINCT(tanggal) tgl FROM marlboro_code.report_dashboard_daily WHERE $WHERE) a LEFT JOIN (SELECT tanggal,total FROM marlboro_code.report_dashboard_daily WHERE type='".$sort[$i]."' AND $WHERE) b ON a.tgl=b.tanggal;";
			//echo $sql;exit;
			$rs = $this->fetch($sql,1);
			
			if($sort[$i] == 1){
				$name = "Logins";
			}elseif($sort[$i] == 2){
				$name = "Active Users";
			}elseif($sort[$i] == 3){
				$name = "Badges Disbursed";
			}elseif($sort[$i] == 4){
				$name = "Trades Completed";
			}
			
			$xml .= '<series>';
			$xml .= '<name>'.$name.'</name>';
			$xml .= '<data>';
			foreach($rs as $k => $v){
				$xml .= '<point>'.intval($v['total']).'</point>';
			}
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		/*
		if($start == '' && $end == ''){
			
			
			if( $login == 1 ){
				$xml = '<chart>';
				$xml .= '<categories>';
				$xml .= '<item>Jan</item>';
				$xml .= '<item>Feb</item>';
				$xml .= '<item>Mar</item>';
				$xml .= '<item>Apr</item>';
				$xml .= '<item>Mei</item>';
				$xml .= '</categories>';
				$xml .= '<series>';
				$xml .= '<name>Login</name>';
				$xml .= '<data>';
				$xml .= '<point>8</point>';
				$xml .= '<point>4</point>';
				$xml .= '<point>6</point>';
				$xml .= '<point>5</point>';
				$xml .= '</data>';
				$xml .= '</series>';
				$xml .= '</chart>';
			}else{
				$xml = '<chart>';
				$xml .= '<categories>';
				$xml .= '<item>Jan</item>';
				$xml .= '<item>Feb</item>';
				$xml .= '<item>Mar</item>';
				$xml .= '<item>Apr</item>';
				$xml .= '<item>Mei</item>';
				$xml .= '</categories>';
				$xml .= '<series>';
				$xml .= '<name>Login</name>';
				$xml .= '<data>';
				$xml .= '<point>8</point>';
				$xml .= '<point>4</point>';
				$xml .= '<point>6</point>';
				$xml .= '<point>5</point>';
				$xml .= '</data>';
				$xml .= '</series>';
				$xml .= '<series>';
				$xml .= '<name>Active user</name>';
				$xml .= '<data>';
				$xml .= '<point>18</point>';
				$xml .= '<point>14</point>';
				$xml .= '<point>16</point>';
				$xml .= '<point>15</point>';
				$xml .= '</data>';
				$xml .= '</series>';
				$xml .= '<series>';
				$xml .= '<name>Badges Disbursed</name>';
				$xml .= '<data>';
				$xml .= '<point>28</point>';
				$xml .= '<point>24</point>';
				$xml .= '<point>26</point>';
				$xml .= '<point>25</point>';
				$xml .= '</data>';
				$xml .= '</series>';
				$xml .= '<series>';
				$xml .= '<name>Trades Complete</name>';
				$xml .= '<data>';
				$xml .= '<point>11</point>';
				$xml .= '<point>15</point>';
				$xml .= '<point>19</point>';
				$xml .= '<point>25</point>';
				$xml .= '</data>';
				$xml .= '</series>';
				$xml .= '</chart>';
			}
		}
		*/
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function dstData(){
		
		$city = $_GET['city'];
		
		//DTS
		$sql = "SELECT tgl FROM marlboro_code.report_surveyor_performance WHERE surveyor LIKE '%$city%' GROUP BY tgl ORDER BY tgl;";
		$rs = $this->fetch($sql,1);
		//$this->View->assign('dtscat',$rs);
		$xml = "<chart><categories>";
		foreach($rs as $k => $v){
			$xml .= "<item>".$v['tgl']."</item>";
		}
		$xml .= "</categories>";
		
		$sql = "SELECT DISTINCT(surveyor) FROM marlboro_code.report_surveyor_performance WHERE surveyor LIKE '%$city%';";
		$rs = $this->fetch($sql,1);
		foreach($rs as $k => $v){
			$sql = "SELECT a.tgl,b.total
FROM (SELECT t1.tgl,t2.surveyor FROM (SELECT DISTINCT tgl FROM marlboro_code.report_surveyor_performance WHERE surveyor LIKE '%$city%') t1, (SELECT DISTINCT surveyor FROM marlboro_code.report_surveyor_performance WHERE surveyor LIKE '%$city%') t2) a
LEFT JOIN marlboro_code.report_surveyor_performance b ON a.tgl=b.tgl AND a.surveyor=b.surveyor WHERE a.surveyor LIKE '%$city%' AND a.surveyor='".$v['surveyor']."';";
			$rs2 = $this->fetch($sql,1);
			$xml .= "<series><name>".$v['surveyor']."</name>";
			$xml .= "<data>";
			foreach($rs2 as $i => $j){
				$xml .= "<point>".intval($j['total'])."</point>";
			}
			$xml .= "</data></series>";
		}
		$xml .= "</chart>";
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function Content(){
		
		//top user
		$sql = "SELECT b.name,b.register_id, a.* FROM (SELECT user_id,COUNT(page) AS total 
					FROM social_tracking GROUP BY user_id) a
					INNER JOIN social_member b
					ON a.user_id = b.id
					WHERE register_id <> 10
					ORDER BY a.total DESC LIMIT 50;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('topuser',$rs);
		
		//top game
		$sql = "SELECT b.name,a.* FROM user_rank a
					INNER JOIN social_member b
					ON a.user_id = b.register_id
					ORDER BY rank ASC LIMIT 100;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('topgame',$rs);
		
		//TOTAL PARTICIPANT & USER
		$sql = "SELECT COUNT(*) as total FROM 
					(SELECT user_id,COUNT(user_id) 
					FROM marlboro_code.badge_inventory 
					GROUP BY user_id) a";
		$rs = $this->fetch($sql);
		$this->View->assign('total_participant',$rs['total']);
		$sql = "SELECT COUNT(*) total FROM social_member;";
		$rs = $this->fetch($sql);
		$this->View->assign('total_user',$rs['total']);
		
		return $this->View->toString("marlboro/admin/content.html");
	}
	
	function contentData(){
		$start = $_GET['start'];
		$end = $_GET['end'];
		$sort =  $_GET['sort'];
		
		if( $start != '' ){
			$start = str_replace("/", "-", $start);
			//echo $start;exit;
		}
		if( $end != '' ){
			$end = str_replace("/", "-", $end);
		}
		
		$WHERE = ' 1 ';
		if($start != '' && $end != ''){
			$WHERE .= " AND tanggal BETWEEN '$start' AND '$end' ";
		}
		
		//print_r($sort);exit;
		
		$sql = "SELECT DISTINCT(tanggal) tgl FROM marlboro_code.report_content_daily WHERE $WHERE";
		$rs = $this->fetch($sql,1);
		$xml = '<chart>';
		$xml .= '<categories>';
		foreach($rs as $k => $v){
			$xml .= '<item>'.$v['tgl'].'</item>';
		}
		$xml .= '</categories>';
		
		$num = count($sort);
		for($i=0;$i<$num;$i++){
			$sql = "SELECT a.tgl,b.total FROM (SELECT DISTINCT(tanggal) tgl FROM marlboro_code.report_content_daily WHERE $WHERE) a LEFT JOIN (SELECT tanggal,total FROM marlboro_code.report_content_daily WHERE type='".$sort[$i]."' AND $WHERE) b ON a.tgl=b.tanggal;";
			//echo $sql;exit;
			$rs = $this->fetch($sql,1);
			
			if($sort[$i] == 1){
				$name = "Actions";
			}elseif($sort[$i] == 2){
				$name = "Game Plays";
			}elseif($sort[$i] == 3){
				$name = "Code Redeems";
			}elseif($sort[$i] == 4){
				$name = "Trades";
			}elseif($sort[$i] == 5){
				$name = "Merchandise";
			}
			
			$xml .= '<series>';
			$xml .= '<name>'.$name.'</name>';
			$xml .= '<data>';
			foreach($rs as $k => $v){
				$xml .= '<point>'.intval($v['total']).'</point>';
			}
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function spreadData(){
		
		$sql = "SELECT DISTINCT(tanggal) FROM marlboro_code.report_content_spread;";
		$rs = $this->fetch($sql,1);
		$xml = '<chart>';
		$xml .= '<categories>';
		foreach($rs as $k => $v){
			$xml .= '<item>'.$v['tanggal'].'</item>';
		}
		$xml .= '</categories>';
		
		$sql = "SELECT pages_name FROM marlboro_code.pages;";
		$rs2 = $this->fetch($sql,1);
		$num = count($rs2);
		for($i=0;$i<$num;$i++){
			$sql = "SELECT 
							c.pages_name,
							a.tanggal,
							SUM(a.total) total
						FROM 
							(SELECT DISTINCT(tanggal) FROM marlboro_code.report_content_spread) t
							LEFT JOIN
							(SELECT tanggal, total, page FROM marlboro_code.report_content_spread) a
							ON t.tanggal=a.tanggal
							LEFT JOIN marlboro_code.pages_lookup b
							ON a.page=b.page
							LEFT JOIN marlboro_code.pages c
							ON b.pages_id=c.pages_id
						WHERE 
							c.pages_name <> '' AND
							c.pages_name='".$rs2[$i]['pages_name']."'
						GROUP BY 
							a.tanggal,c.pages_name;";
			//echo $sql;exit;
			$rs = $this->fetch($sql,1);
			//echo mysql_error();exit;
			$xml .= '<series>';
			$xml .= '<name>'.$rs2[$i]['pages_name'].'</name>';
			$xml .= '<data>';
			foreach($rs as $k => $v){
				$xml .= '<point>'.intval($v['total']).'</point>';
			}
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function channelData(){
		
		$sql = "SELECT DISTINCT(tgl) FROM marlboro_code.report_channel_performance;";
		$rs = $this->fetch($sql,1);
		$xml = '<chart>';
		$xml .= '<categories>';
		foreach($rs as $k => $v){
			$xml .= '<item>'.$v['tgl'].'</item>';
		}
		$xml .= '</categories>';
		
		$sql = "SELECT * FROM marlboro_code.badge_channel;";
		$rs2 = $this->fetch($sql,1);
		$num = count($rs2);
		for($i=0;$i<$num;$i++){
			$sql = "SELECT 
							a.tgl,
							b.total
							FROM 
								(SELECT DISTINCT(tgl) FROM marlboro_code.report_channel_performance) a
								LEFT JOIN
								(SELECT channel,tgl,total FROM marlboro_code.report_channel_performance WHERE channel='".$rs2[$i]['channel_id']."') b
								ON a.tgl=b.tgl
							ORDER BY 
								a.tgl;";
			//echo $sql;exit;
			$rs = $this->fetch($sql,1);
			//echo mysql_error();exit;
			$xml .= '<series>';
			$xml .= '<name>'.$rs2[$i]['channel_name'].'</name>';
			$xml .= '<data>';
			foreach($rs as $k => $v){
				$xml .= '<point>'.intval($v['total']).'</point>';
			}
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function badgesRedemption(){
		
		//redeem channel
		$sql = "SELECT SUM(total) total FROM marlboro_code.report_channel_redeem;";
		$rs = $this->fetch($sql);
		$total = $rs['total'];
		$sql = "SELECT * FROM marlboro_code.report_channel_redeem;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$rs[$i]['persen'] = round(($rs[$i]['total'] / $total) * 100);
		}
		$this->View->assign('channel',$rs);
		
		//DAFTAR PEROLEHAN BADGE USER
		$sql ="SELECT a.user_id,b.name,COUNT(a.user_id) AS total 
					FROM marlboro_code.badge_inventory a
					INNER JOIN 
					social_member b
					ON a.user_id = b.register_id
					GROUP BY a.user_id;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('user',$rs);
		
		//total badge register
		$sql = "SELECT COUNT(*) as total FROM marlboro_code.badge_redeem WHERE kode='REGFREE';";
		$rs = $this->fetch($sql);
		$this->View->assign('total_badge_register',intval($rs['total']));
		$sql = "SELECT COUNT(*) as total FROM marlboro_code.badge_redeem WHERE kode IN ('berlin1','berlin2','yacht','ny');";
		$rs = $this->fetch($sql);
		$this->View->assign('total_badge_game',intval($rs['total']));
		
		//DST
		$sql = "SELECT COUNT(*) AS total FROM 
(
SELECT codeid FROM ipad_code_registration
WHERE surveyor LIKE '%dst%'
GROUP BY codeid) a;";
		$rs = $this->fetch($sql);
		$this->View->assign('total_dst_register',intval($rs['total']));
		$sql = "SELECT COUNT(*) as total FROM 
(
SELECT codeid FROM ipad_code_quiz 
WHERE surveyor LIKE '%dst%'
GROUP BY codeid) a;";
		$rs = $this->fetch($sql);
		$this->View->assign('total_dst_quiz',intval($rs['total']));
		
		//SBA
		$sql = "SELECT 
					(SELECT COUNT(*) AS total FROM 
					(
					SELECT codeid FROM ipad_code_registration
					WHERE surveyor NOT LIKE '%dst%' AND surveyor NOT IN ('admin01','sbatest01')
					GROUP BY codeid) a) +
					(SELECT COUNT(*) AS total FROM 
					(
					SELECT codeid FROM ipad_code_quiz 
					WHERE surveyor NOT LIKE '%dst%' AND surveyor NOT IN ('admin01','sbatest01')
					GROUP BY codeid) a) AS total;";
		$rs = $this->fetch($sql);
		$this->View->assign('total_sba',intval($rs['total']));
		$sql = "SELECT 
					(SELECT COUNT(*) AS total 
					FROM (SELECT b.kode FROM ipad_code_registration a
					INNER JOIN marlboro_code.badge_redeem b
					ON a.codeid = b.kode 
					WHERE a.surveyor NOT LIKE '%dst%' AND a.surveyor NOT IN ('admin01','sbatest01')
					GROUP BY b.kode) c) +
					(SELECT COUNT(*) AS total 
					FROM (SELECT b.kode FROM ipad_code_quiz a
					INNER JOIN marlboro_code.badge_redeem b
					ON a.codeid = b.kode 
					WHERE a.surveyor NOT LIKE '%dst%' AND a.surveyor NOT IN ('admin01','sbatest01')
					GROUP BY b.kode) c) AS total;";
			$rs = $this->fetch($sql);
			$this->View->assign('total_sba_redeem',intval($rs['total']));
		
		//POSTER & POG
		$sql = "SELECT SUM(total) total FROM marlboro_code.report_channel_redeem WHERE channel=4;";
		$rs = $this->fetch($sql);
		$this->View->assign('total_poster',intval($rs['total']));
		$sql = "SELECT SUM(total) total FROM marlboro_code.report_channel_redeem WHERE channel=3;";
		$rs = $this->fetch($sql);
		$this->View->assign('total_pog',intval($rs['total']));
		
		
		return $this->View->toString("marlboro/admin/reporting-code-redeemtion.html");
	}
	
	function user(){
		global $ENGINE_PATH;
		//TOTAL PARTICIPANT & USER
		$sql = "SELECT COUNT(*) as total FROM 
					(SELECT user_id,COUNT(user_id) 
					FROM marlboro_code.badge_inventory 
					GROUP BY user_id) a";
		$rs = $this->fetch($sql);
		$this->View->assign('total_participant',$rs['total']);
		$sql = "SELECT COUNT(*) total FROM social_member;";
		$rs = $this->fetch($sql);
		$this->View->assign('total_user',$rs['total']);
		$total_user = $rs['total'];
		//TOTAL ACTIVE USER
		$sql = "SELECT COUNT(*) AS total FROM (SELECT user_id,COUNT(page) AS total 
					FROM social_tracking 
					GROUP BY user_id) a
					WHERE total > 10;";
		$rs = $this->fetch($sql);
		$this->View->assign('total_active_user',$rs['total']);
		
		//NUMBER OF USER
		$sql = "SELECT COUNT(*) AS total FROM (SELECT * FROM marlboro_code.badge_redeem 
					WHERE kode IN ('berlin1','berlin2','yacht','ny')
					GROUP BY user_id) a;";
		$rs = $this->fetch($sql);
		$this->View->assign('number_of_user',$rs['total']);
		
		$number_of_user = $rs['total'];
		//NUMBER BADGE PER USER
		$sql = "SELECT * FROM marlboro_code.report_user_inventory;";
		$rs = $this->fetch($sql,1);
		$sql = "SELECT id,name FROM marlboro_code.badge_catalog WHERE id < 13 ORDER BY id;";
		$rs2 = $this->fetch($sql,1);
		$totalPerPage = 50;
		$this->View->assign('totalPerPage',$totalPerPage);
		$this->View->assign('numPage',ceil(count($rs) / $totalPerPage));
		$this->View->assign('badge_user',$rs);
		$this->View->assign('badge_name',$rs2);
		
		//USER PLAYING GAMES
		$sql = "SELECT * FROM marlboro_code.report_user_game;";
		$rs = $this->fetch($sql,1);
		//$sql = "SELECT id,name FROM marlboro_code.badge_catalog ORDER BY id;";
		//$rs2 = $this->fetch($sql,1);
		$totalPerPage = 50;
		$this->View->assign('totalPerPageGame',$totalPerPage);
		$this->View->assign('numPageGame',ceil(count($rs) / $totalPerPage));
		$this->View->assign('game',$rs);
		//$this->View->assign('badge_name',$rs2);
		
		//GA Stats
		include_once $ENGINE_PATH."Utility/gapi/gapi.class.php";
		define('ga_email','amienz@gmail.com');
		define('ga_password','winslet9i8u');
		define('ga_profile_id','51258309');
		
		
		$ga = new gapi(ga_email,ga_password);
		
		
		//$ga->requestReportData(ga_profile_id,array('country'),array('pageviews','visits','visitors'));
		
		$ga->requestReportData(ga_profile_id,array('country'),array('pageviews','visits','visitors','newVisits'), null,null,
		                       '2011-10-07', // Start Date
		                       '2011-12-31', // End Date
		                       1,  // Start Index
		                       500 // Max results
		                       );
		$gaResult  = array('page_views'=>$ga->getPageviews(),
							'visits'=>$ga->getVisits(),
							'unique_visitors'=>$ga->getVisitors(),
							'conversion_rate'=>round($total_user/$ga->getVisitors()*100,2),
							'loyalty'=>round(($ga->getVisits()-$ga->getNewVisits())/$ga->getVisits()*100,2));

		$this->View->assign("gaResult",$gaResult);
		
		return $this->View->toString("marlboro/admin/reporting-user.html");
	}
	
	function badgesTrading(){
		//USER COLLECTING BADGE
		$sql = "SELECT * FROM
					(SELECT id FROM marlboro_code.badge_catalog) a
					LEFT JOIN
					(SELECT a.badge_id,b.name,COUNT(a.badge_id) AS total 
						FROM marlboro_code.badge_inventory a 
						INNER JOIN marlboro_code.badge_catalog b
						ON a.badge_id = b.id
						GROUP BY badge_id) b
					ON a.id=b.badge_id;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('collect',$rs);
		
		//GENERATED BADGE
		$sql = "SELECT * FROM
					(SELECT id FROM marlboro_code.badge_catalog) a
					LEFT JOIN
					(SELECT badge_id,SUM(total) AS jumlah
					FROM (SELECT b.badge_id,COUNT(b.badge_id) AS total 
					FROM marlboro_code.badge_redeem a
					INNER JOIN marlboro_code.merchandise_redeem b
					ON a.id = b.redeem_id
					GROUP BY b.badge_id
					UNION ALL
					SELECT b.badge_id,COUNT(b.badge_id) AS total 
					FROM marlboro_code.badge_redeem a
					INNER JOIN marlboro_code.badge_inventory b
					ON a.id = b.redeem_id
					GROUP BY b.badge_id
					) aa
					GROUP BY aa.badge_id) b
					ON a.id=b.badge_id;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('generated',$rs);
		
		//BADGE BEING TRADE
		$sql = "SELECT badge_id,total FROM marlboro_code.report_badge_traded ORDER BY badge_id ASC";
		$rs = $this->fetch($sql,1);
		$this->View->assign('being',$rs);
		
		//MOST TRADE
		$sql = "SELECT badge_id,total FROM marlboro_code.report_badge_traded ORDER BY total DESC";
		$rs = $this->fetch($sql,1);
		$this->View->assign('most',$rs);
		
		//TOTAL TRADE & SUCCESS TRADE
		$sql = "SELECT * FROM marlboro_code.report_trade_summary LIMIT 1";
		$rs = $this->fetch($sql);
		$this->View->assign('trades',$rs);
		
		
		return $this->View->toString("marlboro/admin/reporting-badges-trading.html");
	}
	
	function xmlDSTRedeem(){
		
		$xml = '<chart>';
		$xml .= '<categories>';
		$xml .= '<item> </item>';
		$xml .= '</categories>';
		
		for($i=0;$i<2;$i++){
			if( $i == 0){
				$sql = "SELECT COUNT(*) as total
FROM (SELECT b.kode FROM ipad_code_registration a
INNER JOIN marlboro_code.badge_redeem b
ON a.codeid = b.kode 
WHERE b.kode NOT IN (SELECT kode FROM marlboro_code.sba_code_lookup)
GROUP BY b.kode) c;";
			}elseif($i == 1){
				$sql = "SELECT COUNT(*) as total
FROM (SELECT b.kode FROM ipad_code_quiz a
INNER JOIN marlboro_code.badge_redeem b
ON a.codeid = b.kode 
WHERE b.kode NOT IN (SELECT kode FROM marlboro_code.sba_code_lookup)
GROUP BY b.kode) c";
			}
			$rs = $this->fetch($sql);
			if($i == 0){
				$name = "Registration";
			}elseif($i == 1){
				$name = "Quiz";
			}
			
			$xml .= '<series>';
			$xml .= '<name>'.$name.'</name>';
			$xml .= '<data>';
			$xml .= '<point>'.intval($rs['total']).'</point>';
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function xmlPerChannel(){
		$channel = intval($_GET['channel']);
		
		$xml = '<chart>';
		$xml .= '<categories>';
		$xml .= '<item> </item>';
		$xml .= '</categories>';
		
		$sql = "SELECT * FROM marlboro_code.report_channel_redeem WHERE channel=$channel;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		
		for($i=0;$i<$num;$i++){
			$xml .= '<series>';
			$xml .= '<name>'.$rs[$i]['subchannel'].'</name>';
			$xml .= '<data>';
			$xml .= '<point>'.intval($rs[$i]['total']).'</point>';
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function websiteActivity(){
		
		//TOP HIT
		$sql = "SELECT 
	a.*,
	p.pages_name 
FROM 
	(SELECT page,COUNT(page) AS total FROM social_tracking  GROUP BY page) a
	LEFT JOIN marlboro_code.pages_lookup pl
	ON a.page=pl.page
	LEFT JOIN marlboro_code.pages p
	ON pl.pages_id=p.pages_id
WHERE 
	a.page <> 'news => getlasttrade'
ORDER BY total DESC LIMIT 3;";
		$rs = $this->fetch($sql,1);
		
		//TOP PERCENT
		$sql = "SELECT SUM(total) AS subtotal FROM (SELECT page,COUNT(page) AS total FROM social_tracking  GROUP BY page) a
WHERE page <> 'news => getlasttrade'
ORDER BY total DESC LIMIT 1;";
		$rs2 = $this->fetch($sql);
		
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$percent = (intval($rs[$i]['total']) / intval($rs2['subtotal'])) * 100;
			$rs[$i]['percent'] = sprintf("%01.2f", $percent) . '%';
		}
		
		$this->View->assign('total_hit',$rs);
		
		//top user
		$sql = "SELECT b.name,b.register_id, a.* FROM (SELECT user_id,COUNT(page) AS total 
					FROM social_tracking GROUP BY user_id) a
					INNER JOIN social_member b
					ON a.user_id = b.id
					WHERE register_id <> 10
					ORDER BY a.total DESC LIMIT 50;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('topuser',$rs);
		
		//top game
		$sql = "SELECT b.name,a.* FROM user_rank a
					INNER JOIN social_member b
					ON a.user_id = b.register_id
					ORDER BY rank ASC LIMIT 50;";
		$rs = $this->fetch($sql,1);
		$this->View->assign('topgame',$rs);
		
		return $this->View->toString("marlboro/admin/reporting-website-activity.html");
	}
	
	function xmlMerchandise(){
		$xml = '<chart>';
		$xml .= '<categories>';
		$xml .= '<item> </item>';
		$xml .= '</categories>';
		
		$sql = "SELECT prize,COUNT(user_id) AS total FROM marlboro_code.merchandise_transaction
GROUP BY prize;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		
		for($i=0;$i<$num;$i++){
			if($rs[$i]['prize'] == 'berlin-prize-brief'){
				$name = 'T-SHIRT';
			}elseif($rs[$i]['prize'] == 'new-york-prize-brief-1'){
				$name = 'Samsung Galaxy S II';
			}elseif($rs[$i]['prize'] == 'new-york-prize-brief-2'){
				$name = 'Samsung Galaxy Tab';
			}elseif($rs[$i]['prize'] == 'instanbul-prize-brief'){
				$name = 'VIP';
			}else{
				$name = 'NONE';
			}
			if($name != 'NONE'){
				$xml .= '<series>';
				$xml .= '<name>'.$name.'</name>';
				$xml .= '<data>';
				$xml .= '<point>'.intval($rs[$i]['total']).'</point>';
				$xml .= '</data></series>';
			}
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function xmlDataGames(){
		$xml = '<chart>';
		$xml .= '<categories>';
		$xml .= '<item> </item>';
		$xml .= '</categories>';
		
		$sql = "SELECT game_id,COUNT(id) AS total FROM social_game WHERE LEVEL=1 GROUP BY game_id;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		
		for($i=0;$i<$num;$i++){
			
			if($rs[$i]['game_id'] == 1){
				$name = "Berlin Wall";
			}elseif($rs[$i]['game_id'] == 2){
				$name = "Berlin DJ";
			}elseif($rs[$i]['game_id'] == 3){
				$name = "Yacht";
			}elseif($rs[$i]['game_id'] == 4){
				$name = "Museum";
			}
		
			$xml .= '<series>';
			$xml .= '<name>'.$name.'</name>';
			$xml .= '<data>';
			$xml .= '<point>'.intval($rs[$i]['total']).'</point>';
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function dataXmlHitPage(){
		$xml = '<chart>';
		$xml .= '<categories>';
		$xml .= '<item> </item>';
		$xml .= '</categories>';
		
		$sql = "SELECT SUM(a.total) total FROM
					(SELECT page,COUNT(page) AS total 
					FROM social_tracking WHERE page NOT IN ('news => getlasttrade','news => getbadgeinfo','REDEEM BADGES => getmopprofile')
					GROUP BY page) a;";
		$rs = $this->fetch($sql);
		$total = $rs['total'];
		$sql = "SELECT page,COUNT(page) AS total 
					FROM social_tracking WHERE page NOT IN ('news => getlasttrade','news => getbadgeinfo','REDEEM BADGES => getmopprofile')
					GROUP BY page;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$rs[$i]['persen'] = round(($rs[$i]['total'] / $total) * 100);
			
			if($rs[$i]['page'] == 'about-marlboro-connections => home'){
				$rs[$i]['page'] = 'about marlboro';
			}
			
			$p = explode(' => ',$rs[$i]['page']);
			if($p[0] == 'REDEEM BADGES' && $p[1] != 'home'){
				$rs[$i]['page'] = $p[1];
			}
		}
		
		//print_r($rs);exit;
		
		$num = count($rs);
		
		for($i=0;$i<$num;$i++){
			$xml .= '<series>';
			$xml .= '<name>'.$rs[$i]['page'].'</name>';
			$xml .= '<data>';
			$xml .= '<point>'.intval($rs[$i]['total']).'</point>';
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function dataXmlCityLogin(){
		$xml = '<chart>';
		$xml .= '<categories>';
		$xml .= '<item> </item>';
		$xml .= '</categories>';
		
		$sql = "SELECT SUM(total_login) total FROM (SELECT b.city,SUM(login_count) total_login 
					FROM social_member a
					INNER JOIN mop_city_lookup b
					ON a.city = b.id
					GROUP BY b.id) a;";
		$rs = $this->fetch($sql);
		$total = $rs['total'];
		$sql = "SELECT b.city,SUM(login_count) total_login 
						FROM social_member a
						INNER JOIN mop_city_lookup b
						ON a.city = b.id
						GROUP BY b.id;";
		$rs = $this->fetch($sql,1);
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$rs[$i]['persen'] = round(($rs[$i]['total_login'] / $total) * 100);
		}
		//print_r($rs);exit;
		
		$num = count($rs);
		
		for($i=0;$i<$num;$i++){
			$xml .= '<series>';
			$xml .= '<name>'.$rs[$i]['city'].'</name>';
			$xml .= '<data>';
			$xml .= '<point>'.intval($rs[$i]['total_login']).'</point>';
			$xml .= '</data></series>';
		}
		$xml .= '</chart>';
		
		header("Content-type: text/xml"); 
		print '<?xml version="1.0"?>';
		print $xml;
		exit;
	}
	
	function getCSV(){
		$data = $_GET['data'];
		
		if($data != ''){
			$filename = "report_".$data."_".date("YmdHis").".csv";
			
			if(!$_GET['debug']){
				header("Content-type: application/force-download");
				header("Content-Disposition: attachment; filename=\"".$filename."\"");
			}
			
			if($data == 'user-games'){
				$sql = "SELECT * FROM marlboro_code.report_user_game;";
				$rs = $this->fetch($sql,1);
				//print_r($rs);exit;
				$str = "\"ID\";\"Name\";\"berlin wall\";\"DJ\";\"Yacth\";\"Museum\"\r\n";
				foreach($rs as $d){
					$str.="\"".$d['register_id']."\";\"".$d['name']."\";\"".$d['game_1']."\";\"".$d['game_2']."\";\"".$d['game_3']."\";\"".$d['game_4']."\"\r\n";
				}
			}elseif($data == 'user-badges'){
				//$sql = "SELECT * FROM marlboro_code.report_user_inventory;";
				$sql = "SELECT m.email,if(i.name is null, m.name,i.name) as nama,m.register_id as regid,i.* FROM marlboro_connect.social_member m LEFT JOIN marlboro_code.report_user_inventory i ON m.register_id=i.register_id";
				$rs = $this->fetch($sql,1);
				$sql = "SELECT id,name FROM marlboro_code.badge_catalog WHERE id < 13 ORDER BY id;";
				$rs2 = $this->fetch($sql,1);
				//print_r($rs);exit;
				$str = "\"ID\";\"Name\";\"Email\";";
				foreach($rs2 as $d){
					$str.="\"".$d['name']."\";";
				}
				$str.= "\r\n";
				foreach($rs as $d){
					$str.="\"".$d['regid']."\";\"".$d['nama']."\";\"".$d['email']."\";\"".intval($d['badge_1'])."\";\"".intval($d['badge_2'])."\";\"".intval($d['badge_3'])."\";\"".intval($d['badge_4'])."\";\"".intval($d['badge_5'])."\";\"".intval($d['badge_6'])."\";\"".intval($d['badge_7'])."\";\"".intval($d['badge_8'])."\";\"".intval($d['badge_9'])."\";\"".intval($d['badge_10'])."\";\"".intval($d['badge_11'])."\";\"".intval($d['badge_12'])."\"\r\n";
				}
			}elseif($data == 'dashboard_badge'){
				
				//jumlah badge
				$sql = "SELECT SUM(a.total) total FROM (
							SELECT badge_id,b.name AS badgeName,COUNT(badge_id) AS total 
							FROM marlboro_code.badge_inventory a
							INNER JOIN marlboro_code.badge_catalog b
							ON a.badge_id = b.id
							GROUP BY b.id) a;";
				$rs = $this->fetch($sql);
				$total = $rs['total'];
				$sql = "SELECT badge_id,b.name AS badgeName,COUNT(badge_id) AS total 
							FROM marlboro_code.badge_inventory a
							INNER JOIN marlboro_code.badge_catalog b
							ON a.badge_id = b.id
							GROUP BY b.id;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$rs[$i]['persen'] = round(($rs[$i]['total'] / $total) * 100);
				}
				
				//print_r($rs);exit;
				
				$str = "\"ID\";\"Name\";\"Total\";\"Persen\"";
				$str.= "\r\n";
				foreach($rs as $d){
					$str.="\"".$d['badge_id']."\";\"".$d['badgeName']."\";\"".$d['total']."\";\"".$d['persen']."\"\r\n";
				}
			}elseif($data == 'dashboard_chart'){
				
				$sql = "SELECT DISTINCT(tanggal) tgl FROM marlboro_code.report_dashboard_daily WHERE 1";
				$rs = $this->fetch($sql,1);
				
				$str = "\"Name\"";
				foreach($rs as $k => $v){
					$str .= ";\"".$v['tgl']."\"";
				}
				$str .= "\r\n";
				
				$sort = array(1,3,4);
				$num = count($sort);
				for($i=0;$i<$num;$i++){
					$sql = "SELECT a.tgl,b.total FROM (SELECT DISTINCT(tanggal) tgl FROM marlboro_code.report_dashboard_daily WHERE 1) a LEFT JOIN (SELECT tanggal,total FROM marlboro_code.report_dashboard_daily WHERE type='".$sort[$i]."' AND 1) b ON a.tgl=b.tanggal;";
					//echo $sql;exit;
					$rs = $this->fetch($sql,1);
					
					if($sort[$i] == 1){
						$name = "Logins";
					}elseif($sort[$i] == 2){
						$name = "Active Users";
					}elseif($sort[$i] == 3){
						$name = "Badges Disbursed";
					}elseif($sort[$i] == 4){
						$name = "Trades Completed";
					}
					
					$str .= "\"".$name."\"";
					
					foreach($rs as $k => $v){
						$str .= ";\"".intval($v['total'])."\"";
					}
					
					$str .= "\r\n";
					
				}
				
				//print_r($rs);exit;
			}elseif($data == 'dashboard_sba_dst'){
				
				//DTS
				$sql = "SELECT tgl FROM marlboro_code.report_surveyor_performance GROUP BY tgl ORDER BY tgl;";
				$rs = $this->fetch($sql,1);
				
				$str = "\"Name\"";
				foreach($rs as $k => $v){
					$str .= ";\"".$v['tgl']."\"";
				}
				$str .= "\r\n";
				
				$sql = "SELECT DISTINCT(surveyor) FROM marlboro_code.report_surveyor_performance;";
				$rs = $this->fetch($sql,1);
				foreach($rs as $k => $v){
					$sql = "SELECT a.tgl,b.total
		FROM (SELECT t1.tgl,t2.surveyor FROM (SELECT DISTINCT tgl FROM marlboro_code.report_surveyor_performance) t1, (SELECT DISTINCT surveyor FROM marlboro_code.report_surveyor_performance) t2) a
		LEFT JOIN marlboro_code.report_surveyor_performance b ON a.tgl=b.tgl AND a.surveyor=b.surveyor WHERE a.surveyor='".$v['surveyor']."';";
					$rs2 = $this->fetch($sql,1);
					$str .= "\"".$v['surveyor']."\"";
					foreach($rs2 as $i => $j){
						$str .= ";\"".intval($j['total'])."\"";
					}
					$str .= "\r\n";
				}
				
			}elseif($data == 'dashboard_hitpage'){
			
				$sql = "SELECT SUM(a.total) total FROM
							(SELECT page,COUNT(page) AS total 
							FROM social_tracking WHERE page NOT IN ('news => getlasttrade','news => getbadgeinfo','REDEEM BADGES => getmopprofile')
							GROUP BY page) a;";
				$rs = $this->fetch($sql);
				$total = $rs['total'];
				$sql = "SELECT page,COUNT(page) AS total 
							FROM social_tracking WHERE page NOT IN ('news => getlasttrade','news => getbadgeinfo','REDEEM BADGES => getmopprofile')
							GROUP BY page;";
				$rs = $this->fetch($sql,1);
				
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$rs[$i]['persen'] = round(($rs[$i]['total'] / $total) * 100);
					
					if($rs[$i]['page'] == 'about-marlboro-connections => home'){
						$rs[$i]['page'] = 'about marlboro';
					}
					
					$p = explode(' => ',$rs[$i]['page']);
					if($p[0] == 'REDEEM BADGES' && $p[1] != 'home'){
						$rs[$i]['page'] = $p[1];
					}
				}
				
				//print_r($rs);exit;
				
				$num = count($rs);
				
				$str = "\"Name\";\"Total\"\r\n";
				
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['page']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
				
			}elseif($data == 'dashboard_login_kota'){
			
				$sql = "SELECT SUM(total_login) total FROM (SELECT b.city,SUM(login_count) total_login 
							FROM social_member a
							INNER JOIN mop_city_lookup b
							ON a.city = b.id
							GROUP BY b.id) a;";
				$rs = $this->fetch($sql);
				$total = $rs['total'];
				$sql = "SELECT b.city,SUM(login_count) total_login 
								FROM social_member a
								INNER JOIN mop_city_lookup b
								ON a.city = b.id
								GROUP BY b.id;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$rs[$i]['persen'] = round(($rs[$i]['total_login'] / $total) * 100);
				}
				
				$str = "\"City\";\"Total\"\r\n";
				
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['city']."\";\"".intval($rs[$i]['total_login'])."\"\r\n";
				}
				
			}elseif($data == 'dashboard_redeem'){
				
				//redeem channel
				$sql = "SELECT SUM(a.total_redeem) total FROM (
							SELECT channel_name,SUM(total) AS  total_redeem 
							FROM marlboro_code.report_redeem a
							INNER JOIN marlboro_code.badge_channel b
							ON a.channel = b.channel_id
							GROUP BY channel) a;";
				$rs = $this->fetch($sql);
				$total = $rs['total'];
				$sql = "SELECT channel_name,SUM(total) AS  total_redeem 
							FROM marlboro_code.report_redeem a
							INNER JOIN marlboro_code.badge_channel b
							ON a.channel = b.channel_id
							GROUP BY channel;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$rs[$i]['persen'] = round(($rs[$i]['total_redeem'] / $total) * 100);
				}
				
				//print_r($rs);exit;
				
				$str = "\"Channel\";\"Total Redeem\";\"Persen\"\r\n";
				
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['channel_name']."\";\"".$rs[$i]['total_redeem']."\";\"".$rs[$i]['persen']."%\"\r\n";
				}
				
			}elseif($data == 'website_mostactivecontent'){
			
				//TOP HIT
				$sql = "SELECT 
			a.*,
			p.pages_name 
		FROM 
			(SELECT page,COUNT(page) AS total FROM social_tracking  GROUP BY page) a
			LEFT JOIN marlboro_code.pages_lookup pl
			ON a.page=pl.page
			LEFT JOIN marlboro_code.pages p
			ON pl.pages_id=p.pages_id
		WHERE 
			a.page <> 'news => getlasttrade'
		ORDER BY total DESC LIMIT 3;";
				$rs = $this->fetch($sql,1);
				
				//TOP PERCENT
				$sql = "SELECT SUM(total) AS subtotal FROM (SELECT page,COUNT(page) AS total FROM social_tracking  GROUP BY page) a
		WHERE page <> 'news => getlasttrade'
		ORDER BY total DESC LIMIT 1;";
				$rs2 = $this->fetch($sql);
				
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$percent = (intval($rs[$i]['total']) / intval($rs2['subtotal'])) * 100;
					$rs[$i]['percent'] = sprintf("%01.2f", $percent) . '%';
				}
				$str = "\"Page\";\"Number of Hit\";\"Percentage Ratio\"\r\n";
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['pages_name']."\";\"".$rs[$i]['total']."\";\"".$rs[$i]['percent']."%\"\r\n";
				}
				
			
			}elseif($data == 'website_user_redeem'){
			
				$sql = "SELECT prize,COUNT(user_id) AS total FROM marlboro_code.merchandise_transaction
		GROUP BY prize;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				$str = "\"Prize\";\"Total\"\r\n";
				
				for($i=0;$i<$num;$i++){
					if($rs[$i]['prize'] == 'berlin-prize-brief'){
						$name = 'T-SHIRT';
					}elseif($rs[$i]['prize'] == 'new-york-prize-brief-1'){
						$name = 'Samsung Galaxy S II';
					}elseif($rs[$i]['prize'] == 'new-york-prize-brief-2'){
						$name = 'Samsung Galaxy Tab';
					}elseif($rs[$i]['prize'] == 'instanbul-prize-brief'){
						$name = 'VIP';
					}else{
						$name = 'NONE';
					}
					if($name != 'NONE'){
						$str .= "\"".$name."\";\"".intval($rs[$i]['total'])."\"\r\n";
					}
				}
				
			}elseif($data == 'website_online_games'){
			
				$sql = "SELECT game_id,COUNT(id) AS total FROM social_game WHERE LEVEL=1 GROUP BY game_id;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				$str = "\"Game\";\"Total\"\r\n";
				
				for($i=0;$i<$num;$i++){
					
					if($rs[$i]['game_id'] == 1){
						$name = "Berlin Wall";
					}elseif($rs[$i]['game_id'] == 2){
						$name = "Berlin DJ";
					}elseif($rs[$i]['game_id'] == 3){
						$name = "Yacht";
					}elseif($rs[$i]['game_id'] == 4){
						$name = "Museum";
					}
				
					$str .= "\"".$name."\";\"".intval($rs[$i]['total'])."\"\r\n";
					
				}
				
			}elseif($data == 'website_top_user'){
			
				//top user
				$sql = "SELECT b.name,b.register_id, a.* FROM (SELECT user_id,COUNT(page) AS total 
							FROM social_tracking GROUP BY user_id) a
							INNER JOIN social_member b
							ON a.user_id = b.id
							WHERE register_id <> 10
							ORDER BY a.total DESC LIMIT 50;";
				$rs = $this->fetch($sql,1);
				$str = "\"Name\";\"Register ID\"\r\n";
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['name']."\";\"".$rs[$i]['register_id']."\"\r\n";
				}
				
			}elseif($data == 'website_top_score'){
				
				//top game
				$sql = "SELECT b.name,a.* FROM user_rank a
							INNER JOIN social_member b
							ON a.user_id = b.register_id
							ORDER BY rank ASC LIMIT 50;";
				$rs = $this->fetch($sql,1);
				$str = "\"Name\";\"Register ID\";\"Rank\";\"Score\"\r\n";
				$num = count($rs);
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['name']."\";\"".$rs[$i]['user_id']."\";\"".$rs[$i]['rank']."\";\"".$rs[$i]['score']."\"\r\n";
				}
				
			}elseif($data == 'redeemtion_total'){
			
				$str = "\"Name\";\"Total\"\r\n";
				
				for($i=0;$i<2;$i++){
					if( $i == 0){
						$sql = "SELECT COUNT(*) as total
		FROM (SELECT b.kode FROM ipad_code_registration a
		INNER JOIN marlboro_code.badge_redeem b
		ON a.codeid = b.kode 
		WHERE b.kode NOT IN (SELECT kode FROM marlboro_code.sba_code_lookup)
		GROUP BY b.kode) c;";
					}elseif($i == 1){
						$sql = "SELECT COUNT(*) as total
		FROM (SELECT b.kode FROM ipad_code_quiz a
		INNER JOIN marlboro_code.badge_redeem b
		ON a.codeid = b.kode 
		WHERE b.kode NOT IN (SELECT kode FROM marlboro_code.sba_code_lookup)
		GROUP BY b.kode) c";
					}
					$rs = $this->fetch($sql);
					if($i == 0){
						$name = "Registration";
					}elseif($i == 1){
						$name = "Quiz";
					}
					
					$str .= "\"".$name."\";\"".intval($rs['total'])."\"\r\n";
				}
				
			}elseif($data == 'redeemtion_baliho'){
			
				$channel = 1;
				
				$str = "\"Name\";\"Total\"\r\n";
		
				$sql = "SELECT * FROM marlboro_code.report_channel_redeem WHERE channel=$channel;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['subchannel']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
				
			}elseif($data == 'redeemtion_magazine'){
				
				$channel = 2;
				
				$str = "\"Name\";\"Total\"\r\n";
		
				$sql = "SELECT * FROM marlboro_code.report_channel_redeem WHERE channel=$channel;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['subchannel']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
				
			}elseif($data == 'redeemtion_poster'){
			
				$channel = 4;
				
				$str = "\"Name\";\"Total\"\r\n";
		
				$sql = "SELECT * FROM marlboro_code.report_channel_redeem WHERE channel=$channel;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['subchannel']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
			
			}elseif($data == 'redeemtion_pog'){
				
				$channel = 3;
				
				$str = "\"Name\";\"Total\"\r\n";
		
				$sql = "SELECT * FROM marlboro_code.report_channel_redeem WHERE channel=$channel;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['subchannel']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
				
			}elseif($data == 'redeemtion_lamp'){
			
				$channel = 10;
				
				$str = "\"Name\";\"Total\"\r\n";
		
				$sql = "SELECT * FROM marlboro_code.report_channel_redeem WHERE channel=$channel;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['subchannel']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
			
			}elseif($data == 'redeemtion_digital'){
			
				$channel = 6;
				
				$str = "\"Name\";\"Total\"\r\n";
		
				$sql = "SELECT * FROM marlboro_code.report_channel_redeem WHERE channel=$channel;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['subchannel']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
			
			}elseif($data == 'redeemtion_rich'){
			
				$channel = 7;
				
				$str = "\"Name\";\"Total\"\r\n";
		
				$sql = "SELECT * FROM marlboro_code.report_channel_redeem WHERE channel=$channel;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['subchannel']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
			
			}elseif($data == 'user_login_per_day'){
			
				$sql = "SELECT DISTINCT(tanggal) tgl FROM marlboro_code.report_dashboard_daily WHERE 1";
				$rs = $this->fetch($sql,1);
				
				$str = "\"Name\"";
				foreach($rs as $k => $v){
					$str .= ";\"".$v['tgl']."\"";
				}
				$str .= "\r\n";
				
				$sort = array(1);
				$num = count($sort);
				for($i=0;$i<$num;$i++){
					$sql = "SELECT a.tgl,b.total FROM (SELECT DISTINCT(tanggal) tgl FROM marlboro_code.report_dashboard_daily WHERE 1) a LEFT JOIN (SELECT tanggal,total FROM marlboro_code.report_dashboard_daily WHERE type='".$sort[$i]."' AND 1) b ON a.tgl=b.tanggal;";
					//echo $sql;exit;
					$rs = $this->fetch($sql,1);
					
					if($sort[$i] == 1){
						$name = "Logins";
					}elseif($sort[$i] == 2){
						$name = "Active Users";
					}elseif($sort[$i] == 3){
						$name = "Badges Disbursed";
					}elseif($sort[$i] == 4){
						$name = "Trades Completed";
					}
					
					$str .= "\"".$name."\"";
					
					foreach($rs as $k => $v){
						$str .= ";\"".intval($v['total'])."\"";
					}
					
					$str .= "\r\n";
					
				}
			
			}elseif($data == 'badge_user_collecting'){
				
				//USER COLLECTING BADGE
				$sql = "SELECT * FROM
							(SELECT id FROM marlboro_code.badge_catalog) a
							LEFT JOIN
							(SELECT a.badge_id,b.name,COUNT(a.badge_id) AS total 
								FROM marlboro_code.badge_inventory a 
								INNER JOIN marlboro_code.badge_catalog b
								ON a.badge_id = b.id
								GROUP BY badge_id) b
							ON a.id=b.badge_id;";
				$rs = $this->fetch($sql,1);
				
				$num = count($rs);
				$str .= "\"Badge ID\";\"Total\"\r\n";
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['id']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
				
			}elseif($data == 'badge_generate'){
			
				//GENERATED BADGE
				$sql = "SELECT * FROM
							(SELECT id FROM marlboro_code.badge_catalog) a
							LEFT JOIN
							(SELECT badge_id,SUM(total) AS jumlah
							FROM (SELECT b.badge_id,COUNT(b.badge_id) AS total 
							FROM marlboro_code.badge_redeem a
							INNER JOIN marlboro_code.merchandise_redeem b
							ON a.id = b.redeem_id
							GROUP BY b.badge_id
							UNION ALL
							SELECT b.badge_id,COUNT(b.badge_id) AS total 
							FROM marlboro_code.badge_redeem a
							INNER JOIN marlboro_code.badge_inventory b
							ON a.id = b.redeem_id
							GROUP BY b.badge_id
							) aa
							GROUP BY aa.badge_id) b
							ON a.id=b.badge_id;";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				$str .= "\"Badge ID\";\"Total\"\r\n";
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['id']."\";\"".intval($rs[$i]['jumlah'])."\"\r\n";
				}
			
			}elseif($data == 'badge_being_trade'){
			
				//BADGE BEING TRADE
				$sql = "SELECT badge_id,total FROM marlboro_code.report_badge_traded ORDER BY badge_id ASC";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				$str .= "\"Badge ID\";\"Total\"\r\n";
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['badge_id']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
			
			}elseif($data == 'badge_most_trade'){
			
				//MOST TRADE
				$sql = "SELECT badge_id,total FROM marlboro_code.report_badge_traded ORDER BY total DESC";
				$rs = $this->fetch($sql,1);
				$num = count($rs);
				$str .= "\"Badge ID\";\"Total\"\r\n";
				for($i=0;$i<$num;$i++){
					$str .= "\"".$rs[$i]['badge_id']."\";\"".intval($rs[$i]['total'])."\"\r\n";
				}
			
			}elseif($data == 'total_user'){
			
				$sql = "SELECT 
								register_date AS tgl,
								register_id AS regid,
								name AS nama,
								email
							FROM marlboro_connect.social_member ORDER BY name";
				$rs = $this->fetch($sql,1);
				$str .= "\"Register Date\";\"User ID\";\"Name\";\"Email\"\r\n";
				foreach($rs as $d){
					$str .= "\"".$d['tgl']."\";\"".$d['regid']."\";\"".$d['nama']."\";\"".$d['email']."\"\r\n";
				}
			
			}
			
			header("Content-Length: ".strlen($str));
			print $str;
			die();
		}
	}
}