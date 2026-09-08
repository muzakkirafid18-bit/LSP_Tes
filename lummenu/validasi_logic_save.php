<?php
if (!function_exists('esc_sql')) {
    function esc_sql($conn, $val) { return mysqli_real_escape_string($conn, (string)$val); }
}

$idskemalsp  = esc_sql($conn, $_POST['idskemalsp'] ?? '');
$idasesilsp  = esc_sql($conn, $_POST['idasesilsp'] ?? '');
$tgllsp      = esc_sql($conn, $_POST['tgllsp'] ?? '');
$emailusrlsp = esc_sql($conn, $_POST['emailusrlsp'] ?? '');
$lsprekom    = esc_sql($conn, $_POST['lsprekom'] ?? '');
$cttlsp      = esc_sql($conn, $_POST['cttlsp'] ?? '');
$na          = (int)($_POST['na'] ?? 0);

// Simpan ceklis syarat
for ($i = 0; $i < $na; $i++) {
    $idsyarat = esc_sql($conn, $_POST['idsyaratsatu'.$i] ?? '');
    $nilai    = esc_sql($conn, $_POST['adamsy'.$i] ?? '');

    $cek = mysqli_query($conn, "SELECT * FROM syaratsiswa WHERE idskema='$idskemalsp' AND idasesi='$idasesilsp' AND idsyarat='$idsyarat'");
    if ($cek && mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE syaratsiswa SET ceklista='$nilai' WHERE idskema='$idskemalsp' AND idasesi='$idasesilsp' AND idsyarat='$idsyarat'");
    } else {
        $qInsSyarat = "INSERT INTO syaratsiswa (idskema, idasesi, idsyarat, ceklista) VALUES ('$idskemalsp', '$idasesilsp', '$idsyarat', '$nilai')";
        $resSyarat = mysqli_query($conn, $qInsSyarat);
        if (!$resSyarat) {
            mysqli_query($conn, "INSERT INTO syaratsiswa (idsyaratsiswa, idskema, idasesi, idsyarat, ceklista) SELECT COALESCE(MAX(idsyaratsiswa), 0) + 1, '$idskemalsp', '$idasesilsp', '$idsyarat', '$nilai' FROM syaratsiswa");
        }
    }
}

// Simpan rekomendasi
$tgl_today = date('Y-m-d');
$cekrek = mysqli_query($conn, "SELECT * FROM rekomendasi WHERE namarekom='apl1lsp' AND idskema='$idskemalsp' AND idasesi='$idasesilsp'");
if ($cekrek && mysqli_num_rows($cekrek) > 0) {
    mysqli_query($conn, "UPDATE rekomendasi SET rekom='$lsprekom', catatan='$cttlsp', tanggal='$tgl_today' WHERE namarekom='apl1lsp' AND idskema='$idskemalsp' AND idasesi='$idasesilsp'");
} else {
    $qInsRek = "INSERT INTO rekomendasi (id, namarekom, idskema, idasesi, rekom, catatan, tanggal) SELECT COALESCE(MAX(id), 0) + 1, 'apl1lsp', '$idskemalsp', '$idasesilsp', '$lsprekom', '$cttlsp', '$tgl_today' FROM rekomendasi";
    $resRek = mysqli_query($conn, $qInsRek);
    if (!$resRek) {
        mysqli_query($conn, "INSERT INTO rekomendasi (namarekom, idskema, idasesi, rekom, catatan, tanggal) VALUES ('apl1lsp', '$idskemalsp', '$idasesilsp', '$lsprekom', '$cttlsp', '$tgl_today')");
    }
}

// Update validasi APL1
$validasi = ($lsprekom == 'L') ? 'Y' : 'N';
mysqli_query($conn, "UPDATE apl1 SET validasiapl1='$validasi' WHERE email='$emailusrlsp'");
$statusapl1 = ($lsprekom == 'L') ? 'Y' : 'N';
mysqli_query($conn, "UPDATE skemasiswa SET statusapl1='$statusapl1' WHERE emailsiswa='$emailusrlsp' AND idskema='$idskemalsp'");

echo "<div class='card' style='margin:20px 0'><div class='alert-box alert-success'><i class='fas fa-circle-check'></i> <strong>Validasi APL.01 berhasil disimpan!</strong></div><a href='validasiapl1lsp.php' class='btn btn-secondary btn-sm'><i class='fas fa-arrow-left'></i> Kembali ke Daftar Validasi</a></div>";
?>