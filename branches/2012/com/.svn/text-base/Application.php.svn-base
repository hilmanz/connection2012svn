<?php
include_once $ENGINE_PATH."Utility/Paginate.php";
/**
 * 
 * Enter description here ...
 * @author duf
 *
 */
class Application extends SQLData{
	var $Request;
	var $View;
	var $_mainLayout="";
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
	}
	function mainLayout($val=null){
		if($val==null){
			return $this->_mainLayout;
		}else{
			$this->_mainLayout = $val;
		}
	}
	function main(){
		
	}
	function admin(){
		
	}
	function param($name){
		return $this->Request->getParam($name);
	}
	function assign($name,$val){
		$this->View->assign($name,$val);
	}
	function post($name){
		return $this->Request->getPost($name);
	}
	function out($tpl){
		return $this->View->toString($tpl);
	}
	function __toString(){
		return $this->out($this->_mainLayout);
	}
	function getList($sql,$start,$total,$base_url){
		//paging
		$paging = new Paginate();
		$sql1 = $sql." LIMIT ".$start.",".$total;
		$sql2 = eregi_replace("SELECT (.*) FROM","SELECT COUNT(*) as total FROM",$sql);
		$sql2 = eregi_replace("ORDER BY(.*)","",$sql2);
		$this->open();
		$list = $this->fetch($sql,1);
		$rs = $this->fetch($sql2);
		$this->close();
		$this->assign("list",$list);
		$this->assign("pages",$paging->generate($start, $total, $rs['total']));
		
	}
}
?>