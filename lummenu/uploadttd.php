<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<table cellspacing='0'><tr><td>
<?php
session_start();
include "../lsp_koneksi.php";
if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
 echo "<center><link href='css/adminstyle.css' rel='stylesheet' type='text/css'>
 <strong> Anda Harus Login Dahulu ...!</strong><br>";
 echo "<a href=../lsp_login.php class='btn btn-primary btn-sm' role='button'> Kembali</a></center>"; 
}
else{
$idass = $_POST['iduser']; 
$dir_foto = "../imgttd/";
$namafttd=$idass;
$nama_foto = $_FILES['uploadfttd']['name'];
$nama_foto =str_replace(' ', '', $nama_foto);
//echo $nama_foto;
if ( !empty($_FILES['uploadfttd']['name']) ) {
$ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		$ekstensi = array('png','PNG','jpg','JPG'); // Ektensi yg diterima
                 if( in_array( $ext, $ekstensi ) ) {
          		if( $_FILES['uploadfttd']['size']< 100000 ) {
                        if ( move_uploaded_file( $_FILES['uploadfttd']['tmp_name'], $dir_foto . $namafttd ) ) {
                           $potopes="Photo Sukses ...";
                           $nlinkttd=$dir_foto . $namafttd;
                        $ulink="UPDATE lsp_usertbl SET linkttd='$nlinkttd' WHERE id = '$idass'";
                        mysqli_query($conn, $ulink);
                        //echo $ulink; 
                        }
                        else { $potopes=" Photo Gagal .., Ukuran photo teralalu besar atau type bukan PNG,JPG";}
                        
        	 } else { $potopes=" Photo Gagal .., Ukuran photo teralalu besar";}
			} else { $potopes=" Photo Gagal ..,type bukan PNG,JPG";}
echo"<script>alert('$potopes Proses selesai..!');history.go(-1);</script>";
} else {
echo"<script>alert('Gagal File Kosong..!');history.go(-1);</script>";}
}
?>

