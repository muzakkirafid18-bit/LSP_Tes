<!DOCTYPE html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kelola Unit — LSP SMKN 1 Cibinong</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="js/lumino.glyphs.js"></script>

<?php
include "../lsp_koneksi.php";
$today = date('d F Y');
$current_time = date('H:i');

if (empty($_SESSION['username']) AND empty($_SESSION['password'])) {
    echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<i class='fas fa-lock' style='font-size:2rem;color:#EF4444;margin-bottom:16px'></i>";
    echo "<h3 style='font-family:Plus Jakarta Sans,sans-serif;color:#1A2E3B'>Anda Harus Login Dahulu!</h3>";
    echo "<a href='../lsp_login.php' style='display:inline-block;margin-top:16px;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>";
    echo "</div>"; exit;
}
if (!in_array($_SESSION['level'], ['lsp','admin'])) {
    echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<i class='fas fa-ban' style='font-size:2rem;color:#EF4444;margin-bottom:16px'></i>";
    echo "<h3 style='font-family:Plus Jakarta Sans,sans-serif;color:#1A2E3B'>Anda Tidak Punya Hak Akses!</h3>";
    echo "<a href='../lsp_login.php' style='display:inline-block;margin-top:16px;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>";
    echo "</div>"; exit;
}

if(isset($_SESSION['username'])) { $uname = $_SESSION['username']; }
$l = "SELECT * FROM lsp_usertbl WHERE email='".$uname."'";
$resultx = mysqli_query($conn, $l);
$hasilx  = mysqli_fetch_array($resultx, MYSQLI_ASSOC);
$namax   = $hasilx['nama'] ?? 'Admin';
?>

<style>
    :root {
        --teal:       #3BBFBF;
        --teal-dark:  #2A9999;
        --teal-light: #E8F8F8;
        --teal-glow:  rgba(59,191,191,.18);
        --navy:       #0F2A3A;
        --navy-soft:  #1E4060;
        --white:      #FFFFFF;
        --off:        #F4F8FA;
        --border:     #DDE8ED;
        --text-main:  #1A2E3B;
        --text-sub:   #5A7384;
        --text-muted: #92A9B5;
        --green:      #22C55E;
        --red:        #EF4444;
        --orange:     #F97316;
        --sidebar-w:  260px;
        --radius:     14px;
        --shadow:     0 2px 16px rgba(15,42,58,.07);
        --shadow-md:  0 4px 32px rgba(15,42,58,.13);
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 15px; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--off); color: var(--text-main); display: flex; min-height: 100vh; overflow-x: hidden; }

    .sidebar {
        width: var(--sidebar-w);
        height: 100vh;
        max-height: 100vh;
        bottom: 0;
        background: var(--navy);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .sidebar::-webkit-scrollbar { width: 4px; }
    .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }
    .sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.35); }
    .sidebar-logo { padding: 28px 24px 20px; border-bottom: 1px solid rgba(255,255,255,.07); display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .logo-box { width: 42px; height: 42px; background: var(--teal); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; color: #fff; flex-shrink: 0; }
    .logo-text strong { display: block; color: #fff; font-size: .95rem; font-weight: 700; }
    .logo-text span { color: var(--teal); font-size: .72rem; font-weight: 500; letter-spacing: .5px; }
    .sidebar-nav { padding: 16px 12px; flex: 1; }
    .nav-label { color: rgba(255,255,255,.3); font-size: .67rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; padding: 12px 12px 6px; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 10px; color: rgba(255,255,255,.55); text-decoration: none; font-size: .875rem; font-weight: 500; transition: all .2s; margin-bottom: 2px; }
    .nav-item:hover { background: rgba(255,255,255,.07); color: #fff; text-decoration: none; }
    .nav-item.active { background: var(--teal); color: #fff; box-shadow: 0 4px 12px rgba(59,191,191,.35); }
    .nav-item i { width: 18px; text-align: center; font-size: .9rem; flex-shrink: 0; }
    .sidebar-footer { padding: 16px 14px; border-top: 1px solid rgba(255,255,255,.07); flex-shrink: 0; }
    .user-card { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; background: rgba(255,255,255,.05); }
    .user-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--teal); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; color: #fff; flex-shrink: 0; }
    .user-info strong { display: block; color: #fff; font-size: .82rem; }
    .user-info span { color: var(--teal); font-size: .72rem; }
    .btn-logout { margin-left: auto; color: rgba(255,255,255,.35); background: none; border: none; cursor: pointer; font-size: .85rem; transition: color .2s; }
    .btn-logout:hover { color: var(--red); }

    /* MAIN */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    /* TOPBAR */
    .topbar { background: #fff; border-bottom: 1px solid var(--border); padding: 0 32px; height: 68px; display: flex; align-items: center; gap: 16px; position: sticky; top: 0; z-index: 50; }
    .topbar-title { font-size: 1.1rem; font-weight: 700; flex: 1; }
    .topbar-title span { color: var(--text-sub); font-weight: 400; font-size: .875rem; margin-left: 8px; }
    .topbar-actions { display: flex; align-items: center; gap: 10px; }
    .date-chip { background: var(--teal-light); color: var(--teal-dark); font-size: .78rem; font-weight: 600; padding: 6px 14px; border-radius: 8px; display: flex; align-items: center; gap: 6px; }
    .icon-btn { width: 38px; height: 38px; border-radius: 10px; border: 1.5px solid var(--border); background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-sub); font-size: .9rem; transition: all .2s; }
    .icon-btn:hover { border-color: var(--teal); color: var(--teal); }

    /* CONTENT */
    .content { padding: 32px; display: flex; flex-direction: column; gap: 24px; }

    /* CARD */
    .card { background: #fff; border-radius: var(--radius); padding: 28px; box-shadow: var(--shadow); animation: fadeUp .4s ease both; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .section-head h3 { font-size: 1.05rem; font-weight: 700; }
    .section-head p { font-size: .78rem; color: var(--text-sub); margin-top: 2px; }

    /* ALERT */
    .alert-box { padding: 12px 18px; border-radius: 10px; font-size: .85rem; font-weight: 500; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
    .alert-success { background: #DCFCE7; color: #15803D; border: 1px solid #86EFAC; }
    .alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FCA5A5; }
    .alert-warning { background: #FEFCE8; color: #A16207; border: 1px solid #FDE68A; }

    /* FORM */
    .form-grid { display: grid; grid-template-columns: 160px 1fr; gap: 14px; align-items: start; margin-bottom: 14px; }
    .form-label { font-size: .82rem; font-weight: 600; color: var(--text-sub); padding-top: 10px; }
    .form-input { width: 100%; font-size: .875rem; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-main); background: var(--off); transition: border-color .2s, box-shadow .2s; outline: none; }
    .form-input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px var(--teal-glow); background: #fff; }
    select.form-input { cursor: pointer; }
    textarea.form-input { resize: vertical; min-height: 80px; }
    .form-actions { display: flex; gap: 10px; margin-top: 8px; padding-top: 16px; border-top: 1px solid var(--border); }
    .divider-line { height: 1px; background: var(--border); margin: 20px 0; }

    /* BUTTONS */
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; border-radius: 10px; font-size: .85rem; font-weight: 600; cursor: pointer; text-decoration: none; border: 1.5px solid transparent; transition: all .2s; font-family: 'Plus Jakarta Sans', sans-serif; }
    .btn:hover { text-decoration: none; }
    .btn-primary { background: var(--teal); color: #fff; box-shadow: 0 2px 8px rgba(59,191,191,.3); }
    .btn-primary:hover { background: var(--teal-dark); color: #fff; }
    .btn-secondary { background: #fff; color: var(--text-main); border-color: var(--border); }
    .btn-secondary:hover { border-color: var(--teal); color: var(--teal-dark); background: var(--teal-light); }
    .btn-warning { background: #FFF7ED; color: #C2410C; border-color: #FDBA74; }
    .btn-warning:hover { background: var(--orange); color: #fff; border-color: var(--orange); }
    .btn-danger { background: #FEF2F2; color: #B91C1C; border-color: #FCA5A5; }
    .btn-danger:hover { background: var(--red); color: #fff; border-color: var(--red); }
    .btn-info { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
    .btn-info:hover { background: #1D4ED8; color: #fff; }
    .btn-sm { padding: 6px 14px; font-size: .78rem; border-radius: 8px; }

    /* TABLE */
    .tbl-wrap { overflow-x: auto; }
    .tbl { width: 100%; border-collapse: collapse; }
    .tbl thead tr { background: var(--navy); }
    .tbl th { text-align: left; padding: 12px 14px; font-size: .72rem; font-weight: 700; letter-spacing: .7px; text-transform: uppercase; color: rgba(255,255,255,.75); }
    .tbl th:first-child { border-radius: 8px 0 0 8px; }
    .tbl th:last-child  { border-radius: 0 8px 8px 0; }
    .tbl td { padding: 12px 14px; font-size: .83rem; border-bottom: 1px solid var(--off); vertical-align: middle; }
    .tbl tr:last-child td { border-bottom: none; }
    .tbl tbody tr:hover td { background: #F0F9F9; }

    /* BADGES */
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 6px; font-size: .72rem; font-weight: 700; }
    .badge-teal   { background: var(--teal-light); color: var(--teal-dark); }
    .badge-navy   { background: #EFF6FF; color: #1D4ED8; }
    .badge-green  { background: #DCFCE7; color: #15803D; }
    .badge-gray   { background: #F1F5F9; color: #64748B; }
    .row-num { font-family: 'DM Mono', monospace; color: var(--text-muted); font-size: .75rem; }
    .action-group { display: flex; gap: 6px; }

    /* SUB ELEMEN TABLE */
    .sub-tbl { width: 100%; border-collapse: collapse; margin-top: 12px; }
    .sub-tbl th { background: var(--navy-soft); color: rgba(255,255,255,.8); padding: 9px 12px; font-size: .7rem; font-weight: 700; text-transform: uppercase; }
    .sub-tbl td { padding: 9px 12px; border-bottom: 1px solid var(--off); font-size: .82rem; }

    /* EMPTY STATE */
    .empty-state { text-align: center; padding: 48px 24px; color: var(--text-muted); }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; opacity: .4; display: block; }

    /* FOOTER */
    .page-footer { padding: 20px 32px; border-top: 1px solid var(--border); background: #fff; display: flex; align-items: center; justify-content: space-between; font-size: .78rem; color: var(--text-muted); margin-top: auto; }
    .page-footer strong { color: var(--teal-dark); }

    @media (max-width: 900px) {
        .main { margin-left: 0; }
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-box" style="width:50px;height:50px;background:white;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,0.1);">
            <img src="../images/lsplogosmkn1.png" style="width:100%;height:100%;object-fit:contain;">
        </div>
        <div class="logo-text">
            <strong>LSP</strong>
            <span>SMKN 1 CIBINONG</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboardbaru.php" class="nav-item"><i class="fas fa-gauge-high"></i> Dashboard</a>
        <div class="nav-label">Manajemen Data</div>
        <a href="inputskema.php" class="nav-item"><i class="fas fa-sitemap"></i> Kelola Skema</a>
        <a href="inputunit.php" class="nav-item active"><i class="fas fa-cubes"></i> Kelola Unit</a>
        <a href="inputasesor.php" class="nav-item"><i class="fas fa-user-tie"></i> Kelola Asesor</a>
        <a href="inputpeserta.php" class="nav-item"><i class="fas fa-users"></i> Kelola Peserta</a>
        <a href="inputelemen.php" class="nav-item"><i class="fas fa-star"></i> Kelola Kompetensi</a>
        <a href="inputtempattuk.php" class="nav-item"><i class="fas fa-building"></i> Kelola Tempat TUK</a>
        <div class="nav-label">Input Data</div>
        <a href="inputsyarat.php" class="nav-item"><i class="fas fa-pen-to-square"></i> Input Persyaratan</a>
        <a href="inputkumpan.php" class="nav-item"><i class="fas fa-pen-to-square"></i> Input Umpan Balik</a>
        <a href="inputprosesasesmen.php" class="nav-item"><i class="fas fa-pen-to-square"></i> Input Proses Asesmen</a>
        <a href="inputpengurus.php" class="nav-item"><i class="fas fa-pen-to-square"></i> Input Pengurus</a>
        <div class="nav-label">Proses Uji</div>
        <a href="mapa.php" class="nav-item"><i class="fas fa-paperclip"></i> MAPA</a>
        <a href="settanggal.php" class="nav-item"><i class="fas fa-clock"></i> SET Tanggal</a>
        <a href="pemetaanasesor.php" class="nav-item"><i class="fas fa-calendar-days"></i> Atur Jadwal</a>
        <a href="inputpraktek.php" class="nav-item"><i class="fas fa-clipboard-check"></i> FR.IA.01 Ceklist Observasi</a>
        <a href="inputtestulis.php" class="nav-item"><i class="fas fa-file-lines"></i> FR.IA.05 Tes Tertulis</a>
        <div class="nav-label">Validasi & Monitor</div>
        <a href="validasiapl1lsp.php" class="nav-item"><i class="fas fa-check-double"></i> Validasi APL1</a>
        <a href="monitorasesi.php" class="nav-item"><i class="fas fa-desktop"></i> Monitoring</a>
        <a href="backupdata.php" class="nav-item"><i class="fas fa-download"></i> Backup Data</a>
        <div class="nav-label">Akun</div>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= strtoupper(substr($namax ?? 'AD', 0, 2)) ?></div>
            <div class="user-info">
                <strong><?= htmlspecialchars($namax ?? 'Admin') ?></strong>
                <span>LSP Admin</span>
            </div>
            <button class="btn-logout" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button>
        </div>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <header class="topbar">
        <div class="topbar-title">Kelola Unit Kompetensi <span>Manajemen data unit</span></div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i><?= $today ?></div>
            <button class="icon-btn"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">
<?php
$op = $_GET['op'] ?? '';
$pesan = ''; $pesan_tipe = '';

/* ================================================================ UPDATE ================================================================ */
if ($op == "update") {
    $idunit       = $_POST['idunit'] ?? '';
    $kodeunit     = trim($_POST['kodeunit'] ?? '');
    $namaunit     = trim($_POST['namaunit'] ?? '');
    $skema        = $_POST['skema'] ?? '';
    $ket          = $_POST['ket'] ?? '';
    $status       = $_POST['status'] ?? 'A';
    $kodeunitLama = trim($_POST['kodeunitLama'] ?? '');

    if ($kodeunit == $kodeunitLama) {
        $q = "UPDATE unit SET kodeunit='$kodeunit',namaunit='$namaunit',idskema='$skema',ket='$ket',status='$status' WHERE idunit='$idunit'";
        $h = mysqli_query($conn, $q);
    } else {
        $cek = mysqli_query($conn, "SELECT kodeunit FROM unit WHERE kodeunit='$kodeunit'");
        if (mysqli_num_rows($cek) > 0) { $h = false; $duplikat = true; }
        else {
            $q = "UPDATE unit SET kodeunit='$kodeunit',namaunit='$namaunit',idskema='$skema',ket='$ket',status='$status' WHERE idunit='$idunit'";
            $h = mysqli_query($conn, $q);
        }
    }
    $pesan = isset($duplikat) ? 'Gagal: kode unit sudah ada (duplikat).' : ($h ? 'Data unit berhasil diperbarui.' : 'Gagal memperbarui data: '.mysqli_error($conn));
    $pesan_tipe = ($h && !isset($duplikat)) ? 'success' : 'error';
    $op = '';
}
elseif ($op == "append") {
    $idskema  = $_POST['skema'] ?? '';
    $kodeunit = trim($_POST['kodeunit'] ?? '');
    $namaunit = trim($_POST['namaunit'] ?? '');
    $ket      = $_POST['ket'] ?? '';
    $status   = $_POST['status'] ?? 'A';
    $cek = mysqli_query($conn, "SELECT kodeunit FROM unit WHERE kodeunit='$kodeunit'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = 'Gagal: kode unit sudah ada (duplikat).';
        $pesan_tipe = 'error';
    } elseif (!empty($kodeunit)) {
        $h = mysqli_query($conn, "INSERT INTO unit (idskema, kodeunit, namaunit, ket, status) VALUES ('$idskema','$kodeunit','$namaunit','$ket','$status')");
        $pesan = $h ? 'Data unit berhasil ditambahkan.' : 'Gagal menambahkan data: '.mysqli_error($conn);
        $pesan_tipe = $h ? 'success' : 'error';
    }
    $op = '';
}
elseif ($op == "deletepost") {
    $idunit = $_POST['idunit'] ?? '';
    $h = mysqli_query($conn, "DELETE FROM unit WHERE idunit='$idunit'");
    $pesan = $h ? 'Data unit berhasil dihapus.' : 'Gagal menghapus data.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}
?>

<?php if ($pesan): ?>
<div class="alert-box alert-<?= $pesan_tipe === 'success' ? 'success' : 'error' ?>">
    <i class="fas <?= $pesan_tipe === 'success' ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
    <?= htmlspecialchars($pesan) ?>
</div>
<?php endif; ?>

<?php
/* ================================================================ FORM EDIT ================================================================ */
if ($op == 'edit'):
    $idunit = $_GET['idunit'] ?? '';
    $h = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM unit WHERE idunit='$idunit'"));
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-pen-to-square" style="color:var(--teal);margin-right:8px"></i>Edit Unit Kompetensi</h3><p>Ubah data unit yang sudah ada</p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=update">
        <input type="hidden" name="idunit" value="<?= $h['idunit'] ?>">
        <input type="hidden" name="kodeunitLama" value="<?= $h['kodeunit'] ?>">
        <div class="form-grid">
            <div class="form-label">Skema <span style="color:var(--red)">*</span></div>
            <select name="skema" class="form-input">
                <?php $ts = mysqli_query($conn, "SELECT * FROM skema ORDER BY idskema");
                while ($r = mysqli_fetch_array($ts)) {
                    $sel = ($h['idskema'] == $r['idskema']) ? 'selected' : '';
                    echo "<option value='{$r['idskema']}' $sel>{$r['namaskema']}</option>";
                } ?>
            </select>
            <div class="form-label">Kode Unit <span style="color:var(--red)">*</span></div>
            <input type="text" name="kodeunit" class="form-input" value="<?= htmlspecialchars($h['kodeunit']) ?>" placeholder="Contoh: J.62SAM00.001.2">
            <div class="form-label">Nama Unit <span style="color:var(--red)">*</span></div>
            <input type="text" name="namaunit" class="form-input" value="<?= htmlspecialchars($h['namaunit']) ?>" placeholder="Nama lengkap unit kompetensi">
            <div class="form-label">Keterangan</div>
            <textarea name="ket" class="form-input"><?= htmlspecialchars($h['ket']) ?></textarea>
            <div class="form-label">Status</div>
            <select name="status" class="form-input">
                <option value="Y" <?= $h['status']=='Y'?'selected':'' ?>>Aktif</option>
                <option value="N" <?= $h['status']=='N'?'selected':'' ?>>Belum Aktif</option>
            </select>
        </div>
        <div class="divider-line"></div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Perubahan</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ================================================================ FORM TAMBAH ================================================================ */
elseif ($op == 'tambah'):
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-plus-circle" style="color:var(--teal);margin-right:8px"></i>Tambah Unit Kompetensi</h3><p>Isi data unit baru dengan lengkap</p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=append">
        <div class="form-grid">
            <div class="form-label">Skema <span style="color:var(--red)">*</span></div>
            <select name="skema" class="form-input" autofocus>
                <?php $ts = mysqli_query($conn, "SELECT * FROM skema ORDER BY idskema");
                while ($r = mysqli_fetch_array($ts)) {
                    echo "<option value='{$r['idskema']}'>{$r['namaskema']}</option>";
                } ?>
            </select>
            <div class="form-label">Kode Unit <span style="color:var(--red)">*</span></div>
            <input type="text" name="kodeunit" class="form-input" placeholder="Contoh: J.62SAM00.001.2" required>
            <div class="form-label">Nama Unit <span style="color:var(--red)">*</span></div>
            <input type="text" name="namaunit" class="form-input" placeholder="Nama lengkap unit kompetensi" required>
            <div class="form-label">Keterangan</div>
            <textarea name="ket" class="form-input" placeholder="Keterangan tambahan (opsional)"></textarea>
            <div class="form-label">Status</div>
            <select name="status" class="form-input">
                <option value="Y">Aktif</option>
                <option value="N">Belum Aktif</option>
            </select>
        </div>
        <div class="divider-line"></div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Simpan Unit</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ================================================================ KONFIRMASI HAPUS ================================================================ */
elseif ($op == 'delete'):
    $idunit = $_GET['idunit'] ?? '';
    $hd = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM unit WHERE idunit='$idunit'"));
    $ceke = mysqli_query($conn, "SELECT idunit FROM elemen WHERE idunit='$idunit'");
    $adaElemen = mysqli_num_rows($ceke) > 0;
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-trash" style="color:var(--red);margin-right:8px"></i>Konfirmasi Hapus</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <?php if ($adaElemen): ?>
    <div class="alert-box alert-warning">
        <i class="fas fa-triangle-exclamation"></i>
        <strong>Tidak dapat dihapus!</strong> Unit ini sudah memiliki elemen kompetensi. Hapus elemen terlebih dahulu.
    </div>
    <?php else: ?>
    <div class="alert-box alert-error">
        <i class="fas fa-circle-xmark"></i>
        Yakin ingin menghapus unit <strong><?= htmlspecialchars($hd['kodeunit'] ?? '') ?> — <?= htmlspecialchars($hd['namaunit'] ?? '') ?></strong>?
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=deletepost">
        <input type="hidden" name="idunit" value="<?= $idunit ?>">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php
/* ================================================================ ELEMEN & SUBELEMEN ================================================================ */
elseif ($op == 'elemendansub'):
    $idunitceka = $_GET['idunitcek'] ?? '';
    $unitdata = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM unit WHERE idunit='$idunitceka'"));
    $elemen_result = mysqli_query($conn, "SELECT * FROM elemen WHERE idunit='$idunitceka' ORDER BY idelemen");
    $sub_result    = mysqli_query($conn, "SELECT * FROM subelemen WHERE idunit='$idunitceka' ORDER BY idelemen");
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-layer-group" style="color:var(--teal);margin-right:8px"></i>Elemen & Sub Elemen</h3>
            <p><span class="badge badge-teal"><?= htmlspecialchars($unitdata['kodeunit'] ?? '') ?></span>&nbsp; <?= htmlspecialchars($unitdata['namaunit'] ?? '') ?></p>
        </div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <p style="font-size:.85rem;font-weight:700;color:var(--text-sub);margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px">Elemen Kompetensi</p>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr><th>ID Elemen</th><th>Kode Elemen</th><th>Nama Elemen</th></tr></thead>
            <tbody>
            <?php if ($elemen_result && mysqli_num_rows($elemen_result) > 0): ?>
                <?php while ($e = mysqli_fetch_array($elemen_result)): ?>
                <tr>
                    <td class="row-num"><?= $e['idelemen'] ?></td>
                    <td><span class="badge badge-navy"><?= htmlspecialchars($e['kodeelemen']) ?></span></td>
                    <td><?= htmlspecialchars($e['namaelemen']) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align:center;color:var(--text-muted)">Belum ada elemen untuk unit ini.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="divider-line"></div>
    <p style="font-size:.85rem;font-weight:700;color:var(--text-sub);margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px">Sub Elemen / KUK</p>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr><th>ID Elemen</th><th>Pertanyaan / KUK</th><th style="width:100px;text-align:center">Aksi</th></tr></thead>
            <tbody>
            <?php if ($sub_result && mysqli_num_rows($sub_result) > 0): ?>
                <?php while ($s = mysqli_fetch_array($sub_result)): ?>
                <tr>
                    <td class="row-num"><?= $s['idelemen'] ?></td>
                    <td style="font-size:.82rem"><?= htmlspecialchars($s['pertanyaan']) ?></td>
                    <td style="text-align:center">
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=deletesubelemenunit&idsubelemenunit=<?= $s['idsubelemen'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus sub elemen ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align:center;color:var(--text-muted)">Belum ada sub elemen untuk unit ini.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
/* ================================================================ IMPORT ================================================================ */
elseif ($op == 'import'):
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-file-excel" style="color:#16A34A;margin-right:8px"></i>Import Unit dari Excel</h3><p>Upload file Excel dengan format: ID Skema | Kode Unit | Nama Unit | Keterangan</p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>?op=postuploadunit">
        <div class="form-grid">
            <div class="form-label">Pilih Skema</div>
            <select name="iskema" class="form-input">
                <?php $ti = mysqli_query($conn, "SELECT idskema, namaskema FROM skema ORDER BY idskema");
                while ($ri = mysqli_fetch_array($ti)) {
                    echo "<option value='{$ri['idskema']}'>{$ri['namaskema']}</option>";
                } ?>
            </select>
            <div class="form-label">File Excel</div>
            <div>
                <input type="file" name="uploadedfile" style="display:block;margin-bottom:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload & Import</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ================================================================ DELETE SUBELEMEN ================================================================ */
elseif ($op == 'deletesubelemenunit'):
    $idsubelemenunit = $_GET['idsubelemenunit'] ?? '';
    $datase = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM subelemen WHERE idsubelemen='$idsubelemenunit'"));
    $cekApl2 = mysqli_query($conn, "SELECT idsubelemen FROM apl2 WHERE idsubelemen='$idsubelemenunit'");
    if ($cekApl2 && mysqli_num_rows($cekApl2) > 0):
?>
<div class="card">
    <div class="alert-box alert-warning">
        <i class="fas fa-triangle-exclamation"></i>
        <strong>Tidak dapat dihapus!</strong> Sub elemen ini sudah digunakan peserta di APL2.
    </div>
    <a href="javascript:history.back()" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>
<?php else: ?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-trash" style="color:var(--red);margin-right:8px"></i>Hapus Sub Elemen</h3></div>
    <div class="alert-box alert-error">
        <i class="fas fa-circle-xmark"></i>
        Yakin menghapus: <strong><?= htmlspecialchars($datase['pertanyaan'] ?? '') ?></strong>?
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=deletepertanyaanseunit">
        <input type="hidden" name="idsubelemenseunit" value="<?= $datase['idsubelemen'] ?? '' ?>">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
            <a href="javascript:history.back()" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>
<?php endif; ?>

<?php
/* ================================================================ DELETE PERTANYAAN SEUNIT ================================================================ */
elseif ($op == 'deletepertanyaanseunit'):
    $idse = $_POST['idsubelemenseunit'] ?? '';
    $del  = mysqli_query($conn, "DELETE FROM subelemen WHERE idsubelemen='$idse'");
?>
<div class="card">
    <div class="alert-box alert-<?= $del ? 'success' : 'error' ?>">
        <i class="fas <?= $del ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
        <?= $del ? 'Sub elemen berhasil dihapus.' : 'Gagal menghapus sub elemen.' ?>
    </div>
    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-primary"><i class="fas fa-list"></i> Kembali ke Daftar</a>
</div>

<?php
/* ================================================================ POSTUPLOAD ================================================================ */
elseif ($op == 'postuploadunit'):
    include "excel_reader2.php";
    $data    = new Spreadsheet_Excel_Reader($_FILES['uploadedfile']['tmp_name']);
    $baris   = $data->rowcount(0);
    $sukses  = 0; $gagal = 0; $duplikat_list = [];
    $iskema  = $_POST['iskema'];
    for ($i = 2; $i <= $baris; $i++) {
        $kdunit = $data->val($i, 2);
        $nmunit = $data->val($i, 3);
        $ket    = $data->val($i, 4);
        if (empty($kdunit)) break;
        $cek = mysqli_query($conn, "SELECT kodeunit FROM unit WHERE kodeunit='$kdunit'");
        if (mysqli_num_rows($cek) > 0) { $duplikat_list[] = $kdunit; continue; }
        $h = mysqli_query($conn, "INSERT INTO unit (idskema, kodeunit, namaunit, ket, status) VALUES ('$iskema','$kdunit','$nmunit','$ket','Y')");
        if ($h) $sukses++; else $gagal++;
    }
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-file-import" style="color:var(--teal);margin-right:8px"></i>Hasil Import</h3></div>
    <div class="alert-box alert-success"><i class="fas fa-circle-check"></i> <?= $sukses ?> unit berhasil diimport.</div>
    <?php if ($gagal): ?>
    <div class="alert-box alert-error"><i class="fas fa-circle-xmark"></i> <?= $gagal ?> unit gagal diimport.</div>
    <?php endif; ?>
    <?php if (!empty($duplikat_list)): ?>
    <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i> Duplikat: <?= implode(', ', $duplikat_list) ?></div>
    <?php endif; ?>
    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-primary"><i class="fas fa-list"></i> Kembali ke Daftar</a>
</div>

<?php
/* ================================================================ DAFTAR UTAMA ================================================================ */
else:
    $queryumain  = "SELECT * FROM unit";
    $hasilumain  = mysqli_query($conn, $queryumain);
    $total_unit  = $hasilumain ? mysqli_num_rows($hasilumain) : 0;
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-cubes" style="color:var(--teal);margin-right:8px"></i>Daftar Unit Kompetensi</h3>
            <p>Semua unit yang terdaftar di LSP SMKN 1 Cibinong</p>
        </div>
        <div style="display:flex;gap:10px">
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=import" class="btn btn-secondary btn-sm"><i class="fas fa-file-excel"></i> Import Excel</a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=tambah" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Unit</a>
        </div>
    </div>

    <div style="margin-bottom:16px">
        <span style="background:var(--teal-light);color:var(--teal-dark);padding:4px 12px;border-radius:6px;font-size:.78rem;font-weight:700">
            <?= $total_unit ?> Unit Terdaftar
        </span>
    </div>

    <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th style="width:70px">ID</th>
                    <th style="width:80px">Skema</th>
                    <th style="width:160px">Kode Unit</th>
                    <th>Nama Unit</th>
                    <th style="width:90px;text-align:center">Status</th>
                    <th style="width:180px;text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            if ($hasilumain && mysqli_num_rows($hasilumain) > 0):
                while ($d = mysqli_fetch_array($hasilumain)):
                    $st = $d['status'] == 'Y'
                        ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Aktif</span>'
                        : '<span class="badge badge-gray">Nonaktif</span>';
                ?>
                <tr>
                    <td class="row-num"><?= str_pad($no, 2, '0', STR_PAD_LEFT) ?></td>
                    <td>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=elemendansub&idunitcek=<?= $d['idunit'] ?>&idskemacek=<?= $d['idskema'] ?>"
                           class="btn btn-info btn-sm" title="Lihat Elemen & Sub">
                            <i class="fas fa-layer-group"></i> <?= $d['idunit'] ?>
                        </a>
                    </td>
                    <td><span class="badge badge-navy"><?= $d['idskema'] ?></span></td>
                    <td><span class="badge badge-teal" style="font-size:.78rem"><?= htmlspecialchars($d['kodeunit']) ?></span></td>
                    <td style="font-weight:500"><?= htmlspecialchars($d['namaunit']) ?></td>
                    <td style="text-align:center"><?= $st ?></td>
                    <td>
                        <div class="action-group" style="justify-content:center">
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=edit&idunit=<?= $d['idunit'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i> Edit</a>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=delete&idunit=<?= $d['idunit'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php $no++; endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:30px">Belum ada unit kompetensi.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

    </div><!-- /content -->
    <footer class="page-footer">
        <span>© <?= date('Y') ?> <strong>LSP SMKN 1 Cibinong</strong>. Semua hak dilindungi.</span>
        <span>Versi 1.0.0 &nbsp;·&nbsp; <?= $today ?>, <?= $current_time ?> WIB</span>
    </footer>
</div>
</body>
</html>