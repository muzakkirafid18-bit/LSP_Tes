<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include "../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$today = date('d F Y');
$current_time = date('H:i');

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<h3>Anda Harus Login Dahulu!</h3>";
    echo "<a href='../lsp_login.php'>Kembali ke Login</a>";
    echo "</div>";
    exit;
}

if ($_SESSION['level'] != 'asesor') {
    echo "<style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<h3>Anda Tidak Punya Hak Akses!</h3>";
    echo "<a href='../lsp_login.php'>Kembali ke Login</a>";
    echo "</div>";
    exit;
}

$uname = $_SESSION['username'] ?? '';
$l = "SELECT * FROM lsp_usertbl WHERE email='$uname'";
$resultx = mysqli_query($conn, $l);
$hasilx = mysqli_fetch_array($resultx);

$namax = $hasilx['nama'] ?? 'Asesor';
$idasesor_login = $hasilx['id'] ?? '';
$ttd = $hasilx['linkttd'] ?? '';
$idasesorxv = $idasesor_login;
$op = $_REQUEST['op'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.IA.08 Portofolio - LSP SMKN 1 Cibinong</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<script src="js/lumino.glyphs.js"></script>
<script src="../js/jquery-2.2.3.min.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>
<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/bootstrap.js"></script>

<script>
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

<style>
:root {
    --teal:#3BBFBF;
    --teal-dark:#2A9999;
    --teal-light:#E8F8F8;
    --teal-glow:rgba(59,191,191,.18);
    --navy:#0F2A3A;
    --navy-mid:#163347;
    --navy-soft:#1E4060;
    --white:#FFFFFF;
    --off:#F4F8FA;
    --border:#DDE8ED;
    --text-main:#1A2E3B;
    --text-sub:#5A7384;
    --text-muted:#92A9B5;
    --green:#22C55E;
    --red:#EF4444;
    --orange:#F97316;
    --sidebar-w:260px;
    --radius:14px;
    --shadow:0 2px 16px rgba(15,42,58,.07);
    --shadow-md:0 4px 32px rgba(15,42,58,.13);
}
*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
html { font-size:15px; scroll-behavior:smooth; }
body {
    font-family:'Plus Jakarta Sans', sans-serif;
    background:var(--off);
    color:var(--text-main);
    display:flex;
    min-height:100vh;
    overflow-x:hidden;
}
a { color:inherit; }





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
    padding:28px 24px 20px;
    border-bottom:1px solid rgba(255,255,255,.07);
    display:flex;
    align-items:center;
    gap:12px;
    flex-shrink:0;
}
.logo-box {
    width:50px;
    height:50px;
    background:#fff;
    border-radius:12px;
    padding:5px;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}
.logo-box img { width:100%; height:100%; object-fit:contain; }
.logo-text { line-height:1.2; }
.logo-text strong { display:block; color:var(--white); font-size:.95rem; font-weight:700; }
.logo-text span { color:var(--teal); font-size:.72rem; font-weight:500; letter-spacing:.5px; }

.sidebar-nav { padding:16px 12px; flex:1; }
.nav-label {
    color:rgba(255,255,255,.3);
    font-size:.67rem;
    font-weight:700;
    letter-spacing:1.2px;
    text-transform:uppercase;
    padding:12px 12px 6px;
}
.nav-item {
    display:flex;
    align-items:center;
    gap:12px;
    padding:10px 14px;
    border-radius:10px;
    color:rgba(255,255,255,.55);
    text-decoration:none;
    font-size:.875rem;
    font-weight:500;
    transition:all .2s;
    margin-bottom:2px;
    cursor:pointer;
}
.nav-item:hover { background:rgba(255,255,255,.07); color:var(--white); text-decoration:none; }
.nav-item.active {
    background:var(--teal);
    color:var(--white);
    box-shadow:0 4px 12px rgba(59,191,191,.35);
}
.nav-item i { width:18px; text-align:center; font-size:.9rem; flex-shrink:0; }

.sidebar-footer {
    padding:16px 14px;
    border-top:1px solid rgba(255,255,255,.07);
    flex-shrink:0;
}
.user-card {
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius:10px;
    background:rgba(255,255,255,.05);
}
.user-avatar {
    width:36px;
    height:36px;
    border-radius:50%;
    background:var(--teal);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
    font-size:.85rem;
    color:var(--white);
    flex-shrink:0;
}
.user-info strong { display:block; color:var(--white); font-size:.82rem; }
.user-info span { color:var(--teal); font-size:.72rem; }
.btn-logout {
    margin-left:auto;
    color:rgba(255,255,255,.35);
    background:none;
    border:none;
    cursor:pointer;
    font-size:.85rem;
    transition:color .2s;
}
.btn-logout:hover { color:var(--red); }

.main {
    margin-left:var(--sidebar-w);
    flex:1;
    display:flex;
    flex-direction:column;
    min-height:100vh;
}
.topbar {
    background:var(--white);
    border-bottom:1px solid var(--border);
    padding:0 32px;
    height:68px;
    display:flex;
    align-items:center;
    gap:16px;
    position:sticky;
    top:0;
    z-index:50;
}
.topbar-title { font-size:1.1rem; font-weight:700; flex:1; }
.topbar-title span { color:var(--text-sub); font-weight:400; font-size:.875rem; margin-left:8px; }
.topbar-actions { display:flex; align-items:center; gap:10px; }
.icon-btn {
    width:38px;
    height:38px;
    border-radius:10px;
    border:1.5px solid var(--border);
    background:var(--white);
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    color:var(--text-sub);
    font-size:.9rem;
    transition:all .2s;
}
.icon-btn:hover { border-color:var(--teal); color:var(--teal); }
.date-chip {
    background:var(--teal-light);
    color:var(--teal-dark);
    font-size:.78rem;
    font-weight:600;
    padding:6px 14px;
    border-radius:8px;
    display:flex;
    align-items:center;
    gap:6px;
}
.content { padding:32px; display:flex; flex-direction:column; gap:24px; }

.card {
    background:var(--white);
    border-radius:var(--radius);
    padding:28px;
    box-shadow:var(--shadow);
    animation:fadeUp .4s ease both;
}
@keyframes fadeUp {
    from { opacity:0; transform:translateY(16px); }
    to { opacity:1; transform:translateY(0); }
}
.section-head {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    margin-bottom:20px;
}
.section-head h3 { font-size:1.05rem; font-weight:700; }
.section-head p { font-size:.78rem; color:var(--text-sub); margin-top:2px; }

.alert-box {
    padding:12px 18px;
    border-radius:10px;
    font-size:.85rem;
    font-weight:500;
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:20px;
}
.alert-success { background:#DCFCE7; color:#15803D; border:1px solid #86EFAC; }
.alert-error { background:#FEF2F2; color:#B91C1C; border:1px solid #FCA5A5; }
.alert-warning { background:#FEFCE8; color:#A16207; border:1px solid #FDE68A; }
.alert-info { background:var(--teal-light); color:var(--teal-dark); border:1px solid #99D9D9; }

.tbl-wrap { overflow-x:auto; }
.tbl { width:100%; border-collapse:collapse; }
.tbl thead tr { background:var(--navy); }
.tbl th {
    text-align:left;
    padding:12px 16px;
    font-size:.72rem;
    font-weight:700;
    letter-spacing:.7px;
    text-transform:uppercase;
    color:rgba(255,255,255,.75);
}
.tbl th:first-child { border-radius:8px 0 0 8px; }
.tbl th:last-child { border-radius:0 8px 8px 0; }
.tbl td {
    padding:13px 16px;
    font-size:.845rem;
    border-bottom:1px solid var(--off);
    vertical-align:middle;
}
.tbl tr:last-child td { border-bottom:none; }
.tbl tbody tr { transition:background .15s; }
.tbl tbody tr:hover td { background:#F0F9F9; }

.unit-tbl, .apl-tbl { width:100%; border-collapse:collapse; }
.unit-tbl th, .apl-tbl th {
    text-align:left;
    padding:10px 14px;
    background:var(--navy-soft);
    color:rgba(255,255,255,.8);
    font-size:.72rem;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.6px;
}
.unit-tbl td, .apl-tbl td {
    padding:11px 14px;
    font-size:.83rem;
    border-bottom:1px solid var(--off);
    vertical-align:middle;
}
.apl-tbl .unit-head td {
    background:var(--off);
    font-weight:700;
    color:var(--navy);
    font-size:.85rem;
    border-left:3px solid var(--teal);
    padding-left:14px;
}
.apl-tbl .elemen-head td {
    background:#EFF6FF;
    font-weight:600;
    color:#1D4ED8;
    padding-left:24px;
    font-size:.82rem;
}

.badge {
    display:inline-flex;
    align-items:center;
    gap:4px;
    padding:3px 10px;
    border-radius:6px;
    font-size:.72rem;
    font-weight:700;
}
.badge-green { background:#DCFCE7; color:#15803D; }
.badge-red { background:#FEF2F2; color:#B91C1C; }
.badge-orange { background:#FFF7ED; color:#C2410C; }
.badge-teal { background:var(--teal-light); color:var(--teal-dark); }
.badge-navy { background:#EFF6FF; color:#1D4ED8; }
.badge-gray { background:#F1F5F9; color:#64748B; }

.btn {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:9px 20px;
    border-radius:10px;
    font-size:.85rem;
    font-weight:600;
    cursor:pointer;
    text-decoration:none;
    border:1.5px solid transparent;
    transition:all .2s;
    font-family:'Plus Jakarta Sans', sans-serif;
}
.btn:hover { text-decoration:none; }
.btn[disabled] { opacity:.55; cursor:not-allowed; }
.btn-primary { background:var(--teal); color:var(--white); box-shadow:0 2px 8px rgba(59,191,191,.3); }
.btn-primary:hover { background:var(--teal-dark); color:var(--white); }
.btn-secondary { background:var(--white); color:var(--text-main); border-color:var(--border); }
.btn-secondary:hover { border-color:var(--teal); color:var(--teal-dark); background:var(--teal-light); }
.btn-success { background:#22C55E; color:var(--white); border-color:#22C55E; }
.btn-success:hover { background:#16A34A; color:var(--white); }
.btn-warning { background:#FFF7ED; color:#C2410C; border-color:#FDBA74; }
.btn-warning:hover { background:#F97316; color:var(--white); border-color:#F97316; }
.btn-print { background:var(--navy); color:var(--white); border-color:var(--navy); }
.btn-print:hover { background:var(--navy-soft); color:var(--white); }
.btn-sm { padding:6px 14px; font-size:.78rem; border-radius:8px; }

.form-grid {
    display:grid;
    grid-template-columns:180px 1fr;
    gap:14px;
    align-items:start;
    margin-bottom:18px;
}
.form-label { font-size:.82rem; font-weight:600; color:var(--text-sub); padding-top:10px; }
.form-input {
    width:100%;
    font-size:.875rem;
    padding:10px 14px;
    border:1.5px solid var(--border);
    border-radius:10px;
    font-family:'Plus Jakarta Sans', sans-serif;
    color:var(--text-main);
    background:var(--off);
    transition:border-color .2s, box-shadow .2s;
    outline:none;
}
.form-input:focus { border-color:var(--teal); box-shadow:0 0 0 3px var(--teal-glow); background:var(--white); }
textarea.form-input { resize:vertical; min-height:80px; }
.form-actions {
    display:flex;
    gap:10px;
    margin-top:8px;
    padding-top:16px;
    border-top:1px solid var(--border);
}
.check-group { display:flex; flex-wrap:wrap; gap:12px; align-items:center; }
.check-item { display:flex; align-items:center; gap:5px; cursor:pointer; }
.check-item input { cursor:pointer; accent-color:var(--teal); width:15px; height:15px; }

.rekom-card { background:var(--off); border-radius:12px; padding:20px; margin-top:16px; }
.rekom-row { display:flex; gap:32px; align-items:flex-start; margin-bottom:16px; }
.rekom-section { flex:1; }
.rekom-section h4 {
    font-size:.85rem;
    font-weight:700;
    margin-bottom:12px;
    color:var(--text-sub);
    text-transform:uppercase;
    letter-spacing:.5px;
}
.radio-group { display:flex; gap:16px; flex-wrap:wrap; }
.radio-item {
    display:flex;
    align-items:center;
    gap:8px;
    cursor:pointer;
    padding:8px 16px;
    border:1.5px solid var(--border);
    border-radius:9px;
    transition:all .2s;
    background:var(--white);
}
.radio-item:hover { border-color:var(--teal); background:var(--teal-light); }
.radio-item input { accent-color:var(--teal); width:16px; height:16px; }
.ttd-box img {
    max-height:60px;
    border:1px solid var(--border);
    border-radius:8px;
    padding:4px;
    background:var(--white);
}
.divider-line { height:1px; background:var(--border); margin:20px 0; }
.row-num { font-family:'DM Mono', monospace; color:var(--text-muted); font-size:.75rem; }
.empty-state { text-align:center; padding:48px 24px; color:var(--text-muted); }
.empty-state i { font-size:2.5rem; margin-bottom:12px; opacity:.4; display:block; }
.page-footer {
    padding:20px 32px;
    border-top:1px solid var(--border);
    background:var(--white);
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-size:.78rem;
    color:var(--text-muted);
    margin-top:auto;
}
.page-footer strong { color:var(--teal-dark); }

@media (max-width:900px) {
    



    .main { margin-left:0; }
    .form-grid { grid-template-columns:1fr; }
    .rekom-row { flex-direction:column; }
    .section-head { align-items:flex-start; flex-direction:column; }
}
@media print {
    .sidebar, .topbar, .page-footer, .no-print, .form-actions { display:none !important; }
    .main { margin-left:0; }
    .content { padding:0; }
    .card { box-shadow:none; border:1px solid #ddd; }
}
</style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-box">
            <img src="../images/lsplogosmkn1.png" alt="Logo LSP">
        </div>
        <div class="logo-text">
            <strong>LSP</strong>
            <span>SMKN 1 CIBINONG</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Asesor</div>
        <a href="validasiapl2.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Validasi APL2
        </a>
        <a href="asesormain.php" class="nav-item active">
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
            <div class="user-avatar"><?php echo e(strtoupper(substr($namax, 0, 2))); ?></div>
            <div class="user-info">
                <strong><?php echo e($namax); ?></strong>
                <span>Asesor LSP</span>
            </div>
            <button class="btn-logout" title="Logout" onclick="window.location='../logout.php'">
                <i class="fas fa-right-from-bracket"></i>
            </button>
        </div>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div class="topbar-title">
            FR.IA.08 Portofolio
            <span>Validasi bukti dan rekomendasi portofolio</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip">
                <i class="fas fa-calendar"></i>
                <?php echo e($today); ?>
            </div>
            <button class="icon-btn" type="button">
                <i class="fas fa-bell"></i>
            </button>
            <a href="../logout.php" class="btn btn-danger btn-sm" style="gap:6px;padding:7px 14px;font-weight:600;" title="Keluar dari sistem">
                <i class="fas fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </header>

    <div class="content">

<?php
if ($op == "pilihtanggal"):
    $idasesor_get = $_GET['idasesor'] ?? $idasesor_login;
    $idskema = $_GET['id'] ?? ($_GET['idskema'] ?? '');
    $kelompok = $_GET['kelompok'] ?? '';
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-calendar-days" style="color:var(--teal);margin-right:8px"></i>Pilih Tanggal Uji</h3>
                    <p>Pilih tanggal pelaksanaan asesmen untuk kelompok ini</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta">
                <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($idskema); ?>">
                <input type="hidden" name="idasesor" value="<?php echo e($idasesor_get); ?>">

                <div class="form-grid">
                    <div class="form-label">Tanggal Asesmen</div>
                    <select name="tgl" class="form-input" required>
                    <?php
                    $namaassapl2 = '';
                    $tampiltgl = "SELECT tanggal, namaasesor FROM pemetaan WHERE kelompok='$kelompok' AND idskema='$idskema' AND idasesor='$idasesor_get' GROUP BY tanggal, namaasesor";
                    $exectgl = mysqli_query($conn, $tampiltgl);
                    while ($rtgl = mysqli_fetch_array($exectgl)) {
                        $namaassapl2 = $rtgl['namaasesor'] ?? $namax;
                        echo "<option value='".e($rtgl['tanggal'])."'>".e($rtgl['tanggal'])."</option>";
                    }
                    ?>
                    </select>
                </div>

                <input type="hidden" name="namaasesor" value="<?php echo e($namaassapl2); ?>">

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Lanjutkan
                    </button>
                </div>
            </form>
        </div>

<?php
elseif ($op == "listpeserta"):
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['porto_idasesor'] = $_POST['idasesor'] ?? '';
        $_SESSION['porto_idskema'] = $_POST['idskema'] ?? '';
        $_SESSION['porto_kelompok'] = $_POST['kelompok'] ?? '';
        $_SESSION['porto_tgl'] = $_POST['tgl'] ?? '';
        $_SESSION['porto_namaasesor'] = $_POST['namaasesor'] ?? '';
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpeserta");
        exit;
    }

    $idasesor = $_SESSION['porto_idasesor'] ?? '';
    $idskema = $_SESSION['porto_idskema'] ?? '';
    $kelompok = $_SESSION['porto_kelompok'] ?? '';
    $tgl = $_SESSION['porto_tgl'] ?? '';
    $namaasesor = $_SESSION['porto_namaasesor'] ?? $namax;

    $sqluser = "SELECT linkttd, id FROM lsp_usertbl WHERE id='$idasesor'";
    $sqlusera = mysqli_query($conn, $sqluser);
    $sqluserb = mysqli_fetch_array($sqlusera);
    $linkttd_asesor = $sqluserb['linkttd'] ?? '';

    $ssl = "SELECT * FROM pemetaan WHERE kelompok='$kelompok' AND idskema='$idskema' AND tanggal='$tgl' AND idasesor='$idasesor'";
    $exec0 = mysqli_query($conn, $ssl);
    $total = $exec0 ? mysqli_num_rows($exec0) : 0;
?>
        <div class="card">
            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="alert-box alert-success alert-auto-hide">
                    <i class="fas fa-circle-check"></i>
                    <strong><?php echo e($_SESSION['flash_success']); ?></strong>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <div class="section-head">
                <div>
                    <h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Peserta Uji</h3>
                    <p>Tanggal: <strong><?php echo e($tgl); ?></strong> · <?php echo e($total); ?> peserta</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm">
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
                            <th>Status Portofolio</th>
                            <th style="width:240px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($exec0 && mysqli_num_rows($exec0) > 0):
                        $no = 1;
                        while ($hasil0 = mysqli_fetch_array($exec0)):
                            $id_asesi = $hasil0['idpeserta'];

                            $cekvlapl1 = "SELECT idasesi FROM upload WHERE idskema='$idskema' AND idasesi='$id_asesi' AND (status='N' OR status IS NULL)";
                            $cekvlapl1a = mysqli_query($conn, $cekvlapl1);
                            $b = $cekvlapl1a ? mysqli_num_rows($cekvlapl1a) : 0;

                            $cekvlaplabcd = "SELECT idasesi FROM upload WHERE idskema='$idskema' AND idasesi='$id_asesi'";
                            $cekvlaplabde = mysqli_query($conn, $cekvlaplabcd);
                            $c = $cekvlaplabde ? mysqli_num_rows($cekvlaplabde) : 0;

                            $ketvl = '<span class="badge badge-orange"><i class="fas fa-magnifying-glass"></i> Belum mengisi</span>';
                            if ($c > 0) {
                                if ($b > 0) {
                                    $ketvl = '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Belum divalidasi</span>';
                                } else {
                                    $ketvl = '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Sudah divalidasi</span>';
                                }
                            }

                            $idp_temp = mysqli_real_escape_string($conn, $hasil0['idpeserta']);
                            $qp_temp = mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE id='$idp_temp' LIMIT 1");
                            $dp_temp = $qp_temp ? mysqli_fetch_array($qp_temp) : array();
                            $nama_asesi = $dp_temp['nama'] ?? ($hasil0['namapeserta'] ?? 'Asesi');
                    ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td><span class="badge badge-navy"><?php echo e($id_asesi); ?></span></td>
                            <td style="font-weight:600"><?php echo e($nama_asesi); ?></td>
                            <td style="color:var(--text-sub)"><?php echo e($hasil0['tanggal']); ?></td>
                            <td><?php echo $ketvl; ?></td>
                            <td style="text-align:center">
                                <?php if ($b > 0): ?>
                                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=validasiunitporto&idasesor=<?php echo e($idasesor); ?>&k=<?php echo e(urlencode($kelompok)); ?>&tgl=<?php echo e(urlencode($tgl)); ?>&kode=in&idasesi=<?php echo e($id_asesi); ?>&idskema=<?php echo e($idskema); ?>&nmass=<?php echo e(urlencode($namaasesor)); ?>"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-clipboard-check"></i> Validasi
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-check"></i> Selesai
                                    </button>
                                <?php endif; ?>

                                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=rekompro&idasesor=<?php echo e($idasesor); ?>&tgl=<?php echo e(urlencode($tgl)); ?>&idasesi=<?php echo e($id_asesi); ?>&idskema=<?php echo e($idskema); ?>&lkttd=<?php echo e(urlencode($linkttd_asesor)); ?>&nmass=<?php echo e(urlencode($namaasesor)); ?>&kelomp=<?php echo e(urlencode($kelompok)); ?>"
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-star"></i> Rekomendasi
                                </a>
                            </td>
                        </tr>
                    <?php
                        $no++;
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <p>Belum ada peserta pada tanggal ini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php
elseif ($op == "rekompro"):
    $namaasesorttd = $_GET['nmass'] ?? $namax;
    $idasesorttd = $_GET['idasesor'] ?? '';
    $idadsesittd = $_GET['idasesi'] ?? '';
    $tglttd = $_GET['tgl'] ?? '';
    $idskttd = $_GET['idskema'] ?? '';
    $ttdass = "../imgttd/" . ($_GET['lkttd'] ?? '');
    $kelompttd = $_GET['kelomp'] ?? ($_SESSION['porto_kelompok'] ?? '');

    $cekduluvlapl2 = "SELECT idadsesi,idskema,waktu,svalidasi FROM apl2 WHERE idadsesi='$idadsesittd' AND waktu='$tglttd' AND svalidasi='T' AND idskema='$idskttd' GROUP BY idadsesi,idskema,waktu,svalidasi";
    $cekduluvlapl2a = mysqli_query($conn, $cekduluvlapl2);
    $cekduluvlapl2b = $cekduluvlapl2a ? mysqli_num_rows($cekduluvlapl2a) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-clipboard-check" style="color:var(--teal);margin-right:8px"></i>Rekomendasi Portofolio</h3>
                    <p>Berikan rekomendasi hasil validasi portofolio untuk asesi</p>
                </div>
                <div style="display:flex;gap:10px">
                    <button onclick="window.print()" class="btn btn-print btn-sm no-print" type="button">
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
                    <strong>Validasi belum selesai.</strong> Masih ada item APL2 yang belum divalidasi.
                </div>
            <?php else:
                $cekdata0 = "SELECT * FROM rekomendasi WHERE namarekom='pro' AND idskema='$idskttd' AND idasesi='$idadsesittd' AND tanggal='$tglttd'";
                $ada0 = mysqli_query($conn, $cekdata0);
                if ($ada0 && mysqli_num_rows($ada0) > 0) {
                    $adax = mysqli_fetch_array($ada0);
                    $lrek = $adax['rekom'] ?? '';
                    $cat = $adax['catatan'] ?? '';
                } else {
                    $lrek = '';
                    $cat = '';
                }

                $klrek = ($lrek == 'L') ? 'checked' : '';
                $klrek0 = ($lrek == 'T') ? 'checked' : '';

                $sqlttd = "SELECT * FROM lsp_usertbl WHERE id='$idadsesittd'";
                $sqlttda = mysqli_query($conn, $sqlttd);
                if ($sqlttda && mysqli_num_rows($sqlttda) > 0) {
                    $sqlttdb = mysqli_fetch_array($sqlttda);
                    $linkttda = !empty($sqlttdb['linkttd']) ? "../imgttd/" . $sqlttdb['linkttd'] : "";
                    $namapttd = $sqlttdb['nama'] ?? '';
                    $emailttd = $sqlttdb['email'] ?? '';
                } else {
                    $linkttda = '';
                    $namapttd = '-';
                    $emailttd = '';
                }
            ?>
            <form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=srekompro">
                <input type="hidden" name="idskttd" value="<?php echo e($idskttd); ?>">
                <input type="hidden" name="idadsesittd" value="<?php echo e($idadsesittd); ?>">
                <input type="hidden" name="tglttd" value="<?php echo e($tglttd); ?>">
                <input type="hidden" name="idasesorttd" value="<?php echo e($idasesorttd); ?>">
                <input type="hidden" name="emailttd" value="<?php echo e($emailttd); ?>">
                <input type="hidden" name="nmasesorttd" value="<?php echo e($namaasesorttd); ?>">
                <input type="hidden" name="klttd" value="<?php echo e($kelompttd); ?>">

                <div class="rekom-card">
                    <div class="rekom-row">
                        <div class="rekom-section">
                            <h4>Rekomendasi</h4>
                            <div class="radio-group">
                                <label class="radio-item">
                                    <input type="radio" name="lrekttd" value="L" <?php echo $klrek; ?> required>
                                    <i class="fas fa-circle-check" style="color:#22C55E"></i> Kompetensi
                                </label>
                                <label class="radio-item">
                                    <input type="radio" name="lrekttd" value="T" <?php echo $klrek0; ?>>
                                    <i class="fas fa-circle-xmark" style="color:#EF4444"></i> Uji Kompetensi
                                </label>
                            </div>
                        </div>

                        <div class="rekom-section">
                            <h4>Catatan</h4>
                            <textarea name="cttapl2" class="form-input" rows="3" placeholder="Tulis catatan rekomendasi..."><?php echo e($cat); ?></textarea>
                        </div>
                    </div>

                    <div class="divider-line"></div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                        <div>
                            <p style="font-size:.75rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesi</p>
                            <p style="font-weight:600;margin-bottom:6px"><?php echo e($namapttd); ?></p>
                            <?php if ($linkttda != ""): ?>
                                <div class="ttd-box"><img src="<?php echo e($linkttda); ?>" alt="TTD Asesi"></div>
                            <?php else: ?>
                                <span class="badge badge-gray">TTD belum tersedia</span>
                            <?php endif; ?>
                        </div>

                        <div>
                            <p style="font-size:.75rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesor</p>
                            <p style="font-weight:600;margin-bottom:6px"><?php echo e($namaasesorttd); ?></p>
                            <?php if ($ttdass != "../imgttd/"): ?>
                                <div class="ttd-box"><img src="<?php echo e($ttdass); ?>" height="50" alt="TTD Asesor"></div>
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
elseif ($op == "validasiunitporto"):
    $namaasesor = $_GET['nmass'] ?? $namax;
    $idasesor = $_GET['idasesor'] ?? '';
    $ie = $_GET['kode'] ?? '';
    $idadsesi = $_GET['idasesi'] ?? '';
    $idsk = $_GET['idskema'] ?? '';
    $tgl = $_GET['tgl'] ?? '';
    $kelompok = $_GET['k'] ?? '';
    $emailuser = trim($uname);

    $sqlunitporto = "SELECT unitsiswa.idadsesi,unitsiswa.idunit,unit.kodeunit,unit.namaunit,unit.idskema FROM unitsiswa INNER JOIN unit ON unitsiswa.idunit=unit.idunit WHERE unitsiswa.idskema='$idsk' AND unitsiswa.idadsesi='$idadsesi' ORDER BY unit.kodeunit";
    $execunitporto = mysqli_query($conn, $sqlunitporto);
    $totalunit = $execunitporto ? mysqli_num_rows($execunitporto) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-list-check" style="color:var(--teal);margin-right:8px"></i>Pilih Unit Portofolio</h3>
                    <p>Pilih unit kompetensi yang akan divalidasi · <?php echo e($totalunit); ?> unit tersedia</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=validasi">
                <div class="tbl-wrap">
                    <table class="unit-tbl">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th style="width:60px">Pilih</th>
                                <th style="width:160px">Kode Unit</th>
                                <th>Nama Unit</th>
                                <th style="width:260px">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $idskemavporto = $idsk;

                        if ($execunitporto && mysqli_num_rows($execunitporto) > 0):
                            while ($unitporto = mysqli_fetch_array($execunitporto)):
                                $dunitporto = $unitporto['idunit'];
                                $kodeunitporto = $unitporto['kodeunit'];
                                $namaunitporto = $unitporto['namaunit'];
                                $idskemavporto = $unitporto['idskema'];

                                $sqlcekapporto = "SELECT idskema,idasesi,idunit,idelemen FROM upload WHERE idasesi='$idadsesi' AND idskema='$idsk' AND idunit='$dunitporto'";
                                $execsqlunitaporto = mysqli_query($conn, $sqlcekapporto);
                                $bykunitportov = $execsqlunitaporto ? mysqli_num_rows($execsqlunitaporto) : 0;

                                $sqlelemenportov = "SELECT COUNT(idelemen) AS bykportov FROM elemen WHERE idunit='$dunitporto'";
                                $execelemenportov = mysqli_query($conn, $sqlelemenportov);
                                $execelemenportoav = mysqli_fetch_array($execelemenportov);
                                $bykelemenportov = $execelemenportoav['bykportov'] ?? 0;

                                $ckjmlvalpor = "SELECT COUNT(idasesi) AS bykporto FROM upload WHERE idasesi='$idadsesi' AND idskema='$idsk' AND idunit='$dunitporto' AND status='Y'";
                                $ckjmlvalapor = mysqli_query($conn, $ckjmlvalpor);
                                $ckjmlvalbpor = mysqli_fetch_array($ckjmlvalapor);
                                $ckjmlvaldpor = $ckjmlvalbpor['bykporto'] ?? 0;

                                $stadavpor = ($bykunitportov > 0 && $ckjmlvaldpor == $bykelemenportov && $bykelemenportov > 0) ? 'disabled' : '';

                                if ($stadavpor) {
                                    $statusBadge = '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Selesai ('.$ckjmlvaldpor.'/'.$bykelemenportov.')</span>';
                                } elseif ($bykunitportov > 0) {
                                    $statusBadge = '<span class="badge badge-orange"><i class="fas fa-clock"></i> Proses ('.$ckjmlvaldpor.'/'.$bykelemenportov.')</span>';
                                } else {
                                    $statusBadge = '<span class="badge badge-gray"><i class="fas fa-minus"></i> Belum diisi</span>';
                                }
                        ?>
                            <tr>
                                <td class="row-num"><?php echo e(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></td>
                                <td style="text-align:center">
                                    <input type="hidden" name="idskema<?php echo e($i); ?>" value="<?php echo e($idsk); ?>">
                                    <input type="hidden" name="idunit<?php echo e($i); ?>" value="<?php echo e($dunitporto); ?>">
                                    <input type="checkbox" name="kodeunit<?php echo e($i); ?>" value="<?php echo e($kodeunitporto); ?>" <?php echo $stadavpor; ?> style="width:16px;height:16px;accent-color:var(--teal);cursor:pointer">
                                </td>
                                <td><span class="badge badge-teal"><?php echo e($kodeunitporto); ?></span></td>
                                <td style="font-weight:500"><?php echo e($namaunitporto); ?></td>
                                <td><?php echo $statusBadge; ?></td>
                            </tr>
                        <?php
                                $i++;
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-folder-open"></i>
                                        <p>Tidak ada unit kompetensi untuk asesi ini.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="np" value="<?php echo e($i); ?>">
                <input type="hidden" name="emailuserapl2" value="<?php echo e($emailuser); ?>">
                <input type="hidden" name="idasesorp" value="<?php echo e($idasesor); ?>">
                <input type="hidden" name="kodep" value="<?php echo e($ie); ?>">
                <input type="hidden" name="idasesip" value="<?php echo e($idadsesi); ?>">
                <input type="hidden" name="idskemap" value="<?php echo e($idskemavporto); ?>">
                <input type="hidden" name="tglp" value="<?php echo e($tgl); ?>">
                <input type="hidden" name="kp" value="<?php echo e($kelompok); ?>">
                <input type="hidden" name="nmasserp" value="<?php echo e($namaasesor); ?>">

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Lanjutkan Validasi
                    </button>
                </div>
            </form>
        </div>

<?php
elseif ($op == "validasi"):
    $idasesor = $_POST['idasesorp'] ?? '';
    $idskema = $_POST['idskemap'] ?? '';
    $idasesi = $_POST['idasesip'] ?? '';
    $ie = $_POST['kodep'] ?? '';
    $tgl = $_POST['tglp'] ?? '';
    $kelompok = $_POST['kp'] ?? '';

    $sqlskema = "SELECT * FROM skema WHERE idskema='$idskema'";
    $execskema = mysqli_query($conn, $sqlskema);
    $listskema = mysqli_fetch_array($execskema);
    $namaskema = $listskema['namaskema'] ?? '';

    $sqladsesi = "SELECT * FROM lsp_usertbl WHERE id='$idasesi' OR id='$idasesi' LIMIT 1";
    $execadsesi = mysqli_query($conn, $sqladsesi);
    $listadsesi = mysqli_fetch_array($execadsesi);
    $namaadsesi = $listadsesi['nama'] ?? '-';
    $emailadsesi = $listadsesi['email'] ?? '';
    $nnp = (int)($_POST['np'] ?? 0);
    $w = 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-clipboard-check" style="color:var(--teal);margin-right:8px"></i>Validasi Bukti Portofolio</h3>
                    <p>Skema: <strong><?php echo e($namaskema); ?></strong> · Asesi: <strong><?php echo e($namaadsesi); ?></strong> · Tanggal: <?php echo e($tgl); ?></p>
                </div>
                <div style="display:flex;gap:10px">
                    <button onclick="window.print()" class="btn btn-print btn-sm no-print" type="button">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm no-print">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="alert-box alert-info">
                <i class="fas fa-circle-info"></i>
                Centang bukti yang sesuai: <strong>V</strong> Valid · <strong>A</strong> Asli · <strong>T</strong> Terkini · <strong>M</strong> Memadai.
            </div>

            <form id="formValidasiPorto" method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=simpanvalidasi">
                <div class="tbl-wrap">
                    <table class="apl-tbl">
                        <thead>
                            <tr>
                                <th>Elemen Kompetensi</th>
                                <th style="width:220px">Bukti Relevan</th>
                                <th style="width:180px">Dokumen</th>
                                <th style="width:190px">Kesesuaian Bukti</th>
                                <th style="width:120px">Memadai</th>
                                <th style="width:80px">Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        for ($cbap = 0; $cbap <= $nnp - 1; $cbap++):
                            if (!isset($_POST['kodeunit'.$cbap])) continue;

                            $idunitxyzp = $_POST['idunit'.$cbap] ?? '';
                            if (strlen($_POST['kodeunit'.$cbap]) <= 0) continue;

                            $ssql1 = "SELECT * FROM upload WHERE idskema='$idskema' AND idasesi='$idasesi' AND idunit='$idunitxyzp' ORDER BY id_upload";
                            $exec1 = mysqli_query($conn, $ssql1);

                            if ($exec1 && mysqli_num_rows($exec1) > 0):
                                $unitPrinted = false;
                                while ($list1 = mysqli_fetch_array($exec1)):
                                    $idunit = $list1['idunit'];
                                    $idelemen = $list1['idelemen'];
                                    $bukti = $list1['bukti'];
                                    $path = $list1['path'] ?? ($list1['Path'] ?? '');
                                    $dbukti = $list1['dbukti'] ?? '';
                                    $lt = $list1['lt'] ?? '';
                                    $status = $list1['status'] ?? 'N';

                                    $unitapl1 = "SELECT * FROM unit WHERE idunit='$idunit'";
                                    $unitapl1a = mysqli_query($conn, $unitapl1);
                                    $unitapl1b = mysqli_fetch_array($unitapl1a);
                                    $nmunitapl1 = $unitapl1b['namaunit'] ?? '';
                                    $kdunitapl1 = $unitapl1b['kodeunit'] ?? '';

                                    if (!$unitPrinted):
                        ?>
                            <tr class="unit-head">
                                <td colspan="6">
                                    <i class="fas fa-cubes" style="color:var(--teal);margin-right:6px"></i>
                                    <?php echo e($kdunitapl1); ?> - <?php echo e($nmunitapl1); ?>
                                </td>
                            </tr>
                        <?php
                                        $unitPrinted = true;
                                    endif;

                                    $val = ($status == 'Y') ? 'SV' : 'BV';

                                    $pecahdb = explode(",", $dbukti);
                                    $db0 = $pecahdb[0] ?? '';
                                    $db1 = $pecahdb[1] ?? '';
                                    $db2 = $pecahdb[2] ?? '';
                                    $db3 = $pecahdb[3] ?? '';

                                    $kdb0 = (trim($db0) == 'v') ? 'checked' : '';
                                    $kdb1 = (!empty($db1)) ? 'checked' : '';
                                    $kdb2 = (!empty($db2)) ? 'checked' : '';
                                    $kdb3 = (!empty($db3)) ? 'checked' : '';

                                    $pecahbu = explode(",", $bukti);
                                    $bu0 = $pecahbu[0] ?? '';
                                    $bu1 = $pecahbu[1] ?? '';
                                    $bu2 = $pecahbu[2] ?? '';
                                    $bu3 = $pecahbu[3] ?? '';
                                    $bu4 = $pecahbu[4] ?? '';
                                    $bu5 = $pecahbu[5] ?? '';
                                    $bu6 = $pecahbu[6] ?? '';
                                    $bu7 = $pecahbu[7] ?? '';

                                    $kbu0 = (!empty($bu0)) ? 'checked' : '';
                                    $kbu1 = (!empty($bu1)) ? 'checked' : '';
                                    $kbu2 = (!empty($bu2)) ? 'checked' : '';
                                    $kbu3 = (!empty($bu3)) ? 'checked' : '';
                                    $kbu4 = (!empty($bu4)) ? 'checked' : '';
                                    $kbu5 = (!empty($bu5)) ? 'checked' : '';
                                    $kbu6 = (!empty($bu6)) ? 'checked' : '';
                                    $kbu7 = (!empty($bu7)) ? 'checked' : '';

                                    $lradio = ($lt == 'Y') ? 'checked' : '';
                                    $tradio = ($lt == 'T') ? 'checked' : '';

                                    $ssql2 = "SELECT * FROM elemen WHERE idskema='$idskema' AND idunit='$idunit' AND idelemen='$idelemen'";
                                    $exec2 = mysqli_query($conn, $ssql2);
                                    $list2 = mysqli_fetch_array($exec2);
                                    $namae = $list2['namaelemen'] ?? '';
                        ?>
                            <tr>
                                <td>
                                    <?php echo e($namae); ?>
                                    <input type="hidden" name="idelemen<?php echo e($w); ?>" value="<?php echo e($idelemen); ?>">
                                    <input type="hidden" name="idunit<?php echo e($w); ?>" value="<?php echo e($idunit); ?>">
                                </td>
                                <td>
                                    <div class="check-group">
                                        <label class="check-item"><input type="checkbox" value="sk" <?php echo $kbu0; ?> disabled> SK</label>
                                        <label class="check-item"><input type="checkbox" value="sr" <?php echo $kbu1; ?> disabled> SR</label>
                                        <label class="check-item"><input type="checkbox" value="cp" <?php echo $kbu2; ?> disabled> CP</label>
                                        <label class="check-item"><input type="checkbox" value="jd" <?php echo $kbu3; ?> disabled> JD</label>
                                        <label class="check-item"><input type="checkbox" value="ws" <?php echo $kbu4; ?> disabled> WS</label>
                                        <label class="check-item"><input type="checkbox" value="de" <?php echo $kbu5; ?> disabled> DE</label>
                                        <label class="check-item"><input type="checkbox" value="pe" <?php echo $kbu6; ?> disabled> PE</label>
                                        <label class="check-item"><input type="checkbox" value="l" <?php echo $kbu7; ?> disabled> L</label>
                                    </div>
                                </td>
                                <td>
                                    <a class="btn btn-secondary btn-sm" href="../siswa/gambarimages/<?php echo e($path); ?>" target="_blank">
                                        <i class="fas fa-file"></i> Dokumen
                                    </a>
                                </td>
                                <td>
                                    <div class="check-group">
                                        <label class="check-item"><input type="checkbox" name="validasia<?php echo e($w); ?>" value="v" <?php echo $kdb0; ?>> V</label>
                                        <label class="check-item"><input type="checkbox" name="validasib<?php echo e($w); ?>" value="a" <?php echo $kdb1; ?>> A</label>
                                        <label class="check-item"><input type="checkbox" name="validasic<?php echo e($w); ?>" value="t" <?php echo $kdb2; ?>> T</label>
                                        <label class="check-item"><input type="checkbox" name="validasid<?php echo e($w); ?>" value="m" <?php echo $kdb3; ?>> M</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="check-group">
                                        <label class="check-item"><input type="radio" name="lt<?php echo e($w); ?>" value="Y" <?php echo $lradio; ?>> Y</label>
                                        <label class="check-item"><input type="radio" name="lt<?php echo e($w); ?>" value="T" <?php echo $tradio; ?>> T</label>
                                    </div>
                                </td>
                                <td><span class="badge <?php echo ($val == 'SV') ? 'badge-green' : 'badge-orange'; ?>"><?php echo e($val); ?></span></td>
                            </tr>
                        <?php
                                    $w++;
                                endwhile;
                            endif;
                        endfor;
                        ?>
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="npor" value="<?php echo e($w); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($idskema); ?>">
                <input type="hidden" name="idasesi" value="<?php echo e($idasesi); ?>">
                <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">
                <input type="hidden" name="idasesor" value="<?php echo e($idasesor); ?>">
                <input type="hidden" name="tgl" value="<?php echo e($tgl); ?>">
                <input type="hidden" name="emailadsesi" value="<?php echo e($emailadsesi); ?>">

                <div class="divider-line"></div>
                <div style="background:var(--off);border-radius:10px;padding:16px 20px;margin-bottom:16px">
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:.88rem;font-weight:600">
                        <input type="checkbox" id="ckok" style="width:18px;height:18px;accent-color:var(--teal);cursor:pointer">
                        Saya sudah memeriksa dan melakukan validasi terhadap seluruh data di atas.
                    </label>
                </div>

                <div class="form-actions">
                    <button id="lanjutkan" name="simpan" type="submit" class="btn btn-primary" disabled>
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
elseif ($op == "simpanvalidasi"):
    $idasesor = $_POST['idasesor'] ?? '';
    $kelompok = $_POST['kelompok'] ?? '';
    $idskema = $_POST['idskema'] ?? '';
    $idasesi = $_POST['idasesi'] ?? '';
    $n = (int)($_POST['npor'] ?? 0);
    $tgl = $_POST['tgl'] ?? '';
    $emailad = $_POST['emailadsesi'] ?? '';

    $total_proses = 0;
    $total_sukses = 0;

    for ($i = 0; $i <= $n - 1; $i++) {
        if (
            isset($_POST['validasia'.$i]) ||
            isset($_POST['validasib'.$i]) ||
            isset($_POST['validasic'.$i]) ||
            isset($_POST['validasid'.$i])
        ) {
            $idelemen = $_POST['idelemen'.$i] ?? '';
            $validasi0 = $_POST['validasia'.$i] ?? '';
            $validasi1 = $_POST['validasib'.$i] ?? '';
            $validasi2 = $_POST['validasic'.$i] ?? '';
            $validasi3 = $_POST['validasid'.$i] ?? '';
            $idunitcc = $_POST['idunit'.$i] ?? '';
            $lt = $_POST['lt'.$i] ?? '';

            $allvalidasi = $validasi0 . "," . $validasi1 . "," . $validasi2 . "," . $validasi3;

            $sqlupdate = "UPDATE upload SET dbukti='$allvalidasi', lt='$lt', status='Y' WHERE idskema='$idskema' AND idasesi='$idasesi' AND idelemen='$idelemen' AND idunit='$idunitcc'";
            $total_proses++;
            if (mysqli_query($conn, $sqlupdate)) {
                $total_sukses++;
            }
        }
    }

    $updatesksiswa = "UPDATE skemasiswa SET statusapl1='Y' WHERE emailsiswa='$emailad' AND idskema='$idskema'";
    mysqli_query($conn, $updatesksiswa);
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-circle-check" style="color:var(--teal);margin-right:8px"></i>Hasil Simpan Validasi</h3>
                    <p>Proses validasi portofolio telah selesai.</p>
                </div>
            </div>

            <?php if ($total_proses > 0 && $total_sukses == $total_proses): ?>
                <div class="alert-box alert-success">
                    <i class="fas fa-circle-check"></i>
                    <div>
                        <strong>Data berhasil disimpan.</strong>
                        <p style="margin-top:2px;font-size:.8rem">Total data diproses: <?php echo e($total_sukses); ?>.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert-box alert-warning">
                    <i class="fas fa-triangle-exclamation"></i>
                    <div>
                        <strong>Proses selesai, tapi tidak semua data diproses.</strong>
                        <p style="margin-top:2px;font-size:.8rem">Total diproses: <?php echo e($total_sukses); ?> dari <?php echo e($total_proses); ?>.</p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta">
                <input type="hidden" name="idasesor" value="<?php echo e($idasesor); ?>">
                <input type="hidden" name="tgl" value="<?php echo e($tgl); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($idskema); ?>">
                <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Peserta
                </button>
            </form>
        </div>

<?php
elseif ($op == "srekompro"):
    $idskemarekapl2 = $_POST['idskttd'] ?? '';
    $idasesirekapl2 = $_POST['idadsesittd'] ?? '';
    $tglrekapl2 = $_POST['tglttd'] ?? '';
    $idasesorrekapl2 = $_POST['idasesorttd'] ?? '';
    $lrekapl2 = $_POST['lrekttd'] ?? '';
    $catatanrekapl2 = $_POST['cttapl2'] ?? '';
    $emailad = $_POST['emailttd'] ?? '';
    $nmasesorapl2 = $_POST['nmasesorttd'] ?? $namax;
    $klapl2 = $_POST['klttd'] ?? '';

    $cekdata = "SELECT * FROM rekomendasi WHERE namarekom='pro' AND idskema='$idskemarekapl2' AND idasesi='$idasesirekapl2' AND tanggal='$tglrekapl2'";
    $ada = mysqli_query($conn, $cekdata);

    if ($ada && mysqli_num_rows($ada) > 0) {
        $ssqlrekapl2 = "UPDATE rekomendasi SET rekom='$lrekapl2', catatan='$catatanrekapl2' WHERE namarekom='pro' AND idskema='$idskemarekapl2' AND idasesi='$idasesirekapl2' AND tanggal='$tglrekapl2'";
    } else {
        $ssqlrekapl2 = "INSERT INTO rekomendasi (namarekom, idskema, idasesi, rekom, catatan, tanggal) VALUES ('pro', '$idskemarekapl2', '$idasesirekapl2', '$lrekapl2', '$catatanrekapl2', '$tglrekapl2')";
    }

    $execrekapl2 = mysqli_query($conn, $ssqlrekapl2);

    if ($execrekapl2) {
        $updskemasis = "UPDATE skemasiswa SET statusapl2='Y' WHERE idskema='$idskemarekapl2' AND emailsiswa='$emailad'";
        mysqli_query($conn, $updskemasis);

        $_SESSION['porto_idasesor'] = $idasesorrekapl2;
        $_SESSION['porto_idskema'] = $idskemarekapl2;
        $_SESSION['porto_kelompok'] = $klapl2;
        $_SESSION['porto_tgl'] = $tglrekapl2;
        $_SESSION['porto_namaasesor'] = $nmasesorapl2;
        $_SESSION['flash_success'] = 'Rekomendasi berhasil disimpan.';
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpeserta");
        exit;
    }
?>
        <div class="card">
            <div class="alert-box alert-error">
                <i class="fas fa-circle-xmark"></i>
                <strong>Penyimpanan gagal.</strong>
            </div>
            <button onclick="history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </button>
        </div>

<?php
else:
    $cekduluttd = "SELECT id, linkttd FROM lsp_usertbl WHERE id='$idasesorxv'";
    $cekduluttda = mysqli_query($conn, $cekduluttd);
    $cekduluttdb = mysqli_fetch_array($cekduluttda);
    $cekduluttdc = strlen($cekduluttdb['linkttd'] ?? '');

    if ($cekduluttdc > 0):
        $queryvmain = "SELECT kelompok, idskema, idasesor FROM pemetaan WHERE idasesor='$idasesorxv' GROUP BY kelompok, idskema, idasesor";
        $hasilvmain = mysqli_query($conn, $queryvmain);
        $total_jadwal = $hasilvmain ? mysqli_num_rows($hasilvmain) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-calendar-check" style="color:var(--teal);margin-right:8px"></i>Daftar Jadwal Portofolio</h3>
                    <p>Pilih jadwal untuk memulai validasi portofolio peserta</p>
                </div>
                <span style="background:var(--teal-light);color:var(--teal-dark);padding:4px 14px;border-radius:6px;font-size:.78rem;font-weight:700">
                    <?php echo e($total_jadwal); ?> Jadwal
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
                    while ($datavvmain = mysqli_fetch_array($hasilvmain)):
                        $id_skema_dari_tabel_lain = $datavvmain['idskema'];
                        $ssql = "SELECT namaskema FROM skema WHERE idskema='$id_skema_dari_tabel_lain'";
                        $execssql = mysqli_query($conn, $ssql);

                        if ($execssql && mysqli_num_rows($execssql) > 0) {
                            $baris = mysqli_fetch_array($execssql);
                            $namaskema = e($baris['namaskema']);
                        } else {
                            $namaskema = '<em style="color:var(--text-muted)">Skema tidak ditemukan (ID: '.e($id_skema_dari_tabel_lain).')</em>';
                        }
                    ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td><span class="badge badge-navy"><?php echo e($datavvmain['kelompok']); ?></span></td>
                            <td><span class="badge badge-teal"><?php echo e($datavvmain['idskema']); ?></span></td>
                            <td style="font-weight:600"><?php echo $namaskema; ?></td>
                            <td style="text-align:center">
                                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=pilihtanggal&idasesor=<?php echo e($datavvmain['idasesor']); ?>&id=<?php echo e($datavvmain['idskema']); ?>&kelompok=<?php echo e(urlencode($datavvmain['kelompok'])); ?>"
                                   class="btn btn-primary btn-sm">
                                    <i class="fas fa-users"></i> Tampilkan Peserta
                                </a>
                            </td>
                        </tr>
                    <?php
                        $no++;
                    endwhile;
                    ?>
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
                    <strong>Tanda tangan belum diisi.</strong>
                    <p style="margin-top:2px;font-size:.8rem">Harap lengkapi tanda tangan sebelum melakukan validasi.</p>
                </div>
            </div>
            <a href="tandatanganass.php" class="btn btn-primary">
                <i class="fas fa-signature"></i> Isi Tanda Tangan Sekarang
            </a>
        </div>
    <?php endif; ?>
<?php endif; ?>

    </div>

    <footer class="page-footer">
        <span>© <?php echo date('Y'); ?> <strong>LSP SMKN 1 Cibinong</strong>. Semua hak dilindungi.</span>
        <span>Versi 1.0.0 · <?php echo e($today); ?>, <?php echo e($current_time); ?> WIB</span>
    </footer>
</div>

</body>
</html>
<?php ob_end_flush(); ?>