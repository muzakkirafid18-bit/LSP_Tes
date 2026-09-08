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
<title>Rekap Hasil Tes Asesi - LSP SMKN 1 Cibinong</title>

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
.filter-grid {
    display:grid;
    grid-template-columns:220px minmax(280px, 1fr) auto;
    gap:14px;
    align-items:end;
}
.field label {
    display:block;
    font-size:.78rem;
    font-weight:700;
    color:var(--text-sub);
    margin-bottom:6px;
}
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
.btn {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:10px 20px;
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
.btn-sm { padding:6px 14px; font-size:.78rem; border-radius:8px; }
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
.grade-title td {
    background:#EFF6FF !important;
    color:#1D4ED8;
    font-weight:800;
    border-left:3px solid var(--teal);
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
.summary-line {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:16px;
}
.summary-chip {
    background:var(--off);
    border:1px solid var(--border);
    border-radius:10px;
    padding:8px 12px;
    font-size:.82rem;
    color:var(--text-sub);
}
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
.row-num { font-family:'DM Mono', monospace; color:var(--text-muted); font-size:.75rem; }
@media (max-width:900px) {
    



    .main { margin-left:0; }
    .filter-grid { grid-template-columns:1fr; }
    .section-head { flex-direction:column; align-items:flex-start; }
}
@media print {
    .sidebar, .topbar, .page-footer, .filter-card, .no-print { display:none !important; }
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
        <a href="rekapasesi.php" class="nav-item active"><i class="fas fa-calendar-check"></i> Rekap Hasil Tes</a>

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
            Rekap Hasil Tes Asesi
            <span>Ringkasan nilai terakhir per unit pada tanggal ujian</span>
        </div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div>
            <button class="icon-btn" type="button"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <div class="content">
        <?php
        $tgl_terpilih = $_POST['tgl'] ?? '';
        $asei_terpilih = $_POST['asei'] ?? '';
        ?>
        <div class="card filter-card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-filter" style="color:var(--teal);margin-right:8px"></i>Filter Rekap Nilai</h3>
                    <p>Pilih tanggal ujian dan asesi untuk melihat nilai terbaru tiap unit</p>
                </div>
            </div>

            <form id="formContoh" method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=hasilgrade">
                <input type="hidden" name="idasesorrekap" value="<?php echo e($idasesor); ?>">
                <div class="filter-grid">
                    <div class="field">
                        <label for="tgl">Tanggal Ujian</label>
                        <select id="tgl" name="tgl" class="form-input" required>
                            <?php
                            $tampiltgl = "SELECT DATE(tanggal) AS tgl_saja FROM grade GROUP BY DATE(tanggal) ORDER BY tgl_saja DESC";
                            $exectgl = mysqli_query($conn, $tampiltgl);
                            if ($exectgl && mysqli_num_rows($exectgl) > 0) {
                                while ($rtgl = mysqli_fetch_array($exectgl)) {
                                    $aktif = ($rtgl['tgl_saja'] == $tgl_terpilih) ? "selected" : "";
                                    echo "<option value='".e($rtgl['tgl_saja'])."' $aktif>".e($rtgl['tgl_saja'])."</option>";
                                }
                            } else {
                                echo "<option value=''>Tanggal belum tersedia</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="field">
                        <label for="asei">Asesi</label>
                        <select id="asei" name="asei" class="form-input">
                            <option value="">Semua Asesi</option>
                            <?php
                            $sqlunitsisz = "SELECT DISTINCT pemetaan.idpeserta, pemetaan.idasesor, lsp_usertbl.nama, lsp_usertbl.email
                                            FROM pemetaan
                                            INNER JOIN lsp_usertbl ON pemetaan.idpeserta = lsp_usertbl.id
                                            WHERE pemetaan.idasesor='$idasesor'
                                            ORDER BY lsp_usertbl.nama";
                            $exeunitsisz = mysqli_query($conn, $sqlunitsisz);
                            while ($rasei = mysqli_fetch_array($exeunitsisz)) {
                                $aktif_asei = ($rasei['email'] == $asei_terpilih) ? "selected" : "";
                                echo "<option value='".e($rasei['email'])."' $aktif_asei>".e($rasei['nama'])." - ".e($rasei['email'])."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Lanjutkan
                    </button>
                </div>
            </form>
        </div>

<?php if ($op == "hasilgrade"): ?>
        <?php
        $tgl = $_POST['tgl'] ?? '';
        $asei = $_POST['asei'] ?? '';
        $idasesorrekap = $_POST['idasesorrekap'] ?? $idasesor;

        $sqlunitsisgrade = "SELECT DISTINCT pemetaan.idpeserta, pemetaan.idasesor, lsp_usertbl.nama, lsp_usertbl.email
                            FROM pemetaan
                            INNER JOIN lsp_usertbl ON pemetaan.idpeserta = lsp_usertbl.id
                            WHERE pemetaan.idasesor='$idasesorrekap'";
        if ($asei != '') {
            $asei_safe = mysqli_real_escape_string($conn, $asei);
            $sqlunitsisgrade .= " AND lsp_usertbl.email='$asei_safe'";
        }
        $sqlunitsisgrade .= " ORDER BY lsp_usertbl.nama";
        $exeunitsisgrade = mysqli_query($conn, $sqlunitsisgrade);
        $total_asesi = $exeunitsisgrade ? mysqli_num_rows($exeunitsisgrade) : 0;
        ?>
        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-table" style="color:var(--teal);margin-right:8px"></i>Hasil Rekap Tes</h3>
                    <p>Tanggal: <strong><?php echo e($tgl); ?></strong> · <?php echo e($total_asesi); ?> asesi</p>
                </div>
                <button type="button" class="btn btn-secondary btn-sm no-print" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>

            <div class="summary-line">
                <div class="summary-chip"><strong>Ambang Kompeten:</strong> nilai 75</div>
                <div class="summary-chip"><strong>Data:</strong> nilai ujian terakhir per modul/unit</div>
            </div>

            <div class="tbl-wrap">
                <table class="tbl">
                    <tbody>
                    <?php if ($exeunitsisgrade && mysqli_num_rows($exeunitsisgrade) > 0): ?>
                        <?php while ($rowunitsisgrade = mysqli_fetch_array($exeunitsisgrade)): ?>
                            <?php
                            $namagrade = $rowunitsisgrade['nama'];
                            $emailgrade = trim($rowunitsisgrade['email']);
                            ?>
                            <tr class="grade-title">
                                <td colspan="6">
                                    <i class="fas fa-user" style="margin-right:8px"></i>
                                    NAMA ASESI: <?php echo e($namagrade); ?>
                                    <span style="color:var(--text-sub);font-size:0.85em;font-weight:400;margin-left:8px">(<?php echo e($emailgrade); ?>)</span>
                                </td>
                            </tr>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Kode Unit</th>
                                <th>Nama Unit</th>
                                <th>Hasil Tes</th>
                                <th>Keterangan</th>
                                <th>Total Sementara</th>
                            </tr>
                            <?php
                            $emailgrade_safe = mysqli_real_escape_string($conn, $emailgrade);
                            $namagrade_safe = mysqli_real_escape_string($conn, $namagrade);
                            $tgl_safe = mysqli_real_escape_string($conn, $tgl);
                            $sqlgrade = "SELECT grade.nim, grade.kd_modul, grade.grade, grade.ujianke, DATE(grade.tanggal) AS tgl_saja, COALESCE(modul.modul, grade.kd_modul) AS namaalias, grade.kd_modul AS kdalias
                                         FROM grade
                                         LEFT JOIN modul ON grade.kd_modul = modul.kd_modul
                                         WHERE (grade.nim='$emailgrade_safe' OR grade.nim='$namagrade_safe')
                                         AND DATE(grade.tanggal)='$tgl_safe'
                                         AND grade.ujianke = (
                                             SELECT MAX(g2.ujianke)
                                             FROM grade g2
                                             WHERE g2.nim=grade.nim
                                             AND g2.kd_modul=grade.kd_modul
                                             AND DATE(g2.tanggal)='$tgl_safe'
                                         )
                                         ORDER BY grade.kd_modul";
                            $exegrade = mysqli_query($conn, $sqlgrade);
                            $totalgrade = 0;
                            $hit = 0;
                            ?>
                            <?php while ($rowgrade = mysqli_fetch_array($exegrade)): ?>
                                <?php
                                $hit++;
                                $nilai = (float)$rowgrade['grade'];
                                $kompeten = $nilai >= 75;
                                $ket = $kompeten ? "Kompeten" : "Belum Kompeten";
                                $badge = $kompeten ? "badge-green" : "badge-red";
                                $totalgrade += $nilai;
                                ?>
                                <tr>
                                    <td class="row-num"><?php echo e(str_pad($hit, 2, '0', STR_PAD_LEFT)); ?></td>
                                    <td><span class="badge badge-teal"><?php echo e($rowgrade['kdalias']); ?></span></td>
                                    <td style="font-weight:500"><?php echo e($rowgrade['namaalias']); ?></td>
                                    <td><strong><?php echo e($rowgrade['grade']); ?></strong></td>
                                    <td><span class="badge <?php echo e($badge); ?>"><?php echo e($ket); ?></span></td>
                                    <td><?php echo e($totalgrade); ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if ($hit > 0): ?>
                                <?php $rata_rata = round(($totalgrade / $hit), 2); ?>
                                <tr>
                                    <td colspan="3"><strong>Rata-Rata</strong></td>
                                    <td colspan="3">
                                        <strong><?php echo e($totalgrade); ?> / <?php echo e($hit); ?> = <?php echo e($rata_rata); ?></strong>
                                        <span class="badge <?php echo ($rata_rata >= 75) ? 'badge-green' : 'badge-red'; ?>" style="margin-left:8px">
                                            <?php echo ($rata_rata >= 75) ? 'Kompeten' : 'Belum Kompeten'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state" style="padding:24px">
                                            <i class="fas fa-circle-info"></i>
                                            <p>Belum ada data nilai, atau kode unit di `unitalias` tidak cocok dengan `kd_modul` pada tabel `grade`.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td>
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <p>Asesi tidak ditemukan untuk filter ini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
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
