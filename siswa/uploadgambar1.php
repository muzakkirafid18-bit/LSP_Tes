<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<table cellspacing='0'><tr><td>

<?php
session_start();
include "../lsp_koneksi.php";
$email=trim($_POST['email']);
$ids=$_POST['skema'];
$idasesi=$_POST['idasesi'];
//$tgl = date('Y-m-d H:i:s');
$dir_foto = "image/";
$n = $_POST['n'];
$dir_foto = "gambarimages/";
  $nama_foto ="apl1".$ids.$unit.$_FILES['foto']['name'][$i];
      // echo $nama_foto;       
$tgl=$_POST['tanggal'];
$jumlah_terpilih=count($_POST['unit']);
echo "jkajskas".$jumlah_terpilih;


for ($i=0; $i<=$n-1; $i++)
   {
    //echo "n".$n-1;
     if (isset($_POST['unit'.$i]))
     {
	   //echo $i;
       	$unit = $_POST['unit'.$i];
       	$eunit = $_POST['eunit'.$i];
       	$bukti=$_POST['buktia'.$i];
       	$bukti1=$_POST['buktib'.$i];
	$bukti2=$_POST['buktic'.$i];
       	$bukti3=$_POST['buktid'.$i];
 	$bukti4=$_POST['buktie'.$i];
	$bukti5=$_POST['buktif'.$i];
       	$bukti6=$_POST['buktig'.$i];
       	$bukti7=$_POST['buktih'.$i];
           
       $allbukti=$bukti.",".$bukti1.",".$bukti2.",".$bukti3.",".$bukti4.",".$bukti5.",".$bukti6.",".$bukti7;
       //echo "bbb".$allbukti;    
        $nama_foto ="apl1".$ids.$unit.$_FILES['foto']['name'][$i];
		$ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		//$ekstensi = array('jpg','jpeg','png','gif','JPG','bmp','BMP'); // Ektensi yg diterima
	       $ekstensi = array('pdf','PDF');
                 //chmod($dir_foto,0644);
		 chmod($nama_foto, 0777);
       //echo $nama_foto;       
       if( in_array( $ext, $ekstensi ) ) {
           //echo $nama_foto;
           //echo "nama file ". $_FILES['foto']['name'][$i]; 
           if( $_FILES['foto']['size'][$i] < 270000) {

           if ( move_uploaded_file( $_FILES['foto']['tmp_name'][$i], $dir_foto . $nama_foto ) ) {
					//echo "Foto <b>" . $_FILES['foto']['name'][$i] . "</b> Berhasil <br />";
			$cekdata = "SELECT * FROM upload WHERE email = '$email' and idskema='$ids' and idunit='$eunit' and idelemen='$unit' and waktu='$tgl'";
            //echo $cekdata;
            $ada=mysql_query($cekdata);
   	        if(mysql_num_rows($ada)>0){
             echo "Mungkin terjadi sebagian duplikate ";
             echo"<script>alert('Selesai .. !');history.go(-1);</script>";}
             else {
                    
			$ql="insert into upload (id,idskema,idasesi,email,idunit,idelemen,bukti,path,waktu) value ('','$ids','$idasesi','$email','$eunit','$unit','$allbukti','$nama_foto','$tgl')";
                 $sukses = mysql_query($ql);
                 //if ($berhasil) echo "Proses Sukses";
                 //else echo "Proses Gagal";
                 //echo $ssql;
				chmod($dir_foto,0644);
				echo"<script>alert('Selesai .. !');history.go(-1);</script>";
                  }
				} else {

					echo "Foto <b>" . $_FILES['foto']['name'][$i] . " </b>Gagal <br />";
				}
              } else {

				echo "Ukuran foto terlalu besar<br />";
			}

          }else {
		echo "Kalau anda belum memilih gambar berarti gagal silahkan ulangi atau type file bukan pdf";			
		echo"<script>alert('Selesai.. !');history.go(-1);</script>";
       }
	  
     } //else { echo"<script>alert('Tidak ada pilihan !');history.go(-1);</script>";}
     
 
}

?>

<?php

?>

</td></tr>

</table>
