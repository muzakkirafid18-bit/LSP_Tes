<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_set_cookie_params(3600 * 2, "/");
session_start();
include "../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function checked_if($a, $b) {
    return ((string)$a === (string)$b) ? "checked" : "";
}

function db_escape($conn, $value) {
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
$op = $_REQUEST['op'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.AK.06 Meninjau Proses Asesmen - LSP SMKN 1 Cibinong</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="../lummenu/js/jquery-2.2.3.min.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>
<script src="../lummenu/js/bootstrap.js"></script>

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
.tbl { width:100%; border-collapse:collapse; }
.tbl th {
    text-align:left;
    padding:12px 16px;
    font-size:.72rem;
    font-weight:700;
    letter-spacing:.7px;
    text-transform:uppercase;
    color:rgba(255,255,255,.78);
    background:var(--navy);
}
.tbl th:first-child { border-radius:8px 0 0 8px; }
.tbl th:last-child { border-radius:0 8px 8px 0; }
.tbl td {
    padding:13px 16px;
    font-size:.845rem;
    border-bottom:1px solid var(--off);
    vertical-align:middle;
}
.tbl tbody tr:hover td { background:#F0F9F9; }
.badge {
    display:inline-flex;
    align-items:center;
    gap:4px;
    padding:3px 10px;
    border-radius:6px;
    font-size:.72rem;
    font-weight:700;
}
.badge-teal { background:var(--teal-light); color:var(--teal-dark); }
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

.report-shell {
    width:100%;
    max-width:960px;
    margin:0 auto;
    background:#fff;
    color:#000;
    font-family:Arial, sans-serif;
    line-height:1.35;
}
.report-table {
    width:100%;
    border-collapse:collapse;
    margin-bottom:15px;
}
.report-table td, .report-table th {
    border:1px solid #000;
    padding:7px 9px;
    vertical-align:middle;
    font-size:13px;
}
.report-kop td { border:2px solid #000; }
.report-muted {
    background:#f2f2f2;
    -webkit-print-color-adjust:exact;
    print-color-adjust:exact;
}
.report-center { text-align:center; }
.report-bold { font-weight:bold; }
.report-input, .report-textarea, .report-select {
    width:100%;
    border:1px solid #cbd5e1;
    padding:6px 8px;
    border-radius:5px;
    font:inherit;
}
.report-textarea { min-height:86px; resize:vertical; }
@media (max-width:900px) {
    



    .main { margin-left:0; }
    .section-head { flex-direction:column; align-items:flex-start; }
}
@media print {
    .sidebar, .topbar, .page-footer, .no-print, .form-actions { display:none !important; }
    .main { margin-left:0; }
    .content { padding:0; }
    .card { box-shadow:none; border:0; padding:0; }
    body { background:#fff; color:#000; }
    .report-shell { max-width:100%; }
    .report-input, .report-textarea, .report-select { border:none !important; background:transparent !important; }
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
        <a href="mak6baru.php" class="nav-item active"><i class="fas fa-map"></i> FR.AK.06 Meninjau Proses</a>
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
            FR.AK.06 Meninjau Proses Asesmen
            <span>Review prinsip asesmen dan dimensi kompetensi</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div>
            <button class="icon-btn" type="button"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">

<?php
if ($op == "mak7post"):
    $email = trim($_POST['email'] ?? '');
    $n = (int)($_POST['n'] ?? 0);
    $idskema = $_POST['idskema'] ?? '';
    $komentar = db_escape($conn, $_POST['komentar'] ?? '');
    $rvalid = db_escape($conn, $_POST['rvalid'] ?? '');
    $idpengurus = $_POST['pengm6'] ?? '';
    $tanggal = $_POST['tanggal'] ?? date('Y-m-d');
    $tanggal2 = date('Y-m-d', strtotime($tanggal));

    $success_count = 0;
    $fail_count = 0;

    for ($i = 0; $i <= $n - 1; $i++) {
        if (isset($_POST['idqmak7'.$i])) {
            $idqmak7 = $_POST['idqmak7'.$i];
            $rvalidasi = db_escape($conn, $_POST['rvalidasi'.$i] ?? '');
            $validasi0 = $_POST['validasia'.$i] ?? '';
            $validasi1 = $_POST['validasib'.$i] ?? '';
            $validasi2 = $_POST['validasic'.$i] ?? '';
            $validasi3 = $_POST['validasid'.$i] ?? '';
            $allvalidasi = $validasi0 . "," . $validasi1 . "," . $validasi2 . "," . $validasi3;

            $valida = $_POST['valida'] ?? '';
            $validb = $_POST['validb'] ?? '';
            $validc = $_POST['validc'] ?? '';
            $validd = $_POST['validd'] ?? '';
            $valide = $_POST['valide'] ?? '';
            $allvalid = $valida . "," . $validb . "," . $validc . "," . $validd . "," . $valide;

            $cekdulumak7 = "SELECT * FROM mak7 WHERE email='$email' AND idqmak7='$idqmak7' AND idskemamak7='$idskema'";
            $cekdulumak7a = mysqli_query($conn, $cekdulumak7);

            if ($cekdulumak7a && mysqli_num_rows($cekdulumak7a) > 0) {
                $query = "UPDATE mak7 SET validasi='$allvalidasi', valid='$allvalid', rvalidasi='$rvalidasi', rvalid='$rvalid', tglreg='$tanggal2', idpeninjau='$idpengurus', komentar='$komentar' WHERE email='$email' AND idqmak7='$idqmak7' AND idskemamak7='$idskema'";
            } else {
                $query = "INSERT INTO mak7 (email, idqmak7, validasi, valid, rvalidasi, rvalid, idskemamak7, tglreg, idpeninjau, komentar) VALUES ('$email', '$idqmak7', '$allvalidasi', '$allvalid', '$rvalidasi', '$rvalid', '$idskema', '$tanggal2', '$idpengurus', '$komentar')";
            }

            if (mysqli_query($conn, $query)) {
                $success_count++;
            } else {
                $fail_count++;
            }
        }
    }
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-circle-check" style="color:var(--teal);margin-right:8px"></i>Hasil Simpan FR.AK.06</h3>
                    <p>Data peninjauan proses asesmen telah diproses.</p>
                </div>
            </div>
            <?php if ($success_count > 0): ?>
                <div class="alert-box alert-success">
                    <i class="fas fa-circle-check"></i>
                    <div>
                        <strong>Penyimpanan sukses.</strong>
                        <p style="margin-top:2px;font-size:.8rem">Berhasil: <?php echo e($success_count); ?> · Gagal: <?php echo e($fail_count); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert-box alert-warning">
                    <i class="fas fa-triangle-exclamation"></i>
                    <strong>Penyimpanan gagal atau tidak ada data yang berubah.</strong>
                </div>
            <?php endif; ?>
            <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Skema</a>
        </div>

<?php
elseif ($op == "mak6br"):
    $emailuser = trim($uname);
    $kodeskema = $_GET['kodeskema'] ?? '';
    $namaskema = $_GET['nmskema'] ?? '';
    $idskemamak6 = $_GET['idskema'] ?? '';

    $sql = "SELECT * FROM lsp_usertbl WHERE email='$emailuser'";
    $shasil = mysqli_query($conn, $sql);
    $sdata = mysqli_fetch_array($shasil);
    $namap = $sdata['nama'] ?? $namax;
?>
        <div class="card">
            <div class="section-head no-print">
                <div>
                    <h3><i class="fas fa-map" style="color:var(--teal);margin-right:8px"></i>Formulir Meninjau Proses Asesmen</h3>
                    <p>Skema: <strong><?php echo e($namaskema); ?></strong></p>
                </div>
                <div style="display:flex;gap:10px">
                    <button type="button" class="btn btn-print btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>

            <form id="formContoh" method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=mak7post">
                <input type="hidden" name="idskema" value="<?php echo e($idskemamak6); ?>">
                <input type="hidden" name="email" value="<?php echo e($emailuser); ?>">

                <div class="report-shell">
                    <table class="report-table report-kop">
                        <tr>
                            <td rowspan="4" width="20%" class="report-center">
                                <img src="../images/lsplogosmkn1.png" alt="Logo LSP" style="max-height:80px;max-width:100px;">
                            </td>
                            <td rowspan="4" width="45%" class="report-center report-bold" style="font-size:16px;">
                                FORMULIR<br><br>MENINJAU PROSES ASESMEN
                            </td>
                            <td width="15%">Dokumen</td>
                            <td width="20%">: MUK/247/LSPCBN/2024</td>
                        </tr>
                        <tr><td>Edisi / Revisi</td><td>: 01/00</td></tr>
                        <tr><td>Berlaku sejak</td><td>: 19 Oktober 2024</td></tr>
                        <tr><td>Halaman</td><td>: 1/1</td></tr>
                    </table>

                    <div class="report-bold" style="font-size:14px;margin-bottom:10px;">FR.AK.06. MENINJAU PROSES ASESMEN</div>

                    <table class="report-table">
                        <tr><td width="30%" class="report-bold">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td><td width="3%">:</td><td width="67%" class="report-bold">SKEMA SERTIFIKASI OKUPASI<br><?php echo e(strtoupper($namaskema)); ?></td></tr>
                        <tr><td class="report-bold">Nomor</td><td>:</td><td><?php echo e($kodeskema); ?></td></tr>
                        <tr><td class="report-bold">TUK</td><td>:</td><td>Sewaktu / Tempat Kerja / Mandiri*</td></tr>
                        <tr><td class="report-bold">Nama Asesor</td><td>:</td><td><strong><?php echo e($namap); ?></strong></td></tr>
                        <tr><td class="report-bold">Tanggal</td><td>:</td><td><input type="date" name="tanggal" id="tanggal" class="report-input" value="<?php echo e(date('Y-m-d')); ?>"></td></tr>
                    </table>

                    <table class="report-table report-muted">
                        <tr>
                            <td>
                                <strong>Penjelasan :</strong>
                                <ol style="margin:5px 0 0 20px;padding:0;">
                                    <li>Peninjauan dapat dilakukan oleh lead asesor atau asesor yang melaksanakan asesmen.</li>
                                    <li>Peninjauan dapat dilakukan secara terpadu dalam skema sertifikasi dan/atau peserta kelompok yang homogen.</li>
                                    <li>Isilah pemenuhan dimensi kompetensi dengan menuliskan jenis bukti dan instrumen yang digunakan saat asesmen.</li>
                                </ol>
                            </td>
                        </tr>
                    </table>

                    <table class="report-table">
                        <thead>
                            <tr>
                                <th rowspan="2" width="5%" class="report-center report-muted">NO</th>
                                <th rowspan="2" width="35%" class="report-muted">Aspek yang ditinjau</th>
                                <th colspan="4" class="report-center report-muted">Kesesuaian dengan prinsip asesmen</th>
                                <th rowspan="2" width="20%" class="report-muted">Rekomendasi Perbaikan</th>
                            </tr>
                            <tr>
                                <th width="10%" class="report-center report-muted">Validitas</th>
                                <th width="10%" class="report-center report-muted">Reliabel</th>
                                <th width="10%" class="report-center report-muted">Fleksibel</th>
                                <th width="10%" class="report-center report-muted">Adil</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $num = 1;
                        $rva = "";
                        $idpeninjau = "";
                        $vkomentar = "";
                        $kdbsa = $kdbsb = $kdbsc = $kdbsd = $kdbse = "";

                        $sqlqmak7 = "SELECT * FROM qmak7";
                        $sqlqmak7a = mysqli_query($conn, $sqlqmak7);
                        while ($sqlqmak7b = mysqli_fetch_array($sqlqmak7a)):
                            $cekdmak7 = "SELECT * FROM mak7 WHERE email='$emailuser' AND idqmak7='".$sqlqmak7b['idqmak7']."' AND idskemamak7='$idskemamak6'";
                            $cekdmak7a = mysqli_query($conn, $cekdmak7);

                            $kdbs0 = $kdbs1 = $kdbs2 = $kdbs3 = "";
                            $rval = "";

                            if ($cekdmak7a && mysqli_num_rows($cekdmak7a) > 0) {
                                $cekdmak7b = mysqli_fetch_array($cekdmak7a);
                                $idpeninjau = $cekdmak7b['idpeninjau'] ?? '';
                                $rval = $cekdmak7b['rvalidasi'] ?? '';
                                $rva = $cekdmak7b['rvalid'] ?? '';
                                $vkomentar = $cekdmak7b['komentar'] ?? $vkomentar;

                                $pecahr = explode(",", $cekdmak7b['validasi'] ?? '');
                                $kdbs0 = checked_if(trim($pecahr[0] ?? ''), 'v');
                                $kdbs1 = checked_if(trim($pecahr[1] ?? ''), 'r');
                                $kdbs2 = checked_if(trim($pecahr[2] ?? ''), 'f');
                                $kdbs3 = checked_if(trim($pecahr[3] ?? ''), 'a');

                                $pecahrr = explode(",", $cekdmak7b['valid'] ?? '');
                                $kdbsa = checked_if(trim($pecahrr[0] ?? ''), 'v');
                                $kdbsb = checked_if(trim($pecahrr[1] ?? ''), 'r');
                                $kdbsc = checked_if(trim($pecahrr[2] ?? ''), 'f');
                                $kdbsd = checked_if(trim($pecahrr[3] ?? ''), 'i');
                                $kdbse = checked_if(trim($pecahrr[4] ?? ''), 't');
                            }
                        ?>
                            <tr>
                                <td class="report-center"><?php echo e($num); ?></td>
                                <td>
                                    <input type="hidden" name="idqmak7<?php echo e($i); ?>" value="<?php echo e($sqlqmak7b['idqmak7']); ?>">
                                    <?php echo e($sqlqmak7b['pertanyaan']); ?>
                                </td>
                                <td class="report-center"><input type="checkbox" name="validasia<?php echo e($i); ?>" value="v" <?php echo $kdbs0; ?>></td>
                                <td class="report-center"><input type="checkbox" name="validasib<?php echo e($i); ?>" value="r" <?php echo $kdbs1; ?>></td>
                                <td class="report-center"><input type="checkbox" name="validasic<?php echo e($i); ?>" value="f" <?php echo $kdbs2; ?>></td>
                                <td class="report-center"><input type="checkbox" name="validasid<?php echo e($i); ?>" value="a" <?php echo $kdbs3; ?>></td>
                                <td><input type="text" name="rvalidasi<?php echo e($i); ?>" class="report-input" value="<?php echo e($rval); ?>"></td>
                            </tr>
                        <?php $num++; $i++; endwhile; ?>
                        </tbody>
                    </table>

                    <table class="report-table">
                        <thead>
                            <tr>
                                <th rowspan="2" width="5%" class="report-center report-muted">NO</th>
                                <th rowspan="2" width="35%" class="report-muted">Aspek yang ditinjau</th>
                                <th colspan="5" class="report-center report-muted">Pemenuhan dimensi kompetensi</th>
                            </tr>
                            <tr>
                                <th width="12%" class="report-center report-muted">Task Skills</th>
                                <th width="12%" class="report-center report-muted">Task Management Skills</th>
                                <th width="12%" class="report-center report-muted">Contingency Management Skills</th>
                                <th width="12%" class="report-center report-muted">Job Role / Environment Skills</th>
                                <th width="12%" class="report-center report-muted">Transfer Skills</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="report-center"><?php echo e($num); ?></td>
                                <td>
                                    <strong>Konsistensi keputusan asesmen</strong><br>
                                    <span style="font-size:11px;color:#444;">Bukti dari berbagai asesmen diperiksa untuk konsistensi dimensi kompetensi.</span>
                                </td>
                                <td class="report-center"><input type="checkbox" name="valida" value="v" <?php echo $kdbsa; ?>></td>
                                <td class="report-center"><input type="checkbox" name="validb" value="r" <?php echo $kdbsb; ?>></td>
                                <td class="report-center"><input type="checkbox" name="validc" value="f" <?php echo $kdbsc; ?>></td>
                                <td class="report-center"><input type="checkbox" name="validd" value="i" <?php echo $kdbsd; ?>></td>
                                <td class="report-center"><input type="checkbox" name="valide" value="t" <?php echo $kdbse; ?>></td>
                            </tr>
                            <tr>
                                <td colspan="7">
                                    <strong>Rekomendasi untuk peningkatan:</strong><br>
                                    <input type="text" name="rvalid" class="report-input" value="<?php echo e($rva); ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="report-table">
                        <tr>
                            <td width="25%" class="report-bold report-center">Nama Lead Asesor / Asesor</td>
                            <td width="30%">
                                <?php
                                $pengurusm6 = "SELECT namapengurus,idpengurus,ttd FROM pengurus";
                                $execpengm6 = mysqli_query($conn, $pengurusm6);
                                $pttd = "";
                                echo "<select name='pengm6' id='pengm6' class='report-select'>";
                                while ($rpengm6 = mysqli_fetch_array($execpengm6)) {
                                    if ($idpeninjau == $rpengm6['idpengurus']) {
                                        $pttd = "../imgttd/" . $rpengm6['ttd'];
                                        echo "<option value='".e($rpengm6['idpengurus'])."' selected>".e($rpengm6['namapengurus'])."</option>";
                                    } else {
                                        echo "<option value='".e($rpengm6['idpengurus'])."'>".e($rpengm6['namapengurus'])."</option>";
                                    }
                                }
                                echo "</select>";
                                ?>
                            </td>
                            <td width="20%" class="report-bold report-center">Komentar</td>
                            <td width="25%" rowspan="2" style="vertical-align:top;">
                                <textarea name="komentar" class="report-textarea" placeholder="Tulis komentar peninjauan..."><?php echo e($vkomentar); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="report-bold report-center">Tanggal & Tanda Tangan</td>
                            <td class="report-center" style="height:64px;">
                                <?php if (!empty($pttd)): ?>
                                    <img src="<?php echo e($pttd); ?>" height="50" style="display:block;margin:0 auto 5px auto;" alt="TTD">
                                <?php endif; ?>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>

                <input type="hidden" name="n" value="<?php echo e($i); ?>">
                <div class="form-actions no-print">
                    <button type="submit" name="simpan" class="btn btn-success">
                        <i class="fas fa-floppy-disk"></i> Simpan Formulir AK06
                    </button>
                </div>
            </form>
        </div>

<?php
else:
    $skema = "SELECT idskema,namaskema,noskema FROM skema WHERE status='Y'";
    $skemaa = mysqli_query($conn, $skema);
    $total_skema = $skemaa ? mysqli_num_rows($skemaa) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-map" style="color:var(--teal);margin-right:8px"></i>Daftar Skema Aktif</h3>
                    <p>Pilih skema untuk mengisi FR.AK.06 Meninjau Proses Asesmen</p>
                </div>
                <span style="background:var(--teal-light);color:var(--teal-dark);padding:4px 14px;border-radius:6px;font-size:.78rem;font-weight:700">
                    <?php echo e($total_skema); ?> Skema
                </span>
            </div>

            <?php if ($total_skema > 0): ?>
            <div class="tbl-wrap">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Judul Skema</th>
                            <th>Kode Skema</th>
                            <th style="width:180px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $nom = 1; while ($skemab = mysqli_fetch_array($skemaa)): ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($nom, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td style="font-weight:600"><?php echo e($skemab['namaskema']); ?></td>
                            <td><span class="badge badge-teal"><?php echo e($skemab['noskema']); ?></span></td>
                            <td style="text-align:center">
                                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=mak6br&idskema=<?php echo e($skemab['idskema']); ?>&nmskema=<?php echo e(urlencode($skemab['namaskema'])); ?>&kodeskema=<?php echo e(urlencode($skemab['noskema'])); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-clipboard-check"></i> Ceklist Form
                                </a>
                            </td>
                        </tr>
                    <?php $nom++; endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Belum ada skema aktif.</p>
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
