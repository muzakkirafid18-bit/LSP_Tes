<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Daftar Nilai</title>
<link href="../css/tabel.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
error_reporting(0);
include "../lsp_koneksi.php";
$fkdmodul=$_POST['kdmodul'];
$fkelas=$_POST['kelas'];
$tgl=$_POST['tgl'];
$sql1="SELECT * from modul where kd_modul='".$fkdmodul."'";
$result1=mysql_query($sql1);
$row1=mysql_fetch_array($result1);
$nmodul=$row1[modul];

?>
<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->
</br>
<input  id=gobutton nama=dbantal type=button value=KEMBALI onclick=self.history.back()>
<form method=POST action='export.php'>

      <input type="submit" value="Export ke Xls">
      <input type="hidden" name="tanggal" value="<?php echo $tgl ;?>">
      </form></th></tr>
</table>
<!--<input type="button" value="REFRESH" onclick="window.location='ceklagionline.php'"></th></tr></table>-->
<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->
<tr> 
<th> No </th>
<th> Kode Unit </th>
<th> Nama Unti </th>
<th> Email </th>
<th> Nama</th>
<th> Salah</th>
<th>Benar</th>
<th>Jml. Soal</th>
<th>Nilai</th>
<th>Tanggal</th>

<th>Ket.</th>
<tr>

<?php
//if($_POST['sqlaction']=="SEARCH"){
//$sql="Select * from useronline WHERE ".
//$_POST['txtkriteria']." LIKE '%".
//$_POST['txtcari']."%' limit 3";
//} else {
if (!empty($tgl)){
$sql="SELECT grade.kd_modul, grade.nim, grade.salah, grade.benar, grade.jumlah_soal, grade.grade, grade.tanggal, lsp_usertbl.nama,grade.kelas,unitalias.namaalias
FROM grade
JOIN lsp_usertbl ON grade.nim = lsp_usertbl.email join unitalias on grade.kelas=unitalias.kdalias where grade.tanggal='".$tgl."'";
}
//echo $sql;
//}

$result=mysql_query($sql);

$number=1;
while ($row=mysql_fetch_array($result)){
if($row[grade]<$row[kkm]){
  $tl="Belum kompeten";}
  else {
  $tl="Kompeten";}
//echo "lll";
echo "<tr>";
echo "<td>".$number."</td>";
echo "<td>".$row[kelas]."</td>";
echo "<td align=left>".$row[namaalias]."</td>";
echo "<td>".$row[nim]."</td>";
echo "<td>".$row[nama]."</td>";
echo "<td>".$row[salah]."</td>";
echo "<td>".$row[benar]."</td>";
echo "<td>".$row[jumlah_soal]."</td>";
echo "<td>".$row[grade]."</td>";
echo "<td>".$row[tanggal]."</td>";


//echo "<td>".$row[kelas]."</td>";
echo "<td>".$tl."</td>";
//echo "<td align=center><a href=delete.php?nis=$row[2] & sqlaction=DELETE>HAPUS</a>"; 
$number++;
}
?>
</table>
</form>
</body>
</html>
