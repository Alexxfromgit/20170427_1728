<?php
// News Module for Opencart v1.5.5, modified by villagedefrance (contact@villagedefrance.net)
?>

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
		<div class="buttons">
			<a onclick="location = '<?php echo $news; ?>';" class="button"><span><?php echo $button_news; ?></span></a>
			<a onclick="buttonSave();" class="button"><?php echo $button_save; ?></a>
			<a onclick="buttonApply();" class="button"><?php echo $button_apply; ?></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
		</div>
    </div>
    <div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="newsform">
			<table class="form">
			<?php foreach ($languages as $language) { ?>
				<tr> 
					<td><?php echo $entry_customtitle; ?></td> 
					<td> 
					<input type="text" name="news_customtitle<?php echo $language['language_id']; ?>" id="news_customtitle<?php echo $language['language_id']; ?>" size="30" value="<?php echo ${'news_customtitle' . $language['language_id']}; ?>" />
					<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
					</td>
				</tr>
			<?php } ?> 
				<tr> 
					<td><?php echo $entry_header; ?></td> 
					<td>
						<?php if ($news_header) { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_header" value="1" checked="checked" />
							<?php echo $text_no; ?><input type="radio" name="news_header" value="0" />
						<?php } else { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_header" value="1" />
							<?php echo $text_no; ?><input type="radio" name="news_header" value="0" checked="checked" />
						<?php } ?>
					</td>
				</tr>
				<tr> 
					<td><?php echo $entry_icon; ?></td> 
					<td>
						<?php if ($news_icon) { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_icon" value="1" checked="checked" />
							<?php echo $text_no; ?><input type="radio" name="news_icon" value="0" />
						<?php } else { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_icon" value="1" />
							<?php echo $text_no; ?><input type="radio" name="news_icon" value="0" checked="checked" />
						<?php } ?>
					</td>
				</tr>
				<tr> 
					<td><?php echo $entry_box; ?></td> 
					<td>
						<?php if ($news_box) { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_box" value="1" checked="checked" />
							<?php echo $text_no; ?><input type="radio" name="news_box" value="0" />
						<?php } else { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_box" value="1" />
							<?php echo $text_no; ?><input type="radio" name="news_box" value="0" checked="checked" />
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_template; ?></td>
					<td>
						<?php foreach ($templates as $template) { ?>
							<?php if ($template == $config_template) { ?>
								<span style='color: #225599; padding: 0 5px;'><b><?php echo $template; ?></b></span> 
							<?php } ?>
						<?php } ?>	
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_headline_module; ?></td>
					<td>
						<?php if ($news_headline_module) { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_headline_module" value="1" checked="checked" />
							<?php echo $text_no; ?><input type="radio" name="news_headline_module" value="0" />
						<?php } else { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_headline_module" value="1" />
							<?php echo $text_no; ?><input type="radio" name="news_headline_module" value="0" checked="checked" />
						<?php } ?>
					</td>
				</tr>
				<tr style="background:#FCFCFC;">
					<td><span class="required">*</span> <?php echo $entry_newspage_thumb; ?></td>
					<td>
						<input type="text" name="news_thumb_width" value="<?php echo $news_thumb_width; ?>" size="3" /> x 
						<input type="text" name="news_thumb_height" value="<?php echo $news_thumb_height; ?>" size="3" /> px
						<?php if ($error_newspage_thumb) { ?>
							<span class="error"><?php echo $error_newspage_thumb; ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr style="background:#FCFCFC;">
					<td><span class="required">*</span> <?php echo $entry_newspage_popup; ?></td>
					<td>
						<input type="text" name="news_popup_width" value="<?php echo $news_popup_width; ?>" size="3" /> x 
						<input type="text" name="news_popup_height" value="<?php echo $news_popup_height; ?>" size="3" /> px
						<?php if ($error_newspage_popup) { ?>
							<span class="error"><?php echo $error_newspage_popup; ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr style="background:#FCFCFC;">
					<td><?php echo $entry_newspage_addthis; ?></td>
					<td>
						<?php if ($news_newspage_addthis) { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_newspage_addthis" value="1" checked="checked" />
							<?php echo $text_no; ?><input type="radio" name="news_newspage_addthis" value="0" />
						<?php } else { ?>
							<?php echo $text_yes; ?><input type="radio" name="news_newspage_addthis" value="1" />
							<?php echo $text_no; ?><input type="radio" name="news_newspage_addthis" value="0" checked="checked" />
						<?php } ?>
					</td>
				</tr>
				<tr style="background:#F8F8F8;">
					<td><span class="required">*</span> <?php echo $entry_headline_chars; ?></td>
					<td><input type="text" name="news_headline_chars" value="<?php echo $news_headline_chars; ?>" size="3" /> <?php echo $text_chars; ?>
						<?php if (isset($error_numchars)) { ?>
							<span class="error"><?php echo $error_numchars; ?></span>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><a onclick="window.open('http://www.villagedefrance.net');" title="villagedefrance"><img src="view/image/villagedefrance.png" alt="" /></a></td>
					<td colspan="4"><b><?php echo $text_module_settings; ?></b></td>
				</tr>
			</table>
			
			<table id="module" class="list">
			<thead>
				<tr>
					<td class="left"><?php echo $entry_limit; ?></td>
					<td class="left"><?php echo $entry_headline; ?></td>
					<td class="left"><?php echo $entry_numchars; ?></td>
					<td class="left"><?php echo $entry_layout; ?></td>
					<td class="left"><?php echo $entry_position; ?></td>
					<td class="left"><?php echo $entry_status; ?></td>	
					<td class="left"><?php echo $entry_sort_order; ?></td>
					<td></td>
				</tr>
			</thead>
            <?php $module_row = 0; ?>
            <?php foreach ($modules as $module) { ?>
            <tbody id="module-row<?php echo $module_row; ?>">
				<tr>
                <td class="left"><input type="text" name="news_module[<?php echo $module_row; ?>][limit]" value="<?php echo $module['limit']; ?>" size="1" /></td>
				<td class="left">
					<select name="news_module[<?php echo $module_row; ?>][headline]">
                    <?php if ($module['headline']) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
					</select>
				</td>
                <td class="left">
					<input type="text" name="news_module[<?php echo $module_row; ?>][numchars]" value="<?php echo $module['numchars']; ?>" size="3" /> <?php echo $text_chars; ?>
				</td>
                <td class="left">
					<select name="news_module[<?php echo $module_row; ?>][layout_id]">
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
						<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
						<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
					</select>
				</td>
                <td class="left">
					<select name="news_module[<?php echo $module_row; ?>][position]">
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
					</select>
				</td>
                <td class="left">
					<select name="news_module[<?php echo $module_row; ?>][status]">
                    <?php if ($module['status']) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
					</select>
				</td>
                <td class="center"><input type="text" name="news_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
                <td class="center"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
				</tr>
            </tbody>
            <?php $module_row++; ?>
            <?php } ?>
            <tfoot>
				<tr>
                <td colspan="7"></td>
                <td class="center"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
				</tr>
            </tfoot>
        </table>
		<input type="hidden" name="buttonForm" value="">
      </form>
    </div>
	</div>
	<br>
		<div style="text-align:center; color:#555;">News v<?php echo $news_version; ?></div>
</div>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="news_module[' + module_row + '][limit]" value="5" size="1" /></td>';
	html += '    <td class="left"><select name="news_module[' + module_row + '][headline]">';
    html += '      	<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      	<option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="left"><input type="text" name="news_module[' + module_row + '][numchars]" value="" size="3" /> <?php echo $text_chars; ?></td>';
	html += '    <td class="left"><select name="news_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      	<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="news_module[' + module_row + '][position]">';
	html += '      	<option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      	<option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      	<option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      	<option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="news_module[' + module_row + '][status]">';
    html += '      	<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      	<option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="center"><input type="text" name="news_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="center"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>

<script type="text/javascript">
function buttonSave() {document.newsform.buttonForm.value='save';$('#form').submit();}
function buttonApply() {document.newsform.buttonForm.value='apply';$('#form').submit();}
</script>

<?php echo $footer; ?>