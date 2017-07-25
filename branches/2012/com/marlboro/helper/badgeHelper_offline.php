<?php
include_once "../engines/Gummy.php";
include_once "../engines/functions.php";
include_once '../com/Application.php';

class badgeHelper_offline extends Application{
	
	function getAllAuction(){
		
		//get auction
	
		$sql = "
		SELECT * 
		FROM ".SCHEMA_CONNECTION.".social_auction 
		WHERE 
		n_status = 1 
		AND start_date <= NOW() 
		AND end_date >= NOW()";
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();
		
		return json_encode(array('data'=> array('auction'=>$rs)));
		
	}
	
	function getAuctionLastWinner($otherRegid){
	
			$sql = "
			SELECT user_id,auction_id, bid_amount
			FROM ".SCHEMA_CONNECTION.".social_auction_history 
			WHERE 
			n_status = 1 
			ORDER BY date_time DESC LIMIT 1
			";
	
		$this->open(0);
		$lastWinner = $this->fetch($sql);
		$this->close();	
		
		$auctionDetail =$this->getAuctionByIDForProfile($lastWinner['auction_id']);
		$lastWinner['auction_detail'] = $auctionDetail['auction'];
			
		return json_encode(array('data'=> $lastWinner));
	}
	
	function getAuctionByIDForProfile($auctionid=NULL){
		
		$auction_id=intval($auctionid);
	
		if($auction_id!=''){
	
		$sql = "
		SELECT * 
		FROM ".SCHEMA_CONNECTION.".social_auction 
		WHERE id={$auction_id} 
		LIMIT 1";
		
		$this->open(0);
		$auction = $this->fetch($sql);
		$this->close();	
		
		$data['auction'] = $auction;
		return $data;
		}else return false;
	
	}
	
	
	function getAllBadgeDetail(){
		$sql = "SELECT id as badgeid,name as badgetitle,image as badgeurl,description, badge_value
				FROM ".SCHEMA_CODE.".badge_catalog ORDER BY CodeOrder ASC
				";
		
		
		$this->open(0);
		$rs = $this->fetch($sql,1);
		$this->close();	
		
		if($rs!=null){
			return json_encode(array('data'=> $rs));
		}else{
			return json_encode(array('data'=> null));
		}
	}
	
	function getAuctionByID($auctionid=NULL){
		$auction_id=intval($auctionid);
	
		if($auction_id!=''){
		//get auction
	
		$sql = "
		SELECT * 
		FROM ".SCHEMA_CONNECTION.".social_auction 
		WHERE n_status = 1 
		AND start_date <= NOW() 
		AND end_date >= NOW() 
		AND id={$auction_id} 
		LIMIT 1";
		$this->open(0);
		$auction = $this->fetch($sql);
		$this->close();	
		
		$highestBid = $this->getHighestBid($auction_id);
		
		$data['highestBid'] = $highestBid;
		$data['auction'] = $auction;
		return json_encode(array('data'=> $data));
		}else return json_encode(array('data'=> NULL));
	
	}
	
	function getHighestBid($auction_id=null){
		if($auction_id==null) return false;
		
		//check user id highest bid, multiple with badge value on catalog
		$sql = "
		SELECT SUM(amount*badge_value) as totalAmount,user_id
		FROM ".SCHEMA_CONNECTION.".social_auction_bid aucbid
		INNER JOIN ".SCHEMA_CODE.".badge_catalog as badge ON badge.id=aucbid.badge_id
		WHERE 
			n_status = 1 
			AND amount <> 0
			AND auction_id={$auction_id}
		GROUP BY user_id 
		ORDER BY totalAmount DESC LIMIT 1
		";
	
		$this->open(0);
		$q = mysql_query($sql);
		$highestUserID = mysql_fetch_object($q);	
		$user_id = $highestUserID->user_id;
	
		
		$sql = "
		SELECT sum(amount)as amount , badge_id ,user_id
		FROM ".SCHEMA_CONNECTION.".social_auction_bid 
		WHERE 
		n_status = 1 
		AND auction_id={$auction_id} 
		AND user_id={$user_id}
		GROUP BY user_id,badge_id
		";
		
		$q = mysql_query($sql);
		
		//if found
		if($q){
			//check auction bid from this person
			while($row = mysql_fetch_object($q)){
				$highestBid['bid'][$row->badge_id] =$row->amount;
				$highestBid['user_id'] = $row->user_id;
				$minimalAmount +=$row->amount;
			}
		}else{
		//if not found
			//check minimal bid from this auction
				$sql = "SELECT minimal_bid FROM ".SCHEMA_CONNECTION.".social_auction WHERE n_status = 1 AND id={$auction_id}";
				$q = mysql_query($sql);
				$highestBid = mysql_fetch_object($q);
				$minimalAmount =  $highestBid->minimal_bid;
		}		
		mysql_free_result($q);
		$this->close();	
		$data['total'] = $minimalAmount;
		$data['data'] = $highestBid;
		return $data;
	}
	
	
		
	
	
}