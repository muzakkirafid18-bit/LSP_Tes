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
<link href="../css/tabelbaru2.css" rel="stylesheet">
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
				<a class="navbar-brand" href="#"><img src="../images/logolsp.png" alt="" height="30"><span><?php echo " ".$namax ;?></a>
                                <a class="user-menu" href="#"><img src="../images/logobnsp.png" alt="" height="30"><span></a>
				
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
			<li><a href="validasiapl2.php"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg> Validasi APL2</a></li>
<li><a href="observasi.php"><svg class="glyph stroked eye"><use xlink:href="#stroked-eye"/></svg> FR MPA.05-Observasi</a></li>
<li><a href="mak4.php"><svg class="glyph stroked blank document"><use xlink:href="#stroked-blank-document"/></svg>FR MAK.04</a></li>
<li class="active"><a href="mak6.php"><svg class="glyph stroked star"><use xlink:href="#stroked-star"/></svg> FR MAK.06</a></li>
<li><a href="mak7.php"><svg class="glyph stroked map"><use xlink:href="#stroked-map"/></svg>FR MAK.07</a></li>
<li><a href="rekapasesi.php"><svg class="glyph stroked calendar blank"><use xlink:href="#stroked-calendar-blank"/></svg> Rekap FR MPA.03</a></li>
<li ><a href="mma.php"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></svg> MMA</a></li>
<li role="presentation" class="divider"></li>
	
			<li><a href="../logout.php"><svg class="glyph stroked lock"><use xlink:href="#stroked-lock"/></svg> Logout</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>FR MAK-6 LAPORAN ASESMEN</h4>
                        
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
    echo "<table class=demo-table>";
    echo "<tr><th bgcolor='#006699'>ID Asesi</th><th bgcolor='#006699'>Nama Asesi</th><th bgcolor='#006699'>Tanggal</th><th colspan=2 bgcolor='#006699'>Aksi</th></tr>";
     while($hasil0 = mysql_fetch_array($exec0))
      {
      $ckmak6="select * from mak6 where idskema='".$idskema."' and idasesi='".$hasil0['idpeserta']."' and idasesor='".$idasesor."'";
	 $adackmak6=mysql_query($ckmak6);
	 if(mysql_num_rows($adackmak6)>0){
         $ket="Sudah divalidasi";
         }else{ $ket="Belum di Validasi";}
      
      echo "<tr><td>".$hasil0['idpeserta']."</td>";
      echo "<td>".$hasil0['namapeserta']."</td>";
      echo "<td>".$hasil0['tanggal']."</td>";
      //echo "<td>".$ket."</td>";
      $ei="in";
      echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=validasimak6&kode=".$ei."&k=".$hasil0['kelompok']."&tgl=".$hasil0['tanggal']."&idasesi=".$hasil0['idpeserta']."&idskema=".$idskema."\"><img src=../images/edit.png>".$ket."</a></td>";
/***
      echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=observasi&kode=ed&k=".$hasil0['kelompok']."&tgl=".$hasil0['tanggal']."&idasesi=".$hasil0['idpeserta']."&idskema=".$idskema."\"><img src=../images/edit.png>Edit</a></td></tr>";**/
       }
     echo "<table class=demo-table>";
      
    
    
}

else if($op=="validasimak6")
{
$idskema= $_GET['idskema'];
$idasesi= $_GET['idasesi'];
$tgl= $_GET['tgl'];
$kelompok=$_GET['k'];


$ie=$_GET['kode'];
//echo $ie;
?><form id="obs" name="obsmak6" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=postobsmak6\"";?>>
<?php
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

$sqlunitz="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$idskema' and unitsiswa.idadsesi='$idasesi'";
//echo $sqlunitz;
$execunitz=mysql_query($sqlunitz); 

echo "<input type=hidden name=idasesor value='$idasesor'>";
echo "<input type=hidden name=idadsesi value='$idasesi'>";
echo "<input type=hidden name=idskema value='$idskema'>";
echo "<input type=hidden name=tgl value='$tgl'>";
echo "<input type=hidden name=kelompok value='$kelompok'>";
echo "<input type=hidden name=email value='$emailadsesi'>";


?>


<table class="demo-table" >
<tr>
    <!--<span class='glyphicon glyphicon-asterisk'><font color=red>Kalau Ket tidak berubah klik refresh / F5.</font>-->
    <th bgcolor='#006699'>Kode Unit</th>
    	
    <th bgcolor='#006699'>Nama Unit </th>
    <th bgcolor='#006699'>Kompeten </th>
    <th bgcolor='#006699'>Belum Kompeten</th>
    
</tr>

<?php
$i=1;
while ($daftaobservasi = mysql_fetch_array($execunitz))
{
//$idunitaa=$daftaobservasi['idunit'];
//**
$cekdataat="select * from mak6 where idskema='".$idskema."' and idunit='".$daftaobservasi['idunit']."' and idasesi='".$idasesi."' and idasesor='".$idasesor."'";
	 //echo $cekdataat."</br>";
         $adaat=mysql_query($cekdataat);
	 $ckekk="checked";
         $ckebk="";

	 if(mysql_num_rows($adaat)>0){
            $adaatt= mysql_fetch_array($adaat);
         $bka=$adaatt['bk'];
         //echo $bka;
	 $ckekk="";
         $ckebk="";
         if($bka=='K'){
            $ckekk="checked";}
            else
	    {$ckebk="checked";}
         $l1=$adaatt['lanti'];
	 $pe1=$adaatt['penolakan'];
	 $pr1=$adaatt['perbaikan'];
	 $l2=$adaatt['lantia'];
	 $pe2=$adaatt['penolakana'];
	 $pr2=$adaatt['perbaikana'];
         }
          
         
//$cekobser="select idunit,idadsesi from rekappraktek where idunit='".$daftaobservasi['idunit']."' and idadsesi='".$daftaobservasi['idadsesi']."' group by idunit,idadsesi";
//echo $cekobser;
//$cekobsera=mysql_query($cekobser); 
//$g=mysql_num_rows($cekobsera);
//if($g>0){
//$keto="<font color=green><span class='glyphicon glyphicon-ok'>Pernah di observasi</font>";}
//else { $keto="<font color=red><span class='glyphicon glyphicon-remove'>Belum diobservasi</font>";}

echo "<tr><td><input type=hidden name=idasesi value='".$daftaobservasi['idadsesi']."'><input type=hidden name=idunit".$i." value='".$daftaobservasi['idunit']."'>".$daftaobservasi['kodeunit']."</td>";
echo "<td>".$daftaobservasi['namaunit']."</td>";
echo "<td><input type=radio name=pcpmak6".$i." value='K' $ckekk></td>";
echo "<td><input type=radio name=pcpmak6".$i." value='BK' $ckebk></td>";


//echo "<td><a href=\"".$_SERVER['PHP_SELF'].
  //      "?op=observasi&kode=in&kou=".$daftaobservasi['kodeunit']."&idass=".$idasesor."&k=".$kelompok."&tgl=".$tgl."&idasesi=".$daftaobservasi['idadsesi']."&idskema=".$daftaobservasi['idskema']."\"><span class='glyphicon glyphicon-check'>Observasi</a></td>";
//echo "<td><a href=\"".$_SERVER['PHP_SELF'].
  //     "?op=observasi&kode=ed&kou=".$daftaobservasi['kodeunit']."&idass=".$idasesor."&k=".$kelompok."&tgl=".$tgl."&idasesi=".$daftaobservasi['idadsesi']."&idskema=".$daftaobservasi['idskema']."\"><span class='glyphicon glyphicon-edit'>Edit</a></td></tr>";
$i++;
}
echo "</table>";
echo "<table class=demo-table>";
echo "<tr><td>Aspek Negatifdan dan Positif Dalam asesmen</td><td>Pencatatan Penolakan Hasil Asesmen</td><td>Saran Perbaikan :
(Asesor/Personil Terkait)</td></tr>";
echo "<tr><td>Asesmen (berjalan lancar atau tidak)* <input type=text name=lanti value='".$l1."'>dan tuliskan alasannya jika tidak berjalan lancar: <input type=text name=lantia size=30 value='".$l2."'>
Proses Asesmen/Uji Kompetensi berjalan lancar Proses asesmen/Uji Kompetensi tidak berjalan lancar karena</td><td> (ada atau tidak ada)* <input type=text name=penolakan value='".$pe1."'>penolakan hasil. 
Tuliskan jika ada penolakan hasil: <input type=text name=penolakana size=30 value='".$pe2."'></td><td>( ada atau tidak ada)* <input type=text name=perbaikan value='".$pr1."'>perbaikan. Tuliskan jika ada perbaikan:<input type=text name=perbaikana size=30 value='".$pr2."'></td></tr>";
echo "<tr><td colspan=3> Kode File diiisi oleh LSP </td><tr>";

echo "<tr><td colspan=4><input type=hidden name='n' value='".$i."'></td></tr>";
echo "<tr><td colspan=4><input type=submit name=simpan id=gobutton value=Simpan></td></tr>";
}



else if($op=="postobsmak6")
{
$email=trim($_POST['email']);
$ids=$_POST['idskema'];
$idasesi=$_POST['idadsesi'];
$idasesor=$_POST['idasesor'];
$tgl=$_POST['tgl'];
$kelompok=$_POST['kelompok'];
$n = $_POST['n'];
$la=$_POST['lanti'];
$pen=$_POST['penolakan'];
$per=$_POST['perbaikan'];
$lan=$_POST['lantia'];
$peno=$_POST['penolakana'];
$perb=$_POST['perbaikana'];

$sukses=0;
$gagal=0;
$ntot=0;
//echo "abnsbdn";
//echo $kelompok;
for ($i=0; $i<=$n-1; $i++)
   {
     
     if (isset($_POST['pcpmak6'.$i]))
     {
        
	//echo "alkslaskl";
       	$idunit = $_POST['idunit'.$i];
       	//$idpraktek = $_POST['idpraktek'.$i];
       	//$bk=$_POST['bk'.$i];
       	$bk=$_POST['pcpmak6'.$i];
        //$kodeunit=$_POST['kodeunit'.$i];
                // echo "abc".$aahitung;
         //if($bk=='K'){
        //  $ni=50;}
        //  else {$ni=0;}
	
        // $ntot=$ni+$ny;

        $cekdata="select * from mak6 where idskema='".$ids."' and idunit='".$idunit."'and idasesi='".$idasesi."' and idasesor='".$idasesor."'";
	 //echo $cekdata."</br>";
        $ada=mysql_query($cekdata);
	if(mysql_num_rows($ada)>0){
          //echo "Terjadi duplikate</br>";
          $sqlmk6="update mak6 set bk='$bk', lanti='$la', penolakan='$pen', perbaikan='$per', lantia='$lan', penolakana='$peno', perbaikana='$perb' where idskema='".$ids."' and idunit='".$idunit."' and idasesi='".$idasesi."' and idasesor='".$idasesor."'";
          $sqlm=mysql_query($sqlmk);
	
	$maku="update mak6 set lanti='$la', penolakan='$penn', perbaikan='$per', lantia='$lan', penolakana='$peno', perbaikana='$perb' where idskema='".$ids."' and idasesi='".$idasesi."' and idasesor='".$idasesor."'";
            $makuu=mysql_query($maku);
          //echo  $sqlmak6up;
          }else { 
	  
	//echo $ids.",".$idasesi.",".$idasesor.",".$idunit.",".$idpraktek.",".$bk.",".$yt.",";
        $sqlmak6="insert into mak6 value('','$ids','$idunit','$idasesi','$idasesor','$bk','$lanti','$penolakan','$perbaikan','$lantia','$penolakana','$perbaikana')";
        //echo $sqlmak6;
        $okmak6=mysql_query($sqlmak6);
                	
       
               }
		// else $gagal=$x-$sukses;
     } //else { echo"<script>alert('Tidak ada/kuranglengkap pilihan !');history.go(-1);</script>";}     
//if($okmak6){echo "sukses..";} else {echo "gagal ..";} 
}
// echo"<script>alert('proses selesai.... !');history.go(-2);</script>";
?>
<form id="formContoh" method="post" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=listpeserta\"";?>> 
<?php
echo "<input type=hidden name=tgl value='$tgl'>";
echo "<input type=hidden name=idskema value='$ids'>";
echo "<input type=hidden name=kelompok value='$kelompok'>";
echo "<input type=hidden name=idasesor value='$idasesor'>";
echo "<input type=hidden name=idasesi value='$idasesi'>";

//echo "<script>history.go(-1);</script>";
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
        $ssqlrekapu="update rekappraktek set pencapaians='$yt',penilaians='$bk', niai='$aahitung' where idskema='".$ids."' and idunit='".$idunit."' and idpraktek='".$idpraktek."' and idadsesi='".$idasesi."' and idasesor='".$idasesor."' and idrpraktek='".$idrpraktek."'";
        //echo $ssql;
        $okrekapu=mysql_query($ssqlrekapu);	
        if ($okrekapu) { 
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
<form id="formContoh" method="post" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=listpeserta\"";?>> 
<?php


echo "<input type=hidden name=tgl value='$tgl'>";
echo "<input type=hidden name=idskema value='$ids'>";
echo "<input type=hidden name=kelompok value='$kelompok'>";
echo "<input type=hidden name=idasesor value='$idasesor'>";
echo "<input type=hidden name=idasesi value='$idasesi'>";
echo "<script>history.go(-2);</script>";
?>
<!--</br><input type="submit" id="gobutton" value="Proses selesai.. Lanjutkan" class="button">--> 
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

<table class="demo-table">
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
   $ssqlmain="select * from skema where idskema=".$dataobvmain['idskema'];
   //echo $ssql;
   $execssql=mysql_query($ssqlmain);
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
