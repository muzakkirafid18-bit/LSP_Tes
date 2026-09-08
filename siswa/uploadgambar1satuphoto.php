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
$tgl=$_POST['tglreg'];
$tglpapl1=$_POST['tglpelapl1'];
$tglppapl1 = date('Y-m-d', strtotime($tglpapl1));



  //$nama_foto ="apl1".$ids.$unit.$_FILES['foto']['name'][$i];
  $xnama ="apl1".$ids.$idasesi.$tgl;
      // echo $nama_foto;       
$dup=0;
$suk=0;
for ($i=0; $i<=$n-1; $i++)
   {
    //echo "nilai n :".$i;
    
    //echo "n".$n-1;
     if (isset($_POST['unit'.$i]))
     {
	if (isset($_POST['buktia'.$i]) or isset($_POST['buktib'.$i]) or isset($_POST['buktic'.$i]) or isset($_POST['buktid'.$i]) or isset($_POST['buktie'.$i]) or isset($_POST['buktif'.$i]) or isset($_POST['buktig'.$i]) or isset($_POST['buktih'.$i]))
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
       //echo $eunit;    
        $nama_foto ="apl1".$xnama.$_FILES['foto']['name'];
        $nama_foto =str_replace(' ', '', $nama_foto); 
		$ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		$ekstensi = array('pdf','PDF'); // Ektensi yg diterima
		 chmod($nama_foto, 0777);
        $ufile='Ukuran file '. $_FILES['foto']['size'];
       //echo $nama_foto;       
       if( in_array( $ext, $ekstensi ) ) {
          if( $_FILES['foto']['size']< 2000000 ) {
           //echo $nama_foto;
           //echo "nama file ". $_FILES['foto']['name'][$i]; 
           $cekdata = "SELECT * FROM upload WHERE email = '$email' and idskema='$ids' and idunit='$eunit' and idelemen='$unit' and waktu='$tgl'";
            //echo $cekdata;
            
            $ada=mysql_query($cekdata);
   	        if(mysql_num_rows($ada)>0){
                  $dup++;
                  
             //echo"<script>alert('Data Sudah Ada terjadi duplikate !');history.go(-1);</script>";}
             //echo"<script>alert($ufile);history.go(-1);</script>";
	          }
             else {
                        
			$ql="insert into upload (id,idskema,idasesi,email,idunit,idelemen,bukti,path,waktu,tanggalpapl1) value ('','$ids','$idasesi','$email','$eunit','$unit','$allbukti','$nama_foto','$tgl','$tglppapl1')";
                 $sukses = mysql_query($ql);
                 //if ($sukses){
                    $suk++;
                  //}
                 //else echo "Proses Gagal";
                 //echo $ql;
				//echo"<script>alert('Selesai .. !');history.go(-1);</script>";
                  }
                } else {

				echo"<script>alert('Ukuran File Max 2MB');history.go(-1);</script>";
			}

          
          }else {
		//echo "Kalau anda belum memilih gambar berarti gagal silahkan ulangi";	
                $cekjfile='Cek Lagi Jenis File ';		
		//echo"<script>alert('CEK LAGI JENIS FILE.. !');history.go(-1);</script>";
       }
	}  
     } //else { echo"<script>alert('Tidak ada pilihan !');history.go(-1);</script>";}     
}
                if( in_array( $ext, $ekstensi ) ) {
                if( $_FILES['foto']['size']< 2000000 ) {
                
		 if ( move_uploaded_file( $_FILES['foto']['tmp_name'], $dir_foto . $nama_foto ) ) {
					//echo "Foto <b>" . $_FILES['foto']['name'][$i] . "</b> Berhasil <br />";
			
				} else {
                                        $pes='Gagal mungkin anda belum memilih / Ukuran File terlalu besar cek lagi ';
					//echo "</b>Gagal / anda mungkin belum memilih bukti relevan  minimal pilih 1 bukti relevan <br />";
				}
                    }}
//$x='lakslak';
echo"<script>alert('$cekjfile $pes-- Sukses = '+$suk+' Duplikat = '+$dup);history.go(-2);</script>";
?>

<?php

?>

</td></tr>

</table>
