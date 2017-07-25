<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class user extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'detail' ){
			return $this->userDetail();
		}elseif( $act == 'listTracking' ){
			return $this->ListTracking();
		}elseif( $act == 'deleteAvatar' ){
			return $this->deleteAvatar();
		}elseif( $act == 'trace' ){
			return $this->trace();
		}else{
			return $this->userList();
		}
	}
	
	function trace(){
		if($this->Request->getParam("search")=="1"){
			$sql = "SELECT * FROM social_member 
					WHERE name LIKE '%".$this->Request->getParam("name")."%' 
					OR 
					last_name LIKE '%".$this->Request->getParam("name")."%'";
			$search_result = $this->fetch($sql,1);
			if(sizeof($search_result)==0){
				$this->View->assign("msg","No matched user..");
			}
			foreach($search_result as $n=>$v){
				if($search_result[$n]['last_name']=="Array"){
					$search_result[$n]['last_name'] = "";
				} 
			}
			$this->View->assign("search_result",$search_result);
		} elseif ($this->Request->getParam("show")=="1"){
			$user_id = $this->Request->getParam("user_id");
			$sql = "
				SELECT a.user_id,a.redeem_time,a.kode,d.name as badge_name 
				FROM ".SCHEMA_CODE.".badge_redeem a
				INNER JOIN ".SCHEMA_CODE.".badge_code b ON a.kode = b.kode
				INNER JOIN ".SCHEMA_CODE.".badge_inventory c ON a.id = c.redeem_id
				INNER JOIN ".SCHEMA_CODE.".badge_catalog d ON c.badge_id = d.id
				WHERE a.user_id=".intval($user_id);
			$redeem = $this->fetch($sql,1);
			$this->View->assign("redeem",$redeem);
			
			$sql = "
				SELECT a.*,b.*,d.name as badge_name 
				FROM ".SCHEMA_CODE.".badge_redeem a
				INNER JOIN marlboro_connection_2012.ipad_code_quiz b ON a.kode = b.codeid
				INNER JOIN ".SCHEMA_CODE.".badge_inventory c ON a.id = c.redeem_id
				INNER JOIN ".SCHEMA_CODE.".badge_catalog d ON c.badge_id = d.id
				WHERE a.user_id=".intval($user_id);
			$quiz = $this->fetch($sql,1);
			$this->View->assign("quiz",$quiz);
			
			$sql = "
				SELECT a.*,b.*,d.name as badge_name 
				FROM ".SCHEMA_CODE.".badge_redeem a
				INNER JOIN marlboro_connection_2012.ipad_code_registration b ON a.kode = b.codeid
				INNER JOIN ".SCHEMA_CODE.".badge_inventory c ON a.id = c.redeem_id
				INNER JOIN ".SCHEMA_CODE.".badge_catalog d ON c.badge_id = d.id
				WHERE a.user_id=".intval($user_id);
			$registration = $this->fetch($sql,1);
			$this->View->assign("registration",$registration);
			
			//game
			$sql = "
				SELECT a.user_id,a.redeem_time,a.kode,d.name as badge_name 
				FROM ".SCHEMA_CODE.".badge_redeem a
				INNER JOIN ".SCHEMA_CODE.".badge_inventory c ON a.id = c.redeem_id
				INNER JOIN ".SCHEMA_CODE.".badge_catalog d ON c.badge_id = d.id
				WHERE a.user_id=".intval($user_id)." AND a.kode IN ('GAMEBADGE','REGFREE','berlin1','berlin_2','yacht','ny1')";
			$game = $this->fetch($sql,1);
			$this->View->assign("game",$game);			
			$this->View->assign("show",1);
		}
		return $this->View->toString("marlboro/admin/user_trace.html");
	}
	
	function deleteAvatar(){
		$id = intval($this->Request->getParam('id'));
		$qry = "SELECT img,sex FROM social_member WHERE id=$id;";
		$rs = $this->fetch($qry);
		//echo $rs['img'];exit;
		if( $rs['img'] != '' && $rs['img'] != 'images/avatar-man.jpg' && $rs['img'] != 'images/avatar-woman.jpg'){
			@unlink("../".$rs['img']);
			$newImg = ($rs['sex'] == 'M')? 'images/avatar-man.jpg' : 'images/avatar-woman.jpg';
			$qry = "UPDATE social_member SET img='$newImg' WHERE id=$id;";
			if($this->query($qry)){
				return $this->View->showMessage('Remove Avatar Success!','index.php?s=user');
			}else{
				return $this->View->showMessage('Remove Avatar Failed, Please Try Again!','index.php?s=user');
			}
		}else{
			return $this->View->showMessage('Remove Avatar Failed, Please Try Again!','index.php?s=user');
		}		
	}

	function userList(){
		$channel = intval($this->Request->getParam('channel'));
		$where_channel = ($channel == 0) ? '' : " AND channel='$channel' ";
		
		$start = intval($this->Request->getParam('st'));
		$qry = "SELECT count(*) total FROM social_member WHERE 1 ORDER BY NAME;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT * FROM social_member WHERE 1 ORDER BY NAME LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=user"));		
		return $this->View->toString("marlboro/admin/user-list.html");
	}
	
	function userDetail(){
		$id = intval($this->Request->getParam('id'));
		$qry = "SELECT * FROM ".SCHEMA_CODE.".badge_inventory WHERE user_id='$id';";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		return $this->View->toString("marlboro/admin/user-detail.html");
	}
	
	function ListTracking(){
		$id = intval($this->Request->getParam('id'));
		$nm = $this->Request->getParam('nm');
		$start = intval($this->Request->getParam('st'));
		$qry = "SELECT COUNT(*) AS total 
				FROM (	SELECT 
							a.name AS name,
							b.user_id AS userID,
							b.page AS page,
							b.time AS time
						FROM social_member a
						INNER JOIN social_tracking b
						ON b.user_id='$id' AND a.name='$nm'
						ORDER BY b.time 
					 ) 
				AS track ;";
		//echo $qry."<br>";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT 
					a.name AS name,
					b.user_id AS userID,
					b.page AS page,
					b.time AS time
				FROM social_member a
				INNER JOIN social_tracking b
				ON b.user_id='$id' AND a.name='$nm'
				ORDER BY b.time LIMIT $start,$total_per_page; ";
		//echo "<br>".$qry;
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		$this->View->assign('nm',$nm);
				
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=user&act=listTracking&id=$id&nm=$nm"));		
		return $this->View->toString("marlboro/admin/user-listtracking.html");
	}
	
}