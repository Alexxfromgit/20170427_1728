<?if(!defined("TDM_PROLOG_INCLUDED") || TDM_PROLOG_INCLUDED!==true)die();
?>
<script>
	$(document).ready(function(){
		$('.moreprops').click(function(){ 
			$(this).prev('.itemprops').find('.criteria').removeClass('hidden');
				$(this).remove();
		});
	})
 
</script>

<script type="text/javascript">
   function externalLinks() {
    links = document.getElementsByTagName("a");
    for (i=0; i<links.length; i++) {
      link = links[i];
      if (link.getAttribute("href") && link.getAttribute("rel") == "external")
      link.target = "_blank";
    }
   }
   window.onload = externalLinks;
 </script>
 
 
<table class="corp_table corp_table_2">
	<tr class="head2"> 
		 
		<td colspan="5"> 
				<form action="<?=$arResult['FIRST_PAGE_LINK']?>" id="sortform" method="post">
					<?=Lng('Sort_by',1,0)?>:  
					<select name="SORT" id="sortby" class="styled" style="width:300px;" OnChange="$('#sortform').submit();">
						<option value="1" ><?=Lng('Sort_brand_rating_price',1,0)?></option>
						<option value="2" <?if($arResult['SORT']==2){echo 'selected';}?> ><?=Lng('Sort_description_price',1,0)?></option>
						<option value="3" <?if($arResult['SORT']==3){echo 'selected';}?> ><?=Lng('Sort_lowest_price',1,0)?></option>
						<option value="4" <?if($arResult['SORT']==4){echo 'selected';}?> ><?=Lng('Sort_lowest_delivery_time',1,0)?></option>
						<option value="5" <?if($arResult['SORT']==5){echo 'selected';}?> ><?=Lng('Sort_photo_available',1,0)?></option>
					</select>
				 
				</form> 
		</td>
		<td>
			
			 
			
		</td>
		
	</tr>
	<?
	foreach($arResult['PARTS'] as $NumKey=>$arPart){
		$Cnt++; $PCnt=0; $OpCnt=0; $cm=''; $AddF=0;
		//Criteria display method
		if($arPart['CRITERIAS_COUNT']>0){
			foreach($arPart['CRITERIAS'] as $Criteria=>$Value){ 
				if($Criteria!=''){$arPart['CRITERIA'].=$cm.$Criteria.' - '.$Value;}else{$arPart['CRITERIA'].=$cm.UWord($Value);} $cm='; ';
			}
		}
		//Pictures display method
		if($arPart['IMG_ZOOM']=='Y'){
			$Zoom=$arPart['IMG_SRC']; $ZClass='cbx_imgs';
			$PicText=''; $Target='';
		}else{
			$Zoom='https://www.google.com/search?q='.$arPart['BRAND'].'+'.$arPart['ARTICLE'].'&tbm=isch'; $ZClass='';
			$PicText=Lng('Search_photo_in_google',1,0); $Target='target="_blank"';
		}
		if(TDM_ISADMIN AND $arPart['LINK_CODE']!=''){$BrandClass='linked';
			$BrLink = '<a href="/'.TDM_ROOT_DIR.'/admin/dbedit.php?selecttable=Y&table=TDM_LINKS&LINK='.$arPart['LINK_LEFT_AKEY'].'" target="_blank" class="ttip link" title="'.$arPart['LINK_INFO'].'<br>'.$arPart['LINK_CODE'].'"></a>';
		}else{$BrandClass=''; $BrLink='';}
		?>
		<tr class="cols pads2"> 
		
		<td>
			<?if(is_array($arPart["IMG_ADDITIONAL"])){
				foreach($arPart["IMG_ADDITIONAL"] as $AddImgSrc){ $AddF++;?><a href="<?=$AddImgSrc?>" class="cbx_imgs" rel="img<?=$arPart['PKEY']?>" title="<?=$arPart['BRAND']?> <?=$arPart['ARTICLE']?>"></a><?}
			}?>
			<a href="<?=$Zoom?>" class="image <?=$ZClass?>" rel="img<?=$arPart['PKEY']?>" <?=$Target?> title="<?=$arPart['BRAND']?> <?=$arPart['ARTICLE']?>">
				<?if($PicText!=''){?>
					<div class="gosrch ttip" title="<?=$PicText?>"><?=Lng('Search_photo',1,0)?></div>
				<?}else{?>
					<div class="prevphoto" style="background-image:url('<?=$arPart['IMG_SRC']?>');" ><?if($AddF>0){?><div class="addphoto" title="<?=Lng('Photo_count',1,0);?>">x<?=($AddF+1)?></div><?}?></div>
				<?}?>
			</a>
		</td>
		
		<td class="tdbrand"> 
			
		 <?if($arPart["AID"]>0){?>
				<a href="javascript:void(0)" OnClick="AppWin('<?=TDM_ROOT_DIR?>',<?=$arPart["AID"]?>,980)" class="carsapp" target="_blank" title="<?=Lng('Applicability_to_model_cars',1,0)?>"></a>
				<a href="/<?=TDM_ROOT_DIR?>/props.php?of=<?=$arPart["AID"]?>" class="dopinfo popup" target="_blank" title="<?=Lng('Additional_Information',1,0)?>"></a>
			<?}?>
		 
		 <?php if($arPart['NAME']){  
		    echo '<h3>' .$arPart['NAME']. '</h3>';
		    echo '<div class="dotted"></div>';
	   } ?>	
		 
			
			
			<a href="javascript:void(0)" class="<?=$BrandClass?>" title="<?echo Lng('Information_about_brand',0,0);?>"><?=$arPart['BRAND']?></a>
			<?=$BrLink?><br>
			<div class="ttip" <?if(TDM_ISADMIN){?>ttip" title="BKEY: <?=$arPart['BKEY']?><br>AKEY: <?=$arPart['AKEY']?><br>ID:<?=$arPart['AID']?><?}?>"><?=$arPart['ARTICLE']?></div>
			<?if($arPart['KIND']>0){?><span style="font-size:11px;"><?=TDMPrintArtKinde($arPart['KIND']);?></span><?}?>
		
		
		
		</td>
		<?/*
		<td class="article <?if(TDM_ISADMIN){?>ttip" title="BKEY: <?=$arPart['BKEY']?><br>AKEY: <?=$arPart['AKEY']?><br>ID:<?=$arPart['AID']?><?}?>">
			<?=$arPart['ARTICLE']?>
		</td>*/?>
		
		<td class="opts">
			
			<div class="criteria"><?=$arPart['CRITERIA']?></div>
			<div class="itemprops" id="props<?=$arPart['PKEY']?>">
				<?if($arPart["PROPS_COUNT"]>0){
					$k = 1;
					foreach($arPart['PROPS'] as $PName=>$PValue){
						if ($k <= 3){ ?>
							<div class="criteria"><?=$PName?><?if($PValue!=''){?>: <?=$PValue?><?}?></div>
						<?php } else { ?>
							 <div class="criteria hidden"><?=$PName?><?if($PValue!=''){?>: <?=$PValue?><?}?></div>
						<?php }  
						$k++; }
				}?>
				<div class="moreprops2"><a style="background:#FFF3B5;" href="/kontakti" rel="external" class="lookup_analogues">Наличие уточняйте у менеджера</a></div><br>
			</div>
			<?if($arPart["PROPS_COUNT"]>3){?>
				<div class="moreprops"><a href="javascript:;"><?=Lng('Show_more_properties',1,false)?> (<?=($arPart["PROPS_COUNT"]-3)?>)</a></div>
			<?}?>
			<?if((!isset($_GET['brand'])) OR (TDMSingleKey($_GET['article'])!=TDMSingleKey($arPart['AKEY']) AND TDMSingleKey($_GET['brand'],true)!=TDMSingleKey($arPart['BRAND'],true)) ){?>
				<div></div>
				<div class="moreprops2"><a href="/<?=TDM_ROOT_DIR?>/search/<?=$arPart['AKEY']?>/<?=BrandNameEncode($arPart['BRAND'])?>/" class="lookup_analogues"><?=Lng('Lookup_analogues',1,0)?></a></div>
			<?}?>
		</td>
		<td style="white-space:nowrap;" class="rigbord">
			<?if($arPart["AID"]>0){?><table class="propstb"><tr><td>
				<a href="/<?=TDM_ROOT_DIR?>/props.php?of=<?=$arPart["AID"]?>" class="dopinfo popup" title="<?=Lng('Additional_Information',1,0)?>"></a></td><td>
				<a href="javascript:void(0)" OnClick="AppWin('<?=TDM_ROOT_DIR?>',<?=$arPart["AID"]?>,980)" class="carsapp" target="_blank" title="<?=Lng('Applicability_to_model_cars',1,0)?>"></a></table>
			<?}?>
		</td>
		<td class="options">
			<?if($arPart["PRICES_COUNT"]>0){?>
				<table class="optionstab">
				<?foreach($arResult['PRICES'][$arPart['PKEY']] as $arPrice){ $OpCnt++;
					if($OpCnt>$arResult['LIST_PRICES_LIMIT']){$OpClass='op'.$arPart['PKEY']; $OpStyle='style="display:none;"'; }else{$OpClass=''; $OpStyle='';}?>
					<tr class="<?=$OpClass?>" <?=$OpStyle?> ><td><?=$arPrice['OPTIONS']['VIEW_INTAB']?></td></tr>
				<?}?>
				</table>
			<?}?>
		</td>
		<td style="padding:0px;" class="opts2">
			<?if($arPart["PRICES_COUNT"]>0){?>
				<table class="listprice">
				<?foreach($arResult['PRICES'][$arPart['PKEY']] as $arPrice){
					$PCnt++;
					if($PCnt>1){$TopBord='topbord';}else{$TopBord='';}
					if($PCnt>$arResult['LIST_PRICES_LIMIT']){$HClass='pr'.$arPart['PKEY']; $HStyle='style="display:none;"'; }else{$HStyle=''; $HClass='';}?>
					<tr class="trow <?=$HClass?> <?=$TopBord?>" <?=$HStyle?> >
						<td class="avail"><?=$arPrice['AVAILABLE']?> шт</td>
						<td class="day ttip" <?if(TDM_ISADMIN){?>title="<?=$arPrice['INFO']?>"<?}?> >
							<?=$arPrice['DAY']?> суток
						</td>
						<td class="cost ttip">
							<?if($arPrice['EDIT_LINK']!=''){?><a  href="<?=$arPrice['EDIT_LINK']?>" class="popup editprice" title="<?=Lng('Price',1,0)?>: <?=Lng('Edit',2,0)?>"><?}?>
							<?=$arPrice['PRICE_FORMATED']?></a> <b>грн</b>
						</td>
						<td class="tocart">
							<?if($arResult['ADDED_PHID']==$arPrice['PHID']){?>
								<div class="tdcartadded" title="<?=Lng('Added_to_cart',1,0)?>"></div>
							<?}else{?>
								
								<span class="sprite-side rnd-link rnd-link-green detail-buy-btn-link">
								<input class="rnd-link-i" type="submit" href="javascript:void(0)" class="tdcartadd" OnClick="TDMAddToCart('<?=$arPrice['PHID']?>')" value="Купить" ></input>
								</span>

							<?}?>
						</td>
					</tr>
				<?}?>
				</table>
				<?
				if($arPart["PRICES_COUNT"]>$arResult['LIST_PRICES_LIMIT']){?>
					<div class="moreprops"><a href="javascript:void(0)" OnClick="ShowMoreListPrices('<?=$arPart['PKEY']?>')" class="sbut sb<?=$arPart['PKEY']?>"><?=Lng('Show_more_prices',1,0)?> (<?=($arPart["PRICES_COUNT"]-$arResult['LIST_PRICES_LIMIT'])?>)</a></div>
					<?
				}
			}elseif($arResult['ALLOW_ORDER']==1){?>
				<div class="moreprops"><a href="javascript:void(0)" class="tdorder" OnClick="TDMOrder('<?=$arPart['PKEY']?>')"><?=Lng('Order',1,0)?></a></div>
			<?}?>
			<?if(TDM_ISADMIN){?>
				<?if($arPart["PRICES_COUNT"]<=0){?><br><?}?>
				<a href="/<?=TDM_ROOT_DIR?>/admin/dbedit_price.php?ID=NEW&ARTICLE=<?=urlencode($arPart['ARTICLE'])?>&BRAND=<?=urlencode($arPart['BRAND'])?>" class="popup addprice" title="Add price record">+$</a>
				<a href="/<?=TDM_ROOT_DIR?>/admin/dbedit_link.php?ID=NEW&BKEY=<?=$arPart['BKEY']?>&AKEY=<?=$arPart['AKEY']?>" class="popup addprice" title="Add cross record">+X</a>
			<?}?>
		</td>
		</tr><?
	}?>
</table>