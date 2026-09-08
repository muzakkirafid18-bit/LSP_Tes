<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LSP - Dashboard</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="css/adminstyle.css" rel="stylesheet">
<link href='../css/tombol.css' rel='stylesheet' type='text/css'>
<script src="js/lumino.glyphs.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>
<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script language="javascript"></script>
<script language="javascript">

/*
Auto center window script- Eric King (http://redrival.com/eak/index.shtml)
Permission granted to Dynamic Drive to feature script in archive
For full source, usage terms, and 100's more DHTML scripts, visit http://dynamicdrive.com
*/

var win = null;
function NewWindow(mypage,myname,w,h,scroll){
LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
settings =
'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable'
win = window.open(mypage,myname,settings)
}
</script>
<script type="text/javascript">
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 3000);
</script>
<style>
  .alert {
     width:50%;    
    }
</style>
<link href="../css/tabelbaru2.css" rel="stylesheet">
</head>


<?php

$op = $_GET['op'];
if ($op == "simpanvalidasi1lsp")
 {
   echo "ksjdksdj";
 } 
  
//echo "lksldks";
  include "../lsp_koneksi.php";
  $idasesi=$_GET['idasesi'];
  $tgllsp=$_GET['tgllsp'];
  //echo $idasesi;

 


      $apl1skemasiswa="select skemasiswa.idskema,skemasiswa.emailsiswa,skemasiswa.statusapl1,skemasiswa.statusapl2,skemasiswa.statustest,skema.idskema,skema.namaskema from skemasiswa inner join skema on skemasiswa.idskema = skema.idskema where skemasiswa.statusapl1='N' or skemasiswa.statusapl2='N'";
      $apl1skemasiswaa=mysql_query($apl1skemasiswa);
      $apl1skemasiswaab=mysql_fetch_array($apl1skemasiswaa);
      //echo $apl1skemasiswa;
        
      $apl1abcd="SELECT lsp_usertbl.email as emailusr,lsp_usertbl.linkttd,lsp_usertbl.id, apl1.namasiswa, apl1.email as email,apl1.buktiapl1,apl1.poto FROM lsp_usertbl left JOIN apl1 ON lsp_usertbl.email=apl1.email where apl1.idasesi='".$idasesi."'";
    //$apl1abcd="select namasiswa,validasiapl1 from apl1 where idasesi='".$idasesi."' group by idasesi";
        $apl1abcde=mysql_query($apl1abcd);
        $apl1abcdef=mysql_fetch_array($apl1abcde);

        $linkttda="../imgttd/".$apl1abcdef['linkttd'];
  
    $cekdata0 = "SELECT * FROM rekomendasi WHERE namarekom = 'apl1lsp' and idskema='".$apl1skemasiswaab['idskema']."'and idasesi='$idasesi'";
  //echo $cekdata0;
   $ada0=mysql_query($cekdata0);
   $adax=mysql_fetch_array($ada0);
   
    $lrek=$adax['rekom'];
    $cat=$adax['catatan'];
       if($lrek=='L'){
      $klrek='checked';
     }else{$klrek='';}
     if($lrek=='T'){
      $klrek0='checked';
     }else{$klrek0='';}
   



        //echo $linkttda;
   //echo "<table><tr><td>".$apl1abcdef['namasiswa']."</td><td colspan=2>".$apl1skemasiswaab['namaskema']."</td></tr><tr><td><a href=\"".$_SERVER['PHP_SELF'].
        echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=simpanvalidasi1lsp\">";
        
        echo "<input type=hidden name=idskemalsp value='".$apl1skemasiswaab['idskema']."'>";
        echo "<input type=hidden name=idasesilsp value='".$apl1abcdef['id']."'>";
        echo "<input type=hidden name=tgllsp value=".$tgllsp.">";
        echo "<input type=hidden name=emailusrlsp value='".$apl1abcdef['emailusr']."'>";
echo "<table><tr><td>".$apl1abcdef['namasiswa']."</td><td>".$apl1skemasiswaab['namaskema']."</td></tr>"; /*<tr><td><a href=\"validasiapl1lsp2.php?idasesi=".$idasesi."&idskema=".$apl1skemasiswaab['idskema']."\" ";?>onclick="NewWindow(this.href,'name','700','1300','no');return false" <?php echo "><span class='glyphicon glyphicon-check'>Cek Unit dan Pengajuan Sertifikasi </a></td><td align=left><a href=../siswa/biodatasiswapdf.php?email=" .trim($apl1abcdef['email']) ;?> onclick="NewWindow(this.href,'name','700','1300','no');return false" <?php echo "><span class='glyphicon glyphicon-user'>Tampilkan biodata </a></td></tr>*/ 
echo "</table><table><tr><td  rowspan=4 width=50%><strong>Rekomendasi di isi LSP</strong> </br>Berdasarkan ketentuan persyaratan dasar pemohon,</br>pemohon <strong>(Diterima/Ditolak)</strong>: </br><input type=radio name=lsprekom value='L' $klrek>Diterima</br> <input type=radio name=lsprekom value='T' $klrek0>Ditolak</br>sebagai peserta sertifikasi</td></tr><tr><td colspan=2><strong>Pemohon : </strong> </td></tr><tr><td>Nama  </td><td>".$apl1abcdef['namasiswa']."</td></tr><tr><td> Tanda tangan  </td><td><img src='".$linkttda."'></td></tr></tr><tr><td  rowspan=5 width=50%><strong>Catatan : </strong></br><textarea name=cttlsp rows=3 cols=40 value=>$cat</textarea> </td></tr><tr><td colspan=2>Admin LSP </td></tr><tr><td>Nama </td><td>LSP</td></tr><tr><td>NIK LSP </td><td>909090909</td></tr><tr><td>Tanda Tangan  </td><td>LSP</td></tr><tr><td colspan=3><input type=submit id=gobutton value=Simpan class=button></td></tr> </table></form>";
?>
  </html>


