<?php
switch($_GET['p']){
default:
?>		
		<?php
		$cek_slidehome=mysql_query("SELECT * FROM sh_berita WHERE status_terbit='1' AND status_headline='1'");
		$hasil_slide=mysql_num_rows($cek_slidehome);
		
		if($hasil_slide > 0){
		?>
		<div id="headlineFrame">
			<div id="headline">
			<div id="slides">
				<div class="slides_container">
					<?php
					$slide_berita=mysql_query("SELECT * FROM sh_berita, sh_users WHERE sh_berita.s_username=sh_users.s_username AND status_terbit='1' AND status_headline='1' LIMIT 6");
					while ($sb=mysql_fetch_array($slide_berita)){
					$isi_berita = strip_tags($sb[isi_berita]); 
					$isi = substr($isi_berita,0,200);
					?>
					<div class="slide">
					<a href="<?php echo "?p=detberita&id=$sb[id_berita]"; ?>" title=""><img src="images/<?php echo $sb[gambar_kecil]?>" width="630" height="280" alt="Slide 1"></a>
						<div class="caption" style="bottom:0">
							<p><b style="font-size: 16px"><?php echo $sb[judul_berita]?></b><br>
							<small>Tanggal: <?php echo $sb[tanggal_posting]?>, Oleh: <?php echo $sb[nama_lengkap_users]?></small><br>
							<?php echo $isi ?></p>
							<br><a href="<?php echo "?p=detberita&id=$sb[id_berita]"; ?>">Baca selengkapnya...</a>
						</div>
					</div>
					<?php } ?>
					
					<!--<a href="" title="" target="_blank"><img src="images/1.jpg" width="930" height="280" alt="Slide 1"></a>
					<a href="" title="" target="_blank"><img src="images/2.jpg" width="930" height="280" alt="Slide 1"></a>
					<a href="" title="" target="_blank"><img src="images/3.jpg" width="930" height="280" alt="Slide 1"></a>-->
				</div>
				<a href="#" class="prev"><img src="file/tema/edisi_spesial_hijau/img/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
				<a href="#" class="next"><img src="file/tema/edisi_spesial_hijau/img/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>
			</div>
			</div>
		</div>
		<?php } ?>
	
		<div id="konten">
			<div id="lebar">
			<?php
			$sambutan=mysql_query("SELECT * FROM sh_info_sekolah WHERE id_info='1'");
			$sm=mysql_fetch_array($sambutan);
			?>
			<h4><?php echo "$sm[nama_info]";?></h4><br>
			<p><?php echo "$sm[isi_info]";?></p>
			</div>
			
			
			      <!-- tabs -->
		<ul class="tabs">
			<li><a href="#berita">Berita Terbaru</a></li>
			<li><a href="#kategori">Kategori Berita</a></li>
			<li><a href="#komentar">Komentar Terbaru</a></li>
			<li><a href="#agenda">Agenda Terbaru</a></li>
		</ul>

		<div id="lebarTab">
				<div id="berita" class="tab_content">
					<ul>
					<?php
					$berita=mysql_query("SELECT * FROM sh_berita, sh_kategori, sh_users WHERE 
					sh_berita.id_kategori=sh_kategori.id_kategori AND sh_berita.s_username=sh_users.s_username AND
					status_terbit='1' ORDER BY id_berita DESC LIMIT 2");
					$hitungberita=mysql_num_rows($berita);
					
					if($hitungberita > 0){
					while($b=mysql_fetch_array($berita)){
					?>
						<li><a href="<?php echo "?p=detberita&id=$b[id_berita]";?>"><?php echo "<b>$b[judul_berita]</b>";?></a>
						<br><small>Kategori: <a href="?p=katberita&id=<?php echo $b[id_kategori]?>"><?php echo $b[nama_kategori]?></a>
						&nbsp;Komentar : 
						<?php
						$hitungkomentar=mysql_query("SELECT * FROM sh_komentar WHERE id_berita='$b[id_berita]'");
						$jumlahkomentar=mysql_num_rows($hitungkomentar);
						echo $jumlahkomentar?></small>
						<?php
						$isi_berita = strip_tags($b[isi_berita]); 
						$isi = substr($isi_berita,0,500);
						if ($b[gambar_kecil] != 'no_image.jpg'){
						echo "<p><img src='images/$b[gambar_kecil]' width='100px' style='float:left; margin: 5px 10px 0 0; padding: 3px; background: #fff; border: 1px solid #dcdcdc'>$isi...<a href='?p=detberita&id=$b[id_berita]'>Baca selengkapnya...</a></p><br>";}
						else {
						echo "<p>$isi...<a href='?p=detberita&id=$b[id_berita]'>Baca selengkapnya...</a></p>";
						}
						?>
						</li>
					<?php }}

					else {?>
					<li><a href=""><b>Data berita belum ada</b></a></li>
					<?php } ?>
					</ul>
				</div>
				
				<div id="kategori" class="tab_content">
					<ul>
					<?php
					$kategori_berita=mysql_query("SELECT * FROM sh_kategori ORDER BY id_kategori ASC");
					$hitungkategori=mysql_num_rows($kategori_berita);
					
					if($hitungkategori > 0){
					while($k=mysql_fetch_array($kategori_berita)){
					?>
						<li><a href="<?php echo "?p=katberita&id=$k[id_kategori]";?>"><?php echo "<b>$k[nama_kategori]</b>";?>
						<?php
						$jumlah_berita=mysql_query("SELECT * FROM sh_berita WHERE status_terbit='1' AND id_kategori='$k[id_kategori]'");
						$jml_ber=mysql_num_rows($jumlah_berita);
						echo "($jml_ber)";
						?>
						</a></li>
					<?php }}

					else {?>
					<li><a href=""><b>Data katgeori belum ada</b></a></li>
					<?php } ?>
					</ul>
				</div>
				
				<div id="komentar" class="tab_content">
				    <ul>
					<?php
					$komentar=mysql_query("SELECT * FROM sh_komentar, sh_berita WHERE sh_komentar.id_berita=sh_berita.id_berita AND status_terima ='1' AND status_terbit='1' ORDER BY id_komentar DESC limit 4");
					$hitungkomentar=mysql_num_rows($komentar);
					
					if($hitungkomentar > 0){
					while($kom=mysql_fetch_array($komentar)){
					?>
						<li><a href=""><?php echo "$kom[nama_komentar]";?></a> pada <a href="<?php echo "?p=detberita&id=$kom[id_berita]";?>"><?php echo "$kom[judul_berita]";?></a><br>
						<b><?php echo "$kom[tanggal_komentar]";?></b><br>
						<i><?php echo "$kom[isi_komentar]";?></i></li>
					<?php }}
					
					else {?>
					<li><a href=""><b>Data komentar belum ada</b></a></li>
					<?php } ?>
					</ul>
				</div>
				
				<div id="agenda" class="tab_content">
					<ul>
					<?php
					$agenda=mysql_query("SELECT * FROM sh_agenda ORDER BY id_agenda DESC limit 4");
					$hitungagenda=mysql_num_rows($agenda);
					
					if($hitungagenda > 0){
					while($ag=mysql_fetch_array($agenda)){
					?>
						<li><b><?php echo "<a href=''>$ag[judul_agenda]</a>";?></b> pada tanggal <b><?php echo "$ag[tanggal_agenda]";?></b><br>
							<b>Tempat : </b><?php echo "$ag[tempat_agenda]";?><br>
							<b>Keterangan : </b><?php echo "$ag[keterangan_agenda]";?> </li>
					<?php }}
					else {?>
					<li><b><a href="">Data agenda belum ada</a></b></li>
					<?php } ?>
					</ul>
				</div>
		</div>
		<div class="clear"></div>
		
			<div id="kecil">
			<h3>Galeri Terbaru</h3>
			<?php
			$poto=mysql_query("SELECT * FROM sh_galeri ORDER BY id_galeri DESC LIMIT 4");
			$hitungfoto=mysql_num_rows($poto);
			
			if($hitungfoto > 0){
			while($ph=mysql_fetch_array($poto)){
			?>
			<p class="thumb"><a id="thumb1" href="images/foto/galeri/<?php echo "$ph[nama_galeri]";?>" class="highslide" onclick="return hs.expand(this)">
			<img src="images/foto/galeri/<?php echo "$ph[nama_galeri]";?>" alt="Highslide JS" title="Klik untuk memperbesar"/></a>
			</p>
			<?php }}
			else {?>
			<b>Foto belum ada</b>
			<?php } ?>
			</div>
			
			<div id="kecil">
				<div class="pengumuman"><!-- Awal menampilkan pengumuman paling baru-->
				<?php
				$pengumuman=mysql_query("SELECT * FROM sh_pengumuman, sh_users WHERE sh_pengumuman.s_username=sh_users.s_username ORDER BY id_pengumuman DESC");
				$cek_pengumuman=mysql_num_rows($pengumuman);
				
				if($cek_pengumuman > 0){
				$peng=mysql_fetch_array($pengumuman);
				echo "<h4>$peng[judul_pengumuman]</h4>
				<p>$peng[isi_pengumuman]</p>
				<p><b>Diterbitkan pada: $peng[tanggal_pengumuman]</b></p>
				<p><b>Oleh: $peng[nama_lengkap_users]</b></p>";
				}
				else {
				?>
				<h4>PENGUMUMAN</h4>
				<p><b>Belum ada pengumuman</b></p>
				<?php } ?>
				</div>
			</div>
			
		<div class="clear"></div>
		</div>
<?php break;?>		

<!--Menampilkan informasi sekolah, yaitu profil sekolah-->
<?php case "info":
$info=mysql_query("SELECT * FROM sh_info_sekolah WHERE id_info='$_GET[id]'");
$r=mysql_fetch_array($info);?>
<div id="konten">
<div id="lebar">
<h3><?php echo "$r[nama_info]";?></h3>
<p><?php echo "$r[isi_info]";?></p>
</div>
</div>
<?php break;?>


<!--Menampilkan berita secara keseluruhan-->
<?php case "berita":?>

<div id="konten">
<div id="lebar">
<h3>Berita</h3><br>

<?php	$batas= 5;
		$halaman=$_GET['halaman'];
		If (empty($halaman)){
		$posisi=0;
		$halaman=1;
		}

		else { $posisi=($halaman-1) * $batas;
		}
		$tampil2 = mysql_query ("SELECT * FROM sh_berita WHERE status_terbit='1'");
		$jmldata = mysql_num_rows($tampil2);
		$jmlhal = ceil($jmldata/$batas);
$berita =mysql_query("SELECT * FROM sh_berita, sh_kategori, sh_users WHERE sh_berita.id_kategori=sh_kategori.id_kategori AND sh_berita.s_username=sh_users.s_username AND status_terbit='1' ORDER BY id_berita DESC LIMIT $posisi, $batas");
$cek_berita=mysql_num_rows($berita);

if($cek_berita > 0){
while ($r=mysql_fetch_array($berita)){
$hitung_komen=mysql_query("SELECT * FROM sh_komentar WHERE id_berita='$r[id_berita]'");
$jml_komen=mysql_num_rows($hitung_komen);
?>
<?php echo "<h4><a href='?p=detberita&id=$r[id_berita]'>$r[judul_berita]</a></h4><br>
<small>Diposting pada: <a href='?p=tglberita&tgl=$r[tanggal_posting]'>$r[tanggal_posting]</a>, oleh : <a href='?p=userberita&user=$r[s_username]'>$r[nama_lengkap_users]</a>, Kategori: <a href='?p=katberita&id=$r[id_kategori]'>$r[nama_kategori]</a>
, Komentar : $jml_komen
</small><br>";
						$isi_berita = strip_tags($r[isi_berita]); 
						$isi = substr($isi_berita,0,450);
if ($r[gambar_kecil] != 'no_image.jpg'){
echo "<p><img src='images/$r[gambar_kecil]' width='175px' style='float:left; margin: 5px 10px 0 0; padding: 10px; background: #fff; border: 1px solid #dcdcdc'>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";}
else {
echo "<p>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";
}}
		if ($halaman > 1){
		$prev=$halaman-1;
		echo 	"	<div class='hal' style='float: left'><a href='?p=berita&halaman=$prev' title='Halaman Sebelumnya'>&laquo; Sebelumnya</a></div>";
		}
		if ($halaman < $jmlhal) {
		$next=$halaman+1;
		echo "	<div class='hal' style='float: right'><a href='?p=berita&halaman=$next' title='halaman Berikutnya'>Berikutnya &raquo;</a></div>"; }
}
else { ?>
<b>Data berita belum tersedia</b>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan hasil pencarian berita-->
<?php case "pencarian":
$cari = trim($_POST['cari']);
$cari = htmlentities(htmlspecialchars($cari), ENT_QUOTES);
?>
<div id="konten">
<div id="lebar">
<h3>Hasil Pencarian dengan kata kunci <a href="">"<?php echo "$cari"?>"</a></h3><br><br>
<?php
$pencarian =mysql_query("SELECT * FROM sh_berita, sh_kategori, sh_users WHERE sh_berita.id_kategori=sh_kategori.id_kategori AND sh_berita.s_username=sh_users.s_username AND status_terbit='1' AND judul_berita LIKE '%$cari%' ORDER BY id_berita DESC");
$hitung=mysql_num_rows($pencarian);

if ($hitung > 0){
while ($r=mysql_fetch_array($pencarian)){
$hitung_komen=mysql_query("SELECT * FROM sh_komentar WHERE id_berita='$r[id_berita]'");
$jml_komen=mysql_num_rows($hitung_komen);
?>
<?php echo "<h4><a href='?p=detberita&id=$r[id_berita]'>$r[judul_berita]</a></h4><br>
<small>Diposting pada: <a href='?p=tglberita&tgl=$r[tanggal_posting]'>$r[tanggal_posting]</a>, oleh : <a href='?p=userberita&user=$r[s_username]'>$r[nama_lengkap_users]</a>, Kategori: <a href='?p=katberita&id=$r[id_kategori]'>$r[nama_kategori]</a>
, Komentar : $jml_komen
</small><br>";
						$isi_berita = strip_tags($r[isi_berita]); 
						$isi = substr($isi_berita,0,450);
if ($r[gambar_kecil] != 'no_image.jpg'){
echo "<p><img src='images/$r[gambar_kecil]' width='175px' style='float:left; margin: 5px 10px 0 0; padding: 10px; background: #fff; border: 1px solid #dcdcdc'>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";}
else {
echo "<p>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";
}}}

else {
echo "<p>Berita tidak ditemukan</p>";
}?>
</div>
</div>
<?php break ?>


<!--Menampilkan berita berdasarkan kategori-->
<?php case "katberita":?>

<div id="konten">
<div id="lebar">
<?php
$nama_kategori=mysql_query("SELECT * FROM sh_kategori WHERE id_kategori='$_GET[id]'");
$nk=mysql_fetch_array($nama_kategori);
$jml_berita_kat=mysql_query("SELECT * FROM sh_berita WHERE status_terbit='1' AND id_kategori='$_GET[id]'");
$hitung_berita_kat=mysql_num_rows($jml_berita_kat);
?>
<h3>Ada <?php echo $hitung_berita_kat?> berita dalam kategori <u><?php echo $nk[nama_kategori]?></u></h3><br>

<?php
$berita =mysql_query("SELECT * FROM sh_berita, sh_kategori, sh_users WHERE sh_berita.id_kategori=sh_kategori.id_kategori AND sh_berita.s_username=sh_users.s_username AND status_terbit='1' AND sh_berita.id_kategori='$_GET[id]' ORDER BY id_berita DESC");
$cek_berita=mysql_num_rows($berita);

if($cek_berita > 0){
while ($r=mysql_fetch_array($berita)){
$hitung_komen=mysql_query("SELECT * FROM sh_komentar WHERE id_berita='$r[id_berita]'");
$jml_komen=mysql_num_rows($hitung_komen);
?>
<?php echo "<h4><a href='?p=detberita&id=$r[id_berita]'>$r[judul_berita]</a></h4><br>
<small>Diposting pada: <a href='?p=tglberita&tgl=$r[tanggal_posting]'>$r[tanggal_posting]</a>, oleh : <a href='?p=userberita&user=$r[s_username]'>$r[nama_lengkap_users]</a>, Kategori: <a href='?p=katberita&id=$r[id_kategori]'>$r[nama_kategori]</a>
, Komentar : $jml_komen
</small><br>";
						$isi_berita = strip_tags($r[isi_berita]); 
						$isi = substr($isi_berita,0,450);
if ($r[gambar_kecil] != 'no_image.jpg'){
echo "<p><img src='images/$r[gambar_kecil]' width='175px' style='float:left; margin: 5px 10px 0 0; padding: 10px; background: #fff; border: 1px solid #dcdcdc'>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";}
else {
echo "<p>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";
}}	
}
else { ?>
<b>Data berita dalam kategori <?php echo $nk[nama_kategori]?> belum tersedia</b>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan berita berdasarkan penulis-->
<?php case "userberita":?>

<div id="konten">
<div id="lebar">
<?php
$nama_user=mysql_query("SELECT * FROM sh_users WHERE s_username='$_GET[user]'");
$nu=mysql_fetch_array($nama_user);
$hitung_user_berita=mysql_query("SELECT * FROM sh_berita WHERE status_terbit='1' AND s_username='$_GET[user]'");
$jml_berita_user=mysql_num_rows($hitung_user_berita);
?>
<h3>Ada <?php echo $jml_berita_user?> berita yang ditulis oleh <u><?php echo $nu[nama_lengkap_users]?></u></h3><br>

<?php
$berita =mysql_query("SELECT * FROM sh_berita, sh_kategori, sh_users WHERE sh_berita.id_kategori=sh_kategori.id_kategori AND sh_berita.s_username=sh_users.s_username AND status_terbit='1' AND sh_berita.s_username='$_GET[user]' ORDER BY id_berita DESC");
$cek_berita=mysql_num_rows($berita);

if($cek_berita > 0){
while ($r=mysql_fetch_array($berita)){
$hitung_komen=mysql_query("SELECT * FROM sh_komentar WHERE id_berita='$r[id_berita]'");
$jml_komen=mysql_num_rows($hitung_komen);
?>
<?php echo "<h4><a href='?p=detberita&id=$r[id_berita]'>$r[judul_berita]</a></h4><br>
<small>Diposting pada: <a href='?p=tglberita&tgl=$r[tanggal_posting]'>$r[tanggal_posting]</a>, oleh : <a href='?p=userberita&user=$r[s_username]'>$r[nama_lengkap_users]</a>, Kategori: <a href='?p=katberita&id=$r[id_kategori]'>$r[nama_kategori]</a>
, Komentar : $jml_komen
</small><br>";
						$isi_berita = strip_tags($r[isi_berita]); 
						$isi = substr($isi_berita,0,450);
if ($r[gambar_kecil] != 'no_image.jpg'){
echo "<p><img src='images/$r[gambar_kecil]' width='175px' style='float:left; margin: 5px 10px 0 0; padding: 10px; background: #fff; border: 1px solid #dcdcdc'>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";}
else {
echo "<p>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";
}}	
}
else { ?>
<b>Data berita yang ditulis oleh <?php echo $nu[nama_lengkap_users]?> belum tersedia</b>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan berita berdasarkan penulis-->
<?php case "tglberita":?>

<div id="konten">
<div id="lebar">
<?php
$hitung_tgl_berita=mysql_query("SELECT * FROM sh_berita WHERE status_terbit='1' AND tanggal_posting='$_GET[tgl]'");
$jml_berita_tgl=mysql_num_rows($hitung_tgl_berita);
$tgl=mysql_fetch_array($hitung_tgl_berita);
?>
<h3>Ada <?php echo $jml_berita_tgl?> berita yang ditulis pada <u><?php echo $tgl[tanggal_posting]?></u></h3><br>

<?php
$berita =mysql_query("SELECT * FROM sh_berita, sh_kategori, sh_users WHERE sh_berita.id_kategori=sh_kategori.id_kategori AND sh_berita.s_username=sh_users.s_username AND status_terbit='1' AND sh_berita.tanggal_posting='$_GET[tgl]' ORDER BY id_berita DESC");
$cek_berita=mysql_num_rows($berita);

if($cek_berita > 0){
while ($r=mysql_fetch_array($berita)){
$hitung_komen=mysql_query("SELECT * FROM sh_komentar WHERE id_berita='$r[id_berita]'");
$jml_komen=mysql_num_rows($hitung_komen);
?>
<?php echo "<h4><a href='?p=detberita&id=$r[id_berita]'>$r[judul_berita]</a></h4><br>
<small>Diposting pada: <a href='?p=tglberita&tgl=$r[tanggal_posting]'>$r[tanggal_posting]</a>, oleh : <a href='?p=userberita&user=$r[s_username]'>$r[nama_lengkap_users]</a>, Kategori: <a href='?p=katberita&id=$r[id_kategori]'>$r[nama_kategori]</a>
, Komentar : $jml_komen
</small><br>";
						$isi_berita = strip_tags($r[isi_berita]); 
						$isi = substr($isi_berita,0,450);
if ($r[gambar_kecil] != 'no_image.jpg'){
echo "<p><img src='images/$r[gambar_kecil]' width='175px' style='float:left; margin: 5px 10px 0 0; padding: 10px; background: #fff; border: 1px solid #dcdcdc'>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";}
else {
echo "<p>$isi...<a href='?p=detberita&id=$r[id_berita]'>Baca selengkapnya...</a></p><br>";
}}	
}
else { ?>
<b>Data berita yang ditulis oleh <?php echo $nu[nama_lengkap_users]?> belum tersedia</b>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan detail berita-->
<?php case "detberita": ?>
<?php
$pencarian =mysql_query("SELECT * FROM sh_berita, sh_kategori, sh_users WHERE sh_berita.id_kategori=sh_kategori.id_kategori AND sh_berita.s_username=sh_users.s_username AND status_terbit='1' AND id_berita='$_GET[id]' ORDER BY id_berita DESC");
$r=mysql_fetch_array($pencarian);
?>
<div id="konten">
<div id="lebar">
<h3><?php echo "$r[judul_berita]";?></h3>
<?php echo "
<small>Diposting pada: <a href='?p=tglberita&tgl=$r[tanggal_posting]'>$r[tanggal_posting]</a>, oleh : <a href='?p=userberita&user=$r[s_username]'>$r[nama_lengkap_users]</a>, Kategori: <a href='?p=katberita&id=$r[id_kategori]'>$r[nama_kategori]</a></small><br>
<p>$r[isi_berita]</p><br>";?>

<h3>Berita Lainnya</h3>
<ul style ="list-style: none; padding-left: 20px;">
<?php
$beritaterkait=mysql_query("SELECT * FROM sh_berita WHERE id_berita!='$r[id_berita]' AND status_terbit='1' ORDER BY RAND() LIMIT 5");
while($bt=mysql_fetch_array($beritaterkait)){
?>
	<li style="padding: 5px 0 5px 0;"><a href="<?php echo "?p=detberita&id=$bt[id_berita]";?>"><?php echo "$bt[judul_berita]";?></a></li>
<?php } ?>
</ul><br><br>
<?php
if ($r[status_komentar] == 1){
?>
<h3>Tinggalkan Komentar</h3>
<br>
<form method="POST" action="kontenweb/insert_komentar.php" id="formkomentar" name="formkomentar">
<?php echo "<input type='hidden' name='id' value='$r[id_berita]'>";?>
<table>
	<tr><td width="75px"><b>Nama *</b></td><td><input type="text" name="nama" class="sedang"></td></tr>
	<tr><td><b>Email *</b></td><td><input type="text" name="email" class="sedang">&nbsp;<small><i>Tidak akan diterbitkan</i></small></td></tr>
	<tr><td><b>Komentar *</b></td><td>
	<textarea name="komentar" style="width: 200px; height: 75px;"></textarea>
	</td></tr>
	<tr><td></td><td><img src="kontenweb/captcha.php?date=<?php echo date('YmdHis');?>" alt="security image" /></td></tr>
	<tr><td></td><td><input type="text" name="kode" class="pendek">&nbsp;<small><i>Masukkan kode diatas</i></td></tr>
	<tr><td></td><td><input type="submit" class="tombol" value="Kirim">&nbsp;<input type="reset" class="tombol" value="Reset"></td></tr>
</table>
</form>
<?php include "form_komentar.php";?>

<?php
$hitung_komentar_ini=mysql_query("SELECT * FROM sh_komentar WHERE status_terima='1' AND id_berita='$r[id_berita]'");
$jml_komentar_ini=mysql_num_rows($hitung_komentar_ini); ?>
<br>
<h3>Ada <?php echo $jml_komentar_ini?> komentar untuk berita ini</h3>
<ul style="list-style: none; padding-left: 5px;">
	<?php $komentar=mysql_query("SELECT * FROM sh_komentar WHERE status_terima='1' AND id_berita='$r[id_berita]' ORDER BY id_komentar DESC");
			while($k=mysql_fetch_array($komentar)){ ?>
	<li style="padding: 5px 0 5px 0; border-bottom: 1px solid #dcdcdc">
	<p><b><?php echo "<a href=''>$k[nama_komentar]</a>";?></b><br><small><?php echo "$k[tanggal_komentar]";?></small></p>
	<p><?php echo "$k[isi_komentar]"?></p>
	<?php } ?>
	</li>
		
</ul>
<?php } ?>
</div>
</div>
<?php break ?>


<!--Menampilkan agenda-->
<?php case "agenda":?>
<div id="konten">
<div id="lebar">
<h3>Agenda <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3><br>
<?php
	$batas= 5;
	$halaman=$_GET['halaman'];
		If (empty($halaman)){
		$posisi=0;
		$halaman=1;
		}

		else { $posisi=($halaman-1) * $batas;
		}
		$tampil2 = mysql_query ("SELECT * FROM sh_agenda");
		$jmldata = mysql_num_rows($tampil2);
		$jmlhal = ceil($jmldata/$batas);
$agenda=mysql_query("SELECT * FROM sh_agenda, sh_users WHERE sh_agenda.s_username=sh_users.s_username ORDER BY id_agenda DESC LIMIT $posisi, $batas");
$cek_agenda=mysql_num_rows($agenda);

if($cek_agenda > 0){
while($r=mysql_fetch_array($agenda)){
?>
<table style="margin: 20px 0 10px 0; border-bottom: 1px solid #dcdcdc">
	<tr><th colspan="3"><a href=""><?php echo "$r[judul_agenda]";?></a></th></tr>
	<tr><td width="120px"><b>Tanggal Agenda</b></td><td width="3px">:</td><td><?php echo "$r[tanggal_agenda]";?></td></tr>
	<tr><td width="120px"><b>Tempat Pelaksanaan</b></td><td width="3px">:</td><td><?php echo "$r[tempat_agenda]";?></td></tr>
	<tr><td width="120px"><b>Keterangan</b></td><td>:</td width="3px"><td><?php echo "$r[keterangan_agenda]";?></td></tr>
	<tr><td width="120px"><b>Ditulis Oleh</b></td><td>:</td width="3px"><td><?php echo "$r[nama_lengkap_users]";?></td></tr>
</table>
<?php } 
		if ($halaman > 1){
		$prev=$halaman-1;
		echo 	"	<div class='hal' style='float: left'><a href='?p=agenda&halaman=$prev' title='Halaman Sebelumnya'>&laquo; Sebelumnya</a></div>";
		}
		if ($halaman < $jmlhal) {
		$next=$halaman+1;
		echo "	<div class='hal' style='float: right'><a href='?p=agenda&halaman=$next' title='halaman Berikutnya'>Berikutnya &raquo;</a></div>"; }
}
else { ?>
<b>Data Agenda Belum Tersedia</b>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan pengumuman-->
<?php case "pengumuman": ?>
<div id="konten">
<div id="lebar">
<h3>Pengumuman <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3><br>
<?php
	$batas= 10;
	$halaman=$_GET['halaman'];
		If (empty($halaman)){
		$posisi=0;
		$halaman=1;
		}

		else { $posisi=($halaman-1) * $batas;
		}
		$tampil2 = mysql_query ("SELECT * FROM sh_pengumuman");
		$jmldata = mysql_num_rows($tampil2);
		$jmlhal = ceil($jmldata/$batas);
$pengumuman=mysql_query("SELECT * FROM sh_pengumuman, sh_users WHERE sh_pengumuman.s_username=sh_users.s_username ORDER BY id_pengumuman DESC LIMIT $posisi, $batas");
$cek_pengumuman=mysql_num_rows($pengumuman);

if($cek_pengumuman > 0){
while($r=mysql_fetch_array($pengumuman)){
?>
<table style="margin: 20px 0 10px 0; border-bottom: 1px solid #dcdcdc">
	<tr><th colspan="3"><a href=""><?php echo "$r[judul_pengumuman]";?></a></th></tr>
	<tr><td width="120px"><b>Tanggal Posting</b></td><td width="3px">:</td><td><?php echo "$r[tanggal_pengumuman]";?></td></tr>
	<tr><td width="120px"><b>Diterbitkan oleh</b></td><td>:</td width="3px"><td><?php echo "$r[nama_lengkap_users]";?></td></tr>
	<tr><td colspan="3"><?php echo "$r[isi_pengumuman]";?></td></tr>
</table>
<?php } 
		if ($halaman > 1){
		$prev=$halaman-1;
		echo 	"	<div class='hal' style='float: left'><a href='?p=pengumuman&halaman=$prev' title='Halaman Sebelumnya'>&laquo; Sebelumnya</a></div>";
		}
		if ($halaman < $jmlhal) {
		$next=$halaman+1;
		echo "	<div class='hal' style='float: right'><a href='?p=pengumuman&halaman=$next' title='halaman Berikutnya'>Berikutnya &raquo;</a></div>"; }
		}
		else {?>
		<b>Data Pengumuman Belum Tersedia</b>
		<?php } ?>
</div>
</div>
<?php break;?>


<!--Menampilkan data guru-->
<?php case "guru": ?>
<div id="konten">
<div id="lebar">
<h3>Data Guru <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=gurujk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=gurupencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol"  style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Staff Pengajar</th><th class="garis">JK</th><th class="garis">Mengajar</th></tr>
<?php
$no=1;
$gurustaff=mysql_query("SELECT * FROM sh_guru_staff, sh_mapel WHERE sh_guru_staff.id_mapel=sh_mapel.id_mapel AND posisi='guru' ORDER BY nama_gurustaff ASC");
$hitungguru=mysql_num_rows($gurustaff);

if($hitungguru > 0){
while($r=mysql_fetch_array($gurustaff)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='6'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_gurustaff]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailguru&nip=$r[nip]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_gurustaff]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_mapel]";?></td>
</tr>
<?php $no++; }}

else { ?>
<tr class="garis"><td colspan="4"><b>Data guru belum ada</b></td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>


<!--Menampilkan data guru jenis kelamin-->
<?php case "gurujk": ?>
<div id="konten">
<div id="lebar">
<h3>Data Guru <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=gurujk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=gurupencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Staff Pengajar</th><th class="garis">JK</th><th class="garis">Mengajar</th></tr>
<?php
$no=1;
$gurustaff=mysql_query("SELECT * FROM sh_guru_staff, sh_mapel WHERE sh_guru_staff.id_mapel=sh_mapel.id_mapel AND jenkel='$_POST[jk]'  AND posisi='guru' ORDER BY nama_gurustaff ASC");
$cek_guru=mysql_num_rows($gurustaff);

if($cek_guru > 0){
while($r=mysql_fetch_array($gurustaff)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='6'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_gurustaff]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailguru&nip=$r[nip]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_gurustaff]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_mapel]";?></td>
</tr>
<?php $no++; } }
else {
?>
<tr class="garis"><td colspan="4"><b>Data guru dengan jenis kelamin
<?php
if($_POST[jk]=='L'){ echo "Laki-laki"; }
else { echo "Perempuan"; }
?>
 belum ada</b></td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>


<!--Menampilkan data guru pencarian-->
<?php case "gurupencarian":
$cari = trim($_POST['cari']);
$cari = htmlentities(htmlspecialchars($cari), ENT_QUOTES);
?>

<div id="konten">
<div id="lebar">
<h3>Hasil Pencarian Guru <a href=""><?php echo "$ns[isi_pengaturan]";?></a> dengan kata kunci <a href="">"<?php echo "$cari";?>"</a></h3>

<form method="POST" action="?p=gurujk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=gurupencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Staff Pengajar</th><th class="garis">JK</th><th class="garis">Mengajar</th></tr>
<?php
$no=1;
$gurustaff=mysql_query("SELECT * FROM sh_guru_staff, sh_mapel WHERE sh_guru_staff.id_mapel=sh_mapel.id_mapel AND posisi='guru' AND nama_gurustaff LIKE '%$cari%' ORDER BY nama_gurustaff ASC");
$hitung=mysql_num_rows($gurustaff);

if ($hitung > 0){
while($r=mysql_fetch_array($gurustaff)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='6'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_gurustaff]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailguru&nip=$r[nip]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_gurustaff]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_mapel]";?></td>
</tr>
<?php $no++; } }
else {
echo "<tr class='garis'><td colspan='4'>Data tidak ditemukan</td></tr>";
}
?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>

<!--Menampilkan detail guru-->
<?php case "detailguru": ?>

<div id="konten">
<div id="lebar">
<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='6'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan] > 0){
$detailguru=mysql_query("SELECT * FROM sh_guru_staff, sh_mapel WHERE sh_guru_staff.id_mapel=sh_mapel.id_mapel AND nip='$_GET[nip]'");
$r=mysql_fetch_array($detailguru);
?>
<h3>Detail Guru <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<table style="margin-top: 20px">
<tr><td rowspan="7" width="160px"><?php echo "<img src='images/foto/guru/$r[foto]' width='150px' style='padding: 5px; border: 1px solid #dcdcdc; background: #fff;'>";?></td></tr>
<tr><td width="130px">Nama Guru</td><td>:</td><td><b><?php echo $r[nama_gurustaff]?></b></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><b><?php echo $r[jenkel]?></b></td></tr>
<tr><td>Mengajar</td><td>:</td><td><b><?php echo $r[nama_mapel]?></b></td></tr>
<tr><td>Pendidikan Terakhir</td><td>:</td><td><b><?php echo $r[pendidikan_terakhir]?></b></td></tr>
<tr><td>Tahun Masuk</td><td>:</td><td><b><?php echo $r[tahun_masuk]?></b></td></tr>
<tr><td>Alamat</td><td>:</td><td><b><?php echo $r[alamat]?></b></td></tr>
<tr><td colspan="4"><input type="button" class="tombol" value="Kembali" onclick="self.history.back()"></td></tr>
</table>
<?php } ?>
</div>
</div>

<?php break; ?>

<!--Menampilkan data staff-->
<?php case "staff": ?>
<div id="konten">
<div id="lebar">
<h3>Data Staff <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=staffjk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=staffpencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Staff</th><th class="garis">JK</th><th class="garis">Jabatan</th></tr>
<?php
$no=1;
$gurustaff=mysql_query("SELECT * FROM sh_guru_staff, sh_jabatan WHERE sh_guru_staff.id_jabatan=sh_jabatan.id_jabatan  AND posisi='staff' ORDER BY nama_gurustaff ASC");
$hitungstaff=mysql_num_rows($gurustaff);

if($hitungstaff > 0){
while($r=mysql_fetch_array($gurustaff)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='6'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_gurustaff]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailstaff&nip=$r[nip]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_gurustaff]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_jabatan]";?></td>
</tr>
<?php $no++; }}

else { ?>
<tr class="garis"><td colspan="4"><b>Data staff belum ada</b></td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>

<?php break; ?>


<!--Menampilkan data staff jenis kelamin-->
<?php case "staffjk": ?>

<div id="konten">
<div id="lebar">
<h3>Data Staff <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=staffjk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=staffpencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Staff</th><th class="garis">JK</th><th class="garis">Jabatan</th></tr>
<?php
$no=1;
$gurustaff=mysql_query("SELECT * FROM sh_guru_staff, sh_jabatan WHERE sh_guru_staff.id_jabatan=sh_jabatan.id_jabatan  AND posisi='staff' AND jenkel='$_POST[jk]' ORDER BY nama_gurustaff ASC");
$cek_staff=mysql_num_rows($gurustaff);

if($cek_staff > 0){
while($r=mysql_fetch_array($gurustaff)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='6'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_gurustaff]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailstaff&nip=$r[nip]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_gurustaff]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_jabatan]";?></td>
</tr>
<?php $no++; } }
else {
?>
<tr class="garis"><td colspan="4"><b>Data staff dengan jenis kelamin
<?php
if($_POST[jk]=='L'){ echo "Laki-laki"; }
else { echo "Perempuan"; }
?>
 belum ada</b></td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>


<!--Menampilkan data staff pencarian-->
<?php case "staffpencarian":
$cari = trim($_POST['cari']);
$cari = htmlentities(htmlspecialchars($cari), ENT_QUOTES);
?>
<div id="konten">
<div id="lebar">
<h3>Hasil Pencarian Staff <a href=""><?php echo "$ns[isi_pengaturan]";?></a> dengan kata kunci <a href="">"<?php echo "$cari";?>"</a></h3>

<form method="POST" action="?p=staffjk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=staffpencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Staff</th><th class="garis">JK</th><th class="garis">Jabatan</th></tr>
<?php
$no=1;
$gurustaff=mysql_query("SELECT * FROM sh_guru_staff, sh_jabatan WHERE sh_guru_staff.id_jabatan=sh_jabatan.id_jabatan  AND posisi='staff' AND nama_gurustaff LIKE '%$cari%' ORDER BY nama_gurustaff ASC");
$hitung=mysql_num_rows($gurustaff);

if ($hitung > 0){
while($r=mysql_fetch_array($gurustaff)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='6'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_gurustaff]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailstaff&nip=$r[nip]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_gurustaff]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_jabatan]";?></td>
</tr>
<?php $no++; }}
else {
echo "<tr class='garis'><td colspan='4'>Data tidak ditemukan</td></tr>";
}
 ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>


<!--Menampilkan detail staff-->
<?php case "detailstaff": ?>

<div id="konten">
<div id="lebar">
<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='6'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan] > 0){
$detailstaff=mysql_query("SELECT * FROM sh_guru_staff, sh_jabatan WHERE sh_guru_staff.id_jabatan=sh_jabatan.id_jabatan AND nip='$_GET[nip]'");
$r=mysql_fetch_array($detailstaff);
?>
<h3>Detail Staff <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<table style="margin-top: 20px">
<tr><td rowspan="7" width="160px"><?php echo "<img src='images/foto/guru/$r[foto]' width='150px' style='padding: 5px; border: 1px solid #dcdcdc; background: #fff;'>";?></td></tr>
<tr><td width="130px">Nama Staff</td><td>:</td><td><b><?php echo $r[nama_gurustaff]?></b></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><b><?php echo $r[jenkel]?></b></td></tr>
<tr><td>Jabatan</td><td>:</td><td><b><?php echo $r[nama_jabatan]?></b></td></tr>
<tr><td>Pendidikan Terakhir</td><td>:</td><td><b><?php echo $r[pendidikan_terakhir]?></b></td></tr>
<tr><td>Tahun Masuk</td><td>:</td><td><b><?php echo $r[tahun_masuk]?></b></td></tr>
<tr><td>Alamat</td><td>:</td><td><b><?php echo $r[alamat]?></b></td></tr>
<tr><td colspan="4"><input type="button" class="tombol" value="Kembali" onclick="self.history.back()"></td></tr>
</table>
<?php } ?>
</div>
</div>

<?php break; ?>

<!--Menampilkan data siswa-->
<?php case "siswa": ?>
<div id="konten">
<div id="lebar">
<h3>Data Siswa <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=siswajk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=siswakelas" style="float:left; margin-left: 5px">
<select name="kelas" onChange="this.form.submit()">
	<option selected>Kelas</option>
	<?php
	$kelas=mysql_query("SELECT * FROM sh_kelas ORDER BY id_kelas");
	while($k=mysql_fetch_array($kelas)){
	?>
	<option value="<?php echo "$k[id_kelas]";?>"><?php echo "$k[nama_kelas]";?></option>
	<?php } ?>
</select>
</form>

<form method="POST" action="?p=siswapencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Siswa</th><th class="garis">JK</th><th class="garis">Kelas</th></tr>
<?php
$no=1;
$siswa=mysql_query("SELECT * FROM sh_siswa, sh_kelas WHERE sh_siswa.id_kelas=sh_kelas.id_kelas  AND status_siswa='1' ORDER BY nama_siswa ASC");
$hitungsiswa=mysql_num_rows($siswa);

if($hitungsiswa > 0){
while($r=mysql_fetch_array($siswa)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='4'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_siswa]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailsiswa&nis=$r[nis]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_siswa]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_kelas]";?></td>
</tr>
<?php $no++; }}
else { ?>
<tr class="garis"><td colspan="4"><b>Data siswa belum ada</b></td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>


<!--Menampilkan data siswa pencarian-->
<?php case "siswapencarian":
$cari = trim($_POST['cari']);
$cari = htmlentities(htmlspecialchars($cari), ENT_QUOTES);
?>
<div id="konten">
<div id="lebar">
<h3>Hasil Pencarian Siswa <a href=""><?php echo "$ns[isi_pengaturan]";?></a> dengan kata kunci <a href="">"<?php echo "$cari";?>"</a></h3>

<form method="POST" action="?p=siswajk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=siswakelas" style="float:left; margin-left: 5px">
<select name="kelas" onChange="this.form.submit()">
	<option selected>Kelas</option>
	<?php
	$kelas=mysql_query("SELECT * FROM sh_kelas ORDER BY id_kelas");
	while($k=mysql_fetch_array($kelas)){
	?>
	<option value="<?php echo "$k[id_kelas]";?>"><?php echo "$k[nama_kelas]";?></option>
	<?php } ?>
</select>
</form>

<form method="POST" action="?p=siswapencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Siswa</th><th class="garis">JK</th><th class="garis">Kelas</th></tr>
<?php
$no=1;
$siswa=mysql_query("SELECT * FROM sh_siswa, sh_kelas WHERE sh_siswa.id_kelas=sh_kelas.id_kelas  AND status_siswa='1' AND nama_siswa LIKE '%$cari%' ORDER BY nama_siswa ASC");
$hitung=mysql_num_rows($siswa);
if ($hitung > 0){
while($r=mysql_fetch_array($siswa)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='4'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_siswa]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailsiswa&nis=$r[nis]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_siswa]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_kelas]";?></td>
</tr>
<?php $no++; }}
else {
echo "<tr class='garis'><td colspan='4'>Data tidak ditemukan</td></tr>";
}
?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>


<!--Menampilkan data siswa jenis kelamin-->
<?php case "siswajk": ?>
<div id="konten">
<div id="lebar">
<h3>Data Siswa <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=siswajk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=siswakelas" style="float:left; margin-left: 5px">
<select name="kelas" onChange="this.form.submit()">
	<option selected>Kelas</option>
	<?php
	$kelas=mysql_query("SELECT * FROM sh_kelas ORDER BY id_kelas");
	while($k=mysql_fetch_array($kelas)){
	?>
	<option value="<?php echo "$k[id_kelas]";?>"><?php echo "$k[nama_kelas]";?></option>
	<?php } ?>
</select>
</form>

<form method="POST" action="?p=siswapencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Siswa</th><th class="garis">JK</th><th class="garis">Kelas</th></tr>
<?php
$no=1;
$siswa=mysql_query("SELECT * FROM sh_siswa, sh_kelas WHERE sh_siswa.id_kelas=sh_kelas.id_kelas  AND status_siswa='1' AND jenkel='$_POST[jk]' ORDER BY nama_siswa ASC");
$hitung=mysql_num_rows($siswa);
if ($hitung > 0){
while($r=mysql_fetch_array($siswa)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='4'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_siswa]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailsiswa&nis=$r[nis]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_siswa]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_kelas]";?></td>
</tr>
<?php $no++; } }

else {?>
<tr class="garis"><td class="garis" colspan="4">Data siswa <?php
if($_POST[jk]=='L'){ echo "Laki-laki"; }
else { echo "Perempuan"; }
?> belum tersedia</td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>


<!--Menampilkan data siswa berdasar kelas-->
<?php case "siswakelas": ?>
<div id="konten">
<div id="lebar">
<h3>Data Siswa <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=siswajk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=siswakelas" style="float:left; margin-left: 5px">
<select name="kelas" onChange="this.form.submit()">
	<option selected>Kelas</option>
	<?php
	$kelas=mysql_query("SELECT * FROM sh_kelas ORDER BY id_kelas");
	while($k=mysql_fetch_array($kelas)){
	?>
	<option value="<?php echo "$k[id_kelas]";?>"><?php echo "$k[nama_kelas]";?></option>
	<?php } ?>
</select>
</form>

<form method="POST" action="?p=siswapencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Siswa</th><th class="garis">JK</th><th class="garis">Kelas</th></tr>
<?php
$no=1;
$siswa=mysql_query("SELECT * FROM sh_siswa, sh_kelas WHERE sh_siswa.id_kelas=sh_kelas.id_kelas  AND status_siswa='1' AND sh_siswa.id_kelas='$_POST[kelas]' ORDER BY nama_siswa ASC");
$hitung=mysql_num_rows($siswa);
if ($hitung > 0){
while($r=mysql_fetch_array($siswa)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='4'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_siswa]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailsiswa&nis=$r[nis]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_siswa]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[nama_kelas]";?></td>
</tr>
<?php $no++; } }

else { ?>
<tr class="garis"><td class="garis" colspan="4">Data siswa dengan kelas
<?php
$nama_kelas=mysql_query("SELECT * FROM sh_kelas WHERE id_kelas='$_POST[kelas]'");
$nk=mysql_fetch_array($nama_kelas);
echo "$nk[nama_kelas]";
?> belum tersedia </td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
</div>
</div>
<?php break; ?>


<!--Menampilkan detail siswa-->
<?php case "detailsiswa": ?>

<div id="konten">
<div id="lebar">
<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='4'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan] > 0){
$detailsiswa=mysql_query("SELECT * FROM sh_siswa, sh_kelas WHERE sh_siswa.id_kelas=sh_kelas.id_kelas AND nis='$_GET[nis]'");
$r=mysql_fetch_array($detailsiswa);
?>
<h3>Detail Siswa <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<table style="margin-top: 20px">
<tr><td width="130px">Nama Siswa</td><td width="5px">:</td><td><b><?php echo $r[nama_siswa]?></b></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><b><?php echo $r[jenkel]?></b></td></tr>
<tr><td>Kelas</td><td>:</td><td><b><?php echo $r[nama_kelas]?></b></td></tr>
<tr><td>Tahun Masuk</td><td>:</td><td><b><?php echo $r[tahun_registrasi]?></b></td></tr>
<tr><td>Alamat</td><td>:</td><td><b><?php echo $r[alamat]?></b></td></tr>
<tr><td colspan="3"><input type="button" class="tombol" value="Kembali" onclick="self.history.back()"></td></tr>
</table>
<?php } ?>
</div>
</div>

<?php break; ?>


<!--Menampilkan data alumni-->
<?php case "alumni": ?>
<div id="konten">
<div id="lebar">
<h3>Data Alumni <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=alumnijk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=alumnipencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Alumni</th><th class="garis">JK</th><th class="garis">Tahun Lulus</th></tr>
<?php
$no=1;
$alumni=mysql_query("SELECT * FROM sh_siswa WHERE status_siswa='0' ORDER BY nama_siswa ASC");
$hitungalumni=mysql_num_rows($alumni);

if($hitungalumni > 0){
while($r=mysql_fetch_array($alumni)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='5'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_siswa]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailalumni&id=$r[id_siswa]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_siswa]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[tahun_lulus]";?></td>
</tr>
<?php $no++; }}

else { ?>
<tr class="garis"><td colspan="4"><b>Data alumni belum ada</b></td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
	
<?php
$formalumni=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='7'");
$f=mysql_fetch_array($formalumni);

if ($f[isi_pengaturan] != 0){
?>
<p style="margin-top: 30px">
Klik <a href="?p=formalumni"><b>&raquo;disini&laquo;</b></a> untuk mengisi form data alumni
</p>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan data alumni jenis kelamin-->
<?php case "alumnijk": ?>
<div id="konten">
<div id="lebar">
<h3>Data Alumni <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=alumnijk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=alumnipencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Alumni</th><th class="garis">JK</th><th class="garis">Tahun Lulus</th></tr>
<?php
$no=1;
$alumni=mysql_query("SELECT * FROM sh_siswa WHERE status_siswa='0' AND jenkel='$_POST[jk]' ORDER BY nama_siswa ASC");
$cek_alumni=mysql_num_rows($alumni);
if($cek_alumni > 0){
while($r=mysql_fetch_array($alumni)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='5'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_siswa]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailalumni&id=$r[id_siswa]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_siswa]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[tahun_lulus]";?></td>
</tr>
<?php $no++; } }

else {?>
<tr class="garis"><td class="garis" colspan="4">
Data alumni <?php
if($_POST[jk]=='L'){ echo "Laki-laki"; }
else { echo "Perempuan"; }
?> belum tersedia
</td></tr>
<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
	
	
<?php
$formalumni=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='7'");
$f=mysql_fetch_array($formalumni);

if ($f[isi_pengaturan] != 0){
?>
<p style="margin-top: 30px">
Klik <a href="?p=formalumni"><b>&raquo;disini&laquo;</b></a> untuk mengisi form data alumni
</p>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan data alumni pencarian-->
<?php case "alumnipencarian":
$cari = trim($_POST['cari']);
$cari = htmlentities(htmlspecialchars($cari), ENT_QUOTES);
?>
<div id="konten">
<div id="lebar">
<h3>Hasil Pencarian Alumni <a href=""><?php echo "$ns[isi_pengaturan]";?></a> dengan kata kunci <a href="">"<?php echo "$cari";?>"</a></h3>

<form method="POST" action="?p=alumnijk" style="float:left">
<select name="jk" onChange="this.form.submit()">
	<option selected>Jenis Kelamin</option>
	<option value="L">Laki laki</option>
	<option value="P">Perempuan</option>
</select>
</form>

<form method="POST" action="?p=alumnipencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-bottom: 20px" value="Cari">
</form>

<table class="garis" id="results">
<tr><th class="garis" width="20px">No</th><th class="garis">Nama Alumni</th><th class="garis">JK</th><th class="garis">Tahun Lulus</th></tr>
<?php
$no=1;
$alumni=mysql_query("SELECT * FROM sh_siswa WHERE status_siswa='0' AND nama_siswa LIKE '%$cari%' ORDER BY nama_siswa ASC");
$hitung =mysql_num_rows($alumni);

if ($hitung > 0){
while($r=mysql_fetch_array($alumni)){
?>
<tr class="garis"><td class="garis"><?php echo "$no";?></td>
	<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='5'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan]== 0){
	?>
	<td class="garis"><b><?php echo "$r[nama_siswa]";?></b></td>
	<?php }
	else { ?>
	
	<td class="garis"><a href="<?php echo "?p=detailalumni&id=$r[id_siswa]";?>" title="Klik untuk melihat detail data"><b><?php echo "$r[nama_siswa]";?></b></a></td>
	<?php } ?>
	<td class="garis"><?php echo "$r[jenkel]";?></td>
	<td class="garis"><?php echo "$r[tahun_lulus]";?></td>
</tr>
<?php $no++; }}
else  {
echo "<tr class='garis'><td colspan='4'>Data tidak ditemukan</td></tr>";
}?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 30); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
	
<?php
$formalumni=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='7'");
$f=mysql_fetch_array($formalumni);

if ($f[isi_pengaturan] != 0){
?>
<p style="margin-top: 30px">
Klik <a href="?p=formalumni"><b>&raquo;disini&laquo;</b></a> untuk mengisi form data alumni
</p>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan detail alumni-->
<?php case "detailalumni": ?>

<div id="konten">
<div id="lebar">
<?php
	$detaildata=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='5'");
	$dd=mysql_fetch_array($detaildata);
		if ($dd[isi_pengaturan] > 0){
$detailalumni=mysql_query("SELECT * FROM sh_siswa WHERE id_siswa='$_GET[id]'");
$r=mysql_fetch_array($detailalumni);
?>
<h3>Detail Alumni <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<table style="margin-top: 20px">
<tr><td width="130px">Nama Alumni</td><td width="5px">:</td><td><b><?php echo $r[nama_siswa]?></b></td></tr>
<tr><td>Jenis Kelamin</td><td>:</td><td><b><?php echo $r[jenkel]?></b></td></tr>
<tr><td>Tempat, Tanggal Lahir</td><td>:</td><td><b><?php echo $r[tempat_lahir]?>, <?php echo $r[tanggal_lahir]?></b></td></tr>
<tr><td>Tahun Lulus</td><td>:</td><td><b><?php echo $r[tahun_lulus]?></b></td></tr>
<tr><td>Alamat</td><td>:</td><td><b><?php echo $r[alamat]?></b></td></tr>
<tr><td>Pekerjaan Sekarang</td><td>:</td><td><b><?php echo $r[pekerjaan_sekarang]?></b></td></tr>
<tr><td>Telepon</td><td>:</td><td><b><?php echo $r[telepon]?></b></td></tr>
<tr><td>Email</td><td>:</td><td><b><?php echo $r[email]?></b></td></tr>
<tr><td>Informasi Tambahan</td><td>:</td><td><b><?php echo $r[info_tambahan]?></b></td></tr>
<tr><td colspan="3"><input type="button" class="tombol" value="Kembali" onclick="self.history.back()"></td></tr>
</table>
<?php } ?>
</div>
</div>

<?php break; ?>


<!--Menampilkan form input alumni-->
<?php case "formalumni": ?>
<div id="konten">
<div id="lebar">
<?php
$formalumni=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='7'");
$f=mysql_fetch_array($formalumni);

if ($f[isi_pengaturan] != 0){
?>
<h3>Form Alumni <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<p style="margin-top: 20px">
Silahkan mengisi data alumni pada form dibawah ini, diharapkan data yang anda masukkan adalah data yang valid.
</p>
<form method="POST" action="kontenweb/insert_alumni.php" name="tambahalumni" id="tambahalumni">
<table style="margin-top: 20px">
	<tr><td width="135px"><b>Nama Alumni *</b></td><td><input type="text" name="nama_alumni" class="sedang"></td></tr>
			<tr><td width="135px"><b>Jenis Kelamin *</b></td>
			<td>
					<input type="radio" name="jk" value="L"/>Laki-laki&nbsp;
					<input type="radio" name="jk" value="P"/>Perempuan
			</td></tr>
			<tr><td width="135px"><b>Tempat, Tanggal Lahir *</b></td><td>
			<input type="text" name="tempat_lahir" class="sedang">, 
			<input type="text" id="tanggal" name="tanggal_lahir" class="sedang"></td></tr>
			<tr><td width="135px"><b>Alamat</b></td><td><textarea name="alamat" style="height: 100px"></textarea></td></tr>
			<tr><td width="135px"><b>Tahun Lulus *</b></td><td>
			<?php
				$thn_skrg=date("Y");
				echo "<select name=tahun_lulus style='margin: 0;'>
				<option value='' selected>Pilih Tahun</option>";
				for ($thn=1990;$thn<=$thn_skrg;$thn++){
				echo "<option value=$thn>$thn</option>";
				}
				echo "</select>"; ?>
			</td></tr>
			
			<tr><td width="135px"><b>Email</b></td><td><input type="text" name="email" class="sedang"></td></tr>
			<tr><td width="135px"><b>Telepon/ HP</b></td><td><input type="text" name="telepon" class="sedang"></td></tr>
			
			<tr><td width="135px"><b>Pekerjaan Sekarang</b></td><td><input type="text" name="pekerjaan_sekarang" class="panjang"></td></tr>
			<tr><td width="135px"><b>Informasi Tambahan</b></td><td><textarea name="info_tambahan" style="height: 100px"></textarea></td></tr>
			<tr><td>&nbsp;</td><td>
			<img src="kontenweb/captcha.php?date=<?php echo date('YmdHis');?>" alt="security image" />
			</td></tr>
			<tr><td>&nbsp;</td><td><input type="text" name="kode" class="pendek">&nbsp;<small><i>Masukkan kode diatas</i></td></tr>
			<tr><td></td><td>
			<input type="submit" class="tombol" value="Simpan">
			<input type="reset" class="tombol" value="Reset">
			</td></tr>
</table>
</form>
<?php include "form_alumni.php"; } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan form buku tamu-->
<?php case "bukutamu": ?>
<div id="konten">
<div id="lebar">
<h3>Buku Tamu</h3>
<p style="margin-top: 20px">
Silahkan mengisi buku tamu pada form dibawah ini untuk memberikan kritik maupun saran kepada kamu. Setiap buku tamu yang masuk, kami akan sangat menghargainya.
</p>
<form method="POST" action="kontenweb/insert_bukutamu.php" name="formbuku" id="formbuku">
<table style="margin-top: 20px">
	<tr><td><b>Nama *</b><td>:</td><td><input type="text" class="sedang" name="nama">&nbsp;<small><i>Harus diisi</i></small></td></tr>
	<tr><td><b>Email *</b><td>:</td><td><input type="text" class="sedang" name="email">&nbsp;<small><i>Harus diisi (Tidak akan diterbitkan)</i></small></td></tr>
	<tr><td><b>Subjek *</b><td>:</td><td><input type="text" class="panjang" name="subjek"></td></tr>
	<tr><td><b>Pesan *</b><td>:</td><td><textarea name="pesan" style="height: 150px"></textarea></td></tr>
	<tr><td>&nbsp;</td><td></td><td>
	<img src="kontenweb/captcha.php?date=<?php echo date('YmdHis');?>" alt="security image" />
	</td></tr>
	<tr><td>&nbsp;</td><td></td><td><input type="text" name="kode" class="pendek">&nbsp;<small><i>Masukkan kode diatas</i></td></tr>
	<tr><td>&nbsp;</td><td></td><td><input type="submit" class="tombol" value="Kirim">&nbsp;<input type="reset" class="tombol" value="Reset"></td></tr>

</table>
</form>
<?php include "form_bukutamu.php";?>

<?php
$tampilpesan=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='16'");
$tp=mysql_fetch_array($tampilpesan);
if ($tp[isi_pengaturan]== 1){
?>

<ul style="list-style: none; padding-left: 5px;">
	<?php $bukutamu=mysql_query("SELECT * FROM sh_buku_tamu WHERE status='1' ORDER BY id_bukutamu DESC");
			while($b=mysql_fetch_array($bukutamu)){ ?>
	<li style="padding: 5px 0 5px 0; border-bottom: 1px solid #dcdcdc">
	<p><b><?php echo "<a href=''>$b[nama_bukutamu]</a>";?></b><br><small><?php echo "$b[tanggal_kirim]";?></small></p>
	<p><?php echo "$b[isi_pesan]"?></p>
	<?php } ?>
	</li>
		
</ul>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan halaman PSB-->
<?php case "psb": ?>
<div id="konten">
<div id="lebar">
<h3>Pendaftaran Siswa Baru Online <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<?php
$statuspsb=mysql_query("SELECT *FROM sh_pengaturan WHERE id_pengaturan ='15'");
$r=mysql_fetch_array($statuspsb);
?>
<?php echo "$r[isi_pengaturan]";?>
<?php
if ($r[nama_pengaturan]==1){
?>
<br>
<p>Sekilas Informasi PSB Online 
<table style="width: 35%">
<?php
$pendaftar=mysql_query("SELECT * FROM sh_psb");
$totalpendaftar=mysql_num_rows($pendaftar);

$laki=mysql_query("SELECT * FROM sh_psb WHERE jenkel='L'");
$pendaftarlaki=mysql_num_rows($laki);

$perempuan=mysql_query("SELECT * FROM sh_psb WHERE jenkel='P'");
$pendaftarperempuan=mysql_num_rows($perempuan);

$nem=mysql_query("SELECT * FROM sh_psb ORDER BY nem DESC");
$nemtertinggi=mysql_fetch_array($nem);
?>
	<tr><td><b>Total Pendaftar</b></td><td><b><a href=""><?php echo "$totalpendaftar"?></a></b></td></tr>
	<tr><td><b>Pendaftar Laki-laki</b></td><td><b><a href=""><?php echo "$pendaftarlaki"?></a></b></td></tr>
	<tr><td><b>Pendaftar Perempuan</b></td><td><b><a href=""><?php echo "$pendaftarperempuan"?></a></b></td></tr>
	<tr><td><b>NEM Tertinggi</b></td><td><b><a href=""><?php echo "$nemtertinggi[nem]"?></a></b></td></tr>
</table>
<p>Silahkan Klik <a href="?p=formpsb"><b>&raquo;disini&laquo;</b></a> untuk melakukan pendaftaran secara online, atau klik
<a href="?p=datapsb"><b>&raquo;disini&laquo;</b></a> untuk melihat data pendaftar.</p>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan form psb-->
<?php case "formpsb": ?>
<div id="konten">
<div id="lebar">
<?php
$statuspsb=mysql_query("SELECT *FROM sh_pengaturan WHERE id_pengaturan ='15'");
$r=mysql_fetch_array($statuspsb);
?>
<?php
if ($r[nama_pengaturan]==1){
?>
<h3>Form Pendaftaran Siswa Baru <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<form method ="POST" action="kontenweb/insert_psb.php" name="formpsb" id="formpsb">
<table style="margin-top: 20px">
	<tr><td width="150px"><b>Nama Lengkap *</b></td><td>:</td><td><input type="text" class="sedang" name="nama"></td></tr>
	<tr><td><b>Rata-rata NEM *</b></td><td>:</td><td><input type="text" class="pendek" name="nem"><br>
	<small>Jika ada tanda koma (') maka diganti dengan tanda titik (.)</small></td></tr>
	<tr><td><b>Jenis Kelamin *</b></td><td>:</td><td>
	<input type="radio" name="jk" id="jk" value="L">Laki-laki&nbsp;
	<input type="radio" name="jk" id="jk" value="P">Perempuan
	</td></tr>
	<tr><td><b>Sekolah Asal *</b></td><td>:</td><td><input type="text" class="panjang" name="sekolah_asal"></td></tr>
	<tr><td><b>No. STTB *</b></td><td>:</td><td><input type="text" class="sedang" name="no_sttb"></td></tr>
	<tr><td><b>Tanggal STTB *</b></td><td>:</td><td><input type="text" id="tanggal1" name="tanggal_sttb" class="sedang"></td></tr>
	<tr><td><b>Tempat, Tanggal Lahir *</b></td><td>:</td><td><input type="text" class="sedang" name="tempat_lahir">&nbsp;<input type="text" class="sedang" name="tanggal_lahir" id="tanggal"></td></tr>
	<tr><td><b>Berat Badan</b></td><td>:</td><td><input type="text" class="pendek" name="bb">&nbsp; kg</td></tr>
	<tr><td><b>Tinggi Badan</b></td><td>:</td><td><input type="text" class="pendek" name="tb">&nbsp; cm</td></tr>
	<tr><td><b>Alamat Lengkap *</b></td><td>:</td><td><textarea style="height: 125px" name="alamat"></textarea></td></tr>
	<tr><td><b>Telepon</b></td><td>:</td><td><input type="text" class="sedang" name="telepon"></td></tr>
	<tr><td><b>Email</b></td><td>:</td><td><input type="text" class="sedang" name="email"></td></tr>
	<tr><td><b>Nama Orang Tua *</b></td><td>:</td><td><input type="text" class="sedang" name="nama_ortu"></td></tr>
	<tr><td><b>Pekerjaan Orang Tua *</b></td><td>:</td><td>
			<select name="pekerjaan_ortu">
				<option value="" selected>Pilih pekerjaan orang tua</option>
				<option value="Petani">Petani</option>
				<option value="Wiraswasta">Wiraswasta</option>
				<option value="PNS">PNS</option>
				<option value="TNI/ POLRI">TNI/ POLRI</option>
			</select>
	</td></tr>
	<tr><td colspan="3" style="border: 1px dashed #5b5b5b; padding: 20px;background: #fcffea;"><b>Darimanakah anda mengetahui PSB ONLINE <a href=""><?php echo "$ns[isi_pengaturan]"?></a></b><br><br>
	<input type="radio" name="polling" id="polling" value="Media Cetak">Media Cetak<br><br>
	<input type="radio" name="polling" id="polling" value="Internet">Internet<br><br>
	<input type="radio" name="polling" id="polling" value="Teman">Teman<br><br>
	<input type="radio" name="polling" id="polling" value="Kerabat">Kerabat<br><br>
	</td></tr>
	<tr><td colspan="3"><br><img src="kontenweb/captcha.php?date=<?php echo date('YmdHis');?>" alt="security image" /></td></tr>
	<tr><td colspan="3"><input type="text" name="kode" class="pendek">&nbsp;<small><i>Masukkan kode diatas</i></td></tr>
	<tr><td colspan="3"><br>
	<input type="submit" class="tombol" value="Daftar">
	<input type="reset" class="tombol" value="Reset">
	
	</td></tr>
</table>
</form>
<?php include "form.php";?>
<?php } ?>
</div>
</div>
<?php break; ?>


<!--Menampilkan halaman selesai PSB-->
<?php case "selesaipsb": ?>
<div id="konten">
<div id="lebar">
<?php
$statuspsb=mysql_query("SELECT *FROM sh_pengaturan WHERE id_pengaturan ='15'");
$r=mysql_fetch_array($statuspsb);
if ($r[nama_pengaturan] > 0){
?>
<h3>Terimakasih telah mendaftar di <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<?php echo "$r[isi_pengaturan2]";
echo "Klik <a href='?p=datapsb'>disini</a> untuk melihat data pendaftar";
}?>
</div>
</div>
<?php break; ?>

<!--Menampilkan data pendaftar-->
<?php case "datapsb": ?>
<div id="konten">
<div id="lebar">
<?php
$statuspsb=mysql_query("SELECT *FROM sh_pengaturan WHERE id_pengaturan ='15'");
$r=mysql_fetch_array($statuspsb);
if ($r[nama_pengaturan]==1){
?>
<h3>Data Pendaftar <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<form method="POST" action="?p=datapsbpencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-top: 20px" value="Cari">
</form>
<table class="garis" id="results">
	<tr>
		<th class="garis" width="35px">No</th><th class="garis">Nama Pendaftar</th><th class="garis">NEM</th><th class="garis">Sekolah Asal</th><th class="garis">Status</th>
	</tr>
	<?php
	$no=1;
	$psb=mysql_query("SELECT * FROM sh_psb ORDER BY status_psb DESC");
	$hitungpsb=mysql_num_rows($psb);
	
	if ($hitungpsb > 0){
	while($r=mysql_fetch_array($psb)){
	?>
	<tr class="garis"><td class="garis"><?php echo "$no";?></td>
		<td class="garis"><?php echo "<b>$r[nama_psb]</b>";?></td>
		<td class="garis"><?php echo "$r[nem]";?></td>
		<td class="garis"><?php echo "$r[sekolah_asal]";?></td>
		<td class="garis"><?php
		if ($r[status_psb]== 1){
		echo "<center><img src='kontenweb/terima.png'></center>";}
		else {
		echo "<center><img src='kontenweb/tolak.png'></center>";
		}?></td>
	</tr>
	<?php $no++; }}
	else {?>
	<tr class="garis"><td class="garis" colspan="5"><b>Data Pendaftar Belum Ada</b></td></tr>
	<?php } ?>
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 50); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
	
<table style="margin-top: 20px; width: auto;">
<tr><td><img src="kontenweb/terima.png"></td><td>:</td><td>Daftar Ulang</td></tr>
<tr><td><img src="kontenweb/tolak.png"></td><td>:</td><td>Tidak memenuhi syarat/ belum dimoderasi</td></tr>
<tr><td colspan="3"><b>* Untuk mengetahui hasil moderasi, silahkan menunggu maksimal 1 x 24 jam kerja</b></td></tr>
</table>
<?php } ?>
</div>
</div>
<?php break; ?>

<!--Menampilkan data pendaftar pencarian-->
<?php case "datapsbpencarian": ?>
<div id="konten">
<div id="lebar">
<?php
$cari = trim($_POST['cari']);
$cari = htmlentities(htmlspecialchars($cari), ENT_QUOTES);
$statuspsb=mysql_query("SELECT *FROM sh_pengaturan WHERE id_pengaturan ='15'");
$r=mysql_fetch_array($statuspsb);
if ($r[nama_pengaturan]==1){
?>
<h3>Hasil Pencarian Pendaftar <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>

<form method="POST" action="?p=datapsbpencarian" style="float:right">
<input type="text" class="cari" name="cari"><input type="submit" class="tombol" style="margin-top: 20px" value="Cari">
</form>
<table class="garis" id="results">
	<tr>
		<th class="garis" width="35px">No</th><th class="garis">Nama Pendaftar</th><th class="garis">NEM</th><th class="garis">Sekolah Asal</th><th class="garis">Status</th>
	</tr>
	<?php
	$no=1;
	$psb=mysql_query("SELECT * FROM sh_psb WHERE nama_psb LIKE '%$cari%' ORDER BY status_psb DESC");
	$hitungpsb=mysql_num_rows($psb);
	
	if ($hitungpsb > 0){
	while($r=mysql_fetch_array($psb)){
	?>
	<tr class="garis"><td class="garis"><?php echo "$no";?></td>
		<td class="garis"><?php echo "<b>$r[nama_psb]</b>";?></td>
		<td class="garis"><?php echo "$r[nem]";?></td>
		<td class="garis"><?php echo "$r[sekolah_asal]";?></td>
		<td class="garis"><?php
		if ($r[status_psb]== 1){
		echo "<center><img src='kontenweb/terima.png'></center>";}
		else {
		echo "<center><img src='kontenweb/tolak.png'></center>";
		}?></td>
	</tr>
	<?php $no++; }}
	else {?>
	<tr class="garis"><td class="garis" colspan="5"><b>Data tidak ditemukan</b></td></tr>
	<?php } ?>
	
</table>
				<div id="pageNavPosition"></div>
		
		
			    <script type="text/javascript"><!--
        var pager = new Pager('results', 50); 
        pager.init(); 
        pager.showPageNav('pager', 'pageNavPosition'); 
        pager.showPage(1);
    //--></script>
	
<table style="margin-top: 20px; width: auto;">
<tr><td><img src="kontenweb/terima.png"></td><td>:</td><td>Daftar Ulang</td></tr>
<tr><td><img src="kontenweb/tolak.png"></td><td>:</td><td>Tidak memenuhi syarat/ belum dimoderasi</td></tr>
<tr><td colspan="3"><b>* Untuk mengetahui hasil moderasi, silahkan menunggu maksimal 1 x 24 jam kerja</b></td></tr>
</table>
<?php } ?>
</div>
</div>
<?php break; ?>

<!--Menampilkan hasil polling-->
<?php case "polling": ?>
<div id="konten">
<div id="lebar">
<h3>Data Hasil Polling</h3>
<p>Berikut ini adalah hasil polling dari pengunjung website, terimakasih telah berpartisipasi dalam pengisian polling.</p>
<table style=";margin-top: 20px">
<?php
$jml=mysql_query("SELECT SUM(isi1) as hitung FROM sh_sidebar WHERE jenis='polling' AND status='1' AND isi2 !='pertanyaan'");
$tp=mysql_fetch_array($jml);

$hasilpolling=mysql_query("SELECT * FROM sh_sidebar WHERE jenis='polling' AND status ='1' AND isi2 !='pertanyaan'");
while ($r=mysql_fetch_array($hasilpolling)){
$persen=($r[isi1]!=0)?($r[isi1]/$tp[hitung]) * 100:0; 
?>
	<tr><td width="230px"><b><?php echo "$r[nama]";?></b>&nbsp(<?php printf("%1.0f" , $persen);echo "%";?>)</td><td>
	<img src="kontenweb/blue.jpg" height="38px" width="<?php $panjang=$persen/100* 300; echo "$panjang"; ?>"></td></tr>
<?php }?>
</table>
</div>
</div>
<?php break; ?>

<!--Menampilkan lokasi sekolah-->
<?php case "gmap": ?>
<div id="konten">
<div id="lebar">
<h3>Lokasi <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<?php
$gmap=mysql_query("SELECT * FROM sh_pengaturan WHERE id_pengaturan='14'");
$r=mysql_fetch_array($gmap);
			if (!empty($r[isi_pengaturan])){
			echo "$r[isi_pengaturan]";}
			else {
				echo "<br><img src='images/$r[isi_pengaturan2]' width='628px'>";
			}
?>
</div>
</div>
<?php break; ?>

<!--Menampilkan galeri-->
<?php case "galeri": ?>
<div id="konten">
<div id="lebar">
<h3>Album Galeri <a href=""><?php echo "$ns[isi_pengaturan]";?></a></h3>
<?php
$album=mysql_query("SELECT * FROM sh_album ORDER BY id_album DESC");
$cek_album=mysql_num_rows($album);
if($cek_album > 0){
while($r=mysql_fetch_array($album)){

$jmlfoto=mysql_query("SELECT * FROM sh_galeri WHERE id_album='$r[id_album]'");
$hitung=mysql_num_rows($jmlfoto);

if($hitung > 0 ){
?>
<div class="albumgaleri">
<p class="thumb"><a href="<?php echo "?p=foto&id=$r[id_album]";?>"><img src="images/foto/galeri/album/<?php echo "$r[foto_album]";?>" width="200px"></a></p><br>
<p><?php echo "<b>$r[nama_album]</b>";?><br>
<?php echo "<small>$r[tanggal_album]</small>";?><br>
<?php
echo "Jumlah Foto ($hitung)";
?></p>
</div>
<?php } } }

else { ?>
<b>Data album galeri belum tersedia</b>
<?php } ?>
</div>
</div>
<?php break; ?>

<!--Menampilkan data foto-->
<?php case "foto": ?>
<div id="konten">
<div id="lebar">
<?php
$nama_album=mysql_query("SELECT * FROM sh_album WHERE id_album='$_GET[id]'");
$tampilnama=mysql_fetch_array($nama_album);

$jmlfoto=mysql_query("SELECT * FROM sh_galeri WHERE id_album='$_GET[id]'");
$hitung=mysql_num_rows($jmlfoto);
if ($hitung != 0){
?>
<h3>Foto Album <a href="">"&nbsp;<?php echo "$tampilnama[nama_album]";?>&nbsp;"</a></h3>
<small><?php echo "$tampilnama[tanggal_album]";?></small>
<p><?php echo "$tampilnama[deskripsi_album]";?></p>
<div class="galeriDepan">
<?php
$galeri=mysql_query("SELECT * FROM sh_galeri WHERE id_album='$_GET[id]'");
while ($r=mysql_fetch_array($galeri)){
?>
<p class="thumb"><a id="thumb1" href="images/foto/galeri/<?php echo "$r[nama_galeri]";?>" class="highslide" onclick="return hs.expand(this)">
			<img src="images/foto/galeri/<?php echo "$r[nama_galeri]";?>" alt="Highslide JS" title="Klik untuk memperbesar" width="350px"/></a>
</p><?php } ?>
</div>
<?php }

else { ?>
<h3>Tidak ada foto dalam album "&nbsp;<?php echo "$tampilnama[nama_album]";?>&nbsp;"</h3>
<?php } ?>

</div>
</div>
<?php break; ?>

<?php } ?>