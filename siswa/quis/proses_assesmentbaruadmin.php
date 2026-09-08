<html>
<head>
<title></title>
</style>
      
</head>
<body>
<?php 
session_start();
error_reporting(0);
include "../lsp_koneksi.php";
echo "aslaks";
$nama=trim($_POST['nama']);
$nim=trim($_POST['nim']);
//$user=$_SESSION['username'];
$kelas=trim($_POST['kelas']);
$kmdl=$_POST['kmdl'];
$kmdll=trim($_POST['kmdl']);
$kounter=$_POST['kounter'];
$resultx ="SELECT * FROM pertanyaanbck WHERE kd_modul='$kmdl' AND nim='$nim'";
$result = mysql_query ($resultx);
echo $nama;
$benar=0;
while($row=mysql_fetch_array($result)){
        $nomorsoal=$row["nomorsoal"];
	$tanggall=date("Y-m-d H:i:s");
	$answer=$row["answer"];
	$id=$row["question_id"];
        $njawab=$row["njawab"];
        //echo $njawab."</br>";
	//$q=q.$id;
	if($njawab == $answer)
 	{ 
        $benar++;
	//mysql_query("INSERT INTO catatnomor (nomorsoal,kd_modul,nim,nama,skor,tanggal,jawaban,kali) 
	//		VALUES ('$id','$kmdll','$nim','$nama',1,'$tanggall','$njawab','$kounter')");}
	//else { mysql_query("INSERT INTO catatnomor (nomorsoal,kd_modul,nim,nama,skor,tanggal,jawaban,kali) 
	//		VALUES ('$id','$kmdll','$nim','$nama',0,'$tanggall','$njawab','$kounter')");
	}		
}

$jumlah=mysql_query("SELECT * FROM modul WHERE kd_modul='$kmdl'");
$jumlah1=mysql_fetch_array($jumlah);

$jumlah_soal=$jumlah1["jumlah_soal"];
$salah=$jumlah_soal-$benar;
$nilai=(100/$jumlah_soal*$benar);
$kkm=$jumlah1["kkm"];
$modul=$jumlah1["modul"];

$result=mysql_query("SELECT * FROM user WHERE nim='$nim'");
$row=mysql_fetch_array($result);

//$nama=$row["nama"];

$tanggal=date("Y-m-d H:i:s");

//echo "bahwahh";

//mysql_query("INSERT INTO grade (id,kd_modul,modul,nim,nama,salah,benar,jumlah_soal,grade,tanggal,kelas,kkm,ujianke)VALUES ('','$kmdll','$modul','$nim','$nama','$salah','$benar','$jumlah_soal','$nilai','$tanggal','$kelas','$kkm','$kounter')");

$del = mysql_query("DELETE FROM pertanyaanbck where kd_modul='$kmdl' AND nim='$nim'");

$hasill=mysql_query("SELECT * FROM vhasil");
     $cekdulu=mysql_fetch_array($hasill);
     $cekvhasil=$cekdulu["statushasil"];
   if ($cekvhasil=="Y" OR $cekvhasil=="y"){
echo '<meta http-equiv="refresh" content="0; url=view_grade3.php">';}    
 //include"view_grade3.php";}
elseif($cekvhasil=="S" OR $cekvhasil=="s"){
	echo '<meta http-equiv="refresh" content="0; url=view_grade4.php">';}
     else {//include"view_grade2.php";}
echo '<meta http-equiv="refresh" content="0; url=view_grade2.php">';}
// header('location:view_grade2.php');}
//header('location:index2.php?module=home');  

?>
  
</body>
</html>
