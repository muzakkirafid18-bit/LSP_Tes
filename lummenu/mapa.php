<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "MAPA 01";
$page_sub    = "Merencanakan Aktivitas & Proses Asesmen";
$active_menu = "mapa"; 
$user_level  = "lsp";

include "template_header.php"; 

$op = isset($_GET['op']) ? $_GET['op'] : '';
?>

<div class="card">
    <?php
    if ($op == "mapa") {
        $xmmaskema = $_GET['mmanamaskema'];
        $xmmakskema = $_GET['mmakodeskema'];
        $xmmaids = $_GET['mmaidskema'];

        // Ambil Data MMA jika sudah ada
        $xcariddtmma = "SELECT * FROM mma WHERE idskemamma='$xmmaids'";
        $xexeccari = mysqli_query($conn, $xcariddtmma);
        $xarrmma = mysqli_fetch_array($xexeccari);
        $xtemuddtmma = mysqli_num_rows($xexeccari);

        // Inisialisasi variabel untuk checkbox (agar tidak error jika data kosong)
        $fields = ['kandidat', 'tujuan', 'linkungan', 'peluang', 'hubungan', 'melakukan', 'konfirmasi', 'tolakukur'];
        $data_cb = [];

        foreach ($fields as $f) {
            $data_cb[$f] = isset($xarrmma[$f]) ? explode(",", $xarrmma[$f]) : array_fill(0, 6, '');
        }
    ?>
        <div class="section-head">
            <h3>Detail Perencanaan Asesmen (MAPA)</h3>
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
        </div>

        <form method="post" action="?op=postmapa">
            <input type="hidden" name="vmmaids" value="<?= $xmmaids ?>">
            
            <table class="tbl-form">
                <tr>
                    <td width="30%"><strong>Skema Sertifikasi</strong></td>
                    <td>
                        <strong>Judul:</strong> <?= $xmmaskema ?><br>
                        <strong>Nomor:</strong> <?= $xmmakskema ?>
                    </td>
                </tr>
                <tr>
                    <td><strong>Kandidat</strong></td>
                    <td>
                        <label><input type="checkbox" name="roa1" <?= $data_cb['kandidat'][0]=='on'?'checked':'' ?>> Hasil pelatihan/pendidikan</label><br>
                        <label><input type="checkbox" name="roa2" <?= $data_cb['kandidat'][1]=='on'?'checked':'' ?>> Pekerja berpengalaman</label><br>
                        <label><input type="checkbox" name="roa3" <?= $data_cb['kandidat'][2]=='on'?'checked':'' ?>> Pelatihan mandiri</label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Tujuan Asesmen</strong></td>
                    <td>
                        <label><input type="checkbox" name="rob1" <?= ($data_cb['tujuan'][0]??'')=='on'?'checked':'' ?>> Sertifikasi</label> &nbsp;
                        <label><input type="checkbox" name="rob2" <?= ($data_cb['tujuan'][1]??'')=='on'?'checked':'' ?>> Sertifikasi ulang</label> &nbsp;
                        <label><input type="checkbox" name="rob3" <?= ($data_cb['tujuan'][2]??'')=='on'?'checked':'' ?>> PKT</label> &nbsp;
                        <label><input type="checkbox" name="rob4" <?= ($data_cb['tujuan'][3]??'')=='on'?'checked':'' ?>> RPL</label> &nbsp;
                        <label><input type="checkbox" name="rob5" <?= ($data_cb['tujuan'][4]??'')=='on'?'checked':'' ?>> Lainnya</label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Lingkungan</strong></td>
                    <td>
                        <label><input type="checkbox" name="roc1" <?= ($data_cb['linkungan'][0]??'')=='on'?'checked':'' ?>> Tempat kerja nyata</label> &nbsp;
                        <label><input type="checkbox" name="roc2" <?= ($data_cb['linkungan'][1]??'')=='on'?'checked':'' ?>> Tempat kerja simulasi</label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Peluang Pengumpulan Bukti</strong></td>
                    <td>
                        <label><input type="checkbox" name="rod1" <?= ($data_cb['peluang'][0]??'')=='on'?'checked':'' ?>> Tersedia</label> &nbsp;
                        <label><input type="checkbox" name="rod2" <?= ($data_cb['peluang'][1]??'')=='on'?'checked':'' ?>> Terbatas</label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Hubungan Standar Kompetensi</strong></td>
                    <td>
                        <label><input type="checkbox" name="roe1" <?= ($data_cb['hubungan'][0]??'')=='on'?'checked':'' ?>> Bukti untuk mendukung asesmen / RPL</label><br>
                        <label><input type="checkbox" name="roe2" <?= ($data_cb['hubungan'][1]??'')=='on'?'checked':'' ?>> Aktivitas kerja di tempat kerja Asesi</label><br>
                        <label><input type="checkbox" name="roe3" <?= ($data_cb['hubungan'][2]??'')=='on'?'checked':'' ?>> Kegiatan pembelajaran</label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Siapa yang Melakukan Asesmen</strong></td>
                    <td>
                        <label><input type="checkbox" name="rof1" <?= ($data_cb['melakukan'][0]??'')=='on'?'checked':'' ?>> Lembaga Sertifikasi</label> &nbsp;
                        <label><input type="checkbox" name="rof2" <?= ($data_cb['melakukan'][1]??'')=='on'?'checked':'' ?>> Organisasi Pelatihan</label> &nbsp;
                        <label><input type="checkbox" name="rof3" <?= ($data_cb['melakukan'][2]??'')=='on'?'checked':'' ?>> Asesor Perusahaan</label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Konfirmasi Orang Relevan</strong></td>
                    <td>
                        <div style="margin-bottom:8px">
                            <label><input type="checkbox" name="rog1" <?= ($data_cb['konfirmasi'][0]??'')=='on'?'checked':'' ?>> Manajer sertifikasi LSP</label><br>
                            <label><input type="checkbox" name="rog2" <?= ($data_cb['konfirmasi'][1]??'')=='on'?'checked':'' ?>> Master Asesor / Trainer / Lead Assessor</label><br>
                            <label><input type="checkbox" name="rog3" <?= ($data_cb['konfirmasi'][2]??'')=='on'?'checked':'' ?>> Manajer Pelatihan Lembaga Training</label><br>
                            <label><input type="checkbox" name="rog4" <?= ($data_cb['konfirmasi'][3]??'')=='on'?'checked':'' ?>> Lainnya</label>
                        </div>
                        <select name="orelepan" class="form-input">
                            <option value="">-- Pilih Orang Relevan --</option>
                            <?php
                            $p_res = mysqli_query($conn, "SELECT idpengurus, namapengurus FROM pengurus");
                            while($p = mysqli_fetch_array($p_res)){
                                $sel = ($xarrmma['oreleva'] == $p['idpengurus']) ? "selected" : "";
                                echo "<option value='$p[idpengurus]' $sel>$p[namapengurus]</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Tolok Ukur Asesmen</strong></td>
                    <td>
                        <label><input type="checkbox" name="roh1" <?= ($data_cb['tolakukur'][0]??'')=='on'?'checked':'' ?>> Standar Kompetensi</label><br>
                        <label><input type="checkbox" name="roh2" <?= ($data_cb['tolakukur'][1]??'')=='on'?'checked':'' ?>> Kriteria asesmen dari kurikulum pelatihan</label><br>
                        <label><input type="checkbox" name="roh3" <?= ($data_cb['tolakukur'][2]??'')=='on'?'checked':'' ?>> Spesifikasi kinerja perusahaan atau industri</label><br>
                        <label><input type="checkbox" name="roh4" <?= ($data_cb['tolakukur'][3]??'')=='on'?'checked':'' ?>> Spesifikasi Produk</label><br>
                        <label><input type="checkbox" name="roh5" <?= ($data_cb['tolakukur'][4]??'')=='on'?'checked':'' ?>> Standar Khusus</label>
                    </td>
                </tr>
                <tr>
                    <td><strong>Penyusun / Validator</strong></td>
                    <td>
                        <div style="display:flex; gap:10px;">
                            <select name="openyusun" class="form-input">
                                <option value="">-- Pilih Penyusun --</option>
                                <?php 
                                mysqli_data_seek($p_res, 0);
                                while($p = mysqli_fetch_array($p_res)){
                                    $sel = ($xarrmma['penyusun'] == $p['idpengurus']) ? "selected" : "";
                                    echo "<option value='$p[idpengurus]' $sel>$p[namapengurus]</option>";
                                }
                                ?>
                            </select>
                            <select name="validr" class="form-input">
                                <option value="">-- Pilih Validator --</option>
                                <?php 
                                mysqli_data_seek($p_res, 0);
                                while($p = mysqli_fetch_array($p_res)){
                                    $sel = ($xarrmma['valid'] == $p['idpengurus']) ? "selected" : "";
                                    echo "<option value='$p[idpengurus]' $sel>$p[namapengurus]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="form-actions" style="margin-top:20px;">
                <button type="submit" name="simpan" class="btn btn-primary">Simpan MAPA</button>
                <a href="mapa.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>

    <?php
    } else if ($op == "postmapa") {
        $ymmaids = $_POST['vmmaids'];
        $xrelepan = $_POST['orelepan'];
        $xpenyusun = $_POST['openyusun'];
        $xvalidr = $_POST['validr'];

        // Gabungkan checkbox jadi string koma
        $xroa = ($_POST['roa1']??'').",".($_POST['roa2']??'').",".($_POST['roa3']??'');
        $xrob = ($_POST['rob1']??'').",".($_POST['rob2']??'').",".($_POST['rob3']??'').",".($_POST['rob4']??'').",".($_POST['rob5']??'');
        $xroc = ($_POST['roc1']??'').",".($_POST['roc2']??'');
        $xrod = ($_POST['rod1']??'').",".($_POST['rod2']??'');
        $xroe = ($_POST['roe1']??'').",".($_POST['roe2']??'').",".($_POST['roe3']??'');
        $xrof = ($_POST['rof1']??'').",".($_POST['rof2']??'').",".($_POST['rof3']??'');
        $xrog = ($_POST['rog1']??'').",".($_POST['rog2']??'').",".($_POST['rog3']??'').",".($_POST['rog4']??'');
        $xroh = ($_POST['roh1']??'').",".($_POST['roh2']??'').",".($_POST['roh3']??'').",".($_POST['roh4']??'').",".($_POST['roh5']??'');

        $check = mysqli_query($conn, "SELECT idskemamma FROM mma WHERE idskemamma='$ymmaids'");
        if(mysqli_num_rows($check) > 0){
            $sql = "UPDATE mma SET kandidat='$xroa', tujuan='$xrob', linkungan='$xroc', peluang='$xrod', hubungan='$xroe', melakukan='$xrof', konfirmasi='$xrog', tolakukur='$xroh', oreleva='$xrelepan', penyusun='$xpenyusun', valid='$xvalidr' WHERE idskemamma='$ymmaids'";
        } else {
            $sql = "INSERT INTO mma (idskemamma, kandidat, tujuan, linkungan, peluang, hubungan, melakukan, konfirmasi, tolakukur, oreleva, penyusun, valid) VALUES ('$ymmaids','$xroa','$xrob','$xroc','$xrod','$xroe','$xrof','$xrog','$xroh','$xrelepan','$xpenyusun','$xvalidr')";
        }

        if(mysqli_query($conn, $sql)){
            echo "<div class='alert-box alert-success'>Data MAPA berhasil disimpan! <a href='mapa.php'>OK</a></div>";
        } else {
            echo "<div class='alert-box alert-danger'>Gagal simpan data!</div>";
        }

    } else {
    ?>
        <div class="section-head">
            <h3>Daftar Skema - Perencanaan MAPA</h3>
        </div>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Kode Skema</th>
                        <th>Nama Skema</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $res = mysqli_query($conn, "SELECT * FROM skema");
                    while ($row = mysqli_fetch_array($res)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><span class="badge badge-teal"><?= $row['noskema'] ?></span></td>
                        <td><strong><?= $row['namaskema'] ?></strong></td>
                        <td>
                            <a href="?op=mapa&mmanamaskema=<?= $row['namaskema'] ?>&mmaidskema=<?= $row['idskema'] ?>&mmakodeskema=<?= $row['noskema'] ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> MAPA
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

<?php include "template_footer.php"; ?>