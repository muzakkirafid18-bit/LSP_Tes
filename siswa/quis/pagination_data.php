<?php
session_start();
error_reporting(0);
include "../../lsp_koneksi.php";

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

$per_page = 1; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$kdm = isset($_GET['md']) && $_GET['md'] !== '' ? trim($_GET['md']) : (isset($_SESSION['kd_modul']) ? $_SESSION['kd_modul'] : '');
$unim = isset($_GET['usern']) && $_GET['usern'] !== '' ? trim($_GET['usern']) : (isset($_SESSION['username']) ? $_SESSION['username'] : '');
if (!empty($kdm)) {
    $_SESSION['kd_modul'] = $kdm;
}

// Cek Pesan Login
$login = @mysqli_query($conn, "SELECT * FROM useronline WHERE nim='".mysqli_real_escape_string($conn, $unim)."'");
$login2 = $login ? mysqli_fetch_row($login) : array();
$pesan1 = isset($login2[5]) ? $login2[5] : '';

// Hitung Counter Ujian
$periksa3 = 0;
$periksac = mysqli_query($conn, "SELECT nim FROM gradealias WHERE nim='".mysqli_real_escape_string($conn, $unim)."' AND kodemodul='".mysqli_real_escape_string($conn, $kdm)."'");
if($periksac) {
    $periksacc = mysqli_num_rows($periksac);
    $periksa3 = $periksacc + 1;
}

// Ambil Setting Modul
$resultvv = mysqli_query($conn, "SELECT * FROM modul WHERE kd_modul='".mysqli_real_escape_string($conn, $kdm)."' LIMIT 1");
$baris = $resultvv ? mysqli_fetch_array($resultvv) : array();
$vjmlsoal = isset($baris['jumlah_soal']) ? $baris['jumlah_soal'] : 0;
$vacakpg = isset($baris['acak']) ? $baris['acak'] : '0';

// Query Ambil Soal dari pertanyaanbck
$start = max(0, ($page - 1) * $per_page);
$orderby = ($vacakpg == '1') ? "" : "ORDER BY nourut";
$sql = "SELECT * FROM pertanyaanbck WHERE kd_modul='".mysqli_real_escape_string($conn, $kdm)."' AND nim='".mysqli_real_escape_string($conn, $unim)."' $orderby LIMIT $start, $per_page";
$rsd1 = mysqli_query($conn, $sql);

// Fallback jika pertanyaanbck kosong: Ambil langsung dari `pertanyaan`
if (!$rsd1 || mysqli_num_rows($rsd1) == 0) {
    $sql = "SELECT question_id, question, '' AS njawab FROM pertanyaan WHERE kd_modul='".mysqli_real_escape_string($conn, $kdm)."' ORDER BY question_id LIMIT $start, $per_page";
    $rsd1 = mysqli_query($conn, $sql);
}
?>

<style>
.question-card{background:#fff;border-radius:14px;border:1px solid #DDE8ED;box-shadow:0 2px 14px rgba(15,42,58,.05);padding:24px;margin-bottom:20px}
.question-header{display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #DDE8ED;padding-bottom:14px;margin-bottom:18px}
.question-no{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:999px;background:#E8F8F8;color:#2A9999;font-weight:800;font-size:.88rem}
.btn-finish-quiz{background:#EF4444;color:#fff;border:none;padding:8px 16px;border-radius:10px;font-weight:800;font-size:.84rem;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all .2s}
.btn-finish-quiz:hover{background:#DC2626}
.question-text{font-size:1.02rem;font-weight:700;line-height:1.6;color:#1A2E3B;margin-bottom:20px}
.options-list{display:flex;flex-direction:column;gap:10px}
.option-item{display:flex;align-items:center;gap:12px;padding:12px 16px;border:1.5px solid #DDE8ED;border-radius:12px;background:#FBFDFE;cursor:pointer;transition:all .2s}
.option-item:hover{border-color:#3BBFBF;background:#E8F8F8}
.option-item input[type="radio"]{width:18px;height:18px;accent-color:#3BBFBF;cursor:pointer}
.option-label{font-size:.9rem;font-weight:600;color:#1A2E3B;flex:1;cursor:pointer}
.option-code{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:8px;background:#fff;border:1px solid #DDE8ED;font-weight:800;font-size:.78rem;color:#5A7384}
</style>

<form id="quis" name="quis" action="proses_assesmentbaruabcd.php" method="post">
    <input type="hidden" name="kmdl" value="<?php echo e($kdm); ?>">
    <input type="hidden" name="nim" value="<?php echo e($unim); ?>">
    <input type="hidden" name="vlastnom" value="<?php echo $page; ?>">
    <input type="hidden" name="kounter" value="<?php echo $periksa3; ?>">

    <?php 
    if ($rsd1 && mysqli_num_rows($rsd1) > 0) {
        while ($row = mysqli_fetch_array($rsd1)) {
            $id = $row['question_id']; 
            $question = $row['question']; 
            $alt_6 = $row['njawab'] ?? ''; 
        ?>
        <div class="question-card">
            <?php if (!empty($pesan1)): ?>
                <div style="color:#EF4444;font-weight:700;margin-bottom:12px;text-align:center;"><?php echo e($pesan1); ?></div>
            <?php endif; ?>

            <div class="question-header">
                <span class="question-no"><i class="fas fa-list-ol"></i> Soal No. <?php echo $page; ?></span>
                <button type="submit" id="gobutton" class="btn-finish-quiz" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan dan mengirim seluruh jawaban tes?')">
                    <i class="fas fa-paper-plane"></i> Selesai & Kirim Ujian
                </button>
            </div>

            <input type="hidden" name="idq" value="<?php echo $id; ?>">
            <div class="question-text">
                <?php echo $question; ?>
            </div>

            <div class="options-list">
                <?php
                $options_found = array();
                $sql5 = "SELECT * FROM tbloption WHERE kd_modul='".mysqli_real_escape_string($conn, $kdm)."' AND question_id='$id'";
                if($vacakpg == '1') { $sql5 .= " ORDER BY RAND()"; }
                $rsd5 = mysqli_query($conn, $sql5);

                if ($rsd5 && mysqli_num_rows($rsd5) > 0) {
                    while ($row5 = mysqli_fetch_array($rsd5)) {
                        $options_found[] = array('noption' => $row5['noption'], 'toption' => $row5['toption']);
                    }
                } else {
                    // Fallback ke `pertanyaan` table jika `tbloption` kosong
                    $qp = mysqli_query($conn, "SELECT oa, alt_1, alt_2, alt_3, alt_4 FROM pertanyaan WHERE question_id='$id' LIMIT 1");
                    if ($qp && $rowp = mysqli_fetch_array($qp)) {
                        $raw_opts = array(
                            'A' => $rowp['oa'],
                            'B' => $rowp['alt_1'],
                            'C' => $rowp['alt_2'],
                            'D' => $rowp['alt_3'],
                            'E' => $rowp['alt_4']
                        );
                        foreach ($raw_opts as $opt_code => $opt_val) {
                            if (trim((string)$opt_val) !== '') {
                                $options_found[] = array('noption' => $opt_code, 'toption' => $opt_val);
                            }
                        }
                    }
                }

                foreach ($options_found as $opt):
                    $r = $opt['noption'];
                    $s = $opt['toption'];
                    $is_checked = ($r === $alt_6) ? 'checked' : '';
                ?>
                    <label class="option-item">
                        <span class="option-code"><?php echo e($r); ?></span>
                        <input name="q<?php echo $id; ?>" type="radio" value="<?php echo e($r); ?>" <?php echo $is_checked; ?> onclick="myclick()">
                        <span class="option-label"><?php echo e($s); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php }
    } else { ?>
        <div class="question-card" style="text-align:center;">
            <p style="color:#5A7384;font-weight:700;">Pertanyaan untuk modul ini belum tersedia di sistem.</p>
        </div>
    <?php } ?>
</form>
