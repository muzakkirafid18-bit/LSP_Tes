<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "../lsp_koneksi.php";

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }
function checked_if($a, $b) { return ((string)$a === (string)$b) ? "checked" : ""; }
function row_value($row, $keys, $default = '') {
    foreach ($keys as $key) {
        if (isset($row[$key]) && $row[$key] !== '') return $row[$key];
    }
    return $default;
}
function esc_sql($conn, $value) { return mysqli_real_escape_string($conn, (string)$value); }

$today = date('d F Y');
$current_time = date('H:i');

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'><h3>Anda Harus Login Dahulu!</h3><a href='../lsp_login.php'>Kembali ke Login</a></div>";
    exit;
}
if ($_SESSION['level'] != 'asesor') {
    echo "<style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'><h3>Anda Tidak Punya Hak Akses!</h3><a href='../lsp_login.php'>Kembali ke Login</a></div>";
    exit;
}

$uname = $_SESSION['username'] ?? '';
$qUser = "SELECT * FROM lsp_usertbl WHERE email='$uname'";
$rUser = mysqli_query($conn, $qUser);
$dUser = mysqli_fetch_array($rUser);
$namax = $dUser['nama'] ?? 'Asesor';
$idasesor = $dUser['id'] ?? ($dUser['id'] ?? ($_SESSION['id_user'] ?? ''));
$ttdfrm = $dUser['linkttd'] ?? '';
$op = $_REQUEST['op'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.IA.11 Ceklist Instrumen Asesmen - LSP SMKN 1 Cibinong</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--red:#EF4444;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{font-size:15px;scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}




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
.sidebar-logo {padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px}
.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,.1)}
.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text{line-height:1.2}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:700}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:500;letter-spacing:.5px}
.sidebar-nav{padding:16px 12px;flex:1}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}
.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.875rem;font-weight:500;transition:all .2s;margin-bottom:2px}
.nav-item:hover{background:rgba(255,255,255,.07);color:#fff;text-decoration:none}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}
.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07)}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}
.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;flex-shrink:0}.user-info strong{display:block;color:#fff;font-size:.82rem}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;font-size:.85rem}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}
.topbar-title{font-size:1.1rem;font-weight:700;flex:1}.topbar-title span{color:var(--text-sub);font-weight:400;font-size:.875rem;margin-left:8px}.topbar-actions{display:flex;align-items:center;gap:10px}.icon-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-sub);font-size:.9rem}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:600;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.card{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);animation:fadeUp .4s ease both}@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.section-head{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:700}.section-head p{font-size:.78rem;color:var(--text-sub);margin-top:2px}
.alert-box{padding:12px 18px;border-radius:10px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:10px;margin-bottom:20px}.alert-success{background:#DCFCE7;color:#15803D;border:1px solid #86EFAC}.alert-warning{background:#FEFCE8;color:#A16207;border:1px solid #FDE68A}.alert-error{background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5}
.tbl-wrap{overflow-x:auto}.tbl,.unit-tbl{width:100%;border-collapse:collapse}.tbl th,.unit-tbl th{text-align:left;padding:12px 16px;font-size:.72rem;font-weight:700;letter-spacing:.7px;text-transform:uppercase;color:rgba(255,255,255,.78);background:var(--navy)}.tbl th:first-child,.unit-tbl th:first-child{border-radius:8px 0 0 8px}.tbl th:last-child,.unit-tbl th:last-child{border-radius:0 8px 8px 0}.tbl td,.unit-tbl td{padding:13px 16px;font-size:.845rem;border-bottom:1px solid var(--off);vertical-align:middle}.tbl tbody tr:hover td,.unit-tbl tbody tr:hover td{background:#F0F9F9}
.badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:6px;font-size:.72rem;font-weight:700}.badge-green{background:#DCFCE7;color:#15803D}.badge-red{background:#FEF2F2;color:#B91C1C}.badge-teal{background:var(--teal-light);color:var(--teal-dark)}.badge-navy{background:#EFF6FF;color:#1D4ED8}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:9px 20px;border-radius:10px;font-size:.85rem;font-weight:600;cursor:pointer;text-decoration:none;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif}.btn:hover{text-decoration:none}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3)}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}.btn-success{background:#22C55E;color:#fff;border-color:#22C55E}.btn-print{background:var(--navy);color:#fff;border-color:var(--navy)}.btn-sm{padding:6px 14px;font-size:.78rem;border-radius:8px}
.form-grid{display:grid;grid-template-columns:180px 1fr;gap:14px;align-items:start;margin-bottom:18px}.form-label{font-size:.82rem;font-weight:600;color:var(--text-sub);padding-top:10px}.form-input{width:100%;font-size:.875rem;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-main);background:var(--off);outline:none}.form-input:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-glow);background:#fff}.form-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:8px;padding-top:16px;border-top:1px solid var(--border)}
.row-num{font-family:'DM Mono',monospace;color:var(--text-muted);font-size:.75rem}.empty-state{text-align:center;padding:48px 24px;color:var(--text-muted)}.empty-state i{font-size:2.5rem;margin-bottom:12px;opacity:.4;display:block}.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
.doc-table{width:100%;border-collapse:collapse;margin-bottom:16px;font-family:Arial,sans-serif;color:#000}.doc-table td,.doc-table th{border:1px solid #000;padding:8px 10px;vertical-align:top;font-size:13px;line-height:1.45}.doc-muted{background:#f2f2f2;-webkit-print-color-adjust:exact;print-color-adjust:exact}.doc-head{background:#0F2A3A;color:#fff;font-weight:bold}.doc-input,.doc-textarea{width:100%;border:1px solid #cbd5e1;border-radius:6px;padding:7px 9px;font:inherit}.doc-textarea{min-height:74px;resize:vertical}.radio-cell{text-align:center;vertical-align:middle!important}.ttd-img{max-height:64px;border:1px solid var(--border);border-radius:8px;padding:4px;background:#fff}
@media(max-width:900px){


.main{margin-left:0}.form-grid{grid-template-columns:1fr}.section-head{flex-direction:column;align-items:flex-start}}@media print{.sidebar,.topbar,.page-footer,.no-print,.form-actions{display:none!important}.main{margin-left:0}.content{padding:0}.card{box-shadow:none;border:0;padding:0}body{background:#fff;color:#000}.doc-input,.doc-textarea{border:none!important;background:transparent!important}}
</style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo"><div class="logo-box"><img src="../images/lsplogosmkn1.png" alt="Logo LSP"></div><div class="logo-text"><strong>LSP</strong><span>SMKN 1 CIBINONG</span></div></div>
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
        <a href="pihakketiga.php" class="nav-item"><i class="fas fa-users"></i> FR.IA.10 Pihak Ketiga</a>
        <a href="ceklistintrumen.php" class="nav-item active"><i class="fas fa-clipboard-list"></i> FR.IA.11 Ceklist Instrumen</a>
        <div class="nav-label">Akun</div>
        <a href="tandatanganass.php" class="nav-item"><i class="fas fa-signature"></i> Tanda Tangan</a>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card"><div class="user-avatar"><?php echo e(strtoupper(substr($namax,0,2))); ?></div><div class="user-info"><strong><?php echo e($namax); ?></strong><span>Asesor LSP</span></div><button class="btn-logout" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button></div></div>
</aside>
<div class="main">
<header class="topbar"><div class="topbar-title">FR.IA.11 Ceklist Instrumen Asesmen <span>Meninjau kesiapan dan kualitas instrumen</span></div><div class="topbar-actions"><div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div><button class="icon-btn" type="button"><i class="fas fa-bell"></i></button></div></header>
<div class="content">

<?php
if ($op == "pilihtglceklist"):
    $idasesorckl = $_GET['idasesor'] ?? $idasesor;
    $idskemackl = $_GET['idskema'] ?? '';
    $kelompokckl = $_GET['kelompok'] ?? '';
?>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-calendar-days" style="color:var(--teal);margin-right:8px"></i>Pilih Tanggal</h3><p>Pilih tanggal asesmen untuk menampilkan peserta ceklist instrumen</p></div><a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a></div>
    <form method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpesertackl">
        <input type="hidden" name="kelompokckl" value="<?php echo e($kelompokckl); ?>">
        <input type="hidden" name="idskemackl" value="<?php echo e($idskemackl); ?>">
        <input type="hidden" name="idasesorckl" value="<?php echo e($idasesorckl); ?>">
        <div class="form-grid"><div class="form-label">Tanggal</div><select id="tglckl" name="tglckl" class="form-input" required>
        <?php
        $namaassackl = $namax;
        $tampiltglckl = "SELECT tanggal, namaasesor FROM pemetaan WHERE kelompok='$kelompokckl' AND idskema='$idskemackl' AND idasesor='$idasesorckl' GROUP BY tanggal, namaasesor";
        $exectglckl = mysqli_query($conn, $tampiltglckl);
        if ($exectglckl && mysqli_num_rows($exectglckl) > 0) {
            while ($rtglckl = mysqli_fetch_array($exectglckl)) {
                $namaassackl = $rtglckl['namaasesor'] ?? $namax;
                echo "<option value='".e($rtglckl['tanggal'])."'>".e($rtglckl['tanggal'])."</option>";
            }
        } else echo "<option value=''>Tanggal tidak ditemukan</option>";
        ?>
        </select></div>
        <input type="hidden" name="namasportockl" value="<?php echo e($namaassackl); ?>">
        <div class="form-actions"><button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lanjutkan</button></div>
    </form>
</div>

<?php
elseif ($op == "listpesertackl"):
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['ckl_idasesor'] = $_POST['idasesorckl'] ?? '';
        $_SESSION['ckl_idskema'] = $_POST['idskemackl'] ?? '';
        $_SESSION['ckl_kelompok'] = $_POST['kelompokckl'] ?? '';
        $_SESSION['ckl_tgl'] = $_POST['tglckl'] ?? '';
        $_SESSION['ckl_namaasesor'] = $_POST['namasportockl'] ?? '';
        header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpesertackl");
        exit;
    }
    $idasesorckll = $_SESSION['ckl_idasesor'] ?? '';
    $idskemackll = $_SESSION['ckl_idskema'] ?? '';
    $kelompokckll = $_SESSION['ckl_kelompok'] ?? '';
    $tglckll = $_SESSION['ckl_tgl'] ?? '';
    $namaasesorckll = $_SESSION['ckl_namaasesor'] ?? $namax;
    $sslckll = "SELECT * FROM pemetaan WHERE kelompok='$kelompokckll' AND idskema='$idskemackll' AND tanggal='$tglckll' AND idasesor='$idasesorckll'";
    $exec0ckll = mysqli_query($conn, $sslckll);
    $total = $exec0ckll ? mysqli_num_rows($exec0ckll) : 0;
?>
<div class="card">
    <?php if (!empty($_SESSION['flash_success'])): ?><div class="alert-box alert-success alert-auto-hide"><i class="fas fa-circle-check"></i><strong><?php echo e($_SESSION['flash_success']); ?></strong></div><?php unset($_SESSION['flash_success']); endif; ?>
    <div class="section-head"><div><h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Peserta Ceklist Instrumen</h3><p>Tanggal: <strong><?php echo e($tglckll); ?></strong> · <?php echo e($total); ?> peserta</p></div><a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a></div>
    <div class="tbl-wrap"><table class="tbl"><thead><tr><th style="width:50px">No</th><th>ID Asesi</th><th>Nama Asesi</th><th>Tanggal</th><th style="width:170px;text-align:center">Aksi</th></tr></thead><tbody>
    <?php if ($exec0ckll && mysqli_num_rows($exec0ckll) > 0): $no=1; while($hasil0ckll = mysqli_fetch_array($exec0ckll)): 
        $idpeserta_ckl = $hasil0ckll['idpeserta'];
        $qp_ckl = mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE id='$idpeserta_ckl' LIMIT 1");
        $dp_ckl = $qp_ckl ? mysqli_fetch_array($qp_ckl) : array();
        $nama_asesi_ckl = $dp_ckl['nama'] ?? ($hasil0ckll['namapeserta'] ?? 'Asesi');
    ?>
        <tr><td class="row-num"><?php echo e(str_pad($no,2,'0',STR_PAD_LEFT)); ?></td><td><span class="badge badge-navy"><?php echo e($hasil0ckll['idpeserta']); ?></span></td><td style="font-weight:600"><?php echo e($nama_asesi_ckl); ?></td><td style="color:var(--text-sub)"><?php echo e($hasil0ckll['tanggal']); ?></td><td style="text-align:center"><a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=cklob&idasesor=<?php echo e($hasil0ckll['idasesor']); ?>&kelompok=<?php echo e(urlencode($kelompokckll)); ?>&tgl=<?php echo e(urlencode($hasil0ckll['tanggal'])); ?>&idasesi=<?php echo e($hasil0ckll['idpeserta']); ?>&idskema=<?php echo e($idskemackll); ?>&nmass=<?php echo e(urlencode($namaasesorckll)); ?>" class="btn btn-primary btn-sm"><i class="fas fa-list"></i> Tampilkan Unit</a></td></tr>
    <?php $no++; endwhile; else: ?><tr><td colspan="5"><div class="empty-state"><i class="fas fa-users-slash"></i><p>Peserta tidak ditemukan.</p></div></td></tr><?php endif; ?>
    </tbody></table></div>
</div>

<?php
elseif ($op == "cklob"):
    $idskemacklll = $_GET['idskema'] ?? '';
    $idasesicklll = $_GET['idasesi'] ?? '';
    $idasesorcklll = $_GET['idasesor'] ?? $idasesor;
    $tglcklll = $_GET['tgl'] ?? '';
    $kelompokcklll = $_GET['kelompok'] ?? '';
    $namasesorcklll = $_GET['nmass'] ?? $namax;
    $sqlskemackl = "SELECT * FROM skema WHERE idskema='$idskemacklll' LIMIT 1";
    $execskemackl = mysqli_query($conn, $sqlskemackl);
    $listskemackl = mysqli_fetch_array($execskemackl);
    $namaskemackl = row_value($listskemackl, ['namaskema','namaskema']);
    $kodeskemackl = row_value($listskemackl, ['noskema','kodeskema']);
    $sqladsesickl = "SELECT * FROM lsp_usertbl WHERE id='$idasesicklll' OR id='$idasesicklll' LIMIT 1";
    $execadsesickl = mysqli_query($conn, $sqladsesickl);
    $listadsesickl = mysqli_fetch_array($execadsesickl);
    $namaadsesickl = $listadsesickl['nama'] ?? '-';
    $sqlunitzckl = "SELECT unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit FROM unitsiswa INNER JOIN unit ON unitsiswa.idunit=unit.idunit WHERE unitsiswa.idskema='$idskemacklll' AND unitsiswa.idadsesi='$idasesicklll'";
    $execunitzckl = mysqli_query($conn, $sqlunitzckl);
    $totalunit = $execunitzckl ? mysqli_num_rows($execunitzckl) : 0;
?>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-list-check" style="color:var(--teal);margin-right:8px"></i>Daftar Unit Ceklist Instrumen</h3><p>Asesi: <strong><?php echo e($namaadsesickl); ?></strong> · Skema: <?php echo e($namaskemackl); ?> · <?php echo e($totalunit); ?> unit</p></div><a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpesertackl" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a></div>
    <div class="tbl-wrap"><table class="unit-tbl"><thead><tr><th style="width:50px">No</th><th>ID Asesi</th><th>Kode Unit</th><th>Nama Unit</th><th>Status Observasi</th><th style="width:170px;text-align:center">Aksi</th></tr></thead><tbody>
    <?php if ($execunitzckl && mysqli_num_rows($execunitzckl)>0): $no=1; while($daftackl=mysqli_fetch_array($execunitzckl)): 
        $cekckl = "SELECT idunit,idadsesi FROM rekappraktek WHERE idunit='".$daftackl['idunit']."' AND idadsesi='".$daftackl['idadsesi']."' GROUP BY idunit,idadsesi";
        $cekckll = mysqli_query($conn, $cekckl);
        $gckl = $cekckll ? mysqli_num_rows($cekckll) : 0;
        $ketockl = ($gckl>0) ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Pernah observasi</span>' : '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Belum observasi</span>';
    ?>
        <tr><td class="row-num"><?php echo e(str_pad($no,2,'0',STR_PAD_LEFT)); ?></td><td><?php echo e($daftackl['idadsesi']); ?></td><td><span class="badge badge-teal"><?php echo e($daftackl['kodeunit']); ?></span></td><td style="font-weight:500"><?php echo e($daftackl['namaunit']); ?></td><td><?php echo $ketockl; ?></td><td style="text-align:center"><a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=frmpceklis&kode=in&kou=<?php echo e(urlencode($daftackl['kodeunit'])); ?>&lttd=<?php echo e(urlencode($ttdfrm)); ?>&namaunit=<?php echo e(urlencode($daftackl['namaunit'])); ?>&idunit=<?php echo e($daftackl['idunit']); ?>&idass=<?php echo e($idasesorcklll); ?>&namaskema=<?php echo e(urlencode($namaskemackl)); ?>&kodeskema=<?php echo e(urlencode($kodeskemackl)); ?>&nmasesi=<?php echo e(urlencode($namaadsesickl)); ?>&k=<?php echo e(urlencode($kelompokcklll)); ?>&tgl=<?php echo e(urlencode($tglcklll)); ?>&idasesi=<?php echo e($daftackl['idadsesi']); ?>&nmasesor=<?php echo e(urlencode($namasesorcklll)); ?>&idskema=<?php echo e($daftackl['idskema']); ?>" class="btn btn-primary btn-sm"><i class="fas fa-clipboard-list"></i> Formulir</a></td></tr>
    <?php $no++; endwhile; else: ?><tr><td colspan="6"><div class="empty-state"><i class="fas fa-folder-open"></i><p>Unit belum tersedia.</p></div></td></tr><?php endif; ?>
    </tbody></table></div>
</div>

<?php
elseif ($op == "frmpceklis"):
    $namaasesifrmckl = $_GET['nmasesi'] ?? '';
    $namaskemafrmckl = $_GET['namaskema'] ?? '';
    $kodeskemafrmckl = $_GET['kodeskema'] ?? '';
    $kodeunitfrmckl = $_GET['kou'] ?? '';
    $namaunitfrmckl = $_GET['namaunit'] ?? '';
    $idasesifrmckl = $_GET['idasesi'] ?? '';
    $idasesorfrmckl = $_GET['idass'] ?? $idasesor;
    $idunitfrmckl = $_GET['idunit'] ?? '';
    $tglfrmckl = $_GET['tgl'] ?? '';
    $idskemafrmckl = $_GET['idskema'] ?? '';
    $namaasesorfrmckl = $_GET['nmasesor'] ?? $namax;
    $linkttdfrmckl = !empty($_GET['lttd']) ? "../imgttd/" . $_GET['lttd'] : "";
    $cekdatapckl = "SELECT * FROM cekinstrumen WHERE idskemacek='$idskemafrmckl' AND idasesorcek='$idasesorfrmckl' AND idasesicek='$idasesifrmckl' AND idunitcek='$idunitfrmckl'";
    $adapckl = mysqli_query($conn, $cekdatapckl);
    $datapcklfrm = ($adapckl && mysqli_num_rows($adapckl)>0) ? mysqli_fetch_array($adapckl) : array();
    $pecahjawabckl = explode(",", $datapcklfrm['jawabancek'] ?? '');
    $tglckl = $datapcklfrm['tglcek'] ?? date("Y-m-d");
    $komencklfrm = $datapcklfrm['komencek'] ?? '';
?>
<div class="card">
    <div class="section-head no-print"><div><h3><i class="fas fa-clipboard-list" style="color:var(--teal);margin-right:8px"></i>Formulir Ceklist Instrumen Asesmen</h3><p>Asesi: <strong><?php echo e($namaasesifrmckl); ?></strong> · Unit: <?php echo e($kodeunitfrmckl); ?></p></div><div style="display:flex;gap:10px"><button onclick="window.print()" class="btn btn-print btn-sm" type="button"><i class="fas fa-print"></i> Cetak</button><a href="javascript:history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a></div></div>
    <form id="ckl" name="ckl" method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=postcklfrm">
        <input type="hidden" name="idasesorformckl" value="<?php echo e($idasesorfrmckl); ?>">
        <input type="hidden" name="idadsesiformckl" value="<?php echo e($idasesifrmckl); ?>">
        <input type="hidden" name="idskemaformckl" value="<?php echo e($idskemafrmckl); ?>">
        <input type="hidden" name="idunitformckl" value="<?php echo e($idunitfrmckl); ?>">
        <table class="doc-table">
            <tr><td colspan="5"><strong>Skema Sertifikasi:</strong> <?php echo e($namaskemafrmckl); ?><br><strong>No. Skema Sertifikasi:</strong> <?php echo e($kodeskemafrmckl); ?></td></tr>
            <tr class="doc-muted"><td colspan="5"><strong>Nama Asesi:</strong> <?php echo e($namaasesifrmckl); ?><br><strong>Nama Asesor:</strong> <?php echo e($namaasesorfrmckl); ?><br><strong>Tanggal Asesmen:</strong> <?php echo e($tglfrmckl); ?></td></tr>
            <tr><td colspan="5" class="doc-head">Panduan Bagi Asesor</td></tr>
            <tr><td colspan="5">Isilah tabel ini sesuai informasi pada pertanyaan/pernyataan di bawah. Beri tanda pada hasil penilaian instrumen asesmen berdasarkan tinjauan dan justifikasi profesional Anda.</td></tr>
            <tr><td rowspan="2" class="doc-muted"><strong>Uji Kompetensi</strong></td><td class="doc-muted"><strong>Kode Unit</strong></td><td colspan="3"><?php echo e($kodeunitfrmckl); ?></td></tr>
            <tr><td class="doc-muted"><strong>Judul Unit</strong></td><td colspan="3"><?php echo e($namaunitfrmckl); ?></td></tr>
            <tr><th colspan="3">Kegiatan Asesmen</th><th>Ya</th><th>Tidak</th></tr>
            <?php
            $questions = array(
                array('ins', 'Instruksi perangkat asesmen dan kondisi asesmen diidentifikasi dengan jelas'),
                array('inf', 'Informasi tertulis dituliskan secara tepat'),
                array('keg', 'Kegiatan asesmen membahas persyaratan bukti untuk kompetensi yang diases'),
                array('tin', 'Tingkat kesulitan bahasa, literasi, dan berhitung sesuai dengan tingkat unit kompetensi yang dinilai'),
                array('ting', 'Tingkat kesulitan kegiatan sesuai dengan kompetensi yang diases'),
                array('con', 'Contoh, benchmark dan/atau ceklis asesmen tersedia untuk digunakan dalam pengambilan keputusan asesmen'),
                array('dip', 'Diperlukan modifikasi seperti yang diidentifikasi dalam komentar'),
                array('tug', 'Tugas asesmen siap digunakan')
            );
            foreach ($questions as $idx => $q):
            ?>
            <tr><td colspan="3"><strong><?php echo e($q[1]); ?></strong></td><td class="radio-cell"><input type="radio" name="<?php echo e($q[0]); ?>" value="Y" <?php echo checked_if($pecahjawabckl[$idx] ?? '', 'Y'); ?>></td><td class="radio-cell"><input type="radio" name="<?php echo e($q[0]); ?>" value="T" <?php echo checked_if($pecahjawabckl[$idx] ?? '', 'T'); ?>></td></tr>
            <?php endforeach; ?>
            <tr><td colspan="2">Nama Asesor<br><strong><?php echo e($namaasesorfrmckl); ?></strong></td><td>Tanggal:<input type="date" name="tglckl" class="doc-input" value="<?php echo e($tglckl); ?>"><?php if($linkttdfrmckl): ?><br><img src="<?php echo e($linkttdfrmckl); ?>" class="ttd-img" alt="TTD Asesor"><?php endif; ?></td><td colspan="2"><strong>Komentar:</strong><textarea name="komenckl" class="doc-textarea"><?php echo e($komencklfrm); ?></textarea></td></tr>
        </table>
        <div class="form-actions"><button type="submit" name="simpan" class="btn btn-success"><i class="fas fa-floppy-disk"></i> Simpan</button></div>
    </form>
</div>

<?php
elseif ($op == "postcklfrm"):
    $idskemapockl = $_POST['idskemaformckl'] ?? '';
    $idasesipockl = $_POST['idadsesiformckl'] ?? '';
    $idasesorpockl = $_POST['idasesorformckl'] ?? '';
    $idunitpockl = $_POST['idunitformckl'] ?? '';
    $tglpockl = $_POST['tglckl'] ?? date('Y-m-d');
    $komenpockl = esc_sql($conn, $_POST['komenckl'] ?? '');
    $sukses = 0; $gagal = 0; $sup = 0;
    $required = array('ins','inf','keg','tin','ting','con','dip','tug');
    $complete = true;
    foreach($required as $name){ if(!isset($_POST[$name])) $complete = false; }
    if($complete){
        $ketcklfrm = $_POST['ins'].",".$_POST['inf'].",".$_POST['keg'].",".$_POST['tin'].",".$_POST['ting'].",".$_POST['con'].",".$_POST['dip'].",".$_POST['tug'];
        $cekdatackl = "SELECT * FROM cekinstrumen WHERE idskemacek='$idskemapockl' AND idasesorcek='$idasesorpockl' AND idasesicek='$idasesipockl' AND idunitcek='$idunitpockl'";
        $adackl = mysqli_query($conn, $cekdatackl);
        $adakckl = $adackl ? mysqli_num_rows($adackl) : 0;
        if($adakckl > 0){
            $ssqlcklu = "UPDATE cekinstrumen SET jawabancek='$ketcklfrm', tglcek='$tglpockl', komencek='$komenpockl' WHERE idskemacek='$idskemapockl' AND idasesorcek='$idasesorpockl' AND idasesicek='$idasesipockl' AND idunitcek='$idunitpockl'";
            if(mysqli_query($conn, $ssqlcklu)) $sup++; else $gagal++;
        } else {
            $ssqlpockl = "INSERT INTO cekinstrumen (idskemacek, idasesorcek, idasesicek, idunitcek, jawabancek, tglcek, komencek) VALUES ('$idskemapockl', '$idasesorpockl', '$idasesipockl', '$idunitpockl', '$ketcklfrm', '$tglpockl', '$komenpockl')";
            if(mysqli_query($conn, $ssqlpockl)) $sukses++; else $gagal++;
        }
    } else $gagal++;
?>
<div class="card"><div class="section-head"><div><h3><i class="fas fa-circle-check" style="color:var(--teal);margin-right:8px"></i>Hasil Simpan Ceklist Instrumen</h3><p>Data FR.IA.11 telah diproses.</p></div></div><div class="alert-box <?php echo ($gagal>0 && $sukses==0 && $sup==0)?'alert-error':'alert-success'; ?>"><i class="fas fa-circle-check"></i><div><strong>Proses selesai.</strong><p style="margin-top:2px;font-size:.8rem">Simpan baru: <?php echo e($sukses); ?> · Update: <?php echo e($sup); ?> · Gagal: <?php echo e($gagal); ?></p></div></div><a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali ke Jadwal</a></div>

<?php
else:
    $queryceklist = "SELECT kelompok, idskema, idasesor FROM pemetaan WHERE idasesor='$idasesor' GROUP BY kelompok,idskema,idasesor";
    $hasilceklist = mysqli_query($conn, $queryceklist);
    $total_jadwal = $hasilceklist ? mysqli_num_rows($hasilceklist) : 0;
?>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-clipboard-list" style="color:var(--teal);margin-right:8px"></i>Daftar Jadwal Ceklist Instrumen</h3><p>Pilih jadwal untuk membuka FR.IA.11 Ceklist Instrumen Asesmen</p></div><span style="background:var(--teal-light);color:var(--teal-dark);padding:4px 14px;border-radius:6px;font-size:.78rem;font-weight:700"><?php echo e($total_jadwal); ?> Jadwal</span></div>
    <?php if($total_jadwal > 0): ?><div class="tbl-wrap"><table class="tbl"><thead><tr><th style="width:50px">No</th><th>Paket</th><th>Skema</th><th>Nama Skema</th><th style="width:190px;text-align:center">Aksi</th></tr></thead><tbody>
    <?php $no=1; while($dataceklist=mysqli_fetch_array($hasilceklist)):
        $id_skema = $dataceklist['idskema'];
        $ssqlceklist = "SELECT * FROM skema WHERE idskema='$id_skema' LIMIT 1";
        $execssqlceklist = mysqli_query($conn, $ssqlceklist);
        $barisceklist = mysqli_fetch_array($execssqlceklist);
        $namaskemaceklist = row_value($barisceklist, ['namaskema','namaskema'], 'Nama Skema Tidak Ditemukan');
    ?>
    <tr><td class="row-num"><?php echo e(str_pad($no,2,'0',STR_PAD_LEFT)); ?></td><td><span class="badge badge-navy"><?php echo e($dataceklist['kelompok']); ?></span></td><td><span class="badge badge-teal"><?php echo e($dataceklist['idskema']); ?></span></td><td style="font-weight:600"><?php echo e($namaskemaceklist); ?></td><td style="text-align:center"><a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=pilihtglceklist&idasesor=<?php echo e($dataceklist['idasesor']); ?>&idskema=<?php echo e($dataceklist['idskema']); ?>&kelompok=<?php echo e(urlencode($dataceklist['kelompok'])); ?>" class="btn btn-primary btn-sm"><i class="fas fa-users"></i> Tampilkan Peserta</a></td></tr>
    <?php $no++; endwhile; ?></tbody></table></div><?php else: ?><div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i><strong>Belum ada jadwal ceklist instrumen untuk asesor ini.</strong></div><?php endif; ?>
</div>
<?php endif; ?>
</div>
<footer class="page-footer"><span>© <?php echo date('Y'); ?> <strong>LSP SMKN 1 Cibinong</strong>. Semua hak dilindungi.</span><span>Versi 1.0.0 · <?php echo e($today); ?>, <?php echo e($current_time); ?> WIB</span></footer>
</div>
</body>
</html>
<?php ob_end_flush(); ?>
