<?php
if ( $_POST['action'] && $_POST['action'] == 'file_delete' ) {
    $error = '';
    if ( !$_POST['filename'] )  $error .= "<li>Не указано имя файла</li>";
    if ( !$error )
    {
        $filename = $_POST['filename'];
        if ( file_exists("../../../../../image/cart/".$filename) )
        {
            unlink("../../../../../image/cart/".$filename);
            echo "success";
        }
        else echo "<div class=\"err\">Ошибка! Файл не найден на сервере</div>";
    }
    else echo "<div class=\"err\">Ошибка! ".$error."</div>";
}
?>