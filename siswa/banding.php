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
    if (count($parts) >= 2) return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
    return strtoupper(substr($name, 0, 2));
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

$result = mysqli_query($conn, "SELECT * FROM users WHERE username='$uname_safe' LIMIT 1");
$user = $result ? mysqli_fetch_array($result) : array();
$namax = $user['nama'] ?? 'User';
$idbanding = $user['id'] ?? '';
$linkttdb = $user['linkttd'] ?? '';

if ($op === 'postban' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $idasesor = esc_sql($conn, $_POST['idasesor'] ?? '');
    $idasesi = esc_sql($conn, $_POST['idadsesi'] ?? '');
    $idskema = esc_sql($conn, $_POST['idskema'] ?? '');
    $jls = esc_sql($conn, $_POST['jls'] ?? 'T');
    $dis = esc_sql($conn, $_POST['dis'] ?? 'T');
    $ol = esc_sql($conn, $_POST['ol'] ?? 'T');
    $alasan = esc_sql($conn, $_POST['alasan'] ?? '');
    $tglban = esc_sql($conn, $_POST['tglban'] ?? date('Y-m-d'));
    $ketb = $jls . "," . $dis . "," . $ol;

    if ($idasesor === '' || $idasesi === '' || $idskema === '') {
        $flash_type = 'error';
        $flash_message = 'Data banding tidak lengkap.';
    } else {
        $cek = mysqli_query($conn, "SELECT * FROM banding WHERE idasesib='$idasesi' AND idasesorb='$idasesor' AND idskemab='$idskema' LIMIT 1");
        if ($cek && mysqli_num_rows($cek) > 0) {
            $sql = "UPDATE banding SET ketb='$ketb', alasan='$alasan', tglbanding='$tglban' WHERE idasesib='$idasesi' AND idasesorb='$idasesor' AND idskemab='$idskema'";
        } else {
            $sql = "INSERT INTO banding (idasesib, idasesorb, idskemab, ketb, alasan, tglbanding) VALUES ('$idasesi', '$idasesor', '$idskema', '$ketb', '$alasan', '$tglban')";
        }

        if (mysqli_query($conn, $sql)) {
            $flash_type = 'success';
            $flash_message = 'Form banding berhasil disimpan.';
            $op = '';
        } else {
            $flash_type = 'error';
            $flash_message = 'Gagal menyimpan data banding.';
        }
    }
}

$form_data = array();
if ($op === 'bandingfrm') {
    $form_data['namaasesi'] = $_GET['namaase'] ?? $namax;
    $form_data['idasesi'] = $_GET['iduser'] ?? $idbanding;
    $form_data['idasesor'] = $_GET['idasesor'] ?? '';
    $form_data['namaasesor'] = $_GET['namaaseso'] ?? '';
    $form_data['tglasesmen'] = $_GET['tglasemen'] ?? '';
    $form_data['idskema'] = $_GET['idskema'] ?? '';
    $form_data['linkttd'] = $_GET['linkttd'] ?? $linkttdb;

    $idskema_safe = esc_sql($conn, $form_data['idskema']);
    $sk = mysqli_query($conn, "SELECT namaskema,noskema FROM skema WHERE idskema='$idskema_safe' LIMIT 1");
    $skema = $sk ? mysqli_fetch_array($sk) : array();
    $form_data['namaskema'] = $skema['namaskema'] ?? 'SKEMA SERTIFIKASI';
    $form_data['kodeskema'] = $skema['noskema'] ?? '-';

    $idasesi_safe = esc_sql($conn, $form_data['idasesi']);
    $idasesor_safe = esc_sql($conn, $form_data['idasesor']);
    $cekban = mysqli_query($conn, "SELECT * FROM banding WHERE idskemab='$idskema_safe' AND idasesib='$idasesi_safe' AND idasesorb='$idasesor_safe' LIMIT 1");
    $rowban = $cekban ? mysqli_fetch_array($cekban) : array();
    $form_data['jls'] = $rowban['jls'] ?? '';
    $form_data['dis'] = $rowban['dis'] ?? '';
    $form_data['ol'] = $rowban['ol'] ?? '';
    if (($form_data['jls'] === '' || $form_data['dis'] === '' || $form_data['ol'] === '') && !empty($rowban['ketb'])) {
        $ket_parts = array_map('trim', explode(',', $rowban['ketb']));
        $form_data['jls'] = $form_data['jls'] ?: ($ket_parts[0] ?? '');
        $form_data['dis'] = $form_data['dis'] ?: ($ket_parts[1] ?? '');
        $form_data['ol'] = $form_data['ol'] ?: ($ket_parts[2] ?? '');
    }
    $form_data['alasan'] = $rowban['alasan'] ?? '';
    $form_data['tglban'] = $rowban['tglbanding'] ?? date('Y-m-d');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.AK.04 Banding - LSP</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--green:#22C55E;--red:#EF4444;--orange:#F97316;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{font-size:15px}body{font-family:'Plus Jakarta Sans',Arial,sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}a{text-decoration:none}
.sidebar{width:var(--sidebar-w);min-height:100vh;background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}.sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px}.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center}.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:800}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:600;letter-spacing:.5px}.sidebar-nav{padding:16px 12px;flex:1}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:800;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.58);font-size:.875rem;font-weight:600;transition:all .2s;margin-bottom:2px}.nav-item:hover{background:rgba(255,255,255,.07);color:#fff}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center}.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07)}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff}.user-info{min-width:0}.user-info strong{display:block;color:#fff;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:136px}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}.topbar-title{font-size:1.1rem;font-weight:800;flex:1}.topbar-title span{color:var(--text-sub);font-weight:500;font-size:.875rem;margin-left:8px}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:800;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.hero-card{background:linear-gradient(135deg,var(--navy) 0%,var(--navy-soft) 100%);color:#fff;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center}.hero-card small{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:rgba(59,191,191,.15);color:#A7F3F3;font-weight:800;font-size:.72rem;letter-spacing:.7px;text-transform:uppercase}.hero-card h1{margin-top:16px;font-size:1.65rem;line-height:1.25}.hero-card p{margin-top:8px;color:rgba(255,255,255,.72);max-width:760px;line-height:1.7}.hero-icon{width:86px;height:86px;border-radius:20px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:2.2rem}
.card,.doc-shell{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow)}.section-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:800;display:flex;align-items:center;gap:10px}.section-head h3 i{color:var(--teal)}.section-head p{font-size:.82rem;color:var(--text-sub);margin-top:4px;line-height:1.6}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:10px;font-size:.86rem;font-weight:800;cursor:pointer;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',Arial,sans-serif}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3)}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}.btn-success{background:var(--green);color:#fff}.alert-box{padding:14px 16px;border-radius:12px;font-size:.88rem;font-weight:700;display:flex;align-items:center;gap:10px}.alert-success{background:#DCFCE7;color:#15803D;border:1px solid #86EFAC}.alert-error{background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5}
.table-wrap{overflow-x:auto}.data-table{width:100%;border-collapse:collapse}.data-table th{background:var(--navy);color:#fff;text-align:left;padding:12px;font-size:.78rem}.data-table td{border-bottom:1px solid var(--border);padding:14px 12px;font-size:.86rem;vertical-align:middle}.data-table tr:hover td{background:#FBFDFE}.code-chip{font-family:'DM Mono',monospace;background:var(--teal-light);color:var(--teal-dark);font-weight:800;border-radius:8px;padding:5px 8px;display:inline-flex}.empty-state{text-align:center;padding:44px 20px;color:var(--text-muted)}.empty-state i{font-size:2.5rem;margin-bottom:12px;display:block;opacity:.55}.empty-state strong{display:block;color:var(--text-main);font-size:1rem;margin-bottom:6px}
.doc-toolbar{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:18px}.form-doc{border:1px solid var(--border);border-radius:13px;overflow:hidden}.doc-header{display:grid;grid-template-columns:88px 170px 1fr;border-bottom:1px solid var(--border);background:#FBFDFE}.doc-logo,.doc-title,.doc-meta{padding:14px;border-right:1px solid var(--border)}.doc-logo{display:flex;align-items:center;justify-content:center}.doc-logo img{height:58px}.doc-title{display:flex;align-items:center;justify-content:center;text-align:center;font-weight:800}.doc-meta{border-right:0;display:grid;grid-template-columns:130px 12px 1fr;gap:7px;font-size:.8rem;line-height:1.5}.fr-title{text-align:center;font-weight:800;padding:15px;border-bottom:1px solid var(--border)}.info-table,.question-table,.sign-table{width:100%;border-collapse:collapse}.info-table td,.question-table td,.question-table th,.sign-table td{border:1px solid var(--border);padding:11px 12px;font-size:.86rem}.question-table th{background:var(--navy);color:#fff}.radio-cell{text-align:center;width:76px}.radio-cell input{width:17px;height:17px;accent-color:var(--teal)}.form-input{width:100%;font-size:.88rem;padding:10px 12px;border:1.5px solid var(--border);border-radius:10px;font-family:'Plus Jakarta Sans',Arial,sans-serif;color:var(--text-main);background:#FBFDFE;outline:none}.form-input:focus{border-color:var(--teal);box-shadow:0 0 0 3px var(--teal-glow);background:#fff}.sign-img{max-height:70px;max-width:180px;object-fit:contain}.form-actions{display:flex;justify-content:center;gap:10px;margin-top:20px;padding-top:18px;border-top:1px solid var(--border)}.right-note{font-style:italic;color:var(--text-sub);font-size:.84rem;line-height:1.7;margin:16px 0}.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
@media(max-width:900px){.sidebar{position:relative;width:100%;min-height:auto}.main{margin-left:0}.topbar{padding:0 18px}.content{padding:20px}.hero-card{grid-template-columns:1fr}.hero-icon,.sidebar-footer{display:none}body{display:block}.sidebar-nav{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:4px}.nav-label{grid-column:1/-1}.topbar-title span{display:none}.doc-header{grid-template-columns:80px 1fr}.doc-meta{grid-column:1/-1;border-top:1px solid var(--border)}.doc-title{border-right:0}}@media(max-width:560px){.content{padding:16px}.card,.hero-card,.doc-shell{padding:20px}.section-head,.doc-toolbar{display:block}.date-chip{display:none}.question-table{min-width:720px}.sign-table td{display:block;width:100%}.page-footer{display:block;line-height:1.9}}
@media print{body{display:block;background:#fff}.sidebar,.topbar,.page-footer,.hero-card,.no-print,.form-actions{display:none!important}.main{margin-left:0}.content{padding:0}.doc-shell,.card{box-shadow:none;border-radius:0;padding:0}.form-doc{border-radius:0}.question-table th{background:#003366!important;color:#fff!important;-webkit-print-color-adjust:exact;print-color-adjust:exact}}
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
        <a href="portofolio.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-pen-nib"></i> Portofolio</a>
        <div class="nav-label">Asesmen</div>
        <a href="testulis.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-file-pen"></i> FR.IA.05 Pertanyaan Tertulis</a>
        <a href="mak5.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-file-signature"></i> FR.AK.03</a>
        <a href="statussaya.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-map"></i> Cek Status</a>
        <a href="banding.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item active"><i class="fas fa-scale-balanced"></i> FR.AK.04 Banding</a>
        <a href="rahasia.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-user-shield"></i> FR.AK.01 Kerahasiaan</a>
        <div class="nav-label">Akun</div>
        <a href="ubahpassword.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-key"></i> Ubah Password</a>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.78)"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card"><div class="user-avatar"><?php echo e(initials($namax)); ?></div><div class="user-info"><strong><?php echo e($namax); ?></strong><span>Asesi LSP</span></div><button class="btn-logout" type="button" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button></div></div>
</aside>

<main class="main">
<header class="topbar"><div class="topbar-title">Banding Asesmen <span>FR.AK.04</span></div><div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e(date('d F Y')); ?></div></header>
<section class="content">
    <div class="hero-card"><div><small><i class="fas fa-scale-balanced"></i> FR.AK.04</small><h1>Form banding asesmen</h1><p>Ajukan banding apabila proses asesmen dinilai tidak sesuai SOP atau belum memenuhi prinsip asesmen.</p></div><div class="hero-icon"><i class="fas fa-gavel"></i></div></div>

    <?php if ($flash_message !== ''): ?><div class="alert-box alert-<?php echo e($flash_type); ?>"><i class="fas <?php echo $flash_type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark'; ?>"></i><strong><?php echo e($flash_message); ?></strong></div><?php endif; ?>

    <?php if ($op === 'bandingfrm'): ?>
        <?php $linkttd = "../imgttd/" . basename((string)$form_data['linkttd']); ?>
        <div class="doc-shell">
            <div class="doc-toolbar no-print">
                <div class="section-head" style="margin-bottom:0"><div><h3><i class="fas fa-file-signature"></i> Form FR.AK.04</h3><p>Lengkapi jawaban dan alasan banding, lalu simpan.</p></div></div>
                <div><button class="btn btn-secondary" type="button" onclick="window.print()"><i class="fas fa-print"></i> Print</button> <a class="btn btn-secondary" href="<?php echo e($_SERVER['PHP_SELF']); ?>?uidpes=<?php echo e($menu_uid); ?>"><i class="fas fa-arrow-left"></i> Kembali</a></div>
            </div>
            <div class="form-doc">
                <div class="doc-header">
                    <div class="doc-logo"><img src="../images/lsplogosmkn1.png" alt="Logo"></div>
                    <div class="doc-title">FORMULIR</div>
                    <div class="doc-meta"><span>Dokumen</span><span>:</span><strong>MUK/247/LSPCBN/2024 FR.AK.04</strong><span>Edisi / Revisi</span><span>:</span><strong>01/00</strong><span>Judul</span><span>:</span><strong>BANDING ASESMEN</strong><span>Berlaku sejak</span><span>:</span><strong>19 Oktober 2024</strong></div>
                </div>
                <div class="fr-title">FR.AK.04. BANDING ASESMEN</div>
            </div>
            <form method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=postban&uidpes=<?php echo e($menu_uid); ?>">
                <input type="hidden" name="idasesor" value="<?php echo e($form_data['idasesor']); ?>">
                <input type="hidden" name="idadsesi" value="<?php echo e($form_data['idasesi']); ?>">
                <input type="hidden" name="idskema" value="<?php echo e($form_data['idskema']); ?>">
                <table class="info-table" style="margin-top:16px">
                    <tr><td style="width:190px"><strong>Nama Asesi</strong></td><td>: <?php echo e($form_data['namaasesi']); ?></td></tr>
                    <tr><td><strong>Nama Asesor</strong></td><td>: <?php echo e($form_data['namaasesor']); ?></td></tr>
                    <tr><td><strong>Tanggal Asesmen</strong></td><td>: <?php echo e($form_data['tglasesmen']); ?></td></tr>
                </table>
                <p class="right-note"><strong>Jawablah dengan Ya atau Tidak pertanyaan-pertanyaan berikut ini.</strong></p>
                <div class="table-wrap">
                    <table class="question-table">
                        <tr><th>PERTANYAAN</th><th>YA</th><th>TIDAK</th></tr>
                        <tr><td>Apakah proses banding telah dijelaskan kepada Anda?</td><td class="radio-cell"><input type="radio" name="jls" value="Y" <?php echo $form_data['jls'] === 'Y' ? 'checked' : ''; ?>></td><td class="radio-cell"><input type="radio" name="jls" value="T" <?php echo $form_data['jls'] === 'T' ? 'checked' : ''; ?>></td></tr>
                        <tr><td>Apakah Anda telah mendiskusikan banding dengan asesor?</td><td class="radio-cell"><input type="radio" name="dis" value="Y" <?php echo $form_data['dis'] === 'Y' ? 'checked' : ''; ?>></td><td class="radio-cell"><input type="radio" name="dis" value="T" <?php echo $form_data['dis'] === 'T' ? 'checked' : ''; ?>></td></tr>
                        <tr><td>Apakah Anda mau melibatkan orang lain membantu Anda dalam proses banding?</td><td class="radio-cell"><input type="radio" name="ol" value="Y" <?php echo $form_data['ol'] === 'Y' ? 'checked' : ''; ?>></td><td class="radio-cell"><input type="radio" name="ol" value="T" <?php echo $form_data['ol'] === 'T' ? 'checked' : ''; ?>></td></tr>
                    </table>
                </div>
                <p class="right-note"><strong>Banding ini diajukan atas keputusan asesmen terhadap skema berikut.</strong></p>
                <table class="info-table">
                    <tr><td style="width:220px">Skema Sertifikasi</td><td>: <?php echo e($form_data['namaskema']); ?></td></tr>
                    <tr><td>No. Skema Sertifikasi</td><td>: <?php echo e($form_data['kodeskema']); ?></td></tr>
                </table>
                <p class="right-note"><strong>Banding ini diajukan atas alasan sebagai berikut.</strong></p>
                <textarea name="alasan" rows="5" class="form-input" required><?php echo e($form_data['alasan']); ?></textarea>
                <p class="right-note">Anda mempunyai hak mengajukan banding jika Anda menilai proses asesmen tidak sesuai SOP dan tidak memenuhi prinsip asesmen.</p>
                <table class="sign-table">
                    <tr>
                        <td style="text-align:center;width:50%"><strong>Tanda tangan Asesi</strong><br><br><?php if ($form_data['linkttd'] !== ''): ?><img class="sign-img" src="<?php echo e($linkttd); ?>" alt="TTD"><br><?php endif; ?><?php echo e($form_data['namaasesi']); ?></td>
                        <td style="text-align:center"><strong>Tanggal</strong><br><br><input type="text" name="tglban" value="<?php echo e($form_data['tglban']); ?>" class="form-input" readonly></td>
                    </tr>
                </table>
                <div class="form-actions no-print"><button type="submit" class="btn btn-success"><i class="fas fa-floppy-disk"></i> Simpan Banding</button></div>
            </form>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="section-head">
                <div><h3><i class="fas fa-list-check"></i> Daftar Asesmen</h3><p>Pilih asesmen yang ingin diajukan bandingnya.</p></div>
            </div>
            <?php
            $idbanding_safe = esc_sql($conn, $idbanding);
            $hasilbanding = mysqli_query($conn, "SELECT * FROM pemetaan WHERE idpeserta='$idbanding_safe'");
            if ($hasilbanding && mysqli_num_rows($hasilbanding) > 0):
            ?>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead><tr><th>No</th><th>Nama Peserta</th><th>Paket</th><th>Skema</th><th>Nama Asesor</th><th>Aksi</th></tr></thead>
                        <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_array($hasilbanding)): ?>
                            <?php
                            $skema_id = esc_sql($conn, $row['idskema'] ?? '');
                            $execskema = mysqli_query($conn, "SELECT namaskema FROM skema WHERE idskema='$skema_id' LIMIT 1");
                            $rowskema = $execskema ? mysqli_fetch_array($execskema) : array();
                            $namaskema = $rowskema['namaskema'] ?? ($row['idskema'] ?? '-');
                            $url = $_SERVER['PHP_SELF']."?op=bandingfrm&uidpes=".$menu_uid."&iduser=".rawurlencode((string)$idbanding)."&namaaseso=".rawurlencode((string)$row['namaasesor'])."&idasesor=".rawurlencode((string)$row['idasesor'])."&idskema=".rawurlencode((string)$row['idskema'])."&tglasemen=".rawurlencode((string)$row['tanggal'])."&linkttd=".rawurlencode((string)$linkttdb)."&namaase=".rawurlencode((string)$namax);
                            ?>
                            <tr>
                                <td><?php echo e($no); ?></td>
                                <td><strong><?php echo e($namax); ?></strong></td>
                                <td><?php echo e($row['kelompok'] ?? '-'); ?></td>
                                <td><span class="code-chip"><?php echo e($namaskema); ?></span></td>
                                <td><?php echo e($row['namaasesor'] ?? '-'); ?></td>
                                <td><a class="btn btn-primary" href="<?php echo e($url); ?>"><i class="fas fa-pen-to-square"></i> Isi Form</a></td>
                            </tr>
                        <?php $no++; endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state"><i class="fas fa-calendar-xmark"></i><strong>Belum ada jadwal</strong><p>Data pemetaan asesmen belum tersedia untuk akun ini.</p></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>
<footer class="page-footer"><span>Asesi Panel &copy; <?php echo date('Y'); ?> LSP SMKN 1 Cibinong</span><span>Modernized by <strong>Codex</strong></span></footer>
</main>
</body>
</html>
