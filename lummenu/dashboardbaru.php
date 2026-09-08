<?php
session_start();
include "../lsp_koneksi.php";

if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
    echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<i class='fas fa-lock' style='font-size:2rem;color:#EF4444;margin-bottom:16px'></i>";
    echo "<h3 style='font-family:Plus Jakarta Sans,sans-serif;color:#1A2E3B'>Anda Harus Login Dahulu!</h3>";
    echo "<a href='../lsp_login.php' style='display:inline-block;margin-top:16px;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>";
    echo "</div>";
    exit;
}
if (!in_array($_SESSION['level'], ['lsp','admin'])) {
    echo "<style>body{font-family:'Plus Jakarta Sans',sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;background:#F4F8FA;}</style>";
    echo "<div style='text-align:center;background:#fff;padding:40px;border-radius:14px;box-shadow:0 4px 32px rgba(15,42,58,.13)'>";
    echo "<i class='fas fa-ban' style='font-size:2rem;color:#EF4444;margin-bottom:16px'></i>";
    echo "<h3 style='font-family:Plus Jakarta Sans,sans-serif;color:#1A2E3B'>Anda Tidak Punya Hak Akses!</h3>";
    echo "<a href='../lsp_login.php' style='display:inline-block;margin-top:16px;padding:10px 24px;background:#3BBFBF;color:#fff;border-radius:8px;text-decoration:none;font-weight:600'>Kembali ke Login</a>";
    echo "</div>";
    exit;
}

// == DATA ASLI DARI DATABASE (sebelumnya di-hardcode/dummy) ==
$uname = $_SESSION['username'];
$ur = mysqli_query($conn, "SELECT nama FROM lsp_usertbl WHERE email='".mysqli_real_escape_string($conn,$uname)."'");
$ux = $ur ? mysqli_fetch_assoc($ur) : null;
$user_name = $ux['nama'] ?? $uname;
$user_role = 'Admin LSP';
$today = date('d F Y');
$current_time = date('H:i');

function sq($conn, $sql) {
    $r = mysqli_query($conn, $sql);
    if (!$r) return 0;
    $row = mysqli_fetch_row($r);
    return $row ? (int)$row[0] : 0;
}

$total_peserta      = sq($conn, "SELECT COUNT(*) FROM users WHERE role='peserta'");
$penguji_aktif       = sq($conn, "SELECT COUNT(*) FROM users WHERE role='asesor'");
$uji_aktif           = sq($conn, "SELECT COUNT(*) FROM settanggal WHERE status='A' AND tanggal>=CURDATE()");
$menunggu_verifikasi = sq($conn, "SELECT COUNT(*) FROM apl1 WHERE validasiapl1='N' OR validasiapl1 IS NULL");
$kompeten            = sq($conn, "SELECT COUNT(DISTINCT idasesi) FROM mak5 WHERE hasil='y'");
$belum_kompeten      = sq($conn, "SELECT COUNT(DISTINCT idasesi) FROM mak5 WHERE hasil='t'");
// asumsi: keputusan MAK.05 'kompeten' = sertifikat terbit. Ganti query ini kalau LSP lo punya tabel sertifikat terpisah.
$sertifikat_terbit   = $kompeten;
$jadwal_bulan_ini    = sq($conn, "SELECT COUNT(*) FROM settanggal WHERE MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE())");

$stats = [
    'total_peserta' => $total_peserta,
    'uji_aktif' => $uji_aktif,
    'sertifikat_terbit' => $sertifikat_terbit,
    'penguji_aktif' => $penguji_aktif,
    'menunggu_verifikasi' => $menunggu_verifikasi,
    'kompeten' => $kompeten,
    'belum_kompeten' => $belum_kompeten,
    'jadwal_bulan_ini' => $jadwal_bulan_ini,
];

// jadwal ujian mendatang (dari settanggal, join kasar ke skemasiswa buat hitung peserta & pemetaan buat nama asesor)
$jadwal_upcoming = [];
$rj = mysqli_query($conn, "SELECT tanggal, keterangan, status FROM settanggal WHERE tanggal >= CURDATE() ORDER BY tanggal ASC LIMIT 5");
if ($rj) {
    while ($row = mysqli_fetch_assoc($rj)) {
        $jp = sq($conn, "SELECT COUNT(*) FROM skemasiswa WHERE tanggal='".$row['tanggal']."'");
        $ja = mysqli_query($conn, "SELECT namaasesor FROM pemetaan WHERE tanggal='".$row['tanggal']."' LIMIT 1");
        $arow = $ja ? mysqli_fetch_assoc($ja) : null;
        $jadwal_upcoming[] = [
            'skema'   => $row['keterangan'] !== '' ? $row['keterangan'] : '-',
            'tanggal' => date('d M Y', strtotime($row['tanggal'])),
            'peserta' => $jp,
            'penguji' => $arow['namaasesor'] ?? '-',
            'status'  => $row['status'] === 'A' ? 'Terkonfirmasi' : 'Menunggu',
        ];
    }
}

// peserta yang baru dinilai, dari MAK.05 (keputusan asesmen)
$peserta_recent = [];
$rp = mysqli_query($conn, "SELECT namapeserta, idasesi, hasil, tglmak5 FROM mak5 WHERE tglmak5 IS NOT NULL ORDER BY tglmak5 DESC LIMIT 6");
if ($rp) {
    while ($row = mysqli_fetch_assoc($rp)) {
        $skq = mysqli_query($conn, "SELECT sk.namaskema FROM skemasiswa ss JOIN skema sk ON sk.idskema=ss.idskema WHERE ss.emailsiswa='".mysqli_real_escape_string($conn,$row['idasesi'])."' LIMIT 1");
        $skrow = $skq ? mysqli_fetch_assoc($skq) : null;
        $peserta_recent[] = [
            'nama'    => $row['namapeserta'],
            'skema'   => $skrow['namaskema'] ?? '-',
            'tanggal' => date('d M Y', strtotime($row['tglmak5'])),
            'status'  => $row['hasil'] === 'y' ? 'Kompeten' : 'Belum Kompeten',
            'nilai'   => '-',
        ];
    }
}

// daftar skema yang sudah punya peserta terdaftar, + jumlah kompeten per skema
$skema_list = [];
$rs = mysqli_query($conn, "SELECT idskema, noskema, namaskema FROM skema WHERE status='Y'");
if ($rs) {
    while ($row = mysqli_fetch_assoc($rs)) {
        $totalSk = sq($conn, "SELECT COUNT(*) FROM skemasiswa WHERE idskema='".$row['idskema']."'");
        if ($totalSk == 0) continue;
        $kompetenSk = sq($conn, "SELECT COUNT(DISTINCT m.idasesi) FROM mak5 m JOIN skemasiswa ss ON ss.emailsiswa=m.idasesi WHERE ss.idskema='".$row['idskema']."' AND m.hasil='y'");
        $skema_list[] = [
            'nama' => $row['namaskema'],
            'kode' => $row['noskema'],
            'total' => $totalSk,
            'kompeten' => $kompetenSk,
            'icon' => '📄',
        ];
    }
}

// notifikasi diturunkan dari angka asli
$notifikasi = [];
if ($menunggu_verifikasi > 0) {
    $notifikasi[] = ['tipe'=>'info','pesan'=>$menunggu_verifikasi." dokumen APL1 menunggu verifikasi",'waktu'=>'hari ini'];
}
if ($kompeten > 0) {
    $notifikasi[] = ['tipe'=>'success','pesan'=>$kompeten." peserta telah dinyatakan kompeten",'waktu'=>'terbaru'];
}
if ($belum_kompeten > 0) {
    $notifikasi[] = ['tipe'=>'warning','pesan'=>$belum_kompeten." peserta dinyatakan belum kompeten",'waktu'=>'terbaru'];
}
if (empty($notifikasi)) {
    $notifikasi[] = ['tipe'=>'info','pesan'=>'Belum ada aktivitas tercatat','waktu'=>'-'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — LSP SMKN 1 Cibinong</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --teal:       #3BBFBF;
            --teal-dark:  #2A9999;
            --teal-light: #E8F8F8;
            --teal-glow:  rgba(59,191,191,.18);
            --navy:       #0F2A3A;
            --navy-mid:   #163347;
            --navy-soft:  #1E4060;
            --white:      #FFFFFF;
            --off:        #F4F8FA;
            --border:     #DDE8ED;
            --text-main:  #1A2E3B;
            --text-sub:   #5A7384;
            --text-muted: #92A9B5;
            --green:      #22C55E;
            --red:        #EF4444;
            --orange:     #F97316;
            --yellow:     #EAB308;
            --sidebar-w:  260px;
            --radius:     14px;
            --shadow:     0 2px 16px rgba(15,42,58,.07);
            --shadow-md:  0 4px 32px rgba(15,42,58,.13);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 15px; scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--off);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */





/* Tambahkan ini untuk memastikan navigasi mengambil ruang yang benar */
.sidebar-nav { 
    padding: 16px 12px; 
    flex: 1 0 auto; /* Membiarkan nav memanjang sesuai kontennya */
}


.sidebar {
    width: var(--sidebar-w);
    height: 100vh;
    max-height: 100vh;
    bottom: 0;
    background: var(--navy);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100;
    overflow-y: auto;
    overflow-x: hidden;
}
.sidebar::-webkit-scrollbar { width: 4px; }
.sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }
.sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.35); }
.sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.07);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-box {
            width: 42px; height: 42px;
            background: var(--teal);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 16px; color: var(--white);
            flex-shrink: 0;
        }
        .logo-text { line-height: 1.2; }
        .logo-text strong { display: block; color: var(--white); font-size: .95rem; font-weight: 700; }
        .logo-text span { color: var(--teal); font-size: .72rem; font-weight: 500; letter-spacing: .5px; }

        .sidebar-nav { padding: 16px 12px; flex: 1; overflow-y: auto; }
        .nav-label {
            color: rgba(255,255,255,.3);
            font-size: .67rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 12px 12px 6px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            transition: all .2s;
            margin-bottom: 2px;
            cursor: pointer;
        }
        .nav-item:hover { background: rgba(255,255,255,.07); color: var(--white); }
        .nav-item.active {
            background: var(--teal);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(59,191,191,.35);
        }
        .nav-item i { width: 18px; text-align: center; font-size: .9rem; }
        .nav-badge {
            margin-left: auto;
            background: var(--red);
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 99px;
            min-width: 20px;
            text-align: center;
        }
        .sidebar-footer {
            padding: 16px 14px;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .user-card {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,.05);
        }
        .user-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--teal);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .85rem; color: var(--white);
            flex-shrink: 0;
        }
        .user-info strong { display: block; color: var(--white); font-size: .82rem; }
        .user-info span { color: var(--teal); font-size: .72rem; }
        .btn-logout {
            margin-left: auto;
            color: rgba(255,255,255,.35);
            background: none; border: none;
            cursor: pointer; font-size: .85rem;
            transition: color .2s;
        }
        .btn-logout:hover { color: var(--red); }

        /* ===== MAIN ===== */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 68px;
            display: flex; align-items: center;
            gap: 16px;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 1.1rem; font-weight: 700; flex: 1; }
        .topbar-title span { color: var(--text-sub); font-weight: 400; font-size: .875rem; margin-left: 8px; }
        .topbar-search {
            display: flex; align-items: center; gap: 8px;
            background: var(--off);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 8px 14px;
            width: 220px;
        }
        .topbar-search input {
            border: none; background: none; outline: none;
            font-size: .85rem; color: var(--text-main); width: 100%;
            font-family: inherit;
        }
        .topbar-search i { color: var(--text-muted); font-size: .85rem; }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .icon-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: var(--white);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--text-sub);
            font-size: .9rem; position: relative;
            transition: all .2s;
        }
        .icon-btn:hover { border-color: var(--teal); color: var(--teal); }
        .icon-btn .dot {
            width: 8px; height: 8px;
            background: var(--red); border-radius: 50%;
            position: absolute; top: 6px; right: 6px;
            border: 2px solid var(--white);
        }
        .date-chip {
            background: var(--teal-light);
            color: var(--teal-dark);
            font-size: .78rem; font-weight: 600;
            padding: 6px 14px; border-radius: 8px;
            display: flex; align-items: center; gap: 6px;
        }

        /* ===== PAGE CONTENT ===== */
        .content { padding: 32px; display: flex; flex-direction: column; gap: 28px; }

        /* ===== WELCOME BANNER ===== */
        .welcome-banner {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-soft) 60%, var(--teal-dark) 100%);
            border-radius: var(--radius);
            padding: 28px 36px;
            display: flex; align-items: center; justify-content: space-between;
            overflow: hidden;
            position: relative;
        }
        .welcome-banner::before {
            content: '';
            position: absolute; top: -40px; right: 120px;
            width: 200px; height: 200px;
            background: rgba(59,191,191,.12);
            border-radius: 50%;
        }
        .welcome-banner::after {
            content: '';
            position: absolute; bottom: -60px; right: 60px;
            width: 160px; height: 160px;
            background: rgba(59,191,191,.08);
            border-radius: 50%;
        }
        .welcome-text h2 { color: var(--white); font-size: 1.5rem; font-weight: 700; margin-bottom: 4px; }
        .welcome-text p { color: rgba(255,255,255,.6); font-size: .875rem; }
        .welcome-text p strong { color: var(--teal); }
        .welcome-stats { display: flex; gap: 24px; z-index: 1; }
        .w-stat { text-align: center; }
        .w-stat .num { font-size: 2rem; font-weight: 800; color: var(--white); line-height: 1; }
        .w-stat .lbl { font-size: .72rem; color: rgba(255,255,255,.5); margin-top: 3px; }
        .w-stat-divider { width: 1px; background: rgba(255,255,255,.1); }

        /* ===== STAT CARDS ===== */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }
        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 22px 24px;
            box-shadow: var(--shadow);
            display: flex; flex-direction: column;
            gap: 12px;
            border: 1.5px solid transparent;
            transition: all .25s;
            cursor: default;
        }
        .stat-card:hover { border-color: var(--teal); transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-card-top { display: flex; align-items: flex-start; justify-content: space-between; }
        .stat-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .ic-teal { background: var(--teal-light); color: var(--teal-dark); }
        .ic-green { background: #DCFCE7; color: #16A34A; }
        .ic-orange { background: #FFF7ED; color: #C2410C; }
        .ic-navy { background: #EFF6FF; color: #1D4ED8; }
        .ic-red { background: #FEF2F2; color: #DC2626; }
        .ic-yellow { background: #FEFCE8; color: #A16207; }
        .stat-trend {
            font-size: .75rem; font-weight: 600;
            padding: 3px 8px; border-radius: 6px;
            display: flex; align-items: center; gap: 3px;
        }
        .trend-up { background: #DCFCE7; color: #16A34A; }
        .trend-down { background: #FEF2F2; color: #DC2626; }
        .trend-neu { background: #F1F5F9; color: #64748B; }
        .stat-num { font-size: 2.1rem; font-weight: 800; color: var(--text-main); line-height: 1; }
        .stat-label { font-size: .8rem; color: var(--text-sub); font-weight: 500; }
        .stat-bar { height: 4px; background: var(--border); border-radius: 2px; overflow: hidden; }
        .stat-bar-fill { height: 100%; border-radius: 2px; background: var(--teal); }

        /* ===== SECTION TITLES ===== */
        .section-head {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .section-head h3 { font-size: 1rem; font-weight: 700; }
        .section-head p { font-size: .78rem; color: var(--text-sub); margin-top: 2px; }
        .btn-link {
            font-size: .8rem; color: var(--teal-dark); font-weight: 600;
            text-decoration: none; display: flex; align-items: center; gap: 5px;
            transition: gap .2s;
        }
        .btn-link:hover { gap: 8px; }

        /* ===== TWO COLUMN LAYOUT ===== */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .three-col { display: grid; grid-template-columns: 1.6fr 1fr; gap: 20px; }

        /* ===== CARD ===== */
        .card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
        }

        /* ===== TABLE ===== */
        .tbl { width: 100%; border-collapse: collapse; }
        .tbl th {
            text-align: left;
            padding: 10px 14px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1.5px solid var(--border);
        }
        .tbl td {
            padding: 12px 14px;
            font-size: .83rem;
            border-bottom: 1px solid var(--off);
            vertical-align: middle;
        }
        .tbl tr:last-child td { border-bottom: none; }
        .tbl tr:hover td { background: var(--off); }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 6px;
            font-size: .72rem; font-weight: 700; gap: 4px;
        }
        .badge-green { background: #DCFCE7; color: #15803D; }
        .badge-red { background: #FEF2F2; color: #B91C1C; }
        .badge-orange { background: #FFF7ED; color: #C2410C; }
        .badge-teal { background: var(--teal-light); color: var(--teal-dark); }
        .badge-gray { background: #F1F5F9; color: #64748B; }

        /* ===== PROGRESS ===== */
        .progress-wrap { display: flex; flex-direction: column; gap: 14px; }
        .progress-item { }
        .progress-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .progress-top span { font-size: .82rem; font-weight: 600; }
        .progress-top em { font-size: .75rem; color: var(--text-sub); font-style: normal; }
        .progress-bar { height: 8px; background: var(--border); border-radius: 4px; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 4px; transition: width .8s ease; }
        .pf-teal { background: var(--teal); }
        .pf-green { background: var(--green); }
        .pf-orange { background: var(--orange); }
        .pf-navy { background: var(--navy-soft); }

        /* ===== NOTIF ===== */
        .notif-list { display: flex; flex-direction: column; gap: 10px; }
        .notif-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            background: var(--off);
        }
        .notif-icon {
            width: 34px; height: 34px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: .85rem; flex-shrink: 0;
        }
        .ni-info { background: var(--teal-light); color: var(--teal-dark); }
        .ni-success { background: #DCFCE7; color: #16A34A; }
        .ni-warning { background: #FEFCE8; color: #A16207; }
        .notif-text { flex: 1; }
        .notif-text p { font-size: .82rem; font-weight: 500; }
        .notif-text small { color: var(--text-muted); font-size: .72rem; }

        /* ===== SKEMA GRID ===== */
        .skema-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; }
        .skema-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 18px 16px;
            text-align: center;
            transition: all .25s;
            cursor: default;
        }
        .skema-card:hover { border-color: var(--teal); transform: translateY(-2px); box-shadow: var(--shadow); }
        .skema-icon { font-size: 1.8rem; margin-bottom: 8px; }
        .skema-name { font-size: .78rem; font-weight: 700; color: var(--text-main); margin-bottom: 2px; }
        .skema-kode { font-size: .68rem; color: var(--teal-dark); font-weight: 600; margin-bottom: 10px; }
        .skema-nums { display: flex; gap: 4px; justify-content: center; font-size: .7rem; color: var(--text-sub); }
        .skema-nums strong { color: var(--text-main); }
        .skema-prog { height: 5px; background: var(--border); border-radius: 3px; overflow: hidden; margin-top: 8px; }
        .skema-prog-fill { height: 100%; background: var(--teal); border-radius: 3px; }

        /* ===== CALENDAR / TIMELINE ===== */
        .timeline { display: flex; flex-direction: column; gap: 14px; }
        .tl-item { display: flex; gap: 14px; align-items: flex-start; }
        .tl-date {
            min-width: 52px;
            text-align: center;
            background: var(--navy);
            color: var(--white);
            border-radius: 10px;
            padding: 8px 4px;
        }
        .tl-date .tl-d { font-size: 1.3rem; font-weight: 800; line-height: 1; }
        .tl-date .tl-m { font-size: .62rem; color: var(--teal); text-transform: uppercase; letter-spacing: .5px; }
        .tl-body { flex: 1; background: var(--off); border-radius: 10px; padding: 10px 14px; }
        .tl-body h4 { font-size: .85rem; font-weight: 700; margin-bottom: 4px; }
        .tl-meta { display: flex; gap: 14px; font-size: .75rem; color: var(--text-sub); flex-wrap: wrap; }
        .tl-meta span { display: flex; align-items: center; gap: 4px; }

        /* ===== SCORE BARS ===== */
        .score-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; border-bottom: 1px solid var(--off); }
        .score-item:last-child { border-bottom: none; }
        .score-rank { font-size: .75rem; color: var(--text-muted); font-family: 'DM Mono', monospace; width: 20px; }
        .score-name { flex: 1; font-size: .83rem; font-weight: 600; }
        .score-bar-wrap { width: 100px; height: 7px; background: var(--border); border-radius: 4px; overflow: hidden; }
        .score-bar-fill { height: 100%; background: var(--teal); border-radius: 4px; }
        .score-num { font-size: .8rem; font-family: 'DM Mono', monospace; font-weight: 500; color: var(--text-sub); width: 28px; text-align: right; }

        /* ===== FOOTER ===== */
        .page-footer {
            padding: 20px 32px;
            border-top: 1px solid var(--border);
            background: var(--white);
            display: flex; align-items: center; justify-content: space-between;
            font-size: .78rem; color: var(--text-muted);
            margin-top: auto;
        }
        .page-footer strong { color: var(--teal-dark); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
            .skema-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 900px) {
            



            .main { margin-left: 0; }
            .two-col, .three-col { grid-template-columns: 1fr; }
            .welcome-stats { display: none; }
        }

        /* ===== ANIMATIONS ===== */
        .stat-card, .card, .skema-card { animation: fadeUp .4s ease both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .stat-card:nth-child(1) { animation-delay: .05s; }
        .stat-card:nth-child(2) { animation-delay: .1s; }
        .stat-card:nth-child(3) { animation-delay: .15s; }
        .stat-card:nth-child(4) { animation-delay: .2s; }
        .stat-card:nth-child(5) { animation-delay: .25s; }
        .stat-card:nth-child(6) { animation-delay: .3s; }
    </style>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<aside class="sidebar">
<div class="sidebar-logo">
    <div class="logo-box" style="
        width: 50px; 
        height: 50px; 
        background: white; 
        border-radius: 12px; 
        padding: 5px; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    ">
        <img src="../images/lsplogosmkn1.png" style="width: 100%; height: 100%; object-fit: contain;">
    </div>
    <div class="logo-text">
        <strong>LSP</strong>
        <span>SMKN 1 CIBINONG</span>
    </div>
</div>

    <nav class="sidebar-nav">
        <a href="dashboardbaru.php" class="nav-item active">
            <i class="fas fa-gauge-high"></i> Dashboard
        </a>
        <div class="nav-label">Manajemen Data</div>
        <a href="inputskema.php" class="nav-item">
            <i class="fas fa-sitemap"></i> Kelola Skema
        </a>
        <a href="inputunit.php" class="nav-item">
            <i class="fas fa-cubes"></i> Kelola Unit
        </a>
        <a href="inputasesor.php" class="nav-item">
            <i class="fas fa-user-tie"></i> Kelola Asesor
        </a>
        <a href="inputpeserta.php" class="nav-item">
            <i class="fas fa-users"></i> Kelola Peserta
        </a>
        <a href="inputelemen.php" class="nav-item">
            <i class="fas fa-star"></i> Kelola Kompetensi
        </a>
        <a href="inputtempattuk.php" class="nav-item">
            <i class="fas fa-building"></i> Kelola Tempat TUK
        </a>

        <div class="nav-label">Input Data</div>
        <a href="inputsyarat.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Input Persyaratan
        </a>
        <a href="inputkumpan.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Input Umpan Balik
        </a>
        <a href="inputprosesasesmen.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Input Proses Asesmen
        </a>
        <a href="inputpengurus.php" class="nav-item">
            <i class="fas fa-pen-to-square"></i> Input Pengurus
        </a>

        <div class="nav-label">Proses Uji</div>
        <a href="mapa.php" class="nav-item">
            <i class="fas fa-paperclip"></i> MAPA
        </a>
        <a href="settanggal.php" class="nav-item">
            <i class="fas fa-clock"></i> SET Tanggal
        </a>
        <a href="pemetaanasesor.php" class="nav-item">
            <i class="fas fa-calendar-days"></i> Atur Jadwal
        </a>
        <a href="inputpraktek.php" class="nav-item">
            <i class="fas fa-clipboard-check"></i> FR.IA.01 Ceklist Observasi
        </a>
        <a href="inputtestulis.php" class="nav-item">
            <i class="fas fa-file-lines"></i> FR.IA.05 Tes Tertulis
        </a>

        <div class="nav-label">Validasi & Monitor</div>
        <a href="validasiapl1lsp.php" class="nav-item">
            <i class="fas fa-check-double"></i> Validasi APL1
        </a>
        <a href="monitorasesi.php" class="nav-item">
            <i class="fas fa-desktop"></i> Monitoring
        </a>
        <a href="backupdata.php" class="nav-item">
            <i class="fas fa-download"></i> Backup Data
        </a>

        <div class="nav-label">Akun</div>
        <a href="../logout.php" class="nav-item" style="color:rgba(239,68,68,.7)">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= strtoupper(substr($namax ?? 'AD', 0, 2)) ?></div>
            <div class="user-info">
                <strong><?= htmlspecialchars($namax ?? 'Admin') ?></strong>
                <span>LSP Admin</span>
            </div>
            <button class="btn-logout" title="Logout" onclick="window.location='../logout.php'">
                <i class="fas fa-right-from-bracket"></i>
            </button>
        </div>
    </div>
</aside>

<!-- ===== MAIN ===== -->
<div class="main">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="topbar-title">
            Dashboard
            <span>Selamat datang kembali, <?= htmlspecialchars(explode(' ', $user_name)[0]) ?>!</span>
        </div>
        <div class="topbar-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari peserta, jadwal...">
        </div>
        <div class="topbar-actions">
            <div class="date-chip">
                <i class="fas fa-calendar"></i>
                <?= $today ?>
            </div>
            <button class="icon-btn">
                <i class="fas fa-bell"></i>
                <span class="dot"></span>
            </button>
            <button class="icon-btn">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="content">

        <!-- WELCOME BANNER -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h2>👋 Halo, <?= htmlspecialchars(explode(' ', $user_name)[0]) ?>!</h2>
                <p>Hari ini, <strong><?= $today ?></strong>. Ada <strong><?= $stats['menunggu_verifikasi'] ?> dokumen</strong> menunggu verifikasi kamu.</p>
            </div>
            <div class="welcome-stats">
                <div class="w-stat">
                    <div class="num"><?= $stats['total_peserta'] ?></div>
                    <div class="lbl">Total Peserta</div>
                </div>
                <div class="w-stat-divider"></div>
                <div class="w-stat">
                    <div class="num"><?= $stats['uji_aktif'] ?></div>
                    <div class="lbl">Uji Aktif</div>
                </div>
                <div class="w-stat-divider"></div>
                <div class="w-stat">
                    <div class="num"><?= $stats['sertifikat_terbit'] ?></div>
                    <div class="lbl">Sertifikat Terbit</div>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon ic-teal"><i class="fas fa-users"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-arrow-up"></i>12%</span>
                </div>
                <div class="stat-num"><?= $stats['total_peserta'] ?></div>
                <div class="stat-label">Total Peserta Terdaftar</div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:78%"></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon ic-green"><i class="fas fa-award"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-arrow-up"></i>8%</span>
                </div>
                <div class="stat-num"><?= $stats['sertifikat_terbit'] ?></div>
                <div class="stat-label">Sertifikat Diterbitkan</div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:74%;background:#22C55E"></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon ic-orange"><i class="fas fa-hourglass-half"></i></div>
                    <span class="stat-trend trend-neu">Perlu Aksi</span>
                </div>
                <div class="stat-num"><?= $stats['menunggu_verifikasi'] ?></div>
                <div class="stat-label">Menunggu Verifikasi</div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:30%;background:#F97316"></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon ic-navy"><i class="fas fa-user-tie"></i></div>
                    <span class="stat-trend trend-neu">Aktif</span>
                </div>
                <div class="stat-num"><?= $stats['penguji_aktif'] ?></div>
                <div class="stat-label">Penguji Aktif</div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:60%;background:#3B82F6"></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon ic-green"><i class="fas fa-circle-check"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-arrow-up"></i>5%</span>
                </div>
                <div class="stat-num"><?= $stats['kompeten'] ?></div>
                <div class="stat-label">Peserta Kompeten</div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:<?= round($stats['kompeten']/max($stats['total_peserta'],1)*100) ?>%;background:#22C55E"></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon ic-red"><i class="fas fa-circle-xmark"></i></div>
                    <span class="stat-trend trend-down"><i class="fas fa-arrow-down"></i>3%</span>
                </div>
                <div class="stat-num"><?= $stats['belum_kompeten'] ?></div>
                <div class="stat-label">Belum Kompeten</div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:<?= round($stats['belum_kompeten']/max($stats['total_peserta'],1)*100) ?>%;background:#EF4444"></div></div>
            </div>
        </div>

        <!-- SKEMA SERTIFIKASI -->
        <div>
            <div class="section-head">
                <div>
                    <h3>Skema Sertifikasi</h3>
                    <p>Tingkat kelulusan per skema</p>
                </div>
                <a href="#" class="btn-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="skema-grid">
                <?php foreach ($skema_list as $sk):
                    $pct = round($sk['kompeten'] / $sk['total'] * 100);
                ?>
                <div class="skema-card">
                    <div class="skema-icon"><?= $sk['icon'] ?></div>
                    <div class="skema-name"><?= $sk['nama'] ?></div>
                    <div class="skema-kode"><?= $sk['kode'] ?></div>
                    <div class="skema-nums">
                        <strong><?= $sk['kompeten'] ?></strong>&nbsp;/ <?= $sk['total'] ?> peserta
                    </div>
                    <div class="skema-prog">
                        <div class="skema-prog-fill" style="width:<?= $pct ?>%"></div>
                    </div>
                    <div style="font-size:.7rem;color:var(--teal-dark);font-weight:700;margin-top:4px"><?= $pct ?>% kompeten</div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- JADWAL + NOTIFIKASI -->
        <div class="three-col">
            <!-- Jadwal Uji -->
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3>Jadwal Uji Kompetensi</h3>
                        <p>Periode Mei 2026</p>
                    </div>
                    <a href="#" class="btn-link">Lihat <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="timeline">
                    <?php foreach ($jadwal_upcoming as $j):
                        preg_match('/(\d+)\s+(\w+)\s+(\d+)/', $j['tanggal'], $m);
                        $hari = $m[1] ?? '--'; $bulan = substr($m[2] ?? 'Mei', 0, 3);
                    ?>
                    <div class="tl-item">
                        <div class="tl-date">
                            <div class="tl-d"><?= $hari ?></div>
                            <div class="tl-m"><?= strtoupper($bulan) ?></div>
                        </div>
                        <div class="tl-body">
                            <h4><?= htmlspecialchars($j['skema']) ?></h4>
                            <div class="tl-meta">
                                <span><i class="fas fa-users"></i> <?= $j['peserta'] ?> peserta</span>
                                <span><i class="fas fa-user-tie"></i> <?= htmlspecialchars($j['penguji']) ?></span>
                                <span>
                                    <?php if ($j['status'] === 'Terkonfirmasi'): ?>
                                        <span class="badge badge-green">✓ Terkonfirmasi</span>
                                    <?php else: ?>
                                        <span class="badge badge-orange">⏳ Menunggu</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Notifikasi -->
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3>Notifikasi</h3>
                        <p>Aktivitas terkini</p>
                    </div>
                    <a href="#" class="btn-link">Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="notif-list">
                    <?php foreach ($notifikasi as $n): ?>
                    <div class="notif-item">
                        <div class="notif-icon ni-<?= $n['tipe'] ?>">
                            <?php
                                $icons = ['info'=>'fa-circle-info','success'=>'fa-circle-check','warning'=>'fa-triangle-exclamation'];
                                echo '<i class="fas '.$icons[$n['tipe']].'"></i>';
                            ?>
                        </div>
                        <div class="notif-text">
                            <p><?= htmlspecialchars($n['pesan']) ?></p>
                            <small><?= $n['waktu'] ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Ringkasan kelulusan -->
                <div style="margin-top:20px">
                    <div class="section-head" style="margin-bottom:12px">
                        <h3 style="font-size:.88rem">Tingkat Kelulusan</h3>
                        <span style="font-size:.75rem;color:var(--text-sub)">Keseluruhan</span>
                    </div>
                    <div class="progress-wrap">
                        <div class="progress-item">
                            <div class="progress-top">
                                <span>TKJ</span>
                                <em>72/85 — 85%</em>
                            </div>
                            <div class="progress-bar"><div class="progress-fill pf-teal" style="width:85%"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-top">
                                <span>RPL</span>
                                <em>55/62 — 89%</em>
                            </div>
                            <div class="progress-bar"><div class="progress-fill pf-green" style="width:89%"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-top">
                                <span>Multimedia</span>
                                <em>36/45 — 80%</em>
                            </div>
                            <div class="progress-bar"><div class="progress-fill pf-orange" style="width:80%"></div></div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-top">
                                <span>Akuntansi</span>
                                <em>30/38 — 79%</em>
                            </div>
                            <div class="progress-bar"><div class="progress-fill pf-navy" style="width:79%"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL PESERTA TERKINI -->
        <div class="card">
            <div class="section-head">
                <div>
                    <h3>Peserta Terkini</h3>
                    <p>Hasil uji kompetensi terbaru</p>
                </div>
                <a href="#" class="btn-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            </div>
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Peserta</th>
                        <th>Skema</th>
                        <th>Tanggal Uji</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peserta_recent as $i => $p): ?>
                    <tr>
                        <td style="font-family:'DM Mono',monospace;color:var(--text-muted);font-size:.75rem"><?= str_pad($i+1, 2, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:32px;height:32px;border-radius:50%;background:var(--teal-light);color:var(--teal-dark);display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0">
                                    <?= strtoupper(substr($p['nama'], 0, 2)) ?>
                                </div>
                                <span style="font-weight:600"><?= htmlspecialchars($p['nama']) ?></span>
                            </div>
                        </td>
                        <td><span class="badge badge-teal"><?= htmlspecialchars($p['skema']) ?></span></td>
                        <td style="color:var(--text-sub)"><?= $p['tanggal'] ?></td>
                        <td style="font-family:'DM Mono',monospace;font-weight:600">
                            <?php if ($p['nilai'] !== '-'):
                                $warna = $p['nilai'] >= 75 ? '#16A34A' : '#DC2626';
                                echo "<span style='color:{$warna}'>{$p['nilai']}</span>";
                            else: echo '<span style="color:var(--text-muted)">—</span>';
                            endif; ?>
                        </td>
                        <td>
                            <?php
                            $badges = [
                                'Kompeten' => 'badge-green',
                                'Belum Kompeten' => 'badge-red',
                                'Menunggu Verifikasi' => 'badge-orange',
                            ];
                            $cls = $badges[$p['status']] ?? 'badge-gray';
                            echo "<span class='badge {$cls}'>{$p['status']}</span>";
                            ?>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <button style="border:1.5px solid var(--border);background:none;padding:5px 10px;border-radius:7px;font-size:.75rem;cursor:pointer;color:var(--text-sub);transition:all .2s" onmouseover="this.style.borderColor='var(--teal)';this.style.color='var(--teal-dark)'" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-sub)'">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- RINGKASAN + TOP NILAI -->
        <div class="two-col">
            <!-- Ringkasan Status -->
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3>Ringkasan Status Peserta</h3>
                        <p>Total: <?= $stats['total_peserta'] ?> peserta</p>
                    </div>
                </div>
                <!-- Donut-like visual via CSS -->
                <div style="display:flex;gap:20px;align-items:center;margin-bottom:20px">
                    <div style="position:relative;width:110px;height:110px;flex-shrink:0">
                        <svg viewBox="0 0 36 36" style="width:110px;height:110px;transform:rotate(-90deg)">
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="var(--border)" stroke-width="3.2"/>
                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#22C55E" stroke-width="3.2"
                                stroke-dasharray="<?= round($stats['kompeten']/max($stats['total_peserta'],1)*100) ?> 100" stroke-linecap="round"/>
                        </svg>
                        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center">
                            <span style="font-size:1.3rem;font-weight:800"><?= round($stats['kompeten']/max($stats['total_peserta'],1)*100) ?>%</span>
                            <span style="font-size:.62rem;color:var(--text-muted)">Kompeten</span>
                        </div>
                    </div>
                    <div style="flex:1;display:flex;flex-direction:column;gap:10px">
                        <?php
                        $breakdown = [
                            ['label'=>'Kompeten','val'=>$stats['kompeten'],'pct'=>round($stats['kompeten']/max($stats['total_peserta'],1)*100),'color'=>'#22C55E'],
                            ['label'=>'Belum Kompeten','val'=>$stats['belum_kompeten'],'pct'=>round($stats['belum_kompeten']/max($stats['total_peserta'],1)*100),'color'=>'#EF4444'],
                            ['label'=>'Menunggu Verifikasi','val'=>$stats['menunggu_verifikasi'],'pct'=>round($stats['menunggu_verifikasi']/max($stats['total_peserta'],1)*100),'color'=>'#F97316'],
                            ['label'=>'Dalam Proses','val'=>$stats['total_peserta']-$stats['kompeten']-$stats['belum_kompeten']-$stats['menunggu_verifikasi'],'pct'=>0,'color'=>'#94A3B8'],
                        ];
                        $breakdown[3]['pct'] = 100 - $breakdown[0]['pct'] - $breakdown[1]['pct'] - $breakdown[2]['pct'];
                        foreach ($breakdown as $b):
                        ?>
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:3px;font-size:.78rem">
                                <span style="display:flex;align-items:center;gap:6px">
                                    <span style="width:8px;height:8px;border-radius:50%;background:<?= $b['color'] ?>;display:inline-block"></span>
                                    <?= $b['label'] ?>
                                </span>
                                <strong><?= $b['val'] ?> (<?= $b['pct'] ?>%)</strong>
                            </div>
                            <div style="height:5px;background:var(--border);border-radius:3px;overflow:hidden">
                                <div style="height:100%;width:<?= $b['pct'] ?>%;background:<?= $b['color'] ?>;border-radius:3px"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <?php
                    $quick = [
                        ['label'=>'Uji Aktif','val'=>$stats['uji_aktif'],'icon'=>'fa-clipboard-list','color'=>'var(--teal)'],
                        ['label'=>'Jadwal Bulan Ini','val'=>$stats['jadwal_bulan_ini'],'icon'=>'fa-calendar','color'=>'#3B82F6'],
                    ];
                    foreach ($quick as $q): ?>
                    <div style="background:var(--off);border-radius:10px;padding:14px 16px;display:flex;align-items:center;gap:12px">
                        <i class="fas <?= $q['icon'] ?>" style="color:<?= $q['color'] ?>;font-size:1.1rem"></i>
                        <div>
                            <div style="font-size:1.2rem;font-weight:800"><?= $q['val'] ?></div>
                            <div style="font-size:.72rem;color:var(--text-sub)"><?= $q['label'] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Top Nilai -->
            <div class="card">
                <div class="section-head">
                    <div>
                        <h3>Top Nilai Tertinggi</h3>
                        <p>Peserta terbaik periode ini</p>
                    </div>
                </div>
                <?php
                $top = array_filter($peserta_recent, fn($p) => is_numeric($p['nilai']));
                usort($top, fn($a,$b) => $b['nilai'] <=> $a['nilai']);
                foreach (array_values($top) as $rank => $p): ?>
                <div class="score-item">
                    <div class="score-rank"><?= $rank+1 ?></div>
                    <div style="width:32px;height:32px;border-radius:50%;background:var(--teal-light);color:var(--teal-dark);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0">
                        <?= strtoupper(substr($p['nama'],0,2)) ?>
                    </div>
                    <div class="score-name"><?= htmlspecialchars($p['nama']) ?></div>
                    <div class="score-bar-wrap"><div class="score-bar-fill" style="width:<?= $p['nilai'] ?>%"></div></div>
                    <div class="score-num"><?= $p['nilai'] ?></div>
                </div>
                <?php endforeach; ?>

                <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border)">
                    <div style="font-size:.78rem;font-weight:700;color:var(--text-sub);margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px">Aksi Cepat</div>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <?php
                        $actions = [
                            ['icon'=>'fa-user-plus','label'=>'Tambah Peserta Baru','color'=>'var(--teal)'],
                            ['icon'=>'fa-calendar-plus','label'=>'Buat Jadwal Uji','color'=>'#3B82F6'],
                            ['icon'=>'fa-file-export','label'=>'Ekspor Laporan','color'=>'#8B5CF6'],
                        ];
                        foreach ($actions as $a): ?>
                        <button style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:9px;border:1.5px solid var(--border);background:none;cursor:pointer;font-size:.82rem;font-weight:600;color:var(--text-main);transition:all .2s;font-family:inherit;text-align:left"
                            onmouseover="this.style.borderColor='<?= $a['color'] ?>';this.style.background='var(--off)'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.background='none'">
                            <i class="fas <?= $a['icon'] ?>" style="color:<?= $a['color'] ?>;width:16px"></i>
                            <?= $a['label'] ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /content -->

    <!-- FOOTER -->
    <footer class="page-footer">
        <span>© <?= date('Y') ?> <strong>LSP SMKN 1 Cibinong</strong>. Semua hak dilindungi.</span>
        <span>Versi 1.0.0 &nbsp;·&nbsp; <?= $today ?>, <?= $current_time ?> WIB</span>
    </footer>
</div><!-- /main -->

<script>
// Animate progress bars on load
window.addEventListener('load', () => {
    document.querySelectorAll('.stat-bar-fill, .progress-fill, .skema-prog-fill, .score-bar-fill').forEach(el => {
        const w = el.style.width;
        el.style.width = '0';
        setTimeout(() => { el.style.width = w; }, 100);
    });
});
</script>
</body>
</html>