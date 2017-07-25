<?php
global $APP_PATH,$ENGINE_PATH;
include_once $APP_PATH.'marlboro/helper/codeHelper.php';
include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $APP_PATH.'marlboro/helper/badgeHelper_offline.php';
include_once $ENGINE_PATH."Utility/Mailer.php";
class badges extends App{
	var $Request;
	var $View;
	var $user;
	var $codeHelper;
	var $newsHelper;
	var $badgeHelper;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->user = $this->getUserInfo();
		$this->codeHelper = new codeHelper($this->user['register_id']);
		$this->newsHelper = new newsHelper($this->user['register_id']);
		$this->badgeHelper = new BadgeHelper('badge_api');
		$this->badgeHelper_offline = new badgeHelper_offline();
	}
	function home(){
		$act = $this->Request->getParam('act');
		if($act!='') return $this->$act();
		else return  $this->trade();
	}
	
	
	function trade(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		// echo 'masuk';
		//$have=$this->codeHelper->getUserBadge();
		$have=json_decode($this->badgeHelper->get_user_actual_badges($this->user['register_id']));
		$allBadges = json_decode($this->badgeHelper->getAllBadgeDetail());
		
		foreach($allBadges->data as $key => $val){
			$badges[$key]['id'] = $have->data[$key]->badge_id;
			$badges[$key]['total']= $have->data[$key]->total;
			$badges[$key]['image']= $val->badgeurl;
		}
		
		// print_r('<pre>');print_r($badges);exit;
			
		$this->View->assign('have',$badges);
		$this->log("page","trade");
		return $this->View->toString(APPLICATION.'/badges-trade.html');
	}
	
	function tradeList(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$want = intval($_GET['want']);  // want badge from user
		$badge = intval($_GET['badge']);  // trade with this badge
		$wantDetail = json_decode($this->badgeHelper->get_badge_detail($want));
		$withDetail = json_decode($this->badgeHelper->get_badge_detail($badge));
		
		
		$list = $this->codeHelper->getUserWantBadge($want,$badge);
		// print_r('<pre>');print_r($list);exit;
		$this->View->assign('list',$list);
		$this->View->assign('want',$want);
		$this->View->assign('badge',$badge);
		$this->View->assign('wantDetail',$wantDetail->data);
		$this->View->assign('withDetail',$withDetail->data);
		$this->View->assign('name',$this->user['name']);
		return $this->View->toString(APPLICATION.'/badges-trade-list.html');
	}
	
	//give badge to trade box action
	function submittrade(){
		$_have = intval($_POST['have']); // user badge
		$_req = intval($_POST['req']); // want badge
		
		if($_have != '' && $_req != ''){
			$submit_trade =  $this->codeHelper->submitTrade($_have,$_req);
			if($submit_trade->status==1) {
				
				$hasTrader = $this->codeHelper->getUserWantBadge($_have,$_req);
				if($hasTrader) {
					$submit_trade->hasTrader = 1;					
				}else{					
					$submit_trade->hasTrader = 0;
					$newsHell =  $this->newsHelper->tradeBox($_have,$_req);
				}
			}
		}else{
			$submit_trade =  json_encode(array('status'=>0,'message'=>'Choose your badge!'));
		}
	
		header('Content-type: application/json');
		// print_r($newsHell );exit;
		print_r(json_encode($submit_trade)) ; exit;
		
	}
	
	//trading action
	function confirmtraderequest(){
		$mine = intval($_POST['mine']);
		$your = intval($_POST['your']);
		$sellerId = $_POST['sellerId'];
		
		$res = $this->codeHelper->confirmTradeRequest($mine,$your,$sellerId);
		// print_r($res['status']);
		if($res['status']==1) $this->newsHelper->trade($mine,$your,$sellerId);
		if($res['status']==1) $this->log("trade_badges",$mine.'_'.$your.'_'.$sellerId);
		// print_r($newsa);
		// exit;
		header('Content-type: application/json');
		print_r(json_encode($res)) ; exit;
		
	}
	
	function trade_complete(){
	
			$status =  $this->Request->getParam('status');
				
			$this->View->assign('status',$status);
			return $this->View->toString(APPLICATION.'/badges-trade-success.html');
	}
	
	
	
	
	//auction 2012
	
	
	
	function auction(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
	
		$have=json_decode($this->badgeHelper_offline->getAllAuction());

		$lastestWinnerData = json_decode($this->badgeHelper_offline->getAuctionLastWinner());

		$lastestWinner['user'] = json_decode($this->badgeHelper->getProfile($lastestWinnerData->data->user_id));
		$lastestWinner['auction'] = $lastestWinnerData->data->auction_detail;
		$lastestWinner['bid'] = $lastestWinnerData->data->bid_amount;
		// print_r('<pre>');print_r($have->data->auction);exit;
		$this->View->assign('have',$have->data->auction);
		$this->View->assign('lastestWinner',$lastestWinner);

		return $this->View->toString(APPLICATION.'/badges-auction.html');
	}
	
	function auction_winner(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}

		$lastestWinnerData = json_decode($this->badgeHelper->getAuctionAllWinner());
		// print_r('<pre>');
		foreach($lastestWinnerData->data as $key => $val){

			$winner[$key]['user'] = json_decode($this->badgeHelper->getProfile_min($val->user_id));
			$winner[$key]['auction'] = $val->auction_detail;
			$winner[$key]['bid'] = $val->bid_amount;
		}
		// print_r($winner);exit;
		$lastestWinnerData = null;
		$this->View->assign('winner',$winner);
		
		return $this->View->toString(APPLICATION.'/auction_winner.html');
	}
	
	function auction_bid(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		global $APP_PATH;
		include $APP_PATH.'marlboro/helper/dateHelper.php';
		$diffDate = new dateHelper;
		
		$auction_id = $this->Request->getParam('id');
				
		$res = json_decode($this->badgeHelper->get_user_actual_badges($this->user['register_id']));
		$allBadges = json_decode($this->badgeHelper_offline->getAllBadgeDetail());
		// print_r($allBadges);exit;
		
		$badges=array();
		foreach($allBadges->data as $key => $val){
			$badges[$key] = array('id'=>$val->badgeid,'total'=>0,'description'=>$val->description,'image'=>$val->badgeurl,'badgetitle'=>$val->badgetitle);
				foreach($res->data as $k){
					if($val->badgeid == $k->badge_id){
						$badges[$key] = array('id'=>$val->badgeid,'total'=>$k->total,'description'=>$val->description,'image'=>$val->badgeurl,'badgetitle'=>$val->badgetitle,'badge_value'=>$val->badge_value);
						//$totalBadge += $k->total*$val->badge_value;
					}
					
				}
			}
		
		$auction=json_decode($this->badgeHelper_offline->getAuctionByID($auction_id));
		
		$highestUser=json_decode($this->badgeHelper->getProfile_min($auction->data->highestBid->data->user_id));
	
		foreach($allBadges->data as $key => $val){	
				
			$badgeID = $val->badgeid;
			$valueEachBadgeHighest += (intval($auction->data->highestBid->data->bid->$badgeID) * intval($val->badge_value));
		}

		$auction->data->highestBid->value_total = $valueEachBadgeHighest;
		
		$remainingTime = $diffDate->remainingTime($auction->data->auction->end_date);
		$this->View->assign('auction',$auction->data->auction);
		$this->View->assign('remaining',$remainingTime);
		$this->View->assign('highestBid',$auction->data->highestBid);
		$this->View->assign('winnerlastWeek',$auction->data->winnerlastWeek);
		$this->View->assign('highestUser',$highestUser);	
		$this->View->assign('have',$badges);
		$this->View->assign('user',$this->user);
		$this->log("page","auction");
		return $this->View->toString(APPLICATION.'/badges-auction-bid.html');
	}
	
	function submit_auction(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		//1-0,2-0,3-2,4-0,5-0,6-0,7-0,8-0,9-0,10-0
		$data['bid'] =  trim(str_replace('\\','',stripslashes(str_replace("'",'',$this->Request->getPost('bid')))));
		$data['user_id'] = $this->Request->getPost('user_id');
		$data['auction_id'] = $this->Request->getPost('auction_id');
		
		$submit_bid =$this->badgeHelper->submit_placeBid($data);
		if($submit_bid->status==1) $this->log("auction",$data['auction_id']);
		
		header('Content-type: application/json');
		print_r($submit_bid) ; exit;
	}
	
	
	function ghb001(){
		$data = false;
		$auction_id = intval($this->Request->getPost('auction_id'));
		
		if($auction_id) 
		{
			$allBadges = json_decode($this->badgeHelper_offline->getAllBadgeDetail());
		
			$auction=json_decode($this->badgeHelper_offline->getAuctionByID($auction_id));
		
			$highestUser=json_decode($this->badgeHelper->getProfile_min($auction->data->highestBid->data->user_id));
		
			foreach($allBadges->data as $key => $val){	
					
				$badgeID = $val->badgeid;
				$valueEachBadgeHighest += (intval($auction->data->highestBid->data->bid->$badgeID) * intval($val->badge_value));
			}
			
				
			$data['total'] = $valueEachBadgeHighest;
			$data['user'] = $highestUser;
		
		}
		
		header('Content-type: application/json');
		print_r(json_encode($data)) ; exit;
	}
	
	//this function not in used -------------------------------------------
	
	function ghb001WithService(){
		$data = false;
		$auction_id = intval($this->Request->getPost('auction_id'));
		if($auction_id) 
		{
			$allBadges = json_decode($this->badgeHelper->getAllBadgeDetail());
			$auction=json_decode($this->badgeHelper->getAuctionByID($auction_id));
		
			$highestUser=json_decode($this->badgeHelper->getProfile_min($auction->data->highestBid->data->user_id));
		
			foreach($allBadges->data as $key => $val){	
					
				$badgeID = $val->badgeid;
				$valueEachBadgeHighest += (intval($auction->data->highestBid->data->bid->$badgeID) * intval($val->badge_value));
			}

			
			$data['total'] = $valueEachBadgeHighest;
			$data['user'] = $highestUser;
		}
		
		header('Content-type: application/json');
		print_r(json_encode($data)) ; exit;
	}
	
	function auctionWithService(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		// echo 'masuk';
		//$have=$this->codeHelper->getUserBadge();
		$have=json_decode($this->badgeHelper->getAllAuction());
		$lastestWinnerData = json_decode($this->badgeHelper->getAuctionLastWinner());
		$lastestWinner['user'] = json_decode($this->badgeHelper->getProfile_min($lastestWinnerData->data->user_id));
		$lastestWinner['auction'] = $lastestWinnerData->data->auction_detail;
		$lastestWinner['bid'] = $lastestWinnerData->data->bid_amount;
		// print_r('<pre>');print_r($lastestWinnerData);exit;
		$this->View->assign('have',$have->data->auction);
		$this->View->assign('lastestWinner',$lastestWinner);
		
		return $this->View->toString(APPLICATION.'/badges-auction.html');
	}
	
	function auction_bidWithService(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		global $APP_PATH;
		include $APP_PATH.'marlboro/helper/dateHelper.php';
		$diffDate = new dateHelper;
		
		$auction_id = $this->Request->getParam('id');
				
		$res = json_decode($this->badgeHelper->get_user_actual_badges($this->user['register_id']));
		$allBadges = json_decode($this->badgeHelper->getAllBadgeDetail());
		// print_r($allBadges);exit;
		
		$badges=array();
		foreach($allBadges->data as $key => $val){
			$badges[$key] = array('id'=>$val->badgeid,'total'=>0,'description'=>$val->description,'image'=>$val->badgeurl,'badgetitle'=>$val->badgetitle);
				foreach($res->data as $k){
					if($val->badgeid == $k->badge_id){
						$badges[$key] = array('id'=>$val->badgeid,'total'=>$k->total,'description'=>$val->description,'image'=>$val->badgeurl,'badgetitle'=>$val->badgetitle,'badge_value'=>$val->badge_value);
						//$totalBadge += $k->total*$val->badge_value;
					}
					
				}
			}
		
		$auction=json_decode($this->badgeHelper->getAuctionByID($auction_id));
		
		$highestUser=json_decode($this->badgeHelper->getProfile_min($auction->data->highestBid->data->user_id));
	
		foreach($allBadges->data as $key => $val){	
				
			$badgeID = $val->badgeid;
			$valueEachBadgeHighest += (intval($auction->data->highestBid->data->bid->$badgeID) * intval($val->badge_value));
		}

		$auction->data->highestBid->value_total = $valueEachBadgeHighest;
		
		$remainingTime = $diffDate->remainingTime($auction->data->auction->end_date);
		$this->View->assign('auction',$auction->data->auction);
		$this->View->assign('remaining',$remainingTime);
		$this->View->assign('highestBid',$auction->data->highestBid);
		$this->View->assign('winnerlastWeek',$auction->data->winnerlastWeek);
		$this->View->assign('highestUser',$highestUser);	
		$this->View->assign('have',$badges);
		$this->View->assign('user',$this->user);
		$this->log("page","auction");
		return $this->View->toString(APPLICATION.'/badges-auction-bid.html');
	}
	//------------------------------------------------------------------------------------
	
	//end auction 2012 script -----------------
	//redeem prize
	
	
}