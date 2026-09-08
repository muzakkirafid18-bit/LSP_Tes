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
  width: 800px;
  font-size: 14px;
  padding: 7px;
  border: 1px solid #c3c3c3;
  border-radius: 7px;
  
}

form div.dynamiclabel textarea{
	height: 100px;
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
				<a class="navbar-brand" href="#"><span><?php echo $namax ; ?></a>
				
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
<li  class="active"><a href="inputfeed.php"><svg class="glyph stroked usb flash drive"><use xlink:href="#stroked-usb-flash-drive"/></svg> Feedback</a></li>
<li><a href="monitorasesi.php"><svg class="glyph stroked desktop computer and mobile"><use xlink:href="#stroked-desktop-computer-and-mobile"/></svg> Monitoring</a></li>
<li><a href="backupdata.php"><svg class="glyph stroked download"><use xlink:href="#stroked-download"/></svg> Backup data</a></li>



			<li role="presentation" class="divider"></li>
	
			<li><a href="../logout.php"><svg class="glyph stroked lock"><use xlink:href="#stroked-lock"/></svg> Logout</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>FEEDBACK </h4>
                        
</div><!--/.row-->
		
		
				
		<!--/.row-->
				
		
		<div class="row">
			<div class="col-lg-12">
<!--data nu baru -->
<?php
$op = $_GET['op'];
 
if ($op == "edittulis")
{
	$edit=mysql_query("SELECT * FROM pertanyaan WHERE question_id=".$_GET['idq']);
	
	$row=mysql_fetch_array($edit);
       
	//<tr><td>RePassword</td><td>:		<input type=text name=rpassword></td></tr>
	echo "
        <table width=100%>"; ?>
<?php echo "
    
	<form method=POST action=updatesoaltulis.php>
	<input type=hidden name=kodelama value='$row[question_id]'>
        <input type=hidden name=kodesoal value='$row[kd_modul]'>";
	

        $kdm=$row["kd_modul"];
        echo"
	<tr><th colspan=3>Pertanyaan </th></tr>

<tr><td colspan=3><textarea name=pertanyaan id=pertanyaan rows=4 cols=120>$row[question]</textarea></td></tr>
";

  	$id = $row["question_id"]; 
	$kd_modul=$row["kd_modul"];
	$question = $row["question"]; 
	$oa=$row["oa"];
	$ob=$row["ob"];
	$oc=$row["oc"];
	$od=$row["od"];
	$oe=$row["oe"];	
	$alt_5=$row["answer"];
	$alt_1=$row["alt_1"];
	$alt_2=$row["alt_2"];
	$alt_3=$row["alt_3"];
	$alt_4=$row["alt_4"];
        $alt_6=$row["njawab"];
       
echo "<tr><th> Jawaban</th></tr>
<tr><td colspan=3><textarea name=jawaban rows=4 cols=120>$row[answer]</textarea></td></tr>
";
echo "<tr><th> Option1</th></tr>
<tr><td colspan=3><textarea name=alt_1 rows=4 cols=120>$row[alt_1]</textarea></td></tr>
";
echo "<tr><th> Option2</th></tr>
<tr><td colspan=3><textarea name=alt_2 rows=4 cols=120>$row[alt_2]</textarea></td></tr>
";
echo "<tr><th> Option3</th></tr>
<tr><td colspan=3><textarea name=alt_3 rows=4 cols=120>$row[alt_3]</textarea></td></tr>
";
echo "<tr><th> Option4</th></tr>
<tr><td colspan=3><textarea name=alt_4 rows=4 cols=120>$row[alt_4]</textarea></td></tr>
";
echo"
                <tr><th colspan=2>
                <input  id=gobutton name=dsubmit type=submit value=UBAH>
		<input  id=gobutton nama=dbantal type=button value=BATAL onclick=self.history.back()></th></tr>
	</table>
	</form></div>";


	}
	
else if ($op == "updateprk")
     {
 
       // proses untuk updating data setelah diedit
 
        $idprk = $_POST['idprk'];
        $instruksi=$_POST['instruksi'];
        $observasi= $_POST['observasi'];
        //$skema=$_POST['skema'];
        //$ket=$_POST['ket'];
        //if($kodeunit == $kodeunitLama){
	  
			$query = "UPDATE praktek SET instruksi='$instruksi',obervasi='$observasi' WHERE idpraktek = '$idprk'"; 
        //echo $query;
        	$hasil = mysql_query($query);
   
        		if ($hasil) { echo"<script>alert('Sukses ..!');history.go(-2);</script>";}
   else{echo"<script>alert('Gagal ..!');history.go(-1);</script>";}
   }
else if ($op == "deletetulis"){
   		$idprk= $_GET['idtulis'];
   		$query = "SELECT * FROM pertanyaan WHERE question_id = '$idprk'";
   		$hasil = mysql_query($query);
   		$data  = mysql_fetch_array($hasil);
	    $observasi=$data['obervasi'];
	    //$namaunit=$data['namaunit'];
	 //echo "<form name='myform' method='post' action='".$_SERVER['PHP_SELF']."??op=edit'>";
	    echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF'].
		    "?op=deleteposttulis\">";
		     echo "<center><input type=text name=idtulis value=".$idprk."></br>Yakin ID ini Akan di hapus ???</center>";
          ?>
	 	<div class="formcolumn">
          
   	 	</div>
     	<div class="buttons">
        	<?php
       		echo "<input type=submit id=gobutton value=Delete>
        </div> 
        </div>";
          
	echo "</form>";
	echo "<br/><br/><br/>"; 
	} 

else if ($op == "deleteposttulis"){
		$sql="DELETE from pertanyaan WHERE question_id=".$_POST['idtulis'];
		$ok=mysql_query($sql);
		if ($ok) { echo"<script>alert('Sukses ..!');history.go(-2);</script>";}
   else{echo"<script>alert('Gagal ..!');history.go(-1);</script>";}
	 }

else if($op=="listtulis"){
$kodeunit=$_GET['kdunit']; 
?>

<table ><tr><td colspan=6>EDITING ....</td></tr>
    <th bgcolor='#006699'>No</th>
    <th bgcolor='#006699'>Id </th>	
    <th bgcolor='#006699'>Kode Unit</th>
    <th bgcolor='#006699'>Intruksi</th>
    
    <th bgcolor='#006699'>Aksi</th>
	</tr>
 
	<?php
 
// bagian ini digunakan untuk menampilkan semua data
 
	$no = 1;
	$querypr ="SELECT * FROM pertanyaan where kd_modul='$kodeunit'";
	$hasilpr = mysql_query($querypr);
	while ($datapr = mysql_fetch_array($hasilpr))
		{
		$sta=$data['status'];
			if($sta=='Y'){
   				$st="Aktif";
   			}else{ 
				$st="Belum Aktif";}
		   echo "<tr>";
		   echo "<td>".$no."</td>";
		   echo "<td>".$datapr['question_id']."</td>";
		   echo "<td align=left>".$datapr['kd_modul']."</td>";
		   echo "<td align=left>".$datapr['question']."</td>";
		   
		  echo "<td><a href=\"".$_SERVER['PHP_SELF'].
					"?op=editfeed&idq=".$datapr['question_id']."\"><img src=../images/edit.png>Edit</a>|<a href=\"".$_SERVER['PHP_SELF'].
        "?op=deletetulis&idtulis=".$datapr['question_id']."\"><img src=../images/delete.png>Hapus</a></td>";
		   echo "</tr>";
			   $no++;
		}
	   echo "</div>";
	
}

else if($op=="importfeed"){


echo"<table><tr>";

echo "<form enctype=multipart/form-data action=uploadfeed.php method=POST>
</br><strong>Masukan File FEEDBACK Yang Mau di UPLOAD dengan format (XLS): </strong><input name=uploadedfile type=file /></br>
<input type=submit id=gobutton value=Upload File />
</form></div>";

}

else if($op=="batalfeed"){
?>
<form id="formContoh" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=hapusfeed\"";?>> 
<div class="form-group"><strong>Kode Unit :</strong>
<select id="kodeunit" name="kodeunit" placeholder="Kode Unit" autofocus>
<?php
  $tampilunit="select kd_modul from pertanyaanfeedback group by kd_modul";
      $execunit=mysql_query($tampilunit);
      //echo $tampiltgl;
		while($runit=mysql_fetch_array($execunit))
		{
                //         echo "ll";
   				echo "<option value=$runit[kd_modul] selected>$runit[kd_modul] </option> ";
					}
?>
	 </select>
</div>
<input type="submit" id="gobutton" value="Lanjutkan" class="button"> 
<?php

}

else if($op=="hapusfeed"){
//$kodeunit=$_POST['kodeunit'];
$kdmodul = $_POST['kodeunit'];
$cekdata="select kd_modul from pertanyaanfeedback where kd_modul='$kdmodul'";
$ketemu=mysql_query($cekdata);
if(mysql_num_rows($ketemu)>0){
mysql_query("delete from pertanyaanfeedback where kd_modul='$kdmodul'");
mysql_query("delete from tbloptionfeed where kd_modul='$kdmodul'");
 echo"<script>alert('Sukses ..!');history.go(-2);</script>";
}else {
 echo"<script>alert('Gagal ..!');history.go(-2);</script>";}


}



else if($op=="uploadgbrtulis"){
?>
<form name="contoh" method="post" action="uploadgambart.php" enctype="multipart/form-data" id="form-upload">

		<input type="file" accept="image/*" name="foto[]" multiple />
		<input type="submit" id="gobutton" value="Upload">
</form>
<?php
}
else if($op=="uploadfeed"){
echo "<form enctype=multipart/form-data action=postuploadfeed.php method=POST>
</br><strong>Masukan file kode soal yang akan diupload (xls):</strong></br> <input name=uploadedfile type=file /><br />
<input type=submit value=Upload File />
</form>";
}

else if ($op == "editmodulfeed")
{
 
   // proses untuk menampilkan data yang akan diedit pada form
 
   $kd_modul = $_GET['id'];
 
   $query = "SELECT * FROM modulfeed WHERE id = '$kd_modul'";
   $hasil = mysql_query($query);
   $data  = mysql_fetch_array($hasil);
 //echo "<form name='myform' method='post' action='".$_SERVER['PHP_SELF']."??op=edit'>";
   echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=updatefeed\">";
   echo "<table border=\"0\">";
   
   echo "<tr>
           <th colspan=2 bgcolor='#99CCFF'>Editing ...
           <input type=\"hidden\" name=\"kd_modul\"
               value=\"".$data['id']."\"></th>
         </tr>";
 
   echo "<tr>
         <th bgcolor='#99CCFF'>Modul</th>
         <th><input type=\"text\" name=\"modul\"
              value=\"".$data['modul']."\"></th></tr>";
	echo "<tr>
         <th bgcolor='#99CCFF'>Jumlah Soal</th>
         <th><input type=\"text\" name=\"jumlah_soal\"
              value=\"".$data['jumlah_soal']."\"></th></tr>";
   echo "<tr>
         <th bgcolor='#99CCFF'>Status Soal</th>
         <th><input type=\"text\" name=\"status_soal\"
              value=\"".$data['status_soal']."\"></th></tr>";
	echo "<tr>
         <th bgcolor='#99CCFF'>Waktu (Menit)</th>
         <th><input type=\"text\" name=\"Waktu\"
              value=\"".$data['Waktu']."\"></th></tr>";
	 echo "<tr>
         <th bgcolor='#99CCFF'>Batas (kali)</th>
         <th><input type=\"text\" name=\"batas\"
              value=\"".$data['batas']."\"></th></tr>";
     //echo "<tr>
       //  <th bgcolor='#99CCFF'>KKM</th>
         //<th><input type=\"text\" name=\"kkm\"
           //   value=\"".$data['kkm']."\"></th></tr>";
   echo "<tr>
         <th bgcolor='#99CCFF'>Tanggal</th>
         <th><input type=\"text\" name=\"tanggal\"
              value=\"".$data['tanggal']."\"></th></tr>";
   echo "<tr>
         <th bgcolor='#99CCFF'>Mulai</th>
         <th><input type=\"text\" name=\"btsawal\"
              value=\"".$data['btsawal']."\"></th></tr>";
   echo "<tr>
         <th bgcolor='#99CCFF'>Selesai</th>
         <th><input type=\"text\" name=\"btsakhir\"
              value=\"".$data['btsakhir']."\"></th></tr>";
   echo "</table>";
   //echo "<input type=\"hidden\" name=\"kd_modulLama\"
      //   value=\"".$data['kd_modul']."\">";
   //echo "<input type=\"submit\" name=\"submit\"
     //    value=\"Simpan Perubahan\">";
    echo "<input id=gobutton type=submit value=Simpan>";
   echo "</form>";
}

else if ($op == "updatefeed")
     {
 
       // proses untuk updating data setelah diedit
 
        $id = $_POST['kd_modul'];
        $modul= $_POST['modul'];
		$jumlah= $_POST['jumlah_soal'];
		$status= $_POST['status_soal'];
		$waktu= $_POST['Waktu'];
		$batas= $_POST['batas'];
		$kkm='75';
                $tanggal=$_POST['tanggal'];
                $btsawal=$_POST['btsawal'];
                $btsakhir=$_POST['btsakhir'];
		
        //$kd_modulLama = $_POST['id'];
 
        $query = "UPDATE modulfeed SET modul = '$modul', jumlah_soal='$jumlah', status_soal='$status', Waktu='$waktu',batas='$batas',kkm='$kkm',tanggal='$tanggal',btsawal='$btsawal',btsakhir='$btsakhir'
                  WHERE id = '$id'"; 
        $hasil = mysql_query($query);
        
        if ($hasil) echo "Proses Update Sukses";
        else echo "<p>Proses Update Gagal</p>";
     }
else if($op=="hasiltes")
{
echo "<strong>";
  echo "<form method=POST action='daftarnilai.php'>
	

        Filter Tanggal :<select name =tgl id=tgl >";
	$listmodul=mysql_query("SELECT tanggal FROM grade group by tanggal desc");
			  while($list=mysql_fetch_array($listmodul)){ 
        echo " <option value=$list[tanggal]> $list[tanggal] </option>";
        } echo"</select></br>
	<input type=submit id=gobutton name=dsubmit value=Lanjutkan>
      </form>";
echo "</strong>";



}


?>


<body>
<hr>
	<?php //echo "<a href=\"".$_SERVER['PHP_SELF'].
        //"?op=tambah&kd_modul=".$data['kd_modul']."\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'>Tambah</a>";
     echo " <a href=\"".$_SERVER['PHP_SELF'].
        "?op=importfeed&email=".$data['email']."\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'><strong>Import Feedback </strong> </a>";
echo " <a href=\"".$_SERVER['PHP_SELF'].
        "?op=batalfeed&email=".$data['email']."\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'><strong>Batal Feedback </strong> </a>";
echo " <a href=\"".$_SERVER['PHP_SELF'].
        "?op=uploadgbrfeed&email=".$data['email']."\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'><strong>Upload Gambar feedback </strong> </a>";
echo " <a href=\"".$_SERVER['PHP_SELF'].
        "?op=uploadfeed&email=".$data['email']."\"  class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'><strong>Upload Kode feedback </strong> </a>";
//echo " <a href=\"".$_SERVER['PHP_SELF'].
  //      "?op=hasiltes&email=".$data['email']."\"  class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'><strong>Hasil TES </strong> </a>";?>

    <table ><tr>
    <th bgcolor='#006699'>No</th>
    <th bgcolor='#006699'>Id</th>	
    <th bgcolor='#006699'>Kode Feedback</th>
    <th bgcolor='#006699'>Nama Feedback</th>
    
    <th bgcolor='#006699'>Aksi</th>
	</tr>
 
	<?php
 
// bagian ini digunakan untuk menampilkan semua data
 
	$no = 1;
	$queryfeed ="SELECT * from modulfeed";
	$hasilfeed = mysql_query($queryfeed);
	while ($datafeed = mysql_fetch_array($hasilfeed))
		{
		$sta=$datafeed['status'];
			if($sta=='Y'){
   				$st="Aktif";
   			}else{ 
				$st="Belum Aktif";}
		   echo "<tr>";
		   echo "<td bgcolor='#99CCFF'>".$no."</td>";
		   echo "<td bgcolor='#99CCFF'>".$datafeed['id']."</td>";
		   echo "<td bgcolor='#99CCFF' align=left>".$datafeed['kd_modul']."</td>";
		   echo "<td bgcolor='#99CCFF' align=left>".$datafeed['modul']."</td>";
		  
		   echo "<td><a href=\"".$_SERVER['PHP_SELF'].
					"?op=editmodulfeed&id=".$datafeed['id']."\"><img src=../images/edit.png>Edit</a>";
        //"?op=delete&idunit=".$data['idunit']."\"><img src=../images/delete.png>Hapus</a></td>";
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
