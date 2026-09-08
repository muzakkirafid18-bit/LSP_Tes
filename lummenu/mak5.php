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
$op = $_REQUEST['op'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.AK.05 Laporan Asesmen - LSP SMKN 1 Cibinong</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="../js/jquery-2.2.3.min.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-datepicker.js"></script>

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
.tbl, .unit-tbl { width:100%; border-collapse:collapse; }
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
    background:#fff;
    color:#000;
    padding:8px;
    font-family:'Plus Jakarta Sans', Arial, sans-serif;
}
.report-table {
    border:2px solid #000;
    width:100%;
    margin-bottom:18px;
    border-collapse:collapse;
}
.report-table td, .report-table th {
    border:1px solid #000;
    padding:8px;
    vertical-align:middle;
    font-size:13px;
}
.report-title {
    font-size:18px;
    font-weight:800;
    text-align:center;
}
.report-muted {
    background:#f2f2f2;
    -webkit-print-color-adjust:exact;
    print-color-adjust:exact;
}
.report-center { text-align:center; }
.report-input {
    width:100%;
    border:1px solid #cbd5e1;
    padding:7px 9px;
    border-radius:6px;
    font:inherit;
}
.report-textarea {
    width:100%;
    border:1px solid #cbd5e1;
    padding:8px 10px;
    border-radius:6px;
    font:inherit;
    resize:vertical;
}
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
    .report-input, .report-textarea { border:none !important; background:transparent !important; }
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
        <a href="mak5.php" class="nav-item active"><i class="fas fa-chart-bar"></i> FR.AK.05 Laporan Asesmen</a>
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
            FR.AK.05 Laporan Asesmen
            <span>Rekap rekomendasi dan catatan asesmen</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div>
            <button class="icon-btn" type="button"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">

<?php
if ($op == "pilihunit"):
    $idskemamak5 = $_GET['idskema'] ?? '';
    $kelompokmak5 = $_GET['kelompok'] ?? '';
    $namaskemamak5 = $_GET['nmskema'] ?? '';
    $idasesormak5 = $_GET['idasesor'] ?? $idasesor;
    $kdskemamak5 = $_GET['kdskema'] ?? '';
    $tglmak5 = $_GET['tgl'] ?? '';

    $sqlunitmak5 = "SELECT * FROM unit WHERE idskema='$idskemamak5' ORDER BY kodeunit";
    $execunitmak5 = mysqli_query($conn, $sqlunitmak5);
    $totalunit = $execunitmak5 ? mysqli_num_rows($execunitmak5) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-list-check" style="color:var(--teal);margin-right:8px"></i>Pilih Unit Laporan</h3>
                    <p>Skema: <strong><?php echo e($namaskemamak5); ?></strong> · <?php echo e($totalunit); ?> unit tersedia</p>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

            <div class="tbl-wrap">
                <table class="unit-tbl">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Kode Unit</th>
                            <th>Nama Unit</th>
                            <th style="width:160px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($execunitmak5 && mysqli_num_rows($execunitmak5) > 0): ?>
                        <?php $no = 1; while ($unit2mak5 = mysqli_fetch_array($execunitmak5)): ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td><span class="badge badge-teal"><?php echo e($unit2mak5['kodeunit']); ?></span></td>
                            <td style="font-weight:500"><?php echo e($unit2mak5['namaunit']); ?></td>
                            <td style="text-align:center">
                                <a class="btn btn-primary btn-sm" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta&idskema2=<?php echo e($idskemamak5); ?>&kodeunti2=<?php echo e(urlencode($unit2mak5['kodeunit'])); ?>&tgl2=<?php echo e(urlencode($tglmak5)); ?>&nmunit2=<?php echo e(urlencode($unit2mak5['namaunit'])); ?>&idunit2=<?php echo e($unit2mak5['idunit']); ?>&idasesor2=<?php echo e($idasesormak5); ?>&kdskema2=<?php echo e(urlencode($kdskemamak5)); ?>&nmskema2=<?php echo e(urlencode($namaskemamak5)); ?>&kelompok2=<?php echo e(urlencode($kelompokmak5)); ?>">
                                    <i class="fas fa-users"></i> Peserta
                                </a>
                            </td>
                        </tr>
                        <?php $no++; endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4"><div class="empty-state"><i class="fas fa-folder-open"></i><p>Unit belum tersedia.</p></div></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<?php
elseif ($op == "listpeserta"):
    $idasesor2 = $_GET['idasesor2'] ?? '';
    $idskema = $_GET['idskema2'] ?? '';
    $kelompok = $_GET['kelompok2'] ?? '';
    $tgl = $_GET['tgl2'] ?? '';
    $nmskema = $_GET['nmskema2'] ?? '';
    $kodeskema = $_GET['kdskema2'] ?? '';
    $idunit = $_GET['idunit2'] ?? '';
    $nmunit = $_GET['nmunit2'] ?? '';
    $kdunit = $_GET['kodeunti2'] ?? '';

    $nmass = "SELECT nama,id,linkttd FROM lsp_usertbl WHERE id='$idasesor2'";
    $nmassa = mysqli_query($conn, $nmass);
    $nmassb = mysqli_fetch_array($nmassa);
    $nama_asesor = $nmassb['nama'] ?? $namax;
    $linkttd_asesor = $nmassb['linkttd'] ?? '';

    $ssl = "SELECT p.idpeserta, u.nama AS namapeserta, p.tanggal
            FROM pemetaan p
            INNER JOIN unitsiswa us ON p.idpeserta = us.idadsesi
            INNER JOIN lsp_usertbl u ON p.idpeserta = u.id
            WHERE p.kelompok='$kelompok'
            AND p.idskema='$idskema'
            AND p.tanggal='$tgl'
            AND p.idasesor='$idasesor2'
            AND us.idunit='$idunit'";
    $exec0 = mysqli_query($conn, $ssl);
?>
        <div class="card">
            <div class="section-head no-print">
                <div>
                    <h3><i class="fas fa-chart-bar" style="color:var(--teal);margin-right:8px"></i>Laporan Asesmen</h3>
                    <p>Unit: <strong><?php echo e($kdunit); ?> - <?php echo e($nmunit); ?></strong></p>
                </div>
                <div style="display:flex;gap:10px">
                    <button type="button" class="btn btn-print btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>

            <form id="obsmak5" name="obsmak5" method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=postobsmak5">
                <input type="hidden" name="idasesor" value="<?php echo e($idasesor2); ?>">
                <input type="hidden" name="kdunit" value="<?php echo e($kdunit); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($idskema); ?>">
                <input type="hidden" name="tgl" value="<?php echo e($tgl); ?>">
                <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">
                <input type="hidden" name="idunit" value="<?php echo e($idunit); ?>">
                <input type="hidden" name="nmasesor" value="<?php echo e($nama_asesor); ?>">

                <div class="report-shell">
                    <table class="report-table">
                        <tr>
                            <td rowspan="4" width="15%" class="report-center">
                                <img src="../images/lsplogosmkn1.png" alt="Logo LSP" style="max-height:85px;max-width:100%;">
                            </td>
                            <td rowspan="4" width="45%" class="report-title">LAPORAN ASESMEN</td>
                            <td width="15%" class="report-muted"><strong>Dokumen:</strong></td>
                            <td width="25%">MUK/247/LSPCBN/2024/FR.AK.05</td>
                        </tr>
                        <tr><td class="report-muted"><strong>Edisi / Revisi:</strong></td><td>01 / 00</td></tr>
                        <tr><td class="report-muted"><strong>Berlaku Sejak:</strong></td><td>19 Oktober 2024</td></tr>
                        <tr><td class="report-muted"><strong>Halaman:</strong></td><td>1 dari 1</td></tr>
                    </table>

                    <table class="report-table">
                        <tr><td width="30%" class="report-muted"><strong>Skema Sertifikasi (Klaster/Okupasi)</strong></td><td width="5%">:</td><td width="65%"><strong><?php echo e($nmskema); ?></strong></td></tr>
                        <tr><td class="report-muted"><strong>Nomor Skema</strong></td><td>:</td><td><?php echo e($kodeskema); ?></td></tr>
                        <tr><td class="report-muted"><strong>TUK</strong></td><td>:</td><td>Sewaktu / Tempat Kerja / Mandiri *</td></tr>
                        <tr><td class="report-muted"><strong>Nama Asesor</strong></td><td>:</td><td><strong><?php echo e($nama_asesor); ?></strong></td></tr>
                        <tr><td class="report-muted"><strong>Tanggal Pelaksanaan</strong></td><td>:</td><td><?php echo e($tgl); ?></td></tr>
                    </table>

                    <table class="report-table">
                        <thead>
                            <tr class="report-muted report-center">
                                <th rowspan="2" width="5%">No</th>
                                <th rowspan="2" width="50%">Nama Asesi</th>
                                <th colspan="2" width="15%">Rekomendasi</th>
                                <th rowspan="2" width="30%">Keterangan **</th>
                            </tr>
                            <tr class="report-muted report-center"><th width="7.5%">K</th><th width="7.5%">BK</th></tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $adackmak5a = array('aspek' => '', 'penolakan' => '', 'saran' => '', 'catatan' => '');
                        if ($exec0 && mysqli_num_rows($exec0) > 0):
                            while ($hasil0 = mysqli_fetch_array($exec0)):
                                $ckmak5 = "SELECT * FROM mak5baru WHERE idskema='$idskema' AND idasesi='".$hasil0['idpeserta']."' AND idasesor='$idasesor2' AND idunit='$idunit'";
                                $adackmak5 = mysqli_query($conn, $ckmak5);
                                if ($adackmak5 && mysqli_num_rows($adackmak5) > 0) {
                                    $adackmak5a = mysqli_fetch_array($adackmak5);
                                    $ketmak5a = $adackmak5a['ketmak5'] ?? '';
                                    $ckekk5 = checked_if($adackmak5a['bk'] ?? '', 'K');
                                    $ckebk5 = checked_if($adackmak5a['bk'] ?? '', 'BK');
                                } else {
                                    $ketmak5a = '';
                                    $ckekk5 = '';
                                    $ckebk5 = '';
                                }
                        ?>
                            <tr>
                                <td class="report-center">
                                    <?php echo e($i + 1); ?>
                                    <input type="hidden" name="idpeserta<?php echo e($i); ?>" value="<?php echo e($hasil0['idpeserta']); ?>">
                                </td>
                                <td>
                                    <?php echo e($hasil0['namapeserta']); ?>
                                    <input type="hidden" name="nmpeserta<?php echo e($i); ?>" value="<?php echo e($hasil0['namapeserta']); ?>">
                                </td>
                                <td class="report-center"><input type="radio" name="pcpmak6<?php echo e($i); ?>" value="K" <?php echo $ckekk5; ?>></td>
                                <td class="report-center"><input type="radio" name="pcpmak6<?php echo e($i); ?>" value="BK" <?php echo $ckebk5; ?>></td>
                                <td><input type="text" name="ketmak5<?php echo e($i); ?>" value="<?php echo e($ketmak5a); ?>" class="report-input"></td>
                            </tr>
                        <?php $i++; endwhile; else: ?>
                            <tr><td colspan="5" class="report-center">Tidak ada peserta untuk unit ini.</td></tr>
                        <?php endif; ?>
                            <tr><td colspan="5" style="font-size:11px;font-style:italic;">** Tuliskan Kode dan Judul Unit Kompetensi yang dinyatakan BK (Belum Kompeten) pada kolom keterangan.</td></tr>
                        </tbody>
                    </table>

                    <table class="report-table">
                        <tr><td width="40%" class="report-muted"><strong>Aspek Negatif dan Positif dalam Asesmen</strong></td><td width="60%"><input type="text" name="aspek" class="report-input" value="<?php echo e($adackmak5a['aspek'] ?? ''); ?>"></td></tr>
                        <tr><td class="report-muted"><strong>Pencatatan Penolakan Hasil Asesmen</strong></td><td><input type="text" name="tolak" class="report-input" value="<?php echo e($adackmak5a['penolakan'] ?? ''); ?>"></td></tr>
                        <tr><td class="report-muted"><strong>Saran Perbaikan (Asesor/Personil Terkait)</strong></td><td><input type="text" name="saran" class="report-input" value="<?php echo e($adackmak5a['saran'] ?? ''); ?>"></td></tr>
                    </table>

                    <table class="report-table">
                        <tr class="report-muted">
                            <th width="50%" class="report-center">Catatan / Rekomendasi Asesor</th>
                            <th width="50%" colspan="2" class="report-center">Asesor</th>
                        </tr>
                        <tr>
                            <td rowspan="3" style="vertical-align:top;">
                                <textarea name="catatanmak5" class="report-textarea" rows="5" placeholder="Tulis catatan asesmen di sini..."><?php echo e($adackmak5a['catatan'] ?? ''); ?></textarea>
                            </td>
                            <td width="15%" class="report-muted">Nama:</td>
                            <td width="35%"><strong><?php echo e($nama_asesor); ?></strong></td>
                        </tr>
                        <tr><td class="report-muted">No. Reg:</td><td></td></tr>
                        <tr>
                            <td class="report-muted">Tanda Tangan / Tanggal:</td>
                            <td class="report-center">
                                <?php if (!empty($linkttd_asesor)): ?>
                                    <img src="<?php echo e("../imgttd/" . $linkttd_asesor); ?>" height="60" alt="Tanda Tangan Asesor"><br>
                                <?php endif; ?>
                                <small style="color:#666;"><?php echo e($tgl); ?></small>
                            </td>
                        </tr>
                    </table>
                </div>

                <input type="hidden" name="n" value="<?php echo e($i); ?>">
                <div class="form-actions no-print">
                    <button type="submit" name="simpan" class="btn btn-success">
                        <i class="fas fa-floppy-disk"></i> Simpan Laporan Asesmen
                    </button>
                </div>
            </form>
        </div>

<?php
elseif ($op == "postobsmak5"):
    $aspek = $_POST['aspek'] ?? '';
    $tolak = $_POST['tolak'] ?? '';
    $saran = $_POST['saran'] ?? '';
    $catatan = $_POST['catatanmak5'] ?? '';
    $nmasesor = $_POST['nmasesor'] ?? '';
    $kdunit = $_POST['kdunit'] ?? '';
    $ids = $_POST['idskema'] ?? '';
    $idasesor_post = $_POST['idasesor'] ?? '';
    $tgl = $_POST['tgl'] ?? '';
    $kelompok = $_POST['kelompok'] ?? '';
    $n = (int)($_POST['n'] ?? 0);
    $idunit = $_POST['idunit'] ?? '';
    $sukses = 0;
    $update = 0;
    $gagal = 0;

    for ($i = 0; $i <= $n - 1; $i++) {
        if (isset($_POST['pcpmak6'.$i])) {
            $nmpeserta = $_POST['nmpeserta'.$i] ?? '';
            $idasesi = $_POST['idpeserta'.$i] ?? '';
            $bk = $_POST['pcpmak6'.$i] ?? '';
            $ketmak5 = $_POST['ketmak5'.$i] ?? '';

            $cekdata = "SELECT * FROM mak5baru WHERE idskema='$ids' AND idasesi='$idasesi' AND idasesor='$idasesor_post' AND idunit='$idunit'";
            $ada = mysqli_query($conn, $cekdata);
            if ($ada && mysqli_num_rows($ada) > 0) {
                $sqlmk5 = "UPDATE mak5baru SET bk='$bk', ketmak5='$ketmak5', aspek='$aspek', penolakan='$tolak', saran='$saran', catatan='$catatan' WHERE idskema='$ids' AND idunit='$idunit' AND idasesi='$idasesi' AND idasesor='$idasesor_post'";
                if (mysqli_query($conn, $sqlmk5)) {
                    $update++;
                } else {
                    $gagal++;
                }
            } else {
                $sqlmak5 = "INSERT INTO mak5baru (idskema, idunit, idasesi, idasesor, bk, kelompok, ketmak5, aspek, penolakan, saran, catatan, nmasesor, nmpeserta, kdunit) VALUES ('$ids', '$idunit', '$idasesi', '$idasesor_post', '$bk', '$kelompok', '$ketmak5', '$aspek', '$tolak', '$saran', '$catatan', '$nmasesor', '$nmpeserta', '$kdunit')";
                if (mysqli_query($conn, $sqlmak5)) {
                    $sukses++;
                } else {
                    $gagal++;
                }
            }
        }
    }
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-circle-check" style="color:var(--teal);margin-right:8px"></i>Hasil Simpan Laporan</h3>
                    <p>Data FR.AK.05 telah diproses.</p>
                </div>
            </div>
            <div class="alert-box alert-success">
                <i class="fas fa-circle-check"></i>
                <div>
                    <strong>Proses selesai.</strong>
                    <p style="margin-top:2px;font-size:.8rem">Simpan baru: <?php echo e($sukses); ?> · Update: <?php echo e($update); ?> · Gagal: <?php echo e($gagal); ?></p>
                </div>
            </div>
            <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali ke Jadwal</a>
        </div>

<?php
else:
    $queryobmain = "SELECT kelompok, idskema, idasesor, tanggal FROM pemetaan WHERE idasesor='$idasesor' GROUP BY kelompok, idskema, idasesor, tanggal";
    $hasilobmain = mysqli_query($conn, $queryobmain);
    $total_jadwal = $hasilobmain ? mysqli_num_rows($hasilobmain) : 0;
?>
        <div class="card">
            <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="alert-box alert-success alert-auto-hide"><i class="fas fa-circle-check"></i><strong><?php echo e($_SESSION['flash_success']); ?></strong></div>
                <?php unset($_SESSION['flash_success']); ?>
            <?php endif; ?>

            <div class="section-head">
                <div>
                    <h3><i class="fas fa-calendar-check" style="color:var(--teal);margin-right:8px"></i>Daftar Jadwal Laporan Asesmen</h3>
                    <p>Pilih jadwal untuk membuka unit FR.AK.05</p>
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
                            <th>Tanggal</th>
                            <th style="width:170px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    while ($dataobvmain = mysqli_fetch_array($hasilobmain)):
                        $id_skema = $dataobvmain['idskema'];
                        $ssqlmain = "SELECT * FROM skema WHERE idskema='$id_skema'";
                        $execssql = mysqli_query($conn, $ssqlmain);
                        $baris = mysqli_fetch_array($execssql);
                        $namaskema = $baris['namaskema'] ?? 'Nama Skema Tidak Ditemukan';
                        $kodeskema = $baris['noskema'] ?? ($baris['kodeskema'] ?? '');
                    ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td><span class="badge badge-navy"><?php echo e($dataobvmain['kelompok']); ?></span></td>
                            <td><span class="badge badge-teal"><?php echo e($dataobvmain['idskema']); ?></span></td>
                            <td style="font-weight:600"><?php echo e($namaskema); ?></td>
                            <td style="color:var(--text-sub)"><?php echo e($dataobvmain['tanggal']); ?></td>
                            <td style="text-align:center">
                                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=pilihunit&idskema=<?php echo e($dataobvmain['idskema']); ?>&tgl=<?php echo e(urlencode($dataobvmain['tanggal'])); ?>&idasesor=<?php echo e($idasesor); ?>&kdskema=<?php echo e(urlencode($kodeskema)); ?>&nmskema=<?php echo e(urlencode($namaskema)); ?>&kelompok=<?php echo e(urlencode($dataobvmain['kelompok'])); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-list"></i> Tampilkan Unit
                                </a>
                            </td>
                        </tr>
                    <?php $no++; endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i><strong>Belum ada jadwal FR.AK.05 untuk asesor ini.</strong></div>
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
