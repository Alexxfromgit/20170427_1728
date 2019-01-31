<?php
  $uploadfile = "price/".$_FILES['somename']['name'];
  move_uploaded_file($_FILES['somename']['tmp_name'], $uploadfile);
?>
Прайс успешно загружен
<meta http-equiv="refresh" content="2;URL=http://ваш сайт.com.ua/autoparts/admin" />