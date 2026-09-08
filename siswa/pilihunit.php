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
    return $name === '' ? 'AS' : strtoupper(substr($name, 0, 2));
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

$unameunit = $_SESSION['username'] ?? '';
$unameunit1 = $_GET['uidpes'] ?? $unameunit;
if ($unameunit1 === '') {
    $unameunit1 = $unameunit;
}

$unameunit_safe = esc_sql($conn, $unameunit1);
$lpunit = "SELECT * FROM lsp_usertbl WHERE email='$unameunit_safe' LIMIT 1";
$resultxpunit = mysqli_query($conn, $lpunit);
$hasilxpunit = $resultxpunit ? mysqli_fetch_array($resultxpunit) : array();
$namaxpunit = $hasilxpunit['nama'] ?? 'Asesi';
$emailpesunit = $hasilxpunit['email'] ?? $unameunit1;
$idasesi_user = $hasilxpunit['id'] ?? ($hasilxpunit['id'] ?? '');
$menu_uid = rawurlencode((string)$unameunit1);
$op = $_REQUEST['op'] ?? '';
$flash_type = '';
$flash_message = '';
$selected_units = array();
$duplicate_units = array();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pilih Unit - LSP SMKN 1 Cibinong</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--text-muted:#92A9B5;--green:#22C55E;--red:#EF4444;--orange:#F97316;--sidebar-w:260px;--radius:14px;--shadow:0 2px 16px rgba(15,42,58,.07)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{font-size:15px;scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--text-main);display:flex;min-height:100vh;overflow-x:hidden}
.sidebar{width:var(--sidebar-w);min-height:100vh;background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;z-index:100;overflow-y:auto}
.sidebar-logo{padding:28px 24px 20px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:12px}
.logo-box{width:50px;height:50px;background:#fff;border-radius:12px;padding:5px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 10px rgba(0,0,0,.1)}
.logo-box img{width:100%;height:100%;object-fit:contain}.logo-text{line-height:1.2}.logo-text strong{display:block;color:#fff;font-size:.95rem;font-weight:700}.logo-text span{color:var(--teal);font-size:.72rem;font-weight:500;letter-spacing:.5px}
.sidebar-nav{padding:16px 12px;flex:1}.nav-label{color:rgba(255,255,255,.3);font-size:.67rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;padding:12px 12px 6px}
.nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.875rem;font-weight:500;transition:all .2s;margin-bottom:2px}
.nav-item:hover{background:rgba(255,255,255,.07);color:#fff;text-decoration:none}.nav-item.active{background:var(--teal);color:#fff;box-shadow:0 4px 12px rgba(59,191,191,.35)}.nav-item i{width:18px;text-align:center;font-size:.9rem;flex-shrink:0}
.sidebar-footer{padding:16px 14px;border-top:1px solid rgba(255,255,255,.07)}.user-card{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;background:rgba(255,255,255,.05)}
.user-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;flex-shrink:0}.user-info strong{display:block;color:#fff;font-size:.82rem}.user-info span{color:var(--teal);font-size:.72rem}.btn-logout{margin-left:auto;color:rgba(255,255,255,.35);background:none;border:none;cursor:pointer;font-size:.85rem}.btn-logout:hover{color:var(--red)}
.main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}.topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 32px;height:68px;display:flex;align-items:center;gap:16px;position:sticky;top:0;z-index:50}
.topbar-title{font-size:1.1rem;font-weight:700;flex:1}.topbar-title span{color:var(--text-sub);font-weight:400;font-size:.875rem;margin-left:8px}.topbar-actions{display:flex;align-items:center;gap:10px}.icon-btn{width:38px;height:38px;border-radius:10px;border:1.5px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-sub);font-size:.9rem}.date-chip{background:var(--teal-light);color:var(--teal-dark);font-size:.78rem;font-weight:600;padding:6px 14px;border-radius:8px;display:flex;align-items:center;gap:6px}
.content{padding:32px;display:flex;flex-direction:column;gap:24px}.hero-card{background:linear-gradient(135deg,var(--navy) 0%,var(--navy-soft) 100%);color:#fff;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);display:grid;grid-template-columns:minmax(0,1fr) auto;gap:24px;align-items:center}.hero-card small{display:inline-flex;gap:8px;align-items:center;padding:6px 12px;border-radius:999px;background:rgba(59,191,191,.15);color:#A7F3F3;font-weight:700;font-size:.72rem;letter-spacing:.7px;text-transform:uppercase}.hero-card h1{margin-top:16px;font-size:1.65rem;line-height:1.25}.hero-card p{margin-top:8px;color:rgba(255,255,255,.72);max-width:720px;line-height:1.7}.hero-icon{width:86px;height:86px;border-radius:20px;background:rgba(255,255,255,.1);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:2.2rem}
.card{background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);animation:fadeUp .4s ease both}@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.section-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;margin-bottom:20px}.section-head h3{font-size:1.05rem;font-weight:700;display:flex;align-items:center;gap:10px}.section-head h3 i{color:var(--teal)}.section-head p{font-size:.8rem;color:var(--text-sub);margin-top:4px;line-height:1.6}
.alert-box{padding:13px 16px;border-radius:12px;font-size:.86rem;font-weight:600;display:flex;align-items:center;gap:10px;margin-bottom:18px}.alert-success{background:#DCFCE7;color:#15803D;border:1px solid #86EFAC}.alert-warning{background:#FEFCE8;color:#A16207;border:1px solid #FDE68A}.alert-error{background:#FEF2F2;color:#B91C1C;border:1px solid #FCA5A5}
.meta-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:22px}.meta-card{border:1px solid var(--border);border-radius:12px;padding:14px;background:#FBFDFE}.meta-card span{display:block;color:var(--text-sub);font-size:.75rem;margin-bottom:5px}.meta-card strong{display:block;font-size:.9rem}
.unit-list{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:12px}.unit-option{position:relative}.unit-option input{position:absolute;opacity:0;pointer-events:none}.unit-card{display:grid;grid-template-columns:34px 1fr;gap:12px;padding:15px;border:1.5px solid var(--border);border-radius:13px;background:#fff;cursor:pointer;transition:all .2s;min-height:94px}.unit-card:hover{border-color:var(--teal);box-shadow:0 4px 18px rgba(59,191,191,.11);transform:translateY(-1px)}.unit-option input:checked + .unit-card{border-color:var(--teal);background:var(--teal-light);box-shadow:0 0 0 3px var(--teal-glow)}.check-mark{width:26px;height:26px;border-radius:8px;border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;color:transparent;transition:all .2s}.unit-option input:checked + .unit-card .check-mark{background:var(--teal);border-color:var(--teal);color:#fff}.unit-code{display:inline-flex;align-items:center;gap:6px;padding:4px 9px;border-radius:7px;background:var(--off);color:var(--teal-dark);font-size:.74rem;font-weight:800;margin-bottom:8px}.unit-name{font-weight:700;line-height:1.45;font-size:.9rem}
.empty-state{text-align:center;padding:46px 22px;color:var(--text-muted)}.empty-state i{display:block;font-size:2.6rem;margin-bottom:12px;opacity:.45}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 20px;border-radius:10px;font-size:.86rem;font-weight:700;cursor:pointer;text-decoration:none;border:1.5px solid transparent;transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif}.btn:hover{text-decoration:none}.btn-primary{background:var(--teal);color:#fff;box-shadow:0 2px 8px rgba(59,191,191,.3)}.btn-primary:hover{background:var(--teal-dark);color:#fff}.btn-secondary{background:#fff;color:var(--text-main);border-color:var(--border)}.btn-secondary:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}.form-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:22px;padding-top:18px;border-top:1px solid var(--border)}
.page-footer{padding:20px 32px;border-top:1px solid var(--border);background:#fff;display:flex;align-items:center;justify-content:space-between;font-size:.78rem;color:var(--text-muted);margin-top:auto}.page-footer strong{color:var(--teal-dark)}
@media(max-width:900px){.sidebar{transform:translateX(-100%)}.main{margin-left:0}.topbar{padding:0 18px}.content{padding:20px}.hero-card{grid-template-columns:1fr}.hero-icon{display:none}.meta-grid{grid-template-columns:1fr}}
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
        <a href="pilihunit.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item active"><i class="fas fa-link"></i> Pilih Unit</a>
        <a href="dashsiswa.php?uidpes=<?php echo e($menu_uid); ?>" class="nav-item"><i class="fas fa-tag"></i> FR.APL. 1</a>
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
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?php echo e(initials($namaxpunit)); ?></div>
            <div class="user-info"><strong><?php echo e($namaxpunit); ?></strong><span>Asesi LSP</span></div>
            <button class="btn-logout" onclick="window.location='../logout.php'"><i class="fas fa-right-from-bracket"></i></button>
        </div>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <div class="topbar-title">Pilih Unit <span>Memilih unit kompetensi sesuai skema</span></div>
        <div class="topbar-actions">
            <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div>
            <button class="icon-btn" type="button"><i class="fas fa-bell"></i></button>
        </div>
    </header>

    <section class="content">
        <?php
        if ($op == "simpanpilihunit" && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = esc_sql($conn, trim($_POST['email'] ?? ''));
            $ids = esc_sql($conn, $_POST['skema'] ?? '');
            $idadsesi = esc_sql($conn, $_POST['idasesi'] ?? '');
            $n = (int)($_POST['n'] ?? 0);
            $saved_count = 0;

            for ($i = 0; $i <= $n - 1; $i++) {
                if (!isset($_POST['kodeunit'.$i])) {
                    continue;
                }

                $idunit = esc_sql($conn, $_POST['idunit'.$i] ?? '');
                $kodeunit = trim($_POST['kodeunit'.$i] ?? '');

                if ($idunit === '' || $kodeunit === '') {
                    continue;
                }

                $cekdata = "SELECT * FROM unitsiswa WHERE idskema='$ids' AND idunit='$idunit' AND idadsesi='$idadsesi'";
                $ada = mysqli_query($conn, $cekdata);

                if ($ada && mysqli_num_rows($ada) > 0) {
                    $duplicate_units[] = $kodeunit;
                    continue;
                }

                $ssql = "INSERT INTO unitsiswa (idunit, idskema, idadsesi, emailsiswa, status)
                         VALUES ('$idunit', '$ids', '$idadsesi', '$email', 'T')";
                $exec = mysqli_query($conn, $ssql);

                if ($exec) {
                    $saved_count++;
                    $selected_units[] = $kodeunit;
                }
            }

            if ($saved_count > 0) {
                $flash_type = 'success';
                $flash_message = 'Berhasil menyimpan ' . $saved_count . ' unit: ' . implode(', ', $selected_units);
                if (!empty($duplicate_units)) {
                    $flash_message .= '. Duplikat dilewati: ' . implode(', ', $duplicate_units);
                }
            } elseif (!empty($duplicate_units)) {
                $flash_type = 'warning';
                $flash_message = 'Unit yang dipilih sudah pernah disimpan: ' . implode(', ', $duplicate_units);
            } else {
                $flash_type = 'warning';
                $flash_message = 'Belum ada unit yang dipilih.';
            }
        }

        $emailuser = esc_sql($conn, trim($unameunit1));
        $cek = "SELECT * FROM skemasiswa WHERE emailsiswa='$emailuser' ORDER BY id_skemasiswa DESC LIMIT 1";
        $ada = mysqli_query($conn, $cek);
        $has_skema = $ada && mysqli_num_rows($ada) > 0;
        $skema = '';
        $namaskema = '';
        $units = array();

        if ($has_skema) {
            $data = mysqli_fetch_array($ada);
            $skema = $data['idskema'] ?? '';
            $skema_safe = esc_sql($conn, $skema);
            $ske = "SELECT * FROM skema WHERE idskema='$skema_safe' LIMIT 1";
            $ske1 = mysqli_query($conn, $ske);
            $ske2 = $ske1 ? mysqli_fetch_array($ske1) : array();
            $namaskema = $ske2['namaskema'] ?? 'Skema Tidak Ditemukan';

            $sqlunit = "SELECT * FROM unit WHERE idskema='$skema_safe' ORDER BY kodeunit";
            $execunit = mysqli_query($conn, $sqlunit);
            if ($execunit) {
                while ($unit2 = mysqli_fetch_array($execunit)) {
                    $units[] = $unit2;
                }
            }
        }
        ?>

        <div class="hero-card">
            <div>
                <small><i class="fas fa-link"></i> Langkah Kedua</small>
                <h1>Pilih unit kompetensi yang akan diases</h1>
                <p>Centang unit yang sesuai dengan skema pilihanmu. Sistem akan melewati unit yang sudah pernah disimpan agar data tidak dobel.</p>
            </div>
            <div class="hero-icon"><i class="fas fa-list-check"></i></div>
        </div>

        <div class="card">
            <?php if ($flash_message !== ''): ?>
                <div class="alert-box alert-<?php echo e($flash_type); ?>">
                    <i class="fas <?php echo $flash_type === 'success' ? 'fa-circle-check' : ($flash_type === 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-xmark'); ?>"></i>
                    <strong><?php echo e($flash_message); ?></strong>
                </div>
            <?php endif; ?>

            <div class="section-head">
                <div>
                    <h3><i class="fas fa-cubes"></i> Daftar Unit Kompetensi</h3>
                    <p>Pilih satu atau lebih unit yang ingin disimpan untuk skema aktif.</p>
                </div>
                <a href="pilihskema.php?uidpes=<?php echo e($menu_uid); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Pilih Skema</a>
            </div>

            <?php if ($has_skema): ?>
                <div class="meta-grid">
                    <div class="meta-card"><span>Nama Asesi</span><strong><?php echo e($namaxpunit); ?></strong></div>
                    <div class="meta-card"><span>Email</span><strong><?php echo e($emailpesunit); ?></strong></div>
                    <div class="meta-card"><span>Skema Aktif</span><strong><?php echo e($namaskema); ?></strong></div>
                </div>

                <form id="formContoh" method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=simpanpilihunit&uidpes=<?php echo e($menu_uid); ?>">
                    <input type="hidden" name="email" value="<?php echo e($emailuser); ?>">
                    <input type="hidden" name="idasesi" value="<?php echo e($idasesi_user); ?>">
                    <input type="hidden" name="skema" value="<?php echo e($skema); ?>">

                    <?php if (!empty($units)): ?>
                        <div class="unit-list">
                            <?php $i = 0; foreach ($units as $unit): ?>
                                <label class="unit-option">
                                    <input type="hidden" name="idunit<?php echo e($i); ?>" value="<?php echo e($unit['idunit']); ?>">
                                    <input type="checkbox" name="kodeunit<?php echo e($i); ?>" value="<?php echo e($unit['kodeunit']); ?>">
                                    <div class="unit-card">
                                        <div class="check-mark"><i class="fas fa-check"></i></div>
                                        <div>
                                            <span class="unit-code"><i class="fas fa-hashtag"></i> <?php echo e($unit['kodeunit']); ?></span>
                                            <div class="unit-name"><?php echo e($unit['namaunit']); ?></div>
                                        </div>
                                    </div>
                                </label>
                            <?php $i++; endforeach; ?>
                        </div>
                        <input type="hidden" name="n" value="<?php echo e($i); ?>">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Unit Terpilih</button>
                            <a href="dashsiswa.php?uidpes=<?php echo e($menu_uid); ?>" class="btn btn-secondary"><i class="fas fa-arrow-right"></i> Lanjut FR.APL. 1</a>
                        </div>
                    <?php else: ?>
                        <div class="empty-state"><i class="fas fa-folder-open"></i><strong>Unit belum tersedia</strong><p>Belum ada unit kompetensi untuk skema ini.</p></div>
                    <?php endif; ?>
                </form>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-circle-exclamation"></i>
                    <strong>Skema belum dipilih</strong>
                    <p>Pilih skema terlebih dahulu sebelum memilih unit kompetensi.</p>
                    <div style="margin-top:18px"><a href="pilihskema.php?uidpes=<?php echo e($menu_uid); ?>" class="btn btn-primary"><i class="fas fa-paperclip"></i> Pilih Skema</a></div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="page-footer">
        <span>Asesi Panel &copy; <?php echo date('Y'); ?> LSP SMKN 1 Cibinong</span>
        <span>Modernized by <strong>Codex</strong></span>
    </footer>
</main>
</body>
</html>
