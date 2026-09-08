<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
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

function to_mysql_date($date) {
    $date = trim((string)$date);
    if ($date === '') return date('Y-m-d');
    $d = DateTime::createFromFormat('d-m-Y', $date);
    if ($d instanceof DateTime) return $d->format('Y-m-d');
    $time = strtotime($date);
    return $time ? date('Y-m-d', $time) : date('Y-m-d');
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
$op = $_GET['op'] ?? '';
$flash_type = '';
$flash_message = '';

$qUser = "SELECT * FROM users WHERE username='$uname_safe' LIMIT 1";
$rUser = mysqli_query($conn, $qUser);
$dUser = $rUser ? mysqli_fetch_array($rUser) : array();
$namax = $dUser['nama'] ?? 'Asesi';
$idp = $dUser['id'] ?? '';
$tgluser = $dUser['kode'] ?? '';

if ($op === "mak5post" && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $email_safe = esc_sql($conn, $email);
    $n = (int)($_POST['n'] ?? 0);
    $namape = esc_sql($conn, $_POST['namap'] ?? '');
    $tglmak5 = to_mysql_date($_POST['tglmak5'] ?? '');
    $tglregmak5 = to_mysql_date($_POST['tglregmak5'] ?? '');
    $cttl = esc_sql($conn, $_POST['catl'] ?? '');
    $sukses = 0;
    $update = 0;

    for ($i = 0; $i < $n; $i++) {
        if (!isset($_POST['mak5yt'.$i])) continue;
        $mak5yt = esc_sql($conn, $_POST['mak5yt'.$i]);
        $catatan = esc_sql($conn, $_POST['catatan'.$i] ?? '');
        $idqmak5 = esc_sql($conn, $_POST['idqm5'.$i] ?? ($i + 1));

        $cek = mysqli_query($conn, "SELECT id FROM mak5 WHERE idasesi='$email_safe' AND idqmak5='$idqmak5' LIMIT 1");
        if ($cek && mysqli_num_rows($cek) > 0) {
            $upd = mysqli_query($conn, "UPDATE mak5 SET hasil='$mak5yt', catatan='$catatan', catatanlain='$cttl', tglmak5='$tglmak5' WHERE idasesi='$email_safe' AND idqmak5='$idqmak5'");
            if ($upd) $update++;
        } else {
            $ins = mysqli_query($conn, "INSERT INTO mak5 (idasesi, namapeserta, idqmak5, hasil, catatan, catatanlain, tglreg, tglmak5) VALUES ('$email_safe', '$namape', '$idqmak5', '$mak5yt', '$catatan', '$cttl', '$tglregmak5', '$tglmak5')");
            if ($ins) $sukses++;
        }
    }

    $flash_type = ($sukses > 0 || $update > 0) ? 'success' : 'warning';
    $flash_message = "Data disimpan. Baru: $sukses, update: $update.";
}

$emailuser = trim((string)$uname);
$emailuser_safe = esc_sql($conn, $emailuser);
$shasil = mysqli_query($conn, "SELECT * FROM users WHERE username='$emailuser_safe' LIMIT 1");
$sdata = $shasil ? mysqli_fetch_array($shasil) : array();
$namap = $sdata['nama'] ?? $namax;
$idp = $sdata['id'] ?? $idp;
$tgluser = $sdata['kode'] ?? $tgluser;

$cek_skema = mysqli_query($conn, "SELECT * FROM skemasiswa WHERE emailsiswa='$emailuser_safe' ORDER BY id_skemasiswa DESC LIMIT 1");
$has_skema = $cek_skema && mysqli_num_rows($cek_skema) > 0;
$data_skema = $has_skema ? mysqli_fetch_array($cek_skema) : array();
$skema = $data_skema['idskema'] ?? '';
$statusapl1 = $data_skema['statusapl1'] ?? '';
$namaass = 'Asesor Belum Ditentukan';
$namaskema = '';
$kodeskema = '';
$ctlcek = '';

if ($has_skema && $statusapl1 === 'Y') {
    $skema_safe = esc_sql($conn, $skema);
    $idp_safe = esc_sql($conn, $idp);
    $execpe = mysqli_query($conn, "SELECT * FROM pemetaan WHERE idskema='$skema_safe' AND idpeserta='$idp_safe' LIMIT 1");
    $hpemet = $execpe ? mysqli_fetch_array($execpe) : array();
    $namaass = $hpemet['namaasesor'] ?? 'Asesor Belum Ditentukan';

    $ske1 = mysqli_query($conn, "SELECT * FROM skema WHERE idskema='$skema_safe' LIMIT 1");
    $ske2 = $ske1 ? mysqli_fetch_array($ske1) : array();
    $namaskema = $ske2['namaskema'] ?? '';
    $kodeskema = $ske2['noskema'] ?? '';

    $ceklain = mysqli_query($conn, "SELECT catatanlain FROM mak5 WHERE idasesi='$emailuser_safe' LIMIT 1");
    $datalain = $ceklain ? mysqli_fetch_array($ceklain) : array();
    $ctlcek = $datalain['catatanlain'] ?? '';
}

$pertanyaan = array(
    "Saya mendapatkan penjelasan yang cukup memadai mengenai proses asesmen/uji kompetensi",
    "Saya diberikan kesempatan untuk mempelajari standar kompetensi yang akan diujikan dan menilai diri sendiri terhadap pencapaiannya",
    "Asesor memberikan kesempatan untuk mendiskusikan/menegosiasikan metoda, instrumen dan sumber asesmen serta jadwal asesmen",
    "Asesor berusaha menggali seluruh bukti pendukung yang sesuai dengan latar belakang pelatihan dan pengalaman yang saya miliki",
    "Saya sepenuhnya diberikan kesempatan untuk mendemonstrasikan kompetensi yang saya miliki selama asesmen",
    "Saya mendapatkan penjelasan yang memadai mengenai keputusan asesmen",
    "Asesor memberikan umpan balik yang mendukung setelah asesmen serta tindak lanjutnya",
    "Asesor bersama saya mempelajari semua dokumen asesmen serta menandatanganinya",
    "Saya mendapatkan jaminan kerahasiaan hasil asesmen serta penjelasan penanganan dokumen asesmen",
    "Asesor menggunakan keterampilan komunikasi yang efektif selama asesmen"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.AK.03 - Umpan Balik dan Catatan Asesmen</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--green:#22C55E;--red:#EF4444;--orange:#F97316;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{font-size:15px;scroll-behavior:smooth}body{font-family:'Plus Jakarta Sans',Arial,sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}a{text-decoration:none}
.sidebar{width:var(--sidebar-w);min-height:100vh;background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}.sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px}.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,.1)}.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text{line-height:1.2}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:800}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:600;letter-spacing:.5px}.sidebar-nav{padding:16px 12px;flex:1}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:800;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.58);font-size:.875rem;font-weight:600;transition:all .2s;margin-bottom:2px}.nav-item:hover{background:rgba(255,255,255,.07);color:#fff}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07)}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:#fff;flex-shrink:0}.user-info{min-width:0}.user-info strong{display:block;color:#fff;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:136px}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;font-size:.85rem}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}.topbar-title{font-size:1.1rem;font-weight:800;flex:1}.topbar-title span{color:var(--text-sub);font-weight:500;font-size:.875rem;margin-left:8px}.topbar-actions{display:flex;align-items:center;gap:10px}.icon-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-sub);font-size:.9rem}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:800;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px;white-space:nowrap}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.hero-card{background:linear-gradient(135deg,var(--navy) 0%,var(--navy-soft) 100%);color:#fff;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center}.hero-card small{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:rgba(59,191,191,.15);color:#A7F3F3;font-weight:800;font-size:.72rem;letter-spacing:.7px;text-transform:uppercase}.hero-card h1{margin-top:16px;font-size:1.65rem;line-height:1.25}.hero-card p{margin-top:8px;color:rgba(255,255,255,.72);max-width:760px;line-height:1.7}.hero-icon{width:86px;height:86px;border-radius:20px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:2.2rem}
.card{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);animation:fadeUp .4s ease both}@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}.section-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:800;display:flex;align-items:center;gap:10px}.section-head h3 i{color:var(--teal)}.section-head p{font-size:.82rem;color:var(--text-sub);margin-top:4px;line-height:1.6}.alert-box{padding:14px 16px;border-radius:12px;font-size:.88rem;font-weight:700;display:flex;align-items:center;gap:10px}.alert-success{background:#DCFCE7;color:#15803D;border:1px solid #86EFAC}.alert-warning{background:#FEFCE8;color:#A16207;border:1px solid #FDE68A}.alert-error{background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5}
.meta-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}.meta-card{border:1px solid var(--border);border-radius:12px;padding:14px;background:#FBFDFE}.meta-card span{display:block;color:var(--text-sub);font-size:.75rem;margin-bottom:5px;font-weight:700}.meta-card strong{display:block;font-size:.9rem;line-height:1.5}.meta-card i{color:var(--teal);margin-right:7px}.doc-shell{background:#fff;border-radius:var(--radius);padding:26px;box-shadow:var(--shadow)}.doc-toolbar{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:10px;font-size:.86rem;font-weight:800;cursor:pointer;text-decoration:none;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',Arial,sans-serif}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3)}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}
.form-doc{border:1px solid var(--border);border-radius:13px;overflow:hidden}.doc-header{display:grid;grid-template-columns:88px 170px 1fr;border-bottom:1px solid var(--border);background:#FBFDFE}.doc-logo,.doc-title,.doc-meta{padding:14px;border-right:1px solid var(--border)}.doc-logo{display:flex;align-items:center;justify-content:center}.doc-logo img{height:58px}.doc-title{display:flex;align-items:center;justify-content:center;text-align:center;font-weight:800}.doc-meta{border-right:0;display:grid;grid-template-columns:130px 12px 1fr;gap:7px;font-size:.8rem;line-height:1.5}.fr-title{display:grid;grid-template-columns:140px 1fr;border-bottom:1px solid var(--border);font-weight:800}.fr-title div{padding:13px 16px;border-right:1px solid var(--border)}.fr-title div:last-child{border-right:0}.info-table{width:100%;border-collapse:collapse}.info-table td{border-bottom:1px solid var(--border);padding:11px 14px;font-size:.86rem;vertical-align:middle}.info-table .lbl{background:#F8FAFC;color:var(--text-main);font-weight:800;width:220px}.info-table tr:last-child td{border-bottom:0}.feedback-note{font-size:.84rem;color:var(--text-sub);font-style:italic;margin:18px 0 10px}.feedback-table{width:100%;border-collapse:collapse;border:1px solid var(--border);border-radius:12px;overflow:hidden}.feedback-table th{background:var(--navy);color:#fff;padding:11px 10px;font-size:.78rem;text-align:center;border:1px solid rgba(255,255,255,.12)}.feedback-table td{border:1px solid var(--border);padding:10px;vertical-align:middle;font-size:.84rem}.feedback-table .no{width:42px;text-align:center;font-weight:800;color:var(--text-sub)}.feedback-table .radio-cell{width:62px;text-align:center}.radio-pill{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:9px;border:1.5px solid var(--border);background:#fff;cursor:pointer}.radio-pill input{width:16px;height:16px;accent-color:var(--teal);cursor:pointer}.form-input{width:100%;font-size:.86rem;padding:10px 12px;border:1.5px solid var(--border);border-radius:10px;font-family:'Plus Jakarta Sans',Arial,sans-serif;color:var(--text-main);background:#FBFDFE;outline:none}.form-input:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-glow);background:#fff}.note-row{background:#FBFDFE}.date-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-top:16px}.field-card{border:1px solid var(--border);border-radius:12px;padding:14px;background:#FBFDFE}.field-card label{display:block;font-size:.76rem;font-weight:800;color:var(--text-sub);margin-bottom:7px}.sign-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin-top:16px}.sign-card{border:1px solid var(--border);border-radius:12px;padding:18px;text-align:center}.sign-card span{font-weight:800}.sign-space{height:78px}.form-actions{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin-top:20px;padding-top:18px;border-top:1px solid var(--border)}.foot-note{font-size:.78rem;font-style:italic;color:var(--text-muted);margin-top:12px}.empty-state{text-align:center;padding:44px 20px;color:var(--text-muted)}.empty-state i{font-size:2.5rem;margin-bottom:12px;display:block;opacity:.55}.empty-state strong{display:block;color:var(--text-main);font-size:1rem;margin-bottom:6px}.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
@media(max-width:1100px){.meta-grid{grid-template-columns:repeat(2,1fr)}.doc-header{grid-template-columns:80px 1fr}.doc-meta{grid-column:1/-1;border-top:1px solid var(--border)}.doc-title{border-right:0}}@media(max-width:900px){.sidebar{position:relative;transform:none;width:100%;min-height:auto}.main{margin-left:0}.topbar{padding:0 18px}.content{padding:20px}.hero-card{grid-template-columns:1fr}.hero-icon{display:none}body{display:block}.sidebar-footer{display:none}.sidebar-nav{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:4px}.nav-label{grid-column:1/-1}.topbar-title span{display:none}.date-grid,.sign-grid{grid-template-columns:1fr}}@media(max-width:640px){.content{padding:16px}.card,.hero-card,.doc-shell{padding:20px}.section-head,.doc-toolbar{display:block}.doc-toolbar .btn{margin-top:10px}.meta-grid{grid-template-columns:1fr}.feedback-table{min-width:760px}.table-scroll{overflow-x:auto}.doc-meta{grid-template-columns:1fr}.doc-meta span:nth-child(3n+2){display:none}.fr-title{grid-template-columns:1fr}.fr-title div{border-right:0;border-bottom:1px solid var(--border)}.fr-title div:last-child{border-bottom:0}.page-footer{display:block;line-height:1.9}}
@media print{body{display:block;background:#fff;font-size:11px}.sidebar,.topbar,.page-footer,.hero-card,.doc-toolbar,.no-print,.form-actions{display:none!important}.main{margin-left:0}.content{padding:0;display:block}.meta-grid{display:none}.doc-shell{box-shadow:none;border-radius:0;padding:0}.form-doc{border-radius:0}.feedback-table th{background:#003366!important;color:#fff!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}.form-input{border:0;background:#fff;padding:0}.radio-pill{border:0}.card{box-shadow:none;padding:0}}
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
        <a href="mak5.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item active"><i class="fas fa-file-signature"></i> FR.AK.03</a>
        <a href="statussaya.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-map"></i> Cek Status</a>
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
        <div class="topbar-title">Umpan Balik Asesmen <span>FR.AK.03</span></div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e(date('d F Y')); ?></div>
            <button class="icon-btn" type="button" title="Notifikasi"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <section class="content">
        <div class="hero-card">
            <div>
                <small><i class="fas fa-file-signature"></i> FR.AK.03</small>
                <h1>Umpan balik dan catatan asesmen</h1>
                <p>Isi penilaian pengalaman asesmen, tambahkan komentar bila diperlukan, lalu simpan sebagai catatan FR.AK.03.</p>
            </div>
            <div class="hero-icon"><i class="fas fa-comments"></i></div>
        </div>

        <?php if ($flash_message !== ''): ?>
            <div class="alert-box alert-<?php echo e($flash_type); ?>">
                <i class="fas <?php echo $flash_type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'; ?>"></i>
                <strong><?php echo e($flash_message); ?></strong>
            </div>
        <?php endif; ?>

        <?php if (!$has_skema): ?>
            <div class="card">
                <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i><strong>Belum memilih skema.</strong></div>
                <div class="empty-state"><i class="fas fa-layer-group"></i><strong>Skema belum tersedia</strong><p>Silakan pilih skema terlebih dahulu sebelum mengisi FR.AK.03.</p></div>
            </div>
        <?php elseif ($statusapl1 !== 'Y'): ?>
            <div class="card">
                <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i><strong>APL1 belum divalidasi.</strong></div>
                <div class="empty-state"><i class="fas fa-hourglass-half"></i><strong>Form belum dapat diisi</strong><p>Silakan lengkapi APL1 dan tunggu validasi asesor.</p></div>
            </div>
        <?php else: ?>
            <div class="meta-grid">
                <div class="meta-card"><span><i class="fas fa-user"></i> Asesi</span><strong><?php echo e($namap); ?></strong></div>
                <div class="meta-card"><span><i class="fas fa-user-tie"></i> Asesor</span><strong><?php echo e($namaass); ?></strong></div>
                <div class="meta-card"><span><i class="fas fa-layer-group"></i> Skema</span><strong><?php echo e($namaskema); ?></strong></div>
                <div class="meta-card"><span><i class="fas fa-barcode"></i> Nomor Skema</span><strong><?php echo e($kodeskema); ?></strong></div>
            </div>

            <div class="doc-shell">
                <div class="doc-toolbar no-print">
                    <div class="section-head" style="margin-bottom:0">
                        <div>
                            <h3><i class="fas fa-clipboard-check"></i> Form FR.AK.03</h3>
                            <p>Gunakan tombol print untuk mencetak format dokumen.</p>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-secondary" type="button" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                        <a class="btn btn-secondary" href="dashsiswa.php?uidpes=<?php echo e($menu_uid); ?>"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>

                <div class="form-doc">
                    <div class="doc-header">
                        <div class="doc-logo"><img src="../images/lsplogosmkn1.png" alt="Logo LSP"></div>
                        <div class="doc-title">FORMULIR</div>
                        <div class="doc-meta">
                            <span>Dokumen</span><span>:</span><strong>MUK/247/LSPCBN/2024 FR.AK.03</strong>
                            <span>Edisi / Revisi</span><span>:</span><strong>01/00</strong>
                            <span>Judul</span><span>:</span><strong>UMPAN BALIK DAN CATATAN ASESMEN</strong>
                            <span>Berlaku sejak</span><span>:</span><strong>19 Oktober 2024</strong>
                            <span>Halaman</span><span>:</span><strong>1 / 1</strong>
                        </div>
                    </div>
                    <div class="fr-title">
                        <div>FR.AK.03.</div>
                        <div>UMPAN BALIK DAN CATATAN ASESMEN</div>
                    </div>

                    <table class="info-table">
                        <tr>
                            <td class="lbl" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
                            <td style="width:70px">Judul</td><td style="width:12px">:</td>
                            <td><strong><?php echo e($namaskema); ?></strong></td>
                        </tr>
                        <tr>
                            <td>Nomor</td><td>:</td><td><strong><?php echo e($kodeskema); ?></strong></td>
                        </tr>
                        <tr><td class="lbl">TUK</td><td colspan="3">: Sewaktu / Tempat Kerja / Mandiri *</td></tr>
                        <tr><td class="lbl">Nama Asesor</td><td colspan="3">: <?php echo e($namaass); ?></td></tr>
                        <tr><td class="lbl">Nama Asesi</td><td colspan="3">: <?php echo e($namap); ?></td></tr>
                    </table>
                </div>

                <p class="feedback-note">Umpan balik dari Asesi diisi setelah pengambilan keputusan.</p>

                <form id="formContoh" method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=mak5post&uidpes=<?php echo e($menu_uid); ?>">
                    <input type="hidden" name="idskema" value="<?php echo e($skema); ?>">
                    <input type="hidden" name="idadsesi" value="<?php echo e($idp); ?>">
                    <input type="hidden" name="email" value="<?php echo e($emailuser); ?>">
                    <input type="hidden" name="namap" value="<?php echo e($namap); ?>">
                    <input type="hidden" name="tglregmak5" value="<?php echo e($tgluser); ?>">

                    <div class="table-scroll">
                        <table class="feedback-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="width:42px">NO</th>
                                    <th rowspan="2">KOMPONEN</th>
                                    <th colspan="2">HASIL</th>
                                    <th rowspan="2" style="width:280px">CATATAN / KOMENTAR ASESI</th>
                                </tr>
                                <tr><th style="width:62px">Ya</th><th style="width:62px">Tidak</th></tr>
                            </thead>
                            <tbody>
                            <?php foreach ($pertanyaan as $i => $teks): ?>
                                <?php
                                $idq = $i + 1;
                                $idq_safe = esc_sql($conn, $idq);
                                $cekrow = mysqli_query($conn, "SELECT * FROM mak5 WHERE idasesi='$emailuser_safe' AND idqmak5='$idq_safe' LIMIT 1");
                                $drow = $cekrow ? mysqli_fetch_array($cekrow) : array();
                                $hasilcek = $drow['hasil'] ?? '';
                                $catatancek = $drow['catatan'] ?? '';
                                ?>
                                <tr>
                                    <td class="no"><?php echo e($idq); ?></td>
                                    <td><?php echo e($teks); ?></td>
                                    <td class="radio-cell"><label class="radio-pill"><input type="radio" name="mak5yt<?php echo e($i); ?>" value="y" <?php echo $hasilcek === 'y' ? 'checked' : ''; ?>></label></td>
                                    <td class="radio-cell"><label class="radio-pill"><input type="radio" name="mak5yt<?php echo e($i); ?>" value="t" <?php echo $hasilcek === 't' ? 'checked' : ''; ?>></label></td>
                                    <td>
                                        <input type="text" class="form-input" name="catatan<?php echo e($i); ?>" value="<?php echo e($catatancek); ?>" placeholder="Komentar...">
                                        <input type="hidden" name="idqm5<?php echo e($i); ?>" value="<?php echo e($idq); ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                                <tr class="note-row">
                                    <td colspan="5">
                                        <strong>Catatan/komentar lainnya apabila ada</strong>
                                        <input type="text" class="form-input" name="catl" value="<?php echo e($ctlcek); ?>" placeholder="Tulis catatan tambahan..." style="margin-top:8px">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="date-grid">
                        <div class="field-card">
                            <label for="tanggal">Tanggal Pelaksanaan</label>
                            <input type="text" name="tglmak5" id="tanggal" class="form-input" placeholder="dd-mm-yyyy" required>
                        </div>
                        <div class="field-card">
                            <label for="tglregmak5x">Tanggal Registrasi</label>
                            <input type="text" name="tglregmak5x" id="tglregmak5x" class="form-input" value="<?php echo e($tgluser); ?>" readonly>
                        </div>
                    </div>

                    <div class="sign-grid">
                        <div class="sign-card"><span>Asesi,</span><div class="sign-space"></div><strong><?php echo e($namap); ?></strong></div>
                        <div class="sign-card"><span>Asesor,</span><div class="sign-space"></div><strong><?php echo e($namaass); ?></strong></div>
                    </div>

                    <input type="hidden" name="n" value="<?php echo e(count($pertanyaan)); ?>">
                    <div class="form-actions no-print">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    </div>
                </form>

                <p class="foot-note">* Coret yang tidak perlu</p>
            </div>
        <?php endif; ?>
    </section>

    <footer class="page-footer">
        <span>Asesi Panel &copy; <?php echo date('Y'); ?> LSP SMKN 1 Cibinong</span>
        <span>Modernized by <strong>Codex</strong></span>
    </footer>
</main>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var input = document.getElementById('tanggal');
    if (!input) return;
    if (input.type !== 'date') {
        input.addEventListener('focus', function(){ this.placeholder = 'dd-mm-yyyy'; });
    }
});
</script>
</body>
</html>
