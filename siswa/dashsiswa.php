<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "../lsp_koneksi.php";

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }
function esc_sql($conn, $value) { return mysqli_real_escape_string($conn, (string)$value); }
function initials($name) { $name = trim((string)$name); return $name === '' ? 'AS' : strtoupper(substr($name, 0, 2)); }
function checked_if($value, $target) { return ((string)$value === (string)$target) ? 'checked' : ''; }
function contains_token($csv, $token) { return in_array($token, array_map('trim', explode(',', (string)$csv)), true) ? 'checked' : ''; }

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

$namasiswa = $dUser['nama'] ?? 'Asesi';
$emailasiswa = $dUser['username'] ?? ($dUser['email'] ?? $uidpes);
$iduser = $dUser['id'] ?? ($dUser['id'] ?? '');
$tgluser = $dUser['kode'] ?? '';
$linkttd = $dUser['linkttd'] ?? '';
$menu_uid = rawurlencode((string)$uidpes);
$op = $_REQUEST['op'] ?? '';
$flash_type = '';
$flash_message = '';

function active_menu($name, $active) { return $name === $active ? ' active' : ''; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.APL.01 - LSP SMKN 1 Cibinong</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script>
var win = null;
function NewWindow(mypage,myname,w,h,scroll){
    var leftPosition = (screen.width) ? (screen.width-w)/2 : 0;
    var topPosition = (screen.height) ? (screen.height-h)/2 : 0;
    var settings = 'height='+h+',width='+w+',top='+topPosition+',left='+leftPosition+',scrollbars='+scroll+',resizable';
    win = window.open(mypage,myname,settings);
    if (win) win.focus();
}
</script>
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--green:#22C55E;--red:#EF4444;--orange:#F97316;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{font-size:15px;scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}
.sidebar{width:var(--sidebar-w);height:100vh;max-height:100vh;background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}.sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px;flex-shrink:0}.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,.1)}.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text{line-height:1.2}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:700}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:500;letter-spacing:.5px}.sidebar-nav{padding:16px 12px;flex:1;overflow-y:auto}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.875rem;font-weight:500;transition:all .2s;margin-bottom:2px}.nav-item:hover{background:rgba(255,255,255,.07);color:#fff;text-decoration:none}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;flex-shrink:0}.user-info strong{display:block;color:#fff;font-size:.82rem}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;font-size:.85rem}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}.topbar-title{font-size:1.1rem;font-weight:700;flex:1}.topbar-title span{color:var(--text-sub);font-weight:400;font-size:.875rem;margin-left:8px}.topbar-actions{display:flex;align-items:center;gap:10px}.icon-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-sub);font-size:.9rem}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:600;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.hero-card{background:linear-gradient(135deg,var(--navy) 0%,var(--navy-soft) 100%);color:#fff;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center}.hero-card small{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:rgba(59,191,191,.15);color:#A7F3F3;font-weight:700;font-size:.72rem;letter-spacing:.7px;text-transform:uppercase}.hero-card h1{margin-top:16px;font-size:1.65rem;line-height:1.25}.hero-card p{margin-top:8px;color:rgba(255,255,255,.72);max-width:720px;line-height:1.7}.hero-icon{width:86px;height:86px;border-radius:20px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:2.2rem}
.card{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);animation:fadeUp .4s ease both}@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}.section-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:700;display:flex;align-items:center;gap:10px}.section-head h3 i{color:var(--teal)}.section-head p{font-size:.8rem;color:var(--text-sub);margin-top:4px;line-height:1.6}.alert-box{padding:13px 16px;border-radius:12px;font-size:.86rem;font-weight:600;display:flex;align-items:center;gap:10px;margin-bottom:18px}.alert-success{background:#DCFCE7;color:#15803D;border:1px solid #86EFAC}.alert-warning{background:#FEFCE8;color:#A16207;border:1px solid #FDE68A}.alert-error{background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5}
.grid{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:24px;align-items:start}.profile-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.profile-box{border:1px solid var(--border);border-radius:12px;padding:14px;background:#FBFDFE}.profile-box span{display:block;color:var(--text-sub);font-size:.75rem;margin-bottom:5px}.profile-box strong{display:block;font-size:.9rem}.media-row{display:flex;gap:14px;flex-wrap:wrap}.media-card{flex:1;min-width:170px;border:1px solid var(--border);border-radius:14px;padding:14px;text-align:center;background:#FBFDFE}.media-card img{max-width:100%;height:78px;object-fit:contain;margin-bottom:10px}.status-pill{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:999px;font-size:.78rem;font-weight:700}.pill-green{background:#DCFCE7;color:#15803D}.pill-orange{background:#FFEDD5;color:#C2410C}.pill-blue{background:#DBEAFE;color:#1D4ED8}
.form-grid{display:grid;grid-template-columns:210px 1fr;gap:14px;align-items:start;margin-bottom:14px}.form-label{font-size:.82rem;font-weight:700;color:var(--text-sub);padding-top:10px}.form-input,.form-textarea,.form-select{width:100%;font-size:.9rem;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text-main);background:var(--off);outline:none}.form-textarea{min-height:86px;resize:vertical}.form-input:focus,.form-textarea:focus,.form-select:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-glow);background:#fff}.check-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:10px}.check-item{display:flex;align-items:center;gap:9px;border:1px solid var(--border);border-radius:10px;padding:10px 12px;background:#FBFDFE;font-weight:600;font-size:.86rem}.doc-table{width:100%;border-collapse:collapse;margin-top:14px}.doc-table th,.doc-table td{border:1px solid var(--border);padding:10px 12px;text-align:left;font-size:.86rem}.doc-table th{background:var(--navy);color:#fff}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 20px;border-radius:10px;font-size:.86rem;font-weight:700;cursor:pointer;text-decoration:none;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif}.btn:hover{text-decoration:none}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3)}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}.btn-danger{background:var(--red);color:#fff}.btn-sm{padding:7px 13px;font-size:.78rem}.form-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:20px;padding-top:18px;border-top:1px solid var(--border)}.empty-state{text-align:center;padding:44px 20px;color:var(--text-muted)}.empty-state i{font-size:2.5rem;margin-bottom:12px;display:block;opacity:.5}.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
@media(max-width:1100px){.grid{grid-template-columns:1fr}.profile-grid{grid-template-columns:1fr}}@media(max-width:900px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}.topbar{padding:0 18px}.content{padding:20px}.hero-card{grid-template-columns:1fr}.hero-icon{display:none}.form-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo"><div class="logo-box"><img src="../images/lsplogosmkn1.png" alt="Logo LSP"></div><div class="logo-text"><strong>LSP</strong><span>SMKN 1 CIBINONG</span></div></div>
    <nav class="sidebar-nav">
        <div class="nav-label">Pendaftaran</div>
        <a href="pilihskema.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-paperclip"></i> Pilih Skema</a>
        <a href="pilihunit.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-link"></i> Pilih Unit</a>
        <a href="dashsiswa.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item active"><i class="fas fa-tag"></i> FR.APL. 1</a>
        <a href="apl2.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-chart-line"></i> FR.APL. 2</a>
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
    <div class="sidebar-footer"><div class="user-card"><div class="user-avatar"><?php echo e(initials($namasiswa)); ?></div><div class="user-info"><strong><?php echo e($namasiswa); ?></strong><span>Asesi LSP</span></div><button class="btn-logout" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button></div></div>
</aside>

<main class="main">
<header class="topbar"><div class="topbar-title">FR.APL. 1 <span>Permohonan Sertifikasi Kompetensi</span></div><div class="topbar-actions"><div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div><button class="icon-btn" type="button"><i class="fas fa-bell"></i></button></div></header>
<section class="content">
<?php
if ($op === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $iduser_post = esc_sql($conn, $_POST['iduser'] ?? $iduser);
    $nama = esc_sql($conn, $_POST['nama'] ?? '');
    $email = esc_sql($conn, trim($_POST['emailuser'] ?? $emailasiswa));
    $tmplahir = esc_sql($conn, $_POST['tmplahir'] ?? '');
    $tgllahir = $_POST['tanggal'] ?? '';
    $my_date = date('Y-m-d', strtotime($tgllahir));
    $jeniskelamin = esc_sql($conn, $_POST['jk'] ?? '');
    $kebangsaan = esc_sql($conn, $_POST['kebangsaan'] ?? '');
    $alamat = esc_sql($conn, $_POST['alamat'] ?? '');
    $kodepos = esc_sql($conn, $_POST['kodepos'] ?? '');
    $tlprumah = esc_sql($conn, $_POST['rumah'] ?? '');
    $hp = esc_sql($conn, $_POST['hp'] ?? '');
    $tlpkantor = esc_sql($conn, $_POST['kantor'] ?? '');
    $pendidikan = esc_sql($conn, $_POST['pendidikan'] ?? '');
    $lembaga = esc_sql($conn, $_POST['lembaga'] ?? '');
    $jurusan = esc_sql($conn, $_POST['jurusan'] ?? '');
    $email2 = esc_sql($conn, trim($_POST['emailuser2'] ?? ''));
    $nik = esc_sql($conn, $_POST['nik'] ?? '');

    $cekdata1 = "SELECT * FROM apl1 WHERE email='$email'";
    $ada1 = mysqli_query($conn, $cekdata1);
    if ($ada1 && mysqli_num_rows($ada1) > 0) {
        $query = "UPDATE apl1 SET nik='$nik',namasiswa='$nama',tmplahir='$tmplahir',tgllahir='$my_date',jeniskelamin='$jeniskelamin',kebangsaan='$kebangsaan',alamat='$alamat',kodepos='$kodepos',tlprumah='$tlprumah',hp='$hp',tlpkantor='$tlpkantor',pendidikan='$pendidikan',namalembaga='$lembaga',jurusan='$jurusan',email2='$email2',idasesi='$iduser_post' WHERE email='$email'";
    } else {
        $query = "INSERT INTO apl1 (nik,namasiswa,jurusan,kebangsaan,tmplahir,tgllahir,jeniskelamin,alamat,kodepos,email,email2,validasiapl1,tlprumah,hp,tlpkantor,pendidikan,namalembaga,idasesi) VALUES ('$nik','$nama','$jurusan','$kebangsaan','$tmplahir','$my_date','$jeniskelamin','$alamat','$kodepos','$email','$email2','N','$tlprumah','$hp','$tlpkantor','$pendidikan','$lembaga','$iduser_post')";
    }
    $hasil = mysqli_query($conn, $query);
    $flash_type = $hasil ? 'success' : 'error';
    $flash_message = $hasil ? 'Biodata APL.01 berhasil disimpan.' : 'Biodata gagal disimpan: ' . mysqli_error($conn);
}

if ($op === 'uploadpotospost' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = esc_sql($conn, $_POST['email'] ?? $emailasiswa);
    $idpost = preg_replace('/[^0-9]/', '', (string)($_POST['iduser'] ?? $iduser));
    $nama_clean = preg_replace('/[^A-Za-z0-9_-]/', '', strtok((string)($_POST['namas'] ?? $namasiswa), ' '));
    if (empty($_FILES['fotox3']['name'])) {
        $flash_type = 'warning'; $flash_message = 'File foto belum dipilih.';
    } else {
        $dir = "gambardiri/";
        if (!file_exists($dir)) { @mkdir($dir, 0777, true); }
        $raw_name = str_replace(' ', '', $_FILES['fotox3']['name']);
        $nama_foto = $idpost . $nama_clean . $raw_name;
        $ext = strtolower(pathinfo($nama_foto, PATHINFO_EXTENSION));
        if (!in_array($ext, array('png','jpg','jpeg'), true) || $_FILES['fotox3']['size'] > 2000000) {
            $flash_type = 'warning'; $flash_message = 'Foto harus JPG/PNG dan maksimal 2MB.';
        } elseif (move_uploaded_file($_FILES['fotox3']['tmp_name'], $dir . $nama_foto)) {
            $chk = mysqli_query($conn, "SELECT idasesi FROM apl1 WHERE email='$email'");
            if ($chk && mysqli_num_rows($chk) > 0) {
                mysqli_query($conn, "UPDATE apl1 SET poto='$nama_foto' WHERE email='$email'");
            } else {
                $namas_safe = esc_sql($conn, $namasiswa);
                mysqli_query($conn, "INSERT INTO apl1 (email, namasiswa, poto, validasiapl1, idasesi) VALUES ('$email', '$namas_safe', '$nama_foto', 'N', '$iduser')");
            }
            $flash_type = 'success'; $flash_message = 'Foto berhasil diupload.';
        } else {
            $flash_type = 'error'; $flash_message = 'Foto gagal diupload.';
        }
    }
}

if ($op === 'uploadbuktipost' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = esc_sql($conn, $_POST['email'] ?? $emailasiswa);
    if (empty($_FILES['fotox4']['name'])) {
        $flash_type = 'warning'; $flash_message = 'File bukti belum dipilih.';
    } else {
        $dir = "gambarimages/";
        if (!file_exists($dir)) { @mkdir($dir, 0777, true); }
        $raw_name = str_replace(' ', '', $_FILES['fotox4']['name']);
        $nama_foto = 'apl1' . preg_replace('/[^A-Za-z0-9@._-]/', '', $email) . $raw_name;
        $ext = strtolower(pathinfo($nama_foto, PATHINFO_EXTENSION));
        if ($ext !== 'pdf' || $_FILES['fotox4']['size'] > 2000000) {
            $flash_type = 'warning'; $flash_message = 'Bukti harus PDF dan maksimal 2MB.';
        } elseif (move_uploaded_file($_FILES['fotox4']['tmp_name'], $dir . $nama_foto)) {
            $chk = mysqli_query($conn, "SELECT idasesi FROM apl1 WHERE email='$email'");
            if ($chk && mysqli_num_rows($chk) > 0) {
                mysqli_query($conn, "UPDATE apl1 SET buktiapl1='$nama_foto' WHERE email='$email'");
            } else {
                $namas_safe = esc_sql($conn, $namasiswa);
                mysqli_query($conn, "INSERT INTO apl1 (email, namasiswa, buktiapl1, validasiapl1, idasesi) VALUES ('$email', '$namas_safe', '$nama_foto', 'N', '$iduser')");
            }
            $flash_type = 'success'; $flash_message = 'Bukti pendukung berhasil diupload.';
        } else {
            $flash_type = 'error'; $flash_message = 'Bukti pendukung gagal diupload.';
        }
    }
}

if ($op === 'simpanpermohonan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = esc_sql($conn, trim($_POST['email'] ?? $emailasiswa));
    $ids = esc_sql($conn, $_POST['skema'] ?? '');
    $idp = esc_sql($conn, $_POST['idp'] ?? $iduser);
    $namap = esc_sql($conn, $_POST['namap'] ?? $namasiswa);
    $lain = esc_sql($conn, $_POST['lain'] ?? '');

    $tasesmen = ($_POST['tasesmena'] ?? '') . "," . ($_POST['tasesmenb'] ?? '') . "," . ($_POST['tasesmenc'] ?? '') . "," . ($_POST['tasesmend'] ?? '') . "," . ($_POST['tasesmene'] ?? '');
    $skemas = ($_POST['skemasa'] ?? '') . "," . ($_POST['skemasb'] ?? '') . "," . ($_POST['skemasc'] ?? '') . "," . ($_POST['skemasd'] ?? '');
    $kasesmen = ($_POST['kasesmena'] ?? '') . "," . ($_POST['kasesmenb'] ?? '');
    $karakter = ($_POST['karaktera'] ?? '') . "," . ($_POST['karakterb'] ?? '') . "," . ($_POST['karakterc'] ?? '');
    $acuan = ($_POST['acuana'] ?? '') . "," . ($_POST['acuanb'] ?? '') . "," . ($_POST['acuanc'] ?? '') . "," . ($_POST['acuand'] ?? '') . "," . ($_POST['acuane'] ?? '');

    $tasesmen = esc_sql($conn, $tasesmen);
    $skemas = esc_sql($conn, $skemas);
    $kasesmen = esc_sql($conn, $kasesmen);
    $karakter = esc_sql($conn, $karakter);
    $acuan = esc_sql($conn, $acuan);

    $tuk = esc_sql($conn, $_POST['idtuk'] ?? '');
    $tanggal = esc_sql($conn, trim($_POST['tanggalp'] ?? $tgluser));

    $tglp = trim($_POST['tglpel'] ?? '');
    $tglpp = esc_sql($conn, date('Y-m-d', strtotime($tglp)));

    $cek = "SELECT * FROM permohonan WHERE email='$email' AND idskema='$ids' AND tanggal='$tanggal'";
    $ada = mysqli_query($conn, $cek);

    if ($ada && mysqli_num_rows($ada) > 0) {
        $query = "UPDATE permohonan SET 
                    tujuanasesmen='$tasesmen',
                    lainnya='$lain',
                    sertifikasi='$skemas',
                    kontekasesmen='$kasesmen',
                    karakteristik='$karakter',
                    acuanp='$acuan',
                    tuk='$tuk',
                    tanggalp='$tglpp'
                  WHERE email='$email' 
                    AND idskema='$ids' 
                    AND tanggal='$tanggal'";
    } else {
        $query = "INSERT INTO permohonan 
                    (idskema,email,tujuanasesmen,lainnya,sertifikasi,kontekasesmen,karakteristik,acuanp,tuk,tanggal,tanggalp) 
                  VALUES 
                    ('$ids','$email','$tasesmen','$lain','$skemas','$kasesmen','$karakter','$acuan','$tuk','$tanggal','$tglpp')";
    }

    $ok = mysqli_query($conn, $query);
    $flash_type = $ok ? 'success' : 'error';
    $flash_message = $ok ? 'Pengajuan sertifikasi berhasil disimpan.' : 'Pengajuan sertifikasi gagal: ' . mysqli_error($conn);
}

$qApl = "SELECT * FROM apl1 WHERE TRIM(email)='$uidpes_safe' LIMIT 1";
$rApl = mysqli_query($conn, $qApl);
$dApl = ($rApl && mysqli_num_rows($rApl) > 0) ? mysqli_fetch_array($rApl) : array();
$has_apl = !empty($dApl);
?>

<div class="hero-card"><div><small><i class="fas fa-tag"></i> FR.APL.01</small><h1>Kelola permohonan sertifikasi kompetensi</h1><p>Lengkapi biodata, tanda tangan, foto, bukti pendukung, lalu ajukan permohonan sertifikasi sesuai skema yang sudah dipilih.</p></div><div class="hero-icon"><i class="fas fa-file-signature"></i></div></div>

<?php if ($flash_message !== ''): ?><div class="alert-box alert-<?php echo e($flash_type); ?>"><i class="fas <?php echo $flash_type === 'success' ? 'fa-circle-check' : ($flash_type === 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-xmark'); ?>"></i><strong><?php echo e($flash_message); ?></strong></div><?php endif; ?>

<?php
if ($op === 'edit') {
    $nama = $dApl['namasiswa'] ?? $namasiswa;
    $nik = $dApl['nik'] ?? '';
    $tmplahir = $dApl['tmplahir'] ?? '';
    $tgllahir = !empty($dApl['tgllahir']) ? date('d-m-Y', strtotime($dApl['tgllahir'])) : '';
    $jk = $dApl['jeniskelamin'] ?? '';
    $kebangsaan = $dApl['kebangsaan'] ?? '';
    $alamat = $dApl['alamat'] ?? '';
    $kodepos = $dApl['kodepos'] ?? '';
    $rumah = $dApl['tlprumah'] ?? '';
    $hp = $dApl['hp'] ?? '';
    $tlpkantor = $dApl['tlpkantor'] ?? '';
    $pendidikan = $dApl['pendidikan'] ?? '';
    $lembaga = $dApl['namalembaga'] ?? '';
    $jurusan = $dApl['jurusan'] ?? '';
    $email2 = $dApl['email2'] ?? '';
?>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-user-pen"></i> Biodata Pemohon</h3><p>Lengkapi data pribadi dan data sekolah/lembaga.</p></div><a href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a></div>
    <form method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=update&uidpes=<?php echo e($menu_uid); ?>">
        <input type="hidden" name="iduser" value="<?php echo e($iduser); ?>">
        <input type="hidden" name="emailuser" value="<?php echo e($emailasiswa); ?>">
        <div class="form-grid"><div class="form-label">Nama Lengkap</div><input class="form-input" type="text" name="nama" value="<?php echo e($nama); ?>" required></div>
        <div class="form-grid"><div class="form-label">No. KTP/NIK</div><input class="form-input" type="text" name="nik" value="<?php echo e($nik); ?>" required></div>
        <div class="form-grid"><div class="form-label">Tempat / Tgl Lahir</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:12px"><input class="form-input" type="text" name="tmplahir" value="<?php echo e($tmplahir); ?>"><input class="form-input" type="text" name="tanggal" value="<?php echo e($tgllahir); ?>" placeholder="DD-MM-YYYY"></div></div>
        <div class="form-grid"><div class="form-label">Jenis Kelamin</div><div class="check-grid"><label class="check-item"><input type="radio" name="jk" value="lk" <?php echo checked_if($jk,'lk'); ?>> Laki-laki</label><label class="check-item"><input type="radio" name="jk" value="pr" <?php echo checked_if($jk,'pr'); ?>> Perempuan</label></div></div>
        <div class="form-grid"><div class="form-label">Kebangsaan</div><input class="form-input" type="text" name="kebangsaan" value="<?php echo e($kebangsaan); ?>"></div>
        <div class="form-grid"><div class="form-label">Alamat Rumah</div><textarea class="form-textarea" name="alamat"><?php echo e($alamat); ?></textarea></div>
        <div class="form-grid"><div class="form-label">Kode Pos</div><input class="form-input" type="text" name="kodepos" value="<?php echo e($kodepos); ?>"></div>
        <div class="form-grid"><div class="form-label">Telepon / HP</div><div style="display:grid;grid-template-columns:1fr 1fr;gap:12px"><input class="form-input" type="text" name="rumah" value="<?php echo e($rumah); ?>" placeholder="Rumah"><input class="form-input" type="text" name="hp" value="<?php echo e($hp); ?>" placeholder="HP" required></div></div>
        <div class="form-grid"><div class="form-label">Email Aktif</div><input class="form-input" type="email" name="emailuser2" value="<?php echo e($email2); ?>" required></div>
        <div class="form-grid"><div class="form-label">Pendidikan</div><input class="form-input" type="text" name="pendidikan" value="<?php echo e($pendidikan); ?>"></div>
        <div class="form-grid"><div class="form-label">Lembaga/Sekolah</div><input class="form-input" type="text" name="lembaga" value="<?php echo e($lembaga); ?>"></div>
        <div class="form-grid"><div class="form-label">Jurusan</div><input class="form-input" type="text" name="jurusan" value="<?php echo e($jurusan); ?>"></div>
        <div class="form-grid"><div class="form-label">Telp Kantor/Sekolah</div><input class="form-input" type="text" name="kantor" value="<?php echo e($tlpkantor); ?>"></div>
        <div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-floppy-disk"></i> Simpan Biodata</button><button class="btn btn-danger" type="reset"><i class="fas fa-rotate-left"></i> Reset</button></div>
    </form>
</div>
<?php
} elseif ($op === 'uploadpotos') {
?>
<div class="card"><div class="section-head"><div><h3><i class="fas fa-camera"></i> Upload Foto</h3><p>File JPG/PNG, maksimal 2MB.</p></div><a href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>" class="btn btn-secondary">Kembali</a></div><form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=uploadpotospost&uidpes=<?php echo e($menu_uid); ?>"><input type="hidden" name="namas" value="<?php echo e($namasiswa); ?>"><input type="hidden" name="iduser" value="<?php echo e($iduser); ?>"><input type="hidden" name="email" value="<?php echo e($emailasiswa); ?>"><div class="form-grid"><div class="form-label">Pilih Foto</div><input class="form-input" type="file" name="fotox3" accept=".jpg,.jpeg,.png" required></div><div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> Upload Foto</button></div></form></div>
<?php
} elseif ($op === 'uploadbukti') {
?>
<div class="card"><div class="section-head"><div><h3><i class="fas fa-file-pdf"></i> Upload Bukti Pendukung</h3><p>File PDF, maksimal 2MB.</p></div><a href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>" class="btn btn-secondary">Kembali</a></div><form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=uploadbuktipost&uidpes=<?php echo e($menu_uid); ?>"><input type="hidden" name="namas" value="<?php echo e($namasiswa); ?>"><input type="hidden" name="email" value="<?php echo e($emailasiswa); ?>"><div class="form-grid"><div class="form-label">Pilih PDF</div><input class="form-input" type="file" name="fotox4" accept=".pdf" required></div><div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-upload"></i> Upload Bukti</button></div></form></div>
<?php
} elseif ($op === 'pengajuanserti') {
    $qSkema = "SELECT * FROM skemasiswa WHERE emailsiswa='$uidpes_safe' ORDER BY id_skemasiswa DESC LIMIT 1";
    $rSkema = mysqli_query($conn, $qSkema);
    $dSkema = ($rSkema && mysqli_num_rows($rSkema) > 0) ? mysqli_fetch_array($rSkema) : array();

    $idskema = $dSkema['idskema'] ?? '';

    $qSkemaName = "SELECT * FROM skema WHERE idskema='".esc_sql($conn,$idskema)."' LIMIT 1";
    $rSkemaName = mysqli_query($conn, $qSkemaName);
    $dSkemaName = $rSkemaName ? mysqli_fetch_array($rSkemaName) : array();

    $namaskema = $dSkemaName['namaskema'] ?? 'Skema belum dipilih';

    $tglregistrasi_raw = $tgluser ?? '';

    if ($tglregistrasi_raw === '' || $tglregistrasi_raw === '0000-00-00') {
        $tglregistrasi_view = date('d-m-Y');
        $tglregistrasi_db = date('Y-m-d');
    } else {
        $tglregistrasi_view = date('d-m-Y', strtotime($tglregistrasi_raw));
        $tglregistrasi_db = date('Y-m-d', strtotime($tglregistrasi_raw));
    }

    $qPerm = "SELECT * FROM permohonan 
              WHERE email='$uidpes_safe' 
              AND idskema='".esc_sql($conn,$idskema)."' 
              AND tanggal='".esc_sql($conn,$tglregistrasi_db)."' 
              LIMIT 1";

    $rPerm = mysqli_query($conn, $qPerm);
    $dPerm = ($rPerm && mysqli_num_rows($rPerm) > 0) ? mysqli_fetch_array($rPerm) : array();
?>
<div class="card">
    <div class="section-head"><div><h3><i class="fas fa-award"></i> Pengajuan Sertifikasi</h3><p>Skema: <strong><?php echo e($namaskema); ?></strong></p></div><a href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>" class="btn btn-secondary">Kembali</a></div>
    <?php if ($idskema === ''): ?><div class="empty-state"><i class="fas fa-circle-exclamation"></i><strong>Skema belum dipilih</strong><p>Pilih skema terlebih dahulu.</p></div><?php else: ?>
    <form method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=simpanpermohonan&uidpes=<?php echo e($menu_uid); ?>" onsubmit="return confirm('Simpan pengajuan sertifikasi?')">
        <input type="hidden" name="email" value="<?php echo e($emailasiswa); ?>"><input type="hidden" name="namap" value="<?php echo e($namasiswa); ?>"><input type="hidden" name="idp" value="<?php echo e($iduser); ?>"><input type="hidden" name="skema" value="<?php echo e($idskema); ?>">
        <div class="form-grid"><div class="form-label">Tujuan Asesmen</div><div class="check-grid"><label class="check-item"><input type="checkbox" name="tasesmena" value="rp" <?php echo contains_token($dPerm['tujuanasesmen'] ?? '', 'rp'); ?>> Sertifikasi</label><label class="check-item"><input type="checkbox" name="tasesmenb" value="pp" <?php echo contains_token($dPerm['tujuanasesmen'] ?? '', 'pp'); ?>> PKT</label><label class="check-item"><input type="checkbox" name="tasesmenc" value="rc" <?php echo contains_token($dPerm['tujuanasesmen'] ?? '', 'rc'); ?>> RPL</label><label class="check-item"><input type="checkbox" name="tasesmend" value="sr" <?php echo contains_token($dPerm['tujuanasesmen'] ?? '', 'sr'); ?>> Sertifikasi Ulang</label><label class="check-item"><input type="checkbox" name="tasesmene" value="la" <?php echo contains_token($dPerm['tujuanasesmen'] ?? '', 'la'); ?>> Lainnya</label></div></div>
        <div class="form-grid"><div class="form-label">Lainnya</div><input class="form-input" type="text" name="lain" value="<?php echo e($dPerm['lainnya'] ?? ''); ?>"></div>
        <div class="form-grid"><div class="form-label">Skema Sertifikasi</div><div class="check-grid"><label class="check-item"><input type="checkbox" name="skemasa" value="un" <?php echo contains_token($dPerm['sertifikasi'] ?? '', 'un'); ?>> Unit</label><label class="check-item"><input type="checkbox" name="skemasb" value="kl" <?php echo contains_token($dPerm['sertifikasi'] ?? '', 'kl'); ?>> Klaster</label><label class="check-item"><input type="checkbox" name="skemasc" value="ok" <?php echo contains_token($dPerm['sertifikasi'] ?? '', 'ok'); ?>> Okupasi</label><label class="check-item"><input type="checkbox" name="skemasd" value="kk" <?php echo contains_token($dPerm['sertifikasi'] ?? '', 'kk'); ?>> KKNI</label></div></div>
        <div class="form-grid"><div class="form-label">Konteks Asesmen</div><div class="check-grid"><label class="check-item"><input type="checkbox" name="kasesmena" value="tsi" <?php echo contains_token($dPerm['kontekasesmen'] ?? '', 'tsi'); ?>> TUK Simulasi</label><label class="check-item"><input type="checkbox" name="kasesmenb" value="tk" <?php echo contains_token($dPerm['kontekasesmen'] ?? '', 'tk'); ?>> Tempat Kerja</label><label class="check-item"><input type="checkbox" name="karaktera" value="pr" <?php echo contains_token($dPerm['karakteristik'] ?? '', 'pr'); ?>> Produk</label><label class="check-item"><input type="checkbox" name="karakterb" value="si" <?php echo contains_token($dPerm['karakteristik'] ?? '', 'si'); ?>> Sistem</label><label class="check-item"><input type="checkbox" name="karakterc" value="tkk" <?php echo contains_token($dPerm['karakteristik'] ?? '', 'tkk'); ?>> Tempat Kerja</label></div></div>
        <div class="form-grid"><div class="form-label">Acuan Pembanding</div><div class="check-grid"><label class="check-item"><input type="checkbox" name="acuana" value="sk" <?php echo contains_token($dPerm['acuanp'] ?? '', 'sk'); ?>> Standar Kompetensi</label><label class="check-item"><input type="checkbox" name="acuanb" value="sp" <?php echo contains_token($dPerm['acuanp'] ?? '', 'sp'); ?>> Standar Produk</label><label class="check-item"><input type="checkbox" name="acuanc" value="ss" <?php echo contains_token($dPerm['acuanp'] ?? '', 'ss'); ?>> Standar Sistem</label><label class="check-item"><input type="checkbox" name="acuand" value="re" <?php echo contains_token($dPerm['acuanp'] ?? '', 're'); ?>> Regulasi</label><label class="check-item"><input type="checkbox" name="acuane" value="so" <?php echo contains_token($dPerm['acuanp'] ?? '', 'so'); ?>> SOP</label></div></div>
        <div class="form-grid"><div class="form-label">TUK</div><select class="form-select" name="idtuk"><?php $rtuk=mysqli_query($conn,"SELECT * FROM tuk"); while($tuk=mysqli_fetch_array($rtuk)){ $sel=(($dPerm['tuk'] ?? '')==$tuk['idtuk'])?'selected':''; echo "<option value='".e($tuk['idtuk'])."' $sel>".e($tuk['namatuk'])."</option>"; } ?></select></div>
        <div class="form-grid"><div class="form-label">Tanggal Pelaksanaan</div><input class="form-input" type="text" name="tglpel" value="<?php echo e(!empty($dPerm['tanggalp']) ? date('d-m-Y', strtotime($dPerm['tanggalp'])) : ''); ?>" required></div>
        <div class="form-grid"><div class="form-label">Tanggal Registrasi</div><input class="form-input" type="text" name="tanggalp" value="<?php echo e($tgluser); ?>" readonly></div>
        <div class="form-actions"><button class="btn btn-primary" type="submit"><i class="fas fa-floppy-disk"></i> Simpan Pengajuan</button></div>
    </form><?php endif; ?>
</div>
<?php
} else {
    $poto = !empty($dApl['poto']) ? 'gambardiri/'.$dApl['poto'] : 'gambardiri/tidakada.png';
    $ttd = !empty($linkttd) ? '../imgttd/'.$linkttd : '../imgttd/tidakada.png';
    $bukti = !empty($dApl['buktiapl1']) ? 'gambarimages/'.$dApl['buktiapl1'] : '';
    $valid = $dApl['validasiapl1'] ?? 'N';
?>
<div class="grid">
    <div class="card">
        <div class="section-head"><div><h3><i class="fas fa-user-graduate"></i> Ringkasan APL.01</h3><p>Status kelengkapan data permohonan sertifikasi.</p></div><span class="status-pill <?php echo $valid === 'Y' ? 'pill-green' : 'pill-orange'; ?>"><i class="fas <?php echo $valid === 'Y' ? 'fa-circle-check' : 'fa-hourglass-half'; ?>"></i> <?php echo $valid === 'Y' ? 'Terverifikasi' : 'Menunggu Validasi'; ?></span></div>
        <div class="profile-grid"><div class="profile-box"><span>Nama Registrasi</span><strong><?php echo e($namasiswa); ?></strong></div><div class="profile-box"><span>User ID</span><strong><?php echo e($emailasiswa); ?></strong></div><div class="profile-box"><span>Email Aktif</span><strong><?php echo e($dApl['email2'] ?? '-'); ?></strong></div></div>
        <div style="height:18px"></div>
        <div class="media-row"><div class="media-card"><img src="<?php echo e($poto); ?>" alt="Foto"><strong>Foto Asesi</strong></div><div class="media-card"><img src="<?php echo e($ttd); ?>" alt="Tanda tangan"><strong>Tanda Tangan</strong></div><div class="media-card"><?php if($bukti): ?><a class="btn btn-secondary btn-sm" href="<?php echo e($bukti); ?>" target="_blank"><i class="fas fa-file-pdf"></i> Lihat Bukti</a><?php else: ?><span class="status-pill pill-orange">Bukti belum ada</span><?php endif; ?><div style="margin-top:12px"><strong>Bukti Pendukung</strong></div></div></div>
        <div class="form-actions"><a class="btn btn-primary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=edit&uidpes=<?php echo e($menu_uid); ?>"><i class="fas fa-user-pen"></i> <?php echo $has_apl ? 'Edit Biodata' : 'Lengkapi Biodata'; ?></a><a class="btn btn-secondary" href="./ttdb/index.php?id=<?php echo e($iduser); ?>" onclick="NewWindow(this.href,'ttd','420','440','yes');return false"><i class="fas fa-signature"></i> Tanda Tangan</a><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=uploadpotos&uidpes=<?php echo e($menu_uid); ?>"><i class="fas fa-camera"></i> Upload Foto</a><a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=uploadbukti&uidpes=<?php echo e($menu_uid); ?>"><i class="fas fa-file-pdf"></i> Bukti Pendukung</a><a class="btn btn-primary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=pengajuanserti&uidpes=<?php echo e($menu_uid); ?>"><i class="fas fa-award"></i> Pengajuan Sertifikasi</a></div>
    </div>
    <div class="card"><div class="section-head"><div><h3><i class="fas fa-list-check"></i> Alur APL.01</h3><p>Urutan yang disarankan sebelum lanjut ke APL.02.</p></div></div><table class="doc-table"><tr><th>No</th><th>Langkah</th></tr><tr><td>1</td><td>Lengkapi biodata pemohon</td></tr><tr><td>2</td><td>Buat tanda tangan digital</td></tr><tr><td>3</td><td>Upload foto diri</td></tr><tr><td>4</td><td>Upload bukti pendukung PDF</td></tr><tr><td>5</td><td>Simpan pengajuan sertifikasi</td></tr></table></div>
</div>
<?php } ?>
</section>
<footer class="page-footer"><span>Asesi Panel &copy; <?php echo date('Y'); ?> LSP SMKN 1 Cibinong</span><span>Modernized by <strong>Codex</strong></span></footer>
</main>
</body>
</html>
