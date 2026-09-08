<DOCTYPE html>
<html>
<link href="css/tabel.css" rel="stylesheet" type="text/css" />
<head>
	<title>Multiple Upload Foto dengan PHP</title>

	<style>

	#progress { position:relative; width:300px;color:#111; border: 1px solid #ddd; padding: 1px;
				 border-radius: 3px;display: none; }
	#bar { background-color: #d2322d; width:0%; height:20px; border-radius: 3px; }
	#percent { position:absolute; display:inline-block; top:3px; left:48%; }

	</style>

	<!-- Jquery 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
	<!-- Library Jquery untuk pengiriman form dengan jquery ajax  
	<script src="http://malsup.github.com/jquery.form.js"></script>-->

</head>

<body>

<div style="width:50%;margin:0 auto;border-radius:5px;background:#eee;padding:10px">

	<form name="contoh" method="post" action="uploadgambar1.php" enctype="multipart/form-data" id="form-upload">
<table>
<tr><th ><a href="adminmenu.php" title="logout" style="height:21px;line-height:21px;"><img src="img/users2.png" alt=""/>Kembali</a> </th></tr>
<tr><td>
		<input type="file" accept="image/*" name="foto[]" multiple />
</td></tr><tr><td>
		<input type="submit" id="upload-foto" value="Upload">
</td></tr></table>
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

</body>
</html>
