<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Komponen Umpan Balik";
$page_sub    = "Manajemen Pertanyaan MAK 05";
$active_menu = "inputkumpan";
$user_level  = "lsp";

include "template_header.php"; 

$op = isset($_GET['op']) ? $_GET['op'] : '';

// --- LOGIKA PROSES (PHP) ---
if ($op == "appendku") {
    $syaratu = mysqli_real_escape_string($conn, $_POST['syaratu']);
    if (!empty($syaratu)) {
        $query = "INSERT INTO qmak5 (qmak5) VALUES ('$syaratu')";
        mysqli_query($conn, $query);
        echo "<script>window.location='inputkumpan.php';</script>";
        exit;
    }
}

if ($op == "updatesyaratu") {
    $idsyaratu = (int)$_POST['idsyaratu'];
    $syaratu   = mysqli_real_escape_string($conn, $_POST['syaratu']);
    $query = "UPDATE qmak5 SET qmak5='$syaratu' WHERE idqmak5 = '$idsyaratu'";
    mysqli_query($conn, $query);
    echo "<script>window.location='inputkumpan.php';</script>";
    exit;
}

if ($op == "deletepostsyaratu") {
    $idsyaratu = (int)$_POST['idsyaratu'];
    mysqli_query($conn, "DELETE FROM qmak5 WHERE idqmak5='$idsyaratu'");
    echo "<script>window.location='inputkumpan.php';</script>";
    exit;
}
?>

<?php if ($op == "editsyaratu"): 
    $idsyaratu = (int)$_GET['idsyaratu'];
    $res = mysqli_query($conn, "SELECT * FROM qmak5 WHERE idqmak5 = '$idsyaratu'");
    $data = mysqli_fetch_array($res);
?>
    <div class="card">
        <div class="section-head"><h3>Edit Komponen Umpan Balik</h3></div>
        <form method="post" action="inputkumpan.php?op=updatesyaratu">
            <input type="hidden" name="idsyaratu" value="<?= $data['idqmak5']; ?>">
            <div class="form-grid">
                <div class="form-label">Pertanyaan / Komponen</div>
                <input type="text" name="syaratu" class="form-input" value="<?= htmlspecialchars($data['qmak5']); ?>" required autofocus>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="inputkumpan.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "tambahku"): ?>
    <div class="card">
        <div class="section-head"><h3>Tambah Komponen Baru</h3></div>
        <form method="POST" action="inputkumpan.php?op=appendku">
            <div class="form-grid">
                <div class="form-label">Pertanyaan / Komponen</div>
                <input type="text" name="syaratu" class="form-input" placeholder="Masukkan pertanyaan umpan balik..." required autofocus>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="inputkumpan.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "deletesyaratu"): 
    $idsyaratu = (int)$_GET['idsyaratu'];
    $data = mysqli_fetch_array(mysqli_query($conn, "SELECT qmak5 FROM qmak5 WHERE idqmak5 = '$idsyaratu'"));
?>
    <div class="card">
        <div class="alert-box alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>Yakin ingin menghapus komponen: <br><strong><?= htmlspecialchars($data['qmak5'] ?? ''); ?></strong>?</div>
        </div>
        <form method="post" action="inputkumpan.php?op=deletepostsyaratu">
            <input type="hidden" name="idsyaratu" value="<?= $idsyaratu; ?>">
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            <a href="inputkumpan.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

<?php else: ?>
    <div class="card">
        <div class="section-head">
            <h3>Daftar Komponen Umpan Balik</h3>
            <a href="inputkumpan.php?op=tambahku" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Komponen</a>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Pertanyaan / Komponen</th>
                        <th width="150" style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $res = mysqli_query($conn, "SELECT * FROM qmak5 ORDER BY idqmak5 DESC");
                    while ($row = mysqli_fetch_array($res)) {
                    ?>
                    <tr>
                        <td class="row-num"><?= $no++; ?></td>
                        <td><strong><?= htmlspecialchars($row['qmak5']); ?></strong></td>
                        <td>
                            <div class="action-group" style="justify-content:center">
                                <a href="inputkumpan.php?op=editsyaratu&idsyaratu=<?= $row['idqmak5']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="inputkumpan.php?op=deletesyaratu&idsyaratu=<?= $row['idqmak5']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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