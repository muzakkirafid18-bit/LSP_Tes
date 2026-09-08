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
 
// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
$sukses = 0;
$gagal = 0;

//if(mysql_num_rows($ada)>0)
//{ 
//  echo "</br>Data dengan  ".$cekuser." sudah pernah di upload.<p>
// Coba periksa kembali ...</p>";
 // echo "<p><a href=adminmenu.php><img src=img/arrow-left2.png>Kembali</a></p>";
//}
//else
//{ 
 // import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
 for ($i=2; $i<=$baris; $i++)
{
      $cekuser=trim($data->val($i, 3));
      $cekdata="select email from lsp_usertbl where trim(email)='".$cekuser."'";
      $ada=mysql_query($cekdata);
      //$abc=mysql_fetch_array($ada);
      //echo $abc['email'];
      $no=1;
// membaca data nim (kolom ke-1)
//$kelompok = $data->val($i, 1);
   $nama = $data->val($i, 2);
   $nim = trim($data->val($i, 3));
   //$email = $data->val($i, 4);
   $password = $data->val($i, 4);
   $status= $data->val($i, 5);
   $kode= $data->val($i, 6);
   $level= $data->val($i, 7);
   $notlp= $data->val($i, 8);
   $pass1=md5($password);
   //echo $nim;
   //echo 
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


    if(empty($nim)){
      $baris=$i;
      }else {
       if(mysql_num_rows($ada)>0)
      {  echo "Duplikat atas nama ". $nama."</br>";
         echo $query;
         $gagal++;
       }else{
      
$query="insert into lsp_usertbl (id_user,nama,email,password,status,kode,level,notelp)values ('','$nama','$nim','$pass1','$status','$kode','$level','$notlp')";
$hasil = mysql_query($query);
} 
// jika proses insert data sukses, maka counter $sukses bertambah
// jika gagal, maka counter $gagal yang bertambah
if ($hasil) { $sukses++;
   } else {
   echo $query;}
    
   //echo "Gagal ...";
  }
}
// tampilan status sukses dan gagal
echo "<p><a href=adminmenu.php><img src=img/arrow-left2.png>Kembali</a></p>";
echo "<h3>Proses import data selesai.</h3>";
echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
echo "Jumlah data yang gagal diimporttttt : ".$gagal."</p>";
//}
echo "</div>";

?>
</html>
