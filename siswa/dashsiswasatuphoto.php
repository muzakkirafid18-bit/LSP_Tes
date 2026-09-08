<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LSP - Dashboard</title>

<link href="../lummenu/css/bootstrap.min.css" rel="stylesheet">
<link href="../lummenu/css/datepicker3.css" rel="stylesheet">
<link href="../lummenu/css/styles.css" rel="stylesheet">
<link href="../lummenu/css/adminstyle.css" rel="stylesheet">
<link href='../css/tombol.css' rel='stylesheet' type='text/css'>

<script src="../lummenu/js/lumino.glyphs.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>

<script src="../lummenu/js/jquery-2.2.3.min.js"></script>
<script src="../lummenu/js/bootstrap.js"></script>
<script src="../lummenu/js/bootstrap-datepicker.js"></script>
<script type="text/javascript">
            $(document).ready(function () {
                $('#tanggal').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose:true
                });
            });
        </script>



<?php
session_set_cookie_params(3600*2,"/");
session_start();
include "../lsp_koneksi.php";
if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
 echo "<center><link href='css/adminstyle.css' rel='stylesheet' type='text/css'><strong> Anda Harus Login Dahulu ...!</strong><br>";
 echo "<a href=../lsp_login.php class='btn btn-primary btn-sm' role='button'> Kembali</a></center>"; 
}
else{
?>
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
  /*width: 200px;*/
  font-size: 13px;
  padding: 7px;
  border: 1px solid #c3c3c3;
  border-radius: 7px;
  
}

form div.dynamiclabel textarea{
	height: 150px;
}

form div.dynamiclabel select{
  
  font-size: 14px;
  padding: 7px;
  border: 1px solid #c3c3c3;
  border-radius: 7px;
  /*text-transform: uppercase*/
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
<script type="text/javascript">
            $(document).ready(function () {
                $('#tanggal').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose:true
                });
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
			nama: {
				validators: {
					notEmpty: {
						message: 'Username Tidak Boleh Kosong'
					}
				}
			},
			
			tmplahir: {
				validators: {
					notEmpty: {
						message: 'Nama Tidak Boleh Kosong'
					}
				}
			},

			tahun: {
				validators: {
					notEmpty: {
						message: 'Tahun Tidak Boleh Kosong'
					}
				}
			},
			alamat: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					},
								
				}
			},
                        kodepos: {
				validators: {
					notEmpty: {
						message: 'Tidak boleh kosong'
					},
				}
			},
			pendidikan: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					},
				}
			},
			kebangsaan: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					},
				}
			},
			lembaga: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					},
				}
			},
			jurusan: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					},
				}
			}
		}
	})
});
</script>

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
			<li><a href="pilihskema.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg>Pilih SKema</a></li>
<li><a href="pilihunit.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg>Pilih Unit</a></li>

			<li class="active"><a href="dashsiswa.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg>APL 1</a></li>
<li class="parent ">
<li><a href="apl2.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg>APL 2</a></li>
<li><a href="testulis.php"><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg>FR-MPA.03 Tes Tulis</a></li>
<li class="parent ">
 
				
			<li role="presentation" class="divider"></li>		
			<li><a href="../logout.php"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Logout</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>Pengeloaan APL 1 </h4>
                        
</div><!--/.row-->
		
		
				
		<!--/.row-->
				
		
		<div class="row">
			<div class="col-lg-12">
<!--data nu baru -->

<?php
$op = $_GET['op'];
 
if ($op == "edit")
{
   // proses untuk menampilkan data yang akan diedit pada form
   $email= $_GET['email'];
   $cek="select * from skemasiswa where emailsiswa='$email' and statustesp='N'";
   $ada=mysql_query($cek);
   $ac=mysql_fetch_array($ada);
   $idskemac=$ac['idskema'];
   if(mysql_num_rows($ada)>0){ 
     $cek2="select * from unitsiswa where email='$email' and idskema='$idskemac'";
     echo $cek2;
     $ada2=mysql_query($cek2);
     if(mysql_num_rows($ada2)>0){ 
        $cekdata = "SELECT * FROM apl1 WHERE email = '$email'";
        $ada=mysql_query($cekdata);
   	if(mysql_num_rows($ada)>0){
    	$query = "SELECT * FROM apl1 WHERE email = '$email'";
        $hasil = mysql_query($query);
   	$data  = mysql_fetch_array($hasil);
   	//$iduser=$data['id_user'];
	$nama=$data['namasiswa'];
	//	$pass=$data['password'];
	//$telp=$data['notelp'];
	$emailuser=$data['email'];
	$tmplahir=$data['tmplahir'];
	$tgllahir=$data['tgllahir'];
	$jeniskelamin=$data['jeniskelamin'];
        $kebangsaan=$data['kebangsaan'];
        $alamat=$data['alamat'];
        $kodepos=$data['kodepos'];
        $rumah=$data['tlprumah'];
		$hp=$data['hp'];
        $tlpkantor=$data['tlpkantor'];
        $emailuser=$email;
        $pendidikan=$data['pendidikan'];
        $lembaga=$data['namalembaga'];
        $jurusan=$data['jurusan'];
        
        $tgl = date("d-m-Y", strtotime($tgllahir));
        //echo $tgllahir."lklas".$tgl;
     	}

        else {
        $query = "SELECT * FROM lsp_usertbl WHERE email = '$email'";
        $hasil = mysql_query($query);
   		$data  = mysql_fetch_array($hasil);
   		//$iduser=$data['id_user'];
	   	$nama=$data['nama'];
	   //	$pass=$data['password'];
	   	//$telp=$data['notelp'];
	   $emailuser=$data['email'];
  		}
   		// echo $iduser;
 		//echo "<form name='myform' method='post' action='".$_SERVER['PHP_SELF']."??op=edit'>";
 
		echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=update\">";
		?>
		<table width="95%">
		<tr><td colspan="2" border="0" bgcolor='#006699'><strong>A. Biodata Peserta</strong></td><tr><td>
		<table border="0" width="90%">
		<thead>
								   
		<tr><td colspan="2">
		   <div class="form-group"><strong>Nama Lengkap :</strong>
			<div class="dynamiclabel">
			<input type="text" name="nama" id="nama" placeholder="Nama Lengkap"  value="<?php echo $nama; ?>" size="50" autofocus required disabled='disabled'/>
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
						   </td></tr>
						</table>
						</thead> 	
        				</table>					    
					   <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
					   <input type="hidden" name="emaillama" value="<?php echo $emailuser; ?>">
					   <input type="submit" id="gobutton" value="Simpan"> 
					   <!--<a href="setup_registrasi.php" class="button">Register</a>-->
				
					   </form>
						</div>
						<?php //} else {
						//echo "Data belum ada silahkan klik lengkapi...";} ?>
						<br/>

						<?php
	 } else { echo "<font color=red>Anda belum memilih unit minimal 1 unit untuk skema ini..</font>";}
         } else { echo "<font color=red>Anda belum memilih skema..</font>";}		
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
			//echo $nama;
	   		//echo $emailuser;
	   		//echo $tmplahir;
		    $my_date = date('Y-m-d', strtotime($tgllahir));
			//echo $my_date;
        	//echo $jeniskelamin;
		    //echo $kebangsaan;
		    //echo $alamat;
		    //echo $kodepos;
		    //echo $tlprumah;
		//echo $hp;
		    //echo $tlpkantor;
		    //echo $pendidikan;
		    //echo $lembaga;
		    //echo $jurusan;
		    //echo $emaillama;
		    $cekdata1 = "SELECT * FROM apl1 WHERE email = '$uname'";
   			$ada1=mysql_query($cekdata1);
   				if(mysql_num_rows($ada1)>0){      
					$query = "UPDATE apl1 SET 				namasiswa='$nama',tmplahir='$tmplahir',tgllahir='$my_date',jeniskelamin='$jeniskelamin',kebangsaan='$kebangsaan', alamat='$alamat', kodepos='$kodepos', tlprumah='$tlprumah', hp='$hp', tlpkantor='$tlpkantor', email='$email', pendidikan='$pendidikan', namalembaga='$lembaga',jurusan='$jurusan' WHERE email = '$email'";		} else 
        		{
       			$query = "INSERT INTO apl1 VALUE ('','$nama','$tmplahir','$my_date','$jeniskelamin','$kebangsaan','$alamat','$kodepos','$tlprumah', '$hp','$tlpkantor','$email','$pendidikan','$lembaga','$jurusan')";
 		        }
       
        //echo $query;
 		       $hasil = mysql_query($query);
 			       if ($hasil) echo "Proses Update Sukses";
 			       else echo "<p>Proses Update Gagal</p>";
        
     }

	else if ($op == "pengajuanserti")
     {

      $cektgl="select * from settanggal where status='A'";
        $ada=mysql_query($cektgl);
   	if(mysql_num_rows($ada)>0){

		$emailuser=trim($uname);
		$sql="select * from lsp_usertbl where email='$emailuser'";
		$shasil=mysql_query($sql);
		$sdata=mysql_fetch_array($shasil);
		$namap=$sdata['nama'];
		$idp=$sdata['id'];
		$cek="select * from skemasiswa where emailsiswa='$emailuser' and statustesp='N'";
		//echo $cek;
		 //$ada=mysql_query($cek);
	
		$ada=mysql_query($cek);
			if(mysql_num_rows($ada)>0){
			$data  = mysql_fetch_array($ada);
			$skema=$data['idskema'];
			$ske="select * from skema where idskema='$skema'";
  
  			$ske1=mysql_query($ske);
  			$ske2=mysql_fetch_array($ske1);
  			$namaskema=$ske2['namaskema'];
                        

			$sqlunit="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$skema' and unitsiswa.idadsesi='$idp'";
			$execunit=mysql_query($sqlunit);
                          

			echo "<table width=95%><tr><td colspan=2> <strong>Nama Skema : $namaskema </strong></td></tr><tr><th>Kode Unit (terpilih)</td><th>Nama Unit </th></tr>";
			while ($listunit = mysql_fetch_array($execunit))
   				{
 	  				echo "<tr><td>".$listunit['kodeunit']."</td><td>".$listunit['namaunit']."</td></tr>";
   				}
 				echo "</table>";


			echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=simpanpermohonan\">";
			echo "<input type=hidden name=email value='$emailuser'>";
		echo "<input type=hidden name=namap value='$namap'>";
		echo "<input type=hidden name=idp value='$idp'>";
		echo"
		<div class=form-group><strong></strong>
   		<div class=dynamiclabel>
 		<input type=hidden name=skema value='$skema' readonly>
  		</div>
  		</div>";
?>
	<table>
	<!--<tr><td colspan="3"><strong>Skema : <?php echo $namaskema; ?></strong></td></tr>-->
	<tr><td bgcolor='#006699' align='left'><strong> Bagian 2. Data Permohonan Sertifikasi</strong> </td></tr><tr><td>
	<div class="form-group"><strong>Tujuan asesmen:</strong>
	<div class="dynamiclabel">
   	<input type="checkbox" name="tasesmena" value="rp"> RPL
   	<input type="checkbox" name="tasesmenb" value="pp"> Pecapaian Proses Pembelajaran
   	<input type="checkbox" name="tasesmenc" value="rc"> RCC
   	<input type="checkbox" name="tasesmend" value="sr"> Sertifikasi
   	<input type="checkbox" name="tasesmene" value="la">Lainnya...
   	<input type="text" name="lain" >    
	</div>
	</div>
	<div class="form-group"><strong>Skema Sertifikasi:</strong>
   	<div class="dynamiclabel">
   	<input type="checkbox" name="skemasa" value="un"> Unit
   	<input type="checkbox" name="skemasb" value="kl" > Klaster
   	<input type="checkbox" name="skemasc" value="ok"> Okupasi
   	<input type="checkbox" name="skemasd" value="kk"> KKNI
	</div>
   	</div>
	<div class="form-group"><strong>Konte asesmen:</strong>
   	<div class="dynamiclabel">
   	<input type="checkbox" name="kasesmena" value="si"> TUK Simulasi
   	<input type="checkbox" name="kasesmenb" value="tk" > Tempat Kerja
   ,dengan karakter 
   	<input type="checkbox" name="karaktera" value="pr"> Produk
   	<input type="checkbox" name="karakterb" value="si"> Sistem
   	<input type="checkbox" name="karakterc" value="tkk" > Tempat Kerja
	</div>
   	</div>
	<div class="form-group"><strong>Acuan Pembanding:</strong>
   	<div class="dynamiclabel">
   		<input type="checkbox" name="acuana" value="sk"> Standar Kompetensi
   		<input type="checkbox" name="acuanb" value="sp"> Standar Produk
   		<input type="checkbox" name="acuanc" value="ss"> Standar sistem
   		<input type="checkbox" name="acuand" value="re"> Regulasi sistem
   		<input type="checkbox" name="acuane" value="so"> SOP
	</div>
   	</div>
	<div class="form-group"><strong>TUK :</strong>
	<select class="form-control" id="idtuk" name="idtuk" placeholder="TUK" autofocus>
        
	<?php
      	$tampil=mysql_query("SELECT * FROM tuk");
		while($r=mysql_fetch_array($tampil)){
			echo "<option value=$r[idtuk] selected>$r[namatuk]</option> ";
		}
         
	?>
	 </select>
         </div>
         <div class="form-group"><strong>Set Tanggal :</strong>
        <select class="form-control" name="tanggal" placeholder="Skema">
          <?php
          $tampil=mysql_query("SELECT * FROM settanggal where status='A' group by tanggal ");
		while($r=mysql_fetch_array($tampil)){
		echo "<option value=$r[tanggal]>$r[tanggal]</option>";
		}
		echo "</select";

           ?>
         
	 </div>
	 </tr><tr><td colspan="3"><input type="submit" id="gobutton" value="Simpan" class="button"></td></tr></td></tr></table>

	<?php
	} else { echo "<font color=red>Anda belum memilih skema..</font>";}
      }else {
	echo "<font color=red>Tanggal belum di set..  Hubungi Asesor / Bagian LSP  </font>";}
}

else if ($op == "simpanpermohonan")
     {
		$email=trim($_POST['email']);
		$ids=$_POST['skema'];
		$idp=$_POST['idp'];
		$namap=$_POST['namap'];

		//$tasesmen = implode(',', $_POST['tasesmen']);
		$lain=$_POST['lain'];
		//$skemas=implode(',', $_POST['skemas']);
		//$kasesmen=implode(',', $_POST['kasesmen']);
		//$karakter=implode(',', $_POST['karakter']);
		//$acuan=implode(',', $_POST['acuan']);
		$tasesmen = $_POST['tasesmena'].",".$_POST['tasesmenb'].",".$_POST['tasesmenc'].",".$_POST['tasesmend'].",".$_POST['tasesmene'];
		$skemas=$_POST['skemasa'].",".$_POST['skemasb'].",".$_POST['skemasc'].",".$_POST['skemasd'];		
		$kasesmen=$_POST['kasesmena'].",".$_POST['kasesmenb'];
	        $karakter=$_POST['karaktera'].",".$_POST['karakterb'].",".$_POST['karakterc'];
                $acuan=$_POST['acuana'].",".$_POST['acuanb'].",".$_POST['acuanc'].",".$_POST['acuand'].",".$_POST['acuane'];
		$tuk=$_POST['idtuk'];
                $tanggal=$_POST['tanggal'];
                //echo "skema".$acuan;
		$cek="select * from permohonan where email='$email' and idskema='$ids' and statusvalid='N' and tanggal='$tanggal'";
     	//echo $cek;
     	$ada=mysql_query($cek);
		if(mysql_num_rows($ada)>0){
    		echo "<font color=red>Terjadi Duplikat....</font>";}
    	else {

		$query = "INSERT INTO permohonan  (idpermohonan,idskema,email,tujuanasesmen,lainnya,sertifikasi,kontekasesmen,karakteristik,acuanp,tuk,tanggal) VALUE('','$ids','$email','$tasesmen','$lain','$skemas','$kasesmen','$karakter','$acuan','$tuk','$tanggal')";


      //echo $query;
      $berhasil = mysql_query($query);
      if ($berhasil) echo "Proses Sukses";
      else echo "Proses Gagal";
	}
	}

else if ($op == "kompetensi")
     {
		$emailuser=trim($uname);
		$cek="select * from skemasiswa where emailsiswa='$emailuser' and statustesp='N'";
		//echo $cek;
 		//$ada=mysql_query($cek);
	
		$ada=mysql_query($cek);
		if(mysql_num_rows($ada)>0){
			$data  = mysql_fetch_array($ada);
    		//$b=mysql_fetch_rows($ada);
			$skema=$data['idskema'];
  			$ske="select * from skema where idskema='$skema'";
  			$ske1=mysql_query($ske);
  			$ske2=mysql_fetch_array($ske1);
  			$namaskema=$ske2['namaskema'];
			$cekid="select * from lsp_usertbl where email='$emailuser'";
    		//echo $cekid;
    		$cekid1=$ske1=mysql_query($cekid);
			$cekid2=mysql_fetch_array($cekid1);
    		$idasesi=$cekid2['idasesi'];
                        $sqlunit="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$skema' and unitsiswa.idadsesi='$idasesi'";
			//$sqlunit="select * from unit where idskema='$skema'";
			$execunit=mysql_query($sqlunit);

			echo "<table width=95%><tr><td colspan=2> <strong>Nama Skema : $namaskema </strong></td></tr><tr><th>Kode Unit (Terpilih)</td><th>Nama Unit </th></tr>";
			while ($listunit = mysql_fetch_array($execunit))
   			{
   				echo "<tr><td>".$listunit['kodeunit']."</td><td>".$listunit['namaunit']."</td></tr>";
   			}
 			echo "</table>";

  
			//echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
 			//       "?op=simpankompetensi\">";
			echo "<form name=contoh method=post action=uploadgambar1.php enctype=multipart/form-data id=form-upload>";
			echo "<input type=hidden name=email value='$emailuser'>";
			echo "<input type=hidden name=idasesi value='$idasesi'>";


			echo"
			<div class=form-group><strong></strong>
			   <div class=dynamiclabel>
 				<input type=hidden name=skema value='$skema' readonly>
  			   </div>
  			</div>";
?>
	<table>
	<!--<tr><td colspan="3"><strong>Skema : <?php echo $namaskema; ?></strong></td></tr>-->
	<tr><td bgcolor='#006699' align='left' colspan='3'><strong> Bagian 3. Bukti Pendukung</strong> </td></tr>
	<tr><td>Elemen Kompetensi</td><td>Bukti Relevan</td><td>Upload</td></tr>

	<?php 
         $sqlunitxx="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$skema' and unitsiswa.idadsesi='$idasesi'";
	$execunitxx=mysql_query($sqlunitxx);
        $i = 0;
         while ($dataxx=mysql_fetch_array($execunitxx)){
            $idunitxx=$dataxx['idunit'];
		$unit="Select * from elemen where idskema='$skema' and idunit='$idunitxx'";
		$unit1=mysql_query($unit);
		
			while ($unit2=mysql_fetch_array($unit1)){
			$dunit=$unit2['idunit'];
			//$nunit=$unit2['namaunit'];
			$delemen=$unit2['idelemen'];
			$nelemen=$unit2['namaelemen'];
			echo "<tr><td><input type=checkbox name=unit".$i." value='$delemen' checked> ".$nelemen." </td><td><input type=hidden name=eunit".$i." value='$dunit'><input type=checkbox name=buktia".$i." value='sk'>SK <input type=checkbox name=buktib".$i." value='sr'>SR <input type=checkbox name=buktic".$i." value='cp'>CP<input type=checkbox name=buktid".$i." value='jd'>JD <input type=checkbox name=buktie".$i." value='ws'>WS <input type=checkbox name=buktif".$i." value='de'>De <input type=checkbox name=buktig".$i." value='pe'>Pe <input type=checkbox name=buktih".$i." value='l'>L  ";



			?>
			<?php
			$i++;
			?>
			</td><td><!--<td><input type="file" accept="image/*" name="foto[]"/>-->
			<?php
			}
		}
		echo "<input type='hidden' name='n' value='".$i."' />";
		?>
                </td></tr><tr><td colspan="3">
		<div class="form-group"><strong>Set Tanggal :</strong>
        	<select class="form-control" name="tanggal" placeholder="Skema">
          	<?php
          	$tampil=mysql_query("SELECT * FROM settanggal where status='A' group by tanggal ");
		while($r=mysql_fetch_array($tampil)){
			echo "<option value=$r[tanggal]>$r[tanggal]</option>";
		}
		echo "</select></tr>"; 
		?>
		<tr><td colspan="3"><input type="file" accept="image/*" name="foto"/></td></tr>
		
		</td></tr><tr><td colspan="3"><input type="submit" id="gobutton" value="Simpan" class="button"></td></tr></table>
		</form>
		SK = Sertifikasi atau Kualifikasi(contoh : pelatihan, keahlian)</br>
		SR = Surat referensi dari supervision/perusahaan mengenai pekerjaan anda</br>
		CP = Contoh Pekerjaan yang pernah anda buat (produk jadi)</br>
		JD = Job description dari perusahaan mengenai pekerjaan anda</br>
		WS = Wawancara dengan supervisor , teman sebaya atau klien</br>
		De = Demonstrasi pekerjaan/keterampilan yang dipersyaratkan</br>
		Pe = Pengalaman Idustri (On teh job training, magang,kerja praktek,dll)</br>
		 L  = Bukti-bukti lainnya yang relevan  
		<?php
               
	}else{ echo "<font color=red> Anda belum memilih skema</font>";}

	}


	?>


<body>
<br/>
 
<?php
 
// bagian ini digunakan untuk menampilkan semua data
 $cekdata = "SELECT * FROM apl1 WHERE email = '$uname'";
   $ada=mysql_query($cekdata);
   	if(mysql_num_rows($ada)>0){
        $pesan="Edit Biodata";
        
        }else {
        $pesan="Lengkapi Biodata "; }
	echo "<table>";
	$no = 1;
	$query ="SELECT * FROM lsp_usertbl where email='$uname' ";
	$hasil = mysql_query($query);
	while ($data = mysql_fetch_array($hasil))
		{
   		echo "<tr><td bgcolor='#99CCFF'>Nama Registrasi:</td><td><strong>".$data['nama'] ."</strong></td></tr><tr><td bgcolor='#99CCFF'>";
 		  echo "Email Siswa: </td><td ><strong>".$data['email']."</strong></td><tr></table>";
   
 		echo "<a href=\"".$_SERVER['PHP_SELF'].
        "?op=edit&email=".$data['email']."\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'><strong>$pesan</strong> </a> &nbsp &nbsp";
    	if(mysql_num_rows($ada)>0){
    		echo "<a href=\"".$_SERVER['PHP_SELF'].
        	"?op=pengajuanserti&email=".$data['email']."\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'><strong>Pengajuan sertifikasi</strong> </a> ";
			echo " <a href=\"".$_SERVER['PHP_SELF'].
        "?op=kompetensi&email=".$data['email']."\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'><strong>Kompetensi dan Bukti Pendukung</strong> </a>";}
   //echo "</tr>";
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

<?php } ?>
