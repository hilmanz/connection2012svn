<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class auction extends SQLData{
	function __construct($req){
		parent::SQLData();
		$this->Request = $req;
		$this->View = new BasicView();
		$this->User = new UserManager();
	}
	function admin(){
	
		$act = $this->Request->getParam('act');
		if($act!='') return $this->$act();
		else return $this->listing();
		
	}

	function listing(){
		//	id 	item_name 	img 	type_auction 	minimal_bid 	start_date 	end_date 	n_status
		$qry = "
				SELECT *
				FROM ".SCHEMA_CONNECTION.".social_auction WHERE n_status='1'
				ORDER BY start_date DESC ";		
		$list = $this->fetch($qry,1);
		$this->View->assign('list',$list);		
		return $this->View->toString("marlboro/admin/auction-list.html");
	}
	
	function newAuction(){
		// $this->View->assign('msg',"AUCTION ITEMS is Maintenance ");
		// $this->View->assign('url',"?s=auction");
		// return $this->View->toString("marlboro/admin/backendMessage.html");
		
		return $this->View->toString("marlboro/admin/auction-new.html");
	}
	
	function add(){
		$add = intval($this->Request->getPost('add'));
		$err = "";
		if( $add == 1){
			$item_name = $this->Request->getPost(mysql_escape_string('item_name'));
			$start_date = $this->Request->getPost('start_date');
			$end_date = $this->Request->getPost('end_date');
			$description = $this->Request->getPost('description');
			if( $item_name != '' || $start_date != '' || $end_date != '' || $_FILES['img']['name']!="" ){
				$nname = rand(1,100000)."_".$_FILES['img']['name'];
				move_uploaded_file($_FILES['img']['tmp_name'], "../../public_html/img/auction/".$nname);
				$que = "INSERT IGNORE 
						INTO ".SCHEMA_CONNECTION.".social_auction 
							(item_name,description,start_date,end_date,img) 
						VALUES 
							('$item_name','$description','$start_date','$end_date','$nname');";
				if(!$this->query($que)){
					$err = 'Save failed';
				}else{
						$this->View->assign('msg',"SUCCESS TO INSERT AUCTION ITEMS");
						$this->View->assign('url',"?s=auction");
						return $this->View->toString("marlboro/admin/backendMessage.html");
				
				}
			}else{
				$err = 'fill all field please!';
			}			
			
		}else{
			$err = 'Save failed';
		}
		$this->View->assign('err',$err);
		return $this->View->toString("marlboro/admin/auction-new.html");
	}
	
	function edit(){
		//	id 	item_name 	img 	type_auction 	minimal_bid 	start_date 	end_date 	n_status
		$id = $this->Request->getParam('id');
	
		$qry = "
				SELECT *
				FROM ".SCHEMA_CONNECTION.".social_auction
				WHERE  n_status = 1 AND id={$id}
				LIMIT 1";
		// print_r($qry);exit;
		$result = $this->fetch($qry);
		if($result){
			foreach($result as $key => $val){
			$this->View->assign($key, $val);	
			}
		
		}
		return $this->View->toString("marlboro/admin/auction-edit.html");
	}
	
	function Update(){		
		$update = intval($this->Request->getPost('update'));
		$id = intval($this->Request->getPost('id'));
		$err = "";
		if( $update == 1){
			$item_name = $this->Request->getPost(mysql_escape_string('item_name'));
			$start_date = $this->Request->getPost('start_date');
			$end_date = $this->Request->getPost('end_date');			
			$img = $this->Request->getPost('currimg');
			$description = $this->Request->getPost('description');
			if( $item_name != '' || $start_date != '' || $end_date != '' || $description != ''){
				if($_FILES['img']['name']!=""){
					$nname = rand(1,100000)."_".$_FILES['img']['name'];
					move_uploaded_file($_FILES['img']['tmp_name'], "../../public_html/img/auction/".$nname);
				}else{
					$nname = $img;
				}
				$que = "UPDATE 
							".SCHEMA_CONNECTION.".social_auction
						SET
							item_name='$item_name',
							description='$description',
							start_date='$start_date',	
							end_date='$end_date',
							img='$nname' 
						WHERE id=$id";
				if(!$this->query($que)){
					// @unlink("../../public_html/img/badge/".$nname);
					//echo mysql_error();exit;
					$err = 'Update failed';
				}else{
						// sendRedirect('index.php?s=auction');
						$this->View->assign('msg',"SUCCESS TO UPDATE AUCTION ITEMS");
						$this->View->assign('url',"?s=auction");
						return $this->View->toString("marlboro/admin/backendMessage.html");
				}
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err,"index.php?s=auction&act=edit&id=$id");
			}		
		}else{
			$err = 'Update failed';
		}
		$this->View->assign("id", $id);
		$this->View->assign("item_name", $item_name);	
		$this->View->assign("start_date", $start_date);	
		$this->View->assign("end_date", $end_date);		
		$this->View->assign("img", $img);
		$this->View->assign('err',$err);
		return $this->View->toString("marlboro/admin/auction-edit.html");
	}
	
	function Delete(){
		$id = $this->Request->getParam('id');
		//$classOfBadge = $this->Request->getParam('classOfBadge');
		//$qry = "DELETE FROM ".SCHEMA_CODE.".badge_catalog WHERE id=$id;";
		$qry = "UPDATE ".SCHEMA_CONNECTION.".social_auction SET n_status='0' WHERE id=$id;";
		if(!$this->query($qry)){
			$err = 'Delete failed';
		}else{
			/* $qry = "DELETE FROM ".SCHEMA_CODE.".badge_catalog_type_reference WHERE id=$id AND type={$classOfBadge};";
			if($this->query($qry)){
			sendRedirect('index.php?s=badge');
			exit;
			}else $err = 'Delete of class badge type failed'; */
			sendRedirect('index.php?s=auction');
			exit;
		}
	}
	
}