﻿<?phpclass ipadRegistration {	function __construct(){	}	function call($req_url=''){		$ch = curl_init();		curl_setopt($ch, CURLOPT_URL,$req_url);		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 		curl_setopt($ch, CURLOPT_USERPWD, "kana:kana321");		//curl_setopt($ch,CURLOPT_POST,1); //UNCOMMENT jika POST		//curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string); //UNCOMMENT JIKA POST		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		$response = curl_exec ($ch);		return $response;	}		function getCodeReg($lastid=0){		$url = "http://119.2.66.19/ipadreg/api/apikana/getcodereg/$lastid";		return $this->call($url);	}		function getCodeQuiz($lastid=0){		$url = "http://119.2.66.19/ipadreg/api/apikana/getcodequiz/$lastid";		return $this->call($url);	}}?>