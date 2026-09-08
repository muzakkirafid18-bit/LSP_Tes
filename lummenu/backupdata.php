<?php
session_start();
include "../lsp_koneksi.php";

// 1. KONFIGURASI TEMPLATE
$page_title  = "Maintenance";
$page_sub    = "Backup & Restore Database";
$active_menu = "backupdata";
$user_level  = "lsp";

include "template_header.php"; 
?>

<div class="card" style="animation-delay:.05s">
    <div class="section-head">
        <div>
            <h3><i class="fas fa-database" style="color:var(--teal);margin-right:8px"></i> Backup & Restore Database</h3>
            <p>Kelola keamanan data sistem dengan melakukan pencadangan berkala</p>
        </div>
    </div>

<div style="background: var(--teal-light); padding: 16px; border-radius: 10px; margin-bottom: 24px; border: 1px solid var(--border);">
    <span style="color: var(--teal-dark); font-weight: 700; font-size: 0.9rem;">
        <i class="fas fa-server"></i> Nama Database Aktif: 
        <span style="color: var(--navy);">
            <?php 
            // Mengambil nama database langsung dari koneksi jika variabel $database tidak ada
            $result = mysqli_query($conn, "SELECT DATABASE()");
            $row = mysqli_fetch_row($result);
            echo $row[0]; 
            ?>
        </span>
    </span>
</div>
    <div class="form-grid">
        <div class="form-label">
            <strong>Backup Data</strong>
            <p style="font-size: 0.75rem; font-weight: 400; margin-top: 4px;">Unduh salinan database terbaru dalam format .sql</p>
        </div>
        <div style="padding-top: 10px;">
            <form method="post" action="prosesbackupa.php" target="_blank">
                <button type="submit" name="submit" class="btn btn-primary" style="min-width: 180px;">
                    <i class="fas fa-download"></i> PROSES BACKUP
                </button>
            </form>
        </div>
    </div>

    <div class="divider-line"></div>

    <div class="form-grid">
        <div class="form-label">
            <strong>Restore Data</strong>
            <p style="font-size: 0.75rem; font-weight: 400; margin-top: 4px; color: var(--red);">Peringatan: Data saat ini akan ditimpa!</p>
        </div>
        <div style="padding-top: 10px;">
            <form method="post" action="prosesrestorea.php" target="_blank" onsubmit="return confirm('Apakah Anda yakin ingin me-restore data? Data saat ini akan hilang!');">
                <button type="submit" name="submit" class="btn btn-danger" style="min-width: 180px;">
                    <i class="fas fa-upload"></i> PROSES RESTORE
                </button>
            </form>
        </div>
    </div>

</div>

<style>
/* Memastikan form-grid konsisten dengan yang lu mau: Teks di kiri, Tombol di kanan */
.form-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 24px;
    align-items: center; /* Tombol sejajar tengah dengan teks */
    padding: 10px 0;
}

@media (max-width: 900px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include "template_footer.php"; ?>