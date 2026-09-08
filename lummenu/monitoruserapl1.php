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
<script src="../js/jquery-2.2.3.min.js"></script>
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
?>
<script type="text/javascript">
$(document).ready (function() {
	$('#formContoh').formValidation({
		framework: 'bootstrap',
		excluded: 'disabled',

		icon: {
			valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			namaasesor: {
				validators: {
					notEmpty: {
						message: 'Username Tidak Boleh Kosong'
					}
				}
			},
			
			nama: {
				validators: {
					notEmpty: {
						message: 'Nama Tidak Boleh Kosong'
					}
				}
			},

			notelp: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					}
				}
			},
			password: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					},
					identical: {
						field: 'repassword',
						message: 'Password Tidak sama'
					}					
				}
			},
                        repassword: {
				validators: {
					notEmpty: {
						message: 'Confirm Password Tidak Sama'
					},
					identical: {
						field: 'password',
						message: 'Password Tidak sama'
					},
                                        stringlenght:{
					max :6,
                                        message: 'Minimal 6'
					}	  
				
				}
			},
			emailuser: {
				validators: {
					notEmpty: {
						message: 'Email Tidak Boleh Kosong'
					},
					emailAddress: {
						message: 'Email Tidak Valid'
					}
				}
			}
		}
	})
});
</script>

<style>
form div.dynamiclabel{ /* div container for each form field with pop up label */
  display: block;
  margin: 5px 2px 6px 15px;
  font: 16px;
  position: relative;
}

form div.formcolumn{ /* column div inside form */
width: 49%;
float: left;
}

form div.formcolumn:first-of-type{
margin-right: 1%; /* 2% margin after first column */
}

form div.dynamiclabel input[type="text"], form div.dynamiclabel textarea{
  width: 350px;
  font-size: 14px;
  padding: 7px;
  border: 1px solid #c3c3c3;
  border-radius: 7px;
  
}

form div.dynamiclabel textarea{
	height: 150px;
}

form div.dynamiclabel select{
  width: 200px;
  font-size: 14px;
  padding: 7px;
  border: 1px solid #c3c3c3;
  border-radius: 7px;
  text-transform: uppercase
}

form div.buttons{
clear: both;
text-align: center;
}

form div input.button{
margin-top: 2EM;
width: 35%;
box-shadow: 0 0 10px gray;
text-transform: uppercase;
cursor: pointer;
min-width: 100px;
max-width: 600px;
color: white;
font-weight: bold;
letter-spacing: 10px;
text-shadow: 0 -2px 1px #8a8a8a;
background: rgb(169,3,41);
background: -moz-linear-gradient(top,  rgba(169,3,41,1) 0%, rgba(143,2,34,1) 44%, rgba(109,0,25,1) 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(169,3,41,1)), color-stop(44%,rgba(143,2,34,1)), color-stop(100%,rgba(109,0,25,1)));
background: -webkit-linear-gradient(top,  rgba(169,3,41,1) 0%,rgba(143,2,34,1) 44%,rgba(109,0,25,1) 100%);
background: -o-linear-gradient(top,  rgba(169,3,41,1) 0%,rgba(143,2,34,1) 44%,rgba(109,0,25,1) 100%);
background: -ms-linear-gradient(top,  rgba(169,3,41,1) 0%,rgba(143,2,34,1) 44%,rgba(109,0,25,1) 100%);
background: linear-gradient(to bottom,  rgba(169,3,41,1) 0%,rgba(143,2,34,1) 44%,rgba(109,0,25,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a90329', endColorstr='#6d0019',GradientType=0 );
}

form div input.button:active{
text-shadow: 0 0 1px #8a8a8a;
background: rgb(109,0,25);
background: -moz-linear-gradient(top,  rgba(109,0,25,1) 0%, rgba(143,2,34,1) 56%, rgba(169,3,41,1) 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(109,0,25,1)), color-stop(56%,rgba(143,2,34,1)), color-stop(100%,rgba(169,3,41,1)));
background: -webkit-linear-gradient(top,  rgba(109,0,25,1) 0%,rgba(143,2,34,1) 56%,rgba(169,3,41,1) 100%);
background: -o-linear-gradient(top,  rgba(109,0,25,1) 0%,rgba(143,2,34,1) 56%,rgba(169,3,41,1) 100%);
background: -ms-linear-gradient(top,  rgba(109,0,25,1) 0%,rgba(143,2,34,1) 56%,rgba(169,3,41,1) 100%);
background: linear-gradient(to bottom,  rgba(109,0,25,1) 0%,rgba(143,2,34,1) 56%,rgba(169,3,41,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6d0019', endColorstr='#a90329',GradientType=0 );
}



form div.dynamiclabel label{ /* pop up label style */
  position: absolute;
  left: 0;
  background: lightyellow;
  border: 1px solid yellow;
  border-radius: 2px;
  padding: 3px 7px;
  box-shadow: 4px 1px 5px gray;
  font-weight: bold;
	-webkit-backface-visibility: hidden;
	top: 7px; /* initial top position of label relative to dynamiclabel container */
  -moz-transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  -webkit-transition: all 0.4s ease-in-out; /* Safari doesn't seem to support cubic-bezier values beyond 0 to 1, so use keyword value instead */
  -o-transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  opacity: 0;
  z-index: -1;
}


form div.dynamiclabel > *:focus{ /* when user focuses on child element inside div.dynamiclabel */
  border: 1px solid #45bcd2;
  box-shadow: 0 0 8px 2px #98d865;
}


form div.dynamiclabel > *:focus + label{ /* label style when user focuses on child element inside div.dynamiclabel */
  opacity: 1;
  z-index:100;
	top: -35px; /* Post top position of label on focus relative to dynamiclabel container */
	-ms-transform: rotate(-3deg);
	-moz-transform: rotate(-3deg);
	-webkit-transform: rotate(-3deg);
  transform: rotate(-3deg);
}


@media screen and (max-width: 480px){ /* responsive form settings, when window width is 480px or less */

	form div.dynamiclabel{
	font-size: 14px; /* decrease form font size */
	}

	form div.dynamiclabel .formcolumn{
	width: 100%;
	float: none;
	}
	
	form div.dynamiclabel .formcolumn:first-of-type{
	margin-right: 0; /* remove right margin from first form column */
	}

	form div.dynamiclabel .formcolumn:nth-of-type(2){
	padding-top: 2em; /* add padding to top of 2nd form column, so there is a gap between the 1st and 2nd column */
	}

	form div.dynamiclabel select{
	width: 98%;
	}

}


</style>

<!--Icons-->
<script src="js/lumino.glyphs.js"></script>

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
<?php if(isset($_SESSION['username'])) { $uname=$_SESSION['username'];}
$l="SELECT * FROM lsp_usertbl WHERE email='".$uname."'";
$resultx=mysql_query($l);
$hasilx=mysql_fetch_array($resultx);
$namax=$hasilx['nama']; 
$idasesor=$hasilx['id'];
?>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand"><span><?php echo $namax ;?></span></a>
				
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>
		
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<!--<form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form>-->
		<ul class="nav menu">
			<li class="active"><a href="asesormain.php"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> Validasi APL1</a></li>
			<li><a href="validasiapl2.php"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> Validasi APL2</a></li>
<li><a href="observasi.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> FR MPA.05-Observasi</a></li>
			<li role="presentation" class="divider"></li>
	
			<li><a href="../logout.php"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Logout</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>Validasi APL 1 </h4>
                        
</div><!--/.row-->
		
		
				
		<!--/.row-->
				
		
		<div class="row">
			<div class="col-lg-12">
<!--data nu baru -->

<?php
$op = $_GET['op'];
 
if ($op == "validasi")
{
//$idasesor=$_GET['idasesor'];
//$idskema= $_GET['idskema'];
$idasesi= $_GET['idasesi'];
//$ie=$_GET['kode'];
//$tgl=$_GET['tgl'];
//$kelompok=$_GET['k'];
//echo $ie;

/**$sqlskema="select * from skema where idskema='$idskema'";
$execskema=mysql_query($sqlskema);
$listskema = mysql_fetch_array($execskema);
$namaskema=$listskema['namaskema'];

$sqladsesi="select * from lsp_usertbl where id='$idasesi'";
$execadsesi=mysql_query($sqladsesi);
$listadsesi = mysql_fetch_array($execadsesi);
$namaadsesi=$listadsesi['nama'];
$emailadsesi=$listadsesi['email'];

$sqlunit="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$idskema' and unitsiswa.idadsesi='$idasesi'";
$execunit=mysql_query($sqlunit);

echo "<table width=95%><tr><td colspan=2> <strong>Nama Skema : $namaskema </br>Nama Adsesi : $namaadsesi <a href=../siswa/biodatasiswa.php?email=$emailadsesi target=_blank> Tampilkan Biodata Lengkap </a></strong></td></tr><tr><th>Kode Unit ( Dipilih Siswa ) </td><th>Nama Unit </th></tr>";
while ($listunit = mysql_fetch_array($execunit))
   {
   echo "<tr><td>".$listunit['kodeunit']."</td><td>".$listunit['namaunit']."</td></tr>";
   }
 echo "</table>";
**/

   $cekdata0 = "SELECT * FROM rekomendasi WHERE namarekom = 'apl1' and idasesi='$idasesi'";
  // echo $cekdata0;
   $ada0=mysql_query($cekdata0);
   $adax=mysql_fetch_array($ada0);
   
    $lrek=$adax['rekom'];
    $cat=$adax['catatan'];
       if($lrek=='L'){
      $klrek='checked';
     }else{$klrek='';}
     if($lrek=='T'){
      $klrek0='checked';
     }else{$klrek0='';}
    
    $catatan=$data0['catatan'];
  

echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=simpanvalidasi\">";
    
    //if($ie=='in'){
    //$ssql="select * from permohonan where idskema='$idskema' and id='$idasesi' and statusvalid='N' and tanggal='$tgl'";
    //}else{$ssql="select * from permohonan where idskema='$idskema' and id='$idasesi'";}
    //echo $ssql;
    $ssql="select * from permohonan where id='$idasesi'";
    $exec=mysql_query($ssql);
    //$listx=mysql_fetch_array($exec);
    //$namaadsesi=$listx['nama'];
    if(mysql_num_rows($exec)>0)
    {
    
    echo "<input type=hidden name=emailadsesi value='$emailadsesi'>";
    echo "<table><tr>
    <th>Data Permohonan </th></tr><tr><td>";
        echo "<input type=hidden name=idasesor value='$idasesor'>";
    	echo "<input type=hidden name=kelompok value='$kelompok'>";
       echo "<input type=hidden name=idskema value=$idskema><input type=hidden name=idasesi value=$idasesi><input type=hidden name=tgl value='$tgl'";
        while ($list = mysql_fetch_array($exec)){
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
echo "</td></tr></table>";
echo "<table width=95%><tr>";
//if($ie=='in'){
//$ssql1="select * from upload where idskema='$idskema' and idasesi='$idasesi' and statusvalid='N' and waktu='$tgl' order by statusvalid";////anstatusvalid='N'";
//}else{$ssql1="select * from upload where idskema='$idskema' and idasesi='$idasesi' and statusvalid='Y' and waktu='$tgl' order by statusvalid";}
//echo $ssql1;
$ssql1="select * from upload where idasesi='$idasesi'";
$i = 0;

$exec1=mysql_query($ssql1);
if(mysql_num_rows($exec1)>0){
//echo "kjkasjkas"; 

    while ($list1 = mysql_fetch_array($exec1)){
    $id=$list1['id'];
    $idskema1=$list1['idskema'];
    $idasesi1=$list1['idasesi'];
    $email=$list1['email'];
    $idunit=$list1['idunit'];
    $idelemen=$list1['idelemen'];
    $bukti=$list1['bukti'];
    $path=$list1['Path'];
    $dbukti=$list1['dbukti'];
    $lt=$list1['lt'];
    //echo $idelemen.$dbukti;
    $status=$list1['statusvalid'];
    if($status=='Y') {
      $val="SV";
    }else{ $val="BV";}
    $pecahdb=explode(",",$dbukti);
    
    $db0=$pecahdb[0];
    $db1=$pecahdb[1];
	$db2=$pecahdb[2];
	$db3=$pecahdb[3];
        //echo "B".$db0;
	if(trim($db0)=='v')
        {
         $kdb0="checked";
        }else{$kdb0="";}
       if(!empty($db1))
        {
         $kdb1="checked";
        }else{$kdb1="";}
       if(!empty($db2))
        {
         $kdb2="checked";
        }else{$kdb2="";}
       if(!empty($db3))
        {
         $kdb3="checked";
        }else{$kdb3="";}
        
    

    $pecahbu=explode(",",$bukti);
    $bu0=$pecahbu[0];
    $bu1=$pecahbu[1];
	$bu2=$pecahbu[2];
	$bu3=$pecahbu[3];
	$bu4=$pecahbu[4];
	$bu5=$pecahbu[5];
	$bu6=$pecahbu[6];
	$bu7=$pecahbu[7];
       // echo "bukti".$bu6;
		if(!empty($bu0))
        {
         $kbu0="checked";
        }else {$kbu0="";}
		if(!empty($bu1))
        {
         $kbu1="checked";
        }else {$kbu1="";}
		if(!empty($bu2))
        {
         $kbu2="checked";
        }else {$kbu2="";}
		if(!empty($bu3))
        {
         $kbu3="checked";
        }else {$kbu3="";}
		if(!empty($bu4))
        {
         $kbu4="checked";
        }else {$kbu4="";}
		if(!empty($bu5))
        {
         $kbu5="checked";
        }else {$kbu5="";}
		if(!empty($bu6))
        {
         $kbu6="checked";
        }else {$kbu6="";}
	if(!empty($bu7))
        {
         $kbu7="checked";
        }else {$kbu7="";}
        	
        
        if($lt=='L')
        {
         $l="checked";
        }else{$l="";}
        if($lt=='T')
        { 
        $t="checked";
        }else{$t="";}

        //echo "s".$kdb0;
	echo"</tr><th colspan=7> Kompetensi dan Bukti Pendukung </th></tr><tr>";
    echo"<th></th><th>Elemen Kompetensi</th><th colspan=2>Bukti Yang relevan</th><th>Kesesuain Bukti</th><th>Asesmen Lanjut</th><th>Ket</th></tr><tr>";	
    $ssql2="select * from elemen where idskema='$idskema' and idunit='$idunit' and idelemen='$idelemen'";
    $exec2=mysql_query($ssql2);
    $list2 = mysql_fetch_array($exec2);
    $namae=$list2['namaelemen'];
    echo "<td><input type=hidden name=idelemen".$i." value='$idelemen'>".$i."</td>";
    echo "<td>".$namae."</td>";
    echo "<td><input type=checkbox name=bukti[] value='sk'$kbu0 disabled='disabled'>SR
		<input type=checkbox name=bukti[] value='sr'$kbu1 disabled='disabled'>SR 
		<input type=checkbox name=bukti[] value='cp' $kbu2 disabled='disabled'>CP 
		<input type=checkbox name=bukti[] value='jd' $kbu3 disabled='disabled'>JD
		<input type=checkbox name=bukti[] value='ws'$kbu4 disabled='disabled'>WS 
		<input type=checkbox name=bukti[] value='de'$kbu5 disabled='disabled'>De 
		<input type=checkbox name=bukti[] value='pe'$kbu6 disabled='disabled'>Pe 
		<input type=checkbox name=bukti[] value='l' $kbu7 disabled='disabled'>L</td>";
    		echo"<td><a href=../siswa/gambarimages/".$path." target=_blank> Tampilkan Dokumen </a></td>";
    		echo"<td><input type=checkbox name=validasia".$i." value='v' $kdb0>V
		   <input type=checkbox name=validasib".$i." value='a' $kdb1>A <input type=checkbox name=validasic".$i." value='t' $kdb2>T 			     <input type=checkbox name=validasid".$i." value='m' $kdb3>M</td>";
                //echo "<td><input type=checkbox name=validasi0".$i." value='v' $kdb0>$kdb0</td>";
    		     echo "<td><input type=radio name=lt".$i." value='l' $l>L <input type=radio name=lt".$i." value='t' $t>T</td>";
    echo "<td>$val</td>";
    $i++;
    }

      
    echo"</tr><td colspan=2 rowspan=3> Rekomendasi </br><input type=radio name=lrek value='L' $klrek >Lanjut</br> <input type=radio name=lrek value='T' $klrek0>Tidak</td><td colspan=5>Asesi :</td></tr><tr><td>Nama : </td><td colspan=4>$namaadsesi</td></tr><tr><td>Tanda Tangan : </td><td colspan=4></td>";
echo"</tr><td colspan=2 rowspan=3> Catatan </br><input type=text name=catatan value=$cat><td colspan=5>Asesor :</td></tr><tr><td>Nama : </td><td colspan=4>$namax</td></tr><tr><td>Tanda Tangan : </td><td colspan=4></td>";
    echo "</tr><tr><td colspan=7><input type='hidden' name='n' value='".$i."'><input type=submit name=simpan id=gobutton value=Simpan></td>"; 
    echo "</tr></table>";
 echo "</form>";
}else { echo " <font color=red>Asesi belum melakukan pengisian </font></br>";}
echo "SK = Sertifikasi atau Kualifikasi(contoh : pelatihan, keahlian)</br>
SR = Surat referensi dari supervision/perusahaan mengenai pekerjaan anda</br>
CP = Contoh Pekerjaan yang pernah anda buat (produk jadi)</br>
JD = Job description dari perusahaan mengenai pekerjaan anda</br>
WS = Wawancara dengan supervisor , teman sebaya atau klien</br>
De = Demonstrasi pekerjaan/keterampilan yang dipersyaratkan</br>
Pe = Pengalaman Idustri (On teh job training, magang,kerja praktek,dll)</br>
 L  = Bukti-bukti lainnya yang relevan  </br>
SV = Sudah Validasi </br>
BV = Belum Validasi ";
} else { echo "<strong>TIDAK ADA DATA YANG HARUS DI VALIDASI</strong>";}
}

?>


<body>
<br/>
<?php
$query ="SELECT * FROM pemetaan where idasesor='$idasesor' group by kelompok,idskema";
$hasil = mysql_query($query);
//$ada=mysql_query($);
   	if(mysql_num_rows($hasil)>0){
?>
<table >
<tr>
    <th bgcolor='#006699'>No</th>
    	
    <th bgcolor='#006699'>Paket</th>
    <th bgcolor='#006699'>Skema</th>
    <th bgcolor='#006699'>Nama Skema</th>
    <th bgcolor='#006699' colspan='2'>Aksi</th>
    
</tr>
 
<?php
 
// bagian ini digunakan untuk menampilkan semua data
 
$no = 1;
//echo $query;
while ($data = mysql_fetch_array($hasil))
{
   $ssql="select * from skema where idskema=".$data['idskema'];
   //echo $ssql;
   $execssql=mysql_query($ssql);
   $baris=mysql_fetch_array($execssql);
   $namaskema=$baris['namaskema'];
   echo "<tr>";
   echo "<td bgcolor='#99CCFF'>".$no."</td>";
   //echo "<td bgcolor='#99CCFF'>".$data['id_user']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$data['kelompok']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$data['idskema']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$namaskema."</td>";
  	
   echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=pilihtanggal&idasesor=".$data['idasesor']."&idskema=".$data['idskema']."&kelompok=".$data['kelompok']."\"><img src=../images/edit.png>Tampilkan Peserta</a> 
    </td>";
   echo "</tr>";
   $no++;
}
   echo "</div>";
}else{ echo "Belum ada jadwal";}
?>

<!--end databaru-->
			<div>
		</div>
		
                </div>
         </div> 
			
</body>

</html>

<?php } ?>
