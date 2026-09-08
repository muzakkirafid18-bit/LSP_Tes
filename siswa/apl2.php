<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "../lsp_koneksi.php";

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }
function esc_sql($conn, $value) { return mysqli_real_escape_string($conn, (string)$value); }
function initials($name) { $name = trim((string)$name); return $name === '' ? 'AS' : strtoupper(substr($name, 0, 2)); }
function checked_if($a, $b) { return ((string)$a === (string)$b) ? 'checked' : ''; }

$today = date('d F Y');
$current_time = date('H:i');

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'><h3>Anda Harus Login Dahulu!</h3><a href='../lsp_login.php'>Kembali ke Login</a></div>";
    exit;
}

$login_user = $_SESSION['username'] ?? '';
$uidpes = $_GET['uidpes'] ?? $login_user;
if ($uidpes === '') $uidpes = $login_user;
$uidpes_safe = esc_sql($conn, $uidpes);

$qUser = "SELECT * FROM users WHERE username='$uidpes_safe' LIMIT 1";
$rUser = mysqli_query($conn, $qUser);
$dUser = $rUser ? mysqli_fetch_array($rUser) : array();
if (!$dUser) {
    $qUserAlt = "SELECT * FROM lsp_usertbl WHERE email='$uidpes_safe' LIMIT 1";
    $rUserAlt = mysqli_query($conn, $qUserAlt);
    $dUser = $rUserAlt ? mysqli_fetch_array($rUserAlt) : array();
}

$namaasesi = $dUser['nama'] ?? 'Asesi';
$emailasesi = $dUser['username'] ?? ($dUser['email'] ?? $uidpes);
$idasesi_login = $dUser['id'] ?? ($dUser['id'] ?? '');
$tglreg_user = $dUser['kode'] ?? '';
$menu_uid = rawurlencode((string)$uidpes);
$op = $_REQUEST['op'] ?? '';
$flash_type = '';
$flash_message = '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.APL.02 - LSP SMKN 1 Cibinong</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--green:#22C55E;--red:#EF4444;--orange:#F97316;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{font-size:15px;scroll-behavior:smooth}body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}.sidebar{width:var(--sidebar-w);min-height:100vh;background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}.sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px}.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,.1)}.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text{line-height:1.2}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:700}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:500;letter-spacing:.5px}.sidebar-nav{padding:16px 12px;flex:1}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.875rem;font-weight:500;transition:all .2s;margin-bottom:2px}.nav-item:hover{background:rgba(255,255,255,.07);color:#fff;text-decoration:none}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07)}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;flex-shrink:0}.user-info strong{display:block;color:#fff;font-size:.82rem}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;font-size:.85rem}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}.topbar-title{font-size:1.1rem;font-weight:700;flex:1}.topbar-title span{color:var(--text-sub);font-weight:400;font-size:.875rem;margin-left:8px}.topbar-actions{display:flex;align-items:center;gap:10px}.icon-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-sub);font-size:.9rem}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:600;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.hero-card{background:linear-gradient(135deg,var(--navy) 0%,var(--navy-soft) 100%);color:#fff;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center}.hero-card small{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:rgba(59,191,191,.15);color:#A7F3F3;font-weight:700;font-size:.72rem;letter-spacing:.7px;text-transform:uppercase}.hero-card h1{margin-top:16px;font-size:1.65rem;line-height:1.25}.hero-card p{margin-top:8px;color:rgba(255,255,255,.72);max-width:760px;line-height:1.7}.hero-icon{width:86px;height:86px;border-radius:20px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:2.2rem}.card{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);animation:fadeUp .4s ease both}@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}.section-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:700;display:flex;align-items:center;gap:10px}.section-head h3 i{color:var(--teal)}.section-head p{font-size:.8rem;color:var(--text-sub);margin-top:4px;line-height:1.6}
.alert-box{padding:13px 16px;border-radius:12px;font-size:.86rem;font-weight:600;display:flex;align-items:center;gap:10px;margin-bottom:18px}.alert-success{background:#DCFCE7;color:#15803D;border:1px solid #86EFAC}.alert-warning{background:#FEFCE8;color:#A16207;border:1px solid #FDE68A}.alert-error{background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5}.meta-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:20px}.meta-card{border:1px solid var(--border);border-radius:12px;padding:14px;background:#FBFDFE}.meta-card span{display:block;color:var(--text-sub);font-size:.75rem;margin-bottom:5px}.meta-card strong{display:block;font-size:.9rem}.unit-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:12px}.unit-option{position:relative}.unit-option input{position:absolute;opacity:0;pointer-events:none}.unit-card{display:grid;grid-template-columns:34px 1fr;gap:12px;padding:15px;border:1.5px solid var(--border);border-radius:13px;background:#fff;cursor:pointer;transition:all .2s;min-height:104px}.unit-card:hover{border-color:var(--teal);box-shadow:0 4px 18px rgba(59,191,191,.11);transform:translateY(-1px)}.unit-option input:checked + .unit-card{border-color:var(--teal);background:var(--teal-light);box-shadow:0 0 0 3px var(--teal-glow)}.unit-option input:disabled + .unit-card{opacity:.55;cursor:not-allowed;background:#F8FAFC}.check-mark{width:26px;height:26px;border-radius:8px;border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;color:transparent;transition:all .2s}.unit-option input:checked + .unit-card .check-mark{background:var(--teal);border-color:var(--teal);color:#fff}.unit-code{display:inline-flex;align-items:center;gap:6px;padding:4px 9px;border-radius:7px;background:var(--off);color:var(--teal-dark);font-size:.74rem;font-weight:800;margin-bottom:8px}.unit-name{font-weight:700;line-height:1.45;font-size:.9rem}.unit-note{font-size:.76rem;color:var(--text-sub);margin-top:8px}
.doc-table{width:100%;border-collapse:collapse;margin-bottom:18px}.doc-table th,.doc-table td{border:1px solid var(--border);padding:10px 12px;text-align:left;font-size:.85rem;vertical-align:top}.doc-table th{background:var(--navy);color:#fff}.doc-section{background:#F8FBFC!important;color:var(--text-main)!important}.radio-cell{text-align:center!important;width:60px}.form-input,.form-textarea{width:100%;font-size:.88rem;padding:10px 12px;border:1.5px solid var(--border);border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-main);background:var(--off);outline:none}.form-textarea{min-height:42px;resize:vertical}.form-input:focus,.form-textarea:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-glow);background:#fff}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 20px;border-radius:10px;font-size:.86rem;font-weight:700;cursor:pointer;text-decoration:none;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif}.btn:hover{text-decoration:none}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3)}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}.btn-sm{padding:7px 13px;font-size:.78rem}.form-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:20px;padding-top:18px;border-top:1px solid var(--border)}.empty-state{text-align:center;padding:44px 20px;color:var(--text-muted)}.empty-state i{font-size:2.5rem;margin-bottom:12px;display:block;opacity:.5}.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
@media(max-width:1100px){.meta-grid{grid-template-columns:1fr}}@media(max-width:900px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}.topbar{padding:0 18px}.content{padding:20px}.hero-card{grid-template-columns:1fr}.hero-icon{display:none}}@media print{.sidebar,.topbar,.page-footer,.form-actions,.no-print{display:none!important}.main{margin-left:0}.content{padding:0}.card{box-shadow:none;border:0;padding:0}}
</style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo"><div class="logo-box"><img src="../images/lsplogosmkn1.png" alt="Logo LSP"></div><div class="logo-text"><strong>LSP</strong><span>SMKN 1 CIBINONG</span></div></div>
    <nav class="sidebar-nav">
        <div class="nav-label">Pendaftaran</div>
        <a href="pilihskema.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-paperclip"></i> Pilih Skema</a>
        <a href="pilihunit.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-link"></i> Pilih Unit</a>
        <a href="dashsiswa.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-tag"></i> FR.APL. 1</a>
        <a href="apl2.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item active"><i class="fas fa-chart-line"></i> FR.APL. 2</a>
        <a href="portofolio.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-pen-nib"></i> Portofolio</a>
        <div class="nav-label">Asesmen</div>
        <a href="testulis.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-file-pen"></i> FR.IA.05 Pertanyaan Tertulis</a>
        <a href="mak5.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-file-signature"></i> FR.AK.03</a>
        <a href="statussaya.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-map"></i> Cek Status</a>
        <a href="banding.php" class="nav-item"><i class="fas fa-map"></i> FR.AK.04 Banding</a>
        <a href="rahasia.php" class="nav-item"><i class="fas fa-map"></i> FR.AK.01 Kerahasiaan</a>
        <div class="nav-label">Akun</div>
        <a href="ubahpassword.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-key"></i> Ubah Password</a>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card"><div class="user-avatar"><?php echo e(initials($namaasesi)); ?></div><div class="user-info"><strong><?php echo e($namaasesi); ?></strong><span>Asesi LSP</span></div><button class="btn-logout" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button></div></div>
</aside>
<main class="main">
<header class="topbar"><div class="topbar-title">FR.APL. 2 <span>Asesmen Mandiri</span></div><div class="topbar-actions"><div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div><button class="icon-btn" type="button"><i class="fas fa-bell"></i></button></div></header>
<section class="content">
<?php
if ($op === 'simpanapl2' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = esc_sql($conn, trim($_POST['email'] ?? $emailasesi));
    $idasesi = esc_sql($conn, $_POST['idadsesi'] ?? $idasesi_login);
    $idskema = esc_sql($conn, $_POST['idskema'] ?? '');
    $tglregapl2 = esc_sql($conn, trim($_POST['tglregapl2'] ?? $tglreg_user));
    $n = (int)($_POST['n'] ?? 0);

    $sukses = 0;
    $gagal = 0;
    $updates = 0;
    $gagal_update = 0;
    $kosong = 0;

    for ($i = 0; $i <= $n - 1; $i++) {
        if (!isset($_POST['bk'.$i])) {
            $kosong++;
            continue;
        }

        $cb = esc_sql($conn, $_POST['bk'.$i]);
        $idelemen = esc_sql($conn, $_POST['idelemen'.$i] ?? '');
        $subel = esc_sql($conn, $_POST['idsube'.$i] ?? '');
        $idunit = esc_sql($conn, $_POST['idunit'.$i] ?? '');
        $bukti_relevan = esc_sql($conn, $_POST['bukti_relevan'.$i] ?? '');

        if ($idelemen === '' || $subel === '' || $idunit === '') {
            $gagal++;
            continue;
        }

        if ($bukti_relevan === '') {
            $bukti_relevan = 'v,a,t,m';
        }

        $cekdulu = "SELECT * FROM apl2 
                    WHERE idskema='$idskema' 
                    AND idunit='$idunit' 
                    AND idelemen='$idelemen' 
                    AND idsubelemen='$subel' 
                    AND idadsesi='$idasesi'";

        $adulu = mysqli_query($conn, $cekdulu);

        if ($adulu && mysqli_num_rows($adulu) > 0) {
            $q = "UPDATE apl2 SET 
                    tk='$cb',
                    waktu='$tglregapl2',
                    svalidasi='N'
                  WHERE idskema='$idskema' 
                    AND idunit='$idunit' 
                    AND idelemen='$idelemen' 
                    AND idsubelemen='$subel' 
                    AND idadsesi='$idasesi'";

            mysqli_query($conn, $q) ? $updates++ : $gagal_update++;
        } else {
            $r_max = mysqli_fetch_array(mysqli_query($conn, "SELECT COALESCE(MAX(id), 0) + 1 AS max_id FROM apl2"));
            $next_id = (int)($r_max['max_id'] ?? 1);

            $q = "INSERT INTO apl2 
                    (id, idadsesi, email, idsubelemen, idskema, waktu, svalidasi, idunit, idelemen, tk) 
                  VALUES 
                    ('$next_id', '$idasesi', '$email', '$subel', '$idskema', '$tglregapl2', 'N', '$idunit', '$idelemen', '$cb')";

            $res_q = mysqli_query($conn, $q);
            if (!$res_q) {
                $q_fb = "INSERT INTO apl2 
                        (idadsesi, email, idsubelemen, idskema, waktu, svalidasi, idunit, idelemen, tk) 
                        VALUES 
                        ('$idasesi', '$email', '$subel', '$idskema', '$tglregapl2', 'N', '$idunit', '$idelemen', '$cb')";
                $res_q = mysqli_query($conn, $q_fb);
            }
            $res_q ? $sukses++ : $gagal++;
        }
    }

    $flash_type = ($gagal === 0 && $gagal_update === 0) ? 'success' : 'warning';
    $flash_message = "Sukses simpan: $sukses, sukses update: $updates, belum diisi: $kosong, gagal: ".($gagal + $gagal_update).".";
}
if ($op === 'uploadbuktipostapl2' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = esc_sql($conn, $_POST['email'] ?? $emailasesi);
    $idskemapt2 = esc_sql($conn, $_POST['idskemapt2'] ?? '');
    if (empty($_FILES['fotox4']['name'])) {
        $flash_type = 'warning'; $flash_message = 'File bukti APL2 belum dipilih.';
    } else {
        $raw = str_replace(' ', '', $_FILES['fotox4']['name']);
        $nama_foto = 'apl2' . preg_replace('/[^A-Za-z0-9@._-]/', '', $email) . $raw;
        $ext = strtolower(pathinfo($nama_foto, PATHINFO_EXTENSION));
        if ($ext !== 'pdf' || $_FILES['fotox4']['size'] > 2000000) {
            $flash_type = 'warning'; $flash_message = 'Bukti APL2 harus PDF dan maksimal 2MB.';
        } elseif (move_uploaded_file($_FILES['fotox4']['tmp_name'], "gambarimages/" . $nama_foto)) {
            $ok = mysqli_query($conn, "UPDATE apl2 SET path='$nama_foto' WHERE idskema='$idskemapt2' AND email='$email'");
            $flash_type = $ok ? 'success' : 'error';
            $flash_message = $ok ? 'Upload bukti APL2 berhasil dan data diperbarui.' : 'File terupload, tetapi data APL2 gagal diperbarui.';
        } else {
            $flash_type = 'error'; $flash_message = 'Upload bukti APL2 gagal.';
        }
    }
}

$qSkema = "SELECT * FROM skemasiswa WHERE emailsiswa='$uidpes_safe' ORDER BY id_skemasiswa DESC LIMIT 1";
$rSkema = mysqli_query($conn, $qSkema);
$dSkema = ($rSkema && mysqli_num_rows($rSkema) > 0) ? mysqli_fetch_array($rSkema) : array();
$has_skema = !empty($dSkema);
$skema = $dSkema['idskema'] ?? '';
$statusapl1 = $dSkema['statusapl1'] ?? 'N';
$qApl1 = "SELECT * FROM apl1 WHERE email='$uidpes_safe' LIMIT 1";
$rApl1 = mysqli_query($conn, $qApl1);
$dApl1 = ($rApl1 && mysqli_num_rows($rApl1) > 0) ? mysqli_fetch_array($rApl1) : array();
$validasiapl1 = $dApl1['validasiapl1'] ?? 'N';
$qRekom = "SELECT * FROM rekomendasi WHERE namarekom='apl1lsp' AND idasesi='".esc_sql($conn,$idasesi_login)."' AND idskema='".esc_sql($conn,$skema)."' LIMIT 1";
$rRekom = mysqli_query($conn, $qRekom);
$dRekom = ($rRekom && mysqli_num_rows($rRekom) > 0) ? mysqli_fetch_array($rRekom) : array();
$rekomapl2 = $dRekom['rekom'] ?? 'N';
$qSkemaInfo = "SELECT * FROM skema WHERE idskema='".esc_sql($conn,$skema)."' LIMIT 1";
$rSkemaInfo = mysqli_query($conn, $qSkemaInfo);
$dSkemaInfo = $rSkemaInfo ? mysqli_fetch_array($rSkemaInfo) : array();
$namaskema = $dSkemaInfo['namaskema'] ?? 'Skema belum dipilih';
$qPem = "SELECT namaasesor FROM pemetaan WHERE idskema='".esc_sql($conn,$skema)."' AND idpeserta='".esc_sql($conn,$idasesi_login)."' LIMIT 1";
$rPem = mysqli_query($conn, $qPem);
$dPem = $rPem ? mysqli_fetch_array($rPem) : array();
$namaass = $dPem['namaasesor'] ?? 'Belum Ada Asesor';

?>
<div class="hero-card"><div><small><i class="fas fa-chart-line"></i> FR.APL.02</small><h1>Isi asesmen mandiri untuk unit kompetensi</h1><p>Pilih unit, nilai kemampuan diri pada setiap Kriteria Unjuk Kerja, lalu simpan jawaban K atau BK. Halaman ini bisa dibuka setelah APL.01 valid dan direkomendasikan lanjut.</p></div><div class="hero-icon"><i class="fas fa-clipboard-check"></i></div></div>
<?php if ($flash_message !== ''): ?><div class="alert-box alert-<?php echo e($flash_type); ?>"><i class="fas <?php echo $flash_type === 'success' ? 'fa-circle-check' : ($flash_type === 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-xmark'); ?>"></i><strong><?php echo e($flash_message); ?></strong></div><?php endif; ?>

<?php
if ($op === 'listsubkompetensi' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idskema_cari = esc_sql($conn, $_POST['skema'] ?? $skema);
    $tglpel = date('Y-m-d');
    $qPerm = "SELECT * FROM permohonan WHERE email='$uidpes_safe' AND idskema='$idskema_cari' LIMIT 1";
    $rPerm = mysqli_query($conn, $qPerm);
    if ($rPerm && mysqli_num_rows($rPerm) > 0) {
        $dPerm = mysqli_fetch_array($rPerm);
        $tglpel = $dPerm['tanggalp'] ?? $tglpel;
    }
    $n = (int)($_POST['n'] ?? 0);
    $rows = array();
    $selected_unit_count = 0;
    for ($c=0; $c <= $n-1; $c++) {
        if (!isset($_POST['kodeunit'.$c])) continue;
        $selected_unit_count++;
        if ($selected_unit_count > 2) continue;
        $idunit = esc_sql($conn, $_POST['idunit'.$c] ?? '');
        $qUnit = "SELECT unitsiswa.idunit,unitsiswa.idskema,unit.kodeunit,unit.namaunit FROM unitsiswa INNER JOIN unit ON unitsiswa.idunit=unit.idunit WHERE unitsiswa.idskema='$idskema_cari' AND unitsiswa.idadsesi='".esc_sql($conn,$idasesi_login)."' AND unit.idunit='$idunit' LIMIT 1";
        $rUnit = mysqli_query($conn, $qUnit);
        while ($unit = mysqli_fetch_array($rUnit)) {
            $qElemen = "SELECT * FROM elemen WHERE idunit='".esc_sql($conn,$unit['idunit'])."' ORDER BY idelemen";
            $rElemen = mysqli_query($conn, $qElemen);
            while ($elemen = mysqli_fetch_array($rElemen)) {
                $qSub = "SELECT * FROM subelemen WHERE idelemen='".esc_sql($conn,$elemen['idelemen'])."' ORDER BY idsubelemen";
                $rSub = mysqli_query($conn, $qSub);
                while ($sub = mysqli_fetch_array($rSub)) {
                    $qOld = "SELECT * FROM apl2 WHERE idskema='$idskema_cari' AND idunit='".esc_sql($conn,$unit['idunit'])."' AND idelemen='".esc_sql($conn,$elemen['idelemen'])."' AND idsubelemen='".esc_sql($conn,$sub['idsubelemen'])."' AND idadsesi='".esc_sql($conn,$idasesi_login)."' LIMIT 1";
                    $rOld = mysqli_query($conn, $qOld);
                    $old = ($rOld && mysqli_num_rows($rOld)>0) ? mysqli_fetch_array($rOld) : array();
                    $rows[] = array('unit'=>$unit,'elemen'=>$elemen,'sub'=>$sub,'old'=>$old);
                }
            }
        }
    }
?>
<div class="card">
    <div class="section-head no-print"><div><h3><i class="fas fa-clipboard-list"></i> Form Asesmen Mandiri</h3><p>Isi K/BK dan bukti relevan untuk maksimal 2 unit dalam satu pengisian.</p></div><button type="button" class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print</button></div>
    <?php if ($selected_unit_count > 2): ?><div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i><strong>Unit yang diproses dibatasi 2 unit pertama.</strong></div><?php endif; ?>
    <form method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=simpanapl2&uidpes=<?php echo e($menu_uid); ?>" onsubmit="return confirm('Simpan asesmen mandiri?')">
        <input type="hidden" name="email" value="<?php echo e($emailasesi); ?>">
        <input type="hidden" name="idadsesi" value="<?php echo e($idasesi_login); ?>">
        <input type="hidden" name="idskema" value="<?php echo e($idskema_cari); ?>">
        <input type="hidden" name="tglpelapl2" value="<?php echo e($tglpel); ?>">
        <input type="hidden" name="tglregapl2" value="<?php echo e($tglreg_user); ?>">
        <div class="meta-grid"><div class="meta-card"><span>Nama Peserta</span><strong><?php echo e($namaasesi); ?></strong></div><div class="meta-card"><span>Nama Asesor</span><strong><?php echo e($namaass); ?></strong></div><div class="meta-card"><span>Skema</span><strong><?php echo e($namaskema); ?></strong></div></div>
        <table class="doc-table"><thead><tr><th style="width:42%">Dapatkah Saya ...?</th><th class="radio-cell">K</th><th class="radio-cell">BK</th><th>Bukti yang relevan</th></tr></thead><tbody>
        <?php $i=0; $last_unit=''; $last_elemen=''; foreach ($rows as $row): 
            $unit = $row['unit']; $elemen = $row['elemen']; $sub = $row['sub']; $old = $row['old'];
            if ($last_unit !== $unit['idunit']) { echo "<tr><th colspan='4' class='doc-section'>Unit: ".e($unit['kodeunit'])." - ".e($unit['namaunit'])."</th></tr>"; $last_unit = $unit['idunit']; $last_elemen=''; }
            if ($last_elemen !== $elemen['idelemen']) { echo "<tr><td colspan='4'><strong>Elemen: ".e($elemen['namaelemen'])."</strong></td></tr>"; $last_elemen = $elemen['idelemen']; }
        ?>
            <tr>
                <td><?php echo e($sub['pertanyaan']); ?></td>
                <td class="radio-cell"><input type="radio" name="bk<?php echo e($i); ?>" value="k" <?php echo checked_if($old['tk'] ?? '', 'k'); ?>></td>
                <td class="radio-cell"><input type="radio" name="bk<?php echo e($i); ?>" value="bk" <?php echo checked_if($old['tk'] ?? '', 'bk'); ?>></td>
                <td><textarea class="form-textarea" name="bukti_relevan<?php echo e($i); ?>" rows="1"><?php echo e($old['path'] ?? ''); ?></textarea></td>
                <input type="hidden" name="idelemen<?php echo e($i); ?>" value="<?php echo e($elemen['idelemen']); ?>">
                <input type="hidden" name="idunit<?php echo e($i); ?>" value="<?php echo e($unit['idunit']); ?>">
                <input type="hidden" name="idsube<?php echo e($i); ?>" value="<?php echo e($sub['idsubelemen']); ?>">
            </tr>
        <?php $i++; endforeach; ?>
        </tbody></table>
        <input type="hidden" name="n" value="<?php echo e($i); ?>">
        <div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-floppy-disk"></i> Simpan APL2</button><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>">Kembali</a></div>
    </form>
</div>
<?php
} elseif ($op === 'uploadbuktiapl2') {
    $idskemapt = $_GET['idskemapt'] ?? $skema;
?>
<div class="card"><div class="section-head"><div><h3><i class="fas fa-file-pdf"></i> Upload Bukti APL2</h3><p>File PDF maksimal 2MB. Upload ini akan mengisi lampiran pada data APL2.</p></div><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>">Kembali</a></div><form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=uploadbuktipostapl2&uidpes=<?php echo e($menu_uid); ?>"><input type="hidden" name="idskemapt2" value="<?php echo e($idskemapt); ?>"><input type="hidden" name="email" value="<?php echo e($emailasesi); ?>"><div class="meta-card"><span>Catatan</span><strong>Pastikan semua subkompetensi sudah diisi sebelum upload bukti pendukung.</strong></div><div style="height:16px"></div><input class="form-input" type="file" name="fotox4" accept=".pdf" required><div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> Upload Bukti</button></div></form></div>
<?php
} else {
?>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-list-check"></i> Pilih Unit APL2</h3><p>Pilih maksimal 2 unit untuk mengisi asesmen mandiri.</p></div><?php if($has_skema): ?><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=uploadbuktiapl2&uidpes=<?php echo e($menu_uid); ?>&idskemapt=<?php echo e($skema); ?>"><i class="fas fa-file-pdf"></i> Upload Bukti</a><?php endif; ?></div>
    <div class="meta-grid"><div class="meta-card"><span>Skema</span><strong><?php echo e($namaskema); ?></strong></div><div class="meta-card"><span>Status APL1</span><strong><?php echo e($statusapl1); ?> / Validasi <?php echo e($validasiapl1); ?></strong></div><div class="meta-card"><span>Rekomendasi</span><strong><?php echo e($rekomapl2); ?></strong></div></div>
    <?php if (!$has_skema): ?>
        <div class="empty-state"><i class="fas fa-circle-exclamation"></i><strong>Skema belum dipilih</strong><p>Pilih skema terlebih dahulu.</p><div style="margin-top:16px"><a class="btn btn-primary" href="pilihskema.php?uidpes=<?php echo e($menu_uid); ?>">Pilih Skema</a></div></div>
    <?php elseif (!($statusapl1 === 'Y' && $validasiapl1 === 'Y' && $rekomapl2 === 'L')): ?>
        <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i><strong>APL1 belum valid atau belum direkomendasikan lanjut. Hubungi LSP/asesor.</strong></div>
    <?php else: ?>
        <form method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listsubkompetensi&uidpes=<?php echo e($menu_uid); ?>">
            <input type="hidden" name="email" value="<?php echo e($emailasesi); ?>">
            <input type="hidden" name="idadsesi" value="<?php echo e($idasesi_login); ?>">
            <input type="hidden" name="namap" value="<?php echo e($namaasesi); ?>">
            <input type="hidden" name="namaass" value="<?php echo e($namaass); ?>">
            <input type="hidden" name="skema" value="<?php echo e($skema); ?>">
            <div class="unit-list">
            <?php
            $qUnits = "SELECT unitsiswa.idadsesi,unitsiswa.idunit,unit.kodeunit,unit.namaunit,unit.idskema FROM unitsiswa INNER JOIN unit ON unitsiswa.idunit=unit.idunit WHERE unitsiswa.idskema='".esc_sql($conn,$skema)."' AND unitsiswa.idadsesi='".esc_sql($conn,$idasesi_login)."' ORDER BY unit.kodeunit";
            $rUnits = mysqli_query($conn, $qUnits);
            $i=0;
            if ($rUnits && mysqli_num_rows($rUnits)>0):
                while($unit=mysqli_fetch_array($rUnits)):
                    $qDone = "SELECT * FROM apl2 WHERE idadsesi='".esc_sql($conn,$idasesi_login)."' AND idskema='".esc_sql($conn,$skema)."' AND idunit='".esc_sql($conn,$unit['idunit'])."'";
                    $rDone = mysqli_query($conn,$qDone);
                    $filled = $rDone ? mysqli_num_rows($rDone) : 0;
                    $qTotal = "SELECT COUNT(idsubelemen) AS total FROM subelemen WHERE idunit='".esc_sql($conn,$unit['idunit'])."'";
                    $rTotal = mysqli_query($conn,$qTotal);
                    $dTotal = $rTotal ? mysqli_fetch_array($rTotal) : array('total'=>0);
                    $total = (int)($dTotal['total'] ?? 0);
                    $disabled = '';
            ?>
                <label class="unit-option">
                    <input type="hidden" name="idskema<?php echo e($i); ?>" value="<?php echo e($unit['idskema']); ?>">
                    <input type="hidden" name="idunit<?php echo e($i); ?>" value="<?php echo e($unit['idunit']); ?>">
                    <input type="checkbox" name="kodeunit<?php echo e($i); ?>" value="<?php echo e($unit['kodeunit']); ?>" <?php echo $disabled; ?>>
                    <div class="unit-card"><div class="check-mark"><i class="fas fa-check"></i></div><div><span class="unit-code"><?php echo e($unit['kodeunit']); ?></span><div class="unit-name"><?php echo e($unit['namaunit']); ?></div><div class="unit-note">Diisi <?php echo e($filled); ?> dari <?php echo e($total); ?> KUK <?php echo $disabled ? '(lengkap)' : ''; ?></div></div></div>
                </label>
            <?php $i++; endwhile; else: ?>
                <div class="empty-state"><i class="fas fa-folder-open"></i><strong>Unit belum dipilih</strong><p>Pilih unit terlebih dahulu.</p></div>
            <?php endif; ?>
            </div>
            <input type="hidden" name="n" value="<?php echo e($i); ?>">
            <div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-arrow-right"></i> Lanjutkan</button></div>
        </form>
    <?php endif; ?>
</div>
<?php } ?>
</section>

<footer class="page-footer">
    <span>
        Asesi Panel &copy; <?php echo date('Y'); ?> 
        LSP SMKN 1 Cibinong
    </span>

    <span>
        Modernized by <strong>Codex</strong>
    </span>
</footer>

</main>
</body>
</html>
