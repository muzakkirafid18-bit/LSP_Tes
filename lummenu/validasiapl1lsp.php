<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Validasi APL.01";
$page_sub    = "Validasi Permohonan Sertifikasi";
$active_menu = "validasiapl1lsp";
$user_level  = "lsp";

include "template_header.php"; 

$op = $_GET['op'] ?? '';
?>

<div class="content">

<?php
/* ===========================
   OPERASI: LIST PESERTA
=========================== */
if ($op == "listpesertalsp"):
    $tgl = $_POST['tgl'] ?? '';
    $cekvlapl1 = "SELECT apl1.namasiswa, apl1.email, apl1.email2, apl1.validasiapl1, apl1.buktiapl1, apl1.idasesi, skemasiswa.tglrekskema, skemasiswa.idskema 
                  FROM apl1 
                  INNER JOIN skemasiswa ON apl1.email = skemasiswa.emailsiswa 
                  WHERE skemasiswa.tglrekskema='$tgl'"; 
    $cekvlapl1a = mysqli_query($conn, $cekvlapl1);
?>
    <div class="card">
        <div class="section-head">
            <div>
                <h3>Daftar Peserta - Tanggal: <?= htmlspecialchars($tgl) ?></h3>
                <p>Silahkan validasi dokumen APL.01 peserta di bawah ini</p>
            </div>
            <div class="topbar-actions">
                <input type="text" class="form-input" id="myInput" onkeyup="myFunction()" placeholder="Cari Nama Peserta..." style="width:250px">
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </div>

        <div class="tbl-wrap">
            <table class="tbl" id="myTable">
                <thead>
                    <tr>
                        <th>Nama Asesi</th>
                        <th>Permohonan</th>
                        <th>Bukti</th>
                        <th>Biodata</th>
                        <th>Status</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = mysqli_fetch_array($cekvlapl1a)): 
                    $status_class = ($row['validasiapl1'] == 'N') ? 'badge-navy' : 'badge-teal';
                    $status_text  = ($row['validasiapl1'] == 'N') ? 'Belum Divalidasi' : 'Sudah Divalidasi';
                    $icon_status  = ($row['validasiapl1'] == 'N') ? 'fa-clock' : 'fa-check-circle';
                ?>
                    <tr>
                        <td style="font-weight:600"><?= htmlspecialchars($row['namasiswa']) ?></td>
                        <td>
                            <a href="validasiapl1lsp2.php?idasesi=<?= $row['idasesi'] ?>&idskema=<?= $row['idskema'] ?>" 
                               class="btn btn-secondary btn-sm" onclick="window.open(this.href,'targetWindow','width=800,height=600'); return false;">
                               <i class="fas fa-file-alt"></i> Cek APL1
                            </a>
                        </td>
                        <td>
                            <a href="../siswa/gambarimages/<?= $row['buktiapl1'] ?>" target="_blank" class="badge badge-navy">
                                <i class="fas fa-image"></i> Lihat Bukti
                            </a>
                        </td>
                        <td>
                            <a href="../siswa/biodatasiswapdf.php?email=<?= trim($row['email']) ?>" 
                               class="badge badge-teal" onclick="window.open(this.href,'targetWindow','width=800,height=600'); return false;">
                                <i class="fas fa-user"></i> Biodata
                            </a>
                        </td>
                        <td>
                            <span class="badge <?= $status_class ?>"><i class="fas <?= $icon_status ?> mr-1"></i> <?= $status_text ?></span>
                        </td>
                        <td align="center">
                            <a href="?op=validasidataapl&idasesi=<?= $row['idasesi'] ?>&tgllsp=<?= $tgl ?>&emailps=<?= $row['email'] ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-user-check"></i> Validasi
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
/* ===========================
   OPERASI: FORM VALIDASI DETAIL
=========================== */
elseif ($op == "validasidataapl"):
    $idasesi = $_GET['idasesi'];
    $tgllsp  = $_GET['tgllsp'];
    $emailsya = $_GET['emailps'];

    $execsy = mysqli_fetch_array(mysqli_query($conn, "SELECT idskema FROM skemasiswa WHERE emailsiswa='$emailsya'"));
    $idskemasya = $execsy['idskema'];

    $apl1abcd = "SELECT lsp_usertbl.email as emailusr, lsp_usertbl.linkttd, apl1.namasiswa 
                 FROM lsp_usertbl LEFT JOIN apl1 ON lsp_usertbl.email=apl1.email 
                 WHERE apl1.idasesi='$idasesi'";
    $apl1abcdef = mysqli_fetch_array(mysqli_query($conn, $apl1abcd));
    
    // Ambil Data Rekomendasi (jika sudah pernah diisi)
    $adax = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rekomendasi WHERE namarekom = 'apl1lsp' AND idskema='$idskemasya' AND idasesi='$idasesi'"));
    $cat  = $adax['catatan'] ?? '';
    $lrek = $adax['rekom'] ?? '';

    $pengurus = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pengurus WHERE jabatan='a'"));
?>
    <div class="card">
        <div class="section-head">
            <h3>Pemeriksaan Dokumen Persyaratan</h3>
            <button class="btn btn-secondary btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
        </div>

        <form method="post" action="?op=simpanvalidasilsp">
            <input type="hidden" name="idskemalsp" value="<?= $idskemasya ?>">
            <input type="hidden" name="idasesilsp" value="<?= $idasesi ?>">
            <input type="hidden" name="tgllsp" value="<?= $tgllsp ?>">
            <input type="hidden" name="emailusrlsp" value="<?= $emailsya ?>">

            <h4 style="margin:20px 0 10px; font-size: 0.9rem; color: var(--teal-dark);">I. Bukti Kelengkapan Persyaratan Dasar</h4>
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Bukti Persyaratan</th>
                        <th width="100">Memenuhi</th>
                        <th width="100">Tdk Memenuhi</th>
                        <th width="100">Tdk Ada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $listsya = mysqli_query($conn, "SELECT * FROM syarat WHERE idskema='$idskemasya' AND kodesyarat='1'");
                    $ii = 0;
                    while($row = mysqli_fetch_array($listsya)):
                        $idsyarata = $row['idsyarat'];
                        $cek = mysqli_fetch_array(mysqli_query($conn, "SELECT ceklista FROM syaratsiswa WHERE idskema='$idskemasya' AND idasesi='$idasesi' AND idsyarat='$idsyarata'"));
                        $val = $cek['ceklista'] ?? '';
                    ?>
                    <tr>
                        <td><?= ++$ii ?></td>
                        <td><?= $row['syarat'] ?></td>
                        <td><input type="radio" name="adamsy<?= $ii-1 ?>" value="mms" <?= $val=='mms'?'checked':'' ?>></td>
                        <td><input type="radio" name="adamsy<?= $ii-1 ?>" value="tmms" <?= $val=='tmms'?'checked':'' ?>></td>
                        <td><input type="radio" name="adamsy<?= $ii-1 ?>" value="tdk" <?= $val=='tdk'?'checked':'' ?>></td>
                        <input type="hidden" name="idsyaratsatu<?= $ii-1 ?>" value="<?= $idsyarata ?>">
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <input type="hidden" name="na" value="<?= $ii ?>">

            <div class="divider-line"></div>
            <div class="form-grid">
                <div class="form-label">Rekomendasi LSP</div>
                <div>
                    <label style="margin-right:20px"><input type="radio" name="lsprekom" value="L" <?= $lrek=='L'?'checked':'' ?>> Diterima</label>
                    <label><input type="radio" name="lsprekom" value="T" <?= $lrek=='T'?'checked':'' ?>> Ditolak</label>
                </div>
                <div class="form-label">Catatan</div>
                <textarea name="cttlsp" class="form-input" rows="3"><?= $cat ?></textarea>
            </div>

            <div class="form-actions" style="margin-top:20px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Validasi</button>
                <a href="?op=listpesertalsp&tgl=<?= $tgllsp ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php
/* ===========================
   OPERASI: SIMPAN & LAINNYA
=========================== */
elseif ($op == "simpanvalidasilsp"):
    include "validasi_logic_save.php";
endif;

if ($op == ''):
?>
    <div class="card" style="max-width: 500px; margin: 0 auto;">
        <div class="section-head">
            <div>
                <h3><i class="fas fa-calendar-check" style="color:var(--teal)"></i> Pilih Jadwal</h3>
                <p>Pilih tanggal registrasi untuk memvalidasi asesi</p>
            </div>
        </div>
        <form method="POST" action="?op=listpesertalsp">
            <div class="form-grid" style="grid-template-columns: 1fr;">
                <div class="form-label">Tanggal Registrasi</div>
                <select name="tgl" class="form-input" autofocus>
                    <?php
                    $exectgl = mysqli_query($conn, "SELECT tglrekskema FROM skemasiswa GROUP BY tglrekskema ORDER BY tglrekskema DESC");
                    while($rtgl = mysqli_fetch_array($exectgl)) {
                        echo "<option value='{$rtgl['tglrekskema']}'>{$rtgl['tglrekskema']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-actions" style="margin-top:20px">
                <button type="submit" class="btn btn-primary" style="width:100%">
                    Lanjutkan <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
<?php endif; ?>

</div> <script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      tr[i].style.display = (txtValue.toUpperCase().indexOf(filter) > -1) ? "" : "none";
    }       
  }
}
</script>

<?php include "template_footer.php"; ?>