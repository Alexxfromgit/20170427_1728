<?define('TDM_PROLOG_INCLUDED',true); define('TDM_ADMIN_SIDE',true);
require_once("../../tdmcore/init.php"); 
if($_SESSION['TDM_ISADMIN']!="Y"){header('Location: /'.TDM_ROOT_DIR.'/admin/'); die();}

$resDB = new TDMQuery;
$resDB->Select('TDM_IM_SUPPLIERS',Array(),Array("ID"=>$_REQUEST['ID']));
if(!$arSup = $resDB->Fetch()){header('Location: /'.TDM_ROOT_DIR.'/admin/import/'); die();}
$resDB->Select('TDM_IM_COLUMNS',Array(),Array("SUPID"=>$arSup['ID']));
if(!$arCol = $resDB->Fetch()){ErAdd(Tip("Columns_relations").' - 0',2);}

if(trim($arSup['FILE_PATH'])==''){ErAdd('<b>FILE_PATH</b> is empty',2);}
ini_set("auto_detect_line_endings", true); //Macintosh compatible break lines
ini_set('allow_url_fopen', true);
if(!ini_get('allow_url_fopen')){ErAdd('PHP .ini parameter <b>allow_url_fopen</b> - is false',2);}



?>
<head><title>TDMod :: <?=Lng('Import_master')?></title></head>
<div class="apanel_cont"><?require_once(TDM_PATH."/admin/apanel.php");?></div>
<div class="tdm_acontent">
	<h1><?=Lng('Import_master')?> :: <?=$arSup['NAME']?></h1><div class="tclear"></div>
	<hr>
	<?ErShow()?>
	<?
	if(ErCheck()){
		$arFPURL = parse_url($arSup['FILE_PATH']);
		$FPExt = TDMStrToLow(pathinfo($arFPURL['path'], PATHINFO_EXTENSION));
		//Download
		if($arFPURL['host']!=$_SERVER['HTTP_HOST']){
			$ch = curl_init($arSup['FILE_PATH']);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$CCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);
			if($CCode==200){
				//UnZip
				if($FPExt=='zip'){
					if(trim($arSup['FILE_NAME'])!=''){
						$NewFile = "downloads/temp.zip";
						$Bytes = file_put_contents($NewFile,fopen($arSup['FILE_PATH'],'r'));
						echo '<div class="imlog">Downloaded <b>'.round($Bytes/1024/1024,2).' Mb</b> from <a href="'.$arSup['FILE_PATH'].'">'.$arSup['FILE_PATH'].'</a> to /downloads/temp.zip</div>';
						
						$FPath = pathinfo(realpath($NewFile), PATHINFO_DIRNAME);
						$obZip = new ZipArchive;
						$ZRes = $obZip->open($NewFile);
						if($ZRes === TRUE){
							$obZip->extractTo($FPath); //$obZip->numFiles;
							$obZip->close();
							unlink($NewFile);
							echo '<div class="imlog"><b>ZIP</b> file extracted to /downloads/</div>';
							if(file_exists('downloads/'.$arSup['FILE_NAME'])){
								echo '<div class="imlog"><b>'.$arSup['FILE_NAME'].'</b> is founded</div>';
								//
							}else{
								foreach(glob('downloads/*') as $file){unlink($file);}
								ErAdd('File <b>'.$arSup['FILE_NAME'].'</b> is not exist in ZIP',2);
							}
						}else{
							ErAdd('Cant UnZip file <b>'.$NewFile.'</b>',2);
						}
					}else{
						ErAdd('<b>FILE_NAME</b> is empty',2);
					}
				//CSV
				}elseif($FPExt=='csv'){
					
				}else{
					ErAdd('Unsupported file extension <b>'.$FPExt.'</b>',2);
				}
			}else{
				ErAdd('File <b>'.$arSup['FILE_PATH'].'</b> - is not exist or not readable',2);
			}
		}
	}
	
	
	
	?>
	<br><br>
	<?ErShow()?>
</div>
