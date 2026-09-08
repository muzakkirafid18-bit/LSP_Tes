<html>
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


<?php
//session_set_cookie_params(3600*2,"/");
session_start();
include "../lsp_koneksi.php";
if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
 echo "<center><link href='css/adminstyle.css' rel='stylesheet' type='text/css'>
 <strong> Anda Harus Login Dahulu ...!</strong><br>";
 echo "<a href=../lsp_login.php class='btn btn-primary btn-sm' role='button'> Kembali</a></center>"; 
}
else{
//echo "lklksd";
	$emailuser=trim($_GET['emaila']);
	$sql="select * from lsp_usertbl where email=$emailuser";
        //echo $sql;
	//echo $emailuser; 	
echo "<table>";
	$shasil=mysql_query($sql);
	$sdata=mysql_fetch_array($shasil);
	$namap=$sdata['nama'];
	$idp=$sdata['id'];
        //echo $idp;
	$cek="select * from skemasiswa where emailsiswa='$emailuser' and statustesp='N'"; 
	$ada=mysql_query($cek);
	$data  = mysql_fetch_array($ada);
	$skema=$data['idskema'];
	$statusapl1=$data['statusapl1'];
	$spemet="select * from pemetaan where idpeserta='$idp'";
        $execpe=mysql_query($spemet);
        $hpemet=mysql_fetch_array($execpe);
        $namaass=$hpemet["namaasesor"];
        //$tgl=$hpemet["tanggal"];
  $ske="select * from skema where idskema='$skema'";  
  $ske1=mysql_query($ske);
  $ske2=mysql_fetch_array($ske1);
  $namaskema=$ske2['namaskema'];
  
  echo "<table width=95%><tr><td colspan=5>FR-MAK-05 UMPAN BALIK</td></tr><tr><td colspan=2>
        Nama Peserta : $namap </td><td colspan=3> </td></tr><tr>
        <td colspan=2>Nama Asesor : $namaass<td colspan=3></td></tr>             
        ";
        
   
      $i=0;
      $num=1;
    echo "<input type=hidden name=idskema value='$skema'>";
    echo "<input type=hidden name=idadsesi value='$idp'>";
    echo "<input type=hidden name=email value='$emailuser'>";
    $sqlqmak5="SELECT mak5.idasesi,mak5.idqmak5,mak5.hasil,mak5.catatan,mak5.catatanlain,qmak5.qmak5 FROM mak5 inner join qmak5 on mak5.idqmak5=qmak5.idqmak5 where mak5.idasesi=$emailuser";
    //echo $sqlqmak5;
    $sqlqmak5a=mysql_query($sqlqmak5);
        echo "<tr><th rowspan=2 align=center>NO</th><th rowspan=2>KOMPONEN</th><th colspan=2>Hasil</th><th rowspan=2>Catatan/Komentar Asesi</th></tr>";
    echo "<tr><td>Ya</td><td>Tidak</td></tr>";
    		while($sqlqmak5b=mysql_fetch_array($sqlqmak5a)){
	        $cekteaabc=trim($sqlqmak5b['hasil']);
                $catatan=$sqlqmak5b['catatan'];
                $clain=$sqlqmak5b['catatanlain'];
		//echo $cektea;
		if (trim($sqlqmak5b['hasil'])=='y'){
		        $ycek="YA";
                        }
                else {$ycek="TIDAK";}
    
            echo "<tr><td>".$num."</td><td>".$sqlqmak5b['qmak5']."</td><td colspan=2><input type=text name=mak5yt ".$i." value='$ycek' disabled=disabled></td><td><input type=text name=catatan".$i." value='$catatan' disabled=disabled><input type=hidden name=idqm5".$i." value=".$sqlqmak5b['idqmak5']."></tr>";
		$num++;
                $i++;
		}
        echo "<tr><td colspan=5>Catatan lain kalau ada <input type=text name=catl value='$clain'disabled=disabled ></td></tr>";
	

}
?>
</table>



</html>
