<?php
session_start();
include "../../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

if (empty($_SESSION['username']) || empty($_SESSION['password'])) {
    echo "<!DOCTYPE html><html lang='id'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>Login Diperlukan</title><style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#F4F8FA;color:#1A2E3B}.login-card{text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)}.login-card a{display:inline-block;margin-top:14px;padding:10px 18px;border-radius:10px;background:#3BBFBF;color:#fff;text-decoration:none;font-weight:700}</style></head><body>";
    echo "<div class='login-card'><h3>Anda Harus Login Dahulu!</h3><a href='../../lsp_login.php'>Kembali ke Login</a></div></body></html>";
    exit;
}

$uname = $_SESSION['username'];
$kmd = isset($_GET['kmd']) ? $_GET['kmd'] : '';

$resultabc = "SELECT * FROM modul WHERE kd_modul = '".mysqli_real_escape_string($conn, $kmd)."' LIMIT 1";
$resultabcd = mysqli_query($conn, $resultabc);
$barisx = $resultabcd ? mysqli_fetch_array($resultabcd) : array();

if (!$barisx) {
    die("Kode tes tidak valid (KD: ".e($kmd).")");
}

// Copy questions from `pertanyaan` to `pertanyaanbck` for this student if not existing yet
$cekada = mysqli_query($conn, "SELECT * FROM pertanyaanbck WHERE nim='$uname' AND kd_modul='".mysqli_real_escape_string($conn, $kmd)."'");
if (!$cekada || mysqli_num_rows($cekada) == 0) {
    $q_source = mysqli_query($conn, "SELECT * FROM pertanyaan WHERE kd_modul='".mysqli_real_escape_string($conn, $kmd)."' ORDER BY question_id");
    if ($q_source && mysqli_num_rows($q_source) > 0) {
        $no = 1;
        while ($row_q = mysqli_fetch_array($q_source)) {
            $qid = (int)$row_q['question_id'];
            $qtext = mysqli_real_escape_string($conn, $row_q['question']);
            $ualias = mysqli_real_escape_string($conn, $row_q['unitalias'] ?? '');
            mysqli_query($conn, "INSERT INTO pertanyaanbck (question_id, kd_modul, question, answer, njawab, nim, menit, nourut, unitalias) VALUES ('$qid', '$kmd', '$qtext', '', '', '$uname', 30, '$no', '$ualias')");
            $no++;
        }
    }
}

$waktu_menit = (int)($barisx['waktu'] ?? $barisx['Waktu'] ?? 30);
$jumlah_soal = (int)($barisx['jumlah_soal'] ?? 0);
$nama_modul = $barisx['modul'] ?? 'Uji Kompetensi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Persiapan Tes - LSP</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--navy:#0F2A3A;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--radius:16px;--shadow:0 10px 30px rgba(15,42,58,.1)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',Arial,sans-serif;background:var(--off);color:var(--text-main);display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
.card-box{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--border);width:100%;max-width:520px;overflow:hidden;animation:fadeIn .4s ease both}
@keyframes fadeIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.card-head{background:linear-gradient(135deg,var(--navy) 0%,#1E4060 100%);color:#fff;padding:28px;text-align:center}
.card-head h2{font-size:1.25rem;font-weight:800;margin-bottom:4px}
.timer-badge{display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border-radius:999px;background:rgba(59,191,191,.2);color:#A7F3F3;font-weight:800;font-size:1.1rem;margin-top:14px}
.card-body{padding:28px}
.meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px}
.meta-box{border:1px solid var(--border);background:#FBFDFE;border-radius:12px;padding:14px}
.meta-box span{display:block;font-size:.75rem;font-weight:700;color:var(--text-sub);margin-bottom:4px}
.meta-box strong{display:block;font-size:.9rem}
.btn-start{width:100%;padding:13px;border-radius:12px;background:var(--teal);color:#fff;border:none;font-size:.94rem;font-weight:800;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none}
.btn-start:hover{background:var(--teal-dark)}
.btn-cancel{display:block;text-align:center;margin-top:12px;color:var(--text-sub);font-size:.84rem;font-weight:700;text-decoration:none}
</style>
</head>
<body>

<div class="card-box">
    <div class="card-head">
        <h2><i class="fas fa-clock"></i> Persiapan Memulai Tes</h2>
        <div class="timer-badge">
            <i class="fas fa-hourglass-start"></i> Durasi: <?php echo $waktu_menit; ?> Menit
        </div>
    </div>
    <div class="card-body">
        <div class="meta-grid">
            <div class="meta-box">
                <span>Modul Soal</span>
                <strong><?php echo e($nama_modul); ?></strong>
            </div>
            <div class="meta-box">
                <span>Jumlah Soal</span>
                <strong><?php echo $jumlah_soal > 0 ? $jumlah_soal . ' Pertanyaan' : 'Tersedia'; ?></strong>
            </div>
        </div>

        <form method="GET" action="pagination.php">
            <input type="hidden" name="md" value="<?php echo e($kmd); ?>">
            <button type="submit" class="btn-start">
                <i class="fas fa-arrow-right"></i> Lanjutkan Masuk ke Soal
            </button>
            <a href="javascript:history.back()" class="btn-cancel">Batalkan / Kembali</a>
        </form>
    </div>
</div>

</body>
</html>
