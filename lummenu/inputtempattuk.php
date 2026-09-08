<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Data TUK";
$page_sub    = "Manajemen Tempat Uji Kompetensi";
$active_menu = "inputtempattuk";
$user_level  = "lsp";

include "template_header.php"; 

// 2. LOGIKA PROSES (PHP) - Biar rapi ditaruh sebelum konten visual
$op = isset($_GET['op']) ? $_GET['op'] : '';

// Proses Hapus Data (Tanpa Form Konfirmasi Terpisah agar lebih modern)
if ($op == "deleteposttuk") {
    $sql = "DELETE from tuk WHERE idtuk=" . $_POST['idtuk'];
    mysqli_query($conn, $sql);
    echo "<script>Swal.fire('Berhasil','Data telah dihapus','success').then(() => { window.location='inputtempattuk.php'; });</script>";
}

// Proses Simpan Baru
if ($op == "appendtuk") {
    $namatuk = mysqli_real_escape_string($conn, $_POST['namatuk']);
    $query = "INSERT INTO tuk (namatuk) VALUES ('$namatuk')";
    if (mysqli_query($conn, $query)) {
        echo "<script>Swal.fire('Berhasil','Data ditambahkan','success').then(() => { window.location='inputtempattuk.php'; });</script>";
    }
}

// Proses Update
if ($op == "updatetuk") {
    $idtuk = $_POST['idtuk'];
    $namatuk = mysqli_real_escape_string($conn, $_POST['namatuk']);
    $query = "UPDATE tuk SET namatuk='$namatuk' WHERE idtuk = '$idtuk'";
    if (mysqli_query($conn, $query)) {
        echo "<script>Swal.fire('Berhasil','Data diperbarui','success').then(() => { window.location='inputtempattuk.php'; });</script>";
    }
}
?>

<?php if ($op == "edittuk"): 
    // Ambil ID dari URL dan pastikan dia angka (biar aman dari error)
    $idtuk = isset($_GET['idtuk']) ? $_GET['idtuk'] : 0;
    
    // Jalankan query
    $querytukedit = "SELECT * FROM tuk WHERE idtuk = '$idtuk'";
    $hasiltukedit = mysqli_query($conn, $querytukedit);
    $datatukedit  = mysqli_fetch_array($hasiltukedit, MYSQLI_ASSOC);

    // CEK: Jika data tidak ditemukan, jangan dipaksa tampilkan form
    if (!$datatukedit) {
        echo "<div class='alert-box alert-error'>Data TUK tidak ditemukan! <a href='inputtempattuk.php'>Kembali</a></div>";
    } else {
?>
    <div class="card">
        <div class="section-head"><h3>Edit Tempat TUK</h3></div>
        <form method="post" action="?op=updatetuk">
            <input type="hidden" name="idtuk" value="<?= $datatukedit['idtuk']; ?>">
            
            <div class="form-grid">
                <div class="form-label">Nama TUK</div>
                <input type="text" name="namatuk" class="form-input" 
                       value="<?= htmlspecialchars($datatukedit['namatuk']); ?>" required autofocus>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Data</button>
                <a href="inputtempattuk.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
<?php 
    } // tutup else data ditemukan
?>

<?php elseif ($op == "tambahtuk"): ?>
    <div class="card">
        <div class="section-head"><h3>Tambah TUK Baru</h3></div>
        <form method="POST" action="?op=appendtuk">
            <div class="form-grid">
                <div class="form-label">Nama TUK</div>
                <input type="text" name="namatuk" class="form-input" placeholder="Contoh: Lab Komputer 1" required autofocus>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan TUK</button>
                <a href="inputtempattuk.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "deletetuk"): 
    $idtuk = $_GET['idtuk'];
    $query = "SELECT namatuk FROM tuk WHERE idtuk = '$idtuk'";
    $data = mysqli_fetch_array(mysqli_query($conn, $query));
?>
    <div class="card">
        <div class="alert-box alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Konfirmasi Hapus</strong><br>
                Yakin ingin menghapus TUK: <b><?= $data['namatuk']; ?></b>?
            </div>
        </div>
        <form method="post" action="?op=deleteposttuk">
            <input type="hidden" name="idtuk" value="<?= $idtuk; ?>">
            <button type="submit" class="btn btn-danger">Ya, Hapus Sekarang</button>
            <a href="inputtempattuk.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

<?php else: ?>
    <div class="card">
        <div class="section-head">
            <h3>Daftar Tempat Uji Kompetensi</h3>
            <a href="?op=tambahtuk" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah TUK</a>
        </div>
        
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID TUK</th>
                        <th>Nama TUK</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $querytukmain = "SELECT * FROM tuk";
                    $hasiltukmain = mysqli_query($conn, $querytukmain);
                    while ($datatukmain = mysqli_fetch_array($hasiltukmain, MYSQLI_ASSOC)) {
                    ?>
                    <tr>
                        <td class="row-num"><?= $no++; ?></td>
                        <td><span class="badge badge-teal"><?= $datatukmain['idtuk']; ?></td>
                        <td><strong><?= $datatukmain['namatuk']; ?></strong></td>
                        <td>
                            <div class="action-group" style="justify-content:center">
                                <a href="?op=edittuk&idtuk=<?= $datatukmain['idtuk']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="?op=deletetuk&idtuk=<?= $datatukmain['idtuk']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <input type="button" value="">
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php include "template_footer.php"; ?>