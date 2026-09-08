<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<table cellspacing='0'><tr><td>
<?php
include "../lsp_koneksi.php";
$email=trim($_POST['email']);
//$ids=$_POST['idskema'];
$idasesi=$_POST['idadsesi'];
//$tgl = date('Y-m-d H:i:s');
$dir_foto = "image/";
$n = $_POST['n'];
$dir_foto = "gambarimages/";
$tgl=$_POST['tanggal'];
$idskema=$_POST['idskema'];
//echo "kkk";
$sukses=0;
$gagal=0;
for ($i=0; $i<=$n-1; $i++)
   {
    //echo "aa";
     if (isset($_POST['bk'.$i]))
     {
     //echo "tessss";
     $cb=$_POST['bk'.$i];
     
     $idelemen=$_POST['idelemen'.$i];
     $subel=$_POST['idsube'.$i];
     $idunit=$_POST['idunit'.$i];     
     //echo $cb.$idunit.$idelemen.$subel;
     $fl=$idskema.$idunit.$idelemen.$subel;
     $dir_foto = "gambarimages/";

//if ( !empty($_FILES['foto']['name']) ) {

//	for ( $i = 0; $i < count( $_FILES['foto']['name']); $i++ ) {

		$nama_foto = "apl2".$fl.$_FILES['foto']['name'][$i];
		$ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		$ekstensi = array('pdf','PDF'); // Ektensi yg diterima
		 chmod($nama_foto, 0777);
		//filter ektensi foto yang diterima
		if( in_array( $ext, $ekstensi ) ) {
		 
			//maks ukuran foto 500kb
			if( $_FILES['foto']['size'][$i] < 270000 ) {
				 
				if ( move_uploaded_file( $_FILES['foto']['tmp_name'][$i], $dir_foto . $nama_foto ) ) {
                                   $cekdulu="select * from apl2 where idskema='$idskema' and idunit='$idunit' and idelemen='$idelemen' and idsubelemen='$subel' and idadsesi='$idasesi'";
                                   //echo $cekdulu;
				   $adulu=mysql_query($cekdulu);
				   if(mysql_num_rows($adulu)>0){
                                     echo " Sebagian / Seluruh Data terjadi duplikate</br>"; 
				     //echo"<script>alert('Data Sudah Ada terjadi duplikate !');history.go(-1);</script>";
                                     }
				     else {
 				   
                                   
                                   $ssql="insert into apl2 (id,idskema,idunit,idelemen,idsubelemen,idadsesi,email,path,waktu,tk)  value('','$idskema','$idunit','$idelemen','$subel','$idasesi','$email','$nama_foto','$tgl','$cb')";
                                   //echo $ssql;
				   $ok=mysql_query($ssql);
                                   if ($ok) $sukses++;
				      else $gagal++;
					//echo"<script>alert('Simpan data dan Gambar Berhasil diupload !');history.go(-1);</script>";
				         }
				} else {
					echo "Foto <b>" . $nama_foto . " </b>Gagal <br />";
				}

			} else {

				echo "Ukuran foto terlalu besar, maksimum 170Kb. <br />";
			}

		} else {

			echo "<script>alert('file bukti kosong!');history.go(-1);</script>";
		}
                echo "Sukses simpan ".$sukses. " Gagal Kirim ".$gagal;
                echo "<script>alert('Proses .. selesai');history.go(-1);</script>";

}
}
?>



