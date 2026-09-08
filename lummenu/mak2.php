<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function checked_if($a, $b) {
    return ((string)$a === (string)$b) ? "checked" : "";
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
$qUser = "SELECT * FROM lsp_usertbl WHERE email='$uname'";
$rUser = mysqli_query($conn, $qUser);
$dUser = mysqli_fetch_array($rUser);

$namax = $dUser['nama'] ?? 'Asesor';
$idasesor = $dUser['id'] ?? ($_SESSION['id'] ?? '');
$idasesor_user = $dUser['id'] ?? ($_SESSION['id_user'] ?? $idasesor);
$linkttd_asesor_file = $dUser['linkttd'] ?? '';
$linkttd_asesor = $linkttd_asesor_file !== '' ? "../imgttd/" . $linkttd_asesor_file : "";
$op = $_REQUEST['op'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.AK.02 Rekaman Asesmen - LSP SMKN 1 Cibinong</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="../js/jquery-2.2.3.min.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>
<script src="js/bootstrap.js"></script>

<script>
window.setTimeout(function() {
    document.querySelectorAll('.alert-auto-hide').forEach(function(el) {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(function(){ el.remove(); }, 500);
    });
}, 3000);
</script>

<style>
:root {
    --teal:#3BBFBF;
    --teal-dark:#2A9999;
    --teal-light:#E8F8F8;
    --teal-glow:rgba(59,191,191,.18);
    --navy:#0F2A3A;
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
.logo-text strong { display:block; color:#fff; font-size:.95rem; font-weight:700; }
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
}
.nav-item:hover { background:rgba(255,255,255,.07); color:#fff; text-decoration:none; }
.nav-item.active { background:var(--teal); color:#fff; box-shadow:0 4px 12px rgba(59,191,191,.35); }
.nav-item i { width:18px; text-align:center; font-size:.9rem; flex-shrink:0; }
.sidebar-footer {
    padding:16px 14px;
    border-top:1px solid rgba(255,255,255,.07);
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
    color:#fff;
    flex-shrink:0;
}
.user-info strong { display:block; color:#fff; font-size:.82rem; }
.user-info span { color:var(--teal); font-size:.72rem; }
.btn-logout {
    margin-left:auto;
    color:rgba(255,255,255,.35);
    background:none;
    border:none;
    cursor:pointer;
    font-size:.85rem;
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
    background:#fff;
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
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    color:var(--text-sub);
    font-size:.9rem;
}
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
    background:#fff;
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
.tbl, .unit-tbl, .mak-tbl { width:100%; border-collapse:collapse; }
.tbl th, .unit-tbl th, .mak-tbl th {
    text-align:left;
    padding:12px 16px;
    font-size:.72rem;
    font-weight:700;
    letter-spacing:.7px;
    text-transform:uppercase;
    color:rgba(255,255,255,.78);
    background:var(--navy);
}
.tbl th:first-child, .unit-tbl th:first-child, .mak-tbl th:first-child { border-radius:8px 0 0 8px; }
.tbl th:last-child, .unit-tbl th:last-child, .mak-tbl th:last-child { border-radius:0 8px 8px 0; }
.tbl td, .unit-tbl td, .mak-tbl td {
    padding:13px 16px;
    font-size:.845rem;
    border-bottom:1px solid var(--off);
    vertical-align:middle;
}
.tbl tbody tr:hover td, .unit-tbl tbody tr:hover td, .mak-tbl tbody tr:hover td { background:#F0F9F9; }
.mak-tbl .unit-head td {
    background:var(--off);
    font-weight:700;
    color:var(--navy);
    border-left:3px solid var(--teal);
}
.mak-tbl .elemen-head td {
    background:#EFF6FF;
    font-weight:600;
    color:#1D4ED8;
    padding-left:24px;
}
.mak-tbl .guide-row td {
    background:#EFF6FF;
    color:#1D4ED8;
    line-height:1.6;
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
.btn-primary { background:var(--teal); color:#fff; box-shadow:0 2px 8px rgba(59,191,191,.3); }
.btn-primary:hover { background:var(--teal-dark); color:#fff; }
.btn-secondary { background:#fff; color:var(--text-main); border-color:var(--border); }
.btn-secondary:hover { border-color:var(--teal); color:var(--teal-dark); background:var(--teal-light); }
.btn-success { background:#22C55E; color:#fff; border-color:#22C55E; }
.btn-success:hover { background:#16A34A; color:#fff; }
.btn-warning { background:#FFF7ED; color:#C2410C; border-color:#FDBA74; }
.btn-warning:hover { background:#F97316; color:#fff; border-color:#F97316; }
.btn-print { background:var(--navy); color:#fff; border-color:var(--navy); }
.btn-print:hover { background:var(--navy-soft); color:#fff; }
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
    outline:none;
}
.form-input:focus { border-color:var(--teal); box-shadow:0 0 0 3px var(--teal-glow); background:#fff; }
textarea.form-input { resize:vertical; min-height:90px; }
.form-actions {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:8px;
    padding-top:16px;
    border-top:1px solid var(--border);
}
.radio-group, .check-group { display:flex; flex-wrap:wrap; gap:12px; align-items:center; }
.radio-item, .check-item {
    display:inline-flex;
    align-items:center;
    gap:7px;
    cursor:pointer;
}
.radio-item input, .check-item input {
    accent-color:var(--teal);
    width:16px;
    height:16px;
}
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
.method-grid {
    display:grid;
    grid-template-columns:minmax(220px, 1.3fr) repeat(7, minmax(86px, .7fr));
    gap:1px;
    background:var(--border);
    border-radius:10px;
    overflow:hidden;
}
.method-cell {
    background:#fff;
    padding:10px 12px;
    font-size:.82rem;
}
.method-head {
    background:var(--navy);
    color:rgba(255,255,255,.78);
    font-size:.7rem;
    text-transform:uppercase;
    letter-spacing:.5px;
    font-weight:700;
}
.ttd-box img {
    max-height:70px;
    border:1px solid var(--border);
    border-radius:8px;
    padding:4px;
    background:#fff;
}
.divider-line { height:1px; background:var(--border); margin:20px 0; }
.row-num { font-family:'DM Mono', monospace; color:var(--text-muted); font-size:.75rem; }
.empty-state { text-align:center; padding:48px 24px; color:var(--text-muted); }
.empty-state i { font-size:2.5rem; margin-bottom:12px; opacity:.4; display:block; }
.page-footer {
    padding:20px 32px;
    border-top:1px solid var(--border);
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-size:.78rem;
    color:var(--text-muted);
    margin-top:auto;
}
.page-footer strong { color:var(--teal-dark); }
@media (max-width:1100px) {
    .method-grid { grid-template-columns:1fr; }
    .method-head { display:none; }
}
@media (max-width:900px) {
    



    .main { margin-left:0; }
    .form-grid { grid-template-columns:1fr; }
    .rekom-row, .section-head { flex-direction:column; align-items:flex-start; }
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
        <a href="validasiapl2.php" class="nav-item"><i class="fas fa-pen-to-square"></i> Validasi APL2</a>
        <a href="asesormain.php" class="nav-item"><i class="fas fa-folder-open"></i> FR.IA.08 Portofolio</a>
        <a href="observasi.php" class="nav-item"><i class="fas fa-eye"></i> FR.IA.01 Observasi</a>

        <div class="nav-label">Rekaman & Laporan</div>
        <a href="mak2.php" class="nav-item active"><i class="fas fa-file-lines"></i> FR.AK.02 Rekaman Asesmen</a>
        <a href="mak5.php" class="nav-item"><i class="fas fa-chart-bar"></i> FR.AK.05 Laporan Asesmen</a>
        <a href="mak6baru.php" class="nav-item"><i class="fas fa-map"></i> FR.AK.06 Meninjau Proses</a>
        <a href="rekapasesi.php" class="nav-item"><i class="fas fa-calendar-check"></i> Rekap Hasil Tes</a>

        <div class="nav-label">Perencanaan</div>
        <a href="mapaasesor.php" class="nav-item"><i class="fas fa-sitemap"></i> FR.MAPA.01 Merencanakan</a>
        <a href="pihakketiga.php" class="nav-item"><i class="fas fa-users"></i> FR.IA.10 Pihak Ketiga</a>
        <a href="ceklistintrumen.php" class="nav-item"><i class="fas fa-clipboard-list"></i> FR.IA.11 Ceklist Instrumen</a>

        <div class="nav-label">Akun</div>
        <a href="tandatanganass.php" class="nav-item"><i class="fas fa-signature"></i> Tanda Tangan</a>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)"><i class="fas fa-right-from-bracket"></i> Logout</a>
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
            FR.AK.02 Rekaman Asesmen
            <span>Dokumentasi bukti, keputusan, dan rekomendasi asesmen</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div>
            <button class="icon-btn" type="button"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">

<?php
if ($op == "pilihtanggal"):
    $idskema = $_GET['idskema'] ?? '';
    $kelompok = $_GET['kelompok'] ?? '';
    $idasesor_get = $_GET['idasesor'] ?? $idasesor;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-calendar-days" style="color:var(--teal);margin-right:8px"></i>Pilih Tanggal Asesmen</h3>
                    <p>Pilih tanggal untuk membuka peserta pada kelompok ini</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <form method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta">
                <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($idskema); ?>">
                <input type="hidden" name="idasesor" value="<?php echo e($idasesor_get); ?>">

                <div class="form-grid">
                    <div class="form-label">Tanggal Asesmen</div>
                    <select id="tgl" name="tgl" class="form-input" required>
                        <?php
                        $tampiltgl = "SELECT tanggal, namaasesor FROM pemetaan WHERE kelompok='$kelompok' AND idskema='$idskema' AND idasesor='$idasesor_get' GROUP BY tanggal, namaasesor";
                        $exectgl = mysqli_query($conn, $tampiltgl);
                        if ($exectgl && mysqli_num_rows($exectgl) > 0) {
                            while ($rtgl = mysqli_fetch_array($exectgl)) {
                                echo "<option value='".e($rtgl['tanggal'])."'>".e($rtgl['tanggal'])."</option>";
                            }
                        } else {
                            echo "<option value=''>Tanggal tidak ditemukan</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lanjutkan</button>
                </div>
            </form>
        </div>

<?php
elseif ($op == "listpeserta"):
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $p_idasesor = $_POST['idasesor'] ?? '';
        $p_idskema  = $_POST['idskema'] ?? '';
        $p_kelompok = $_POST['kelompok'] ?? '';
        $p_tgl      = $_POST['tgl'] ?? '';
        $_SESSION['mak2_idasesor'] = $p_idasesor;
        $_SESSION['mak2_idskema']  = $p_idskema;
        $_SESSION['mak2_kelompok'] = $p_kelompok;
        $_SESSION['mak2_tgl']      = $p_tgl;
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpeserta&idasesor=" . urlencode($p_idasesor) . "&idskema=" . urlencode($p_idskema) . "&kelompok=" . urlencode($p_kelompok) . "&tgl=" . urlencode($p_tgl));
        exit;
    }

    // Baca dari GET dulu, fallback ke session, lalu fallback ke idasesor login
    $idasesor_post = $_GET['idasesor'] ?? ($_SESSION['mak2_idasesor'] ?? '');
    if (!is_numeric($idasesor_post) || empty($idasesor_post)) {
        $idasesor_post = $idasesor;
    }
    $idskema       = $_GET['idskema']  ?? ($_SESSION['mak2_idskema']  ?? '');
    $kelompok      = $_GET['kelompok'] ?? ($_SESSION['mak2_kelompok'] ?? '');
    $tgl           = $_GET['tgl']      ?? ($_SESSION['mak2_tgl']      ?? '');

    $sqlttdass = "SELECT * FROM lsp_usertbl WHERE id='$idasesor_post'";
    $sqlttdassa = mysqli_query($conn, $sqlttdass);
    $sqlttdassb = mysqli_fetch_array($sqlttdassa);
    $ttdass_file = $sqlttdassb['linkttd'] ?? '';

    $ssl = "SELECT * FROM pemetaan WHERE kelompok='$kelompok' AND idskema='$idskema' AND tanggal='$tgl' AND idasesor='$idasesor_post'";
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
                    <h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Peserta Rekaman Asesmen</h3>
                    <p>Tanggal: <strong><?php echo e($tgl); ?></strong> · <?php echo e($total); ?> peserta</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>ID Asesi</th>
                            <th>Nama Asesi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th style="width:260px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($exec0 && mysqli_num_rows($exec0) > 0): ?>
                        <?php $no = 1; while ($hasil0 = mysqli_fetch_array($exec0)): ?>
                            <?php
                            $idpeserta = $hasil0['idpeserta'];
                            $idp_temp = mysqli_real_escape_string($conn, $idpeserta);
                            $qp_temp = mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE id='$idp_temp' LIMIT 1");
                            $dp_temp = $qp_temp ? mysqli_fetch_array($qp_temp) : array();
                            $nama_asesi = $dp_temp['nama'] ?? ($hasil0['namapeserta'] ?? 'Asesi');

                            $cekvlmak4 = "SELECT idadsesi,idskema,waktu,svalidasi FROM mak2 WHERE idskema='$idskema' AND waktu='$tgl' AND idadsesi='$idpeserta' AND svalidasi='Y' GROUP BY idadsesi,idskema,waktu,svalidasi";
                            $cekvlmak4a = mysqli_query($conn, $cekvlmak4);
                            $b = $cekvlmak4a ? mysqli_num_rows($cekvlmak4a) : 0;
                            $ketvlmak2 = ($b > 0)
                                ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Sudah pernah divalidasi</span>'
                                : '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Belum divalidasi</span>';
                            ?>
                            <tr>
                                <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                                <td><span class="badge badge-navy"><?php echo e($idpeserta); ?></span></td>
                                <td style="font-weight:600"><?php echo e($nama_asesi); ?></td>
                                <td style="color:var(--text-sub)"><?php echo e($hasil0['tanggal']); ?></td>
                                <td><?php echo $ketvlmak2; ?></td>
                                <td style="text-align:center">
                                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=validasiunitdmak2&idasesor=<?php echo e($hasil0['idasesor']); ?>&k=<?php echo e(urlencode($hasil0['kelompok'])); ?>&tgl=<?php echo e(urlencode($hasil0['tanggal'])); ?>&kode=in&idasesi=<?php echo e($idpeserta); ?>&nmasesor=<?php echo e(urlencode($hasil0['namaasesor'])); ?>&idskema=<?php echo e($idskema); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-clipboard-check"></i> Validasi
                                    </a>
                                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=rekommak2&idasesor=<?php echo e($hasil0['idasesor']); ?>&tgl=<?php echo e(urlencode($hasil0['tanggal'])); ?>&idasesi=<?php echo e($idpeserta); ?>&idskema=<?php echo e($idskema); ?>&nmasesor=<?php echo e(urlencode($hasil0['namaasesor'])); ?>&ttdass=<?php echo e(urlencode($ttdass_file)); ?>&kelompok=<?php echo e(urlencode($hasil0['kelompok'])); ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-star"></i> Rekomendasi
                                    </a>
                                </td>
                            </tr>
                        <?php $no++; endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <p>Data peserta tidak ditemukan untuk tanggal <?php echo e($tgl); ?>.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php
elseif ($op == "validasiunitdmak2"):
    $idasesor_get = $_GET['idasesor'] ?? '';
    $ie = $_GET['kode'] ?? 'in';
    $idadsesi = $_GET['idasesi'] ?? '';
    $idsk = $_GET['idskema'] ?? '';
    $tgl = $_GET['tgl'] ?? '';
    $kelompok = $_GET['k'] ?? '';
    $nmasesor = $_GET['nmasesor'] ?? $namax;
    $emailuser = trim($uname);

    $sqlunitvapl2 = "SELECT unitsiswa.idadsesi,unitsiswa.idunit,unit.kodeunit,unit.namaunit,unit.idskema
                     FROM unitsiswa
                     INNER JOIN unit ON unitsiswa.idunit=unit.idunit
                     WHERE unitsiswa.idskema='$idsk' AND unitsiswa.idadsesi='$idadsesi'
                     ORDER BY unit.kodeunit";
    $execunitvapl2 = mysqli_query($conn, $sqlunitvapl2);
    $totalunit = $execunitvapl2 ? mysqli_num_rows($execunitvapl2) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-list-check" style="color:var(--teal);margin-right:8px"></i>Pilih Unit Rekaman Asesmen</h3>
                    <p>Pilih unit kompetensi yang akan divalidasi · <?php echo e($totalunit); ?> unit tersedia</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=validasiaplmak2">
                <div class="tbl-wrap">
                    <table class="unit-tbl">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th style="width:60px">Pilih</th>
                                <th style="width:160px">Kode Unit</th>
                                <th>Nama Unit</th>
                                <th style="width:240px">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        if ($execunitvapl2 && mysqli_num_rows($execunitvapl2) > 0):
                            while ($unit2vapl2 = mysqli_fetch_array($execunitvapl2)):
                                $dunitvapl2 = $unit2vapl2['idunit'];
                                $kodeunitvapl2 = $unit2vapl2['kodeunit'];
                                $namaunitvapl2 = $unit2vapl2['namaunit'];

                                $sqlcekapl2v = "SELECT idskema,idadsesi,idunit,idelemen,idsubelemen FROM mak2 WHERE idadsesi='$idadsesi' AND idskema='$idsk' AND idunit='$dunitvapl2'";
                                $execsqlunitapl2v = mysqli_query($conn, $sqlcekapl2v);
                                $bykunitapl2v = $execsqlunitapl2v ? mysqli_num_rows($execsqlunitapl2v) : 0;

                                $sqlelemenapl2v = "SELECT COUNT(idelemen) AS byk2apl2v FROM subelemen WHERE idunit='$dunitvapl2'";
                                $execelemenapl2v = mysqli_query($conn, $sqlelemenapl2v);
                                $execelemenapl2av = mysqli_fetch_array($execelemenapl2v);
                                $bykelemenapl2v = $execelemenapl2av['byk2apl2v'] ?? 0;

                                $ckjmlval = "SELECT COUNT(svalidasi) AS bykvalidasi FROM mak2 WHERE idadsesi='$idadsesi' AND idskema='$idsk' AND idunit='$dunitvapl2' AND svalidasi='Y'";
                                $ckjmlvala = mysqli_query($conn, $ckjmlval);
                                $ckjmlvalb = mysqli_fetch_array($ckjmlvala);
                                $ckjmlvald = $ckjmlvalb['bykvalidasi'] ?? 0;

                                $stadav = ($bykunitapl2v > 0 && $ckjmlvald == $bykelemenapl2v && $bykelemenapl2v > 0) ? 'disabled' : '';
                                $statusBadge = $stadav
                                    ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Selesai ('.$ckjmlvald.'/'.$bykelemenapl2v.')</span>'
                                    : '<span class="badge badge-orange"><i class="fas fa-clock"></i> Proses ('.$ckjmlvald.'/'.$bykelemenapl2v.')</span>';
                        ?>
                            <tr>
                                <td class="row-num"><?php echo e(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></td>
                                <td style="text-align:center">
                                    <input type="hidden" name="idskema<?php echo e($i); ?>" value="<?php echo e($idsk); ?>">
                                    <input type="hidden" name="idunit<?php echo e($i); ?>" value="<?php echo e($dunitvapl2); ?>">
                                    <input type="checkbox" name="kodeunit<?php echo e($i); ?>" value="<?php echo e($kodeunitvapl2); ?>" <?php echo $stadav; ?> style="width:16px;height:16px;accent-color:var(--teal);cursor:pointer">
                                </td>
                                <td><span class="badge badge-teal"><?php echo e($kodeunitvapl2); ?></span></td>
                                <td style="font-weight:500"><?php echo e($namaunitvapl2); ?></td>
                                <td><?php echo $statusBadge; ?></td>
                            </tr>
                        <?php $i++; endwhile; else: ?>
                            <tr><td colspan="5"><div class="empty-state"><i class="fas fa-folder-open"></i><p>Unit kompetensi belum tersedia.</p></div></td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="nz" value="<?php echo e($i); ?>">
                <input type="hidden" name="emailuserapl2" value="<?php echo e($emailuser); ?>">
                <input type="hidden" name="idasesorz" value="<?php echo e($idasesor_get); ?>">
                <input type="hidden" name="kodez" value="<?php echo e($ie); ?>">
                <input type="hidden" name="idasesiz" value="<?php echo e($idadsesi); ?>">
                <input type="hidden" name="idskemaz" value="<?php echo e($idsk); ?>">
                <input type="hidden" name="tglz" value="<?php echo e($tgl); ?>">
                <input type="hidden" name="kz" value="<?php echo e($kelompok); ?>">
                <input type="hidden" name="nmasser" value="<?php echo e($nmasesor); ?>">

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lanjutkan Validasi</button>
                </div>
            </form>
        </div>

<?php
elseif ($op == "validasiaplmak2"):
    $idasesorm2 = $_POST['idasesorz'] ?? '';
    $idadsesim2 = $_POST['idasesiz'] ?? '';
    $idskm2 = $_POST['idskemaz'] ?? '';
    $tglm2 = $_POST['tglz'] ?? '';
    $kelompokm2 = $_POST['kz'] ?? '';
    $nmasesorm2 = $_POST['nmasser'] ?? $namax;

    $sql = "SELECT * FROM lsp_usertbl WHERE id='$idadsesim2' OR id='$idadsesim2' LIMIT 1";
    $shasil = mysqli_query($conn, $sql);
    $sdata = mysqli_fetch_array($shasil);
    $namap = $sdata['nama'] ?? '-';
    $idp = $sdata['id'] ?? ($sdata['id'] ?? $idadsesim2);
    $emailp = $sdata['email'] ?? '';

    $ckskemam2 = "SELECT * FROM skema WHERE idskema='$idskm2'";
    $ckskemam2a = mysqli_query($conn, $ckskemam2);
    $ckskemam2b = mysqli_fetch_array($ckskemam2a);
    $namaskema = $ckskemam2b['namaskema'] ?? '';

    $nn = (int)($_POST['nz'] ?? 0);
    $i = 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-file-lines" style="color:var(--teal);margin-right:8px"></i>Rekaman Asesmen</h3>
                    <p>Peserta: <strong><?php echo e($namap); ?></strong> · Skema: <strong><?php echo e($namaskema); ?></strong> · Tanggal: <?php echo e($tglm2); ?></p>
                </div>
                <div style="display:flex;gap:10px">
                    <button onclick="window.print()" class="btn btn-print btn-sm no-print" type="button"><i class="fas fa-print"></i> Cetak</button>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm no-print"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>

            <div class="alert-box alert-info">
                <i class="fas fa-circle-info"></i>
                Asesor menilai pencapaian, keputusan kompetensi, dan jenis bukti pendukung untuk setiap KUK.
            </div>

            <form method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=simpanvalidasimak2">
                <input type="hidden" name="tgl" value="<?php echo e($tglm2); ?>">
                <input type="hidden" name="kelompok" value="<?php echo e($kelompokm2); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($idskm2); ?>">
                <input type="hidden" name="idadsesi" value="<?php echo e($idp); ?>">
                <input type="hidden" name="email" value="<?php echo e($emailp); ?>">
                <input type="hidden" name="idasesor" value="<?php echo e($idasesorm2); ?>">

                <div class="tbl-wrap">
                    <table class="mak-tbl">
                        <tbody>
                            <tr class="unit-head"><td colspan="10">Nama Skema: <?php echo e($namaskema); ?> · Nama Peserta: <?php echo e($namap); ?> · Nama Asesor: <?php echo e($nmasesorm2); ?></td></tr>
                            <tr class="guide-row"><td colspan="10">Asesor mengorganisasikan pelaksanaan asesmen, mengumpulkan bukti, membuat keputusan K/BK/AL, dan mendokumentasikan jenis bukti yang dipilih.</td></tr>
                            <?php
                            for ($cma = 0; $cma <= $nn - 1; $cma++):
                                if (!isset($_POST['kodeunit'.$cma])) continue;
                                $idunitxyz = $_POST['idunit'.$cma] ?? '';
                                if (strlen($_POST['kodeunit'.$cma]) <= 0) continue;

                                $cek = "SELECT * FROM skemasiswa WHERE emailsiswa='$emailp' AND idskema='$idskm2' LIMIT 1";
                                $ada = mysqli_query($conn, $cek);
                                if (!$ada || mysqli_num_rows($ada) < 1) continue;
                                $data = mysqli_fetch_array($ada);
                                $skema = $data['idskema'];
                                $statusapl1 = $data['statusapl1'] ?? '';
                                if ($statusapl1 != 'Y') continue;

                                $sqlunit = "SELECT unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit
                                            FROM unitsiswa
                                            INNER JOIN unit ON unitsiswa.idunit=unit.idunit
                                            WHERE unitsiswa.idskema='$skema' AND unitsiswa.idadsesi='$idp' AND unitsiswa.idunit='$idunitxyz'
                                            ORDER BY unitsiswa.idunit";
                                $eunit = mysqli_query($conn, $sqlunit);
                                while ($dunit = mysqli_fetch_array($eunit)):
                            ?>
                            <tr class="unit-head"><td colspan="10"><?php echo e($dunit['kodeunit']); ?> - <?php echo e($dunit['namaunit']); ?></td></tr>
                            <?php
                                    $sqelemen = "SELECT * FROM elemen WHERE idunit='".$dunit['idunit']."' AND idskema='$skema'";
                                    $eelemen = mysqli_query($conn, $sqelemen);
                                    $y = 0;
                                    while ($delemen = mysqli_fetch_array($eelemen)):
                                        $y++;
                            ?>
                            <tr class="elemen-head"><td colspan="10"><?php echo e($y); ?>. <?php echo e($delemen['namaelemen']); ?></td></tr>
                            <tr>
                                <th colspan="3">Kriteria Unjuk Kerja</th>
                                <th style="width:60px;text-align:center">Y</th>
                                <th style="width:60px;text-align:center">T</th>
                                <th style="width:70px;text-align:center">K</th>
                                <th style="width:70px;text-align:center">BK</th>
                                <th>Bukti Langsung</th>
                                <th>Bukti Tidak Langsung</th>
                                <th>Bukti Tambahan</th>
                            </tr>
                            <?php
                                        $sqsubelemen = "SELECT * FROM subelemen WHERE idelemen='".$delemen['idelemen']."' AND idunit='".$dunit['idunit']."' AND idskema='$skema'";
                                        $esubelemen = mysqli_query($conn, $sqsubelemen);
                                        $x = 0;
                                        while ($dsubelemen = mysqli_fetch_array($esubelemen)):
                                            $x++;
                                            $idsube = $dsubelemen['idsubelemen'];
                                            $kuk = $dsubelemen['pertanyaan'];
                                            $cekmak22 = "SELECT * FROM mak2 WHERE idelemen='".$delemen['idelemen']."' AND idunit='".$dunit['idunit']."' AND idskema='$skema' AND idsubelemen='$idsube' AND idadsesi='$idp' AND waktu='$tglm2'";
                                            $cekmak22a = mysqli_query($conn, $cekmak22);
                                            $cekmak22c = ($cekmak22a && mysqli_num_rows($cekmak22a) > 0) ? mysqli_fetch_array($cekmak22a) : null;

                                            $tk = $cekmak22c['tk'] ?? 'K';
                                            $yt = $cekmak22c['YT'] ?? ($cekmak22c['yt'] ?? 'Y');
                                            $sbukti = $cekmak22c['sbukti'] ?? '';
                                            $pecahdbs = explode(",", $sbukti);
                                            $kdbs0 = checked_if($pecahdbs[0] ?? '', 'v');
                                            $kdbs1 = checked_if($pecahdbs[1] ?? '', 'a');
                                            $kdbs2 = checked_if($pecahdbs[2] ?? '', 't');
                            ?>
                            <tr>
                                <td class="row-num"><?php echo e($y . "." . $x); ?></td>
                                <td colspan="2">
                                    <?php echo e($kuk); ?>
                                    <input type="hidden" name="idunit<?php echo e($i); ?>" value="<?php echo e($dunit['idunit']); ?>">
                                    <input type="hidden" name="idelemen<?php echo e($i); ?>" value="<?php echo e($delemen['idelemen']); ?>">
                                    <input type="hidden" name="idsube<?php echo e($i); ?>" value="<?php echo e($idsube); ?>">
                                </td>
                                <td style="text-align:center"><input type="radio" name="py<?php echo e($i); ?>" value="Y" <?php echo checked_if($yt, 'Y'); ?>></td>
                                <td style="text-align:center"><input type="radio" name="py<?php echo e($i); ?>" value="T" <?php echo checked_if($yt, 'T'); ?>></td>
                                <td style="text-align:center"><input type="radio" name="bk<?php echo e($i); ?>" value="K" <?php echo checked_if(strtoupper($tk), 'K'); ?>></td>
                                <td style="text-align:center"><input type="radio" name="bk<?php echo e($i); ?>" value="BK" <?php echo checked_if(strtoupper($tk), 'BK'); ?>></td>
                                <td style="text-align:center"><input type="checkbox" name="validasia<?php echo e($i); ?>" value="v" <?php echo $kdbs0; ?>></td>
                                <td style="text-align:center"><input type="checkbox" name="validasib<?php echo e($i); ?>" value="a" <?php echo $kdbs1; ?>></td>
                                <td style="text-align:center"><input type="checkbox" name="validasic<?php echo e($i); ?>" value="t" <?php echo $kdbs2; ?>></td>
                            </tr>
                            <?php $i++; endwhile; endwhile; endwhile; endfor; ?>
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="n" value="<?php echo e($i); ?>">
                <div class="form-actions">
                    <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Rekaman</button>
                </div>
            </form>
        </div>

<?php
elseif ($op == "simpanvalidasimak2"):
    $idasesor = $_POST['idasesor'] ?? '';
    $idskema = $_POST['idskema'] ?? '';
    $idasesi = $_POST['idadsesi'] ?? '';
    $email = $_POST['email'] ?? '';
    $n = (int)($_POST['n'] ?? 0);
    $tgl = $_POST['tgl'] ?? '';
    $kelompok = $_POST['kelompok'] ?? '';
    $sukses = 0;
    $gagal = 0;

    for ($i = 0; $i <= $n - 1; $i++) {
        if (isset($_POST['idunit'.$i])) {
            $idunit = $_POST['idunit'.$i] ?? '';
            $idelemen = $_POST['idelemen'.$i] ?? '';
            $idsubelemen = $_POST['idsube'.$i] ?? '';
            $validasi0 = $_POST['validasia'.$i] ?? '';
            $validasi1 = $_POST['validasib'.$i] ?? '';
            $validasi2 = $_POST['validasic'.$i] ?? '';
            $allvalidasi = $validasi0 . "," . $validasi1 . "," . $validasi2;
            $py = $_POST['py'.$i] ?? 'Y';
            $bk = $_POST['bk'.$i] ?? 'K';

            $cekmak4ada = "SELECT * FROM mak2 WHERE idelemen='$idelemen' AND idunit='$idunit' AND idskema='$idskema' AND idsubelemen='$idsubelemen' AND idadsesi='$idasesi' AND waktu='$tgl'";
            $cekmak4adaa = mysqli_query($conn, $cekmak4ada);
            $cekmak4adab = $cekmak4adaa ? mysqli_num_rows($cekmak4adaa) : 0;

            if ($cekmak4adab > 0) {
                $sqlupdate = "UPDATE mak2 SET tk='$bk', YT='$py', sbukti='$allvalidasi', svalidasi='Y' WHERE idskema='$idskema' AND idadsesi='$idasesi' AND idelemen='$idelemen' AND idsubelemen='$idsubelemen' AND waktu='$tgl'";
            } else {
                $sqlupdate = "INSERT INTO mak2 (idskema, idunit, idelemen, idsubelemen, idadsesi, idasesor, waktu, tk, sbukti, svalidasi, YT) VALUES ('$idskema', '$idunit', '$idelemen', '$idsubelemen', '$idasesi', '$idasesor', '$tgl', '$bk', '$allvalidasi', 'Y', '$py')";
            }

            if (mysqli_query($conn, $sqlupdate)) {
                $sukses++;
            } else {
                $gagal++;
            }
        }
    }
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-circle-check" style="color:var(--teal);margin-right:8px"></i>Hasil Simpan Rekaman Asesmen</h3>
                    <p>Data rekaman asesmen telah diproses.</p>
                </div>
            </div>

            <div class="alert-box alert-success">
                <i class="fas fa-circle-check"></i>
                <div>
                    <strong>Proses selesai.</strong>
                    <p style="margin-top:2px;font-size:.8rem">Sukses: <?php echo e($sukses); ?> · Gagal: <?php echo e($gagal); ?></p>
                </div>
            </div>

            <form method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta">
                <input type="hidden" name="tgl" value="<?php echo e($tgl); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($idskema); ?>">
                <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">
                <input type="hidden" name="idasesor" value="<?php echo e($idasesor); ?>">
                <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Peserta</button>
            </form>
        </div>

<?php
elseif ($op == "rekommak2"):
    $namaasesorttd2 = $_GET['nmasesor'] ?? $namax;
    $idasesorttd2 = $_GET['idasesor'] ?? '';
    $idadsesittd2 = $_GET['idasesi'] ?? '';
    $tglttd2 = $_GET['tgl'] ?? '';
    $idskttd2 = $_GET['idskema'] ?? '';
    $ttdass = $_GET['ttdass'] ?? '';
    $kelompok = $_GET['kelompok'] ?? ($_SESSION['mak2_kelompok'] ?? '');

    $cekduluvlapl2 = "SELECT idadsesi,idskema,waktu,svalidasi FROM apl2 WHERE idadsesi='$idadsesittd2' AND waktu='$tglttd2' AND svalidasi='T' AND idskema='$idskttd2' GROUP BY idadsesi,idskema,waktu,svalidasi";
    $cekduluvlapl2a = mysqli_query($conn, $cekduluvlapl2);
    $cekduluvlapl2b = $cekduluvlapl2a ? mysqli_num_rows($cekduluvlapl2a) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-star" style="color:var(--teal);margin-right:8px"></i>Rekomendasi FR.AK.02</h3>
                    <p>Umpan balik pencapaian unjuk kerja, bukti, dan rekomendasi akhir</p>
                </div>
                <div style="display:flex;gap:10px">
                    <button onclick="window.print()" class="btn btn-print btn-sm no-print" type="button"><i class="fas fa-print"></i> Cetak</button>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm no-print"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>

            <?php if ($cekduluvlapl2b > 0): ?>
                <div class="alert-box alert-warning">
                    <i class="fas fa-triangle-exclamation"></i>
                    <strong>Validasi belum selesai.</strong>
                </div>
            <?php else: ?>
            <?php
                $unitmak2 = "SELECT unitsiswa.idunit,unitsiswa.idadsesi,unit.namaunit
                             FROM unitsiswa
                             INNER JOIN unit ON unitsiswa.idunit=unit.idunit
                             WHERE unitsiswa.idadsesi='$idadsesittd2' AND unitsiswa.idskema='$idskttd2'";
                $unitmak2a = mysqli_query($conn, $unitmak2);

                $cekrekommak22 = "SELECT * FROM mak2rekom WHERE namarekom='mak2' AND idskema='$idskttd2' AND idasesi='$idadsesittd2' AND tanggal='$tglttd2'";
                $cekrekommak22a = mysqli_query($conn, $cekrekommak22);
                $cekrekommak22c = ($cekrekommak22a && mysqli_num_rows($cekrekommak22a) > 0) ? mysqli_fetch_array($cekrekommak22a) : null;
                $pencapaian = $cekrekommak22c['pencapaian'] ?? 'Y';
                $senjang = $cekrekommak22c['senjang'] ?? 'T';
                $saran = $cekrekommak22c['saran'] ?? 'Y';
                $ccatsenjang = $cekrekommak22c['catsenjang'] ?? '';
                $ccatsaran = $cekrekommak22c['catsaran'] ?? '';

                $cekdata0mak2 = "SELECT * FROM rekomendasi WHERE namarekom='mak2' AND idskema='$idskttd2' AND idasesi='$idadsesittd2' AND tanggal='$tglttd2'";
                $ada02 = mysqli_query($conn, $cekdata0mak2);
                $adax2 = ($ada02 && mysqli_num_rows($ada02) > 0) ? mysqli_fetch_array($ada02) : null;
                $lrek2 = $adax2['rekom'] ?? 'L';
                $cat2 = $adax2['catatan'] ?? '';

                $sqlttd2 = "SELECT * FROM lsp_usertbl WHERE id='$idadsesittd2' OR id='$idadsesittd2' LIMIT 1";
                $sqlttda2 = mysqli_query($conn, $sqlttd2);
                $sqlttdb2 = mysqli_fetch_array($sqlttda2);
                $linkttda2 = !empty($sqlttdb2['linkttd']) ? "../imgttd/" . $sqlttdb2['linkttd'] : "";
                $namapttd2 = $sqlttdb2['nama'] ?? '-';
                $emailttd2 = $sqlttdb2['email'] ?? '';
                $linkttdass = $ttdass !== '' ? "../imgttd/" . $ttdass : "";
            ?>

            <form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=srekommak2">
                <div class="rekom-section">
                    <h4>Metode dan Jenis Bukti Per Unit</h4>
                    <div class="method-grid">
                        <div class="method-cell method-head">Unit Kompetensi</div>
                        <div class="method-cell method-head">Observasi</div>
                        <div class="method-cell method-head">Portofolio</div>
                        <div class="method-cell method-head">Pihak Ketiga / Wawancara</div>
                        <div class="method-cell method-head">Lisan</div>
                        <div class="method-cell method-head">Tertulis</div>
                        <div class="method-cell method-head">Proyek</div>
                        <div class="method-cell method-head">Lainnya</div>
                        <?php
                        $eidx = 0;
                        while ($unitmak2b = mysqli_fetch_array($unitmak2a)):
                            $cekduluumak23 = "SELECT * FROM unitmak2 WHERE idskemamak2='$idskttd2' AND idunitmak2='".$unitmak2b['idunit']."' AND idasesormak2='$idasesorttd2' AND idasesimak2='$idadsesittd2'";
                            $excekduluumak23 = mysqli_query($conn, $cekduluumak23);
                            $jawaban = '';
                            if ($excekduluumak23 && mysqli_num_rows($excekduluumak23) > 0) {
                                $arrcekduluumak23 = mysqli_fetch_array($excekduluumak23);
                                $jawaban = $arrcekduluumak23['jawabanmak2'] ?? '';
                            }
                            $pecah = explode(",", $jawaban);
                        ?>
                            <div class="method-cell">
                                <strong><?php echo e($unitmak2b['namaunit']); ?></strong>
                                <input type="hidden" name="idunitmak2b<?php echo e($eidx); ?>" value="<?php echo e($unitmak2b['idunit']); ?>">
                            </div>
                            <div class="method-cell"><label class="check-item"><input type="checkbox" name="obs<?php echo e($eidx); ?>" value="obs" <?php echo checked_if($pecah[0] ?? '', 'obs'); ?>> Ya</label></div>
                            <div class="method-cell"><label class="check-item"><input type="checkbox" name="por<?php echo e($eidx); ?>" value="por" <?php echo checked_if($pecah[1] ?? '', 'por'); ?>> Ya</label></div>
                            <div class="method-cell"><label class="check-item"><input type="checkbox" name="ww<?php echo e($eidx); ?>" value="ww" <?php echo checked_if($pecah[2] ?? '', 'ww'); ?>> Ya</label></div>
                            <div class="method-cell"><label class="check-item"><input type="checkbox" name="pl<?php echo e($eidx); ?>" value="pl" <?php echo checked_if($pecah[3] ?? '', 'pl'); ?>> Ya</label></div>
                            <div class="method-cell"><label class="check-item"><input type="checkbox" name="pt<?php echo e($eidx); ?>" value="pt" <?php echo checked_if($pecah[4] ?? '', 'pt'); ?>> Ya</label></div>
                            <div class="method-cell"><label class="check-item"><input type="checkbox" name="pk<?php echo e($eidx); ?>" value="pk" <?php echo checked_if($pecah[5] ?? '', 'pk'); ?>> Ya</label></div>
                            <div class="method-cell"><label class="check-item"><input type="checkbox" name="ln<?php echo e($eidx); ?>" value="ln" <?php echo checked_if($pecah[6] ?? '', 'ln'); ?>> Ya</label></div>
                        <?php $eidx++; endwhile; ?>
                    </div>
                </div>

                <input type="hidden" name="banyake" value="<?php echo e($eidx); ?>">

                <div class="rekom-card">
                    <div class="rekom-row">
                        <div class="rekom-section">
                            <h4>Umpan Balik Pencapaian</h4>
                            <div class="radio-group">
                                <label class="radio-item"><input type="radio" name="tercapai" value="Y" <?php echo checked_if($pencapaian, 'Y'); ?>> Seluruh KUK tercapai</label>
                                <label class="radio-item"><input type="radio" name="tercapai" value="T" <?php echo checked_if($pencapaian, 'T'); ?>> Ada kesenjangan</label>
                            </div>
                        </div>
                        <div class="rekom-section">
                            <h4>Identifikasi Kesenjangan</h4>
                            <div class="radio-group">
                                <label class="radio-item"><input type="radio" name="senjang" value="T" <?php echo checked_if($senjang, 'T'); ?>> Tidak ada</label>
                                <label class="radio-item"><input type="radio" name="senjang" value="Y" <?php echo checked_if($senjang, 'Y'); ?>> Ditemukan</label>
                            </div>
                            <textarea name="cttsenjang" class="form-input" rows="3" placeholder="Tuliskan kesenjangan jika ada..."><?php echo e($ccatsenjang); ?></textarea>
                        </div>
                    </div>

                    <div class="rekom-row">
                        <div class="rekom-section">
                            <h4>Saran Tindak Lanjut</h4>
                            <div class="radio-group">
                                <label class="radio-item"><input type="radio" name="ulang" value="Y" <?php echo checked_if($saran, 'Y'); ?>> Memelihara kompetensi</label>
                                <label class="radio-item"><input type="radio" name="ulang" value="T" <?php echo checked_if($saran, 'T'); ?>> Asesmen ulang</label>
                            </div>
                            <textarea name="cttulang" class="form-input" rows="3" placeholder="Unit yang perlu asesmen ulang..."><?php echo e($ccatsaran); ?></textarea>
                        </div>
                        <div class="rekom-section">
                            <h4>Rekomendasi Akhir</h4>
                            <div class="radio-group">
                                <label class="radio-item"><input type="radio" name="lrekkp2" value="L" <?php echo checked_if($lrek2, 'L'); ?>> Kompeten</label>
                                <label class="radio-item"><input type="radio" name="lrekkp2" value="T" <?php echo checked_if($lrek2, 'T'); ?>> Belum Kompeten</label>
                            </div>
                            <textarea name="cttmak22" class="form-input" rows="3" placeholder="Catatan rekomendasi..."><?php echo e($cat2); ?></textarea>
                        </div>
                    </div>

                    <div class="divider-line"></div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                        <div>
                            <p style="font-size:.75rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesi</p>
                            <p style="font-weight:600;margin-bottom:6px"><?php echo e($namapttd2); ?></p>
                            <?php if ($linkttda2 != ""): ?><div class="ttd-box"><img src="<?php echo e($linkttda2); ?>" alt="TTD Asesi"></div><?php else: ?><span class="badge badge-gray">TTD belum tersedia</span><?php endif; ?>
                        </div>
                        <div>
                            <p style="font-size:.75rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesor</p>
                            <p style="font-weight:600;margin-bottom:6px"><?php echo e($namaasesorttd2); ?></p>
                            <?php if ($linkttdass != ""): ?><div class="ttd-box"><img src="<?php echo e($linkttdass); ?>" alt="TTD Asesor"></div><?php else: ?><span class="badge badge-gray">TTD belum tersedia</span><?php endif; ?>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="idskttd2" value="<?php echo e($idskttd2); ?>">
                <input type="hidden" name="idadsesittd2" value="<?php echo e($idadsesittd2); ?>">
                <input type="hidden" name="tglttd2" value="<?php echo e($tglttd2); ?>">
                <input type="hidden" name="idasesorttd2" value="<?php echo e($idasesorttd2); ?>">
                <input type="hidden" name="emailttd2" value="<?php echo e($emailttd2); ?>">
                <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Rekomendasi</button>
                </div>
            </form>
            <?php endif; ?>
        </div>

<?php
elseif ($op == "srekommak2"):
    $idskemarekapl22 = $_POST['idskttd2'] ?? '';
    $idasesirekapl22 = $_POST['idadsesittd2'] ?? '';
    $tglrekapl22 = $_POST['tglttd2'] ?? '';
    $idasesorrekapl22 = $_POST['idasesorttd2'] ?? '';
    $lrekapl22 = $_POST['lrekkp2'] ?? '';
    $catatanrekapl22 = $_POST['cttmak22'] ?? '';
    $emailad2 = $_POST['emailttd2'] ?? '';
    $tercapai = $_POST['tercapai'] ?? '';
    $senjang = $_POST['senjang'] ?? '';
    $ulang = $_POST['ulang'] ?? '';
    $cttsenjang = $_POST['cttsenjang'] ?? '';
    $cttulang = $_POST['cttulang'] ?? '';
    $kelompok = $_POST['kelompok'] ?? ($_SESSION['mak2_kelompok'] ?? '');

    $banyakumak2 = (int)($_POST['banyake'] ?? 0);
    for ($i = 0; $i <= $banyakumak2 - 1; $i++) {
        $idunitrekapl22 = $_POST['idunitmak2b'.$i] ?? '';
        $jawabanrekapl22 = ($_POST['obs'.$i] ?? '') . "," . ($_POST['por'.$i] ?? '') . "," . ($_POST['ww'.$i] ?? '') . "," . ($_POST['pl'.$i] ?? '') . "," . ($_POST['pt'.$i] ?? '') . "," . ($_POST['pk'.$i] ?? '') . "," . ($_POST['ln'.$i] ?? '');

        $cekduluumak2 = "SELECT * FROM unitmak2 WHERE idskemamak2='$idskemarekapl22' AND idunitmak2='$idunitrekapl22' AND idasesormak2='$idasesorrekapl22' AND idasesimak2='$idasesirekapl22'";
        $excekduluumak2 = mysqli_query($conn, $cekduluumak2);
        if ($excekduluumak2 && mysqli_num_rows($excekduluumak2) > 0) {
            $upunitmak2 = "UPDATE unitmak2 SET jawabanmak2='$jawabanrekapl22', tgl='$tglrekapl22' WHERE idskemamak2='$idskemarekapl22' AND idunitmak2='$idunitrekapl22' AND idasesormak2='$idasesorrekapl22' AND idasesimak2='$idasesirekapl22'";
            mysqli_query($conn, $upunitmak2);
        } else {
            $insunitmak2 = "INSERT INTO unitmak2 (idskemamak2, idunitmak2, idasesormak2, idasesimak2, jawabanmak2, tgl) VALUES ('$idskemarekapl22', '$idunitrekapl22', '$idasesorrekapl22', '$idasesirekapl22', '$jawabanrekapl22', '$tglrekapl22')";
            mysqli_query($conn, $insunitmak2);
        }
    }

    $cekdata2 = "SELECT * FROM rekomendasi WHERE namarekom='mak2' AND idskema='$idskemarekapl22' AND idasesi='$idasesirekapl22' AND tanggal='$tglrekapl22'";
    $ada2 = mysqli_query($conn, $cekdata2);
    if ($ada2 && mysqli_num_rows($ada2) > 0) {
        $ssqlrekapl22 = "UPDATE rekomendasi SET rekom='$lrekapl22', catatan='$catatanrekapl22' WHERE namarekom='mak2' AND idskema='$idskemarekapl22' AND idasesi='$idasesirekapl22' AND tanggal='$tglrekapl22'";
    } else {
        $ssqlrekapl22 = "INSERT INTO rekomendasi (namarekom, idskema, idasesi, rekom, catatan, tanggal) VALUES ('mak2', '$idskemarekapl22', '$idasesirekapl22', '$lrekapl22', '$catatanrekapl22', '$tglrekapl22')";
    }

    $execrekapl2 = mysqli_query($conn, $ssqlrekapl22);
    if ($execrekapl2) {
        $cekrekommak2 = "SELECT * FROM mak2rekom WHERE namarekom='mak2' AND idskema='$idskemarekapl22' AND idasesi='$idasesirekapl22' AND tanggal='$tglrekapl22'";
        $cekrekommak2a = mysqli_query($conn, $cekrekommak2);
        if ($cekrekommak2a && mysqli_num_rows($cekrekommak2a) > 0) {
            $uprekommak2 = "UPDATE mak2rekom SET pencapaian='$tercapai', senjang='$senjang', saran='$ulang', catsenjang='$cttsenjang', catsaran='$cttulang' WHERE namarekom='mak2' AND idskema='$idskemarekapl22' AND idasesi='$idasesirekapl22' AND tanggal='$tglrekapl22'";
            mysqli_query($conn, $uprekommak2);
        } else {
            $inrekommak2 = "INSERT INTO mak2rekom (namarekom, idskema, idasesor, idasesi, pencapaian, senjang, saran, catsenjang, catsaran, tanggal) VALUES ('mak2', '$idskemarekapl22', '$idasesorrekapl22', '$idasesirekapl22', '$tercapai', '$senjang', '$ulang', '$cttsenjang', '$cttulang', '$tglrekapl22')";
            mysqli_query($conn, $inrekommak2);
        }

        $_SESSION['mak2_idasesor'] = $idasesorrekapl22;
        $_SESSION['mak2_idskema'] = $idskemarekapl22;
        $_SESSION['mak2_kelompok'] = $kelompok;
        $_SESSION['mak2_tgl'] = $tglrekapl22;
        $_SESSION['flash_success'] = 'Rekomendasi FR.AK.02 berhasil disimpan.';
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpeserta&idasesor=" . urlencode($idasesorrekapl22) . "&idskema=" . urlencode($idskemarekapl22) . "&kelompok=" . urlencode($kelompok) . "&tgl=" . urlencode($tglrekapl22));
        exit;
    }
?>
        <div class="card">
            <div class="alert-box alert-error">
                <i class="fas fa-circle-xmark"></i>
                <strong>Penyimpanan gagal.</strong>
            </div>
            <button onclick="history.back()" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</button>
        </div>

<?php
else:
    $queryvmain = "SELECT kelompok, idskema, idasesor FROM pemetaan WHERE idasesor='$idasesor' GROUP BY kelompok, idskema, idasesor";
    $hasilvmain = mysqli_query($conn, $queryvmain);
    $total_jadwal = $hasilvmain ? mysqli_num_rows($hasilvmain) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-calendar-check" style="color:var(--teal);margin-right:8px"></i>Daftar Jadwal Rekaman Asesmen</h3>
                    <p>Pilih jadwal untuk memulai FR.AK.02 Rekaman Asesmen</p>
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
                            <th>Skema</th>
                            <th>Nama Skema</th>
                            <th style="width:190px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    while ($datavvmain = mysqli_fetch_array($hasilvmain)):
                        $id_skema_cari = $datavvmain['idskema'];
                        $ssql = "SELECT * FROM skema WHERE idskema='$id_skema_cari'";
                        $execssql = mysqli_query($conn, $ssql);
                        $baris = mysqli_fetch_array($execssql);
                        $namaskema = $baris['namaskema'] ?? "Nama Skema Tidak Ditemukan";
                    ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td><span class="badge badge-navy"><?php echo e($datavvmain['kelompok']); ?></span></td>
                            <td><span class="badge badge-teal"><?php echo e($datavvmain['idskema']); ?></span></td>
                            <td style="font-weight:600"><?php echo e($namaskema); ?></td>
                            <td style="text-align:center">
                                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=pilihtanggal&idskema=<?php echo e($datavvmain['idskema']); ?>&kelompok=<?php echo e(urlencode($datavvmain['kelompok'])); ?>&idasesor=<?php echo e($datavvmain['idasesor']); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-users"></i> Tampilkan Peserta
                                </a>
                            </td>
                        </tr>
                    <?php $no++; endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert-box alert-warning">
                    <i class="fas fa-triangle-exclamation"></i>
                    <strong>Belum ada jadwal FR.AK.02 untuk asesor ini.</strong>
                </div>
            <?php endif; ?>
        </div>
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
