<?php 

include "../lsp_koneksi.php";
$email=trim($_POST['email']);
//$ids=$_POST['idskema'];
$idasesi=$_POST['idadsesi'];
//$tgl = date('Y-m-d H:i:s');

$n = $_POST['n'];
$tgl=$_POST['tanggal'];
$idskema=$_POST['idskema'];
//echo "kkk";
for ($i=0; $i<=$n-1; $i++)
   {
    //echo "aa";
     if (isset($_POST['bk'.$i]))
     {
     $mak5yt=$_POST['mak5yt'.$i];
     $cekdulumak5="select * from mak5 where idasesi='$idasesi'";
     $cekdulumak5a=mysql_query($cekdulumak5);
     if(mysql_num_rows($cekdulumak5a)>0){
       
 	}
      }
    }
?>
