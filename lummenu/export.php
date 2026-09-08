<?php
include "../lsp_koneksi.php";
$ffkdmodul=$_POST['kdmodulf'];
$ffkelas=$_POST['kelasf'];
$ftgl=$_POST['tanggal'];

//if (!empty($ftgl)){
$result=mysql_query("SELECT grade.kd_modul, grade.modul,grade.nim, grade.salah, grade.benar, grade.jumlah_soal, grade.grade, grade.tanggal, lsp_usertbl.nama,grade.kelas,grade.kkm,unitalias.namaalias,unitalias.nmasli
FROM grade
JOIN lsp_usertbl ON grade.nim = lsp_usertbl.email join unitalias on grade.kelas=unitalias.kdalias where tanggal='".$ftgl."'");
$namafile="Nilai_".$ftgl;

//}else{
//$result=mysql_query("select * from grade where kd_modul='".$ffkdmodul."' AND kelas='".$ffkelas."'");
//$namafile=$ffkelas."_".$ffkdmodul;

//}


function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;}
function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;}
function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;}
function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;}
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=".$namafile.".xls ");
header("Content-Transfer-Encoding: binary ");
xlsBOF();
xlsWriteLabel(0,0,"DAFTAR NAMA SISWA DAN NILAI ");
xlsWriteLabel(2,0,"User");
xlsWriteLabel(2,1,"Nama");
xlsWriteLabel(2,2,"Kd Unit");
xlsWriteLabel(2,3,"Nama Unit");
xlsWriteLabel(2,4,"Salah");
xlsWriteLabel(2,5,"Benar");
xlsWriteLabel(2,6,"Nilai");
xlsWriteLabel(2,7,"JML Soal");
xlsWriteLabel(2,8,"Tanggal");
xlsWriteLabel(2,9,"KdAlias");
xlsWriteLabel(2,10,"Kdmodul");
xlsWriteLabel(2,11,"skema");
xlsWriteLabel(2,12,"KKM");



//xlsWriteLabel(2,8,"Kelas");
$xlsRow = 3;
while($row=mysql_fetch_array($result)){ 
xlsWriteNumber($xlsRow,0,strval($row['nim']));
xlsWriteLabel($xlsRow,1,$row['nama']);
xlsWriteLabel($xlsRow,2,$row['nmasli']);
xlsWriteLabel($xlsRow,3,$row['namaalias']);
xlsWriteLabel($xlsRow,4,$row['salah']);
xlsWriteLabel($xlsRow,5,$row['benar']);
xlsWriteLabel($xlsRow,6,$row['grade']);
xlsWriteLabel($xlsRow,7,$row['jumlah_soal']);
xlsWriteLabel($xlsRow,8,$row['tanggal']);
xlsWriteLabel($xlsRow,9,$row['kelas']);
xlsWriteLabel($xlsRow,10,$row['kd_modul']);
xlsWriteLabel($xlsRow,11,$row['modul']);
xlsWriteLabel($xlsRow,12,$row['kkm']);
//xlsWriteLabel($xlsRow,8,$row['kelas']);
$xlsRow++;
}
xlsEOF();
exit();
?>
