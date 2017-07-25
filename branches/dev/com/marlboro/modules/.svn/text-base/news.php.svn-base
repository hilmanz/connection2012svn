<?php
class news extends App{
	var $Request;
	var $View;
	var $user;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->user = $this->getUserInfo();
	}
	function home(){
		$que = "SELECT * FROM social_news WHERE news_status='1' ORDER BY news_published_date DESC;";
		$this->open(0);
		$rs = $this->fetch($que,1);
		$this->close();
		
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$date = $this->ago(strtotime($rs[$i]['news_published_date']));
			$rs[$i]['time'] = $date;
		}
		
		$this->View->assign('list',$rs);
		return $this->View->toString(APPLICATION.'/news.html');
	}
	function trade(){
		//$que="SELECT * FROM social_tradenews ORDER BY tradenews_date DESC";
		$que="SELECT n.*,m.img,m.sex FROM social_tradenews n LEFT JOIN social_member m ON n.user_id=m.register_id ORDER BY n.tradenews_date DESC LIMIT 25";
		$this->open(0);
		$rs=$this->fetch($que,1);
		$this->close();
		
		$num = count($rs);
		for($i=0;$i<$num;$i++){
			$date = $this->ago(strtotime($rs[$i]['tradenews_date']));
			$rs[$i]['time'] = $date;
			
			//fix image avatar
			//echo $rs[$i]['img'];
			$img = $rs[$i]['img'];
			if(!file_exists($img)){
				if( strtolower($rs[$i]['sex']) == 'm' ){
					$img = 'images/avatar-man.jpg';
				}else{
					$img = 'images/avatar-woman.jpg';
				}
			}
			$rs[$i]['img'] = $img;
		}
		//exit;
		
		$this->View->assign('last_id',$rs[0]['tradenews_id']);
		$this->View->assign('list',$rs);
		return $this->View->toString(APPLICATION.'/news-trade.html');
	}
	
	function getlasttrade(){
		$last_id = intval($_POST['id']);
		//$que="SELECT * FROM social_tradenews WHERE tradenews_id > $last_id ORDER BY tradenews_date DESC";
		$que="SELECT n.*,m.img FROM social_tradenews n LEFT JOIN social_member m ON n.user_id=m.register_id WHERE n.tradenews_id > $last_id ORDER BY n.tradenews_date DESC";
		$this->open(0);
		$rs=$this->fetch($que,1);
		$this->close();
		$num = count($rs);
		
		if($num > 0){
			for($i=0;$i<$num;$i++){
				$date = $this->ago(strtotime($rs[$i]['tradenews_date']));
				$rs[$i]['time'] = $date;
			}
			
			$data['status'] = 1;
			$data['data'] = $rs;
			$data['last_id'] = $rs[0]['tradenews_id'];
			
		}else{
			$data['status'] = 0;
		}
		//$this->View->assign('last_id',$rs[0]['tradenews_id']);
		//$this->View->assign('list',$rs);
		//return $this->View->toString(APPLICATION.'/news-trade.html');
		
		echo json_encode($data);
		exit;
	}
	
	function ago($time)
	{
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");

	   $now = time();

		   $difference     = $now - $time;
		   $tense         = "ago";

	   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		   $difference /= $lengths[$j];
	   }

	   $difference = round($difference);

	   if($difference != 1) {
		   $periods[$j].= "s";
	   }
		
		if($j > 2){
			return date("d/m", $time);
		}else{
			return "$difference $periods[$j] ago ";
		}
	}
	
	function getbadgeinfo(){
		$badgeid = intval($_POST['id']);
		$qry = "SELECT * FROM marlboro_code.badge_catalog WHERE id=$badgeid;";
		$this->open(0);
		$rs = $this->fetch($qry);
		$this->close();
		
		if($rs['id'] > 0){
			$data['status'] = 1;
			$data['data'] = $rs;
		}else{
			$data['status'] = 0;
		}
		
		echo json_encode($data);
		exit;
	}
}
