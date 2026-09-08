<link href='css/tabel.css' rel='stylesheet' type='text/css'>
<table>

<?php
session_start();
  include "connect.php";
  $nim=$_POST['nim'];
  $pass=$_POST['password'];
  //echo $nim;  
if (empty($pass)){

   if($_SESSION['level']=='user'){
   echo "<br /> <tr><td><a href=editpassword.php title=kembali style=height:21px;line-height:21px;><img src=img/arrow-left2.png alt=/>Kembali</a></td></tr><tr> <th><h3>Tidak ada perubahan password</h3></th></tr>";}	
else {
echo "<br /> <tr><td><a href=radmin/daftarsiswa.php title=kembali style=height:21px;line-height:21px;><img src=img/arrow-left2.png alt=/>Kembali</a></td></tr><tr><th><h3>Tidak ada perubahan password</h3> </th></tr>";}	
}
//ini kalo passwordnya di ubah bray
	else{
		$pass1=md5($pass);
		$sql="UPDATE user SET password='$pass1' where nim='$nim'";
                //echo $sql;
                mysql_query($sql);
                if($_SESSION['level']=='user'){
		echo "<br /> <tr><td><a href=editpassword.php title=kembali style=height:21px;line-height:21px;><img src=img/arrow-left2.png alt=/>Kembali</a></td></tr><tr><th><h3>Update Perubahan Password Sukses ..!</h3> </th></tr>";}
                else {
echo "<br /> <tr><td><a href=radmin/daftarsiswa.php title=kembali style=height:21px;line-height:21px;><img src=img/arrow-left2.png alt=/>Kembali</a></td></tr><tr><th><h3>Update Perubahan Password Sukses ..!</h3> </th></tr>";}
}
?> 
