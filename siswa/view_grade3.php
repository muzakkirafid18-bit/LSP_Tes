<script type="text/javascript" src="../js/dropdowncontent.js"></script>
<script type="text/javascript" src="../js/textsizer.js"></script>

<html>
<head>
<title>Grade .</title>



<link rel="stylesheet" href="../css/tabel2.css" type="text/css"/>



		
</head>
<body>
<div class="CSS_Table_Example" style="width:850px;height:150px;" >

<?php
session_start();
//error_reporting(0);
include "../lsp_koneksi.php";
echo "<form action=logout.php method=post >";
$cektgl=date("Y-m-d");
//echo "asasa";
$resultv="SELECT * FROM grade WHERE nim='$_SESSION[username]' ";
$result=mysql_query($resultv);
//echo "lakslak".$resultv;

$jumlahbaris=mysql_num_rows($result);
if($jumlahbaris<=0){
echo"<div align=\"center\" class=\"style1 style2\">";
echo"<br><br>";
echo"Anda Belum mengikuti Ujian";
echo"</div>";
echo"<br><br>";
echo"<br><br>";
}else{
?>
<br/>
<table >
<tr><th colspan="10"> Daftar Nilai Ujian</th></tr>


				<tr> 
					<th>
						N I S
					</th>
					<th >
						Nama Siswa
					</th>
					<th >
						Kode Unit
					</th>
					<th>
						Nama Unit
					</th>
					<th >
						Benar
					</th>
					<th>
						Salah
					</th>
					<th >
						JML Soal
					</th>
					<th>
						Nilai 
					</th>
					<th >
						Tanggal
					</th>
					<th>
						Ket.
					</th>
				</tr>
  <?php 
while($row=mysql_fetch_array($result)) {
$nim=$row["nim"];
$nama=$row["nama"];
$modul=$row["modul"];
$benar=$row["benar"];
$salah=$row["salah"];
$jumlah_soal=$row["jumlah_soal"];
$nilai=number_format($row["grade"],2);
$waktu=$row["tanggal"];
$kkm=$row["kkm"];
$kdmodul=$row["kd_modul"];
if ($nilai>=$kkm) { $ket="Kompeten";}
else { $ket="Belum Kompeten";}
?>
  <tr> 
    <td align="center"><?php echo"$nim";?></td>
    <td><?php echo"$nama";?></td>
    <td><?php echo"$kdmodul";?></td>
    <td><?php echo"$modul";?></td>
    <td><?php echo"$benar";?></td>
    <td><?php echo"$salah";?></td>
    <td><?php echo"$jumlah_soal";?></td>
    <td><?php echo"$nilai";?></td>
    <td><?php echo"$waktu";?></td>
    
	<td><?php echo"$ket";?></td>
  </tr>
  <?php } ?></table>
    <center>
    <!--<input id="gobutton" nama="close" type="submit" value="LOGOUT" >-->
    </center>
<?php }?>
</form>
</body>
</html>
