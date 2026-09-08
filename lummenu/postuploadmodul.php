<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<!--<link href="css/adminstyle.css" rel="stylesheet" type="text/css" />-->
        <link rel="stylesheet" href="css/styleupload.css">
	<!-- Start css3menu.com HEAD section 
	<link rel="stylesheet" href="../css/style2.css" type="text/css" /><style type="text/css">._css3m{display:none}</style>-->
	<!-- End css3menu.com HEAD section -->
</head>
<?php
include "../lsp_koneksi.php";
include "excel_reader2.php";
echo "<div id=second>";
//echo "ade ";
$data = new Spreadsheet_Excel_Reader($_FILES['uploadedfile']['tmp_name']);
 
// membaca jumlah baris dari data excel
$baris = $data->rowcount($sheet_index=0);
 //echo "kajsak".$baris;
// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
$sukses = 0;
$gagal = 0;
$dupli=0;
$koddup3=' ';
$cekkodemodul=$data->val(2, 2);
//$cekdata="select kd_modul from modul where kd_modul='".$cekkodemodul."'";

//$ada=mysql_query($cekdata);
//if(mysql_num_rows($ada)>0)
//{ 
//echo"<script>alert('modul pernah di upload ..!');history.go(-1);</script>";
  //echo "<a href=uploadmodul.php><img src=img/arrow-left2.png>Kembali</a><p>";
  //echo "</br>Pertanyaan  dengan kode sudah pernah di upload.<p>
 //Coba periksa kembali ...</p>"; 
//}
//else
//{ 
 // import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
 for ($i=2; $i<=$baris; $i++)
{

  $kodemodul = $data->val($i, 2);
   $namamodul = $data->val($i, 3);
   $jmlsoal = $data->val($i, 4);
   $status = $data->val($i, 5);
   $waktu = $data->val($i, 6);
   $batas = $data->val($i, 7);
   $kkm = $data->val($i, 8);
   $tanggal=$data->val($i, 9);
   $btsawal=$data->val($i, 10);
   $btsakhir=$data->val($i, 11);
   $token="ELxxx";
  $cekdata="select kd_modul from modul where kd_modul='".$kodemodul."'";
  //$kdmdalias=substr($kodemodul,1,2);
  //echo $kdmdalias;
  //echo $cekdata ;
  $ada=mysql_query($cekdata);
   if(mysql_num_rows($ada)>0)
   {
    $koddup3=$koddup3." ".$kodemodul;
    //echo $koddup3;
   }
   else {

   $no=1;
// membaca data nim (kolom ke-1)
//$kelompok = $data->val($i, 1);
   
//echo $tanggal;
/********
"<p>";
echo $kelompok;
echo $semester;
echo $tahunpelajaran;
echo $matapelajaran;
echo $nilai_ta; 
echo $nilai_th;
echo $nilai_pa;
echo $nilai_ph;
echo $nilai_sa;
echo $nilai_sh;
**********/          
// setelah data dibaca, sisipkan ke dalam tabel mhs
   //$kodemodul=strtoupper($kodemodul);


    if(empty($kodemodul)){
      $baris=$i;
      }else {
        //$cekkdalias="select kdalias from unitalias where kdalias='$kdmdalias'";
       //echo $cekkdalias;
       // $cekkdaliasa=mysql_query($cekkdalias);
      //  $dd=mysql_num_rows($cekkdaliasa);


       // if($dd>0)
      //  {
$querym="insert into modul(kd_modul,modul,jumlah_soal,status_soal,Waktu,batas,kkm,tanggal,btsawal,btsakhir,token,acak)values ('$kodemodul','$namamodul','$jmlsoal','$status','$waktu','$batas','$kkm','$tanggal','$btsawal','$btsakhir','$token',1)";
//echo $query;
$hasil = mysql_query($querym);
 //} else 
$modulalias="insert into modulalias(idmalias,kdmodulalias) values('','$kodemodul')";
$modulaliasa=mysql_query($modulalias);
//{ echo "Kode Alias untuk kode '$kdmdalias' belum di definisikan" ;}
} 
// jika proses insert data sukses, maka counter $sukses bertambah
// jika gagal, maka counter $gagal yang bertambah
  }
if ($hasil) $sukses++;
else $gagal++;

}

// tampilan status sukses dan gagal
echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
echo "Jumlah data yang gagal diimport : ".$gagal."<br>";
echo "Duplikat Data : ".$koddup3."</p>";
echo"<script>alert('Selesai ..!');history.go(-2);</script>";
//}
echo "</div>";

?>
</html>
