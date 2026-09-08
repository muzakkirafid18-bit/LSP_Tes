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

function row_value($row, $keys, $default = '') {
    foreach ($keys as $key) {
        if (isset($row[$key]) && $row[$key] !== '') {
            return $row[$key];
        }
    }
    return $default;
}

function esc_sql($conn, $value) {
    return mysqli_real_escape_string($conn, (string)$value);
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
$idasesor_user = $dUser['id'] ?? $idasesor;
$ttdfrm = $dUser['linkttd'] ?? '';
$op = $_REQUEST['op'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.IA.10 Pihak Ketiga - LSP SMKN 1 Cibinong</title>

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
.alert-warning { background:#FEFCE8; color:#A16207; border:1px solid #FDE68A; }
.alert-error { background:#FEF2F2; color:#B91C1C; border:1px solid #FCA5A5; }
.tbl-wrap { overflow-x:auto; }
.tbl, .unit-tbl, .form-table { width:100%; border-collapse:collapse; }
.tbl th, .unit-tbl th {
    text-align:left;
    padding:12px 16px;
    font-size:.72rem;
    font-weight:700;
    letter-spacing:.7px;
    text-transform:uppercase;
    color:rgba(255,255,255,.78);
    background:var(--navy);
}
.tbl th:first-child, .unit-tbl th:first-child { border-radius:8px 0 0 8px; }
.tbl th:last-child, .unit-tbl th:last-child { border-radius:0 8px 8px 0; }
.tbl td, .unit-tbl td {
    padding:13px 16px;
    font-size:.845rem;
    border-bottom:1px solid var(--off);
    vertical-align:middle;
}
.tbl tbody tr:hover td, .unit-tbl tbody tr:hover td { background:#F0F9F9; }
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
.badge-teal { background:var(--teal-light); color:var(--teal-dark); }
.badge-navy { background:#EFF6FF; color:#1D4ED8; }
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
.btn-primary { background:var(--teal); color:#fff; box-shadow:0 2px 8px rgba(59,191,191,.3); }
.btn-primary:hover { background:var(--teal-dark); color:#fff; }
.btn-secondary { background:#fff; color:var(--text-main); border-color:var(--border); }
.btn-secondary:hover { border-color:var(--teal); color:var(--teal-dark); background:var(--teal-light); }
.btn-success { background:#22C55E; color:#fff; border-color:#22C55E; }
.btn-success:hover { background:#16A34A; color:#fff; }
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
.form-actions {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top:8px;
    padding-top:16px;
    border-top:1px solid var(--border);
}
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

.doc-table {
    width:100%;
    border-collapse:collapse;
    margin-bottom:16px;
    font-family:Arial, sans-serif;
    color:#000;
}
.doc-table td, .doc-table th {
    border:1px solid #000;
    padding:8px 10px;
    vertical-align:top;
    font-size:13px;
    line-height:1.45;
}
.doc-muted { background:#f2f2f2; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
.doc-head { background:#0F2A3A; color:#fff; font-weight:bold; }
.doc-input, .doc-textarea {
    width:100%;
    border:1px solid #cbd5e1;
    border-radius:6px;
    padding:7px 9px;
    font:inherit;
}
.doc-textarea { min-height:74px; resize:vertical; }
.radio-cell { text-align:center; vertical-align:middle !important; }
.ttd-img {
    max-height:64px;
    border:1px solid var(--border);
    border-radius:8px;
    padding:4px;
    background:#fff;
}
@media (max-width:900px) {
    



    .main { margin-left:0; }
    .form-grid { grid-template-columns:1fr; }
    .section-head { flex-direction:column; align-items:flex-start; }
}
@media print {
    .sidebar, .topbar, .page-footer, .no-print, .form-actions { display:none !important; }
    .main { margin-left:0; }
    .content { padding:0; }
    .card { box-shadow:none; border:0; padding:0; }
    body { background:#fff; color:#000; }
    .doc-input, .doc-textarea { border:none !important; background:transparent !important; }
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
        <a href="mak2.php" class="nav-item"><i class="fas fa-file-lines"></i> FR.AK.02 Rekaman Asesmen</a>
        <a href="mak5.php" class="nav-item"><i class="fas fa-chart-bar"></i> FR.AK.05 Laporan Asesmen</a>
        <a href="mak6baru.php" class="nav-item"><i class="fas fa-map"></i> FR.AK.06 Meninjau Proses</a>
        <a href="rekapasesi.php" class="nav-item"><i class="fas fa-calendar-check"></i> Rekap Hasil Tes</a>

        <div class="nav-label">Perencanaan</div>
        <a href="mapaasesor.php" class="nav-item"><i class="fas fa-sitemap"></i> FR.MAPA.01 Merencanakan</a>
        <a href="pihakketiga.php" class="nav-item active"><i class="fas fa-users"></i> FR.IA.10 Pihak Ketiga</a>
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
            FR.IA.10 Pihak Ketiga
            <span>Klarifikasi pihak ketiga untuk bukti asesmen</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div>
            <button class="icon-btn" type="button"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">

<?php
if ($op == "pilihtanggalpk"):
    $idasesorpk = $_GET['idasesor'] ?? $idasesor;
    $idskemapk = $_GET['idskema'] ?? '';
    $kelompokpk = $_GET['kelompok'] ?? '';
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-calendar-days" style="color:var(--teal);margin-right:8px"></i>Pilih Tanggal</h3>
                    <p>Pilih tanggal asesmen untuk menampilkan peserta pihak ketiga</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <form method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpesertapk">
                <input type="hidden" name="kelompokpk" value="<?php echo e($kelompokpk); ?>">
                <input type="hidden" name="idskemapk" value="<?php echo e($idskemapk); ?>">
                <input type="hidden" name="idasesorpk" value="<?php echo e($idasesorpk); ?>">

                <div class="form-grid">
                    <div class="form-label">Tanggal</div>
                    <select id="tglpk" name="tglpk" class="form-input" required>
                        <?php
                        $namaassapk = $namax;
                        $tampiltglpk = "SELECT tanggal, namaasesor FROM pemetaan WHERE kelompok='$kelompokpk' AND idskema='$idskemapk' AND idasesor='$idasesorpk' GROUP BY tanggal, namaasesor";
                        $exectglpk = mysqli_query($conn, $tampiltglpk);
                        if ($exectglpk && mysqli_num_rows($exectglpk) > 0) {
                            while ($rtglpk = mysqli_fetch_array($exectglpk)) {
                                $namaassapk = $rtglpk['namaasesor'] ?? $namax;
                                echo "<option value='".e($rtglpk['tanggal'])."'>".e($rtglpk['tanggal'])."</option>";
                            }
                        } else {
                            echo "<option value=''>Tanggal tidak ditemukan</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="namasportopk" value="<?php echo e($namaassapk); ?>">
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lanjutkan</button>
                </div>
            </form>
        </div>

<?php
elseif ($op == "listpesertapk"):
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['pk_idasesor'] = $_POST['idasesorpk'] ?? '';
        $_SESSION['pk_idskema'] = $_POST['idskemapk'] ?? '';
        $_SESSION['pk_kelompok'] = $_POST['kelompokpk'] ?? '';
        $_SESSION['pk_tgl'] = $_POST['tglpk'] ?? '';
        $_SESSION['pk_namaasesor'] = $_POST['namasportopk'] ?? '';
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpesertapk");
        exit;
    }

    $idasesorpkk = $_SESSION['pk_idasesor'] ?? '';
    $idskemapkk = $_SESSION['pk_idskema'] ?? '';
    $kelompokpkk = $_SESSION['pk_kelompok'] ?? '';
    $tglpkk = $_SESSION['pk_tgl'] ?? '';
    $namaasesorpkk = $_SESSION['pk_namaasesor'] ?? $namax;

    $sqluserpkk = "SELECT linkttd,id FROM lsp_usertbl WHERE id='$idasesorpkk' OR id='$idasesorpkk' LIMIT 1";
    $sqluserapkk = mysqli_query($conn, $sqluserpkk);
    $sqluserbpkk = mysqli_fetch_array($sqluserapkk);
    $linkttd_asesor_pk = $sqluserbpkk['linkttd'] ?? $ttdfrm;

    $sslpkk = "SELECT * FROM pemetaan WHERE kelompok='$kelompokpkk' AND idskema='$idskemapkk' AND tanggal='$tglpkk' AND idasesor='$idasesorpkk'";
    $exec0pkk = mysqli_query($conn, $sslpkk);
    $total = $exec0pkk ? mysqli_num_rows($exec0pkk) : 0;
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
                    <h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Peserta Pihak Ketiga</h3>
                    <p>Tanggal: <strong><?php echo e($tglpkk); ?></strong> · <?php echo e($total); ?> peserta</p>
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
                            <th style="width:170px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($exec0pkk && mysqli_num_rows($exec0pkk) > 0): ?>
                        <?php $no = 1; while ($hasil0pkk = mysqli_fetch_array($exec0pkk)): ?>
                        <?php
                        $idpeserta_pk = $hasil0pkk['idpeserta'];
                        $qp_pk = mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE id='$idpeserta_pk' LIMIT 1");
                        $dp_pk = $qp_pk ? mysqli_fetch_array($qp_pk) : array();
                        $nama_asesi_pk = $dp_pk['nama'] ?? ($hasil0pkk['namapeserta'] ?? 'Asesi');
                        ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td><span class="badge badge-navy"><?php echo e($hasil0pkk['idpeserta']); ?></span></td>
                            <td style="font-weight:600"><?php echo e($nama_asesi_pk); ?></td>
                            <td style="color:var(--text-sub)"><?php echo e($hasil0pkk['tanggal']); ?></td>
                            <td style="text-align:center">
                                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=pkob&idasesor=<?php echo e($hasil0pkk['idasesor']); ?>&kelompok=<?php echo e(urlencode($kelompokpkk)); ?>&tgl=<?php echo e(urlencode($hasil0pkk['tanggal'])); ?>&idasesi=<?php echo e($hasil0pkk['idpeserta']); ?>&idskema=<?php echo e($idskemapkk); ?>&lkttd=<?php echo e(urlencode($linkttd_asesor_pk)); ?>&nmass=<?php echo e(urlencode($namaasesorpkk)); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-list"></i> Tampilkan Unit
                                </a>
                            </td>
                        </tr>
                        <?php $no++; endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5"><div class="empty-state"><i class="fas fa-users-slash"></i><p>Peserta tidak ditemukan.</p></div></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php
elseif ($op == "pkob"):
    $idskema = $_GET['idskema'] ?? '';
    $idasesi = $_GET['idasesi'] ?? '';
    $tgl = $_GET['tgl'] ?? '';
    $kelompok = $_GET['kelompok'] ?? '';
    $namasesor = $_GET['nmass'] ?? $namax;

    $sqlskema = "SELECT * FROM skema WHERE idskema='$idskema' LIMIT 1";
    $execskema = mysqli_query($conn, $sqlskema);
    $listskema = mysqli_fetch_array($execskema);
    $namaskema = row_value($listskema, ['namaskema', 'namaskema']);
    $kodeskema = row_value($listskema, ['noskema', 'kodeskema']);

    $sqladsesi = "SELECT * FROM lsp_usertbl WHERE id='$idasesi' OR id='$idasesi' LIMIT 1";
    $execadsesi = mysqli_query($conn, $sqladsesi);
    $listadsesi = mysqli_fetch_array($execadsesi);
    $namaadsesi = $listadsesi['nama'] ?? '-';
    $emailadsesi = $listadsesi['email'] ?? '';

    $sqlunitz = "SELECT unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit
                 FROM unitsiswa
                 INNER JOIN unit ON unitsiswa.idunit=unit.idunit
                 WHERE unitsiswa.idskema='$idskema' AND unitsiswa.idadsesi='$idasesi'";
    $execunitz = mysqli_query($conn, $sqlunitz);
    $totalunit = $execunitz ? mysqli_num_rows($execunitz) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-list-check" style="color:var(--teal);margin-right:8px"></i>Daftar Unit Pihak Ketiga</h3>
                    <p>Asesi: <strong><?php echo e($namaadsesi); ?></strong> · Skema: <?php echo e($namaskema); ?> · <?php echo e($totalunit); ?> unit</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpesertapk" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <div class="tbl-wrap">
                <table class="unit-tbl">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>ID Asesi</th>
                            <th>Kode Unit</th>
                            <th>Nama Unit</th>
                            <th>Status Observasi</th>
                            <th style="width:140px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($execunitz && mysqli_num_rows($execunitz) > 0): ?>
                        <?php $no = 1; while ($daftaobservasi = mysqli_fetch_array($execunitz)): ?>
                            <?php
                            $cekobser = "SELECT idunit,idadsesi FROM rekappraktek WHERE idunit='".$daftaobservasi['idunit']."' AND idadsesi='".$daftaobservasi['idadsesi']."' GROUP BY idunit,idadsesi";
                            $cekobsera = mysqli_query($conn, $cekobser);
                            $g = $cekobsera ? mysqli_num_rows($cekobsera) : 0;
                            $keto = ($g > 0)
                                ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Pernah observasi</span>'
                                : '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Belum observasi</span>';
                            ?>
                            <tr>
                                <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                                <td><?php echo e($daftaobservasi['idadsesi']); ?></td>
                                <td><span class="badge badge-teal"><?php echo e($daftaobservasi['kodeunit']); ?></span></td>
                                <td style="font-weight:500"><?php echo e($daftaobservasi['namaunit']); ?></td>
                                <td><?php echo $keto; ?></td>
                                <td style="text-align:center">
                                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=frmpihakketiga&kode=in&kou=<?php echo e(urlencode($daftaobservasi['kodeunit'])); ?>&lttd=<?php echo e(urlencode($ttdfrm)); ?>&namaunit=<?php echo e(urlencode($daftaobservasi['namaunit'])); ?>&idunit=<?php echo e($daftaobservasi['idunit']); ?>&idass=<?php echo e($idasesor); ?>&namaskema=<?php echo e(urlencode($namaskema)); ?>&kodeskema=<?php echo e(urlencode($kodeskema)); ?>&nmasesi=<?php echo e(urlencode($namaadsesi)); ?>&k=<?php echo e(urlencode($kelompok)); ?>&tgl=<?php echo e(urlencode($tgl)); ?>&idasesi=<?php echo e($daftaobservasi['idadsesi']); ?>&nmasesor=<?php echo e(urlencode($namasesor)); ?>&idskema=<?php echo e($daftaobservasi['idskema']); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-file-lines"></i> Formulir
                                    </a>
                                </td>
                            </tr>
                        <?php $no++; endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6"><div class="empty-state"><i class="fas fa-folder-open"></i><p>Unit belum tersedia.</p></div></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php
elseif ($op == "frmpihakketiga"):
    $namaasesifrm = $_GET['nmasesi'] ?? '';
    $namaskemafrm = $_GET['namaskema'] ?? '';
    $kodeskemafrm = $_GET['kodeskema'] ?? '';
    $kodeunitfrm = $_GET['kou'] ?? '';
    $namaunitfrm = $_GET['namaunit'] ?? '';
    $idasesifrm = $_GET['idasesi'] ?? '';
    $idasesorfrm = $_GET['idass'] ?? $idasesor;
    $idunitfrm = $_GET['idunit'] ?? '';
    $tglfrm = $_GET['tgl'] ?? '';
    $idskemafrm = $_GET['idskema'] ?? '';
    $namaasesorfrm = $_GET['nmasesor'] ?? $namax;
    $linkttdfrm = !empty($_GET['lttd']) ? "../imgttd/" . $_GET['lttd'] : "";

    $cekdatapktt = "SELECT * FROM pihakketiga WHERE idskemaphktiga='$idskemafrm' AND idasesorphktiga='$idasesorfrm' AND idasesiphktiga='$idasesifrm' AND idunitphktiga='$idunitfrm'";
    $adapktt = mysqli_query($conn, $cekdatapktt);
    $datapkttfrm = ($adapktt && mysqli_num_rows($adapktt) > 0) ? mysqli_fetch_array($adapktt) : array();

    $jawabanfrm = $datapkttfrm['jawabanphktiga'] ?? '';
    $pecahjawab = explode(",", $jawabanfrm);
    $tglban = date("Y-m-d");
?>
        <div class="card">
            <div class="section-head no-print">
                <div>
                    <h3><i class="fas fa-file-lines" style="color:var(--teal);margin-right:8px"></i>Formulir Klarifikasi Pihak Ketiga</h3>
                    <p>Asesi: <strong><?php echo e($namaasesifrm); ?></strong> · Unit: <?php echo e($kodeunitfrm); ?></p>
                </div>
                <div style="display:flex;gap:10px">
                    <button onclick="window.print()" class="btn btn-print btn-sm" type="button"><i class="fas fa-print"></i> Cetak</button>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>

            <form id="ban" name="ban" method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=postpktfrm">
                <input type="hidden" name="idasesor" value="<?php echo e($idasesorfrm); ?>">
                <input type="hidden" name="idadsesi" value="<?php echo e($idasesifrm); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($idskemafrm); ?>">
                <input type="hidden" name="tgl" value="<?php echo e($tglfrm); ?>">
                <input type="hidden" name="idunit" value="<?php echo e($idunitfrm); ?>">

                <table class="doc-table">
                    <tr><td colspan="5"><strong>Skema Sertifikasi:</strong> <?php echo e($namaskemafrm); ?><br><strong>No. Skema Sertifikasi:</strong> <?php echo e($kodeskemafrm); ?></td></tr>
                    <tr class="doc-muted"><td colspan="5"><strong>Nama Asesi:</strong> <?php echo e($namaasesifrm); ?><br><strong>Nama Asesor:</strong> <?php echo e($namaasesorfrm); ?><br><strong>Tanggal Asesmen:</strong> <?php echo e($tglfrm); ?></td></tr>
                    <tr><td colspan="5" class="doc-head">Panduan Bagi Asesor</td></tr>
                    <tr><td colspan="5">Lengkapi formulir ini sesuai dengan pertanyaan dalam tabel ini secara seksama.</td></tr>
                    <tr><td rowspan="2" class="doc-muted"><strong>Uji Kompetensi</strong></td><td class="doc-muted"><strong>Kode Unit</strong></td><td colspan="3"><?php echo e($kodeunitfrm); ?></td></tr>
                    <tr><td class="doc-muted"><strong>Judul Unit</strong></td><td colspan="3"><?php echo e($namaunitfrm); ?></td></tr>
                    <tr><td colspan="5" class="doc-head">Nama Pengawas / Penyelia / Atasan / Orang Lain di Perusahaan</td></tr>
                    <tr><td>Tempat kerja</td><td colspan="4"><input type="text" name="tkerja" class="doc-input" value="<?php echo e($datapkttfrm['tempatkerja'] ?? ''); ?>"></td></tr>
                    <tr><td>Alamat</td><td colspan="4"><input type="text" name="talamat" class="doc-input" value="<?php echo e($datapkttfrm['alamat'] ?? ''); ?>"></td></tr>
                    <tr><td>No. Telepon</td><td colspan="4"><input type="text" name="tnotlp" class="doc-input" value="<?php echo e($datapkttfrm['notlp'] ?? ''); ?>"></td></tr>
                    <tr><th colspan="3">Pertanyaan</th><th>Ya</th><th>Tidak</th></tr>
                    <tr><td colspan="3"><strong>Apakah asesi bekerja dengan mempertimbangkan Kesehatan, Keamanan dan Keselamatan Kerja?</strong></td><td class="radio-cell"><input type="radio" name="tim" value="Y" <?php echo checked_if($pecahjawab[0] ?? '', 'Y'); ?>></td><td class="radio-cell"><input type="radio" name="tim" value="T" <?php echo checked_if($pecahjawab[0] ?? '', 'T'); ?>></td></tr>
                    <tr><td colspan="3"><strong>Apakah asesi berinteraksi dengan harmonis didalam kelompoknya?</strong></td><td class="radio-cell"><input type="radio" name="har" value="Y" <?php echo checked_if($pecahjawab[1] ?? '', 'Y'); ?>></td><td class="radio-cell"><input type="radio" name="har" value="T" <?php echo checked_if($pecahjawab[1] ?? '', 'T'); ?>></td></tr>
                    <tr><td colspan="3"><strong>Apakah asesi dapat mengelola tugas-tugas secara bersamaan?</strong></td><td class="radio-cell"><input type="radio" name="tugas" value="Y" <?php echo checked_if($pecahjawab[2] ?? '', 'Y'); ?>></td><td class="radio-cell"><input type="radio" name="tugas" value="T" <?php echo checked_if($pecahjawab[2] ?? '', 'T'); ?>></td></tr>
                    <tr><td colspan="3"><strong>Apakah asesi dapat dengan cepat beradaptasi dengan peralatan dan lingkungan yang baru?</strong></td><td class="radio-cell"><input type="radio" name="adap" value="Y" <?php echo checked_if($pecahjawab[3] ?? '', 'Y'); ?>></td><td class="radio-cell"><input type="radio" name="adap" value="T" <?php echo checked_if($pecahjawab[3] ?? '', 'T'); ?>></td></tr>
                    <tr><td colspan="3"><strong>Apakah asesi dapat merespon dengan cepat masalah-masalah yang ada di tempat kerjanya?</strong></td><td class="radio-cell"><input type="radio" name="respon" value="Y" <?php echo checked_if($pecahjawab[4] ?? '', 'Y'); ?>></td><td class="radio-cell"><input type="radio" name="respon" value="T" <?php echo checked_if($pecahjawab[4] ?? '', 'T'); ?>></td></tr>
                    <tr><td colspan="3"><strong>Apakah Anda bersedia dihubungi jika verifikasi lebih lanjut dari pernyataan ini diperlukan?</strong></td><td class="radio-cell"><input type="radio" name="sedia" value="Y" <?php echo checked_if($pecahjawab[5] ?? '', 'Y'); ?>></td><td class="radio-cell"><input type="radio" name="sedia" value="T" <?php echo checked_if($pecahjawab[5] ?? '', 'T'); ?>></td></tr>
                    <tr><td colspan="5"><strong>Apa hubungan Anda dengan asesi?</strong><input type="text" name="hubungan" class="doc-input" value="<?php echo e($datapkttfrm['hubunganphktiga'] ?? ''); ?>"></td></tr>
                    <tr><td colspan="5"><strong>Berapa lama Anda bekerja dengan asesi?</strong><input type="text" name="lama" class="doc-input" value="<?php echo e($datapkttfrm['lamaphktiga'] ?? ''); ?>"></td></tr>
                    <tr><td colspan="5"><strong>Seberapa dekat Anda bekerja dengan asesi di area yang dinilai?</strong><input type="text" name="dekat" class="doc-input" value="<?php echo e($datapkttfrm['dekatphktiga'] ?? ''); ?>"></td></tr>
                    <tr><td colspan="5"><strong>Apa pengalaman teknis dan/atau kualifikasi Anda di bidang yang dinilai?</strong><input type="text" name="pengalaman" class="doc-input" value="<?php echo e($datapkttfrm['pengalamanphktiga'] ?? ''); ?>"></td></tr>
                    <tr><td colspan="5"><strong>Secara keseluruhan, apakah Anda yakin asesi melakukan sesuai standar yang diminta oleh unit kompetensi secara konsisten?</strong><input type="text" name="standar" class="doc-input" value="<?php echo e($datapkttfrm['standarphktiga'] ?? ''); ?>"></td></tr>
                    <tr><td colspan="5"><strong>Identifikasi kebutuhan pelatihan lebih lanjut untuk asesi:</strong><input type="text" name="kebutuhan" class="doc-input" value="<?php echo e($datapkttfrm['kebutuhanphktiga'] ?? ''); ?>"></td></tr>
                    <tr><td colspan="5"><strong>Ada komentar lain:</strong><textarea name="komen" class="doc-textarea"><?php echo e($datapkttfrm['komenphktiga'] ?? ''); ?></textarea></td></tr>
                    <tr>
                        <td colspan="3">
                            Tanda tangan
                            <?php if ($linkttdfrm !== ""): ?><br><img src="<?php echo e($linkttdfrm); ?>" class="ttd-img" alt="TTD Asesor"><?php endif; ?>
                        </td>
                        <td colspan="2">Tanggal: <input type="text" name="tglban" class="doc-input" value="<?php echo e($tglban); ?>" readonly></td>
                    </tr>
                </table>

                <div class="form-actions">
                    <button type="submit" name="simpan" class="btn btn-success"><i class="fas fa-floppy-disk"></i> Simpan</button>
                </div>
            </form>
        </div>

<?php
elseif ($op == "postpktfrm"):
    $idskemapfrm = $_POST['idskema'] ?? '';
    $idasesipfrm = $_POST['idadsesi'] ?? '';
    $idasesorpfrm = $_POST['idasesor'] ?? '';
    $idunitpfrm = $_POST['idunit'] ?? '';
    $tempatkpfrm = esc_sql($conn, $_POST['tkerja'] ?? '');
    $alamatpfrm = esc_sql($conn, $_POST['talamat'] ?? '');
    $notlppfrm = esc_sql($conn, $_POST['tnotlp'] ?? '');
    $hubuganpfrm = esc_sql($conn, $_POST['hubungan'] ?? '');
    $lamakpfrm = esc_sql($conn, $_POST['lama'] ?? '');
    $dekatpfrm = esc_sql($conn, $_POST['dekat'] ?? '');
    $pengalamanpfrm = esc_sql($conn, $_POST['pengalaman'] ?? '');
    $standarpfrm = esc_sql($conn, $_POST['standar'] ?? '');
    $kebutuhanpfrm = esc_sql($conn, $_POST['kebutuhan'] ?? '');
    $komentarpfrm = esc_sql($conn, $_POST['komen'] ?? '');
    $sukses = 0;
    $gagal = 0;
    $sup = 0;

    if (isset($_POST['tim'], $_POST['har'], $_POST['tugas'], $_POST['adap'], $_POST['respon'], $_POST['sedia'])) {
        $ketpktfrm = $_POST['tim'] . "," . $_POST['har'] . "," . $_POST['tugas'] . "," . $_POST['adap'] . "," . $_POST['respon'] . "," . $_POST['sedia'];
        $cekdatapkt = "SELECT * FROM pihakketiga WHERE idskemaphktiga='$idskemapfrm' AND idasesorphktiga='$idasesorpfrm' AND idasesiphktiga='$idasesipfrm' AND idunitphktiga='$idunitpfrm'";
        $adapkt = mysqli_query($conn, $cekdatapkt);
        $adakpkt = $adapkt ? mysqli_num_rows($adapkt) : 0;

        if ($adakpkt > 0) {
            $ssqlphku = "UPDATE pihakketiga SET jawabanphktiga='$ketpktfrm', tempatkerja='$tempatkpfrm', alamat='$alamatpfrm', notlp='$notlppfrm', hubunganphktiga='$hubuganpfrm', lamaphktiga='$lamakpfrm', dekatphktiga='$dekatpfrm', pengalamanphktiga='$pengalamanpfrm', standarphktiga='$standarpfrm', kebutuhanphktiga='$kebutuhanpfrm', komenphktiga='$komentarpfrm' WHERE idskemaphktiga='$idskemapfrm' AND idasesiphktiga='$idasesipfrm' AND idasesorphktiga='$idasesorpfrm' AND idunitphktiga='$idunitpfrm'";
            if (mysqli_query($conn, $ssqlphku)) {
                $sup++;
            } else {
                $gagal++;
            }
        } else {
            $ssqlpktp = "INSERT INTO pihakketiga (idskemaphktiga, idasesiphktiga, idasesorphktiga, idunitphktiga, jawabanphktiga, tempatkerja, alamat, notlp, hubunganphktiga, lamaphktiga, dekatphktiga, pengalamanphktiga, standarphktiga, kebutuhanphktiga, komenphktiga) VALUES ('$idskemapfrm', '$idasesipfrm', '$idasesorpfrm', '$idunitpfrm', '$ketpktfrm', '$tempatkpfrm', '$alamatpfrm', '$notlppfrm', '$hubuganpfrm', '$lamakpfrm', '$dekatpfrm', '$pengalamanpfrm', '$standarpfrm', '$kebutuhanpfrm', '$komentarpfrm')";
            if (mysqli_query($conn, $ssqlpktp)) {
                $sukses++;
            } else {
                $gagal++;
            }
        }
    } else {
        $gagal++;
    }
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-circle-check" style="color:var(--teal);margin-right:8px"></i>Hasil Simpan Pihak Ketiga</h3>
                    <p>Data klarifikasi pihak ketiga telah diproses.</p>
                </div>
            </div>
            <div class="alert-box <?php echo ($gagal > 0 && $sukses == 0 && $sup == 0) ? 'alert-error' : 'alert-success'; ?>">
                <i class="fas fa-circle-check"></i>
                <div>
                    <strong>Proses selesai.</strong>
                    <p style="margin-top:2px;font-size:.8rem">Simpan baru: <?php echo e($sukses); ?> · Update: <?php echo e($sup); ?> · Gagal: <?php echo e($gagal); ?></p>
                </div>
            </div>
            <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali ke Jadwal</a>
        </div>

<?php
else:
    $queryptiga = "SELECT kelompok, idskema, idasesor FROM pemetaan WHERE idasesor='$idasesor' GROUP BY kelompok,idskema,idasesor";
    $hasilptiga = mysqli_query($conn, $queryptiga);
    $total_jadwal = $hasilptiga ? mysqli_num_rows($hasilptiga) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Jadwal Pihak Ketiga</h3>
                    <p>Pilih jadwal untuk membuka FR.IA.10 Pihak Ketiga</p>
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
                            <th>Paket</th>
                            <th>Skema</th>
                            <th>Nama Skema</th>
                            <th style="width:190px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; while ($dataptiga = mysqli_fetch_array($hasilptiga)): ?>
                        <?php
                        $id_skema = $dataptiga['idskema'];
                        $ssqlptiga = "SELECT * FROM skema WHERE idskema='$id_skema' LIMIT 1";
                        $execssqlptiga = mysqli_query($conn, $ssqlptiga);
                        $barisptiga = mysqli_fetch_array($execssqlptiga);
                        $namaskemaptiga = row_value($barisptiga, ['namaskema', 'namaskema'], 'Nama Skema Tidak Ditemukan');
                        ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td><span class="badge badge-navy"><?php echo e($dataptiga['kelompok']); ?></span></td>
                            <td><span class="badge badge-teal"><?php echo e($dataptiga['idskema']); ?></span></td>
                            <td style="font-weight:600"><?php echo e($namaskemaptiga); ?></td>
                            <td style="text-align:center">
                                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=pilihtanggalpk&idasesor=<?php echo e($dataptiga['idasesor']); ?>&idskema=<?php echo e($dataptiga['idskema']); ?>&kelompok=<?php echo e(urlencode($dataptiga['kelompok'])); ?>" class="btn btn-primary btn-sm">
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
                    <strong>Belum ada jadwal pihak ketiga untuk asesor ini.</strong>
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
