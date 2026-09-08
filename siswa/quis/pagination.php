<?php
session_set_cookie_params(3600*2,"/");
session_start();
include "../../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

if (empty($_SESSION['username']) || empty($_SESSION['password'])){
    echo "<!DOCTYPE html><html lang='id'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>Login Diperlukan</title><style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#F4F8FA;color:#1A2E3B}.login-card{text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)}.login-card a{display:inline-block;margin-top:14px;padding:10px 18px;border-radius:10px;background:#3BBFBF;color:#fff;text-decoration:none;font-weight:700}</style></head><body>";
    echo "<div class='login-card'><h3>Anda Harus Login Dahulu!</h3><a href='../../lsp_login.php'>Kembali ke Login</a></div></body></html>";
    exit;
}

$user = $_SESSION['username'];
$nama_lengkap = $_SESSION['nama_lengkap'] ?? $user;
$kd_modul = isset($_GET['md']) ? trim($_GET['md']) : '';
$_SESSION['kd_modul'] = $kd_modul;

$waktuk = time();

// Cek / hitung histori tes
$periksa = mysqli_query($conn, "SELECT nim FROM gradealias WHERE nim='".mysqli_real_escape_string($conn, $user)."' AND kodemodul='".mysqli_real_escape_string($conn, $kd_modul)."'");
$periksa3 = $periksa ? mysqli_num_rows($periksa) : 0;

$result = mysqli_query($conn, "SELECT * FROM modul WHERE kd_modul='".mysqli_real_escape_string($conn, $kd_modul)."' LIMIT 1");
$baris = $result ? mysqli_fetch_array($result) : array();
$batas = (isset($baris['batas']) && $baris['batas'] !== '' && (int)$baris['batas'] > 0) ? (int)$baris['batas'] : 1;
$vmenit = (int)($baris['waktu'] ?? $baris['Waktu'] ?? 30);
$vacaksoal = $baris['acak'] ?? '0';
$nmodul = $baris['modul'] ?? 'Uji Kompetensi';

if ($periksa3 >= $batas && $batas > 0) {
    echo "<!DOCTYPE html><html lang='id'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>Batas Ujian Terlampaui</title><style>body{font-family:Arial,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#F4F8FA;color:#1A2E3B}.card{text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)}</style></head><body>";
    echo "<div class='card'><h3>Batas Ujian Terlampaui</h3><p>Batas ujian modul ini adalah <strong>$batas kali</strong>. Anda telah mengikuti ujian sebanyak <strong>$periksa3 kali</strong>.</p><br><a href='javascript:window.close()' style='padding:10px 18px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:700;'>Tutup Halaman</a></div></body></html>";
    exit;
}

// Record status kerja
@mysqli_query($conn, "INSERT INTO statuskerja (nim, statusk, waktu) VALUES ('".mysqli_real_escape_string($conn, $user)."', 'Y', '$waktuk')");

// Populate pertanyaanbck jika belum ada untuk user ini
$cekada = mysqli_query($conn, "SELECT * FROM pertanyaanbck WHERE nim='".mysqli_real_escape_string($conn, $user)."' AND kd_modul='".mysqli_real_escape_string($conn, $kd_modul)."'");
if (!$cekada || mysqli_num_rows($cekada) < 1) {
    @mysqli_query($conn, "DELETE FROM pertanyaanbck WHERE kd_modul='".mysqli_real_escape_string($conn, $kd_modul)."' AND nim='".mysqli_real_escape_string($conn, $user)."'");
    
    $rsd = mysqli_query($conn, "SELECT * FROM pertanyaan WHERE kd_modul='".mysqli_real_escape_string($conn, $kd_modul)."' ORDER BY question_id");
    if ($rsd && mysqli_num_rows($rsd) > 0) {
        $no = 1;
        $q_list = array();
        while ($r_q = mysqli_fetch_array($rsd)) {
            $q_list[] = $r_q;
        }
        if ($vacaksoal == '1') {
            shuffle($q_list);
        }
        foreach ($q_list as $row) {
            $id = (int)$row["question_id"];
            $question = mysqli_real_escape_string($conn, $row["question"]);
            $alt_5 = mysqli_real_escape_string($conn, $row["oa"] ?? '');
            $unitalias = mysqli_real_escape_string($conn, $row["unitalias"] ?? '');
            mysqli_query($conn, "INSERT INTO pertanyaanbck (question_id, kd_modul, question, answer, njawab, nim, menit, nourut, unitalias) VALUES ('$id', '$kd_modul', '$question', '$alt_5', '', '".mysqli_real_escape_string($conn, $user)."', '$vmenit', '$no', '$unitalias')");
            $no++;
        }
    }
}

// Fetch all questions in pertanyaanbck
$resulta = mysqli_query($conn, "SELECT * FROM pertanyaanbck WHERE nim='".mysqli_real_escape_string($conn, $user)."' AND kd_modul='".mysqli_real_escape_string($conn, $kd_modul)."' ORDER BY nourut ASC");
$amenit = $vmenit;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tes Online - <?php echo e($nmodul); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--teal-light:#E8F8F8;--navy:#0F2A3A;--navy-soft:#1E4060;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--green:#22C55E;--red:#EF4444;--radius:16px;--shadow:0 6px 24px rgba(15,42,58,.08)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',Arial,sans-serif;background:var(--off);color:var(--text-main);min-height:100vh;display:flex;flex-direction:column}
.quiz-topbar{background:#fff;border-bottom:1px solid var(--border);padding:14px 28px;position:sticky;top:0;z-index:100;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 10px rgba(15,42,58,.04)}
.topbar-info strong{display:block;font-size:1.02rem;color:var(--navy)}
.topbar-info span{font-size:.8rem;color:var(--text-sub);font-weight:600}
.timer-card{display:flex;align-items:center;gap:10px;padding:8px 18px;border-radius:12px;background:var(--navy);color:#fff}
.timer-card i{color:var(--teal);font-size:1.1rem}
.timer-card input{background:transparent;border:none;color:#fff;font-family:'DM Mono',monospace;font-size:1.25rem;font-weight:800;width:105px;outline:none;text-align:center}

.quiz-container{max-width:1100px;width:100%;margin:28px auto;padding:0 20px;display:grid;grid-template-columns:1fr 310px;gap:24px;align-items:start}

.nav-panel{background:#fff;border-radius:var(--radius);border:1px solid var(--border);box-shadow:var(--shadow);padding:22px;position:sticky;top:90px}
.nav-panel h4{font-size:.9rem;font-weight:800;color:var(--text-sub);margin-bottom:14px;text-transform:uppercase;letter-spacing:.5px}
.nav-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px}
.nav-num{aspect-ratio:1;border-radius:10px;border:1.5px solid var(--border);background:#FBFDFE;color:var(--text-main);font-weight:800;font-size:.88rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all .2s;list-style:none}
.nav-num:hover{border-color:var(--teal);color:var(--teal-dark);background:var(--teal-light)}
.nav-num.answered{background:var(--green);color:#fff;border-color:var(--green)}
.nav-legend{display:flex;gap:14px;margin-top:18px;padding-top:14px;border-top:1px solid var(--border);font-size:.78rem;font-weight:700;color:var(--text-sub)}
.legend-item{display:flex;align-items:center;gap:6px}
.legend-box{width:12px;height:12px;border-radius:4px}
.legend-box.ans{background:var(--green)}
.legend-box.unans{background:#FBFDFE;border:1px solid var(--border)}

#loading{text-align:center;padding:20px;color:var(--teal-dark);font-weight:700;display:none}

@media(max-width:850px){.quiz-container{grid-template-columns:1fr}.nav-panel{position:static}}
</style>
</head>
<body>

<form name="User" style="display:none">
    <input id="clock" name="TimeLeft">
    <input name="TimeTaken" type="hidden">
    <input name="Watch" type="hidden">
</form>

<header class="quiz-topbar">
    <div class="topbar-info">
        <strong><?php echo e($nmodul); ?> (Kode: <?php echo e($kd_modul); ?>)</strong>
        <span>Peserta: <?php echo e($nama_lengkap); ?> (ID: <?php echo e($user); ?>)</span>
    </div>
    <div class="timer-card">
        <i class="fas fa-clock"></i>
        <span>Sisa Waktu:</span>
        <input type="text" id="display_clock" readonly value="00:00:00">
    </div>
</header>

<div class="quiz-container">
    <main class="quiz-main">
        <div id="loading"><i class="fas fa-spinner fa-spin"></i> Memuat Soal...</div>
        <div id="content"></div>
    </main>

    <aside class="nav-panel">
        <h4><i class="fas fa-grip"></i> Nomor Soal</h4>
        <ul class="nav-grid" id="pagination">
            <?php
            $i = 1;
            if ($resulta && mysqli_num_rows($resulta) > 0) {
                while ($a = mysqli_fetch_array($resulta)) {
                    $abcjawab = $a["njawab"];
                    $cls = !empty($abcjawab) ? 'answered' : '';
                    echo '<li class="nav-num '.$cls.'" id="'.$i.'">'.$i.'</li>';
                    $i++;
                }
            }
            ?>
        </ul>
        <div class="nav-legend">
            <div class="legend-item"><div class="legend-box ans"></div> Sudah Dijawab</div>
            <div class="legend-item"><div class="legend-box unans"></div> Belum Dijawab</div>
        </div>
    </aside>
</div>

<form id="p1" name="p1" action="proses_assesmentbaruabcd.php" method="post">
    <input type="hidden" name="nim" value="<?php echo e($user); ?>">
    <input type="hidden" name="nama" value="<?php echo e($nama_lengkap); ?>">
    <input type="hidden" name="kmdl" value="<?php echo e($kd_modul); ?>">
    <input type="hidden" name="kounter" value="<?php echo $periksa3 + 1; ?>">
</form>

<script>
$(document).ready(function(){
    function Display_Load() {
        $("#loading").show();
    }
    function Hide_Load() {
        $("#loading").hide();
    }

    // Default load page 1
    Display_Load();
    $("#content").load("pagination_data.php?page=1&md=<?php echo urlencode($kd_modul); ?>&usern=<?php echo urlencode($user); ?>", function(){
        Hide_Load();
    });

    // Pagination Click
    $(document).on("click", "#pagination li", function(){
        Display_Load();
        var pageNum = this.id;
        var mmenit = document.User.TimeLeft.value;
        var vwreal = document.User.Watch.value;
        var vkdmdl = "<?php echo e($kd_modul); ?>";
        var vnim = "<?php echo e($user); ?>";
        
        $.post('updatewaktu.php', {vmenit: mmenit, kodemd: vkdmdl, usern: vnim, wreal: vwreal});

        $.ajax({ 
            type: 'post',                                     
            url: 'api.php',                          
            data: {vkdm: vkdmdl, vnis: vnim, vno: pageNum},                        
            dataType: 'json',                     
            success: function(data) {
                if (data && data[8]) {
                    var vnomor = data[8];         
                    var vnjawab = data[4];            
                    if (vnjawab !== '') {
                        $("#" + vnomor).addClass("answered");
                    }
                }
            } 
        });

        $("#content").load("pagination_data.php?page=" + pageNum + "&md=" + encodeURIComponent(vkdmdl) + "&usern=" + encodeURIComponent(vnim), function(){
            Hide_Load();
        });
    });
});

// Sync timer value to topbar display
setInterval(function(){
    var val = document.User.TimeLeft.value;
    if(val) {
        document.getElementById('display_clock').value = val;
    }
}, 500);

// Timer logic
var TimeOver = true;
var n = <?php echo $amenit; ?>;

function getJam(Tanggal) {
   Jam = (Tanggal.getHours() < 10) ? "0" + Tanggal.getHours() + ":" : Tanggal.getHours() + ":";
   Jam += (Tanggal.getMinutes() < 10) ? "0" + Tanggal.getMinutes() + ":" : Tanggal.getMinutes() + ":";
   Jam += (Tanggal.getSeconds() < 10) ? "0" + Tanggal.getSeconds() : Tanggal.getSeconds();
   return Jam;
}

function dispJam() {
   TglCur = new Date();
   document.User.Watch.value = getJam(TglCur);
   document.User.TimeTaken.value = getWaktu(TglCur, TglStart);
    
   if ((Tgl.getTime() - TglCur.getTime()) <= 0) {
      if(TimeOver) TimeOverWarn();
      document.User.TimeLeft.value = "00:00:00";
   } else {
      document.User.TimeLeft.value = getWaktu(Tgl, TglCur);
   }
   setTimeout("dispJam()", 1000);
}

function getWaktu(Tgl, TglCur) {
   TmLf = Tgl.getTime() - TglCur.getTime();
   TmLfHours = Math.floor(TmLf / 3600000); 
   TmLfMinutes = Math.floor((TmLf % 3600000) / 60000);
   TmLfSeconds = Math.round((TmLf % 60000) / 1000);
   TmLfStr = (TmLfHours < 10) ? "0" + TmLfHours + ":" : TmLfHours + ":";
   TmLfStr += (TmLfMinutes < 10) ? "0" + TmLfMinutes + ":" : TmLfMinutes + ":";
   TmLfStr += (TmLfSeconds < 10) ? "0" + TmLfSeconds : TmLfSeconds;
   return TmLfStr;
}

function TimeOverWarn() {
   alert("Waktu ujian Anda telah habis!"); 
   document.p1.submit();
   TimeOver = true;
   return true;
}

Tanggal = new Date();
Tgl = new Date();
TglStart = new Date();
Tgl.setTime(Tgl.getTime() + n * 60 * 1000);
dispJam();
</script>
</body>
</html>
