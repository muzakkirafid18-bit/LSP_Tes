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

<link href="../css/tabelbaru2.css" rel="stylesheet">

<script type="text/javascript">
            $(document).ready(function () {
                $('#tanggal').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose:true
                });
            });
        </script>

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
if (!in_array($_SESSION['level'], ['lsp','admin'])) {
echo "<center><link href='css/adminstyle.css' rel='stylesheet' type='text/css'>
 <strong> Anda Tidak Punya Hak ...!</strong><br>";
 echo "<a href=../lsp_login.php class='btn btn-primary btn-sm' role='button'> Kembali</a></center>"; 
} 
else 
{

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
<li ><a href="inputskema.php"><svg class="glyph stroked paperclip"><use xlink:href="#stroked-paperclip"/></svg> Kelola Skema</a></li>
<li><a href="inputunit.php"><svg class="glyph stroked chain"><use xlink:href="#stroked-chain"/></svg> Kelola Unit</a></li>
<li><a href="inputasesor.php"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg>Kelola Asesor</a></li>
<li><a href="inputpeserta.php"><svg class="glyph stroked female user"><use xlink:href="#stroked-female-user"/></svg>Kelola Peserta</a></li>
<li><a href="inputelemen.php"><svg class="glyph stroked star"><use xlink:href="#stroked-star"/></svg> Kelola Komptetensi</a></li>
<li><a href="inputtempattuk.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"/></svg> Kelola Tempat TUK</a></li>
<li><a href="inputsyarat.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"/></svg> Input Persyaratan</a></li>
<li><a href="inputkumpan.php"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg>Input Umpan Balik</a></li>
<li><a href="inputpengurus.php"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg>Input Pengurus</a></li>
<li  class="active"><a href="settanggal.php"><svg class="glyph stroked clock"><use xlink:href="#stroked-clock"/></svg> SET Tanggal</a></li>
<li><a href="pemetaanasesor.php"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"/></use></svg> Atur jadwal</a></li>
<li><a href="inputpraktek.php"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg> FR MPA.05-Praktek</a></li>
<li><a href="inputtestulis.php"><svg class="glyph stroked clipboard with paper"><use xlink:href="#stroked-clipboard-with-paper"/></svg> FR-MPA 03 - Tes Tulis </a></li>
<li><a href="validasiapl1lsp.php"><svg class="glyph stroked usb flash drive"><use xlink:href="#stroked-usb-flash-drive"/></svg> Validasi APL1</a></li>
<li><a href="monitorasesi.php"><svg class="glyph stroked desktop computer and mobile"><use xlink:href="#stroked-desktop-computer-and-mobile"/></svg> Monitoring</a></li>
<li><a href="backupdata.php"><svg class="glyph stroked download"><use xlink:href="#stroked-download"/></svg> Backup data</a></li>

			<li role="presentation" class="divider"></li>
	
			<li><a href="../logout.php"><svg class="glyph stroked lock"><use xlink:href="#stroked-lock"/></svg> Logout</a></li>

		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>SET TANGGAL</h4>
                        
</div><!--/.row-->
		
		
				
		<!--/.row-->
				
		
		<div class="row">
			<div class="col-lg-12">
<!--data nu baru -->

<?php
$op = $_GET['op'];
 
if ($op == "tambahsett")
{

echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=simpansett\">";
?>
<table class=demo-table width=90%><thead><tr>
<td size="10"><strong>Tanggal    </strong></td><td>
    <input type="text" name="tanggaltes" id="tanggaltes" placeholder="Tahun/Bulan/Hari(2019-04-01)"></td></tr><tr>
<td size="10"><strong>TUK </strong>
	</td><td> 
	<select class="form-control" id="idtuktes" name="idtuktes" placeholder="TUK">
        
	<?php
	  	$tampiltes=mysql_query("SELECT * FROM tuk");
		while($rtes=mysql_fetch_array($tampiltes)){
			if($rtes['idtuk']==$tukptes) {
            
			echo "<option value=$rtes[idtuk] selected>$rtes[namatuk]</option> ";}
			else 
			{
			 echo "<option value=$rtes[idtuk]>$rtes[namatuk]</option> ";	
			}
		}
         
	?>
	 </select>   
      </div></td></tr>
<!--<td><strong>Status </strong></td><td> :
<input type="radio" name="yt" value="a" checked> Aktif <input type="radio" name="yt" value="t"> Tidak Aktif </td></tr><tr>-->
<td colspan="2"><input type="submit" name="simpan" id="gobutton" value="Simpan"></td></tr></thead></table>
<?php
   
}

else if($op=="simpansett")
{
$tgltes=$_POST['tanggaltes'];

$idtuktes=$_POST['idtuktes'];
$tanggaltes = date('Y-m-d', strtotime($tgltes));
$cekdatates = "SELECT * FROM tanggaltes WHERE namatuktes = '$idtuktes'";
   $adates=mysql_query($cekdatates);
   	if(mysql_num_rows($adates)>0){
         echo "duplikate..";}
        else{
    $ssqltgltes="insert into tanggaltes value('','$idtuktes','$tanggaltes')";
    $exectgltes=mysql_query($ssqltgltes);
    // echo $ssql;
    if($exectgltes){ 
    	?> <button onclick="goBack()">Sukses</button>

			<script>
			function goBack() {
			  window.history.go(-1);
			}
			</script>

    	<?php }
          else{echo "gagal ...";}
	}
}

else if($op=="hapustest")
{
$idtgltes=$_GET['idtgl'];
$hapustes="delete from tanggaltes where id_tgl='$idtgltes'";
$ehapustes=mysql_query($hapustes);
//echo $hapus;
    if($ehapustes){ echo "sukses...";}
          else{echo "gagal ...";}
}

else if($op=="edittest")
{
$idtgltes=$_GET['idtgl'];
$kettes=$_GET['tk'];
$tanggal=$_GET['tanggal'];
$st=$_GET['st'];
$tanggal = date("d-m-Y", strtotime($tanggal));
echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=updatetest\">";

?>
<table><tr>
<td><strong>Tanggal    </strong></td><td> : 
    <input type="text" name="tanggaltes" id="tanggaltes" placeholder="Tanggal Tes" value="<?php echo $tanggal;?>"></td></tr><tr>
<td><div class="form-group"><strong>TUK :</strong></td><td>
	<select class="form-control" id="idtuktes" name="idtuktes" placeholder="TUK" autofocus>
        
<?php $tampiltesedit=mysql_query("SELECT * FROM tuk");
		while($redit=mysql_fetch_array($tampiltesedit)){
			if($redit['idtuk']==$kettes) {
            
			echo "<option value=$redit[idtuk] selected>$redit[namatuk]</option> ";}
			else 
			{
			 echo "<option value=$redit[idtuk]>$redit[namatuk]</option> ";	
			}
		}
?>
</select>   
      </div></td>
</tr><tr>
<td colspan="2"><input type="submit" name="simpan" id="gobutton" value="Simpan"><input type="hidden" name="idtese" value="<?php echo $idtgltes;?>"></td></tr></table>
<?php
}

else if ($op=="updatetest")
{
$tgltesu=$_POST['tanggaltes'];
$kettesu=$_POST['idtuktes'];
//$yt=$_POST['yt'];
$idtesu=$_POST['idtese'];
$tanggaltesu = date('Y-m-d', strtotime($tgltesu));

$tesup="update tanggaltes set tgltes='$tanggaltesu',namatuktes='$kettesu' where id_tgl='$idtesu'";
//echo $up;
$teseup=mysql_query($tesup);
    if($teseup){ echo "sukses...";}
          else{echo "gagal ...";}

}

else if($op=="updatestatust")
{
 $idt = $_GET['id'];
 
   $queryustt = "SELECT * FROM settanggal WHERE id_set = '$idt'";
   $hasilustt = mysql_query($queryustt);
   $dataustt  = mysql_fetch_array($hasilustt);
   $stt=$dataustt['status'];
   //echo $st;
   if($stt=='A'){
      $sbarut='N';}
    else {
      $sbarut='A';}
   $sqlustut="update settanggal set status='$sbarut' where id_set='$idt'";
   $execustut=mysql_query($sqlustut);

}
?>


<body>
<?php echo "<a href=\"".$_SERVER['PHP_SELF'].
        "?op=tambahsett\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'></span>Tambah Tanggal</a>";?>
<table class=demo-table width=100%><thead>
<tr>
    <th bgcolor='#006699'>No</th>
    	
    <th bgcolor='#006699'>Tanggal</th>
    <th bgcolor='#006699'>Keterangan</th>
    <!--<th bgcolor='#006699'>Status</th>-->
    <th bgcolor='#006699'>Aksi</th>
</tr></thead>
 
<?php
 
// bagian ini digunakan untuk menampilkan semua data
 
$no = 1;
$querysetmaintes ="SELECT tanggaltes.id_tgl,tanggaltes.namatuktes,tanggaltes.tgltes,tuk.namatuk FROM tanggaltes inner join  tuk on tanggaltes.namatuktes=tuk.idtuk ";
//echo $querysetmaintes;
$hasilsetmaintes = mysql_query($querysetmaintes);
while ($datasetmaintes = mysql_fetch_array($hasilsetmaintes))
{
/*  if($datasetmain['status']=='A') {
    $ketset="Aktif";
    $warset="green";}

    else {
    $ketset="Tidak Aktif";
    $warset="red";} */
   echo "<tr>";
   echo "<td bgcolor='#99CCFF'>".$no."</td>";
   //echo "<td bgcolor='#99CCFF'>".$data['id_user']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$datasetmaintes['tgltes']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$datasetmaintes['namatuk']."</td>";
   //echo "<td bgcolor='#99CCFF' align=left>".$datasetmain['status']."</td>";
  	 //echo "<td align=left><strong><a href=\"".$_SERVER['PHP_SELF'].
		//			"?op=updatestatust&id=".$datasetmain['idtanggal']."\"><font color=$warset><span class='glyphicon glyphicon-refresh'> ".$ketset."</font></strong></td>";
   echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=edittest&tanggal=".$datasetmaintes['tgltes']."&tk=".$datasetmaintes['namatuktes']."&idtgl=".$datasetmaintes['id_tgl']."\"><span class='glyphicon glyphicon-pencil'>Edit</a> | <a href=\"".$_SERVER['PHP_SELF'].
        "?op=hapustest&idtgl=".$datasetmaintes['id_tgl']."\"><span class='glyphicon glyphicon-remove'>Hapus</a></td>";
   echo "</tr>";
   $no++;
}
   echo "</div>";
?>

<!--end databaru-->
			<div>
		</div>
		
                </div>
         </div> 
			
</body>

</html>

<?php 
   }
 } ?>
