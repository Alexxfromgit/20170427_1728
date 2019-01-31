<?define('TDM_PROLOG_INCLUDED',true);?>
<div class="breadcumbs">
	<a href="<?=$Link?>"><?=Lng('Catalog',1,0)?></a> &nbsp;
	<?if($_REQUEST['article']!=''){
		$Link.= 'search/'.$_REQUEST['article'].'/';?>
		&#187;&nbsp; <a href="<?=$Link?>"><?=Lng('Number',1,0)?>: <?=$_REQUEST['article']?></a> &nbsp;
	<?}?>
	<?if($_REQUEST['brand']!=''){
		$Link.= $arBrnd['tcode'].'/';?>
		&#187;&nbsp; <a href="<?=$Link?>"><?=$arBrnd['uname']?></a> &nbsp;
	<?}?>
	<?if($_REQUEST['mod_name']!=''){
		$Link.= $_REQUEST['mod_name'].'/';?>
		&#187;&nbsp; <a href="<?=$Link?>?of=m<?=$_REQUEST['mod_id']?>"><?=$MODELNAME?></a> &nbsp;
	<?}?>
	<?if($_REQUEST['type_name']!=''){
		$Link.= $_REQUEST['type_name'].'/';?>
		&#187;&nbsp; <a href="<?=$Link?>?of=m<?=$_REQUEST['mod_id']?>;t<?=$_REQUEST['type_id']?>"><?=$TYPENAME?></a> &nbsp;
	<?}?>
	<?if($_REQUEST['sec_name']!=''){
		if($_REQUEST['subsec_name']==''){$Link='javascript:void(0)';}else{$Link=$Link.$_REQUEST['sec_name'].'/?of=m'.$_REQUEST['mod_id'].';t'.$_REQUEST['type_id'].';s'.$RootSID;}
		?>
		&#187;&nbsp; <a href="<?=$Link?>"><?=$SectionName?></a> &nbsp;
	<?}?>
	<?if($_REQUEST['subsec_name']!=''){?>
		&#187;&nbsp; <a href="javascript:void(0)"><?=$SubSectionName?></a>
	<?}?>
</div>