<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<table cellspacing='0'><tr><td>

<?php
session_start();
include "../lsp_koneksi.php";
$email=trim($_POST['email']);
//$ids=$_POST['idskema'];
$idasesi=$_POST['idasesi'];
$tgl = date('Y-m-d H:i:s');
$dir_foto = "image/";
$n = $_POST['n'];
$dir_foto = "gambarimages/";
$idunit=$_POST['idunit'];
$idskema=$_POST['idskema'];
$idelemen=$_POST['idelemen'];
/*$a=$_FILES['foto']['name'];
echo $a;	
echo "lakslaks".$email.$idskema.$idunit.$idelemen;

$sq="select * from subelemen where idelemen='$idelemen' and idunit='$idunit' and idskema='$idskema'";
$exec=mysql_query($sq);
for ($i=0; $i<=$n-1; $i++)
   {
   $sdata=mysql_fetch_array($exec);
   $ax=$sdata['idsubelemen'];
     if (isset($_POST['bk'.$i.$ax]))
     {
     $cb=$_POST['bk'.$i.$ax];
     echo $cb;      
//echo $i;
       $unit = $_POST['unit'.$i];
       $eunit = $_POST['eunit'.$i];
       //echo "bbb".$allbukti;    
*/
        $nama_foto ="apl1".$idskema.$idunit.$_FILES['foto']['name'][$i];
		$ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		$ekstensi = array('jpg','jpeg','png','gif','JPG','bmp','BMP'); // Ektensi yg diterima
		 chmod($nama_foto, 0777);
        echo $nama_foto;
       /*
       if( in_array( $ext, $ekstensi ) ) {
           //echo $nama_foto;
           //echo "nama file ". $_FILES['foto']['name'][$i]; 

           if ( move_uploaded_file( $_FILES['foto']['tmp_name'][$i], $dir_foto . $nama_foto ) ) {
					//echo "Foto <b>" . $_FILES['foto']['name'][$i] . "</b> Berhasil <br />";
			$cekdata = "SELECT * FROM upload WHERE email = '$email' and idskema='$ids' and idunit='$eunit' and idelemen='$unit'";
            //echo $cekdata;
            $ada=mysql_query($cekdata);
   	        if(mysql_num_rows($ada)>0){
             echo"<script>alert('Data Sudah Ada terjadi duplikate !');history.go(-1);</script>";}
             else {
                    
			$ql="insert into upload (id,idskema,idasesi,email,idunit,idelemen,bukti,path,waktu) value ('','$ids','$idasesi','$email','$eunit','$unit','$allbukti','$nama_foto','$tgl')";
                 $sukses = mysql_query($ql);
                 //if ($berhasil) echo "Proses Sukses";
                 //else echo "Proses Gagal";
                 //echo $ssql;
				echo"<script>alert('Simpan data dan Gambar Berhasil diupload !');history.go(-1);</script>";
                  }
				} else {

					echo "Foto <b>" . $_FILES['foto']['name'][$i] . " </b>Gagal <br />";
				}

          }else {
			echo"<script>alert('Format File tidak sesuai atau File belum di isi !');history.go(-1);</script>";
       }
	  
     } else { echo"<script>alert('Tidak ada pilihan !');history.go(-1);</script>";
    }
      
}
*/

?>

<?php

?>

</td></tr>

</table>
