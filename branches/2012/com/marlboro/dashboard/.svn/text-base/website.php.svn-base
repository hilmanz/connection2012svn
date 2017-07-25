<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class website extends SQLData{
	var $startDate ;
	var $endDate  ;
	var $startDateOthers ;
	var $endDateOthers  ;
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
		$this->getRangeDate();
	}
	
	function getRangeDate(){
		// print_r('<pre>');print_r($this);
		$startDate = $this->Request->getParam('from');
		$endDate = $this->Request->getParam('to');

		
		if($startDate=='') $this->startDate= ' 2012-07-12 ';
		else $this->startDate =  $startDate;
		if($endDate=='') $this->endDate= date('Y-m-d');
		else $this->endDate = $endDate;
		

	
		$this->View->assign('from',$this->startDate);
		$this->View->assign('to',$this->endDate);
	}

	function numberuser(){
		
		$qLogin = "	SELECT SUM(num) as login_num ,date_d, gender 
					FROM
					(SELECT count(*) as num, user_id, DATE(date_time) as date_d , sex as gender
					FROM tbl_activity_log log
					INNER JOIN social_member sm ON sm.id=log.user_id
					WHERE action_id=1 GROUP BY user_id,date_d) as tbl_daily_user_login
					WHERE date_d BETWEEN '{$this->startDate}' AND '{$this->endDate}'
					GROUP BY date_d,gender
				";

		$qGender="	SELECT gender, login_num
					FROM marlboro_connection_report_2012.rp_overall_gender_daily
					WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY);
					";
		
		$qRegistrant="	SELECT registrant_num, existing_num, new_num
						FROM marlboro_connection_report_2012.rp_overall_user_daily
						WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY);
						";
		//pake query ini sementara
		$qRegistrant ="
		SELECT count(*) as registrant_num 
		FROM  marlboro_connection_2012.social_member sm 
		WHERE not exists (SELECT 1 FROM marlboro_connection_report_2012.tbl_existing_user WHERE email=sm.email ) 
		AND register_id <> 0
		AND DATE(register_date) BETWEEN '{$this->startDate}' AND '{$this->endDate}'
		";
		$qExistingRegistrant ="
		SELECT count(*) as existing_num 
		FROM  marlboro_connection_2012.social_member sm 
		WHERE exists (SELECT 1 FROM marlboro_connection_report_2012.tbl_existing_user WHERE email=sm.email ) 
		AND register_id <> 0
		AND DATE(register_date) BETWEEN '{$this->startDate}' AND '{$this->endDate}'
		";
		$qParticipant ="
		SELECT count(*) as new_num 
		FROM  marlboro_connection_2012.social_member sm 
		WHERE not exists (SELECT 1 FROM marlboro_connection_report_2012.tbl_existing_user WHERE email=sm.email ) 
		AND register_id <> 0 AND n_status=1 AND login_count >=1
		AND DATE(register_date) BETWEEN '{$this->startDate}' AND '{$this->endDate}'
		";
		$qAge = "	SELECT *
					FROM marlboro_connection_report_2012.rp_overall_age_daily
					WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)";
		//pake query ini dulu buat age
		$qAgeRegistrant = "
						SELECT DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(birthday, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(birthday, '00-%m-%d')) AS age
						FROM marlboro_connection_2012.social_member sm
						WHERE 
						not exists (SELECT 1 FROM marlboro_connection_report_2012.tbl_existing_user WHERE email=sm.email ) AND 
						register_id<>0
						AND DATE(register_date) BETWEEN '{$this->startDate}' AND '{$this->endDate}'
						";
		$qAgeExisiting = "
						SELECT DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(birthday, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(birthday, '00-%m-%d')) AS age
						FROM marlboro_connection_2012.social_member sm
						WHERE 
						exists (SELECT 1 FROM marlboro_connection_report_2012.tbl_existing_user WHERE email=sm.email ) AND 
						register_id<>0
						AND DATE(register_date) BETWEEN '{$this->startDate}' AND '{$this->endDate}'
						";
		
		$qAgeNewUser = "
						SELECT DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(birthday, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(birthday, '00-%m-%d')) AS age
						FROM marlboro_connection_2012.social_member sm
						WHERE 
						exists (SELECT 1 FROM marlboro_connection_report_2012.tbl_existing_user WHERE email=sm.email ) AND 
						register_id<>0
						AND register_id <> 0 AND n_status=1 AND login_count >=1
						AND DATE(register_date) BETWEEN '{$this->startDate}' AND '{$this->endDate}'
						";
		$this->open(0);
			$login=$this->fetch($qLogin,1);
			$gender=$this->fetch($qGender,1);
			$registrant=$this->fetch($qRegistrant);
			$existing=$this->fetch($qExistingRegistrant);
			$participant=$this->fetch($qParticipant);
			$ageUser=$this->fetch($qAgeRegistrant,1);
			$ageUserExisting=$this->fetch($qAgeExisiting,1);	
			$ageNewUser=$this->fetch($qAgeNewUser,1);	
		$this->close();
		//login
		
		foreach ($login as $kLogin => $vLogin) {
			$dataLogin[$vLogin['date_d']]+=$vLogin['login_num'];
			if($vLogin['gender']=='F') $gdr[$vLogin['gender']] += $vLogin['login_num'];
			if($vLogin['gender']=='M') $gdr[$vLogin['gender']] += $vLogin['login_num'];
			
		}
		// print_r('<pre>');print_r($login);
		$this->View->assign('login',json_encode($dataLogin));
		//gender
		if(!$gdr){
		$gdr['F']=0;
		$gdr['M']=0;
		$gdr['U']=0;
		}else $gdr['U']=0;
		// if($gender){
			// foreach ($gender as $kGender => $vGender) {
				// $gdr[$vGender['gender']] = $vGender['login_num'];
			// }
		// }else{
		// $gdr['F']=0;
		// $gdr['M']=0;
		// $gdr['U']=0;
		// }
		
		//age registrant
		foreach($ageUser as $keyAge =>  $valAge){
			if($valAge['age']<=29) {
					$ageRegistrant[0]['age_category'] = 0 ;
					$ageRegistrant[0]['login_num']++;
			}
			if($valAge['age']>=30 && $valAge['age']<=35) {
					$ageRegistrant[1]['age_category'] = 1 ;
					$ageRegistrant[1]['login_num']++;
			}
			if($valAge['age']>=36) {
					$ageRegistrant[2]['age_category'] =2 ;
					$ageRegistrant[2]['login_num']++;
			}
		}
		//age existing
		foreach($ageUserExisting as $keyAgeEx =>  $valAgeEx){
			if($valAgeEx['age']<=29) {
					$ageRegistrantEx[0]['age_category'] = 0 ;
					$ageRegistrantEx[0]['login_num']++;
			}
			if($valAgeEx['age']>=30 && $valAgeEx['age']<=35) {
					$ageRegistrantEx[1]['age_category'] = 1 ;
					$ageRegistrantEx[1]['login_num']++;
			}
			if($valAgeEx['age']>=36) {
					$ageRegistrantEx[2]['age_category'] =2 ;
					$ageRegistrantEx[2]['login_num']++;
			}
		}
		//age participant
		foreach($ageNewUser as $keyAgeNew =>  $valAgeNew){
			if($valAgeNew['age']<=29) {
					$ageRegistrantNew[0]['age_category'] = 0 ;
					$ageRegistrantNew[0]['login_num']++;
			}
			if($valAgeNew['age']>=30 && $valAgeNew['age']<=35) {
					$ageRegistrantNew[1]['age_category'] = 1 ;
					$ageRegistrantNew[1]['login_num']++;
			}
			if($valAgeNew['age']>=36) {
					$ageRegistrantNew[2]['age_category'] =2 ;
					$ageRegistrantNew[2]['login_num']++;
			}
		}
		
		// Array ( 
		//[0] => Array ( [age_category] => 0 [login_num] => 7 ) 
		//[1] => Array ( [age_category] => 1 [login_num] => 2 ) )

		// foreach($ageRegistrant as $keypart => $valPart){
			// $agePart[$keypart]['age_category']=$keypart;
			// $agePart[$keypart]['login_num']=$ageRegistrant[$keypart]['login_num'] - $ageRegistrantEx[$keypart]['login_num'];
		// }
		
		$this->View->assign('female',$gdr['F']);
		$this->View->assign('male',$gdr['M']);
		$this->View->assign('unknown',$gdr['U']);
		
		//registrant
		$new_member = $participant['new_num'];
		if($new_member<=0 )$new_member=$registrant['registrant_num'];
		$this->View->assign('registrant_num',$registrant['registrant_num']);
		$this->View->assign('existing_num',$existing['existing_num']);
		$this->View->assign('new_num',$new_member);

		//age
		$this->View->assign('userAgeAll',json_encode($ageRegistrant));
		$this->View->assign('userAgeExisting',json_encode($ageRegistrantEx));
		$this->View->assign('userAgePart',json_encode($ageRegistrantNew));
		// print('<pre>');print_r($gdr);echo $gdr['F'];exit;
		return $this->View->toString("dashboard/number-of-user.html");
	}

	function activities(){
		$qMostVisitedPage ="SELECT count(*) t, action_value FROM `tbl_activity_log` where action_id=7 group by action_value order by t DESC ";
		$qActivityWeb ="
			SELECT count(*) t, action_id , `activityName`
			FROM tbl_activity_actions act
			INNER JOIN `tbl_activity_log`  log ON log.action_id=act.id
			where action_id <>7 group by action_id order by t DESC ";
		$this->open(0);
			$rqMostVisitedPage=$this->fetch($qMostVisitedPage,1);
			$rqActivityWeb=$this->fetch($qActivityWeb,1);
		$this->close();
			
		foreach($rqMostVisitedPage as $key => $val){
			$mostVisitedPage[$val['action_value']]=$val['t'];
			$totalVisitAllPage +=$val['t'];
		}
		
		foreach($rqActivityWeb as $keyrqActivityWeb => $valrqActivityWeb){
			$activityWeb[$valrqActivityWeb['activityName']]=$valrqActivityWeb['t'];
			$totalactivityWeb +=$val['t'];
		}			
		
		$this->View->assign('mostVisitedPage',json_encode($mostVisitedPage));
		$this->View->assign('totalVisitAllPage',$totalVisitAllPage);
		$this->View->assign('activityWeb',json_encode($activityWeb));
		$this->View->assign('totalactivityWeb',$totalactivityWeb);
		return $this->View->toString("dashboard/activities.html");
	}

	function badges(){
	
		$qredeemBadge="SELECT channel_id, channel_name, redeem_num
		FROM marlboro_connection_report_2012.rp_badge_channel_daily
		WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY);
		";
		//sementara
		$qredeemBadge ="SELECT channel as channel_id,  count(*) as redeem_num, channel_name
						FROM marlboro_connection_code_2012.badge_inventory inven
						INNER JOIN marlboro_connection_code_2012.badge_redeem redeem ON redeem.id=inven.redeem_id
						INNER JOIN marlboro_connection_code_2012.badge_code code ON code.kode=redeem.kode
						INNER JOIN  marlboro_connection_code_2012.badge_channel channels ON channels.channel_id=code.channel
						GROUP BY  channel";
		$qredeemBadgeGetYellowHunt = "
						SELECT 8 as channel_id,  count(*) as redeem_num, 'Yellow Cabs Hunt' as channel_name
						FROM marlboro_connection_report_2012.tbl_report_user_got_yellow_cab ";
		$qbadgepercent="SELECT badge_id,badge_name,redeem_num,redeem_total,redeem_percent
		FROM marlboro_connection_report_2012.rp_badge_redeem_daily
		WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY);
		";
		$qSingleBadge="SELECT badge_id, badge_name, redeem_num as num
		FROM marlboro_connection_report_2012.rp_badge_single_realtime
		";
		//pake ini sementara
		$qSingleBadge="	SELECT badge_id,  count(*) as num
						FROM marlboro_connection_code_2012.badge_inventory
						GROUP BY badge_id";
		$qBadgeSet="SELECT set_id, set_name, redeem_num
		FROM marlboro_connection_report_2012.rp_badge_set_realtime";		
		//pake ini
		$qBadgeSet = "	
						SELECT SUM( redeem_num) as redeem_num , set_name,set_id
						FROM(
						SELECT  IF(COUNT(*) >=3 , 1 ,0) as redeem_num, series.name as set_name,series.id as set_id,user_id
						FROM marlboro_connection_code_2012.badge_inventory inven
						INNER JOIN marlboro_connection_code_2012.badge_catalog catalog ON catalog.id=inven.badge_id
						INNER JOIN  marlboro_connection_code_2012.badge_series series ON series.id=catalog.series_type
						GROUP BY series_type,user_id
						) as setbadge
						GROUP BY set_id";
						
		//newyork
		$qBadgetSetNY = "	SELECT SUM(num) as redeem_num , 1 as set_id, 'New York' as set_name
							FROM(
							SELECT if(COUNT(*)>2,1,0) as num, user_id FROM (
							SELECT badge_id, user_id 
							FROM marlboro_connection_code_2012.badge_inventory inven
							WHERE exists (
							SELECT 1 FROM 
							 marlboro_connection_code_2012.badge_catalog 
							WHERE series_type=1 AND id=inven.badge_id
							) group by user_id,badge_id
							order by user_id
							) n
							GROUP BY user_id
							) as totalSet";
		//berlin 2
		$qBadgetSetBerlin = "	
							SELECT SUM(num) as redeem_num , 2 as set_id, 'Berlin' as set_name
							FROM(
							SELECT if(COUNT(*)>2,1,0) as num, user_id FROM (
							SELECT badge_id, user_id 
							FROM marlboro_connection_code_2012.badge_inventory inven
							WHERE exists (
							SELECT 1 FROM 
							 marlboro_connection_code_2012.badge_catalog 
							WHERE series_type=2 AND id=inven.badge_id
							) group by user_id,badge_id
							order by user_id
							) n
							GROUP BY user_id
							) as totalSet";
		//istanbul 3
		$qBadgetSetIstan = "	SELECT SUM(num) as redeem_num , 3 as set_id, 'Istanbul' as set_name
							FROM(
							SELECT if(COUNT(*)>2,1,0) as num, user_id FROM (
							SELECT badge_id, user_id 
							FROM marlboro_connection_code_2012.badge_inventory inven
							WHERE exists (
							SELECT 1 FROM 
							 marlboro_connection_code_2012.badge_catalog 
							WHERE series_type=3 AND id=inven.badge_id
							) group by user_id,badge_id
							order by user_id
							) n
							GROUP BY user_id
							) as totalSet";				
		
		
		
		$getBadge = "SELECT id,image,name FROM marlboro_connection_code_2012.badge_catalog ";	
		
		$this->open(0);
			// redeem badges
			$reedem_badges=$this->fetch($qredeemBadge,1);
			$qredeemBadgeGetYellowHunt=$this->fetch($qredeemBadgeGetYellowHunt);
			// badges percentage		
			// $rsbadgePersentage=$this->fetch($qbadgepercent,1);
			// single badges
			$single_badges=$this->fetch($qSingleBadge,1);
			// badges set
			//$badges_set=$this->fetch($qBadgeSet,1);
			
			$rqBadgetSetNY=$this->fetch($qBadgetSetNY);
			$rqBadgetSetBerlin=$this->fetch($qBadgetSetBerlin);
			$rqBadgetSetIstan=$this->fetch($qBadgetSetIstan);
			
			$rGetBadge =$this->fetch($getBadge,1);
		$this->close();
		
		//single_badges
		foreach($single_badges as $key => $val){
			$single_badgesTotal+=$val['num'];
		}
		foreach($rGetBadge as $keyrGetBadge => $valrGetBadge){
			$badge[$valrGetBadge['id']]=$valrGetBadge['image'];
			$badge['name'][$valrGetBadge['id']]=$valrGetBadge['name'];
		}
		
		//single Badge
		foreach($single_badges as $keysingle_badges => $valsingle_badges){
			$badgeSingle[$keysingle_badges]['percent'] = $valsingle_badges['num']/$single_badgesTotal*100;
			$badgeSingle[$keysingle_badges]['num'] = $valsingle_badges['num'];
			$badgeSingle[$keysingle_badges]['badgeid'] = $valsingle_badges['badge_id'];
			$badgeSingle[$keysingle_badges]['image'] = $badge[$valsingle_badges['badge_id']];
			$badgeSingle[$keysingle_badges]['badge_name'] =$badge['name'][$valsingle_badges['badge_id']];
			
			$badges_percentage[$keysingle_badges]['redeem_percent'] =  $valsingle_badges['num']/$single_badgesTotal*100;
			$badges_percentage[$keysingle_badges]['redeem_num'] = $valsingle_badges['num'];
			$badges_percentage[$keysingle_badges]['badge_id'] =$valsingle_badges['badge_id'];
			$badges_percentage[$keysingle_badges]['image'] = $badge[$valsingle_badges['badge_id']];
			$badges_percentage[$keysingle_badges]['badge_name'] =$badge['name'][$valsingle_badges['badge_id']];
		}
		
		//
		
		$badges_set[0] = $rqBadgetSetNY;
		$badges_set[1] = $rqBadgetSetBerlin;
		$badges_set[2] = $rqBadgetSetIstan;
		// print_r($badges_set);exit;
		//yellow cab hunt re fill
		$reedem_badges[7] = $qredeemBadgeGetYellowHunt;
		
		$this->View->assign('reedem_badges',json_encode($reedem_badges));
		$this->View->assign('badges_percentage',json_encode($badges_percentage));
		$this->View->assign('badges_percentage',json_encode($badges_percentage));
		$this->View->assign('single_badges',$badgeSingle);
		$this->View->assign('badges_set',json_encode($badges_set));
		// print('<pre>');print_r($badges_percentage);exit;
		return $this->View->toString("dashboard/badges.html");
	}

	function badgestrading(){
	
		$qBadgeList="
		SELECT badge_id,  count(*) as num
		FROM marlboro_connection_code_2012.badge_inventory
		GROUP BY badge_id";
		$qBadgetrading="
		SELECT with_id as badge_id, count(*) as num FROM marlboro_connection_code_2012.auction_post GROUP BY with_id
		";
		$qTotalTrading="	
		SELECT total_trade, successful_trade
		FROM marlboro_connection_report_2012.rp_badge_trading_summary_realtime LIMIT 1";
		//sementara
		$qTotalTrading="
		SELECT SUM(num) as num FROM ( SELECT with_id as badge_id, count(*) as num FROM marlboro_connection_code_2012.auction_post WHERE n_status = 1 GROUP BY with_id ) a
		";
		
		$qMostTradedBadge ="
		SELECT with_id as badge_id, count(*) as num FROM marlboro_connection_code_2012.auction_post GROUP BY with_id ORDER BY num DESC
		";
		$getBadge = "SELECT id,image,name FROM marlboro_connection_code_2012.badge_catalog ";
			
		$this->open(0);
		// badges list
		$badgeList=$this->fetch($qBadgeList,1);
		// badges trading
		$badgetrading=$this->fetch($qBadgetrading,1);
		// badges trading
		$totalTrading=$this->fetch($qTotalTrading);
		// MostTradedBadge 
		$mostTradedBadge=$this->fetch($qMostTradedBadge,1);
		$rGetBadge = $this->fetch($getBadge,1);
		$this->close();
		
		
		//badge list
		foreach($badgeList as $keybadgeListT => $valbadgeListT){
			$badgeListTotal+=$valbadgeListT['num'];
		}
		//badgetrading
		foreach($badgetrading as $keybadgetradingT => $valbadgetradingT){
			$badgetradingTotal+=$valbadgetradingT['num'];
		}
		//itung persen tradeing most badge
		foreach($mostTradedBadge as $key => $val){
			$mosttradeTotal+=$val['num'];
		}
		foreach($rGetBadge as $keyrGetBadge => $valrGetBadge){
			$badge[$valrGetBadge['id']]=$valrGetBadge['image'];
			$badge[$valrGetBadge['id']."_name"]=$valrGetBadge['name'];
		}
		
		//badgelist
		foreach($badgeList as $keybadgeList => $valbadgeList){
			$badgeSingle[$keybadgeList]['percent'] = $valbadgeList['num']/$badgeListTotal*100;
			$badgeSingle[$keybadgeList]['num'] = $valbadgeList['num'];
			$badgeSingle[$keybadgeList]['badgeid'] = $valbadgeList['badge_id'];
			$badgeSingle[$keybadgeList]['image'] = $badge[$valbadgeList['badge_id']];
			$badgeSingle[$keybadgeList]['badge_name'] = $badge[$valbadgeList['badge_id']."_name"];
		}
		
		//badgetrading
		foreach($badgetrading as $keybadgetrading => $valbadgetrading){
			$badgeHasTrade[$keybadgetrading]['percent'] = $valbadgetrading['num']/$badgetradingTotal*100;
			$badgeHasTrade[$keybadgetrading]['num'] = $valbadgetrading['num'];
			$badgeHasTrade[$keybadgetrading]['badgeid'] = $valbadgetrading['badge_id'];
			$badgeHasTrade[$keybadgetrading]['image'] = $badge[$valbadgetrading['badge_id']];
			$badgeHasTrade[$keybadgetrading]['badge_name'] = $badge[$valbadgetrading['badge_id']."_name"];
		}
		
		//mostrade
		foreach($mostTradedBadge as $key => $val){
			$mosttrade[$key]['percent'] = $val['num']/$mosttradeTotal*100;
			$mosttrade[$key]['num'] = $val['num'];
			$mosttrade[$key]['badgeid'] = $val['badge_id'];
			$mosttrade[$key]['image'] = $badge[$val['badge_id']];
			$mosttrade[$key]['badge_name'] = $badge[$val['badge_id']."_name"];
		}
		
		//total trade
		$totalTrade['total_trade'] = $badgetradingTotal;
		$totalTrade['successful_trade'] = $totalTrading['num'];
		
		
		$this->View->assign('badgeList',$badgeSingle);
		$this->View->assign('badgetrading',$badgeHasTrade);
		$this->View->assign('totalTrading',$totalTrade);
		$this->View->assign('mostTradedBadge',$mosttrade);

		// print('<pre>');print_r($badges_list2);exit;
		return $this->View->toString("dashboard/badges-trading.html");
	}

	function loginhistory(){
	
		$qLogin = "	
					SELECT SUM(num) as login_num ,date_d, gender 
					FROM
					(SELECT count(*) as num, user_id, DATE(date_time) as date_d , sex as gender
					FROM tbl_activity_log log
					INNER JOIN social_member sm ON sm.id=log.user_id
					WHERE action_id=1 GROUP BY user_id,date_d) as tbl_daily_user_login
					WHERE date_d BETWEEN '{$this->startDate}' AND '{$this->endDate}'
					GROUP BY date_d,gender
					";
		$this->open(0);
			$login=$this->fetch($qLogin,1);
		$this->close();			
		
		//login
		foreach ($login as $kLogin => $vLogin) {
			$dataLogin[$vLogin['date_d']]+=$vLogin['login_num'];
		}
		
		$this->View->assign('login',json_encode($dataLogin));
		
		return $this->View->toString("dashboard/login-history.html");
	}

	function redeemmerch(){
		$this->getRangeDate();
		
		$qClock="
			SELECT date_d, merchandise_id, merchandise_name, merchandise_prefix, redeem_num
			FROM marlboro_connection_report_2012.rp_merchandise_redeem_daily
			WHERE merchandise_id = 4 AND date_d BETWEEN  '{$this->startDate}' AND '{$this->endDate}'";
		$qUSB ="
			SELECT merchandise_id, merchandise_name, merchandise_prefix, SUM(redeem_num)
			FROM marlboro_connection_report_2012.rp_merchandise_redeem_daily
			WHERE merchandise_id = 3 AND date_d BETWEEN  '{$this->startDate}' AND '{$this->endDate}'
			";
			
		//query gw.. atas ga muncul angka nya
		$qRedeemMerchandise ="
			SELECT DATE(`submit_date`) as date_d, `prize`, COUNT(`prize`) as redeem_num  FROM `social_redeem` WHERE 
			DATE(`submit_date`) BETWEEN  '{$this->startDate}' AND '{$this->endDate}'
			GROUP BY `prize`, date_d ORDER BY date_d
		";
		
		$this->open(0);
		// $rqClock=$this->fetch($qClock,1);
		// $rqUSB=$this->fetch($qUSB,1);
		$rqRedeemMerchandise=$this->fetch($qRedeemMerchandise,1);
		$this->close();
	
		foreach($rqRedeemMerchandise as $key => $val){
			if($val['prize']=='istanbul-prize-clock') $merchTotal['clock']+= $val['redeem_num'];
			if($val['prize']=='berlin-prize-fd') $merchTotal['usb']+= $val['redeem_num'];
			$merch[$val['prize']][$val['date_d']] = $val['redeem_num'];
		}	
			// print_r($merch);exit;
		$this->View->assign('redeemMerchandiseTotal',$merchTotal);
		$this->View->assign('redeemMerchandise',json_encode($merch));
		// print('<pre>');print_r($data);exit;
		return $this->View->toString("dashboard/redeem-merchandise.html");
	}


	function auctionhistory(){
		// auction history

		$qry="
				SELECT auction.item_name, history.bid_amount, member.name, auction.start_date, auction.end_date 
				FROM marlboro_connection_2012.social_auction_history history
				inner join marlboro_connection_2012.social_member member on member.id = history.user_id
				inner join marlboro_connection_2012.social_auction auction on auction.id = history.auction_id 
		";
		
		//query baru 
		$qAuctionHistory="
			SELECT auction.id,auction.item_name,auction.start_date,auction.end_date,COUNT(tUser)as user, history.bid_amount,history.user_id as winner,sm.name FROM  social_auction auction
			INNER JOIN  social_auction_history history  ON auction.id=history.auction_id
			INNER JOIN 
			(
			SELECT 1 as tUser, auction_id  FROM
			social_auction_bid GROUP BY auction_id,user_id ) bid ON auction.id= bid.auction_id
			INNER JOIN social_member sm ON sm.id=history.user_id
			GROUP BY auction.id";
		
		$qHighestBidder = "	 
			SELECT SUM(amount*badge_value) as totalAmount,user_id,auction_id,sa.item_name, sa.start_date, sa.end_date , sm.name, sm.last_name, count(user_id) as tUser
			FROM marlboro_connection_2012.social_auction_bid aucbid
			INNER JOIN marlboro_connection_code_2012.badge_catalog as badge ON badge.id=aucbid.badge_id
			INNER JOIN marlboro_connection_2012.social_auction sa ON sa.id=aucbid.auction_id
			INNER JOIN marlboro_connection_2012.social_member sm ON sm.id=aucbid.user_id
			WHERE 
			aucbid.n_status = 1 
			AND amount <> 0			
			GROUP BY auction_id,user_id
			ORDER BY totalAmount DESC
		";
		
		$this->open(0);
		$rqAuctionHistory=$this->fetch($qAuctionHistory,1);
		$rqhighestData=$this->fetch($qHighestBidder,1);
		$this->close();
		$this->View->assign('auctionHistory',$rqAuctionHistory);
		$this->View->assign('highestData',$rqhighestData);
		// print('<pre>');print_r($data);exit;
		return $this->View->toString("dashboard/auction-history.html");
	}

	function topuser(){
		return $this->View->toString("dashboard/top-user.html");
	}

	function topcity(){
		return $this->View->toString("dashboard/top-city.html");
	}

	function deviceused(){
		return $this->View->toString("dashboard/device-used.html");
	}
	
}