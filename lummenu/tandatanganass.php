<?php ob_start(); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../lsp_koneksi.php";

function e($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<style>
            body{
                font-family:Arial,sans-serif;
                display:flex;
                align-items:center;
                justify-content:center;
                height:100vh;
                background:#F4F8FA;
            }
          </style>";

    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>
            <h3>Anda Harus Login Dahulu!</h3>
            <a href='../lsp_login.php'>Kembali ke Login</a>
          </div>";
    exit;
}

if ($_SESSION['level'] != 'asesor') {
    echo "<style>
            body{
                font-family:Arial,sans-serif;
                display:flex;
                align-items:center;
                justify-content:center;
                height:100vh;
                background:#F4F8FA;
            }
          </style>";

    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>
            <h3>Anda Tidak Punya Hak Akses!</h3>
            <a href='../lsp_login.php'>Kembali ke Login</a>
          </div>";
    exit;
}

$today = date('d F Y');
$current_time = date('H:i');

$uname = $_SESSION['username'] ?? '';

$qUser = "SELECT * FROM lsp_usertbl WHERE email='$uname' LIMIT 1";
$rUser = mysqli_query($conn, $qUser);
$dUser = mysqli_fetch_array($rUser);

$namax = $dUser['nama'] ?? 'Asesor';
$iduser = $dUser['id'] ?? '';
$linkttd = $dUser['linkttd'] ?? '';

if (empty($linkttd)) {
    $namattd = "../imgttd/tidakada.png";
} else {
    $namattd = "../imgttd/" . $linkttd . "?v=" . time();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Tanda Tangan Asesor - LSP SMKN 1 Cibinong</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<script>
function NewWindow(mypage,myname,w,h,scroll){
    LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
    TopPosition = (screen.height) ? (screen.height-h)/2 : 0;

    settings =
        'height=' + h +
        ',width=' + w +
        ',top=' + TopPosition +
        ',left=' + LeftPosition +
        ',scrollbars=' + scroll +
        ',resizable';

    win = window.open(mypage,myname,settings);
}
</script>

<style>
:root{
    --teal:#3BBFBF;
    --teal-dark:#2A9999;
    --teal-light:#E8F8F8;
    --teal-glow:rgba(59,191,191,.18);

    --navy:#0F2A3A;
    --navy-soft:#1E4060;

    --off:#F4F8FA;
    --border:#DDE8ED;

    --text-main:#1A2E3B;
    --text-sub:#5A7384;
    --text-muted:#92A9B5;

    --red:#EF4444;

    --sidebar-w:260px;
    --radius:14px;

    --shadow:0 2px 16px rgba(15,42,58,.07);
}

*,
*::before,
*::after{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

html{
    font-size:15px;
    scroll-behavior:smooth;
}

body{
    font-family:'Plus Jakarta Sans',sans-serif;
    background:var(--off);
    color:var(--text-main);
    display:flex;
    min-height:100vh;
    overflow-x:hidden;
}

/* SIDEBAR */





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

.logo-box{
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

.logo-box img{
    width:100%;
    height:100%;
    object-fit:contain;
}

.logo-text{
    line-height:1.2;
}

.logo-text strong{
    display:block;
    color:#fff;
    font-size:.95rem;
    font-weight:700;
}

.logo-text span{
    color:var(--teal);
    font-size:.72rem;
    font-weight:500;
    letter-spacing:.5px;
}

.sidebar-nav{
    padding:16px 12px;
    flex:1;
}

.nav-label{
    color:rgba(255,255,255,.3);
    font-size:.67rem;
    font-weight:700;
    letter-spacing:1.2px;
    text-transform:uppercase;
    padding:12px 12px 6px;
}

.nav-item{
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

.nav-item:hover{
    background:rgba(255,255,255,.07);
    color:#fff;
    text-decoration:none;
}

.nav-item.active{
    background:var(--teal);
    color:#fff;
    box-shadow:0 4px 12px rgba(59,191,191,.35);
}

.nav-item i{
    width:18px;
    text-align:center;
    font-size:.9rem;
    flex-shrink:0;
}

.sidebar-footer{
    padding:16px 14px;
    border-top:1px solid rgba(255,255,255,.07);
}

.user-card{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px 12px;
    border-radius:10px;
    background:rgba(255,255,255,.05);
}

.user-avatar{
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

.user-info strong{
    display:block;
    color:#fff;
    font-size:.82rem;
}

.user-info span{
    color:var(--teal);
    font-size:.72rem;
}

.btn-logout{
    margin-left:auto;
    color:rgba(255,255,255,.35);
    background:none;
    border:none;
    cursor:pointer;
    font-size:.85rem;
}

.btn-logout:hover{
    color:var(--red);
}

/* MAIN */
.main{
    margin-left:var(--sidebar-w);
    flex:1;
    display:flex;
    flex-direction:column;
    min-height:100vh;
}

.topbar{
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

.topbar-title{
    font-size:1.1rem;
    font-weight:700;
    flex:1;
}

.topbar-title span{
    color:var(--text-sub);
    font-weight:400;
    font-size:.875rem;
    margin-left:8px;
}

.topbar-actions{
    display:flex;
    align-items:center;
    gap:10px;
}

.icon-btn{
    width:38px;
    height:38px;
    border-radius:10px;
    border:1.5px solid var(--border);
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--text-sub);
}

.date-chip{
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

.content{
    padding:32px;
    display:flex;
    flex-direction:column;
    gap:24px;
}

.card{
    background:#fff;
    border-radius:var(--radius);
    padding:28px;
    box-shadow:var(--shadow);
    animation:fadeUp .4s ease both;
}

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(16px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

.section-head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    margin-bottom:24px;
}

.section-head h3{
    font-size:1.05rem;
    font-weight:700;
}

.section-head p{
    font-size:.78rem;
    color:var(--text-sub);
    margin-top:2px;
}

.btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:10px 18px;
    border-radius:10px;
    font-size:.85rem;
    font-weight:600;
    cursor:pointer;
    text-decoration:none;
    border:none;
    transition:all .2s;
    font-family:'Plus Jakarta Sans',sans-serif;
}

.btn:hover{
    text-decoration:none;
}

.btn-primary{
    background:var(--teal);
    color:#fff;
    box-shadow:0 2px 8px rgba(59,191,191,.3);
}

.btn-primary:hover{
    background:var(--teal-dark);
    color:#fff;
}

.signature-wrapper{
    display:flex;
    flex-direction:column;
    gap:18px;
    align-items:flex-start;
}

.signature-box{
    width:100%;
    max-width:620px;
    min-height:260px;
    border:2px dashed var(--border);
    border-radius:18px;
    background:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    overflow:hidden;
}

.signature-box img{
    max-width:100%;
    max-height:220px;
    object-fit:contain;
}

.signature-note{
    font-size:.82rem;
    color:var(--text-sub);
    line-height:1.6;
}

.page-footer{
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

.page-footer strong{
    color:var(--teal-dark);
}

@media(max-width:900px){

    




    .main{
        margin-left:0;
    }

    .content{
        padding:20px;
    }

    .section-head{
        flex-direction:column;
        align-items:flex-start;
    }

    .signature-box{
        min-height:220px;
    }
}
</style>
</head>

<body>

<!-- SIDEBAR -->
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

        <a href="validasiapl2.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i>
            Validasi APL2
        </a>

        <a href="asesormain.php" class="nav-item">
            <i class="fas fa-folder-open"></i>
            FR.IA.08 Portofolio
        </a>

        <a href="observasi.php" class="nav-item">
            <i class="fas fa-eye"></i>
            FR.IA.01 Observasi
        </a>

        <div class="nav-label">Rekaman & Laporan</div>

        <a href="mak2.php" class="nav-item">
            <i class="fas fa-file-lines"></i>
            FR.AK.02 Rekaman Asesmen
        </a>

        <a href="mak5.php" class="nav-item">
            <i class="fas fa-chart-bar"></i>
            FR.AK.05 Laporan Asesmen
        </a>

        <a href="mak6baru.php" class="nav-item">
            <i class="fas fa-map"></i>
            FR.AK.06 Meninjau Proses
        </a>

        <a href="rekapasesi.php" class="nav-item">
            <i class="fas fa-calendar-check"></i>
            Rekap Hasil Tes
        </a>

        <div class="nav-label">Perencanaan</div>

        <a href="mapaasesor.php" class="nav-item">
            <i class="fas fa-sitemap"></i>
            FR.MAPA.01 Merencanakan
        </a>

        <a href="pihakketiga.php" class="nav-item">
            <i class="fas fa-users"></i>
            FR.IA.10 Pihak Ketiga
        </a>

        <a href="ceklistintrumen.php" class="nav-item">
            <i class="fas fa-clipboard-list"></i>
            FR.IA.11 Ceklist Instrumen
        </a>

        <div class="nav-label">Akun</div>

        <a href="tandatanganass.php" class="nav-item active">
            <i class="fas fa-signature"></i>
            Tanda Tangan
        </a>

        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)">
            <i class="fas fa-right-from-bracket"></i>
            Logout
        </a>

    </nav>

    <div class="sidebar-footer">
        <div class="user-card">

            <div class="user-avatar">
                <?php echo e(strtoupper(substr($namax,0,2))); ?>
            </div>

            <div class="user-info">
                <strong><?php echo e($namax); ?></strong>
                <span>Asesor LSP</span>
            </div>

            <button class="btn-logout" onclick="window.location='../logout.php'">
                <i class="fas fa-right-from-bracket"></i>
            </button>

        </div>
    </div>

</aside>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <header class="topbar">

        <div class="topbar-title">
            Tanda Tangan Asesor
            <span>Kelola dan perbarui tanda tangan digital asesor</span>
        </div>

        <div class="topbar-actions">

            <div class="date-chip">
                <i class="fas fa-calendar"></i>
                <?php echo e($today); ?>
            </div>

            <button class="icon-btn" type="button">
                <i class="fas fa-signature"></i>
            </button>

        </div>

    </header>

    <!-- CONTENT -->
    <div class="content">

        <div class="card">

            <div class="section-head">

                <div>
                    <h3>
                        <i class="fas fa-signature" style="color:var(--teal);margin-right:8px"></i>
                        Form Tanda Tangan
                    </h3>

                    <p>
                        Upload atau perbarui tanda tangan asesor untuk kebutuhan dokumen asesmen.
                    </p>
                </div>

                <a
                    href="../siswa/ttdb/index.php?id=<?php echo e($iduser); ?>"
                    class="btn btn-primary"
                    onclick="NewWindow(this.href,'name','500','500','yes');return false;"
                >
                    <i class="fas fa-pen"></i>
                    Tanda Tangan Baru / Update
                </a>

            </div>

            <div class="signature-wrapper">

                <div class="signature-box">
                    <img src="<?php echo e($namattd); ?>" alt="Tanda Tangan">
                </div>

                <div class="signature-note">
                    Tanda tangan ini akan digunakan secara otomatis pada formulir dan dokumen asesmen
                    seperti FR.IA, FR.AK, dan formulir pendukung lainnya.
                </div>

            </div>

        </div>

    </div>

    <!-- FOOTER -->
    <footer class="page-footer">

        <span>
            © <?php echo date('Y'); ?>
            <strong>LSP SMKN 1 Cibinong</strong>.
            Semua hak dilindungi.
        </span>

        <span>
            Versi 1.0.0 ·
            <?php echo e($today); ?>,
            <?php echo e($current_time); ?> WIB
        </span>

    </footer>

</div>

</body>
</html>

<?php ob_end_flush(); ?>