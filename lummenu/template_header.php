<?php
/**
 * ============================================================
 *  TEMPLATE HEADER — LSP SMKN 1 Cibinong
 * ============================================================
 *
 *  CARA PAKAI di setiap halaman PHP:
 *
 *  1. Pastikan baris paling atas halaman kamu seperti ini:
 *
 *     <?php
 *     session_start();
 *     include "../lsp_koneksi.php";
 *
 *     // Wajib diisi sebelum include template:
 *     $page_title   = "Kelola Skema";        // Judul di topbar
 *     $page_sub     = "Manajemen data skema"; // Sub-judul kecil
 *     $active_menu  = "inputskema";           // Nama file aktif (tanpa .php)
 *     $user_level   = "lsp";                  // "lsp" atau "asesor"
 *
 *     include "template_header.php";  // untuk halaman di folder lummenu/
 *     // ATAU
 *     include "../lummenu/template_header.php"; // untuk halaman di subfolder lain
 *     ?>
 *
 *  2. Tulis konten halaman kamu di sini (card, tabel, form, dll)
 *
 *  3. Di paling bawah halaman:
 *
 *     <?php include "template_footer.php"; ?>
 *
 * ============================================================
 */

// Variabel dengan nilai default kalau lupa diisi
$page_title   = $page_title   ?? 'Dashboard';
$page_sub     = $page_sub     ?? 'LSP SMKN 1 Cibinong';
$active_menu  = $active_menu  ?? '';
$user_level   = $user_level   ?? 'lsp';

// Cek login — harus sudah include lsp_koneksi.php sebelum template ini
if (empty($_SESSION['username']) AND empty($_SESSION['password'])) {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'>
    <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css' rel='stylesheet'>
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;margin:0}</style></head><body>
    <div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>
    <i class='fas fa-lock' style='font-size:2rem;color:#EF4444;margin-bottom:16px;display:block'></i>
    <h3 style='color:#1A2E3B;margin-bottom:16px'>Anda Harus Login Dahulu!</h3>
    <a href='../lsp_login.php' style='display:inline-block;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>
    </div></body></html>";
    exit;
}

// Ambil data user dari DB
$uname   = $_SESSION['username'] ?? '';
$lu      = "SELECT * FROM lsp_usertbl WHERE email='".$uname."'";
$reslu   = mysqli_query($conn, $lu);
$hasilu  = mysqli_fetch_array($reslu, MYSQLI_ASSOC);
$namax   = $hasilu['nama'] ?? 'User';
$today   = date('d F Y');
$curtime = date('H:i');     

// Helper: apakah menu ini aktif?
function isActive($menu, $active) {
    return $menu === $active ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($page_title) ?> — LSP SMKN 1 Cibinong</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
/* ============================================================
   DESIGN SYSTEM — variabel warna & ukuran
============================================================ */
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

/* ============================================================
   SIDEBAR
============================================================ */





/* Tambahkan ini untuk memastikan navigasi mengambil ruang yang benar */
.sidebar-nav { 
    padding: 16px 12px; 
    flex: 1 0 auto; /* Membiarkan nav memanjang sesuai kontennya */
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
    display: flex; align-items: center; gap: 12px;
    flex-shrink: 0;
}
.logo-box {
    width: 42px; height: 42px;
    background: var(--teal);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 16px; color: #fff;
    flex-shrink: 0;
}
.logo-text strong { display: block; color: #fff; font-size: .95rem; font-weight: 700; }
.logo-text span   { color: var(--teal); font-size: .72rem; font-weight: 500; letter-spacing: .5px; }

.sidebar-nav { padding: 16px 12px; flex: 1; }
.nav-label {
    color: rgba(255,255,255,.3);
    font-size: .67rem; font-weight: 700;
    letter-spacing: 1.2px; text-transform: uppercase;
    padding: 12px 12px 6px;
}
.nav-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 14px; border-radius: 10px;
    color: rgba(255,255,255,.55); text-decoration: none;
    font-size: .875rem; font-weight: 500;
    transition: all .2s; margin-bottom: 2px;
}
.nav-item:hover { background: rgba(255,255,255,.07); color: #fff; text-decoration: none; }
.nav-item.active {
    background: var(--teal); color: #fff;
    box-shadow: 0 4px 12px rgba(59,191,191,.35);
}
.nav-item i { width: 18px; text-align: center; font-size: .9rem; flex-shrink: 0; }
.nav-badge {
    margin-left: auto;
    background: var(--red); color: #fff;
    font-size: .65rem; font-weight: 700;
    padding: 2px 7px; border-radius: 99px;
}

.sidebar-footer {
    padding: 16px 14px;
    border-top: 1px solid rgba(255,255,255,.07);
    flex-shrink: 0;
}
.user-card {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 10px;
    background: rgba(255,255,255,.05);
}
.user-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--teal);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .85rem; color: #fff;
    flex-shrink: 0;
}
.user-info strong { display: block; color: #fff; font-size: .82rem; }
.user-info span   { color: var(--teal); font-size: .72rem; }
.btn-logout {
    margin-left: auto;
    color: rgba(255,255,255,.35);
    background: none; border: none;
    cursor: pointer; font-size: .85rem;
    transition: color .2s;
}
.btn-logout:hover { color: var(--red); }

/* ============================================================
   MAIN AREA
============================================================ */
/* Update bagian ini agar content terdorong ke kanan */
.main {
    margin-left: var(--sidebar-w); /* Pastikan ini sama dengan lebar sidebar */
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    width: calc(100% - var(--sidebar-w)); /* Tambahkan ini agar lebar main pas */
}

/* Pastikan topbar tidak 'melayang' menutupi konten */
.topbar {
    background: #fff;
    border-bottom: 1px solid var(--border);
    padding: 0 32px; 
    height: 68px;
    display: flex; 
    align-items: center; 
    gap: 16px;
    position: sticky; /* Ganti ke sticky agar dia tetap di atas tapi tidak menutupi konten di bawahnya */
    top: 0; 
    z-index: 50;
    width: 100%;
}

/* Tambahkan ini di bagian bawah style untuk handle layar kecil */
.topbar-title { font-size: 1.1rem; font-weight: 700; flex: 1; }
.topbar-title span { color: var(--text-sub); font-weight: 400; font-size: .875rem; margin-left: 8px; }
.topbar-actions { display: flex; align-items: center; gap: 10px; }
.date-chip {
    background: var(--teal-light); color: var(--teal-dark);
    font-size: .78rem; font-weight: 600;
    padding: 6px 14px; border-radius: 8px;
    display: flex; align-items: center; gap: 6px;
}
.icon-btn {
    width: 38px; height: 38px; border-radius: 10px;
    border: 1.5px solid var(--border); background: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--text-sub); font-size: .9rem;
    transition: all .2s; position: relative;
}
.icon-btn:hover { border-color: var(--teal); color: var(--teal); }
.icon-btn .dot {
    width: 8px; height: 8px;
    background: var(--red); border-radius: 50%;
    position: absolute; top: 6px; right: 6px;
    border: 2px solid #fff;
}

/* ============================================================
   CONTENT WRAPPER
============================================================ */
.content {
    padding: 32px;
    flex: 1; /* Ini supaya konten ngedorong footer ke paling bawah */
}
.page-footer {
    background: #fff;
    padding: 20px 32px;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    color: var(--text-sub);
}
.page-footer strong { color: var(--teal-dark); }
/* ============================================================
   CARD
============================================================ */
.card {
    background: #fff; border-radius: var(--radius);
    padding: 28px; box-shadow: var(--shadow);
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
.section-head p  { font-size: .78rem; color: var(--text-sub); margin-top: 2px; }

/* ============================================================
   ALERT BOXES
============================================================ */
.alert-box {
    padding: 12px 18px; border-radius: 10px;
    font-size: .85rem; font-weight: 500;
    display: flex; align-items: flex-start; gap: 10px;
    margin-bottom: 20px;
}
.alert-success { background: #DCFCE7; color: #15803D; border: 1px solid #86EFAC; }
.alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FCA5A5; }
.alert-warning { background: #FEFCE8; color: #A16207; border: 1px solid #FDE68A; }
.alert-info    { background: var(--teal-light); color: var(--teal-dark); border: 1px solid #99D9D9; }
.alert-auto-hide { transition: opacity .5s; }

/* ============================================================
   FORM
============================================================ */
.form-grid { display: grid; grid-template-columns: 170px 1fr; gap: 13px; align-items: start; margin-bottom: 13px; }
.form-label { font-size: .82rem; font-weight: 600; color: var(--text-sub); padding-top: 10px; }
.form-input {
    width: 100%; font-size: .875rem; padding: 10px 14px;
    border: 1.5px solid var(--border); border-radius: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-main); background: var(--off);
    transition: border-color .2s, box-shadow .2s; outline: none;
}
.form-input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px var(--teal-glow); background: #fff; }
.form-input[readonly] { opacity: .65; cursor: not-allowed; }
select.form-input { cursor: pointer; }
textarea.form-input { resize: vertical; min-height: 80px; }
.form-hint { font-size: .72rem; color: var(--text-muted); margin-top: 4px; }
.form-actions { display: flex; gap: 10px; margin-top: 8px; padding-top: 16px; border-top: 1px solid var(--border); }
.form-section-title {
    font-size: .75rem; font-weight: 700; color: var(--text-sub);
    text-transform: uppercase; letter-spacing: .6px;
    padding: 14px 0 10px; border-bottom: 1px solid var(--border); margin-bottom: 16px;
}
.divider-line { height: 1px; background: var(--border); margin: 20px 0; }
.radio-group { display: flex; gap: 16px; padding-top: 10px; }
.radio-item { display: flex; align-items: center; gap: 6px; font-size: .85rem; cursor: pointer; }
.radio-item input { accent-color: var(--teal); width: 15px; height: 15px; }

/* ============================================================
   BUTTONS
============================================================ */
.btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 20px; border-radius: 10px;
    font-size: .85rem; font-weight: 600;
    cursor: pointer; text-decoration: none;
    border: 1.5px solid transparent;
    transition: all .2s;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.btn:hover { text-decoration: none; }
.btn-primary   { background: var(--teal);  color: #fff; box-shadow: 0 2px 8px rgba(59,191,191,.3); }
.btn-primary:hover   { background: var(--teal-dark); color: #fff; }
.btn-secondary { background: #fff; color: var(--text-main); border-color: var(--border); }
.btn-secondary:hover { border-color: var(--teal); color: var(--teal-dark); background: var(--teal-light); }
.btn-success   { background: #22C55E; color: #fff; border-color: #22C55E; }
.btn-success:hover   { background: #16A34A; color: #fff; }
.btn-warning   { background: #FFF7ED; color: #C2410C; border-color: #FDBA74; }
.btn-warning:hover   { background: var(--orange); color: #fff; border-color: var(--orange); }
.btn-danger    { background: #FEF2F2; color: #B91C1C; border-color: #FCA5A5; }
.btn-danger:hover    { background: var(--red); color: #fff; border-color: var(--red); }
.btn-info      { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
.btn-info:hover      { background: #1D4ED8; color: #fff; }
.btn-purple    { background: #F5F3FF; color: #7C3AED; border-color: #DDD6FE; }
.btn-purple:hover    { background: #7C3AED; color: #fff; }
.btn-print     { background: var(--navy); color: #fff; border-color: var(--navy); }
.btn-print:hover     { background: var(--navy-soft); color: #fff; }
.btn-sm { padding: 6px 14px; font-size: .78rem; border-radius: 8px; }

/* ============================================================
   TABLE
============================================================ */
.tbl-wrap { overflow-x: auto; }
.tbl { width: 100%; border-collapse: collapse; }
.tbl thead tr { background: var(--navy); }
.tbl th {
    text-align: left; padding: 12px 14px;
    font-size: .72rem; font-weight: 700;
    letter-spacing: .7px; text-transform: uppercase;
    color: rgba(255,255,255,.75);
}
.tbl th:first-child { border-radius: 8px 0 0 8px; }
.tbl th:last-child  { border-radius: 0 8px 8px 0; }
.tbl td {
    padding: 12px 14px; font-size: .83rem;
    border-bottom: 1px solid var(--off);
    vertical-align: middle;
}
.tbl tr:last-child td { border-bottom: none; }
.tbl tbody tr { transition: background .15s; }
.tbl tbody tr:hover td { background: #F0F9F9; }
.row-num { font-family: 'DM Mono', monospace; color: var(--text-muted); font-size: .75rem; }
.action-group { display: flex; gap: 5px; flex-wrap: wrap; }

/* ============================================================
   BADGES
============================================================ */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 6px;
    font-size: .72rem; font-weight: 700;
}
.badge-teal   { background: var(--teal-light); color: var(--teal-dark); }
.badge-navy   { background: #EFF6FF; color: #1D4ED8; }
.badge-green  { background: #DCFCE7; color: #15803D; }
.badge-red    { background: #FEF2F2; color: #B91C1C; }
.badge-orange { background: #FFF7ED; color: #C2410C; }
.badge-purple { background: #F5F3FF; color: #7C3AED; }
.badge-gray   { background: #F1F5F9; color: #64748B; }

/* ============================================================
   SEARCH BAR
============================================================ */
.search-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
.search-input-wrap {
    display: flex; align-items: center; gap: 8px;
    background: var(--off); border: 1.5px solid var(--border);
    border-radius: 10px; padding: 8px 14px;
    flex: 1; min-width: 200px;
}
.search-input-wrap input { border: none; background: none; outline: none; font-size: .875rem; font-family: 'Plus Jakarta Sans',sans-serif; width: 100%; }
.search-input-wrap i { color: var(--text-muted); }
.search-select { font-size: .85rem; padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 10px; background: var(--off); font-family: 'Plus Jakarta Sans',sans-serif; outline: none; cursor: pointer; }

/* ============================================================
   PAGINATION
============================================================ */
.pagination { display: flex; align-items: center; gap: 6px; margin-top: 20px; flex-wrap: wrap; }
.page-btn { padding: 6px 13px; border-radius: 8px; border: 1.5px solid var(--border); background: #fff; font-size: .8rem; font-weight: 600; color: var(--text-sub); cursor: pointer; text-decoration: none; transition: all .2s; }
.page-btn:hover { border-color: var(--teal); color: var(--teal-dark); }
.page-btn.active { background: var(--teal); color: #fff; border-color: var(--teal); }

/* ============================================================
   PRINT
============================================================ */
@media print {
    .sidebar, .topbar, .page-footer, .no-print { display: none !important; }
    .main { margin-left: 0; }
    .content { padding: 0; }
    .card { box-shadow: none; border: 1px solid #ddd; }
}

/* ============================================================
   RESPONSIVE
============================================================ */
@media (max-width: 900px) {
    



    .main { margin-left: 0; }
    .form-grid { grid-template-columns: 1fr; }
}
@media (max-width: 900px) {
    .main {
        margin-left: 0;
        width: 100%;
    }
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
        <?php if ($user_level === 'lsp'): ?>
        <!-- MENU UNTUK LEVEL LSP (ADMIN) -->
        <a href="dashboardbaru.php" class="nav-item <?= isActive('dashboard', $active_menu) ?>"> <i class="fas fa-gauge-high"></i> Dashboard</a>

        <div class="nav-label">Manajemen Data</div>
        <a href="inputskema.php"     class="nav-item <?= isActive('inputskema', $active_menu) ?>"><i class="fas fa-sitemap"></i> Kelola Skema</a>
        <a href="inputunit.php"      class="nav-item <?= isActive('inputunit', $active_menu) ?>"><i class="fas fa-cubes"></i> Kelola Unit</a>
        <a href="inputasesor.php"    class="nav-item <?= isActive('inputasesor', $active_menu) ?>"><i class="fas fa-user-tie"></i> Kelola Asesor</a>
        <a href="inputpeserta.php"   class="nav-item <?= isActive('inputpeserta', $active_menu) ?>"><i class="fas fa-users"></i> Kelola Peserta</a>
        <a href="inputelemen.php"    class="nav-item <?= isActive('inputelemen', $active_menu) ?>"><i class="fas fa-star"></i> Kelola Kompetensi</a>
        <a href="inputtempattuk.php" class="nav-item <?= isActive('inputtempattuk', $active_menu) ?>"><i class="fas fa-building"></i> Kelola Tempat TUK</a>

        <div class="nav-label">Input Data</div>
        <a href="inputsyarat.php"        class="nav-item <?= isActive('inputsyarat', $active_menu) ?>"><i class="fas fa-pen-to-square"></i> Input Persyaratan</a>
        <a href="inputkumpan.php"        class="nav-item <?= isActive('inputkumpan', $active_menu) ?>"><i class="fas fa-pen-to-square"></i> Input Umpan Balik</a>
        <a href="inputprosesasesmen.php" class="nav-item <?= isActive('inputprosesasesmen', $active_menu) ?>"><i class="fas fa-pen-to-square"></i> Input Proses Asesmen</a>
        <a href="inputpengurus.php"      class="nav-item <?= isActive('inputpengurus', $active_menu) ?>"><i class="fas fa-pen-to-square"></i> Input Pengurus</a>

        <div class="nav-label">Proses Uji</div>
        <a href="mapa.php"           class="nav-item <?= isActive('mapa', $active_menu) ?>"><i class="fas fa-paperclip"></i> MAPA</a>
        <a href="settanggal.php"     class="nav-item <?= isActive('settanggal', $active_menu) ?>"><i class="fas fa-clock"></i> SET Tanggal</a>
        <a href="pemetaanasesor.php" class="nav-item <?= isActive('pemetaanasesor', $active_menu) ?>"><i class="fas fa-calendar-days"></i> Atur Jadwal</a>
        <a href="inputpraktek.php"   class="nav-item <?= isActive('inputpraktek', $active_menu) ?>"><i class="fas fa-clipboard-check"></i> FR.IA.01 Ceklist Observasi</a>
        <a href="inputtestulis.php"  class="nav-item <?= isActive('inputtestulis', $active_menu) ?>"><i class="fas fa-file-lines"></i> FR.IA.05 Tes Tertulis</a>

        <div class="nav-label">Validasi & Monitor</div>
        <a href="validasiapl1lsp.php" class="nav-item <?= isActive('validasiapl1lsp', $active_menu) ?>"><i class="fas fa-check-double"></i> Validasi APL1</a>
        <a href="monitorasesi.php"    class="nav-item <?= isActive('monitorasesi', $active_menu) ?>"><i class="fas fa-desktop"></i> Monitoring</a>
        <a href="backupdata.php"      class="nav-item <?= isActive('backupdata', $active_menu) ?>"><i class="fas fa-download"></i> Backup Data</a>

        <?php else: ?>
        <!-- MENU UNTUK LEVEL ASESOR -->
        <div class="nav-label">Menu Asesor</div>
        <a href="validasiapl2.php" class="nav-item <?= isActive('validasiapl2', $active_menu) ?>"><i class="fas fa-pen-to-square"></i> Validasi APL2</a>
        <a href="asesormain.php"   class="nav-item <?= isActive('asesormain', $active_menu) ?>"><i class="fas fa-folder-open"></i> FR.IA.08 Portofolio</a>
        <a href="observasi.php"    class="nav-item <?= isActive('observasi', $active_menu) ?>"><i class="fas fa-eye"></i> FR.IA.01 Observasi</a>

        <div class="nav-label">Rekaman & Laporan</div>
        <a href="mak2.php"     class="nav-item <?= isActive('mak2', $active_menu) ?>"><i class="fas fa-file-lines"></i> FR.AK.02 Rekaman Asesmen</a>
        <a href="mak5.php"     class="nav-item <?= isActive('mak5', $active_menu) ?>"><i class="fas fa-chart-bar"></i> FR.AK.05 Laporan Asesmen</a>
        <a href="mak6baru.php" class="nav-item <?= isActive('mak6baru', $active_menu) ?>"><i class="fas fa-map"></i> FR.AK.06 Meninjau Proses</a>
        <a href="rekapasesi.php" class="nav-item <?= isActive('rekapasesi', $active_menu) ?>"><i class="fas fa-calendar-check"></i> Rekap Hasil Tes</a>

        <div class="nav-label">Perencanaan</div>
        <a href="mapaasesor.php"       class="nav-item <?= isActive('mapaasesor', $active_menu) ?>"><i class="fas fa-sitemap"></i> FR.MAPA.01 Merencanakan</a>
        <a href="pihakketiga.php"      class="nav-item <?= isActive('pihakketiga', $active_menu) ?>"><i class="fas fa-users"></i> FR.IA.10 Pihak Ketiga</a>
        <a href="ceklistintrumen.php"  class="nav-item <?= isActive('ceklistintrumen', $active_menu) ?>"><i class="fas fa-clipboard-list"></i> FR.IA.11 Ceklist Instrumen</a>

        <div class="nav-label">Akun</div>
        <a href="tandatanganass.php" class="nav-item <?= isActive('tandatanganass', $active_menu) ?>"><i class="fas fa-signature"></i> Tanda Tangan</a>
        <?php endif; ?>

        <!-- LOGOUT (selalu muncul) -->
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7); margin-top:8px">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= strtoupper(substr($namax, 0, 2)) ?></div>
            <div class="user-info">
                <strong><?= htmlspecialchars($namax) ?></strong>
                <span><?= $user_level === 'lsp' ? 'LSP Admin' : 'Asesor LSP' ?></span>
            </div>
            <button class="btn-logout" onclick="window.location='../logout.php'" title="Logout">
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
            <?= htmlspecialchars($page_title) ?>
            <span><?= htmlspecialchars($page_sub) ?></span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip">
                <i class="fas fa-calendar"></i>
                <?= $today ?>
            </div>
            <button class="icon-btn">
                <i class="fas fa-bell"></i>
                <span class="dot"></span>
            </button>
        </div>
    </header>

    <!-- KONTEN HALAMAN MULAI DI SINI -->
    <div class="content">