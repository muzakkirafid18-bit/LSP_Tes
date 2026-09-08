<?php 
include "../../lsp_koneksi.php";
$iduserttd = $_POST['iduser'] ?? '';
$result = array();
$imagedata = base64_decode($_POST['img_data'] ?? '');
$filename = $iduserttd;
$file_name = '../../imgttd/'.$filename.'.png';
$filename2 = $iduserttd.'.png';
file_put_contents($file_name, $imagedata);
$result['status'] = 1;
$result['file_name'] = $file_name;
$cekdata1pp = "SELECT * FROM pengurus WHERE nip = '$iduserttd'";
$ada1pp = mysqli_query($conn, $cekdata1pp);
if ($ada1pp && mysqli_num_rows($ada1pp) > 0) {      
    $querypp = "UPDATE pengurus SET ttd='$filename2' WHERE nip = '$iduserttd'";	
    mysqli_query($conn, $querypp);
}
echo json_encode($result);
?>
