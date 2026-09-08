<?php
require('pdf/fpdf.php');
include "../lsp_koneksi.php";
$email= $_GET['email'];
//echo $email;
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
        $poto=$data['poto'];
        $poto="gambardiri/".$poto;
        $tgl = date("d-m-Y", strtotime($tgllahir));

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
			<input type="text" name="tmplahir" id="tmplahir"  size="50" value="<?php echo $tmplahir; ?>"required disabled='disabled'/>
		  </div>
		  </div>
		 </td></tr>
			<tr><td colspan="2"> 
		   <div class="form-group"><strong>Tanggal Lahir :</strong>
		   <div class="dynamiclabel">
				        <label for="tanggal"></label>
				        <input type="text" name="tanggal" id="tanggal" value="<?php echo $tgl ;?>" disabled='disabled'/>
				    </div>
				    </div>
		   </td></tr>
		   <tr><td colspan="2">
		   <div class="form-group"><strong>Jenis Kelamin :</strong>
		   <div class="dynamiclabel">
   				<?php if($jeniskelamin=='lk'){?>
   						<input type="radio" name="jk" value="lk" <?php echo checked ;?> disabled='disabled'/>Laki-laki<br/>
   						<input type="radio" name="jk" value="pr"/ disabled='disabled'>Perempuan<br/>
    					<?php }else if ($jeniskelamin=='pr'){ ?>
						<input type="radio" name="jk" value="lk"/ disabled='disabled'>Laki-laki<br/>
					   <input type="radio" name="jk" value="pr" <?php echo checked ;?> disabled='disabled'/>Perempuan<br/>
   				<?php }else{ ?>
					   <input type="radio" name="jk" value="lk" disabled='disabled'/ >Laki-laki<br/>
					   <input type="radio" name="jk" value="pr" disabled='disabled'/>Perempuan<br/>
					   <?php } ?>
					   </div>
					   </div>
					   </td></tr>
					   <tr><td colspan="2">
					   <div class="form-group"><strong>Kebangsaan:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="kebangsaan" id="kebangsaan" size="10" value="<?php echo $kebangsaan; ?>" required disabled='disabled'/>
					   </div>
					   </div>
					   </td></tr>
					   <tr><td>
					   <div class="form-group"><strong>Alamat:</strong>
					   <div class="dynamiclabel">
					   <textarea name="alamat" id="alamat" cols="50" required disabled='disabled'/><?php echo $alamat; ?></textarea>
					   </div>
					   </div>
					   </td>
					   <td>
					   <div class="form-group"><strong>Kode Pos:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="kodepos" id="kodepos" size="10" value="<?php echo $kodepos; ?>"required disabled='disabled'/>
					   </div>
					   </div>
					   </td></tr>
					   <tr><td>
					   <div class="form-group"><strong>No Telp Rumah:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="rumah" id="rumah" size="15" value="<?php echo $rumah; ?>" disabled='disabled'>
					   </div>
					   </div>
					   </td>
					   <td>
					   <div class="form-group"><strong>No Telp Kantor:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="kantor" id="Kantor" size="15" value="<?php echo $tlpkantor; ?>" disabled='disabled'>
					   </div>
					   </div>
					   </td></tr>
					   <tr><td>
					   <div class="form-group"><strong>Hp:</strong>
					   <div class="dynamiclabel">
					   <input type="text" name="hp" id="hp" size="15" value="<?php echo $hp; ?>" required disabled='disabled'/>
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
						   <input type="text" name="pendidikan" id="pendidikan" size="50" value="<?php echo $pendidikan; ?>"required disabled='disabled'/>
						   </div>
						   </div>
						   </td></tr></table>
						   <tr><td colspan="2" border="0" bgcolor='#006699'><strong>B. Data Pendidikan</strong> </td><tr><td>
						   <table width="95%">
						   <tr><td colspan="2">
						   <div class="form-group"><strong>Nama Sekolah / lembaga:</strong>
						   <div class="dynamiclabel">
						   <input type="text" name="lembaga" id="lembaga" size="70" value="<?php echo $lembaga; ?>" required disabled='disabled'/>
						   </div>
						   </div>
						   </td></tr>
						   <tr><td colspan="2">
						   <div class="form-group"><strong>Jurusan / Program :</strong>
						   <div class="dynamiclabel">
						   <input type="text" name="jurusan" id="jurusan" size="50" value="<?php echo $jurusan; ?>" required disabled='disabled'/>


						   </div>
						   </div>
						   </td></tr>
<?php 
echo $poto;
echo "<tr><td><img src='$poto' height='150'></td></tr>"; ?>
						</table>
		
