<?php

if (extension_loaded("curl") && $_GET["action"] != "") {
	define("TDM_PROLOG_INCLUDED", true);
	require_once("../tdmcore/init.php");
	//$ch = curl_init(TDM_UPDATES_SERVER . "src/response.php?" . TDM_UPDATES_PARAMS . "&file=" . $_GET["action"]);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//$Response = curl_exec($ch);
	//curl_close($ch);
	$FinishCom = substr($Response, -12);
	if ($Response != "") {
		if ($FinishCom == "\$Finish=\"Y\";") {
			eval($Response);
		}
		else {
			echo("Error! Damaged response from updates server! Try again later.");
		}
	}
	else {
		echo("Warning! Empty response from updates server!");
	}
}
else {
	echo("CURL extension is not loaded on PHP!");
}

