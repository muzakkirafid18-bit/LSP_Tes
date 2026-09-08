<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<table cellspacing='0'><tr><td>

<?php
session_start();
include "../lsp_koneksi.php";

$email=trim($_POST['email']);
$ids=$_POST['idskema'];
$idasesi=$_POST['idadsesi'];
$idasesor=$_POST['idasesor'];
$tgl=$_POST['tgl'];
$kelompok=$_POST['kelompok'];
$n = $_POST['n'];
$sukses=0;
$gagal=0;
$ntot=0;
//echo $kelompok;
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
                // echo "abc".$aahitung;
         //if($bk=='K'){
        //  $ni=50;}
        //  else {$ni=0;}
	$aaunit=$_POST['aaunit'.$idunit];
        if($yt=='Y'){
            	
        	$aahitung=(100/$aaunit);
	}
        else { $aahitung=0;}
        // $ntot=$ni+$ny;

        $cekdata="select * from rekappraktek where idskema='".$ids."' and idunit='".$idunit."' and idpraktek='".$idpraktek."' and idadsesi='".$idasesi."' and idasesor='".$idasesor."'";
	 //echo $cekdata."</br>";
        $ada=mysql_query($cekdata);
	if(mysql_num_rows($ada)>0){
          echo "Terjadi duplikate</br>";
          }else { 
	 
	//echo $ids.",".$idasesi.",".$idasesor.",".$idunit.",".$idpraktek.",".$bk.",".$yt.",";
        $ssqlrekapp="insert into rekappraktek value('','$ids','$idunit','$kodeunit','$idpraktek','$idasesor','$idasesi','$yt','$bk','$tgl','$aahitung','$aaunit')";
        //echo $ssqlrekapp;
        $okrekapp=mysql_query($ssqlrekapp);	
        if ($okrekapp) {
		$sukses++;
		$updatesksiswa="update skemasiswa set statustest='Y' where emailsiswa='$email' and idskema='$ids'";
                $execupdate=mysql_query($updatesksiswa);}
               }
		// else $gagal=$x-$sukses;
     } //else { echo"<script>alert('Tidak ada/kuranglengkap pilihan !');history.go(-1);</script>";}     
 
}
// echo"<script>alert('proses selesai.... !');history.go(-2);</script>";
$gagal=$n-$sukses;
echo "Sukses :".$sukses ;
echo "</br>Gagal  :".$gagal;?>
<form id="formContoh" method="POST" action="observasi.php?op=listpeserta">
<?php
echo "<input type=hidden name=tgl value='$tgl'>";
echo "<input type=hidden name=idskema value='$ids'>";
echo "<input type=hidden name=kelompok value='$kelompok'>";
echo "<input type=hidden name=idasesor value='$idasesor'>";
?>
</br><input type="submit" id="gobutton" value="Proses selesai.. Lanjutkan" class="button"> 


