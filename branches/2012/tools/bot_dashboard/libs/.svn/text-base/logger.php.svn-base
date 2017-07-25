<?php
class Logger{
	var $prefix;
	var $namespace;
	var $is_verbose = false;
	function __construct($prefix="log"){
		$this->prefix = $prefix;
		$this->_namespace = "Application";
	}
	function verbose($flag){
		$this->is_verbose = $flag;
	}
	function logger_namespace($name=NULL){
		if($name==NULL){
			return $this->_namespace;
		}else{
			$this->_namespace = $name;
		}
	}
	function info($msg){
		$str = date("Y/m/d H:i:s")." ".$this->_namespace." - [INFO] ".$msg."\n";
		$this->write($str);
	}
	function error($msg){
		$str = date("Y/m/d H:i:s")." ".$this->_namespace." - [ERROR] ".$msg."\n";
		$this->write($str);
	}
	function status($msg,$flag){
		if($flag){
			$str = date("Y/m/d H:i:s")." ".$this->_namespace." - [STATUS] ".$msg." SUCCESS\n";
		}else{
			$str = date("Y/m/d H:i:s")." ".$this->_namespace." - [STATUS] ".$msg." FAILED\n";
		}
		$this->write($str);
	}
	function write($msg){
		if(!is_dir("logs")){
			print "logs directory not found, please create it first !\n";
		}else{
			$fp = fopen("logs/".$this->prefix."-".date("Ymd").".log","a+");
			fwrite($fp,$msg,strlen($msg));
			fclose($fp);
			if($this->is_verbose){
				print $msg;
			}
		}
	}
}
?>