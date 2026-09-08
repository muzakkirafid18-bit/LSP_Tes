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
			<li  class="active"><a href="inputasesor.php"><svg class="glyph stroked male user "><use xlink:href="#stroked-male-user"/></svg>Kelola Asesor</a></li>
			<li><a href="inputelemen.php"><svg class="glyph stroked star"><use xlink:href="#stroked-star"/></svg> Kelola Komptetensi</a></li>
			
			<li><a href="inputtempattuk.php"><svg class="glyph stroked home"><use xlink:href="#stroked-home"/></svg> Kelola Tempat TUK</a></li>
<li><a href="settanggal.php"><svg class="glyph stroked clock"><use xlink:href="#stroked-clock"/></svg> SET Tanggal</a></li>
<li><a href="pemetaanasesor.php"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"/></use></svg> Atur jadwal</a></li>
<li><a href="inputpraktek.php"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg> FR MPA.05-Praktek</a></li>
<li><a href="inputtestulis.php"><svg class="glyph stroked clipboard with paper"><use xlink:href="#stroked-clipboard-with-paper"/></svg> FR-MPA 03 - Tes Tulis </a></li>
<li><a href="inputfeed.php"><svg class="glyph stroked usb flash drive"><use xlink:href="#stroked-usb-flash-drive"/></svg> Feedback</a></li>
<li><a href="monitorasesi.php"><svg class="glyph stroked desktop computer and mobile"><use xlink:href="#stroked-desktop-computer-and-mobile"/></svg> Monitoring</a></li>
<li><a href="backupdata.php"><svg class="glyph stroked download"><use xlink:href="#stroked-download"/></svg> Backup data</a></li>



			<li role="presentation" class="divider"></li>
	
			<li><a href="../logout.php"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Logout</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>Kelola Data Asesor </h4>
                        
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
   $iduser= $_GET['iduser']; 
   $queryedit = "SELECT * FROM lsp_usertbl WHERE id = '$iduser'";
   $hasiledit = mysql_query($queryedit);
   $dataedit  = mysql_fetch_array($hasiledit);
   $iduser=$dataedit['id'];
   $nama=$dataedit['nama'];
   //$pass=$data['password'];
   $telp=$dataedit['notelp'];
   $emailuser=$dataedit['email'];
  // echo $iduser;
 //echo "<form name='myform' method='post' action='".$_SERVER['PHP_SELF']."??op=edit'>";
 
echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=update\">";
   echo "<table border=\"0\">"; 
?>
  <div class="form-group"><strong>Email :</strong> 
  <input type="text" name="emailuser" id="emailuser" placeholder="Email Aktif" class="form-control" value="<?php echo $emailuser; ?>" autofocus required/>
</div>
   <div class="form-group"><strong>Nama Lengkap :</strong>
    <input type="text" name="nama" id="nama" placeholder="Nama Lengkap" class="form-control" value="<?php echo $nama; ?>" required/>
  </div>
     <div class="form-group"><strong>No Telp :</strong>
    <input type="text" name="notelp" id="notelp" placeholder="Nomor Telp" class="form-control" value="<?php echo $telp; ?>" required/>
  </div>

  <div class="form-group"><strong>Password :</strong>
  <input type="password" name="password1" id="password1" placeholder="Password (Kosong tdk berubah)" class="form-control" >
  </div>
  </div>
   <hr>
   <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
   <input type="hidden" name="emaillama" value="<?php echo $emailuser; ?>">
   <input type="submit" id="gobutton" value="Simpan"> 
   <!--<a href="setup_registrasi.php" class="button">Register</a>-->
  </form>
</div>
<br/>

<?php

}
else if ($op == "tambah")
{
?>
  
  <form id="formContoh" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=append\">";?>
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
        $emailuser= trim($_POST['emailuser']);
        $nama= $_POST['nama'];
        $notelp=$_POST['notelp'];
        $pass=md5($_POST['password1']);
        $emaillama=trim($_POST['emaillama']);
        if($emailuser == $emaillama){    
        if(empty($pass)){
	$queryupdate = "UPDATE lsp_usertbl SET email='$emailuser',nama='$nama',notelp='$notelp' WHERE id = '$iduser'";
        }else{
	$queryupdate = "UPDATE lsp_usertbl SET email='$emailuser',nama='$nama',notelp='$notelp',password='$pass' WHERE id = '$iduser'";
         }
 
        //echo $query;
        $hasilupdate = mysql_query($queryupdate);
        if ($hasilupdate) echo "Proses Update Sukses";
        else echo "<p>Proses Update Gagal</p>";
        }else{ 
          echo $emailuser;
	  $cekdata="select email from lsp_usertbl where email='".$emailuser."'";
	  $ada=mysql_query($cekdata);
	  if(mysql_num_rows($ada)>0){
           echo "Gagal Duplikat ..";}
          else {
if(empty($pass)){
	$query = "UPDATE unit SET email='$emailuser',nama='$nama',notelp='$notelp' WHERE id = '$iduser'";
        }else{
	$query = "UPDATE lsp_usertbl SET email='$emailuser',nama='$nama',notelp='$notelp',password='$pass' WHERE id = '$iduser'";}
         //echo $query;
        $hasil = mysql_query($query);
        echo "<table><tr><th>";
        if ($hasil) echo "Proses Update Sukses</th></th>";
        else echo "<p>Proses Update Gagal1</p></th></tr></table>";
        
        } 
        }
     }
else if ($op == "delete"){

   $iduser= $_GET['iduser'];
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
    	Yakin Asesor : <strong><?php echo $nama ?></strong>  Akan di hapus ? 
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
?>


<body>
<br/>
<?php echo "<a href=\"".$_SERVER['PHP_SELF'].
        "?op=tambah\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'></span>Tambah</a>";?>
<table >
<tr>
    <th bgcolor='#006699'>No</th>
    	
    <th bgcolor='#006699'>Nama Asesor</th>
    <th bgcolor='#006699'>No Telp</th>
    <th bgcolor='#006699'>Email </th>
    <th bgcolor='#006699'>Aksi</th>
</tr>
 
<?php
 
// bagian ini digunakan untuk menampilkan semua data
 
$no = 1;
$querymain ="SELECT * FROM lsp_usertbl where level='asesor'";
$hasilmain = mysql_query($querymain);
while ($datamain = mysql_fetch_array($hasilmain))
{

   echo "<tr>";
   echo "<td bgcolor='#99CCFF'>".$no."</td>";
   //echo "<td bgcolor='#99CCFF'>".$data['id_user']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$datamain['nama']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$datamain['notelp']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$datamain['email']."</td>";
  	
   echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=edit&iduser=".$datamain['id']."\"><img src=../images/edit.png>Edit</a> | <a href=\"".$_SERVER['PHP_SELF'].
        "?op=delete&iduser=".$datamain['id']."\"><img src=../images/delete.png>Hapus</a></td>";
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

<?php } ?>
