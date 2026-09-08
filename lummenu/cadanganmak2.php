?>
<!--<form name="contoh" method="post" action="mak4post.php" enctype="multipart/form-data" id="form-upload">
<table>-->
<?php
echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=simpanvalidasimak2\">";
        $idasesor=$_GET['idasesor'];
        $ie=$_GET['kode'];
        $idadsesi=$_GET['idasesi'];
        $idsk=$_GET['idskema'];
        $tgl=$_GET['tgl'];
	$kelompok=$_GET['k'];	
	$emailuser=trim($uname);
	$sql="select * from lsp_usertbl where id='$idadsesi'";
	$shasil=mysql_query($sql);
	$sdata=mysql_fetch_array($shasil);
	$namap=$sdata['nama'];
	$idp=$sdata['id'];
        $emailp=$sdata['email']; 
    
        $nn = $_POST['nz'];
		//echo $nn;
		$cc=0;
		$ii=0; 
		$cc=0;
		$ccc=0;
		for ($cba=0; $cba<=$nn-1; $cba++)     		 	//echo "terplikh";
       {

		}        
         //if($ie=="ed"){
        $cekdata0 = "SELECT * FROM mak4rekom WHERE idskema='$idsk' and idasesi='$idadsesi' and tanggal='$tgl'";
          //} 	
 		// echo $cekdata0;
   	$ada0=mysql_query($cekdata0);
   	$adax=mysql_fetch_array($ada0);
   
    	$lrek=$adax['rekom'];
    	$cat=$adax['catatan'];
        $umpanb=$adax['umpanb'];
        $idenkes=$adax['idenkes'];
	$saran=$adax['saran'];
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
			  echo "<table class=demo-table><tr><td colspan=10>FR-MAK4-KEPUTUSAN DAN UMPAN BALIK</td></tr>
				<tr><td colspan=10>Nama Skema : $namaskema</td></tr><tr><td colspan=5>
				Nama Peserta : $namap </td><td colspan=5>Tanggal : $tgl </td></tr><tr>
				<td colspan=5>Nama Asesor : $namaass<td colspan=5></td></tr>             
				<tr><td colspan=10> Pada Bagian ini anda di minta untuk menilai diri sendiri terhadap unit kompetensi yang akan di ases</td></tr><tr> 
			       <td colspan=10>Penjelasan untuk Asesor : </br>
				1. 	Asesor mengorganisasikan pelaksanaan asesmen berdasarkan metoda dan instrumen/sumber-sumber asesmen seperti yang tercantum dalam perencanaan asesmen.</br>
				2. Asesor melaksanakan kegiatan pengumpulan bukti serta mendokumentasikan seluruh bukti pendukung yang dapat ditunjukkan oleh Peserta sesuai dengan kriteria unjuk kerja yang dipersyaratkan.</br>
				3. Asesor membuat keputusan apakah Peserta sudah Kompeten (K),  Belum kompeten (BK) atau Asesmen Lanjut (AL), untuk setiap kriteria unjuk kerja berdasarkan bukti-bukti.</br>
				4. Asesor memberikan umpan balik kepada Peserta mengenai pencapaian unjuk kerja dan Peserta juga diminta untuk memberikan umpan balik terhadap proses asesmen yang dilaksanakan (kuesioner).</br>
				5. Asesor dan Peserta bersama-sama menandatangani pelaksanaan asesmen.</br>    
				6. Kolom Jenis Bukti diisi dengan jenis bukti  yang dipilih</td></tr>";
		
			      $sqlunit="select unitsiswa.idunit,unitsiswa.idskema,unitsiswa.idadsesi,unit.kodeunit,unit.namaunit from unitsiswa inner join unit on unitsiswa.idunit=unit.idunit where unitsiswa.idskema='$skema' and unitsiswa.idadsesi='$idp' order by unitsiswa.idunit";
			    $eunit=mysql_query($sqlunit);
			      $i=0;
			    echo "<input type=hidden name=idskema value='$skema'>";
			    echo "<input type=hidden name=idadsesi value='$idp'>";
			    echo "<input type=hidden name=email value='$emailp'>";
			    echo "<input type=hidden name=idasesor value='$idasesor'>";
			    while ($dunit=mysql_fetch_array($eunit))
				    {
			   
     
				       $sqelemen="select * from elemen where idunit='".$dunit['idunit']."' and idskema='$skema'";
				       $eelemen=mysql_query($sqelemen);
				      echo "<tr><th colspan=10>".$dunit['kodeunit']." ".$dunit['namaunit']."</th></tr>";
					 $y=0;
					while ($delemen=mysql_fetch_array($eelemen))
      						{
          						$y++;
           	
							    $sqsubelemen="select * from subelemen where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema'";
            $esubelemen=mysql_query($sqsubelemen);


							    $x=0;
									echo "<tr><th colspan=9>".$y.". ".$delemen['namaelemen']."</th></tr>";
									echo "<tr><td colspan=3><strong>Kriteria Unjuk Kerja</strong></td><td colspan=2><strong>Pencpaian</strong></td><td colspan=2><strong>Keputusan</strong></td><td colspan=5><strong>BUKTI  PENDUKUNG</strong> </td>"; 
									echo "<tr><td colspan=3><strong></strong></td><td><strong>Y</strong></td><td><strong>T</strong><td><strong>K</strong></td><td><strong>BK</strong></td><td></td><td><strong>Bukti Langsung</strong> </td><td><strong>Bukti Tidak Langsung</strong> </td><td><strong>Bukti Tambahan</strong> </td>"; 
									
									while ($dsubelemen=mysql_fetch_array($esubelemen))
									  {
							    			 $idsube=$dsubelemen['idsubelemen'];
										 $kuk=$dsubelemen['pertanyaan'];
              									 $x++;
									         //if($ie=="in"){
                                       //$cekmak2="select * from subelemen where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema'";//}// and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' and waktu='$tgl'"; }
											//else {
//										   $cekmak22="select * from mak4 where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema' and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' and svalidasi='Y' and waktu='$tgl'" ; //}
     								//echo $cekmak4;
									//$rcekmak2=mysql_query($cekmak2);
									//if(mysql_num_rows($rcekmak4)>0){ 
                                      $cekmak22="select * from mak2 where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema' and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' and waktu='$tgl'" ; //}
                                      //echo $cekmak22;
                                      $cekmak22a=mysql_query($cekmak22);
                                      $cekmak22b=mysql_num_rows($cekmak22a);

                                    //$pp='';
									//$ketk="";
    			    				//$ketbk="";
									//$sbukti="";
									//if($ie=="in"){
									//$kdbs0="checked";}else{$kdbs0="";}
									//$kdbs1="";
									//$kdbs2="";
									//$kdbs3="";
								
									//if(mysql_num_rows($rcekmak4)>0)

									//{ 
                                      //if($cekmak22b>0)
                                      //{
                                      	$cekmak22c=mysql_fetch_array($cekmak22a);
										echo $cekmak22c['tk']; 
										if ($cekmak22c['tk']=='K')
										
										{
											$ketk="checked";
										}
										else
										{
											$ketk=" ";
										}
										if( $cekmak22c['tk']=='BK' )
										{
											$ketbk="checked";
										}
										else
										{
											$ketbk=" ";
										}
										if( $cekmak22c['YT']=='Y' )
										{
											$kety="checked";
										}
										else
										{
											$kety=" ";
										}
										if( $cekmak22c['YT']=='T' )
										{
											$ketyt="checked";
										}
										else
										{
											$ketyt=" ";
										}	
											//echo "kkk".$cekmak22c['YT'].$kety;

																			
									//$hcekmak4=mysql_fetch_array($rcekmak4);
									//while ($hcekmak4=mysql_fetch_row($rcekmak4)){  
 									//echo "abc"; 
									//echo "ook".$hcekmak4['idsubelemen'];    
									 $gambar="../siswa/gambarimages/".$hcekmak4['path']; 
									 $pp="Tampilkan bukti";
									 $sbukti=$cekmak22c['sbukti']; 
									 $pecahdbs=explode(",",$sbukti);
    
									        $dbs0=$pecahdbs[0];
									        $dbs1=$pecahdbs[1];
										$dbs2=$pecahdbs[2];
										$dbs3=$pecahdbs[3];
										//echo "B".$db0;
										if(($dbs0)=='v')
										{
										 $kdbs0="checked";
										}
										else 
										{
										  $kdbs0=" ";	
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
									  //}
									 //$kbk=$hcekmak4['tk'];
									   // if($kbk=='T')
									//	{$ketbk="checked";}
									//	else{$ketk="checked";}
									//} 
									//	echo $sbukti;								 //$kuk=$hcekmak4['pertanyaan'];
									 //echo $x.$kbk."</br>";
									         
  									 echo"<tr><td colspan=2>".$y.".".$x."</td><td>$kuk<input type=hidden name=idunit".$i." value=".$dunit['idunit']."><input type=hidden name=idelemen".$i." value=".$delemen['idelemen']."></td><td><input type=radio name=py".$i." value=py $kety></td><td><input type=radio name=pt".$i." value=pt $ketbk ></td><td><input type=radio name=bk".$i." value=k $ketk ><br/></td><td><input type=radio name=bk".$i." value=bk $ketbk ></td><td><input type=hidden name=idsube".$i." value='$idsube'></td>"; 
//<td><a href=$gambar target=_blank>$pp</td>";
echo"<td><input type=checkbox name=validasia".$i." value='v' $kdbs0></td><td><input type=checkbox name=validasib".$i." value='a' $kdbs1></td><td><input type=checkbox name=validasic".$i." value='t' $kdbs2></td>";
									   //  }else{
										//echo "<tr><th colspan=9> -----tidak ada data----</th>";}
            									 $i++;
                      							 }
 						}
      					}
					echo"
<tr><td colspan=9><strong>Umpan balik terhadap pencapaian unjuk kerja : </strong></td></tr>
<tr><td colspan=9>Semua KUK yang diujikan  <input type=text name='umpanb' value='".$umpanb."'> tercapai </td></tr>
<tr><td colspan=9><strong>Identitas kesenjangan pencapaian kerja : </strong></td></tr>
<tr><td colspan=9><input type=text name='idenkes' value='".$idenkes."'> Kesenjangan </td></tr>
<tr><td colspan=9><strong>Saran tindak lanjut hasil asesmen : </strong></td></tr>
<tr><td colspan=9><input type=text name='saran' value='".$saran."'> Jika dikaji ulang sesuaikan dengan No KUK kesenjangan pencapaian kerja  </td></tr>

</tr><td colspan=2 rowspan=3> Rekomendasi </br><input type=radio name=lrek value='L' $klrek >Kompeten</br> <input type=radio name=lrek value='T' $klrek0>Belum Kompeten</td><td colspan=7>Asesi :</td></tr><tr><td>Nama : </td><td colspan=6>$namap</td></tr><tr><td>Tanda Tangan : </td><td colspan=6></td>";
echo"</tr><td colspan=2 rowspan=3> Catatan </br><input type=text name=catatan value=$cat><td colspan=7>Asesor :</td></tr><tr><td>Nama : </td><td colspan=6>$namax</td></tr><tr><td>Tanda Tangan : </td><td colspan=6></td>";
    echo "</tr><tr><td colspan=9><input type='hidden' name='n' value='".$i."'><input type=submit name=simpan id=gobutton value=Simpan></td>";
  				}

  						}


?>
</table class="demo-table">
<!--</td><td><input type=hidden  name="n" value="<?php echo $i ;?>"/>-->
</form>
<?php
