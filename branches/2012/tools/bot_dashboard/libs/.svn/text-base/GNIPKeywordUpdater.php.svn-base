<?php
class GNIPKeywordUpdater{
	// URL Gnip provided to access power track
	protected $gnip_powertrack_location = '';
	
	// Set this to something identifiable in case Gnip needs to troubleshoot
	protected $user_agent = 'KANADIGITAL';
	
	// path to log data
	protected $log_location = 'keyword.log';
	
	// reconnect to Gnip if we are idle for more than X minutes.  If set to 0, will be set to a 24 hours from now.
	protected $reconnect_if_idle_for = 15;

	
	protected $keywords = array();
	protected $delete_keywords = array();
	private $b_success=false;

	function __construct($username,$password,$location){
		$this->username = $username;
		$this->password = $password;
		$this->gnip_powertrack_location = $location;
	}
	public function success(){
		return $this->b_success;
	}
	public function addKeyword($keyword){
		$this->keywords[] = $keyword;
	}
	public function deleteKeyword($keyword){
		$this->delete_keywords[] = $keyword;
	}
	public function execute() {
		//var_dump($this->keywords);https://kanadigital-powertrack.gnip.com/data_collectors/1/rules.xml
		//$payload = "<rules><rule><value>macet</value></rule></rules>";
		$this->doDelete();
		$this->doAdd();
	}
	public function doAdd(){
		if(sizeof($this->keywords)>0){
			print "adding keywords\n";
			$payload = "<rules>";
			foreach($this->keywords as $keyword){
				$payload.="<rule tag=\"".$keyword['id']."\"><value>".htmlspecialchars($keyword['txt'])."</value></rule>";
			}
			$payload.="</rules>";
			print $payload."\n";
			$this->send($this->gnip_powertrack_location,$payload);
		}
	}
	public function doDelete(){
		if(sizeof($this->delete_keywords)>0){
			print "removing keywords";
			$payload = "<rules>";
			foreach($this->delete_keywords as $keyword){
				$payload.="<rule tag=\"".$keyword['id']."\"><value>".htmlspecialchars($keyword['txt'])."</value></rule>";
			}
			$payload.="</rules>";
			$this->send($this->gnip_powertrack_location."?_method=delete",$payload);
		}
	}
	private function send($uri,$payload){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $uri);
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
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		$rs = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		if($info['http_code']==202||$info['http_code']==201){
			print "......ok\n";
			$this->b_success=true;
			return true;
		}else{
			$this->b_success=false;
			print "......Failed\n";
		}
	}
}
?>
