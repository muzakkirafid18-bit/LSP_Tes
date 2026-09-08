<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Validasi APL 2 — LSP SMKN 1 Cibinong</title>

<!-- Font & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<!-- Script lama yang masih dibutuhkan -->
<script src="js/lumino.glyphs.js"></script>
<script src="../js/jquery-2.2.3.min.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>
<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/bootstrap.js"></script>

<script type="text/javascript">
window.setTimeout(function() {
    var alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(function(el) {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(function(){ el.remove(); }, 500);
    });
}, 3000);

var win = null;
function NewWindow(mypage,myname,w,h,scroll){
    LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
    TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
    settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable';
    win = window.open(mypage,myname,settings);
}
</script>

<?php
include "../lsp_koneksi.php";
$today = date('d F Y');
$current_time = date('H:i');

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
    echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<i class='fas fa-lock' style='font-size:2rem;color:#EF4444;margin-bottom:16px'></i>";
    echo "<h3 style='font-family:Plus Jakarta Sans,sans-serif;color:#1A2E3B;margin-bottom:8px'>Anda Harus Login Dahulu!</h3>";
    echo "<a href='../lsp_login.php' style='display:inline-block;margin-top:16px;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>";
    echo "</div>";
    exit;
}
if ($_SESSION['level'] != 'asesor') {
    echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<i class='fas fa-ban' style='font-size:2rem;color:#EF4444;margin-bottom:16px'></i>";
    echo "<h3 style='font-family:Plus Jakarta Sans,sans-serif;color:#1A2E3B;margin-bottom:8px'>Anda Tidak Punya Hak Akses!</h3>";
    echo "<a href='../lsp_login.php' style='display:inline-block;margin-top:16px;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>";
    echo "</div>";
    exit;
}

if(isset($_SESSION['username'])) { $uname = $_SESSION['username']; }
$l = "SELECT * FROM lsp_usertbl WHERE email='".$uname."'";
$resultx   = mysqli_query($conn, $l);
$hasilx    = mysqli_fetch_array($resultx);
$namax     = $hasilx['nama'];
$idasesor  = $hasilx['id'];
$ttd       = $hasilx['linkttd'];
$idasesorxv = $hasilx['id'];
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
        font-weight: 800; font-size: 16px; color: var(--white);
        flex-shrink: 0;
    }
    .logo-text { line-height: 1.2; }
    .logo-text strong { display: block; color: var(--white); font-size: .95rem; font-weight: 700; }
    .logo-text span { color: var(--teal); font-size: .72rem; font-weight: 500; letter-spacing: .5px; }

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
        transition: all .2s; margin-bottom: 2px; cursor: pointer;
    }
    .nav-item:hover { background: rgba(255,255,255,.07); color: var(--white); text-decoration: none; }
    .nav-item.active {
        background: var(--teal); color: var(--white);
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
        padding: 10px 12px; border-radius: 10px;
        background: rgba(255,255,255,.05);
    }
    .user-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--teal);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .85rem; color: var(--white); flex-shrink: 0;
    }
    .user-info strong { display: block; color: var(--white); font-size: .82rem; }
    .user-info span { color: var(--teal); font-size: .72rem; }
    .btn-logout {
        margin-left: auto; color: rgba(255,255,255,.35);
        background: none; border: none; cursor: pointer;
        font-size: .85rem; transition: color .2s;
    }
    .btn-logout:hover { color: var(--red); }

    /* ===== MAIN ===== */
    .main {
        margin-left: var(--sidebar-w);
        flex: 1; display: flex; flex-direction: column; min-height: 100vh;
    }

    /* ===== TOPBAR ===== */
    .topbar {
        background: var(--white);
        border-bottom: 1px solid var(--border);
        padding: 0 32px; height: 68px;
        display: flex; align-items: center; gap: 16px;
        position: sticky; top: 0; z-index: 50;
    }
    .topbar-title { font-size: 1.1rem; font-weight: 700; flex: 1; }
    .topbar-title span { color: var(--text-sub); font-weight: 400; font-size: .875rem; margin-left: 8px; }
    .topbar-actions { display: flex; align-items: center; gap: 10px; }
    .icon-btn {
        width: 38px; height: 38px; border-radius: 10px;
        border: 1.5px solid var(--border); background: var(--white);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-sub); font-size: .9rem;
        transition: all .2s;
    }
    .icon-btn:hover { border-color: var(--teal); color: var(--teal); }
    .date-chip {
        background: var(--teal-light); color: var(--teal-dark);
        font-size: .78rem; font-weight: 600; padding: 6px 14px;
        border-radius: 8px; display: flex; align-items: center; gap: 6px;
    }

    /* ===== CONTENT ===== */
    .content { padding: 32px; display: flex; flex-direction: column; gap: 24px; }

    /* ===== CARD ===== */
    .card {
        background: var(--white); border-radius: var(--radius);
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
    .section-head p { font-size: .78rem; color: var(--text-sub); margin-top: 2px; }

    /* ===== ALERT ===== */
    .alert-box {
        padding: 12px 18px; border-radius: 10px;
        font-size: .85rem; font-weight: 500;
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 20px;
    }
    .alert-success { background: #DCFCE7; color: #15803D; border: 1px solid #86EFAC; }
    .alert-error   { background: #FEF2F2; color: #B91C1C; border: 1px solid #FCA5A5; }
    .alert-warning { background: #FEFCE8; color: #A16207; border: 1px solid #FDE68A; }
    .alert-info    { background: var(--teal-light); color: var(--teal-dark); border: 1px solid #99D9D9; }

    /* ===== TABLE ===== */
    .tbl-wrap { overflow-x: auto; }
    .tbl { width: 100%; border-collapse: collapse; }
    .tbl thead tr { background: var(--navy); }
    .tbl th {
        text-align: left; padding: 12px 16px;
        font-size: .72rem; font-weight: 700;
        letter-spacing: .7px; text-transform: uppercase;
        color: rgba(255,255,255,.75);
    }
    .tbl th:first-child { border-radius: 8px 0 0 8px; }
    .tbl th:last-child  { border-radius: 0 8px 8px 0; }
    .tbl td {
        padding: 13px 16px; font-size: .845rem;
        border-bottom: 1px solid var(--off);
        vertical-align: middle;
    }
    .tbl tr:last-child td { border-bottom: none; }
    .tbl tbody tr { transition: background .15s; }
    .tbl tbody tr:hover td { background: #F0F9F9; }

    /* ===== BADGES ===== */
    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 6px;
        font-size: .72rem; font-weight: 700;
    }
    .badge-green  { background: #DCFCE7; color: #15803D; }
    .badge-red    { background: #FEF2F2; color: #B91C1C; }
    .badge-orange { background: #FFF7ED; color: #C2410C; }
    .badge-teal   { background: var(--teal-light); color: var(--teal-dark); }
    .badge-navy   { background: #EFF6FF; color: #1D4ED8; }
    .badge-gray   { background: #F1F5F9; color: #64748B; }

    /* ===== BUTTONS ===== */
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
    .btn-primary { background: var(--teal); color: var(--white); box-shadow: 0 2px 8px rgba(59,191,191,.3); }
    .btn-primary:hover { background: var(--teal-dark); color: var(--white); }
    .btn-secondary { background: var(--white); color: var(--text-main); border-color: var(--border); }
    .btn-secondary:hover { border-color: var(--teal); color: var(--teal-dark); background: var(--teal-light); }
    .btn-success { background: #22C55E; color: var(--white); border-color: #22C55E; }
    .btn-success:hover { background: #16A34A; color: var(--white); }
    .btn-warning { background: #FFF7ED; color: #C2410C; border-color: #FDBA74; }
    .btn-warning:hover { background: #F97316; color: var(--white); border-color: #F97316; }
    .btn-danger { background: #FEF2F2; color: #B91C1C; border-color: #FCA5A5; }
    .btn-danger:hover { background: #EF4444; color: var(--white); border-color: #EF4444; }
    .btn-print { background: var(--navy); color: var(--white); border-color: var(--navy); }
    .btn-print:hover { background: var(--navy-soft); color: var(--white); }
    .btn-sm { padding: 6px 14px; font-size: .78rem; border-radius: 8px; }

    /* ===== FORM ===== */
    .form-grid { display: grid; grid-template-columns: 180px 1fr; gap: 14px; align-items: start; margin-bottom: 18px; }
    .form-label { font-size: .82rem; font-weight: 600; color: var(--text-sub); padding-top: 10px; }
    .form-input {
        width: 100%; font-size: .875rem; padding: 10px 14px;
        border: 1.5px solid var(--border); border-radius: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-main);
        background: var(--off); transition: border-color .2s, box-shadow .2s; outline: none;
    }
    .form-input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px var(--teal-glow); background: var(--white); }
    select.form-input { cursor: pointer; }
    textarea.form-input { resize: vertical; min-height: 80px; }
    .form-actions { display: flex; gap: 10px; margin-top: 8px; padding-top: 16px; border-top: 1px solid var(--border); }

    /* ===== UNIT VALIDATION TABLE ===== */
    .unit-tbl { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .unit-tbl th {
        text-align: left; padding: 10px 14px;
        background: var(--navy-soft); color: rgba(255,255,255,.8);
        font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px;
    }
    .unit-tbl th:first-child { border-radius: 8px 0 0 0; }
    .unit-tbl th:last-child  { border-radius: 0 8px 0 0; }
    .unit-tbl td {
        padding: 11px 14px; font-size: .83rem;
        border-bottom: 1px solid var(--off); vertical-align: middle;
    }
    .unit-tbl tr:hover td { background: #F0F9F9; }

    /* APL2 deep-validation table */
    .apl-tbl { width: 100%; border-collapse: collapse; font-size: .82rem; margin-top: 12px; }
    .apl-tbl th {
        padding: 9px 12px; background: var(--navy);
        color: rgba(255,255,255,.8); font-size: .7rem;
        font-weight: 700; text-transform: uppercase; letter-spacing: .5px;
    }
    .apl-tbl td { padding: 10px 12px; border-bottom: 1px solid var(--off); vertical-align: middle; }
    .apl-tbl .unit-head td {
        background: var(--off); font-weight: 700;
        color: var(--navy); font-size: .85rem; border-left: 3px solid var(--teal);
        padding-left: 14px;
    }
    .apl-tbl .elemen-head td {
        background: #EFF6FF; font-weight: 600; color: #1D4ED8;
        padding-left: 24px; font-size: .82rem;
    }
    .apl-tbl .sub-row td { padding-left: 32px; }
    .apl-tbl .empty-row td { color: var(--red); font-style: italic; }

    .check-group { display: flex; gap: 14px; align-items: center; }
    .check-item { display: flex; align-items: center; gap: 5px; cursor: pointer; }
    .check-item input[type=checkbox] { cursor: pointer; accent-color: var(--teal); width: 15px; height: 15px; }

    /* Rekomendasi */
    .rekom-card { background: var(--off); border-radius: 12px; padding: 20px; margin-top: 16px; }
    .rekom-row { display: flex; gap: 32px; align-items: flex-start; margin-bottom: 16px; }
    .rekom-section { flex: 1; }
    .rekom-section h4 { font-size: .85rem; font-weight: 700; margin-bottom: 12px; color: var(--text-sub); text-transform: uppercase; letter-spacing: .5px; }
    .radio-group { display: flex; gap: 16px; }
    .radio-item { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 8px 16px; border: 1.5px solid var(--border); border-radius: 9px; transition: all .2s; }
    .radio-item:hover { border-color: var(--teal); background: var(--teal-light); }
    .radio-item input { accent-color: var(--teal); width: 16px; height: 16px; }
    .ttd-box { text-align: center; }
    .ttd-box img { max-height: 60px; border: 1px solid var(--border); border-radius: 8px; padding: 4px; background: var(--white); }
    .ttd-box p { font-size: .78rem; color: var(--text-sub); margin-top: 4px; }

    /* ===== DIVIDER ===== */
    .divider-line { height: 1px; background: var(--border); margin: 20px 0; }

    /* ===== ROW NUM ===== */
    .row-num { font-family: 'DM Mono', monospace; color: var(--text-muted); font-size: .75rem; }

    /* ===== EMPTY STATE ===== */
    .empty-state { text-align: center; padding: 48px 24px; color: var(--text-muted); }
    .empty-state i { font-size: 2.5rem; margin-bottom: 12px; opacity: .4; display: block; }

    /* ===== PRINT ===== */
    .print-bar { display: flex; gap: 10px; margin-bottom: 20px; }

    /* ===== FOOTER ===== */
    .page-footer {
        padding: 20px 32px; border-top: 1px solid var(--border);
        background: var(--white);
        display: flex; align-items: center; justify-content: space-between;
        font-size: .78rem; color: var(--text-muted); margin-top: auto;
    }
    .page-footer strong { color: var(--teal-dark); }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        



        .main { margin-left: 0; }
        .form-grid { grid-template-columns: 1fr; }
        .rekom-row { flex-direction: column; }
    }

    @media print {
        .sidebar, .topbar, .page-footer, .print-bar, .no-print { display: none !important; }
        .main { margin-left: 0; }
        .content { padding: 0; }
        .card { box-shadow: none; border: 1px solid #ddd; }
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
        <div class="nav-label">Menu Asesor</div>
        <a href="validasiapl2.php" class="nav-item active">
            <i class="fas fa-pen-to-square"></i> Validasi APL2
        </a>
        <a href="asesormain.php" class="nav-item">
            <i class="fas fa-folder-open"></i> FR.IA.08 Portofolio
        </a>
        <a href="observasi.php" class="nav-item">
            <i class="fas fa-eye"></i> FR.IA.01 Observasi
        </a>

        <div class="nav-label">Rekaman & Laporan</div>
        <a href="mak2.php" class="nav-item">
            <i class="fas fa-file-lines"></i> FR.AK.02 Rekaman Asesmen
        </a>
        <a href="mak5.php" class="nav-item">
            <i class="fas fa-chart-bar"></i> FR.AK.05 Laporan Asesmen
        </a>
        <a href="mak6baru.php" class="nav-item">
            <i class="fas fa-map"></i> FR.AK.06 Meninjau Proses
        </a>
        <a href="rekapasesi.php" class="nav-item">
            <i class="fas fa-calendar-check"></i> Rekap Hasil Tes
        </a>

        <div class="nav-label">Perencanaan</div>
        <a href="mapaasesor.php" class="nav-item">
            <i class="fas fa-sitemap"></i> FR.MAPA.01 Merencanakan
        </a>
        <a href="pihakketiga.php" class="nav-item">
            <i class="fas fa-users"></i> FR.IA.10 Pihak Ketiga
        </a>
        <a href="ceklistintrumen.php" class="nav-item">
            <i class="fas fa-clipboard-list"></i> FR.IA.11 Ceklist Instrumen
        </a>

        <div class="nav-label">Akun</div>
        <a href="tandatanganass.php" class="nav-item">
            <i class="fas fa-signature"></i> Tanda Tangan
        </a>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= strtoupper(substr($namax ?? 'AS', 0, 2)) ?></div>
            <div class="user-info">
                <strong><?= htmlspecialchars($namax ?? 'Asesor') ?></strong>
                <span>Asesor LSP</span>
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
            Validasi APL 2
            <span>FR-APL-02 Asesmen Mandiri</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip">
                <i class="fas fa-calendar"></i>
                <?= $today ?>
            </div>
            <button class="icon-btn">
                <i class="fas fa-bell"></i>
            </button>
            <a href="../logout.php" class="btn btn-danger btn-sm" style="gap:6px;padding:7px 14px;font-weight:600;" title="Keluar dari sistem">
                <i class="fas fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="content">

<?php
$op = $_REQUEST['op'] ?? '';

/* ================================================================
   OP: PILIHTANGGAL
================================================================ */
if ($op == "pilihtanggal"):
    $idskema  = $_GET['idskema'];
    $kelompok = $_GET['kelompok'];
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-calendar-days" style="color:var(--teal);margin-right:8px"></i>Pilih Tanggal Uji</h3>
                    <p>Pilih tanggal pelaksanaan asesmen untuk kelompok ini</p>
                </div>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>?op=listpeserta">
                <input type="hidden" name="kelompok" value="<?= htmlspecialchars($kelompok) ?>">
                <input type="hidden" name="idskema"  value="<?= htmlspecialchars($idskema) ?>">
                <input type="hidden" name="idasesor" value="<?= $idasesor ?>">

                <div class="form-grid">
                    <div class="form-label">Tanggal Asesmen</div>
                    <select name="tgl" class="form-input">
                        <?php
                        $tampiltgl = "SELECT tanggal, namaasesor FROM pemetaan WHERE kelompok='$kelompok' AND idskema='$idskema' AND idasesor='$idasesor' GROUP BY tanggal, namaasesor";
                        $exectgl   = mysqli_query($conn, $tampiltgl);
                        while ($rtgl = mysqli_fetch_array($exectgl)) {
                            $namaassapl2 = $rtgl['namaasesor'];
                            echo "<option value='{$rtgl['tanggal']}'>{$rtgl['tanggal']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="namasapl2" value="<?= htmlspecialchars($namaassapl2 ?? '') ?>">
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Lanjutkan
                    </button>
                </div>
            </form>
        </div>

<?php
/* ================================================================
   OP: LISTPESERTA
================================================================ */
elseif ($op == "listpeserta"):
    // PRG Pattern: jika data datang via POST, simpan ke session lalu redirect ke GET
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['lp_idasesor']   = $_POST['idasesor']  ?? '';
        $_SESSION['lp_idskema']    = $_POST['idskema']   ?? '';
        $_SESSION['lp_kelompok']   = $_POST['kelompok']  ?? '';
        $_SESSION['lp_tgl']        = $_POST['tgl']       ?? '';
        $_SESSION['lp_namaasesor'] = $_POST['namasapl2'] ?? '';
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpeserta");
        exit;
    }
    // Ambil dari session (GET setelah redirect)
    $idasesor   = $_SESSION['lp_idasesor']   ?? '';
    $idskema    = $_SESSION['lp_idskema']    ?? '';
    $kelompok   = $_SESSION['lp_kelompok']   ?? '';
    $tgl        = $_SESSION['lp_tgl']        ?? '';
    $namaasesor = $_SESSION['lp_namaasesor'] ?? '';

    $sqluser  = "SELECT linkttd,id FROM lsp_usertbl WHERE id='$idasesor'";
    $sqlusera = mysqli_query($conn, $sqluser);
    $sqluserb = mysqli_fetch_array($sqlusera);

    $ssl   = "SELECT * FROM pemetaan WHERE kelompok='$kelompok' AND idskema='$idskema' AND tanggal='$tgl' AND idasesor='$idasesor'";
    $exec0 = mysqli_query($conn, $ssl);
    $total = mysqli_num_rows($exec0);
?>
        <div class="card">
            <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert-box alert-success alert-auto-hide" style="margin-bottom:16px">
                <i class="fas fa-circle-check"></i>
                <strong><?= htmlspecialchars($_SESSION['flash_success']) ?></strong>
            </div>
            <?php unset($_SESSION['flash_success']); endif; ?>

            <div class="section-head">
                <div>
                    <h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Peserta</h3>
                    <p>Tanggal: <strong><?= htmlspecialchars($tgl) ?></strong> &nbsp;·&nbsp; <?= $total ?> peserta</p>
                </div>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>ID Asesi</th>
                            <th>Nama Asesi</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th style="width:140px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    mysqli_data_seek($exec0, 0);
                    while ($hasil0 = mysqli_fetch_array($exec0)):
$idpeserta = $hasil0['idpeserta'];

// Lookup nama peserta dari lsp_usertbl
$qNamaPes = mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE id='$idpeserta' LIMIT 1");
$rNamaPes = $qNamaPes ? mysqli_fetch_array($qNamaPes) : null;
$namaPeserta = $rNamaPes['nama'] ?? 'Peserta #'.$idpeserta;

$cekTotalApl2 = "SELECT idadsesi FROM apl2 
                 WHERE idadsesi='$idpeserta' 
                 AND idskema='$idskema'";
$rTotalApl2 = mysqli_query($conn, $cekTotalApl2);
$totalApl2 = $rTotalApl2 ? mysqli_num_rows($rTotalApl2) : 0;

$cekBelumValid = "SELECT idadsesi FROM apl2 
                  WHERE idadsesi='$idpeserta' 
                  AND idskema='$idskema' 
                  AND (svalidasi='' OR svalidasi='N' OR svalidasi IS NULL)";
$rBelumValid = mysqli_query($conn, $cekBelumValid);
$totalBelumValid = $rBelumValid ? mysqli_num_rows($rBelumValid) : 0;

if ($totalApl2 == 0) {
    $ketBadge = '<span class="badge badge-gray"><i class="fas fa-minus"></i> Belum Mengisi APL2</span>';
} elseif ($totalBelumValid > 0) {
    $ketBadge = '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Belum Divalidasi Semua</span>';
} else {
    $ketBadge = '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Sudah Divalidasi</span>';
}
                    ?>
                        <tr>
                            <td class="row-num"><?= str_pad($no, 2, '0', STR_PAD_LEFT) ?></td>
                            <td><span class="badge badge-navy"><?= htmlspecialchars($idpeserta) ?></span></td>
                            <td style="font-weight:600"><?= htmlspecialchars($namaPeserta) ?></td>
                            <td style="color:var(--text-sub)"><?= htmlspecialchars($hasil0['tanggal']) ?></td>
                            <td><?= $ketBadge ?></td>
                            <td style="text-align:center">
                                <a href="<?= $_SERVER['PHP_SELF'] ?>?op=validasiunitdapl2&idasesor=<?= $hasil0['idasesor'] ?>&k=<?= $hasil0['kelompok'] ?>&tgl=<?= $hasil0['tanggal'] ?>&kode=in&idasesi=<?= $hasil0['idpeserta'] ?>&idskema=<?= $idskema ?>&lkttd=<?= $sqluserb['linkttd'] ?>&nmass=<?= urlencode($namaasesor) ?>"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-clipboard-check"></i> Validasi
                                </a>
                            </td>
                        </tr>
                    <?php $no++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php
/* ================================================================
   OP: REKOMAPL2
================================================================ */
elseif ($op == "rekomapl2"):
    $namaasesorttd = $_GET['nmass'];
    $idasesorttd   = $_GET['idasesor'];
    $idadsesittd   = $_GET['idasesi'];
    $tglttd        = $_GET['tgl'];
    $idskttd       = $_GET['idskema'];
    $ttdass        = "../imgttd/".$_GET['lkttd'];
    $kelompttd     = $_GET['kelomp'] ?? '';

    $cekduluvlapl2  = "SELECT idadsesi,idskema,waktu,svalidasi FROM apl2 WHERE idadsesi='$idadsesittd' AND waktu='$tglttd' AND svalidasi='T' AND idskema='$idskttd' GROUP BY idadsesi,idskema,waktu,svalidasi";
    $cekduluvlapl2a = mysqli_query($conn, $cekduluvlapl2);
    $cekduluvlapl2b = mysqli_num_rows($cekduluvlapl2a);
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-clipboard-check" style="color:var(--teal);margin-right:8px"></i>Rekomendasi APL 2</h3>
                    <p>Berikan rekomendasi hasil validasi untuk asesi</p>
                </div>
                <div style="display:flex;gap:10px">
                    <button onclick="window.print()" class="btn btn-print btn-sm no-print">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm no-print">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <?php if ($cekduluvlapl2b > 0): ?>
            <div class="alert-box alert-warning">
                <i class="fas fa-triangle-exclamation"></i>
                <strong>Validasi Belum Selesai!</strong> Masih ada item yang belum divalidasi.
            </div>
            <?php else:
                $cekdata0 = "SELECT * FROM rekomendasi WHERE namarekom='apl2' AND idskema='$idskttd' AND idasesi='$idadsesittd' AND tanggal='$tglttd'";
                $ada0     = mysqli_query($conn, $cekdata0);
                if ($ada0 && mysqli_num_rows($ada0) > 0) {
                    $adax = mysqli_fetch_array($ada0);
                    $lrek = $adax['rekom'];
                    $cat  = $adax['catatan'];
                } else {
                    $lrek = ''; $cat = '';
                }
                $klrek  = ($lrek == 'L') ? 'checked' : '';
                $klrek0 = ($lrek == 'T') ? 'checked' : '';

                $sqlttd  = "SELECT * FROM lsp_usertbl WHERE id='$idadsesittd'";
                $sqlttda = mysqli_query($conn, $sqlttd);
                if ($sqlttda && mysqli_num_rows($sqlttda) > 0) {
                    $sqlttdb  = mysqli_fetch_array($sqlttda);
                    $linkttda = "../imgttd/" . ($sqlttdb['linkttd'] ?? '');
                    $namapttd = $sqlttdb['nama'] ?? '';
                    $emailttd = $sqlttdb['email'] ?? '';
                } else {
                    $linkttda = ''; $namapttd = '-'; $emailttd = '';
                }
            ?>
            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=srekomapl2">
                <input type="hidden" name="idskttd"     value="<?= $idskttd ?>">
                <input type="hidden" name="idadsesittd" value="<?= $idadsesittd ?>">
                <input type="hidden" name="tglttd"      value="<?= $tglttd ?>">
                <input type="hidden" name="idasesorttd" value="<?= $idasesorttd ?>">
                <input type="hidden" name="emailttd"    value="<?= $emailttd ?>">
                <input type="hidden" name="nmasesorttd" value="<?= htmlspecialchars($namaasesorttd) ?>">
                <input type="hidden" name="klttd"       value="<?= $kelompttd ?>">

                <div class="rekom-card">
                    <div class="rekom-row">
                        <!-- Rekomendasi -->
                        <div class="rekom-section">
                            <h4>Rekomendasi</h4>
                            <div class="radio-group">
                                <label class="radio-item">
                                    <input type="radio" name="lrekttd" value="L" <?= $klrek ?>>
                                    <i class="fas fa-circle-check" style="color:#22C55E"></i> Lanjut
                                </label>
                                <label class="radio-item">
                                    <input type="radio" name="lrekttd" value="T" <?= $klrek0 ?>>
                                    <i class="fas fa-circle-xmark" style="color:#EF4444"></i> Tidak Lanjut
                                </label>
                            </div>
                        </div>
                        <!-- Catatan -->
                        <div class="rekom-section">
                            <h4>Catatan</h4>
                            <textarea name="cttapl2" class="form-input" rows="3" placeholder="Tulis catatan..."><?= htmlspecialchars($cat) ?></textarea>
                        </div>
                    </div>

                    <div class="divider-line"></div>

                    <!-- Info Asesi & Asesor -->
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                        <div>
                            <p style="font-size:.75rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesi</p>
                            <p style="font-weight:600;margin-bottom:6px"><?= htmlspecialchars($namapttd) ?></p>
                            <?php if ($linkttda && file_exists($linkttda)): ?>
                            <div class="ttd-box"><img src="<?= $linkttda ?>" alt="TTD Asesi"><p>Tanda Tangan</p></div>
                            <?php else: ?>
                            <span class="badge badge-gray">TTD belum tersedia</span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p style="font-size:.75rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesor</p>
                            <p style="font-weight:600;margin-bottom:6px"><?= htmlspecialchars($namax) ?></p>
                            <?php if ($ttdass && file_exists($ttdass)): ?>
                            <div class="ttd-box"><img src="<?= $ttdass ?>" alt="TTD Asesor"><p>Tanda Tangan</p></div>
                            <?php else: ?>
                            <span class="badge badge-gray">TTD belum tersedia</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-floppy-disk"></i> Simpan Rekomendasi
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>

<?php
/* ================================================================
   OP: VALIDASIUNITDAPL2
================================================================ */
elseif ($op == "validasiunitdapl2"):
    $namaasesor = $_GET['nmass'];
    $idasesor   = $_GET['idasesor'];
    $ie         = $_GET['kode'] ?? '';
    $idadsesi   = $_GET['idasesi'];
    $idsk       = $_GET['idskema'];
    $tgl        = $_GET['tgl'];
    $kelompok   = $_GET['k'];
    $emailuser  = trim($uname);
    $linkttda   = $_GET['lkttd'];

    $sqlunitvapl2  = "SELECT unitsiswa.idadsesi,unitsiswa.idunit,unit.kodeunit,unit.namaunit,unit.idskema FROM unitsiswa INNER JOIN unit ON unitsiswa.idunit=unit.idunit WHERE unitsiswa.idskema='$idsk' AND unitsiswa.idadsesi='$idadsesi' ORDER BY unit.kodeunit";
    $execunitvapl2 = mysqli_query($conn, $sqlunitvapl2);
    $totalunit     = mysqli_num_rows($execunitvapl2);
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-list-check" style="color:var(--teal);margin-right:8px"></i>Pilih Unit Kompetensi</h3>
                    <p>Pilih unit yang akan divalidasi &nbsp;·&nbsp; <?= $totalunit ?> unit tersedia</p>
                </div>
                <div style="display:flex;gap:10px">
                    <a href="<?= $_SERVER['PHP_SELF'] ?>?op=rekomapl2&idasesor=<?= $idasesor ?>&tgl=<?= $tgl ?>&idasesi=<?= $idadsesi ?>&idskema=<?= $idsk ?>&kelomp=<?= $kelompok ?>&lkttd=<?= $linkttda ?>&nmass=<?= urlencode($namaasesor) ?>"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-star"></i> Rekomendasi
                    </a>
                    <a href="validasiapl2.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>?op=vapl22">
                <?php
                $i = 0;
                $idskemavapl2 = "";
                mysqli_data_seek($execunitvapl2, 0);
                while ($unit2vapl2 = mysqli_fetch_array($execunitvapl2)):
                    $dunitvapl2    = $unit2vapl2['idunit'];
                    $kodeunitvapl2 = $unit2vapl2['kodeunit'];
                    $namaunitvapl2 = $unit2vapl2['namaunit'];
                    $idskemavapl2  = $unit2vapl2['idskema'];

                    $sqlcekapl2v      = "SELECT apl2.idskema,apl2.idadsesi,apl2.idunit,apl2.idelemen,apl2.idsubelemen FROM apl2 WHERE idadsesi='$idadsesi' AND idskema='$idsk' AND idunit='$dunitvapl2'";
                    $execsqlunitapl2v = mysqli_query($conn, $sqlcekapl2v);
                    $bykunitapl2v     = mysqli_num_rows($execsqlunitapl2v);
                    $execsqlunitapl2av = mysqli_fetch_array($execsqlunitapl2v);
                    $idev = $execsqlunitapl2av['idunit'] ?? '';

                    $sqlelemenapl2v   = "SELECT count(idelemen) as byk2apl2v FROM subelemen WHERE idunit='$idev'";
                    $execelemenapl2v  = mysqli_query($conn, $sqlelemenapl2v);
                    $execelemenapl2av = mysqli_fetch_array($execelemenapl2v);
                    $bykelemenapl2v   = $execelemenapl2av['byk2apl2v'] ?? 0;

                    $ckjmlval  = "SELECT count(svalidasi) as bykvalidasi FROM apl2 WHERE idadsesi='$idadsesi' AND idskema='$idsk' AND idunit='$dunitvapl2' AND svalidasi='Y'";
                    $ckjmlvala = mysqli_query($conn, $ckjmlval);
                    $ckjmlvalb = mysqli_fetch_array($ckjmlvala);
                    $ckjmlvald = $ckjmlvalb['bykvalidasi'] ?? 0;

                    $stadav = ($bykunitapl2v > 0 && $ckjmlvald == $bykelemenapl2v && $bykelemenapl2v > 0) ? 'disabled' : '';

                    // Status badge
                    if ($stadav) {
                        $statusBadge = '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Selesai</span>';
                    } elseif ($bykunitapl2v > 0) {
                        $statusBadge = '<span class="badge badge-orange"><i class="fas fa-clock"></i> Sebagian</span>';
                    } else {
                        $statusBadge = '<span class="badge badge-gray">Belum diisi</span>';
                    }
                ?>
                    <input type="hidden" name="idskema<?= $i ?>" value="<?= $idskemavapl2 ?>">
                    <input type="hidden" name="idunit<?= $i ?>"  value="<?= $dunitvapl2 ?>">
                <?php endwhile; // tutup unit loop dulu untuk tampilkan tabel ?>

                <?php
                // Reset dan ulang untuk tampilan tabel
                mysqli_data_seek($execunitvapl2, 0);
                $i = 0;
                ?>
                <div class="tbl-wrap">
                    <table class="unit-tbl">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th style="width:50px">Pilih</th>
                                <th style="width:160px">Kode Unit</th>
                                <th>Nama Unit</th>
                                <th style="width:200px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $idskemavapl2 = "";
                        mysqli_data_seek($execunitvapl2, 0);
                        while ($unit2vapl2 = mysqli_fetch_array($execunitvapl2)):
                            $dunitvapl2    = $unit2vapl2['idunit'];
                            $kodeunitvapl2 = $unit2vapl2['kodeunit'];
                            $namaunitvapl2 = $unit2vapl2['namaunit'];
                            $idskemavapl2  = $unit2vapl2['idskema'];

                            $sqlcekapl2v2      = "SELECT count(*) as cnt FROM apl2 WHERE idadsesi='$idadsesi' AND idskema='$idsk' AND idunit='$dunitvapl2'";
                            $execsqlunitapl2v2 = mysqli_query($conn, $sqlcekapl2v2);
                            $execsqlunitapl2av2 = mysqli_fetch_array($execsqlunitapl2v2);
                            $bykunitapl2v2 = $execsqlunitapl2av2['cnt'] ?? 0;

                            $sqlelemenapl2v2   = "SELECT count(idelemen) as byk FROM subelemen WHERE idunit='$dunitvapl2'";
                            $execelemenapl2v2  = mysqli_query($conn, $sqlelemenapl2v2);
                            $execelemenapl2av2 = mysqli_fetch_array($execelemenapl2v2);
                            $bykelemenapl2v2   = $execelemenapl2av2['byk'] ?? 0;

                            $ckjmlval2  = "SELECT count(svalidasi) as bykv FROM apl2 WHERE idadsesi='$idadsesi' AND idskema='$idsk' AND idunit='$dunitvapl2' AND svalidasi='Y'";
                            $ckjmlvala2 = mysqli_query($conn, $ckjmlval2);
                            $ckjmlvalb2 = mysqli_fetch_array($ckjmlvala2);
                            $ckjmlvald2 = $ckjmlvalb2['bykv'] ?? 0;

                            $stadav2 = ($bykunitapl2v2 > 0 && $ckjmlvald2 == $bykelemenapl2v2 && $bykelemenapl2v2 > 0) ? 'disabled' : '';

                            if ($stadav2) {
                                $statusBadge = '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Selesai ('.$ckjmlvald2.'/'.$bykelemenapl2v2.')</span>';
                            } elseif ($bykunitapl2v2 > 0) {
                                $statusBadge = '<span class="badge badge-orange"><i class="fas fa-clock"></i> Proses ('.$ckjmlvald2.'/'.$bykelemenapl2v2.')</span>';
                            } else {
                                $statusBadge = '<span class="badge badge-gray"><i class="fas fa-minus"></i> Belum diisi</span>';
                            }
                        ?>
                            <tr>
                                <td class="row-num"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></td>
                                <td style="text-align:center">
                                    <input type="hidden" name="idskema<?= $i ?>" value="<?= $idskemavapl2 ?>">
                                    <input type="hidden" name="idunit<?= $i ?>"  value="<?= $dunitvapl2 ?>">
                                    <input type="checkbox" name="kodeunit<?= $i ?>" value="<?= htmlspecialchars($kodeunitvapl2) ?>" <?= $stadav2 ?>
                                           style="width:16px;height:16px;accent-color:var(--teal);cursor:pointer">
                                </td>
                                <td><span class="badge badge-teal"><?= htmlspecialchars($kodeunitvapl2) ?></span></td>
                                <td style="font-weight:500"><?= htmlspecialchars($namaunitvapl2) ?></td>
                                <td><?= $statusBadge ?></td>
                            </tr>
                        <?php $i++; endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="nz"          value="<?= $i ?>">
                <input type="hidden" name="emailuserapl2" value="<?= htmlspecialchars($emailuser) ?>">
                <input type="hidden" name="idasesorz"   value="<?= $idasesor ?>">
                <input type="hidden" name="kodez"        value="<?= $ie ?>">
                <input type="hidden" name="idasesiz"     value="<?= $idadsesi ?>">
                <input type="hidden" name="idskemaz"     value="<?= $idskemavapl2 ?>">
                <input type="hidden" name="tglz"         value="<?= $tgl ?>">
                <input type="hidden" name="kz"           value="<?= $kelompok ?>">
                <input type="hidden" name="nmasser"      value="<?= htmlspecialchars($namaasesor) ?>">
                <input type="hidden" name="linkttda2"    value="<?= $linkttda ?>">

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Lanjutkan Validasi
                    </button>
                </div>
            </form>
        </div>

<?php
/* ================================================================
   OP: VAPL22 — form validasi per KUK
================================================================ */
elseif ($op == "vapl22"):
    $namaasesor = $_POST['nmasser'] ?? '';
    $idasesor   = $_POST['idasesorz'] ?? '';
    $ieq        = $_POST['kodez'] ?? '';
    $idadsesiq  = $_POST['idasesiz'] ?? '';
    $idskq      = $_POST['idskemaz'] ?? '';
    $tgl        = $_POST['tglz'] ?? '';
    $kelompokq  = $_POST['kz'] ?? '';
    $linkttda2  = $_POST['linkttda2'] ?? '';

    $sql    = "SELECT * FROM lsp_usertbl WHERE id='$idadsesiq'";
    $shasil = mysqli_query($conn, $sql);
    $sdata  = mysqli_fetch_array($shasil);
    $namap  = $sdata['nama'] ?? '-';
    $idp    = $sdata['id'] ?? '';
    $emailp = $sdata['email'] ?? '';

    $nn = (int)($_POST['nz'] ?? 0);
    $i  = 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-clipboard-check" style="color:var(--teal);margin-right:8px"></i>FR-APL-02 Asesmen Mandiri</h3>
                    <p>Peserta: <strong><?= htmlspecialchars($namap) ?></strong> &nbsp;·&nbsp; Asesor: <strong><?= htmlspecialchars($namaasesor) ?></strong> &nbsp;·&nbsp; Tanggal: <?= htmlspecialchars($tgl) ?></p>
                </div>
                <div style="display:flex;gap:10px">
                    <button onclick="window.print()" class="btn btn-print btn-sm no-print">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm no-print">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="alert-box alert-info">
                <i class="fas fa-circle-info"></i>
                Validasi setiap sub-kompetensi dengan mencentang jenis bukti yang sesuai: <strong>V</strong> (Valid) · <strong>A</strong> (Asli) · <strong>T</strong> (Terkini) · <strong>M</strong> (Memadai)
            </div>

            <form id="formValidasiAPL2" method="post" action="<?= $_SERVER['PHP_SELF'] ?>?op=simpanvalidasiapl2">
                <input type="hidden" name="idskema"  value="<?php
                    // Ambil dari POST unit pertama yang ada
                    for ($x = 0; $x < $nn; $x++) {
                        if (isset($_POST['kodeunit'.$x])) { echo $_POST['idskema'.$x] ?? ''; break; }
                    }
                ?>">
                <input type="hidden" name="idadsesi" value="<?= $idp ?>">
                <input type="hidden" name="email"    value="<?= $emailp ?>">
                <input type="hidden" name="idasesor" value="<?= $idasesor ?>">
                <input type="hidden" name="tgl"      value="<?= $tgl ?>">
                <input type="hidden" name="lkttda"   value="<?= $linkttda2 ?>">
                <input type="hidden" name="nmsor"    value="<?= htmlspecialchars($namaasesor) ?>">
                <input type="hidden" name="klmpk"    value="<?= $kelompokq ?>">

                <div class="tbl-wrap">
                    <table class="apl-tbl">
                        <thead>
                            <tr>
                                <th style="width:60px">No KUK</th>
                                <th>Sub Kompetensi</th>
                                <th style="width:60px;text-align:center">K</th>
                                <th style="width:60px;text-align:center">BK</th>
                                <th style="width:200px">Validasi (V-A-T-M)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        for ($cba = 0; $cba < $nn; $cba++):
                            if (!isset($_POST['kodeunit'.$cba])) continue;
                            $idunitxyz = $_POST['idunit'.$cba];

                            $cek = "SELECT idskema, statusapl1 FROM skemasiswa WHERE emailsiswa='$emailp'";
                            $ada = mysqli_query($conn, $cek);
                            if (!$ada || mysqli_num_rows($ada) < 1) continue;
                            $data  = mysqli_fetch_array($ada);
                            $skema = $data['idskema'];
                            if ($data['statusapl1'] != 'Y') continue;

                            $sqlunit = "SELECT unitsiswa.idunit, unit.kodeunit, unit.namaunit FROM unitsiswa INNER JOIN unit ON unitsiswa.idunit=unit.idunit WHERE unitsiswa.idskema='$skema' AND unitsiswa.idadsesi='$idp' AND unitsiswa.idunit='$idunitxyz'";
                            $eunit   = mysqli_query($conn, $sqlunit);

                            while ($dunit = mysqli_fetch_array($eunit)):
                        ?>
                            <tr class="unit-head">
                                <td colspan="5">
                                    <i class="fas fa-cubes" style="color:var(--teal);margin-right:6px"></i>
                                    <?= htmlspecialchars($dunit['kodeunit']) ?> — <?= htmlspecialchars($dunit['namaunit']) ?>
                                </td>
                            </tr>
                            <?php
                            $sqelemen = "SELECT * FROM elemen WHERE idunit='{$dunit['idunit']}' AND idskema='$skema'";
                            $eelemen  = mysqli_query($conn, $sqelemen);
                            
                            // [PELACAK 1] Jika elemen kosong di DB
                            if (mysqli_num_rows($eelemen) == 0) {
                                echo "<tr><td colspan='5' style='color:red; background:#FFEBEB; padding:8px 32px;'>⚠️ Data ELEMEN tidak ditemukan di DB! (Unit ID: {$dunit['idunit']})</td></tr>";
                            }

                            $y = 0;
                            while ($delemen = mysqli_fetch_array($eelemen)):
                                $y++;
                            ?>
                            <tr class="elemen-head">
                                <td colspan="5">
                                    <i class="fas fa-layer-group" style="color:#1D4ED8;margin-right:6px"></i>
                                    <?= $y ?>. <?= htmlspecialchars($delemen['namaelemen']) ?>
                                </td>
                            </tr>
                            <?php
                            $sqsubelemen = "SELECT * FROM subelemen WHERE idelemen='{$delemen['idelemen']}' AND idunit='{$dunit['idunit']}'";
                            $esubelemen  = mysqli_query($conn, $sqsubelemen);
                            
                            // [PELACAK 2] Jika subelemen kosong di DB
                            if (mysqli_num_rows($esubelemen) == 0) {
                                echo "<tr><td colspan='5' style='color:orange; background:#FFF5E6; padding:8px 32px;'>⚠️ Data SUB-ELEMEN / KUK tidak ditemukan di DB! (Elemen ID: {$delemen['idelemen']})</td></tr>";
                            }

                            $x = 0;
                            while ($dsubelemen = mysqli_fetch_array($esubelemen)):
                                $x++;
                                $idsube = $dsubelemen['idsubelemen'];
                                $kuk    = $dsubelemen['pertanyaan'];

                                $cekapl2  = "SELECT * FROM apl2 WHERE idsubelemen='$idsube' AND idadsesi='$idp' AND waktu='$tgl' LIMIT 1";
                                $rcekapl2 = mysqli_query($conn, $cekapl2);

                                if (mysqli_num_rows($rcekapl2) > 0):
                                    $hcekapl2  = mysqli_fetch_array($rcekapl2);
                                    $sbukti    = $hcekapl2['sbukti'] ?? '';
                                    $pecahdbs  = explode(",", $sbukti);
                                    $kdbs0 = (isset($pecahdbs[0]) && $pecahdbs[0] == 'v') ? 'checked' : '';
                                    $kdbs1 = (isset($pecahdbs[1]) && $pecahdbs[1] == 'a') ? 'checked' : '';
                                    $kdbs2 = (isset($pecahdbs[2]) && $pecahdbs[2] == 't') ? 'checked' : '';
                                    $kdbs3 = (isset($pecahdbs[3]) && $pecahdbs[3] == 'm') ? 'checked' : '';
                                    $kbk   = $hcekapl2['tk'];
                                    $ketk  = ($kbk == 'K') ? '<i class="fas fa-circle-check" style="color:#22C55E"></i>' : '';
                                    $ketbk = ($kbk != 'K') ? '<i class="fas fa-circle-check" style="color:#EF4444"></i>' : '';
                            ?>
                            <tr class="sub-row">
                                <td class="row-num" style="padding-left:32px"><?= $y ?>.<?= $x ?></td>
                                <td>
                                    <?= htmlspecialchars($kuk) ?>
                                    <input type="hidden" name="idunit<?= $i ?>"   value="<?= $dunit['idunit'] ?>">
                                    <input type="hidden" name="idelemen<?= $i ?>" value="<?= $delemen['idelemen'] ?>">
                                    <input type="hidden" name="idsube<?= $i ?>"   value="<?= $idsube ?>">
                                </td>
                                <td style="text-align:center"><?= $ketk ?></td>
                                <td style="text-align:center"><?= $ketbk ?></td>
                                <td>
                                    <div class="check-group">
                                        <label class="check-item"><input type="checkbox" name="validasia<?= $i ?>" value="v" <?= $kdbs0 ?>> V</label>
                                        <label class="check-item"><input type="checkbox" name="validasib<?= $i ?>" value="a" <?= $kdbs1 ?>> A</label>
                                        <label class="check-item"><input type="checkbox" name="validasic<?= $i ?>" value="t" <?= $kdbs2 ?>> T</label>
                                        <label class="check-item"><input type="checkbox" name="validasid<?= $i ?>" value="m" <?= $kdbs3 ?>> M</label>
                                    </div>
                                </td>
                            </tr>
                            <?php
                                else:
                                    // JIKA DATA ASESI BELUM ADA DI TABEL APL2 (DATA KOSONG)
                            ?>
                            <tr class="sub-row row-empty-suntik" style="background:#FFFDF0;">
                                <td class="row-num" style="padding-left:32px"><?= $y ?>.<?= $x ?></td>
                                <td>
                                    <?= htmlspecialchars($kuk) ?> <br>
                                    <span class="badge badge-warning" style="font-size:10px; color:orange; font-weight:bold;">*Belum diisi Asesi (Sistem Otomatis)</span>
                                    <input type="hidden" name="idunit<?= $i ?>"   value="<?= $dunit['idunit'] ?>">
                                    <input type="hidden" name="idelemen<?= $i ?>" value="<?= $delemen['idelemen'] ?>">
                                    <input type="hidden" name="idsube<?= $i ?>"   value="<?= $idsube ?>">
                                </td>
                                <td style="text-align:center">—</td>
                                <td style="text-align:center"><i class="fas fa-circle-check" style="color:#EF4444"></i></td>
                                <td>
                                    <div class="check-group">
                                        <label class="check-item"><input type="checkbox" name="validasia<?= $i ?>" value="v"> V</label>
                                        <label class="check-item"><input type="checkbox" name="validasib<?= $i ?>" value="a"> A</label>
                                        <label class="check-item"><input type="checkbox" name="validasic<?= $i ?>" value="t"> T</label>
                                        <label class="check-item"><input type="checkbox" name="validasid<?= $i ?>" value="m"> M</label>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endif; 
                                $i++; 
                            endwhile; // subelemen 
                            endwhile; // elemen
                            endwhile; // unit
                        endfor; // nn loop
                        ?>
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="n" value="<?= $i ?>">

                <div class="divider-line"></div>
                <div style="background:var(--off);border-radius:10px;padding:16px 20px;margin-bottom:16px">
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:.88rem;font-weight:600">
                        <input type="checkbox" id="ckok" style="width:18px;height:18px;accent-color:var(--teal);cursor:pointer">
                        Saya sudah memeriksa dan melakukan validasi terhadap seluruh data di atas.
                    </label>
                </div>
                <div class="form-actions">
                    <button id="lanjutkan" name="lanjutkan" type="submit" class="btn btn-primary" disabled>
                        <i class="fas fa-floppy-disk"></i> Simpan Validasi
                    </button>
                </div>
            </form>
        </div>
        <script>
        document.getElementById('ckok').onchange = function() {
            document.getElementById('lanjutkan').disabled = !this.checked;
        };
        </script>
<?php
/* ================================================================
   OP: SIMPANVALIDASIAPL2
================================================================ */
elseif ($op == "simpanvalidasiapl2"):
    $nmasesor = $_POST['nmsor'] ?? '';
    $idasesor = $_POST['idasesor'] ?? '';
    $idskema  = $_POST['idskema'] ?? '';
    $idasesi  = $_POST['idadsesi'] ?? '';
    $email    = $_POST['email'] ?? '';
    if (empty($email) && !empty($idasesi)) {
        $qEm = mysqli_query($conn, "SELECT email FROM lsp_usertbl WHERE id='$idasesi' LIMIT 1");
        if ($qEm && $rEm = mysqli_fetch_array($qEm)) {
            $email = $rEm['email'];
        }
    }
    $tgl      = $_POST['tgl'] ?? '';
    $klmpk    = $_POST['klmpk'] ?? '';
    $lkttda   = $_POST['lkttda'] ?? '';
    $n        = (int)($_POST['n'] ?? 0);
    
    $kode_kembali = $_POST['kode'] ?? ($_GET['kode'] ?? ''); 

    $total_proses = 0;
    $total_sukses = 0;

    for ($i = 0; $i < $n; $i++) {
        $idunit      = $_POST['idunit'.$i] ?? '';
        $idelemen    = $_POST['idelemen'.$i] ?? '';
        $idsubelemen = $_POST['idsube'.$i] ?? '';
        $validasi0   = $_POST['validasia'.$i] ?? '';
        $validasi1   = $_POST['validasib'.$i] ?? '';
        $validasi2   = $_POST['validasic'.$i] ?? '';
        $validasi3   = $_POST['validasid'.$i] ?? '';
        $allvalidasi = "$validasi0,$validasi1,$validasi2,$validasi3";

        if (!empty($idsubelemen)) {
            $total_proses++;
            
            // Cek apakah data validasi KUK ini sudah pernah ada di DB pada tanggal ujian tersebut
            $cek_data = "SELECT idsubelemen FROM apl2 
                         WHERE idskema='$idskema' 
                         AND idadsesi='$idasesi' 
                         AND idsubelemen='$idsubelemen' 
                         AND DATE(waktu)='$tgl' LIMIT 1";
            $r_cek = mysqli_query($conn, $cek_data);

            if (mysqli_num_rows($r_cek) > 0) {
                // JIKA SUDAH ADA: Jalankan UPDATE asli milik lu (svalidasi set 'Y')
                $sqlquery = "UPDATE apl2 
                             SET svalidasi='Y' 
                             WHERE idskema='$idskema' 
                             AND idadsesi='$idasesi' 
                             AND idelemen='$idelemen' 
                             AND idsubelemen='$idsubelemen' 
                             AND DATE(waktu)='$tgl'";
            } else {
                // JIKA BELUM ADA: Jalankan INSERT
                $sqlquery = "INSERT INTO apl2 (idskema, idadsesi, idunit, idelemen, idsubelemen, email, svalidasi, waktu) 
                             VALUES ('$idskema', '$idasesi', '$idunit', '$idelemen', '$idsubelemen', '$email', 'Y', '$tgl')";
            }
            
            if (mysqli_query($conn, $sqlquery)) {
                $total_sukses++;
            } else {
                echo "Error MySQL: " . mysqli_error($conn) . "<br>";
            }
        }
    }

    $exec = ($total_proses > 0 && $total_sukses == $total_proses) ? true : false;
    if ($exec) {
        mysqli_query($conn, "UPDATE skemasiswa SET statusapl2='Y' WHERE idskema='$idskema' AND (idasesi='$idasesi' OR emailsiswa='$email' OR emailsiswa=(SELECT username FROM users WHERE id='$idasesi' LIMIT 1))");
    }
?>
        <div class="card">
            <div class="section-head">
                <h3><i class="fas fa-circle-check" style="color:var(--teal);margin-right:8px"></i>Hasil Simpan Validasi</h3>
            </div>
            <?php if ($exec): ?>
            <div class="alert-box alert-success alert-auto-hide">
                <i class="fas fa-circle-check"></i>
                <div>
                    <strong>Data Berhasil Disimpan!</strong>
                    <p style="margin-top:2px;font-size:.8rem">Validasi untuk Asesi ID: <strong><?= $idasesi ?></strong> telah diperbarui dengan svalidasi 'Y'.</p>
                </div>
            </div>
            <a href="<?= $_SERVER['PHP_SELF'] ?>?op=validasiunitdapl2&idasesor=<?= $idasesor ?>&k=<?= $klmpk ?>&tgl=<?= $tgl ?>&idasesi=<?= $idasesi ?>&idskema=<?= $idskema ?>&lkttd=<?= $lkttda ?>&nmass=<?= urlencode($nmasesor) ?>&kode=<?= $kode_kembali ?>"
               class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Unit
            </a>
            <?php else: ?>
            <div class="alert-box alert-error">
                <i class="fas fa-circle-xmark"></i>
                <div>
                    <strong>Gagal Menyimpan Validasi</strong>
                    <p style="margin-top:4px;font-size:.84rem">
                        <?php if ($total_proses == 0): ?>
                            Unit kompetensi yang Anda pilih <strong>belum memiliki data Elemen / Sub-Elemen (KUK)</strong> di database. Silakan tambahkan elemen kompetensi terlebih dahulu melalui menu <strong>Kelola Kompetensi</strong>.
                        <?php else: ?>
                            Terjadi kesalahan saat menyimpan data validasi ke database.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <?php endif; ?>
        </div>
<?php
/* ================================================================
   OP: SREKOMAPL2
================================================================ */
elseif ($op == "srekomapl2"):
    $idskemarekapl2  = $_POST['idskttd'];
    $idasesirekapl2  = $_POST['idadsesittd'];
    $tglrekapl2      = $_POST['tglttd'];
    $idasesorrekapl2 = $_POST['idasesorttd'];
    $lrekapl2        = $_POST['lrekttd'];
    $catatanrekapl2  = $_POST['cttapl2'];
    $emailad         = $_POST['emailttd'];
    $nmasesorapl2    = $_POST['nmasesorttd'];
    $klapl2          = $_POST['klttd'];

    $cekdata  = "SELECT * FROM rekomendasi WHERE namarekom='apl2' AND idskema='$idskemarekapl2' AND idasesi='$idasesirekapl2' AND tanggal='$tglrekapl2'";
    $ada      = mysqli_query($conn, $cekdata);
    if ($ada && mysqli_num_rows($ada) > 0) {
        $ssqlrekapl2 = "UPDATE rekomendasi SET rekom='$lrekapl2', catatan='$catatanrekapl2' WHERE namarekom='apl2' AND idskema='$idskemarekapl2' AND idasesi='$idasesirekapl2' AND tanggal='$tglrekapl2'";
    } else {
        $ssqlrekapl2 = "INSERT INTO rekomendasi VALUES('','apl2','$idskemarekapl2','$idasesorrekapl2','$idasesirekapl2','$lrekapl2','$catatanrekapl2','','$tglrekapl2')";
    }
    $execrekapl2 = mysqli_query($conn, $ssqlrekapl2);

    if ($execrekapl2) {
        $updskemasis  = "UPDATE skemasiswa SET statusapl2='Y' WHERE idskema='$idskemarekapl2' AND emailsiswa='$emailad'";
        mysqli_query($conn, $updskemasis);
        // PRG: simpan ke session lalu redirect ke GET listpeserta
        $_SESSION['lp_idasesor']   = $idasesorrekapl2;
        $_SESSION['lp_idskema']    = $idskemarekapl2;
        $_SESSION['lp_kelompok']   = $klapl2;
        $_SESSION['lp_tgl']        = $tglrekapl2;
        $_SESSION['lp_namaasesor'] = $nmasesorapl2;
        $_SESSION['flash_success'] = 'Rekomendasi berhasil disimpan!';
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpeserta");
        exit;
    } else {
?>
        <div class="card">
            <div class="alert-box alert-error">
                <i class="fas fa-circle-xmark"></i>
                <strong>Penyimpanan Gagal!</strong>
            </div>
            <button onclick="history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>
<?php } ?>

<?php
/* ================================================================
   DEFAULT — Tampilkan daftar jadwal
================================================================ */
else:
    $cekduluttd  = "SELECT id,linkttd FROM lsp_usertbl WHERE id='$idasesorxv'";
    $cekduluttda = mysqli_query($conn, $cekduluttd);
    $cekduluttdb = mysqli_fetch_array($cekduluttda);
    $cekduluttdc = strlen($cekduluttdb['linkttd'] ?? '');
?>
        <?php if ($cekduluttdc > 0):
            $queryvmain  = "SELECT kelompok, idskema FROM pemetaan WHERE idasesor='$idasesorxv' GROUP BY kelompok,idskema";
            $hasilvmain  = mysqli_query($conn, $queryvmain);
            $total_jadwal = $hasilvmain ? mysqli_num_rows($hasilvmain) : 0;
        ?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-calendar-check" style="color:var(--teal);margin-right:8px"></i>Daftar Jadwal Asesmen</h3>
                    <p>Pilih jadwal untuk memulai validasi APL 2</p>
                </div>
                <span style="background:var(--teal-light);color:var(--teal-dark);padding:4px 14px;border-radius:6px;font-size:.78rem;font-weight:700">
                    <?= $total_jadwal ?> Jadwal
                </span>
            </div>

            <?php if ($total_jadwal > 0): ?>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Kelompok</th>
                            <th>Kode Skema</th>
                            <th>Nama Skema</th>
                            <th style="width:180px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $queryvvmain  = "SELECT kelompok, idskema FROM pemetaan WHERE idasesor='$idasesorxv' GROUP BY kelompok,idskema";
                    $hasilvvmain  = mysqli_query($conn, $queryvvmain);
                    while ($datavvmain = mysqli_fetch_array($hasilvvmain)):
                        $id_skema_dari_tabel_lain = $datavvmain['idskema'];
                        $ssql     = "SELECT namaskema FROM skema WHERE idskema='$id_skema_dari_tabel_lain'";
                        $execssql = mysqli_query($conn, $ssql);
                        if ($execssql && mysqli_num_rows($execssql) > 0) {
                            $baris     = mysqli_fetch_array($execssql);
                            $namaskema = $baris['namaskema'];
                        } else {
                            $namaskema = '<em style="color:var(--text-muted)">Skema tidak ditemukan (ID: '.$id_skema_dari_tabel_lain.')</em>';
                        }
                    ?>
                        <tr>
                            <td class="row-num"><?= str_pad($no, 2, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <span class="badge badge-navy"><?= htmlspecialchars($datavvmain['kelompok']) ?></span>
                            </td>
                            <td>
                                <span class="badge badge-teal"><?= htmlspecialchars($datavvmain['idskema']) ?></span>
                            </td>
                            <td style="font-weight:600"><?= $namaskema ?></td>
                            <td style="text-align:center">
                                <a href="<?= $_SERVER['PHP_SELF'] ?>?op=pilihtanggal&idskema=<?= $datavvmain['idskema'] ?>&kelompok=<?= urlencode($datavvmain['kelompok']) ?>"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-users"></i> Tampilkan Peserta
                                </a>
                            </td>
                        </tr>
                    <?php $no++; endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-calendar-xmark"></i>
                <p>Belum ada jadwal asesmen yang ditugaskan.</p>
            </div>
            <?php endif; ?>
        </div>

        <?php else: ?>
        <div class="card">
            <div class="alert-box alert-warning">
                <i class="fas fa-triangle-exclamation"></i>
                <div>
                    <strong>Tanda Tangan Belum Diisi!</strong>
                    <p style="margin-top:2px;font-size:.8rem">Harap lengkapi tanda tangan Anda sebelum melakukan validasi.</p>
                </div>
            </div>
            <a href="tandatanganass.php" class="btn btn-primary">
                <i class="fas fa-signature"></i> Isi Tanda Tangan Sekarang
            </a>
        </div>
        <?php endif; ?>

<?php endif; // end op switch ?>

    </div><!-- /content -->

    <!-- FOOTER -->
    <footer class="page-footer">
        <span>© <?= date('Y') ?> <strong>LSP SMKN 1 Cibinong</strong>. Semua hak dilindungi.</span>
        <span>Versi 1.0.0 &nbsp;·&nbsp; <?= $today ?>, <?= $current_time ?> WIB</span>
    </footer>

</div><!-- /main -->

</body>
</html>
<?php ob_end_flush(); ?>