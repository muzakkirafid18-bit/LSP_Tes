<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root{
--primary:#0f766e;
--primary-dark:#0b5e57;
--primary-soft:#e6f7f5;
--primary-glow:rgba(15,118,110,.18);

--dark:#0f172a;
--dark-soft:#1e293b;

--text:#0f172a;
--text-light:#64748b;

--border:#e2e8f0;
--bg:#f8fafc;
--white:#ffffff;
}

*{
margin:0;
padding:0;
box-sizing:border-box;
scroll-behavior:smooth;
}

body{
font-family:'Plus Jakarta Sans',sans-serif;
background:var(--white);
color:var(--text);
}

.lp{
width:100%;
overflow-x:hidden;
background:#fff;
}

/* NAVBAR */
.nav{
position:fixed;
top:18px;
left:50%;
transform:translateX(-50%);
width:calc(100% - 60px);
max-width:1380px;
height:78px;
padding:0 34px;
display:flex;
align-items:center;
justify-content:space-between;
background:rgba(255,255,255,.72);
backdrop-filter:blur(18px);
border:1px solid rgba(255,255,255,.5);
border-radius:24px;
z-index:9999;
box-shadow:
0 10px 40px rgba(15,23,42,.08);
}

.nav-brand{
display:flex;
align-items:center;
gap:14px;
}

.nav-logo{
width:54px;
height:54px;
border-radius:18px;
overflow:hidden;
background:#fff;
display:flex;
align-items:center;
justify-content:center;
box-shadow:
0 8px 20px rgba(15,118,110,.14);
border:1px solid rgba(15,118,110,.08);
}

.nav-logo img{
width:100%;
height:100%;
object-fit:contain;
padding:6px;
}

.nav-name{
font-size:15px;
font-weight:800;
color:var(--dark);
line-height:1.2;
}

.nav-sub{
font-size:12px;
color:var(--text-light);
margin-top:2px;
}

.nav-links{
display:flex;
align-items:center;
gap:34px;
}

.nav-links a{
font-size:14px;
font-weight:600;
text-decoration:none;
color:#475569;
transition:.25s ease;
position:relative;
}

.nav-links a:hover{
color:var(--primary);
}

.nav-links a::after{
content:'';
position:absolute;
left:0;
bottom:-8px;
width:0;
height:2px;
background:var(--primary);
border-radius:10px;
transition:.25s ease;
}

.nav-links a:hover::after{
width:100%;
}

.nav-btn{
background:linear-gradient(
135deg,
var(--primary),
var(--primary-dark)
);
color:#fff;
padding:12px 24px;
border-radius:14px;
text-decoration:none;
font-size:14px;
font-weight:700;
box-shadow:
0 10px 24px rgba(15,118,110,.25);
transition:.25s ease;
}

.nav-btn:hover{
transform:translateY(-2px);
box-shadow:
0 14px 32px rgba(15,118,110,.34);
}

/* HERO */
.hero{
position:relative;
min-height:100vh;
overflow:hidden;
display:flex;
align-items:center;
padding:
140px 70px 110px;
}

.hero-bg{
position:absolute;
inset:0;
width:100%;
height:100%;
object-fit:cover;
filter:brightness(.72);
}

.hero-overlay{
position:absolute;
inset:0;
background:
linear-gradient(
135deg,
rgba(2,6,23,.82) 0%,
rgba(15,118,110,.58) 55%,
rgba(15,23,42,.68) 100%
);
}

.hero-content{
position:relative;
z-index:2;
max-width:760px;
}

.hero-badge{
display:inline-flex;
align-items:center;
gap:8px;
padding:10px 18px;
border-radius:999px;
background:rgba(255,255,255,.12);
backdrop-filter:blur(12px);
border:1px solid rgba(255,255,255,.18);
color:#fff;
font-size:12px;
font-weight:700;
letter-spacing:1px;
margin-bottom:28px;
}

.hero-content h1{
font-size:clamp(48px,7vw,78px);
font-weight:800;
line-height:1.08;
letter-spacing:-3px;
color:#fff;
margin-bottom:26px;
max-width:780px;

}

.hero-content p{
font-size:18px;
line-height:1.9;
color:rgba(255,255,255,.84);
max-width:690px;
margin-bottom:42px;
}

.hero-actions{
display:flex;
align-items:center;
gap:16px;
flex-wrap:wrap;
}

.btn-primary{
background:#fff;
color:var(--primary);
padding:16px 30px;
border-radius:18px;
font-size:14px;
font-weight:800;
text-decoration:none;
transition:.25s ease;
box-shadow:
0 14px 40px rgba(255,255,255,.14);
}

.btn-primary:hover{
transform:translateY(-3px);
}

.btn-outline{
border:1.5px solid rgba(255,255,255,.28);
background:rgba(255,255,255,.08);
backdrop-filter:blur(10px);
color:#fff;
padding:16px 30px;
border-radius:18px;
font-size:14px;
font-weight:700;
text-decoration:none;
transition:.25s ease;
}

.btn-outline:hover{
background:rgba(255,255,255,.14);
}

/* HERO STATS */
.hero-stats{
position:absolute;
left:50%;
bottom:-110px;
transform:translateX(-50%);
width:calc(100% - 140px);
max-width:1280px;

display:grid;
grid-template-columns:repeat(4,1fr);
gap:18px;

z-index:5;
}

.hero-stat{
background:rgba(255,255,255,.10);
backdrop-filter:blur(18px);
border:1px solid rgba(255,255,255,.12);
border-radius:26px;
padding:30px;
}

.hero-stat .num{
font-size:38px;
font-weight:800;
color:#fff;
line-height:1;
display:block;
}

.hero-stat .lbl{
margin-top:10px;
font-size:12px;
font-weight:700;
letter-spacing:1px;
text-transform:uppercase;
color:rgba(255,255,255,.72);
}
/* SECTION */
.section{
padding:110px 72px;
}

.section-alt{
background:linear-gradient(
180deg,
#f8fffe 0%,
#f5f7fb 100%
);
}

.section-label{
display:inline-block;
padding:8px 16px;
border-radius:999px;
background:var(--primary-soft);
color:var(--primary);
font-size:11px;
font-weight:800;
letter-spacing:1.5px;
text-transform:uppercase;
margin-bottom:18px;
}

.section-title{
font-size:46px;
font-weight:800;
line-height:1.15;
letter-spacing:-2px;
color:var(--dark);
margin-bottom:18px;
max-width:760px;
}

.section-desc{
font-size:16px;
line-height:1.9;
color:var(--text-light);
max-width:700px;
}

/* ABOUT */
.about-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:70px;
align-items:center;
}

.about-list{
list-style:none;
margin-top:34px;
display:flex;
flex-direction:column;
gap:18px;
}

.about-list li{
position:relative;
padding-left:24px;
font-size:15px;
line-height:1.8;
color:#334155;
}

.about-list li::before{
content:'';
position:absolute;
left:0;
top:11px;
width:10px;
height:10px;
border-radius:50%;
background:linear-gradient(
135deg,
var(--primary),
#14b8a6
);
box-shadow:
0 0 0 6px rgba(20,184,166,.12);
}

/* MODERN ABOUT IMAGE */
.about-img-wrap{
position:relative;
}

.about-modern-img{
position:relative;
width:100%;
height:460px;
border-radius:34px;
overflow:hidden;
box-shadow:
0 25px 70px rgba(15,23,42,.16);
}

.about-modern-img img{
width:100%;
height:100%;
object-fit:cover;
transition:transform .7s ease;
}

.about-modern-img:hover img{
transform:scale(1.06);
}

.about-overlay{
position:absolute;
inset:0;
background:
linear-gradient(
180deg,
rgba(15,23,42,.02) 0%,
rgba(15,23,42,.58) 100%
);
}

.about-floating-card{
position:absolute;
left:28px;
bottom:28px;
background:rgba(255,255,255,.12);
backdrop-filter:blur(18px);
border:1px solid rgba(255,255,255,.16);
padding:20px 24px;
border-radius:24px;
box-shadow:
0 10px 30px rgba(0,0,0,.18);
}

.about-year{
font-size:38px;
font-weight:800;
color:#fff;
line-height:1;
}

.about-label{
font-size:13px;
color:rgba(255,255,255,.82);
margin-top:4px;
}

/* SKEMA */
.skema-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:28px;
margin-top:50px;
}

.skema-card{
background:#fff;
border:1px solid rgba(15,118,110,.08);
border-radius:28px;
padding:30px;
transition:.3s ease;
box-shadow:
0 8px 30px rgba(15,23,42,.04);
}

.skema-card:hover{
transform:translateY(-8px);
border-color:rgba(15,118,110,.24);
box-shadow:
0 18px 50px rgba(15,118,110,.12);
}

.skema-icon{
width:62px;
height:62px;
border-radius:20px;
background:var(--primary-soft);
display:flex;
align-items:center;
justify-content:center;
margin-bottom:22px;
}

.skema-icon svg{
width:28px;
height:28px;
stroke:var(--primary);
stroke-width:1.8;
fill:none;
}

.skema-card h3{
font-size:18px;
font-weight:800;
line-height:1.4;
margin-bottom:10px;
color:var(--dark);
}

.skema-card p{
font-size:14px;
line-height:1.8;
color:#64748b;
}

.skema-code{
display:inline-flex;
align-items:center;
justify-content:center;
margin-top:18px;
padding:8px 14px;
border-radius:999px;
background:var(--primary-soft);
color:var(--primary);
font-size:12px;
font-weight:800;
letter-spacing:.8px;
}
/* STEPS */
.steps{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:28px;
margin-top:60px;
}

.step{
background:#fff;
border:1px solid rgba(15,118,110,.08);
border-radius:28px;
padding:34px 28px;
position:relative;
transition:.3s ease;
box-shadow:
0 8px 30px rgba(15,23,42,.04);
}

.step:hover{
transform:translateY(-8px);
box-shadow:
0 18px 50px rgba(15,118,110,.12);
}

.step-num{
width:58px;
height:58px;
border-radius:18px;
background:linear-gradient(
135deg,
var(--primary),
var(--primary-dark)
);
display:flex;
align-items:center;
justify-content:center;
font-size:22px;
font-weight:800;
color:#fff;
margin-bottom:22px;
box-shadow:
0 10px 24px rgba(15,118,110,.25);
}

.step h4{
font-size:18px;
font-weight:800;
margin-bottom:12px;
color:var(--dark);
}

.step p{
font-size:14px;
line-height:1.8;
color:#64748b;
}

/* INFO */
.info-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:32px;
margin-top:50px;
}

.info-card{
background:#fff;
border-radius:30px;
padding:34px;
border:1px solid rgba(15,118,110,.08);
box-shadow:
0 10px 40px rgba(15,23,42,.05);
}

.info-card h3{
font-size:20px;
font-weight:800;
margin-bottom:28px;
color:var(--dark);
}

.info-row{
display:flex;
justify-content:space-between;
gap:20px;
padding:16px 0;
border-bottom:1px solid #eef2f7;
}

.info-row:last-child{
border-bottom:none;
}

.info-row .label{
font-size:14px;
color:#64748b;
}

.info-row .value{
font-size:14px;
font-weight:700;
color:#0f172a;
text-align:right;
}

.badge-aktif{
display:inline-flex;
align-items:center;
padding:5px 10px;
border-radius:999px;
background:#dcfce7;
color:#166534;
font-size:11px;
font-weight:800;
}

.badge-info{
display:inline-flex;
align-items:center;
padding:5px 10px;
border-radius:999px;
background:#dbeafe;
color:#1d4ed8;
font-size:11px;
font-weight:800;
}
/* CONTACT */

.contact-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:70px;
}

.contact-item{
display:flex;
gap:16px;
margin-bottom:28px;
}

.contact-icon{
width:52px;
height:52px;
border-radius:18px;
background:var(--primary-soft);
display:flex;
align-items:center;
justify-content:center;
flex-shrink:0;
}

.contact-icon svg{
width:22px;
height:22px;
stroke:var(--primary);
stroke-width:1.8;
fill:none;
}

.contact-label{
font-size:11px;
font-weight:800;
letter-spacing:1.5px;
text-transform:uppercase;
color:#94a3b8;
margin-bottom:6px;
}

.contact-val{
font-size:15px;
line-height:1.7;
color:#0f172a;
}

.contact-form{
background:#fff;
border:1px solid rgba(15,118,110,.08);
border-radius:32px;
padding:34px;
box-shadow:
0 10px 40px rgba(15,23,42,.05);
}

.contact-form input,
.contact-form textarea{
width:100%;
padding:16px 18px;
border-radius:18px;
border:1px solid #e2e8f0;
outline:none;
font-family:inherit;
font-size:14px;
margin-bottom:16px;
transition:.2s ease;
}

.contact-form input:focus,
.contact-form textarea:focus{
border-color:var(--primary);
box-shadow:
0 0 0 4px rgba(20,184,166,.12);
}

.contact-form textarea{
height:130px;
resize:none;
}

.contact-form button{
background:linear-gradient(
135deg,
var(--primary),
var(--primary-dark)
);
border:none;
padding:15px 26px;
border-radius:16px;
font-size:14px;
font-weight:800;
color:#fff;
cursor:pointer;
box-shadow:
0 10px 26px rgba(15,118,110,.22);
}

/* FOOTER */
.footer{
background:
linear-gradient(
135deg,
#082f2d 0%,
#0f172a 100%
);
padding:80px 72px 40px;
}

.footer-grid{
display:grid;
grid-template-columns:2fr 1fr 1fr;
gap:60px;
margin-bottom:50px;
}

.footer-logo-box{
width:52px;
height:52px;
border-radius:18px;
background:rgba(255,255,255,.08);
display:flex;
align-items:center;
justify-content:center;
border:1px solid rgba(255,255,255,.08);
}

.footer-logo-box span{
color:#fff;
font-size:11px;
font-weight:800;
line-height:1.2;
}

.footer-brand-wrap{
display:flex;
align-items:center;
gap:14px;
}

.footer-logo-name{
font-size:16px;
font-weight:800;
color:#fff;
}

.footer-logo-sub{
font-size:12px;
color:rgba(255,255,255,.5);
margin-top:3px;
}

.footer-brand p{
margin-top:18px;
font-size:14px;
line-height:1.9;
color:rgba(255,255,255,.58);
max-width:360px;
}

.footer-col h4{
font-size:12px;
font-weight:800;
letter-spacing:1.5px;
text-transform:uppercase;
color:rgba(255,255,255,.35);
margin-bottom:20px;
}

.footer-col a{
display:block;
margin-bottom:14px;
font-size:14px;
color:rgba(255,255,255,.65);
text-decoration:none;
transition:.2s ease;
}

.footer-col a:hover{
color:#fff;
transform:translateX(3px);
}

.footer-bottom{
padding-top:28px;
border-top:1px solid rgba(255,255,255,.08);
display:flex;
justify-content:space-between;
align-items:center;
}

.footer-bottom p{
font-size:13px;
color:rgba(255,255,255,.45);
}

/* RESPONSIVE */
@media(max-width:768px){

.nav{
width:calc(100% - 20px);
padding:0 16px;
height:74px;
top:12px;
}

.nav-links{
display:none;
}

.hero{
padding:
150px 24px 120px;
min-height:auto;
}

.hero-content{
max-width:100%;
padding-top:30px;
position:relative;
z-index:3;
max-width:760px;

}

.hero-content h1{
font-size:48px;
line-height:1.1;
letter-spacing:-2px;
}

.hero-content p{
font-size:15px;
line-height:1.8;
}

.hero-actions{
flex-direction:column;
align-items:flex-start;
width:100%;
}

.btn-primary,
.btn-outline{
width:100%;
text-align:center;
justify-content:center;
}

.hero-stats{
position:relative;
left:auto;
bottom:auto;
transform:none;
width:100%;
margin-top:48px;
grid-template-columns:1fr;
}

.about-grid,
.skema-grid,
.contact-grid,
.footer-grid,
.info-grid,
.steps{
grid-template-columns:1fr;
}

.section{
padding:80px 24px;
}

.section-title{
font-size:34px;
line-height:1.2;
}

.footer-bottom{
flex-direction:column;
gap:10px;
text-align:center;
}

}
</style>
<div class="lp">

<!-- NAV -->
<nav class="nav">
  <div class="nav-brand">
<div class="nav-logo">
  <img src="../images/lsplogosmkn1.png" alt="Logo LSP">
</div>
    <div>
      <div class="nav-name">LSP SMKN 1 Cibinong</div>
      <div class="nav-sub">Lembaga Sertifikasi Profesi</div>
    </div>
  </div>
  <div class="nav-links">
    <a href="#">Beranda</a>
    <a href="#">Tentang Kami</a>
    <a href="#">Skema Sertifikasi</a>
    <a href="#">Prosedur</a>
    <a href="#">Kontak</a>
  </div>
<a href="../lsp_login.php" class="nav-btn">Login</a></nav>

<!-- HERO -->
<div class="hero">
  <!-- School building illustration as hero -->
<img src="../images/gedungbnsp.jpg" class="hero-bg" alt="Background SMKN 1 Cibinong">
  <div class="hero-overlay"></div>
    <div class="hero-content">
      <div class="hero-badge">Terakreditasi BNSP</div>
      <h1>Lembaga Sertifikasi Profesi<br>SMKN 1 Cibinong</h1>
      <p>Menerbitkan sertifikasi kompetensi kerja yang diakui secara nasional sesuai standar BNSP untuk mempersiapkan tenaga kerja Indonesia yang kompeten dan berdaya saing.</p>
      <div class="hero-actions">
        <a href="#" class="btn-primary">Daftar Sertifikasi</a>
        <a href="#" class="btn-outline">Unduh Panduan</a>
      </div>
    </div>
  </div>
  <!-- <div class="hero-stats">
    <div class="hero-stat"><span class="num">1.240+</span><span class="lbl">Peserta Tersertifikasi</span></div>
    <div class="hero-stat"><span class="num">12</span><span class="lbl">Skema Aktif</span></div>
    <div class="hero-stat"><span class="num">28</span><span class="lbl">Asesor Berlisensi</span></div>
    <div class="hero-stat"><span class="num">94%</span><span class="lbl">Tingkat Kelulusan</span></div>
  </div> -->
</div>

<!-- ABOUT -->
<div class="section">
  <div class="about-grid">
    <div>
      <div class="section-label">Tentang Kami</div>
      <h2 class="section-title">Lembaga Sertifikasi Profesi yang Terpercaya</h2>
      <div class="divider"></div>
      <p class="section-desc">LSP SMKN 1 Cibinong merupakan lembaga sertifikasi profesi yang telah mendapatkan lisensi dari Badan Nasional Sertifikasi Profesi (BNSP). Kami berkomitmen untuk menghasilkan tenaga kerja yang kompeten dan terstandarisasi secara nasional.</p>
      <ul class="about-list">
        <li>Berlisensi resmi dari BNSP dengan nomor lisensi terverifikasi</li>
        <li>Didukung asesor berpengalaman dan bersertifikat nasional</li>
        <li>Menggunakan Tempat Uji Kompetensi (TUK) yang telah terverifikasi</li>
        <li>Sistem penilaian yang transparan, objektif, dan terstandarisasi</li>
        <li>Sertifikat diakui oleh industri dan instansi pemerintah</li>
      </ul>
    </div>
<div class="about-img-wrap">

  <div class="about-modern-img">
    <img src="../images/back2.jpg" alt="SMKN 1 Cibinong">
    
    <div class="about-overlay"></div>

    <div class="about-floating-card">
      <span class="about-year">2019</span>
      <span class="about-label">Tahun Berdiri</span>
    </div>

  </div>

</div>
    </div>
  </div>
</div>

<!-- SKEMA -->
<div class="section section-alt">
  <div style="text-align:center;margin-bottom:8px">
    <div class="section-label" style="justify-content:center;display:flex">Skema Sertifikasi</div>
    <h2 class="section-title" style="text-align:center;margin:0 auto 18px">Bidang Kompetensi yang Kami Sertifikasi</h2>
    <p class="section-desc" style="margin:0 auto;text-align:center">Kami menawarkan berbagai skema sertifikasi kompetensi yang relevan dengan kebutuhan industri saat ini.</p>
  </div>
  <div class="skema-grid">
    <div class="skema-card">
      <div class="skema-icon"><svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><polyline points="8,21 12,17 16,21"/><line x1="12" y1="17" x2="12" y2="3"/></svg></div>
      <h3>Teknik Komputer dan Jaringan</h3>
      <p>Meliputi instalasi, konfigurasi, dan pemeliharaan jaringan komputer serta perangkat keras.</p>
      <span class="skema-code">TKJ</span>
    </div>
    <div class="skema-card">
      <div class="skema-icon"><svg viewBox="0 0 24 24"><polyline points="16,18 22,12 16,6"/><polyline points="8,6 2,12 8,18"/></svg></div>
      <h3>Rekayasa Perangkat Lunak</h3>
      <p>Pengembangan aplikasi, pemrograman, dan pengujian perangkat lunak berbasis standar industri.</p>
      <span class="skema-code">RPL</span>
    </div>
    <div class="skema-card">
      <div class="skema-icon"><svg viewBox="0 0 24 24"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg></div>
      <h3>Multimedia</h3>
      <p>Desain grafis, produksi video, animasi, dan konten digital kreatif untuk kebutuhan industri.</p>
      <span class="skema-code">MM</span>
    </div>
    <div class="skema-card">
      <div class="skema-icon"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
      <h3>Akuntansi dan Keuangan</h3>
      <p>Pembukuan, laporan keuangan, perpajakan, dan administrasi keuangan perusahaan.</p>
      <span class="skema-code">AKL</span>
    </div>
    <div class="skema-card">
      <div class="skema-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M4.93 4.93a10 10 0 0 0 0 14.14"/></svg></div>
      <h3>Teknik Otomotif</h3>
      <p>Perawatan, perbaikan, dan diagnosis kendaraan bermotor roda dua dan roda empat.</p>
      <span class="skema-code">OTO</span>
    </div>
    <div class="skema-card">
      <div class="skema-icon"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <h3>Bisnis dan Pemasaran</h3>
      <p>Strategi pemasaran, manajemen bisnis, dan layanan pelanggan di era digital.</p>
      <span class="skema-code">BDP</span>
    </div>
  </div>
</div>

<!-- PROSEDUR -->
<div class="section">
  <div style="text-align:center">
    <div class="section-label" style="display:flex;justify-content:center">Prosedur</div>
    <h2 class="section-title" style="text-align:center;margin:0 auto 18px">Alur Proses Sertifikasi</h2>
    <p class="section-desc" style="margin:0 auto;text-align:center">Proses sertifikasi dirancang untuk memastikan setiap peserta mendapatkan penilaian yang adil, objektif, dan sesuai standar BNSP.</p>
  </div>
  <div class="steps">
    <div class="step">
      <div class="step-num">1</div>
      <h4>Pendaftaran</h4>
      <p>Mengisi formulir pendaftaran APL-01 dan melengkapi persyaratan dokumen yang diperlukan.</p>
    </div>
    <div class="step">
      <div class="step-num">2</div>
      <h4>Asesmen Mandiri</h4>
      <p>Peserta mengisi formulir APL-02 untuk menilai kemampuan diri terhadap unit kompetensi yang dipilih.</p>
    </div>
    <div class="step">
      <div class="step-num">3</div>
      <h4>Uji Kompetensi</h4>
      <p>Pelaksanaan uji kompetensi oleh asesor bersertifikat melalui observasi, tes tulis, dan portofolio.</p>
    </div>
    <div class="step">
      <div class="step-num">4</div>
      <h4>Penerbitan Sertifikat</h4>
      <p>Peserta yang dinyatakan kompeten menerima sertifikat yang diakui secara nasional oleh BNSP.</p>
    </div>
  </div>
</div>

<!-- INFO -->
<div class="section section-alt">
  <div class="section-label">Informasi</div>
  <h2 class="section-title">Jadwal dan Persyaratan</h2>
  <div class="divider"></div>
  <div class="info-grid">
    <div class="info-card">
      <h3>Persyaratan Pendaftaran</h3>
      <div class="info-row"><span class="label">Pendidikan minimum</span><span class="value">SMK / Sederajat</span></div>
      <div class="info-row"><span class="label">Dokumen identitas</span><span class="value">KTP / Kartu Pelajar</span></div>
      <div class="info-row"><span class="label">Pas foto</span><span class="value">3×4 latar merah (2 lembar)</span></div>
      <div class="info-row"><span class="label">Ijazah / SKL</span><span class="value">Fotokopi terlegalisir</span></div>
      <div class="info-row"><span class="label">Biaya pendaftaran</span><span class="value">Gratis (siswa aktif)</span></div>
    </div>
    <div class="info-card">
      <h3>Jadwal Pelaksanaan 2026</h3>
      <div class="info-row"><span class="label">Periode I</span><span class="value"><span class="badge-aktif">Buka</span> &nbsp; Mar – Apr 2026</span></div>
      <div class="info-row"><span class="label">Periode II</span><span class="value"><span class="badge-info">Segera</span> &nbsp; Jun – Jul 2026</span></div>
      <div class="info-row"><span class="label">Periode III</span><span class="value">Sep – Okt 2026</span></div>
      <div class="info-row"><span class="label">Periode IV</span><span class="value">Nov – Des 2026</span></div>
      <div class="info-row"><span class="label">Batas pendaftaran</span><span class="value">H-14 sebelum pelaksanaan</span></div>
    </div>
  </div>
</div>

<!-- CONTACT -->
<div class="section">
  <div class="contact-grid">
    <div>
      <div class="section-label">Kontak</div>
      <h2 class="section-title">Hubungi Kami</h2>
      <div class="divider"></div>
      <div class="contact-item">
        <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
        <div>
          <div class="contact-label">Alamat</div>
          <div class="contact-val">Jl. Baru Salabenda No.04, Cibinong,<br>Kabupaten Bogor, Jawa Barat 16912</div>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.86-.86a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
        <div>
          <div class="contact-label">Telepon</div>
          <div class="contact-val">(0251) 8654-321</div>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon"><svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
        <div>
          <div class="contact-label">Email</div>
          <div class="contact-val">lsp@smkn1cibinong.sch.id</div>
        </div>
      </div>
      <div class="contact-item">
        <div class="contact-icon"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12,6 12,12 16,14"/></svg></div>
        <div>
          <div class="contact-label">Jam Operasional</div>
          <div class="contact-val">Senin – Jumat, 07.30 – 15.30 WIB</div>
        </div>
      </div>
    </div>
    <div>
      <div style="background:#f8f8f6;border-radius:4px;padding:32px">
        <h3 style="font-size:15px;font-weight:600;color:#111;margin-bottom:20px">Kirim Pesan</h3>
        <div class="contact-form">
          <input type="text" placeholder="Nama lengkap">
          <input type="email" placeholder="Alamat email">
          <input type="text" placeholder="Subjek">
          <textarea placeholder="Tulis pesan Anda di sini..."></textarea>
          <button>Kirim Pesan</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-grid">
    <div>
      <div class="footer-brand-wrap">
        <div class="footer-logo-box"><span>LSP<br>SMK1</span></div>
        <div>
          <div class="footer-logo-name">LSP SMKN 1 Cibinong</div>
          <div class="footer-logo-sub">Lembaga Sertifikasi Profesi</div>
        </div>
      </div>
      <p style="font-size:13px;color:rgba(255,255,255,0.45);line-height:1.7;margin-top:16px;max-width:260px">Mencetak tenaga kerja kompeten dan bersertifikat yang siap bersaing di tingkat nasional maupun internasional.</p>
      <div style="margin-top:20px;display:flex;gap:10px">
        <div style="width:32px;height:32px;background:rgba(255,255,255,0.08);border-radius:4px;display:flex;align-items:center;justify-content:center;cursor:pointer">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </div>
        <div style="width:32px;height:32px;background:rgba(255,255,255,0.08);border-radius:4px;display:flex;align-items:center;justify-content:center;cursor:pointer">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
        </div>
        <div style="width:32px;height:32px;background:rgba(255,255,255,0.08);border-radius:4px;display:flex;align-items:center;justify-content:center;cursor:pointer">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="2"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.54C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75,15.02 15.5,12 9.75,8.98 9.75,15.02"/></svg>
        </div>
      </div>
    </div>
    <div class="footer-col">
      <h4>Navigasi</h4>
      <a href="#">Beranda</a>
      <a href="#">Tentang Kami</a>
      <a href="#">Skema Sertifikasi</a>
      <a href="#">Prosedur</a>
      <a href="#">Jadwal</a>
      <a href="#">Kontak</a>
    </div>
    <div class="footer-col">
      <h4>Layanan</h4>
      <a href="#">Portal Peserta</a>
      <a href="#">Portal Asesor</a>
      <a href="#">Unduh Formulir</a>
      <a href="#">Verifikasi Sertifikat</a>
      <a href="#">Hubungi Kami</a>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2026 LSP SMKN 1 Cibinong. Lisensi BNSP. Seluruh hak dilindungi.</p>
    <p>Kebijakan Privasi &nbsp;·&nbsp; Syarat Layanan</p>
  </div>
</footer>

</div>
