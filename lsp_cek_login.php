<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "lsp_koneksi.php"; // pastiin file ini bikin $conn = mysqli_connect(...);

// Ambil input dari form
$usern = trim($_POST['username']);
$pass  = md5(trim($_POST['password']));

// Query ke tabel/view lsp_usertbl menggunakan prepared statement
$sql = "SELECT * FROM lsp_usertbl WHERE email = ? AND password = ?";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Prepare error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, 'ss', $usern, $pass);             
if (!mysqli_stmt_execute($stmt)) {
    die("Execute error: " . mysqli_stmt_error($stmt));
}

$login = mysqli_stmt_get_result($stmt);
if (!$login) {
    die("Result error: " . mysqli_error($conn));
}

$ketemu = mysqli_num_rows($login);
$r      = mysqli_fetch_array($login);

mysqli_stmt_close($stmt);

// Kalau username & password cocok
if ($ketemu > 0){
    $_SESSION['id_user']       = $r['id'];
    $_SESSION['username']      = $r['email'];
    $_SESSION['password']      = $r['password'];
    $_SESSION['nama_lengkap']  = $r['nama'];
    // $_SESSION['status']        = $r['status'];
    $_SESSION['level']         = $r['level']; // alias dari role

    // Arahkan sesuai role/level
    if($r['level'] == 'lsp'){
        header('Location: lummenu/inputskema.php');
        exit(); 
    } elseif($r['level'] == 'asesor'){
        header('Location: lummenu/validasiapl2.php');
        exit();
    } elseif($r['level'] == 'peserta'){ 
        header('Location: siswa/pilihskema.php?uidpes='.$r['email']);
        exit();
    } elseif($r['level'] == 'admin'){
        // admin belum di-handle di kode lama, tambahin disini
        header('Location: lummenu/inputskema.php');
        exit();
    } else {
        echo "Role tidak dikenali: ".$r['level'];
    }

} else {
    // Kalau login gagal
    echo "<link href=css/adminstyle.css rel=stylesheet type=text/css>";
    echo "<center>Login gagal! Username / Password salah<br>";
    echo "<a href=lsp_login.php><b>ULANGI LAGI</b></a></center>";
}
?>
