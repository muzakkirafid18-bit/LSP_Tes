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
$cekkodemodul=$data->val(2, 2);
$cekdata="select kd_modul from pertanyaanfeedback where kd_modul='".$cekkodemodul."'";

$ada=mysql_query($cekdata);
if(mysql_num_rows($ada)>0)
{ 
  echo"<script>alert('Unit pernah di upload ..!');history.go(-1);</script>";
  //echo "</br>Data dengan kode <strong>".$cekkodemodul."</strong> sudah pernah di upload.<p>
 //Coba periksa kembali ...</p>";
  //echo "<p> <a href=adminmenu.php><img src=img/arrow-left2.png>Kembali</a> </p>"; 
}
else
{ 
 // import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
 for ($i=2; $i<=$baris; $i++)
{
   $no=1;
// membaca data nim (kolom ke-1)
//$kelompok = $data->val($i, 1);
   $kodemodul = $data->val($i, 2);
   $question = $data->val($i, 3);
   $answer = $data->val($i, 4);
   $alt_1 = $data->val($i, 5);
   $alt_2 = $data->val($i, 6);
   $alt_3 = $data->val($i, 7);
   $alt_4 = $data->val($i, 8);


    if(empty($kodemodul)){
      $baris=$i;
      }else {
$query="insert into pertanyaanfeedback (question_id,kd_modul,question,answer,oa,ob,oc,od,oe,alt_1,alt_2,alt_3,alt_4,format)values ('','$kodemodul','$question','$answer','5','4','3','2','1','$alt_1','$alt_2','$alt_3','$alt_4','')";
//echo $query;
$hasil = mysql_query($query);
} 
// jika proses insert data sukses, maka counter $sukses bertambah
// jika gagal, maka counter $gagal yang bertambah

if ($hasil) $sukses++;
else $gagal++;
}

$sql1 = "select * from pertanyaanfeedback where kd_modul='$kodemodul'";
$rsd11 = mysql_query($sql1);
$hitung=0;
while (($row=mysql_fetch_array($rsd11))) {
  	$id = $row["question_id"]; 
	$kd_modul=$row["kd_modul"];
	$question = $row["question"]; 
	$oa=$row["oa"];
	$ob=$row["ob"];
	$oc=$row["oc"];
	$od=$row["od"];
	$oe=$row["oe"];	
	$alt_5=$row["answer"];
	$alt_1=$row["alt_1"];
	$alt_2=$row["alt_2"];
	$alt_3=$row["alt_3"];
	$alt_4=$row["alt_4"];
        $alt_6=$row["njawab"];
	$input=array($alt_1,$alt_2,$alt_3,$alt_4,$alt_5);
	$noption=array($ob,$oc,$od,$oe,$oa);

        for ($j = 0; $j < 5; $j++){  
        //echo "tt".$j;
	$p=$input[$j];
	$o=$noption[$j];
$query2="insert into tbloptionfeed (question_id,kd_modul,noption,toption)values($id,'$kodemodul','$o','$p')";
//echo $query2;
$hasil2 = mysql_query($query2);
//$hitung++;
}
	//sort($input, SORT_STRING);
	//srand ((float)microtime()*1000000);
	//shuffle($input);


} 

// tampilan status sukses dan gagal
//echo "<a href=adminmenu.php><img src=img/arrow-left2.png>Kembali</a>";
echo "<p><h3>Proses import data pertanyaan dengan kode".$kodemodul." selesai.</h3></p>";
echo "<p>Jumlah data yang sukses diimport : ".$sukses."<br>";
echo "Jumlah data yang gagal diimport : ".$gagal."</p>";
echo"<script>alert('Proses import selesai ..!');history.go(-1);</script>";
//echo $query2;

}
echo "</div>";

?>
</html>
