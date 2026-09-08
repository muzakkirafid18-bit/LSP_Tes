<?php
require('pdf/fpdf.php');
include "../lsp_koneksi.php";

$email = trim($_GET['email']);

// Query Data Siswa
$query = "SELECT apl1.*, lsp_usertbl.linkttd FROM apl1 
          INNER JOIN lsp_usertbl ON apl1.email = lsp_usertbl.email 
          WHERE apl1.email = '$email'";
$hasil = mysqli_query($conn, $query);

if (mysqli_num_rows($hasil) > 0) {
    $data = mysqli_fetch_array($hasil);
    
    // Variabel Data
    $nama = $data['namasiswa'];
    $tmplahir = $data['tmplahir'];
    $tgllahir = date("d-m-Y", strtotime($data['tgllahir']));
    $jk = ($data['jeniskelamin'] == 'pr') ? "Wanita" : "Laki-laki";
    $kebangsaan = $data['kebangsaan'];
    $alamat = $data['alamat'];
    $kodepos = $data['kodepos'];
    $hp = $data['hp'];
    $nik = $data['nik'];
    $pendidikan = $data['pendidikan'];
    $email_pribadi = $data['email2'];
    $instansi = $data['namalembaga'];

    // TTD & Foto
    $poto = "gambardiri/" . $data['poto'];
    $ttd = "../imgttd/" . $data['linkttd'];
    if (!file_exists($poto) || empty($data['poto'])) $poto = "gambardiri/tidakada.png";
    if (!file_exists($ttd) || empty($data['linkttd'])) $ttd = "../imgttd/tidakada.png";

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->AddPage();

    // --- HEADER (Source 1) ---
    $pdf->Image('../images/lsplogosmkn1.png', 12, 12, 22); 
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(30, 21, '', 1, 0); 
    $pdf->Cell(85, 11, 'FORMULIR', 1, 0, 'C');
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(25, 5.5, ' Dokumen', 'LTR', 0);
    $pdf->Cell(45, 5.5, ': MUK/247/LSPCBN/2024', 'TR', 1);
    
    $pdf->SetX(40);
    $pdf->Cell(85, 10, '', 'LR', 0); 
    $pdf->Cell(25, 5.5, ' No. FR', 'LR', 0);
    $pdf->Cell(45, 5.5, ': FR.APL 01', 'R', 1);
    
    $pdf->SetY(21); $pdf->SetX(40);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(85, 10, 'PERMOHONAN SERTIFIKASI KOMPETENSI', 0, 0, 'C');
    
    $pdf->SetY(21); $pdf->SetX(125);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(25, 5, ' Edisi / Revisi', 'LR', 0);
    $pdf->Cell(45, 5, ': 01/00', 'R', 1);
    $pdf->SetX(125);
    $pdf->Cell(25, 5, ' Berlaku sejak', 'LBR', 0);
    $pdf->Cell(45, 5, ': 19 Oktober 2024', 'BR', 1);

    $pdf->Ln(5);

    // --- BAGIAN 1 (Source 3-8) ---
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 7, 'Bagian 1 : Rincian Data Pemohon Sertifikasi', 0, 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(0, 4, 'Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.', 0);
    
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 6, 'Data Pribadi', 0, 1);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(45, 6, 'Nama Lengkap', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $nama, 'B', 1);
    $pdf->Cell(45, 6, 'No. KTP/NIK/Paspor', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $nik, 'B', 1);
    $pdf->Cell(45, 6, 'Tempat / tgl. Lahir', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $tmplahir . ' / ' . $tgllahir, 'B', 1);
    $pdf->Cell(45, 6, 'Jenis kelamin', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $jk, 'B', 1);
    $pdf->Cell(45, 6, 'Kebangsaan', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $kebangsaan, 'B', 1);
    $pdf->Cell(45, 6, 'Alamat rumah', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $alamat, 'B', 1);
    $pdf->Cell(45, 6, '', 0, 0); $pdf->Cell(5, 6, '', 0, 0); $pdf->Cell(40, 6, 'Kode Pos: '.$kodepos, 'B', 1);
    $pdf->Cell(45, 6, 'No. Telepon/E-mail', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $hp.' / '.$email_pribadi, 'B', 1);
    $pdf->Cell(45, 6, 'Kualifikasi Pendidikan', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $pendidikan, 'B', 1);

    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 6, 'Data Pekerjaan Sekarang', 0, 1);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(45, 6, 'Nama Instansi', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, $instansi, 'B', 1);
    $pdf->Cell(45, 6, 'Jabatan', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, '-', 'B', 1);

    // --- BAGIAN 2 (Source 9-13) ---
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 7, 'Bagian 2 : Data Sertifikasi', 0, 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(0, 4, 'Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan...', 0);
    
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(45, 6, 'Judul Skema', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, 'PEMROGRAM JUNIOR (JUNIOR CODER)', 'B', 1);
    $pdf->Cell(45, 6, 'Nomor Skema', 0, 0); $pdf->Cell(5, 6, ':', 0, 0); $pdf->Cell(0, 6, 'SKM-LSPCBN-247-05', 'B', 1);
    
    $pdf->Ln(2);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(10, 6, 'No', 1, 0, 'C');
    $pdf->Cell(35, 6, 'Kode Unit', 1, 0, 'C');
    $pdf->Cell(95, 6, 'Judul Unit Kompetensi', 1, 0, 'C');
    $pdf->Cell(50, 6, 'Standar Kompetensi', 1, 1, 'C');
    
    $pdf->SetFont('Arial', '', 7);
    $units = [
        ['1', 'J.6204100.004.02', 'Menggunakan Struktur data', 'SKKNI 282/2016'],
        ['2', 'J.6204100.009.01', 'Menggunakan Spesifikasi Program', 'SKKNI 282/2016'],
        ['3', 'J.6204100.010.01', 'Menerapkan Perintah Eksekusi Bahasa...', 'SKKNI 282/2016'],
        ['4', 'J.6204100.016.01', 'Menulis Kode Program dengan Prinsip...', 'SKKNI 282/2016'],
        ['5', 'J.6204100.017.02', 'Mengimplementasikan Pemrograman...', 'SKKNI 282/2016']
    ];
    foreach($units as $u) {
        $pdf->Cell(10, 5, $u[0], 1, 0, 'C');
        $pdf->Cell(35, 5, $u[1], 1, 0);
        $pdf->Cell(95, 5, $u[2], 1, 0);
        $pdf->Cell(50, 5, $u[3], 1, 1);
    }

    // --- BAGIAN 3 (Source 14-18) ---
    $pdf->Ln(4);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 6, 'Bagian 3 : Bukti Kelengkapan Pemohon', 0, 1);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(10, 6, 'No', 1, 0, 'C');
    $pdf->Cell(130, 6, 'Bukti Persyaratan Dasar', 1, 0, 'C');
    $pdf->Cell(25, 6, 'Ada', 1, 0, 'C');
    $pdf->Cell(25, 6, 'Tidak Ada', 1, 1, 'C');
    
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(10, 6, '1', 1, 0, 'C'); $pdf->Cell(130, 6, ' Foto copy kartu pelajar SMK Negeri 1 Cibinong', 1, 0); $pdf->Cell(25, 6, '', 1, 0); $pdf->Cell(25, 6, '', 1, 1);
    $pdf->Cell(10, 6, '2', 1, 0, 'C'); $pdf->Cell(130, 6, ' Fotocopy Raport Semester 1 - 5', 1, 0); $pdf->Cell(25, 6, '', 1, 0); $pdf->Cell(25, 6, '', 1, 1);

    // --- PENUTUP (Source 19) ---
    $pdf->Ln(5);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(0, 5, 'Pemohon / Kandidat :', 0, 1);
    
    $pdf->Image($poto, 15, $pdf->GetY(), 22, 28); // Foto
    $pdf->Image($ttd, 135, $pdf->GetY()+2, 30, 12); // TTD

    $pdf->Ln(20);
    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(0, 5, 'Nama : '.$nama, 0, 1);
    $pdf->Cell(130, 5, '', 0, 0);
    $pdf->Cell(0, 5, 'Tanggal : '.date('d-m-Y'), 0, 1);

    $pdf->Output('I', 'FR_APL_01_'.$nama.'.pdf');
} else {
    echo "Data Tidak Ditemukan";
}
?>
