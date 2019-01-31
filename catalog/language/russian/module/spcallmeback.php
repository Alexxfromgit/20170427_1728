<?php

global $session, $languages;
$code = $session->data['language'];

require(DIR_APPLICATION.'/../admin/language/'.$languages[$code]['directory'].'/module/spcallmeback.php');

?>