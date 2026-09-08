<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Data Pengurus";
$page_sub    = "Manajemen Pengurus & Tanda Tangan";
$active_menu = "inputpengurus"; 
$user_level  = "lsp";

include "template_header.php"; 

$op = isset($_GET['op']) ? $_GET['op'] : '';

// --- LOGIKA PROSES ---
if ($op == "appendpengurus") {
    $nip   = mysqli_real_escape_string($conn, $_POST['nip']);
    $namap = mysqli_real_escape_string($conn, $_POST['namap']);
    $jabat = mysqli_real_escape_string($conn, $_POST['jabat']);
    if (!empty($nip)) {
        mysqli_query($conn, "INSERT INTO pengurus (namapengurus, nip, jabatan, ttd) VALUES ('$namap','$nip','$jabat','')");
        echo "<script>window.location='inputpengurus.php';</script>";
        exit;
    }
}

if ($op == "updatepeng") {
    $idpeng  = (int)$_POST['idpeng'];
    $nippeng = mysqli_real_escape_string($conn, $_POST['nippeng']);
    $nmpeng  = mysqli_real_escape_string($conn, $_POST['nmpeng']);
    $jbpeng  = mysqli_real_escape_string($conn, $_POST['jbpeng']);
    mysqli_query($conn, "UPDATE pengurus SET namapengurus='$nmpeng', nip='$nippeng', jabatan='$jbpeng' WHERE idpengurus = '$idpeng'");
    echo "<script>window.location='inputpengurus.php';</script>";
    exit;
}

if ($op == "deletepostpengurus") {
    $idp = (int)$_POST['idp'];
    mysqli_query($conn, "DELETE FROM pengurus WHERE idpengurus='$idp'");
    echo "<script>window.location='inputpengurus.php';</script>";
    exit;
}
?>

<?php if ($op == "editpengurus"): 
    $idp = (int)$_GET['idp'];
    $res = mysqli_query($conn, "SELECT * FROM pengurus WHERE idpengurus = '$idp'");
    $data = mysqli_fetch_array($res);
?>
    <div class="card">
        <div class="section-head"><h3>Edit Pengurus</h3></div>
        <form method="post" action="inputpengurus.php?op=updatepeng">
            <input type="hidden" name="idpeng" value="<?= $data['idpengurus']; ?>">
            <div class="form-grid">
                <div>
                    <label class="form-label">NIP / NO REG</label>
                    <input type="text" name="nippeng" class="form-input" value="<?= htmlspecialchars($data['nip']); ?>" required>
                </div>
                <div>
                    <label class="form-label">Nama Pengurus</label>
                    <input type="text" name="nmpeng" class="form-input" value="<?= htmlspecialchars($data['namapengurus']); ?>" required>
                </div>
                <div>
                    <label class="form-label">Jabatan</label>
                    <select name="jbpeng" class="form-input">
                        <option value="k" <?= $data['jabatan']=='k'?'selected':'' ?>>Ketua Dewan Pengarah</option>
                        <option value="s" <?= $data['jabatan']=='s'?'selected':'' ?>>Ketua / Direktur</option>
                        <option value="a" <?= $data['jabatan']=='a'?'selected':'' ?>>Manajer Sertifikasi</option>
                        <option value="t" <?= $data['jabatan']=='t'?'selected':'' ?>>Manajer Administrasi</option>
                        <option value="b" <?= $data['jabatan']=='b'?'selected':'' ?>>Manajer Manajemen Mutu</option>
                        <option value="i" <?= $data['jabatan']=='i'?'selected':'' ?>>Ketua Bidang IT</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="inputpengurus.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "tambahpengurus"): ?>
    <div class="card">
        <div class="section-head"><h3>Tambah Pengurus Baru</h3></div>
        <form method="POST" action="inputpengurus.php?op=appendpengurus">
            <div class="form-grid">
                <div>
                    <label class="form-label">NIP / NO REG</label>
                    <input type="text" name="nip" class="form-input" placeholder="Masukkan NIP" required>
                </div>
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="namap" class="form-input" placeholder="Masukkan Nama" required>
                </div>
                <div>
                    <label class="form-label">Jabatan</label>
                    <select name="jabat" class="form-input">
                        <option value="k">Ketua Dewan Pengarah</option>
                        <option value="s">Ketua / Direktur</option>
                        <option value="a">Manajer Sertifikasi</option>
                        <option value="t">Manajer Administrasi</option>
                        <option value="b">Manajer Manajemen Mutu</option>
                        <option value="i">Ketua Bidang IT</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Data</button>
                <a href="inputpengurus.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

<?php elseif ($op == "deletepengurus"): 
    $idp = (int)$_GET['idp'];
    $data = mysqli_fetch_array(mysqli_query($conn, "SELECT namapengurus FROM pengurus WHERE idpengurus = '$idp'"));
?>
    <div class="card">
        <div class="alert-box alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>Yakin mau hapus pengurus: <br><strong><?= htmlspecialchars($data['namapengurus'] ?? ''); ?></strong>?</div>
        </div>
        <form method="post" action="inputpengurus.php?op=deletepostpengurus">
            <input type="hidden" name="idp" value="<?= $idp; ?>">
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
            <a href="inputpengurus.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

<?php else: ?>
    <div class="card">
        <div class="section-head">
            <h3>Daftar Pengurus LSP</h3>
            <a href="inputpengurus.php?op=tambahpengurus" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Pengurus</a>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>NIP / No Reg</th>
                        <th>Nama Pengurus</th>
                        <th>Jabatan</th>
                        <th width="150" style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $res = mysqli_query($conn, "SELECT * FROM pengurus ORDER BY idpengurus ASC");
                    while ($row = mysqli_fetch_array($res)) {
                        $jb = "";
                        switch($row['jabatan']) {
                            case 'k': $jb = "Ketua Dewan Pengarah"; break;
                            case 's': $jb = "Ketua / Direktur"; break;
                            case 'a': $jb = "Manajer Sertifikasi"; break;
                            case 't': $jb = "Manajer Administrasi"; break;
                            case 'b': $jb = "Manajer Manajemen Mutu"; break;
                            case 'i': $jb = "Ketua Bidang IT"; break;
                            default: $jb = "Staff"; break;
                        }
                    ?>
                    <tr>
                        <td class="row-num"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nip']); ?></td>
                        <td><strong><?= htmlspecialchars($row['namapengurus']); ?></strong></td>
                        <td><span class="badge badge-teal"><?= $jb; ?></span></td>
                        <td>
                            <div class="action-group" style="justify-content:center">
                                <a href="inputpengurus.php?op=editpengurus&idp=<?= $row['idpengurus']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="inputpengurus.php?op=deletepengurus&idp=<?= $row['idpengurus']; ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
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