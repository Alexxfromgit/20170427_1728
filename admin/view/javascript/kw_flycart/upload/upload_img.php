<?php
$uploaddir = '../../../../../image/cart/'; 

$format = end(explode(".", $_FILES['uploadfile']['name']));
$name = $_FILES['uploadfile']['name'];
$md = date("Y-m-d-h-i-s");
$file = $uploaddir . basename($md.".".$format); 

$ext = substr($name,strpos($name,'.'),strlen($name)-1);
$filetypes = array('.jpg','.gif','.bmp','.png','.JPG','.BMP','.GIF','.PNG','.jpeg','.JPEG');

if(!in_array($ext,$filetypes)){
	echo "<p>Error format</p>";
} else {
	if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file)) {
		echo "success";
	} else {
		echo "error";
	}
}

?>