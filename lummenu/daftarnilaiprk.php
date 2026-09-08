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

      <!--<input type="submit" value="Export ke Xls">-->
      <input type="hidden" name="tanggal" value="<?php echo $tgl ;?>">
      </form></th></tr>
</table>
<!--<input type="button" value="REFRESH" onclick="window.location='ceklagionline.php'"></th></tr></table>-->
<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->
<?php 
$nm="select * from pemetaan where tanggal='$tgl'";
$execnm=mysql_query($nm);
while ($raw=mysql_fetch_array($execnm)){
echo "<tr>"; 
echo "<th colspan=5>Nama Siswa : ".$raw[namapeserta]."</th>";

?>

<tr> 
<th> No </th>
<th> Idskema</th>
<th> kode Unit  </th>
<th> Nama Unit </th>
<th> Persentase </th>
<tr>

<?php
//if($_POST['sqlaction']=="SEARCH"){
//$sql="Select * from useronline WHERE ".
//$_POST['txtkriteria']." LIKE '%".
//$_POST['txtcari']."%' limit 3";
//} else {
if (!empty($tgl)){
$sql="SELECT rekappraktek.idskema,rekappraktek.kodeunit, sum(rekappraktek.niai) as j , count(rekappraktek.idunit) as c,unit.namaunit from rekappraktek inner join unit ON rekappraktek.idunit=unit.idunit where rekappraktek.tanggal='".$tgl."' and rekappraktek.idadsesi='".$raw[idpeserta]."' group by rekappraktek.idskema,rekappraktek.idunit,rekappraktek.idadsesi";
}
//echo $sql;
//}
//echo "dd";
$result=mysql_query($sql);

$number=1;
while ($row=mysql_fetch_array($result)){
//$per=(($row[j])/($row[c]*100))*100;
//echo "lll";
echo "<tr>";
echo "<td>".$number."</td>";
echo "<td>".$row[idskema]."</td>";
echo "<td>".$row[kodeunit]."</td>";
echo "<td align=left>".$row[namaunit]."</td>";
echo "<td align=right>".$row[j]."</td>";
//echo "<td>".$row[c]."</td>";
//echo "<td>"d$per."</td>";
//echo "<td>".$row[jumlah_soal]."</td>";
//echo "<td>".$row[grade]."</td>";
//echo "<td>".$row[tanggal]."</td>";
//echo "<td>".$row[kelas]."</td>";
//echo "<td>".$tl."</td>";
//echo "<td align=center><a href=delete.php?nis=$row[2] & sqlaction=DELETE>HAPUS</a>"; 
$number++;
}
}
?>
</table>
</form>
</body>
</html>
