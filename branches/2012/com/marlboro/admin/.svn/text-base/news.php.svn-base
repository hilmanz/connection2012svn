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
			return $this->add();
			//return $this->addNew();
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
		$category = intval($this->Request->getPost('category'));
		
		if ($category==0){ //default
			$filter = "";
		} elseif($category==1) { //News
			$filter = "AND a.news_category = '1'";
		} else { //Hunting
			$filter = "AND a.news_category = '2'";
		}
		
		if ($_POST['category']==$category) {
			$qry = "SELECT count(*) total FROM social_news WHERE 1 ORDER BY news_last_update DESC;";
			$list = $this->fetch($qry);
			$total = $list['total'];
			$total_per_page = 50;
			
			$qry = "SELECT a.news_id,a.news_title,a.news_published_date,a.news_brief,b.name 
					FROM social_news a LEFT JOIN social_news_category b ON a.news_category = b.id
					WHERE 1 
					$filter
					ORDER BY a.news_last_update DESC LIMIT $start,$total_per_page;";
		}
		
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);
		$this->View->assign('_cat',$category);
		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=news&category=".$this->Request->getPost("category")));
		return $this->View->toString("marlboro/admin/news-list.html");
	}
	
	function addNew(){
		return $this->View->toString("marlboro/admin/news-new.html");
	}
	
	function add2(){
		$add = intval($this->Request->getParam('add'));
		$err = "";
		if( $add == 1){
			$_title = $this->Request->getParam(mysql_escape_string('title'));
			$_brief = $this->Request->getParam(mysql_escape_string('brief'));
			$_content = $this->Request->getParam(mysql_escape_string('content'));
			$cat = $this->Request->getParam(mysql_escape_string('category'));
			$_status = intval($this->Request->getParam('status'));
			$_text = $this->Request->getParam(mysql_escape_string('ptext'));
			
			if( $_title != '' && $_brief != '' && $_content != '' && $_text != '' ){
				$que = "INSERT 
						INTO social_news 
							(news_title,news_brief,news_content,news_status,news_plaintext,news_published_date) 
						VALUES 
							('$_title','$_brief','$_content','$_status','$_text',NOW());";
				if(!$this->query($que)){
					$err = 'Save failed';
				}else{
					sendRedirect('index.php?s=news');
					exit;
				}
			}else{
				$err = 'fill all field please!';
			}			
		} else{
			//$err = 'Save failed';
		}
		
		$category = $this->getCategoryList();
		$this->View->assign('cat',$category);
		$this->View->assign('err',$err);
		return $this->View->toString("marlboro/admin/news-new.html");
	}
	
	function add(){
		$save = $this->Request->getPost("save");
		if($save==1){
			$_title = $this->Request->getPost("title");
			$cat = $this->Request->getPost("category");
			$_brief = $this->Request->getPost("brief");
			$_content = $this->Request->getPost("content");
			$img = $this->Request->getPost("img1");
			$_status = intval($this->Request->getPost('status'));
			$_text = $this->Request->getPost("ptext");
			
			if($_FILES['img1']['name']==''){
				$q = "INSERT INTO social_news (news_title,news_brief,news_content,news_status,news_plaintext,news_published_date,news_category)
						VALUES ('$_title','$_brief','$_content','$_status','$_text',NOW(),'$cat');";
				$r = $this->query($q);
				if($r){
					return $this->View->showMessage('Success', "index.php?s=news");
				}else{
					return $this->View->showMessage('Failed, please try again later', "index.php?s=news");
				}
			} else {
				// primary image
				$img_name = $_FILES['img1']['name'];
				$img_loc = $_FILES['img1']['tmp_name'];
				$img_type = $_FILES['img1']['type'];
				$img_newname = "news_".date('YmdHis');
				
				//declare extension primary image
				if($img_type=='image/jpeg'){$ext = '.jpg';}
				if($img_type=='image/png'){$ext = '.png';}
				if($img_type=='image/gif'){$ext = '.gif';}
				
				// new file
				$newfile = $img_newname.$ext; // new file name for primary image
				$thumbfile = "thumb_".$newfile; // new file name for thumbnail image
				$folder = "../contents/news/";
				// $folder = "../../public_html/contents/news/";
				//echo $newfile;
				global $ENGINE_PATH;
				include_once $ENGINE_PATH."Utility/Thumbnail.php";	
				$thumb 	= new Thumbnail();
				if(move_uploaded_file($img_loc,$folder.$newfile)){
					$q = "INSERT INTO social_news (news_title,news_brief,news_content,news_status,news_plaintext,news_published_date,news_category,images)
					VALUES ('$_title','$_brief','$_content','$_status','$_text',NOW(),'$cat','$newfile');";
					
					$r = $this->query($q);
					if($r){
						return $this->View->showMessage('Success', "index.php?s=news");
					} else {
						$err = mysql_error();
						$err = "failed upload image! $err";
						@unlink($folder.$newfile);
						@unlink($folder.$thumbfile);
					}
				} else {
					$msg = "failed move upload file";
				}
			}
		}
		
		$category = $this->getCategoryList();
		$this->View->assign('cat',$category);
		//$this->View->assign('err',$err);
		//$this->View->assign('msg',$msg);
		return $this->View->toString("marlboro/admin/news-new.html");
	}
	
	function Edit(){
		$id = $this->Request->getParam('id');
		$status = array(array("id"=>"0","name"=>"Block"),array("id"=>"1","name"=>"Publish"));
		$this->View->assign('status',$status);
		$category = $this->getCategoryList();
		$this->View->assign('cat',$category);
		
		$qry = "SELECT * FROM social_news WHERE news_id=$id LIMIT 1;";
		
		$r = $this->fetch($qry);
		$this->View->assign("_id", $r['news_id']);
		$this->View->assign("_title", $r['news_title']);
		$this->View->assign('_cat',$r['news_category']);
		$this->View->assign("_brief", $r['news_brief']);
		$this->View->assign("_content", $r['news_content']);
		$this->View->assign("_status", $r['news_status']);
		$this->View->assign("_img", $r['images']);		
		$this->View->assign("_text", $r['news_plaintext']);
		return $this->View->toString("marlboro/admin/news-edit.html");
	}
	
	function Update(){
		$category = $this->getCategoryList();
		$this->View->assign('cat',$category);
		$update = intval($this->Request->getPost('update'));
		$id = intval($this->Request->getPost('id'));
		$err = "";
		if( $update == 1){
			$_id = intval($this->Request->getPost('id'));
			$_cat = intval($this->Request->getPost('category'));
			$_title = $this->Request->getPost(mysql_escape_string('title'));
			$_brief = $this->Request->getPost(mysql_escape_string('brief'));
			$_content = $this->Request->getPost(mysql_escape_string('content'));
			$_status = intval($this->Request->getPost('status'));
			$_text = $this->Request->getPost(mysql_escape_string('ptext'));
			$img = $this->Request->getPost('currimg');
			if( $_title != '' && $_brief != '' && $_content != '' && $_text != '' ){
				if($_FILES['img1']['name']!=""){
					$img_name = $_FILES['img1']['name'];
					$img_loc = $_FILES['img1']['tmp_name'];
					$img_type = $_FILES['img1']['type'];
					$img_newname = "news_".date('YmdHis');
					
					//declare extension primary image
					if($img_type=='image/jpeg'){$ext = '.jpg';}
					if($img_type=='image/png'){$ext = '.png';}
					if($img_type=='image/gif'){$ext = '.gif';}
					
					// new file
					$newfile = $img_newname.$ext; // new file name for primary image
					$thumbfile = "thumb_".$newfile; // new file name for thumbnail image
					$folder = "../contents/news/";
					// $folder = "../../public_html/contents/news/";
					global $ENGINE_PATH;
					include_once $ENGINE_PATH."Utility/Thumbnail.php";	
					$thumb 	= new Thumbnail();
					//move_uploaded_file($img_loc,$folder,$newfile);
					$qImg = '';
					if(move_uploaded_file($_FILES['img1']['tmp_name'],"../../public_html/contents/news/".$newfile))	$qImg= ",images = '$newfile'" ;					
				}else{
					$newfile = $img;
				}
				$que = "UPDATE social_news
						SET
							news_title='$_title',
							news_brief='$_brief',	
							news_content='$_content',
							news_status='$_status',
							news_plaintext='$_text', 
							news_category='$_cat',
							news_last_update=NOW()
							{$qImg}
						WHERE news_id=$id";
				
				if(!$this->query($que)){
					$err = 'Update failed';
					//return $this->View->showMessage($err,"index.php?s=news&act=edit&id=$id");
					return $this->View->showMessage($err, "index.php?s=news&act=edit&id=$id");
				}else{
					//sendRedirect('index.php?s=news');
					//exit;
					$err = 'Update Berhasil';
					//return $this->View->showMessage($err,"index.php?s=news&act=edit&id=$id");
					return $this->View->showMessage($err, "index.php?s=news");
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
	
	function getCategoryList(){
		$category = $this->fetch("SELECT * FROM social_news_category",1);
		return $category;
	}
	
}