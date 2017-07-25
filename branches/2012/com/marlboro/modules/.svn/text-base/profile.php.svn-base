<?php
global $APP_PATH;
include_once $APP_PATH.'marlboro/helper/BadgeHelper.php';
include_once $APP_PATH.'marlboro/helper/newsHelper.php';
class profile extends App{
	var $Request;
	var $View;
	var $user;
	var $badgeHelper;
	var $news;
	function __construct($req){
		$this->Request = $req;
		$this->View = new BasicView();
		$this->setVar();
		
		$this->badgeHelper = new BadgeHelper('badge_api');
		
		$this->user = $this->getUserInfo();
		//print_r($this->user);exit; 
		//jika tidak ada parameter id berarti profile sendiri
		$id = ( $this->Request->getParam('id') ) ? $this->Request->getParam('id') : $this->user['id'] ;
		$param = ( $this->Request->getParam('id') ) ? '&id='.$this->Request->getParam('id') : '' ;
		$this->user = $this->getOtherUserInfo($id);
		$this->View->assign('param',$param);
	}
	
	function home(){
		//print_r($this->user);exit;
		if($this->user['last_name']=="Array"){
			$this->user['last_name'] = "";
		}
		$this->View->assign('name',$this->user['name'].' '.$this->user['last_name']);
		
		$this->open(0);
		$qry="SELECT city FROM mop_city_lookup WHERE id='".$this->user['city']."'";
		$rs=$this->fetch($qry);
		$this->close();
		
		$this->View->assign('kota',$rs['city']);
		$sex = ($this->user['sex'] == 'F') ? 'Female' : 'Male';
		$this->View->assign('sex', $sex);
		$this->View->assign('age', $this->birthday($this->user['birthday']));
		$this->View->assign('date', $this->user['register_date']);
		
		$rand1 = rand(1111111111,9999999999);
		$rand2 = rand(111111111,999999999);
		$desc = $this->user['sex']." < ".strtoupper($this->user['last_name'])." < < ".strtoupper($this->user['name'])." < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < < M".$rand1." < 0IND".$rand2." < < < < < < < < < < < < < < < < < < < < < < < <";
		$this->View->assign('description', $desc);
		
		$img = $this->user['img'];
		//echo 'img => '.$img.'<hr />';
		if(file_exists('/'.$img)){
			if( strtolower($this->user['sex']) == 'm' ){
				$img = 'images/avatar-man.jpg';
			}else{
				$img = 'images/avatar-woman.jpg';
			}
		}
		//exit;
		
		$res = json_decode($this->badgeHelper->get_user_badges($this->user['register_id']));
		//$num = count($res->data);
		$num = 12; //jumlah badge 12
		$badge=array();
		for($i=1;$i<=$num;$i++){
			//$badge[$i] = array('badge_id'=>$res->data[$i]->badge_id,'total'=>$res->data[$i]->total);
			$badge[$i-1] = array('badge_id'=>$i,'total'=>0);
			foreach($res->data as $k){
				if($i == $k->badge_id){
					$badge[$i-1] = array('badge_id'=>$i,'total'=>$k->total);
				}
			}
		}
		
		$this->View->assign('badge', $badge);
		$this->View->assign('avatar', $img);
		return $this->View->toString(APPLICATION.'/profile.html');
	}
	
	function edit(){
		$id = $this->user['id'];
		//echo $id;exit;
		$req = $this->Request;
		if( $req->getParam('id') == '' ){
			$this->open(0);
			if($req->getParam('edit') == 1){
				$_regid = $req->getParam('regid');
				$_nama = $req->getParam('nama');
				$_panggilan = $req->getParam('panggilan');
				$_jenis_kelamin = $req->getParam('jenis_kelamin');
				$_tempat_lahir = $req->getParam('tempat_lahir');
				$_tanggal_lahir = $req->getParam('tanggal_lahir');
				$_no_id = $req->getParam('no_id');
				$_email = $req->getParam('email');
				$_kota = $req->getParam('kota');
				$_propinsi = $req->getParam('propinsi');
				$_merk_rokok_1 = $req->getParam('merk_rokok_1');
				$_merk_rokok_2 = $req->getParam('merk_rokok_2');
				$_acc_social_media = $req->getParam('acc_social_media');
				$_password = $req->getParam('password');
				$_type = $req->getParam('type');
				$_status = $req->getParam('status');
				
				if( $id > 0 && $_email != '' && $_nama != ''){
					
					$qry = "UPDATE dm_member SET
								nama='$_nama',
								panggilan='$_panggilan',
								jenis_kelamin='$_jenis_kelamin',
								tempat_lahir='$_tempat_lahir',
								tgl_lahir='$_tanggal_lahir',
								no_id='$_no_id',
								email='$_email',
								kota='$_kota',
								provinsi='$_propinsi',
								merk_rokok_1='$_merk_rokok_1',
								merk_rokok_2='$_merk_rokok_2',
								acc_social_media='$_acc_social_media'";
					
					if($_password != ''){
						$qry2 = "SELECT dm.*,m.type FROM social_member m LEFT JOIN dm_member dm ON m.register_id=dm.id WHERE m.id=$id";
						$list = $this->fetch($qry2);
						$salt = $list['salt'];
						$hash = sha1($_password.$list['username'].$salt);
						$qry .= ", password='$hash'";
					}
					$qry .= " WHERE id='$_regid'";
					//echo $qry;exit;
					if($this->query($qry)){
						$qry = "UPDATE social_member SET
									name='$_nama',
									email='$_email'
									WHERE
									id='$id'";
						if($this->query($qry)){
							return $this->View->showMessage("Success","?s=member");
						}else{
							$this->View->assign('msg','Error, please try again!');
							echo mysql_error();
							exit;
						}
					}else{
						$this->View->assign('msg','Error, please try again!');
						echo mysql_error();
						exit;
					}
					
				}else{
					$this->View->assign('msg','Error, please fill all field!');
				}
			}
			$qry = "SELECT dm.*,m.type FROM social_member m LEFT JOIN dm_member dm ON m.register_id=dm.id WHERE m.id=$id";
			$list = $this->fetch($qry);
			$this->View->assign('list',$list);
			$this->close();
			return $this->View->toString(APPLICATION.'/edit-profile.html');
		}else{
			sendRedirect("index.php");
			die();
		}
	}
	function updatepicture(){
		global $ENGINE_PATH;
		require_once $ENGINE_PATH.'/Utility/phpthumb/ThumbLib.inc.php';
		try{ $thumb = PhpThumbFactory::create( $_FILES['avatar']['tmp_name'] );	}catch (Exception $e){}
		
		$img=$_FILES["avatar"]["name"];
		//print_r($_FILES);exit;
		
		if ($img==""){
	    	return $this->View->showMessage('Choose image please', "index.php"); 
		}else{
				
				if(!is_dir("contents")){
					mkdir("contents");
				}
				if(!is_dir("contents/avatar")){
					mkdir("contents/avatar");
				}
				
				$ext = strtolower(end(explode('.',$_FILES["avatar"]["name"])));
				//echo "extension ".$ext;exit;
				
				if($ext == 'jpg' || $ext == 'png' || $ext == 'gif' || $ext == 'bmp'){
					$time = time();
					$name = $this->user['id']."_". $time.".".$ext;
					$origin = $this->user['id']."_origin.".$ext;
					$thumb->AdaptiveResize(121,171);
					$thumb->save( "contents/avatar/".$name);
					move_uploaded_file($_FILES['avatar']['tmp_name'],"contents/avatar/".$origin);
					
					$qry = "UPDATE social_member SET img='contents/avatar/$name' WHERE id=".$this->user['id'];
					$this->open(0);
					$this->query($qry);
					$this->close();
					
					$msg = "Update picture success.";
					
					//News edit avatar
					$this->news = new newsHelper($this->user['register_id']);
					$this->news->editAvatar();
					
				}else{
					$msg = "Only JPG,PNG,GIF & BMP please!";	
				}
				return $this->View->showMessage($msg, "index.php?page=profile");		
		}
	}
	
	function editdescription(){
		$id = $this->user['id'];
		//echo $id;exit;
		$req = $this->Request;
		if( $id != '' ){
			$this->open(0);
			if($req->getParam('edit') == 1){
				$_desc = mysql_escape_string($req->getParam('desc'));
				
				if( $id > 0 && $_desc != ''){
					
					$qry = "UPDATE social_member SET description='$_desc' WHERE id='$id'";
					if($this->query($qry)){
						//return $this->View->showMessage("Success","?page=profile");
						sendRedirect('index.php?page=profile');
						exit;
					}else{
						$this->View->assign('msg','Error, please try again!');
					}
				}else{
					$this->View->assign('msg','Error, please fill all field!');
				}
			}
			$qry = "SELECT * FROM social_member WHERE id=$id";
			$list = $this->fetch($qry);
			$this->View->assign('list',$list);
			$this->close();
			return $this->View->toString(APPLICATION.'/edit-description.html');
		}else{
			sendRedirect("index.php");
			die();
		}
	}
	
}