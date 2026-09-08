<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "../lsp_koneksi.php";

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }
function esc_sql($conn, $value) { return mysqli_real_escape_string($conn, (string)$value); }
function initials($name) { $name = trim((string)$name); return $name === '' ? 'AS' : strtoupper(substr($name, 0, 2)); }
function checked_token($csv, $token) { return in_array($token, array_map('trim', explode(',', (string)$csv)), true) ? 'checked' : ''; }

$today = date('d F Y');
$current_time = date('H:i');

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'><h3>Anda Harus Login Dahulu!</h3><a href='../lsp_login.php'>Kembali ke Login</a></div>";
    exit;
}

$uname = $_GET['uidpes'] ?? ($_SESSION['username'] ?? '');
if ($uname === '') $uname = $_SESSION['username'] ?? '';
$uname_safe = esc_sql($conn, $uname);
$qUser = "SELECT * FROM users WHERE username='$uname_safe' LIMIT 1";
$rUser = mysqli_query($conn, $qUser);
$dUser = $rUser ? mysqli_fetch_array($rUser) : array();
$namax = $dUser['nama'] ?? 'Asesi';
$iduser = $dUser['id'] ?? '';
$menu_uid = rawurlencode((string)$uname);
$op = $_REQUEST['op'] ?? '';
$flash_type = '';
$flash_message = '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Portofolio - LSP SMKN 1 Cibinong</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script>
var win = null;
function NewWindow(mypage,myname,w,h,scroll){
    var leftPosition = (screen.width) ? (screen.width-w)/2 : 0;
    var topPosition = (screen.height) ? (screen.height-h)/2 : 0;
    var settings = 'height='+h+',width='+w+',top='+topPosition+',left='+leftPosition+',scrollbars='+scroll+',resizable';
    win = window.open(mypage,myname,settings);
}
</script>
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--green:#22C55E;--red:#EF4444;--orange:#F97316;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{font-size:15px;scroll-behavior:smooth}body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}
.sidebar{width:var(--sidebar-w);min-height:100vh;background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}.sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px}.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,.1)}.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text{line-height:1.2}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:700}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:500;letter-spacing:.5px}.sidebar-nav{padding:16px 12px;flex:1}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.875rem;font-weight:500;transition:all .2s;margin-bottom:2px}.nav-item:hover{background:rgba(255,255,255,.07);color:#fff;text-decoration:none}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07)}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;flex-shrink:0}.user-info strong{display:block;color:#fff;font-size:.82rem}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;font-size:.85rem}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}.topbar-title{font-size:1.1rem;font-weight:700;flex:1}.topbar-title span{color:var(--text-sub);font-weight:400;font-size:.875rem;margin-left:8px}.topbar-actions{display:flex;align-items:center;gap:10px}.icon-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-sub);font-size:.9rem}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:600;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.hero-card{background:linear-gradient(135deg,var(--navy) 0%,var(--navy-soft) 100%);color:#fff;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center}.hero-card small{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:rgba(59,191,191,.15);color:#A7F3F3;font-weight:700;font-size:.72rem;letter-spacing:.7px;text-transform:uppercase}.hero-card h1{margin-top:16px;font-size:1.65rem;line-height:1.25}.hero-card p{margin-top:8px;color:rgba(255,255,255,.72);max-width:760px;line-height:1.7}.hero-icon{width:86px;height:86px;border-radius:20px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:2.2rem}
.card{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);animation:fadeUp .4s ease both}@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}.section-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:700;display:flex;align-items:center;gap:10px}.section-head h3 i{color:var(--teal)}.section-head p{font-size:.8rem;color:var(--text-sub);margin-top:4px;line-height:1.6}
.alert-box{padding:13px 16px;border-radius:12px;font-size:.86rem;font-weight:600;display:flex;align-items:center;gap:10px;margin-bottom:18px}.alert-success{background:#DCFCE7;color:#15803D;border:1px solid #86EFAC}.alert-warning{background:#FEFCE8;color:#A16207;border:1px solid #FDE68A}.alert-error{background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5}
.action-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:16px}.action-card{border:1px solid var(--border);border-radius:14px;padding:18px;background:#FBFDFE}.action-card i{width:42px;height:42px;border-radius:12px;background:var(--teal-light);color:var(--teal-dark);display:flex;align-items:center;justify-content:center;margin-bottom:12px}.action-card h4{font-size:.96rem;margin-bottom:6px}.action-card p{font-size:.8rem;color:var(--text-sub);line-height:1.6;margin-bottom:14px}.meta-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:20px}.meta-card{border:1px solid var(--border);border-radius:12px;padding:14px;background:#FBFDFE}.meta-card span{display:block;color:var(--text-sub);font-size:.75rem;margin-bottom:5px}.meta-card strong{display:block;font-size:.9rem}.unit-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:12px}.unit-option{position:relative}.unit-option input{position:absolute;opacity:0;pointer-events:none}.unit-card{display:grid;grid-template-columns:34px 1fr;gap:12px;padding:15px;border:1.5px solid var(--border);border-radius:13px;background:#fff;cursor:pointer;transition:all .2s;min-height:104px}.unit-card:hover{border-color:var(--teal);box-shadow:0 4px 18px rgba(59,191,191,.11);transform:translateY(-1px)}.unit-option input:checked + .unit-card{border-color:var(--teal);background:var(--teal-light);box-shadow:0 0 0 3px var(--teal-glow)}.unit-option input:disabled + .unit-card{opacity:.55;cursor:not-allowed;background:#F8FAFC}.check-mark{width:26px;height:26px;border-radius:8px;border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;color:transparent;transition:all .2s}.unit-option input:checked + .unit-card .check-mark{background:var(--teal);border-color:var(--teal);color:#fff}.unit-code{display:inline-flex;align-items:center;gap:6px;padding:4px 9px;border-radius:7px;background:var(--off);color:var(--teal-dark);font-size:.74rem;font-weight:800;margin-bottom:8px}.unit-name{font-weight:700;line-height:1.45;font-size:.9rem}.unit-note{font-size:.76rem;color:var(--text-sub);margin-top:8px}
.doc-table{width:100%;border-collapse:collapse;margin-bottom:18px}.doc-table th,.doc-table td{border:1px solid var(--border);padding:10px 12px;text-align:left;font-size:.85rem;vertical-align:top}.doc-table th{background:var(--navy);color:#fff}.form-input{width:100%;font-size:.88rem;padding:10px 12px;border:1.5px solid var(--border);border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-main);background:var(--off);outline:none}.form-input:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-glow);background:#fff}.check-grid{display:flex;flex-wrap:wrap;gap:8px}.check-item{display:inline-flex;align-items:center;gap:7px;border:1px solid var(--border);border-radius:9px;padding:7px 10px;background:#FBFDFE;font-size:.8rem;font-weight:600}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 20px;border-radius:10px;font-size:.86rem;font-weight:700;cursor:pointer;text-decoration:none;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif}.btn:hover{text-decoration:none}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3)}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}.form-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:20px;padding-top:18px;border-top:1px solid var(--border)}.empty-state{text-align:center;padding:44px 20px;color:var(--text-muted)}.empty-state i{font-size:2.5rem;margin-bottom:12px;display:block;opacity:.5}.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
@media(max-width:1000px){.meta-grid{grid-template-columns:1fr}}@media(max-width:900px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}.topbar{padding:0 18px}.content{padding:20px}.hero-card{grid-template-columns:1fr}.hero-icon{display:none}}@media print{.sidebar,.topbar,.page-footer,.form-actions,.no-print{display:none!important}.main{margin-left:0}.content{padding:0}.card{box-shadow:none;border:0;padding:0}}
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
        <a href="apl2.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-chart-line"></i> FR.APL. 2</a>
        <a href="portofolio.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item active"><i class="fas fa-pen-nib"></i> Portofolio</a>
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
    <div class="sidebar-footer"><div class="user-card"><div class="user-avatar"><?php echo e(initials($namax)); ?></div><div class="user-info"><strong><?php echo e($namax); ?></strong><span>Asesi LSP</span></div><button class="btn-logout" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button></div></div>
</aside>
<main class="main">
<header class="topbar"><div class="topbar-title">Portofolio <span>Bukti kompetensi pendukung</span></div><div class="topbar-actions"><div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e(date('d F Y')); ?></div><button class="icon-btn" type="button"><i class="fas fa-bell"></i></button></div></header>
<section class="content">
<?php
if ($op === 'kompetensi3' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $dup = 0; $suk = 0;
    $email = esc_sql($conn, trim($_POST['email'] ?? ''));
    $idskema = esc_sql($conn, $_POST['skema'] ?? '');
    $idasesi = esc_sql($conn, $_POST['idasesi'] ?? '');
    $n = (int)($_POST['n'] ?? 0);

    for ($idx = 0; $idx < $n; $idx++) {
        if (!isset($_POST['unit'.$idx])) continue;
        $idelemen = esc_sql($conn, $_POST['unit'.$idx] ?? '');
        $idunit = esc_sql($conn, $_POST['eunit'.$idx] ?? '');
        if ($idelemen === '' || $idunit === '') continue;

        $bukti_arr = array();
        if (!empty($_POST['buktia'.$idx])) $bukti_arr[] = 'sk';
        if (!empty($_POST['buktib'.$idx])) $bukti_arr[] = 'sr';
        if (!empty($_POST['buktic'.$idx])) $bukti_arr[] = 'cp';
        if (!empty($_POST['buktid'.$idx])) $bukti_arr[] = 'jd';
        if (!empty($_POST['buktie'.$idx])) $bukti_arr[] = 'ws';
        if (!empty($_POST['buktif'.$idx])) $bukti_arr[] = 'de';
        if (!empty($_POST['buktig'.$idx])) $bukti_arr[] = 'pe';
        if (!empty($_POST['buktih'.$idx])) $bukti_arr[] = 'l';
        $allbukti = esc_sql($conn, implode(',', $bukti_arr));

        $path_file = '';
        if (isset($_FILES['fotox2']['name'][$idx]) && $_FILES['fotox2']['error'][$idx] == 0) {
            $target_dir = "uploads/portofolio/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
            $file_ext = strtolower(pathinfo($_FILES['fotox2']['name'][$idx], PATHINFO_EXTENSION));
            $new_name = time() . "_" . $idx . "_" . rand(1000, 9999) . "." . $file_ext;
            $target_file = $target_dir . $new_name;
            $allowed = array('jpg','jpeg','png','pdf');
            if (in_array($file_ext, $allowed, true) && $_FILES['fotox2']['size'][$idx] <= 2000000) {
                if (move_uploaded_file($_FILES['fotox2']['tmp_name'][$idx], $target_file)) {
                    $path_file = esc_sql($conn, $new_name);
                }
            }
        }

        $cek = mysqli_query($conn, "SELECT * FROM upload WHERE idskema='$idskema' AND idasesi='$idasesi' AND idunit='$idunit' AND idelemen='$idelemen'");
        if ($cek && mysqli_num_rows($cek) > 0) {
            $path_sql = ($path_file !== '') ? ", path='$path_file'" : "";
            $sql = "UPDATE upload SET bukti='$allbukti' $path_sql, waktu=NOW() WHERE idskema='$idskema' AND idasesi='$idasesi' AND idunit='$idunit' AND idelemen='$idelemen'";
            if (mysqli_query($conn, $sql)) $dup++;
        } else {
            $sql = "INSERT INTO upload (idskema,idasesi,idunit,idelemen,email,bukti,path,waktu) VALUES ('$idskema','$idasesi','$idunit','$idelemen','$email','$allbukti','$path_file',NOW())";
            if (mysqli_query($conn, $sql)) $suk++;
        }
    }

    $flash_type = 'success';
    $flash_message = "Berhasil menyimpan portofolio. Insert: $suk, Update: $dup.";
}

if ($op === 'uploadbuktipostp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = esc_sql($conn, $_POST['email'] ?? $uname);
    $idskema = esc_sql($conn, $_POST['idskema'] ?? '');
    if (empty($_FILES['fotox4']['name'])) {
        $flash_type = 'warning';
        $flash_message = 'File bukti pendukung belum dipilih.';
    } else {
        $namaffpor = str_replace(' ', '', $_FILES['fotox4']['name']);
        $nama_foto = 'porto' . preg_replace('/[^A-Za-z0-9@._-]/', '', $email) . $namaffpor;
        $ext = strtolower(pathinfo($nama_foto, PATHINFO_EXTENSION));
        if ($ext !== 'pdf' || $_FILES['fotox4']['size'] > 2000000) {
            $flash_type = 'warning';
            $flash_message = 'Bukti pendukung harus PDF dan maksimal 2MB.';
        } elseif (move_uploaded_file($_FILES['fotox4']['tmp_name'], "gambarimages/" . $nama_foto)) {
            $ok = mysqli_query($conn, "UPDATE upload SET path='$nama_foto' WHERE email='$email' AND idskema='$idskema'");
            $flash_type = $ok ? 'success' : 'error';
            $flash_message = $ok ? 'File pendukung berhasil diupload.' : 'File terupload, tetapi data upload gagal diperbarui.';
        } else {
            $flash_type = 'error';
            $flash_message = 'File pendukung gagal diupload.';
        }
    }
}
?>
<?php if ($flash_message !== ''): ?><div class="alert-box alert-<?php echo e($flash_type); ?>"><i class="fas <?php echo $flash_type === 'success' ? 'fa-circle-check' : ($flash_type === 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-xmark'); ?>"></i><strong><?php echo e($flash_message); ?></strong></div><?php endif; ?>

<?php
if ($op === 'kompetensi') {
    $emailuser = esc_sql($conn, trim($uname));
    $cek = mysqli_query($conn, "SELECT * FROM skemasiswa WHERE emailsiswa='$emailuser' ORDER BY id_skemasiswa DESC LIMIT 1");
    if ($cek && mysqli_num_rows($cek) > 0) {
        $data = mysqli_fetch_array($cek);
        $skema = $data['idskema'];
        $ske = mysqli_query($conn, "SELECT * FROM skema WHERE idskema='$skema' LIMIT 1");
        $ske2 = $ske ? mysqli_fetch_array($ske) : array();
        $namaskema = $ske2['namaskema'] ?? 'Skema Tidak Ditemukan';
        $idasesi = $iduser;
?>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-list-check"></i> Pilih Unit Portofolio</h3><p>Skema: <strong><?php echo e($namaskema); ?></strong>. Maksimal 2 unit akan ditampilkan pada langkah berikutnya, sesuai fungsi lama.</p></div><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>">Kembali</a></div>
    <form method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=kompetensi1&uidpes=<?php echo e($menu_uid); ?>">
        <input type="hidden" name="email" value="<?php echo e($emailuser); ?>">
        <input type="hidden" name="idasesi" value="<?php echo e($idasesi); ?>">
        <input type="hidden" name="skema" value="<?php echo e($skema); ?>">
        <div class="unit-list">
        <?php
        $sqlunit = "SELECT unitsiswa.idadsesi,unitsiswa.idunit,unit.kodeunit,unit.namaunit,unit.idskema FROM unitsiswa INNER JOIN unit ON unitsiswa.idunit=unit.idunit WHERE unitsiswa.idskema='$skema' AND unitsiswa.idadsesi='$idasesi' ORDER BY unit.kodeunit";
        $execunit = mysqli_query($conn, $sqlunit);
        $i = 0;
        while ($unit2 = mysqli_fetch_array($execunit)) {
            $dunit = $unit2['idunit'];
            $sqltotal = mysqli_query($conn, "SELECT COUNT(idelemen) AS total FROM elemen WHERE idunit='$dunit'");
            $rowtotal = mysqli_fetch_array($sqltotal);
            $bykelemen = $rowtotal['total'] ?? 0;
            $sqlbykunit = mysqli_query($conn, "SELECT COUNT(DISTINCT idelemen) AS bykunit FROM upload WHERE idskema='$skema' AND idunit='$dunit' AND idasesi='$idasesi'");
            $rowbyk = mysqli_fetch_array($sqlbykunit);
            $bykunit = $rowbyk['bykunit'] ?? 0;
            $stada = ($bykelemen > 0 && $bykunit >= $bykelemen) ? 'disabled' : '';
        ?>
            <label class="unit-option">
                <input type="hidden" name="idskema<?php echo e($i); ?>" value="<?php echo e($unit2['idskema']); ?>">
                <input type="hidden" name="idunit<?php echo e($i); ?>" value="<?php echo e($dunit); ?>">
                <input type="checkbox" name="kodeunit<?php echo e($i); ?>" value="<?php echo e($unit2['kodeunit']); ?>" <?php echo $stada; ?>>
                <div class="unit-card"><div class="check-mark"><i class="fas fa-check"></i></div><div><span class="unit-code"><?php echo e($unit2['kodeunit']); ?></span><div class="unit-name"><?php echo e($unit2['namaunit']); ?></div><div class="unit-note">Bukti terisi <?php echo e($bykunit); ?> dari <?php echo e($bykelemen); ?> elemen <?php echo $stada ? '(lengkap)' : ''; ?></div></div></div>
            </label>
        <?php $i++; } ?>
        </div>
        <input type="hidden" name="n" value="<?php echo e($i); ?>">
        <div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-arrow-right"></i> Lanjutkan</button></div>
    </form>
</div>
<?php } else { ?>
<div class="card"><div class="empty-state"><i class="fas fa-circle-exclamation"></i><strong>Skema belum dipilih</strong><p>Pilih skema terlebih dahulu sebelum mengisi portofolio.</p></div></div>
<?php } 
} elseif ($op === 'kompetensi1') {
    $n = (int)($_POST['n'] ?? 0);
    $emailuser = esc_sql($conn, trim($uname));
    $idasesi = esc_sql($conn, $_POST['idasesi'] ?? $iduser);
    $skema = esc_sql($conn, $_POST['skema'] ?? '');
?>
<div class="card">
    <div class="section-head no-print"><div><h3><i class="fas fa-paperclip"></i> Bukti Pendukung Portofolio</h3><p>Centang jenis bukti relevan dan unggah file bila ada.</p></div><button type="button" class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print</button></div>
    <form method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=kompetensi3&uidpes=<?php echo e($menu_uid); ?>" enctype="multipart/form-data">
        <input type="hidden" name="email" value="<?php echo e($emailuser); ?>">
        <input type="hidden" name="idasesi" value="<?php echo e($idasesi); ?>">
        <input type="hidden" name="skema" value="<?php echo e($skema); ?>">
        <table class="doc-table"><thead><tr><th>Elemen Kompetensi</th><th>Bukti Relevan</th><th>Upload File</th></tr></thead><tbody>
        <?php
        $i = 0; $selected = 0;
        for ($idx = 0; $idx <= $n-1; $idx++) {
            if (!isset($_POST['kodeunit'.$idx])) continue;
            $selected++;
            if ($selected > 2) continue;
            $idskema = esc_sql($conn, $_POST['idskema'.$idx] ?? '');
            $idunitxx = esc_sql($conn, $_POST['idunit'.$idx] ?? '');
            $unit1 = mysqli_query($conn, "SELECT * FROM elemen WHERE idskema='$idskema' AND idunit='$idunitxx'");
            while ($unit2 = mysqli_fetch_array($unit1)) {
                $delemen = $unit2['idelemen'];
                $cek = mysqli_query($conn, "SELECT bukti,path FROM upload WHERE idskema='$idskema' AND idasesi='$idasesi' AND idunit='$idunitxx' AND idelemen='$delemen' LIMIT 1");
                $row = $cek ? mysqli_fetch_array($cek) : array();
                $bukti = $row['bukti'] ?? '';
                $path = $row['path'] ?? '';
        ?>
            <tr>
                <td><label class="check-item"><input type="checkbox" name="unit<?php echo e($i); ?>" value="<?php echo e($delemen); ?>" checked> <?php echo e($unit2['namaelemen']); ?></label></td>
                <td><input type="hidden" name="eunit<?php echo e($i); ?>" value="<?php echo e($unit2['idunit']); ?>"><div class="check-grid">
                    <label class="check-item"><input type="checkbox" name="buktia<?php echo e($i); ?>" value="sk" <?php echo checked_token($bukti,'sk'); ?>> SK</label>
                    <label class="check-item"><input type="checkbox" name="buktib<?php echo e($i); ?>" value="sr" <?php echo checked_token($bukti,'sr'); ?>> SR</label>
                    <label class="check-item"><input type="checkbox" name="buktic<?php echo e($i); ?>" value="cp" <?php echo checked_token($bukti,'cp'); ?>> CP</label>
                    <label class="check-item"><input type="checkbox" name="buktid<?php echo e($i); ?>" value="jd" <?php echo checked_token($bukti,'jd'); ?>> JD</label>
                    <label class="check-item"><input type="checkbox" name="buktie<?php echo e($i); ?>" value="ws" <?php echo checked_token($bukti,'ws'); ?>> WS</label>
                    <label class="check-item"><input type="checkbox" name="buktif<?php echo e($i); ?>" value="de" <?php echo checked_token($bukti,'de'); ?>> De</label>
                    <label class="check-item"><input type="checkbox" name="buktig<?php echo e($i); ?>" value="pe" <?php echo checked_token($bukti,'pe'); ?>> Pe</label>
                    <label class="check-item"><input type="checkbox" name="buktih<?php echo e($i); ?>" value="l" <?php echo checked_token($bukti,'l'); ?>> L</label>
                </div><?php if($path): ?><div style="margin-top:8px;font-size:.76rem;color:var(--text-sub)">File saat ini: <?php echo e($path); ?></div><?php endif; ?></td>
                <td><input class="form-input" type="file" name="fotox2[<?php echo e($i); ?>]" accept=".pdf,.jpg,.jpeg,.png"></td>
            </tr>
        <?php $i++; }} ?>
        </tbody></table>
        <?php if ($selected > 2): ?><div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i><strong>Anda memilih lebih dari 2 unit. Hanya 2 yang ditampilkan.</strong></div><?php endif; ?>
        <input type="hidden" name="n" value="<?php echo e($i); ?>">
        <div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-floppy-disk"></i> Simpan</button><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=kompetensi&uidpes=<?php echo e($menu_uid); ?>">Kembali</a></div>
    </form>
    <div class="meta-card"><span>Keterangan</span><strong>SK = Sertifikasi/Kualifikasi, SR = Surat Referensi, CP = Contoh Pekerjaan, JD = Job Description, WS = Wawancara, De = Demonstrasi, Pe = Pengalaman Industri, L = Lainnya.</strong></div>
</div>
<?php
} elseif ($op === 'uploadbuktiz') {
    $emailbp = $_GET['email'] ?? $uname;
    $emailbp_safe = esc_sql($conn, $emailbp);
    $ceksyaps = mysqli_query($conn, "SELECT * FROM skemasiswa WHERE emailsiswa='$emailbp_safe' AND statustest='N' ORDER BY id_skemasiswa DESC LIMIT 1");
    $ceksyapsb = $ceksyaps ? mysqli_fetch_array($ceksyaps) : array();
    $idskemasyac = $ceksyapsb['idskema'] ?? '';
?>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-file-pdf"></i> Upload Bukti Pendukung</h3><p>File PDF maksimal 2MB. Kalau ada penambahan unit/kompetensi, upload ulang bukti pendukung.</p></div><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>">Kembali</a></div>
    <form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=uploadbuktipostp&uidpes=<?php echo e($menu_uid); ?>">
        <input type="hidden" name="namas" value="<?php echo e($_GET['namas'] ?? $namax); ?>">
        <input type="hidden" name="email" value="<?php echo e($emailbp); ?>">
        <input type="hidden" name="idskema" value="<?php echo e($idskemasyac); ?>">
        <input class="form-input" type="file" name="fotox4" accept=".pdf" required>
        <div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> Kirim</button></div>
    </form>
</div>
<?php } else { ?>
<div class="hero-card"><div><small><i class="fas fa-pen-nib"></i> Portofolio</small><h1>Kelola bukti portofolio kompetensi</h1><p>Fungsi tetap sama: isi bukti kompetensi per elemen dan upload bukti pendukung portofolio.</p></div><div class="hero-icon"><i class="fas fa-folder-open"></i></div></div>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-toolbox"></i> Aksi Portofolio</h3><p>Pilih aksi yang ingin dilakukan.</p></div></div>
    <div class="action-grid">
        <div class="action-card"><i class="fas fa-list-check"></i><h4>Kompetensi</h4><p>Pilih unit dan isi bukti relevan untuk elemen kompetensi.</p><a class="btn btn-primary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=kompetensi&uidpes=<?php echo e($menu_uid); ?>&email=<?php echo e(urlencode($uname)); ?>">Kompetensi</a></div>
        <div class="action-card"><i class="fas fa-file-pdf"></i><h4>Bukti Pendukung</h4><p>Upload file bukti pendukung portofolio dalam format PDF.</p><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=uploadbuktiz&uidpes=<?php echo e($menu_uid); ?>&email=<?php echo e(urlencode($uname)); ?>&namas=<?php echo e(urlencode($namax)); ?>">Bukti Pendukung</a></div>
    </div>
</div>
<?php } ?>
</section>
<footer class="page-footer"><span>Asesi Panel &copy; <?php echo date('Y'); ?> LSP SMKN 1 Cibinong</span><span>Modernized by <strong>Codex</strong></span></footer>
</main>
</body>
</html>
