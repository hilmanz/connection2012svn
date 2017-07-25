<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HAPUS ACCOUNT SAYA</title>
<link href="css/sba_login.css" rel="stylesheet" type="text/css" />

</head>
<body id="landing"> 
<div id="wrapper-login">
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<div align="center">
        <div style="text-align:left; width:350px;" align="center">
          <h2><strong>Hapus Account Saya </strong> </h2>
          <P> Mohon masukkan username dan password kamu, untuk memastikan bahwa kamu ingin menghapus account kamu.</P>
          <P>Mohon masukkan detail kamu.</P>
          <form name="hapus_saya" method="post">
          <div style="padding:10px 0; clear:both;"> <strong style="width:100px; display:block; float:left;">Username *</strong>
            <input style="float:left; border-bottom:1px dashed #C6AC00;" type="text" name="username" />
          </div>
          <div style="padding:10px 0; clear:both;"> <strong style="width:100px; display:block; float:left;">Password *</strong>
            <input type="password" style="border-bottom:1px dashed #C6AC00;" name="password" />
          </div>
          <span style="dispx 0; clear:both;">
          <input type="checkbox" name="checkbox" id="checkbox" value="checked"/>
          <label for="checkbox"></label>
          Konfirmasi bahwa kamu ingin menghapus <br />
		  </span> 
          <br />
    	<input type="submit" class="btnKirim" value="Kirim" name="submit" />
		</form>
	</div>
<?php
include_once 'common.php';
$db=new SQLData();
if(isset($_POST['submit'])){
	if($_POST['username']==""){
		echo "masukkan user name";
	}
	elseif ($_POST['password']==""){
		echo "Masukkan Password";
	}
	else{
		$sql="SELECT * FROM dm_member WHERE email='".$_POST['username']."' AND n_status=1";
		$db->open(0);
		$rs=$db->fetch($sql);
		$db->close();
		if($_POST['username']==$rs['username']){
			if ($_POST['checkbox']=="checked"){
				$hash = sha1($_POST['password'].$_POST['username'].$rs['salt']);
				if($hash==$rs['password']){
					$query="UPDATE dm_member SET n_status=0 WHERE username='".$_POST['username']."' AND password='".$hash."'";
					$db->open(0);
					$rs=$db->query($query);
					$db->close();
					print "Sukses menghapus akun anda";
				}
				else{
					print "gagal menghapus akun anda";
				}				
			}
			else{
				print "Checked konfirmasi menghapus akun";
			}
		}
		else{
			print "salah user name";			
		}
	}
}
?>
  
 
</form></div>
  </div>    
</div>
</body>
</html>