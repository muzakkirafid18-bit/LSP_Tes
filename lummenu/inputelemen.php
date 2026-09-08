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
<title>Kelola Elemen — LSP SMKN 1 Cibinong</title>
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
    .sidebar-logo { padding:28px 24px 20px; border-bottom:1px solid rgba(255,255,255,.07); display:flex; align-items:center; gap:12px; flex-shrink:0; }
    .logo-box { width:42px; height:42px; background:var(--teal); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:16px; color:#fff; flex-shrink:0; }
    .logo-text strong { display:block; color:#fff; font-size:.95rem; font-weight:700; }
    .logo-text span { color:var(--teal); font-size:.72rem; font-weight:500; letter-spacing:.5px; }
    .sidebar-nav { padding:16px 12px; flex:1; }
    .nav-label { color:rgba(255,255,255,.3); font-size:.67rem; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; padding:12px 12px 6px; }
    .nav-item { display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:10px; color:rgba(255,255,255,.55); text-decoration:none; font-size:.875rem; font-weight:500; transition:all .2s; margin-bottom:2px; }
    .nav-item:hover { background:rgba(255,255,255,.07); color:#fff; text-decoration:none; }
    .nav-item.active { background:var(--teal); color:#fff; box-shadow:0 4px 12px rgba(59,191,191,.35); }
    .nav-item i { width:18px; text-align:center; font-size:.9rem; flex-shrink:0; }
    .sidebar-footer { padding:16px 14px; border-top:1px solid rgba(255,255,255,.07); flex-shrink:0; }
    .user-card { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; background:rgba(255,255,255,.05); }
    .user-avatar { width:36px; height:36px; border-radius:50%; background:var(--teal); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.85rem; color:#fff; flex-shrink:0; }
    .user-info strong { display:block; color:#fff; font-size:.82rem; }
    .user-info span { color:var(--teal); font-size:.72rem; }
    .btn-logout { margin-left:auto; color:rgba(255,255,255,.35); background:none; border:none; cursor:pointer; font-size:.85rem; transition:color .2s; }
    .btn-logout:hover { color:var(--red); }

    /* MAIN */
    .main { margin-left:var(--sidebar-w); flex:1; display:flex; flex-direction:column; min-height:100vh; }
    .topbar { background:#fff; border-bottom:1px solid var(--border); padding:0 32px; height:68px; display:flex; align-items:center; gap:16px; position:sticky; top:0; z-index:50; }
    .topbar-title { font-size:1.1rem; font-weight:700; flex:1; }
    .topbar-title span { color:var(--text-sub); font-weight:400; font-size:.875rem; margin-left:8px; }
    .topbar-actions { display:flex; align-items:center; gap:10px; }
    .date-chip { background:var(--teal-light); color:var(--teal-dark); font-size:.78rem; font-weight:600; padding:6px 14px; border-radius:8px; display:flex; align-items:center; gap:6px; }
    .icon-btn { width:38px; height:38px; border-radius:10px; border:1.5px solid var(--border); background:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--text-sub); transition:all .2s; }
    .icon-btn:hover { border-color:var(--teal); color:var(--teal); }

    /* CONTENT */
    .content { padding:32px; display:flex; flex-direction:column; gap:24px; }
    .card { background:#fff; border-radius:var(--radius); padding:28px; box-shadow:var(--shadow); animation:fadeUp .4s ease both; }
    @keyframes fadeUp { from{opacity:0;transform:translateY(16px);} to{opacity:1;transform:translateY(0);} }
    .section-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
    .section-head h3 { font-size:1.05rem; font-weight:700; }
    .section-head p { font-size:.78rem; color:var(--text-sub); margin-top:2px; }

    /* ALERT */
    .alert-box { padding:12px 18px; border-radius:10px; font-size:.85rem; font-weight:500; display:flex; align-items:flex-start; gap:10px; margin-bottom:20px; }
    .alert-success { background:#DCFCE7; color:#15803D; border:1px solid #86EFAC; }
    .alert-error   { background:#FEF2F2; color:#B91C1C; border:1px solid #FCA5A5; }
    .alert-warning { background:#FEFCE8; color:#A16207; border:1px solid #FDE68A; }

    /* FORM */
    .form-grid { display:grid; grid-template-columns:170px 1fr; gap:13px; align-items:start; margin-bottom:13px; }
    .form-label { font-size:.82rem; font-weight:600; color:var(--text-sub); padding-top:10px; }
    .form-input { width:100%; font-size:.875rem; padding:10px 14px; border:1.5px solid var(--border); border-radius:10px; font-family:'Plus Jakarta Sans',sans-serif; color:var(--text-main); background:var(--off); transition:border-color .2s,box-shadow .2s; outline:none; }
    .form-input:focus { border-color:var(--teal); box-shadow:0 0 0 3px var(--teal-glow); background:#fff; }
    select.form-input { cursor:pointer; }
    textarea.form-input { resize:vertical; min-height:80px; }
    .form-hint { font-size:.72rem; color:var(--text-muted); margin-top:4px; }
    .form-actions { display:flex; gap:10px; margin-top:8px; padding-top:16px; border-top:1px solid var(--border); }
    .divider-line { height:1px; background:var(--border); margin:20px 0; }

    /* BUTTONS */
    .btn { display:inline-flex; align-items:center; gap:8px; padding:9px 20px; border-radius:10px; font-size:.85rem; font-weight:600; cursor:pointer; text-decoration:none; border:1.5px solid transparent; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif; }
    .btn:hover { text-decoration:none; }
    .btn-primary  { background:var(--teal);  color:#fff; box-shadow:0 2px 8px rgba(59,191,191,.3); }
    .btn-primary:hover  { background:var(--teal-dark); color:#fff; }
    .btn-secondary{ background:#fff; color:var(--text-main); border-color:var(--border); }
    .btn-secondary:hover{ border-color:var(--teal); color:var(--teal-dark); background:var(--teal-light); }
    .btn-warning  { background:#FFF7ED; color:#C2410C; border-color:#FDBA74; }
    .btn-warning:hover  { background:var(--orange); color:#fff; border-color:var(--orange); }
    .btn-danger   { background:#FEF2F2; color:#B91C1C; border-color:#FCA5A5; }
    .btn-danger:hover   { background:var(--red); color:#fff; border-color:var(--red); }
    .btn-info     { background:#EFF6FF; color:#1D4ED8; border-color:#BFDBFE; }
    .btn-info:hover     { background:#1D4ED8; color:#fff; }
    .btn-purple   { background:#F5F3FF; color:#7C3AED; border-color:#DDD6FE; }
    .btn-purple:hover   { background:#7C3AED; color:#fff; }
    .btn-sm { padding:6px 14px; font-size:.78rem; border-radius:8px; }

    /* TABLE */
    .tbl-wrap { overflow-x:auto; }
    .tbl { width:100%; border-collapse:collapse; }
    .tbl thead tr { background:var(--navy); }
    .tbl th { text-align:left; padding:12px 14px; font-size:.72rem; font-weight:700; letter-spacing:.7px; text-transform:uppercase; color:rgba(255,255,255,.75); }
    .tbl th:first-child { border-radius:8px 0 0 8px; }
    .tbl th:last-child  { border-radius:0 8px 8px 0; }
    .tbl td { padding:11px 14px; font-size:.83rem; border-bottom:1px solid var(--off); vertical-align:middle; }
    .tbl tr:last-child td { border-bottom:none; }
    .tbl tbody tr:hover td { background:#F0F9F9; }
    .row-num { font-family:'DM Mono',monospace; color:var(--text-muted); font-size:.75rem; }
    .action-group { display:flex; gap:5px; flex-wrap:wrap; }

    /* BADGE */
    .badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:6px; font-size:.72rem; font-weight:700; }
    .badge-teal   { background:var(--teal-light); color:var(--teal-dark); }
    .badge-navy   { background:#EFF6FF; color:#1D4ED8; }
    .badge-green  { background:#DCFCE7; color:#15803D; }
    .badge-purple { background:#F5F3FF; color:#7C3AED; }
    .badge-gray   { background:#F1F5F9; color:#64748B; }

    /* SUB-ELEMEN INPUT TABLE */
    .sub-input-tbl { width:100%; border-collapse:collapse; }
    .sub-input-tbl th { background:var(--navy-soft); color:rgba(255,255,255,.8); padding:10px 12px; font-size:.72rem; font-weight:700; text-transform:uppercase; }
    .sub-input-tbl td { padding:8px 10px; border-bottom:1px solid var(--off); }
    .sub-input-tbl input[type=text], .sub-input-tbl textarea { width:100%; padding:8px 10px; border:1.5px solid var(--border); border-radius:8px; font-family:'Plus Jakarta Sans',sans-serif; font-size:.84rem; outline:none; }
    .sub-input-tbl input[type=text]:focus, .sub-input-tbl textarea:focus { border-color:var(--teal); }
    .sub-input-tbl textarea { resize:vertical; min-height:60px; }

    /* FOOTER */
    .page-footer { padding:20px 32px; border-top:1px solid var(--border); background:#fff; display:flex; align-items:center; justify-content:space-between; font-size:.78rem; color:var(--text-muted); margin-top:auto; }
    .page-footer strong { color:var(--teal-dark); }

    @media (max-width:900px) {
        .main { margin-left:0; }
        .form-grid { grid-template-columns:1fr; }
    }
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-box" style="width: 50px; height: 50px; background: white; border-radius: 12px; padding: 5px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <img src="../images/lsplogosmkn1.png" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <div class="logo-text">
            <strong>LSP</strong>
            <span>SMKN 1 CIBINONG</span>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboardbaru.php" class="nav-item"><i class="fas fa-gauge-high"></i> Dashboard</a>
        <div class="nav-label">Manajemen Data</div>
        <a href="inputskema.php"    class="nav-item"><i class="fas fa-sitemap"></i> Kelola Skema</a>
        <a href="inputunit.php"     class="nav-item"><i class="fas fa-cubes"></i> Kelola Unit</a>
        <a href="inputasesor.php"   class="nav-item"><i class="fas fa-user-tie"></i> Kelola Asesor</a>
        <a href="inputpeserta.php"  class="nav-item"><i class="fas fa-users"></i> Kelola Peserta</a>
        <a href="inputelemen.php"   class="nav-item active"><i class="fas fa-star"></i> Kelola Kompetensi</a>
        <a href="inputtempattuk.php" class="nav-item"><i class="fas fa-building"></i> Kelola Tempat TUK</a>
        <div class="nav-label">Input Data</div>
        <a href="inputsyarat.php"        class="nav-item"><i class="fas fa-pen-to-square"></i> Input Persyaratan</a>
        <a href="inputkumpan.php"        class="nav-item"><i class="fas fa-pen-to-square"></i> Input Umpan Balik</a>
        <a href="inputprosesasesmen.php" class="nav-item"><i class="fas fa-pen-to-square"></i> Input Proses Asesmen</a>
        <a href="inputpengurus.php"      class="nav-item"><i class="fas fa-pen-to-square"></i> Input Pengurus</a>
        <div class="nav-label">Proses Uji</div>
        <a href="mapa.php"           class="nav-item"><i class="fas fa-paperclip"></i> MAPA</a>
        <a href="settanggal.php"     class="nav-item"><i class="fas fa-clock"></i> SET Tanggal</a>
        <a href="pemetaanasesor.php" class="nav-item"><i class="fas fa-calendar-days"></i> Atur Jadwal</a>
        <a href="inputpraktek.php"   class="nav-item"><i class="fas fa-clipboard-check"></i> FR.IA.01 Ceklist Observasi</a>
        <a href="inputtestulis.php"  class="nav-item"><i class="fas fa-file-lines"></i> FR.IA.05 Tes Tertulis</a>
        <div class="nav-label">Validasi & Monitor</div>
        <a href="validasiapl1lsp.php" class="nav-item"><i class="fas fa-check-double"></i> Validasi APL1</a>
        <a href="monitorasesi.php"    class="nav-item"><i class="fas fa-desktop"></i> Monitoring</a>
        <a href="backupdata.php"      class="nav-item"><i class="fas fa-download"></i> Backup Data</a>
        <div class="nav-label">Akun</div>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= strtoupper(substr($namax??'AD',0,2)) ?></div>
            <div class="user-info"><strong><?= htmlspecialchars($namax??'Admin') ?></strong><span>LSP Admin</span></div>
            <button class="btn-logout" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button>
        </div>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <header class="topbar">
        <div class="topbar-title">Kelola Elemen & Sub Elemen <span>Manajemen data kompetensi</span></div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i><?= $today ?></div>
            <button class="icon-btn"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">
<?php
$op = $_GET['op'] ?? '';
$pesan = ''; $pesan_tipe = '';

/* ============================================================ PROSES POST ============================================================ */
if ($op == "appendelemen") {
    $idskema    = $_POST['idskema'] ?? '';
    $idunit     = $_POST['idunit'] ?? '';
    $kodeelemen = trim($_POST['kodeelemen'] ?? '');
    $namaelemen = trim($_POST['namaelemen'] ?? '');
    $deskripsi  = $_POST['deskripsi'] ?? '';
    $cek = mysqli_query($conn, "SELECT * FROM elemen WHERE kodeelemen='$kodeelemen' AND idskema='$idskema' AND idunit='$idunit'");
    if (mysqli_num_rows($cek) > 0) { $pesan = 'Gagal: data elemen sudah ada (duplikat).'; $pesan_tipe = 'error'; }
    elseif (!empty($kodeelemen)) {
        $h = mysqli_query($conn, "INSERT INTO elemen (kodeelemen, idskema, namaelemen, idunit, deskripsi) VALUES ('$kodeelemen','$idskema','$namaelemen','$idunit','$deskripsi')");
        $pesan = $h ? 'Elemen berhasil ditambahkan.' : 'Gagal menambahkan elemen: '.mysqli_error($conn);
        $pesan_tipe = $h ? 'success' : 'error';
    }
    $op = '';
}
elseif ($op == "update") {
    $idskema        = trim($_POST['idskema'] ?? '');
    $idunit         = trim($_POST['idunit'] ?? '');
    $idelemen       = $_POST['idelemen'] ?? '';
    $kodeelemen     = trim($_POST['kodeelemen'] ?? '');
    $namaelemen     = $_POST['namaelemen'] ?? '';
    $idskemalama    = trim($_POST['idskemalama'] ?? '');
    $idunitlama     = trim($_POST['idunitlama'] ?? '');
    $kodeelemenlama = trim($_POST['kodeelemenlama'] ?? '');

    if ($idskema == $idskemalama && $idunit == $idunitlama && $kodeelemen == $kodeelemenlama) {
        $h = mysqli_query($conn, "UPDATE elemen SET namaelemen='$namaelemen' WHERE idelemen='$idelemen'");
    } else {
        $cek = mysqli_query($conn, "SELECT kodeelemen FROM elemen WHERE kodeelemen='$kodeelemen' AND idskema='$idskema' AND idunit='$idunit'");
        if (mysqli_num_rows($cek) > 0) { $h = false; $dup = true; }
        else $h = mysqli_query($conn, "UPDATE elemen SET idskema='$idskema',idunit='$idunit',kodeelemen='$kodeelemen',namaelemen='$namaelemen' WHERE idelemen='$idelemen'");
    }
    $pesan = isset($dup) ? 'Gagal: data duplikat.' : ($h ? 'Elemen berhasil diperbarui.' : 'Gagal memperbarui: '.mysqli_error($conn));
    $pesan_tipe = ($h && !isset($dup)) ? 'success' : 'error';
    $op = '';
}
elseif ($op == "deletepost") {
    $h = mysqli_query($conn, "DELETE FROM elemen WHERE idelemen='".$_POST['idelemen']."'");
    $pesan = $h ? 'Elemen berhasil dihapus.' : 'Gagal menghapus elemen.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}
elseif ($op == "subelemenpostsimpan") {
    $idskema    = $_POST['idskema'] ?? '';
    $idunit     = $_POST['idunit'] ?? '';
    $idelemen   = $_POST['idelemen'] ?? '';
    $kodeelemen = trim($_POST['kodeelemen'] ?? '');
    $jumlah     = count($_POST['pertanyaan'] ?? []);
    $sukses = 0;
    for ($i = 0; $i < $jumlah; $i++) {
        $pert = trim($_POST['pertanyaan'][$i] ?? '');
        if (empty($pert)) continue;
        $h = mysqli_query($conn, "INSERT INTO subelemen (idelemen, idunit, idskema, pertanyaan) VALUES ('$idelemen','$idunit','$idskema','$pert')");
        if ($h) $sukses++;
    }
    $pesan = "$sukses sub elemen berhasil disimpan.";
    $pesan_tipe = $sukses > 0 ? 'success' : 'error';
    $op = '';
}
elseif ($op == "deletepertanyaan") {
    $h = mysqli_query($conn, "DELETE FROM subelemen WHERE idsubelemen='".$_POST['idsubelemen']."'");
    $pesan = $h ? 'Sub elemen berhasil dihapus.' : 'Gagal menghapus sub elemen.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}
elseif ($op == "postuploadelemen") {
    include "excel_reader2.php";
    $data    = new Spreadsheet_Excel_Reader($_FILES['uploadedfile']['tmp_name']);
    $baris   = $data->rowcount(0);
    $sukses  = 0; $gagal = 0; $dup_list = [];
    $ieskema = $_POST['id'];
    $ieunit  = $_POST['idunit'];
    for ($i = 2; $i <= $baris; $i++) {
        $kdel = $data->val($i, 2);
        $nmel = $data->val($i, 3);
        if (empty($kdel)) break;
        $cek = mysqli_query($conn, "SELECT kodeelemen FROM elemen WHERE kodeelemen='$kdel'");
        if (mysqli_num_rows($cek) > 0) { $dup_list[] = $kdel; continue; }
        $h = mysqli_query($conn, "INSERT INTO elemen (kodeelemen, idskema, namaelemen, idunit, deskripsi) VALUES ('$kdel','$ieskema','$nmel','$ieunit','')");
        if ($h) $sukses++; else $gagal++;
    }
    $pesan = "$sukses elemen diimport, $gagal gagal.".(!empty($dup_list)?' Duplikat: '.implode(', ',$dup_list):'');
    $pesan_tipe = $sukses > 0 ? 'success' : 'error';
    $op = '';
}
elseif ($op == "postuploadsubelemen") {
    include "excel_reader2.php";
    $data      = new Spreadsheet_Excel_Reader($_FILES['uploadedfile']['tmp_name']);
    $baris     = $data->rowcount(0);
    $sukses    = 0; $gagal = 0; $dup_list = [];
    $isubeskema  = $_POST['iesubskema'];
    $isubeunit   = $_POST['iesubunit'];
    $isubelemen  = $_POST['ielemen'];
    for ($i = 2; $i <= $baris; $i++) {
        $kdsubel = $data->val($i, 2);
        $nmsubel = $data->val($i, 3);
        if (empty($kdsubel)) break;
        $h = mysqli_query($conn, "INSERT INTO subelemen (idelemen, idunit, idskema, pertanyaan) VALUES ('$isubelemen','$isubeunit','$isubeskema','$nmsubel')");
        if ($h) $sukses++; else $gagal++;
    }
    $pesan = "$sukses sub elemen diimport, $gagal gagal.".(!empty($dup_list)?' Duplikat: '.implode(', ',$dup_list):'');
    $pesan_tipe = $sukses > 0 ? 'success' : 'error';
    $op = '';
}
?>

<?php if ($pesan): ?>
<div class="alert-box alert-<?= $pesan_tipe ?>">
    <i class="fas <?= $pesan_tipe=='success'?'fa-circle-check':($pesan_tipe=='warning'?'fa-triangle-exclamation':'fa-circle-xmark') ?>"></i>
    <?= htmlspecialchars($pesan) ?>
</div>
<?php endif; ?>

<?php
/* ============================================================ FORM EDIT ELEMEN ============================================================ */
if ($op == 'edit'):
    $idelemen = $_GET['idelemen'] ?? '';
    $de = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM elemen WHERE idelemen='$idelemen'"));
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-pen-to-square" style="color:var(--teal);margin-right:8px"></i>Edit Elemen Kompetensi</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=update">
        <input type="hidden" name="idelemen"       value="<?= $de['idelemen'] ?>">
        <input type="hidden" name="kodeelemenlama" value="<?= $de['kodeelemen'] ?>">
        <input type="hidden" name="idskemalama"    value="<?= $de['idskema'] ?>">
        <input type="hidden" name="idunitlama"     value="<?= $de['idunit'] ?>">
        <div class="form-grid">
            <div class="form-label">Skema <span style="color:var(--red)">*</span></div>
            <select name="idskema" class="form-input">
                <?php $ts = mysqli_query($conn, "SELECT * FROM skema ORDER BY idskema");
                while ($r = mysqli_fetch_array($ts)) {
                    $sel = ($de['idskema'] == $r['idskema']) ? 'selected' : '';
                    echo "<option value='{$r['idskema']}' $sel>{$r['namaskema']}</option>";
                } ?>
            </select>
            <div class="form-label">Unit <span style="color:var(--red)">*</span></div>
            <select name="idunit" class="form-input">
                <?php $tu = mysqli_query($conn, "SELECT * FROM unit ORDER BY idunit");
                while ($r = mysqli_fetch_array($tu)) {
                    $sel = ($de['idunit'] == $r['idunit']) ? 'selected' : '';
                    echo "<option value='{$r['idunit']}' $sel>{$r['namaunit']}</option>";
                } ?>
            </select>
            <div class="form-label">Kode Elemen <span style="color:var(--red)">*</span></div>
            <input type="text" name="kodeelemen" class="form-input" value="<?= htmlspecialchars($de['kodeelemen'] ?? '') ?>" required>
            <div class="form-label">Nama Elemen <span style="color:var(--red)">*</span></div>
            <input type="text" name="namaelemen" class="form-input" value="<?= htmlspecialchars($de['namaelemen'] ?? '') ?>" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Perubahan</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ============================================================ FORM TAMBAH ELEMEN ============================================================ */
elseif ($op == 'tambah'):
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-plus-circle" style="color:var(--teal);margin-right:8px"></i>Tambah Elemen Kompetensi</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=appendelemen">
        <div class="form-grid">
            <div class="form-label">Skema <span style="color:var(--red)">*</span></div>
            <select name="idskema" class="form-input" autofocus>
                <?php $ts = mysqli_query($conn, "SELECT * FROM skema ORDER BY idskema");
                while ($r = mysqli_fetch_array($ts))
                    echo "<option value='{$r['idskema']}'>{$r['namaskema']}</option>"; ?>
            </select>
            <div class="form-label">Unit <span style="color:var(--red)">*</span></div>
            <select name="idunit" class="form-input">
                <?php $tu = mysqli_query($conn, "SELECT * FROM unit ORDER BY idunit");
                while ($r = mysqli_fetch_array($tu))
                    echo "<option value='{$r['idunit']}'>{$r['namaunit']}</option>"; ?>
            </select>
            <div class="form-label">Kode Elemen <span style="color:var(--red)">*</span></div>
            <input type="text" name="kodeelemen" class="form-input" placeholder="Contoh: KE-01" required>
            <div class="form-label">Nama Elemen <span style="color:var(--red)">*</span></div>
            <input type="text" name="namaelemen" class="form-input" placeholder="Nama lengkap elemen kompetensi" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Simpan Elemen</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ============================================================ KONFIRMASI HAPUS ELEMEN ============================================================ */
elseif ($op == 'delete'):
    $idelemen = $_GET['idelemen'] ?? '';
    $dd = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM elemen WHERE idelemen='$idelemen'"));
    $cekSub = mysqli_query($conn, "SELECT idelemen FROM subelemen WHERE idelemen='$idelemen'");
    $adaSub = mysqli_num_rows($cekSub) > 0;
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-trash" style="color:var(--red);margin-right:8px"></i>Hapus Elemen</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <?php if ($adaSub): ?>
    <div class="alert-box alert-warning">
        <i class="fas fa-triangle-exclamation"></i>
        <strong>Tidak dapat dihapus!</strong> Elemen ini masih memiliki sub elemen. Hapus sub elemen terlebih dahulu.
    </div>
    <?php else: ?>
    <div class="alert-box alert-error">
        <i class="fas fa-circle-xmark"></i>
        Yakin menghapus elemen <strong><?= htmlspecialchars($dd['namaelemen'] ?? '') ?></strong>? Tindakan ini tidak dapat dibatalkan.
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=deletepost">
        <input type="hidden" name="idelemen" value="<?= $idelemen ?>">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php
/* ============================================================ TAMBAH SUB ELEMEN (form jumlah) ============================================================ */
elseif ($op == 'subelemen'):
    $idelemen = $_GET['idelemen'] ?? '';
    $de = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM elemen WHERE idelemen='$idelemen'"));
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-list-plus" style="color:var(--teal);margin-right:8px"></i>Tambah Sub Elemen</h3>
            <p>Elemen: <span class="badge badge-teal"><?= htmlspecialchars($de['kodeelemen'] ?? '') ?></span> &nbsp;<?= htmlspecialchars($de['namaelemen'] ?? '') ?></p>
        </div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=subelemenpost">
        <input type="hidden" name="idskema"    value="<?= $de['idskema'] ?>">
        <input type="hidden" name="idunit"     value="<?= $de['idunit'] ?>">
        <input type="hidden" name="idelemen"   value="<?= $de['idelemen'] ?>">
        <input type="hidden" name="kodeelemen" value="<?= $de['kodeelemen'] ?>">
        <div class="form-grid">
            <div class="form-label">Berapa sub elemen? <span style="color:var(--red)">*</span></div>
            <div>
                <input type="number" name="banyak" class="form-input" style="max-width:120px" min="1" max="50" placeholder="1-50" required autofocus>
                <p class="form-hint">Masukkan jumlah sub elemen yang akan ditambahkan sekaligus.</p>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lanjutkan</button>
        </div>
    </form>
</div>

<?php
/* ============================================================ INPUT SUB ELEMEN (form isi) ============================================================ */
elseif ($op == 'subelemenpost'):
    $banyak     = (int)($_POST['banyak'] ?? 1);
    $idskema    = $_POST['idskema'] ?? '';
    $idunit     = $_POST['idunit'] ?? '';
    $idelemen   = $_POST['idelemen'] ?? '';
    $kodeelemen = trim($_POST['kodeelemen'] ?? '');
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-list-plus" style="color:var(--teal);margin-right:8px"></i>Isi Sub Elemen</h3>
            <p>Elemen: <span class="badge badge-teal"><?= htmlspecialchars($kodeelemen) ?></span> &nbsp;·&nbsp; <?= $banyak ?> sub elemen</p>
        </div>
        <a href="javascript:history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=subelemenpostsimpan">
        <input type="hidden" name="idskema"    value="<?= $idskema ?>">
        <input type="hidden" name="idunit"     value="<?= $idunit ?>">
        <input type="hidden" name="idelemen"   value="<?= $idelemen ?>">
        <input type="hidden" name="kodeelemen" value="<?= $kodeelemen ?>">
        <div class="tbl-wrap">
            <table class="sub-input-tbl">
                <thead><tr><th style="width:50px">No</th><th>Komponen / KUK</th></tr></thead>
                <tbody>
                <?php for ($i = 0; $i < $banyak; $i++): ?>
                <tr>
                    <td class="row-num" style="text-align:center"><?= $i+1 ?></td>
                    <td><textarea name="pertanyaan[]" placeholder="Tulis komponen / KUK..." required></textarea></td>
                </tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Sub Elemen</button>
        </div>
    </form>
</div>

<?php
/* ============================================================ KELOLA SUB ELEMEN (daftar) ============================================================ */
elseif ($op == 'kelolasubelemen'):
    $idelemen = $_GET['idelemen'] ?? '';
    $de = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM elemen WHERE idelemen='$idelemen'"));
    $hasil_sub = mysqli_query($conn, "SELECT * FROM subelemen WHERE idelemen='$idelemen'");
    $total_sub = $hasil_sub ? mysqli_num_rows($hasil_sub) : 0;
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-layer-group" style="color:var(--teal);margin-right:8px"></i>Daftar Sub Elemen</h3>
            <p>Elemen: <span class="badge badge-teal"><?= htmlspecialchars($de['kodeelemen'] ?? '') ?></span> &nbsp;<?= htmlspecialchars($de['namaelemen'] ?? '') ?> &nbsp;·&nbsp; <?= $total_sub ?> sub elemen</p>
        </div>
        <div style="display:flex;gap:8px">
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=subelemen&idelemen=<?= $idelemen ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Sub
            </a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th style="width:50px">No</th>
                <th>Komponen / KUK</th>
                <th style="width:130px;text-align:center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; if ($hasil_sub && mysqli_num_rows($hasil_sub) > 0):
            while ($d = mysqli_fetch_array($hasil_sub)): ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td style="font-size:.82rem"><?= htmlspecialchars($d['pertanyaan']) ?></td>
                <td>
                    <div class="action-group" style="justify-content:center">
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=editpertanyaan&idsubelemen=<?= $d['idsubelemen'] ?>"
                           class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=deletekelolasubelemen&idsubelemen=<?= $d['idsubelemen'] ?>"
                           class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php $no++; endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3" style="text-align:center;color:var(--text-muted)">Belum ada sub elemen.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
/* ============================================================ EDIT PERTANYAAN / SUB ELEMEN ============================================================ */
elseif ($op == 'editpertanyaan'):
    $idsubelemen = $_GET['idsubelemen'] ?? '';
    $ds = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM subelemen WHERE idsubelemen='$idsubelemen'"));
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveedit'])) {
        $pert = $_POST['pertanyaan'];
        $h = mysqli_query($conn, "UPDATE subelemen SET pertanyaan='$pert' WHERE idsubelemen='$idsubelemen'");
        $pesan = $h ? 'Sub elemen berhasil diperbarui.' : 'Gagal memperbarui.';
        $pesan_tipe = $h ? 'success' : 'error';
        if ($h) { header("Location: ".$_SERVER['PHP_SELF']."?op=kelolasubelemen&idelemen=".$ds['idelemen']); exit; }
    }
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-pen-to-square" style="color:var(--teal);margin-right:8px"></i>Edit Sub Elemen</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=kelolasubelemen&idelemen=<?= $ds['idelemen'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <?php if ($pesan): ?>
    <div class="alert-box alert-<?= $pesan_tipe ?>">
        <i class="fas <?= $pesan_tipe=='success'?'fa-circle-check':'fa-circle-xmark' ?>"></i>
        <?= htmlspecialchars($pesan) ?>
    </div>
    <?php endif; ?>
    <form method="post">
        <div class="form-grid">
            <div class="form-label">Komponen / KUK</div>
            <textarea name="pertanyaan" class="form-input" rows="4"><?= htmlspecialchars($ds['pertanyaan'] ?? '') ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" name="saveedit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan</button>
        </div>
    </form>
</div>

<?php
/* ============================================================ KONFIRMASI HAPUS SUB ELEMEN ============================================================ */
elseif ($op == 'deletekelolasubelemen'):
    $idsubelemen = $_GET['idsubelemen'] ?? '';
    $ds = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM subelemen WHERE idsubelemen='$idsubelemen'"));
    $cekApl2 = mysqli_query($conn, "SELECT idsubelemen FROM apl2 WHERE idsubelemen='$idsubelemen'");
    $adaApl2 = $cekApl2 && mysqli_num_rows($cekApl2) > 0;
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-trash" style="color:var(--red);margin-right:8px"></i>Hapus Sub Elemen</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=kelolasubelemen&idelemen=<?= $ds['idelemen'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <?php if ($adaApl2): ?>
    <div class="alert-box alert-warning">
        <i class="fas fa-triangle-exclamation"></i>
        <strong>Tidak dapat dihapus!</strong> Sub elemen ini sudah digunakan peserta di APL2.
    </div>
    <?php else: ?>
    <div class="alert-box alert-error">
        <i class="fas fa-circle-xmark"></i>
        Yakin menghapus sub elemen: <strong><?= htmlspecialchars($ds['pertanyaan'] ?? '') ?></strong>?
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=deletepertanyaan">
        <input type="hidden" name="idsubelemen" value="<?= $idsubelemen ?>">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=kelolasubelemen&idelemen=<?= $ds['idelemen'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
    <?php endif; ?>
</div>

<?php
/* ============================================================ IMPORT ELEMEN ============================================================ */
elseif ($op == 'importelemen'):
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-file-excel" style="color:#16A34A;margin-right:8px"></i>Import Elemen dari Excel</h3><p>Format: No | Kode Elemen | Nama Elemen</p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form enctype="multipart/form-data" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>?op=postuploadelemen">
        <div class="form-grid">
            <div class="form-label">Skema</div>
            <select name="id" class="form-input">
                <?php $ti = mysqli_query($conn, "SELECT * FROM skema ORDER BY idskema");
                while ($r = mysqli_fetch_array($ti))
                    echo "<option value='{$r['idskema']}'>{$r['namaskema']} ({$r['idskema']})</option>"; ?>
            </select>
            <div class="form-label">Unit</div>
            <select name="idunit" class="form-input">
                <?php $tu = mysqli_query($conn, "SELECT * FROM unit ORDER BY idunit");
                while ($r = mysqli_fetch_array($tu))
                    echo "<option value='{$r['idunit']}'>{$r['namaunit']} ({$r['idunit']})</option>"; ?>
            </select>
            <div class="form-label">File Excel</div>
            <div>
                <input type="file" name="uploadedfile" accept=".xls" style="display:block;margin-bottom:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload & Import</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ============================================================ IMPORT SUB ELEMEN ============================================================ */
elseif ($op == 'importsubelemen'):
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-file-excel" style="color:#16A34A;margin-right:8px"></i>Import Sub Elemen dari Excel</h3><p>Format: No | Kode Sub Elemen | Komponen / KUK</p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form enctype="multipart/form-data" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>?op=postuploadsubelemen">
        <div class="form-grid">
            <div class="form-label">Skema</div>
            <select name="iesubskema" class="form-input">
                <?php $ti = mysqli_query($conn, "SELECT * FROM skema ORDER BY idskema");
                while ($r = mysqli_fetch_array($ti))
                    echo "<option value='{$r['idskema']}'>{$r['namaskema']} ({$r['idskema']})</option>"; ?>
            </select>
            <div class="form-label">Unit</div>
            <select name="iesubunit" class="form-input">
                <?php $tu = mysqli_query($conn, "SELECT * FROM unit ORDER BY idunit");
                while ($r = mysqli_fetch_array($tu))
                    echo "<option value='{$r['idunit']}'>{$r['namaunit']} ({$r['idunit']})</option>"; ?>
            </select>
            <div class="form-label">Elemen</div>
            <select name="ielemen" class="form-input">
                <?php $te = mysqli_query($conn, "SELECT * FROM elemen ORDER BY idelemen");
                while ($r = mysqli_fetch_array($te))
                    echo "<option value='{$r['idelemen']}'>{$r['namaelemen']} ({$r['idelemen']})</option>"; ?>
            </select>
            <div class="form-label">File Excel</div>
            <div>
                <input type="file" name="uploadedfile" accept=".xls" style="display:block;margin-bottom:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload & Import</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ============================================================ DAFTAR UTAMA ============================================================ */
else:
    $query  = "SELECT elemen.idunit,elemen.kodeelemen,elemen.namaelemen,elemen.idelemen,elemen.idskema,unit.kodeunit FROM elemen INNER JOIN unit ON elemen.idunit=unit.idunit ORDER BY unit.kodeunit";
    $hasil  = mysqli_query($conn, $query);
    $total  = $hasil ? mysqli_num_rows($hasil) : 0;
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-star" style="color:var(--teal);margin-right:8px"></i>Daftar Elemen Kompetensi</h3>
            <p><?= $total ?> elemen terdaftar</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=importsubelemen" class="btn btn-secondary btn-sm"><i class="fas fa-file-excel"></i> Import Sub Elemen</a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=importelemen"    class="btn btn-secondary btn-sm"><i class="fas fa-file-excel"></i> Import Elemen</a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=tambah"          class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Elemen</a>
        </div>
    </div>

    <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th style="width:70px">ID</th>
                    <th style="width:150px">Kode Elemen</th>
                    <th>Nama Elemen</th>
                    <th style="width:160px">Kode Unit / Sub</th>
                    <th style="width:260px;text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; if ($hasil && mysqli_num_rows($hasil) > 0):
            while ($d = mysqli_fetch_array($hasil)):
                $jSub = mysqli_fetch_array(mysqli_query($conn, "SELECT count(idelemen) as jsubel FROM subelemen WHERE idelemen='{$d['idelemen']}'"));
            ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td><span class="badge badge-navy" style="font-family:'DM Mono',monospace"><?= $d['idelemen'] ?></span></td>
                <td><span class="badge badge-teal"><?= htmlspecialchars($d['kodeelemen'] ?? '') ?></span></td>
                <td style="font-weight:500;font-size:.82rem"><?= htmlspecialchars($d['namaelemen']) ?></td>
                <td>
                    <span class="badge badge-gray" style="font-family:'DM Mono',monospace"><?= $d['kodeunit'] ?></span>
                    &nbsp;
                    <span class="badge <?= ($jSub && $jSub['jsubel']>0)?'badge-green':'badge-gray' ?>"><?= $jSub ? $jSub['jsubel'] : 0 ?> sub</span>
                </td>
                <td>
                    <div class="action-group" style="justify-content:center">
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=kelolasubelemen&idelemen=<?= $d['idelemen'] ?>" class="btn btn-info btn-sm"><i class="fas fa-list"></i> Kelola Sub</a>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=subelemen&idelemen=<?= $d['idelemen'] ?>"       class="btn btn-purple btn-sm"><i class="fas fa-plus"></i> Tambah Sub</a>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=edit&idelemen=<?= $d['idelemen'] ?>"            class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=delete&idelemen=<?= $d['idelemen'] ?>"          class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php $no++; endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:30px">Belum ada elemen kompetensi.</td></tr>
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
