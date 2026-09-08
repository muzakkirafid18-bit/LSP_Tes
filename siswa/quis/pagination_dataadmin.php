

<link href='../public/assets/css/tabel3.css' rel='stylesheet' type='text/css'>
<link href='../public/assets/css/radio.css' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="../public/assets/js/jquery.min.js"></script>

<script type="text/javascript" src="../public/assets/js/ddpowerzoomer.js">

/***********************************************
* Image Power Zoomer- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Please keep this notice intact
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for this script and 100s more
***********************************************/

</script>

<script type="text/javascript">

jQuery(document).ready(function($){ //fire on DOM ready
$('#gambarku').addpowerzoom({
		defaultpower: 2,
		powerrange: [2,5],
		largeimage: null,
		magnifiersize: [100,100] //<--no comma following last option!
	})
$('#gambarku_a').addpowerzoom({
		defaultpower: 2,
		powerrange: [2,5],
		largeimage: null,
		magnifiersize: [100,100] //<--no comma following last option!
	})
$('#gambarku_b').addpowerzoom({
		defaultpower: 2,
		powerrange: [2,5],
		largeimage: null,
		magnifiersize: [100,100] //<--no comma following last option!
	})
$('#gambarku_c').addpowerzoom({
		defaultpower: 2,
		powerrange: [2,5],
		largeimage: null,
		magnifiersize: [100,100] //<--no comma following last option!
	})
$('#gambarku_d').addpowerzoom({
		defaultpower: 2,
		powerrange: [2,5],
		largeimage: null,
		magnifiersize: [100,100] //<--no comma following last option!
	})
$('#gambarku_e').addpowerzoom({
		defaultpower: 2,
		powerrange: [2,5],
		largeimage: null,
		magnifiersize: [100,100] //<--no comma following last option!
	})

	/*
	EXAMPLE 1:
	$('#myimage').addpowerzoom()

	EXAMPLE 2:
	$('img.vacation').addpowerzoom({
		defaultpower: 2,
		powerrange: [2,5],
		largeimage: null,
		magnifiersize: [100,100] //<--no comma following last option!
	})
	*/

})

</script>

<script>
function myclick()
{ 
    var kdmdl=document.quis.kmdl.value;
    var vnim=document.quis.nim.value;
    var xlastnom=document.quis.vlastnom.value;
var a=document.quis.idq.value;
d='q'+a; 
var c=document.quis[d].value;
$.post('proses_assesmentbck.php',{jawaban: c, kodemd: kdmdl, usern: vnim, nid: a, ylastnom: xlastnom });

/*alert(c);*/
} 
</script>
<style>
lo{	
background-color:blue;
list-style: none; 
float: left;
width:150px;
font-size:17px;
text-align:center;
/*margin-right: 16px; */
padding:8px;
border:solid 1px white;
color:black; 
}
</style>

<?php
session_set_cookie_params(3600*2,"/");
session_start();
//error_reporting(0);
include "../../lsp_koneksi.php";

$per_page = 1; 

if($_GET)
{
$page=$_GET['page'];
}

?>
<form id="quis" name="quis" action="proses_assesmentbaruabcd.php" method="post" onSubmit="return confirm('Apakah yakin sudah selesai ??')">
<input type="hidden" name="kmdl" value="
<?php if(isset($_SESSION['kd_modul'])) { echo $_SESSION['kd_modul'];} ?>">
<input type="hidden" name="nim" value="
<?php if(isset($_SESSION['username'])) { echo $_SESSION['username'];} ?>">
<input type="hidden" name="nama" value="
<?php if(isset($_SESSION['nama_lengkap'])) { echo $_SESSION['nama_lengkap'];} ?>">
<input type="hidden" name="kelas" value="
<?php if(isset($_SESSION['kelas'])) { echo $_SESSION['kelas'];} ?>">

<input type="hidden" name="vlastnom" value="<?php echo $page;?>">



<?php
if(isset($_SESSION['kd_modul'])) { $kdm=$_SESSION['kd_modul'];} ?>
<?php if(isset($_SESSION['username'])) { $unim=$_SESSION['username'];} ?>
<?php $login="SELECT * FROM useronline WHERE nim='$unim'";
       $login1=mysql_query($login);
       $login2=mysql_fetch_row($login1);
       $pesan1=$login2[5];

?>

<?php 
$periksac=mysql_query("SELECT nim,kodemodul FROM gradealias WHERE nim='$unim' AND kodemodul='$kdm'");
//echo "kjksjdk"$periksac; //ini gatau knp error
$periksacc=mysql_num_rows($periksac);
$periksaccc=$periksacc+1;?>
<input type="text" name="kounter" value="<?php echo $periksaccc ; ?>">
<?php 

//get table contents
$start = ($page-1)*$per_page;
/**$sql = "select * from pertanyaanbck where kd_modul='$kdm' and nim='$unim' order by nourut limit $start,$per_page";
$rsd1 = mysql_query($sql);
$cekw="aku";
//$cekwaktu = mysql_fetch_array($rsd1);
//$cekwaktu2=$cekwaktu["menit"];
$sql2 = "select count(njawab) as terjawab from pertanyaanbck where kd_modul='$kdm' and nim='$unim'";
$rsd2 = mysql_query($sql2);
$terj1=mysql_fetch_array($rsd2);
$terj2=$terj1["terjawab"];**/

//echo $terj2;
?>
<table width="95%" >
  	<?php
  	$resultv=("SELECT * FROM modul WHERE kd_modul='$kdm'");
  	$resultvv=mysql_query($resultv);
  	//echo $resultv;
  	$baris=mysql_fetch_array($resultvv);
        $vmenit=$baris["Waktu"];
        $vjmlsoal=$baris["jumlah_soal"];
        $vacakpg=$baris["acak"];
  	//echo "ll".$vjmlsoal;
  	?>
	<input type="hidden" name="md" value="<?php echo $_GET[md] ; ?>">
	 <?php 
	 $kdmx=substr($kdm,3,5);
	 //echo "llll".$kdmx;
	if($vacakpg=='1'){        
	$sql = "select * from pertanyaanbck where kd_modul='$kdm' and nim='$unim' limit $start,$per_page";
	 
	 } 
	 else 
         { 
	 $sql = "select * from pertanyaanbck where kd_modul='$kdm' and nim='$unim' order by nourut limit $start,$per_page";
         }

     //echo "klks".$sql;
	$rsd1 = mysql_query($sql);
	$cekw="aku";
	//$cekwaktu = mysql_fetch_array($rsd1);
	//$cekwaktu2=$cekwaktu["menit"];
	$sql2 = "select count(njawab) as terjawab from pertanyaanbck where kd_modul='$kdm' and nim='$unim'";
	$rsd2 = mysql_query($sql2);
	$terj1=mysql_fetch_array($rsd2);
	$terj2=$terj1["terjawab"];
  
  while (($row=mysql_fetch_array($rsd1))) {
  	$id = $row["question_id"]; 
	$kd_modul=$row["kd_modul"];
	$question = $row["question"]; 
	//$alt_5=$row["answer"];
	//$alt_1=$row["alt_1"];
	//$alt_2=$row["alt_2"];
	//$alt_3=$row["alt_3"];
	//$alt_4=$row["alt_4"];
        $alt_6=$row["njawab"];
	//$input=array($alt_1,$alt_2,$alt_3,$alt_4,$alt_5);
	//sort($input, SORT_STRING);
	//srand ((float)microtime()*1000000);
	//shuffle($input);
        $unitalias=$row["unitalias"];
 ?>

<tr><th colspan="4"> 

<?php echo "<center><font color=red>".$pesan1."</font></center>"; ?>
</th></tr>
<tr>

<?php if ($page==$vjmlsoal) {?>
<th colspan="4"><font color=black size=5><?php echo "NO."." $page </font>"."$menit"; ?>&nbsp&nbsp&nbsp<input id="gobutton" type="submit" value="Kirim"></th></tr>
<?php }  
else {?>
<th bgcolor="#00CCCC" colspan="4"><font color=black size=5><?php echo "NO."." $page </font>"."$menit"; ?></th></tr><?php } ?>
<tr>
	<!--<th width="2%" height="30"> 
	<?php $num++; echo"$page"."."; ?>-->
         <input type ="hidden" name="idq" value="<?php echo $id ?>" >
        <?php //echo"$id";?>
       	</th>
	<td height="30 " style="border: 1px solid #ffffFF;" height="30" colspan="4" align="left">
        <?php echo" &nbsp&nbsp$question";?>
      
	</td>

</tr>
<tr><td colspan="3">&nbsp</td></tr>
<?php
 if($vacakpg=='1'){
   $sql5 = "select * from tbloption where kd_modul='$kdm' and question_id=$id order by rand()"; //and nim='$unim' limit $start,$per_page";
   } else {
    $sql5 = "select * from tbloption where kd_modul='$kdm' and question_id=$id"; //order by rand()"; //and nim='$unim' limit $start,$per_page";
   }
//echo $rsd5;
   //cho $sql5;
$rsd5 = mysql_query($sql5);
while (($row5=mysql_fetch_array($rsd5))) {
$r=$row5["noption"];
$s=$row5["toption"];
?>
<div>
<tr></tr>
<tr>
    <td width="2%" height="30" style="border: 1px solid #ffffFF;"></td>
    <td width="2%" height="30" style="border: 1px solid #ffffFF;">
      <input name="<?php echo 'q'.$id; ?>" type="radio" value="<?php echo $r;?>" <?php if($r==$alt_6){ echo checked ;}?> onclick="myclick()">
</div>	
     </td>
    <td width="94%" height="30" align="left" style="border: 1px solid #ffffFF;" >
	<?php echo $s;?>
	</td>
     <!--oopppop-->
     <td width="94%" height="30" align="left" style="border: 1px solid #ffffFF;" >
	<!--<?php echo $r;?>-->
	</td>

    
</tr>

  <?php 

}?>  

<tr><td colspan="4">&nbsp</td></tr>
<?php
}
?>
</table>
</form> 
<script type="text/javascript" src="../public/assets/js/dropdowncontent.js">
</script>
<style>
.tooltip {
    position: relative;
    display: inline-block;
    border-bottom: 0px dotted black;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width:150px;
    background-color: black;
    color: #fff;
    text-align: left;
    border-radius: 6px;
    padding: 3px 0;

    /* Position the tooltip */
    position: absolute;
    z-index: 1;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>


<!-- mulai

<p align="right" >Lihat Sudah dijawab<a href="#" id="contentlink" rel="subcontent2"><img src="../public/assets/img/show.png" height="25"></a> </p>
<DIV id="subcontent2" style="position:absolute; visibility: hidden; border: 0px solid black;  width: 700px; height: 400px; padding: 0px;z-index:1000">
<div style="width: 100%; float: left">
<?php

$sql1 = "select * from pertanyaanbck where kd_modul='$kdm' and nim='$unim'";
$rsd2 = mysql_query($sql1);
?>
<table width="70%" border="1px"> 

  <?php 
$number=1; 
$a=1;
  while (($row=mysql_fetch_array($rsd2))) {
  	$id = $row["question_id"]; 
	$kd_modul=$row["kd_modul"];
	$question = $row["question"]; 
	$alt_5=$row["answer"];
	$alt_1=$row["alt_1"];
	$alt_2=$row["alt_2"];
	$alt_3=$row["alt_3"];
	$alt_4=$row["alt_4"];
        $alt_6=$row["njawab"];
	$input=array($alt_1,$alt_2,$alt_3,$alt_4,$alt_5);
	//sort($input, SORT_STRING);
	//srand ((float)microtime()*1000000);
	//shuffle($input);
if(!empty($alt_6)){
$warna="green";
//$j=$alt_6;}
$j="OK";}
else{
$warna="red";
$j="Belum terjawab";}
?>

<?php 
if(($number%10)==1){
echo "<tr>";
}
echo "<td bgcolor=$warna border=1><div class=tooltip>" .$number."<span class=tooltiptext>".$j."</span>
</div></td>";

?>

  
<?php

//if(($number%10)==0){
//echo "</tr>";
//}
$number++;
}
?>
</table >
<table >
<?php 
$sisa=$vjmlsoal-$terj2;
?>
<tr><td bgcolor="#0B4C5F">Ket: </td><td bgcolor="green"></td></tr> 
<tr><td colspan="3" bgcolor="#01A9DB">*) Gerakan mouse ke nomor untuk lihat jawaban</br>Klik simbol di pojok kanan untuk sembunyikan tabel ini</td></tr></table>

<!--<a href="javascript:dropdowncontent.hidediv('subcontent2')">Tutup</a>
</div>-->




<div align="right"></div>

</DIV>


<script type="text/javascript">
//Call dropdowncontent.init("anchorID", "positionString", glideduration, "revealBehavior") at the end of the page:

dropdowncontent.init("searchlink", "right-bottom", 500, "mouseover")
dropdowncontent.init("contentlink", "left-top", 300, "click")

</script>
<!--end --->



