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
$tglpelapl2=$_POST['tglpelapl2'];
$idskema=$_POST['idskema'];
$tglregapl2=$_POST['tglregapl2'];
$tglpelapl2 = date('Y-m-d', strtotime($tglpelapl2));

echo $n;
$sukses=0;
$gagal=0;
$dupp=0;
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
     //$fl=$idskema.$idunit.$idelemen.$subel;
     $fl=$idskema.$id.$idasesi.$tglpelapl2;
     $dir_foto = "gambarimages/";

//if ( !empty($_FILES['foto']['name']) ) {

//	for ( $i = 0; $i < count( $_FILES['foto']['name']); $i++ ) {

		//$nama_foto = "apl2".$fl.$_FILES['foto']['name'][$i];
		$nama_foto = "apl2".$fl.$_FILES['foto']['name'];
                $nama_foto =str_replace(' ', '', $nama_foto);
                //$nama_foto=trim($nama_foto);
		$ext = pathinfo( $nama_foto, PATHINFO_EXTENSION );
		$ekstensi = array('pdf','PDF'); // Ektensi yg diterima
		 chmod($nama_foto, 0777);
                //echo $nama_foto;
		//filter ektensi foto yang diterima
		if( in_array( $ext, $ekstensi ) ) {
		 
			//maks ukuran foto 500kb
			if( $_FILES['foto']['size']< 20000000 ) {
			//if ( move_uploaded_file( $_FILES['foto']['tmp_name'][$i], $dir_foto . $nama_foto ) ) {
                                  $cekdulu="select * from apl2 where idskema='$idskema' and idunit='$idunit' and idelemen='$idelemen' and idsubelemen='$subel' and idadsesi='$idasesi'";
                                   //echo $cekdulu;
				   $adulu=mysql_query($cekdulu);
				   if(mysql_num_rows($adulu)>0){
                                     $dupp++; 
                                     //echo " Sebagian / Seluruh Data terjadi duplikate</br>"; 
				     //echo"<script>alert('Data Sudah Ada terjadi duplikate !');history.go(-1);</script>";
                                     }
				     else {
 				   
                                   
                                   $ssql="insert into apl2 (id,idskema,idunit,idelemen,idsubelemen,idadsesi,email,path,tanggalpapl2,waktu,tk)  value('','$idskema','$idunit','$idelemen','$subel','$idasesi','$email','$nama_foto','$tglpelapl2','$tglregapl2','$cb')";
                                   //echo $ssql;
				   $ok=mysql_query($ssql);
                                   if ($ok) $sukses++;
				      else $gagal++;
					} 
				
			} else {
                                $ukuran='Ukuran foto terlalu besar';
				//echo "Ukuran foto terlalu besar <br />";
			}

		} else {
                        $kosong='file type data bukan pdf';
			//echo "<script>alert('file bukti kosong!/type data bukan pdf');history.go(-1);</script>";
		}
                //echo "Sukses simpan ".$sukses. " Gagal Kirim ".$gagal;
                $sk=$sukses;
                $gl=$gagal;
                //echo "<script>alert('Proses .. selesai');history.go(-1);</script>";

}else {
$ppilih='Belum memilih sebagian atau semuanya ..';
//echo "Belum memilih sebagian atau semuanya ..";
//echo "<script>alert('Selesai ...');history.go(-1);</script>";
}
}
                               if( in_array( $ext, $ekstensi ) ){
			       if( $_FILES['foto']['size']< 2000000 ) {
                               if ( move_uploaded_file( $_FILES['foto']['tmp_name'], $dir_foto . $nama_foto ) ) {
                                   	//echo"<script>alert('Simpan data dan Gambar Berhasil diupload !');history.go(-1);</script>";
				$pphoto='File Bukti berhasil di kirim';				         
//echo "berhasil";
				} else {
                                        $pphoto='File gagal dikirim';
					//echo "Foto <b>" . $nama_foto . " </b>Gagal <br />";
				}
                              } //else { echo "Anda tidak memilih";}

                              }
//echo $ssql;
echo "<script>alert('$ppilih $kosong $ukuran $pphoto-- Sukses = '+$sk+' Gagal = '+$gl+' Duplikat = '+$dupp);history.go(-1);</script>";

?>


 
