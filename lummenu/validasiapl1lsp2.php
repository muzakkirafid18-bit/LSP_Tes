<!DOCTYPE html>
<?php
error_reporting(E_ALL);
session_start();
?>
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
    <button type="button" class="btn btn-primary" onclick="window.print()">Print Halaman Ini</button></br>
    
<?php
include "../lsp_koneksi.php";
// --- Bagian Penjemput Variabel ---
$emailasesi = $_GET['emailasesi'] ?? ($sqlusernb['email'] ?? "");
$idasesor   = $_GET['idasesor'] ?? "";
$kelompok   = $_GET['kelompok'] ?? "";
$tgl        = $_GET['tgl'] ?? "";
// --------------------------------
$idskema=$_GET['idskema'];
$idasesi=$_GET['idasesi'];
$sqlusern="Select * from lsp_usertbl where id='".$idasesi."'";
$sqluserna=mysqli_query($conn, $sqlusern);
$sqlusernb=mysqli_fetch_array($sqluserna);


$sqlnama = "
SELECT namasiswa 
FROM apl1 
WHERE idasesi='$idasesi'
";

$qnama = mysqli_query($conn, $sqlnama);

$datanama = mysqli_fetch_array($qnama);

echo "<h4>Nama Peserta : ".$datanama['namasiswa']."</h4>";

$sqlunit = "
SELECT 
    unitsiswa.idunit,
    unitsiswa.idskema,
    unitsiswa.idadsesi,
    unit.kodeunit,
    unit.namaunit

FROM unitsiswa

INNER JOIN unit 
ON unitsiswa.idunit = unit.idunit

WHERE unitsiswa.idskema='$idskema'
AND unitsiswa.idadsesi='$idasesi'
";

$execunit = mysqli_query($conn, $sqlunit);
        echo "<table width=95% class=demo-table><tr><th bgcolor='#006699'>No.</th><th bgcolor='#006699'>Kode Unit ( Dipilih Asesi ) </th><th bgcolor='#006699'>Nama Unit </th></tr>";
        $nourut=1;
    while ($listunit = mysqli_fetch_array($execunit))
        {
        echo "<tr><td>$nourut</td><td>".$listunit['kodeunit']."</td><td>".$listunit['namaunit']."</td></tr>";
        $nourut++;
        }
    echo "</table>";
        
		$ssql="select * from permohonan where idskema='$idskema' and id='$idasesi'";
    //echo $ssql;
    $exec=mysqli_query($conn, $ssql);
    //$listx=mysql_fetch_array($exec);
    //$namaadsesi=$listx['nama'];
    if(mysqli_num_rows($exec)>0)
    {
    
    echo "<input type=hidden name=emailasesi value='$emailasesi'>";
    echo "<table class=demo-table width=95%><tr>
    <th bgcolor='#006699'>Data Permohonan </th></tr><tr><td>";
    echo "<input type=hidden name=idasesor value='$idasesor'>";
    echo "<input type=hidden name=kelompok value='$kelompok'>";
    echo "<input type=hidden name=idskema value=$idskema><input type=hidden name=idasesi value=$idasesi><input type=hidden name=tgl value='$tgl'";
    while ($list = mysqli_fetch_array($exec)){
    $tasesmen=$list['tujuanasesmen'];
    $idpermohonan=$list['idpermohonan'];
    $sertifikasi=$list['sertifikasi'];
    $kasesment=$list['kontekasesmen'];
    $karakter=$list['karakteristik'];
    $acuan=$list['acuanp'];
    $tuk=$list['tuk'];
    $pecahta=explode(",",$tasesmen);
    $ta0=$pecahta[0];
    $ta1=$pecahta[1];
    $ta2=$pecahta[2];
    $ta3=$pecahta[3];
    $ta4=$pecahta[4];
    //echo "tasesmen=".$tasesmen."</br>";
    $pecahser=explode(",",$sertifikasi);
    $ser0=$pecahser[0];
    $ser1=$pecahser[1];
    $ser2=$pecahser[2];
    $ser3=$pecahser[3];
    //echo "serti=".$sertifikasi."</br>";
    $pecahkas=explode(",",$kasesment);
    $kas0=$pecahkas[0];
    $kas1=$pecahkas[1];
   //echo "kasesment=".$kasesment."</br>";
    $pecahkar=explode(",",$karakter);
    $kar0=$pecahkar[0];
	$kar1=$pecahkar[1];
 	$kar2=$pecahkar[2];
    //echo "karakter=".$karakter."</br>";
    $pecahac=explode(",",$acuan);
    $ac0=$pecahac[0];
    $ac1=$pecahac[1];
    $ac2=$pecahac[2];
    $ac3=$pecahac[3];
    $ac4=$pecahac[4];
    //echo "acuan=".$acuan."</br>";

       if(!empty($ta0))
        {
         $kta0="checked";
        }
		if(!empty($ta1))
        {
         $kta1="checked";
        }
		if(!empty($ta2))
        {
         $kta2="checked";
        }
		if(!empty($ta3))
        {
         $kta3="checked";
        }
		if(!empty($ta4))
        {
         
         $kta4="checked";
         $lain=$list['lainnya'];
         }
          
		 if(!empty($ser0))
        {
         $kser0="checked";
        }

		if(!empty($ser1))
        {
         $kser1="checked";
        }
		if(!empty($ser2))
        {
         $kser2="checked";
        }
		if(!empty($ser3))
        {
         $kser3="checked";
        }

		if(!empty($kas0))
        {
         $kkas0="checked";
        }
        if(!empty($kas1))
        {
         $kkas1="checked";
        }
		if(!empty($kar0))
        {
         $kkar0="checked";
        }
		if(!empty($kar1))
        {
         $kkar1="checked";
        }
		if(!empty($kar2))
        {
         $kkar2="checked";
        }

        	if(!empty($ac0))
        	{$kac0="checked";}
		
		if(!empty($ac1))
        	{ $kac1="checked";}
		

		if(!empty($ac2))
		{
		 $kac2="checked";
		}
			if(!empty($ac3))
		{
		 $kac3="checked";
		}
			if(!empty($ac4))
		{
		 $kac4="checked";
		}
         ?>
        <input type='hidden' name='idpermohonan' value=" <?php echo $idpermohonan; ?> ">
       <div class="form-group"><strong>Tujuan asesmen:</strong>
	   <div class="dynamiclabel">
	   <input type="checkbox" name="tasesmen[]" value="rpl" <?php echo $kta0; ?> disabled='disabled'> RPL
	   <input type="checkbox" name="tasesmen[]" value="ppp" <?php echo $kta1; ?> disabled='disabled'> Pecapaian Proses Pembelajaran
	   <input type="checkbox" name="tasesmen[]" value="rcc" <?php echo $kta2; ?> disabled='disabled'> RCC
	   <input type="checkbox" name="tasesmen[]" value="serti" <?php echo $kta3; ?> disabled='disabled'> Sertifikasi
	   <input type="checkbox" name="tasesmen[]" value="la" <?php echo $kta4; ?> disabled='disabled'>Lainnya...
	   <input type="text" name="lain" value="<?php echo $lain ; ?>" disabled='disabled' >    
		</div>
	   </div>
		<div class="form-group"><strong>Skema Sertifikasi:</strong>
		   <div class="dynamiclabel">
		   <input type="checkbox" name="skemas[]" value="unit" <?php echo $kser0; ?> disabled='disabled' > Unit
		   <input type="checkbox" name="skemas[]" value="klaster" <?php echo $kser1; ?> disabled='disabled'> Klaster
		   <input type="checkbox" name="skemas[]" value="okupasi" <?php echo $kser2; ?> disabled='disabled'> Okupasi
		   <input type="checkbox" name="skemas[]" value="kki" <?php echo $kser3; ?> disabled='disabled'> KKNI
			</div>
		   </div>
		<div class="form-group"><strong>Konten asesmen:</strong>
		   <div class="dynamiclabel">
		   <input type="checkbox" name="kasesmen[]" value="simulasi"<?php echo $kkas0; ?> disabled='disabled'> TUK Simulasi
		   <input type="checkbox" name="kasesmen[]" value="tkerja" <?php echo $kkas1; ?> disabled='disabled'> Tempat Kerja
		   ,dengan karakter 
		   <input type="checkbox" name="karakter[]" value="produk" <?php echo $kkar0; ?> disabled='disabled'> Produk
		   <input type="checkbox" name="karakter[]" value="sistem" <?php echo $kkar1; ?> disabled='disabled'> Sistem
		   <input type="checkbox" name="karakter[]" value="tkerja1" <?php echo $kkar2; ?> disabled='disabled'> Tempat Kerja
			</div>
		   </div>
		<div class="form-group"><strong>Acuan Pembanding:</strong>
		   <div class="dynamiclabel">
		   <input type="checkbox" name="acuan[]" value="skompetensi" <?php echo $kac0; ?> disabled='disabled'> Standar Kompetensi
		   <input type="checkbox" name="acuan[]" value="sproduk" <?php echo $kac1; ?> disabled='disabled'> Standar Produk
		   <input type="checkbox" name="acuan[]" value="ssistem" <?php echo $kac2; ?> disabled='disabled'> Standar sistem
		   <input type="checkbox" name="acuan[]" value="regulasi" <?php echo $kac3; ?> disabled='disabled'> Regulasi sistem
		   <input type="checkbox" name="acuan[]" value="sop" <?php echo $kac4; ?> disabled='disabled'> SOP
			</div>
		   </div>
		<?php
       }
	 }
     ?>
     </html>
