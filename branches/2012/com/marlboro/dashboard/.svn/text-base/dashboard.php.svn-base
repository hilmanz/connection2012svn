<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class dashboard extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'new' ){
			return $this->addNew();
		}elseif( $act == 'add' ){
			return $this->add();
		}elseif( $act == 'edit' ){
			return $this->Edit();
		}elseif( $act == 'update' ){
			return $this->Update();
		}elseif( $act == 'delete' ){
			return $this->Delete();
		}elseif( $act == 'getHighestBidder' ){
			return $this->getHighestBidder();
		}else{
			return $this->index();
		}
	}

	function index(){
		$act = $this->Request->getParam('act');
		if( $act == 'getHighestBidder' ){
			return $this->getHighestBidder();
		}
		$qLogin = "	
				SELECT SUM(num) as login_num ,date_d, gender 
				FROM
				(SELECT count(*) as num, user_id, DATE(date_time) as date_d , sex as gender
				FROM tbl_activity_log log
				INNER JOIN social_member sm ON sm.id=log.user_id
				WHERE action_id=1 GROUP BY user_id,date_d) as tbl_daily_user_login
				WHERE date_d BETWEEN '2012-07-12' AND DATE(NOW())
				GROUP BY date_d,gender
					";
		$qRegistrant="	SELECT registrant_num, existing_num, new_num
						FROM marlboro_connection_report_2012.rp_overall_user_daily
						WHERE date_d = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY);
						";
		//sementara pake query ini
		$qRegistrant ="
		SELECT count(*) as registrant_num 
		FROM  marlboro_connection_2012.social_member sm 
		WHERE not exists (SELECT 1 FROM marlboro_connection_report_2012.tbl_existing_user WHERE email=sm.email ) 
		AND register_id <> 0
		AND DATE(register_date) BETWEEN '2012-07-12' AND DATE(NOW())";
		$this->open(0);
			$login=$this->fetch($qLogin,1);
			$registrant=$this->fetch($qRegistrant);
		$this->close();			
		
		//login
		foreach ($login as $kLogin => $vLogin) {
			$dataLogin[$vLogin['date_d']]+=$vLogin['login_num'];
		}
		
		$this->View->assign('login',json_encode($dataLogin));
		$this->View->assign('registrant_num',$registrant['registrant_num']);
	
		return $this->View->toString("dashboard/dashboard.html");
	}

	function getHighestBidder(){
	
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
			$highestData=$this->fetch($qHighestBidder,1);
		$this->close();
			header('Content-type: application/json');
		print json_encode($highestData);exit;
	
	}
	
}