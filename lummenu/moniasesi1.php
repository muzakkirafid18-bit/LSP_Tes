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

<script>

$(document).ready(function() {

var options = {
	beforeSend: function() {

		$("#progress").show();
		$("#bar").width('0%');
		$("#message").html("");
		$("#percent").html("0%");
		$("#upload-foto").attr("disabled",""); // Membuat button upload jadi tidak bisa terklik
		$("#upload-foto").html("Memproses...");
	 
	},
	uploadProgress: function(event, position, total, percentComplete) {

		$("#bar").width(percentComplete+'%');
		$("#percent").html(percentComplete+'%');

	},
	success:function(data, textStatus, jqXHR,ui) {
		$("#percent").html("100%");
		$("#progress").hide();
		$("#message").html(data);
		$("#upload-foto").removeAttr("disabled");
		$("#upload-foto").html("Upload");
		$("input[type='file']").val('');

	},
	error: function() {
		$("#message").html("<span style='color:red'> ERROR: Tidak dapat mengupload</span>");
	}
 
};
 
// kirim form dengan opsi yang telah dibuat diatas
$("#form-upload").ajaxForm(options);
 
});

</script>


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

	#progress { position:relative; width:300px;color:#111; border: 1px solid #ddd; padding: 1px;
				 border-radius: 3px;display: none; }
	#bar { background-color: #d2322d; width:0%; height:20px; border-radius: 3px; }
	#percent { position:absolute; display:inline-block; top:3px; left:48%; }

	</style>


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
			<li ><a href="inputskema.php"><svg class="glyph stroked paperclip"><use xlink:href="#stroked-paperclip"/></svg> Kelola Skema</a></li>
			<li ><a href="inputunit.php"><svg class="glyph stroked chain"><use xlink:href="#stroked-chain"/></svg> Kelola Unit</a></li>
			<li><a href="inputasesor.php"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg>Kelola Asesor</a></li>
			<li><a href="inputelemen.php"><svg class="glyph stroked star"><use xlink:href="#stroked-star"/></svg> Kelola Komptetensi</a></li>
			
			<li><a href="inputtempattuk.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"/></svg> Kelola Tempat TUK</a></li>
<li><a href="settanggal.php"><svg class="glyph stroked clock"><use xlink:href="#stroked-clock"/></svg> SET Tanggal</a></li>
<li><a href="pemetaanasesor.php"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"/></use></svg> Atur jadwal</a></li>
<li><a href="inputpraktek.php"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg> FR MPA.05-Praktek</a></li>
<li><a href="inputtestulis.php"><svg class="glyph stroked clipboard with paper"><use xlink:href="#stroked-clipboard-with-paper"/></svg> FR-MPA 03 - Tes Tulis </a></li>
<li><a href="inputfeed.php"><svg class="glyph stroked usb flash drive"><use xlink:href="#stroked-usb-flash-drive"/></svg> Feedback</a></li>
<li  class="active"><a href="monitorasesi.php"><svg class="glyph stroked desktop computer and mobile"><use xlink:href="#stroked-desktop-computer-and-mobile"/></svg> Monitoring</a></li>
<li><a href="backupdata.php"><svg class="glyph stroked download"><use xlink:href="#stroked-download"/></svg> Backup data</a></li>



			<li role="presentation" class="divider"></li>
	
			<li><a href="../logout.php"><svg class="glyph stroked lock"><use xlink:href="#stroked-lock"/></svg> Logout</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>Monitoring</h4>
                        
</div><!--/.row-->
		
		
				
		<!--/.row-->
				
		
		<div class="row">
			<div class="col-lg-12">
<!--data nu baru -->

<?php
$op = $_GET['op'];
 
if ($op == "edit")
{
   
$email= $_GET['email'];
$queryesiswa = "SELECT * FROM apl1 WHERE email = '$email'";
        $hasilesiswa = mysql_query($queryesiswa);
   	$dataesiswa  = mysql_fetch_array($hasilesiswa);
   	$iduser=$dataesiswa['idsiswa'];
	$nama=$dataesiswa['namasiswa'];
	//	$pass=$data['password'];
	//$telp=$data['notelp'];
	$emailuser=$dataesiswa['email'];
	$tmplahir=$dataesiswa['tmplahir'];
	$tgllahir=$dataesiswa['tgllahir'];
	$jeniskelamin=$dataesiswa['jeniskelamin'];
        $kebangsaan=$dataesiswa['kebangsaan'];
        $alamat=$dataesiswa['alamat'];
        $kodepos=$dataesiswa['kodepos'];
        $rumah=$dataesiswa['tlprumah'];
		$hp=$dataesiswa['hp'];
        $tlpkantor=$dataesiswa['tlpkantor'];
        $emailuser=$email;
        $pendidikan=$dataesiswa['pendidikan'];
        $lembaga=$dataesiswa['namalembaga'];
        $jurusan=$dataesiswa['jurusan'];
        
        $tgl = date("d-m-Y", strtotime($tgllahir));
 
	echo "<form id=\"formContoh\" method=\"post\" enctype=\"multipart/form-data\" action=\"".$_SERVER['PHP_SELF'].
        "?op=update\">";
		?>
		<table width="95%">
		<tr><td colspan="2" border="0" bgcolor='#006699'><strong>A. Biodata Peserta</strong></td><tr><td>
		<table border="0" width="90%">
		<thead>
								   
		<tr><td colspan="2">
		   <div class="form-group"><strong>Nama Lengkap :</strong>
			<div class="dynamiclabel">
			<input type="text" name="nama" id="nama" placeholder="Nama Lengkap"  value="<?php echo $nama; ?>" size="50" autofocus required/>
		  </div>
		  </div>
		 </td></tr>
		</td></tr><tr><td colspan="2">
		   <div class="form-group"><strong>Tempat Lahir :</strong>
			<div class="dynamiclabel">
			<input type="text" name="tmplahir" id="tmplahir"  size="50" value="<?php echo $tmplahir; ?>"required/>
		  </div>
		  </div>
		 </td></tr>
			<tr><td colspan="2"> 
		   <div class="form-group"><strong>Tanggal Lahir :</strong>
		   <div class="dynamiclabel">
				        <label for="tanggal"></label>
				        <input type="text" name="tanggal" id="tanggal" value="<?php echo $tgl ;?>" />
				    </div>
				    </div>
		   </td></tr>
		   <tr><td colspan="2">
		   <div class="form-group"><strong>Jenis Kelamin :</strong>
		   <div class="dynamiclabel">
   				<?php if($jeniskelamin=='lk'){?>
   						<input type="radio" name="jk" value="lk" <?php echo checked ;?>/>Laki-laki<br/>
   						<input type="radio" name="jk" value="pr"/>Perempuan<br/>
    					<?php }else if ($jeniskelamin=='pr'){ ?>
						<input type="radio" name="jk" value="lk"/>Laki-laki<br/>
					   <input type="radio" name="jk" value="pr" <?php echo checked ;?> />Perempuan<br/>
   				<?php }else{ ?>
					   <input type="radio" name="jk" value="lk"/>Laki-laki<br/>
					   <input type="radio" name="jk" value="pr" />Perempuan<br/>
					   <?php } ?>
					   </div>
					   </div>
					   </td></tr>
					   <tr><td colspan="2">
					   <div class="form-group"><strong>Kebangsaan:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="kebangsaan" id="kebangsaan" size="10" value="<?php echo $kebangsaan; ?>" required/>
					   </div>
					   </div>
					   </td></tr>
					   <tr><td>
					   <div class="form-group"><strong>Alamat:</strong>
					   <div class="dynamiclabel">
					   <textarea name="alamat" id="alamat" cols="50" required/><?php echo $alamat; ?></textarea>
					   </div>
					   </div>
					   </td>
					   <td>
					   <div class="form-group"><strong>Kode Pos:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="kodepos" id="kodepos" size="10" value="<?php echo $kodepos; ?>"required/>
					   </div>
					   </div>
					   </td></tr>
					   <tr><td>
					   <div class="form-group"><strong>No Telp Rumah:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="rumah" id="rumah" size="15" value="<?php echo $rumah; ?>">
					   </div>
					   </div>
					   </td>
					   <td>
					   <div class="form-group"><strong>No Telp Kantor:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="kantor" id="Kantor" size="15" value="<?php echo $tlpkantor; ?>">
					   </div>
					   </div>
					   </td></tr>
					   <tr><td>
					   <div class="form-group"><strong>Hp:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="hp" id="hp" size="15" value="<?php echo $hp; ?>" required/>
					   </div>
					   </div>
					   </td>
					   <td>
					   <div class="form-group"><strong>Email:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="emailuser" id="emailuser" placeholder="Email Aktif" value="<?php echo $emailuser; ?>" size="30" readonly/>
					   </div>
					   </div>
					   </td></tr>
						   <tr><td colspan="2">
						   <div class="form-group"><strong>Pendidikan Terakhir:</strong>
						   <div class="dynamiclabel">
						   <input type="text" name="pendidikan" id="pendidikan" size="50" value="<?php echo $pendidikan; ?>"required/>
						   </div>
						   </div>
						   </td></tr></table>
						   <tr><td colspan="2" border="0" bgcolor='#006699'><strong>B. Data Pendidikan</strong> </td><tr><td>
						   <table width="95%">
						   <tr><td colspan="2">
						   <div class="form-group"><strong>Nama Sekolah / lembaga:</strong>
						   <div class="dynamiclabel">
						   <input type="text" name="lembaga" id="lembaga" size="70" value="<?php echo $lembaga; ?>" required/>
						   </div>
						   </div>
						   </td></tr>
						   <tr><td colspan="2">
						   <div class="form-group"><strong>Jurusan / Program :</strong>
						   <div class="dynamiclabel">
						   <input type="text" name="jurusan" id="jurusan" size="50" value="<?php echo $jurusan; ?>" required/>										
						   </div>
						   </div>
						   </td></tr><tr>
						<td>File Photo <font color='red'> ( Ukuran file max 100kb dan type file jpg,png)</font> <input type="file" name="fotox3" /></td>
						</tr>
						</table>
						</thead> 	
        				</table>					    
					   <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
					   <input type="hidden" name="emaillama" value="<?php echo $emailuser; ?>">
					   <input type="submit" id="gobutton" value="Simpan"> 
					   <!--<a href="setup_registrasi.php" class="button">Register</a>-->
				
					   </form>
						</div>
	
<?php
}
else if ($op == "uploadgbr")
{
?>

<div style="width:50%;margin:0 auto;border-radius:5px;background:#eee;padding:10px">

	<form name="contoh" method="post" action="uploadgambarbukti.php" enctype="multipart/form-data" id="form-upload">
	  <!--<<?php echo "<form id=formContoh method=\"post\" action=\"".$_SERVER['PHP_SELF']."?op=uploadgbrbukti\">";?>-->

		<input type="file" name="foto[]" multiple />
		<input type="submit" id="upload-foto" value="Upload">
	</form>

	<!-- untuk progress bar -->
	<div id="progress">
	<div id="bar"></div>
	<div id="percent">0%</div>
	</div>
	<br/>
	<!-- pesan setelah proses upload -->
	<div id="message"></div>

</div>

<?php

}

else if ($op == "uploadgbrbukti")
{

$dir_foto = "../siswa/gambarimages/";

if ( !empty($_FILES['foto']['name']) ) {

	for ( $i = 0; $i < count( $_FILES['foto']['name']); $i++ ) {

		$nama_foto = $_FILES['foto']['name'][$i];
		$ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		$ekstensi = array('pdf','PDF'); // Ektensi yg diterima
		 chmod($nama_foto, 0777);
		//filter ektensi foto yang diterima
		if( in_array( $ext, $ekstensi ) ) {
		 
			//maks ukuran foto 500kb
			if( $_FILES['foto']['size'][$i] < 2000000 ) {
				 
				if ( move_uploaded_file( $_FILES['foto']['tmp_name'][$i], $dir_foto . $nama_foto ) ) {

					
					echo "Foto <b>" . $_FILES['foto']['name'][$i] . "</b> Berhasil <br />";
				
				} else {
					echo "Foto <b>" . $_FILES['foto']['name'][$i] . " </b>Gagal <br />";
				}

			} else {

				echo "Ukuran foto terlalu besar <br />";
			}

		} else {

			echo "Format  " . $_FILES['foto']['name'][$i] . "  tidak didukung. <br>";
		}

	}

} else {

	echo "Foto masih kosong.";
}


}


else if ($op == "tambah")
{
?>
  
  <form id="formContoh" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=append \">";?>
  <hr>
  <div class="form-group"><strong>Email :</strong>
  <input type="text" name="emailuser" id="emailuser" placeholder="Email Aktif" class="form-control" autofocus required/>
</div>
   <div class="form-group"><strong>Nama Lengkap :</strong>
    <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" class="form-control" required/>
  </div>
     <div class="form-group"><strong>No Telp :</strong>
    <input type="text" name="notelp" id="notelp" placeholder="Nomor Telp" class="form-control" required/>
  </div>

  <div class="form-group"><strong>Password :</strong>
  <input type="password" name="password" id="password" placeholder="Password" class="form-control" required/>
  </div>
<div class="form-group"><strong>Re Password :</strong>
  <input type="password" name="repassword" id="repassword" placeholder="RePassword" class="form-control" required/>
  </div>
   <hr>
   <input type="submit" id="gobutton" value="Simpan" class="button"> 
   <!--<a href="setup_registrasi.php" class="button">Register</a>-->
  </form>
</div>
<br/>

<?php

}
else if ($op == "append")
     {
      $emailuser=$_POST['emailuser'];
      $nama=$_POST['nama'];
      $password=md5($_POST['password']);
      $ket=$_POST['ket'];
      $level="asesor";
      $notelp=$_POST['notelp'];
      $cekdata="select email from lsp_usertbl where email='".$emailuser."'";
	$ada=mysql_query($cekdata);
	if(mysql_num_rows($ada)>0){
           echo "Gagal Duplikat...";}
      else {
      if (!empty($emailuser)){
      $queryappend = "INSERT INTO lsp_usertbl VALUE('','$nama','$emailuser','$password','1','0','$level','$notelp')";
      //echo $query;
      $berhasilappend = mysql_query($queryappend);
      if ($berhasilappend) echo "Proses Sukses";
      else echo "Proses Gagal";
      }
      }
}

else if ($op == "update")
     {
 
       // proses untuk updating data setelah diedit
 
              	$iduser = $_POST['iduser'];
        
		   //$iduser=$data['id_user'];
		   	$nama=$_POST['nama'];
		   	$emailuser=$_POST['email'];
		   	$tmplahir=$_POST['tmplahir'];
			$tgllahir=$_POST['tanggal'];
		    $jeniskelamin=$_POST['jk'];
		    $kebangsaan=$_POST['kebangsaan'];
		    $alamat=$_POST['alamat'];
		    $kodepos=$_POST['kodepos'];
		    $tlprumah=$_POST['rumah'];
			$hp=$_POST['hp'];
        	$tlpkantor=$_POST['kantor'];
		    $pendidikan=$_POST['pendidikan'];
		    $lembaga=$_POST['lembaga'];
		    $jurusan=$_POST['jurusan'];
		    $email=trim($_POST['emailuser']);
			$dir_foto = "../siswa/gambardiri/";
			//echo $nama;
	   		//echo $emailuser;
	   		//echo $tmplahir;
		    $my_date = date('Y-m-d', strtotime($tgllahir));
                     $namasa=explode(" ", $nama);
                     $namasb=$namasa[0].$namasa[1];
                     
	             $nama_foto =$iduser.$namasb.$_FILES['fotox3']['name'];
                     //echo  $nama_foto;
                     $ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		$ekstensi = array('png','PNG','jpg','JPG'); // Ektensi yg diterima
                 if( in_array( $ext, $ekstensi ) ) {
          		if( $_FILES['fotox3']['size']< 100000 ) {
                        if ( move_uploaded_file( $_FILES['fotox3']['tmp_name'], $dir_foto . $nama_foto ) ) {
                           $potopes="Photo Sukses ...";}
                        else { $potopes=" Photo Gagal .., Ukuran photo teralalu besar atau type bukan PNG";}
                        
        	 } else { $potopes=" Photo Gagal .., Ukuran photo teralalu besar";}
			} else { $potopes=" Photo Gagal ..,type bukan PNG";}
			
		    
   				   
					$queryusiswa = "UPDATE apl1 SET 				namasiswa='$nama',tmplahir='$tmplahir',tgllahir='$my_date',jeniskelamin='$jeniskelamin',kebangsaan='$kebangsaan', alamat='$alamat', kodepos='$kodepos', tlprumah='$tlprumah', hp='$hp', tlpkantor='$tlpkantor', email='$email', pendidikan='$pendidikan', namalembaga='$lembaga',jurusan='$jurusan',poto='$nama_foto' WHERE email = '$email'";		
       
        //echo $query;
 		       $hasilusiswa = mysql_query($queryusiswa);
 			       if ($hasilusiswa) echo "Proses Update Biodata Sukses , DAN  <font color=red> ".$potopes."</font>";
 			       else echo "<p>Proses Update Gagal</p>";
 
     }
else if ($op == "delete"){

   $iduser= $_GET['idasesi'];
   $querydelete = "SELECT * FROM lsp_usertbl WHERE id = '$iduser'";
   $hasildelete = mysql_query($querydelete);
   $datadelete  = mysql_fetch_array($hasildelete);
   $iduser=$datadelete['id'];
   $nama=$datadelete['nama'];
 //echo "<form name='myform' method='post' action='".$_SERVER['PHP_SELF']."??op=edit'>";
   echo "<form id=formContoh method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=deletepost\">";
         echo "<input type=hidden name=iduser value=".$iduser.">";
          ?>
	 <div class="formcolumn">
    	Yakin user : <strong><font color=red><?php echo $nama ?></font></strong>  Akan di hapus ? 
        <div class="buttons">
        <?php
       echo "<input type=submit id=gobutton value=Delete>
	
        </div></div>";
          
echo "</form>";
echo "<br/><br/><br/><br/>"; 
} 

else if ($op == "deletepost"){
$sql="DELETE from lsp_usertbl WHERE id=".$_POST['iduser'];
mysql_query($sql);
}

else if ($op == "validasi")
{
//$idasesor=$_GET['idasesor'];
//$idskema= $_GET['idskema'];
$idasesi= $_GET['idasesi'];
$namaadsesi=$_GET['nama'];
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
    $idasesor=$adax['idasesor'];
    $catatan=$adax['catatan'];
  

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
    $ssql2="select * from elemen where idskema='$idskema1' and idunit='$idunit' and idelemen='$idelemen'";
   
    //echo $ssql2;
    $exec2=mysql_query($ssql2);
    $list2= mysql_fetch_array($exec2);
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
echo"</tr><td colspan=2 rowspan=3> Catatan </br><input type=text name=catatan value=$cat><td colspan=5> ID Asesor :</td></tr><tr><td>Nama : </td><td colspan=4>$idasesor</td></tr><tr><td>Tanda Tangan : </td><td colspan=4></td>";
    echo "</tr><tr><td colspan=7><input type='hidden' name='n' value='".$i."'></td>"; 
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
} else { echo "<strong><font color='red'>TIDAK ADA DATA APL 1<font></strong>";}
}


else if ($op == "validasiapl2")

{
?>
<!--<form name="contoh" method="post" action="apl2post.php" enctype="multipart/form-data" id="form-upload">
<table>-->
<?php


//echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
    //    "?op=simpanvalidasiapl2\">";
        //$idasesor=$_GET['idasesor'];
        //$ie=$_GET['kode'];
        $idadsesi=$_GET['idasesi'];
        $namaadsesi=$_GET['nama'];
        //$tgl=$_GET['tgl'];
	//$kelompok=$_GET['k'];	
	$emailuser=trim($_GET['email']);
	$sql="select * from lsp_usertbl where id='$idadsesi'";
	$shasil=mysql_query($sql);
	$sdata=mysql_fetch_array($shasil);
	$namap=$sdata['nama'];
	$idp=$sdata['id'];
        $emailp=$sdata['email']; 
 $cekdapl2="select * FROM apl2 where email='$emailuser'";
 $execceapl2=mysql_query($cekdapl2);
 if(mysql_num_rows($execceapl2)>0){
         if($ie=="ed"){
        $cekdata0 = "SELECT * FROM rekomendasi WHERE namarekom = 'apl2' and idasesi='$idadsesi'";
          } 	
 		// echo $cekdata0;
   	$ada0=mysql_query($cekdata0);
   	$adax=mysql_fetch_array($ada0);
   
    	$lrek=$adax['rekom'];
    	$cat=$adax['catatan'];
        $namax=$adax['idasesor'];
       	if($lrek=='L'){
      	$klrek='checked';
     	}else{$klrek='';}
     	if($lrek=='T'){
      	$klrek0='checked';
     	}else{$klrek0='';}
    
    $catatan=$data0['catatan'];        
	$cek="select * from skemasiswa where emailsiswa='$emailp' and statustesp='N'"; 
	$ada=mysql_query($cek);
        //echo $cek;
	if(mysql_num_rows($ada)>0){
		$data  = mysql_fetch_array($ada);
	        $skema=$data['idskema'];
	        $statusapl1=$data['statusapl1'];
                //echo "lklkl";
	        if($statusapl1=='Y')
	 		{
			$spemet="select * from pemetaan where idskema='$skema' and idpeserta='$idp'";
			$execpe=mysql_query($spemet);
			$hpemet=mysql_fetch_array($execpe);
			$namaass=$hpemet["namaasesor"];
			$tgl=$hpemet["tanggal"];
			//echo $namaass;
			  $ske="select * from skema where idskema='$skema'";  
			  $ske1=mysql_query($ske);
			  $ske2=mysql_fetch_array($ske1);
			  $namaskema=$ske2['namaskema'];
                          echo "<input type=hidden name=tgl value='$tgl'>";
                          echo "<input type=hidden name=kelompok value='$kelompok'>"; 
			  echo "<table><tr><td colspan=9>FR-APL-02 ASESMEN MANDIRI</td></tr><tr><td colspan=4>
				Nama Peserta : $namap </td><td colspan=5>Tanggal : $tgl </td></tr><tr>
				<td colspan=4>Nama Asesor : $namaass<td colspan=5></td></tr>             
				<tr><td colspan=9> Pada Bagian ini anda di minta untuk menilai diri sendiri terhadap unit kompetensi yang akan di ases</td></tr><tr> 
			       <td colspan=8>1. Pelajari seluruh standar Kriteria Unjuk Kerja  (KUK), batasan variabel, panduan penilaian dan aspek kritis serta yakinkan bahwa anda sudah benar-benar memahami seluruh isinya.</br>
2. Laksanakan penilaian mandiri dengan mempelajari dan menilai kemampuan yang anda miliki secara obyektif terhadap seluruh daftar pertanyaan yang ada, serta tentukan apakah sudah kompeten (K) atau belum kompeten (BK). </br>  
3. Siapkan bukti-bukti yang anda anggap relevan terhadap unit kompetensi, serta ‘matching’-kan setiap bukti yang ada terhadap setiap elemen/KUK, konteks variable, pengetahuan dan keterampilan yang dipersyaratkan serta aspek kritis</br>
4. Asesor dan asesi menandatangi form Asesmen Mandiri.</td></tr>";
		
			      $sqlunit="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$skema' and unitsiswa.idadsesi='$idp' order by unitsiswa.idunit";
			    //echo $sqlunit;
			    $eunit=mysql_query($sqlunit);

			      $i=0;
			    echo "<input type=hidden name=idskema value='$skema'>";
			    echo "<input type=hidden name=idadsesi value='$idp'>";
			    echo "<input type=hidden name=email value='$emailp'>";
			    echo "<input type=hidden name=idasesor value='$idasesor'>";
			    while ($dunit=mysql_fetch_array($eunit))
				    {
			   
     
				       $sqelemen="select * from elemen where idunit='".$dunit['idunit']."' and idskema='$skema'";
					//echo $sqelemen;				       
					$eelemen=mysql_query($sqelemen);
				      echo "<tr><th colspan=9>".$dunit['kodeunit']." ".$dunit['namaunit']."</th></tr>";
					 $y=0;
					while ($delemen=mysql_fetch_array($eelemen))
      						{
          						$y++;
           	
							    $sqsubelemen="select * from subelemen where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema'";
//echo $sqsubelemen;
            $esubelemen=mysql_query($sqsubelemen);


							    $x=0;
									echo "<tr><th colspan=9>".$y.". ".$delemen['namaelemen']."</th></tr>";
									echo "<tr><td colspan=3><strong>NO KUK</strong></td><td><strong>SUB KOMPETENSI</strong></td><td><strong>K</strong></td><td><strong>BK</strong></td><td colspan=2><strong>BUKTI  PENDUKUNG</strong> </td><td colspan=2><strong>VALIDASI</strong> </td>"; 
									
									while ($dsubelemen=mysql_fetch_array($esubelemen))
									  {
							    			 $idsube=$dsubelemen['idsubelemen'];
										 $kuk=$dsubelemen['pertanyaan'];
              									 $x++;
								/*	         if($ie=="in"){
                                                                                 $cekapl2="select * from apl2 where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema' and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp'"; }
											else {
										$cekapl2="select * from apl2 where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema' and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' " ; }
*/     								
									$cekapl2="select * from apl2 where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema' and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' " ;		
//echo $cekapl2;
									$rcekapl2=mysql_query($cekapl2);
									//if(mysql_num_rows($rcekapl2)>0){ 
                                                                        $pp='';
									$ketk="";
    			    						$ketbk="";
									$sbukti="";
									$kdbs0="";
									$kdbs1="";
									$kdbs2="";
									$kdbs3="";
								
									if(mysql_num_rows($rcekapl2)>0)

									{ 
									
									$hcekapl2=mysql_fetch_array($rcekapl2);
									//while ($hcekapl2=mysql_fetch_row($rcekapl2)){  
 									//echo "abc"; 
									//echo "ook".$hcekapl2['idsubelemen'];    
									 $gambar="../siswa/gambarimages/".$hcekapl2['path']; 
									 $pp="Tampilkan bukti";
									 $sbukti=$hcekapl2['sbukti']; 
									 $pecahdbs=explode(",",$sbukti);
    
									        $dbs0=$pecahdbs[0];
									        $dbs1=$pecahdbs[1];
										$dbs2=$pecahdbs[2];
										$dbs3=$pecahdbs[3];
										//echo "B".$db0;
										if(trim($dbs0)=='v')
										{
										 $kdbs0="checked";
										}
									       if(!empty($dbs1))
										{
										 $kdbs1="checked";
										}
									       if(!empty($dbs2))
										{
										 $kdbs2="checked";
										}
									       if(!empty($dbs3))
										{
										 $kdbs3="checked";
										}
									  
									 $kbk=$hcekapl2['tk'];
									    if($kbk=='K')
										{$ketk="checked";}
										else{$ketbk="checked";}
									//} 
									//	echo $sbukti;								 //$kuk=$hcekapl2['pertanyaan'];
									 //echo $x.$kbk."</br>";
									         
  									 echo"<tr><td colspan=3>".$y.".".$x."</td><td>$kuk<input type=hidden name=idunit".$i." value=".$dunit['idunit']."><input type=hidden name=idelemen".$i." value=".$delemen['idelemen']."></td><td><input type=radio name=bk".$i." value=k $ketk disabled=disabled><br/></td><td><input type=radio name=bk".$i." value=bk $ketbk disabled=disabled></td><td><input type=hidden name=idsube".$i." value='$idsube'></td></td><td><a href=$gambar target=_blank>$pp</td>";
echo"<td><input type=checkbox name=validasia".$i." value='v' $kdbs0>V<input type=checkbox name=validasib".$i." value='a' $kdbs1>A<input type=checkbox name=validasic".$i." value='t' $kdbs2>T <input type=checkbox name=validasid".$i." value='m' $kdbs3>M</td>";
									     }else{
										echo "<tr><th colspan=9> -----tidak ada data----</th>";}
            									 $i++;
                      							 }
 						}
      					}
					echo"</tr><td colspan=2 rowspan=3> Rekomendasi </br><input type=radio name=lrek value='L' $klrek >Lanjut</br> <input type=radio name=lrek value='T' $klrek0>Tidak</td><td colspan=7>Asesi :</td></tr><tr><td>Nama : </td><td colspan=6>$namap</td></tr><tr><td>Tanda Tangan : </td><td colspan=6></td>";
echo"</tr><td colspan=2 rowspan=3> Catatan </br><input type=text name=catatan value=$cat><td colspan=7>Asesor :</td></tr><tr><td>Nama : </td><td colspan=6>$namax</td></tr><tr><td>Tanda Tangan : </td><td colspan=6></td>";
    echo "</tr><tr><td colspan=9><input type='hidden' name='n' value='".$i."'>";
  				}
		}


?>
</table>
<!--</td><td><input type=hidden  name="n" value="<?php echo $i ;?>"/>-->

<?php
}else{ echo "<font color='red'> BELUM ADA DATA APL2 </font>";}
}

else if ($op == "observasi")
{
//$idskema= $_GET['idskema'];
$idasesi= $_GET['idasesi'];
//$tgl= $_GET['tgl'];
//$kelompok=$_GET['k'];


$ie='ed';
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

$sqlunit="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idadsesi='$idasesi'";
$execunit=mysql_query($sqlunit); 
//echo "<form name=contoh method=post action=postobservasi.php enctype=multipart/form-data id=form-upload>";
if(mysql_num_rows($execunit)>0){
echo "<table width=95%><tr><td bgcolor='#99CCFF' colspan=8> Nama Asesi : $namaadsesi </strong></td></tr>";
$i=0;
while ($listunit = mysql_fetch_array($execunit))
   {
  
//echo "<form id=\"formContoh\" method=\"post\" action=\"postobservasi.php\">";
    

$ssql="select rekappraktek.idrpraktek,rekappraktek.idskema,rekappraktek.idadsesi,rekappraktek.bunit,rekappraktek.kodeunit,rekappraktek.idpraktek,rekappraktek.idasesor,rekappraktek.tanggal,rekappraktek.pencapaians,rekappraktek.penilaians,praktek.instruksi,praktek.obervasi from rekappraktek inner join praktek on rekappraktek.idpraktek=praktek.idpraktek where rekappraktek.idskema='".$listunit['idskema']."' and rekappraktek.idadsesi='$idasesi' and rekappraktek.kodeunit='".$listunit['kodeunit']."'";   
  //echo $ssql;
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
    
     echo "</table>";
echo "</form>";    
}else{ echo "<font color='red'> ASESOR BELUM MELAKUKAN OBSERVASI UNTUK ASESI TERSEBUT ...</font>";}
}

else if ($op == "ubahpassword")
{

$editpass="SELECT * FROM lsp_usertbl where id='".$_GET['idasesi']."'";
$editpassa=mysql_query($editpass);
//echo $editpass;	
	$rpass=mysql_fetch_array($editpassa);
	//<tr><td>RePassword</td><td>:		<input type=text name=rpassword></td></tr>
        $namap=$rpass['nama'];
echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
    "?op=postubahpass\">";
echo "<div id=three>

	<input type=hidden name=iduser value='$rpass[id_user]'>
	
	
		<table width=60%>
               
                <th colspan=2><h4><strong>Reset Password</strong></h4></th>
		<tr><td>Nama Asesi </td><td>: $namap </td></tr>		
		<tr><td>Password</td><td>:
		<input type=text name=passwordubah> * </td></tr>
		
		<tr><td colspan=2>*)Apabila tidak diubah, dikosongkan saja</td></tr>
		<tr><td colspan=2>
                <input class=submit name=dsubmit type=submit value=Kirim>
		</td></tr>
	</table>
	</form></div>";

}
else if ($op=="postubahpass"){

  $iduser=$_POST['iduser'];
  $passu=trim($_POST['passwordubah']);
  // echo "siap".  $passu;  
if (!empty($passu)){
		$pass1=md5($passu);
		$sqlup="UPDATE lsp_usertbl SET password='$pass1' where id='$iduser'";
                echo $sqlup;
                $suks=mysql_query($sqlup);
                if($suks) 
		{ echo " Perubahan Sukses ...";}
		else { echo "gagal....";} 

} else { echo "<font color='green'>Tidak ada perubahan password </font>";}
}

else if ($op=="rekaphasiltes"){
$email=$_GET['email'];
$sqlgrade="Select grade.id,grade.nim,grade.kd_modul,grade.grade,unit.namaunit from grade inner join unit on grade.kd_modul=unit.kodeunit  where grade.nim='$email'";
//echo $sqlgrade;
$exegrade=mysql_query($sqlgrade);
if(mysql_num_rows($exegrade)>0){
echo "<font color=red> Hati dalam menghapus hasil tes !!!</font>";
echo "<table><tr>";
echo "<td colspan=2 bgcolor='#A9D0F5'><strong>Kode Unit</strong></td>";
echo "<td bgcolor='#A9D0F5'><strong>Hasil Tes</strong></td><td bgcolor='#A9D0F5'><strong>Keterangan</strong></td><td colspan=2 bgcolor='#A9D0F5'><strong>Aksi</strong></td></tr>";
while ($rowgrade=mysql_fetch_array($exegrade)){
if($rowgrade['grade']<100)
 {
 $ket="Belum Kompeten";
 $wr="red";} 
 else {
 $wr="green";
 $ket="Kompeten";}
echo "<tr>";
echo "<td>".$rowgrade['kd_modul']."</td>";
echo "<td>".$rowgrade['namaunit']."</td>";
echo "<td>".$rowgrade['grade']."</td>";
echo "<td><font color=$wr> $ket </font></td>";
echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=hapusgrade&idgrade=".$rowgrade['id']."\"><span class='glyphicon glyphicon-trash'>Hapus Hasil</font></a>";
echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=editgrade&idgrade=".$rowgrade['id']."\"><span class='glyphicon glyphicon-trash'>Edit Hasil</font></a>



</tr>";
}
echo "</table>";
} else { echo "<font color=red> Belum tes </font>";}
}

else if ($op=="hapusgrade"){
$idgrade=$_GET['idgrade'];
$sqldgrade="Delete from grade where id='$idgrade'";
//echo "abc".$sqldgrade;
$hps=mysql_query($sqldgrade); 
if($hps){
    echo "Hapus sukses ..";}
   else{ echo "Gagal ..";}

}

else if ($op=="editgrade"){
$idgrade=$_GET['idgrade'];
$sqlgradee="select * from grade where id='$idgrade'";
$edgrade=mysql_query($sqlgradee);
$dataeditg  = mysql_fetch_array($edgrade);
echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=updategrade\">";

echo "<table width=65%>";
echo "<tr><th colspan=2> Edit Nilai </th></tr>";
echo "<tr><td>Kode Unit : </td><td>".$dataeditg['kd_modul']."</td></tr>";
echo "<tr><td>Jumlah Soal : </td><td>".$dataeditg['jumlah_soal']."</td></tr>";
echo "<tr><td>Jumlah Salah : </td><td><input type=text name=salah value=".$dataeditg['salah']."></td></tr>";
echo "<tr><td>Jumlah Benar : </td><td><input type=text name=benar value=".$dataeditg['benar']."></td></tr>";
echo "<tr><td>Nilai : </td><td><input type=text name=nilai value=".$dataeditg['grade']."></td></tr>";
echo "<tr><td colspan=2><input type=hidden name=idlama value=".$dataeditg['id']."></td></tr>";
echo "<tr><td colspan=2><input id=gobutton type=submit value=Simpan></td>";
echo "</tr></table>";
echo "</form>";

}

else if ($op=="updategrade"){
$id=$_POST['idlama'];
$salah=$_POST['salah'];
$benar=$_POST['benar'];
$nilai=$_POST['nilai'];
$sqlupdateg="update grade set salah='$salah', benar='$benar', grade='$nilai' WHERE id = '$id'";
//echo $sqlupdateg;
$execupdateg=mysql_query($sqlupdateg);


}



else if ($op=="cekstatus"){
$email=trim($_GET['email']);
$sqlapl1="Select * from apl1 where email='$email'";
$execsqlapl1=mysql_query($sqlapl1);
$rowapl1=mysql_fetch_array($execsqlapl1);
echo "<font color=blue>BIODATA</font></br>";
if(mysql_num_rows($execsqlapl1)>0){
echo "<font color=green> <span class='glyphicon glyphicon-floppy-saved'> Sudah terdaftar dengan nama : ".$rowapl1['namasiswa']." </font>";
}
else { echo "<font color=red> <span class='glyphicon glyphicon-floppy-remove'>Belum terdaftar</font>";}

$sqlpermohonan="select * from permohonan where email='$email' group by email,tanggal";
//echo $sqlpermohonan;
$execsqlpermohonan=mysql_query($sqlpermohonan);
echo "</br><font color=blue>PERMOHONAN</font></br>";

if(mysql_num_rows($execsqlpermohonan)>0){
while ($rowpermohonan=mysql_fetch_array($execsqlpermohonan)){

echo "<font color=green><span class='glyphicon glyphicon-floppy-saved'> Terdaptar dengan Email ".$rowpermohonan['email']." Pada Tanggal ".$rowpermohonan['tanggal']."</font>";

  }

}else { echo "<font color=red><span class='glyphicon glyphicon-floppy-remove'> Belum terdaftar</font>";}

$sqlupload="select * from upload where email='$email' group by email,waktu";
//echo $sqlupload;
$execsqlupload=mysql_query($sqlupload);
echo "</br><font color=blue>APL1 Dengan Bukti</font></br>";
if(mysql_num_rows($execsqlupload)>0){
while ($rowupload=mysql_fetch_array($execsqlupload)){

echo "<font color=green><span class='glyphicon glyphicon-floppy-saved'> Terdaptar dengan Email ".$rowupload['email']." Pada Tanggal ".$rowupload['waktu']."</font>";

  }
 }else { echo "<font color=red><span class='glyphicon glyphicon-floppy-remove'> Belum terdaftar</font>";}

$sqlapl2="select * from apl2 where email='$email' group by email,waktu";
$execsqlapl2=mysql_query($sqlapl2);
echo "</br><font color=blue>APL2 Dengan Bukti</font></br>";
if(mysql_num_rows($execsqlapl2)>0){
while ($rowapl2=mysql_fetch_array($execsqlapl2)){

echo "<font color=green><span class='glyphicon glyphicon-floppy-saved'> Terdaptar dengan Email ".$rowapl2['email']." Pada Tanggal ".$rowapl2['waktu']."</font>";
}
}else { echo "<font color=red><span class='glyphicon glyphicon-floppy-remove'> Belum terdaftar</font>";}

$slquser="select * from lsp_usertbl where email='$email'";
$execslquser=mysql_query($slquser);
$rowuser=mysql_fetch_array($execslquser);
$iduser=$rowuser['id'];
//echo $iduser;
echo "</br><font color=blue>ASESOR</font></br>";
$sqljadwal="select * from pemetaan where idpeserta='$iduser'";
$execsqljadwal=mysql_query($sqljadwal);
if(mysql_num_rows($execsqljadwal)>0){
$rowjadwal=mysql_fetch_array($execsqljadwal);
$asesor=$rowjadwal['namaasesor'];

echo "<font color=green><span class='glyphicon glyphicon-floppy-saved'> Nama Asesor : $asesor </font>";}
else{echo "<font color=red><span class='glyphicon glyphicon-floppy-remove'> Belum di Jadwalkan</font>";}

}

?>


<body>
<br/>
<?php echo "<a href=\"".$_SERVER['PHP_SELF'].
        "?op=uploadgbr\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'></span>Upload Bukti</a>";?>


<table width="95%">
<tr><td><input type="button" value="REFRESH" onclick="window.location='monitorasesi.php'">
<form method="post" action="moniasesi1.php">
<input type="hidden" name="sqlaction" value="SEARCH">
<tr><td>Kriteria pencarian :
<select name="txtkriteria">
<option value="nama"> NAMA
</select>
<input type="text" name="txtcari">
<input type="submit" value="CARI"><br></td>
<tr> 
</table>
<table  width="97%">
<th align="center"> NO </th>
<th align="center"> ID Asesi</th>
<th align="center"> NAMA </th>
<th align="center"> Status Email </th>
<th colspan="7" align="center"> Aksi </th>

<tr>
<?php
//<table border=1>

//echo "<table border=1>";
$dataPerPage = 25;
if(isset($_GET['page']))
{
    $noPage = $_GET['page'];
} 
else $noPage = 1;
$offset = ($noPage - 1) * $dataPerPage;
if($_POST['sqlaction']=="SEARCH"){
$sql="Select * from lsp_usertbl WHERE level='peserta' and ".
$_POST['txtkriteria']." LIKE '%".
$_POST['txtcari']."%' LIMIT $offset, $dataPerPage";
} else {
$sql="select * from lsp_usertbl where level='peserta' order by id LIMIT $offset, $dataPerPage";
}

$result=mysql_query($sql);
$number=1;
while ($row=mysql_fetch_row($result)){
if($row[4]==1){$konok='Sudah konfirmasi'; $wrn='green';} else { $konok='Belum Konfirmasi'; $wrn='red';}
echo "<tr>";
echo "<td>".$number."</td>";
//echo "<td>".$row[0]."</td>";
echo "<td align=left><a href=\"".$_SERVER['PHP_SELF'].
        "?op=edit&email=".$row[2]."\" title='Edit Biodata'>*".$row[0]."</a></td>";
echo "<td align=left><a href=../siswa/biodatasiswa.php?email=" .trim($row[2]). " target=_blank><span class='glyphicon glyphicon-user'>".$row[1]."</td></a>";
//echo "<td align=left>".$row[2]."</td>";
echo "<td align=center>".$row[2]." --- <font color='$wrn'>".$konok."</font></td>";
//echo "<td align=center>".$row[4]."</td>";
echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=validasi&idasesi=".$row[0]."&nama=".$row[1]."\"><span class='glyphicon glyphicon-book'>APL1</a>";
echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=validasiapl2&idasesi=".$row[0]."&nama=".$row[1]."&email=".$row[2]."\"><span class='glyphicon glyphicon-file'>APL2</a>";
echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=observasi&idasesi=".$row[0]."&nama=".$row[1]."&email=".$row[2]."\"><span class='glyphicon glyphicon-eye-open'>Observasi</a>";
echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=rekaphasiltes&idasesi=".$row[0]."&nama=".$row[1]."&email=".$row[2]."\"><span class='glyphicon glyphicon-bookmark'>Hasil Tes</a>";
echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=ubahpassword&idasesi=".$row[0]."\"><span class='glyphicon glyphicon-refresh'>Reset Password</a>";
echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=cekstatus&email=".$row[2]."&idasesi=".$row[0]."\"><span class='glyphicon glyphicon-share'>Cek Status</font></a>";

echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=delete&idasesi=".$row[0]."\"><span class='glyphicon glyphicon-trash'>Hapus</font></a>";

//echo "</table>";

$number++;
}

echo"<tr><td colspan=11><center>";
$query   = "SELECT COUNT(*) AS jumData FROM lsp_usertbl where level='peserta'";
$hasil  = mysql_query($query);
$data     = mysql_fetch_array($hasil);
$jumData = $data['jumData'];
$jumPage = ceil($jumData/$dataPerPage);
if ($noPage > 1) echo  "<a href='".$_SERVER['PHP_SELF']."?page=".($noPage-1)."'>&lt&lt; Prev</a>";
for($page = 1; $page <= $jumPage; $page++)
{
         if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
         {   
            if (($showPage == 1) && ($page != 2))  echo "..."; 
            if (($showPage != ($jumPage - 1)) && ($page == $jumPage))  echo "...";
            if ($page == $noPage) echo " <span class=active_tnt_link><b> ".$page." </b></span> ";
            else echo "<a href='".$_SERVER['PHP_SELF']."?page=".$page."'> ".$page."</a> ";
            $showPage = $page;          
         }
}
 
// menampilkan link next

if ($noPage < $jumPage) echo "<a href='".$_SERVER['PHP_SELF']."?page=".($noPage+1)."'>Next &gt&gt;</a>";
echo "</center></td></tr></table>";
}
?>
</form>




<!--end databaru-->
			<div>
		</div>
		
                </div>
         </div> 
			
</body>

</html>

