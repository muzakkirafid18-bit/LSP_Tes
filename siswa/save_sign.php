<?php 
        $iduserttd=$_POST['iduser'];
	$result = array();
	$imagedata = base64_decode($_POST['img_data']);
	//$filename = md5(date("dmYhisA"));
	//Location to where you want to created sign image
	$filename = $iduserttd;
        $file_name = '../../imgttd/'.$filename.'.png';
	file_put_contents($file_name,$imagedata);
	$result['status'] = 1;
	$result['file_name'] = $file_name;
	echo json_encode($result);
        
?>
