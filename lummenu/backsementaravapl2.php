 if($ieq=="ed"){
        $cekdata0 = "SELECT * FROM rekomendasi WHERE namarekom = 'apl2' and idskema='$idskq' and idasesi='$idadsesiq' and tanggal='$tgl'";
          } 	
 		// echo $cekdata0;
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
    
    $catatan=$data0['catatan'];        
	$cek="select * from skemasiswa where emailsiswa='$emailp' and statustesp='N'"; 
	$ada=mysql_query($cek);
        //echo $cek;
	if(mysql_num_rows($ada)>0){
		$data  = mysql_fetch_array($ada);
	        $skema=$data['idskema'];
	        $statusapl1=$data['statusapl1'];
                //echo "lklkl";
	        if($statusapl1=='Y')
	 		{
			$spemet="select * from pemetaan where idskema='$skema' and idpeserta='$idp'";
			$execpe=mysql_query($spemet);
			$hpemet=mysql_fetch_array($execpe);
			$namaass=$hpemet["namaasesor"];
			$tgl=$hpemet["tanggal"];
			//echo $namaass;
			  $ske="select * from skema where idskema='$skema'";  
			  $ske1=mysql_query($ske);
			  $ske2=mysql_fetch_array($ske1);
			  $namaskema=$ske2['namaskema'];
                          echo "<input type=hidden name=tgl value='$tgl'>";
                          echo "<input type=hidden name=kelompok value='$kelompok'>"; 
			  echo "<table class=demo-table><tr><td colspan=9>FR-APL-02 ASESMEN MANDIRI</td></tr><tr><td colspan=4>
				Nama Peserta : $namap </td><td colspan=5>Tanggal : $tgl </td></tr><tr>
				<td colspan=4>Nama Asesor : $namaass<td colspan=5></td></tr>             
				<tr><td colspan=9> Pada Bagian ini anda di minta untuk menilai diri sendiri terhadap unit kompetensi yang akan di ases</td></tr><tr> 
			       <td colspan=8>1. Pelajari seluruh standar Kriteria Unjuk Kerja  (KUK), batasan variabel, panduan penilaian dan aspek kritis serta yakinkan bahwa anda sudah benar-benar memahami seluruh isinya.</br>
2. Laksanakan penilaian mandiri dengan mempelajari dan menilai kemampuan yang anda miliki secara obyektif terhadap seluruh daftar pertanyaan yang ada, serta tentukan apakah sudah kompeten (K) atau belum kompeten (BK). </br>  
3. Siapkan bukti-bukti yang anda anggap relevan terhadap unit kompetensi, serta ‘matching’-kan setiap bukti yang ada terhadap setiap elemen/KUK, konteks variable, pengetahuan dan keterampilan yang dipersyaratkan serta aspek kritis</br>
4. Asesor dan asesi menandatangi form Asesmen Mandiri.</td></tr>";
		
			      $sqlunit="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$skema' and unitsiswa.idadsesi='$idp' order by unitsiswa.idunit";
			      //echo $sqlunit;
			    $eunit=mysql_query($sqlunit);
			      $i=0;
			    echo "<input type=hidden name=idskema value='$skema'>";
			    echo "<input type=hidden name=idadsesi value='$idp'>";
			    echo "<input type=hidden name=email value='$emailp'>";
			    echo "<input type=hidden name=idasesor value='$idasesor'>";
			    while ($dunit=mysql_fetch_array($eunit))
				    {
			   
     
				       $sqelemen="select * from elemen where idunit='".$dunit['idunit']."' and idskema='$skema'";
				       //echo $sqelemen;
				       $eelemen=mysql_query($sqelemen);
				      echo "<tr><th colspan=9>".$dunit['kodeunit']." ".$dunit['namaunit']."</th></tr>";
					 $y=0;
					while ($delemen=mysql_fetch_array($eelemen))
      						{
          						$y++;
           	
							    $sqsubelemen="select * from subelemen where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema'";
							 //   echo $sqsubelemen;
            $esubelemen=mysql_query($sqsubelemen);


							    $x=0;
									echo "<tr><th colspan=9>".$y.". ".$delemen['namaelemen']."</th></tr>";
									echo "<tr><td colspan=3><strong>NO KUK</strong></td><td><strong>SUB KOMPETENSI</strong></td><td><strong>K</strong></td><td><strong>BK</strong></td><td colspan=2><strong>BUKTI  PENDUKUNG</strong> </td><td colspan=2><strong>VALIDASI</strong> </td>"; 
									
									while ($dsubelemen=mysql_fetch_array($esubelemen))
									  {
							    			 $idsube=$dsubelemen['idsubelemen'];
										 $kuk=$dsubelemen['pertanyaan'];
              									 $x++;
									         if($ieq=="in"){
                                                                                 $cekapl2="select * from apl2 where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema' and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' and svalidasi='T' and waktu='$tgl'"; }

											else {
										$cekapl2="select * from apl2 where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema' and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' and svalidasi='Y' and waktu='$tgl'" ; }
     								//echo $cekapl2;
									$rcekapl2=mysql_query($cekapl2);
									//if(mysql_num_rows($rcekapl2)>0){ 
                                                                        $pp='';
									$ketk="";
    			    						$ketbk="";
									$sbukti="";
									$kdbs0="";
									$kdbs1="";
									$kdbs2="";
									$kdbs3="";
								
									if(mysql_num_rows($rcekapl2)>0)

									{ 
									
									$hcekapl2=mysql_fetch_array($rcekapl2);
									//while ($hcekapl2=mysql_fetch_row($rcekapl2)){  
 									//echo "abc"; 
									//echo "ook".$hcekapl2['idsubelemen'];    
									 $gambar="../siswa/gambarimages/".$hcekapl2['path']; 
									 $pp="Tampilkan bukti";
									 $sbukti=$hcekapl2['sbukti']; 
									 $pecahdbs=explode(",",$sbukti);
    
									        $dbs0=$pecahdbs[0];
									        $dbs1=$pecahdbs[1];
										$dbs2=$pecahdbs[2];
										$dbs3=$pecahdbs[3];
										//echo "B".$db0;
										if(trim($dbs0)=='v')
										{
										 $kdbs0="checked";
										}
									       if(!empty($dbs1))
										{
										 $kdbs1="checked";
										}
									       if(!empty($dbs2))
										{
										 $kdbs2="checked";
										}
									       if(!empty($dbs3))
										{
										 $kdbs3="checked";
										}
									  
									 $kbk=$hcekapl2['tk'];
									    if($kbk=='K')
										{$ketk="checked";}
										else{$ketbk="checked";}
									//} 
									//	echo $sbukti;								 //$kuk=$hcekapl2['pertanyaan'];
									 //echo $x.$kbk."</br>";
									         
  									 echo"<tr><td colspan=3>".$y.".".$x."</td><td>$kuk<input type=hidden name=idunit".$i." value=".$dunit['idunit']."><input type=hidden name=idelemen".$i." value=".$delemen['idelemen']."></td><td><input type=radio name=bk".$i." value=k $ketk disabled=disabled><br/></td><td><input type=radio name=bk".$i." value=bk $ketbk disabled=disabled></td><td><input type=hidden name=idsube".$i." value='$idsube'></td></td><td><a href=$gambar target=_blank>$pp</td>";
echo"<td><input type=checkbox name=validasia".$i." value='v' $kdbs0>V<input type=checkbox name=validasib".$i." value='a' $kdbs1>A<input type=checkbox name=validasic".$i." value='t' $kdbs2>T <input type=checkbox name=validasid".$i." value='m' $kdbs3>M</td>";
									     }else{
										echo "<tr><th colspan=9> -----tidak ada data----</th>";}
            									 $i++;
                      							 }
 						}
      					}
					echo"</tr><td colspan=2 rowspan=3> Rekomendasi </br><input type=radio name=lrek value='L' $klrek >Lanjut</br> <input type=radio name=lrek value='T' $klrek0>Tidak</td><td colspan=7>Asesi :</td></tr><tr><td>Nama : </td><td colspan=6>$namap</td></tr><tr><td>Tanda Tangan : </td><td colspan=6></td>";
echo"</tr><td colspan=2 rowspan=3> Catatan </br><input type=text name=catatan value=$cat><td colspan=7>Asesor :</td></tr><tr><td>Nama : </td><td colspan=6>$namax</td></tr><tr><td>Tanda Tangan : </td><td colspan=6><img src='$ttd' height=50></td>";
    echo "</tr><tr><td colspan=9><input type='hidden' name='n' value='".$i."'><input type=submit name=simpan id=gobutton value=Simpan></td>";
  				}
		}
	
}	


?>
</table>
<!--</td><td><input type=hidden  name="n" value="<?php echo $i ;?>"/>-->



<?php
if ($op=="simpanrekomapl2")
{
    $idskemarekapl2=$_POST['idskttd'];
    $idasesirekapl2=$_POST['idadsesittd'];
    $tglrekapl2=$_POST['tglttd'];
    $idasesorrekapl2=$_POST['idasesorttd'];
    $lrekapl2=$_POST['lrekttd'];
    $catatanrekapl2=$_POST['cttapl2'];
	$emailad=$_POST['emailadsesi'];

	//echo "lrek".$lrekapl2;
	//echo $catatanrekapl2;
     $cekdata = "SELECT * FROM rekomendasi WHERE namarekom = 'apl2' and idskema='$idskemarekapl2' and idasesi='$idasesirekapl2' and tanggal='$tglrekapl2'";
     //echo $cekdata;
   $ada=mysql_query($cekdata);
   	if(mysql_num_rows($ada)>0){
    $ssqlrekapl2="update rekomendasi set rekom='$lrekapl2' , catatan='$catatanrekapl2' where namarekom = 'apl2' and idskema='$idskemarekapl2' and idasesi='$idasesirekapl2' and tanggal='$tglrekapl2'"; 
    }else{$ssqlrekapl2="insert into rekomendasi value('','apl2','$idskemarekapl2','$idasesorrekapl2','$idasesirekapl2','$lrekapl2','$catatanrekapl2','','$tglrekapl2')";}
    //echo $ssqlrekapl2;
   $execrekapl2=mysql_query($ssqlrekapl2);
if($execrekapl2){
   	?>
   	<div class="container">
			  <div class="alert alert-success">
			    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <strong>Penyimpanan Sukses ..!</strong>
			  </div>
			</div> <?php 
   } else {
   	?>
   	<div class="container">
			  <div class="alert alert-warning">
			    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <strong>Penyimpanan Gagal ..!</strong>Ukuran File terlalu besar
			  </div>
			</div> <?php
   }

}