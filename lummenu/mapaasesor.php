<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function checked_token($tokens, $index, $expected = 'on') {
    return (isset($tokens[$index]) && trim($tokens[$index]) === $expected) ? "checked" : "";
}

function row_value($row, $keys, $default = '') {
    foreach ($keys as $key) {
        if (isset($row[$key]) && $row[$key] !== '') {
            return $row[$key];
        }
    }
    return $default;
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
$op = $_REQUEST['op'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.MAPA.01 Merencanakan Asesmen - LSP SMKN 1 Cibinong</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="../js/jquery-2.2.3.min.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>
<script src="js/bootstrap.js"></script>

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
    --red:#EF4444;
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
.alert-warning { background:#FEFCE8; color:#A16207; border:1px solid #FDE68A; }
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
.btn-print { background:var(--navy); color:#fff; border-color:var(--navy); }
.btn-print:hover { background:var(--navy-soft); color:#fff; }
.btn-sm { padding:6px 14px; font-size:.78rem; border-radius:8px; }
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

.mapa-shell {
    background:#fff;
    color:#000;
    font-family:Arial, sans-serif;
}
.mapa-table {
    width:100%;
    border-collapse:collapse;
    margin-bottom:16px;
}
.mapa-table td, .mapa-table th {
    border:1px solid #000;
    padding:9px 10px;
    vertical-align:top;
    font-size:13px;
    line-height:1.45;
}
.mapa-muted { background:#f2f2f2; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
.mapa-head {
    background:#0F2A3A;
    color:#fff;
    font-weight:bold;
    text-transform:uppercase;
}
.check-list {
    display:grid;
    gap:7px;
}
.check-item {
    display:flex;
    align-items:flex-start;
    gap:8px;
}
.check-item input { margin-top:2px; accent-color:var(--teal); }
.sign-grid {
    display:grid;
    grid-template-columns:repeat(3, 1fr);
    gap:14px;
}
.sign-box {
    border:1px solid var(--border);
    border-radius:10px;
    padding:14px;
    background:var(--off);
}
.sign-box strong {
    display:block;
    font-size:.78rem;
    color:var(--text-sub);
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-bottom:6px;
}
.sign-box img {
    max-height:64px;
    border:1px solid var(--border);
    border-radius:8px;
    padding:4px;
    background:#fff;
    margin-top:8px;
}
@media (max-width:900px) {
    



    .main { margin-left:0; }
    .section-head { flex-direction:column; align-items:flex-start; }
    .sign-grid { grid-template-columns:1fr; }
}
@media print {
    .sidebar, .topbar, .page-footer, .no-print { display:none !important; }
    .main { margin-left:0; }
    .content { padding:0; }
    .card { box-shadow:none; border:0; padding:0; }
    body { background:#fff; color:#000; }
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
        <a href="mapaasesor.php" class="nav-item active"><i class="fas fa-sitemap"></i> FR.MAPA.01 Merencanakan</a>
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
            FR.MAPA.01 Merencanakan
            <span>Aktivitas dan proses asesmen</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div>
            <button class="icon-btn" type="button"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">

<?php
if ($op == "mapa"):
    $xmmaskema = $_GET['mmanamaskema'] ?? '';
    $xmmakskema = $_GET['mmakodeskema'] ?? '';
    $xmmaids = $_GET['mmaidskema'] ?? '';

    $xcariddtmma = "SELECT * FROM mma WHERE idskemamma='$xmmaids'";
    $xexeccari = mysqli_query($conn, $xcariddtmma);
    $xtemuddtmma = $xexeccari ? mysqli_num_rows($xexeccari) : 0;
?>
        <div class="card">
            <div class="section-head no-print">
                <div>
                    <h3><i class="fas fa-sitemap" style="color:var(--teal);margin-right:8px"></i>Detail MAPA</h3>
                    <p>Skema: <strong><?php echo e($xmmaskema); ?></strong></p>
                </div>
                <div style="display:flex;gap:10px">
                    <button type="button" class="btn btn-print btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>
            </div>

            <?php if ($xtemuddtmma > 0): ?>
                <?php
                $xarrmma = mysqli_fetch_array($xexeccari);

                $kandidat = explode(",", $xarrmma['kandidat'] ?? '');
                $tujuan = explode(",", $xarrmma['tujuan'] ?? '');
                $linkungan = explode(",", $xarrmma['linkungan'] ?? '');
                $peluang = explode(",", $xarrmma['peluang'] ?? '');
                $hubungan = explode(",", $xarrmma['hubungan'] ?? '');
                $melakukan = explode(",", $xarrmma['melakukan'] ?? '');
                $konfirmasi = explode(",", $xarrmma['konfirmasi'] ?? '');
                $tolakukur = explode(",", $xarrmma['tolakukur'] ?? '');

                $xoreleva = $xarrmma['oreleva'] ?? '';
                $xypenyusun = $xarrmma['penyusun'] ?? '';
                $xyvalid = $xarrmma['valid'] ?? '';

                $xpengurus = "SELECT * FROM pengurus WHERE idpengurus='$xoreleva'";
                $expengurus = mysqli_query($conn, $xpengurus);
                $axpengurus = mysqli_fetch_array($expengurus);
                $namaoreleva = $axpengurus['namapengurus'] ?? '-';
                $ttdoreleva = !empty($axpengurus['ttd']) ? "../imgttd/" . $axpengurus['ttd'] : "";

                $ypengurus = "SELECT * FROM pengurus WHERE idpengurus='$xypenyusun'";
                $eypengurus = mysqli_query($conn, $ypengurus);
                $aypengurus = mysqli_fetch_array($eypengurus);
                $namapenyusun = $aypengurus['namapengurus'] ?? '-';
                $ttdpenyusun = !empty($aypengurus['ttd']) ? "../imgttd/" . $aypengurus['ttd'] : "";

                $vpengurus = "SELECT * FROM pengurus WHERE idpengurus='$xyvalid'";
                $evpengurus = mysqli_query($conn, $vpengurus);
                $avpengurus = mysqli_fetch_array($evpengurus);
                $namavalid = $avpengurus['namapengurus'] ?? '-';
                $ttdvalid = !empty($avpengurus['ttd']) ? "../imgttd/" . $avpengurus['ttd'] : "";
                ?>

                <div class="mapa-shell">
                    <table class="mapa-table">
                        <tr><td colspan="2">Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen.</td></tr>
                        <tr>
                            <td width="36%" rowspan="2" class="mapa-muted"><strong>Skema Sertifikasi (KKNI/Okupasi/Klaster)</strong></td>
                            <td><strong>Judul:</strong> <?php echo e($xmmaskema); ?></td>
                        </tr>
                        <tr><td><strong>Nomor:</strong> <?php echo e($xmmakskema); ?></td></tr>
                        <tr><td colspan="2" class="mapa-head">Menentukan Pendekatan Asesmen</td></tr>
                        <tr>
                            <td class="mapa-muted"><strong>Kandidat</strong></td>
                            <td>
                                <div class="check-list">
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($kandidat, 0); ?> disabled> Hasil pelatihan dan/atau pendidikan</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($kandidat, 1); ?> disabled> Pekerja berpengalaman</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($kandidat, 2); ?> disabled> Pelatihan / belajar mandiri</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mapa-muted"><strong>Tujuan Asesmen</strong></td>
                            <td>
                                <div class="check-list">
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tujuan, 0); ?> disabled> Sertifikasi</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tujuan, 1); ?> disabled> Sertifikasi ulang</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tujuan, 2); ?> disabled> Pengakuan Kompetensi Terkini (PKT)</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tujuan, 3); ?> disabled> Rekognisi Pembelajaran Lampau</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tujuan, 4); ?> disabled> Lainnya</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mapa-muted" rowspan="4"><strong>Bukti yang dikumpulkan</strong></td>
                            <td>
                                <strong>Lingkungan</strong>
                                <div class="check-list">
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($linkungan, 0); ?> disabled> Tempat kerja nyata</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($linkungan, 1); ?> disabled> Tempat kerja simulasi</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Peluang untuk mengumpulkan bukti dalam sejumlah situasi</strong>
                                <div class="check-list">
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($peluang, 0); ?> disabled> Tersedia</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($peluang, 1); ?> disabled> Terbatas</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Hubungan antara standar kompetensi dan:</strong>
                                <div class="check-list">
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($hubungan, 0); ?> disabled> Bukti untuk mendukung asesmen / RPL</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($hubungan, 1); ?> disabled> Aktivitas kerja di tempat kerja Asesi</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($hubungan, 2); ?> disabled> Kegiatan pembelajaran</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Siapa yang melakukan asesmen / RPL</strong>
                                <div class="check-list">
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($melakukan, 0); ?> disabled> Lembaga Sertifikasi</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($melakukan, 1); ?> disabled> Organisasi Pelatihan</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($melakukan, 2); ?> disabled> Asesor Perusahaan</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mapa-muted"><strong>Konfirmasi dengan orang yang relevan</strong></td>
                            <td>
                                <div class="check-list">
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($konfirmasi, 0); ?> disabled> Manajer sertifikasi LSP</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($konfirmasi, 1); ?> disabled> Master Asesor / Master Trainer / Asesor Utama Kompetensi</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($konfirmasi, 2); ?> disabled> Manajer Pelatihan Lembaga Training terakreditasi / terdaftar</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($konfirmasi, 3); ?> disabled> Lainnya</label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="mapa-muted"><strong>Tolok Ukur Asesmen</strong></td>
                            <td>
                                <div class="check-list">
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tolakukur, 0); ?> disabled> Standar Kompetensi</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tolakukur, 1); ?> disabled> Kriteria asesmen dari kurikulum pelatihan</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tolakukur, 2); ?> disabled> Spesifikasi kinerja perusahaan atau industri</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tolakukur, 3); ?> disabled> Spesifikasi Produk</label>
                                    <label class="check-item"><input type="checkbox" <?php echo checked_token($tolakukur, 4); ?> disabled> Pedoman khusus</label>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="sign-grid">
                    <div class="sign-box">
                        <strong>Konfirmasi Orang Relevan</strong>
                        <?php echo e($namaoreleva); ?>
                        <?php if ($ttdoreleva !== ""): ?><br><img src="<?php echo e($ttdoreleva); ?>" alt="TTD Orang Relevan"><?php endif; ?>
                    </div>
                    <div class="sign-box">
                        <strong>Penyusun</strong>
                        <?php echo e($namapenyusun); ?>
                        <?php if ($ttdpenyusun !== ""): ?><br><img src="<?php echo e($ttdpenyusun); ?>" alt="TTD Penyusun"><?php endif; ?>
                    </div>
                    <div class="sign-box">
                        <strong>Validator</strong>
                        <?php echo e($namavalid); ?>
                        <?php if ($ttdvalid !== ""): ?><br><img src="<?php echo e($ttdvalid); ?>" alt="TTD Validator"><?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert-box alert-warning">
                    <i class="fas fa-triangle-exclamation"></i>
                    <strong>MAPA belum tersedia untuk skema ini.</strong>
                </div>
            <?php endif; ?>
        </div>

<?php
else:
    $querysskema = "SELECT * FROM skema ORDER BY namaskema";
    $hasilsskema = mysqli_query($conn, $querysskema);
    $total_skema = $hasilsskema ? mysqli_num_rows($hasilsskema) : 0;
?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-sitemap" style="color:var(--teal);margin-right:8px"></i>Daftar Skema</h3>
                    <p>Pilih skema untuk melihat FR.MAPA.01 Merencanakan Aktivitas dan Proses Asesmen</p>
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
                            <th>ID Skema</th>
                            <th>Kode Skema</th>
                            <th>Nama Skema</th>
                            <th style="width:140px;text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; while ($datasskema = mysqli_fetch_array($hasilsskema)): ?>
                        <?php
                        $idskema = row_value($datasskema, ['id', 'idskema']);
                        $kodeskema = row_value($datasskema, ['noskema', 'kodeskema']);
                        $namaskema = row_value($datasskema, ['namaskema', 'namaskema']);
                        ?>
                        <tr>
                            <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                            <td><span class="badge badge-navy"><?php echo e($idskema); ?></span></td>
                            <td><span class="badge badge-teal"><?php echo e($kodeskema); ?></span></td>
                            <td style="font-weight:600"><?php echo e($namaskema); ?></td>
                            <td style="text-align:center">
                                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=mapa&mmanamaskema=<?php echo e(urlencode($namaskema)); ?>&mmaidskema=<?php echo e($idskema); ?>&mmakodeskema=<?php echo e(urlencode($kodeskema)); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> MAPA
                                </a>
                            </td>
                        </tr>
                    <?php $no++; endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Belum ada data skema.</p>
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
