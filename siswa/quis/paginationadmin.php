<link href='../../../css/tabel3.css' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="../../js/dropdowncontent.js"></script>
<script type="text/javascript" src="../../js/textsizer.js"></script>
<script type="text/javascript" src="../../js/jquery.js"></script>
<script type="text/javascript">
// --- FITUR ANTI CURANG LU ---
var tenth='';function ninth() {
if (document.all) {(tenth);alert("Tidak diperbolehkan Klik kanan"); return false;}}
function twelfth(e) {
if (document.layers||(document.getElementById&&!document.all)) {
if (e.which==2||e.which==3) {(tenth);return false;}}}
if (document.layers) {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=twelfth;}
else{document.onmouseup=twelfth;document.oncontextmenu=ninth;}
document.oncontextmenu=new Function('alert("Tidak diperbolehkan Klik kanan"); return false')

document.onkeydown=function(e) {
    e=e||window.event;
    if (e.keyCode === 115 ) { e.keyCode = 0; alert("Tombol ini tidak diijinkan F4"); return false; }
    if(e.keyCode === 18 || e.keyCode === 91 || e.keyCode === 9) { logoutck(); return false; }
    if (e.keyCode === 32 || e.keyCode === 8 ) { alert("Tombol tidak diijinkan"); return false; }
}
function logoutck() {
    alert("Anda menekan tombol yang tidak diijinkan ... anda akan di keluarkan dari sistem");
    window.location.href = 'logoutck.php';
}
</script>

<style>
#clock { padding:1px; font-weight:bold; color:green; background-color:#00CCCC; border:solid 0px white; font-size:25px; font-family:"Arial Black", Arial, serif; }
body { margin: 0; padding: 0; font-family:Verdana; font-size:13px }
#pagination { padding:1px; text-align:left; }
li { background-color:yellow; list-style: none; float: left; width:17px; font-size:17px; text-align:center; padding:8px; border:solid 1px white; color:black; cursor: pointer; }
li:hover { color:red; }
#loading { width: 100%; position: absolute; }
</style>

<?php 
session_set_cookie_params(3600*2,"/");
session_start();
// Paksa error keluar biar gak putih
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "../../lsp_koneksi.php";

if (empty($_SESSION['username'])){
    echo "<center><strong> Anda Harus Login Dahulu ...!</strong><br><a href=../../lsp_login.php> Kembali</a></center>"; 
    exit;
} else {
    $user = $_SESSION['username'];
    $waktuk = time();
    $md_param = mysqli_real_escape_string($conn, $_GET['md']);

    // CEK STATUS UJIAN
    $periksa = mysqli_query($conn, "SELECT nim FROM gradealias WHERE nim='$user' AND kodemodul ='$md_param'");
    $periksa3 = mysqli_num_rows($periksa);

    // AMBIL DATA MODUL (Pake mysqli)
    $result = mysqli_query($conn, "SELECT * FROM modul WHERE kd_modul='$md_param'");
    $baris = mysqli_fetch_array($result);
    
    if(!$baris) { die("<center><h1>Modul Tidak Ditemukan!</h1>ID: $md_param tidak ada di tabel modul.</center>"); }

    $batas = $baris['batas'];
    $vmenit = $baris['Waktu'];
    $vacaksoal = $baris['acak'];
    $kmodul = $baris['kd_modul'];
    $nmodul = $baris['modul'];

    // Simpan ke session dengan kutip yang bener
    $_SESSION['kd_modul'] = $baris['kd_modul'];

    $insertSQL = "INSERT INTO statuskerja (nim, statusk, waktu) VALUES ('$user','Y',$waktuk)";
    mysqli_query($conn, $insertSQL);

    // HITUNG HALAMAN
    $per_page = 1; 
    $sql_count = "SELECT * FROM pertanyaan WHERE kd_modul='$md_param'";
    $rsd = mysqli_query($conn, $sql_count);
    $count = mysqli_num_rows($rsd);
    $pages = ceil($count/$per_page);
?>

<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        function Display_Load() { $("#loading").fadeIn(900,0).html("<img src='loading1.gif' height='20%'/>"); }
        function Hide_Load() { $("#loading").fadeOut('slow'); }

        $("#content").load("pagination_data.php?page=1", Hide_Load);

        $("#pagination li").click(function(){
            Display_Load();
            var pageNum = this.id;
            var mmenit = document.User.TimeLeft.value;
            var vwreal = document.User.Watch.value;
            var vkdmdl = document.p1.kmdl.value;
            var vnim = document.p1.nim.value;

            $.post('updatewaktu.php',{vmenit: mmenit, kodemd: vkdmdl, usern: vnim, wreal:vwreal});
            
            $.ajax({ 
                type: 'post', url: 'api.php', 
                data: {vkdm:vkdmdl, vnis:vnim, vno:pageNum}, 
                dataType: 'json',
                success: function(data) {
                    var vnomor = data[8];
                    if (data[4] != '') {
                        $("#"+vnomor).css({"background-color":"green", "color":"white"});
                    }
                }
            });
            $("#content").load("pagination_data.php?page=" + pageNum, Hide_Load);
        });
    });
    </script>
</head>
<body>
<center>
    <form name="User">
        <table>
            <tr>
                <td><?php echo $_SESSION['nama_lengkap']; ?></td>
                <th> Waktu Sisa : </th>
                <th><input id="clock" name="TimeLeft" size="8"></th>
                <input name="TimeTaken" type="hidden">
                <input name="Watch" type="hidden">
            </tr>
        </table>
    </form>

    <table width="90%">
    <?php
    $cekvste = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM vsessay"));
    if ($cekvste && $cekvste['stsessay'] == 'y'){
        $execsqlessay = mysqli_query($conn, "SELECT * FROM soalessay WHERE kodesoal='$md_param'");
        if (mysqli_num_rows($execsqlessay) > 0){
            echo "<tr><th><center><font color=blue>SOAL ESSAY</font></center></th></tr><tr><td>";
            $g = 1;
            while ($rowessay = mysqli_fetch_array($execsqlessay)) {
                echo $g . ". " . $rowessay['soalessay'] . "<br>";
                $g++;
            }
            echo "</td></tr>";
        }
    }
    ?>
    </table>

    <form id="p1" name="p1" action="proses_assesmentbaruabcd.php" method="post" onSubmit="return confirm('Apakah yakin sudah selesai ??')">
        <input type="hidden" name="nim" value="<?php echo $user; ?>">
        <input type="hidden" name="kmdl" value="<?php echo $_SESSION['kd_modul']; ?>">
        
        <table>
            <tr><td>Kode Soal : <strong><?php echo $kmodul; ?></strong></td><td> Nama Mapel : <strong><?php echo $nmodul; ?></strong></td></tr>
        </table>

        <?php 
        // LOGIKA PERTANYAANBCK
        $cekada0 = mysqli_query($conn, "SELECT * FROM pertanyaanbck WHERE nim='$user' AND kd_modul='$md_param'");
        if (mysqli_num_rows($cekada0) < 1){
            mysqli_query($conn, "DELETE FROM pertanyaanbck WHERE kd_modul='$md_param' AND nim='$user'");
            $no = 1;
            $rows = [];
            while($r = mysqli_fetch_array($rsd)) { $rows[] = $r; }
            if($vacaksoal == '1') { shuffle($rows); }

            foreach($rows as $row) {
                $id = $row['question_id'];
                $q = mysqli_real_escape_string($conn, $row['question']);
                $ans = $row['oa'];
                $ua = isset($row['unitalias']) ? $row['unitalias'] : '';
                mysqli_query($conn, "INSERT INTO pertanyaanbck (question_id, kd_modul, question, answer, njawab, nim, menit, nourut, unitalias) 
                                    VALUES ($id, '$md_param', '$q', '$ans', '', '$user', '$vmenit', '$no', '$ua')");
                $no++;
            }
        }
        
        $aa = mysqli_query($conn, "SELECT * FROM pertanyaanbck WHERE nim='$user' AND kd_modul='$md_param'");
        ?>

        <table width="95%">
            <tr><th>
                <ul id="pagination">
                    <?php 
                    $i = 1;
                    $amenit = $vmenit;
                    while ($a = mysqli_fetch_array($aa)) {
                        $amenit = $a['menit']; // Ambil menit terakhir dari backup
                        $st = (!empty($a['njawab'])) ? "style='background-color:green;color:white;'" : "";
                        echo "<li id='$i' $st>$i</li>";
                        $i++;
                    }
                    ?>
                </ul>
            </th></tr>
        </table>
        <div id="loading"></div>
        <div id="content"></div>
    </form>
</center>

<SCRIPT>
// =============================================================================
// The files is belong to InVirCom - Fixed for PHP 7/8
// =============================================================================
var TimeOver = true;

// Pengaman: kalau $amenit kosong atau error, kita default-in ke 0 biar gak blank putih
var n = <?php echo (isset($amenit) && !empty($amenit)) ? (int)$amenit : 0; ?>;

function getJam(Tanggal)
{
   var Jam = (Tanggal.getHours() < 10) ? "0" + Tanggal.getHours() + ":" : Tanggal.getHours() + ":";
   Jam += (Tanggal.getMinutes() < 10) ? "0" + Tanggal.getMinutes() + ":" : Tanggal.getMinutes() + ":";
   Jam += (Tanggal.getSeconds() < 10) ? "0" + Tanggal.getSeconds() : Tanggal.getSeconds();
   return Jam;
}

function dispJam()
{
   var TglCur = new Date();
   // Pastikan form User dan input Watch/TimeTaken ada di HTML
   if(document.User && document.User.Watch) {
       document.User.Watch.value = getJam(TglCur);
       document.User.TimeTaken.value = getWaktu(TglCur,TglStart);
       
       if ((Tgl.getTime() - TglCur.getTime()) <= 0)
       {
          if(TimeOver) {
              TimeOver = false; // Set false biar gak alert terus-terusan
              TimeOverWarn();
          }
          document.User.TimeLeft.value = "Habis";
       }
       else
       {
          document.User.TimeLeft.value = getWaktu(Tgl,TglCur);
       }
   }
   setTimeout("dispJam()",1000);
}

function getWaktu(Tgl,TglCur)
{
   var TmLf = Tgl.getTime() - TglCur.getTime();
   var TmLfHours = Math.floor(TmLf/3600000); 
   var TmLfMinutes = Math.floor((TmLf%3600000)/60000);
   var TmLfSeconds = Math.round((TmLf%60000)/1000);
   var TmLfStr = (TmLfHours < 10) ? "0" + TmLfHours + ":" : TmLfHours + ":";
   TmLfStr += (TmLfMinutes < 10) ? "0" + TmLfMinutes + ":" : TmLfMinutes + ":";
   TmLfStr += (TmLfSeconds < 10) ? "0" + TmLfSeconds : TmLfSeconds;
   return TmLfStr;
}

function TimeOverWarn()
{
   alert("Waktu anda sudah habis"); 
   if(document.p1) {
       document.p1.submit();
   }
   return true;
}

//=========================================//

var Tanggal = new Date();
var Tgl = new Date();
var TglStart = new Date();

var ArrayBulan = new Array("Januari","Pebruari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
var Tahun = (Tanggal.getFullYear()); // Pake getFullYear biar gak '126' di browser baru
var TglStr = Tanggal.getDate() + " " + ArrayBulan[Tanggal.getMonth()] + " " + Tahun;

// Set waktu target: waktu sekarang + (n menit)
Tgl.setTime(Tgl.getTime() + n * 60 * 1000);

dispJam();
</SCRIPT>
</body>
</html>
<?php } ?>