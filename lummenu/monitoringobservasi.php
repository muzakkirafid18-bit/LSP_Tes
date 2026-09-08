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
session_set_cookie_params(3600*2,"/");
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
			<li><a href="asesormain.php"><svg class="glyph stroked clipboard with paper"><use xlink:href="#stroked-clipboard-with-paper"/></svg> Validasi APL1</a></li>
			<li ><a href="validasiapl2.php"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg> Validasi APL2</a></li>
<li  class="active"><a href="observasi.php"><svg class="glyph stroked star"><use xlink:href="#stroked-star"/></svg> FR MPA.05-Observasi</a></li>
			<li role="presentation" class="divider"></li>
	
			<li><a href="../logout.php"><svg class="glyph stroked lock"><use xlink:href="#stroked-lock"/></svg> Logout</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>FR MPA.05-Observasi</h4>
                        
</div><!--/.row-->
		
		
				
		<!--/.row-->
				
		
		<div class="row">
			<div class="col-lg-12">
<!--data nu baru -->

<?php
$op = $_GET['op'];

if($op=="pilihtanggal")
{
$idskema= $_GET['idskema'];
$kelompok=$_GET['kelompok'];

?>
<form id="formContoh" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=listpeserta\"";?>> 
<input type="hidden" name="kelompok" value="<?php echo $kelompok ;?>">
<input type="hidden" name="idskema" value="<?php echo $idskema ;?>">
<input type="hidden" name="idasesor" value="<?php echo $idasesor ;?>">
<div class="form-group"><strong>Pilih Tanggal :</strong>
<select id="tgl" name="tgl" placeholder="Tanggal" autofocus>
<?php
      $tampiltgl="select * from pemetaan where kelompok='$kelompok' and idskema='$idskema' and idasesor='$idasesor' group by tanggal";
      $exectgl=mysql_query($tampiltgl);
      //echo $tampiltgl;
		while($rtgl=mysql_fetch_array($exectgl))
		{
                //         echo "ll";
   				echo "<option value=$rtgl[tanggal] selected>$rtgl[tanggal]</option> ";
					}
?>
	 </select>
</div>
<input type="submit" id="gobutton" value="Lanjutkan" class="button"> 
<?php
}
 
else if ($op == "listpeserta")
{
    $idasesor=$_POST['idasesor'];
    $idskema= $_POST['idskema'];
    $kelompok=$_POST['kelompok'];
    $tgl=$_POST['tgl'];
    $ssl="select * from pemetaan where kelompok='$kelompok' and idskema='$idskema' and tanggal='$tgl' and idasesor='$idasesor'";
    $exec0=mysql_query($ssl);
    //echo "laksl".$ssl;
    echo "<table>";
    echo "<tr><th bgcolor='#006699'>ID Asesi</th><th bgcolor='#006699'>Nama Asesi</th><th bgcolor='#006699'>Tanggal</th><th colspan=2 bgcolor='#006699'>Aksi</th></tr>";
     while($hasil0 = mysql_fetch_array($exec0))
      {
      if($hasil0['statusvalid']=='Y'){
         $ket="Sudah divalidasi";
         }else{ $ket="Belum di Validasi";}
      
      echo "<tr><td>".$hasil0['idpeserta']."</td>";
      echo "<td>".$hasil0['namapeserta']."</td>";
      echo "<td>".$hasil0['tanggal']."</td>";
      //echo "<td>".$ket."</td>";
      $ei="in";
      echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=observasi&kode=".$ei."&k=".$hasil0['kelompok']."&tgl=".$hasil0['tanggal']."&idasesi=".$hasil0['idpeserta']."&idskema=".$idskema."\"><img src=../images/edit.png>Observasi</a></td>";
      echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=observasi&kode=ed&k=".$hasil0['kelompok']."&tgl=".$hasil0['tanggal']."&idasesi=".$hasil0['idpeserta']."&idskema=".$idskema."\"><img src=../images/edit.png>Edit</a></td></tr>";
       }
     echo "<table>";
      
    
    
}

else if ($op == "observasi")
{
$idskema= $_GET['idskema'];
$idasesi= $_GET['idasesi'];
$tgl= $_GET['tgl'];
$kelompok=$_GET['k'];


$ie=$_GET['kode'];
//echo $ie;
$sqlunit="select * from unit where idskema='$idskema'";

$execunit=mysql_query($sqlunit);


$sqlskema="select * from skema where idskema='$idskema'";
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
//echo "<form name=contoh method=post action=postobservasi.php enctype=multipart/form-data id=form-upload>";
if($ie=='in'){
?>
<form id="obs" name="obs" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=postobs\"";?>> 
<?php
}else{
?>
<form id="obs" name="obs" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=updateobs\"";?>> 
<?php }
echo "<input type=hidden name=idasesor value='$idasesor'>";
echo "<input type=hidden name=idadsesi value='$idasesi'>";
echo "<input type=hidden name=idskema value='$idskema'>";
echo "<input type=hidden name=tgl value='$tgl'>";
echo "<input type=hidden name=kelompok value='$kelompok'>";
echo "<input type=hidden name=email value='$emailadsesi'>";

echo "<table width=95%><tr><td bgcolor='#99CCFF' colspan=8> <strong>Nama Skema : $namaskema </br>Nama Adsesi : $namaadsesi </strong></td></tr>";
$i=0;
while ($listunit = mysql_fetch_array($execunit))
   {
  
//echo "<form id=\"formContoh\" method=\"post\" action=\"postobservasi.php\">";
    
if($ie=='in'){
    $ssql="select * from praktek where idskema='$idskema' and kodeunit='".$listunit['kodeunit']."'";
    }else{$ssql="select rekappraktek.idrpraktek,rekappraktek.idskema,rekappraktek.idadsesi,rekappraktek.bunit,rekappraktek.kodeunit,rekappraktek.idpraktek,rekappraktek.idasesor,rekappraktek.tanggal,rekappraktek.pencapaians,rekappraktek.penilaians,praktek.instruksi,praktek.obervasi from rekappraktek inner join praktek on rekappraktek.idpraktek=praktek.idpraktek where rekappraktek.idskema='$idskema' and rekappraktek.idadsesi='$idasesi' and rekappraktek.kodeunit='".$listunit['kodeunit']."' and rekappraktek.idasesor='$idasesor' and rekappraktek.tanggal='$tgl'";}
   
  // echo $ssql;
    echo "<tr><td bgcolor=bluelight colspan=3><strong>KODE UNIT :".$listunit['kodeunit']."</strong></td><td colspan=2 rowspan=2><strong>Pencapaian</strong></td><td colspan=2 rowspan=2><strong>Penilaian</strong></td></tr><tr><td colspan=3><strong>NAMA UNIT : ".$listunit['namaunit']."</strong></td></tr><tr><td><strong>Instruksi</strong></td><td colspan=2><strong>Observasi</strong></td><td><strong>Ya</strong></td><td><strong>Tidak</strong></td><td><strong>K</strong></td><td><strong>BK</strong></td></tr>";
    $exec=mysql_query($ssql);
      //echo $listunit['kodeunit']."</br";
      $xjj=0;
      while ($list = mysql_fetch_array($exec)){
        
        $xjj++;
        if($ie=='in'){
        echo "<tr><td>".$list['instruksi']."<input type=hidden name=idunit".$i." value=".$listunit['idunit']."><input type=hidden name=kodeunit".$i." value=".$listunit['kodeunit']."></td><td>".$list['obervasi']."</td><td><input type=hidden name=idpraktek".$i." value=".$list['idpraktek']."></td><td><input type=radio name=pcp".$i." value='Y' </td><td><input type=radio name=pcp".$i." value='T'></td><td><input type=radio name=bk".$i." value='K'></td><td><input type=radio name=bk".$i." value='BK'></td></tr>";
         }else{
         $cky="";
         $ckt="";
         $ckk="";
         $ckbk="";
         if($list['pencapaians']=='Y'){
          $cky="checked";}
         else {
          $ckt="checked";}
	 if($list['penilaians']=='K'){
          $ckk="checked";}
         else {
          $ckbk="checked";}

         echo "<tr><td>".$list['instruksi']."<input type=hidden name=idunit".$i." value=".$listunit['idunit']."><input type=hidden name=kodeunit".$i." value=".$listunit['kodeunit']."></td><td>".$list['obervasi']."</td><td><input type=hidden name=idpraktek".$i." value=".$list['idpraktek']."><input type=hidden name=idrpraktek".$i." value=".$list['idrpraktek']."></td><td><input type=radio name=pcp".$i." value='Y' $cky></td><td><input type=radio name=pcp".$i." value='T' $ckt></td><td><input type=radio name=bk".$i." value='K' $ckk></td><td><input type=radio name=bk".$i." value='BK' $ckbk></td></tr>";
       $bunit=$list['bunit'];
       }
      $i++;
      
      }
      echo "<tr><td>";
      //$b=$ccunit;
	if($ie=='in'){
          echo "<input type=hidden id=aaunit name=aaunit".$listunit['idunit']." value='$xjj'>"; }
         else {
          echo "<input type=hidden id=aaunit name=aaunit".$listunit['idunit']." value='$bunit'>"; }
      echo "</td></tr>";
    }
     echo "<tr><td colspan=7><input type=submit name=simpan id=gobutton value=Simpan><input type=hidden name='n' value='".$i."'></td></tr>";
     echo "</table>";
echo "</form>";    
}

else if($op=="postobs")
{
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
        $ssql="insert into rekappraktek value('','$ids','$idunit','$kodeunit','$idpraktek','$idasesor','$idasesi','$yt','$bk','$tgl','$aahitung','$aaunit')";
       
        $ok=mysql_query($ssql);	
        if ($ok) {
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
<form id="formContoh" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=listpeserta\"";?>> 
<?php
echo "<input type=hidden name=tgl value='$tgl'>";
echo "<input type=hidden name=idskema value='$ids'>";
echo "<input type=hidden name=kelompok value='$kelompok'>";
echo "<input type=hidden name=idasesor value='$idasesor'>";
?>
</br><input type="submit" id="gobutton" value="Proses selesai.. Lanjutkan" class="button"> 
<?php
}

else if($op=="updateobs")
{
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
$ni=0;
$ny=0;

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
        $idrpraktek=$_POST['idrpraktek'.$i];
	//$aaunit=$_POST['aaunit'.$idunit];
	//$aahitung=(100/$aaunit);

	if($yt=='Y'){
            	$aaunit=$_POST['aaunit'.$idunit];
        	$aahitung=(100/$aaunit);
	}
        else { $aahitung=0;}
	

        //$cekdata="select * from rekappraktek where idskema='".$ids."' and idunit='".$idunit."' and idpraktek='".$idpraktek."' and idadsesi='".$idasesi."' and idasesor='".$idasesor."'";
	 //echo $cekdata."</br>";
        //$ada=mysql_query($cekdata);
	//if(mysql_num_rows($ada)>0){
         // echo "Terjadi duplikate</br>";
         // }else { 
	 
	//echo $ids.",".$idasesi.",".$idasesor.",".$idunit.",".$idpraktek.",".$bk.",".$yt.",";
        $ssql="update rekappraktek set pencapaians='$yt',penilaians='$bk', niai='$aahitung' where idskema='".$ids."' and idunit='".$idunit."' and idpraktek='".$idpraktek."' and idadsesi='".$idasesi."' and idasesor='".$idasesor."' and idrpraktek='".$idrpraktek."'";
        //echo $ssql;
        $ok=mysql_query($ssql);	
        if ($ok) { 
		$sukses++;
		$updatesksiswa="update skemasiswa set statustest='Y' where emailsiswa='$email' and idskema='$ids'";
                $execupdate=mysql_query($updatesksiswa);}


           //    }

		// else $gagal=$x-$sukses;
     } //else { echo"<script>alert('Tidak ada/kuranglengkap pilihan !');history.go(-1);</script>";}     
 
}
// echo"<script>alert('proses selesai.... !');history.go(-2);</script>";
$gagal=$n-$sukses;
echo "Sukses :".$sukses ;
echo "</br>Gagal  :".$gagal;?>
<form id="formContoh" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=listpeserta\"";?>> 
<?php
echo "<input type=hidden name=tgl value='$tgl'>";
echo "<input type=hidden name=idskema value='$ids'>";
echo "<input type=hidden name=kelompok value='$kelompok'>";
?>
</br><input type="submit" id="gobutton" value="Proses selesai.. Lanjutkan" class="button"> 
<?php
}



?>


<body>
<br/>
<?php
$queryobmain ="SELECT * FROM pemetaan where idasesor='$idasesor' group by kelompok,idskema";
//echo $queryobmain;
$hasilobmain = mysql_query($queryobmain);
//$ada=mysql_query($);
   	if(mysql_num_rows($hasilobmain)>0){
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
$queryobvmain ="SELECT * FROM pemetaan where idasesor='$idasesor' group by kelompok,idskema";
$hasilobvmain = mysql_query($queryobvmain);
//echo $query;
while ($dataobvmain = mysql_fetch_array($hasilobvmain))
{
   $ssql="select * from skema where idskema=".$dataobvmain['idskema'];
   //echo $ssql;
   $execssql=mysql_query($ssql);
   $baris=mysql_fetch_array($execssql);
   $namaskema=$baris['namaskema'];
   echo "<tr>";
   echo "<td bgcolor='#99CCFF'>".$no."</td>";
   //echo "<td bgcolor='#99CCFF'>".$data['id_user']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$dataobvmain['kelompok']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$dataobvmain['idskema']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$namaskema."</td>";
  	
   echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=pilihtanggal&idskema=".$dataobvmain['idskema']."&kelompok=".$dataobvmain['kelompok']."\"><img src=../images/edit.png>Tampilkan Peserta</a> 
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


<script type="text/javascript">
function myclick()
{ 
var bb=document.getElementById("nili").value;
var aa=document.getElementById("aaaa").value;
var cc='v';
f=cc+bb;
var v=document.getElementById(f).value='abc';


alert(bb);
} 
</script>


			
</body>

</html>

<?php } ?>
