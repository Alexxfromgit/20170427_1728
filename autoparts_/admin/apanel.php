<?if(!defined("TDM_PROLOG_INCLUDED") || TDM_PROLOG_INCLUDED!==true)die();?>
<?if($_SESSION['TDM_ISADMIN']=="Y"){
	define("TDM_ADMIN_PANEL","Y");
	require_once(TDM_PATH.'/includes.php');
	?>
	<link rel="stylesheet" href="/<?=TDM_ROOT_DIR?>/admin/styles.css" type="text/css">
	<script src="/<?=TDM_ROOT_DIR?>/admin/functions.js"></script>
	
	
	
	<div class="admin_panel">
		<a href="/<?=TDM_ROOT_DIR?>/" class="apbut catalog" title="<?=Lng('Catalog',1,false)?>"></a>
		<?if(!defined("TDM_ADMIN_SIDE")){
			$META_EDIT="Y";?>
			<a href="javascript:void(0);" onclick="$('#metalay').slideToggle();" class="apbut meta <?if(defined("TDM_HAVE_SEOMETA")){?>bactive<?}?>" title="">SEO-Meta</a>
		<?}?>
		<?if($_REQUEST['com']!=''){
			if($_REQUEST['brand']!=''){$RLast.='&brand='.$_REQUEST['brand'];}
			if($_REQUEST['sec_id']>0){$RLast.='&sec_id='.$_REQUEST['sec_id'];}
			?>
			<a href="/<?=TDM_ROOT_DIR?>/admin/comsets.php?to=<?=$_REQUEST['com'].$RLast?>" target="_blank" class="apbut comsets" title="<?=Lng('Edit',1,false)?> <?=Lng('this',2,false)?> <?=Lng('Component',2,false)?>"><?=Lng('Component',1,false)?></a>
		<?}?>
		<?if(defined("TDM_CCACHE_INCLUDED")){?>
			<form action="" id="recache_fomr" method="post"><input type="hidden" name="recache" value="Y"></form>
			<a href="javascript:void(0);" onclick="$('#recache_fomr').submit();" class="apbut recache" title="<?=Tip('Refresh_component_cache')?>"></a>
		<?}?>
		
		<a href="/<?=TDM_ROOT_DIR?>/admin/?logout=Y" class="apbut apr exit" title="<?=Lng('Logout',1,false)?>"></a>
		<a href="/<?=TDM_ROOT_DIR?>/admin/dbserv.php" class="apbut apr dbserv" title="<?=Lng('Database_service',1,false)?>"></a>
		<a href="/<?=TDM_ROOT_DIR?>/admin/" class="apbut apr ainfo" title="<?=Tip('Tecdoc_module')?>"></a>
		<a href="/<?=TDM_ROOT_DIR?>/admin/settings.php" class="apbut apr setts" title="<?=Lng('Module_settings',1,false)?>"></a>
				<a href="/<?=TDM_ROOT_DIR?>/admin/upd.php" class="apbut apr setts" ">Загрузка прайса</a>

		<div class="submdiv" id="curbut">
			<a href="/<?=TDM_ROOT_DIR?>/admin/curs.php" class="apbut apr crates" title="<?=Lng('Exchange_rates',1,false)?>"><?=TDM_CUR?></a>
			<div class="asubmenu" id="cursubmenu">
				<div style="float:left;">
				<?foreach($TDMCore->arCurs as $Cur=>$arCur){?>
					<a href="javascript:void(0)" onclick="$('#crv').val('<?=$Cur?>'); $('#crf').submit();"><?=$Cur?></a>
				<?}?>
				</div>
				<div style="float:left; border-left:1px solid #D4D4D4;">
					<?foreach($TDMCore->arPriceType as $PtID=>$PtName){
						if($_SESSION['TDM_USER_GROUP']==$PtID){$Style='style="color:#FFBB00;"';}else{$Style='';}?>
						<a href="javascript:void(0)" onclick="$('#ctp').val('<?=$PtID?>'); $('#tpf').submit();" <?=$Style?> ><?=$PtName?></a>
					<?}?>
				</div>
			</div>
		</div>
		<div class="submdiv" id="lngbut">
			<a href="/<?=TDM_ROOT_DIR?>/admin/langs.php" class="apbut apr langs" ><?=UWord(TDM_LANG)?></a>
			<div class="asubmenu" id="lngsubmenu">
				<?foreach($TDMCore->arLangs as $Lng){
					if(in_array($Lng,$TDMCore->arConfig['MODULE_ACTIVE_LNG'])){?>
						<a href="javascript:void(0)" onclick="$('#lnv').val('<?=$Lng?>'); $('#lnf').submit();"><?=UWord($Lng)?></a>
					<?}?>
				<?}?>
			</div>
		</div>
		
		<a href="/<?=TDM_ROOT_DIR?>/admin/ws.php" class="apbut apr webserv" title="<?=Lng('Webservices',1,false)?>"></a>
		<a href="/<?=TDM_ROOT_DIR?>/admin/dbedit.php" class="apbut apr dbedit" title="<?=Tip('DB_Editor')?>"><?=Lng('Editor',1,false)?></a>
		<a href="/<?=TDM_ROOT_DIR?>/admin/import/" class="apbut apr import" title="<?=Lng('Import_master',1,false)?>"><?=Lng('Import',1,false)?></a>
		
		
		<div class="tclear"></div>
	</div>
	<form id="crf" method="POST"><input type="hidden" name="SET_CUR" id="crv" value=""></form>
	<form id="lnf" method="POST"><input type="hidden" name="SET_LANG" id="lnv" value=""></form>
	<form id="tpf" method="POST"><input type="hidden" name="SET_TYPE" id="ctp" value=""></form>
	
	<?if($META_EDIT=="Y"){?>
		<div class="sublay" id="metalay">
			<form action="" method="post">
			<input type="hidden" name="tdm_set_meta" value="Y">
			<input type="hidden" name="recache" value="Y">
			<table class="sublaytab">
				<tr><td></td><td><span class="tiptext"><?=Tip('With_form_create_unique_SEO');?></span></td></tr>
				<tr><td>Title: </td><td><input type="text" name="TITLE" value="<?if(defined("TDM_TITLE")){echo TDM_TITLE;}?>" class="subinput" placeholder="<?=Tip('if_seometa_not_set',0,0)?>"></td></tr>
				<tr><td>Keywords: </td><td><input type="text" name="KEYWORDS" value="<?if(defined("TDM_KEYWORDS")){echo TDM_KEYWORDS;}?>" class="subinput" placeholder="<?=Tip('if_seometa_not_set',0,0)?>"></td></tr>
				<tr><td>Description: </td><td><input type="text" name="DESCRIPTION" value="<?if(defined("TDM_DESCRIPTION")){echo TDM_DESCRIPTION;}?>" class="subinput" placeholder="<?=Tip('if_seometa_not_set',0,0)?>"></td></tr>
				<tr><td><nobr><?=Lng('Title')?> H1: </nobr></td><td><input type="text" name="H1" value="<?if(defined("TDM_H1")){echo TDM_H1;}?>" class="subinput" placeholder="<?=Tip('if_seometa_not_set',0,0)?>"></td></tr>
				<tr><td><nobr><?=Tip('Top_SEO_text')?>: </nobr></td><td><textarea name="TOPTEXT" class="subinput sbinp"><?if(defined("TDM_TOPTEXT")){echo TDM_TOPTEXT;}?></textarea>
				<tr><td><nobr><?=Tip('Bottom_SEO_text')?>: </nobr></td><td><textarea name="BOTTEXT" class="subinput sbinp"><?if(defined("TDM_BOTTEXT")){echo TDM_BOTTEXT;}?></textarea></td></tr>
				<tr><td></td><td>
					<input type="submit" value="<?=Lng('Save')?>" class="abutton"/> 
					<?if(defined("TDM_HAVE_SEOMETA")){?>
						<input type="submit" name="set_delete" value="<?=Tip('Delete_this_Meta_record')?>" class="abutton smbut smgrey flrig"/>
					<?}?>
				</td></tr>
			</table>
			</form>
		</div>
		<?if($_POST['tdm_set_meta']=="Y"){?><script>$('#metalay').show();</script><?}?>
	<?}?>
	
	
	<script type="text/javascript">
		$('#lngbut').hover(function(){ $('#lngsubmenu').show();}, function() { $('#lngsubmenu').hide(); });
		$('#curbut').hover(function(){ $('#cursubmenu').show();}, function() { $('#cursubmenu').hide(); });
	</script>
		
<?}?>