document.addEventListener('DOMContentLoaded', function () {
  var sectionMap = {
    'Beranda': 'beranda',
    'Tentang Kami': 'tentang',
    'Skema Sertifikasi': 'skema',
    'Prosedur': 'prosedur',
    'Jadwal': 'informasi',
    'Kontak': 'kontak'
  };

  var hero = document.querySelector('.hero');
  if (hero) hero.id = 'beranda';

  document.querySelectorAll('.section').forEach(function (section) {
    var text = section.textContent.replace(/\s+/g, ' ').trim().toLowerCase();
    if (text.includes('tentang kami')) section.id = 'tentang';
    if (text.includes('bidang kompetensi')) section.id = 'skema';
    if (text.includes('alur proses sertifikasi')) section.id = 'prosedur';
    if (text.includes('jadwal dan persyaratan')) section.id = 'informasi';
    if (text.includes('hubungi kami')) section.id = 'kontak';
  });

  function scrollToTarget(id) {
    var target = document.getElementById(id);
    if (!target) return;
    var offset = 110;
    var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({ top: top, behavior: 'smooth' });
  }

  document.querySelectorAll('.nav-links a, .footer-col a').forEach(function (link) {
    var label = link.textContent.trim();
    if (sectionMap[label]) {
      link.setAttribute('href', '#' + sectionMap[label]);
      link.addEventListener('click', function (event) {
        event.preventDefault();
        scrollToTarget(sectionMap[label]);
      });
    }
  });

  var daftarBtn = document.querySelector('.hero-actions .btn-primary');
  if (daftarBtn) {
    daftarBtn.setAttribute('href', 'lsp_login.php');
  }

  var panduanBtn = document.querySelector('.hero-actions .btn-outline');
  if (panduanBtn) {
    panduanBtn.setAttribute('href', '#prosedur');
    panduanBtn.addEventListener('click', function (event) {
      event.preventDefault();
      scrollToTarget('prosedur');
    });
  }

  var loginBtn = document.querySelector('.nav-btn');
  if (loginBtn) {
    loginBtn.setAttribute('href', 'lsp_login.php');
  }

  var contactButton = document.querySelector('.contact-form button');
  if (contactButton) {
    contactButton.setAttribute('type', 'button');
    contactButton.addEventListener('click', function () {
      var form = contactButton.closest('.contact-form');
      var inputs = form ? form.querySelectorAll('input, textarea') : [];
      var nama = inputs[0] ? inputs[0].value.trim() : '';
      var email = inputs[1] ? inputs[1].value.trim() : '';
      var subjek = inputs[2] ? inputs[2].value.trim() : '';
      var pesan = inputs[3] ? inputs[3].value.trim() : '';

      if (!nama || !email || !subjek || !pesan) {
        alert('Lengkapi nama, email, subjek, dan pesan terlebih dahulu.');
        return;
      }

      var body = 'Nama: ' + nama + '\nEmail: ' + email + '\n\n' + pesan;
      window.location.href = 'mailto:lsp@smkn1cibinong.sch.id?subject=' +
        encodeURIComponent(subjek) + '&body=' + encodeURIComponent(body);
    });
  }
});
