<?php
class BasicStreamer{
	var $username;
	var $password;
	var $url;
	var $rule;
	var $type;
	var $volume;
	var $user_agent = "KANADIGITAL";
	var $log_location = "standard_stream.log";
	var $connect_delay = 0;
	var $n_feed = 0;
	function __construct($username,$password,$url,$rule,$type=0,$volume=1000){
		$this->username = $username;
		$this->password = $password;
		$this->url = $url;
		$this->rule = $rule;
		$this->type = $type;
		$this->volume = $volume;
		$this->reconnect_if_idle_for = 0;
		$this->connect_delay = 0;

	}
	
	function connect(){
		print "Connect to stream : ".$this->url."\n";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);  
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_NOSIGNAL, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
		$header = curl_exec($ch);
		$info = curl_getinfo($ch);
		//print $header;
		curl_close($ch);
		if($info['http_code']=="200"){
			return true;
		}
		
	}
	function consume($timeout=15){
		
		if ($this->connect_delay > 0) {
			$this->write_log('info', 'Connect delay currently: ' . $this->connect_delay . ' seconds');
			sleep($this->connect_delay);
		}
	
		// increase the delay by one second
		$this->connect_delay++;
		if ($this->connect_delay > 60) $this->connect_delay = 60;
	
		$this->last_found_time = $this->last_log_time = time();
		$this->idle_reconnect_time = ($this->reconnect_if_idle_for==0) ? time()+(60*60*24) : time()+($this->reconnect_if_idle_for*60);
		$this->consume_count = 0;
		$this->disconnect = false;
		$this->terminated = false;
		$this->last_log_time = 0;
		$debug_headers = '';
		
		$rules = json_decode($this->get_rules());
		$this->n_current_rules = sizeof($rules->rules);
		if($this->n_current_rules>0){

			if($this->connect()){
			
			$location_tmp = parse_url($this->url);
			$this->gnip_powertrack_reconnect_scheme = $location_tmp['scheme'];
			$this->gnip_powertrack_reconnect_host = $location_tmp['host'];
			$this->gnip_powertrack_reconnect_port = 443;
			$this->gnip_powertrack_reconnect_path = $location_tmp['path'];
			//$this->gnip_powertrack_reconnect_cookie = implode('; ', $header_arr['Set-Cookie']);
		
			$debug_headers = "";
				print "Connecting..\n";
			
				$fp = fsockopen((($this->gnip_powertrack_reconnect_scheme=='https') ? 'ssl://' : '') . 
					$this->gnip_powertrack_reconnect_host, $this->gnip_powertrack_reconnect_port, $errno, $errstr, 30);

				$out = "GET " . $this->url . " HTTP/1.0\r\n";
				$out .= "Host: " . $this->gnip_powertrack_reconnect_host . "\r\n";
				//$out .= "Cookie: " . $this->gnip_powertrack_reconnect_cookie . "\r\n";
				$out .= "Authorization: Basic ".base64_encode("".$this->username.":".$this->password."")."\r\n";
				$out .= "Connection: Close\r\n\r\n";
			
				//print $fp;
				   fwrite($fp, $out);
				    
				   stream_set_blocking($fp, 1); 
				  stream_set_timeout($fp, ($this->reconnect_if_idle_for==0) ? (60*60*24) : ($this->reconnect_if_idle_for*60)); 
				  $stream_info = stream_get_meta_data($fp);

				    // save the headers so we can use them for debugging if we are forcefully disconnected.
				    while(!feof($fp) && ($debug = fgets($fp)) != "\r\n" ) {
				    	$debug_headers .= trim($debug) . '; ';
				    }
				print $debug_headers."\n\n";    
			
				stream_set_blocking($fp, 0); 
				print "Consuming..\n";
				$response = "";
				while ((!feof($fp)) AND (!$stream_info['timed_out'])) {
					$response.= fgets($fp, 16384);


					//if (($newline = strpos($response, "<\/entry>")) === FALSE) {
					//	continue; // We need a newline
					//}
					if(!eregi("<\/entry>",$response)){
						continue;
					}
		
					// only enqueue the responses that have data (e.g. ignore keep alive data)
					$response = trim($response);
					//print "-->".$response."\n";
					if (strlen($response) > 0) {
						// set the fact that we just got valid data
						$this->connect_delay = 0;

						$this->idle_reconnect_time = ($this->reconnect_if_idle_for==0) ? time()+(60*60*24) : time()+($this->reconnect_if_idle_for*60);
						$this->consume_count++;
			
						// enqueue our new item
						$enqueue_start = microtime(TRUE);
						$this->enqueue($response);
						$this->enqueue_time += (microtime(TRUE) - $enqueue_start);
					}
					// clean up the response variable for next time around.
					$response = '';
					$this->log();
			
		
					// this only gets triggered if during the log action, 
					//we determined that we were completely idle for X minutes. X = $this->reconnect_if_idle_for
					if ($this->disconnect == true) {
						$this->disconnect = false;
						break;
					}
				}		
				fclose($fp);
			}else{
				print "Failed to connect\n";
			}
			
		}else{
			print "no keyword available.\n";
		}
		
		sleep(5);
		//try to reconnect
		$this->consume($timeout);
	}
	private function get_rules(){
		

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->rule);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);  
		curl_setopt($ch, CURLOPT_ENCODING, "");
		curl_setopt($ch, CURLOPT_NOSIGNAL, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		//curl_setopt($ch, CURLOPT_HEADER, true);
		//curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
		
		$rs = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		return $rs;
	}
	public function log($message='') {
		$now = time();

		// if there is something to log, lets log it immediately
		if (isset($message['error'])) {
			$this->write_log('error', $message['error']);
		}
		if (isset($message['notice'])) {
			$this->write_log('notice', $message['notice']);
		}
		
		// it has been equal to or more than a minute since we last logged data to the log, lets write some stats data
		if (($this->last_log_time+60) <= $now) {
			$this->write_log('stats', array('items_consumed_total'=>number_format($this->consume_count), 'items_consumed_per_sec'=>($this->consume_count == 0) ? '0' : number_format($this->consume_count/($now-$this->last_log_time), 2), 'items_consumed_per_min'=>($this->consume_count == 0) ? '0' : number_format($this->consume_count/(($now-$this->last_log_time)/60), 2), 'avg_enqueue_time'=>($this->consume_count > 0) ? round($this->enqueue_time / $this->consume_count * 1000, 2) : 0));
			
			$this->last_log_time = $now;
			$this->consume_count = 0;
			$this->enqueue_time = 0;

			
		}
		//--added by duf--
		//--check for new keywords... if there's a new keyword.. force the streamer to disconnect
		$rules = json_decode($this->get_rules());
		if($this->n_current_rules!=sizeof($rules->rules)){
			$this->write_log('error', 'Keywords Changed.  Disconnecting.');
			$this->connect_delay = 0;
			$this->disconnect = true;
		}
		// if we've been idle for longer than we allow ourselves to be, disconnect and reconnect.
		if ($this->idle_reconnect_time <= $now) {
			// log the error, we've been idle for more than allowed
			$this->write_log('error', 'Max idle time reached (' . $this->reconnect_if_idle_for . ' minutes).  Disconnecting.');
			
			$this->disconnect = true;
		}
	}
	
	// overwrite this with a custom enqueue function
	public function enqueue($message) {
		// just for example. $message will contain a complete, json_encoded string
		//echo "\n".$this->n_feed." vs ".$this->volume."\n";
		//echo "\nMessage: '" . $message . "'\n";
		$this->n_feed++;
		//if($this->n_feed>=$this->volume){
		//	echo "prepare for terminate\n"; 
		//	$this->disconnect = true;
		//	$this->terminated = true;
		//}else{
			//if($this->type==1){
				//power track data
				//insert_raw_json($message);
			//}else{
				//corporate feed data
				insert_raw_corporate_data($message);
			//}
		//}
		
	}
	
	// overwrite this with a custom logging function, if needed.
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
	public function getFeedTotal(){
		return $this->n_feed;
	}
	public function getVolume(){
		return $this->volume;
	}
	function stream_progress($ch,$str){
		print $str."\n";
	}
}
?>
