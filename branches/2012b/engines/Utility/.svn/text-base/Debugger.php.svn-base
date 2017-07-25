<?php
class Debugger{
	var $isDebug = false;
	var $path="../logs/";
	function enable($foo){
		
		$this->isDebug = $foo;
		//$this->isDebug = false;
	}
	/*function addLog($msg){
		if($this->isDebug){
			$fp = fopen("logs/debug-".date("Ymd").".txt","a+");
			$msg = date("d-m-Y H:i:s")." ".$msg."\n";
			fwrite($fp,$msg,strlen($msg));
			fclose($fp);
		}
	}*/
	function setDirectory($path="../logs/"){
		$this->path = $path;
	}
	function addLog($msg,$user,$wsdl,$ws_method,$sessionID,$sResult){
		if($this->isDebug){
		$sDate = date("d-m-Y H:i:s");
		//$sFilename = "/home/umild/logs/debug-".date("Ymd").".csv";
		$sFilename = $this->path."debug-".date("Ymd").".csv";
		if(!file_exists($sFilename)){
			$fp = fopen($this->path."debug-".date("Ymd").".csv","a+");
			if (flock($fp, LOCK_EX)) { // do an exclusive lock
				$sMsg = "Date,Activity,User,Web Service,Command,Response ,SessionID\n";
				fwrite($fp,$sMsg,strlen($sMsg));
				flock($fp, LOCK_UN); // release the lock
			}
				fclose($fp);	
			
		}
		$fp = fopen($this->path."debug-".date("Ymd").".csv","a+");
		if (flock($fp, LOCK_EX)) { // do an exclusive lock
			$sMsg = "\"".$sDate."\",\"".$msg."\",$user,\"".$wsdl."\",$ws_method,$sResult,$sessionID\n";
			fwrite($fp,$sMsg,strlen($sMsg));
			flock($fp, LOCK_UN); // release the lock
		}
		fclose($fp);	
		}
	}
}
?>