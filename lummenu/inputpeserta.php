<!DOCTYPE html>
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kelola Peserta — LSP SMKN 1 Cibinong</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="js/lumino.glyphs.js"></script>
<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script>
$(document).ready(function(){
    $('#tanggal').datepicker({ format:"dd-mm-yyyy", autoclose:true });
});
</script>

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
    .topbar { background: #fff; border-bottom: 1px solid var(--border); padding: 0 32px; height: 68px; display: flex; align-items: center; gap: 16px; position: sticky; top: 0; z-index: 50; }
    .topbar-title { font-size: 1.1rem; font-weight: 700; flex: 1; }
    .topbar-title span { color: var(--text-sub); font-weight: 400; font-size: .875rem; margin-left: 8px; }
    .topbar-actions { display: flex; align-items: center; gap: 10px; }
    .date-chip { background: var(--teal-light); color: var(--teal-dark); font-size: .78rem; font-weight: 600; padding: 6px 14px; border-radius: 8px; display: flex; align-items: center; gap: 6px; }
    .icon-btn { width: 38px; height: 38px; border-radius: 10px; border: 1.5px solid var(--border); background: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-sub); transition: all .2s; }
    .icon-btn:hover { border-color: var(--teal); color: var(--teal); }

    /* CONTENT */
    .content { padding: 32px; display: flex; flex-direction: column; gap: 24px; }
    .card { background: #fff; border-radius: var(--radius); padding: 28px; box-shadow: var(--shadow); animation: fadeUp .4s ease both; }
    @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .section-head h3 { font-size: 1.05rem; font-weight: 700; }
    .section-head p { font-size: .78rem; color: var(--text-sub); margin-top: 2px; }

    /* ALERT */
    .alert-box { padding: 12px 18px; border-radius: 10px; font-size: .85rem; font-weight: 500; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 20px; }
    .alert-success { background: #DCFCE7; color: #15803D; border: 1px solid #86EFAC; }
    .alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FCA5A5; }
    .alert-warning { background: #FEFCE8; color: #A16207; border: 1px solid #FDE68A; }

    /* FORM */
    .form-section-title { font-size: .75rem; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: .6px; padding: 14px 0 10px; border-bottom: 1px solid var(--border); margin-bottom: 16px; }
    .form-grid { display: grid; grid-template-columns: 180px 1fr; gap: 13px; align-items: start; margin-bottom: 13px; }
    .form-grid-2 { display: grid; grid-template-columns: 180px 1fr 180px 1fr; gap: 13px; align-items: start; margin-bottom: 13px; }
    .form-label { font-size: .82rem; font-weight: 600; color: var(--text-sub); padding-top: 10px; }
    .form-input { width: 100%; font-size: .875rem; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-main); background: var(--off); transition: border-color .2s, box-shadow .2s; outline: none; }
    .form-input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px var(--teal-glow); background: #fff; }
    .form-input[readonly] { opacity: .65; cursor: not-allowed; }
    select.form-input { cursor: pointer; }
    textarea.form-input { resize: vertical; min-height: 80px; }
    .form-hint { font-size: .72rem; color: var(--text-muted); margin-top: 4px; }
    .form-actions { display: flex; gap: 10px; margin-top: 8px; padding-top: 16px; border-top: 1px solid var(--border); }
    .divider-line { height: 1px; background: var(--border); margin: 20px 0; }
    .radio-group { display: flex; gap: 16px; padding-top: 10px; }
    .radio-item { display: flex; align-items: center; gap: 6px; font-size: .85rem; cursor: pointer; }
    .radio-item input { accent-color: var(--teal); width: 15px; height: 15px; }

    /* BUTTONS */
    .btn { display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; border-radius: 10px; font-size: .85rem; font-weight: 600; cursor: pointer; text-decoration: none; border: 1.5px solid transparent; transition: all .2s; font-family: 'Plus Jakarta Sans', sans-serif; }
    .btn:hover { text-decoration: none; }
    .btn-primary  { background: var(--teal); color: #fff; box-shadow: 0 2px 8px rgba(59,191,191,.3); }
    .btn-primary:hover  { background: var(--teal-dark); color: #fff; }
    .btn-secondary{ background: #fff; color: var(--text-main); border-color: var(--border); }
    .btn-secondary:hover{ border-color: var(--teal); color: var(--teal-dark); background: var(--teal-light); }
    .btn-warning  { background: #FFF7ED; color: #C2410C; border-color: #FDBA74; }
    .btn-warning:hover  { background: var(--orange); color: #fff; border-color: var(--orange); }
    .btn-danger   { background: #FEF2F2; color: #B91C1C; border-color: #FCA5A5; }
    .btn-danger:hover   { background: var(--red); color: #fff; border-color: var(--red); }
    .btn-info     { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
    .btn-info:hover     { background: #1D4ED8; color: #fff; }
    .btn-sm { padding: 6px 14px; font-size: .78rem; border-radius: 8px; }

    /* SEARCH BAR */
    .search-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .search-input-wrap { display: flex; align-items: center; gap: 8px; background: var(--off); border: 1.5px solid var(--border); border-radius: 10px; padding: 8px 14px; flex: 1; min-width: 200px; }
    .search-input-wrap input { border: none; background: none; outline: none; font-size: .875rem; font-family: 'Plus Jakarta Sans',sans-serif; width: 100%; }
    .search-input-wrap i { color: var(--text-muted); }
    .search-select { font-size: .85rem; padding: 9px 12px; border: 1.5px solid var(--border); border-radius: 10px; background: var(--off); font-family: 'Plus Jakarta Sans',sans-serif; outline: none; cursor: pointer; }

    /* TABLE */
    .tbl-wrap { overflow-x: auto; }
    .tbl { width: 100%; border-collapse: collapse; }
    .tbl thead tr { background: var(--navy); }
    .tbl th { text-align: left; padding: 12px 14px; font-size: .72rem; font-weight: 700; letter-spacing: .7px; text-transform: uppercase; color: rgba(255,255,255,.75); }
    .tbl th:first-child { border-radius: 8px 0 0 8px; }
    .tbl th:last-child  { border-radius: 0 8px 8px 0; }
    .tbl td { padding: 11px 14px; font-size: .83rem; border-bottom: 1px solid var(--off); vertical-align: middle; }
    .tbl tr:last-child td { border-bottom: none; }
    .tbl tbody tr:hover td { background: #F0F9F9; }
    .row-num { font-family: 'DM Mono', monospace; color: var(--text-muted); font-size: .75rem; }
    .action-group { display: flex; gap: 5px; flex-wrap: wrap; }

    /* BADGE */
    .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 6px; font-size: .72rem; font-weight: 700; }
    .badge-green { background: #DCFCE7; color: #15803D; }
    .badge-red   { background: #FEF2F2; color: #B91C1C; }
    .badge-teal  { background: var(--teal-light); color: var(--teal-dark); }
    .peserta-avatar { width: 34px; height: 34px; border-radius: 50%; background: var(--teal-light); color: var(--teal-dark); display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: .72rem; flex-shrink: 0; }

    /* PAGINATION */
    .pagination { display: flex; align-items: center; gap: 6px; margin-top: 20px; flex-wrap: wrap; }
    .page-btn { padding: 6px 13px; border-radius: 8px; border: 1.5px solid var(--border); background: #fff; font-size: .8rem; font-weight: 600; color: var(--text-sub); cursor: pointer; text-decoration: none; transition: all .2s; }
    .page-btn:hover { border-color: var(--teal); color: var(--teal-dark); }
    .page-btn.active { background: var(--teal); color: #fff; border-color: var(--teal); }

    /* FOOTER */
    .page-footer { padding: 20px 32px; border-top: 1px solid var(--border); background: #fff; display: flex; align-items: center; justify-content: space-between; font-size: .78rem; color: var(--text-muted); margin-top: auto; }
    .page-footer strong { color: var(--teal-dark); }

    @media (max-width: 900px) {
        .main { margin-left: 0; }
        .form-grid, .form-grid-2 { grid-template-columns: 1fr; }
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
        <a href="inputpeserta.php"  class="nav-item active"><i class="fas fa-users"></i> Kelola Peserta</a>
        <a href="inputelemen.php"   class="nav-item"><i class="fas fa-star"></i> Kelola Kompetensi</a>
        <a href="inputtempattuk.php" class="nav-item"><i class="fas fa-building"></i> Kelola Tempat TUK</a>
        <div class="nav-label">Input Data</div>
        <a href="inputsyarat.php"        class="nav-item"><i class="fas fa-pen-to-square"></i> Input Persyaratan</a>
        <a href="inputkumpan.php"        class="nav-item"><i class="fas fa-pen-to-square"></i> Input Umpan Balik</a>
        <a href="inputprosesasesmen.php" class="nav-item"><i class="fas fa-pen-to-square"></i> Input Proses Asesmen</a>
        <a href="inputpengurus.php"      class="nav-item"><i class="fas fa-pen-to-square"></i> Input Pengurus</a>
        <div class="nav-label">Proses Uji</div>
        <a href="mapa.php"            class="nav-item"><i class="fas fa-paperclip"></i> MAPA</a>
        <a href="settanggal.php"      class="nav-item"><i class="fas fa-clock"></i> SET Tanggal</a>
        <a href="pemetaanasesor.php"  class="nav-item"><i class="fas fa-calendar-days"></i> Atur Jadwal</a>
        <a href="inputpraktek.php"    class="nav-item"><i class="fas fa-clipboard-check"></i> FR.IA.01 Ceklist Observasi</a>
        <a href="inputtestulis.php"   class="nav-item"><i class="fas fa-file-lines"></i> FR.IA.05 Tes Tertulis</a>
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
        <div class="topbar-title">Kelola Peserta <span>Manajemen data peserta uji kompetensi</span></div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i><?= $today ?></div>
            <button class="icon-btn"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">
<?php
$op = $_GET['op'] ?? '';
$pesan = ''; $pesan_tipe = '';

/* ============================================================
   PROSES POST
============================================================ */
if ($op == "append") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $nama     = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $notelp   = isset($_POST['notelp']) ? trim($_POST['notelp']) : '';
    $role     = "peserta";
    $tanggal  = !empty($_POST['tanggal']) ? $_POST['tanggal'] : date('Y-m-d');

    if (empty($username) || empty($password)) {
        $pesan = 'Gagal: Username dan Password wajib diisi.';
        $pesan_tipe = 'error';
    } else {
        $password_md5 = md5($password);
        $cek = mysqli_query($conn, "SELECT id FROM lsp_usertbl WHERE email='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $pesan = "Gagal: Username '$username' sudah terdaftar.";
            $pesan_tipe = 'error';
        } else {
            $query = "INSERT INTO users (nama, username, password, role, notelp, kode, linkttd) 
                      VALUES ('$nama', '$username', '$password_md5', '$role', '$notelp', '$tanggal', '')";
            $h = mysqli_query($conn, $query);
            $pesan = $h ? 'Peserta berhasil ditambahkan.' : 'Gagal menyimpan: ' . mysqli_error($conn);
            $pesan_tipe = $h ? 'success' : 'error';
        }
    }
    $op = '';
}
elseif ($op == "update") {
    $iduser    = $_POST['iduser'] ?? '';
    $emailuser = trim($_POST['emailuser'] ?? '');
    $nama      = $_POST['nama'] ?? '';
    $notelp    = $_POST['notelp'] ?? '';
    $pass      = !empty($_POST['password1']) ? md5($_POST['password1']) : null;
    $emaillama = trim($_POST['emaillama'] ?? '');

    if ($emailuser == $emaillama) {
        $q = $pass
            ? "UPDATE lsp_usertbl SET email='$emailuser',nama='$nama',notelp='$notelp',password='$pass' WHERE id='$iduser'"
            : "UPDATE lsp_usertbl SET email='$emailuser',nama='$nama',notelp='$notelp' WHERE id='$iduser'";
        $h = mysqli_query($conn, $q);
    } else {
        $cek = mysqli_query($conn, "SELECT email FROM lsp_usertbl WHERE email='$emailuser'");
        if (mysqli_num_rows($cek) > 0) { $h = false; $dup = true; }
        else {
            $q = $pass
                ? "UPDATE lsp_usertbl SET email='$emailuser',nama='$nama',notelp='$notelp',password='$pass' WHERE id='$iduser'"
                : "UPDATE lsp_usertbl SET email='$emailuser',nama='$nama',notelp='$notelp' WHERE id='$iduser'";
            $h = mysqli_query($conn, $q);
        }
    }
    $pesan = isset($dup) ? 'Gagal: email sudah digunakan.' : ($h ? 'Data peserta berhasil diperbarui.' : 'Gagal memperbarui data.');
    $pesan_tipe = ($h && !isset($dup)) ? 'success' : 'error';
    $op = '';
}
elseif ($op == "deletepost") {
    $h = mysqli_query($conn, "DELETE FROM lsp_usertbl WHERE id='".$_POST['iduser']."'");
    $pesan = $h ? 'Peserta berhasil dihapus.' : 'Gagal menghapus peserta.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}
elseif ($op == "postubahpass") {
    $passu = trim($_POST['passwordubah']);
    if (!empty($passu)) {
        $h = mysqli_query($conn, "UPDATE lsp_usertbl SET password='".md5($passu)."' WHERE id='".$_POST['iduser']."'");
        $pesan = $h ? 'Password berhasil diubah.' : 'Gagal mengubah password.';
        $pesan_tipe = $h ? 'success' : 'error';
    } else {
        $pesan = 'Tidak ada perubahan password.'; $pesan_tipe = 'warning';
    }
    $op = '';
}
elseif ($op == "updatestatus") {
    $idus  = $_GET['id'];
    $datast = mysqli_fetch_array(mysqli_query($conn, "SELECT status FROM lsp_usertbl WHERE id='$idus'"), MYSQLI_ASSOC);
    $sbaru  = ($datast['status'] == '1') ? '0' : '1';
    mysqli_query($conn, "UPDATE lsp_usertbl SET status='$sbaru' WHERE id='$idus'");
    $op = '';
}
elseif ($op == "batuploadpes") {
    $kodebat = $_POST['kelas'];
    $cek = mysqli_query($conn, "SELECT * FROM lsp_usertbl WHERE notelp='$kodebat'");
    if (mysqli_num_rows($cek) > 0) {
        $h = mysqli_query($conn, "DELETE FROM lsp_usertbl WHERE notelp='$kodebat'");
        $pesan = $h ? 'Pembatalan upload peserta berhasil.' : 'Gagal membatalkan.';
        $pesan_tipe = $h ? 'success' : 'error';
    } else {
        $pesan = 'Data tidak ditemukan.'; $pesan_tipe = 'warning';
    }
    $op = '';
}
elseif ($op == "updatebio") {
    $iduser    = $_POST['iduser'];
    $nama      = $_POST['nama'];
    $tmplahir  = $_POST['tmplahir'];
    $tgllahir  = date('Y-m-d', strtotime($_POST['tanggal']));
    $jk        = $_POST['jk'];
    $kebangsaan= $_POST['kebangsaan'];
    $alamat    = $_POST['alamat'];
    $kodepos   = $_POST['kodepos'];
    $tlprumah  = $_POST['rumah'];
    $hp        = $_POST['hp'];
    $tlpkantor = $_POST['kantor'];
    $pendidikan= $_POST['pendidikan'];
    $lembaga   = $_POST['lembaga'];
    $jurusan   = $_POST['jurusan'];
    $email     = trim($_POST['emailuser']);
    $nama_foto = $_POST['xpoto'] ?? '';
    $potopes   = '';

    if (!empty($_FILES['fotox3']['name'])) {
        $namasa = explode(" ", $nama);
        $namasb = ($namasa[0] ?? '').''.($namasa[1] ?? '');
        $nama_foto = $iduser.$namasb.$_FILES['fotox3']['name'];
        $ext = strtolower(pathinfo($nama_foto, PATHINFO_EXTENSION));
        if (in_array($ext, ['png','jpg'])) {
            if ($_FILES['fotox3']['size'] < 100000) {
                $moved = move_uploaded_file($_FILES['fotox3']['tmp_name'], "../siswa/gambardiri/".$nama_foto);
                $potopes = $moved ? 'Foto berhasil diupload.' : 'Foto gagal diupload.';
            } else { $potopes = 'Foto gagal: ukuran terlalu besar.'; }
        } else { $potopes = 'Foto gagal: tipe bukan PNG/JPG.'; }
    }

    $q = "UPDATE apl1 SET namasiswa='$nama',tmplahir='$tmplahir',tgllahir='$tgllahir',jeniskelamin='$jk',kebangsaan='$kebangsaan',alamat='$alamat',kodepos='$kodepos',tlprumah='$tlprumah',hp='$hp',tlpkantor='$tlpkantor',email='$email',pendidikan='$pendidikan',namalembaga='$lembaga',jurusan='$jurusan',poto='$nama_foto' WHERE email='$email'";
    $h = mysqli_query($conn, $q);
    mysqli_query($conn, "UPDATE lsp_usertbl SET nama='$nama' WHERE email='$email'");
    $pesan = $h ? 'Biodata berhasil diperbarui. '.$potopes : 'Gagal memperbarui biodata.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}
elseif ($op == "postuploaduser") {
    include "excel_reader2.php";
    $data   = new Spreadsheet_Excel_Reader($_FILES['uploadedfile']['tmp_name']);
    $baris  = $data->rowcount(0);
    $sukses = 0; $gagal = 0; $dup_list = [];
    for ($i = 2; $i <= $baris; $i++) {
        $nim = trim($data->val($i, 3));
        if (empty($nim)) continue;

        $cek = mysqli_query($conn, "SELECT username FROM users WHERE username='$nim'");
        if (mysqli_num_rows($cek) > 0) { 
            $dup_list[] = $nim; 
            $gagal++; 
            continue; 
        }

        $nm    = $data->val($i, 2);
        $pass  = md5($data->val($i, 4));
        $lv    = "peserta";
        $notlp = $data->val($i, 8);

        $query_upload = "INSERT INTO users (nama, linkttd, username, password, role, notelp, kode) 
                         VALUES ('$nm', '', '$nim', '$pass', '$lv', '$notlp', '".date('Y-m-d')."')";
        $h = mysqli_query($conn, $query_upload);

        if ($h) {
            $sukses++;
        } else {	
            $gagal++;
        }
    }
    $pesan = "$sukses peserta berhasil diimport, $gagal gagal." . (!empty($dup_list) ? ' Duplikat: '.implode(', ',$dup_list) : '');
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
/* ============================================================ FORM EDIT ============================================================ */
if ($op == 'edit'):
    $iduser = $_GET['iduser'] ?? '';
    $de = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lsp_usertbl WHERE id='$iduser'"), MYSQLI_ASSOC);
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-pen-to-square" style="color:var(--teal);margin-right:8px"></i>Edit Data Peserta</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=update">
        <input type="hidden" name="iduser"    value="<?= $de['id'] ?>">
        <input type="hidden" name="emaillama" value="<?= $de['email'] ?>">
        <div class="form-grid">
            <div class="form-label">Email <span style="color:var(--red)">*</span></div>
            <input type="text" name="emailuser" class="form-input" value="<?= htmlspecialchars($de['email']) ?>" required autofocus>
            <div class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></div>
            <input type="text" name="nama" class="form-input" value="<?= htmlspecialchars($de['nama']) ?>" required>
            <div class="form-label">No. Telp</div>
            <input type="text" name="notelp" class="form-input" value="<?= htmlspecialchars($de['notelp']) ?>">
            <div class="form-label">Password Baru</div>
            <div>
                <input type="password" name="password1" class="form-input" placeholder="Kosongkan jika tidak ingin mengubah">
                <p class="form-hint">Biarkan kosong agar password tidak berubah.</p>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ============================================================ FORM TAMBAH ============================================================ */
elseif ($op == 'tambah'):
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-user-plus" style="color:var(--teal);margin-right:8px"></i>Tambah Peserta Baru</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=append">
        <div class="form-grid">
            <div class="form-label">Username <span style="color:var(--red)">*</span></div>
            <input type="text" name="username" class="form-input" placeholder="Username / email peserta" autofocus required>
            <div class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></div>
            <input type="text" name="nama" class="form-input" placeholder="Nama lengkap peserta" required>
            <div class="form-label">No. Telp</div>
            <input type="text" name="notelp" class="form-input" placeholder="08xxxxxxxxxx">
            <div class="form-label">Password <span style="color:var(--red)">*</span></div>
            <input type="password" name="password" class="form-input" required>
            <div class="form-label">Re-Password <span style="color:var(--red)">*</span></div>
            <input type="password" name="repassword" class="form-input" required>
            <div class="form-label">Tanggal Uji / Registrasi</div>
            <input type="date" name="tanggal" class="form-input" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Tambah Peserta</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ============================================================ KONFIRMASI HAPUS ============================================================ */
elseif ($op == 'delete'):
    $iduser = $_GET['iduser'] ?? '';
    $dd = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lsp_usertbl WHERE id='$iduser'"), MYSQLI_ASSOC);
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-trash" style="color:var(--red);margin-right:8px"></i>Hapus Peserta</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="alert-box alert-error">
        <i class="fas fa-circle-xmark"></i>
        Yakin ingin menghapus peserta <strong><?= htmlspecialchars($dd['nama'] ?? '') ?></strong> (<?= htmlspecialchars($dd['email'] ?? '') ?>)?
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=deletepost">
        <input type="hidden" name="iduser" value="<?= $dd['id'] ?? '' ?>">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ============================================================ RESET PASSWORD ============================================================ */
elseif ($op == 'ubahpassword'):
    $idasesi = $_GET['idasesi'] ?? '';
    $rp = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lsp_usertbl WHERE id='$idasesi'"), MYSQLI_ASSOC);
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-key" style="color:var(--teal);margin-right:8px"></i>Reset Password</h3><p><?= htmlspecialchars($rp['nama'] ?? '') ?></p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=postubahpass">
        <input type="hidden" name="iduser" value="<?= $rp['id'] ?? '' ?>">
        <div class="form-grid">
            <div class="form-label">Password Baru</div>
            <div>
                <input type="text" name="passwordubah" class="form-input" placeholder="Kosongkan jika tidak diubah">
                <p class="form-hint">Biarkan kosong jika tidak ingin mengubah password.</p>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Simpan Password</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ============================================================ EDIT BIODATA ============================================================ */
elseif ($op == 'editbio'):
    $email = $_GET['email'] ?? '';
    $es = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM apl1 WHERE email='$email'"), MYSQLI_ASSOC);
    $tgl = !empty($es['tgllahir']) ? date("d-m-Y", strtotime($es['tgllahir'])) : '';
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-id-card" style="color:var(--teal);margin-right:8px"></i>Edit Biodata Peserta</h3><p><?= htmlspecialchars($es['namasiswa'] ?? '') ?></p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=updatebio" enctype="multipart/form-data">
        <input type="hidden" name="iduser"    value="<?= $es['idsiswa'] ?? '' ?>">
        <input type="hidden" name="emaillama" value="<?= $es['email'] ?? '' ?>">

        <div class="form-section-title"><i class="fas fa-user" style="margin-right:6px"></i>A. Biodata Peserta</div>
        <div class="form-grid">
            <div class="form-label">Nama Lengkap *</div>
            <input type="text" name="nama" class="form-input" value="<?= htmlspecialchars($es['namasiswa']??'') ?>" required autofocus>
            <div class="form-label">Tempat Lahir *</div>
            <input type="text" name="tmplahir" class="form-input" value="<?= htmlspecialchars($es['tmplahir']??'') ?>" required>
            <div class="form-label">Tanggal Lahir</div>
            <input type="text" name="tanggal" id="tanggal" class="form-input" value="<?= $tgl ?>" placeholder="dd-mm-yyyy">
            <div class="form-label">Jenis Kelamin</div>
            <div class="radio-group">
                <label class="radio-item"><input type="radio" name="jk" value="lk" <?= ($es['jeniskelamin']??'')==='lk'?'checked':'' ?>> Laki-laki</label>
                <label class="radio-item"><input type="radio" name="jk" value="pr" <?= ($es['jeniskelamin']??'')==='pr'?'checked':'' ?>> Perempuan</label>
            </div>
            <div class="form-label">Kebangsaan *</div>
            <input type="text" name="kebangsaan" class="form-input" value="<?= htmlspecialchars($es['kebangsaan']??'') ?>" required>
            <div class="form-label">Alamat *</div>
            <textarea name="alamat" class="form-input" required><?= htmlspecialchars($es['alamat']??'') ?></textarea>
            <div class="form-label">Kode Pos</div>
            <input type="text" name="kodepos" class="form-input" value="<?= htmlspecialchars($es['kodepos']??'') ?>">
            <div class="form-label">No. HP *</div>
            <input type="text" name="hp" class="form-input" value="<?= htmlspecialchars($es['hp']??'') ?>" required>
            <div class="form-label">No. Telp Rumah</div>
            <input type="text" name="rumah" class="form-input" value="<?= htmlspecialchars($es['tlprumah']??'') ?>">
            <div class="form-label">No. Telp Kantor</div>
            <input type="text" name="kantor" class="form-input" value="<?= htmlspecialchars($es['tlpkantor']??'') ?>">
            <div class="form-label">Email</div>
            <input type="text" name="emailuser" class="form-input" value="<?= htmlspecialchars($es['email']??'') ?>" readonly>
            <div class="form-label">Pendidikan Terakhir *</div>
            <input type="text" name="pendidikan" class="form-input" value="<?= htmlspecialchars($es['pendidikan']??'') ?>" required>
        </div>

        <div class="form-section-title"><i class="fas fa-graduation-cap" style="margin-right:6px"></i>B. Data Pendidikan</div>
        <div class="form-grid">
            <div class="form-label">Nama Sekolah / Lembaga *</div>
            <input type="text" name="lembaga" class="form-input" value="<?= htmlspecialchars($es['namalembaga']??'') ?>" required>
            <div class="form-label">Jurusan / Program *</div>
            <input type="text" name="jurusan" class="form-input" value="<?= htmlspecialchars($es['jurusan']??'') ?>" required>
            <div class="form-label">Foto</div>
            <?php if (empty($es['poto'])): ?>
            <div>
                <input type="file" name="fotox3" accept="image/*">
                <p class="form-hint">Format PNG/JPG, maks 100KB</p>
            </div>
            <?php else: ?>
            <div>
                <input type="text" name="xpoto" class="form-input" value="<?= htmlspecialchars($es['poto']) ?>" readonly>
                <p class="form-hint">Foto sudah ada. Kosongkan untuk tidak mengubah.</p>
            </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Biodata</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ============================================================ UPLOAD PESERTA ============================================================ */
elseif ($op == 'impeserta'):
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-file-excel" style="color:#16A34A;margin-right:8px"></i>Upload Peserta dari Excel</h3><p>Format kolom: No | Nama | Username/Email | Password | Status | Kode | Level | No Telp</p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form enctype="multipart/form-data" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>?op=postuploaduser">
        <div class="form-grid">
            <div class="form-label">File Excel (.xls)</div>
            <div>
                <input type="file" name="uploadedfile" accept=".xls" style="display:block;margin-bottom:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload & Import</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ============================================================ BATAL UPLOAD ============================================================ */
elseif ($op == 'batpeserta'):
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-ban" style="color:var(--red);margin-right:8px"></i>Batalkan Upload Peserta</h3><p>Hapus semua peserta berdasarkan kelas/kode</p></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=batuploadpes">
        <div class="form-grid">
            <div class="form-label">Pilih Kelas</div>
            <div>
                <select name="kelas" class="form-input">
                    <?php
                    $lp = mysqli_query($conn, "SELECT notelp FROM lsp_usertbl WHERE level='peserta' GROUP BY notelp");
                    while ($lpa = mysqli_fetch_array($lp, MYSQLI_ASSOC))
                        echo "<option value='{$lpa['notelp']}'>{$lpa['notelp']}</option>";
                    ?>
                </select>
                <p class="form-hint" style="margin-top:8px;color:var(--red)"><i class="fas fa-triangle-exclamation"></i> Semua peserta dengan kelas ini akan dihapus permanen!</p>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin membatalkan upload peserta kelas ini?')">
                <i class="fas fa-ban"></i> Batalkan
            </button>
        </div>
    </form>
</div>

<?php
/* ============================================================ DAFTAR UTAMA ============================================================ */
else:
    $dataPerPage = 25;
    $noPage  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset  = ($noPage - 1) * $dataPerPage;
    $txtcari    = $_POST['txtcari'] ?? '';
    $txtkriteria = $_POST['txtkriteria'] ?? 'nama';

    if (isset($_POST['sqlaction']) && $_POST['sqlaction'] == "SEARCH" && !empty($txtcari)) {
        $querymain = "SELECT * FROM lsp_usertbl WHERE level='peserta' AND $txtkriteria LIKE '%$txtcari%' ORDER BY id DESC LIMIT $offset, $dataPerPage";
        $querycount = "SELECT COUNT(*) AS jumData FROM lsp_usertbl WHERE level='peserta' AND $txtkriteria LIKE '%$txtcari%'";
    } else {
        $querymain  = "SELECT * FROM lsp_usertbl WHERE level='peserta' ORDER BY id DESC LIMIT $offset, $dataPerPage";
        $querycount = "SELECT COUNT(*) AS jumData FROM lsp_usertbl WHERE level='peserta'";
    }

    $hasilmain = mysqli_query($conn, $querymain);
    $jumData   = mysqli_fetch_array(mysqli_query($conn, $querycount), MYSQLI_ASSOC)['jumData'] ?? 0;
    $jumPage   = ceil($jumData / $dataPerPage);
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Peserta</h3>
            <p><?= $jumData ?> peserta terdaftar</p>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap">
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=batpeserta" class="btn btn-danger btn-sm"><i class="fas fa-ban"></i> Batal Upload</a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=impeserta"  class="btn btn-secondary btn-sm"><i class="fas fa-file-excel"></i> Upload Excel</a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=tambah"     class="btn btn-primary"><i class="fas fa-user-plus"></i> Tambah Peserta</a>
        </div>
    </div>

    <!-- SEARCH -->
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
        <input type="hidden" name="sqlaction" value="SEARCH">
        <div class="search-bar">
            <select name="txtkriteria" class="search-select">
                <option value="nama">Nama</option>
                <option value="email">Email</option>
            </select>
            <div class="search-input-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="txtcari" placeholder="Cari peserta..." value="<?= htmlspecialchars($txtcari) ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-rotate"></i> Reset</a>
        </div>
    </form>

    <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Peserta</th>
                    <th>Email / Username</th>
                    <th style="width:100px;text-align:center">Status</th>
                    <th style="width:100px">Tgl Reg</th>
                    <th style="width:240px;text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $no = ($noPage - 1) * $dataPerPage + 1;
            if ($hasilmain && mysqli_num_rows($hasilmain) > 0):
                while ($d = mysqli_fetch_array($hasilmain, MYSQLI_ASSOC)):
                    $aktif = ($d['status'] ?? '0') == '1';
                    $statusBadge = $aktif
                        ? '<a href="'.$_SERVER['PHP_SELF'].'?op=updatestatus&id='.$d['id'].'" class="badge badge-green" title="Klik untuk nonaktifkan"><i class="fas fa-circle-check"></i> Aktif</a>'
                        : '<a href="'.$_SERVER['PHP_SELF'].'?op=updatestatus&id='.$d['id'].'" class="badge badge-red" title="Klik untuk aktifkan"><i class="fas fa-circle-xmark"></i> Nonaktif</a>';
                ?>
                <tr>
                    <td class="row-num"><?= str_pad($no, 2, '0', STR_PAD_LEFT) ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="peserta-avatar"><?= strtoupper(substr($d['nama'],0,2)) ?></div>
                            <span style="font-weight:600"><?= htmlspecialchars($d['nama']) ?></span>
                        </div>
                    </td>
                    <td style="color:var(--text-sub);font-size:.82rem"><?= htmlspecialchars($d['email']) ?></td>
                    <td style="text-align:center"><?= $statusBadge ?></td>
                    <td style="font-family:'DM Mono',monospace;font-size:.78rem;color:var(--text-sub)"><?= htmlspecialchars($d['kode']??'') ?></td>
                    <td>
                        <div class="action-group" style="justify-content:center">
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=ubahpassword&idasesi=<?= $d['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-key"></i> Reset Pass</a>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=delete&iduser=<?= $d['id'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>
                        </div>
                    </td>
                </tr>
                <?php $no++; endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:30px">Belum ada data peserta.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php if ($noPage > 1): ?>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $noPage-1 ?>" class="page-btn"><i class="fas fa-chevron-left"></i> Kembali</a>
        <?php endif; ?>
        <?php
        $showPage = 0;
        for ($p = 1; $p <= $jumPage; $p++):
            if (($p >= $noPage-3 && $p <= $noPage+3) || $p == 1 || $p == $jumPage):
                if ($showPage == 1 && $p != 2) echo '<span style="color:var(--text-muted);padding:0 4px">...</span>';
                if ($showPage != ($jumPage-1) && $p == $jumPage) echo '<span style="color:var(--text-muted);padding:0 4px">...</span>';
        ?>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $p ?>" class="page-btn <?= $p==$noPage?'active':'' ?>"><?= $p ?></a>
        <?php $showPage = $p; endif; endfor; ?>
        <?php if ($noPage < $jumPage): ?>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $noPage+1 ?>" class="page-btn">Lanjutkan <i class="fas fa-chevron-right"></i></a>
        <?php endif; ?>
        <span style="font-size:.78rem;color:var(--text-muted);margin-left:8px"><?= $jumData ?> total peserta</span>
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