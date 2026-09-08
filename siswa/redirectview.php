<link href='css/tabel.css' rel='stylesheet' type='text/css'>
<?php
//cek lanjutan dari menu sebelumnya apa sudah sesuai semuanya
session_start();
include "connect.php";
if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
 echo "<link href='css/adminstyle.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
 header('location:index.php');
 //echo "sss";
}
else{
if(isset($_SESSION['username'])) { $uname=$_SESSION['username'];}
$abc=trim($_POST[kmd]);
$resultxx="SELECT * FROM modul WHERE kd_modul='$abc'";
$resultx=mysql_query($resultxx);
echo $resultxx;
$cketemu = mysql_num_rows($resultx);
echo "lklsdk".$cketemu;


$barisx=mysql_fetch_array($resultx);
$cekada  = mysql_query("SELECT * FROM pertanyaanbck WHERE nim='$uname' AND kd_modul='$_GET[kmd]'");
      $cekada1 = mysql_num_rows($cekada);
     //if ($cekada1> 0){
       $r="pagination.php"; //}
     //else{ $r="view_assesment.php";} 
 ?>

	<form method="GET" action="<?php echo $r ; ?>">
<?php
// muncul verifikasi soal sesuai tidak
echo "<center>";
echo "<img src=img/logo.png height=80px>";
echo "<table width=60%>";	
        echo "<tr><th colspan=3> TES ONLINE </th></tr>";
        echo "<tr><td> SOAL </td><td> : </td><td>" .$barisx[modul]. "</td></tr>";
        echo "<tr><td> Jumlah Soal  </td><td> : </td><td>" .$barisx[jumlah_soal]. "</td></tr>";
	echo "<tr><td> Waktu  </td><td> : </td><td>" .$barisx[Waktu]. " Menit </td></tr>"; 
        echo "<input name=md id=md type=hidden value='$_GET[kmd]'";
        echo "<tr><td colspan=3> * Batalkan jika ada ketidak sesuaian </td></tr>";
        echo "</br>";
        echo "<tr><td colspan=3><input id=gobutton type=submit value=Lanjutkan>"; ?>    
	<input id="gobutton" type="button" onclick="window.history.back()" value="Batalkan"></td></tr>
<?php
 
}?>

