<!DOCTYPE html>
<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kelola Skema — LSP SMKN 1 Cibinong</title>

<!-- Font & Icons dari dashboardbaru -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- Script lama inputskema yang masih dibutuhkan -->
<script src="js/lumino.glyphs.js"></script>

<?php
include "../lsp_koneksi.php";
if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
    echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<i class='fas fa-lock' style='font-size:2rem;color:#EF4444;margin-bottom:16px'></i>";
    echo "<h3 style='font-family:Plus Jakarta Sans,sans-serif;color:#1A2E3B'>Anda Harus Login Dahulu!</h3>";
    echo "<a href='../lsp_login.php' style='display:inline-block;margin-top:16px;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>";
    echo "</div>";
    exit;
}
if (!in_array($_SESSION['level'], ['lsp','admin'])) {
    echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<i class='fas fa-ban' style='font-size:2rem;color:#EF4444;margin-bottom:16px'></i>";
    echo "<h3 style='font-family:Plus Jakarta Sans,sans-serif;color:#1A2E3B'>Anda Tidak Punya Hak Akses!</h3>";
    echo "<a href='../lsp_login.php' style='display:inline-block;margin-top:16px;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>";
    echo "</div>";
    exit;
}

if(isset($_SESSION['username'])) { $uname=$_SESSION['username']; }
$l="SELECT * FROM lsp_usertbl WHERE email='".$uname."'";
$resultx = mysqli_query($conn, $l);
$hasilx = mysqli_fetch_array($resultx, MYSQLI_ASSOC);
$namax = $hasilx['nama'];
$today = date('d F Y');
$current_time = date('H:i');
?>

<style>
    :root {
        --teal:       #3BBFBF;
        --teal-dark:  #2A9999;
        --teal-light: #E8F8F8;
        --teal-glow:  rgba(59,191,191,.18);
        --navy:       #0F2A3A;
        --navy-mid:   #163347;
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
        --yellow:     #EAB308;
        --sidebar-w:  260px;
        --radius:     14px;
        --shadow:     0 2px 16px rgba(15,42,58,.07);
        --shadow-md:  0 4px 32px rgba(15,42,58,.13);
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 15px; scroll-behavior: smooth; }
    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--off);
        color: var(--text-main);
        display: flex;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* ===== SIDEBAR ===== */
/* ===== SIDEBAR (Updated) ===== */


/* Tambahkan ini untuk memastikan navigasi mengambil ruang yang benar */
.sidebar-nav { 
    padding: 16px 12px; 
    flex: 1 0 auto; /* Membiarkan nav memanjang sesuai kontennya */
}




.sidebar:hover::-webkit-scrollbar-thumb {
    background: var(--teal);
}
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
.sidebar-logo {
        padding: 28px 24px 20px;
        border-bottom: 1px solid rgba(255,255,255,.07);
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }
    .logo-box {
        width: 42px; height: 42px;
        background: var(--teal);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px; color: var(--white);
        flex-shrink: 0;
    }
    .logo-text { line-height: 1.2; }
    .logo-text strong { display: block; color: var(--white); font-size: .95rem; font-weight: 700; }
    .logo-text span { color: var(--teal); font-size: .72rem; font-weight: 500; letter-spacing: .5px; }

    .sidebar-nav { padding: 16px 12px; flex: 1; }
    .nav-label {
        color: rgba(255,255,255,.3);
        font-size: .67rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 12px 12px 6px;
    }
    .nav-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        color: rgba(255,255,255,.55);
        text-decoration: none;
        font-size: .875rem;
        font-weight: 500;
        transition: all .2s;
        margin-bottom: 2px;
        cursor: pointer;
    }
    .nav-item:hover { background: rgba(255,255,255,.07); color: var(--white); text-decoration: none; }
    .nav-item.active {
        background: var(--teal);
        color: var(--white);
        box-shadow: 0 4px 12px rgba(59,191,191,.35);
    }
    .nav-item i { width: 18px; text-align: center; font-size: .9rem; flex-shrink: 0; }
    .sidebar-footer {
        padding: 16px 14px;
        border-top: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0;
    }
    .user-card {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        background: rgba(255,255,255,.05);
    }
    .user-avatar {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: var(--teal);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem; color: var(--white);
        flex-shrink: 0;
    }
    .user-info strong { display: block; color: var(--white); font-size: .82rem; }
    .user-info span { color: var(--teal); font-size: .72rem; }
    .btn-logout {
        margin-left: auto;
        color: rgba(255,255,255,.35);
        background: none; border: none;
        cursor: pointer; font-size: .85rem;
        transition: color .2s;
    }
    .btn-logout:hover { color: var(--red); }

    /* ===== MAIN ===== */
    .main {
        margin-left: var(--sidebar-w);
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* ===== TOPBAR ===== */
    .topbar {
        background: var(--white);
        border-bottom: 1px solid var(--border);
        padding: 0 32px;
        height: 68px;
        display: flex; align-items: center;
        gap: 16px;
        position: sticky; top: 0; z-index: 50;
    }
    .topbar-title { font-size: 1.1rem; font-weight: 700; flex: 1; }
    .topbar-title span { color: var(--text-sub); font-weight: 400; font-size: .875rem; margin-left: 8px; }
    .topbar-actions { display: flex; align-items: center; gap: 10px; }
    .icon-btn {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        background: var(--white);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-sub);
        font-size: .9rem; position: relative;
        transition: all .2s;
    }
    .icon-btn:hover { border-color: var(--teal); color: var(--teal); }
    .date-chip {
        background: var(--teal-light);
        color: var(--teal-dark);
        font-size: .78rem; font-weight: 600;
        padding: 6px 14px; border-radius: 8px;
        display: flex; align-items: center; gap: 6px;
    }

    /* ===== CONTENT ===== */
    .content { padding: 32px; display: flex; flex-direction: column; gap: 24px; }

    /* ===== CARD ===== */
    .card {
        background: var(--white);
        border-radius: var(--radius);
        padding: 28px 28px;
        box-shadow: var(--shadow);
        animation: fadeUp .4s ease both;
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .section-head {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px;
    }
    .section-head h3 { font-size: 1.05rem; font-weight: 700; }
    .section-head p { font-size: .78rem; color: var(--text-sub); margin-top: 2px; }

    /* ===== ALERT / PESAN ===== */
    .alert-box {
        padding: 12px 18px;
        border-radius: 10px;
        font-size: .85rem;
        font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 20px;
    }
    .alert-success { background: #DCFCE7; color: #15803D; border: 1px solid #86EFAC; }
    .alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FCA5A5; }
    .alert-warning { background: #FEFCE8; color: #A16207; border: 1px solid #FDE68A; }

    /* ===== FORM ===== */
    .form-grid { display: grid; grid-template-columns: 200px 1fr; gap: 16px; align-items: start; margin-bottom: 20px; }
    .form-label {
        font-size: .82rem; font-weight: 600; color: var(--text-sub);
        padding-top: 10px;
    }
    .form-input {
        width: 100%;
        font-size: .875rem;
        padding: 10px 14px;
        border: 1.5px solid var(--border);
        border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
        background: var(--off);
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        text-transform: uppercase;
    }
    .form-input:focus {
        border-color: var(--teal);
        box-shadow: 0 0 0 3px var(--teal-glow);
        background: var(--white);
    }
    textarea.form-input { resize: vertical; min-height: 80px; }
    .form-actions { display: flex; gap: 10px; margin-top: 4px; }

    /* ===== BUTTONS ===== */
    .btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 20px;
        border-radius: 10px;
        font-size: .85rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        border: 1.5px solid transparent;
        transition: all .2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .btn-primary {
        background: var(--teal); color: var(--white);
        box-shadow: 0 2px 8px rgba(59,191,191,.3);
    }
    .btn-primary:hover { background: var(--teal-dark); box-shadow: 0 4px 16px rgba(59,191,191,.4); }
    .btn-secondary {
        background: var(--white); color: var(--text-main);
        border-color: var(--border);
    }
    .btn-secondary:hover { border-color: var(--teal); color: var(--teal-dark); background: var(--teal-light); }
    .btn-danger {
        background: #FEF2F2; color: #B91C1C;
        border-color: #FCA5A5;
    }
    .btn-danger:hover { background: #EF4444; color: var(--white); border-color: #EF4444; }
    .btn-warning {
        background: #FFF7ED; color: #C2410C;
        border-color: #FDBA74;
    }
    .btn-warning:hover { background: #F97316; color: var(--white); border-color: #F97316; }
    .btn-sm { padding: 6px 14px; font-size: .78rem; border-radius: 8px; }

    /* ===== TABLE ===== */
    .tbl-wrap { overflow-x: auto; }
    .tbl { width: 100%; border-collapse: collapse; }
    .tbl thead tr { background: var(--navy); }
    .tbl th {
        text-align: left;
        padding: 12px 16px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .7px;
        text-transform: uppercase;
        color: rgba(255,255,255,.7);
    }
    .tbl th:first-child { border-radius: 8px 0 0 8px; }
    .tbl th:last-child  { border-radius: 0 8px 8px 0; }
    .tbl td {
        padding: 13px 16px;
        font-size: .845rem;
        border-bottom: 1px solid var(--off);
        vertical-align: middle;
        color: var(--text-main);
    }
    .tbl tr:last-child td { border-bottom: none; }
    .tbl tbody tr { transition: background .15s; }
    .tbl tbody tr:hover td { background: #F0F9F9; }

    .badge {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 6px;
        font-size: .72rem; font-weight: 700;
    }
    .badge-teal { background: var(--teal-light); color: var(--teal-dark); }
    .badge-navy { background: #EFF6FF; color: #1D4ED8; }

    .row-num {
        font-family: 'DM Mono', monospace;
        color: var(--text-muted);
        font-size: .75rem;
    }

    .action-group { display: flex; gap: 6px; }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center; padding: 48px 24px;
        color: var(--text-muted);
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; opacity: .4; }
    .empty-state p { font-size: .9rem; }

    /* ===== PAGE FOOTER ===== */
    .page-footer {
        padding: 20px 32px;
        border-top: 1px solid var(--border);
        background: var(--white);
        display: flex; align-items: center; justify-content: space-between;
        font-size: .78rem; color: var(--text-muted);
        margin-top: auto;
    }
    .page-footer strong { color: var(--teal-dark); }

    /* ===== DIVIDER ===== */
    .divider-line { height: 1px; background: var(--border); margin: 20px 0; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        
        .main { margin-left: 0; }
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
</head>

<body>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar">
<div class="sidebar-logo">
    <div class="logo-box" style="
        width: 50px; 
        height: 50px; 
        background: white; 
        border-radius: 12px; 
        padding: 5px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    ">
        <img src="../images/lsplogosmkn1.png" style="width: 100%; height: 100%; object-fit: contain;">
    </div>
    <div class="logo-text">
        <strong>LSP</strong>
        <span>SMKN 1 CIBINONG</span>
    </div>
</div>

    <nav class="sidebar-nav">
        <a href="dashboardbaru.php" class="nav-item">
            <i class="fas fa-gauge-high"></i> Dashboard
        </a>
        <div class="nav-label">Manajemen Data</div>
        <a href="inputskema.php" class="nav-item active">
            <i class="fas fa-sitemap"></i> Kelola Skema
        </a>
        <a href="inputunit.php" class="nav-item">
            <i class="fas fa-cubes"></i> Kelola Unit
        </a>
        <a href="inputasesor.php" class="nav-item">
            <i class="fas fa-user-tie"></i> Kelola Asesor
        </a>
        <a href="inputpeserta.php" class="nav-item">
            <i class="fas fa-users"></i> Kelola Peserta
        </a>
        <a href="inputelemen.php" class="nav-item">
            <i class="fas fa-star"></i> Kelola Kompetensi
        </a>
        <a href="inputtempattuk.php" class="nav-item">
            <i class="fas fa-building"></i> Kelola Tempat TUK
        </a>

        <div class="nav-label">Input Data</div>
        <a href="inputsyarat.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Input Persyaratan
        </a>
        <a href="inputkumpan.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Input Umpan Balik
        </a>
        <a href="inputprosesasesmen.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Input Proses Asesmen
        </a>
        <a href="inputpengurus.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Input Pengurus
        </a>

        <div class="nav-label">Proses Uji</div>
        <a href="mapa.php" class="nav-item">
            <i class="fas fa-paperclip"></i> MAPA
        </a>
        <a href="settanggal.php" class="nav-item">
            <i class="fas fa-clock"></i> SET Tanggal
        </a>
        <a href="pemetaanasesor.php" class="nav-item">
            <i class="fas fa-calendar-days"></i> Atur Jadwal
        </a>
        <a href="inputpraktek.php" class="nav-item">
            <i class="fas fa-clipboard-check"></i> FR.IA.01 Ceklist Observasi
        </a>
        <a href="inputtestulis.php" class="nav-item">
            <i class="fas fa-file-lines"></i> FR.IA.05 Tes Tertulis
        </a>

        <div class="nav-label">Validasi & Monitor</div>
        <a href="validasiapl1lsp.php" class="nav-item">
            <i class="fas fa-check-double"></i> Validasi APL1
        </a>
        <a href="monitorasesi.php" class="nav-item">
            <i class="fas fa-desktop"></i> Monitoring
        </a>
        <a href="backupdata.php" class="nav-item">
            <i class="fas fa-download"></i> Backup Data
        </a>

        <div class="nav-label">Akun</div>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= strtoupper(substr($namax ?? 'AD', 0, 2)) ?></div>
            <div class="user-info">
                <strong><?= htmlspecialchars($namax ?? 'Admin') ?></strong>
                <span>LSP Admin</span>
            </div>
            <button class="btn-logout" title="Logout" onclick="window.location='../logout.php'">
                <i class="fas fa-right-from-bracket"></i>
            </button>
        </div>
    </div>
</aside>

<!-- ===== MAIN ===== -->
<div class="main">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-title">
            Kelola Skema Sertifikasi
            <span>Manajemen data skema</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip">
                <i class="fas fa-calendar"></i>
                <?= $today ?>
            </div>
            <button class="icon-btn">
                <i class="fas fa-bell"></i>
            </button>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="content">

<?php
$op = $_GET['op'] ?? '';
$pesan = '';
$pesan_tipe = '';

/* ===========================
   PROSES OP
=========================== */
if ($op == "append") {
    $kodeskema = trim($_POST['kodeskema'] ?? '');
    $namaskema = trim($_POST['skema'] ?? '');
    if (!empty($namaskema)) {
        // Generate next ID Skema jika belum ada (e.g. SKM004)
        $qMax = mysqli_query($conn, "SELECT idskema FROM skema WHERE idskema LIKE 'SKM%' ORDER BY idskema DESC LIMIT 1");
        $dMax = mysqli_fetch_array($qMax);
        if ($dMax && preg_match('/^SKM(\d+)$/i', $dMax['idskema'], $m)) {
            $num = (int)$m[1] + 1;
            $newIdSkema = "SKM" . str_pad($num, 3, "0", STR_PAD_LEFT);
        } else {
            $newIdSkema = "SKM001";
        }

        $squeryappend = "INSERT INTO skema (idskema, noskema, namaskema, status) VALUES ('$newIdSkema', '$kodeskema', '$namaskema', 'Y')";
        $berhasil = mysqli_query($conn, $squeryappend);
        $pesan = $berhasil ? 'Data skema berhasil ditambahkan (ID: '.$newIdSkema.').' : 'Gagal menambahkan data: ' . mysqli_error($conn);
        $pesan_tipe = $berhasil ? 'success' : 'error';
        $op = ''; // kembali ke list
    }
}

else if ($op == "update") {
    $id        = $_POST['idLama'] ?? '';
    $skema     = $_POST['skema'] ?? '';
    $kodeskema = $_POST['kodeskema'] ?? '';
    $squeryupdate = "UPDATE skema SET noskema='$kodeskema', namaskema='$skema' WHERE idskema='$id'";
    $hasil = mysqli_query($conn, $squeryupdate);
    $pesan = $hasil ? 'Data skema berhasil diperbarui.' : 'Gagal memperbarui data: ' . mysqli_error($conn);
    $pesan_tipe = $hasil ? 'success' : 'error';
    $op = ''; // kembali ke list
}

else if ($op == "hapus") {
    $id = $_GET['id'] ?? '';
    if (!empty($id)) {
        $squeryhapus = "DELETE FROM skema WHERE idskema = '$id'";
        $hasil = mysqli_query($conn, $squeryhapus);
        $pesan = $hasil ? 'Data skema berhasil dihapus.' : 'Gagal menghapus data: ' . mysqli_error($conn);
        $pesan_tipe = $hasil ? 'success' : 'error';
    }
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
/* ===========================
   FORM TAMBAH / EDIT
=========================== */
if ($op === 'tambah' || $op === 'edit'):
    $form_action = ($op === 'tambah') ? '?op=append' : '?op=update';
    $form_title  = ($op === 'tambah') ? 'Tambah Skema Baru' : 'Edit Skema';
    $form_icon   = ($op === 'tambah') ? 'fa-plus-circle' : 'fa-pen-to-square';

    $skema_val    = '';
    $kodeskema_val = '';
    $idLama = '';

    if ($op === 'edit') {
        $id = $_GET['id'] ?? '';
        $squeryedit = "SELECT * FROM skema WHERE idskema = '$id'";
        $shasiledit = mysqli_query($conn, $squeryedit);
        $sdataedit  = mysqli_fetch_array($shasiledit);
        if ($sdataedit) {
            $skema_val     = $sdataedit['namaskema'];
            $kodeskema_val = $sdataedit['noskema'];
            $idLama        = $sdataedit['idskema'];
        } else {
            echo '<div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i> Data tidak ditemukan. Mungkin sudah dihapus.</div>';
        }
    }
?>
        <!-- FORM CARD -->
        <div class="card" style="animation-delay:.05s">
            <div class="section-head">
                <div>
                    <h3><i class="fas <?= $form_icon ?>" style="color:var(--teal);margin-right:8px"></i><?= $form_title ?></h3>
                    <p>Isi data skema sertifikasi dengan lengkap dan benar</p>
                </div>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>

            <form method="post" action="<?= $_SERVER['PHP_SELF'] . $form_action ?>">
                <?php if ($op === 'edit'): ?>
                <input type="hidden" name="idLama" value="<?= htmlspecialchars($idLama) ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-label">Kode Skema <span style="color:var(--red)">*</span></div>
                    <input type="text" class="form-input" name="kodeskema"
                           placeholder="Contoh: TKJ, RPL, MM ..."
                           value="<?= htmlspecialchars($kodeskema_val) ?>" autofocus>

                    <div class="form-label">Nama Skema <span style="color:var(--red)">*</span></div>
                    <textarea class="form-input" name="skema"
                              placeholder="Tulis nama lengkap skema sertifikasi ..."><?= htmlspecialchars($skema_val) ?></textarea>
                </div>

                <div class="divider-line"></div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas <?= $op === 'tambah' ? 'fa-plus' : 'fa-floppy-disk' ?>"></i>
                        <?= $op === 'tambah' ? 'Simpan Skema' : 'Perbarui Skema' ?>
                    </button>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">
                        <i class="fas fa-xmark"></i> Batal
                    </a>
                </div>
            </form>
        </div>

<?php
else:
/* ===========================
   TABEL DAFTAR SKEMA
=========================== */
?>
        <!-- TABLE CARD -->
        <div class="card" style="animation-delay:.05s">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-sitemap" style="color:var(--teal);margin-right:8px"></i>Daftar Skema Sertifikasi</h3>
                    <p>Semua skema yang terdaftar di LSP SMKN 1 Cibinong</p>
                </div>
                <a href="<?= $_SERVER['PHP_SELF'] ?>?op=tambah" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Skema
                </a>
            </div>

            <?php
            $querysskema  = "SELECT * FROM skema";
            $hasilsskema  = mysqli_query($conn, $querysskema);
            $total_skema  = mysqli_num_rows($hasilsskema);
            ?>

            <div style="margin-bottom:16px;display:flex;align-items:center;gap:10px">
                <span style="background:var(--teal-light);color:var(--teal-dark);padding:4px 12px;border-radius:6px;font-size:.78rem;font-weight:700">
                    <?= $total_skema ?> Skema Terdaftar
                </span>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th style="width:80px">ID</th>
                            <th style="width:150px">Kode Skema</th>
                            <th>Nama Skema</th>
                            <th style="width:160px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($total_skema > 0):
                        $no = 1;
                        // Reset pointer
                        mysqli_data_seek($hasilsskema, 0);
                        while ($datasskema = mysqli_fetch_array($hasilsskema)):
                    ?>
                        <tr>
                            <td class="row-num"><?= str_pad($no, 2, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <span class="badge badge-navy"><?= htmlspecialchars($datasskema['idskema']) ?></span>
                            </td>
                            <td>
                                <span class="badge badge-teal" style="font-size:.8rem;padding:4px 12px">
                                    <?= htmlspecialchars($datasskema['noskema'] ?? '') ?>
                                </span>
                            </td>
                            <td style="font-weight:600"><?= htmlspecialchars($datasskema['namaskema']) ?></td>
                            <td>
                                <div class="action-group" style="justify-content:center">
                                    <a href="<?= $_SERVER['PHP_SELF'] ?>?op=edit&id=<?= $datasskema['idskema'] ?>"
                                       class="btn btn-warning btn-sm">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    <a href="<?= $_SERVER['PHP_SELF'] ?>?op=hapus&id=<?= $datasskema['idskema'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus skema ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $no++;
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>Belum ada data skema. Tambahkan skema pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
<?php endif; ?>

    </div><!-- /content -->

    <!-- FOOTER -->
    <footer class="page-footer">
        <span>© <?= date('Y') ?> <strong>LSP SMKN 1 Cibinong</strong>. Semua hak dilindungi.</span>
        <span>Versi 1.0.0 &nbsp;·&nbsp; <?= $today ?>, <?= $current_time ?> WIB</span>
    </footer>

</div><!-- /main -->

</body>
</html>
<?php // tutup else level check
?>