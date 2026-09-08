<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Data Syarat";
$page_sub    = "Manajemen Persyaratan Sertifikasi";
$active_menu = "inputsyarat";
$user_level  = "lsp";

include "template_header.php"; 

$op = isset($_GET['op']) ? $_GET['op'] : '';

// --- LOGIKA PROSES (PHP) ---
if ($op == "appendsyarat") {
    $idskema = mysqli_real_escape_string($conn, $_POST['skema']);
    $syarat  = mysqli_real_escape_string($conn, $_POST['syarat']);
    $kodesya = mysqli_real_escape_string($conn, $_POST['kodesyarat']);

    if (!empty($syarat)) {
        $query = "INSERT INTO syarat (idskema, kodesyarat, syarat) VALUES ('$idskema','$kodesya','$syarat')";
        mysqli_query($conn, $query);
        echo "<script>window.location='inputsyarat.php';</script>";
        exit;
    }
}

if ($op == "updatesyarat") {
    $idsyarat = (int)$_POST['idsyarat'];
    $syarat   = mysqli_real_escape_string($conn, $_POST['syarat']);
    $idskema  = mysqli_real_escape_string($conn, $_POST['skema']);
    $kdsyarat = mysqli_real_escape_string($conn, $_POST['kdsyarat']);

    $query = "UPDATE syarat SET idskema='$idskema', kodesyarat='$kdsyarat', syarat='$syarat' WHERE idsyarat='$idsyarat'";
    mysqli_query($conn, $query);
    echo "<script>window.location='inputsyarat.php';</script>";
    exit;
}

if ($op == "deletepostsyarat") {
    $sqldelsyarat = "DELETE from syarat WHERE idsyarat=" . (int)$_POST['idsyarat'];
    mysqli_query($conn, $sqldelsyarat);
    echo "<script>window.location='inputsyarat.php';</script>";
    exit;
}
?>

<?php if ($op == "editsyarat"): 
    $idsyarat = (int)$_GET['idsyarat'];
    $query = "SELECT * FROM syarat WHERE idsyarat = '$idsyarat'";
    $data  = mysqli_fetch_array(mysqli_query($conn, $query));
?>
    <div class="card">
        <div class="section-head"><h3>Edit Persyaratan</h3></div>
        <form method="post" action="inputsyarat.php?op=updatesyarat">
            <input type="hidden" name="idsyarat" value="<?= $data['idsyarat'] ?>">
            
            <div class="form-grid">
                <div class="form-label">Pilih Skema</div>
                <select name="skema" class="form-input">
                    <?php
                    $tampil = mysqli_query($conn, "SELECT * FROM skema ORDER BY idskema");
                    while($r = mysqli_fetch_array($tampil)) {
                        $sel = ($data['idskema'] == $r['idskema']) ? "selected" : "";
                        echo "<option value='$r[idskema]' $sel>$r[namaskema]</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-grid">
                <div class="form-label">Jenis Bukti</div>
                <select name="kdsyarat" class="form-input">
                    <option value="1" <?= $data['kodesyarat'] == '1' ? 'selected' : '' ?>>Bukti Kelengkapan Dasar</option>
                    <option value="2" <?= $data['kodesyarat'] == '2' ? 'selected' : '' ?>>Bukti Kelengkapan Kompetensi</option>
                </select>
            </div>

            <div class="form-grid">
                <div class="form-label">Detail Syarat</div>
                <input type="text" name="syarat" class="form-input" value="<?= htmlspecialchars($data['syarat']) ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="inputsyarat.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "tambahsyarat"): ?>
    <div class="card">
        <div class="section-head"><h3>Tambah Persyaratan Baru</h3></div>
        <form method="post" action="inputsyarat.php?op=appendsyarat">
            <div class="form-grid">
                <div class="form-label">Pilih Skema</div>
                <select name="skema" class="form-input">
                    <?php
                    $tampil = mysqli_query($conn, "SELECT * FROM skema ORDER BY idskema");
                    while($r = mysqli_fetch_array($tampil)) {
                        echo "<option value='$r[idskema]'>$r[namaskema]</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-grid">
                <div class="form-label">Jenis Bukti</div>
                <select name="kodesyarat" class="form-input">
                    <option value="1">Bukti Kelengkapan Dasar</option>
                    <option value="2">Bukti Kelengkapan Kompetensi</option>
                </select>
            </div>

            <div class="form-grid">
                <div class="form-label">Persyaratan</div>
                <input type="text" name="syarat" class="form-input" placeholder="Misal: Fotocopy Ijazah terakhir" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="inputsyarat.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "deletesyarat"): 
    $idsyarat = (int)$_GET['idsyarat'];
    $data = mysqli_fetch_array(mysqli_query($conn, "SELECT syarat FROM syarat WHERE idsyarat='$idsyarat'"));
?>
    <div class="card">
        <div class="alert-box alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>Yakin ingin menghapus syarat: <strong><?= htmlspecialchars($data['syarat']) ?></strong>?</div>
        </div>
        <form method="post" action="inputsyarat.php?op=deletepostsyarat">
            <input type="hidden" name="idsyarat" value="<?= $idsyarat ?>">
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            <a href="inputsyarat.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

<?php else: ?>
    <div class="card">
        <div class="section-head">
            <h3>Daftar Persyaratan Skema</h3>
            <a href="inputsyarat.php?op=tambahsyarat" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Syarat</a>
        </div>

        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Skema</th>
                        <th>Persyaratan</th>
                        <th>Jenis Bukti</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $res = mysqli_query($conn, "SELECT syarat.*, skema.namaskema FROM syarat LEFT JOIN skema ON skema.idskema = syarat.idskema ORDER BY syarat.idsyarat DESC");
                    while ($row = mysqli_fetch_array($res)) {
                        $ket = ($row['kodesyarat'] == '1') ? "Dasar" : "Kompetensi";
                        $badge = ($row['kodesyarat'] == '1') ? "badge-teal" : "badge-purple";
                    ?>
                    <tr>
                        <td class="row-num"><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($row['namaskema'] ?? '') ?></strong></td>
                        <td><strong><?= htmlspecialchars($row['syarat'] ?? '') ?></strong></td>
                        <td><span class="badge <?= $badge ?>"><?= $ket ?></span></td>
                        <td>
                            <div class="action-group" style="justify-content:center">
                                <a href="inputsyarat.php?op=editsyarat&idsyarat=<?= $row['idsyarat'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="inputsyarat.php?op=deletesyarat&idsyarat=<?= $row['idsyarat'] ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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