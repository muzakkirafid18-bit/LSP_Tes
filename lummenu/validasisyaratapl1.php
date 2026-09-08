<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LSP - Dashboard</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="css/adminstyle.css" rel="stylesheet">
<link href='../css/tombol.css' rel='stylesheet' type='text/css'>
<script src="js/lumino.glyphs.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>
<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script language="javascript"></script>
</head>

<?php
include "../lsp_koneksi.php";
$op = $_GET['op'];

if ($op == "simpanceklistyarat")
{
echo "oook";
}
//$idskema=$_GET['idskema'];
//$idasesi=$_GET['idasesi'];
$emailsy=$_GET['emailps'];
//echo "vsyarat";
echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=simpanceklistyarat\">";
$ceksya="select * from skemasiswa where emailsiswa='$emailsy' and statustesp='N' and statusapl2='N'"; 
 $adasya=mysql_query($ceksya);
 $execsy=mysql_fetch_array($adasya);
 $idskemasya=$execsy['idskema'];

 $listsy="select * from syarat where idskema='$idskemasya' and kodesyarat='1'";
 $listsya=mysql_query($listsy);
 
 echo "<table class=demo-table  width=90%>";
 echo "<tr><th colspan=5>Bukti kelengkapan persyaratan dasar permohonan</th></tr>";
 echo "<tr><th rowspan=2 align=center bgcolor='#006699'>NO</th><th rowspan=2 bgcolor='#006699'>Bukti persyaratan </th><th colspan=2 bgcolor='#006699'>Ada</th><th rowspan=2 bgcolor='#006699'>Tidak ada</th></tr>";
    echo "<tr><th bgcolor='#006699'>Memenuhi syarat</td><th bgcolor='#006699'>Tidak memenuhi syarat</td></tr>";
 $nom=1;
 $ii=0;
 while($listsyb=mysql_fetch_array($listsya)){
    $idsyarata=$listsyb['idsyarat'];
    echo "<tr><td>".$nom."</td>";
    echo "<td>".$listsyb['syarat']."</td>
    <td><input type=checkbox name=adamsya".$ii." value='Y'></td><td><input type=checkbox name=adatmsy".$ii." value='T'></td><td><input type=checkbox name=tdada".$ii." value='T'></td></tr>";
    $nom++;
    $ii++;
 } 
 echo "<table class=demo-table  width=90%>";
 echo "<tr><th colspan=5>Bukti kelengkapan kompetensi relevan</th></tr>";
 echo "<tr><th rowspan=2 align=center bgcolor='#006699'>NO</th><th rowspan=2 bgcolor='#006699'>Bukti persyaratan </th><th colspan=2 bgcolor='#006699'>Lampiran bukti</th></tr>";
    echo "<tr><th bgcolor='#006699'>Ada</td><th bgcolor='#006699'>Tidak Ada</td></tr>";
 $listsyb="select * from syarat where idskema='$idskemasya' and kodesyarat='2'";
 $listsyab=mysql_query($listsyb);
 
 $nomb=1;
 $iib=0;
 
 while($listsybb=mysql_fetch_array($listsyab)){
    $idsyaratab=$listsybb['idsyarat'];
    echo "<tr><td>".$nomb."</td>";
    echo "<td>".$listsybb['syarat']."</td>
    <td><input type=checkbox name=adamsya".$iib." value='Y'></td><td><input type=checkbox name=adatmsy".$iib." value='T'></td></tr>";
    $nomb++;
    $iib++;
 } 
 echo "</tr><tr><td colspan=5><input type='hidden' name='na' value='".$ii."'>
 <input type='hidden' nama='nb' value='".$iib."'> 
 <input type=submit name=simpan id=gobutton value=Simpan></td></tr></table>";

?>
     </html>
