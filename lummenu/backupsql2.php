<?php
error_reporting(0);
//include "../parser-php-version.php";
include "../lsp_koneksi.php";
backup_tables($dbhost,$userdb,$passworddb,$database);//host-name,user-name,password,DB name
echo "<br/>Done";

/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name)
{
$return = "";
$link = mysql_connect($host,$user,$pass);
mysql_select_db($name,$link);
//get all of the tables
if($_POST['nmtabel']=='upload'){
	$tables='upload,apl1';
	$backup_name='upload_apl1';}
elseif ($_POST['nmtabel']=='apl2'){
	$tables='apl2';
	$backup_name='apl2';}
elseif ($_POST['nmtabel']=='uas'){
	$tables='unitsiswa';
	$backup_name='unitsiswa';}
elseif ($_POST['nmtabel']=='grade'){
	$tables='grade';
	$backup_name='grade';}    
elseif ($_POST['nmtabel']=='us'){
	$tables='lsp_usertbl';
	$backup_name='user';}
elseif ($_POST['nmtabel']=='pertanyaan'){
	$tables='pertanyaan';
	$backup_name='pertanyaan';}
elseif ($_POST['nmtabel']=='kdsoal'){
	$tables='modul';
	$backup_name='kdsoal';}
elseif ($_POST['nmtabel']=='opt'){
	$tables='tbloption';
	$backup_name='tbloption';}

else {
      $tables='unit,tuk,subelemen,statuskerja,skemasiswa,skema,settanggal,rekomendasi,rekappraktek,qmak7,qmak5,praktek,permohonan,pemetaan,onoff,mak7,mak6,mak5,mak4rekom,mak4,elemen';
      $backup_name='filependukung';} 


//$tables=$_POST['nmtabel'];
if($tables == '*')
{
$tables = array();
$result = mysql_query('SHOW TABLES');
while($row = mysql_fetch_row($result))
{
$tables[] = $row[0];
}
}
else
{
$tables = is_array($tables) ? $tables : explode(',',$tables);
}
//cycle through
foreach($tables as $table)
{
$result = mysql_query('SELECT * FROM '.$table);
$num_fields = mysql_num_fields($result);
$return.= 'DROP TABLE '.$table.';';
$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
$return.= "\n\n".$row2[1].";\n\n";
while($row = mysql_fetch_row($result))
{
$return.= 'INSERT INTO '.$table.' VALUES(';
for($j=0; $j<$num_fields; $j++)
{
//echo $row[$j];	
//$row[$j] = addslashes($row[$j]);
//$row[$j] = preg_replace("\n","\\n",$row[$j]);
if (isset($row[$j])) { $return.= "'" . mysql_real_escape_string($row[$j], $link) . "'"; } else { $return.= 'NULL'; }
if ($j<($num_fields-1)) { $return.= ','; }
}
$return.= ");\n";
}
$return.="\n\n\n";
}
//if($_POST['nmtabel']=='apl1'){
//$backup_name='upload_apl1';}
 // else {$backup_name='filependukung';}
//save file
$tanggal=date("dmY");
$namebck='backupdata/db-backup-'.time().'-'.$tanggal.'-'.$backup_name.'.sql';
$handle = fopen('backupdata/db-backup-'.time().'-'.$tanggal.'-'.$backup_name.'.sql','w+');
// echo $return;
fwrite($handle,$return);
fclose($handle);
if (file_exists($namebck)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($namebck).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($namebck));
    readfile($namebck);
    unlink($namebck);
    exit;
}
}
?>
