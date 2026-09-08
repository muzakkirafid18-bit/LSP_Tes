<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ujian Selesai - LSP</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
:root{--teal:#3BBFBF;--teal-dark:#2A9999;--navy:#0F2A3A;--off:#F4F8FA;--border:#DDE8ED;--text-main:#1A2E3B;--text-sub:#5A7384;--green:#22C55E;--radius:16px;--shadow:0 10px 30px rgba(15,42,58,.1)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',Arial,sans-serif;background:var(--off);color:var(--text-main);display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
.result-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);border:1px solid var(--border);width:100%;max-width:480px;text-align:center;padding:36px 28px;animation:fadeIn .4s ease both}
@keyframes fadeIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.icon-box{width:76px;height:76px;border-radius:50%;background:#DCFCE7;color:var(--green);display:flex;align-items:center;justify-content:center;font-size:2.2rem;margin:0 auto 20px}
.result-card h2{font-size:1.35rem;font-weight:800;color:var(--navy);margin-bottom:8px}
.result-card p{font-size:.88rem;color:var(--text-sub);line-height:1.6;margin-bottom:24px}
.btn-close-window{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:12px 24px;border-radius:12px;background:var(--teal);color:#fff;font-weight:800;font-size:.9rem;text-decoration:none;border:none;cursor:pointer;transition:all .2s}
.btn-close-window:hover{background:var(--teal-dark)}
</style>
</head>
<body>

<div class="result-card">
    <div class="icon-box"><i class="fas fa-circle-check"></i></div>
    <h2>Ujian Berhasil Diselesaikan!</h2>
    <p>Terima kasih, seluruh jawaban tes tertulis Anda telah tersimpan dengan aman ke dalam sistem LSP.</p>
    <button type="button" class="btn-close-window" onclick="window.close()">
        <i class="fas fa-xmark"></i> Tutup Halaman Ini
    </button>
</div>

</body>
</html>
