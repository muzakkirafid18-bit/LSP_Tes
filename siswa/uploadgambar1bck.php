<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<table cellspacing='0'><tr><td>
<?php
$email=trim($_POST['email']);
$ids=$_POST['skema'];
$tgl = date('Y-m-d H:i:s');
$dir_foto = "image/";
$n = $_POST['n'];
       $namagambar=$_FILES['gambar']['name'];
       echo  "klakslask".$_FILES['gambar']['name'];
	   
       if(strlen($namagambar) >0) {
          echo "aya";
       } 
//$bukti=$_POST['bukti'];
//$bukti1=implode(',', $bukti);
//echo $bukti1;
for ($i=0; $i<=$n-1; $i++)
   {
     if (isset($_POST['unit'.$i]))
     {
       //$bukti=$_POST['bukti'.$i];
       //$bukti1 = implode(',', $bukti);
       //echo "Bukti :".$bukti;
	   echo $i;
       $unit = $_POST['unit'.$i]; 
       echo "kajkajs".$unit."</br>";
       $bukti=$_POST['bukti'.$i];
       $bukti1=$_POST['bukti1'.$i];
	   $bukti2=$_POST['bukti2'.$i];
       $bukti3=$_POST['bukti3'.$i];
	   $bukti4=$_POST['bukti4'.$i];
	   $bukti5=$_POST['bukti5'.$i];
       $bukti6=$_POST['bukti6'.$i];
       $bukti7=$_POST['bukti7'.$i];
           
       $allbukti=$bukti.",".$bukti1.",".$bukti2.",".$bukti3.",".$bukti4.",".$bukti5.",".$bukti6.",".$bukti7;
       echo "bbb".$allbukti;    
       
	}
      
}

?>
<?php
$dir_foto = "image/";

if ( !empty($_FILES['foto']['name']) ) {

	for ( $i = 0; $i < count( $_FILES['foto']['name']); $i++ ) {

		$nama_foto = $_FILES['foto']['name'][$i];
		$ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		$ekstensi = array('jpg','jpeg','png','gif','JPG','bmp','BMP'); // Ektensi yg diterima
		 chmod($nama_foto, 0777);
		//filter ektensi foto yang diterima
		if( in_array( $ext, $ekstensi ) ) {
		 
			//maks ukuran foto 500kb
			//if( $_FILES['foto']['size'][$i] < 200000 ) {
				 
				if ( move_uploaded_file( $_FILES['foto']['tmp_name'][$i], $dir_foto . $nama_foto ) ) {

					
					echo "Foto <b>" . $_FILES['foto']['name'][$i] . "</b> Berhasil <br />";
				
				} else {
					echo "Foto <b>" . $_FILES['foto']['name'][$i] . " </b>Gagal <br />";
				}

			//} else {

			//	echo "Ukuran foto terlalu besar, maksimum 500kb. <br />";
			//}

		} else {

			echo "Format  " . $_FILES['foto']['name'][$i] . "  tidak didukung. <br>";
		}

	}

} else {

	echo "Foto masih kosong.";
}
?>
</td></tr>
<tr><td><?php echo $i." File Terupload Selesai"; ?></td></tr>
<tr><th ><a href="adminmenu.php" title="logout" style="height:21px;line-height:21px;"><img src="img/users2.png" alt=""/>Kembali</a> </th></tr>
</table>
