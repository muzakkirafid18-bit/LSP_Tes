<?php
require_once __DIR__ . '/mysql_compat.php';
$host = "localhost";   // biasanya "localhost"
$user = "root";        // default user XAMPP
$pass = "";            // default password XAMPP kosong
$db   = "lsp_p1_db";   // ganti sesuai nama database lo

// Alias untuk halaman lama yang masih memakai nama variabel koneksi lama.
// Semua halaman sekarang mengambil konfigurasi dari satu sumber yang sama.
$hostdb     = $host;
$userdb     = $user;
$passworddb = $pass;
$database   = $db;

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>
