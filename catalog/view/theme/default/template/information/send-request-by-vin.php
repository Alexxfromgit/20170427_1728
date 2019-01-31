<?php 

 if(!$_POST['name']){
 	echo 'oops, what are you doing here?';
 	exit;
 }
 
 $message = 'ФИО: ' . $_POST['name'] . "\r\n \r\n";  
 $message .= 'Телефон: ' . $_POST['phone']. "\r\n \r\n";  
 $message .= 'Электронная почта: ' . $_POST['email'] . "\r\n \r\n";  
 $message .= 'Номер кузова(VIN): ' . $_POST['vin'] . "\r\n \r\n";  
 $message .= 'Марка: ' . $_POST['mark'] . "\r\n \r\n";  
 $message .= 'Модель: ' . $_POST['model'] . "\r\n \r\n";  
 $message .= 'Двигатель: ' . $_POST['engine'] . "\r\n \r\n";  
 $message .= 'Объём: ' . $_POST['volume'] . "\r\n \r\n";   
 $message .= 'Страна изготовитель: ' . $_POST['country'] . "\r\n \r\n";     
 $message .= 'Месяц/Год выпуска: ' . $_POST['month'] . "\r\n \r\n";   
 $message .= 'КПП: ' . $_POST['gearshift'] . "\r\n \r\n";  
 $message .= 'Заказываемые запчасти: ' . $_POST['parts'] . "\r\n \r\n";  
 
 $email_to = 'info@technoride.com.ua';
 $email_from = 'info@technoride.com.ua';
 $subject = 'Запрос по VIN коду';
 
 $headers = 'From: '.$email_from."\r\n". 
'Reply-To: '.$email_from."\r\n" . 
'X-Mailer: PHP/' . phpversion();
 
 mail($email_to, $subject , $message, $headers); 

?>