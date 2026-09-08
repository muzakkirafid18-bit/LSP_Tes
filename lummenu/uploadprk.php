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
//$duplikat=0;
   $cekidskema = $data->val(2, 2);
   $cekkodeunit = $data->val(2, 3);
   $cekdata="select kodeunit from praktek where kodeunit='".$cekkodeunit."' and idskema='".$cekidskema."'";
   //echo $cekdata."</br>";
//$cekkodeunit=$data->val(2, 3);
//$cekdata="select kodeunit from praktek where kodeunit='".$cekkodeunit."'";

$ada=mysql_query($cekdata);
if(mysql_num_rows($ada)>0)
{ 
//  echo"<script>alert('Sudah Ada ..!');history.go(-1);</script>";
$duplikat="Terjadi Duplikasi data ...";
$kodeunitab=$cekkodeunit;
}
else
{ 
 // import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
 for ($i=2; $i<=$baris; $i++)
{
   $no=1;
// membaca data nim (kolom ke-1)
//$kelompok = $data->val($i, 1);
   $idskema = $data->val($i, 2);
   $kodeunit = $data->val($i, 3);
   $instruksi = $data->val($i, 4);
   $observasi = $data->val($i, 5);
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

    //echo $kodeunit;
    if(empty($kodeunit)){
      $baris=$i;
      }else {
       	//$cekkodeunit=$data->val($i, 3);
	
        //$ada=mysql_query($cekdata);
	//if(mysql_num_rows($ada)>0){
          // $hasil="";
           //}else{
		$query="insert into praktek (idpraktek,idskema,kodeunit,instruksi,obervasi)values ('','$idskema','$kodeunit','$instruksi','$observasi')";
//}
//echo $query;
		$hasil = mysql_query($query);
} 
// jika proses insert data sukses, maka counter $sukses bertambah
// jika gagal, maka counter $gagal yang bertambah

if ($hasil) $sukses++;
else $gagal++;
}
}
// tampilan status sukses dan gagal
//echo "<a href=adminmenu.php><img src=img/arrow-left2.png>Kembali</a>";
//echo "<p><h3>Informasi proses...</h3></p>";
//echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
//echo "Jumlah data yang gagal diimport : ".$gagal."</p>";
echo"<script>alert('Proses import selesai ..!'+' Sukses '+'$sukses'+' Gagal '+'$gagal'+' $duplikat' + '$cekkodeunitab ');history.go(-1);</script>";
//echo $query2;

//}
echo "</div>";

?>
</html>
