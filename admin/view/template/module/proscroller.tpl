<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">	
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="vtabs">
          <?php $module_row = 1; ?>
          <?php foreach ($modules as $module) { ?>
          <a href="#tab-module-<?php echo $module_row; ?>" modid="<?php echo $module_row; ?>" id="module-<?php echo $module_row; ?>"><?php echo $tab_module . ' ' . $module_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('.vtabs a:first').trigger('click'); $('#module-<?php echo $module_row; ?>').remove(); $('#tab-module-<?php echo $module_row; ?>').remove(); return false;" /></a>
          <?php $module_row++; ?>
          <?php } ?>
          <span id="module-add"><?php echo $button_add_module; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addModule();" /></span> </div>
        <?php $module_row = 1; ?>
        <?php foreach ($modules as $module) { ?>
        <div id="tab-module-<?php echo $module_row; ?>" class="vtabs-content">
          <div id="language-<?php echo $module_row; ?>" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>	  
		    <table class="form">
			<tr style="background:#aaa;">
				<td align="center" colspan="2" style="color:#fff;font-weight:bold;"><?php echo $entry_main_options;?>
				</td>
			</tr>
            <tr>
              <td><?php echo $entry_layout; ?></td>
              <td><select name="proscroller_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_position; ?></td>
              <td><select name="proscroller_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
				  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
				  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="proscroller_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="proscroller_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            </tr>
		    </table>
			<?php foreach ($languages as $language) { ?>
            <div id="tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>">
            <table class="form">
				<tr style="background:#aaa;">
					<td align="center" colspan="2" style="color:#fff;font-weight:bold;"><?php echo $entry_scroller_options;?>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_title; ?></td>
					<td><input name="proscroller_module[<?php echo $module_row; ?>][title][<?php echo $language['language_id']; ?>]" size="40" id="title-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" value="<?php echo isset($module['title'][$language['language_id']]) ? $module['title'][$language['language_id']] : ''; ?>"/></td>
              </tr>
            </table>
			</div>
			<?php } ?>
			<table class="form">
			<tr>
			  <td><?php echo $entry_source; ?></td>
			  <td class="left"><select name="proscroller_module[<?php echo $module_row; ?>][category_id]" id ="select<?php echo $module_row; ?>" onchange="showhide();">
			    <option value="featured" <?php if ($module['category_id']=='featured') { ?>selected="selected"<?php } ?>><?php echo $text_featured; ?></option>
				<option value="0" <?php if ($module['category_id']=='0') { ?>selected="selected"<?php } ?>><?php echo $text_root; ?></option>
                <?php foreach ($rootcats as $rootcat) { ?>
                <?php if ($rootcat['category_id'] == $module['category_id']) { ?>
                <option value="<?php echo $rootcat['category_id']; ?>" selected="selected"><?php echo $rootcat['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $rootcat['category_id']; ?>"><?php echo $rootcat['name']; ?></option>
                <?php } ?>
                <?php } ?>
             </select></td>
			</tr>
			</table>
			<?php if ($module['category_id']=='featured') { $featured_style="block";} else {$featured_style="none";}?>
			<table class="form" id="rowfeatured<?php echo $module_row; ?>" style="display:<?php echo $featured_style; ?>">
			  <tr>
				<td><?php echo $entry_product; ?></td>
				<td><input type="text" name="product" value="" /></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td><div id="featured-product<?php echo $module_row; ?>" class="scrollbox">
					<?php $class = 'odd'; ?>
					<?php foreach ($products as $product) { ?>
					<?php if ($product['module_id'] ==$module_row) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<div id="featured-product<?php echo $module_row; ?><?php echo $product['product_id']; ?>" class="<?php echo $class; ?>"><?php echo $product['name']; ?> <img src="view/image/delete.png" />
					  <input type="hidden" value="<?php echo $product['product_id']; ?>" />
					</div>
					<?php } ?>
					<?php } ?>
				  </div>
				  <input type="hidden" name="proscroller_module[<?php echo $module_row; ?>][featured]" value="<?php echo $module['featured']; ?>" /></td>
			  </tr>
			</table>
			<?php if ($module['category_id']=='featured') { $featured_style="none";} else {$featured_style="block";}?>
			<table class="form" id="catonly<?php echo $module_row; ?>" style="display:<?php echo $featured_style;?>">
			<tr>
			  <td><?php echo $entry_sort; ?></td>
			  <td class="left"><select name="proscroller_module[<?php echo $module_row; ?>][sort]">
                <?php if ($module['sort'] == 'p.date_added') { ?>
                <option value="p.date_added" selected="selected"><?php echo $text_date_added; ?></option>
                <?php } else { ?>
                <option value="p.date_added"><?php echo $text_date_added; ?></option>
                <?php } ?>
				<?php if ($module['sort'] == 'topsellers') { ?>
                <option value="topsellers" selected="selected"><?php echo $text_topsellers; ?></option>
                <?php } else { ?>
                <option value="topsellers"><?php echo $text_topsellers; ?></option>
                <?php } ?>
                <?php if ($module['sort'] == 'special') { ?>
                <option value="special" selected="selected"><?php echo $text_special; ?></option>
                <?php } else { ?>
                <option value="special"><?php echo $text_special; ?></option>
                <?php } ?>
				<?php if ($module['sort'] == 'rating') { ?>
                <option value="rating" selected="selected"><?php echo $text_rating; ?></option>
                <?php } else { ?>
                <option value="rating"><?php echo $text_rating; ?></option>
                <?php } ?>
				<?php if ($module['sort'] == 'p.viewed') { ?>
                <option value="p.viewed" selected="selected"><?php echo $text_viewed; ?></option>
                <?php } else { ?>
                <option value="p.viewed"><?php echo $text_viewed; ?></option>
                <?php } ?>
                <?php if ($module['sort'] == 'p.sort_order') { ?>
                <option value="p.sort_order" selected="selected"><?php echo $text_sort_order; ?></option>
                <?php } else { ?>
                <option value="p.sort_order"><?php echo $text_sort_order; ?></option>
                <?php } ?>
              </select></td>
			</tr>
		  	<tr>
			  <td><?php echo $entry_count; ?></td>
			  <td class="right"><input type="text" name="proscroller_module[<?php echo $module_row; ?>][count]" value="<?php echo $module['count']; ?>" size="3" /></td>
			</tr>
			</table>
			<table class="form">
			<tr>
			  <td><?php echo $entry_visible; ?></td>
			  <td class="right"><input type="text" name="proscroller_module[<?php echo $module_row; ?>][visible]" value="<?php echo $module['visible']; ?>" size="3" /></td>
			</tr>
			<tr>
			<tr>
			  <td><?php echo $entry_scroll; ?></td>
			  <td class="right"><input type="text" name="proscroller_module[<?php echo $module_row; ?>][scroll]" value="<?php echo $module['scroll']; ?>" size="3" /></td>
			</tr>
			<tr>
			  <td><?php echo $entry_autoscroll; ?></td>
			  <td class="right"><input type="text" name="proscroller_module[<?php echo $module_row; ?>][autoscroll]" value="<?php echo $module['autoscroll']; ?>" size="3" /></td>
			</tr>
			<tr>
			  <td><?php echo $entry_animationspeed; ?></td>
			  <td class="right"><input type="text" name="proscroller_module[<?php echo $module_row; ?>][animationspeed]" value="<?php echo $module['animationspeed']; ?>" size="5" /></td>
			</tr>
			<tr>
				<td><?php echo $entry_hoverpause?></td>
				<td><input id="yes_hoverpause" type="radio" name="proscroller_module[<?php echo $module_row; ?>][hoverpause]" value="1" <?php if(!isset($module['hoverpause']) || $module['hoverpause'] == '1') echo " checked='checked'"?>>
					<label for="yes_hoverpause"><?php echo $text_yes?></label>
					<input id="no_hoverpause" type="radio" name="proscroller_module[<?php echo $module_row; ?>][hoverpause]" value="0" <?php if(isset($module['hoverpause']) && $module['hoverpause'] == '0') echo " checked='checked'"?>>
					<label for="no_hoverpause"><?php echo $text_no?></label>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_disableauto?></td>
				<td><input id="yes_disableauto" type="radio" name="proscroller_module[<?php echo $module_row; ?>][disableauto]" value="1" <?php if(!isset($module['disableauto']) || $module['disableauto'] == '1') echo " checked='checked'"?>>
					<label for="yes_disableauto"><?php echo $text_yes?></label>
					<input id="no_disableauto" type="radio" name="proscroller_module[<?php echo $module_row; ?>][disableauto]" value="0" <?php if(isset($module['disableauto']) && $module['disableauto'] == '0') echo " checked='checked'"?>>
					<label for="no_disableauto"><?php echo $text_no?></label>
				</td>
			</tr>
			<tr style="background:#aaa;">
				<td align="center" colspan="2" style="color:#fff;font-weight:bold;"><?php echo $entry_product_options;?>
				</td>
			</tr>
			<tr>
			  <td><?php echo $entry_image; ?></td>
			  <td class="right"><input type="text" name="proscroller_module[<?php echo $module_row; ?>][image_width]" value="<?php echo $module['image_width']; ?>" size="3" /> x <input type="text" name="proscroller_module[<?php echo $module_row; ?>][image_height]" value="<?php echo $module['image_height']; ?>" size="3" /></td>
			</tr>
			<tr>
				<td><?php echo $entry_show_title?></td>
				<td><input id="yes_title" type="radio" name="proscroller_module[<?php echo $module_row; ?>][show_title]" value="1" <?php if(!isset($module['show_title']) || $module['show_title'] == '1') echo " checked='checked'"?>>
					<label for="yes_title"><?php echo $text_yes?></label>
					<input id="no_title" type="radio" name="proscroller_module[<?php echo $module_row; ?>][show_title]" value="0" <?php if(isset($module['show_title']) && $module['show_title'] == '0') echo " checked='checked'"?>>
					<label for="no_title"><?php echo $text_no?></label>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_show_price?></td>
				<td><input id="yes_price" type="radio" name="proscroller_module[<?php echo $module_row; ?>][show_price]" value="1" <?php if(!isset($module['show_price']) || $module['show_price'] == '1') echo " checked='checked'"?>>
					<label for="yes_price"><?php echo $text_yes?></label>
					<input id="no_price" type="radio" name="proscroller_module[<?php echo $module_row; ?>][show_price]" value="0" <?php if(isset($module['show_price']) && $module['show_price'] == '0') echo " checked='checked'"?>>
					<label for="no_price"><?php echo $text_no?></label>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_show_rate?></td>
				<td><input id="yes_rate" type="radio" name="proscroller_module[<?php echo $module_row; ?>][show_rate]" value="1" <?php if(!isset($module['show_rate']) || $module['show_rate'] == '1') echo " checked='checked'"?>>
					<label for="yes_rate"><?php echo $text_yes?></label>
					<input id="no_rate" type="radio" name="proscroller_module[<?php echo $module_row; ?>][show_rate]" value="0" <?php if(isset($module['show_rate']) && $module['show_rate'] == '0') echo " checked='checked'"?>>
					<label for="no_rate"><?php echo $text_no?></label>
				</td>
			</tr>
			<tr>
				<td><?php echo $entry_show_cart?></td>
				<td><input id="yes_show_cart" type="radio" name="proscroller_module[<?php echo $module_row; ?>][show_cart]" value="1" <?php if(!isset($module['show_cart']) || $module['show_cart'] == '1') echo " checked='checked'"?>>
					<label for="yes_show_cart"><?php echo $text_yes?></label>
					<input id="no_show_cart" type="radio" name="proscroller_module[<?php echo $module_row; ?>][show_cart]" value="0" <?php if(isset($module['show_cart']) && $module['show_cart'] == '0') echo " checked='checked'"?>>
					<label for="no_show_cart"><?php echo $text_no?></label>
				</td>
			</tr>
          </table>
        </div>
        <?php $module_row++; ?>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>

<?php $module_row++; ?>
<?php } ?>
//--></script> 

<script type="text/javascript">

function showhide() {
	var modid = $(".selected").attr('modid');
	//alert($("#select"+ modid).val());
    var selectedValue = $("#select"+ modid).val();
    //alert();
	if (selectedValue == "featured") {
	$("#rowfeatured"+ modid).show();
	$("#catonly"+ modid).hide();
	} else {
	$("#rowfeatured"+ modid).hide();
	$("#catonly"+ modid).show();
	}
	
   }
</script> 
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;
function addModule() {	

	html  = '<div id="tab-module-' + module_row + '" class="vtabs-content">';
	html += '  <div id="language-' + module_row + '" class="htabs">';
    <?php foreach ($languages as $language) { ?>
    html += '    <a href="#tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
    <?php } ?>
	html += '  </div>';


	html += '  <table class="form">';
	html += '  <tr style="background:#aaa;">';
	html += '  <td align="center" colspan="2" style="color:#fff;font-weight:bold;"><?php echo $entry_main_options;?></td>';
	html += '  </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_layout; ?></td>';
	html += '      <td><select name="proscroller_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '           <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_position; ?></td>';
	html += '      <td><select name="proscroller_module[' + module_row + '][position]">';
	html += '        <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '        <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '        <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '        <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_status; ?></td>';
	html += '      <td><select name="proscroller_module[' + module_row + '][status]">';
	html += '        <option value="1"><?php echo $text_enabled; ?></option>';
	html += '        <option value="0"><?php echo $text_disabled; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_sort_order; ?></td>';
	html += '      <td><input type="text" name="proscroller_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    </tr>';
	html += '  </table>'; 
				<?php foreach ($languages as $language) { ?>
	html += '    <div id="tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>">';
	html += '      <table class="form">';
	html += '      <tr style="background:#aaa;">';
	html += '      <td align="center" colspan="2" style="color:#fff;font-weight:bold;"><?php echo $entry_scroller_options;?></td>';
	html += '        </tr>';
	html += '        <tr>';
	html += '          <td><?php echo $entry_title; ?></td>';
	html += '          <td><input name="proscroller_module[' + module_row + '][title][<?php echo $language['language_id']; ?>]" size="40" id="title-' + module_row + '-<?php echo $language['language_id']; ?>"></input></td>';
	html += '        </tr>';
	html += '      </table>';
	html += '    </div>';
	<?php } ?>
	html += '  <table class="form">';
	html += '    <tr>';
	html += '      <td><?php echo $entry_source; ?></td>';
	html += '      <td><select name="proscroller_module[' + module_row + '][category_id]" id="select' + module_row + '" onchange="showhide();">';
	html += '           <option value="featured"><?php echo $text_featured; ?></option>';
	html += '           <option value="0" selected="selected"><?php echo $text_root; ?></option>';
	<?php foreach ($rootcats as $rootcat) { ?>
	html += '           <option value="<?php echo $rootcat['category_id']; ?>"><?php echo addslashes($rootcat['name']); ?></option>';
	<?php } ?>
	html += '      </select></td>';
	html += '  </table>'; 
	
	html += '  <table class="form" id="rowfeatured' + module_row + '" style="display:none">';
	html += '    <tr>';
	html += '      <td><?php echo $entry_product; ?></td>';
	html += '      <td><input type="text" name="product" value="" /></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '    <td>&nbsp;</td>';
	html += '    <td><div id="featured-product' + module_row + '" class="scrollbox">';
	html += '    </div>';
	html += '    <input type="hidden" name="proscroller_module[' + module_row + '][featured]" value="" /></td>';
	html += '    </tr>';
	html += '  </table>';
	
	html += ' 	<table class="form" id="catonly' + module_row + '">';
	html += '    <tr>';
	html += '      <td><?php echo $entry_sort; ?></td>';
	html += '      <td><select name="proscroller_module[' + module_row + '][sort]">';
	html += '        <option value="p.date_added"><?php echo $text_date_added; ?></option>';
	html += '        <option value="topsellers"><?php echo $text_topsellers; ?></option>';
	html += '        <option value="special"><?php echo $text_special; ?></option>';
	html += '        <option value="rating"><?php echo $text_rating; ?></option>';
	html += '        <option value="p.viewed"><?php echo $text_viewed; ?></option>';
	html += '        <option value="p.sort_order"><?php echo $text_sort_order; ?></option>';
	html += '      </select></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_count; ?></td>';
	html += '      <td><input name="proscroller_module[' + module_row + '][count]" size="3" value="10"></input>';
	html += '    </tr>';
	html += '  </table>';
	
	html += '  <table class="form">';
	html += '    <tr>';
	html += '      <td><?php echo $entry_visible; ?></td>';
	html += '      <td><input name="proscroller_module[' + module_row + '][visible]" size="3" value="4"></input>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_scroll; ?></td>';
	html += '      <td><input name="proscroller_module[' + module_row + '][scroll]" size="3" value="1"></input>';
	html += '    </tr>';

	html += '    <tr>';
	html += '      <td><?php echo $entry_autoscroll; ?></td>';
	html += '      <td><input name="proscroller_module[' + module_row + '][autoscroll]" size="3" ></input>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_animationspeed; ?></td>';
	html += '      <td><input name="proscroller_module[' + module_row + '][animationspeed]" size="5" value="1000"></input>';
	html += '    </tr>';
	html += '    <tr>';
	html += '    <td><?php echo $entry_hoverpause;?></td>';
	html += '    <td><input id="yes_hoverpause" type="radio" name="proscroller_module[' + module_row + '][hoverpause]" value="1" checked=checked"><label for="yes_hoverpause"><?php echo $text_yes;?></label><input id="no_hoverpause" type="radio" name="proscroller_module[' + module_row + '][hoverpause]" value="0"><label for="no_hoverpause"><?php echo $text_no;?></label></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '    <td><?php echo $entry_disableauto;?></td>';
	html += '    <td><input id="yes_disableauto" type="radio" name="proscroller_module[' + module_row + '][disableauto]" value="1" checked=checked"><label for="yes_disableauto"><?php echo $text_yes;?></label><input id="no_disableauto" type="radio" name="proscroller_module[' + module_row + '][disableauto]" value="0"><label for="no_disableauto"><?php echo $text_no;?></label></td>';
	html += '    </tr>';
	html += '    <tr style="background:#aaa;">';
	html += '    <td align="center" colspan="2" style="color:#fff;font-weight:bold;"><?php echo $entry_product_options;?></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_image; ?></td>';
	html += '      <td><input name="proscroller_module[' + module_row + '][image_width]" value="150" size="3"></input> x <input name="proscroller_module[' + module_row + '][image_height]" value="150" size="3"></input>';
	html += '    </tr>';
	html += '    <tr>';
	html += '    <td><?php echo $entry_show_title;?></td>';
	html += '    <td><input id="yes_title" type="radio" name="proscroller_module[' + module_row + '][show_title]" value="1" checked=checked"><label for="yes_title"><?php echo $text_yes;?></label><input id="no_title" type="radio" name="proscroller_module[' + module_row + '][show_title]" value="0"><label for="no_title"><?php echo $text_no;?></label></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '    <td><?php echo $entry_show_price;?></td>';
	html += '    <td><input id="yes_price" type="radio" name="proscroller_module[' + module_row + '][show_price]" value="1" checked=checked"><label for="yes_price"><?php echo $text_yes;?></label><input id="no_price" type="radio" name="proscroller_module[' + module_row + '][show_price]" value="0"><label for="no_price"><?php echo $text_no;?></label></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '    <td><?php echo $entry_show_rate;?></td>';
	html += '    <td><input id="yes_rate" type="radio" name="proscroller_module[' + module_row + '][show_rate]" value="1" checked=checked"><label for="yes_rate"><?php echo $text_yes;?></label><input id="no_rate" type="radio" name="proscroller_module[' + module_row + '][show_rate]" value="0"><label for="no_rate"><?php echo $text_no;?></label></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '    <td><?php echo $entry_show_cart;?></td>';
	html += '    <td><input id="yes_cart" type="radio" name="proscroller_module[' + module_row + '][show_cart]" value="1" checked=checked"><label for="yes_cart"><?php echo $text_yes;?></label><input id="no_cart" type="radio" name="proscroller_module[' + module_row + '][show_cart]" value="0"><label for="no_cart"><?php echo $text_no;?></label></td>';
	html += '    </tr>';
	html += '  </table>'; 
	
	html += '</div>';
	
	$('#form').append(html);
	
	//function showhide() {
	//var modid = $(".selected").attr('modid');
    //var selectBox = $("select"+ modid);
    //var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    //alert(modid);
	
	
   //}
	
	//autocmplete func
	$('input[name=\'product\']').autocomplete({
	
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 

	select: function(event, ui) {
	var modid = $(".selected").attr('modid');
		$('#featured-product'+ modid +'' + ui.item.value).remove();
		
		$('#featured-product'+ modid +'').append('<div id="featured-product'+ modid +'' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" value="' + ui.item.value + '" /></div>');

		$('#featured-product'+ modid +' div:odd').attr('class', 'odd');
		$('#featured-product'+ modid +' div:even').attr('class', 'even');
		
		data = $.map($('#featured-product'+ modid +' input'), function(element){
			return $(element).attr('value');
		});
		
		//alert(modid);
		
		$('input[name=\'proscroller_module[' + modid + '][featured]\']').attr('value', data.join());
					
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('#featured-product'+ module_row +' div img').live('click', function() {
var modid = $(".selected").attr('modid');
	$(this).parent().remove();
	
	$('#featured-product'+ modid +' div:odd').attr('class', 'odd');
	$('#featured-product'+ modid +' div:even').attr('class', 'even');

	data = $.map($('#featured-product'+ modid +' input'), function(element){
		return $(element).attr('value');
	});
					
	$('input[name=\'proscroller_module[' + modid + '][featured]\']').attr('value', data.join());
});
	//autocmplete func ends
	
	$('#language-' + module_row + ' a').tabs();
	
	$('#module-add').before('<a href="#tab-module-' + module_row + '" modid ="' + module_row + '" id="module-' + module_row + '"><?php echo $tab_module; ?> ' + module_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + module_row + '\').remove(); $(\'#tab-module-' + module_row + '\').remove(); return false;" /></a>');
	
	$('.vtabs a').tabs();
	
	$('#module-' + module_row).trigger('click');
	
	module_row++;
}
//--></script> 

<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 

	select: function(event, ui) {
	var modid = $(".selected").attr('modid');
		$('#featured-product'+ modid +'' + ui.item.value).remove();
		
		$('#featured-product'+ modid +'').append('<div id="featured-product'+ modid +'' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" value="' + ui.item.value + '" /></div>');

		$('#featured-product'+ modid +' div:odd').attr('class', 'odd');
		$('#featured-product'+ modid +' div:even').attr('class', 'even');
		
		data = $.map($('#featured-product'+ modid +' input'), function(element){
			return $(element).attr('value');
		});
		
		//alert(modid);
		
		$('input[name=\'proscroller_module[' + modid + '][featured]\']').attr('value', data.join());
					
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});


$('.scrollbox div img').live('click', function() {
var modid = $(".selected").attr('modid');
	$(this).parent().remove();
	
	$('#featured-product'+ modid +' div:odd').attr('class', 'odd');
	$('#featured-product'+ modid +' div:even').attr('class', 'even');

	data = $.map($('#featured-product'+ modid +' input'), function(element){
		return $(element).attr('value');
	});
					
	$('input[name=\'proscroller_module[' + modid + '][featured]\']').attr('value', data.join());
});
//--></script> 

<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
<script type="text/javascript"><!--
<?php $module_row = 1; ?>
<?php foreach ($modules as $module) { ?>
$('#language-<?php echo $module_row; ?> a').tabs();
<?php $module_row++; ?>
<?php } ?> 
//--></script> 
<?php echo $footer; ?>