<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Data Observasi Praktek";
$page_sub    = "Manajemen Soal Praktek & Observasi";
$active_menu = "inputpraktek";
$user_level  = "lsp";

include "template_header.php"; 

$op = isset($_GET['op']) ? $_GET['op'] : "";

// --- LOGIC SECTION (UPDATE, DELETE, HAPUS UNIT) ---
if ($op == "updateprk") {
    $idprk     = $_POST['idprk'];
    $instruksi = mysqli_real_escape_string($conn, $_POST['instruksi']);
    $observasi = mysqli_real_escape_string($conn, $_POST['observasi']);
    $query = "UPDATE praktek SET instruksi='$instruksi', observasi='$observasi' WHERE idpraktek='$idprk'";
    if (mysqli_query($conn, $query)) { echo "<script>alert('Sukses Update'); window.location='inputpraktek.php';</script>"; }
}
else if ($op == "deletepostprk") {
    $id = $_POST['idpraktek'];
    if (mysqli_query($conn, "DELETE FROM praktek WHERE idpraktek='$id'")) { echo "<script>alert('Terhapus'); window.location='inputpraktek.php';</script>"; }
}
else if($op == "hapusprk"){
    $kodeunit = $_POST['kodeunit'];
    if(mysqli_query($conn, "DELETE FROM praktek WHERE kodeunit='$kodeunit'")) { echo "<div class='alert alert-warning'>Data Unit $kodeunit Berhasil Dihapus.</div>"; }
}
?>

<div class="card">
    <div class="section-head">
        <a href="inputpraktek.php" class="btn btn-secondary btn-sm"><i class="fas fa-list"></i> Daftar Unit</a>
        <a href="?op=importprk" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload XLS</a>
        <a href="?op=batalprk" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus per Unit</a>
        <a href="?op=hasiltesprk" class="btn btn-info btn-sm"><i class="fas fa-poll"></i> Hasil TES</a>
    </div>

    <?php 
    // --- OP: HASIL TES PRK (YANG TADI ILANG) ---
    if($op == "hasiltesprk"): ?>
        <div class="section-title">Filter Hasil Tes Praktek</div>
        <form method="POST" action="daftarnilaiprk.php">
            <div class="form-group">
                <label>Pilih Tanggal Uji:</label>
                <select name="tgl" class="form-input">
                    <?php
                    $listmodul = mysqli_query($conn, "SELECT tanggal FROM rekappraktek GROUP BY tanggal DESC");
                    while($list = mysqli_fetch_array($listmodul)) echo "<option value='$list[tanggal]'>$list[tanggal]</option>";
                    ?>
                </select>
            </div>
            <button type="submit" name="dsubmit" class="btn btn-primary">Tampilkan Nilai</button>
        </form>

    <?php 
    // --- OP: LIST OBSERVASI (DETAIL SOAL PER UNIT) ---
    elseif ($op == "listobser"): 
        $kodeunit = $_GET['kdunit'];
    ?>
        <div class="section-title">Rincian Soal: <?= $kodeunit; ?></div>
        <table class="tbl">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Instruksi Kerja</th>
                    <th>Observasi</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $res = mysqli_query($conn, "SELECT * FROM praktek WHERE kodeunit='$kodeunit'");
                while($row = mysqli_fetch_array($res)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['instruksi']; ?></td>
                    <td><?= $row['observasi']; ?></td>
                    <td>
                        <a href="?op=editprk&idpraktek=<?= $row['idpraktek']; ?>" class="btn-edit"><i class="fas fa-pencil-alt"></i></a>
                        <a href="?op=deleteprk&idpraktek=<?= $row['idpraktek']; ?>" class="btn-delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php 
    // --- OP: EDIT SOAL ---
    elseif ($op == "editprk"): 
        $idpraktek = $_GET['idpraktek'];
        $data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM praktek WHERE idpraktek='$idpraktek'"));
    ?>
        <form method="post" action="?op=updateprk">
            <input type="hidden" name="idprk" value="<?= $data['idpraktek']; ?>">
            <table class="tbl-form">
                <tr><td>Instruksi</td><td><input type="text" name="instruksi" class="form-input" style="width:100%" value="<?= $data['instruksi']; ?>"></td></tr>
                <tr><td>Observasi</td><td><textarea name="observasi" class="form-input" rows="5" style="width:100%"><?= $data['observasi']; ?></textarea></td></tr>
                <tr><td></td><td><button type="submit" class="btn btn-primary">Update</button></td></tr>
            </table>
        </form>

    <?php 
    // --- OP: IMPORT & BATAL ---
    elseif($op == "importprk"): ?>
        <form enctype="multipart/form-data" action="uploadprk.php" method="POST">
            <p>Pilih file Excel (.xls) soal praktek:</p>
            <input name="uploadedfile" type="file" class="form-input" /><br><br>
            <button type="submit" class="btn btn-primary">Upload Sekarang</button>
        </form>
    <?php elseif($op == "batalprk"): ?>
        <form method="POST" action="?op=hapusprk">
            <label>Pilih Kode Unit yang akan dikosongkan soalnya:</label>
            <select name="kodeunit" class="form-input">
                <?php
                $u = mysqli_query($conn, "SELECT kodeunit FROM praktek GROUP BY kodeunit");
                while($ru = mysqli_fetch_array($u)) echo "<option value='$ru[kodeunit]'>$ru[kodeunit]</option>";
                ?>
            </select><br><br>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Hapus permanen?')">Hapus Unit Ini</button>
        </form>

    <?php 
    // --- TAMPILAN DEFAULT (TABEL UNIT) ---
    elseif($op == ""): ?>
        <table class="tbl">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Skema</th>
                    <th>Kode Unit</th>
                    <th>Nama Unit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $res = mysqli_query($conn, "SELECT * FROM unit");
                while ($row = mysqli_fetch_array($res)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><span class="badge badge-teal"><?= $row['idskema']; ?></td>
                    <td><span class="badge badge-teal"><a href="?op=listobser&kdunit=<?= $row['kodeunit']; ?>"><strong><?= $row['kodeunit']; ?></strong></a></td>
                    <td><?= $row['namaunit']; ?></td>
                    <td>
                        <a href="?op=listobser&kdunit=<?= $row['kodeunit']; ?>" class="btn btn-info btn-sm">Lihat Soal</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include "template_footer.php"; ?>