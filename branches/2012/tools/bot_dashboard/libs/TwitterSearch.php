<?php
class TwitterSearch{	
	protected  $_user_agent = "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6.13";
	protected  $_url;
	protected  $_timeout;
	protected  $_port = 80;
	protected  $reconnect_if_idle_for = 1;
	protected $log_location = "twitter_search.log";
	protected $_response;
	protected $_status;
	protected $_refresh_url = "";
	protected $_auto_refresh = false;
	public function __construct(){
		
	}

	//getter setter
	public function auto_refresh($f=NULL){
		if($f==NULL){
			return $this->_auto_refresh;
		}else{
			$this->_auto_refresh = $f;
		}
	}
	public function url($u=NULL){
		if($u==NULL){
			return $this->_url;
		}else{
			$this->_url = $u;
		}
	}
	public function port($u=NULL){
		if($u==NULL){
			return $this->_port;
		}else{
			$this->_port = $u;
		}
	}
	public function timeout($t=NULL){
		if($t==NULL){
			return $this->_timeout;
		}else{
			$this->_timeout = intval($t);
		}
	}
	public function status($t=NULL){
		if($t==NULL){
			return $this->_status;
		}else{
			$this->_status = intval($t);
		}
	}
	public function refresh_url($url=NULL){	
		if($url!=NULL){
			$this->_refresh_url = $url;
		}else{
			return $this->_refresh_url;
		}
	}
	public function user_agent($u=NULL){
		if($u==NULL){
			return $this->_user_agent;
		}else{
			$this->_user_agent = $u;
		}
	}
	//end of getter setter
	public function search($keyword){
		//print "search for : ".$keyword."\n";
		$this->write_log('info','search for '.$keyword." --> searching");
		//var_dump(parse_url($this->url()));
		$url = parse_url($this->url());
		//print "connecting to ".$this->url()."\n";
		if($this->refresh_url()==""){
			print "Using initial URL\n";
			$this->refresh_url('?q='.$keyword);
		}else{
			print "Using refresh URL\n";
		}
		$fp = fsockopen((($url['scheme']=='https')?'ssl://':'').$url['host'],$this->port(), $errno, $errstr, $this->timeout());
		$out = "GET " . $url['path'].$this->refresh_url(). " HTTP/1.0\r\n";
				$out .= "Host: " . $url['host']. "\r\n";
				$out .= "User-Agent: ".$this->user_agent()."\r\n";
				$out .= "Connection: Close\r\n\r\n";
		//print $out;
		if($fp){
			fwrite($fp, $out);
						
			stream_set_blocking($fp, 1); 
			stream_set_timeout($fp, ($this->reconnect_if_idle_for==0) ? (60*60*24) : ($this->reconnect_if_idle_for*60)); 
			$stream_info = stream_get_meta_data($fp);
		
			$debug_headers = "";
			// save the headers so we can use them for debugging if we are forcefully disconnected.
			while(!feof($fp) && ($debug = fgets($fp)) != "\r\n" ) {
				$debug_headers .= trim($debug) . '; ';
			}
			//print $debug_headers."\n\n";    
			stream_set_blocking($fp, 0); 

			$this->_response = "";

			//check for the response..
			if(eregi("Status: 200 OK",$debug_headers)||eregi("HTTP/1.1 200 OK;",$debug_headers)){
				//print "header accepted.. we're good to go !\n";
				while ((!feof($fp))) {
					$this->_response.= fgets($fp, 16384);
				}
				$this->status(1);
			}else{
				
				$this->status(0);
				//print "header rejected..\n\n";
				$this->write_log('error',"header rejected --> ".$debug_headers);
			}
			fclose($fp);
			preg_match('/(Retry-After:\ )([0-9]+)/',$debug_headers,$matches);
			$retry = intval($matches[2]);
						
			$this->write_log('info','search for '.$keyword." --> done");
			if($retry>0){
				$this->write_log('info','request exceeded... wait for '.$retry.'s');
				sleep($retry);
			}
			//print "done\n";
		}else{
			$this->status(0);
			//print "could not connect to : ".$url['host']."\n\n";
			$this->write_log('error',"could not connect to : ".$url['host']);
		}

		//reset refresh_url
		if($this->auto_refresh()){
			$o = json_decode($this->_response);
			if(is_object($o)){
				//print $o->refresh_url."\n\n";
				$this->refresh_url($o->refresh_url);
			}
		}else{
			$this->refresh_url('');
		}
	}
	public function response(){
		return $this->_response;
	}
	private function write_log($type, $log_item) {
		if (is_array($log_item)) {
			foreach ($log_item AS $name=>$value) {
				$log_item_temp_arr[] = $name . ': ' . $value;
			}
			$log_line = date('Y-m-d H:i:s') . ' [' . ucfirst($type) . '] ' . implode('; ', $log_item_temp_arr);
		}
		else {
			$log_line = date('Y-m-d H:i:s') . ' [' . ucfirst($type) . '] ' . $log_item;
		}
		if ($type == 'stats') echo "\n";
		echo $log_line . "\n";
		$fh = fopen($this->log_location, 'a') or die("Can't open log file\n");
		fwrite($fh, $log_line . "\n");
		fclose($fh);
	}
}
?>
