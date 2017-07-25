<?php
class SessionHelper{
	var $namespace;
	function __construct($namespace){
		$this->namespace = $namespace;
	}
	function set($name,$val){
		//jika sessionnya kosong, maka create session baru
		if(strlen($_SESSION[$name])==0){
			$p = array($name=>$val);
			$_SESSION[$this->namespace] = urlencode64(json_encode($p));
		}else{
			$arr = json_decode(urldecode64($_SESSION[$this->namespace]));
			$arr->$name = $val;
			$_SESSION[$this->namespace] = urlencode64(json_encode($arr));
		}
	}
	function get($name){
		$arr = json_decode(urldecode64($_SESSION[$this->namespace]));
		return $arr->$name;
	}
}
?>