


<form name="contoh" method="post" action="apl2postsatuphoto.php" enctype="multipart/form-data" id="form-upload" onSubmit="return confirm('Apakah yakin sudah MEMILIH semua dan ingin disimpan ??')">
<table class=demo-table>
<?php
    $emailuser=trim($uname);

	$sql="select * from lsp_usertbl where email='$emailuser'";
	$shasil=mysql_query($sql);
	$sdata=mysql_fetch_array($shasil);
	$namap=$sdata['nama'];
	$idp=$sdata['id'];
    $tgluser=$sdata['kode'];
	
	$cek="select * from skemasiswa where emailsiswa='$emailuser' and statustesp='N' and statusapl2='N' "; 
	$ada=mysql_query($cek);
	if(mysql_num_rows($ada)>0){
		$data  = mysql_fetch_array($ada);
	$skema=$data['idskema'];
	$statusapl1=$data['statusapl1'];
	$ckrekomendasiapl2="Select idskema,idasesi,rekom,tanggal from rekomendasi where idasesi='".$idp."' and idskema='".$skema."'";
	if($statusapl1=='Y')
	 {
        $spemet="select * from pemetaan where idskema='$skema' and idpeserta='$idp'";
        $execpe=mysql_query($spemet);
        $hpemet=mysql_fetch_array($execpe);
        $namaass=$hpemet["namaasesor"];
        //$tgl=$hpemet["tanggal"];
  $ske="select * from skema where idskema='$skema'";  
  $ske1=mysql_query($ske);
  $ske2=mysql_fetch_array($ske1);
  $namaskema=$ske2['namaskema'];
  echo "<table class=demo-table><tr><td colspan=8>FR-APL-02 ASESMEN MANDIRI</td></tr><tr><td colspan=4>
        Nama Peserta : $namap </td><td colspan=4> </td></tr><tr>
        <td colspan=4>Nama Asesor : $namaass<td colspan=4></td></tr>             
        <tr><td colspan=8> Pada Bagian ini anda di minta untuk menilai diri sendiri terhadap unit kompetensi yang akan di ases</td></tr><tr> 
       <td colspan=8>1. Pelajari seluruh standar Kriteria Unjuk Kerja  (KUK), batasan variabel, panduan penilaian dan aspek kritis serta yakinkan bahwa anda sudah benar-benar memahami seluruh isinya.</br>
2. Laksanakan penilaian mandiri dengan mempelajari dan menilai kemampuan yang anda miliki secara obyektif terhadap seluruh daftar pertanyaan yang ada, serta tentukan apakah sudah kompeten (K) atau belum kompeten (BK). </br>  
3. Siapkan bukti-bukti yang anda anggap relevan terhadap unit kompetensi, serta ‘matching’-kan setiap bukti yang ada terhadap setiap elemen/KUK, konteks variable, pengetahuan dan keterampilan yang dipersyaratkan serta aspek kritis</br>
4. Asesor dan asesi menandatangi form Asesmen Mandiri.</td></tr>";
        
    //$squnit="select * from unit where idskema='$skema'";
    //$eunit=mysql_query($squnit);
    //echo $squnit;
    $sqlunit="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$skema' and unitsiswa.idadsesi='$idp' order by unitsiswa.idunit";
    $eunit=mysql_query($sqlunit);
      $i=0;
    echo "<input type=hidden name=idskema value='$skema'>";
    echo "<input type=hidden name=idadsesi value='$idp'>";
    echo "<input type=hidden name=email value='$emailuser'>";
     while ($dunit=mysql_fetch_array($eunit))
    {
   
     
       $sqelemen="select * from elemen where idunit='".$dunit['idunit']."' and idskema='$skema'";
       $eelemen=mysql_query($sqelemen);
      echo "<tr><th colspan=8>".$dunit['namaunit']."</th></tr>";
         $y=0;
        while ($delemen=mysql_fetch_array($eelemen))
      	{
          $y++;
           	
           $sqsubelemen="select * from subelemen where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema'";
            $esubelemen=mysql_query($sqsubelemen);


            $x=0;
                        echo "<tr><th colspan=8>".$delemen['namaelemen']."</th></tr>";
                        echo "<tr><td colspan=3><strong>NO</strong></td><td><strong>SUB KOMPETENSI</strong></td><td><strong>K</strong></td><td><strong>BK</strong></td><td colspan=2><strong>BUKTI  PENDUKUNG</strong> </td></tr>"; 
                        
			while ($dsubelemen=mysql_fetch_array($esubelemen))
			{
              $idsube=$dsubelemen['idsubelemen'];
              $x++;
           
               echo"<tr><td colspan=3>".$y.".".$x."</td><td>".$dsubelemen['pertanyaan']."<input type=hidden name=idunit".$i." value=".$dunit['idunit']."><input type=hidden name=idelemen".$i." value=".$delemen['idelemen']."></td><td><input type=radio name=bk".$i." value=k><br/></td><td><input type=radio name=bk".$i." value=bk></td><td><input type=hidden name=idsube".$i." value='$idsube'></td>";?></td><td><input type="file" name="fotox2[]" disabled="disabled"/></td>
       <?php

             $i++;     
                       }
        
 	}
      }?>
       </tr>
       <tr><td colspan="9">
       <div class="form-group"><strong>Tanggal Pelaksanaan :</strong>
    	<input type="text" name="tglpelapl2" id="tanggal" placeholder="Tanggal Pelaksaan" class="form-control" required/>
        </div>
       <div class="form-group"><strong>Tanggal Regestrisai :</strong>
    	        <input type="text" name="tglregapl2" placeholder="Tanggal Reg" class="form-control" value=" <?php echo $tgluser ; ?>" readonly/>
                </div>
         
	<!--<div class="form-group"><strong>Tanggal : (Sesuaikan dengan jadwal)</strong>
        	<select class="form-control" name="tanggal" placeholder="Skema">
          	<?php
          	$tampil=mysql_query("SELECT * FROM permohonan where email='$emailuser' group by tanggal ");
		while($r=mysql_fetch_array($tampil)){
			echo "<option value=$r[tanggal]>$r[tanggal]</option>";
		}
		echo "</select"; 
		?>--></td></tr>
		<tr><td colspan="9"><input type="file" name="foto" required /><font color=red>File Berbentuk PDF dengan ukuran Maks 1MB dan namafile jangan menggunakan spasi</font></td></tr>
 		<tr><td  colspan="9"><input type="hidden"  name="n" value="<?php echo $i ;?>"/>
		<input type="submit" name="upload-foto" id="gobutton" value="Simpan"></td></tr> <?php
      }else {?>				
				<div class="container">
			  	<div class="alert alert-warning">
			    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    	<strong>APL 1 Belum di validasai / Tidak Ada data  ! </strong> <font color="red">Hubungi Asesor
</font>
			  	</div>
				</div>
				<?php }
} else { ?>				
				<div class="container">
			  	<div class="alert alert-warning">
			    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    	<strong>Anda belum memilih skema</strong> ....
				</div>
				<?php  }

?>
</table>




</table>
	</form>

	<!-- untuk progress bar -->
	<div id="progress">
	<div id="bar"></div>
	<div id="percent">0%</div>
	</div>
	<br/>
	<!-- pesan setelah proses upload -->
	<div id="message"></div>

</div>

<script>

$(document).ready(function() {

var options = {
	beforeSend: function() {

		$("#progress").show();
		$("#bar").width('0%');
		$("#message").html("");
		$("#percent").html("0%");
		$("#upload-foto").attr("disabled",""); // Membuat button upload jadi tidak bisa terklik
		$("#upload-foto").html("Memproses...");
	 
	},
	uploadProgress: function(event, position, total, percentComplete) {

		$("#bar").width(percentComplete+'%');
		$("#percent").html(percentComplete+'%');

	},
	success:function(data, textStatus, jqXHR,ui) {
		$("#percent").html("100%");
		$("#progress").hide();
		$("#message").html(data);
		$("#upload-foto").removeAttr("disabled");
		$("#upload-foto").html("Upload");
		$("input[type='file']").val('');

	},
	error: function() {
		$("#message").html("<span style='color:red'> ERROR: Tidak dapat mengupload</span>");
	}
 
};
 
// kirim form dengan opsi yang telah dibuat diatas
$("#form-upload").ajaxForm(options);
 
});

</script>
