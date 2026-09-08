<?php 
session_start();
error_reporting(0);
include "../../lsp_koneksi.php";

$nim = isset($_POST['usern']) ? mysqli_real_escape_string($conn, trim($_POST['usern'])) : '';
$kdmdl = isset($_POST['kodemd']) ? mysqli_real_escape_string($conn, trim($_POST['kodemd'])) : '';
$vwreal = isset($_POST['wreal']) ? mysqli_real_escape_string($conn, trim($_POST['wreal'])) : '';
$mmenit = isset($_POST['vmenit']) ? trim($_POST['vmenit']) : '';

$vamenit = 30;
if (!empty($mmenit) && strpos($mmenit, ':') !== false) {
    $arr = explode(":", $mmenit);
    $vamenit = ((int)($arr[0] ?? 0) * 60) + (int)($arr[1] ?? 0);
}

if (!empty($nim) && !empty($kdmdl)) {
    mysqli_query($conn, "UPDATE pertanyaanbck SET menit='$vamenit', waktureal='$vwreal' WHERE nim='$nim' AND kd_modul='$kdmdl'");
}
echo "OK";
?>
