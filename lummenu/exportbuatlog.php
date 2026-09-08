<?php
include "../lsp_koneksi.php";
error_reporting(0);
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Data Pegawai.xls");

$resulta="select mak5baru.idskema,mak5baru.idunit, mak5baru.idasesi, mak5baru.idasesor, mak5baru.bk,mak5baru.kelompok, mak5baru.ketmak5, mak5baru.aspek, mak5baru.penolakan, mak5baru.saran, mak5baru.catatan, mak5baru.namaassmak5, mak5baru.namaasesimak5, mak5baru.kodeunittmak5 from mak5baru ";
//JOIN pemetaan ON mak5baru.idasesor = lsp_usertbl.email join unitalias on grade.kelas=unitalias.kdalias where tanggal='".$ftgl."'");
$result=mysql_query($resulta);
//echo $result;
$namafile="LOG";
//echo "<tabel>";
xlsBOF();
xlsWriteLabel(1,1,"HASIL LAPORAN ASESMEN");
xlsWriteLabel(2,1,"ID SKEMA");
xlsWriteLabel(2,2,"ID UNIT");
xlsWriteLabel(2,3,"KODE UNIT");
xlsWriteLabel(2,4,"ID ASESI");
xlsWriteLabel(2,5,"NAMA ASESI");
xlsWriteLabel(2,6,"ID ASESOR");
xlsWriteLabel(2,7,"NAMA ASESOR");
xlsWriteLabel(2,8,"KETERANGAN");
xlsWriteLabel(2,9,"KELOMPOK");
xlsWriteLabel(2,10,"KETERANGAN HASIL");
xlsWriteLabel(2,11,"ASPEK");
xlsWriteLabel(2,12,"PENOLAKAN");
xlsWriteLabel(2,13,"SARAN");




$xlsRow = 3;


while($row=mysql_fetch_array($result)){

	xlsWriteLabel($xlsRow,1,trim($row[idskema]));
	xlsWriteLabel($xlsRow,2,trim($row[idunit])); 
	xlsWriteLabel($xlsRow,3,trim($row[kodeunittmak5]));
	xlsWriteLabel($xlsRow,4,trim($row[idasesi]));
	xlsWriteLabel($xlsRow,5,trim($row[namaasesimak5]));
	xlsWriteLabel($xlsRow,6,trim($row[idasesor]));
	xlsWriteLabel($xlsRow,7,trim($row[namaassmak5]));
	xlsWriteLabel($xlsRow,8,trim($row[bk]));
	xlsWriteLabel($xlsRow,9,trim($row[kelompok]));
	xlsWriteLabel($xlsRow,10,trim($row[ketmak5]));
	xlsWriteLabel($xlsRow,11,trim($row[aspek]));
	xlsWriteLabel($xlsRow,12,trim($row[penolakan]));
	xlsWriteLabel($xlsRow,13,trim($row[saran]));  

$xlsRow++;
	}
xlsEOF();
	
function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;}

function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;}	
	
function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;}


function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;}
?>
