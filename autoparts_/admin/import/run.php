<?define('TDM_PROLOG_INCLUDED',true);
require_once("../../tdmcore/init.php"); 

//CRON params
//if(isset($argv[1])){$_REQUEST['ID'] = $argv[1];}
//if(isset($argv[2])){$_GET['KEY'] = $argv[2];}


if(intval($_REQUEST['ID'])<=0){header('Location: /'.TDM_ROOT_DIR.'/admin/'); die();}
if($_SESSION['TDM_ISADMIN']!="Y" AND $_GET['KEY']!=$TDMCore->arSettings['CRON_KEY']){
	header('Location: /'.TDM_ROOT_DIR.'/admin/'); die();
}
if($_SESSION['TDM_ISADMIN']=="Y"){define('TDM_ADMIN_SIDE',true);}else{$CRON="Y";}//for CRON 




$resDB = new TDMQuery;
$resDB->Select('TDM_IM_SUPPLIERS',Array(),Array("ID"=>$_REQUEST['ID']));
if(!$arSup = $resDB->Fetch()){header('Location: /'.TDM_ROOT_DIR.'/admin/import/'); die();}
//Hide for CRON
ob_start();
$resDB->Select('TDM_IM_COLUMNS',Array(),Array("SUPID"=>$arSup['ID']));
while($arCol = $resDB->Fetch()){$ClsCnt++;
	$arFtoN[$arCol['FIELD']] = ($arCol['NUM']-1);
}
if($ClsCnt<=0){ErAdd(Tip("Columns_relations").' - 0',2);}
if(trim($arSup['COLUMN_SEP'])==''){ErAdd("<b>COLUMN_SEP</b> is empty",2);}

if(trim($arSup['FILE_PATH'])==''){ErAdd('<b>FILE_PATH</b> is empty',2);}
ini_set("auto_detect_line_endings", true); //Macintosh compatible break lines
ini_set('allow_url_fopen', true);
if(!ini_get('allow_url_fopen')){ErAdd('PHP .ini parameter <b>allow_url_fopen</b> - is false',2);}
set_time_limit(3600);

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
		
		if($_GET['FP']==''){
			//foreach(glob('downloads/*') as $file){@unlink($file);} //clear
			//Check Remote
			if(($arFPURL['scheme']=="http" OR $arFPURL['scheme']=="https") AND $arFPURL['host']!=$_SERVER['HTTP_HOST']){
				$ch = curl_init($arSup['FILE_PATH']);
				curl_setopt($ch, CURLOPT_NOBODY, true);
				curl_exec($ch);
				$arHeaders = curl_getinfo($ch);
				curl_close($ch);
				if($arHeaders['http_code']==200){
					$FileLocation='Remote';
					if($arHeaders['content_type']=='application/zip'){$FPExt='zip';}
					if($arHeaders['content_type']=='application/x-rar-compressed' OR $arHeaders['content_type']=='application/octet-stream'){$FPExt='zip';}
					echo '<div class="imlog">File <a href="'.$arSup['FILE_PATH'].'">'.$arSup['FILE_PATH'].'</a> <b>exist on remote</b> server</div>';
				}else{
					ErAdd('File <b>'.$arSup['FILE_PATH'].'</b> - is not exist on remote server',2);
				}
			//Check Local
			}else{
				if(substr($arSup['FILE_PATH'],0,1)=='/'){$arSup['FILE_PATH']=$_SERVER['DOCUMENT_ROOT'].$arSup['FILE_PATH'];}
				$arSup['FILE_PATH']=str_replace('//','/',$arSup['FILE_PATH']);
				if(file_exists($arSup['FILE_PATH'])){
					$FileLocation='Local';
					echo '<div class="imlog">File '.$arSup['FILE_PATH'].' <b>exist on local</b> server</div>';
				}else{
					ErAdd('File not exist on local server <b>'.$arSup['FILE_PATH'].'</b>',2);
				}
			}
			
			if(ErCheck()){
				//UnZip
				if($FPExt=='zip'){
					if(trim($arSup['FILE_NAME'])!=''){
						$NewFile = "downloads/temp.zip";
						$Bytes = file_put_contents($NewFile,fopen($arSup['FILE_PATH'],'r'));
						$MbSize = round($Bytes/1024/1024,2);
						if($FileLocation=='Remote'){
							echo '<div class="imlog">Downloaded <b>'.$MbSize.' Mb</b> from <a href="'.$arSup['FILE_PATH'].'">'.$arSup['FILE_PATH'].'</a> to /downloads/temp.zip</div>';
							$FileLocation='Local';
						}elseif($FileLocation=='Local'){
							echo '<div class="imlog">Copyed <b>'.$MbSize.' Mb</b> from '.$arSup['FILE_PATH'].' to <b>downloads/temp.zip</b></div>';
						}					
						$FPath = pathinfo(realpath($NewFile), PATHINFO_DIRNAME);
						$obZip = new ZipArchive;
						$ZRes = $obZip->open($NewFile);
						if($arSup['FILE_PASSW']!=''){
							if(phpversion()>5.6){$code = $obZip->setPassword($arSup['FILE_PASSW']);}else{$ZRes=false; ErAdd('To UnZip <b>password protected</b> files you need <b>PHP >=5.6</b>',2);}
						}
						if($ZRes === TRUE){
							$obZip->extractTo($FPath); //$obZip->numFiles;
							$obZip->close();
							unlink($NewFile);
							echo '<div class="imlog"><b>ZIP</b> file extracted to <b>downloads/</b></div>';
							if(file_exists('downloads/'.$arSup['FILE_NAME'])){
								echo '<div class="imlog"><b>'.$arSup['FILE_NAME'].'</b> is founded and extracted</div>';
								$arSup['FILE_PATH'] = 'downloads/'.$arSup['FILE_NAME'];
							}else{
								//foreach(glob('downloads/*') as $file){unlink($file);}
								ErAdd('File <b>'.$arSup['FILE_NAME'].'</b> is not exist in ZIP',2);
							}
						}else{ErAdd('Cant UnZip file <b>'.$NewFile.'</b>',2);}
					}else{ErAdd('<b>FILE_NAME</b> - not set',2);}
				}
				//UnRar
				if($FPExt=='rar'){
					if(trim($arSup['FILE_NAME'])!=''){
						if(extension_loaded('rar')){
							$NewFile = "downloads/temp.rar";
							$Bytes = file_put_contents($NewFile,fopen($arSup['FILE_PATH'],'r'));
							$MbSize = round($Bytes/1024/1024,2);
							if($FileLocation=='Remote'){
								echo '<div class="imlog">Downloaded <b>'.$MbSize.' Mb</b> from <a href="'.$arSup['FILE_PATH'].'">'.$arSup['FILE_PATH'].'</a> to /downloads/temp.rar</div>';
								$FileLocation='Local';
							}elseif($FileLocation=='Local'){
								echo '<div class="imlog">Copyed <b>'.$MbSize.' Mb</b> from '.$arSup['FILE_PATH'].' to <b>downloads/temp.rar</b></div>';
							}
							if($rar = @rar_open(realpath($NewFile),$arSup['FILE_PASSW'])){
								if($entry = @rar_entry_get($rar,$arSup['FILE_NAME'])){
									if(@$entry->extract('downloads/')){
										rar_close($rar);
										unlink($NewFile);
										$arSup['FILE_PATH'] = 'downloads/'.$arSup['FILE_NAME'];
										echo '<div class="imlog"><b>'.$arSup['FILE_NAME'].'</b> is founded.</div>';
									}else{ErAdd('Failed to extract RAR - missing/wrong <b>PASSWORD</b> or bad data',2);}
								}else{ErAdd('Failed to find "<b>'.$arSup['FILE_NAME'].'</b>" in RAR',2);}
							}else{ErAdd('Cant open "<b>'.$NewFile.'</b>"',2);}
						}else{ErAdd('PHP extension "<b>php_rar.dll</b>" is not installed!',2);}
					}else{ErAdd('<b>FILE_NAME</b> - not set',2);}
				}
				$FPExt = TDMStrToLow(pathinfo($arSup['FILE_PATH'], PATHINFO_EXTENSION));
				
				
				if($FileLocation=='Remote'){
					$NewFile = "downloads/temp.".$FPExt;
					$Bytes = file_put_contents($NewFile,fopen($arSup['FILE_PATH'],'r'));
					$MbSize = round($Bytes/1024/1024,2);
					echo '<div class="imlog">Downloaded <b>'.$MbSize.' Mb</b> from <a href="'.$arSup['FILE_PATH'].'">'.$arSup['FILE_PATH'].'</a> to /'.$NewFile.'</div>';
					$arSup['FILE_PATH'] = $NewFile;
				}
					
				//XLSX
				if($FPExt=='xlsx' OR $FPExt=='xls'){
					echo '<div class="imlog">Try convert '.$arSup['FILE_NAME'].' to <b>CSV</b> format. PHP memory limit: <b>'.(int)(ini_get('memory_limit')).' Mb</b></div>';
					flush();
					require_once 'PHPExcel.php';
					require_once 'PHPExcel/IOFactory.php';
					$fileType = PHPExcel_IOFactory::identify($arSup['FILE_PATH']);
					$objReader = PHPExcel_IOFactory::createReader($fileType);
					$objReader->setReadDataOnly(true); 
					$objPHPExcel = $objReader->load($arSup['FILE_PATH']);
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
					$objWriter->setDelimiter($arSup['COLUMN_SEP']);
					$objWriter->setEnclosure("");
					$objWriter->setUseBOM();
					$arSup['FILE_PATH'] = str_replace('.'.$FPExt,'.csv',$arSup['FILE_PATH']);
					$objWriter->save($arSup['FILE_PATH']);					
					echo '<div class="imlog"><b>.'.$FPExt.' to .csv</b> conversion. Peak memory usage: <b>'.(memory_get_peak_usage(true)/1024/1024).' Mb</b>. Current memory usage: <b>'.(memory_get_usage(true)/1024/1024).' Mb</b></div>';
					$FPExt='csv';
				}
				
				//CSV
				if($FPExt=='csv' OR $FPExt=='txt'){
					echo '<div class="imlog">'.$arSup['FILE_PATH'].' file size <b>'.round((filesize($arSup['FILE_PATH'])/1024/1024),2).' Mb</b></div>';
				}else{ErAdd('Unsupported file extension <b>'.$FPExt.'</b>',2);}
			}
		}else{$arSup['FILE_PATH']=$_GET['FP'];}
		
		
		
		//IMPORT
		if(ErCheck() AND $arSup['FILE_PATH']!=''){
			ini_set('memory_limit', '512M');
			if($arCFile = file($arSup['FILE_PATH'])){
				$CSVcount = count($arCFile);
				if($arSup['START_FROM']<2){$arSup['START_FROM']=0;}else{$arSup['START_FROM']--;} //$Line from 0
				if($CSVcount>$arSup['START_FROM']){
					if($arSup['STOP_BEFORE']>$CSVcount OR $arSup['STOP_BEFORE']==0){$arSup['STOP_BEFORE']=$CSVcount;}
					echo '<div class="imlog"><b>'.$CSVcount.'</b> records in CSV file '.$arSup['FILE_PATH'].'</div>';
					
					
					$PriceTime = TDMSetPriceDate();
					$arStat=Array();
					$arVF=Array();
					$arInsDupl=Array('ARTICLE','ALT_NAME','BRAND','PRICE','CURRENCY','AVAILABLE','OPTIONS','DATE');
					$arLogicOps=Array('USED','RESTORED','DAMAGED','NORETURN','COPY','HOT');
					$arIntvOps=Array('SET','WEIGHT','PERCENTGIVE','MINIMUM');
					$arLngs=Array('ARTICLE'=>32,'ALT_NAME'=>128,'BRAND'=>32,'SUPPLIER'=>32,'STOCK'=>32,'OPTIONS'=>64,'CODE'=>32);
					$LIMIT=1000;
					$START=intval($_GET['START']);
					$INSERTED = intval($_GET['INS']);
					$IGNORED = intval($_GET['IGN']);
					
					//Удалить старые записи
					if($_GET['TEST']!='Y' AND $arSup['DELETE_ON_START']==1 AND $START<=0){
						mysql_query('DELETE FROM TDM_PRICES WHERE SUPPLIER="'.$arSup['NAME'].'" ');
						$DeletedOld = mysql_affected_rows();
						echo '<div class="imlog">Deleted old prices <b>'.$DeletedOld.'</b> records</div>';
						$arStat['Deleted old prices records'] = $DeletedOld;
					}
					
					$OnePerc=(($arSup['STOP_BEFORE']-$arSup['START_FROM'])/100);
					if($START>0){
						$TotalP = ceil($START/$OnePerc);
						$BxW = $TotalP*7; //LBox width:700px;
						$InW = ceil(($INSERTED/$OnePerc)*7);
						$IgW = ceil(($IGNORED/$OnePerc)*7);
					}else{
						$_SESSION['IM_DATA']=Array();
					}
					
					if($_GET['TEST']!='Y'){
						if($TotalP>98){$TotalP=100; $PRows=$CSVcount;}else{$PRows=$START+1;}?>
						<div class="imloaderbox">
							<table style="width:<?=$BxW?>px;"><tr><td class="inserted" style="width:<?=$InW?>px;"> <?=$INSERTED?></td><td class="ignoreg" style="width:<?=$IgW?>px;"> <?=$IGNORED?></td></tr></table> 
							<b><?=intval($TotalP)?>%</b> - <?=$PRows?> rows
						</div><?
					}
					
					foreach($arCFile as $Line=>$strLine){
						if($START<=0 AND $Line<$arSup['START_FROM']){continue;} 
						if($START>0 AND $Line<=$START){continue;} 
						if($Line>$arSup['STOP_BEFORE']){$SUCCESS="Y"; break;}
						if($_GET['TEST']!='Y'){
							$LnNum++;
						}
						
						define("TDM_ISEP",$arSup['COLUMN_SEP']);
						//$strLine = preg_replace_callback('/(?<!\\\)".*?(?<!\\\)"/s', create_function('$m','return str_replace(TDM_ISEP, ", ", $m[0]);'), $strLine); //Delete SEP inside columns with ""
						//$strLine = str_replace('"','',$strLine);
						$arCSVrow = explode(TDM_ISEP, $strLine);
						
						$arFields=Array();
						foreach($arFtoN as $FIELD=>$NUM){
							$arFields[$FIELD] = trim($arCSVrow[$NUM]);
						}
						
						
						//Brand column inside Article
						if($arSup['ARTBRA_SEP']!='' AND $arFields['ARTICLE_BRAND']!=''){
							$arAB = explode($arSup['ARTBRA_SEP'],$arFields['ARTICLE_BRAND']);
							if(count($arAB)>1){
								if($arSup['ARTBRA_SIDE']==1){
									$arFields['ARTICLE']=$arAB[0]; $arFields['BRAND']=$arAB[1];
								}elseif($arSup['ARTBRA_SIDE']==2){
									$arFields['ARTICLE']=$arAB[1]; $arFields['BRAND']=$arAB[0];
								}
							}else{$arStat['WRONG_ARTBRA_SEP']++;}
							unset($arFields['ARTICLE_BRAND']);
						}
						
						//ENCODE
						if($arSup['ENCODE']!='UTF-8'){
							if($arFields["ALT_NAME"]!=''){$arFields["ALT_NAME"] = iconv($arSup['ENCODE'], "UTF-8//TRANSLIT", $arFields["ALT_NAME"]);}
							if($arFields["BRAND"]!=''){$arFields["BRAND"] = iconv($arSup['ENCODE'], "UTF-8//TRANSLIT", $arFields["BRAND"]);}
							if($arFields["ARTICLE"]!=''){$arFields["ARTICLE"] = iconv($arSup['ENCODE'], "UTF-8//TRANSLIT", $arFields["ARTICLE"]);}
							if($arFields["DAY"]!=''){$arFields["DAY"] = iconv($arSup['ENCODE'], "UTF-8//TRANSLIT", $arFields["DAY"]);}
							if($arFields["AVAILABLE"]!=''){$arFields["AVAILABLE"] = iconv($arSup['ENCODE'], "UTF-8//TRANSLIT", $arFields["AVAILABLE"]);}
							if($arFields["STOCK"]!=''){$arFields["STOCK"] = iconv($arSup['ENCODE'], "UTF-8//TRANSLIT", $arFields["STOCK"]);}
						}
						
						//Defaults
						$arFields['SUPPLIER'] = $arSup['NAME'];
						$arFields['CODE'] = $arSup['CODE'];
						$arFields['DATE'] = $PriceTime;
						$arFields['TYPE'] = $arSup['PRICE_TYPE'];
						$arFields["PRICE"] = str_replace(' ','',$arFields["PRICE"]);
						$arFields["PRICE"] = str_replace(',','.',$arFields["PRICE"]);
						$arFields['PRICE'] = floatval($arFields['PRICE']);
						if($arFields['CURRENCY']==''){$arFields['CURRENCY']=$arSup['DEF_CURRENCY'];}
						if($arFields['AVAILABLE']==''){$arFields['AVAILABLE']=$arSup['DEF_AVAILABLE'];}
						if($arFields['STOCK']==''){$arFields['STOCK']=$arSup['DEF_STOCK'];}
						if($arFields['BRAND']==''){$arFields['BRAND']=$arSup['DEF_BRAND'];}
						
						//Cut
						foreach($arLngs as $LField=>$Lng){
							if(mb_strlen($arFields[$LField],'UTF-8')>$Lng){
								$arFields[$LField] = mb_substr(trim($arFields[$LField]),0,$Lng,'UTF-8');
							}
						}
						
						$arFields["BKEY"] = TDMSingleKey($arFields['BRAND'],true);
						$arFields["AKEY"] = TDMSingleKey($arFields['ARTICLE']);
						$arFields["ALT_NAME"] = TDMClearName($arFields["ALT_NAME"]);
						
						//OPTIONS
						$arOps = Array();
						if(isset($arFields['LITERS'])){if($arFields['LITERS']!=''){$arOps['LITERS']=floatval($arFields['LITERS']);} unset($arFields['LITERS']);}
						foreach($arIntvOps as $LOp){ //Intval options
							if(isset($arFields[$LOp])){if($arFields[$LOp]!=''){$arOps[$LOp]=intval($arFields[$LOp]);} unset($arFields[$LOp]);}
						}
						foreach($arLogicOps as $LOp){ //Logik options
							if(isset($arFields[$LOp])){if($arFields[$LOp]!=''){$arOps[$LOp]=1;} unset($arFields[$LOp]);}
						}
						$arFields["OPTIONS"] = TDMOptionsImplode($arOps,$arFields);
						//FIELDS
						$arFields["DAY"] = TDMOnlyNumbers($arFields["DAY"]);
						$arFields["AVAILABLE"] = TDMOnlyNumbers($arFields["AVAILABLE"]);
						if($arFields["AVAILABLE"]>9999){$arFields["AVAILABLE"]=9999;}
						if($arSup['DAY_ADD']!=0){$arFields['DAY']=$arFields['DAY']+$arSup['DAY_ADD'];}
						if($arFields["DAY"]>9999){$arFields["DAY"]=9999;}
						
						//Limits
						if($arSup['MIN_AVAIL']>0 AND $arFields["AVAILABLE"]<$arSup['MIN_AVAIL']){$arStat['Ignored by minimal availability']++; $IGNORED++; continue;}
						if($arSup['MAX_DAY']>0 AND $arFields["DAY"]>$arSup['MAX_DAY']){$arStat['Ignored by minimal delivery term']++; $IGNORED++; continue;}
						
						//Добавить наценку (скидку)
						if($arSup['CONSIDER_HOT']!=1 OR $arOps['HOT']!=1){ //Если цена содержит опцию "Распродажа" - то наценку (скидку) не применять
							if($arSup['PRICE_EXTRA']!=0){
								$arFields["PRICE"]=($arFields["PRICE"]+(($arFields["PRICE"]/100)*$arSup['PRICE_EXTRA']));
							}
						}
						$arFields["PRICE"]=round($arFields["PRICE"],2); 
						if($_GET['TEST']=='Y'){$arFields["PRICE"]=substr($arFields["PRICE"],0,strpos($arFields["PRICE"],'.',0)+3);}
						if($arSup['PRICE_ADD']>0){$arFields["PRICE"]=$arFields["PRICE"]+$arSup['PRICE_ADD']; }
						$arFields["PRICE"] = str_replace(',','.',$arFields["PRICE"]);
						
						//Clear
						$arFields["ARTICLE"]=str_replace('"','',$arFields["ARTICLE"]);
						$arFields["ARTICLE"]=str_replace("'",'',$arFields["ARTICLE"]);
						
						//echo '<pre>'; print_r($arFields); echo '</pre>';
						
						//INSERT / UPDATE
						if($arFields["BKEY"]!='' AND $arFields["AKEY"]!='' AND $arFields["PRICE"]>0){
							$arUKeys=Array(); $arUValue=Array(); $arUDuplc=Array();
							foreach($arFields as $key=>$value){
								$arUKeys[]=$key; 
								$arUValue[] = "'".mysql_real_escape_string($value)."'";
							}
							$qKeys = implode(',',$arUKeys);
							$qValues = implode(',',$arUValue);
							foreach($arInsDupl as $DKey){
								if($DKey=='ALT_NAME' AND $arFields['ALT_NAME']==''){continue;}
								$arUDuplc[] = $DKey."='".mysql_real_escape_string($arFields[$DKey])."'";
							}
							$qDuplc = implode(',',$arUDuplc);
							$SQL = "INSERT INTO TDM_PRICES 
(".$qKeys.") 
VALUES 
(".$qValues.") 
ON DUPLICATE KEY UPDATE 
".$qDuplc;
							$arFields['N']=$Line;
							//SQL QUERY
							if($_GET['TEST']!='Y'){
								mysql_query($SQL);
								if(mysql_error()!=''){
									echo '<div class="imlog"><pre>'.$SQL.'</pre></div>';
									ErAdd("MySQL Error: ".mysql_error()); 
									break;
								}
								if(count($arViewFs)<20){$arViewFs[] = $arFields;}
							//TEST
							}else{
								$arViewFs[] = $arFields;
								if($Line>300){break;}
							}
							
							$INSERTED++;
						}else{
							$arStat['Ignored without required fields']++;
							$IGNORED++;
						}
						
						if($LnNum>=$LIMIT AND $CRON!="Y"){
							$Location = '/'.TDM_ROOT_DIR.'/admin/import/run.php?ID='.$_REQUEST['ID'].'&START='.$Line.'&INS='.$INSERTED.'&IGN='.$IGNORED.'&FP='.$arSup['FILE_PATH'].'&TEST='.$_GET['TEST'];
							foreach($arStat as $STKey=>$STVal){
								$_SESSION['IM_DATA'][$STKey] = $_SESSION['IM_DATA'][$STKey]+$STVal;
							}
							$JSRedirect = '<script>window.setTimeout(function(){window.location.href = "'.$Location.'";},100);</script>';
							break;
						}
						
					}
				}else{ErAdd("CSV file: active lines count - <b>0</b>",2);}
			}else{ErAdd("Function false <b>file('".$arSup['FILE_PATH']."')</b> ",2);}
			
			//echo '<pre>'; print_r($_SESSION['IM_DATA']); echo '</pre>';
			?>
			
			<?if(count($arViewFs)>0){
				$arColumns = Array(
					"ARTICLE"=>Array("VALUE"=>Lng('Number'),"TITLE"=>Lng('Number',1,0)),
					"BRAND"=>Array("VALUE"=>Lng('Manufacturer'),"TITLE"=>Lng('Manufacturer',1,0)),
					"PRICE"=>Array("VALUE"=>Lng('Price'),"TITLE"=>Lng('Price',1,0)),
					"CURRENCY"=>Array("VALUE"=>"<img src='../images/currency.png' width='16' height='16' alt=''>","TITLE"=>Lng('Currency',1,0)),
					"AVAILABLE"=>Array("VALUE"=>"<img src='../images/avail.png' width='16' height='16' alt=''>","TITLE"=>Lng('Avail.',1,0)),
					"ALT_NAME"=>Array("VALUE"=>Lng('Name'),"TITLE"=>Lng('Name',1,0)),
					"STOCK"=>Array("VALUE"=>"<img src='../images/stock.png' width='16' height='16' alt=''>","TITLE"=>Lng('Stock',1,0)),
					"DAY"=>Array("VALUE"=>"<img src='../images/clock.png' width='16' height='16' alt=''>","TITLE"=>Lng('Dtime',1,0)),
					"OPTIONS"=>Array("VALUE"=>Lng('Options'),"TITLE"=>Lng('Options',1,0))
				);?>
				<br>
				<b>Demo price list parsing:</b><br>
				<br>
				<table class="etab"><tr class="head">
				<td class="ttip" title="Position">#</td>
				<?foreach($arColumns as $Code=>$arValues){?>
					<td class="ttip" title="<?=$arValues['TITLE']?>"><?=$arValues['VALUE']?></td>
				<?}?></tr>
				<?foreach($arViewFs as $arVF){
					$arOptions = TDMFormatOptions($arVF["OPTIONS"]);?>
					<tr class="rows">
					<td><?=$arVF['N']?></td>
					<td><?=$arVF['ARTICLE']?></td>
					<td><?=$arVF['BRAND']?></td>
					<td><?=$arVF['PRICE']?></td>
					<td><?=$arVF['CURRENCY']?></td>
					<td><?=$arVF['AVAILABLE']?></td>
					<td><?=$arVF['ALT_NAME']?></td>
					<td><?=$arVF['STOCK']?></td>
					<td><?=$arVF['DAY']?></td>
					<td><?=$arOptions['VIEW']?></td>
					</tr>
				<?}?>
				</table>
			<?}?>
			
			<table class="stattab">
			<?foreach($_SESSION['IM_DATA'] as $STKey=>$STVal){
				echo '<tr><td>'.$STKey.'</td><td>'.$STVal.'</td></tr>';
			}?>
			</table>
			<?if($SUCCESS=="Y"){?><br><div class="imsuccess">Import finished</div><?}?>
			
			
			<?
			if($_GET['TEST']=='Y'){?>
				<table class="stattab"><?
				foreach($arStat as $STKey=>$STVal){
					echo '<tr><td>'.$STKey.'</td><td>'.$STVal.'</td></tr>';
				}?>
				</table>
				<br><div class="imsuccess">TEST parsing finished</div><?
			}?>
			<?
		}
	}
	?>
	<br><br>
	<?ErShow()?>
</div>
<?=$JSRedirect?>
<?
ob_end_flush();
?>
