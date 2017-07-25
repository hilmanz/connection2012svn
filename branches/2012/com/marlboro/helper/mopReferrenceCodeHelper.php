<?php
class mopReferrenceCodeHelper extends Application{

	var $Request;
	var $userID;
	
	function __construct($req,$userID){
	$this->Request = $req;
	$this->userID = $userID;
	}
	
	
	function get_code($page=NULL,$act=NULL){
		$sql ="SELECT code FROM ".SCHEMA_CONNECTION.".mop_referrence_code ";
		if($act!=NULL) $sql .="WHERE page LIKE '".$page."' AND act LIKE '".$act."' ";
		else  $sql .="WHERE page LIKE '".$page."' ";
		$sql .=" LIMIT 1";
		$this->open();
		$qData = $this->fetch($sql);
		$this->close();
		if($qData)	return $qData['code'];
		else return null;
		
	}
	
	
	
	
	
}
?>
