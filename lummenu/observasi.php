    <?php ob_start(); ?>
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    include "../lsp_koneksi.php";

    function e($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
    }

    if (!function_exists('esc_sql')) {
        function esc_sql($conn, $value) {
            return mysqli_real_escape_string($conn, (string)$value);
        }
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
    $idasesor_login = $dUser['id'] ?? ($dUser['id'] ?? ($_SESSION['id_user'] ?? ''));
    $idasesor_table_id = $dUser['id'] ?? $idasesor_login;
    $linkttdas = !empty($dUser['linkttd']) ? "../imgttd/" . $dUser['linkttd'] : "";
    $op = $_REQUEST['op'] ?? '';
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FR.IA.01 Observasi - LSP SMKN 1 Cibinong</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script src="../js/jquery-2.2.3.min.js"></script>
    <script src="../js/formValidation.min.js"></script>
    <script src="../js/framework/bootstrap.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap-datepicker.js"></script>

    <script>
    window.setTimeout(function() {
        document.querySelectorAll('.alert-auto-hide').forEach(function(el) {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(function(){ el.remove(); }, 500);
        });
    }, 3000);
    </script>

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
    .logo-text strong { display:block; color:var(--white); font-size:.95rem; font-weight:700; }
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
    .alert-box {
        padding:12px 18px;
        border-radius:10px;
        font-size:.85rem;
        font-weight:500;
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:20px;
    }
    .alert-success { background:#DCFCE7; color:#15803D; border:1px solid #86EFAC; }
    .alert-error { background:#FEF2F2; color:#B91C1C; border:1px solid #FCA5A5; }
    .alert-warning { background:#FEFCE8; color:#A16207; border:1px solid #FDE68A; }
    .alert-info { background:var(--teal-light); color:var(--teal-dark); border:1px solid #99D9D9; }
    .tbl-wrap { overflow-x:auto; }
    .tbl, .unit-tbl, .obs-tbl { width:100%; border-collapse:collapse; }
    .tbl thead tr { background:var(--navy); }
    .tbl th, .unit-tbl th, .obs-tbl th {
        text-align:left;
        padding:12px 16px;
        font-size:.72rem;
        font-weight:700;
        letter-spacing:.7px;
        text-transform:uppercase;
        color:rgba(255,255,255,.78);
        background:var(--navy);
    }
    .tbl th:first-child, .unit-tbl th:first-child, .obs-tbl th:first-child { border-radius:8px 0 0 8px; }
    .tbl th:last-child, .unit-tbl th:last-child, .obs-tbl th:last-child { border-radius:0 8px 8px 0; }
    .tbl td, .unit-tbl td, .obs-tbl td {
        padding:13px 16px;
        font-size:.845rem;
        border-bottom:1px solid var(--off);
        vertical-align:middle;
    }
    .tbl tbody tr:hover td, .unit-tbl tbody tr:hover td, .obs-tbl tbody tr:hover td { background:#F0F9F9; }
    .obs-tbl .unit-head td {
        background:var(--off);
        font-weight:700;
        color:var(--navy);
        font-size:.86rem;
        border-left:3px solid var(--teal);
    }
    .obs-tbl .guide-row td {
        background:#EFF6FF;
        color:#1D4ED8;
        font-weight:600;
        line-height:1.65;
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
    .badge-gray { background:#F1F5F9; color:#64748B; }
    .btn {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        gap:8px;
        padding:9px 20px;
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
    .btn-success { background:#22C55E; color:#fff; border-color:#22C55E; }
    .btn-success:hover { background:#16A34A; color:#fff; }
    .btn-warning { background:#FFF7ED; color:#C2410C; border-color:#FDBA74; }
    .btn-warning:hover { background:#F97316; color:#fff; border-color:#F97316; }
    .btn-print { background:var(--navy); color:#fff; border-color:var(--navy); }
    .btn-print:hover { background:var(--navy-soft); color:#fff; }
    .btn-sm { padding:6px 14px; font-size:.78rem; border-radius:8px; }
    .form-grid {
        display:grid;
        grid-template-columns:180px 1fr;
        gap:14px;
        align-items:start;
        margin-bottom:18px;
    }
    .form-label { font-size:.82rem; font-weight:600; color:var(--text-sub); padding-top:10px; }
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
    textarea.form-input { resize:vertical; min-height:90px; }
    .form-actions {
        display:flex;
        flex-wrap:wrap;
        gap:10px;
        margin-top:8px;
        padding-top:16px;
        border-top:1px solid var(--border);
    }
    .radio-group, .check-group { display:flex; flex-wrap:wrap; gap:14px; align-items:center; }
    .radio-item {
        display:inline-flex;
        align-items:center;
        gap:8px;
        cursor:pointer;
        padding:7px 12px;
        border:1.5px solid var(--border);
        border-radius:9px;
        background:#fff;
    }
    .radio-item input { accent-color:var(--teal); width:16px; height:16px; }
    .rekom-card { background:var(--off); border-radius:12px; padding:20px; margin-top:16px; }
    .rekom-row { display:flex; gap:32px; align-items:flex-start; margin-bottom:16px; }
    .rekom-section { flex:1; }
    .rekom-section h4 {
        font-size:.85rem;
        font-weight:700;
        margin-bottom:12px;
        color:var(--text-sub);
        text-transform:uppercase;
        letter-spacing:.5px;
    }
    .ttd-box img {
        max-height:70px;
        border:1px solid var(--border);
        border-radius:8px;
        padding:4px;
        background:#fff;
    }
    .divider-line { height:1px; background:var(--border); margin:20px 0; }
    .row-num { font-family:'DM Mono', monospace; color:var(--text-muted); font-size:.75rem; }
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
    @media (max-width:900px) {
        



        .main { margin-left:0; }
        .form-grid { grid-template-columns:1fr; }
        .rekom-row, .section-head { flex-direction:column; align-items:flex-start; }
    }
    @media print {
        .sidebar, .topbar, .page-footer, .no-print, .form-actions { display:none !important; }
        .main { margin-left:0; }
        .content { padding:0; }
        .card { box-shadow:none; border:1px solid #ddd; }
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
            <a href="observasi.php" class="nav-item active"><i class="fas fa-eye"></i> FR.IA.01 Observasi</a>

            <div class="nav-label">Rekaman & Laporan</div>
            <a href="mak2.php" class="nav-item"><i class="fas fa-file-lines"></i> FR.AK.02 Rekaman Asesmen</a>
            <a href="mak5.php" class="nav-item"><i class="fas fa-chart-bar"></i> FR.AK.05 Laporan Asesmen</a>
            <a href="mak6baru.php" class="nav-item"><i class="fas fa-map"></i> FR.AK.06 Meninjau Proses</a>
            <a href="rekapasesi.php" class="nav-item"><i class="fas fa-calendar-check"></i> Rekap Hasil Tes</a>

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
                FR.IA.01 Observasi
                <span>Ceklis observasi aktivitas praktik</span>
            </div>
            <div class="topbar-actions">
                <div class="date-chip"><i class="fas fa-calendar"></i> <?php echo e($today); ?></div>
                <button class="icon-btn" type="button"><i class="fas fa-bell"></i></button>
            </div>
        </header>

        <div class="content">

    <?php
    if ($op == "pilihtanggal"):
        $idskema = $_GET['idskema'] ?? '';
        $kelompok = $_GET['kelompok'] ?? '';
        $idasesor = $_GET['idasesor'] ?? $idasesor_login;
    ?>
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-calendar-days" style="color:var(--teal);margin-right:8px"></i>Pilih Tanggal Observasi</h3>
                        <p>Pilih tanggal pelaksanaan observasi untuk kelompok ini</p>
                    </div>
                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>

                <form method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta">
                    <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">
                    <input type="hidden" name="idskema" value="<?php echo e($idskema); ?>">
                    <input type="hidden" name="idasesor" value="<?php echo e($idasesor); ?>">

                    <div class="form-grid">
                        <div class="form-label">Tanggal Observasi</div>
                        <select id="tanggal" name="tanggal" class="form-input" required>
                        <?php
                        $tampiltgl = "SELECT tanggal FROM pemetaan WHERE kelompok='$kelompok' AND idskema='$idskema' AND idasesor='$idasesor' GROUP BY tanggal";
                        $exectgl = mysqli_query($conn, $tampiltgl);
                        if ($exectgl && mysqli_num_rows($exectgl) > 0) {
                            while ($rtgl = mysqli_fetch_array($exectgl)) {
                                echo "<option value='".e($rtgl['tanggal'])."'>".e($rtgl['tanggal'])."</option>";
                            }
                        } else {
                            echo "<option value=''>Tanggal tidak ditemukan</option>";
                        }
                        ?>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lanjutkan</button>
                    </div>
                </form>
            </div>

    <?php
    elseif ($op == "listpeserta"):
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['obs_idasesor'] = $_POST['idasesor'] ?? '';
            $_SESSION['obs_idskema'] = $_POST['idskema'] ?? '';
            $_SESSION['obs_kelompok'] = $_POST['kelompok'] ?? '';
            $_SESSION['obs_tgl'] = $_POST['tanggal'] ?? ($_POST['tgl'] ?? '');
            header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpeserta");
            exit;
        }

        $idasesor = $_SESSION['obs_idasesor'] ?? '';
        $idskema = $_SESSION['obs_idskema'] ?? '';
        $kelompok = $_SESSION['obs_kelompok'] ?? '';
        $tgl = $_SESSION['obs_tgl'] ?? '';

        $ssl = "SELECT * FROM pemetaan WHERE kelompok='$kelompok' AND idskema='$idskema' AND tanggal='$tgl' AND idasesor='$idasesor'";
        $exec0 = mysqli_query($conn, $ssl);
        $total = $exec0 ? mysqli_num_rows($exec0) : 0;
    ?>
            <div class="card">
                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="alert-box alert-success alert-auto-hide">
                        <i class="fas fa-circle-check"></i>
                        <strong><?php echo e($_SESSION['flash_success']); ?></strong>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>

                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Peserta Observasi</h3>
                        <p>Tanggal: <strong><?php echo e($tgl); ?></strong> · <?php echo e($total); ?> peserta</p>
                    </div>
                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>

                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>ID Asesi</th>
                                <th>Nama Asesi</th>
                                <th>Tanggal</th>
                                <th style="width:180px;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($exec0 && mysqli_num_rows($exec0) > 0): ?>
                            <?php $no = 1; while ($hasil0 = mysqli_fetch_array($exec0)): 
                                $idp_temp = esc_sql($conn, $hasil0['idpeserta']);
                                $qp_temp = mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE id='$idp_temp' LIMIT 1");
                                $dp_temp = $qp_temp ? mysqli_fetch_array($qp_temp) : array();
                                $nama_asesi = $dp_temp['nama'] ?? ($hasil0['namapeserta'] ?? 'Asesi');
                            ?>
                            <tr>
                                <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                                <td><span class="badge badge-navy"><?php echo e($hasil0['idpeserta']); ?></span></td>
                                <td style="font-weight:600"><?php echo e($nama_asesi); ?></td>
                                <td style="color:var(--text-sub)"><?php echo e($hasil0['tanggal']); ?></td>
                                <td style="text-align:center">
                                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=sblobservasi&kode=in&k=<?php echo e(urlencode($hasil0['kelompok'])); ?>&tgl=<?php echo e(urlencode($hasil0['tanggal'])); ?>&idasesi=<?php echo e($hasil0['idpeserta']); ?>&idskema=<?php echo e($idskema); ?>&idasesor=<?php echo e($idasesor); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-list"></i> Daftar Unit
                                    </a>
                                </td>
                            </tr>
                            <?php $no++; endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-users-slash"></i>
                                        <p>Data peserta tidak ditemukan untuk tanggal <?php echo e($tgl); ?>.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

    <?php
    elseif ($op == "sblobservasi"):
        $idskema = $_GET['idskema'] ?? '';
        $idasesi = $_GET['idasesi'] ?? '';
        $tgl = $_GET['tgl'] ?? ($_GET['tanggal'] ?? '');
        $kelompok = $_GET['k'] ?? ($_GET['kelompok'] ?? '');
        $ie = $_GET['kode'] ?? 'in';
        $idasesor = $_GET['idasesor'] ?? $idasesor_login;

        $sqladsesi = "SELECT * FROM lsp_usertbl WHERE id='$idasesi' OR id='$idasesi' LIMIT 1";
        $execadsesi = mysqli_query($conn, $sqladsesi);
        $listadsesi = mysqli_fetch_array($execadsesi);
        $namaadsesi = $listadsesi['nama'] ?? '-';
        $emailadsesi = $listadsesi['email'] ?? '';
        $lkttdasesi = $listadsesi['linkttd'] ?? '';

        $sqlunitz = "SELECT unitsiswa.idunit, unitsiswa.idskema, unitsiswa.idadsesi, unit.kodeunit, unit.namaunit
                    FROM unitsiswa
                    INNER JOIN unit ON unitsiswa.idunit=unit.idunit
                    WHERE unitsiswa.idskema='$idskema' AND unitsiswa.idadsesi='$idasesi'";
        $execunitz = mysqli_query($conn, $sqlunitz);
        $totalunit = $execunitz ? mysqli_num_rows($execunitz) : 0;
    ?>
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-list-check" style="color:var(--teal);margin-right:8px"></i>Daftar Unit Observasi</h3>
                        <p>Asesi: <strong><?php echo e($namaadsesi); ?></strong> · Skema: <?php echo e($idskema); ?> · <?php echo e($totalunit); ?> unit</p>
                    </div>
                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>

                <div class="tbl-wrap">
                    <table class="unit-tbl">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Kode Unit</th>
                                <th>Nama Unit</th>
                                <th style="width:210px">Keterangan</th>
                                <th style="width:150px;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $idasesiobs = $idasesi;
                        $idskemaobs = $idskema;
                        if ($execunitz && mysqli_num_rows($execunitz) > 0):
                            $no = 1;
                            while ($daftaobservasi = mysqli_fetch_array($execunitz)):
                                $id_unit_skrg = $daftaobservasi['idunit'];
                                $id_asesi_skrg = $daftaobservasi['idadsesi'];
                                $cekobser = "SELECT idunit FROM rekappraktek WHERE idunit='$id_unit_skrg' AND idadsesi='$id_asesi_skrg'";
                                $cekobsera = mysqli_query($conn, $cekobser);
                                $g = $cekobsera ? mysqli_num_rows($cekobsera) : 0;
                                $keto = ($g > 0)
                                    ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Pernah diobservasi</span>'
                                    : '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Belum diobservasi</span>';
                                $idasesiobs = $daftaobservasi['idadsesi'];
                                $idskemaobs = $daftaobservasi['idskema'];
                        ?>
                            <tr>
                                <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                                <td><span class="badge badge-teal"><?php echo e($daftaobservasi['kodeunit']); ?></span></td>
                                <td style="font-weight:500"><?php echo e($daftaobservasi['namaunit']); ?></td>
                                <td><?php echo $keto; ?></td>
                                <td style="text-align:center">
                                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=observasi&kode=<?php echo e($ie); ?>&kou=<?php echo e(urlencode($daftaobservasi['kodeunit'])); ?>&idass=<?php echo e($idasesor); ?>&k=<?php echo e(urlencode($kelompok)); ?>&tgl=<?php echo e(urlencode($tgl)); ?>&idasesi=<?php echo e($idasesiobs); ?>&idskema=<?php echo e($idskemaobs); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-clipboard-check"></i> Observasi
                                    </a>
                                </td>
                            </tr>
                        <?php $no++; endwhile; else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-folder-open"></i>
                                        <p>Unit kompetensi belum tersedia untuk asesi ini.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-actions">
                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=rekomproobser&emailad=<?php echo e(urlencode($emailadsesi)); ?>&ttdasesi=<?php echo e(urlencode($lkttdasesi)); ?>&nmasesi=<?php echo e(urlencode($namaadsesi)); ?>&k=<?php echo e(urlencode($kelompok)); ?>&idass=<?php echo e($idasesor); ?>&tgl=<?php echo e(urlencode($tgl)); ?>&idasesi=<?php echo e($idasesiobs); ?>&idskema=<?php echo e($idskemaobs); ?>" class="btn btn-success">
                        <i class="fas fa-star"></i> Berikan Rekomendasi Akhir FR.IA.01
                    </a>
                </div>
            </div>

    <?php
    elseif ($op == "observasi"):
        $idskema = $_GET['idskema'] ?? '';
        $idasesi = $_GET['idasesi'] ?? '';
        $tgl = $_GET['tgl'] ?? '';
        $kelompok = $_GET['k'] ?? '';
        $kodeu = $_GET['kou'] ?? '';
        $idasesor = $_GET['idass'] ?? $idasesor_login;

        $sqlskema = "SELECT * FROM skema WHERE idskema='$idskema'";
        $execskema = mysqli_query($conn, $sqlskema);
        $listskema = mysqli_fetch_array($execskema);
        $namaskema = $listskema['namaskema'] ?? '';
        $nomorskemaob = $listskema['kodeskema'] ?? '';

        $sqladsesi = "SELECT * FROM lsp_usertbl WHERE id='$idasesi' OR id='$idasesi' LIMIT 1";
        $execadsesi = mysqli_query($conn, $sqladsesi);
        $listadsesi = mysqli_fetch_array($execadsesi);
        $namaadsesi = $listadsesi['nama'] ?? '-';
        $emailadsesi = $listadsesi['email'] ?? '';
        $linkttd1 = !empty($listadsesi['linkttd']) ? "../imgttd/" . $listadsesi['linkttd'] : "";

        $sqlunit = "SELECT unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit
                    FROM unitsiswa
                    INNER JOIN unit ON unitsiswa.idunit=unit.idunit
                    WHERE unitsiswa.idskema='$idskema' AND unitsiswa.idadsesi='$idasesi' AND unit.kodeunit='$kodeu'";
        $execunit = mysqli_query($conn, $sqlunit);
        $listunit = mysqli_fetch_array($execunit);
    ?>
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-eye" style="color:var(--teal);margin-right:8px"></i>Form Observasi Praktik</h3>
                        <p>Unit: <strong><?php echo e($kodeu); ?></strong> · Asesi: <strong><?php echo e($namaadsesi); ?></strong> · Tanggal: <?php echo e($tgl); ?></p>
                    </div>
                    <div style="display:flex;gap:10px">
                        <button onclick="window.print()" class="btn btn-print btn-sm no-print" type="button"><i class="fas fa-print"></i> Cetak</button>
                        <a href="javascript:history.back()" class="btn btn-secondary btn-sm no-print"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>

                <?php if (!$listunit): ?>
                    <div class="alert-box alert-error">
                        <i class="fas fa-circle-xmark"></i>
                        <strong>Unit tidak ditemukan.</strong>
                    </div>
                <?php else: ?>
                <form id="obs" name="obs" method="POST" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=postobs">
                    <input type="hidden" name="idasesor" value="<?php echo e($idasesor); ?>">
                    <input type="hidden" name="idadsesi" value="<?php echo e($idasesi); ?>">
                    <input type="hidden" name="idskema" value="<?php echo e($idskema); ?>">
                    <input type="hidden" name="tgl" value="<?php echo e($tgl); ?>">
                    <input type="hidden" name="kelompok" value="<?php echo e($kelompok); ?>">
                    <input type="hidden" name="email" value="<?php echo e($emailadsesi); ?>">

                    <div class="tbl-wrap">
                        <table class="obs-tbl">
                            <tbody>
                                <tr class="unit-head"><td colspan="7">Skema Sertifikasi: <?php echo e($namaskema); ?> · No. Skema: <?php echo e($nomorskemaob); ?></td></tr>
                                <tr class="unit-head"><td colspan="7">TUK: Sewaktu · Asesor: <?php echo e($namax); ?> · Asesi: <?php echo e($namaadsesi); ?> · Tanggal: <?php echo e($tgl); ?></td></tr>
                                <tr class="guide-row">
                                    <td colspan="7">
                                        Panduan bagi asesor: lengkapi nama unit kompetensi, elemen, dan kriteria unjuk kerja sesuai tabel. Centang Ya/Tidak untuk pencapaian dan K/BK untuk rekomendasi.
                                    </td>
                                </tr>
                                <tr class="unit-head">
                                    <td colspan="7">
                                        Kode Unit: <?php echo e($listunit['kodeunit']); ?> · Nama Unit: <?php echo e($listunit['namaunit']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Langkah Kerja</th>
                                    <th colspan="2">Kriteria Unjuk Kerja</th>
                                    <th style="width:70px;text-align:center">Ya</th>
                                    <th style="width:70px;text-align:center">Tidak</th>
                                    <th style="width:70px;text-align:center">K</th>
                                    <th style="width:70px;text-align:center">BK</th>
                                </tr>
                                <?php
                                $ssql = "SELECT * FROM praktek WHERE idskema='$idskema' AND kodeunit='".$listunit['kodeunit']."'";
                                $exec = mysqli_query($conn, $ssql);
                                $i = 0;
                                $xjj = 0;
                                while ($list = mysqli_fetch_array($exec)):
                                    $idpr = $list['idpraktek'];
                                    $xjj++;
                                    $ssqlzz = "SELECT rekappraktek.id AS idrpraktek,rekappraktek.idskema,rekappraktek.idadsesi,rekappraktek.bunit,rekappraktek.kodeunit,rekappraktek.idpraktek,rekappraktek.idasesor,rekappraktek.tanggal,rekappraktek.pencapaians,rekappraktek.penilaians,praktek.instruksi,praktek.obervasi
                                            FROM rekappraktek
                                            INNER JOIN praktek ON rekappraktek.idpraktek=praktek.idpraktek
                                            WHERE rekappraktek.idskema='$idskema'
                                            AND rekappraktek.idadsesi='$idasesi'
                                            AND rekappraktek.kodeunit='".$listunit['kodeunit']."'
                                            AND rekappraktek.idasesor='$idasesor'
                                            AND rekappraktek.tanggal='$tgl'
                                            AND rekappraktek.idpraktek='$idpr'";
                                    $ssqlzza = mysqli_query($conn, $ssqlzz);
                                    $ssqlzzb = $ssqlzza ? mysqli_num_rows($ssqlzza) : 0;

                                    $idrpraktek = '';
                                    $cky = 'checked';
                                    $ckt = '';
                                    $ckk = 'checked';
                                    $ckbk = '';

                                    if ($ssqlzzb > 0) {
                                        $ssqlzzd = mysqli_fetch_array($ssqlzza);
                                        $idrpraktek = $ssqlzzd['idrpraktek'] ?? '';
                                        $cky = (($ssqlzzd['pencapaians'] ?? '') == 'Y') ? 'checked' : '';
                                        $ckt = (($ssqlzzd['pencapaians'] ?? '') == 'T') ? 'checked' : '';
                                        $ckk = (($ssqlzzd['penilaians'] ?? '') == 'K') ? 'checked' : '';
                                        $ckbk = (($ssqlzzd['penilaians'] ?? '') == 'BK') ? 'checked' : '';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <?php echo e($list['instruksi']); ?>
                                        <input type="hidden" name="idunit<?php echo e($i); ?>" value="<?php echo e($listunit['idunit']); ?>">
                                        <input type="hidden" name="kodeunit<?php echo e($i); ?>" value="<?php echo e($listunit['kodeunit']); ?>">
                                    </td>
                                    <td colspan="2">
                                        <?php echo e($list['obervasi']); ?>
                                        <input type="hidden" name="idpraktek<?php echo e($i); ?>" value="<?php echo e($list['idpraktek']); ?>">
                                        <input type="hidden" name="idrpraktek<?php echo e($i); ?>" value="<?php echo e($idrpraktek); ?>">
                                    </td>
                                    <td style="text-align:center"><input type="radio" name="pcp<?php echo e($i); ?>" value="Y" <?php echo $cky; ?>></td>
                                    <td style="text-align:center"><input type="radio" name="pcp<?php echo e($i); ?>" value="T" <?php echo $ckt; ?>></td>
                                    <td style="text-align:center"><input type="radio" name="bk<?php echo e($i); ?>" value="K" <?php echo $ckk; ?>></td>
                                    <td style="text-align:center"><input type="radio" name="bk<?php echo e($i); ?>" value="BK" <?php echo $ckbk; ?>></td>
                                </tr>
                                <?php $i++; endwhile; ?>
                                <tr>
                                    <td colspan="7">
                                        <input type="hidden" id="aaunit" name="aaunit<?php echo e($listunit['idunit']); ?>" value="<?php echo e($xjj); ?>">
                                        <input type="hidden" name="n" value="<?php echo e($i); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">Nama Asesi:<br><strong><?php echo e($namaadsesi); ?></strong></td>
                                    <td colspan="5"><?php if ($linkttd1 != ""): ?><div class="ttd-box"><img src="<?php echo e($linkttd1); ?>" alt="TTD Asesi"></div><?php endif; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2">Nama Asesor:<br><strong><?php echo e($namax); ?></strong></td>
                                    <td colspan="5"><?php if ($linkttdas != ""): ?><div class="ttd-box"><img src="<?php echo e($linkttdas); ?>" alt="TTD Asesor"></div><?php endif; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="simpan" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Observasi</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>

    <?php
    elseif ($op == "postobs"):
        $email = trim($_POST['email'] ?? '');
        $ids = $_POST['idskema'] ?? '';
        $idasesi = $_POST['idadsesi'] ?? '';
        $idasesor = $_POST['idasesor'] ?? '';
        $tgl = $_POST['tgl'] ?? '';
        $kelompok = $_POST['kelompok'] ?? '';
        $n = (int)($_POST['n'] ?? 0);
        $sukses = 0;
        $gagal = 0;
        $sup = 0;

        for ($i = 0; $i <= $n - 1; $i++) {
            if (isset($_POST['pcp'.$i]) && isset($_POST['bk'.$i])) {
                $idunit = $_POST['idunit'.$i] ?? '';
                $idpraktek = $_POST['idpraktek'.$i] ?? '';
                $bk = $_POST['bk'.$i] ?? '';
                $yt = $_POST['pcp'.$i] ?? '';
                $kodeunit = $_POST['kodeunit'.$i] ?? '';
                $idrpraktek = $_POST['idrpraktek'.$i] ?? '';
                $aaunit = (float)($_POST['aaunit'.$idunit] ?? 1);
                $aahitung = ($yt == 'Y' && $aaunit > 0) ? (100 / $aaunit) : 0;

                $cekdata = "SELECT * FROM rekappraktek WHERE idskema='$ids' AND idunit='$idunit' AND idpraktek='$idpraktek' AND idadsesi='$idasesi' AND idasesor='$idasesor'";
                $ada = mysqli_query($conn, $cekdata);
                $adak = $ada ? mysqli_num_rows($ada) : 0;

                if ($adak > 0) {
                    $ssqlrekapu = "UPDATE rekappraktek SET pencapaians='$yt', penilaians='$bk', niai='$aahitung' WHERE idskema='$ids' AND idunit='$idunit' AND idpraktek='$idpraktek' AND idadsesi='$idasesi' AND idasesor='$idasesor' AND idrpraktek='$idrpraktek'";
                    $okrekapu = mysqli_query($conn, $ssqlrekapu);
                    if ($okrekapu) {
                        $sup++;
                    } else {
                        $gagal++;
                    }
                } else {
                    $ssqlrekapp = "INSERT INTO rekappraktek (idskema, idunit, kodeunit, idpraktek, idasesor, idadsesi, pencapaians, penilaians, tanggal, niai, bunit) VALUES ('$ids', '$idunit', '$kodeunit', '$idpraktek', '$idasesor', '$idasesi', '$yt', '$bk', '$tgl', '$aahitung', '$aaunit')";
                    $okrekapp = mysqli_query($conn, $ssqlrekapp);
                    if ($okrekapp) {
                        $sukses++;
                    } else {
                        $gagal++;
                    }
                }
            }
        }
    ?>
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-circle-check" style="color:var(--teal);margin-right:8px"></i>Hasil Simpan Observasi</h3>
                        <p>Data observasi praktik telah diproses.</p>
                    </div>
                </div>

                <div class="alert-box alert-success">
                    <i class="fas fa-circle-check"></i>
                    <div>
                        <strong>Proses selesai.</strong>
                        <p style="margin-top:2px;font-size:.8rem">
                            Simpan baru: <?php echo e($sukses); ?> · Update: <?php echo e($sup); ?> · Gagal: <?php echo e($gagal); ?>
                        </p>
                    </div>
                </div>

                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=sblobservasi&kode=in&k=<?php echo e(urlencode($kelompok)); ?>&tgl=<?php echo e(urlencode($tgl)); ?>&idasesi=<?php echo e($idasesi); ?>&idskema=<?php echo e($ids); ?>&idasesor=<?php echo e($idasesor); ?>" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Unit
                </a>
            </div>

    <?php
    elseif ($op == "rekomproobser"):
        $namaasesittdr = $_GET['nmasesi'] ?? '';
        $idasesorttdr = $_GET['idass'] ?? '';
        $idadsesittdr = $_GET['idasesi'] ?? '';
        $tglttdr = $_GET['tgl'] ?? '';
        $idskttdr = $_GET['idskema'] ?? '';
        $ttdasesir = !empty($_GET['ttdasesi']) ? "../imgttd/" . $_GET['ttdasesi'] : "";
        $ttdemailr = $_GET['emailad'] ?? '';
        $ttdkelompok = $_GET['k'] ?? '';

        $cekdata0r = "SELECT * FROM rekomendasi WHERE namarekom='obs' AND idskema='$idskttdr' AND idasesi='$idadsesittdr' AND tanggal='$tglttdr'";
        $ada0r = mysqli_query($conn, $cekdata0r);
        $adaxr = ($ada0r && mysqli_num_rows($ada0r) > 0) ? mysqli_fetch_array($ada0r) : null;
        $lrekr = $adaxr['rekom'] ?? 'L';
        $catr = $adaxr['catatan'] ?? '';
        $klrekr = ($lrekr == 'L') ? 'checked' : '';
        $klrek0r = ($lrekr == 'T') ? 'checked' : '';

        $sqlttd1 = "SELECT * FROM lsp_usertbl WHERE id='$idadsesittdr' LIMIT 1";
        $sqlttda1 = mysqli_query($conn, $sqlttd1);
        $listttda1 = $sqlttda1 ? mysqli_fetch_array($sqlttda1) : array();
        $namaasesittdr = $listttda1['nama'] ?? $namaasesittdr;
        if (!empty($listttda1['linkttd'])) {
            $ttdasesir = "../imgttd/" . $listttda1['linkttd'];
        }

        $sqlttdb = "SELECT * FROM lsp_usertbl WHERE id='$idasesorttdr' LIMIT 1";
        $sqlttdbr = mysqli_query($conn, $sqlttdb);
        $sqlttdbr = $sqlttdbr ? mysqli_fetch_array($sqlttdbr) : array();
        $linkttdar = !empty($sqlttdbr['linkttd']) ? "../imgttd/" . $sqlttdbr['linkttd'] : "";
        $namapttdr = $sqlttdbr['nama'] ?? $namax;
    ?>
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-star" style="color:var(--teal);margin-right:8px"></i>Rekomendasi Akhir FR.IA.01</h3>
                        <p>Berikan rekomendasi dan catatan hasil observasi praktik</p>
                    </div>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
                </div>

                <form method="post" enctype="multipart/form-data" action="<?php echo e($_SERVER['PHP_SELF']); ?>?op=srekomproobs">
                    <input type="hidden" name="idskttdo" value="<?php echo e($idskttdr); ?>">
                    <input type="hidden" name="idadsesittdo" value="<?php echo e($idadsesittdr); ?>">
                    <input type="hidden" name="tglttdo" value="<?php echo e($tglttdr); ?>">
                    <input type="hidden" name="idasesorttdo" value="<?php echo e($idasesorttdr); ?>">
                    <input type="hidden" name="emailttdo" value="<?php echo e($ttdemailr); ?>">
                    <input type="hidden" name="kelompokttddo" value="<?php echo e($ttdkelompok); ?>">

                    <div class="rekom-card">
                        <div class="rekom-row">
                            <div class="rekom-section">
                                <h4>Rekomendasi Asesor</h4>
                                <div class="radio-group">
                                    <label class="radio-item">
                                        <input type="radio" name="lrekttdo" value="L" <?php echo $klrekr; ?> required>
                                        <i class="fas fa-circle-check" style="color:#22C55E"></i> Kompeten
                                    </label>
                                    <label class="radio-item">
                                        <input type="radio" name="lrekttdo" value="T" <?php echo $klrek0r; ?>>
                                        <i class="fas fa-circle-xmark" style="color:#EF4444"></i> Belum Kompeten
                                    </label>
                                </div>
                            </div>

                            <div class="rekom-section">
                                <h4>Catatan</h4>
                                <textarea name="catatanr" class="form-input" rows="3" placeholder="Tulis catatan observasi..."><?php echo e($catr); ?></textarea>
                            </div>
                        </div>

                        <div class="divider-line"></div>

                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
                            <div>
                                <p style="font-size:.75rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesi</p>
                                <p style="font-weight:600;margin-bottom:6px"><?php echo e($namaasesittdr); ?></p>
                                <?php if ($ttdasesir != ""): ?><div class="ttd-box"><img src="<?php echo e($ttdasesir); ?>" alt="TTD Asesi"></div><?php else: ?><span class="badge badge-gray">TTD belum tersedia</span><?php endif; ?>
                            </div>
                            <div>
                                <p style="font-size:.75rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesor</p>
                                <p style="font-weight:600;margin-bottom:6px"><?php echo e($namapttdr); ?></p>
                                <?php if ($linkttdar != ""): ?><div class="ttd-box"><img src="<?php echo e($linkttdar); ?>" alt="TTD Asesor"></div><?php else: ?><span class="badge badge-gray">TTD belum tersedia</span><?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Rekomendasi</button>
                    </div>
                </form>
            </div>

    <?php
    elseif ($op == "srekomproobs"):
        $idskemarekaplo = $_POST['idskttdo'] ?? '';
        $idasesirekaplo = $_POST['idadsesittdo'] ?? '';
        $tglrekaplo = $_POST['tglttdo'] ?? '';
        $idasesorrekaplo = $_POST['idasesorttdo'] ?? '';
        $lrekaplo = $_POST['lrekttdo'] ?? '';
        $catatanrekaplo = $_POST['catatanr'] ?? '';
        $emailado = $_POST['emailttdo'] ?? '';
        $kelompoklo = $_POST['kelompokttddo'] ?? '';

        $cekdatao = "SELECT * FROM rekomendasi WHERE namarekom='obs' AND idskema='$idskemarekaplo' AND idasesi='$idasesirekaplo' AND tanggal='$tglrekaplo'";
        $adao = mysqli_query($conn, $cekdatao);

        if ($adao && mysqli_num_rows($adao) > 0) {
            $ssqlrekaplo = "UPDATE rekomendasi SET rekom='$lrekaplo', catatan='$catatanrekaplo' WHERE namarekom='obs' AND idskema='$idskemarekaplo' AND idasesi='$idasesirekaplo' AND tanggal='$tglrekaplo'";
        } else {
            $ssqlrekaplo = "INSERT INTO rekomendasi (namarekom, idskema, idasesor, idasesi, rekom, catatan, tanggal) VALUES ('obs', '$idskemarekaplo', '$idasesorrekaplo', '$idasesirekaplo', '$lrekaplo', '$catatanrekaplo', '$tglrekaplo')";
        }

        $execrekaplo = mysqli_query($conn, $ssqlrekaplo);
        if ($execrekaplo) {
            $updskemasiso = "UPDATE skemasiswa SET statustesp='Y' WHERE idskema='$idskemarekaplo' AND emailsiswa='$emailado'";
            mysqli_query($conn, $updskemasiso);

            $_SESSION['obs_idasesor'] = $idasesorrekaplo;
            $_SESSION['obs_idskema'] = $idskemarekaplo;
            $_SESSION['obs_kelompok'] = $kelompoklo;
            $_SESSION['obs_tgl'] = $tglrekaplo;
            $_SESSION['flash_success'] = 'Rekomendasi observasi berhasil disimpan.';
            header("Location: " . $_SERVER['PHP_SELF'] . "?op=listpeserta");
            exit;
        }
    ?>
            <div class="card">
                <div class="alert-box alert-error">
                    <i class="fas fa-circle-xmark"></i>
                    <strong>Penyimpanan gagal.</strong>
                </div>
                <button onclick="history.back()" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</button>
            </div>

    <?php
    elseif ($op == "updateobs"):
        $email = trim($_POST['email'] ?? '');
        $ids = $_POST['idskema'] ?? '';
        $idasesi = $_POST['idadsesi'] ?? '';
        $idasesor = $_POST['idasesor'] ?? '';
        $tgl = $_POST['tgl'] ?? '';
        $kelompok = $_POST['kelompok'] ?? '';
        $n = (int)($_POST['n'] ?? 0);
        $sukses = 0;
        $gagal = 0;

        for ($i = 0; $i <= $n - 1; $i++) {
            if (isset($_POST['pcp'.$i]) && isset($_POST['bk'.$i])) {
                $idunit = $_POST['idunit'.$i] ?? '';
                $idpraktek = $_POST['idpraktek'.$i] ?? '';
                $bk = $_POST['bk'.$i] ?? '';
                $yt = $_POST['pcp'.$i] ?? '';
                $idrpraktek = $_POST['idrpraktek'.$i] ?? '';
                $aaunit = (float)($_POST['aaunit'.$idunit] ?? 1);
                $aahitung = ($yt == 'Y' && $aaunit > 0) ? (100 / $aaunit) : 0;

                $ssqlrekapu = "UPDATE rekappraktek SET pencapaians='$yt', penilaians='$bk', niai='$aahitung' WHERE idskema='$ids' AND idunit='$idunit' AND idpraktek='$idpraktek' AND idadsesi='$idasesi' AND idasesor='$idasesor' AND idrpraktek='$idrpraktek'";
                if (mysqli_query($conn, $ssqlrekapu)) {
                    $sukses++;
                    $updatesksiswa = "UPDATE skemasiswa SET statustest='Y' WHERE emailsiswa='$email' AND idskema='$ids'";
                    mysqli_query($conn, $updatesksiswa);
                } else {
                    $gagal++;
                }
            }
        }
    ?>
            <div class="card">
                <div class="alert-box alert-success">
                    <i class="fas fa-circle-check"></i>
                    <strong>Update selesai. Sukses: <?php echo e($sukses); ?> · Gagal: <?php echo e($gagal); ?></strong>
                </div>
                <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=listpeserta" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>

    <?php
    else:
        $id_asesor_login = $_SESSION['id_user'] ?? $idasesor_login;
        $queryobmain = "SELECT kelompok, idskema, idasesor FROM pemetaan WHERE idasesor='$id_asesor_login' GROUP BY kelompok, idskema, idasesor";
        $hasilobmain = mysqli_query($conn, $queryobmain);
        $total_jadwal = $hasilobmain ? mysqli_num_rows($hasilobmain) : 0;
    ?>
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3><i class="fas fa-calendar-check" style="color:var(--teal);margin-right:8px"></i>Daftar Jadwal Observasi</h3>
                        <p>Pilih jadwal untuk memulai FR.IA.01 Observasi</p>
                    </div>
                    <span style="background:var(--teal-light);color:var(--teal-dark);padding:4px 14px;border-radius:6px;font-size:.78rem;font-weight:700">
                        <?php echo e($total_jadwal); ?> Jadwal
                    </span>
                </div>

                <?php if ($total_jadwal > 0): ?>
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th style="width:50px">No</th>
                                <th>Paket</th>
                                <th>Skema</th>
                                <th>Nama Skema</th>
                                <th style="width:190px;text-align:center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
                        while ($dataobvmain = mysqli_fetch_array($hasilobmain)):
                            $id_skema_cari = $dataobvmain['idskema'];
                            $ssqlmain = "SELECT * FROM skema WHERE idskema='$id_skema_cari'";
                            $execssql = mysqli_query($conn, $ssqlmain);
                            $baris = mysqli_fetch_array($execssql);
                            $namaskema = $baris['namaskema'] ?? "Nama Skema Tidak Ditemukan";
                        ?>
                            <tr>
                                <td class="row-num"><?php echo e(str_pad($no, 2, '0', STR_PAD_LEFT)); ?></td>
                                <td><span class="badge badge-navy"><?php echo e($dataobvmain['kelompok']); ?></span></td>
                                <td><span class="badge badge-teal"><?php echo e($dataobvmain['idskema']); ?></span></td>
                                <td style="font-weight:600"><?php echo e($namaskema); ?></td>
                                <td style="text-align:center">
                                    <a href="<?php echo e($_SERVER['PHP_SELF']); ?>?op=pilihtanggal&idskema=<?php echo e($dataobvmain['idskema']); ?>&kelompok=<?php echo e(urlencode($dataobvmain['kelompok'])); ?>&idasesor=<?php echo e($id_asesor_login); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-users"></i> Tampilkan Peserta
                                    </a>
                                </td>
                            </tr>
                        <?php $no++; endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="alert-box alert-warning">
                        <i class="fas fa-triangle-exclamation"></i>
                        <strong>Belum ada jadwal Observasi FR.IA.01 untuk ID: <?php echo e($id_asesor_login); ?>.</strong>
                    </div>
                <?php endif; ?>
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
