<?php
session_start();
include "../lsp_koneksi.php";

$page_title  = "FR.IA.05 Pertanyaan Tertulis";
$page_sub    = "Manajemen soal tes tulis dan modul";
$active_menu = "inputtestulis";
$user_level  = "lsp";

include "template_header.php";

$op = $_GET['op'] ?? '';

/* ================================================================
   PROSES POST — semua dihandle di atas sebelum output HTML
================================================================ */

// Update Modul
if ($op == "update") {
    $id       = $_POST['kd_modul'];
    $modul    = $_POST['modul'];
    $jumlah   = $_POST['jumlah_soal'];
    $status   = $_POST['status_soal'];
    $waktu    = $_POST['Waktu'];
    $batas    = $_POST['batas'];
    $kkm      = $_POST['kkm'];
    $tanggal  = $_POST['tanggal'];
    $btsawal  = $_POST['btsawal'];
    $btsakhir = $_POST['btsakhir'];
    $q = "UPDATE modul SET modul='$modul',jumlah_soal='$jumlah',status_soal='$status',Waktu='$waktu',batas='$batas',kkm='$kkm',tanggal='$tanggal',btsawal='$btsawal',btsakhir='$btsakhir' WHERE id='$id'";
    $h = mysqli_query($conn, $q);
    $pesan = $h ? 'Modul berhasil diperbarui.' : 'Gagal: '.mysqli_error($conn);
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}

// Update Soal Tulis
if ($op == "updateprk") {
    $idprk     = $_POST['idprk'];
    $instruksi = $_POST['instruksi'];
    $observasi = $_POST['observasi'];
    $q = "UPDATE praktek SET instruksi='$instruksi',obervasi='$observasi' WHERE idpraktek='$idprk'";
    $h = mysqli_query($conn, $q);
    $pesan = $h ? 'Soal berhasil diperbarui.' : 'Gagal: '.mysqli_error($conn);
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}

// Hapus Soal Tulis
if ($op == "deleteposttulis") {
    $h = mysqli_query($conn, "DELETE FROM pertanyaan WHERE question_id='".$_POST['idtulis']."'");
    $pesan = $h ? 'Soal berhasil dihapus.' : 'Gagal menghapus soal.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}

// Hapus Kerjaan Asesi (multi)
if ($op == "deletekerjaan") {
    $n = (int)$_POST['n'];
    $sukses = 0;
    for ($i = 0; $i < $n; $i++) {
        if (isset($_POST['nim'.$i])) {
            $nim   = trim($_POST['nim'.$i]);
            $kdmdl = $_POST['kdmdl'.$i];
            $h = mysqli_query($conn, "DELETE FROM pertanyaanbck WHERE nim='$nim' AND kd_modul='$kdmdl'");
            if ($h) $sukses++;
        }
    }
    $pesan = "$sukses data berhasil dihapus.";
    $pesan_tipe = $sukses > 0 ? 'success' : 'warning';
    $op = '';
}

// Hapus Alias
if ($op == "deletepostalias") {
    mysqli_query($conn, "DELETE FROM unitalias WHERE kdalias='".$_POST['kdalias']."'");
    $pesan = 'Alias berhasil dihapus.';
    $pesan_tipe = 'success';
    $op = '';
}

// Simpan Tanggal Tes
if ($op == "simpansett") {
    $tgltes   = date('Y-m-d', strtotime($_POST['tanggaltes']));
    $idtuktes = $_POST['idtuktes'];
    $cek = mysqli_query($conn, "SELECT * FROM tanggaltes WHERE namatuktes='$idtuktes'");
    if (mysqli_num_rows($cek) > 0) { $pesan = 'Duplikat! Tanggal TUK ini sudah ada.'; $pesan_tipe = 'warning'; }
    else {
        $h = mysqli_query($conn, "INSERT INTO tanggaltes VALUE('','$idtuktes','$tgltes')");
        $pesan = $h ? 'Tanggal tes berhasil disimpan.' : 'Gagal: '.mysqli_error($conn);
        $pesan_tipe = $h ? 'success' : 'error';
    }
    $op = 'settanggaltes';
}

// Update Tanggal Tes
if ($op == "updatetest") {
    $tgltesu  = date('Y-m-d', strtotime($_POST['tanggaltes']));
    $kettesu  = $_POST['idtuktes'];
    $idtesu   = $_POST['idtese'];
    $h = mysqli_query($conn, "UPDATE tanggaltes SET tgltes='$tgltesu',namatuktes='$kettesu' WHERE id_tgl='$idtesu'");
    $pesan = $h ? 'Tanggal tes berhasil diperbarui.' : 'Gagal: '.mysqli_error($conn);
    $pesan_tipe = $h ? 'success' : 'error';
    $op = 'settanggaltes';
}

// Hapus Tanggal Tes
if ($op == "hapustest") {
    $h = mysqli_query($conn, "DELETE FROM tanggaltes WHERE id_tgl='".$_GET['idtgl']."'");
    $pesan = $h ? 'Tanggal tes berhasil dihapus.' : 'Gagal menghapus.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = 'settanggaltes';
}

// Hapus Pekerjaan Satu Asesi
if ($op == "hapuspekerjaan") {
    $email  = trim($_GET['email']);
    $kdunit = $_GET['kdu'];
    $h = mysqli_query($conn, "DELETE FROM pertanyaanbck WHERE nim='$email' AND kd_modul='$kdunit'");
    $pesan = $h ? 'Data berhasil dihapus.' : 'Gagal menghapus.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = 'daftarpekerjaan';
}

// Hapus Modul
if ($op == "hapusmodul") {
    $h = mysqli_query($conn, "DELETE FROM modul WHERE id='".$_GET['id']."'");
    $pesan = $h ? 'Modul berhasil dihapus.' : 'Gagal menghapus modul.';
    $pesan_tipe = $h ? 'success' : 'error';
    $op = '';
}

// Update Status Modul
if ($op == "updatestatus") {
    $kd  = $_GET['id'];
    $dst = mysqli_fetch_array(mysqli_query($conn, "SELECT status_soal FROM modul WHERE id='$kd'"), MYSQLI_ASSOC);
    $sbaru = (strtolower($dst['status_soal']) === 'aktif') ? 'Tidak Aktif' : 'aktif';
    mysqli_query($conn, "UPDATE modul SET status_soal='$sbaru' WHERE id='$kd'");
    $op = '';
}

// Hapus Alias
if ($op == "hapusalias") {
    $kdalias = $_GET['id'];
    // tampilkan konfirmasi di bawah
}

// Upload Alias
if ($op == "uploadpostalias") {
    include "excel_reader2.php";
    $dataas  = new Spreadsheet_Excel_Reader($_FILES['uploadedfilee']['tmp_name']);
    $barisas = $dataas->rowcount(0);
    $sukses = 0; $gagal = 0; $dup = [];
    for ($ii = 2; $ii <= $barisas; $ii++) {
        $kdalias = $dataas->val($ii, 3);
        $nmalias = $dataas->val($ii, 4);
        $nmasli  = $dataas->val($ii, 5);
        $skema   = $dataas->val($ii, 2);
        if (empty($kdalias)) break;
        $cek = mysqli_query($conn, "SELECT kdalias FROM unitalias WHERE kdalias='$kdalias'");
        if (mysqli_num_rows($cek) > 0) { $dup[] = $kdalias; continue; }
        $h = mysqli_query($conn, "INSERT INTO unitalias VALUES('','$skema','$kdalias','$nmalias','$nmasli')");
        if ($h) $sukses++; else $gagal++;
    }
    $pesan = "$sukses alias diimport, $gagal gagal.".(!empty($dup)?' Duplikat: '.implode(', ',$dup):'');
    $pesan_tipe = $sukses > 0 ? 'success' : 'error';
    $op = 'inputalias';
}
?>

<?php if (!empty($pesan)): ?>
<div class="alert-box alert-<?= $pesan_tipe ?>">
    <i class="fas <?= $pesan_tipe=='success'?'fa-circle-check':($pesan_tipe=='warning'?'fa-triangle-exclamation':'fa-circle-xmark') ?>"></i>
    <?= htmlspecialchars($pesan) ?>
</div>
<?php endif; ?>

<?php
/* ================================================================
   EDIT SOAL TULIS
================================================================ */
if ($op == "edittulis"):
    $idq = $_GET['idq'];
    $row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pertanyaan WHERE question_id='$idq'"), MYSQLI_ASSOC);
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-pen-to-square" style="color:var(--teal);margin-right:8px"></i>Edit Soal Tes Tulis</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>?op=listtulis&kdunit=<?= $row['kd_modul'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="POST" action="updatesoaltulis.php">
        <input type="hidden" name="kodelama" value="<?= $row['question_id'] ?>">
        <input type="hidden" name="kodesoal" value="<?= $row['kd_modul'] ?>">
        <div class="form-grid">
            <div class="form-label">Pertanyaan</div>
            <textarea name="pertanyaan" class="form-input" rows="5"><?= htmlspecialchars($row['question']) ?></textarea>
            <div class="form-label">Jawaban Benar</div>
            <textarea name="jawaban" class="form-input" rows="3"><?= htmlspecialchars($row['answer']) ?></textarea>
            <div class="form-label">Option 1</div>
            <textarea name="alt_1" class="form-input" rows="3"><?= htmlspecialchars($row['alt_1']) ?></textarea>
            <div class="form-label">Option 2</div>
            <textarea name="alt_2" class="form-input" rows="3"><?= htmlspecialchars($row['alt_2']) ?></textarea>
            <div class="form-label">Option 3</div>
            <textarea name="alt_3" class="form-input" rows="3"><?= htmlspecialchars($row['alt_3']) ?></textarea>
            <div class="form-label">Option 4</div>
            <textarea name="alt_4" class="form-input" rows="3"><?= htmlspecialchars($row['alt_4']) ?></textarea>
        </div>
        <div class="form-actions">
            <button type="submit" name="dsubmit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Ubah Soal</button>
            <button type="button" class="btn btn-secondary" onclick="history.back()"><i class="fas fa-xmark"></i> Batal</button>
        </div>
    </form>
</div>

<?php
/* ================================================================
   LIST SOAL PER KODE UNIT
================================================================ */
elseif ($op == "listtulis"):
    $kodeunit = $_GET['kdunit'] ?? '';
    $hasil    = mysqli_query($conn, "SELECT * FROM pertanyaan WHERE kd_modul='$kodeunit'");
    $total    = mysqli_num_rows($hasil);
?>
<div class="card">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-list" style="color:var(--teal);margin-right:8px"></i>Daftar Soal</h3>
            <p>Kode Modul: <span class="badge badge-teal"><?= htmlspecialchars($kodeunit) ?></span> &nbsp;·&nbsp; <?= $total ?> soal</p>
        </div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th style="width:50px">No</th>
                <th style="width:80px">ID</th>
                <th style="width:120px">Kode Unit</th>
                <th>Pertanyaan</th>
                <th style="width:130px;text-align:center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; while ($d = mysqli_fetch_array($hasil, MYSQLI_ASSOC)): ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td><span class="badge badge-navy"><?= $d['question_id'] ?></span></td>
                <td><span class="badge badge-teal"><?= htmlspecialchars($d['kd_modul']) ?></span></td>
                <td style="font-size:.82rem"><?= htmlspecialchars(mb_strimwidth($d['question'],0,120,'...')) ?></td>
                <td>
                    <div class="action-group" style="justify-content:center">
                        <a href="?op=edittulis&idq=<?= $d['question_id'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <a href="?op=deletetulis&idtulis=<?= $d['question_id'] ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
/* ================================================================
   KONFIRMASI HAPUS SOAL
================================================================ */
elseif ($op == "deletetulis"):
    $idtulis = $_GET['idtulis'];
    $d = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pertanyaan WHERE question_id='$idtulis'"), MYSQLI_ASSOC);
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-trash" style="color:var(--red);margin-right:8px"></i>Hapus Soal</h3>
        <a href="javascript:history.back()" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="alert-box alert-error">
        <i class="fas fa-circle-xmark"></i>
        Yakin menghapus soal ID <strong><?= $idtulis ?></strong>: <em><?= htmlspecialchars(mb_strimwidth($d['question'],0,100,'...')) ?></em>?
    </div>
    <form method="post" action="?op=deleteposttulis">
        <input type="hidden" name="idtulis" value="<?= $idtulis ?>">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
            <a href="javascript:history.back()" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ================================================================
   BATAL / HAPUS SOAL BATCH
================================================================ */
elseif ($op == "bataltulis"):
    $kdmodul_list  = mysqli_query($conn, "SELECT kd_modul FROM pertanyaan GROUP BY kd_modul");
    $unitalias_list = mysqli_query($conn, "SELECT unitalias FROM pertanyaan GROUP BY unitalias");
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-ban" style="color:var(--red);margin-right:8px"></i>Batalkan / Hapus Soal Batch</h3>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="alert-box alert-warning"><i class="fas fa-triangle-exclamation"></i> Hapus akan menghapus semua soal berdasarkan kode modul <b>dan</b> unit alias yang dipilih.</div>
    <form method="POST" action="?op=hapustulis">
        <div class="form-grid">
            <div class="form-label">Kode Soal (Modul)</div>
            <select name="kodeunit" class="form-input">
                <?php while ($r = mysqli_fetch_array($kdmodul_list, MYSQLI_ASSOC))
                    echo "<option value='{$r['kd_modul']}'>{$r['kd_modul']}</option>"; ?>
            </select>
            <div class="form-label">Kode Unit Alias</div>
            <select name="kodeperunit" class="form-input">
                <?php while ($r = mysqli_fetch_array($unitalias_list, MYSQLI_ASSOC))
                    echo "<option value='{$r['unitalias']}'>{$r['unitalias']}</option>"; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus semua soal ini?')"><i class="fas fa-trash"></i> Hapus Soal</button>
        </div>
    </form>
</div>

<?php
// Proses hapus batch
elseif ($op == "hapustulis"):
    $kdmodul    = $_POST['kodeunit'];
    $kdunitalias = $_POST['kodeperunit'];
    $cek = mysqli_query($conn, "SELECT kd_modul FROM pertanyaan WHERE kd_modul='$kdmodul' AND unitalias='$kdunitalias'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "DELETE FROM pertanyaan WHERE kd_modul='$kdmodul' AND unitalias='$kdunitalias'");
        mysqli_query($conn, "DELETE FROM tbloption WHERE kd_modul='$kdmodul' AND tunitalias='$kdunitalias'");
        echo '<div class="alert-box alert-success"><i class="fas fa-circle-check"></i> Soal berhasil dihapus.</div>';
    } else {
        echo '<div class="alert-box alert-error"><i class="fas fa-circle-xmark"></i> Data tidak ditemukan.</div>';
    }

/*================================================================
   UPLOAD SOAL
================================================================*/
elseif ($op == "importprk"):
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-file-excel" style="color:#16A34A;margin-right:8px"></i>Upload Soal Tes Tulis</h3>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form enctype="multipart/form-data" action="uploadtulis.php" method="POST">
        <div class="form-grid">
            <div class="form-label">File Soal (XLS)</div>
            <div>
                <input type="file" name="uploadedfile" accept=".xls" style="display:block;margin-bottom:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Soal</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ================================================================
   UPLOAD GAMBAR
================================================================ */
elseif ($op == "uploadgbrtulis"):
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-image" style="color:var(--teal);margin-right:8px"></i>Upload Gambar Tes</h3>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="uploadgambart.php" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-label">File Gambar</div>
            <div>
                <input type="file" accept="image/*" name="foto[]" multiple style="display:block;margin-bottom:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Gambar</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ================================================================
   UPLOAD KODE MODUL
================================================================ */
elseif ($op == "uploadmodul"):
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-upload" style="color:var(--teal);margin-right:8px"></i>Upload Kode Modul</h3>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form enctype="multipart/form-data" action="postuploadmodul.php" method="POST">
        <div class="form-grid">
            <div class="form-label">File Excel (XLS)</div>
            <div>
                <input type="file" name="uploadedfile" accept=".xls" style="display:block;margin-bottom:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Kode</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ================================================================
   EDIT MODUL
================================================================ */
elseif ($op == "editmodul"):
    $kd = $_GET['id'];
    $dm = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM modul WHERE id='$kd'"), MYSQLI_ASSOC);
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-pen-to-square" style="color:var(--teal);margin-right:8px"></i>Edit Modul</h3>
            <p><span class="badge badge-teal"><?= htmlspecialchars($dm['kd_modul']) ?></span></p>
        </div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="?op=update">
        <input type="hidden" name="kd_modul" value="<?= $dm['id'] ?>">
        <div class="form-grid">
            <div class="form-label">Nama Modul</div>
            <input type="text" name="modul" class="form-input" value="<?= htmlspecialchars($dm['modul']) ?>">
            <div class="form-label">Jumlah Soal</div>
            <input type="number" name="jumlah_soal" class="form-input" value="<?= $dm['jumlah_soal'] ?>">
            <div class="form-label">Status Soal</div>
            <input type="text" name="status_soal" class="form-input" value="<?= htmlspecialchars($dm['status_soal']) ?>">
            <div class="form-label">Waktu (Menit)</div>
            <input type="number" name="Waktu" class="form-input" value="<?= $dm['Waktu'] ?>">
            <div class="form-label">Batas (kali)</div>
            <input type="number" name="batas" class="form-input" value="<?= $dm['batas'] ?>">
            <div class="form-label">KKM</div>
            <input type="number" name="kkm" class="form-input" value="<?= $dm['kkm'] ?>">
            <div class="form-label">Tanggal</div>
            <input type="date" name="tanggal" class="form-input" value="<?= $dm['tanggal'] ?>">
            <div class="form-label">Mulai</div>
            <input type="text" name="btsawal" class="form-input" value="<?= htmlspecialchars($dm['btsawal']) ?>">
            <div class="form-label">Selesai</div>
            <input type="text" name="btsakhir" class="form-input" value="<?= htmlspecialchars($dm['btsakhir']) ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan Perubahan</button>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ================================================================
   HASIL TES
================================================================ */
elseif ($op == "hasiltes"):
    $listmodul = mysqli_query($conn, "SELECT tanggal FROM grade GROUP BY tanggal DESC");
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-chart-bar" style="color:var(--teal);margin-right:8px"></i>Filter Hasil Tes</h3></div>
    <form method="POST" action="daftarnilai.php">
        <div class="form-grid">
            <div class="form-label">Pilih Tanggal</div>
            <select name="tgl" class="form-input">
                <?php while ($ld = mysqli_fetch_array($listmodul, MYSQLI_ASSOC))
                    echo "<option value='{$ld['tanggal']}'>{$ld['tanggal']}</option>"; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" name="dsubmit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Lihat Hasil</button>
        </div>
    </form>
</div>

<?php
/* ================================================================
   DAFTAR ASESI SEDANG TES
================================================================ */
elseif ($op == "daftarpekerjaan"):
    $hasil = mysqli_query($conn, "SELECT * FROM pertanyaanbck GROUP BY nim");
    $i = 0;
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-users" style="color:var(--teal);margin-right:8px"></i>Daftar Asesi Sedang Tes</h3></div>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="alert-box alert-warning">
        <i class="fas fa-triangle-exclamation"></i>
        <strong>Hati-hati!</strong> Menghapus akan menghapus semua jawaban asesi dan mereka harus mengulang tes.
    </div>
    <form method="POST" action="?op=deletekerjaan">
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr>
                    <th style="width:50px">Cek</th>
                    <th>Kode Unit</th>
                    <th>Waktu (Menit)</th>
                    <th>Email Asesi</th>
                    <th style="width:100px;text-align:center">Aksi</th>
                </tr></thead>
                <tbody>
                <?php while ($d = mysqli_fetch_array($hasil, MYSQLI_ASSOC)): ?>
                <tr>
                    <td style="text-align:center">
                        <input type="checkbox" name="nim<?= $i ?>" value="<?= $d['nim'] ?>" style="accent-color:var(--teal);width:16px;height:16px">
                        <input type="hidden" name="kdmdl<?= $i ?>" value="<?= $d['kd_modul'] ?>">
                    </td>
                    <td><span class="badge badge-teal"><?= htmlspecialchars($d['kd_modul']) ?></span></td>
                    <td><?= $d['menit'] ?></td>
                    <td style="color:var(--text-sub)"><?= htmlspecialchars($d['nim']) ?></td>
                    <td style="text-align:center">
                        <a href="?op=hapuspekerjaan&email=<?= urlencode($d['nim']) ?>&kdu=<?= $d['kd_modul'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus data asesi ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php $i++; endwhile; ?>
                </tbody>
            </table>
        </div>
        <input type="hidden" name="n" value="<?= $i ?>">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus semua yang dicentang?')">
                <i class="fas fa-trash"></i> Hapus yang Dicentang
            </button>
        </div>
    </form>
</div>

<?php
/* ================================================================
   INPUT ALIAS
================================================================ */
elseif ($op == "inputalias"):
    $hasil_alias = mysqli_query($conn, "SELECT * FROM unitalias ORDER BY kdalias");
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-tags" style="color:var(--teal);margin-right:8px"></i>Daftar Kode Alias Unit</h3></div>
        <div style="display:flex;gap:8px">
            <a href="?op=importalias" class="btn btn-secondary btn-sm"><i class="fas fa-file-excel"></i> Import Excel</a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th>No</th>
                <th>Kode Alias</th>
                <th>Nama Unit Alias</th>
                <th style="width:100px;text-align:center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; while ($da = mysqli_fetch_array($hasil_alias, MYSQLI_ASSOC)): ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td><span class="badge badge-teal"><?= htmlspecialchars($da['kdalias']) ?></span></td>
                <td><?= htmlspecialchars($da['namaalias']) ?></td>
                <td style="text-align:center">
                    <a href="?op=hapusalias&id=<?= urlencode($da['kdalias']) ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Hapus alias ini?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
/* ================================================================
   KONFIRMASI HAPUS ALIAS
================================================================ */
elseif ($op == "hapusalias"):
    $kdalias = $_GET['id'];
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-trash" style="color:var(--red);margin-right:8px"></i>Hapus Alias</h3></div>
    <div class="alert-box alert-error"><i class="fas fa-circle-xmark"></i>Yakin menghapus kode alias <strong><?= htmlspecialchars($kdalias) ?></strong>?</div>
    <form method="post" action="?op=deletepostalias">
        <input type="hidden" name="kdalias" value="<?= htmlspecialchars($kdalias) ?>">
        <div class="form-actions">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Ya, Hapus</button>
            <a href="?op=inputalias" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php
/* ================================================================
   IMPORT ALIAS
================================================================ */
elseif ($op == "importalias"):
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-file-excel" style="color:#16A34A;margin-right:8px"></i>Import Alias dari Excel</h3>
        <a href="?op=inputalias" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form enctype="multipart/form-data" method="POST" action="?op=uploadpostalias">
        <div class="form-grid">
            <div class="form-label">File Excel (XLS)</div>
            <div>
                <input type="file" name="uploadedfilee" accept=".xls" style="display:block;margin-bottom:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload & Import</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ================================================================
   UPLOAD HASIL TES
================================================================ */
elseif ($op == "uphtes"):
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-upload" style="color:var(--teal);margin-right:8px"></i>Upload Hasil Tes</h3>
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form enctype="multipart/form-data" method="POST" action="?op=postuphtes">
        <div class="form-grid">
            <div class="form-label">File Hasil Tes (XLS)</div>
            <div>
                <input type="file" name="uploadedfile" accept=".xls" style="display:block;margin-bottom:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Hasil</button>
            </div>
        </div>
    </form>
</div>

<?php
/* ================================================================
   SET TANGGAL TES TUK
================================================================ */
elseif ($op == "settanggaltes" || $op == "tambahsett" || $op == "edittest"):
    // --- Sub-form: Tambah tanggal ---
    if ($op == "tambahsett"):
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-calendar-plus" style="color:var(--teal);margin-right:8px"></i>Tambah Tanggal Tes</h3>
        <a href="?op=settanggaltes" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="?op=simpansett">
        <div class="form-grid">
            <div class="form-label">Tanggal (YYYY-MM-DD)</div>
            <input type="date" name="tanggaltes" class="form-input" required autofocus>
            <div class="form-label">TUK</div>
            <select name="idtuktes" class="form-input">
                <?php $tl = mysqli_query($conn, "SELECT * FROM tuk");
                while ($rt = mysqli_fetch_array($tl, MYSQLI_ASSOC))
                    echo "<option value='{$rt['idtuk']}'>{$rt['namatuk']}</option>"; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan</button>
            <a href="?op=settanggaltes" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php elseif ($op == "edittest"):
    $idtgltes = $_GET['idtgl'];
    $de = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tanggaltes WHERE id_tgl='$idtgltes'"), MYSQLI_ASSOC);
?>
<div class="card">
    <div class="section-head"><h3><i class="fas fa-pen-to-square" style="color:var(--teal);margin-right:8px"></i>Edit Tanggal Tes</h3>
        <a href="?op=settanggaltes" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <form method="post" action="?op=updatetest">
        <input type="hidden" name="idtese" value="<?= $idtgltes ?>">
        <div class="form-grid">
            <div class="form-label">Tanggal</div>
            <input type="date" name="tanggaltes" class="form-input" value="<?= $de['tgltes'] ?>" required>
            <div class="form-label">TUK</div>
            <select name="idtuktes" class="form-input">
                <?php $tl = mysqli_query($conn, "SELECT * FROM tuk");
                while ($rt = mysqli_fetch_array($tl, MYSQLI_ASSOC)) {
                    $sel = ($de['namatuktes'] == $rt['idtuk']) ? 'selected' : '';
                    echo "<option value='{$rt['idtuk']}' $sel>{$rt['namatuk']}</option>";
                } ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Simpan</button>
            <a href="?op=settanggaltes" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
        </div>
    </form>
</div>

<?php else: // settanggaltes — daftar
    $hasil_st = mysqli_query($conn, "SELECT tanggaltes.id_tgl,tanggaltes.namatuktes,tanggaltes.tgltes,tuk.namatuk FROM tanggaltes INNER JOIN tuk ON tanggaltes.namatuktes=tuk.idtuk");
    $total_st = mysqli_num_rows($hasil_st);
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-calendar-days" style="color:var(--teal);margin-right:8px"></i>Set Tanggal Tes TUK</h3><p><?= $total_st ?> jadwal</p></div>
        <div style="display:flex;gap:8px">
            <a href="?op=tambahsett" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Tanggal</a>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th>No</th><th>Tanggal Tes</th><th>Nama TUK</th>
                <th style="width:130px;text-align:center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; while ($ds = mysqli_fetch_array($hasil_st, MYSQLI_ASSOC)): ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td style="font-family:'DM Mono',monospace"><?= $ds['tgltes'] ?></td>
                <td><?= htmlspecialchars($ds['namatuk']) ?></td>
                <td>
                    <div class="action-group" style="justify-content:center">
                        <a href="?op=edittest&idtgl=<?= $ds['id_tgl'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <a href="?op=hapustest&idtgl=<?= $ds['id_tgl'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus jadwal ini?')"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php
/* ================================================================
   HALAMAN UTAMA — Daftar Modul
================================================================ */
else:
    $hasil_modul = mysqli_query($conn, "SELECT * FROM modul");
    $total_modul = mysqli_num_rows($hasil_modul);
?>
<div class="card">
    <div class="section-head">
        <div><h3><i class="fas fa-file-lines" style="color:var(--teal);margin-right:8px"></i>Daftar Modul Tes Tulis</h3><p><?= $total_modul ?> modul</p></div>
        <div style="display:flex;gap:6px;flex-wrap:wrap">
            <a href="?op=settanggaltes" class="btn btn-secondary btn-sm"><i class="fas fa-calendar"></i> Set Tanggal TUK</a>
            <a href="?op=uphtes"        class="btn btn-secondary btn-sm"><i class="fas fa-upload"></i> Upload Hasil Tes</a>
            <a href="?op=daftarpekerjaan" class="btn btn-secondary btn-sm"><i class="fas fa-users"></i> Asesi Sedang Tes</a>
            <a href="?op=hasiltes"      class="btn btn-secondary btn-sm"><i class="fas fa-chart-bar"></i> Hasil Tes</a>
            <a href="?op=bataltulis"    class="btn btn-secondary btn-sm"><i class="fas fa-ban"></i> Hapus Soal</a>
            <a href="?op=uploadgbrtulis" class="btn btn-secondary btn-sm"><i class="fas fa-image"></i> Upload Gambar</a>
            <a href="?op=uploadmodul"   class="btn btn-secondary btn-sm"><i class="fas fa-upload"></i> Upload Kode</a>
            <a href="?op=importprk"     class="btn btn-secondary btn-sm"><i class="fas fa-file-excel"></i> Upload Soal</a>
            <a href="?op=inputalias"    class="btn btn-primary btn-sm"><i class="fas fa-tags"></i> Kode Alias</a>
        </div>
    </div>
    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr>
                <th>No</th>
                <th>ID</th>
                <th>Kode Unit</th>
                <th>Nama Modul</th>
                <th style="width:120px;text-align:center">Status</th>
                <th style="width:200px;text-align:center">Aksi</th>
            </tr></thead>
            <tbody>
            <?php $no = 1; while ($dm = mysqli_fetch_array($hasil_modul, MYSQLI_ASSOC)):
                $aktif = strtolower($dm['status_soal']) === 'aktif';
                $statusBadge = $aktif
                    ? '<a href="?op=updatestatus&id='.$dm['id'].'" class="badge badge-green" title="Klik toggle"><i class="fas fa-circle-check"></i> Aktif</a>'
                    : '<a href="?op=updatestatus&id='.$dm['id'].'" class="badge badge-red" title="Klik toggle"><i class="fas fa-circle-xmark"></i> Tidak Aktif</a>';
            ?>
            <tr>
                <td class="row-num"><?= str_pad($no,2,'0',STR_PAD_LEFT) ?></td>
                <td><span class="badge badge-navy"><?= $dm['id'] ?></span></td>
                <td><span class="badge badge-teal"><?= htmlspecialchars($dm['kd_modul']) ?></span></td>
                <td style="font-weight:500"><?= htmlspecialchars($dm['modul']) ?></td>
                <td style="text-align:center"><?= $statusBadge ?></td>
                <td>
                    <div class="action-group" style="justify-content:center">
                        <a href="?op=listtulis&kdunit=<?= urlencode($dm['kd_modul']) ?>" class="btn btn-info btn-sm"><i class="fas fa-list"></i> Soal</a>
                        <a href="?op=editmodul&id=<?= $dm['id'] ?>"    class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
                        <a href="?op=hapusmodul&id=<?= $dm['id'] ?>"   class="btn btn-danger btn-sm"
                           onclick="return confirm('Hapus modul ini?')"><i class="fas fa-trash"></i></a>
                        <a href="../siswa/quis/paginationadmin.php?md=<?= urlencode($dm['kd_modul']) ?>"
                           target="_blank" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                    </div>
                </td>
            </tr>
            <?php $no++; endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include "template_footer.php"; ?>