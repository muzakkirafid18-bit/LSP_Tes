<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<?php 
error_reporting(0);
include "../lsp_koneksi.php";
$pertanyaan=$_POST['pertanyaan'];
$jawaban=$_POST['jawaban'];
$alt1=$_POST['alt_1'];
$alt2=$_POST['alt_2'];
$alt3=$_POST['alt_3'];
$alt4=$_POST['alt_4'];
$idquestionlama=$_POST['kodelama'];
$kodesoal=$_POST['kodesoal'];
echo "<table>"; 
		$a="UPDATE pertanyaan SET question='$pertanyaan',answer='$jawaban',alt_1='$alt1',alt_2='$alt2',alt_3='$alt3',alt_4='$alt4' where question_id='$_POST[kodelama]'";
		$result=mysql_query($a);
$b="Delete from tbloption where question_id='$idquestionlama'";
$b1=mysql_query($b);

$cekid="select * from pertanyaan where question_id='$idquestionlama'";
//echo $cekid;
$cekid1=mysql_query($cekid);
$row=mysql_fetch_array($cekid1);
  	$id = $row["question_id"]; 
	$kd_modul=$row["kd_modul"];
	$question = $row["question"]; 
	$oa=$row["oa"];
	$ob=$row["ob"];
	$oc=$row["oc"];
	$od=$row["od"];
	$oe=$row["oe"];	
	$alt_5=$row["answer"];
	$alt_1=$row["alt_1"];
	$alt_2=$row["alt_2"];
	$alt_3=$row["alt_3"];
	$alt_4=$row["alt_4"];
        $alt_6=$row["njawab"];
	$input=array($alt_1,$alt_2,$alt_3,$alt_4,$alt_5);
	$noption=array($ob,$oc,$od,$oe,$oa);



	for ($j = 0; $j < 5; $j++){  
        //echo "tt".$j;
	$p=$input[$j];
	$o=$noption[$j];
$query2="insert into tbloption (question_id,kd_modul,noption,toption)values($id,'$kd_modul','$o','$p')";
//echo $query2;
$hasil2 = mysql_query($query2);
//$hitung++;
}

              
		//echo $a;
		echo"<script>alert('Sukses ..!');history.go(-2);</script>";
	       // echo "<input type=hidden name=kodesoal value='$kodesoal'>";
               // echo "<tr><th ><input type=submit value=OK></th></tr></table>";	

?>
