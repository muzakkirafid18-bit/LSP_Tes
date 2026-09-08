<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
include "../../lsp_koneksi.php";

function esc_sql($conn, $value) {
    return mysqli_real_escape_string($conn, (string)$value);
}

// Ambil data dari POST
$nim = esc_sql($conn, trim($_POST['nim'] ?? $_SESSION['username'] ?? ''));
$kmdl = esc_sql($conn, trim($_POST['kmdl'] ?? $_SESSION['kd_modul'] ?? ''));
$kounter = (int)($_POST['kounter'] ?? 1);

// Look up student name
$nama = trim($_POST['nama'] ?? '');
if (empty($nama)) {
    $q_u = mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE email='$nim' LIMIT 1");
    $d_u = $q_u ? mysqli_fetch_array($q_u) : array();
    $nama = $d_u['nama'] ?? $_SESSION['nama_lengkap'] ?? $nim;
}
$nama_safe = esc_sql($conn, $nama);

// 1. Ambil semua soal yang dikerjakan si siswa
$resultxx = "SELECT * FROM pertanyaanbck WHERE kd_modul='$kmdl' AND nim='$nim'";
$resultxxx = mysqli_query($conn, $resultxx);
$cekjum = $resultxxx ? mysqli_num_rows($resultxxx) : 0;

if ($cekjum > 0) {
    $benar = 0;
    $banyak = 0;
    $jawabanabcd = "";
    $tanggall = date("Y-m-d H:i:s");
    $wreal = date("H:i:s");

    while($row = mysqli_fetch_array($resultxxx)) {
        $question_id = $row["question_id"];
        $answer = trim((string)$row["answer"]); // Kunci (Teks/Huruf)
        $njawab = trim((string)$row["njawab"]); // Pilihan user (A/B/C/D/E)
        
        // Cari Opsi Teks di tbloption atau pertanyaan
        $teks_jawaban_user = '';
        $q_opsi = mysqli_query($conn, "SELECT toption FROM tbloption WHERE question_id='$question_id' AND noption='$njawab' AND kd_modul='$kmdl' LIMIT 1");
        $d_opsi = $q_opsi ? mysqli_fetch_array($q_opsi) : array();
        if ($d_opsi && !empty($d_opsi['toption'])) {
            $teks_jawaban_user = trim((string)$d_opsi['toption']);
        } else {
            // Fallback: Cari di pertanyaan
            $q_p = mysqli_query($conn, "SELECT oa, alt_1, alt_2, alt_3, alt_4 FROM pertanyaan WHERE question_id='$question_id' LIMIT 1");
            if ($q_p && $row_p = mysqli_fetch_array($q_p)) {
                $map = array('A' => $row_p['oa'], 'B' => $row_p['alt_1'], 'C' => $row_p['alt_2'], 'D' => $row_p['alt_3'], 'E' => $row_p['alt_4']);
                $teks_jawaban_user = trim((string)($map[$njawab] ?? ''));
            }
        }

        $jawabanabcd .= ($njawab !== '' ? $njawab : '-') . ","; 
        $banyak++;

        // Bandingkan Teks atau Huruf
        if (!empty($njawab)) {
            if (strcasecmp($teks_jawaban_user, $answer) === 0 || strcasecmp($njawab, $answer) === 0) {
                $benar++;
            }
        }
    }

    // 2. Ambil data Modul (KKM & Nama Modul)
    $q_modul = mysqli_query($conn, "SELECT * FROM modul WHERE kd_modul='$kmdl' LIMIT 1");
    $d_modul = $q_modul ? mysqli_fetch_array($q_modul) : array();
    
    $jumlah_soal = $banyak;
    $salah = $jumlah_soal - $benar;
    
    // Hitung Nilai
    $nilai = ($jumlah_soal > 0) ? round((100 / $jumlah_soal) * $benar, 2) : 0;
    
    $kkm = (isset($d_modul["kkm"]) && $d_modul["kkm"] !== '') ? (int)$d_modul["kkm"] : 75;
    $modul_name = esc_sql($conn, $d_modul["modul"] ?? 'Uji Kompetensi');

    // 3. Simpan ke tabel Grade (Hasil Akhir)
    $simok = "INSERT INTO grade (kd_modul, modul, nim, nama, salah, benar, jumlah_soal, grade, tanggal, kkm, ujianke, waktureal) 
              VALUES ('$kmdl', '$modul_name', '$nim', '$nama_safe', '$salah', '$benar', '$jumlah_soal', '$nilai', '$tanggall', '$kkm', '$kounter', '$wreal')";
    
    if(mysqli_query($conn, $simok)) {
        // Simpan Log Jawaban
        $que = "INSERT INTO jawabanabc (nis, nama, jawaban, ket, tgl, waktureal) 
                VALUES ('$nim', '$nama_safe', '$jawabanabcd', '$kmdl', '$tanggall', '$wreal')";
        @mysqli_query($conn, $que);

        // Catat bahwa siswa sudah ujian modul ini
        @mysqli_query($conn, "INSERT INTO gradealias (kodemodul, nim) VALUES ('$kmdl', '$nim')");

        // Hapus data sementara di pertanyaanbck
        @mysqli_query($conn, "DELETE FROM pertanyaanbck WHERE kd_modul='$kmdl' AND nim='$nim'");

        header("Location: view_grade2.php");
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan hasil ujian: " . addslashes(mysqli_error($conn)) . "'); window.location.href='view_grade2.php';</script>";
    }
} else {
    header("Location: view_grade2.php");
    exit;
}
?>
