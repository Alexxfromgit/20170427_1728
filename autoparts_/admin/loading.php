<?php
  $uploadfile = "home/perova/24parts.in.ua/www/price/".$_FILES['somename']['name'];
  move_uploaded_file($_FILES['somename']['tmp_name'], $uploadfile);
?>