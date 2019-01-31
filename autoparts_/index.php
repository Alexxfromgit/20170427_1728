<?php
    namespace tdmcore {

	define("TDM_DOMAIN", $_SERVER["SERVER_NAME"]);
	define("TDM_PROLOG_INCLUDED", true);
	require_once("tdmcore/defines.php");
	global $TDMContent;
	global $TDMAPanel;
	global $TDMTop;
	global $TDMComponent;
	ob_start();
	
	
	require_once("tdmcore/init.php");


	$arUrlRewrite = array(array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/import/(.+?)/([0-9]+).php#", "RULE" => "ID=\$2&KEY=\$1"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/(.+?)/(.+?)/(.+?)/(.+?)/(.+?)/(.)of=m([0-9]+);t([0-9]+);s([0-9]+)&page=([0-9]+)#", "RULE" => "com=sectionparts&brand=\$1&mod_name=\$2&type_name=\$3&sec_name=\$4&subsec_name=\$5&mod_id=\$7&type_id=\$8&sec_id=\$9&page=\$10", "CACHE" => "\$8_\$9_p\$10"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/(.+?)/(.+?)/(.+?)/(.+?)/(.+?)/(.)of=m([0-9]+);t([0-9]+);s([0-9]+)#", "RULE" => "com=sectionparts&brand=\$1&mod_name=\$2&type_name=\$3&sec_name=\$4&subsec_name=\$5&mod_id=\$7&type_id=\$8&sec_id=\$9", "CACHE" => "\$8_\$9"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/(.+?)/(.+?)/(.+?)/(.+?)/(.)of=m([0-9]+);t([0-9]+);s([0-9]+)#", "RULE" => "com=subsections&brand=\$1&mod_name=\$2&type_name=\$3&sec_name=\$4&mod_id=\$6&type_id=\$7&sec_id=\$8", "CACHE" => "\$7\$8"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/(.+?)/(.+?)/(.+?)/(.)of=m([0-9]+);t([0-9]+)#", "RULE" => "com=sections&brand=\$1&mod_name=\$2&type_name=\$3&mod_id=\$5&type_id=\$6", "CACHE" => "\$6"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/(.+?)/(.+?)/(.)of=m([0-9]+)#", "RULE" => "com=types&brand=\$1&mod_name=\$2&mod_id=\$4", "CACHE" => "\$4"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/search/(.+?)/(.+?)/#", "RULE" => "com=analogparts&article=\$1&brand=\$2", "CACHE" => "\$1\$2"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/search/(.+?)/(.+?)#", "RULE" => "com=analogparts&article=\$1&brand=\$2", "CACHE" => "\$1\$2"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/search/(.+?)/#", "RULE" => "com=searchparts&article=\$1", "CACHE" => "\$1"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/search/(.+?)#", "RULE" => "com=searchparts&article=\$1", "CACHE" => "\$1"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/(.+?)/#", "RULE" => "com=models&brand=\$1", "CACHE" => "\$1"), array("CONDITION" => "#^/" . TDM_ROOT_DIR . "/#", "RULE" => "com=manufacturers&last=\$1", "CACHE" => "main"));
	foreach ($arUrlRewrite as $arVal) {
		//	print("Error TDM_D");
	
		if (!(preg_match($arVal["CONDITION"], $_SERVER["REQUEST_URI"]))) {
		continue;
	    }
	    $pURL = preg_replace($arVal["CONDITION"], $arVal["RULE"], $_SERVER["REQUEST_URI"]);
	    $CacheName = preg_replace($arVal["CONDITION"], $arVal["CACHE"], $_SERVER["REQUEST_URI"]);
	    parse_str($pURL, $vars);
	    $_GET += $vars;
	    $_REQUEST += $vars;
	    $GLOBALS += $vars;
	    break;
		
	}
	if (0 < $_REQUEST["ID"] && $_REQUEST["KEY"] != "") {
		tdmcore\chdir(TDM_PATH . "/admin/import/");
		require_once(TDM_PATH . "/admin/import/run.php");
		exit();
	}
	require_once("includes.php");
	if ($_REQUEST["com"] != "") {
		$arComSets = TDMGetSets($_REQUEST["com"]);
		     
		if ($arComSets) {
			$CachePath = TDM_PATH . "/tdmcore/cache/" . $_REQUEST["com"] . "/" . $CacheName . "_" . TDM_LANG . ".php";
			if ($TDMCore->arSettings["USE_CACHE"] && $arComSets["CACHE"] && ErCheck()) {
				if (file_exists($CachePath)) {
					if ($_POST["recache"] == "Y" && $_SESSION["TDM_ISADMIN"] == "Y") {
						array_map("unlink", glob(TDM_PATH . "/tdmcore/cache/" . $_REQUEST["com"] . "/*"));
					}
					else {
						define("TDM_CCACHE_INCLUDED", true);
					}
				}
			}
			$TDMTop = GetPHPCached();
			if ($_SESSION["TDM_ISADMIN"] == "Y") {
				require_once("admin/apanel.php");
				$TDMAPanel = GetPHPCached();
			}
			if (defined("TDM_CCACHE_INCLUDED")) {
				require_once($CachePath);
			}
			else {
				$ComPath = TDM_PATH . "/tdmcore/components/" . $_REQUEST["com"] . "/component.php";
				if (file_exists($ComPath)) {
					$TScriptName = "template";
					require_once($ComPath);
					ErShow();
					if ($_REQUEST["com"] == "searchparts" || $_REQUEST["com"] == "sectionparts" || $_REQUEST["com"] == "analogparts") {
						$TemType = "partslist";
					}
					else {
						$TemType = $_REQUEST["com"];
					}
					$TemPath = TDM_PATH . "/templates/" . $TemType . "/" . $arComSets["TEMPLATE"] . "/" . $TScriptName . ".php";
					if (file_exists($TemPath)) {
						$StylPath = TDM_PATH . "/templates/" . $TemType . "/" . $arComSets["TEMPLATE"] . "/style.css";
						if (file_exists($StylPath)) {
							echo("<link rel=\"stylesheet\" href=\"/" . TDM_ROOT_DIR . "/templates/" . $TemType . "/" . $arComSets["TEMPLATE"] . "/style.css\" type=\"text/css\">");
						}
						$JsPath = TDM_PATH . "/templates/" . $TemType . "/" . $arComSets["TEMPLATE"] . "/funcs.js";
						if (file_exists($JsPath)) {
							echo("<script src=\"/" . TDM_ROOT_DIR . "/templates/" . $TemType . "/" . $arComSets["TEMPLATE"] . "/funcs.js\"></script>");
						}
						require_once($TemPath);
						if ($TDMCore->arSettings["USE_CACHE"] && $arComSets["CACHE"] && ErCheck()) {
							$CDir = dirname($CachePath);
							if (!(is_dir($CDir))) {
								mkdir($CDir, 493, true);
							}
							if ($cHand = fopen($CachePath, "w")) {
								$CachMeta = GetComMetaForCache();
								fwrite($cHand, $CachMeta . ob_get_contents());
								fclose($cHand);
							}
						}
					}
					else {
						ErAdd("Components \"" . $_REQUEST["com"] . "\" - template \"" . $arComSets["TEMPLATE"] . "\" not exists...");
					}
				}
				else {
					ErAdd("Component \"" . $_REQUEST["com"] . "\" not exists...");
				}
			}
			$TDMComponent = GetPHPCached();
		}
		else {
			ErAdd("No settings file associated with component \"" . $_REQUEST["com"] . "\" ");
		}
	}
	else {
		ErAdd("No component name associated with FURL...");
	}
  if ($TDMCore->arSettings["SHOW_SEARCHFORM"] == 1) {
		require_once("searchform.php");
	} 
	ErShow();
	
	global $TDMContent;
	$TDMContent .= $TDMTop;
	if ($TDMCore->arSettings["APANEL_POSITION"] != "Bottom") {
		$TDMContent .= $TDMAPanel;
	}
	$TDMContent .= "<div class=\"tdm_content\">";
	$TDMContent .= GetPHPCached();
	$TDMContent .= $TDMComponent;
	$TDMContent .= "<div class=\"tclear\"></div>";
	if ($_SESSION["TDM_ISADMIN"] == "Y" && $TDMCore->arSettings["SHOW_STAT"] == 1) {
		$TDMContent .= TDMShowStat();
	}
	$TDMContent .= "</div>";
	if ($TDMCore->arSettings["APANEL_POSITION"] == "Bottom") {
		$TDMContent .= $TDMAPanel;
	}
/*	if (defined("TDM_VERSION")) {
		$TDMContent .= "<a href=\"http://tecdoc-module.com\" target=\"_blank\" class=\"tdmversion\">v " . TDMShowVersion(TDM_VERSION) . "</a><style>.tdmversion{font-size:10px; display:block; color:#c1c1c1!important; float:right; margin:8px;}</style>";
	}*/
	
	ob_end_clean();
	
	if ($TDMCore->arSettings["CMS_INTEGRATION"] == "") {
		$TDMCore->arSettings["CMS_INTEGRATION"] = "NoCMS";
	}
	require_once(TDM_PATH . "/tocms/" . $TDMCore->arSettings["CMS_INTEGRATION"] . ".php");
}

