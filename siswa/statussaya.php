<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
include "../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function esc_sql($conn, $value) {
    return mysqli_real_escape_string($conn, (string)$value);
}

function initials($name) {
    $name = trim((string)$name);
    if ($name === '') return 'AS';
    $parts = preg_split('/\s+/', $name);
    if (count($parts) >= 2) {
        return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
    }
    return strtoupper(substr($name, 0, 2));
}

function status_badge($type, $text) {
    $icon = $type === 'success' ? 'fa-circle-check' : ($type === 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-xmark');
    return "<span class='status-badge status-$type'><i class='fas $icon'></i> ".e($text)."</span>";
}

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<!DOCTYPE html><html lang='id'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>Login Diperlukan</title><style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#F4F8FA;color:#1A2E3B}.login-card{text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)}.login-card a{display:inline-block;margin-top:14px;padding:10px 18px;border-radius:10px;background:#3BBFBF;color:#fff;text-decoration:none;font-weight:700}</style></head><body>";
    echo "<div class='login-card'><h3>Anda Harus Login Dahulu!</h3><a href='../lsp_login.php'>Kembali ke Login</a></div></body></html>";
    exit;
}

$uname = $_GET['uidpes'] ?? ($_SESSION['username'] ?? '');
if ($uname === '') $uname = $_SESSION['username'] ?? '';
$uname_safe = esc_sql($conn, $uname);
$menu_uid = rawurlencode((string)$uname);

$resultx = mysqli_query($conn, "SELECT * FROM users WHERE username='$uname_safe' LIMIT 1");
$hasilx = $resultx ? mysqli_fetch_array($resultx) : array();
$namax = $hasilx['nama'] ?? 'Asesi';
$id_user = $hasilx['id'] ?? '';
$iduser = $id_user;

$email = trim((string)$uname);
$email_safe = esc_sql($conn, $email);

$idskema = '';
$tglrekskema = '';
$idasesist = '';
$skema_name = '';
$skema_code = '';

$status_apl2 = '';

$status = array(
    'permohonan' => array('type' => 'danger', 'label' => 'Belum Terdaftar', 'details' => array()),
    'apl1' => array('type' => 'danger', 'label' => 'Belum Terdaftar', 'details' => array()),
    'apl2' => array('type' => 'danger', 'label' => 'Belum Terdaftar', 'details' => array()),
    'mak5' => array('type' => 'danger', 'label' => 'Belum Mengisi', 'details' => array()),
    'rekom_apl1' => array('type' => 'warning', 'label' => 'Belum Ada Rekomendasi', 'details' => array()),
    'rekom_apl2' => array('type' => 'warning', 'label' => 'Belum Ada Rekomendasi', 'details' => array())
);

$cekskemasisa = mysqli_query($conn, "SELECT * FROM skemasiswa WHERE emailsiswa='$email_safe' ORDER BY id_skemasiswa DESC LIMIT 1");
$cekskemasisb = $cekskemasisa ? mysqli_fetch_array($cekskemasisa) : array();
if ($cekskemasisb) {
    $tglrekskema = $cekskemasisb['tglrekskema'] ?? '';
    $status_apl2 = $cekskemasisb['statusapl2'] ?? '';
    $idskema = $cekskemasisb['idskema'] ?? '';
    $idskema_safe = esc_sql($conn, $idskema);

    $qSkema = mysqli_query($conn, "SELECT namaskema,noskema FROM skema WHERE idskema='$idskema_safe' LIMIT 1");
    $dSkema = $qSkema ? mysqli_fetch_array($qSkema) : array();
    $skema_name = $dSkema['namaskema'] ?? '';
    $skema_code = $dSkema['noskema'] ?? '';
}

if ($tglrekskema !== '') {
    $tglrekskema_safe = esc_sql($conn, $tglrekskema);
    $qpermohonana = mysqli_query($conn, "SELECT email,tanggal FROM permohonan WHERE (email='$email_safe' OR email='".esc_sql($conn, $dUser['email'] ?? '')."') AND tanggal='$tglrekskema_safe' GROUP BY email,tanggal");
    if ($qpermohonana && mysqli_num_rows($qpermohonana) > 0) {
        $row = mysqli_fetch_array($qpermohonana);
        $status['permohonan'] = array(
            'type' => 'success',
            'label' => 'Sudah Terdaftar',
            'details' => array('Tanggal: '.($row['tanggal'] ?? '-'), 'User ID: '.($row['email'] ?? '-'))
        );
    }
}
if ($status['permohonan']['type'] === 'danger' && ($skema_code !== '' || !empty($dskemasiswa))) {
    $status['permohonan'] = array(
        'type' => 'success',
        'label' => 'Sudah Terdaftar',
        'details' => array('Skema: '.($skema_code !== '' ? $skema_code : 'Terdaftar'))
    );
}

$qapl1a = mysqli_query($conn, "SELECT email2,namasiswa,validasiapl1,idasesi FROM apl1 WHERE email='$email_safe' GROUP BY email2,namasiswa,validasiapl1,idasesi");
if ($qapl1a && mysqli_num_rows($qapl1a) > 0) {
    while ($qapl1b = mysqli_fetch_array($qapl1a)) {
        $idasesist = $qapl1b['idasesi'] ?? $idasesist;
        $is_valid = ($qapl1b['validasiapl1'] ?? '') === 'Y';
        $status['apl1'] = array(
            'type' => $is_valid ? 'success' : 'danger',
            'label' => $is_valid ? 'Sudah Divalidasi' : 'Belum Divalidasi',
            'details' => array('Nama: '.($qapl1b['namasiswa'] ?? '-'), 'Email: '.($qapl1b['email2'] ?? '-'), 'Data biodata sudah terdaftar')
        );
    }
}

$qapl2a = mysqli_query($conn, "SELECT email,waktu FROM apl2 WHERE email='$email_safe' GROUP BY email,waktu ORDER BY waktu DESC");
if ($qapl2a && mysqli_num_rows($qapl2a) > 0) {
    while ($qapl2b = mysqli_fetch_array($qapl2a)) {
        $waktu = $qapl2b['waktu'] ?? '';
        $waktu_safe = esc_sql($conn, $waktu);
        $cekvlapl2a = mysqli_query($conn, "SELECT idadsesi,idskema,waktu,svalidasi FROM apl2 WHERE email='$email_safe' AND waktu='$waktu_safe' AND svalidasi='T' GROUP BY idadsesi,idskema,waktu,svalidasi");
        $pending_count = $cekvlapl2a ? mysqli_num_rows($cekvlapl2a) : 0;
        $cekdiskemasisa = mysqli_query($conn, "SELECT emailsiswa,statusapl2,idskema FROM skemasiswa WHERE idskema='".esc_sql($conn, $idskema)."' AND emailsiswa='$email_safe' AND statusapl2='Y'");
        $recommended = $cekdiskemasisa && mysqli_num_rows($cekdiskemasisa) > 0;

        if (!$recommended) {
            $status['apl2'] = array(
                'type' => 'danger',
                'label' => 'Belum Direkomendasi',
                'details' => array('Tanggal: '.$waktu, 'Hubungi asesor untuk rekomendasi APL2')
            );
        } elseif ($pending_count > 0) {
            $status['apl2'] = array(
                'type' => 'warning',
                'label' => 'Belum Divalidasi Semua',
                'details' => array('Tanggal: '.$waktu, 'Sebagian data masih perlu validasi asesor')
            );
        } else {
            $status['apl2'] = array(
                'type' => 'success',
                'label' => 'Sudah Divalidasi',
                'details' => array('Tanggal: '.$waktu, 'User ID: '.$email)
            );
        }
        break;
    }
}

$mak5a = mysqli_query($conn, "SELECT idasesi FROM mak5 WHERE idasesi='$email_safe' GROUP BY idasesi");
if ($mak5a && mysqli_num_rows($mak5a) > 0) {
    $status['mak5'] = array('type' => 'success', 'label' => 'Sudah Mengisi', 'details' => array('Umpan balik FR.AK.03 sudah tersimpan'));
}

if ($idasesist !== '' || $iduser !== '') {
    $idasesist_safe = esc_sql($conn, $idasesist !== '' ? $idasesist : $iduser);
    $idskema_safe = esc_sql($conn, $idskema);
    $ckrekomedasia = mysqli_query($conn, "SELECT namarekom,idskema,idasesi,rekom,catatan FROM rekomendasi WHERE idskema='$idskema_safe' AND (idasesi='$idasesist_safe' OR idasesi='$iduser') AND namarekom='apl1lsp' LIMIT 1");
    $ckrekomedasib = $ckrekomedasia ? mysqli_fetch_array($ckrekomedasia) : array();
    if ($ckrekomedasib) {
        $accepted = ($ckrekomedasib['rekom'] ?? '') === 'L';
        $status['rekom_apl1'] = array(
            'type' => $accepted ? 'success' : 'danger',
            'label' => $accepted ? 'Diterima' : 'Ditolak',
            'details' => array('Catatan: '.(($ckrekomedasib['catatan'] ?? '') !== '' ? $ckrekomedasib['catatan'] : '-'))
        );
    }

    $ckrekomedasiaapl2 = mysqli_query($conn, "SELECT namarekom,idskema,idasesi,rekom,catatan FROM rekomendasi WHERE (idasesi='$idasesist_safe' OR idasesi='$iduser') AND namarekom='apl2' LIMIT 1");
    $ckrekomedasibapl2 = $ckrekomedasiaapl2 ? mysqli_fetch_array($ckrekomedasiaapl2) : array();
    if ($ckrekomedasibapl2) {
        $accepted = ($ckrekomedasibapl2['rekom'] ?? '') === 'L';
        $status['rekom_apl2'] = array(
            'type' => $accepted ? 'success' : 'danger',
            'label' => $accepted ? 'Diterima' : 'Ditolak',
            'details' => array('Catatan: '.(($ckrekomedasibapl2['catatan'] ?? '') !== '' ? $ckrekomedasibapl2['catatan'] : '-'))
        );
    } elseif ($status_apl2 === 'Y') {
        $status['rekom_apl2'] = array(
            'type' => 'success',
            'label' => 'Diterima',
            'details' => array('Telah divalidasi oleh Asesor')
        );
    }
}

$completed = 0;
foreach ($status as $item) {
    if ($item['type'] === 'success') $completed++;
}
$total = count($status);
$progress = (int)round(($completed / $total) * 100);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cek Status - LSP</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--green:#22C55E;--red:#EF4444;--orange:#F97316;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{font-size:15px;scroll-behavior:smooth}body{font-family:'Plus Jakarta Sans',Arial,sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}a{text-decoration:none}
.sidebar{width:var(--sidebar-w);height:100vh;max-height:100vh;background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}.sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px;flex-shrink:0}.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,.1)}.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text{line-height:1.2}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:800}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:600;letter-spacing:.5px}.sidebar-nav{padding:16px 12px;flex:1;overflow-y:auto}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:800;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.58);font-size:.875rem;font-weight:600;transition:all .2s;margin-bottom:2px}.nav-item:hover{background:rgba(255,255,255,.07);color:#fff}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:#fff;flex-shrink:0}.user-info{min-width:0}.user-info strong{display:block;color:#fff;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:136px}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;font-size:.85rem}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}.topbar-title{font-size:1.1rem;font-weight:800;flex:1}.topbar-title span{color:var(--text-sub);font-weight:500;font-size:.875rem;margin-left:8px}.topbar-actions{display:flex;align-items:center;gap:10px}.icon-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-sub);font-size:.9rem}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:800;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px;white-space:nowrap}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.hero-card{background:linear-gradient(135deg,var(--navy) 0%,var(--navy-soft) 100%);color:#fff;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center}.hero-card small{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:rgba(59,191,191,.15);color:#A7F3F3;font-weight:800;font-size:.72rem;letter-spacing:.7px;text-transform:uppercase}.hero-card h1{margin-top:16px;font-size:1.65rem;line-height:1.25}.hero-card p{margin-top:8px;color:rgba(255,255,255,.72);max-width:760px;line-height:1.7}.hero-icon{width:86px;height:86px;border-radius:20px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:2.2rem}
.card{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);animation:fadeUp .4s ease both}@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}.section-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:800;display:flex;align-items:center;gap:10px}.section-head h3 i{color:var(--teal)}.section-head p{font-size:.82rem;color:var(--text-sub);margin-top:4px;line-height:1.6}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:10px;font-size:.86rem;font-weight:800;cursor:pointer;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',Arial,sans-serif}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3)}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}
.overview{display:grid;grid-template-columns:1fr 270px;gap:18px}.profile-card{background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow)}.profile-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.profile-item{border:1px solid var(--border);background:#FBFDFE;border-radius:12px;padding:14px}.profile-item span{display:block;color:var(--text-sub);font-size:.75rem;font-weight:800;margin-bottom:5px}.profile-item strong{display:block;font-size:.9rem;line-height:1.45}.progress-card{background:linear-gradient(135deg,var(--teal-light),#fff);border:1px solid #B7EFEF;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);display:flex;flex-direction:column;justify-content:center}.progress-ring{width:124px;height:124px;border-radius:50%;background:conic-gradient(var(--teal) <?php echo $progress; ?>%,#DDE8ED 0);display:flex;align-items:center;justify-content:center;margin:0 auto 14px}.progress-ring span{width:88px;height:88px;border-radius:50%;background:#fff;display:flex;align-items:center;justify-content:center;font-size:1.35rem;font-weight:800;color:var(--teal-dark)}.progress-card p{text-align:center;color:var(--text-sub);font-weight:700}
.status-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.status-card{border:1px solid var(--border);background:#fff;border-radius:14px;padding:18px;box-shadow:0 2px 12px rgba(15,42,58,.04);display:grid;grid-template-columns:44px 1fr;gap:14px;min-height:150px}.status-icon{width:44px;height:44px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.05rem}.status-icon.success{background:#DCFCE7;color:#15803D}.status-icon.warning{background:#FEFCE8;color:#A16207}.status-icon.danger{background:#FEF2F2;color:#B91C1C}.status-card h4{font-size:.98rem;margin-bottom:8px}.status-badge{display:inline-flex;align-items:center;gap:7px;border-radius:999px;padding:5px 10px;font-size:.76rem;font-weight:800;margin-bottom:10px}.status-success{background:#DCFCE7;color:#15803D}.status-warning{background:#FEFCE8;color:#A16207}.status-danger{background:#FEF2F2;color:#B91C1C}.detail-list{display:flex;flex-direction:column;gap:5px;color:var(--text-sub);font-size:.8rem;line-height:1.5}.detail-list span{display:flex;gap:7px}.detail-list i{color:var(--teal);margin-top:3px}.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
@media(max-width:1000px){.overview{grid-template-columns:1fr}.profile-grid{grid-template-columns:1fr}.status-grid{grid-template-columns:1fr}}@media(max-width:900px){.sidebar{position:relative;transform:none;width:100%;min-height:auto}.main{margin-left:0}.topbar{padding:0 18px}.content{padding:20px}.hero-card{grid-template-columns:1fr}.hero-icon{display:none}body{display:block}.sidebar-footer{display:none}.sidebar-nav{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:4px}.nav-label{grid-column:1/-1}.topbar-title span{display:none}}@media(max-width:520px){.content{padding:16px}.card,.hero-card,.profile-card,.progress-card{padding:20px}.section-head{display:block}.section-head .btn{margin-top:10px}.date-chip{display:none}.status-card{grid-template-columns:1fr}.page-footer{display:block;line-height:1.9}}
@media print{body{display:block;background:#fff}.sidebar,.topbar,.page-footer,.hero-card,.no-print{display:none!important}.main{margin-left:0}.content{padding:0}.card,.profile-card,.progress-card,.status-card{box-shadow:none}.status-grid{grid-template-columns:1fr 1fr}.overview{grid-template-columns:1fr}.progress-card{display:none}}
</style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-box"><img src="../images/lsplogosmkn1.png" alt="Logo LSP"></div>
        <div class="logo-text"><strong>LSP</strong><span>SMKN 1 CIBINONG</span></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Pendaftaran</div>
        <a href="pilihskema.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-paperclip"></i> Pilih Skema</a>
        <a href="pilihunit.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-link"></i> Pilih Unit</a>
        <a href="dashsiswa.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-tag"></i> FR.APL. 1</a>
        <a href="apl2.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-chart-line"></i> FR.APL. 2</a>
        <a href="portofolio.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-pen-nib"></i> Portofolio</a>
        <div class="nav-label">Asesmen</div>
        <a href="testulis.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-file-pen"></i> FR.IA.05 Pertanyaan Tertulis</a>
        <a href="mak5.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-file-signature"></i> FR.AK.03</a>
        <a href="statussaya.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item active"><i class="fas fa-map"></i> Cek Status</a>
        <a href="banding.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-map"></i> FR.AK.04 Banding</a>
        <a href="rahasia.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-map"></i> FR.AK.01 Kerahasiaan</a>
        <div class="nav-label">Akun</div>
        <a href="ubahpassword.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-key"></i> Ubah Password</a>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.78)"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?php echo e(initials($namax)); ?></div>
            <div class="user-info"><strong><?php echo e($namax); ?></strong><span>Asesi LSP</span></div>
            <button class="btn-logout" type="button" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button>
        </div>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <div class="topbar-title">Cek Status <span>Progres asesmen</span></div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e(date('d F Y')); ?></div>
            <button class="icon-btn" type="button" title="Notifikasi"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <section class="content">
        <div class="hero-card">
            <div>
                <small><i class="fas fa-map"></i> Status Asesmen</small>
                <h1>Pantau kelengkapan proses sertifikasi</h1>
                <p>Lihat status permohonan, biodata, APL2, umpan balik, dan rekomendasi asesor dalam satu dashboard.</p>
            </div>
            <div class="hero-icon"><i class="fas fa-route"></i></div>
        </div>

        <div class="overview">
            <div class="profile-card">
                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-id-card"></i> Ringkasan Asesi</h3>
                        <p>Data utama yang digunakan untuk pengecekan status.</p>
                    </div>
                    <button class="btn btn-secondary no-print" type="button" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                </div>
                <div class="profile-grid">
                    <div class="profile-item"><span>Nama</span><strong><?php echo e($namax); ?></strong></div>
                    <div class="profile-item"><span>Email / User ID</span><strong><?php echo e($email); ?></strong></div>
                    <div class="profile-item"><span>ID Skema</span><strong><?php echo e($idskema !== '' ? $idskema : '-'); ?></strong></div>
                    <div class="profile-item"><span>Nama Skema</span><strong><?php echo e($skema_name !== '' ? $skema_name : '-'); ?></strong></div>
                    <div class="profile-item"><span>Kode Skema</span><strong><?php echo e($skema_code !== '' ? $skema_code : '-'); ?></strong></div>
                    <div class="profile-item"><span>Tanggal Registrasi</span><strong><?php echo e($tglrekskema !== '' ? $tglrekskema : '-'); ?></strong></div>
                </div>
            </div>
            <div class="progress-card">
                <div class="progress-ring"><span><?php echo e($progress); ?>%</span></div>
                <p><?php echo e($completed); ?> dari <?php echo e($total); ?> status selesai</p>
            </div>
        </div>

        <div class="card">
            <div class="section-head">
                <div>
                    <h3><i class="fas fa-list-check"></i> Detail Status</h3>
                    <p>Setiap kartu mengikuti data yang tersimpan pada sistem LSP.</p>
                </div>
            </div>

            <div class="status-grid">
                <?php
                $cards = array(
                    array('key' => 'permohonan', 'title' => 'Permohonan', 'icon' => 'fa-file-circle-check'),
                    array('key' => 'apl1', 'title' => 'Biodata / APL1', 'icon' => 'fa-address-card'),
                    array('key' => 'apl2', 'title' => 'APL2', 'icon' => 'fa-clipboard-list'),
                    array('key' => 'mak5', 'title' => 'Umpan Balik FR.AK.03', 'icon' => 'fa-comments'),
                    array('key' => 'rekom_apl1', 'title' => 'Rekomendasi APL1', 'icon' => 'fa-thumbs-up'),
                    array('key' => 'rekom_apl2', 'title' => 'Rekomendasi APL2', 'icon' => 'fa-award')
                );
                foreach ($cards as $card):
                    $item = $status[$card['key']];
                ?>
                    <article class="status-card">
                        <div class="status-icon <?php echo e($item['type']); ?>"><i class="fas <?php echo e($card['icon']); ?>"></i></div>
                        <div>
                            <h4><?php echo e($card['title']); ?></h4>
                            <?php echo status_badge($item['type'], $item['label']); ?>
                            <div class="detail-list">
                                <?php if (!empty($item['details'])): ?>
                                    <?php foreach ($item['details'] as $detail): ?>
                                        <span><i class="fas fa-circle-info"></i> <?php echo e($detail); ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span><i class="fas fa-circle-info"></i> Belum ada detail tambahan.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="page-footer">
        <span>Asesi Panel &copy; <?php echo date('Y'); ?> LSP SMKN 1 Cibinong</span>
        <span>Modernized by <strong>Codex</strong></span>
    </footer>
</main>
</body>
</html>
