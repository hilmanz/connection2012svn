<?php
global $ENGINE_PATH;
include_once $ENGINE_PATH."Utility/Paginate.php";
class badge extends SQLData{
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
		$classOfBadge = intval($this->Request->getParam('classOfBadge'));
		if($classOfBadge=='') $classOfBadge=1;
		$qry = "SELECT count(*) total FROM ".SCHEMA_CODE.".badge_catalog WHERE 1 ORDER BY name;";
		$list = $this->fetch($qry);
		$total = $list['total'];
		$total_per_page = 50;
		$qry = "SELECT c.*, badgeRef.type as tier,s.name as series,badgeRef.prob_rate as prob_rate
				FROM ".SCHEMA_CODE.".badge_catalog c
					INNER JOIN ".SCHEMA_CODE.".badge_series s ON c.series_type = s.id
					INNER JOIN ".SCHEMA_CODE.".badge_catalog_type_reference badgeRef ON c.id=badgeRef.id
				WHERE 
					badgeRef.type = {$classOfBadge}
				ORDER BY c.CodeOrder ASC LIMIT {$start},{$total_per_page};";
		
		$list = $this->fetch($qry,1);
	
		$this->View->assign('list',$list);
		$this->View->assign('classOfBadge',$classOfBadge);

		$this->Paging = new Paginate();
		$this->View->assign("paging",$this->Paging->getAdminPaging($start, $total_per_page, $total, "?s=badge"));
		
		return $this->View->toString("marlboro/admin/badge-list.html");
	}
	
	function addNew(){

		$q = "SELECT * FROM ".SCHEMA_CODE.".badge_series ORDER BY id";
		$series = $this->fetch($q,1);
		//print'<pre>';print_r($series);exit;
		$this->View->assign('series',$series);
		return $this->View->toString("marlboro/admin/badge-new.html");
	}
	
	function add(){
		$q = "SELECT * FROM ".SCHEMA_CODE.".badge_series ORDER BY id";
		$series = $this->fetch($q,1);
		//print'<pre>';print_r($series);exit;
		$this->View->assign('series',$series);
		$add = intval($this->Request->getPost('add'));
		$err = "";
		if( $add == 1){
			$name = $this->Request->getPost(mysql_escape_string('name'));
			$rate = $this->Request->getPost('rate');
			$tier = $this->Request->getPost('tier');			
			$series = intval($this->Request->getPost('series'));
			$description = $this->Request->getPost('description');
			if( $name != '' || $rate != '' || $tier != '' || $series != '' || $_FILES['img']['name']!="" ){
				$nname = rand(1,100000)."_".$_FILES['img']['name'];
				move_uploaded_file($_FILES['img']['tmp_name'], "../../public_html/img/badge/".$nname);
				$que = "INSERT IGNORE 
						INTO ".SCHEMA_CODE.".badge_catalog 
							(name,prob_rate,tier,series_type,image,description) 
						VALUES 
							('$name','$rate','$tier','$series','$nname','$description');";
				if(!$this->query($que)){
					$err = 'Save failed';
				}else{
					$newIdBadge = 	mysql_insert_id();
					$que = "INSERT IGNORE 
							INTO ".SCHEMA_CODE.".badge_catalog_type_reference 
								(prob_rate,type,id) 
							VALUES 
								('$rate','$tier','$newIdBadge');";
					if($this->query($que)){
					sendRedirect('index.php?s=badge');
					exit;
					}else $err = 'Save of Class Badge failed';
				}
			}else{
				$err = 'fill all field please!';
			}			
			
		}else{
			$err = 'Save failed';
		}
		$this->View->assign('err',$err);
		return $this->View->toString("marlboro/admin/badge-new.html");
	}
	
	function Edit(){
		$q = "SELECT * FROM ".SCHEMA_CODE.".badge_series ORDER BY id";
		$series = $this->fetch($q,1);
		// print'<pre>';print_r($series);exit;
		$this->View->assign('series',$series);
		$id = $this->Request->getParam('id');
		$classOfBadge = $this->Request->getParam('classOfBadge');
		$qry = "
		SELECT badgeMaster.*, badgeRef.type as tier,badgeRef.prob_rate as prob_rate
		FROM ".SCHEMA_CODE.".badge_catalog badgeMaster
		INNER JOIN ".SCHEMA_CODE.".badge_catalog_type_reference badgeRef ON badgeMaster.id=badgeRef.id
		WHERE 
			badgeRef.type = {$classOfBadge}
			AND badgeMaster.id={$id} LIMIT 1
		";
		// print_r($qry);exit;
		$r = $this->fetch($qry);
		if($r){
			 $tierValue="A";
			if($r['tier']==2) $tierValue="B";
			if($r['tier']==3) $tierValue="C";
		$this->View->assign("tierValue", $tierValue);
			foreach($r as $key => $val){
			$this->View->assign($key, $val);	
			}
		
		}
		return $this->View->toString("marlboro/admin/badge-edit.html");
	}
	
	function Update(){
		$q = "SELECT * FROM ".SCHEMA_CODE.".badge_series ORDER BY id";
		$series = $this->fetch($q,1);
		//print'<pre>';print_r($series);exit;
		$this->View->assign('series',$series);
		$update = intval($this->Request->getPost('update'));
		$id = intval($this->Request->getPost('id'));
		$err = "";
		if( $update == 1){
			$name = $this->Request->getPost(mysql_escape_string('name'));
			$prob_rate = $this->Request->getPost('rate');
			$tier = $this->Request->getPost('tier');			
			$serie = intval($this->Request->getPost('series'));
			$img = $this->Request->getPost('currimg');	
			$description = $this->Request->getPost('description');
			if( $name != '' || $rate != '' || $tier != '' || $series != '' ){
				if($_FILES['img']['name']!=""){
					$nname = rand(1,100000)."_".$_FILES['img']['name'];
					move_uploaded_file($_FILES['img']['tmp_name'], "../../public_html/img/badge/".$nname);
				}else{
					$nname = $img;
				}
				$que = "UPDATE 
							".SCHEMA_CODE.".badge_catalog
						SET
							name='$name',
							prob_rate='$prob_rate',	
							tier='$tier',
							series_type='$serie',
							image='$nname' 
						WHERE id=$id";
				if(!$this->query($que)){
					// @unlink("../../public_html/img/badge/".$nname);
					//echo mysql_error();exit;
					$err = 'Update failed';
				}else{
					$que = "
						UPDATE 
							".SCHEMA_CODE.".badge_catalog_type_reference
						SET
							prob_rate='$prob_rate'								
						WHERE id=$id AND type='$tier'";
					if($this->query($que)){
						// @unlink("../../public_html/img/badge/".$img);
						// $err = $que;
						// exit;
						sendRedirect('index.php?s=badge');
						exit;
					}else {
						$err = 'Update Class of Badge failed';
					}
				}
			}else{
				$err = 'fill all field please!';
				return $this->View->showMessage($err,"index.php?s=badge&act=edit&id=$id");
			}		
		}else{
			$err = 'Update failed';
		}
		$this->View->assign("id", $id);
		$this->View->assign("name", $name);	
		$this->View->assign("prob_rate", $prob_rate);	
		$this->View->assign("tier", $tier);		
		$this->View->assign("serie", $serie);		
		$this->View->assign("img", $img);
		$this->View->assign('err',$err);
		return $this->View->toString("marlboro/admin/badge-edit.html");
	}
	
	function Delete(){
		$id = $this->Request->getParam('id');
		$classOfBadge = $this->Request->getParam('classOfBadge');
		$qry = "DELETE FROM ".SCHEMA_CODE.".badge_catalog WHERE id=$id;";
		if(!$this->query($qry)){
			$err = 'Delete failed';
		}else{
			$qry = "DELETE FROM ".SCHEMA_CODE.".badge_catalog_type_reference WHERE id=$id AND type={$classOfBadge};";
			if($this->query($qry)){
			sendRedirect('index.php?s=badge');
			exit;
			}else $err = 'Delete of class badge type failed';
		}
	}
	
}