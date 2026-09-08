<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Setting Tanggal";
$page_sub    = "Manajemen Tanggal Uji Kompetensi";
$active_menu = "settanggal";
$user_level  = "lsp";

include "template_header.php"; 

$op = isset($_GET['op']) ? $_GET['op'] : "";

// --- LOGIC SECTION ---

if ($op == "simpansett") {
    $tgl     = $_POST['tanggal'] ?? date('Y-m-d');
    $ket     = mysqli_real_escape_string($conn, $_POST['ket'] ?? '');
    $yt      = $_POST['yt'] ?? 'A';
    $tanggal = date('Y-m-d', strtotime($tgl));
    
    $cekdata = mysqli_query($conn, "SELECT * FROM settanggal WHERE tanggal = '$tanggal'");
    if(mysqli_num_rows($cekdata) > 0) {
        echo "<div class='alert-box alert-error'><i class='fas fa-circle-xmark'></i> Data Tanggal Sudah Terdaftar!</div>";
    } else {
        $ssql = "INSERT INTO settanggal (tanggal, keterangan, status) VALUES ('$tanggal','$ket','$yt')";
        $exec = mysqli_query($conn, $ssql);
        if($exec) { 
            echo "<script>window.location='settanggal.php';</script>";
            exit;
        } else { 
            echo "<div class='alert-box alert-error'><i class='fas fa-circle-xmark'></i> Gagal Simpan: " . mysqli_error($conn) . "</div>"; 
        }
    }
}

else if($op == "updatesett") {
    $tgl     = $_POST['tanggal'] ?? date('Y-m-d');
    $ket     = mysqli_real_escape_string($conn, $_POST['ket'] ?? '');
    $yt      = $_POST['yt'] ?? 'A';
    $idt     = (int)$_POST['id'];
    $tanggal = date('Y-m-d', strtotime($tgl));

    $up   = "UPDATE settanggal SET tanggal='$tanggal', keterangan='$ket', status='$yt' WHERE id_set='$idt'";
    $eup  = mysqli_query($conn, $up);
    if($eup) { 
        echo "<script>window.location='settanggal.php';</script>";
        exit;
    } else { 
        echo "<div class='alert-box alert-error'><i class='fas fa-circle-xmark'></i> Gagal Update: " . mysqli_error($conn) . "</div>"; 
    }
}

else if($op == "hapussett") {
    $tgl   = (int)$_GET['idtgl'];
    $hapus = mysqli_query($conn, "DELETE FROM settanggal WHERE id_set='$tgl'");
    echo "<script>window.location='settanggal.php';</script>";
    exit;
}

else if($op == "updatestatust") {
    $idt     = (int)$_GET['id'];
    $query   = mysqli_query($conn, "SELECT status FROM settanggal WHERE id_set = '$idt'");
    $data    = mysqli_fetch_array($query);
    $st_baru = ($data['status'] == 'A') ? 'N' : 'A';
    mysqli_query($conn, "UPDATE settanggal SET status='$st_baru' WHERE id_set='$idt'");
    echo "<script>window.location='settanggal.php';</script>";
    exit;
}

// --- VIEW SECTION ---
?>

<div class="card">
    <div class="section-head">
        <div><h3>Daftar Tanggal Uji Kompetensi</h3></div>
        <div style="display:flex;gap:8px">
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
            <a href="settanggal.php?op=tambahsett" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Tanggal</a>
        </div>
    </div>

    <?php if ($op == "tambahsett" || $op == "editsett"): 
        $val_tgl = date('Y-m-d'); $val_ket = ""; $val_st = "A"; $val_id = ""; $btn_label = "Simpan Data";
        $action  = "simpansett";

        if($op == "editsett") {
            $val_id  = (int)$_GET['idtgl'];
            $val_ket = isset($_GET['ket']) ? $_GET['ket'] : "";
            $val_tgl = !empty($_GET['tanggal']) ? date("Y-m-d", strtotime($_GET['tanggal'])) : date('Y-m-d');
            $val_st  = $_GET['st'] ?? 'A';
            $action  = "updatesett";
            $btn_label = "Simpan Perubahan";
        }
    ?>
        <div style="background:var(--off);padding:20px;border-radius:12px;margin-bottom:24px;border:1px solid var(--border)">
            <h4 style="margin-bottom:16px"><i class="fas fa-calendar-day" style="color:var(--teal);margin-right:8px"></i><?= $op=='editsett'?'Edit Tanggal Uji':'Tambah Tanggal Uji Baru' ?></h4>
            <form method="post" action="settanggal.php?op=<?php echo $action; ?>">
                <input type="hidden" name="id" value="<?php echo $val_id; ?>">
                <div class="form-grid">
                    <div class="form-label">Tanggal Uji *</div>
                    <input type="date" name="tanggal" value="<?php echo $val_tgl; ?>" class="form-input" required>
                    
                    <div class="form-label">Keterangan</div>
                    <input type="text" name="ket" value="<?php echo htmlspecialchars($val_ket); ?>" placeholder="Contoh: Gelombang 1 - RPL" class="form-input">
                    
                    <div class="form-label">Status</div>
                    <div class="radio-group">
                        <label class="radio-item"><input type="radio" name="yt" value="A" <?php if(strtoupper($val_st)=='A') echo "checked"; ?>> Aktif</label>
                        <label class="radio-item"><input type="radio" name="yt" value="N" <?php if(strtoupper($val_st)=='N' || strtoupper($val_st)=='T') echo "checked"; ?>> Tidak Aktif</label>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> <?php echo $btn_label; ?></button>
                    <a href="settanggal.php" class="btn btn-secondary"><i class="fas fa-xmark"></i> Batal</a>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div class="tbl-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Tanggal Uji</th>
                    <th>Keterangan</th>
                    <th width="120" style="text-align:center">Status</th>
                    <th width="150" style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $res = mysqli_query($conn, "SELECT * FROM settanggal ORDER BY tanggal DESC");
                if ($res && mysqli_num_rows($res) > 0):
                    while ($row = mysqli_fetch_array($res)) {
                        $is_aktif = (strtoupper($row['status'] ?? '') == 'A');
                        $row_ket = isset($row['keterangan']) ? $row['keterangan'] : (isset($row['ket']) ? $row['ket'] : '');
                        $row_id  = isset($row['id_set']) ? $row['id_set'] : '';
                    ?>
                    <tr>
                        <td class="row-num"><?= $no++; ?></td>
                        <td><strong style="color:var(--navy-soft)"><i class="fas fa-calendar" style="color:var(--teal);margin-right:6px"></i><?= date("d M Y", strtotime($row['tanggal'])); ?></strong></td>
                        <td><?= htmlspecialchars($row_ket); ?></td>
                        <td style="text-align:center">
                            <a href="settanggal.php?op=updatestatust&id=<?= $row_id; ?>" class="badge <?= $is_aktif ? 'badge-green' : 'badge-red' ?>" title="Klik untuk ubah status">
                                <i class="fas <?= $is_aktif ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i> <?= $is_aktif ? 'Aktif' : 'Tidak Aktif' ?>
                            </a>
                        </td>
                        <td>
                            <div class="action-group" style="justify-content:center">
                                <a href="settanggal.php?op=editsett&st=<?= $row['status']; ?>&tanggal=<?= $row['tanggal']; ?>&ket=<?= urlencode($row_ket); ?>&idtgl=<?= $row_id; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <a href="settanggal.php?op=hapussett&idtgl=<?= $row_id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus tanggal uji ini?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:30px">Belum ada data tanggal uji.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "template_footer.php"; ?>