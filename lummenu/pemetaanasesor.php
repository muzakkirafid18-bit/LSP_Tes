<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Pemetaan Asesor";
$page_sub    = "Manajemen Plotting Asesor & Asesi";
$active_menu = "pemetaanasesor";
$user_level  = "lsp";

include "template_header.php"; 

$op = isset($_GET['op']) ? $_GET['op'] : "";

// --- LOGIC SECTION ---

if ($op == "prosespemetaan1") {
    $n           = $_POST['n'];
    $idasesor    = $_POST["idasesor"];
    $kelompok    = $_POST["kelompok"];
    $idskema     = $_POST["idskema"];
    $tanggal     = date('Y-m-d', strtotime($_POST["tanggal"]));
    $namaasesor  = $_POST["namaasesor"];

    for ($i=0; $i<$n; $i++) {
        if (isset($_POST['id'.$i])) {
            $idasesi = $_POST['id'.$i];
            $nasesi  = $_POST['nama'.$i];

            // Cek Duplikat
            $cek = mysqli_query($conn, "SELECT * FROM pemetaan WHERE idskema='$idskema' AND idpeserta='$idasesi' AND tanggal='$tanggal'");
            if (mysqli_num_rows($cek) == 0) {
                $sql = "INSERT INTO pemetaan (idskema, idasesor, namaasesor, kelompok, tanggal, idpeserta) 
                        VALUES ('$idskema','$idasesor','$namaasesor','$kelompok','$tanggal','$idasesi')";
                if(mysqli_query($conn, $sql)) {
                    mysqli_query($conn, "UPDATE permohonan SET statuspmt='Y' WHERE idskema='$idskema' AND id='$idasesi'");
                }
            }
        }
    }
    echo "<div class='alert alert-success'>Proses Pemetaan Berhasil Disimpan!</div>";
}

else if ($op == "batalpemetaan") {
    $n = $_POST['n'];
    for ($i=0; $i<$n; $i++) {
        if (isset($_POST['id'.$i])) {
            $idpemetaan = $_POST['id'.$i];
            $idskema    = $_POST['idskema'.$i];
            $idasesi    = $_POST['idasesi'.$i];

            if(mysqli_query($conn, "DELETE FROM pemetaan WHERE idpemetaan='$idpemetaan'")) {
                mysqli_query($conn, "UPDATE permohonan SET statuspmt='N' WHERE idskema='$idskema' AND id='$idasesi'");
            }
        }
    }
    echo "<div class='alert alert-warning'>Pemetaan Berhasil Dibatalkan!</div>";
}

// --- VIEW SECTION ---
?>

<div class="card">
    <?php if ($op == "setpemetaan"): 
        $iduser = $_GET['iduser'];
        $namaasesor = $_GET["namaa"];
        $cektgl = mysqli_query($conn, "SELECT * FROM settanggal WHERE status='A'");
        if(mysqli_num_rows($cektgl) > 0):
    ?>
        <div class="section-head"><h3>Setting Pemetaan: <?php echo $namaasesor; ?></h3></div>
        <form method="post" action="?op=setpemetaan1">
            <input type="hidden" name="iduser" value="<?php echo $iduser; ?>">
            <input type="hidden" name="namaa" value="<?php echo $namaasesor; ?>">
            
            <div class="form-group">
                <label>Pilih Skema:</label>
                <select class="form-control" name="idskema">
                    <?php
                    $skema = mysqli_query($conn, "SELECT * FROM skema ORDER BY namaskema");
                    while($r = mysqli_fetch_array($skema)) echo "<option value='$r[idskema]'>$r[namaskema]</option>";
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Paket/Group:</label>
                <select class="form-control" name="kelompok">
                    <?php for($v=1; $v<=30; $v++) echo "<option value=$v>Paket $v</option>"; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Tanggal Uji:</label>
                <select class="form-control" name="tanggal">
                    <?php
                    $tgl = mysqli_query($conn, "SELECT tanggal FROM settanggal WHERE status='A' GROUP BY tanggal");
                    while($r = mysqli_fetch_array($tgl)) echo "<option value='$r[tanggal]'>$r[tanggal]</option>";
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Lanjutkan <i class="fas fa-arrow-right"></i></button>
        </form>
    <?php else: echo "<div class='alert alert-danger'>Tanggal Aktif Belum Di-set!</div>"; endif; ?>

    <?php elseif ($op == "setpemetaan1"): 
        $idasesor = $_POST["iduser"];
        $kelompok = $_POST["kelompok"];
        $idskema  = $_POST["idskema"];
        $tanggal  = $_POST["tanggal"];
        $namaasesor = $_POST["namaa"];

        // Ambil Nama Asesor berdasarkan ID lsp_usertbl
        $as = mysqli_fetch_array(mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE id='$idasesor'"));
        
        // PERBAIKAN: Mengubah u.id_user menjadi u.id agar sesuai dengan primary key asli lsp_usertbl
// Hapus dulu filter statusnya buat tes apakah datanya beneran ada
$sql_asesi = "SELECT u.id, u.nama FROM permohonan p 
              JOIN lsp_usertbl u ON p.email = u.email";
                      $res_asesi = mysqli_query($conn, $sql_asesi);
    ?>
        <form method="post" action="?op=prosespemetaan1">
            <input type="hidden" name="idasesor" value="<?php echo $idasesor; ?>">
            <input type="hidden" name="idskema" value="<?php echo $idskema; ?>">
            <input type="hidden" name="tanggal" value="<?php echo $tanggal; ?>">
            <input type="hidden" name="kelompok" value="<?php echo $kelompok; ?>">
            <input type="hidden" name="namaasesor" value="<?php echo $namaasesor; ?>">

            <div class="section-head">
                <h3>Plotting Asesi (Asesor: <?php echo $as['nama']; ?> | Paket: <?php echo $kelompok; ?>)</h3>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">Pilih</th>
                        <th>ID User</th>
                        <th>Nama Asesi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i=0;
                    while($d = mysqli_fetch_array($res_asesi)): ?>
                    <tr>
                        <td><input type="checkbox" name="id<?php echo $i; ?>" value="<?php echo $d['id']; ?>"></td>
                        <td><?php echo $d['id']; ?></td>
                        <td>
                            <?php echo $d['nama']; ?>
                            <input type="hidden" name="nama<?php echo $i; ?>" value="<?php echo $d['nama']; ?>">
                        </td>
                    </tr>
                    <?php $i++; endwhile; ?>
                </tbody>
            </table>
            <input type="hidden" name="n" value="<?php echo $i; ?>">
            <br>
            <button type="submit" class="btn btn-primary">Simpan Pemetaan</button>
        </form>

    <?php elseif ($op == "listpemetaan"): 
        $idasesor = $_GET['iduser'];
        $list = mysqli_query($conn, "SELECT * FROM pemetaan WHERE idasesor='$idasesor' ORDER BY tanggal DESC");
    ?>
        <form method="post" action="?op=batalpemetaan">
            <div class="section-head"><h3>Daftar Pemetaan Asesor</h3></div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">Batal</th>
                        <th>Skema</th>
                        <th>Asesi</th>
                        <th>Paket</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; while($row = mysqli_fetch_array($list)): ?>
                    <tr>
                        <td><input type="checkbox" name="id<?php echo $i; ?>" value="<?php echo $row['id']; ?>"></td>
                        <td><?php echo $row['idskema']; ?><input type="hidden" name="idskema<?php echo $i; ?>" value="<?php echo $row['idskema']; ?>"></td>
                        <td><?php echo $row['namapeserta']; ?><input type="hidden" name="idasesi<?php echo $i; ?>" value="<?php echo $row['idpeserta']; ?>"></td>
                        <td><?php echo $row['kelompok']; ?></td>
                        <td><?php echo $row['tanggal']; ?></td>
                    </tr>
                    <?php $i++; endwhile; ?>
                </tbody>
            </table>
            <input type="hidden" name="n" value="<?php echo $i; ?>">
            <br>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan pemetaan yang dipilih?')">Proses Pembatalan</button>
        </form>

    <?php else: ?>
        <div class="section-head">
            <h3>Manajemen Pemetaan Asesor</h3>
            <button class="btn btn-secondary btn-sm" onclick="window.print()">Print</button>
        </div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Asesor</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $res = mysqli_query($conn, "SELECT * FROM lsp_usertbl WHERE level='asesor'");
                while ($row = mysqli_fetch_array($res)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><strong><?php echo $row['nama']; ?></strong></td>
                    <td><?php echo $row['email']; ?></td>
                    <td>
                        <a href="?op=setpemetaan&namaa=<?php echo $row['nama']; ?>&iduser=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Set Pemetaan</a>
                        <a href="?op=listpemetaan&iduser=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">Lihat List</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include "template_footer.php"; ?>