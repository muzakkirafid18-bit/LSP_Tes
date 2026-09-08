<?php
session_start();
include "../lsp_koneksi.php";

$page_title  = "Monitoring Asesi";
$page_sub    = "Pemantauan status dan progress peserta";
$active_menu = "monitorasesi";
$user_level  = "lsp";

include "template_header.php";

$op = $_GET['op'] ?? '';

/* ================================================================
   HELPER: badge status
================================================================ */
function statusBadge($kondisi, $labelOk, $labelNo) {
    if ($kondisi) {
        return '<span class="badge badge-green"><i class="fas fa-circle-check"></i> '.$labelOk.'</span>';
    }
    return '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> '.$labelNo.'</span>';
}

/* ================================================================
   OP: VALIDASI — tampilkan data APL1 asesi (read-only)
================================================================ */
if ($op == "validasi"):
	
    $idasesi    = $_GET['idasesi'];
    $namaadsesi = $_GET['nama'];

    $sqlpemetaan   = "SELECT * FROM pemetaan WHERE idpeserta='$idasesi'";
    $execpemetaan  = mysqli_query($conn, $sqlpemetaan);
    $listpemetaan  = mysqli_fetch_array($execpemetaan, MYSQLI_ASSOC);
    $idpasse       = $listpemetaan['idasesor'] ?? '';
    $nmass         = $listpemetaan['namaasesor'] ?? '-';

    $lttd     = "SELECT * FROM lsp_usertbl WHERE id='$idpasse'";
    $hasilxttd = mysqli_fetch_array(mysqli_query($conn, $lttd), MYSQLI_ASSOC);
    $ttd      = $hasilxttd['linkttd'] ?? '';

    $cekdata0 = "SELECT * FROM rekomendasi WHERE namarekom='apl1' AND idasesi='$idasesi'";
    $adax     = mysqli_fetch_array(mysqli_query($conn, $cekdata0), MYSQLI_ASSOC);
    $lrek     = $adax['rekom'] ?? '';
    $cat      = $adax['catatan'] ?? '';
    $klrek    = ($lrek == 'L') ? 'checked' : '';
    $klrek0   = ($lrek == 'T') ? 'checked' : '';
    $idasesor = $adax['idasesor'] ?? '';

    $ssql  = "SELECT * FROM permohonan WHERE id='$idasesi'";
    $exec  = mysqli_query($conn, $ssql);
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-clipboard-check" style="color:var(--teal);margin-right:8px"></i>Permohonan APL 1</h3>
            <p>Peserta: <strong><?= htmlspecialchars($namaadsesi) ?></strong> &nbsp;·&nbsp; Asesor: <?= htmlspecialchars($nmass) ?></p>
        </div>
        <div style="display:flex;gap:8px">
            <button onclick="window.print()" class="btn btn-print btn-sm no-print"><i class="fas fa-print"></i> Cetak</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm no-print"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <?php if (mysqli_num_rows($exec) > 0):
        $list = mysqli_fetch_array($exec, MYSQLI_ASSOC);
        $tasesmen = $list['tujuanasesmen'] ?? '';
        $sertifikasi = $list['sertifikasi'] ?? '';
        $kasesment = $list['kontekasesmen'] ?? '';
        $karakter = $list['karakteristik'] ?? '';
        $acuan = $list['acuanp'] ?? '';
        $tuk = $list['tuk'] ?? '';

        $pecahta  = explode(",", $tasesmen);
        $pecahser = explode(",", $sertifikasi);
        $pecahkas = explode(",", $kasesment);
        $pecahkar = explode(",", $karakter);
        $pecahac  = explode(",", $acuan);

        function ck($arr, $idx) { return !empty($arr[$idx] ?? '') ? 'checked' : ''; }
    ?>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">
        <div>
            <p class="form-section-title">Tujuan Asesmen</p>
            <div style="display:flex;flex-direction:column;gap:6px;font-size:.85rem">
                <label><input type="checkbox" <?= ck($pecahta,0) ?> disabled> RPL</label>
                <label><input type="checkbox" <?= ck($pecahta,1) ?> disabled> Pencapaian Proses Pembelajaran</label>
                <label><input type="checkbox" <?= ck($pecahta,2) ?> disabled> RCC</label>
                <label><input type="checkbox" <?= ck($pecahta,3) ?> disabled> Sertifikasi</label>
                <label><input type="checkbox" <?= ck($pecahta,4) ?> disabled> Lainnya: <?= htmlspecialchars($list['lainnya'] ?? '') ?></label>
            </div>
        </div>
        <div>
            <p class="form-section-title">Skema Sertifikasi</p>
            <div style="display:flex;flex-direction:column;gap:6px;font-size:.85rem">
                <label><input type="checkbox" <?= ck($pecahser,0) ?> disabled> Unit</label>
                <label><input type="checkbox" <?= ck($pecahser,1) ?> disabled> Klaster</label>
                <label><input type="checkbox" <?= ck($pecahser,2) ?> disabled> Okupasi</label>
                <label><input type="checkbox" <?= ck($pecahser,3) ?> disabled> KKNI</label>
            </div>
        </div>
        <div>
            <p class="form-section-title">Konteks Asesmen</p>
            <div style="display:flex;flex-direction:column;gap:6px;font-size:.85rem">
                <label><input type="checkbox" <?= ck($pecahkas,0) ?> disabled> TUK Simulasi</label>
                <label><input type="checkbox" <?= ck($pecahkas,1) ?> disabled> Tempat Kerja</label>
                <p style="margin-top:6px;font-weight:600;color:var(--text-sub)">Karakter:</p>
                <label><input type="checkbox" <?= ck($pecahkar,0) ?> disabled> Produk</label>
                <label><input type="checkbox" <?= ck($pecahkar,1) ?> disabled> Sistem</label>
                <label><input type="checkbox" <?= ck($pecahkar,2) ?> disabled> Tempat Kerja</label>
            </div>
        </div>
        <div>
            <p class="form-section-title">Acuan Pembanding</p>
            <div style="display:flex;flex-direction:column;gap:6px;font-size:.85rem">
                <label><input type="checkbox" <?= ck($pecahac,0) ?> disabled> Standar Kompetensi</label>
                <label><input type="checkbox" <?= ck($pecahac,1) ?> disabled> Standar Produk</label>
                <label><input type="checkbox" <?= ck($pecahac,2) ?> disabled> Standar Sistem</label>
                <label><input type="checkbox" <?= ck($pecahac,3) ?> disabled> Regulasi Sistem</label>
                <label><input type="checkbox" <?= ck($pecahac,4) ?> disabled> SOP</label>
            </div>
        </div>
    </div>

    <!-- Bukti Portofolio -->
    <?php
    $ssql1 = "SELECT * FROM upload WHERE idasesi='$idasesi'";
    $exec1 = mysqli_query($conn, $ssql1);
    if (mysqli_num_rows($exec1) > 0):
    ?>
    <p class="form-section-title">Bukti Kompetensi &amp; Portofolio</p>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th>No</th><th>Elemen Kompetensi</th><th>Bukti</th>
                <th>Dokumen</th><th>Kesesuaian (V-A-T-M)</th><th>Lanjut</th><th>Status</th>
            </tr></thead>
            <tbody>
            <?php $i=0; while ($list1 = mysqli_fetch_array($exec1, MYSQLI_ASSOC)):
                $idskema1 = $list1['idskema'];
                $idunit   = $list1['idunit'];
                $idelemen = $list1['idelemen'];
                $bukti    = $list1['bukti'];
                $path     = $list1['Path'];
                $dbukti   = $list1['dbukti'];
                $lt       = $list1['lt'];
                $status   = $list1['statusvalid'];
                $valabel  = ($status == 'Y') ? '<span class="badge badge-green">Sudah Validasi</span>' : '<span class="badge badge-red">Belum Validasi</span>';

                $pecahdb = explode(",", $dbukti);
                $pecahbu = explode(",", $bukti);
                function chk($arr, $idx) { return !empty($arr[$idx] ?? '') ? 'checked' : ''; }

                $list2e = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM elemen WHERE idskema='$idskema1' AND idunit='$idunit' AND idelemen='$idelemen'"), MYSQLI_ASSOC);
                $namae = $list2e['namaelemen'] ?? '-';

                $lLabel = ($lt == 'L') ? '<span class="badge badge-green">Lanjut</span>' : '<span class="badge badge-red">Tidak</span>';
            ?>
            <tr>
                <td class="row-num"><?= $i+1 ?></td>
                <td style="font-weight:500;font-size:.82rem"><?= htmlspecialchars($namae) ?></td>
                <td style="font-size:.78rem">
                    <label><input type="checkbox" <?= chk($pecahbu,0) ?> disabled> SK</label>
                    <label><input type="checkbox" <?= chk($pecahbu,1) ?> disabled> SR</label>
                    <label><input type="checkbox" <?= chk($pecahbu,2) ?> disabled> CP</label>
                    <label><input type="checkbox" <?= chk($pecahbu,3) ?> disabled> JD</label>
                    <label><input type="checkbox" <?= chk($pecahbu,4) ?> disabled> WS</label>
                    <label><input type="checkbox" <?= chk($pecahbu,5) ?> disabled> De</label>
                    <label><input type="checkbox" <?= chk($pecahbu,6) ?> disabled> Pe</label>
                    <label><input type="checkbox" <?= chk($pecahbu,7) ?> disabled> L</label>
                </td>
                <td><a href="../siswa/gambarimages/<?= htmlspecialchars($path) ?>" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Lihat</a></td>
                <td style="font-size:.78rem">
                    <label><input type="checkbox" <?= chk($pecahdb,0) ?> disabled> V</label>
                    <label><input type="checkbox" <?= chk($pecahdb,1) ?> disabled> A</label>
                    <label><input type="checkbox" <?= chk($pecahdb,2) ?> disabled> T</label>
                    <label><input type="checkbox" <?= chk($pecahdb,3) ?> disabled> M</label>
                </td>
                <td><?= $lLabel ?></td>
                <td><?= $valabel ?></td>
            </tr>
            <?php $i++; endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="alert-box alert-info" style="margin-top:16px;font-size:.78rem">
        <i class="fas fa-circle-info"></i>
        <div>SK=Sertifikasi/Kualifikasi &nbsp;·&nbsp; SR=Surat Referensi &nbsp;·&nbsp; CP=Contoh Pekerjaan &nbsp;·&nbsp;
        JD=Job Description &nbsp;·&nbsp; WS=Wawancara &nbsp;·&nbsp; De=Demonstrasi &nbsp;·&nbsp; Pe=Pengalaman &nbsp;·&nbsp; L=Lainnya</div>
    </div>
    <?php else: ?>
    <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i> Asesi belum melakukan pengisian portofolio.</div>
    <?php endif; ?>
    <?php else: ?>
    <div class="alert-box alert-error"><i class="fas fa-circle-xmark"></i> <strong>Tidak ada data APL 1</strong> untuk asesi ini.</div>
    <?php endif; ?>
</div>

<?php
/* ================================================================
   OP: VALIDASIAPL2 — tampilkan data APL2 asesi (read-only)
================================================================ */
elseif ($op == "validasiapl2"):
    $idadsesi  = $_GET['idasesi'];
    $namaadsesi = $_GET['nama'];
    $emailuser = trim($_GET['email']);

    $sdata = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lsp_usertbl WHERE id='$idadsesi'"), MYSQLI_ASSOC);
    $namap = $sdata['nama'] ?? '-';
    $emailp = $sdata['email'] ?? '';

    $execceapl2 = mysqli_query($conn, "SELECT * FROM apl2 WHERE email='$emailuser'");
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-file-lines" style="color:var(--teal);margin-right:8px"></i>APL 2 — Asesmen Mandiri</h3>
            <p>Peserta: <strong><?= htmlspecialchars($namaadsesi) ?></strong></p>
        </div>
        <div style="display:flex;gap:8px">
            <button onclick="window.print()" class="btn btn-print btn-sm no-print"><i class="fas fa-print"></i> Cetak</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm no-print"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <?php if (mysqli_num_rows($execceapl2) > 0):
        // Ambil semua unit asesi
        $sqlunit = "SELECT DISTINCT idunit FROM apl2 WHERE email='$emailuser'";
        $execunit = mysqli_query($conn, $sqlunit);
    ?>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th>No KUK</th><th>Sub Kompetensi</th>
                <th style="width:60px;text-align:center">K</th>
                <th style="width:60px;text-align:center">BK</th>
                <th style="width:180px">Bukti (V-A-T-M)</th>
                <th style="width:120px;text-align:center">Validasi</th>
            </tr></thead>
            <tbody>
            <?php
            $no = 1;
            $execapl2all = mysqli_query($conn, "SELECT apl2.*,subelemen.pertanyaan FROM apl2 LEFT JOIN subelemen ON apl2.idsubelemen=subelemen.idsubelemen WHERE apl2.email='$emailuser' ORDER BY apl2.idunit,apl2.idelemen");
            while ($dapl2 = mysqli_fetch_array($execapl2all, MYSQLI_ASSOC)):
                $tk     = $dapl2['tk'] ?? '';
                $sbukti = $dapl2['sbukti'] ?? '';
                $pecahbs = explode(",", $sbukti);
                $svali  = $dapl2['svalidasi'] ?? '';
                $ketk  = ($tk == 'K')  ? '<i class="fas fa-circle-check" style="color:#22C55E"></i>' : '';
                $ketbk = ($tk != 'K')  ? '<i class="fas fa-circle-check" style="color:#EF4444"></i>' : '';
                $svBadge = ($svali == 'Y') ? '<span class="badge badge-green">Sudah</span>' : '<span class="badge badge-red">Belum</span>';
            ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td style="font-size:.82rem"><?= htmlspecialchars($dapl2['pertanyaan'] ?? '') ?></td>
                <td style="text-align:center"><?= $ketk ?></td>
                <td style="text-align:center"><?= $ketbk ?></td>
                <td style="font-size:.78rem">
                    <?php
                    $labels = ['V','A','T','M'];
                    foreach ($labels as $ki => $lbl):
                        $chk = !empty($pecahbs[$ki] ?? '') ? 'checked' : '';
                    ?>
                    <label><input type="checkbox" <?= $chk ?> disabled> <?= $lbl ?></label>
                    <?php endforeach; ?>
                </td>
                <td style="text-align:center"><?= $svBadge ?></td>
            </tr>
            <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i> Asesi belum mengisi APL2.</div>
    <?php endif; ?>
</div>

<?php
/* ================================================================
   OP: OBSERVASI
================================================================ */
elseif ($op == "observasi"):
    $idasesi   = $_GET['idasesi'];
    $namaadsesi = $_GET['nama'];
    $emailuser = trim($_GET['email']);

    $sqlobs = "SELECT rekappraktek.*,unit.namaunit,unit.kodeunit FROM rekappraktek LEFT JOIN unit ON rekappraktek.idunit=unit.idunit WHERE rekappraktek.idadsesi='$idasesi'";
    $execobs = mysqli_query($conn, $sqlobs);
    $totobs  = mysqli_num_rows($execobs);
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-eye" style="color:var(--teal);margin-right:8px"></i>FR.IA.01 Observasi</h3>
            <p>Peserta: <strong><?= htmlspecialchars($namaadsesi) ?></strong> &nbsp;·&nbsp; <?= $totobs ?> rekap</p>
        </div>
        <div style="display:flex;gap:8px">
            <button onclick="window.print()" class="btn btn-print btn-sm no-print"><i class="fas fa-print"></i> Cetak</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm no-print"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <?php if ($totobs > 0): ?>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr><th>No</th><th>Unit</th><th>Kode Unit</th><th>Hasil</th></tr></thead>
            <tbody>
            <?php $no=1; while ($dobs = mysqli_fetch_array($execobs, MYSQLI_ASSOC)): ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td style="font-weight:500"><?= htmlspecialchars($dobs['namaunit'] ?? '-') ?></td>
                <td><span class="badge badge-teal"><?= htmlspecialchars($dobs['kodeunit'] ?? '-') ?></span></td>
                <td><?= ($dobs['hasil']??'') == 'K'
                    ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Kompeten</span>'
                    : '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Belum Kompeten</span>' ?>
                </td>
            </tr>
            <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i> Asesi belum pernah diobservasi.</div>
    <?php endif; ?>
</div>

<?php
/* ================================================================
   OP: REKAPHASILTES
================================================================ */
elseif ($op == "rekaphasiltes"):
    $idasesi   = $_GET['idasesi'];
    $namaadsesi = $_GET['nama'];
    $emailuser = trim($_GET['email']);

    $sqlgrade = "SELECT * FROM grade WHERE nim='$emailuser' ORDER BY tanggal DESC";
    $execgrade = mysqli_query($conn, $sqlgrade);
    $totgrade  = mysqli_num_rows($execgrade);
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-chart-bar" style="color:var(--teal);margin-right:8px"></i>Rekap Hasil Tes Tulis</h3>
            <p>Peserta: <strong><?= htmlspecialchars($namaadsesi) ?></strong> &nbsp;·&nbsp; <?= $totgrade ?> rekap</p>
        </div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm no-print"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <?php if ($totgrade > 0): ?>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th>No</th><th>Kode Modul</th><th>Nama Modul</th>
                <th style="text-align:center">Benar</th><th style="text-align:center">Salah</th>
                <th style="text-align:center">Nilai</th><th style="text-align:center">KKM</th>
                <th>Tanggal</th><th style="text-align:center">Lulus</th>
            </tr></thead>
            <tbody>
            <?php $no=1; while ($dg = mysqli_fetch_array($execgrade, MYSQLI_ASSOC)):
                $lulus = ($dg['grade'] >= $dg['kkm']);
            ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td><span class="badge badge-teal"><?= htmlspecialchars($dg['kd_modul']) ?></span></td>
                <td style="font-weight:500"><?= htmlspecialchars($dg['modul']) ?></td>
                <td style="text-align:center;color:#22C55E;font-weight:600"><?= $dg['benar'] ?></td>
                <td style="text-align:center;color:#EF4444;font-weight:600"><?= $dg['salah'] ?></td>
                <td style="text-align:center;font-family:'DM Mono',monospace;font-weight:700"><?= $dg['grade'] ?></td>
                <td style="text-align:center;font-family:'DM Mono',monospace"><?= $dg['kkm'] ?></td>
                <td style="color:var(--text-sub);font-size:.8rem"><?= $dg['tanggal'] ?></td>
                <td style="text-align:center"><?= $lulus
                    ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Lulus</span>'
                    : '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Tidak</span>' ?>
                </td>
            </tr>
            <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i> Asesi belum memiliki rekap nilai tes.</div>
    <?php endif; ?>
</div>

<?php
/* ================================================================
   OP: CEK STATUS — ringkasan lengkap 1 asesi
================================================================ */
elseif ($op == "cekstatus"):
    $email = trim($_GET['email']);

    $rowapl1   = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM apl1 WHERE email='$email'"), MYSQLI_ASSOC);
    $rowuser   = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM lsp_usertbl WHERE email='$email'"), MYSQLI_ASSOC);
    $iduser    = $rowuser['id'] ?? '';

    $rowjadwal = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pemetaan WHERE idpeserta='$iduser'"), MYSQLI_ASSOC);
    $asesor    = $rowjadwal['namaasesor'] ?? '';
    $tglpem    = $rowjadwal['tanggal'] ?? '';
    $dskema    = $rowjadwal['idskema'] ?? '';

    $cntpermohonan = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM permohonan WHERE email='$email' GROUP BY email,tanggal"));
    $cntapl2       = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM apl2 WHERE email='$email' GROUP BY email,waktu"));
    $cntporto      = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM upload WHERE email='$email' AND waktu='$tglpem'"));
    $cntobs        = mysqli_num_rows(mysqli_query($conn, "SELECT idadsesi FROM rekappraktek WHERE idadsesi='$iduser'"));
    $cntmak5       = mysqli_num_rows(mysqli_query($conn, "SELECT idasesi FROM mak5 WHERE idasesi='$email' GROUP BY idasesi"));

    $rkapl1 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE idskema='$dskema' AND idasesi='$iduser' AND namarekom='apl1lsp'"), MYSQLI_ASSOC);
    $rkapl2 = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE idskema='$dskema' AND idasesi='$iduser' AND namarekom='apl2'"), MYSQLI_ASSOC);
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-list-check" style="color:var(--teal);margin-right:8px"></i>Cek Status Asesi</h3>
            <p>Email: <strong><?= htmlspecialchars($email) ?></strong></p>
        </div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <!-- Kolom Kiri -->
        <div style="display:flex;flex-direction:column;gap:12px">
            <div style="background:var(--off);border-radius:10px;padding:14px 16px">
                <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Asesor</p>
                <?= statusBadge(!empty($asesor), 'Dijadwalkan: '.$asesor, 'Belum dijadwalkan') ?>
            </div>
            <div style="background:var(--off);border-radius:10px;padding:14px 16px">
                <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Biodata (APL1)</p>
                <?= statusBadge(!empty($rowapl1), 'Terdaftar: '.($rowapl1['namasiswa']??''), 'Belum terdaftar') ?>
            </div>
            <div style="background:var(--off);border-radius:10px;padding:14px 16px">
                <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Permohonan</p>
                <?= statusBadge($cntpermohonan > 0, 'Sudah mengisi', 'Belum mengisi') ?>
            </div>
            <div style="background:var(--off);border-radius:10px;padding:14px 16px">
                <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">APL1 dengan Bukti</p>
                <?= statusBadge(strlen($rowapl1['buktiapl1'] ?? '') > 0, 'Sudah upload bukti', 'Belum upload') ?>
            </div>
        </div>
        <!-- Kolom Kanan -->
        <div style="display:flex;flex-direction:column;gap:12px">
            <div style="background:var(--off);border-radius:10px;padding:14px 16px">
                <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">APL2</p>
                <?= statusBadge($cntapl2 > 0, 'Sudah mengisi', 'Belum mengisi') ?>
            </div>
            <div style="background:var(--off);border-radius:10px;padding:14px 16px">
                <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Portofolio</p>
                <?= statusBadge($cntporto > 0, 'Sudah upload', 'Belum upload') ?>
            </div>
            <div style="background:var(--off);border-radius:10px;padding:14px 16px">
                <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Observasi</p>
                <?= statusBadge($cntobs > 0, 'Pernah diobservasi', 'Belum pernah diobservasi') ?>
            </div>
            <div style="background:var(--off);border-radius:10px;padding:14px 16px">
                <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Umpan Balik (MAK5)</p>
                <?= statusBadge($cntmak5 > 0, 'Sudah mengisi', 'Belum mengisi') ?>
            </div>
        </div>
    </div>

    <!-- Rekomendasi -->
    <div class="divider-line"></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
        <div style="background:var(--off);border-radius:10px;padding:14px 16px">
            <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Rekomendasi APL1</p>
            <?php if (!empty($rkapl1)): ?>
                <?= $rkapl1['rekom'] == 'L'
                    ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Diterima</span>'
                    : '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Ditolak</span>' ?>
                <p style="margin-top:8px;font-size:.82rem;color:var(--text-sub)">Catatan: <?= htmlspecialchars($rkapl1['catatan'] ?? '') ?></p>
            <?php else: ?>
                <span class="badge badge-gray">Belum ada rekomendasi</span>
            <?php endif; ?>
        </div>
        <div style="background:var(--off);border-radius:10px;padding:14px 16px">
            <p style="font-size:.72rem;font-weight:700;color:var(--text-sub);text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px">Rekomendasi APL2</p>
            <?php if (!empty($rkapl2)): ?>
                <?= $rkapl2['rekom'] == 'L'
                    ? '<span class="badge badge-green"><i class="fas fa-circle-check"></i> Diterima</span>'
                    : '<span class="badge badge-red"><i class="fas fa-circle-xmark"></i> Ditolak</span>' ?>
                <p style="margin-top:8px;font-size:.82rem;color:var(--text-sub)">Catatan: <?= htmlspecialchars($rkapl2['catatan'] ?? '') ?></p>
            <?php else: ?>
                <span class="badge badge-gray">Belum ada rekomendasi</span>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
/* ================================================================
   OP: PUMUM — filter pemantauan per tanggal
================================================================ */
elseif ($op == "pumum"):
    $listmodul = mysqli_query($conn, "SELECT tanggal FROM settanggal WHERE status='A'");
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-calendar-check" style="color:var(--teal);margin-right:8px"></i>Pemantauan Per Tanggal</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>?op=daftarpes">
        <div class="form-grid">
            <div class="form-label">Filter Tanggal</div>
            <select name="tgl" class="form-input">
                <?php while ($list = mysqli_fetch_array($listmodul, MYSQLI_ASSOC))
                    echo "<option value='{$list['tanggal']}'>{$list['tanggal']}</option>"; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" name="dsubmit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Tampilkan</button>
        </div>
    </form>
</div>

<?php
/* ================================================================
   OP: DAFTARPES — tabel status per tanggal
================================================================ */
elseif ($op == "daftarpes"):
    $tanggal = $_POST['tgl'] ?? '';
    $skem    = $_POST['sk'] ?? '';

    if (empty($tanggal)):
        echo '<div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i> Tanggal masih kosong!</div>';
    else:
        $sql   = "SELECT * FROM lsp_usertbl WHERE kode='$tanggal' ORDER BY nama";
        $hasil = mysqli_query($conn, $sql);
        $total = mysqli_num_rows($hasil);
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-calendar-days" style="color:var(--teal);margin-right:8px"></i>Status Asesi — <?= htmlspecialchars($tanggal) ?></h3>
            <p><?= $total ?> asesi</p>
        </div>
        <a href="?op=pumum" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th>No</th><th>ID</th><th>Nama / Asesor</th>
                <th>Permohonan</th><th>APL1</th>
                <th>Portofolio</th><th>APL2</th><th>Umpan Balik</th>
            </tr></thead>
            <tbody>
            <?php $i=0; while ($data = mysqli_fetch_array($hasil, MYSQLI_ASSOC)):
                $iduser = $data['id'];
                $nama   = $data['nama'];
                $email  = trim($data['email']);

                $nmdrpemetaan  = mysqli_fetch_array(mysqli_query($conn, "SELECT namaasesor FROM pemetaan WHERE idpeserta='$iduser'"), MYSQLI_ASSOC);
                $nmasesor      = $nmdrpemetaan['namaasesor'] ?? '-';

                $p1 = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM permohonan WHERE email='$email' AND tanggal='$tanggal' GROUP BY email,tanggal")) > 0;
                $p3 = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM apl1 WHERE email='$email' GROUP BY email")) > 0;

                $quploada = mysqli_query($conn, "SELECT email,waktu FROM upload WHERE email='$email' AND waktu='$tanggal' AND idskema='$skem' GROUP BY email,waktu");
                if (mysqli_num_rows($quploada) > 0) {
                    $quploadb = mysqli_fetch_array($quploada, MYSQLI_ASSOC);
                    $cvl = mysqli_num_rows(mysqli_query($conn, "SELECT idasesi FROM upload WHERE email='{$quploadb['email']}' AND waktu='{$quploadb['waktu']}' AND statusvalid='Y' GROUP BY idasesi,idskema,waktu,statusvalid")) > 0;
                    $ketvl = $cvl;
                } else {
                    $ketvl = false;
                }

                $capl2 = mysqli_query($conn, "SELECT idadsesi FROM apl2 WHERE waktu='$tanggal' AND email='$email' AND svalidasi='T' GROUP BY idadsesi,idskema,waktu,svalidasi");
                if (mysqli_num_rows($capl2) > 0) {
                    $ketvl2 = false;
                } else {
                    $ketvl2 = mysqli_num_rows(mysqli_query($conn, "SELECT idadsesi FROM apl2 WHERE waktu='$tanggal' AND email='$email' AND svalidasi='Y' GROUP BY idadsesi,idskema,waktu")) > 0;
                }

                $umpanb = mysqli_num_rows(mysqli_query($conn, "SELECT idasesi FROM mak5 WHERE idasesi='$email' AND tglreg='$tanggal'")) > 0;
            ?>
            <tr>
                <td class="row-num"><?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?></td>
                <td><span class="badge badge-navy"><?= $iduser ?></span></td>
                <td>
                    <div style="font-weight:600"><?= htmlspecialchars($nama) ?></div>
                    <div style="font-size:.75rem;color:var(--text-sub)">Asesor: <?= htmlspecialchars($nmasesor) ?></div>
                </td>
                <td><?= statusBadge($p1, 'Terdaftar', 'Belum') ?></td>
                <td><?= statusBadge($p3, 'Terdaftar', 'Belum') ?></td>
                <td><?= statusBadge($ketvl, 'Divalidasi', 'Belum') ?></td>
                <td><?= statusBadge($ketvl2, 'Divalidasi', 'Belum') ?></td>
                <td><?= statusBadge($umpanb, 'Sudah', 'Belum') ?></td>
            </tr>
            <?php $i++; endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php
/* ================================================================
   DEFAULT — Daftar semua asesi + search + pagination
================================================================ */
else:
    $dataPerPage = 25;
    $noPage  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset  = ($noPage - 1) * $dataPerPage;
    $txtcari    = $_POST['txtcari'] ?? '';
    $txtkriteria = $_POST['txtkriteria'] ?? 'nama';

    if (isset($_POST['sqlaction']) && $_POST['sqlaction'] == "SEARCH" && !empty($txtcari)) {
        $sql = "SELECT * FROM lsp_usertbl WHERE level='peserta' AND $txtkriteria LIKE '%$txtcari%' ORDER BY id DESC LIMIT $offset, $dataPerPage";
        $sqlcount = "SELECT COUNT(*) AS jumData FROM lsp_usertbl WHERE level='peserta' AND $txtkriteria LIKE '%$txtcari%'";
    } else {
        $sql = "SELECT * FROM lsp_usertbl WHERE level='peserta' ORDER BY id DESC LIMIT $offset, $dataPerPage";
        $sqlcount = "SELECT COUNT(*) AS jumData FROM lsp_usertbl WHERE level='peserta'";
    }

    $result  = mysqli_query($conn, $sql);
    $jumData = mysqli_fetch_array(mysqli_query($conn, $sqlcount), MYSQLI_ASSOC)['jumData'];
    $jumPage = ceil($jumData / $dataPerPage);
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-desktop" style="color:var(--teal);margin-right:8px"></i>Monitoring Asesi</h3>
            <p><?= $jumData ?> total peserta</p>
        </div>
        <div style="display:flex;gap:8px">
            <a href="exportbuatlog.php" class="btn btn-secondary btn-sm"><i class="fas fa-file-export"></i> Export Laporan</a>
            <a href="?op=pumum" class="btn btn-primary btn-sm"><i class="fas fa-calendar-days"></i> Pemantauan Per Tanggal</a>
        </div>
    </div>

    <!-- SEARCH -->
    <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
        <input type="hidden" name="sqlaction" value="SEARCH">
        <div class="search-bar">
            <select name="txtkriteria" class="search-select">
                <option value="nama">Nama</option>
                <option value="email">Email</option>
            </select>
            <div class="search-input-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="txtcari" placeholder="Cari asesi..." value="<?= htmlspecialchars($txtcari) ?>">
            </div>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-rotate"></i> Reset</a>
        </div>
    </form>

    <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th style="width:80px">ID</th>
                    <th>Nama Asesi</th>
                    <th style="width:320px;text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $number = ($noPage - 1) * $dataPerPage + 1;
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)):
            ?>
            <tr>
                <td class="row-num"><?= str_pad($number,2,'0',STR_PAD_LEFT) ?></td>
                <td><span class="badge badge-navy"><?= $row['id'] ?></span></td>
                <td>
                    <div style="font-weight:600"><?= htmlspecialchars($row['nama']) ?></div>
                    <div style="font-size:.75rem;color:var(--text-sub)"><?= htmlspecialchars($row['email']) ?></div>
                </td>
                <td>
                    <div class="action-group" style="justify-content:center;flex-wrap:wrap">
                        <a href="?op=validasi&idasesi=<?= $row['id'] ?>&nama=<?= urlencode($row['nama']) ?>"
                           class="btn btn-info btn-sm"><i class="fas fa-book"></i> Permohonan</a>
                        <a href="?op=validasiapl2&idasesi=<?= $row['id'] ?>&nama=<?= urlencode($row['nama']) ?>&email=<?= urlencode($row['email']) ?>"
                           class="btn btn-purple btn-sm"><i class="fas fa-file"></i> APL2</a>
                        <a href="?op=observasi&idasesi=<?= $row['id'] ?>&nama=<?= urlencode($row['nama']) ?>&email=<?= urlencode($row['email']) ?>"
                           class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> Observasi</a>
                        <a href="?op=rekaphasiltes&idasesi=<?= $row['id'] ?>&nama=<?= urlencode($row['nama']) ?>&email=<?= urlencode($row['email']) ?>"
                           class="btn btn-secondary btn-sm"><i class="fas fa-chart-bar"></i> Hasil Tes</a>
                        <a href="../siswa/biodatasiswapdf.php?email=<?= urlencode($row['email']) ?>" target="_blank"
                           class="btn btn-secondary btn-sm"><i class="fas fa-user"></i> Biodata</a>
                        <a href="?op=cekstatus&email=<?= urlencode($row['email']) ?>&idasesi=<?= $row['id'] ?>"
                           class="btn btn-warning btn-sm"><i class="fas fa-list-check"></i> Cek Status</a>
                    </div>
                </td>
            </tr>
            <?php $number++; endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php if ($noPage > 1): ?>
        <a href="?page=<?= $noPage-1 ?>" class="page-btn"><i class="fas fa-chevron-left"></i> Kembali</a>
        <?php endif; ?>
        <?php
        $showPage = 0;
        for ($page = 1; $page <= $jumPage; $page++):
            if (($page >= $noPage-3 && $page <= $noPage+3) || $page == 1 || $page == $jumPage):
                if ($showPage == 1 && $page != 2) echo '<span style="color:var(--text-muted);padding:0 4px">...</span>';
                if ($showPage != ($jumPage-1) && $page == $jumPage) echo '<span style="color:var(--text-muted);padding:0 4px">...</span>';
        ?>
        <a href="?page=<?= $page ?>" class="page-btn <?= $page==$noPage?'active':'' ?>"><?= $page ?></a>
        <?php $showPage = $page; endif; endfor; ?>
        <?php if ($noPage < $jumPage): ?>
        <a href="?page=<?= $noPage+1 ?>" class="page-btn">Lanjutkan <i class="fas fa-chevron-right"></i></a>
        <?php endif; ?>
        <span style="font-size:.78rem;color:var(--text-muted);margin-left:8px"><?= $jumData ?> total peserta</span>
    </div>
</div>
<?php endif; ?>

<?php include "template_footer.php"; ?>