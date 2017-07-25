<?php
global $APP_PATH,$ENGINE_PATH;
include_once $APP_PATH.'marlboro/helper/codeHelper.php';
include_once $APP_PATH.'marlboro/helper/newsHelper.php';
include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $ENGINE_PATH."Utility/Mailer.php";
class code extends App{
	var $Request;
	var $View;
	var $user;
	var $codeHelper;
	var $newsHelper;
	var $badgeHelper;
	
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		$this->user = $this->getUserInfo();
		$this->codeHelper = new codeHelper($this->user['register_id']);
		$this->newsHelper = new newsHelper($this->user['register_id']);
		$this->badgeHelper = new BadgeHelper('badge_api');
	}
	function home(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$_badge = 0;
		if(intval($_POST['universal']) == 1){
			$_code = $_POST['code'];
			$_badge = intval($_POST['badge']);
			
			if($_code != '' && $_badge != 0){
				
				$res=json_decode($this->codeHelper->inputUniversalCode($_code,$_badge));
				if(intval($res->status) == 1){
					global $CONFIG;
					if($CONFIG['enable_news']){
						$this->newsHelper->unlockBadge($res->data->badge->name,$res->data->badge->id);
					}
				}else{
					//gagal
					$_badge = 66;
				}
			}
		
		}
		
		$this->View->assign('uni',$_badge);
		return $this->View->toString(APPLICATION.'/code.html');
	}
	function submit(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		//echo json_encode(array('status'=>9));
		//exit;
		
		if(!$_COOKIE['DISABLE_INPUT_CODE']){
			$_code = $_POST['code'];
			$_captcha = $_POST['captcha'];
			$_valid = (md5($_captcha) == $_SESSION['mrlbCaptchaSimple']) ? true : false;
			
			$_SESSION['mrlbCaptchaSimple'] = "bed" . rand(00000000,99999999) . "bed";
			
			if($_code != '' && $_captcha != '' && $_valid){
				$res=json_decode($this->codeHelper->inputCodeSuccess($_code));
				if(intval($res->status) == 1){
					setcookie("COUNT_INPUT_CODE", "", time()-3600);
					
					global $CONFIG;
					if($CONFIG['enable_news']){
						$this->newsHelper->unlockBadge($res->data->badge->name,$res->data->badge->id);
					}
					echo json_encode($res);
				}else{
					setcookie("COUNT_INPUT_CODE", intval($_COOKIE['COUNT_INPUT_CODE'])+1, time() + (1*60) );
					if( $_COOKIE['COUNT_INPUT_CODE'] >= 3 ){
						setcookie("DISABLE_INPUT_CODE", true, time() + (1*60) );
						setcookie("COUNT_INPUT_CODE", "", time()-3600);
					}
					echo json_encode($res);
				}
			}else{
				//JIKA CODE/CAPTCHA SALAH
				echo json_encode(array('status'=>444));
			}
		}else{
			//JIKA USER DI BANNED
			echo json_encode(array('status'=>666));
		}
		
		exit;
	}
	function yourbadges(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		return $this->View->toString(APPLICATION.'/code.html');
	}
	function traderequestmatch(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$want = intval($_GET['want']);
		$badge = intval($_GET['badge']);
		
		$list = $this->codeHelper->getUserWantBadge($want,$badge);
		
		$this->View->assign('list',$list);
		$this->View->assign('want',$want);
		$this->View->assign('badge',$badge);
		$this->View->assign('name',$this->user['name']);
		
		return $this->View->toString(APPLICATION.'/traderequestmatch.html');
	}
	
	function prize(){
		
		//Di tutup per 9 des 2011
		sendRedirect('index.php');
		exit;
		
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$prize = $_GET['prize'];
		if( $prize != ''){
			
			$type=$_GET['type'];
			$size=$_GET['size'];
			
			if($prize == 'berlin-prize-brief' && $type == '' && $size == ''){
				
				$this->View->assign('prize',$prize);
				return $this->View->toString(APPLICATION.'/tshirt.html');
				
			}else{
			
				$require=$this->codeHelper->getBadgeRequestForPrize($prize);
				$have=$this->codeHelper->checkBadgeRequestForPrize($prize);
				$allow = $this->codeHelper->checkAllowRequestForPrize() ? 1 : 0;
				$this->View->assign('require',$require);
				$this->View->assign('have',$have);
				$this->View->assign('allow',$allow);
				$this->View->assign('prize',$prize);
				
				$this->open(0);
				$qry = "SELECT
								id,
								register_id,
								street StreetName,
								complex,
								province,
								city,
								phone,
								mobile MobilePhone
							FROM social_redeem WHERE register_id='".$this->user['register_id']."' ORDER BY id DESC LIMIT 1;";
				$data = $this->fetch($qry);
				
				if(intval($data['id']) <= 0){
					$data = $this->user;
					$qry = "SELECT city FROM mop_city_lookup WHERE id='".$this->user['city']."'";
				}else{
					$this->View->assign('data',$data);
					$rs['city'] = $data['city'];
				}
				
				$qry2 = "SELECT * FROM mop_city_lookup ORDER BY city ASC;";
				
				$rs = $this->fetch($qry);
				$city = $this->fetch($qry2,1);
				$this->close();
				
				$this->View->assign('kota',$rs['city']);
				$this->View->assign('city',$city);
				$this->View->assign('type',$type);
				$this->View->assign('size',$size);
				
				return $this->View->toString(APPLICATION.'/redeem-input.html');
			}
		}
		
		//$sql = "SELECT COUNT(*) AS total, prize FROM social_redeem GROUP BY prize ORDER BY prize;";
		$sql = "SELECT COUNT(*) AS total FROM social_redeem WHERE prize='new-york-prize-brief-1';";
		$this->open(0);
		$rs = $this->fetch($sql);
		$this->View->assign('total_1',intval($rs['total']));
		$sql = "SELECT COUNT(*) AS total FROM social_redeem WHERE prize='new-york-prize-brief-2';";
		$rs = $this->fetch($sql);
		$this->View->assign('total_2',intval($rs['total']));
		
		//CEK APAKAH SUDAH PERNAH REDEEM NY1 / NY2
		$sql = "SELECT COUNT(*) AS total FROM marlboro_connect.social_redeem WHERE register_id='".$this->user['register_id']."' AND (prize='new-york-prize-brief-2' OR prize='new-york-prize-brief-1');";
		$rs = $this->fetch($sql);
		$this->View->assign('sudah',intval($rs['total']));
		
		$this->close();
		
		
		
		return $this->View->toString(APPLICATION.'/redeem-badge.html');
	}
	
	function prizesubmit(){
		
		//Di tutup per 9 des 2011
		sendRedirect('index.php');
		exit;
		
		$prize = $_POST['prize'];
		$_street = $_POST['street'];
		$_complex = $_POST['complex'];
		$_province = $_POST['province'];
		$_city = $_POST['city'];
		$_phone = $_POST['phone'];
		$_mobile = $_POST['mobile'];
		$_agree = $_POST['agree'];
		$type=$_GET['type'];
		$size=$_GET['size'];
		
		if(!$_agree){
			//return $this->View->showMessage('You don\'t agree with us, please check agree!','index.php?page=code&act=prize');
			$data = array('status'=>0,'message'=>'You don\'t agree with us, please check agree!');
		}
		
		//cek apakah prize masih tersedia
		$tersedia = true;
		if($prize == 'new-york-prize-brief-1' || $prize == 'new-york-prize-brief-2'){
			$this->open(0);
			$sql = "SELECT COUNT(*) AS total FROM social_redeem WHERE prize='$prize';";
			$rs = $this->fetch($sql);
			$total = intval($rs['total']);
			
			if($total >= 15){
				$tersedia = false;
			}

			//CEK APAKAH SUDAH PERNAH REDEEM NY1 / NY2
			$sql = "SELECT COUNT(*) AS total FROM marlboro_connect.social_redeem WHERE register_id='".$this->user['register_id']."' AND (prize='new-york-prize-brief-2' OR prize='new-york-prize-brief-1');";
			$rs = $this->fetch($sql);
			if( intval($rs['total']) > 0){
				$tersedia = false;
			}
			$this->close();
		}
		
		//Buat prize instanbul atau VIP sudah habis
		if($prize == 'instanbul-prize-brief'){
			$tersedia = false;
		}
		
		//echo $prize.' - '.$_street.' - '.$_complex.' - '.$_province.' - '.$_city.' - '.$_phone.' - '.$_mobile;exit;
		
		if( $prize != '' && $_street!='' && $_complex!='' && $_province!='' && $_city!='' && $_phone!='' && $_mobile!='' && $tersedia){
			$this->codeHelper->getBadgeRequestForPrize($prize);
			$this->codeHelper->checkBadgeRequestForPrize($prize);
			if($this->codeHelper->checkAllowRequestForPrize()){
				global $PRIZE;
				$res = json_decode($this->badgeHelper->badge_redeemed($this->user['register_id'],$PRIZE[$prize],$prize));
				if( $res->status == 1){
					$transaction_id = $res->data->transaction_id;
					
					$NOW = date( 'Y-m-d H:i:s');
					
					$qry = "INSERT INTO social_redeem 
								(register_id,street,complex,province,city,phone,mobile,prize,submit_date,transaction_id,tshirt_type,tshirt_size)
								VALUES
								('".$this->user['register_id']."','$_street','$_complex','$_province','$_city','$_phone','$_mobile','$prize','$NOW','$transaction_id','$type','$size');";
					$this->open(0);
					if($this->query($qry)){
						//if($prize=='berlin-prize-brief'){
							//$id = mysql_insert_id();
							//$data = array('status'=>1,'message'=>'Redeem Success', 'url' => 'index.php?page=code&act=tshirt&id='.$id.'&prize='.$prize);
						//}else{
						
							//SEND eMAIL
							global $CONFIG;
							$to = $CONFIG['EMAIL3PARTY'];
							$tonum = count($to);
							
							if($prize == 'berlin-prize-brief'){
								$merchandise = "T-Shirt, type $type, size $size";
							}elseif($prize == 'new-york-prize-brief-1'){
								$merchandise = "Samsung Galaxy II Smartphone";
							}elseif($prize == 'new-york-prize-brief-2'){
								$merchandise = "Samsung Galaxy Tab 10.1";
							}elseif($prize == 'instanbul-prize-brief'){
								$merchandise = "VIP";
							}
							
							$name = $this->user['name'];
							if( !is_array($this->user['last_name']) AND $this->user['last_name'] != 'Array' ){
								$name .= ' '.$this->user['last_name'];
							}
							
							$msg = "<p>The following user has requested for a merchandise</p>
											Name:  $name<br />
											Register ID: ".$this->user['register_id']."<br />
											Merchandise: $merchandise<br />
											Request time: $NOW<br />";
							
							for($i=0;$i<$tonum;$i++){
								$mail = new Mailer();
								$mail->setSubject("Redeem Merchandise request");
								$mail->setSender("info@marlboro.co.id");
								$mail->setMessage($msg);
								$mail->setRecipient($to[$i]);
								$mail->send();
							}
						
							$data = array('status'=>1,'message'=>'Redeem Success', 'url' => 'index.php?page=code&act=sendprize&prize='.$prize);
						//}
						//sendRedirect('index.php?page=code&act=sendprize&prize='.$prize);
						//exit;
					}else{
						//sendRedirect('index.php?page=code&act=prize');
						//exit;
						$data = array('status'=>0,'message'=>'Redeem Failed, Please Try Again!');
					}
				}else{
					$data = array('status'=>0,'message'=>'Redeem Failed, Please Try Again!');
				}
			}else{
				//return $this->View->showMessage('Please complete your badges before','index.php?page=code&act=prize');
				$data = array('status'=>0,'message'=>'You don\'t have enough badges for this merchandise');
			}
		}else{
			if(!$tersedia){
				$data = array('status'=>0,'message'=>'<span style="font-size:18px">The Prize has been completely claimed. Why not continue collecting the other badges for a chance to travel to Berlin, Istanbul and New York?</span>');
			}else{
				$data = array('status'=>0,'message'=>'Complete The Form Please');
			}
		}
		//return $this->View->showMessage('Completed the form please!','index.php?page=code&act=prize');
		echo json_encode($data);
		exit;
	}
	
	function sendprize(){
		
		//Di tutup per 9 des 2011
		sendRedirect('index.php');
		exit;
		
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$prize = $_GET['prize'];
		$require=$this->codeHelper->getBadgeRequestForPrize($prize);
		$this->View->assign('require',$require);
		$this->View->assign('prizeimage',$this->codeHelper->getPrizeImage($prize));
		return $this->View->toString(APPLICATION.'/redeem-success.html');
	}
	
	function trade(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		//$have=$this->codeHelper->getUserBadge();
		$have=$this->codeHelper->getActualUserBadge();
		
		//print_r($have);exit;
		$this->View->assign('have',$have);
		return $this->View->toString(APPLICATION.'/badge.html');
	}
	
	function submittrade(){
		$_have = intval($_POST['have']);
		$_req = intval($_POST['req']);
		
		if($_have != '' && $_req != ''){
			/*
			if($this->codeHelper->submitTrade($_have,$_req)){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
			*/
			echo json_encode($this->codeHelper->submitTrade($_have,$_req));
		}else{
			echo json_encode(array('status'=>0,'message'=>'Choose your badge!'));
		}
		exit;
	}
	
	function getmopprofile(){
		$regid=$_POST['regid'];
		
		if( $regid == '' || $regid == 0){
			echo json_encode(array('status'=>0));
			exit;
		}else{
			$list=$this->codeHelper->getUserProfileAndBadge($regid);
			//print_r($list);exit;
			
			$d = explode(' ',$list['register_date']);
			$d = explode('-',$d[0]);
			$d = date("M d, Y", mktime(0, 0, 0, $d[1], $d[2], $d[0]));
			$list['register_date'] = $d;
			
			echo json_encode(array('status'=>1,'data' => $list));
			exit;
		}
	}
	
	function confirmtraderequest(){
		$mine = intval($_POST['mine']);
		$your = intval($_POST['your']);
		$sellerId = $_POST['sellerId'];
		
		$res = $this->codeHelper->confirmTradeRequest($mine,$your,$sellerId);
		
		if($res['status'] == 1){
			//echo "masuk cuy";
			//exit;
			$this->newsHelper->trade($your,$mine,$sellerId);
		}
		
		echo  json_encode($res);
		exit;
	}
	function tshirt(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$id = intval($_GET['id']);
		$this->View->assign('id',$id);
		$this->View->assign('prize',$_GET['prize']);
		return $this->View->toString(APPLICATION.'/tshirt.html');
	}
	function tshirtsubmit(){
		//CHECK VERIFIED ACCOUNT
		if(intval($this->user['n_status']) <= 0){
			return $this->View->showMessage($this->strBlocked,'index.php');
		}
		
		$_type=$_POST['type'];
		$_size=$_POST['size'];
		$_prize=$_POST['prize'];
		$_id=intval($_POST['id']);
		
		if($_type != '' && $_size != '' && $_id != ''){
			$qry = "UPDATE social_redeem SET tshirt_type='$_type', tshirt_size='$_size' WHERE id='$_id' && register_id='".$this->user['register_id']."'";
			$this->open(0);
			if($this->query($qry)){
				$data = array('status'=>1,'message'=>'Submit success', 'url'=>'index.php?page=code&act=sendprize&prize='.$_prize);
			}else{
				//echo mysql_error();
				//exit;
				$data = array('status'=>0,'message'=>'Submit failed, please try again!');
			}
			$this->close();
		}else{
			$data = array('status'=>0,'message'=>'Submit failed, please try again!');
		}
		echo json_encode($data);
		exit;
	}
	
	function universal(){
		
		$kode = $this->Request->getParam('m');
		$kode = base64_decode($kode);
		$secret = "[*]marlboro";
		$kode = explode($secret,$kode);
		$kode = strtolower($kode[0]);
		
		$ajax = intval($this->Request->getParam('ajax'));
		
		if($ajax == 1){
		
			$data = array();
			
			if($kode != ''){
				
				$this->open(0);
				//cek apakah kode universal masih tersedia?
				$tersedia = false;
				$sql = "SELECT * FROM
							(SELECT COUNT(*) AS total FROM marlboro_code.badge_redeem WHERE kode = '$kode') a,
							(SELECT * FROM marlboro_code.universal_cap WHERE kode='$kode') b;";
				$rs = $this->fetch($sql);
				if(intval($rs['total']) < intval($rs['cap'])){
					$tersedia = true;
				}
				
				//cek apakah user sudah pernah dapat universal code
				$belumdapat =false;
				$sql = "SELECT COUNT(*) AS total FROM marlboro_code.badge_redeem WHERE kode='$kode' AND user_id='".$this->user['register_id']."';";
				$rs = $this->fetch($sql);
				if(intval($rs['total']) == 0){
					$belumdapat = true;
				}
				
				$this->close();
				
				if($tersedia && $belumdapat){
				
					$data['status'] = 1;
					$data['kode'] = $kode;
					
				}else{
				
					$data['status'] = 0;
				
				}
				
			}
			
			echo json_encode($data);
			exit;
		
		}else{
			
			sendRedirect('index.php');
			exit;
			
		}
		
	}
	
	function saveuniversal(){
		
		$kode = strtolower($this->Request->getParam('kode'));
		$ajax = intval($this->Request->getParam('ajax'));
		$badge = intval($this->Request->getParam('badge'));
		
		if($ajax == 1){
		
			$data = array();
			
			if($kode != ''){
				
				$this->open(0);
				//cek apakah kode universal masih tersedia?
				$tersedia = false;
				$sql = "SELECT * FROM
							(SELECT COUNT(*) AS total FROM marlboro_code.badge_redeem WHERE kode = '$kode') a,
							(SELECT * FROM marlboro_code.universal_cap WHERE kode='$kode') b;";
				$rs = $this->fetch($sql);
				if(intval($rs['total']) < intval($rs['cap'])){
					$tersedia = true;
				}
				
				//cek apakah user sudah pernah dapat universal code
				$belumdapat =false;
				$sql = "SELECT COUNT(*) AS total FROM marlboro_code.badge_redeem WHERE kode='$kode' AND user_id='".$this->user['register_id']."';";
				$rs = $this->fetch($sql);
				if(intval($rs['total']) == 0){
					$belumdapat = true;
				}
				
				$this->close();
				
				if($tersedia && $belumdapat){
				
					$res=json_decode($this->codeHelper->inputUniversalCode($kode,$badge));
					if(intval($res->status) == 1){
						global $CONFIG;
						if($CONFIG['enable_news']){
							$this->newsHelper->unlockBadge($res->data->badge->name,$res->data->badge->id);
						}
						$data['status'] = 1;
					}else{
						$data['status'] = 0;
					}
					
				}else{
				
					$data['status'] = 0;
				
				}
				
			}
			
			echo json_encode($data);
			exit;
		
		}else{
			
			sendRedirect('index.php');
			exit;
			
		}
		
	}
}