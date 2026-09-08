<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Proses Asesmen";
$page_sub    = "Manajemen Komponen MAK 07";
$active_menu = "inputprosesasesmen";
$user_level  = "lsp";

include "template_header.php"; 

$op = isset($_GET['op']) ? $_GET['op'] : '';

// --- LOGIKA PROSES (PHP) ---
if ($op == "appendkpa") {
    $syaratkpa = mysqli_real_escape_string($conn, $_POST['kpa']);
    if (!empty($syaratkpa)) {
        mysqli_query($conn, "INSERT INTO qmak7 (pertanyaan) VALUES ('$syaratkpa')");
        echo "<script>window.location='inputprosesasesmen.php';</script>";
        exit;
    }
}

if ($op == "updatesyaratkpa") {
    $idsyaratkpa = mysqli_real_escape_string($conn, $_POST['idsyaratkpa']);
    $syaratekpa  = mysqli_real_escape_string($conn, $_POST['syaratkpa']);
    mysqli_query($conn, "UPDATE qmak7 SET pertanyaan='$syaratekpa' WHERE idqmak7 = '$idsyaratkpa'");
    echo "<script>window.location='inputprosesasesmen.php';</script>";
    exit;
}

if ($op == "deletepostsyaratkpa") {
    $idsyaratkpa = mysqli_real_escape_string($conn, $_POST['idsyaratkpa']);
    mysqli_query($conn, "DELETE FROM qmak7 WHERE idqmak7='$idsyaratkpa'");
    echo "<script>window.location='inputprosesasesmen.php';</script>";
    exit;
}
?>

<?php if ($op == "editkpa"): 
    $idsyaratkpa = mysqli_real_escape_string($conn, $_GET['idkpa']);
    $res = mysqli_query($conn, "SELECT * FROM qmak7 WHERE idqmak7 = '$idsyaratkpa'");
    $data = mysqli_fetch_array($res);
?>
    <div class="card">
        <div class="section-head"><h3>Edit Komponen Proses Asesmen</h3></div>
        <form method="post" action="inputprosesasesmen.php?op=updatesyaratkpa">
            <input type="hidden" name="idsyaratkpa" value="<?= $data['idqmak7']; ?>">
            <div class="form-grid">
                <div class="form-label">Pertanyaan Proses Asesmen</div>
                <input type="text" name="syaratkpa" class="form-input" value="<?= htmlspecialchars($data['pertanyaan']); ?>" required autofocus>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="inputprosesasesmen.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "tambahkpa"): ?>
    <div class="card">
        <div class="section-head"><h3>Tambah Komponen Baru</h3></div>
        <form method="POST" action="inputprosesasesmen.php?op=appendkpa">
            <div class="form-grid">
                <div class="form-label">Pertanyaan Proses Asesmen</div>
                <input type="text" name="kpa" class="form-input" placeholder="Contoh: Apakah asesor memberikan umpan balik?" required autofocus>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="inputprosesasesmen.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "deletekpa"): 
    $idsyaratkpa = mysqli_real_escape_string($conn, $_GET['idkpa']);
    $data = mysqli_fetch_array(mysqli_query($conn, "SELECT pertanyaan FROM qmak7 WHERE idqmak7 = '$idsyaratkpa'"));
?>
    <div class="card">
        <div class="alert-box alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>Yakin mau hapus pertanyaan: <br><strong><?= $data['pertanyaan']; ?></strong>?</div>
        </div>
        <form method="post" action="inputprosesasesmen.php?op=deletepostsyaratkpa">
            <input type="hidden" name="idsyaratkpa" value="<?= $idsyaratkpa; ?>">
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            <a href="inputprosesasesmen.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

<?php else: ?>
    <div class="card">
        <div class="section-head">
            <h3>Daftar Komponen Proses Asesmen</h3>
            <a href="inputprosesasesmen.php?op=tambahkpa" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Komponen</a>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Pertanyaan Proses Asesmen</th>
                        <th width="150" style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $res = mysqli_query($conn, "SELECT * FROM qmak7 ORDER BY idqmak7 DESC");
                    while ($row = mysqli_fetch_array($res)) {
                    ?>
                    <tr>
                        <td class="row-num"><?= $no++; ?></td>
                        <td><strong><?= htmlspecialchars($row['pertanyaan']); ?></strong></td>
                        <td>
                            <div class="action-group" style="justify-content:center">
                                <a href="inputprosesasesmen.php?op=editkpa&idkpa=<?= $row['idqmak7']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="inputprosesasesmen.php?op=deletekpa&idkpa=<?= $row['idqmak7']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php include "template_footer.php"; ?>