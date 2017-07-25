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
			return $this->index();
		}
	}

	function mobile(){
		return $this->View->toString("dashboard/mobile.html");
	}

	
	
}