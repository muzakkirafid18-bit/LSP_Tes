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
<link href="../css/tabelbaru2.css" rel="stylesheet">

<script src="js/lumino.glyphs.js"></script>
<script src="../js/jquery-2.2.3.min.js"></script>
<script src="../js/formValidation.min.js"></script>
<script src="../js/framework/bootstrap.min.js"></script>


<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap-datepicker.js"></script>

<?php
session_set_cookie_params(3600*2,"/");
session_start();
include "../lsp_koneksi.php";
if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
 echo "<center><link href='css/adminstyle.css' rel='stylesheet' type='text/css'>
 <strong> Anda Harus Login Dahulu ...!</strong><br>";
 echo "<a href=../lsp_login.php class='btn btn-primary btn-sm' role='button'> Kembali</a></center>"; 
}
else{
?>
<script type="text/javascript">
$(document).ready (function() {
	$('#formContoh').formValidation({
		framework: 'bootstrap',
		excluded: 'disabled',

		icon: {
			valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			namaasesor: {
				validators: {
					notEmpty: {
						message: 'Username Tidak Boleh Kosong'
					}
				}
			},
			
			nama: {
				validators: {
					notEmpty: {
						message: 'Nama Tidak Boleh Kosong'
					}
				}
			},

			notelp: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					}
				}
			},
			password: {
				validators: {
					notEmpty: {
						message: 'Tidak Boleh Kosong'
					},
					identical: {
						field: 'repassword',
						message: 'Password Tidak sama'
					}					
				}
			},
                        repassword: {
				validators: {
					notEmpty: {
						message: 'Confirm Password Tidak Sama'
					},
					identical: {
						field: 'password',
						message: 'Password Tidak sama'
					},
                                        stringlenght:{
					max :6,
                                        message: 'Minimal 6'
					}	  
				
				}
			},
			emailuser: {
				validators: {
					notEmpty: {
						message: 'Email Tidak Boleh Kosong'
					},
					emailAddress: {
						message: 'Email Tidak Valid'
					}
				}
			}
		}
	})
});
</script>

<style>
form div.dynamiclabel{ /* div container for each form field with pop up label */
  display: block;
  margin: 5px 2px 6px 15px;
  font: 16px;
  position: relative;
}

form div.formcolumn{ /* column div inside form */
width: 49%;
float: left;
}

form div.formcolumn:first-of-type{
margin-right: 1%; /* 2% margin after first column */
}

form div.dynamiclabel input[type="text"], form div.dynamiclabel textarea{
  width: 350px;
  font-size: 14px;
  padding: 7px;
  border: 1px solid #c3c3c3;
  border-radius: 7px;
  
}

form div.dynamiclabel textarea{
	height: 150px;
}

form div.dynamiclabel select{
  width: 200px;
  font-size: 14px;
  padding: 7px;
  border: 1px solid #c3c3c3;
  border-radius: 7px;
  text-transform: uppercase
}

form div.buttons{
clear: both;
text-align: center;
}

form div input.button{
margin-top: 2EM;
width: 35%;
box-shadow: 0 0 10px gray;
text-transform: uppercase;
cursor: pointer;
min-width: 100px;
max-width: 600px;
color: white;
font-weight: bold;
letter-spacing: 10px;
text-shadow: 0 -2px 1px #8a8a8a;
background: rgb(169,3,41);
background: -moz-linear-gradient(top,  rgba(169,3,41,1) 0%, rgba(143,2,34,1) 44%, rgba(109,0,25,1) 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(169,3,41,1)), color-stop(44%,rgba(143,2,34,1)), color-stop(100%,rgba(109,0,25,1)));
background: -webkit-linear-gradient(top,  rgba(169,3,41,1) 0%,rgba(143,2,34,1) 44%,rgba(109,0,25,1) 100%);
background: -o-linear-gradient(top,  rgba(169,3,41,1) 0%,rgba(143,2,34,1) 44%,rgba(109,0,25,1) 100%);
background: -ms-linear-gradient(top,  rgba(169,3,41,1) 0%,rgba(143,2,34,1) 44%,rgba(109,0,25,1) 100%);
background: linear-gradient(to bottom,  rgba(169,3,41,1) 0%,rgba(143,2,34,1) 44%,rgba(109,0,25,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a90329', endColorstr='#6d0019',GradientType=0 );
}

form div input.button:active{
text-shadow: 0 0 1px #8a8a8a;
background: rgb(109,0,25);
background: -moz-linear-gradient(top,  rgba(109,0,25,1) 0%, rgba(143,2,34,1) 56%, rgba(169,3,41,1) 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(109,0,25,1)), color-stop(56%,rgba(143,2,34,1)), color-stop(100%,rgba(169,3,41,1)));
background: -webkit-linear-gradient(top,  rgba(109,0,25,1) 0%,rgba(143,2,34,1) 56%,rgba(169,3,41,1) 100%);
background: -o-linear-gradient(top,  rgba(109,0,25,1) 0%,rgba(143,2,34,1) 56%,rgba(169,3,41,1) 100%);
background: -ms-linear-gradient(top,  rgba(109,0,25,1) 0%,rgba(143,2,34,1) 56%,rgba(169,3,41,1) 100%);
background: linear-gradient(to bottom,  rgba(109,0,25,1) 0%,rgba(143,2,34,1) 56%,rgba(169,3,41,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6d0019', endColorstr='#a90329',GradientType=0 );
}



form div.dynamiclabel label{ /* pop up label style */
  position: absolute;
  left: 0;
  background: lightyellow;
  border: 1px solid yellow;
  border-radius: 2px;
  padding: 3px 7px;
  box-shadow: 4px 1px 5px gray;
  font-weight: bold;
	-webkit-backface-visibility: hidden;
	top: 7px; /* initial top position of label relative to dynamiclabel container */
  -moz-transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  -webkit-transition: all 0.4s ease-in-out; /* Safari doesn't seem to support cubic-bezier values beyond 0 to 1, so use keyword value instead */
  -o-transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  opacity: 0;
  z-index: -1;
}


form div.dynamiclabel > *:focus{ /* when user focuses on child element inside div.dynamiclabel */
  border: 1px solid #45bcd2;
  box-shadow: 0 0 8px 2px #98d865;
}


form div.dynamiclabel > *:focus + label{ /* label style when user focuses on child element inside div.dynamiclabel */
  opacity: 1;
  z-index:100;
	top: -35px; /* Post top position of label on focus relative to dynamiclabel container */
	-ms-transform: rotate(-3deg);
	-moz-transform: rotate(-3deg);
	-webkit-transform: rotate(-3deg);
  transform: rotate(-3deg);
}


@media screen and (max-width: 480px){ /* responsive form settings, when window width is 480px or less */

	form div.dynamiclabel{
	font-size: 14px; /* decrease form font size */
	}

	form div.dynamiclabel .formcolumn{
	width: 100%;
	float: none;
	}
	
	form div.dynamiclabel .formcolumn:first-of-type{
	margin-right: 0; /* remove right margin from first form column */
	}

	form div.dynamiclabel .formcolumn:nth-of-type(2){
	padding-top: 2em; /* add padding to top of 2nd form column, so there is a gap between the 1st and 2nd column */
	}

	form div.dynamiclabel select{
	width: 98%;
	}

}


</style>

<!--Icons-->
<script src="js/lumino.glyphs.js"></script>

<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
<?php if(isset($_SESSION['username'])) { $uname=$_SESSION['username'];}
$l="SELECT * FROM lsp_usertbl WHERE email='".$uname."'";
$resultx=mysql_query($l);
$hasilx=mysql_fetch_array($resultx);
$namax=$hasilx['nama']; 
$idasesor=$hasilx['id'];
?>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"><img src="../images/logolsp.png" alt="" height="30"><span><?php echo " ".$namax ;?></a>
                                <a class="user-menu" href="#"><img src="../images/logobnsp.png" alt="" height="30"><span></a>
				
			</div>
							
		</div><!-- /.container-fluid -->
	</nav>
		
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<!--<form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form>-->
		<ul class="nav menu">
				<li><a href="asesormain.php"><svg class="glyph stroked clipboard with paper"><use xlink:href="#stroked-clipboard-with-paper"/></svg> Validasi APL1</a></li>
			<li><a href="validasiapl2.php"><svg class="glyph stroked pen tip"><use xlink:href="#stroked-pen-tip"/></svg> Validasi APL2</a></li>
<li><a href="observasi.php"><svg class="glyph stroked eye"><use xlink:href="#stroked-eye"/></svg> FR MPA.05-Observasi</a></li>
<li class="active"><a href="mak4.php"><svg class="glyph stroked blank document"><use xlink:href="#stroked-blank-document"/></svg>FR MAK.04</a></li>
<li><a href="mak6.php"><svg class="glyph stroked star"><use xlink:href="#stroked-star"/></svg> FR MAK.06</a></li>
<li><a href="mak7.php"><svg class="glyph stroked map"><use xlink:href="#stroked-map"/></svg>FR MAK.07</a></li>
<li><a href="rekapasesi.php"><svg class="glyph stroked calendar blank"><use xlink:href="#stroked-calendar-blank"/></svg> Rekap FR MPA.03</a></li>
<li ><a href="mma.php"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg></svg> MMA</a></li>

			<li role="presentation" class="divider"></li>
	
			<li><a href="../logout.php"><svg class="glyph stroked lock"><use xlink:href="#stroked-lock"/></svg> Logout</a></li>
		</ul>

	</div><!--/.sidebar-->
		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
		<div class="row">
			<h4>Validasi MAK-4 </h4>
                        
</div><!--/.row-->
		
		
				
		<!--/.row-->
				
		
		<div class="row">
			<div class="col-lg-12">
<!--data nu baru -->


<?php
$op = $_GET['op'];
 
if($op=="pilihtanggal")
{
$idskema= $_GET['idskema'];
$kelompok=$_GET['kelompok'];

?>
<form id="formContoh" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=listpeserta\"";?>> 
<input type="hidden" name="kelompok" value="<?php echo $kelompok ;?>">
<input type="hidden" name="idskema" value="<?php echo $idskema ;?>">
<input type="hidden" name="idasesor" value="<?php echo $idasesor ;?>">
<div class="form-group"><strong>Pilih Tanggal :</strong>
<select id="tgl" name="tgl" placeholder="Tanggal" autofocus>
<?php
      $tampiltgl="select * from pemetaan where kelompok='$kelompok' and idskema='$idskema' and idasesor='$idasesor' group by tanggal";
      $exectgl=mysql_query($tampiltgl);
      //echo $tampiltgl;
		while($rtgl=mysql_fetch_array($exectgl))
		{
                //         echo "ll";
   				echo "<option value=$rtgl[tanggal] selected>$rtgl[tanggal]</option> ";
					}
?>
	 </select>
</div>
<input type="submit" id="gobutton" value="Lanjutkan" class="button"> 
<?php
}



else if ($op == "listpeserta")
{
    $idasesor=$_POST['idasesor'];
    $idskema= $_POST['idskema'];
    $kelompok=$_POST['kelompok'];
    $tgl=$_POST['tgl'];

    //$idskema= $_GET['idskema'];
    //$kelompok=$_GET['kelompok'];
    $ssl="select * from pemetaan where kelompok='$kelompok' and idskema='$idskema' and tanggal='$tgl' and idasesor='$idasesor'";
    $exec0=mysql_query($ssl);
    //echo "laksl".$ssl;
    echo "<table class=demo-table>";
    echo "<tr><th bgcolor='#006699'>ID Asesi</th><th bgcolor='#006699'>Nama Asesi</th><th bgcolor='#006699'>Tanggal</th><th bgcolor='#006699'>Ket</th><th colspan=2 bgcolor='#006699'>Aksi</th></tr>";
     while($hasil0 = mysql_fetch_array($exec0))
      {

        $cekvlmak4="select idadsesi,idskema,waktu,svalidasi from mak4 where idskema='$idskema' and waktu='$tgl' and idadsesi='".$hasil0['idpeserta']."' and svalidasi='Y' group by idadsesi,idskema,waktu,svalidasi";
      //echo $cekvlmak4;
      $cekvlmak4a=mysql_query($cekvlmak4);
      $cekvlmak4b=mysql_fetch_array($cekvlmak4a);
      
      //if(
      $b=mysql_num_rows($cekvlmak4a);
      //echo "jjj".$cekvlmak4b['loba'];
      //echo trim($b);
     if($b>0){
             $ketvlmak2="<font color=green><span class='glyphicon glyphicon-ok'>Sudah Pernah divalidasi</font>";}
          else{
             $ketvlmak2="<font color=red><span class='glyphicon glyphicon-remove'>Belum Divalidasi</font>";
		
        }


      if($hasil0['statusvalid']=='Y'){
         $ket="Sudah divalidasiiii";
         }else{ $ket="Belum di Validasiii";}
      
      echo "<tr><td>".$hasil0['idpeserta']."</td>";
      echo "<td>".$hasil0['namapeserta']."</td>";
      echo "<td>".$hasil0['tanggal']."</td>";
      echo "<td>".$ketvlmak2."</td>";
      $ei="in";
      echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=validasiaplmak4&idasesor=".$hasil0['idasesor']."&k=".$hasil0['kelompok']."&tgl=".$hasil0['tanggal']."&kode=".$ei."&idasesi=".$hasil0['idpeserta']."&idskema=".$idskema."\"><span class='glyphicon glyphicon-check'>Validasi</a></td>";
      echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=validasiaplmak4&idasesor=".$hasil0['idasesor']."&k=".$hasil0['kelompok']."&tgl=".$hasil0['tanggal']."&kode=ed&idasesi=".$hasil0['idpeserta']."&idskema=".$idskema."\"><span class='glyphicon glyphicon-edit'>Edit</a></td></tr>";
       }
     echo "<table class=demo-table>";
      
    
}

else if ($op == "validasiaplmak4")

{
?>
<!--<form name="contoh" method="post" action="mak4post.php" enctype="multipart/form-data" id="form-upload">
<table>-->
<?php
echo "<form id=\"formContoh\" method=\"post\" action=\"".$_SERVER['PHP_SELF'].
        "?op=simpanvalidasimak4\">";
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
        
         if($ie=="ed"){
        $cekdata0 = "SELECT * FROM mak4rekom WHERE idskema='$idsk' and idasesi='$idadsesi' and tanggal='$tgl'";
          } 	
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
			       <td colspan=10>Penjelasan untuk Asesor :
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
									echo "<tr><th colspan=10>".$y.". ".$delemen['namaelemen']."</th></tr>";
									echo "<tr><td colspan=4><strong>Kriteria Unjuk Kerja</strong></td><td colspan=2><strong>Keputusan</strong></td><td colspan=5><strong>BUKTI  PENDUKUNG</strong> </td>"; 
									echo "<tr><td colspan=4><strong></strong></td><td><strong>K</strong></td><td><strong>BK</strong></td><td></td><td><strong>Bukti Langsung</strong> </td><td><strong>Bukti Tidak Langsung</strong> </td><td><strong>Bukti Tambahan</strong> </td>"; 
									
									while ($dsubelemen=mysql_fetch_array($esubelemen))
									  {
							    			 $idsube=$dsubelemen['idsubelemen'];
										 $kuk=$dsubelemen['pertanyaan'];
              									 $x++;
									         if($ie=="in"){
                                                                                 $cekmak4="select * from subelemen where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema'";}// and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' and waktu='$tgl'"; }
											else {
										$cekmak4="select * from mak4 where idelemen='".$delemen['idelemen']."' and idunit='".$dunit['idunit']."' and idskema='$skema' and idsubelemen='".$dsubelemen['idsubelemen']."' and idadsesi='$idp' and svalidasi='Y' and waktu='$tgl'" ; }
     								//echo $cekmak4;
									$rcekmak4=mysql_query($cekmak4);
									//if(mysql_num_rows($rcekmak4)>0){ 
                                                                        $pp='';
									$ketk="";
    			    						$ketbk="";
									$sbukti="";
									if($ie=="in"){
									$kdbs0="checked";}else{$kdbs0="";}
									$kdbs1="";
									$kdbs2="";
									$kdbs3="";
								
									if(mysql_num_rows($rcekmak4)>0)

									{ 
									
									$hcekmak4=mysql_fetch_array($rcekmak4);
									//while ($hcekmak4=mysql_fetch_row($rcekmak4)){  
 									//echo "abc"; 
									//echo "ook".$hcekmak4['idsubelemen'];    
									 $gambar="../siswa/gambarimages/".$hcekmak4['path']; 
									 $pp="Tampilkan bukti";
									 $sbukti=$hcekmak4['sbukti']; 
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
									  
									 $kbk=$hcekmak4['tk'];
									    if($kbk=='T')
										{$ketbk="checked";}
										else{$ketk="checked";}
									//} 
									//	echo $sbukti;								 //$kuk=$hcekmak4['pertanyaan'];
									 //echo $x.$kbk."</br>";
									         
  									 echo"<tr><td colspan=3>".$y.".".$x."</td><td>$kuk<input type=hidden name=idunit".$i." value=".$dunit['idunit']."><input type=hidden name=idelemen".$i." value=".$delemen['idelemen']."></td><td><input type=radio name=bk".$i." value=k $ketk ><br/></td><td><input type=radio name=bk".$i." value=bk $ketbk ></td><td><input type=hidden name=idsube".$i." value='$idsube'></td></td>"; 
//<td><a href=$gambar target=_blank>$pp</td>";
echo"<td><input type=checkbox name=validasia".$i." value='v' $kdbs0></td><td><input type=checkbox name=validasib".$i." value='a' $kdbs1></td><td><input type=checkbox name=validasic".$i." value='t' $kdbs2></td>";
									     }else{
										echo "<tr><th colspan=9> -----tidak ada data----</th>";}
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

<?php
}

else if ($op == "simpanvalidasimak4")
{

//$idpermohonan=$_POST['idpermohonan'];
$idasesor=$_POST['idasesor'];
$idskema=$_POST['idskema'];
$idasesi=$_POST['idadsesi'];
$email=$_POST['email'];
$n=$_POST['n'];
$tgl=$_POST['tgl'];
$kelompok=$_POST['kelompok'];
$umpanb=$_POST['umpanb'];
$idenkes=$_POST['idenkes'];
$saran=$_POST['saran'];
for ($i=0; $i<=$n-1; $i++)
   {
     
     if (isset($_POST['validasia'.$i]) or isset($_POST['validasib'.$i]) or isset($_POST['validasic'.$i]))
     {
       //echo "oook";
     $idunit=$_POST['idunit'.$i];
     $idelemen= $_POST['idelemen'.$i];
     $idsubelemen= $_POST['idsube'.$i];	
     $validasi0=$_POST['validasia'.$i];
     $validasi1=$_POST['validasib'.$i];
     $validasi2=$_POST['validasic'.$i];
     //$validasi3=$_POST['validasid'.$i];
     $allvalidasi=$validasi0.",".$validasi1.",".$validasi2; //,".$validasi3;
     
     
     //echo $idelemen;
     //$lt=$_POST['lt'.$i];
     $cekmak4ada="select * from mak4 where idelemen='".$idelemen."' and idunit='".$idunit."' and idskema='$idskema' and idsubelemen='".$idsubelemen."' and idadsesi='$idasesi' and svalidasi='Y' and waktu='$tgl'" ;
     $cekmak4adaa=mysql_query($cekmak4ada);
     //echo  $cekmak4ada;
     $cekmak4adab=mysql_num_rows($cekmak4adaa);
     if($cekmak4adab>0){
    $sqlupdate="update mak4 set sbukti='$allvalidasi', svalidasi='Y' where idskema='$idskema' and idadsesi='$idasesi' and idelemen='$idelemen' and idsubelemen='$idsubelemen' and waktu='$tgl'";}
      else {
     $sqlupdate="insert into  mak4 value('','$idskema','$idunit','$idelemen','$idsubelemen','$idasesi','$email','$tgl','','$allvalidasi','Y')";}
     //echo $sqlupdate;
     //echo $sqlupdate;
     $exec=mysql_query($sqlupdate);
     }

    }
    $lrek=$_POST['lrek'];
    $catatan=$_POST['catatan'];
	$emailad=$_POST['emailadsesi'];
     $cekdata = "SELECT * FROM mak4rekom WHERE idskema='$idskema' and idasesi='$idasesi' and idpermohonan='$idpermohonan' and tanggal='$tgl'";
   $ada=mysql_query($cekdata);
   	if(mysql_num_rows($ada)>0){
    $ssql="update mak4rekom set rekom='$lrek' , umpanb='$umpanb' , idenkes='$idenkes', saran='$saran', catatan='$catatan' where idskema='$idskema' and idasesi='$idasesi'"; 
    }else{$ssql="insert into mak4rekom value('','mak4','$idskema','$idasesor','$idasesi','$lrek','$umpanb','$idenkes','$saran','$catatan','$idpermohonan','$tgl')";}
  $exec=mysql_query($ssql);
    //echo $ssql;
    //$cedulu="select * from mak4 where idelemen='$idelemen' and idskema='$idskema' and idsubelemen='$idsubelemen' and idadsesi='$idasesi' and svalidasi='T' and waktu='$tgl'";
    //echo $cedulu;
    //$updatesksiswa="update skemasiswa set statusmak4='Y' where emailsiswa='$email' and idskema='$idskema'";
    //echo $updatesksiswa;
    //$execu=mysql_query($updatesksiswa);
    //$cekupl="select * from mak4 where statusvalid='N'";
    //$execupl=mysql_query($cekupl);
    //if(mysql_num_rows($execupl)>0){
    // echo "Masih ada bagian yang belum di validasi";}
    //else{
      //  $updatepermohonan="update permohonan set statusvalid='Y'";
       // $pros=mysql_query($updatepermohonan);
    //}
  //echo $updatesksiswa;
  //if($exec){
   //echo"<script>alert('Sukses ..!');history.go(-2);</script>";}
   //else{echo"<script>alert('Gagal ..!');history.go(-1);</script>";}?>
<form id="formContoh" method="POST" <?php echo "action=\"".$_SERVER['PHP_SELF'].
        "?op=listpeserta\"";?>> 
<?php
echo "<input type=hidden name=tgl value='$tgl'>";
echo "<input type=hidden name=idskema value='$idskema'>";
echo "<input type=hidden name=kelompok value='$kelompok'>";
echo "<input type=hidden name=idasesor value='$idasesor'>";
?>
</br><input type="submit" id="gobutton" value="Proses selesai.. Lanjutkan" class="button"> 
<?php


}

?>


<body>
<br/>
<?php
$queryvmain ="SELECT * FROM pemetaan where idasesor='$idasesor' group by kelompok,idskema";
$hasilvmain = mysql_query($queryvmain);
//$ada=mysql_query($);
   	if(mysql_num_rows($hasilvmain)>0){
?>

<table class="demo-table">
<tr>
    <th bgcolor='#006699'>No</th>
    	
    <th bgcolor='#006699'>Kelompok</th>
    <th bgcolor='#006699'>Skema</th>
    <th bgcolor='#006699'>Nama Skema</th>
    <th bgcolor='#006699' colspan='2'>Aksi</th>
    
</tr>
 
<?php
 
// bagian ini digunakan untuk menampilkan semua data
 
$no = 1;
$queryvvmain ="SELECT * FROM pemetaan where idasesor='$idasesor' group by kelompok,idskema";
$hasilvvmain = mysql_query($queryvvmain);
while ($datavvmain = mysql_fetch_array($hasilvvmain))
{
   $ssql="select * from skema where idskema=".$datavvmain['idskema'];
   //echo $ssql;
   $execssql=mysql_query($ssql);
   $baris=mysql_fetch_array($execssql);
   $namaskema=$baris['namaskema'];
   echo "<tr>";
   echo "<td bgcolor='#99CCFF'>".$no."</td>";
   //echo "<td bgcolor='#99CCFF'>".$data['id_user']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$datavvmain['kelompok']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$datavvmain['idskema']."</td>";
   echo "<td bgcolor='#99CCFF' align=left>".$namaskema."</td>";
  	
   echo "<td><a href=\"".$_SERVER['PHP_SELF'].
        "?op=pilihtanggal&idskema=".$datavvmain['idskema']."&kelompok=".$datavvmain['kelompok']."\"><span class='glyphicon glyphicon-user'>Tampilkan Peserta</a> 
    </td>";
   echo "</tr>";
   $no++;
}
   echo "</div>";
}else{ echo "Belum ada jadwal";}

?>

<!--end databaru-->
			<div>
		</div>
		
                </div>
         </div> 
			
</body>

</html>

<?php } ?>
