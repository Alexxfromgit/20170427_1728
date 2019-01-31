<?php

if (!(defined("TDM_PROLOG_INCLUDED")) || TDM_PROLOG_INCLUDED !== true) {
	exit();
}
if (extension_loaded("curl")) {
	if ($_POST["DOWNLOAD"] == "Y") {
		$Action = "download";
	}
	else {
		$Action = "preview";
	}
	if (TDM_LANG == "ru") {
		$Lng = "ru";
	}
	else {
		$Lng = "en";
	}
	//$ch = curl_init(TDM_UPDATES_SERVER . "updates/get.php?" . TDM_UPDATES_PARAMS . "&action=" . $Action . "&php=" . phpversion() . "&lng=" . $Lng . "&rd=" . TDM_ROOT_DIR . "&ap=" . urlencode($TDMCore->arConfig["MODULE_ADMIN_PASSW"]));
	//curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//$Response = curl_exec($ch);
	//curl_close($ch);
	/*$FinishCom = substr($Response, -12);
	if ($Response != "") {
		if ($FinishCom == "\$Finish=\"Y\";") {
			eval($Response);
		}
		else {
			ErAdd("Error! Damaged response from updates server! Try again later. ");
			echo($Response);
		}
	}
	else {
		ErAdd("Warning! Empty response from updates server!");
	}*/
}
else {
	ErAdd("CURL extension is not loaded on PHP!");
}
ErShow();

