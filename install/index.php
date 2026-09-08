<link href="../css/tabelbaru2.css" rel="stylesheet">
<link href="../lummenu/css/bootstrap.min.css" rel="stylesheet">
<link href="../lummenu/css/datepicker3.css" rel="stylesheet">
<link href="../lummenu/css/styles.css" rel="stylesheet">
<link href="../lummenu/css/adminstyle.css" rel="stylesheet">
<link href='../css/tombol.css' rel='stylesheet' type='text/css'>
<?php 
echo "<center><table width=30%><tr><td colspan=3 align=center>INSTALL DATABASE DAN PASWWORD</td></tr><td>";
echo "<a href=\"".$_SERVER['PHP_SELF'].
        "?op=passdatabase\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'></span>Setting Database</a></td><td>";
		
		echo "<a href=\"".$_SERVER['PHP_SELF'].
        "?op=adminaplikasi\" class='btn btn-primary btn-sm' role='button'> <span class='glyphicon glyphicon-plus'></span>Setting Admin Aplikasi</a></td></tr><tr><td colspan=3> * Setelah selesai setting folder install di hapus / di rename </td></tr></table></center>";
      echo "</br>";
?>
<?php
require_once __DIR__ . '/../mysql_compat.php';
error_reporting(0);
/* echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=updatepasswordroot \">"; ?>
<table class=demo-table width=80%>
  <tr><th colspan=2> Seting Password Root dan Pembuatan Database</th></tr>
   <tr><td>
   <label for="upeng">User Database ?</label></td><td>
    <input id="userdtbs" name="userdtbs" placeholder="User Database " type="text" size="25"  autofocus>
	</td></tr>
  <tr><td>
   <label for="nippeng">Password root lama ?</label></td><td>
    <input id="passrootlama" name="passrootlama" placeholder="Password root lamaaa" type="text" size="25">
	</td></tr>
<tr><td>
   <label for="nmpeng">Password root baru ? (kosongkan jika tidak ada perubahan)</label></td><td>
    <input id="passrootbaru" name="passrootbaru" placeholder="Password root baru" type="text" size="60" >
  </td></tr>
<tr><td>
   <label for="nmpeng">Ulang password root baru ?</label></td><td>
    <input id="upassrootbaru" name="upassrootbaru" placeholder="Ulang password root baru" type="text" size="60" >
  </td></tr>  

<tr><td colspan=2>
<div class="buttons">

		<input id="gobutton" type="submit" value="Simpan " />
		
		</div>
</td></tr>		
</table>
		</form>
*/
?>
<?php
$op = $_GET['op'];
if ($op =="passdatabase")
{
	echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=updatepasswordroot \">"; ?>
<table class=demo-table width=80%>
  <tr><th colspan=2> Seting Password Root dan Pembuatan Database</th></tr>
   <tr><td>
   <label for="upeng">User Database ?</label></td><td>
    <input id="userdtbs" name="userdtbs" placeholder="User Database " type="text" size="25"  autofocus>
	</td></tr>
  <tr><td>
   <label for="nippeng">Password root lama ?</label></td><td>
    <input id="passrootlama" name="passrootlama" placeholder="Password root lama" type="password" size="25"  autofocus>
	</td></tr>
<tr><td>
   <label for="nmpeng">Password root baru ? (kosongkan jika tidak ada perubahan)</label></td><td>
    <input id="passrootbaru" name="passrootbaru" placeholder="Password root baru" type="password" size="60" >
  </td></tr>
<tr><td>
   <label for="nmpeng">Ulang password root baru ?</label></td><td>
    <input id="upassrootbaru" name="upassrootbaru" placeholder="Ulang password root baru" type="password" size="60" >
  </td></tr>  
  
<tr><td>
   <label for="nmpeng">Nama Database ?</label></td><td>
    <input id="ndatabase" name="ndatabase" placeholder="Nama Database" type="text" size="60" >
  </td></tr>    

<tr><td colspan=2>
<div class="buttons">

		<input id="gobutton" type="submit" value="Simpan " />
		
		</div>
</td></tr>		
</table>
		</form>
	
<?php	
}

else if ($op =="adminaplikasi")
{
	echo "<form method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=updatepasswordadmin \">"; ?>
<table class=demo-table width=80%>
  <tr><th colspan=2> Seting user dan password admin aplikasi </th></tr>

	<tr><td>
	   <label for="nippeng">Nama : ?</label></td><td>
		<input id="yname" name="yname" placeholder="User Name " type="text" size="25"  autofocus required>
		</td></tr>
	  <tr><td>
   <label for="nippeng">User Name : ?</label></td><td>
    <input id="yusername" name="yusername" placeholder="User Name " type="text" size="25" required>
	</td></tr>
<tr><td>
   <label for="nmpeng">Password ?</label></td><td>
    <input id="ypassadmin" name="ypassadmin" placeholder="Password " type="password" size="60" required>
  </td></tr>
<tr><td>
   <label for="nmpeng">Ulang password ?</label></td><td>
    <input id="yypassadmin" name="yypassadmin" placeholder="Ulang password " type="password" size="60" required >
  </td></tr>  

<tr><td colspan=2>
<div class="buttons">

		<input id="gobutton" type="submit" value="Simpan " />
		
		</div>
</td></tr>		
</table>
		</form>
	
<?php	
}

elseif ($op == "updatepasswordadmin")
{
    $naman=$_POST["yname"];
	$usern=$_POST["yusername"];
	$passa=$_POST["ypassadmin"];
	$passb=$_POST["yypassadmin"];
	
	if ($passa != $passb )
	{
		echo "<img src='../images/close.png'> Password tidak sama ";
	}
	else
    {		
		include "../lsp_koneksi.php";
		$passok=MD5($passa);
		$cekduluu="select * from lsp_usertbl where email='".$usern."'";
		//echo $cekduluu;
		$cekdulua=mysql_query($cekduluu);
		$cekdulub=mysql_fetch_row($cekdulua);
		//echo $cekdulub;
		if ($cekdulub>0)
		{
			echo " <img src='../images/close.png'> User sudah ada ";
			
		}
         else 		
		 {
			 
		$yql="INSERT INTO lsp_usertbl VALUE('','$naman','$usern','$passok','1','','lsp','','')";
	    //echo $yql;
		$yqla=mysql_query($yql);
		if ($yqla) 
		{
			echo "<img src='../images/ceklist.png'> Update admin sukses"; 
		}
		else 
		{
			echo "<img src='../images/close.png'> Gagal !!!";
		}
		 }
	}
	
}	

 
elseif ($op == "updatepasswordroot")
{	
	include "../parser-php-version.php";
	//include "../lsp_koneksi";
    $passwordrlama=$_POST["passrootlama"];
	$passwordrbaru=$_POST["passrootbaru"];
	$upasswordrbaru=$_POST["upassrootbaru"];
	$vndatabase=$_POST["ndatabase"];
	$vuserdtbs=$_POST["userdtbs"];
	//echo "$passwordrlama";
	// if isset set the varibables
	if ($passwordrbaru=='')
	{
	echo "<b>Tidak ada perubahan password</b></br>";
	$passr=$passwordrlama;}
	else {
	 if ($passwordrbaru != $upasswordrbaru) 
	 {
	  echo "<b>Password tidak sama</b></br>" ;
	 }
	 else {
    $passr = $_POST["passrootbaru"];
    $dbhost = 'localhost';
	$dbuser = $vuserdtbs;
	//$dbpass = $passwordrlama;
	$koneksi = mysql_connect($dbhost, $dbuser, $passwordrlama);
	$x="SET PASSWORD FOR root@localhost = PASSWORD('".$passr."')";
	mysql_query($x);
	 }
	}
	  $dbhostx = 'localhost';
	  $dbuserx = $vuserdtbs;
	  //echo "dddd";
	  $koneksi = mysql_connect($dbhostx, $dbuserx, $passr);
		if(! $koneksi )
		{
		  die('Gagal Koneksi: ' . mysql_error());
		}
		echo '<b>SETELAH SELESAI SILAHKAN EDIT FILE lsp_koneksi.php di ROOT <b></br>'; 
		echo '<b>Koneksi Berhasil</b></br>';
		mysql_query("CREATE USER 'adminlsp'@'%' IDENTIFIED BY 'aplikasilsp';");
		//mysql_query("GRANT ALL ON aku.* TO 'ade'@'localhost'");
		// GRANT ALL PRIVILEGES ON *.* TO 'ade'@'localhost'
		//mysql_query("GRANT ALL PRIVILEGES ON lsp.* TO 'adminlsp'@'%'");
		mysql_query("GRANT ALL PRIVILEGES ON".$vndatabase.".* TO 'adminlsp'@'%'");
		//$sql = 'CREATE Database lsp';
		$sql = 'CREATE Database '.$vndatabase;
		$buatdb = mysql_query( $sql, $koneksi );
		if(! $buatdb )
		{
		  echo '<b>Pembuatan database, gagal</b> : ' . mysql_error().'</br>';
		}
		else {
		echo "<b>Database".$vndatabase."berhasil dibuat</b> </br>";
		}
		// buat tabel 
		$konek=mysql_select_db($vndatabase);
            if (! $konek){
				echo "<b>Gagal koneksi ke database terpilih</b>";
			}
				else
				{
					$tbapl1="CREATE TABLE `apl1` (
					  `idasesi` int(11) NOT NULL,
					  `namasiswa` varchar(70) NOT NULL,
					  `tmplahir` varchar(30) NOT NULL,
					  `tgllahir` date NOT NULL,
					  `jeniskelamin` varchar(2) NOT NULL,
					  `kebangsaan` varchar(50) NOT NULL,
					  `alamat` varchar(245) NOT NULL,
					  `kodepos` varchar(10) NOT NULL,
					  `tlprumah` varchar(15) NOT NULL,
					  `hp` varchar(15) NOT NULL,
					  `tlpkantor` varchar(15) NOT NULL,
					  `email` varchar(50) NOT NULL,
					  `pendidikan` varchar(50) NOT NULL,
					  `namalembaga` varchar(100) NOT NULL,
					  `jurusan` varchar(100) NOT NULL,
					  `poto` varchar(150) NOT NULL,
					  `email2` varchar(50) NOT NULL,
					  `buktiapl1` varchar(100) NOT NULL,
					  `validasiapl1` enum('N','Y') NOT NULL,
					  `nik` varchar(20) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";
					 //echo $tbapl1;
					$buattabelapl1 = mysql_query( $tbapl1, $koneksi );
					if(! $buattabelapl1 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error()."</br>";
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel apl1 sukses dibuat </br>";}

					$tbapl2 = "CREATE TABLE `apl2` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `idunit` varchar(15) NOT NULL,
					  `idelemen` varchar(15) NOT NULL,
					  `idsubelemen` varchar(15) NOT NULL,
					  `idadsesi` varchar(15) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `path` varchar(100) NOT NULL,
					  `waktu` date NOT NULL,
					  `tanggalpapl2` date NOT NULL,
					  `tk` enum('K','BK') NOT NULL,
					  `sbukti` varchar(20) DEFAULT NULL,
					  `svalidasi` enum('T','Y') NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM AUTO_INCREMENT=127619 DEFAULT CHARSET=latin1";

					$buattabelapl2 = mysql_query( $tbapl2, $koneksi );
					if(! $buattabelapl2 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel apl2 sukses dibuat </br>";}

					$tbelemen = "CREATE TABLE `elemen` (
					  `idelemen` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` int(11) NOT NULL,
					  `idunit` int(11) NOT NULL,
					  `kodeelemen` varchar(10) NOT NULL,
					  `namaelemen` varchar(250) NOT NULL,
					  PRIMARY KEY (`idelemen`)
					) ENGINE=MyISAM AUTO_INCREMENT=339 DEFAULT CHARSET=latin1;";

					$buattabelelemen = mysql_query( $tbelemen, $koneksi );
					if(! $buattabelelemen )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : ". mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel elemen sukses dibuat </br>";}

					$tbgrade = "CREATE TABLE `grade` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `kd_modul` varchar(20) NOT NULL DEFAULT '',
					  `modul` text NOT NULL,
					  `nim` varchar(15) NOT NULL DEFAULT '',
					  `nama` varchar(50) NOT NULL DEFAULT '',
					  `salah` int(11) NOT NULL DEFAULT 0,
					  `benar` int(11) NOT NULL DEFAULT 0,
					  `jumlah_soal` int(3) NOT NULL DEFAULT 0,
					  `grade` int(11) NOT NULL DEFAULT 0,
					  `tanggal` date NOT NULL ,
					  `kelas` text DEFAULT NULL,
					  `kkm` double DEFAULT NULL,
					  `ujianke` varchar(5) NOT NULL,
					  `waktureal` varchar(20) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM AUTO_INCREMENT=15064 DEFAULT CHARSET=latin1;";

					$buattabelgrade = mysql_query( $tbgrade, $koneksi );
					if(! $buattabelgrade )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel grade sukses dibuat </br>";}

					$tbgradealias = "CREATE TABLE `gradealias` (
					  `idgalias` int(11) NOT NULL AUTO_INCREMENT,
					  `kodemodul` varchar(20) NOT NULL,
					  `nim` varchar(20) NOT NULL,
					  PRIMARY KEY (`idgalias`)
					) ENGINE=InnoDB AUTO_INCREMENT=1042 DEFAULT CHARSET=latin1";

					$buattabelgradealias = mysql_query( $tbgradealias, $koneksi );
					if(! $buattabelgradealias )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel gradealias sukses dibuat </br>";}

					$tbgradefeed = "CREATE TABLE  `gradefeed` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `kd_modul` varchar(20) NOT NULL DEFAULT '',
					  `modul` text NOT NULL,
					  `nim` varchar(100) NOT NULL DEFAULT '',
					  `nama` varchar(50) NOT NULL DEFAULT '',
					  `salah` int(11) NOT NULL DEFAULT 0,
					  `benar` int(11) NOT NULL DEFAULT 0,
					  `jumlah_soal` int(3) NOT NULL DEFAULT 0,
					  `grade` int(11) NOT NULL DEFAULT 0,
					  `tanggal` date NOT NULL ,
					  `kelas` text DEFAULT NULL,
					  `kkm` double DEFAULT NULL,
					  `ujianke` varchar(5) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelgradefeed = mysql_query( $tbgradefeed, $koneksi );
					if(! $buattabelgradefeed )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel gradefeed sukses dibuat </br>";}

					$tbjawabanabc = "CREATE TABLE `jawabanabc` (
					  `nis` varchar(15) NOT NULL,
					  `nama` varchar(70) NOT NULL,
					  `jawaban` varchar(100) NOT NULL,
					  `ket` varchar(100) NOT NULL,
					  `kelas` varchar(15) NOT NULL,
					  `tgl` date NOT NULL,
					  `waktureal` varchar(20) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=latin1";

					$buattabeljawabanabc = mysql_query( $tbjawabanabc, $koneksi );
					if(! $buattabeljawabanabc )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel jawabanabc sukses dibuat </br>";}

					$tbuser = "CREATE TABLE `lsp_usertbl` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `nama` varchar(50) NOT NULL,
					  `email` varchar(50) NOT NULL,
					  `password` varchar(50) NOT NULL,
					  `status` varchar(1) NOT NULL,
					  `kode` date NOT NULL,
					  `level` enum('lsp','asesor','peserta') NOT NULL,
					  `notelp` varchar(25) NOT NULL,
					  `linkttd` varchar(100) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM AUTO_INCREMENT=4914 DEFAULT CHARSET=latin1";

					$buattabeluser = mysql_query( $tbuser, $koneksi );
					if(! $buattabeluser )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel user sukses dibuat </br>";}

					$tbmak2 = "CREATE TABLE `mak2` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `idunit` varchar(15) NOT NULL,
					  `idelemen` varchar(15) NOT NULL,
					  `idsubelemen` varchar(15) NOT NULL,
					  `idadsesi` varchar(15) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `waktu` date NOT NULL,
					  `tk` enum('K','BK') NOT NULL,
					  `sbukti` varchar(20) NOT NULL,
					  `svalidasi` enum('T','Y') NOT NULL,
					  `yt` enum('Y','T') NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM AUTO_INCREMENT=6854 DEFAULT CHARSET=latin1";

					$buattabelmak2 = mysql_query( $tbmak2, $koneksi );
					if(! $buattabelmak2 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mak2 sukses dibuat </br>";}

					$tbmak2rekom = "CREATE TABLE `mak2rekom` (
					  `idrekomendasi` int(11) NOT NULL AUTO_INCREMENT,
					  `namarekom` varchar(20) NOT NULL,
					  `idskema` varchar(15) NOT NULL,
					  `idasesor` varchar(15) NOT NULL,
					  `idasesi` varchar(15) NOT NULL,
					  `pencapaian` enum('Y','T') NOT NULL,
					  `senjang` enum('Y','T') NOT NULL,
					  `saran` enum('Y','T') NOT NULL,
					  `catsenjang` varchar(200) NOT NULL,
					  `catsaran` varchar(200) NOT NULL,
					  `tanggal` date NOT NULL,
					  PRIMARY KEY (`idrekomendasi`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelmak2rekom = mysql_query( $tbmak2rekom, $koneksi );
					if(! $buattabelmak2rekom )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mak2rekom sukses dibuat </br>";}


					$tbmak4 = "CREATE TABLE `mak4` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `idunit` varchar(15) NOT NULL,
					  `idelemen` varchar(15) NOT NULL,
					  `idsubelemen` varchar(15) NOT NULL,
					  `idadsesi` varchar(15) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `waktu` date NOT NULL,
					  `tk` enum('K','BK') NOT NULL,
					  `sbukti` varchar(20) NOT NULL,
					  `svalidasi` enum('T','Y') NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelmak4 = mysql_query( $tbmak4, $koneksi );
					if(! $buattabelmak4 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mak4 sukses dibuat </br>";}

					$tbmak4rekom = "CREATE TABLE `mak4rekom` (
					  `idrekomendasi` int(11) NOT NULL AUTO_INCREMENT,
					  `namarekom` varchar(20) NOT NULL,
					  `idskema` varchar(15) NOT NULL,
					  `idasesor` varchar(15) NOT NULL,
					  `idasesi` varchar(15) NOT NULL,
					  `pencapaian` enum('Y','T') NOT NULL,
					  `senjang` enum('Y','T') NOT NULL,
					  `saran` enum('Y','T') NOT NULL,
					  `catsenjang` varchar(200) NOT NULL,
					  `catsaran` varchar(200) NOT NULL,
					  `tanggal` date NOT NULL,
					  PRIMARY KEY (`idrekomendasi`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelmak4rekom = mysql_query( $tbmak4rekom, $koneksi );
					if(! $buattabelmak4rekom )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mak4rekom sukses dibuat </br>";}

					$tbmak5 = "CREATE TABLE `mak5` (
					  `idmak5` int(11) NOT NULL AUTO_INCREMENT,
					  `idasesi` varchar(100) NOT NULL,
					  `namap` varchar(100) NOT NULL,
					  `idqmak5` int(11) NOT NULL,
					  `hasil` varchar(3) NOT NULL,
					  `catatan` varchar(100) NOT NULL,
					  `catatanlain` text NOT NULL,
					  `tglreg` date NOT NULL,
					  `tglpmak5` date NOT NULL,
					  PRIMARY KEY (`idmak5`)
					) ENGINE=InnoDB AUTO_INCREMENT=3951 DEFAULT CHARSET=latin1";

					$buattabelmak5 = mysql_query( $tbmak5, $koneksi );
					if(! $buattabelmak5 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mak5 sukses dibuat </br>";}

					$tbmak5baru = "CREATE TABLE `mak5baru` (
					  `idmak5` int(11) NOT NULL,
					  `idskema` int(11) NOT NULL,
					  `idunit` int(11) NOT NULL,
					  `idasesi` int(11) NOT NULL,
					  `idasesor` int(11) NOT NULL,
					  `bk` varchar(2) NOT NULL,
					  `kelompok` varchar(5) NOT NULL,
					  `ketmak5` varchar(150) NOT NULL,
					  `aspek` varchar(200) NOT NULL,
					  `penolakan` varchar(200) NOT NULL,
					  `saran` varchar(200) NOT NULL,
					  `catatan` varchar(200) NOT NULL,
					  `namaassmak5` varchar(50) NOT NULL,
					  `namaasesimak5` varchar(50) NOT NULL,
					  `kodeunittmak5` varchar(20) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=latin1";

					$buattabelmak5baru = mysql_query( $tbmak5baru, $koneksi );
					if(! $buattabelmak5baru )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mak5baru sukses dibuat </br>";}

					$tbmak6 = "CREATE TABLE `mak6` (
					  `idmak6` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` int(11) NOT NULL,
					  `idunit` int(11) NOT NULL,
					  `idasesi` int(11) NOT NULL,
					  `idasesor` int(11) NOT NULL,
					  `bk` varchar(2) NOT NULL,
					  `lanti` varchar(35) NOT NULL,
					  `penolakan` varchar(35) NOT NULL,
					  `perbaikan` varchar(35) NOT NULL,
					  `lantia` varchar(100) NOT NULL,
					  `penolakana` varchar(100) NOT NULL,
					  `perbaikana` varchar(100) NOT NULL,
					  PRIMARY KEY (`idmak6`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1";

					$buattabelmak6 = mysql_query( $tbmak6, $koneksi );
					if(! $buattabelmak6 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mak6 sukses dibuat </br>";}


					$tbmak7 = "CREATE TABLE `mak7` (
					  `idmak7` int(11) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `idqmak7` int(11) NOT NULL,
					  `validasi` varchar(10) NOT NULL,
					  `valid` varchar(10) NOT NULL,
					  `rvalidasi` varchar(250) NOT NULL,
					  `rvalid` varchar(250) NOT NULL,
					  `idskemamak7` varchar(150) NOT NULL,
					  `tglreg` date NOT NULL,
					  `idpeninjau` varchar(5) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=latin1";

					$buattabelmak7 = mysql_query( $tbmak7, $koneksi );
					if(! $buattabelmak7 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mak7 sukses dibuat </br>";}

					$tbmodul = "CREATE TABLE `modul` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `kd_modul` varchar(20) NOT NULL DEFAULT '0',
					  `modul` varchar(150) NOT NULL DEFAULT '',
					  `jumlah_soal` int(3) NOT NULL DEFAULT 0,
					  `status_soal` varchar(15) NOT NULL DEFAULT '',
					  `Waktu` int(2) NOT NULL,
					  `batas` int(1) NOT NULL,
					  `kkm` double DEFAULT NULL,
					  `tanggal` date NOT NULL,
					  `btsawal` time NOT NULL,
					  `btsakhir` time NOT NULL,
					  `token` varchar(5) NOT NULL,
					  `updatetoken` time NOT NULL,
					  `acak` varchar(1) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=latin1";

					$buattabelmodul = mysql_query( $tbmodul, $koneksi );
					if(! $buattabelmodul )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel modul sukses dibuat </br>";}

					$tbmodulalias = "CREATE TABLE `modulalias` (
					  `idmalias` int(11) NOT NULL AUTO_INCREMENT,
					  `kdmodulalias` varchar(20) NOT NULL,
					  PRIMARY KEY (`idmalias`)
					) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1";

					$buattabelmodulalias = mysql_query( $tbmodulalias, $koneksi );
					if(! $buattabelmodulalias )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel modulalias sukses dibuat </br>";}

					$tbmodulfeed = "CREATE TABLE `modulfeed` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `kd_modul` varchar(20) NOT NULL DEFAULT '0',
					  `modul` varchar(150) NOT NULL DEFAULT '',
					  `jumlah_soal` int(3) NOT NULL DEFAULT 0,
					  `status_soal` varchar(15) NOT NULL DEFAULT '',
					  `Waktu` int(2) NOT NULL,
					  `batas` int(1) NOT NULL,
					  `kkm` double DEFAULT NULL,
					  `tanggal` date NOT NULL,
					  `btsawal` time NOT NULL,
					  `btsakhir` time NOT NULL,
					  `token` varchar(5) NOT NULL,
					  `updatetoken` time NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelmodulfeed = mysql_query( $tbmodulfeed, $koneksi );
					if(! $buattabelmodulfeed )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel modulfeed sukses dibuat </br>";}

					$tbpemetaan = "CREATE TABLE `pemetaan` (
					  `idpemetaan` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `idasesor` varchar(15) NOT NULL,
					  `namaasesor` varchar(100) NOT NULL,
					  `kelompok` varchar(10) NOT NULL,
					  `tanggal` date NOT NULL,
					  `idpeserta` varchar(15) NOT NULL,
					  `namapeserta` varchar(100) NOT NULL,
					  `statusvalid` enum('N','Y') NOT NULL,
					  PRIMARY KEY (`idpemetaan`)
					) ENGINE=MyISAM AUTO_INCREMENT=1641 DEFAULT CHARSET=latin1";

					$buattabelpemetaan = mysql_query( $tbpemetaan, $koneksi );
					if(! $buattabelpemetaan )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel pemetaan sukses dibuat </br>";}

					$tbpengurus = "CREATE TABLE  `pengurus` (
					  `idpengurus` int(11) NOT NULL AUTO_INCREMENT,
					  `namapengurus` varchar(50) NOT NULL,
					  `nip` varchar(20) NOT NULL,
					  `jabatan` varchar(50) NOT NULL,
					  `ttd` varchar(20) NOT NULL,
					  PRIMARY KEY (`idpengurus`)
					) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1";

					$buattabelpengurus = mysql_query( $tbpengurus, $koneksi );
					if(! $buattabelpengurus )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel pengurus sukses dibuat </br>";}

					$tbpermohonan = "CREATE TABLE `permohonan` (
					  `idpermohonan` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `id_user` varchar(15) NOT NULL,
					  `nama` varchar(100) NOT NULL,
					  `email` varchar(50) NOT NULL,
					  `tujuanasesmen` varchar(100) NOT NULL,
					  `lainnya` varchar(100) NOT NULL,
					  `sertifikasi` varchar(100) NOT NULL,
					  `kontekasesmen` varchar(100) NOT NULL,
					  `karakteristik` varchar(100) NOT NULL,
					  `acuanp` varchar(100) NOT NULL,
					  `tuk` varchar(15) NOT NULL,
					  `statuspmt` enum('N','Y') NOT NULL,
					  `statusvalid` enum('N','Y') NOT NULL,
					  `tanggal` date NOT NULL,
					  `tanggalp` date NOT NULL,
					  PRIMARY KEY (`idpermohonan`)
					) ENGINE=MyISAM AUTO_INCREMENT=1179 DEFAULT CHARSET=latin1";

					$buattabelpermohonan = mysql_query( $tbpermohonan, $koneksi );
					if(! $buattabelpermohonan )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel permohonan sukses dibuat </br>";}

					$tbpertanyaan = "CREATE TABLE `pertanyaan` (
					  `question_id` int(11) NOT NULL AUTO_INCREMENT,
					  `kd_modul` varchar(20) DEFAULT NULL,
					  `question` longtext DEFAULT NULL,
					  `oa` varchar(2) NOT NULL,
					  `ob` varchar(2) NOT NULL,
					  `oc` varchar(2) NOT NULL,
					  `od` varchar(2) NOT NULL,
					  `oe` varchar(2) NOT NULL,
					  `answer` varchar(255) DEFAULT NULL,
					  `alt_1` varchar(255) DEFAULT NULL,
					  `alt_2` varchar(255) DEFAULT NULL,
					  `alt_3` varchar(255) DEFAULT NULL,
					  `alt_4` varchar(255) DEFAULT NULL,
					  `format` double DEFAULT NULL,
					  `unitalias` varchar(5) NOT NULL,
					  PRIMARY KEY (`question_id`)
					) ENGINE=MyISAM AUTO_INCREMENT=818 DEFAULT CHARSET=latin1";

					$buattabelpertanyaan = mysql_query( $tbpertanyaan, $koneksi );
					if(! $buattabelpertanyaan )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel pertanyaan sukses dibuat </br>";}

					$tbpertanyaanbck = "CREATE TABLE `pertanyaanbck` (
					  `question_id` int(11) NOT NULL,
					  `kd_modul` double DEFAULT NULL,
					  `question` longtext DEFAULT NULL,
					  `answer` varchar(255) DEFAULT NULL,
					  `njawab` varchar(255) DEFAULT NULL,
					  `nim` varchar(20) NOT NULL,
					  `menit` int(5) NOT NULL,
					  `nourut` int(11) NOT NULL,
					  `lastnom` int(11) NOT NULL,
					  `waktureal` varchar(20) NOT NULL,
					  `unitalias` varchar(5) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelpertanyaanbck = mysql_query( $tbpertanyaanbck, $koneksi );
					if(! $buattabelpertanyaanbck )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : ". mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel pertanyaanbck sukses dibuat </br>";}

					$tbpertanyaanfeed = "CREATE TABLE  `pertanyaanbckfeed` (
					  `question_id` int(11) NOT NULL,
					  `kd_modul` varchar(20) DEFAULT NULL,
					  `question` text DEFAULT NULL,
					  `answer` varchar(255) DEFAULT NULL,
					  `njawab` varchar(255) DEFAULT NULL,
					  `nim` varchar(100) NOT NULL,
					  `menit` int(5) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelpertanyaanfeed = mysql_query( $tbpertanyaanfeed, $koneksi );
					if(! $buattabelpertanyaanfeed )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel pertanyaanbckfeed sukses dibuat </br>";}

					$tbpertanyaanfeedback = "CREATE TABLE `pertanyaanfeedback` (
					  `question_id` int(11) NOT NULL AUTO_INCREMENT,
					  `kd_modul` varchar(20) DEFAULT NULL,
					  `question` text DEFAULT NULL,
					  `oa` varchar(2) NOT NULL,
					  `ob` varchar(2) NOT NULL,
					  `oc` varchar(2) NOT NULL,
					  `od` varchar(2) NOT NULL,
					  `oe` varchar(2) NOT NULL,
					  `answer` varchar(255) DEFAULT NULL,
					  `alt_1` varchar(255) DEFAULT NULL,
					  `alt_2` varchar(255) DEFAULT NULL,
					  `alt_3` varchar(255) DEFAULT NULL,
					  `alt_4` varchar(255) DEFAULT NULL,
					  `format` double DEFAULT NULL,
					  PRIMARY KEY (`question_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelpertanyaanfeedback = mysql_query( $tbpertanyaanfeedback, $koneksi );
					if(! $buattabelpertanyaanfeedback )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel pertanyaanfeedback sukses dibuat </br>";}

					$tbpraktek = "CREATE TABLE `praktek` (
					  `idpraktek` int(15) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `kodeunit` varchar(20) NOT NULL,
					  `instruksi` text NOT NULL,
					  `obervasi` text NOT NULL,
					  PRIMARY KEY (`idpraktek`)
					) ENGINE=MyISAM AUTO_INCREMENT=697 DEFAULT CHARSET=latin1";

					$buattabelpraktek = mysql_query( $tbpraktek, $koneksi );
					if(! $buattabelpraktek )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel praktek sukses dibuat </br>";}

					$tbqmak5 = "CREATE TABLE `qmak5` (
					  `idqmak5` int(11) NOT NULL AUTO_INCREMENT,
					  `qmak5` text NOT NULL,
					  PRIMARY KEY (`idqmak5`)
					) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1";

					$buattabelqmak5 = mysql_query( $tbqmak5, $koneksi );
					if(! $buattabelqmak5 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel qmak5 sukses dibuat </br>";}

					$tbqmak7 = "CREATE TABLE `qmak7` (
					  `idqmak7` int(11) NOT NULL AUTO_INCREMENT,
					  `pertanyaan` text NOT NULL,
					  PRIMARY KEY (`idqmak7`)
					) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1";

					$buattabelqmak7 = mysql_query( $tbqmak7, $koneksi );
					if(! $buattabelqmak7 )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel qmak7 sukses dibuat </br>";}

					$tbrekappraktek = "CREATE TABLE `rekappraktek` (
					  `idrpraktek` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `idunit` varchar(15) NOT NULL,
					  `kodeunit` varchar(20) NOT NULL,
					  `idpraktek` varchar(15) NOT NULL,
					  `idasesor` varchar(15) NOT NULL,
					  `idadsesi` varchar(15) NOT NULL,
					  `pencapaians` enum('T','Y') NOT NULL,
					  `penilaians` enum('BK','K') NOT NULL,
					  `tanggal` date NOT NULL,
					  `niai` double NOT NULL,
					  `bunit` int(11) NOT NULL,
					  PRIMARY KEY (`idrpraktek`)
					) ENGINE=MyISAM AUTO_INCREMENT=22946 DEFAULT CHARSET=latin1";

					$buattabelrekappraktek = mysql_query( $tbrekappraktek, $koneksi );
					if(! $buattabelrekappraktek )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel rekappraktek sukses dibuat </br>";}

					$tbrekomendasi = "CREATE TABLE `rekomendasi` (
					  `idrekomendasi` int(11) NOT NULL AUTO_INCREMENT,
					  `namarekom` varchar(20) NOT NULL,
					  `idskema` varchar(15) NOT NULL,
					  `idasesor` varchar(15) NOT NULL,
					  `idasesi` varchar(15) NOT NULL,
					  `rekom` enum('L','T') NOT NULL,
					  `catatan` text NOT NULL,
					  `idpermohonan` varchar(15) NOT NULL,
					  `tanggal` date NOT NULL,
					  PRIMARY KEY (`idrekomendasi`)
					) ENGINE=MyISAM AUTO_INCREMENT=3031 DEFAULT CHARSET=latin1";

					$buattabelrekomendasi = mysql_query( $tbrekomendasi, $koneksi );
					if(! $buattabelrekomendasi )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel rekomendasi sukses dibuat </br>";}

					$tbsettanggal = "CREATE TABLE `settanggal` (
					  `idtanggal` int(11) NOT NULL AUTO_INCREMENT,
					  `tanggal` date NOT NULL,
					  `ket` text NOT NULL,
					  `status` enum('A','T') NOT NULL,
					  PRIMARY KEY (`idtanggal`)
					) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1";

					$buattabelsettanggal = mysql_query( $tbsettanggal, $koneksi );
					if(! $buattabelsettanggal )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel settanggal sukses dibuat </br>";}

					$tbskema = "CREATE TABLE `skema` (
					  `idskema` int(11) NOT NULL AUTO_INCREMENT,
					  `kodeskema` varchar(30) NOT NULL,
					  `namaskema` varchar(100) NOT NULL,
					  `status` enum('Y','N') NOT NULL,
					  PRIMARY KEY (`idskema`)
					) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1";

					$buattabelskema = mysql_query( $tbskema, $koneksi );
					if(! $buattabelskema )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel skema sukses dibuat </br>";}

					$tbskemasiswa = "CREATE TABLE `skemasiswa` (
					  `idpilihs` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `emailsiswa` varchar(50) NOT NULL,
					  `idunit` varchar(15) NOT NULL,
					  `statusapl1` enum('N','Y') NOT NULL,
					  `statusapl2` enum('N','Y') NOT NULL,
					  `statustest` enum('N','Y') NOT NULL,
					  `statustesp` enum('N','Y') NOT NULL,
					  `tglrekskema` date NOT NULL,
					  PRIMARY KEY (`idpilihs`)
					) ENGINE=MyISAM AUTO_INCREMENT=1233 DEFAULT CHARSET=latin1";

					$buattabelskemasiswa = mysql_query( $tbskemasiswa, $koneksi );
					if(! $buattabelskemasiswa )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel skemasiswa sukses dibuat </br>";}

					$tbstatuskerja = "CREATE TABLE `statuskerja` (
					  `nim` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
					  `statusk` varchar(5) NOT NULL,
					  `waktu` time NOT NULL,
					  PRIMARY KEY (`nim`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelsstatuskerja = mysql_query( $tbstatuskerja, $koneksi );
					if(! $buattabelsstatuskerja )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel statuskerja sukses dibuat </br>";}

					$tbsubelemen = "CREATE TABLE `subelemen` (
					  `idsubelemen` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` int(11) NOT NULL,
					  `idunit` int(11) NOT NULL,
					  `idelemen` int(11) NOT NULL,
					  `kodesubelemen` varchar(25) NOT NULL,
					  `pertanyaan` text NOT NULL,
					  PRIMARY KEY (`idsubelemen`)
					) ENGINE=MyISAM AUTO_INCREMENT=964 DEFAULT CHARSET=latin1";

					$buattabelsubelemen = mysql_query( $tbsubelemen, $koneksi );
					if(! $buattabelsubelemen )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel subelemen sukses dibuat </br>";}

					$tbsyarat = "CREATE TABLE `syarat` (
					  `idsyarat` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(10) NOT NULL,
					  `kodesyarat` varchar(5) NOT NULL,
					  `syarat` text NOT NULL,
					  PRIMARY KEY (`idsyarat`)
					) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1";

					$buattabelsyarat = mysql_query( $tbsyarat, $koneksi );
					if(! $buattabelsyarat )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel syarat sukses dibuat </br>";}

					$tbsyaratsiswa = "CREATE TABLE `syaratsiswa` (
					  `idsyaratsiswa` int(11) NOT NULL AUTO_INCREMENT,
					  `idsyarat` varchar(10) NOT NULL,
					  `idskema` varchar(10) NOT NULL,
					  `idasesi` varchar(20) NOT NULL,
					  `ceklista` varchar(5) NOT NULL,
					  `ceklistb` varchar(5) NOT NULL,
					  PRIMARY KEY (`idsyaratsiswa`)
					) ENGINE=InnoDB AUTO_INCREMENT=5446 DEFAULT CHARSET=latin1";

					$buattabelsyaratsiswa = mysql_query( $tbsyaratsiswa, $koneksi );
					if(! $buattabelsyaratsiswa )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel syaratsiswa sukses dibuat </br>";}

					$tbtanggaltes = "CREATE TABLE `tanggaltes` (
					  `idtgltes` int(11) NOT NULL AUTO_INCREMENT,
					  `namatuktes` varchar(100) NOT NULL,
					  `tgltes` date NOT NULL,
					  PRIMARY KEY (`idtgltes`)
					) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1";

					$buattabeltanggaltes = mysql_query( $tbtanggaltes, $koneksi );
					if(! $buattabeltanggaltes )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel tanggaltes sukses dibuat </br>";}

					$tbtblgbr = "CREATE TABLE `tblgbr` (
					  `idg` int(11) NOT NULL AUTO_INCREMENT,
					  `user` varchar(50) NOT NULL,
					  `poto` varchar(100) NOT NULL,
					  `ttd` varchar(100) NOT NULL,
					  PRIMARY KEY (`idg`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1";

					$buattabeltblgbr = mysql_query( $tbtblgbr, $koneksi );
					if(! $buattabeltblgbr )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel tblgbr sukses dibuat </br>";}

					$tbtbloption = "CREATE TABLE `tbloption` (
					  `question_id` int(11) NOT NULL,
					  `kd_modul` varchar(20) NOT NULL,
					  `noption` varchar(2) NOT NULL,
					  `toption` text NOT NULL,
					  `tunitalias` varchar(5) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabeltbloption = mysql_query( $tbtbloption, $koneksi );
					if(! $buattabeltbloption )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : ". mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel tbloption sukses dibuat </br>";}

					$tbtbloptionfeed = "CREATE TABLE `tbloptionfeed` (
					  `question_id` int(11) NOT NULL,
					  `kd_modul` varchar(20) NOT NULL,
					  `noption` varchar(2) NOT NULL,
					  `toption` text NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabeltbloptionfeed = mysql_query( $tbtbloptionfeed, $koneksi );
					if(! $buattabeltbloptionfeed )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel tbloptionfeed sukses dibuat </br>";}

					$tbtuk = "CREATE TABLE `tuk` (
					  `idtuk` int(11) NOT NULL AUTO_INCREMENT,
					  `namatuk` varchar(200) NOT NULL,
					  PRIMARY KEY (`idtuk`)
					) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1";

					$buattabeltuk = mysql_query( $tbtuk, $koneksi );
					if(! $buattabeltuk )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel tuk sukses dibuat </br>";}

					$tbunit = "CREATE TABLE `unit` (
					  `idunit` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` int(11) NOT NULL,
					  `kodeunit` varchar(50) NOT NULL,
					  `namaunit` varchar(200) NOT NULL,
					  `keterangan` varchar(250) NOT NULL,
					  `status` enum('Y','N') NOT NULL,
					  PRIMARY KEY (`idunit`)
					) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=latin1";

					$buattabelunit = mysql_query( $tbunit, $koneksi );
					if(! $buattabelunit )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel unit sukses dibuat </br>";}

					$tbunitalias = "CREATE TABLE `unitalias` (
					  `idalias` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(5) NOT NULL,
					  `kdalias` varchar(5) NOT NULL,
					  `namaalias` varchar(255) NOT NULL,
					  `nmasli` varchar(50) NOT NULL,
					  PRIMARY KEY (`idalias`)
					) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1";

					$buattabelunitalias = mysql_query( $tbunitalias, $koneksi );
					if(! $buattabelunitalias )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel unitalias sukses dibuat </br>";}


					$tbunitsiswa = "CREATE TABLE `unitsiswa` (
					  `idunitsiswa` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `idunit` varchar(15) NOT NULL,
					  `idadsesi` varchar(15) NOT NULL,
					  `email` varchar(100) NOT NULL,
					  `status` enum('T','Y') NOT NULL,
					  PRIMARY KEY (`idunitsiswa`)
					) ENGINE=MyISAM AUTO_INCREMENT=18316 DEFAULT CHARSET=latin1";
					
					
					$buattabelunitsiswa = mysql_query( $tbunitsiswa, $koneksi );
					if(! $buattabelunitsiswa )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel unitsiswa sukses dibuat </br>";}
				
					$tbbanding="CREATE TABLE `banding` (
					  `idbanding` int(11) NOT NULL,
					  `idasesorb` int(11) NOT NULL,
					  `idasesib` int(11) NOT NULL,
					  `idskemab` int(11) NOT NULL,
					  `ketb` varchar(10) NOT NULL,
					  `tglbanding` date NOT NULL,
					  `alasan` varchar(200) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
					$buattabelbanding = mysql_query( $tbbanding, $koneksi );
					if(! $buattabelbanding )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel banding sukses dibuat </br>";}
				
					
					$tbrahasia="CREATE TABLE `rahasia` (
				  `idrahasia` int(11) NOT NULL,
				  `idskemarhs` varchar(4) NOT NULL,
				  `idasesirhs` varchar(10) NOT NULL,
				  `idasesorrhs` varchar(10) NOT NULL,
				  `buktirhs` varchar(15) NOT NULL,
				  `tukrhs` varchar(20) NOT NULL,
				  `tglrhs` varchar(30) NOT NULL,
				  `wakturhs` varchar(15) NOT NULL,
				  `tukb` varchar(40) NOT NULL,
				  `tgltetap` varchar(12) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
				$buattabelrahasia = mysql_query( $tbrahasia, $koneksi );
					if(! $buattabelrahasia )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel rahasia sukses dibuat </br>";}
				
				
				$tbpihakketiga="CREATE TABLE `pihakketiga` (
				  `idpihakketiga` int(11) NOT NULL,
				  `idskemaphktiga` varchar(5) NOT NULL,
				  `idasesiphktiga` varchar(5) NOT NULL,
				  `idasesorphktiga` varchar(5) NOT NULL,
				  `idunitphktiga` varchar(5) NOT NULL,
				  `jawabanphktiga` varchar(15) NOT NULL,
				  `tempatkerja` varchar(50) NOT NULL,
				  `alamat` varchar(150) NOT NULL,
				  `notlp` varchar(15) NOT NULL,
				  `hubunganphktiga` varchar(100) NOT NULL,
				  `lamaphktiga` varchar(100) NOT NULL,
				  `dekatphktiga` varchar(100) NOT NULL,
				  `pengalamanphktiga` varchar(100) NOT NULL,
				  `standarphktiga` varchar(100) NOT NULL,
				  `kebutuhanphktiga` varchar(100) NOT NULL,
				  `komenphktiga` varchar(100) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
				$buattabelpihakketiga = mysql_query( $tbpihakketiga, $koneksi );
					if(! $buattabelpihakketiga )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel Pihakketiga sukses dibuat </br>";}
				

					$tbupload = "CREATE TABLE `upload` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `idskema` varchar(15) NOT NULL,
					  `idasesi` varchar(15) NOT NULL,
					  `email` varchar(50) NOT NULL,
					  `idunit` varchar(15) NOT NULL,
					  `idelemen` varchar(15) NOT NULL,
					  `bukti` varchar(100) NOT NULL,
					  `Path` varchar(100) NOT NULL,
					  `waktu` date NOT NULL,
					  `tanggalpapl1` date NOT NULL,
					  `statusvalid` enum('N','Y') NOT NULL,
					  `dbukti` varchar(20) NOT NULL,
					  `lt` enum('Y','T') NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1";

					$buattabelupload = mysql_query( $tbupload, $koneksi );
					if(! $buattabelupload )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel upload sukses dibuat </br>";}

					$tbver = "CREATE TABLE `ver` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `nm` varchar(20) NOT NULL,
					  `mc` varchar(100) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1";

					$buattabelver = mysql_query( $tbver, $koneksi );
					if(! $buattabelver )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel ver sukses dibuat </br>";}

					$tbhasil = "CREATE TABLE `vhasil` (
					  `statushasil` varchar(3) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelvhasil = mysql_query( $tbhasil, $koneksi );
					if(! $buattabelvhasil )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel vhasil sukses dibuat </br>";}

					$tbvwaktu = "CREATE TABLE `vwaktu` (
					  `status` varchar(2) NOT NULL
					) ENGINE=MyISAM DEFAULT CHARSET=latin1";

					$buattabelvwaktu = mysql_query( $tbvwaktu, $koneksi );
					if(! $buattabelvwaktu )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel vwaktu sukses dibuat </br>";}
				    
					$buattblinstr="CREATE TABLE `cekinstrumen` (
					  `idcek` int(11) NOT NULL,
					  `idskemacek` varchar(5) NOT NULL,
					  `idasesorcek` varchar(15) NOT NULL,
					  `idasesicek` varchar(15) NOT NULL,
					  `idunitcek` varchar(10) NOT NULL,
					  `jawabancek` varchar(25) NOT NULL,
					  `tglcek` date NOT NULL,
					  `komencek` varchar(100) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
					$buattblinstrv=mysql_query($buattblinstr, $koneksi );
					if(! $buattblinstrv )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel cekinstrumen sukses dibuat </br>";}
				
				$buatstmapa="CREATE TABLE `mma` (
				  `idmma` int(11) NOT NULL,
				  `idskemamma` int(11) NOT NULL,
				  `kandidat` varchar(25) NOT NULL,
				  `tujuan` varchar(25) NOT NULL,
				  `linkungan` varchar(25) NOT NULL,
				  `peluang` varchar(25) NOT NULL,
				  `hubungan` varchar(25) NOT NULL,
				  `melakukan` varchar(25) NOT NULL,
				  `konfirmasi` varchar(25) NOT NULL,
				  `tolakukur` varchar(25) NOT NULL,
				  `oreleva` varchar(5) NOT NULL,
				  `penyusun` varchar(5) NOT NULL,
				  `valid` varchar(5) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
				    $buattbmapa=mysql_query($buatstmapa, $koneksi );
					if(! $buattbmapa )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel mapa sukses dibuat </br>";}
				
				$buatstrukunitmak2="CREATE TABLE `unitmak2` (
				  `id_unitmak2` int(11) NOT NULL,
				  `idskemamak2` varchar(5) NOT NULL,
				  `idunitmak2` varchar(5) NOT NULL,
				  `idasesormak2` varchar(5) NOT NULL,
				  `idasesimak2` varchar(5) NOT NULL,
				  `jawabanmak2` varchar(35) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
				
				$buattbunitmak2=mysql_query($buatstrukunitmak2, $koneksi );
				if(! $buattbmapa )
					{
					  echo "<img src='../images/close.png'> Gagal Membuat : " . mysql_error().'</br>';
					} else 
					{ echo "<img src='../images/ceklist.png'> Tabel unitmak2 sukses dibuat </br>";}
				
				}

		mysql_close($koneksi);
	    
	
	
}
?>