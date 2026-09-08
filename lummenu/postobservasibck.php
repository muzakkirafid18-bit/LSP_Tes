<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<table cellspacing='0'><tr><td>

<?php
session_start();
include "../lsp_koneksi.php";
//$email=trim($_POST['email']);
$ids=$_POST['idskema'];
$idasesi=$_POST['idadsesi'];
$idasesor=$_POST['idasesor'];
$tgl=$_POST['tgl'];
//echo "oooo";

$n = $_POST['n'];
$x = $_POST['n'];
$sukses=0;
$gagal=0;
for ($i=0; $i<=$n-1; $i++)
   {
     
     if (isset($_POST['pcp'.$i]) and isset($_POST['bk'.$i]) )
     {
	   //echo $i;
       	$idunit = $_POST['idunit'.$i];
       	$idpraktek = $_POST['idpraktek'.$i];
       	$bk=$_POST['bk'.$i];
       	$yt=$_POST['pcp'.$i];
        $kodeunit=$_POST['kodeunit'.$i];
        $cekdata="select * from rekappraktek where idskema='".$ids."' and idunit='".$idunit."' and idpraktek='".$idpraktek."' and idadsesi='".$idasesi."' and idasesor='".$idasesor."'";
	 //echo $cekdata."</br>";
        $ada=mysql_query($cekdata);
	if(mysql_num_rows($ada)>0){
          echo "Terjadi duplikate</br>";
          }else { 
	 
	//echo $ids.",".$idasesi.",".$idasesor.",".$idunit.",".$idpraktek.",".$bk.",".$yt.",";
        $ssql="insert into rekappraktek value('','$ids','$idunit','$kodeunit','$idpraktek','$idasesor','$idasesi','$yt','$bk','$tgl')";
        //echo $ssql;
        $ok=mysql_query($ssql);	
        if ($ok) { 
                 $sukses++;
                 $updatesksiswa="update skemasiswa set status='Y' where emailsiswa='$email' and idskema='$ids'";}
               }
		// else $gagal=$x-$sukses;
     } //else { echo"<script>alert('Tidak ada/kuranglengkap pilihan !');history.go(-1);</script>";}     
 
}
// echo"<script>alert('proses selesai.... !');history.go(-2);</script>";
$gagal=$x-$sukses;
echo "Sukses :".$sukses ;
echo "Gagal  :".$gagal;

echo "<form name=contoh method=post action=observasi.php?op=listpeserta enctype=multipart/form-data id=form-upload>";
echo "<input type=text name=tgl value='$tgl'>";
echo "<input type=text name=idskema value='$ids'>";
echo "<input type=text name=kelompok value='$kelompok'>";

?>
<input type="submit" id="gobutton" value="Lanjutkan" class="button"> 
</form>
<?php

?>

</td></tr>

</table>
