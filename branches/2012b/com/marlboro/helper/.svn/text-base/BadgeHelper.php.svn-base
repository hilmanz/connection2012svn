<?php
class BadgeHelper{
	var $namespace;
	var $uri;
	var $info;
	
	function __construct($namespace){
		global $CONFIG;
		$this->namespace = $namespace;
		$this->uri = $CONFIG['BADGE_API'];
	}
	function call($data){
		$this->info = null;
		$data = http_build_query($data);
		$ch = curl_init (); 
		curl_setopt($ch,CURLOPT_URL,$this->uri); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
		curl_setopt($ch,CURLOPT_USERPWD,"$username:$password");
		curl_setopt($ch, CURLOPT_TIMEOUT, 15); //times out after 10s 
		curl_setopt($ch, CURLOPT_POST, TRUE); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1); 
		$result = curl_exec ($ch);
		$this->info = curl_getinfo($ch); 
		curl_close($ch); 
		return $result;
	}
	/**
	 * 
	 * method untuk redeem code
	 * @param $user_id gunakan MOP's RegistrationID
	 * @param $code 8 digit kodenya
	 * @return bool
	 */
	function redeem_code($user_id,$code){
		
		//echo $user_id.' - '.$code;exit;
		$chck = array("method"=>"check","kode"=>$code);
		
		$check_response = ($this->call($chck));
		$o_resp = json_decode($check_response);
		
		if($o_resp->status=="1"){
			$data = array("method"=>"redeem_code","user_id"=>$user_id,"kode"=>$code,"badge"=>$o_resp->data->badge);
			$response = $this->call($data);
			return $response;
		}else{
			return $check_response;
		}
	}
	/**
	 * 
	 * method untuk redeem universal code
	 * @param $user_id gunakan MOP's RegistrationID
	 * @param $code 8 digit kodenya
	 * @return bool
	 */
	function redeem_universal_code($user_id,$code,$badge){
		
		//echo $user_id.' - '.$code;exit;
		$chck = array("method"=>"check","kode"=>$code);
		
		$check_response = ($this->call($chck));
		$o_resp = json_decode($check_response);
		
		if($o_resp->status=="1"){
			$data = array("method"=>"get_direct_badge","user_id"=>$user_id,"kode"=>$code,"badge_id"=>$badge);
			$response = $this->call($data);
			
			return $response;
		}else{
			return $check_response;
		}
	}
	/**
	 * 
	 * method untuk mendapatkan daftar badge yang dimiliki oleh user.
	 * @param $user_id
	 */
	function get_user_badges($user_id){
		
		$data = array("method"=>"get_inventory","user_id"=>$user_id);
		$response = $this->call($data);
		
		return $response;
	}
	
	function get_user_actual_badges($user_id){
		$data = array("method"=>"get_actual_inventory","user_id"=>$user_id);
		$response = $this->call($data);
		
		return $response;
	}
	
	/**
	 * cari orang2 yang memiliki badge id ini.. kecuali orang dengan user_id == exclude_user_id
	 * @param $badge_id
	 * @param $exclude_user_id
	 */
	function search_badge_owners($badge_id,$exclude_user_id){
		$data = array("method"=>"search_badge_owners","badge_id"=>$badge_id,"exclude_user_id"=>$exclude_user_id);
		$response = $this->call($data);
		return $response;
	}
	/**
	 * post auction
	 * @param $user_id
	 * @param $need_id
	 * @param $with_id
	 */
	function auction_post($user_id,$need_id,$with_id){
		$data = array("method"=>"auction_post","user_id"=>$user_id,"need_id"=>$need_id,"with_id"=>$with_id);
		$response = $this->call($data);
		return $response;
	}
	
	/**
	 * mencari orang2 dengan kebutuhan trade yang sama.
	 * @param $exclude_user_id
	 * @param $need_id
	 * @param $with_id
	 */
	function search_auction($exclude_user_id,$need_id,$with_id){
		$data = array("method"=>"search_auction","exclude_user_id"=>$exclude_user_id,"need_id"=>$need_id,"with_id"=>$with_id);
		$response = $this->call($data);
		return $response;
	}
	
	/**
	 * proses trade
	 * @param $user_id
	 * @param $need_id
	 * @param $with_id
	 * @param $auction_id --> setelah user memilih salah seorang untuk diajak trade... 
	 * maka kita ambil auction_id nya untuk di supply di method trade ketika user meng-confirm trade
	 */
	function trade($user_id,$need_id,$with_id,$auction_id){
		$data = array("method"=>"trade","user_id"=>$user_id,"need_id"=>$need_id,"with_id"=>$with_id,"auction_id"=>$auction_id);
		$response = $this->call($data);
		return $response;
	}
	/**
	 * 
	 * method untuk redeem merchandise
	 * method ini akan menghapus badge dari inventory user
	 * @param $user_id
	 * @param $badges array contoh : array(10,8,12) --> badge id yang didelete adalah badge_id 10, 8  dan 12
	 * @param $prize
	 */
	function badge_redeemed($user_id,$badges,$prize){
		$str_badges = "";
		$n=0;
		foreach($badges as $badge){
			if($n>0){
				$str_badges.=",";
			}
			$str_badges .= $badge;
			$n++;
		}
		$data = array("method"=>"badge_redeemed","user_id"=>$user_id,"badges"=>$str_badges,"prize"=>$prize);
		$response = $this->call($data);
		return $response;
	}
	/**
	 * 
	 * fungsi ini dipanggil kalau redeem ditolak
	 * @param $user_id
	 * @param $transaction_id
	 */
	function cancel_redeem($user_id,$transaction_id){
		$data = array("method"=>"cancel_redeem","user_id"=>$user_id,"transaction_id"=>$transaction_id);
		$response = $this->call($data);
		return $response;
	}
	/**
	 * 
	 * fungsi ini dipanggil kalo redeem diapprove.
	 * @param $user_id
	 * @param $transaction_id
	 */
	function approve_redeem($user_id,$transaction_id){
		$data = array("method"=>"approve_redeem","user_id"=>$user_id,"transaction_id"=>$transaction_id);
		$response = $this->call($data);
		return $response;
	}
	function get_badge_detail($badge_id){
		
		$data = array("method"=>"get_badge_detail","badge_id"=>intval($badge_id));
		$response = $this->call($data);
		
		return $response;
	}
	function code_info($kode){
		
		$data = array("method"=>"code_info","kode"=>mysql_escape_string($kode));
		$response = $this->call($data);
		
		return $response;
	}
	
}
?>