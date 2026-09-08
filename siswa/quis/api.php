<?php 
include "../../lsp_koneksi.php";

$vnoa = isset($_POST['vno']) ? (int)$_POST['vno'] : 1;
$vkda = isset($_POST['vkdm']) ? mysqli_real_escape_string($conn, trim($_POST['vkdm'])) : '';
$vnisa = isset($_POST['vnis']) ? mysqli_real_escape_string($conn, trim($_POST['vnis'])) : '';

$result = mysqli_query($conn, "SELECT * FROM pertanyaanbck WHERE kd_modul='$vkda' AND nim='$vnisa' AND nourut='$vnoa' LIMIT 1");
$array = $result ? mysqli_fetch_row($result) : array();    

echo json_encode($array);
?>
