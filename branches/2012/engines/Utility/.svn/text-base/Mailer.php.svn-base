<?php
global $ENGINE_PATH;
require_once $ENGINE_PATH."Utility/smtp.php";
require_once $ENGINE_PATH."Utility/sasl.php";
class Mailer{
	var $host;
	var $smtp_host;
	var $smtp_port;
	var $sender;
	var $recipient;
	var $subject;
	var $message;
	var $status;
	function Mailer(){
		
	}
	function setRecipient($email){
		$this->recipient = $email;
	}
	function setSubject($str){
		$this->subject = $str;
	}
	function setMessage($msg){
		$this->message = $msg;
	}
	function sendStandardMail(){
		return @mail($this->sender, $this->subject, $this->message,$this->headers);
	}
	function send(){
		global $CONFIG;
		$smtp=new smtp_class;
		$smtp->host_name=$CONFIG['EMAIL_SMTP_HOST'];
		$smtp->host_port=$CONFIG['EMAIL_SMTP_PORT'];             /* Change this variable to the port of the SMTP server to use, like 465 */
		$smtp->ssl=$CONFIG['EMAIL_SMTP_SSL'];                       /* Change this variable if the SMTP server requires an secure connection using SSL */
		$smtp->start_tls=0;                 /* Change this variable if the SMTP server requires security by starting TLS during the connection */
		$smtp->localhost=$CONFIG['EMAIL_SMTP_HOST'];       /* Your computer address */
		$smtp->direct_delivery=0;           /* Set to 1 to deliver directly to the recepient SMTP server */
		$smtp->timeout=10;                  /* Set to the number of seconds wait for a successful connection to the SMTP server */
		$smtp->data_timeout=10;              /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
		Set to 0 to use the same defined in the timeout variable */
		//	$smtp->debug=1;                     /* Set to 1 to output the communication with the SMTP server */
		$smtp->debug=0;                     /* Set to 1 to output the communication with the SMTP server */
		//	$smtp->html_debug=1;                /* Set to 1 to format the debug output as HTML */
		$smtp->html_debug=0;                /* Set to 1 to format the debug output as HTML */
		$smtp->pop3_auth_host="";           /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
		$smtp->user=$CONFIG['EMAIL_SMTP_USER'];                     /* Set to the user name if the server requires authetication */
		$smtp->realm="";                    /* Set to the authetication realm, usually the authentication user e-mail domain */
		$smtp->password=$CONFIG['EMAIL_SMTP_PASSWORD'];                 /* Set to the authetication password */
		$smtp->workstation="";              /* Workstation name for NTLM authentication */
		$smtp->authentication_mechanism=""; /* Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
		Leave it empty to make the class negotiate if necessary */
		
		$to      = $this->recipient;
		$subject = $this->subject;
		$message = $this->message;
		$headers = 'From: '.$CONFIG['EMAIL_FROM_DEFAULT'] . "\r\n" .
    'Reply-To: '.$CONFIG['EMAIL_FROM_DEFAULT']. "\r\n";
		//print $to;
		
		if($smtp->SendMessage($CONFIG['EMAIL_FROM_DEFAULT'],array($to),array(
			"MIME-Version: 1.0",
			"Content-Type: text/html",
			"From: ".$CONFIG['EMAIL_FROM_DEFAULT'],
			"To: $to",
			"Subject: $subject",
			"Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
		),
		trim($message))){
			//print "berhasil";
			$this->status="101";
			return true;
		}else{
		//	print "gagal-->".$smtp->error;
			$this->status=$smtp->error;
			return false;
		}
	}
}

?>
