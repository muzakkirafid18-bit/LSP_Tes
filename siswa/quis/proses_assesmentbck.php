<?php 
session_start();
error_reporting(0);
include "../../lsp_koneksi.php";

$nid = isset($_POST['nid']) ? (int)$_POST['nid'] : 0;
$nim = isset($_POST['usern']) ? mysqli_real_escape_string($conn, trim($_POST['usern'])) : '';
$kdmdl = isset($_POST['kodemd']) ? mysqli_real_escape_string($conn, trim($_POST['kodemd'])) : '';
$jawaban = isset($_POST['jawaban']) ? mysqli_real_escape_string($conn, trim($_POST['jawaban'])) : '';
$lastnom = isset($_POST['ylastnom']) ? (int)$_POST['ylastnom'] : 1;

if ($nid > 0 && !empty($nim) && !empty($kdmdl)) {
    mysqli_query($conn, "UPDATE pertanyaanbck SET njawab='$jawaban' WHERE question_id='$nid' AND nim='$nim' AND kd_modul='$kdmdl'");
    mysqli_query($conn, "UPDATE pertanyaanbck SET lastnom='$lastnom' WHERE nim='$nim' AND kd_modul='$kdmdl'");
}
echo "OK";
?>
