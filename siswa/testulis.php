<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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

$qUser = "SELECT * FROM lsp_usertbl WHERE email='$uname_safe' LIMIT 1";
$rUser = mysqli_query($conn, $qUser);
$dUser = $rUser ? mysqli_fetch_array($rUser) : array();
$namax = $dUser['nama'] ?? 'Asesi';

$emailuser = trim((string)$uname);
$emailuser_safe = esc_sql($conn, $emailuser);
$cekapl2 = mysqli_query($conn, "SELECT * FROM skemasiswa WHERE emailsiswa='$emailuser_safe' AND statusapl2='Y' ORDER BY id_skemasiswa DESC LIMIT 1");
$apl2_valid = $cekapl2 && mysqli_num_rows($cekapl2) > 0;
$dataapl = $apl2_valid ? mysqli_fetch_array($cekapl2) : array();
$idskema = $dataapl['idskema'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>FR.IA.05 Pertanyaan Tertulis - LSP</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--green:#22C55E;--red:#EF4444;--orange:#F97316;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{font-size:15px;scroll-behavior:smooth}body{font-family:'Plus Jakarta Sans',Arial,sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}
a{text-decoration:none}.sidebar{width:var(--sidebar-w);min-height:100vh;background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}.sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px}.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,.1)}.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text{line-height:1.2}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:800}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:600;letter-spacing:.5px}.sidebar-nav{padding:16px 12px;flex:1}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:800;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.58);font-size:.875rem;font-weight:600;transition:all .2s;margin-bottom:2px}.nav-item:hover{background:rgba(255,255,255,.07);color:#fff}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07)}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;color:#fff;flex-shrink:0}.user-info{min-width:0}.user-info strong{display:block;color:#fff;font-size:.82rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:136px}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;font-size:.85rem}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}.topbar-title{font-size:1.1rem;font-weight:800;flex:1}.topbar-title span{color:var(--text-sub);font-weight:500;font-size:.875rem;margin-left:8px}.topbar-actions{display:flex;align-items:center;gap:10px}.icon-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-sub);font-size:.9rem}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:800;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px;white-space:nowrap}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.hero-card{background:linear-gradient(135deg,var(--navy) 0%,var(--navy-soft) 100%);color:#fff;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center}.hero-card small{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:rgba(59,191,191,.15);color:#A7F3F3;font-weight:800;font-size:.72rem;letter-spacing:.7px;text-transform:uppercase}.hero-card h1{margin-top:16px;font-size:1.65rem;line-height:1.25}.hero-card p{margin-top:8px;color:rgba(255,255,255,.72);max-width:760px;line-height:1.7}.hero-icon{width:86px;height:86px;border-radius:20px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:2.2rem}
.card{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);animation:fadeUp .4s ease both}@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}.section-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:800;display:flex;align-items:center;gap:10px}.section-head h3 i{color:var(--teal)}.section-head p{font-size:.82rem;color:var(--text-sub);margin-top:4px;line-height:1.6}.alert-box{padding:14px 16px;border-radius:12px;font-size:.88rem;font-weight:700;display:flex;align-items:center;gap:10px}.alert-info{background:var(--teal-light);color:var(--teal-dark);border:1px solid #B7EFEF}.alert-warning{background:#FEFCE8;color:#A16207;border:1px solid #FDE68A}.alert-error{background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5}
.summary-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.summary-card{border:1px solid var(--border);border-radius:12px;padding:15px;background:#FBFDFE}.summary-card span{display:block;color:var(--text-sub);font-size:.75rem;margin-bottom:5px;font-weight:700}.summary-card strong{display:block;font-size:.94rem}.summary-card i{color:var(--teal);margin-right:7px}.module-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:14px}.module-card{border:1.5px solid var(--border);border-radius:14px;background:#FBFDFE;padding:18px;display:flex;flex-direction:column;gap:14px;min-height:186px;transition:all .2s}.module-card:hover{border-color:var(--teal);box-shadow:0 4px 18px rgba(59,191,191,.11);transform:translateY(-1px)}.module-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}.module-code{display:inline-flex;align-items:center;gap:7px;padding:5px 10px;border-radius:8px;background:var(--teal-light);color:var(--teal-dark);font-family:'DM Mono',monospace;font-size:.78rem;font-weight:800}.module-no{width:30px;height:30px;border-radius:9px;background:#fff;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-sub);font-weight:800;font-size:.78rem;flex-shrink:0}.module-name{font-size:.98rem;font-weight:800;line-height:1.5;flex:1}.module-meta{display:flex;align-items:center;gap:8px;color:var(--text-muted);font-size:.78rem;font-weight:700;margin-top:auto}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;border-radius:10px;font-size:.86rem;font-weight:800;cursor:pointer;text-decoration:none;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',Arial,sans-serif}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3);width:100%}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}.module-card form{margin:0}.empty-state{text-align:center;padding:44px 20px;color:var(--text-muted)}.empty-state i{font-size:2.5rem;margin-bottom:12px;display:block;opacity:.55}.empty-state strong{display:block;color:var(--text-main);font-size:1rem;margin-bottom:6px}.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
@media(max-width:1000px){.summary-grid{grid-template-columns:1fr}}@media(max-width:900px){.sidebar{position:relative;transform:none;width:100%;min-height:auto}.main{margin-left:0}.topbar{padding:0 18px}.content{padding:20px}.hero-card{grid-template-columns:1fr}.hero-icon{display:none}body{display:block}.sidebar-footer{display:none}.sidebar-nav{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:4px}.nav-label{grid-column:1/-1}.topbar-title span{display:none}}@media(max-width:520px){.content{padding:16px}.card,.hero-card{padding:20px}.section-head{display:block}.date-chip{display:none}.module-grid{grid-template-columns:1fr}.page-footer{display:block;line-height:1.9}}
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
        <a href="testulis.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item active"><i class="fas fa-file-pen"></i> FR.IA.05 Pertanyaan Tertulis</a>
        <a href="mak5.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-file-signature"></i> FR.AK.03</a>
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
        <div class="topbar-title">Pertanyaan Tertulis <span>FR.IA.05</span></div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e(date('d F Y')); ?></div>
            <button class="icon-btn" type="button" title="Notifikasi"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <section class="content">
        <div class="hero-card">
            <div>
                <small><i class="fas fa-file-pen"></i> FR.IA.05 Pertanyaan Tertulis</small>
                <h1>Pilih kode unit atau modul untuk mulai tes</h1>
                <p>Pastikan APL 2 sudah divalidasi asesor. Setelah memilih modul, tes akan dibuka di tab baru seperti alur halaman lama.</p>
            </div>
            <div class="hero-icon"><i class="fas fa-clipboard-question"></i></div>
        </div>

        <?php if (!$apl2_valid): ?>
            <div class="card">
                <div class="alert-box alert-warning">
                    <i class="fas fa-triangle-exclamation"></i>
                    <strong>APL 2 belum divalidasi oleh asesor.</strong>
                </div>
                <div class="empty-state">
                    <i class="fas fa-hourglass-half"></i>
                    <strong>Tes tertulis belum dapat dimulai</strong>
                    <p>Silakan cek kembali setelah asesor memvalidasi FR.APL.2 Anda.</p>
                </div>
            </div>
        <?php else: ?>
            <?php
            $query = "SELECT kd_modul, modul FROM modul WHERE status_soal='aktif' ORDER BY kd_modul";
            $hasil = mysqli_query($conn, $query);
            $jumlah_modul = $hasil ? mysqli_num_rows($hasil) : 0;
            ?>
            <div class="summary-grid">
                <div class="summary-card"><span><i class="fas fa-id-card"></i> Asesi</span><strong><?php echo e($namax); ?></strong></div>
                <div class="summary-card"><span><i class="fas fa-layer-group"></i> ID Skema</span><strong><?php echo e($idskema); ?></strong></div>
                <div class="summary-card"><span><i class="fas fa-circle-check"></i> Modul Aktif</span><strong><?php echo e($jumlah_modul); ?> modul tersedia</strong></div>
            </div>

            <div class="card">
                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-list-check"></i> Daftar Modul Tes</h3>
                        <p>Pilih salah satu modul aktif untuk membuka halaman pertanyaan tertulis.</p>
                    </div>
                    <a class="btn btn-secondary" href="statussaya.php?uidpes=<?php echo e($menu_uid); ?>"><i class="fas fa-map"></i> Cek Status</a>
                </div>

                <?php if ($jumlah_modul > 0): ?>
                    <div class="module-grid">
                        <?php $no = 1; while ($data = mysqli_fetch_array($hasil)): ?>
                            <article class="module-card">
                                <div class="module-top">
                                    <span class="module-code"><i class="fas fa-code"></i> <?php echo e($data['kd_modul']); ?></span>
                                    <span class="module-no"><?php echo e($no); ?></span>
                                </div>
                                <div class="module-name"><?php echo e($data['modul']); ?></div>
                                <div class="module-meta"><i class="fas fa-up-right-from-square"></i> Dibuka di tab baru</div>
                                <form method="GET" action="quis/flatmenu.php" target="_blank">
                                    <input type="hidden" name="md" value="<?php echo e($data['kd_modul']); ?>">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-play"></i> Mulai Tes</button>
                                </form>
                            </article>
                        <?php $no++; endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-circle-exclamation"></i>
                        <strong>Belum ada modul soal aktif</strong>
                        <p>Modul akan tampil otomatis ketika status soal sudah aktif.</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>

    <footer class="page-footer">
        <span>Asesi Panel &copy; <?php echo date('Y'); ?> LSP SMKN 1 Cibinong</span>
        <span>Modernized by <strong>Codex</strong></span>
    </footer>
</main>
</body>
</html>
