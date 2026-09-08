<link href='../css/tabel3.css' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="../js/dropdowncontent.js"></script>
<script type="text/javascript" src="../js/textsizer.js">

/***********************************************
* Document Text Sizer- Copyright 2003 - Taewook Kang.  All rights reserved.
* Coded by: Taewook Kang (http://www.txkang.com)
* This notice must stay intact for use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script type="text/javascript">
var tenth='';function ninth() {
if (document.all) {(tenth);alert("Tidak diperbolehkan Klik kanan"); return false;}}
function twelfth(e) {
if (document.layers||(document.getElementById&&!document.all)) {
if (e.which==2||e.which==3) {(tenth);return false;}}}
if (document.layers) {document.captureEvents(Event.MOUSEDOWN);document.onmousedown=twelfth;}
else{document.onmouseup=twelfth;document.oncontextmenu=ninth;}
document.oncontextmenu=new Function('alert("Tidak diperbolehkan Klik kanan"); return false')



//Cek tombol yang di pijit tidak diijinkan ALT / Windows/ TAB langsung logout

/*** batal fungsi tombol
  document.onkeydown=function(e) {
    e=e||window.event;
    if (e.keyCode === 115 ) { 
      e.keyCode = 0;
      alert("Tombol ini tidak diijinkan F4");
      if(e.preventDefault)e.preventDefault();
      else e.returnValue = false;
      return false;
    }
    if(e.keyCode === 18) {
      e.keyCode = 0;
      logoutck()
      return false;}

	if(e.keyCode === 91) {
      e.keyCode = 0;
      logoutck()
      return false;} 

     if (e.keyCode === 9 ) {
      e.keyCode = 0;
      logoutck()
      return false;
    }
     if (e.keyCode === 32 ) {
      e.keyCode = 0;
      alert("Tombol ini tidak diijinkan 32");
      if(e.preventDefault)e.preventDefault();
      else e.returnValue = false;
      return false;
    }
 if (e.keyCode === 8 ) {
      e.keyCode = 0;
      alert("Tombol ini tidak diijinkan 8");
      if(e.preventDefault)e.preventDefault();
      else e.returnValue = false;
      return false;
    }
  }
function logoutck() {
       alert("Anda menekan tombol yang tidak diijinkan ... anda akan di keluarkan dari sistem");
       window.location.href = 'logoutck.php'
}
**//
</script>

<style>

#clock {
/*	background-color:#000;
	border:#999 2px inset;
	padding:6px;
	color:#0FF;
	font-family: 'Orbitron', sans-serif;
    font-size:16px;
    font-weight:bold;
	letter-spacing: 2px;
	display:inline;
*/
padding:1px,1px,1px,1px;
font-weight:bold;
color:green;
background-color:#00CCCC;
/*letter-spacing: 1px;*/
border:solid 0px white;
font-size:25px;
font-family:"Arial Balck", Arial, serif;
}
</style>


<?php 
session_set_cookie_params(3600*2,"/");
session_start();
error_reporting(0);
include "../lsp_koneksi.php";
//$a=$_GET[md];
//echo $a;
if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "<link href='css/adminstyle.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=index.php><b>LOGIN</b></a></center>";
}
else{


$user=$_SESSION['username'];

$waktuk=time();
//PERIKSA APAKAH USER UDAH UJIAN ATAU BELUM
$periksaa="SELECT nim,kd_modul FROM grade WHERE nim='$user' AND kd_modul='$_GET[md]'";
$periksa=mysql_query($periksaa);
//echo $periksaa;

        $periksa3=mysql_num_rows($periksa);
	$result=mysql_query("SELECT * FROM modul WHERE kd_modul='$_GET[md]'");
  	$baris=mysql_fetch_array($result);
        $batas=$baris['batas'];
        $status=$baris['status_soal'];
        

 if ($status=='aktif'){
if($periksa3 >=$batas){ //ganti angka satu jika variabel cekmodul, cekmodul1 dan variabel batas diaktifkan
echo"<center><table><tr><th><H4> Batas ujian soal ini adalah  ' $batas ' kali,  Anda telah mengikuti ujian pada modul ini ' $periksa3 'kali </H4></th></tr>
<tr><td><center><img src=../images/people.png /></center></td></tr></table></center>";
?>
<center>
</center>
<?php }else{ 
$insertSQL="insert into statuskerja (nim, statusk,waktu) values ('$user','Y',$waktuk)";
//echo $insertSQL;
mysql_query($insertSQL);
?>

<?php
session_set_cookie_params(3600*2,"/");
session_start();
error_reporting(0);
include "../lsp_koneksi.php";
$per_page = 1; 

//getting number of rows and calculating no of pages
$sql = "select * from pertanyaan where kd_modul='$_GET[md]'";
$rsd = mysql_query($sql);
$count = mysql_num_rows($rsd);
$pages = ceil($count/$per_page)


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>....</title>
	
	<script type="text/javascript" src="../css/jquery.min.js"></script>
	<script type="text/javascript">
	
	$(document).ready(function(){
		
	//Display Loading Image
	function Display_Load()
	{
	    $("#loading").fadeIn(900,0);
		$("#loading").html("<img src='../images/bigLoader2.gif' />");
	}
	//Hide Loading Image
	function Hide_Load()
	{
		$("#loading").fadeOut('slow');
	};
	

   //Default Starting Page Results
   
	$("#pagination li:first").css({'color' : '#FF0084'}).css({'border' : 'solid #dddddd 1px'}).css({'background-color' : 'yellow'});
	
	Display_Load();
	
	$("#content").load("pagination_data.php?page=1", Hide_Load());



	//Pagination Click
	$("#pagination li").click(function(){
			
		Display_Load();
		
		//CSS Styles
		$("#pagination li")
		.css({'border' : 'solid white 1px'})
		.css({'color' : 'black'})
		.css({'background-color' : 'yellow'})
		


		
		$(this)
		.css({'color' : '#FF0084'})
		.css({'border' : 'solid #FF0084 1px'})
		.css({'background-color' : 'yellow'});


		//Loading Data
		var pageNum = this.id;
		var mmenit=document.User.TimeLeft.value;
		var vkdmdl=document.p1.kmdl.value;
		var vnim=document.p1.nim.value;
                $.post('updatewaktu.php',{vmenit: mmenit, kodemd: vkdmdl, usern: vnim });
                //alert(vkdmdl);
  		
                $("#content").load("pagination_data.php?page=" + pageNum, Hide_Load());
		
	});
	
	
});


	</script>


<style>
body { margin: 0; padding: 0; font-family:Verdana; font-size:13px }

a
{
text-decoration:none;
color:#B2b2b2;

}

a:hover
{

color:#DF3D82;
text-decoration:underline;

}
#loading { 
width: 100%; 
position: absolute;
}

#pagination
{
padding:5px;
text-align:left;

/*margin-left:1px;*/

}
li{	
background-color:yellow;
list-style: none; 
float: left; 
/*margin-right: 16px; */
padding:2px;
border:solid 1px white;
color:black; 
}
li:hover
{ 
color:#FF0084; 
cursor: pointer; 
}

h1 {font-size:180%;}
h2 {font-size:150%;}
h3 {font-size:125%;}
h4 {font-size:100%;}
h5 {font-size:90%;}
h6 {font-size:80%;}


</style>
</head>

<body>

<center>
<img src=../images/logo.png height="80px">
<?php $_SESSION['kd_modul']=$baris[kd_modul] ?>
<table><tr ><!--<th>SOAL <?php echo $baris[modul];?></th>--> 
<?php echo "<td rowspan=2 align=right>&nbsp&nbsp <img src=../images/people.png height=30> </td></tr><tr><td></td><td align=left rowspan=2>$_SESSION[nama_lengkap] </td><td>&nbsp&nbsp</td>"?>
<!--<?php echo "<th> NIS : "; if(isset($_SESSION['username'])) { echo trim($_SESSION['username']);} ?>-->

<form name=User> 
<th> Waktu Sisa : </th>
<th>
<INPUT id=clock name=TimeLeft size=8></font> 
<INPUT name=TimeTaken type=hidden>
<INPUT name=Watch type=hidden></th>
<td align="left">&nbsp&nbsp<a href="javascript:ts('body',1)"><strong>+A</a> || <a
href="javascript:ts('body',-1)">-A</strong></a>&nbsp&nbsp</td></tr>
</table>
</table></center>
</FORM>
<form id="p1" name="p1" action="proses_assesmentbaru.php" method="post" onSubmit="return confirm('Apakah yakin sudah selesai ??')">
<?php


  	$result=mysql_query("SELECT * FROM modul WHERE kd_modul='$_GET[md]'");
  	$baris=mysql_fetch_array($result);
        $vmenit=$baris["Waktu"];
  	?>

<?php if(isset($_SESSION['username'])) { $su=$_SESSION['username'];} ?> 
<input type="hidden" name="nim" value="
<?php if(isset($_SESSION['username'])) { echo $_SESSION['username'];} ?>">
<input type="hidden" name="nama" value="
<?php if(isset($_SESSION['nama_lengkap'])) { echo $_SESSION['nama_lengkap'];} ?>">
<input type="hidden" name="kmdl" value="
<?php if(isset($_SESSION['kd_modul'])) { echo $_SESSION['kd_modul'];} ?>">
<input type="hidden" name="kelas" value="
<?php if(isset($_SESSION['kelas'])) { echo $_SESSION['kelas'];} ?>">
<?php 
$periksa4=$periksa3+1;?>
<input type="hidden" name="kounter" value="<?php echo $periksa4 ; ?>">


<?php 
$cekada  = mysql_query("SELECT * FROM pertanyaanbck WHERE nim='$su' AND kd_modul='$_GET[md]'");
      $cekada1 = mysql_num_rows($cekada);
      //echo $cekada1; 
     if ($cekada1 < 1  ){
     $del = mysql_query("DELETE FROM pertanyaanbck where kd_modul='$_GET[md]' AND nim='$su'"); 
     $acak = array();
     while (($row=mysql_fetch_array($rsd))) {
        array_push($acak, $row);
        }
        shuffle($acak);
        foreach( $acak as $row ) {
        
  	$id = $row["question_id"]; 
	$kd_modul=$row["kd_modul"];
	$question = $row["question"]; 
	$alt_5=$row["oa"];
	//$alt_1=$row["alt_1"];
	//$alt_2=$row["alt_2"];
	//$alt_3=$row["alt_3"];
	//$alt_4=$row["alt_4"];
        //srand ((float)microtime()*1000000);
//$tambahkan = mysql_query ("INSERT INTO pertanyaanbck (question_id,kd_modul,question,answer,alt_1,alt_2,alt_3,alt_4,nim,menit) VALUES ($id,'$kd_modul','$question','$alt_5','$alt_1','$alt_2','$alt_3','$alt_4','$su','$vmenit')");
$tambahkan = mysql_query ("INSERT INTO pertanyaanbck (question_id,kd_modul,question,answer,nim,menit) VALUES ($id,'$kd_modul','$question','$alt_5','$su','$vmenit')");
 
} 
     } ?>

<?php $resulta="SELECT * FROM pertanyaanbck WHERE nim='$su' AND kd_modul='$_GET[md]'";
       
  	$aa=mysql_query($resulta);
        $a=mysql_fetch_array($aa);
        $amenit=$a["menit"]; 
	//echo $amenit;
?>
        

	<div align="center">				
<table width="95%">
<th>
			<ul id="pagination">
				<?php
				//Show page links
				for($i=1; $i<=$pages; $i++)
				{      
				       	echo '<li id="'.$i.'">'.$i.'</li>';
				}
				?>
        
	</ul>	</th></table>
		
        <div id="loading" ></div>
	<div id="content" ></div>



</form>
</body>
</html>
<?php } 
}else{echo"Tidak ada soal aktif ..!";}
}
?>
<SCRIPT>
// =============================================================================
// =============================================================================
// The files is belong to InVirCom
// You can copy it or print it, but do not edit/delete 
// the messages and the copyright above and below this page
// Created by InVirCom (C) July 1998, Last edited May 2002 modify by aris3t in 2005
// banksoal@invir.com
// http://www.invir.com
// =============================================================================
// =============================================================================
var TimeOver = true
var n=<?=$amenit ?>

function getJam(Tanggal)
{
   Jam =  (Tanggal.getHours() < 10) ? "0"  + Tanggal.getHours()  + ":" : Tanggal.getHours() + ":"
   Jam += (Tanggal.getMinutes() < 10) ? "0" + Tanggal.getMinutes() + ":" : Tanggal.getMinutes() + ":"
   Jam += (Tanggal.getSeconds() < 10) ? "0" + Tanggal.getSeconds() : Tanggal.getSeconds()
   return Jam
}

function dispJam()
{
   TglCur = new Date()
   document.User.Watch.value = getJam(TglCur)
   document.User.TimeTaken.value = getWaktu(TglCur,TglStart)
    
   if ((Tgl.getTime() - TglCur.getTime()) <= 0)
   {
      if(TimeOver) TimeOverWarn()
      document.User.TimeLeft.value = "Habis"
   }
   else
      document.User.TimeLeft.value = getWaktu(Tgl,TglCur)
   setTimeout("dispJam()",1000)
 
}

function getWaktu(Tgl,TglCur)
{
   TmLf = Tgl.getTime() - TglCur.getTime()
   TmLfHours = Math.floor(TmLf/3600000) 
   TmLfMinutes = Math.floor((TmLf%3600000)/60000)
   TmLfSeconds = Math.round((TmLf%60000)/1000)
   TmLfStr = (TmLfHours < 10) ? "0" + TmLfHours + ":" : TmLfHours + ":"
   TmLfStr += (TmLfMinutes < 10) ? "0" + TmLfMinutes + ":" : TmLfMinutes + ":"
   TmLfStr += (TmLfSeconds < 10) ? "0" + TmLfSeconds : TmLfSeconds
   return TmLfStr
}

function TimeOverWarn()
{
   
	  alert("Waktu anda sudah habis"); 
	  document.p1.submit()
	  TimeOver = true
	  return true
}

//=========================================//


Tanggal =  new Date()
Tgl = new Date()
TglStart = new Date()

ArrayBulan = new Array("Januari","Pebruari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember")
Tahun = (Tanggal.getYear()  <= 99) ? "19"  + Tanggal.getYear() : Tanggal.getYear()
TglStr = Tanggal.getDate()  + " " + ArrayBulan[Tanggal.getMonth()] + " " + Tahun

Tgl.setTime(Tgl.getTime() + n * 60 * 1000)  // n adalah menit asli 120 menit = 120 * 60 * 1000 jadi 1 menit

dispJam()

</SCRIPT>

