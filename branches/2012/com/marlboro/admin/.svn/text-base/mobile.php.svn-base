<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class mobile extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
		$act = $this->Request->getParam('act');
		if( $act == 'quiz' ){
			return $this->codeQuizList();
		}else{
			return $this->codeRegList();
		}
	}
	
	function codeRegList(){
		$start = intval($this->Request->getParam('st'));
		$qry = "SELECT count(*) total FROM ipad_code_registration WHERE 1 ORDER BY id;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT * FROM ipad_code_registration WHERE 1 ORDER BY id LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=mobile"));
		return $this->View->toString("marlboro/admin/mobile-registrations.html");
	}
	
	function codeQuizList(){
		$start = intval($this->Request->getParam('st'));
		$qry = "SELECT count(*) total FROM ipad_code_quiz WHERE 1 ORDER BY id;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT * FROM ipad_code_quiz WHERE 1 ORDER BY id LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		$this->View->assign('filter','quiz');
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=mobile&act=quiz"));
		return $this->View->toString("marlboro/admin/mobile-registrations.html");
	}

	function listing(){
		
		/*
		$start = intval($this->Request->getParam('st'));
		$qry = "SELECT count(*) total FROM social_news WHERE 1 ORDER BY news_published_date;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		
		$qry = "SELECT * FROM social_news WHERE 1 ORDER BY news_published_date LIMIT $start,$total_per_page;";
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=news"));
		*/
		
		$start = intval($this->Request->getParam('st'));
		$total = 150;
		$total_per_page = 50;
		
		$list = array(
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss'),
								array('date' => '2011-10-01','code'=>'HJSE98U','name'=>'Moss')
							);
		
		$this->View->assign('list',$list);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=mobile"));
		
		return $this->View->toString("marlboro/admin/mobile-registrations.html");
	}
}