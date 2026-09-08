<?php
session_start();
include "../../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
    echo "<!DOCTYPE html><html lang='id'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>Login Diperlukan</title><style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#F4F8FA;color:#1A2E3B}.login-card{text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)}.login-card a{display:inline-block;margin-top:14px;padding:10px 18px;border-radius:10px;background:#3BBFBF;color:#fff;text-decoration:none;font-weight:700}</style></head><body>";
    echo "<div class='login-card'><h3>Anda Harus Login Dahulu!</h3><a href='../../lsp_login.php'>Kembali ke Login</a></div></body></html>";
    exit;
}

$uname = $_SESSION['username'];
$nama = $_SESSION['nama_lengkap'] ?? $uname;
$kd_modul = $_GET['md'] ?? '';

$q_mod = mysqli_query($conn, "SELECT * FROM modul WHERE kd_modul='".mysqli_real_escape_string($conn, $kd_modul)."' LIMIT 1");
$d_mod = $q_mod ? mysqli_fetch_array($q_mod) : array();
$nama_modul = $d_mod['modul'] ?? 'Tes Tertulis';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Konfirmasi Tes Online - LSP</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--teal-glow:rgba(59,191,191,.18);--navy:#0F2A3A;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--radius:16px;--shadow:0 10px 30px rgba(15,42,58,.1)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',Arial,sans-serif;background:var(--off);color:var(--text-main);display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
.quiz-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--border);width:100%;max-width:540px;overflow:hidden;animation:fadeIn .4s ease both}
@keyframes fadeIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.card-head{background:linear-gradient(135deg,var(--navy) 0%,#1E4060 100%);color:#fff;padding:32px 28px;text-align:center;position:relative}
.card-head h2{font-size:1.35rem;font-weight:800;margin-bottom:6px}
.card-head p{font-size:.86rem;color:rgba(255,255,255,.75)}
.card-body{padding:28px}
.info-row{display:flex;justify-content:space-between;align-items:center;padding:12px 14px;border:1px solid var(--border);border-radius:12px;background:#FBFDFE;margin-bottom:10px}
.info-row span{font-size:.82rem;font-weight:700;color:var(--text-sub)}
.info-row strong{font-size:.9rem;color:var(--text-main)}
.honest-box{margin:22px 0;padding:14px;border:1.5px dashed var(--teal);border-radius:12px;background:var(--teal-light);display:flex;align-items:center;gap:12px;cursor:pointer}
.honest-box input[type="checkbox"]{width:18px;height:18px;accent-color:var(--teal-dark);cursor:pointer}
.honest-box label{font-size:.85rem;font-weight:700;color:var(--teal-dark);cursor:pointer}
.btn-submit{width:100%;padding:13px;border-radius:12px;background:var(--teal);color:#fff;border:none;font-size:.94rem;font-weight:800;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px}
.btn-submit:disabled{background:#C2D1D9;cursor:not-allowed;opacity:.7}
.btn-submit:not(:disabled):hover{background:var(--teal-dark)}
.btn-cancel{display:block;text-align:center;margin-top:12px;color:var(--text-sub);font-size:.84rem;font-weight:700;text-decoration:none}
.btn-cancel:hover{color:var(--text-main)}
</style>
</head>
<body>

<div class="quiz-card">
    <div class="card-head">
        <h2><i class="fas fa-file-pen"></i> Konfirmasi Tes Online</h2>
        <p>Silakan periksa detail tes sebelum memulai</p>
    </div>
    <div class="card-body">
        <div class="info-row">
            <span>Nama Asesi</span>
            <strong><?php echo e($nama); ?></strong>
        </div>
        <div class="info-row">
            <span>Kode Modul</span>
            <strong><?php echo e($kd_modul); ?></strong>
        </div>
        <div class="info-row">
            <span>Nama Modul</span>
            <strong><?php echo e($nama_modul); ?></strong>
        </div>

        <form method="GET" action="redirectview.php">
            <input type="hidden" name="usern" value="<?php echo e($uname); ?>">
            <input type="hidden" name="kmd" value="<?php echo e($kd_modul); ?>">
            
            <div class="honest-box" onclick="document.getElementById('ckok').click()">
                <input type="checkbox" name="test" id="ckok" onclick="event.stopPropagation()">
                <label for="ckok">Saya berjanji akan bersikap jujur dan mengerjakan tes ini sendiri.</label>
            </div>

            <button type="submit" id="lanjutkan" name="lanjutkan" class="btn-submit" value="Lanjutkan" disabled>
                <i class="fas fa-play"></i> Mulai Kerjakan Soal
            </button>
            <a href="javascript:window.close()" class="btn-cancel">Batal / Tutup Halaman</a>
        </form>
    </div>
</div>

<script>
var checker = document.getElementById('ckok');
var sendbtn = document.getElementById('lanjutkan');
checker.onchange = function() {
    sendbtn.disabled = !this.checked;
};
</script>
</body>
</html>
