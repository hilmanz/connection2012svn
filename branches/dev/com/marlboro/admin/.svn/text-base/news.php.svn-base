<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class news extends SQLData{
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
		}else{
			return $this->listing();
		}
	}

	function listing(){
		
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
		
		return $this->View->toString("marlboro/admin/news-list.html");
	}
	
	function addNew(){
		return $this->View->toString("marlboro/admin/news-new.html");
	}
	
	function add(){
		$add = intval($this->Request->getParam('add'));
		$err = "";
		if( $add == 1){
			$_title = $this->Request->getParam(mysql_escape_string('title'));
			$_brief = $this->Request->getParam(mysql_escape_string('brief'));
			$_content = $this->Request->getParam(mysql_escape_string('content'));			
			$_status = intval($this->Request->getParam('status'));
			$_text = $this->Request->getParam(mysql_escape_string('ptext'));	 		
			if( $_title != '' && $_brief != '' && $_content != '' && $_text != '' ){
				$que = "INSERT 
						INTO social_news 
							(news_title,news_brief,news_content,news_status,news_plaintext,news_published_date) 
						VALUES 
							('$_title)','$_brief','$_content)','$_status','$_text',NOW());";
				if(!$this->query($que)){
					$err = 'Save failed';
				}else{
					sendRedirect('index.php?s=news');
					exit;
				}
			}else{
				$err = 'fill all field please!';
			}			
			
		}else{
			$err = 'Save failed';
		}
		$this->View->assign('err',$err);
		return $this->View->toString("marlboro/admin/news-new.html");
	}
	
	function Edit(){
		$id = $this->Request->getParam('id');
		$_id = $this->Request->getParam('id');
		$qry = "SELECT * FROM social_news WHERE news_id=$id LIMIT 1;";
		$r = $this->fetch($qry);
		$this->View->assign("_id", $r['news_id']);
		$this->View->assign("_title", $r['news_title']);	
		$this->View->assign("_brief", $r['news_brief']);	
		$this->View->assign("_content", $r['news_content']);		
		$this->View->assign("_status", $r['news_status']);		
		$this->View->assign("_text", $r['news_plaintext']);	
		return $this->View->toString("marlboro/admin/news-edit.html");
	}
	
	function Update(){
		$update = intval($this->Request->getParam('update'));
		$_id = $this->Request->getParam('id');	
		$err = "";
		if( $update == 1){
			$_id = $this->Request->getParam('id');
			$_title = $this->Request->getParam(mysql_escape_string('title'));
			$_brief = $this->Request->getParam(mysql_escape_string('brief'));
			$_content = $this->Request->getParam(mysql_escape_string('content'));			
			$_status = intval($this->Request->getParam('status'));
			$_text = $this->Request->getParam(mysql_escape_string('ptext'));
			if( $_title != '' && $_brief != '' && $_content != '' && $_text != '' ){
				$que = "UPDATE 
							social_news
						SET
							news_title='$_title',
							news_brief='$_brief',	
							news_content='$_content',
							news_status='$_status',
							news_plaintext='$_text' 
						WHERE news_id=$_id";
				if(!$this->query($que)){
					//echo mysql_error();exit;
					$err = 'Update failed';
				}else{
					sendRedirect('index.php?s=news');
					exit;
				}
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err,"index.php?s=news&act=edit&id=$_id");
			}		
		}else{
			$err = 'Update failed';
		}
		$this->View->assign('err',$err);
		return $this->View->toString("marlboro/admin/news-edit.html");
	}
	
	function Delete(){
		$id = $this->Request->getParam('id');
		$qry = "DELETE FROM social_news WHERE news_id=$id;";
		if(!$this->query($qry)){
			$err = 'Delete failed';
		}else{
			sendRedirect('index.php?s=news');
			exit;
		}
	}
	
}