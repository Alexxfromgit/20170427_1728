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
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div class="box">
		<div class="heading">
			<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
			<div class="buttons">
				<a href="<?php echo $clear_cache; ?>" class="button"><?php echo $button_clear_cache; ?></a>
				<a onclick="$('#form input[name=apply]').val(1); $('a.submit').trigger('click');" class="button"><?php echo $button_apply; ?></a>
				<a onclick="$('#form').submit();" class="button submit"><?php echo $button_save; ?></a>
				<a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
			</div>
		</div>
		<div class="content">
			<div id="tabs" class="htabs">
				<a href="#tab-general"><?php echo $tab_position; ?></a>
				<a href="#tab-html"><?php echo $tab_blocks; ?></a>
				<a href="#tab-themes"><?php echo $tab_themes; ?></a>
			</div>
			<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				<div id="tab-general">
					<table id="module" class="list">
						<thead>
							<tr>
								<td class="left"><span class="required" title="<?php echo $text_required; ?>">*</span> <?php echo $entry_html_block; ?></td>
								<td class="left"><?php echo $entry_layout; ?></td>
								<td class="left"><?php echo $entry_position; ?></td>
								<td class="left"><?php echo $entry_status; ?></td>
								<td class="right"><?php echo $entry_sort_order; ?></td>
								<td></td>
							</tr>
						</thead>
						<?php $module_row = 0; ?>
						<?php foreach ($modules as $module) { ?>
						<tbody id="module-row<?php echo $module_row; ?>">
							<tr>
								<td class="left html_block_id">
									<select name="html_block_module[<?php echo $module_row; ?>][html_block_id]">
										<option value=""><?php echo $text_select; ?></option>
										<?php $block_row = 0;
										foreach ($html_block_content as $content_id => $content) { ?>
										<option <?php if ($content_id == $module['html_block_id']) echo 'selected="selected"'; ?> value="<?php echo $content_id; ?>"><?php echo ($content['machine_name']) ? $content['machine_name'] : $content['default']; ?></option>
										<?php } ?>
									</select>
									<?php if (isset($error['content'][$module_row])) { ?>
									<div class="error"><?php echo $error['content'][$module_row]; ?></div>
									<?php } ?>
								</td>
								<td class="left">
									<select name="html_block_module[<?php echo $module_row; ?>][layout_id]">
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
									<select name="html_block_module[<?php echo $module_row; ?>][position]">
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
										<td class="left"><select name="html_block_module[<?php echo $module_row; ?>][status]">
										<?php if ($module['status']) { ?>
										<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
										<option value="0"><?php echo $text_disabled; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_enabled; ?></option>
										<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
										<?php } ?>
									</select>
								</td>
								<td class="right"><input type="text" name="html_block_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
								<td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
							</tr>
						</tbody>
						<?php $module_row++; ?>
						<?php } ?>
						<tfoot>
							<tr>
								<td colspan="5"></td>
								<td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div id="tab-html">
					<div id="vtabs" class="vtabs">
						<?php $content_row = 1; ?>
						<?php foreach ($html_block_content as $block_id => $content) { ?>
						<a href="#tab-content-<?php echo $content_row; ?>" id="content-<?php echo $content_row; ?>" rel="<?php echo $block_id; ?>"><b class="b-name"><?php echo ($content['short_title']) ? $content['short_title'] : $content['default']; ?> </b> <img src="view/image/delete.png" alt="" onclick="removeBlock(<?php echo $content_row; ?>);  return false;" /></a>
						<?php $content_row++; ?>
						<?php } ?>
						<span id="content-add"><?php echo $button_add_block; ?> <img src="view/image/add.png" alt="" onclick="addBlock();" /></span>
					</div>
					<?php $content_row = 1; $block_id = 0; ?>
					<?php foreach ($html_block_content as $block_id => $content) { ?>
					<div id="tab-content-<?php echo $content_row; ?>" class="vtabs-content" rel="<?php echo $block_id; ?>">
						<div id="tabs-block-<?php echo $block_id; ?>" class="htabs">
							<a href="#tabs-block-<?php echo $block_id; ?>-content"><?php echo $tab_data; ?></a>
							<a href="#tabs-block-<?php echo $block_id; ?>-config"><?php echo $tab_config; ?></a>
						</div>
						<div id="tabs-block-<?php echo $block_id; ?>-content">
							<div class="config">
								<table class="form config">
									<tbody>
										<tr>
											<td colspan="2"><span class="help id">ID:</span> <?php echo $block_id; ?></td>
										</tr>
										<tr>
											<td><label for="html-block-<?php echo $block_id; ?>-machine-name"><?php echo $entry_block_title; ?></label></td>
											<td><input class="machine-name" id="html-block-<?php echo $block_id; ?>-machine-name" type="text" name="html_block_<?php echo $block_id; ?>[machine_name]" value="<?php echo $content['machine_name']; ?>" maxlength="64" size="50" /><p class="help"><?php echo $text_help_machine_name; ?> <b><?php echo $text_block . ' ' . $block_id; ?></b></p>
											</td>
										</tr>
										<tr class="parent html-block-theme">
											<td><label for="html-block-<?php echo $block_id; ?>-theme"><?php echo $entry_use_theme; ?></label></td>
											<td><input id="html-block-<?php echo $block_id; ?>-theme" type="checkbox" class="slider" name="html_block_<?php echo $block_id; ?>[theme]" <?php echo isset($content['theme']) ? 'checked="checked"' : ''; ?>  />
											</td>
										</tr>
										<tr>
											<td colspan="2" class="include">
												<div class="slider-content <?php if(!isset($content['theme'])) echo ' hide'; ?>">
													<table class="form">
														<tbody>
															<tr class="html-block-theme-id">
																<td><label for="html-block-<?php echo $block_id; ?>-theme-id"><?php echo $entry_select_theme; ?></label></td>
																<td>
																	<select id="html-block-<?php echo $block_id; ?>-theme-id" name="html_block_<?php echo $block_id; ?>[theme_id]" >
																		<option value="0"><?php echo $text_none; ?></option>
																		<?php foreach ($html_block_theme as $theme_id => $theme_info) { ?>
																		<option <?php if ($content['theme_id'] == $theme_id) echo 'selected="selected"'; ?> value="<?php echo $theme_id; ?>"><?php echo ($theme_info['machine_name']) ? $theme_info['machine_name'] : $theme_info['default']; ?></option>
																		<?php } ?>
																		
																	</select>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
										<tr class="parent">
											<td><label for="html-block-<?php echo $block_id; ?>-style"><?php echo $entry_style; ?></label></td>
											<td><input id="html-block-<?php echo $block_id; ?>-style" type="checkbox" class="slider" name="html_block_<?php echo $block_id; ?>[style]" <?php echo isset($content['style']) ? 'checked="checked"' : ''; ?>  value="1" />
											</td>
										</tr>
										<tr>
											<td colspan="2" class="include">
												<div class="slider-content <?php if(!isset($content['style'])) echo ' hide'; ?>">
													<table class="form">
														<tbody>
															<tr>
																<td></td>
																<td><textarea rows="10" cols="100" name="html_block_<?php echo $block_id; ?>[css]"><?php echo isset($content['css']) ? $content['css'] : ''; ?></textarea></td>
															</tr>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div id="language-<?php echo $content_row; ?>" class="htabs">
								<?php foreach ($languages as $language) { ?>
								<a href="#tab-language-<?php echo $content_row; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
								<?php } ?>
							</div>
							<?php foreach ($languages as $language) { ?>
							<div id="tab-language-<?php echo $content_row; ?>-<?php echo $language['language_id']; ?>" class="html-block-content-content">
								<a class="button live-view"><?php echo $button_live_view; ?></a>
								<table class="form margin">
									<tbody>
										<tr>
											<td><label for="html-block-<?php echo $block_id; ?>-title-<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label></td>
											<td><input id="html-block-<?php echo $block_id; ?>-title-<?php echo $language['language_id']; ?>" type="text" name="html_block_<?php echo $block_id; ?>[title][<?php echo $language['language_id']; ?>]" value="<?php echo $content['title'][$language['language_id']]; ?>" /></td>
										</tr>
										<tr>
											<td class="message">
											<label for="content-<?php echo $content_row; ?>-<?php echo $language['language_id']; ?>"><?php echo $entry_content; ?></label><br /><a class="js show-hide-editor help"><?php echo ($content['editor'][$language['language_id']]) ? $text_disable_editor : $text_enabled_editor; ?></a></td>
										<td>
											<p class="link">
												<a class="js"><?php echo $text_tokens; ?></a>
											</p>
											<div class="content hide" style="display: none;">
												<table class="list">
													<thead>
														<tr>
															<td class="left"><?php echo $column_token; ?></td>
															<td class="left"><?php echo $column_value; ?></td>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td class="left" colspan="2"><span class="title"><?php echo $text_title_shop; ?></span></td>
														</tr>
														<tr>
															<td class="left">[config::name]</td>
															<td class="left"><?php echo $text_token_config_name; ?></td>
														</tr>
														<tr>
															<td class="left">[config::title]</td>
															<td class="left"><?php echo $text_token_config_title; ?></td>
														</tr>
														<tr>
															<td class="left">[config::owner]</td>
															<td class="left"><?php echo $text_token_config_owner; ?></td>
														</tr>
														<tr>
															<td class="left">[config::address]</td>
															<td class="left"><?php echo $text_token_config_address; ?></td>
														</tr>
														<tr>
															<td class="left">[config::email]</td>
															<td class="left"><?php echo $text_token_config_email; ?></td>
														</tr>
														<tr>
															<td class="left">[config::telephone]</td>
															<td class="left"><?php echo $text_token_config_telephone; ?></td>
														</tr>
														<tr>
															<td class="left">[config::fax]</td>
															<td class="left"><?php echo $text_token_config_fax; ?></td>
														</tr>
														<tr>
															<td class="left" colspan="2"><span class="title"><?php echo $text_title_customer; ?></span> <sup>[1]</sup></td>
														</tr>
														<tr>
															<td class="left">[customer::firstname]</td>
															<td class="left"><?php echo $text_token_customer_firstname; ?></td>
														</tr>
														<tr>
															<td class="left">[customer::lastname]</td>
															<td class="left"><?php echo $text_token_customer_lastname; ?></td>
														</tr>
														<tr>
															<td class="left">[customer::email]</td>
															<td class="left"><?php echo $text_token_customer_email; ?></td>
														</tr>
														<tr>
															<td class="left">[customer::telephone]</td>
															<td class="left"><?php echo $text_token_customer_telephone; ?></td>
														</tr>
														<tr>
															<td class="left">[customer::fax]</td>
															<td class="left"><?php echo $text_token_customer_fax; ?></td>
														</tr>
														<tr>
															<td class="left">[customer::reward]</td>
															<td class="left"><?php echo $text_token_customer_reward; ?></td>
														</tr>
														<tr>
														<td class="l	eft" colspan="2"><span class="title"><?php echo $text_title_over; ?></span></td>
														</tr>
														<tr>
															<td class="left">[currency::code]</td>
															<td class="left"><?php echo $text_token_currency_code; ?></td>
														</tr>
														<tr>
															<td class="left">[currency::title]</td>
															<td class="left"><?php echo $text_token_currency_title; ?></td>
														</tr>
														<tr>
															<td class="left">[language::code]</td>
															<td class="left"><?php echo $text_token_language_code; ?></td>
														</tr>
														<tr>
															<td class="left">[language::name]</td>
															<td class="left"><?php echo $text_token_language_name; ?></td>
														</tr>
														<tr>
															<td class="left">[block::ID]</td>
															<td class="left"><?php echo $text_token_block; ?></td>
														</tr>
													</tbody>
												</table>
												<p><sup>[1]</sup> <span class="help"><?php echo $text_help_tokens_customer; ?></span></p>
											</div>
											<textarea class="<?php echo ($content['editor'][$language['language_id']]) ? 'enabled' : 'disable'; ?>" rows="19" cols="130" name="html_block_<?php echo $block_id; ?>[content][<?php echo $language['language_id']; ?>]" id="content-<?php echo $content_row; ?>-<?php echo $language['language_id']; ?>"><?php echo (isset($content['content'][$language['language_id']])) ? $content['content'][$language['language_id']] : ''; ?></textarea><p class="help"><?php echo $text_php_help . '.<br />' . $text_php_help_editor; ?></p><input type="hidden" name="html_block_<?php echo $block_id; ?>[editor][<?php echo $language['language_id']; ?>]" value="<?php echo $content['editor'][$language['language_id']]; ?>" /></td>
									</tr>
									</tbody>
								</table>
							</div>
							<?php } ?>
						</div>
						<div id="tabs-block-<?php echo $block_id; ?>-config">
							<div class="config">
								<table class="form">
									<tbody>
										<tr>
											<td><label for="html-block-<?php echo $block_id; ?>-use-php"><?php echo $entry_php; ?></label></td>
											<td>
											<input id="html-block-<?php echo $block_id; ?>-use-php" type="checkbox" name="html_block_<?php echo $block_id; ?>[use_php]" <?php echo isset($content['use_php']) ? 'checked="checked"' : ''; ?>  />
											</td>
										</tr>
										<tr>
											<td><label for="html-block-<?php echo $block_id; ?>-use-cache"><?php echo $entry_cache; ?></label></td>
											<td>
											<input id="html-block-<?php echo $block_id; ?>-use-cache" type="checkbox" name="html_block_<?php echo $block_id; ?>[use_cache]" <?php echo isset($content['use_cache']) ? 'checked="checked"' : ''; ?>  />
											</td>
										</tr>
										<tr>
											<td><?php echo $entry_store; ?></td>
											<td>
												<div class="scrollbox">
													<?php $class = 'even'; ?>
													<?php foreach ($stores as $store) { ?>
													<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
													<div class="<?php echo $class; ?>">
													<input type="checkbox" name="html_block_<?php echo $block_id; ?>[store][]" value="<?php echo $store['store_id']; ?>" <?php  if (isset($content['store']) && in_array($store['store_id'], $content['store'])) echo 'checked="checked"'; ?> />
													<?php echo $store['name']; ?>
													
													</div>
													<?php } ?>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php $content_row++; ?>
					<?php } ?>
				</div>
				<div id="tab-themes">
					<div id="ttabs" class="vtabs ttabs">
						<?php $theme_row = 1; $theme_id = 0; ?>
						<?php foreach ($html_block_theme as $theme_id => $theme_info) { ?>
						<a href="#tab-theme-<?php echo $theme_row; ?>" id="theme-<?php echo $theme_row; ?>" rel="<?php echo $theme_id; ?>"><b class="b-name"><?php echo ($theme_info['short_title']) ? $theme_info['short_title'] : $theme_info['default']; ?> </b> <img src="view/image/delete.png" alt="" onclick="removeTheme(<?php echo $theme_row; ?>, <?php echo $theme_id; ?>);  return false;" /></a>
						<?php $theme_row++; ?>
						<?php } ?>
						<span id="theme-add"><?php echo $button_add_theme; ?> <img src="view/image/add.png" alt="" onclick="addTheme();" /></span>
					</div>
					<?php $theme_row = 1; $theme_id = 0; ?>
					<?php foreach ($html_block_theme as $theme_id => $theme_info) { ?>		
					<div id="tab-theme-<?php echo $theme_row; ?>" class="vtabs-content" rel="<?php echo $theme_id; ?>">
						<table class="form no-margin-bottom" cols="2">
							<tbody>
								<tr>
									<td><label for="theme-<?php echo $theme_id; ?>-machine-name"><?php echo $entry_theme_title; ?></label></td>
									<td>
										<input class="machine-name" id="theme-<?php echo $theme_id; ?>-machine-name" type="text" name="html_block_theme[<?php echo $theme_id; ?>][machine_name]" value="<?php echo $theme_info['machine_name']; ?>" maxlength="127" size="50" />
										<p class="help"><?php echo $text_help_machine_name; ?> <b><?php echo $text_theme; ?> <?php echo $theme_id; ?></b></p>
									</td>
								</tr>
								<tr>
									<td style="vertical-align: top;"><label for="html-block-theme-<?php echo $theme_id; ?>-template"><?php echo $entry_theme; ?></label></td>
									<td>
										<p class="link" style="margin-top: 0;"><a class="js"><?php echo $text_tokens; ?></a></p>
										<div class="content" style="display: none;">
											<table class="list">
												<thead>
													<tr>
														<td class="left"><?php echo $column_token; ?></td>
														<td class="left"><?php echo $column_value; ?></td>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td class="left">[title]</td>
														<td class="left"><?php echo $text_replace_title; ?></td>
													</tr>
													<tr>
														<td class="left">[content]</td>
														<td class="left"><?php echo $text_replace_content; ?></td>
													</tr>
												</tbody>
											</table>
										</div>
										<textarea id="html-block-theme-<?php echo $theme_id; ?>-template" rows="10" cols="100" name="html_block_theme[<?php echo $theme_id; ?>][template]"><?php echo $theme_info['template']; ?></textarea>
										<p class="help"><?php echo $text_php_help; ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php $theme_row++; ?>
					<?php } ?>
				</div>	
			<input type="hidden" name="apply" value="0" />
		</form>
	</div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--

function addCkeditor(el) {
	CKEDITOR.replace(el, {
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	}); 
}

<?php $content_row = 1; ?>
<?php foreach ($html_block_content as $content) { ?>
<?php foreach ($languages as $language) { ?>
	if ($('#content-<?php echo $content_row; ?>-<?php echo $language['language_id']; ?>').hasClass('enabled')) {
		addCkeditor('content-<?php echo $content_row; ?>-<?php echo $language['language_id']; ?>');
	}
<?php } ?>
<?php $content_row++; ?>
<?php } ?>

$('.content').delegate('.live-view', 'click', function(event){

	var parent_id = $(this).closest('.vtabs-content').attr('id');
	var languale_id = $(this).closest('.html-block-content-content').attr('id');
	
	var data = '#' + parent_id + ' .config input, #' + parent_id + ' .config textarea, #' + parent_id + ' .config select';
	data += ', #' + languale_id + ' input, #' + languale_id + ' textarea, #' + languale_id + ' select';
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><div class="throbber">&nbsp;</div><iframe name="preview-iframe" id="preview-iframe" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_preview; ?>',
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: true,
		close: function() {
			$('#dialog, #preview-form').remove();
		}
	});
	
	$('#preview-iframe').load(function(){
		$('#dialog .throbber').remove();
	});
	
	preview_form = document.createElement("form");
	preview_form.style.display = "none";
	preview_form.enctype = "application/x-www-form-urlencoded";
	preview_form.method = "POST";
	
	document.body.appendChild(preview_form);

	preview_form.action = "/index.php?route=module/html_block/preview&key=dmkapd8qweuqiweqjwkeh123123123";
	preview_form.target = 'preview-iframe';
	preview_form.setAttribute("target", 'preview-iframe');
	preview_form.id = 'preview-form';
	preview_form.setAttribute("id", 'preview-form');

	$(data).each(function(){
		if ($(this).is(':checkbox')) {
			if ($(this).is(':checked')) {
				var html = '<input type="hiden" name="' + $(this).attr('name') + '" value="' + $(this).val() + '" />';
			}
		} else if ($(this).is(':text')) {
			var html = '<input type="hiden" name="' + $(this).attr('name') + '" value="' + $(this).val() + '" />';
		} else {
			$id = $(this).attr('id');
			
			if (CKEDITOR.instances[$id]) {
				var value = CKEDITOR.instances[$id].getData();
			} else {
				var value = $(this).val();
			}
			
			var html = '<textarea name="' + $(this).attr('name') + '">' + value + '</textarea>';
		}
		
		$('#preview-form').append(html);
	});
	
	$(preview_form).submit();
});

$('#tab-html').delegate('.show-hide-editor', 'click', function(event){
	event.preventDefault();
	var context = $(this).parents('tr');
	var textarea = $('textarea', context);
	if (CKEDITOR.instances[$(textarea).attr('id')]) {
		CKEDITOR.instances[$(textarea).attr('id')].destroy(true);
		var help_text = '<?php echo $text_enabled_editor; ?>';
		var val = 0;
	} else {
		addCkeditor($(textarea).attr('id'));
		var help_text = '<?php echo $text_disable_editor; ?>';
		var val = 1;
	}
	$('input[type=hidden]', context).val(val);
	$(this).text(help_text);
});


//--></script> 
<script type="text/javascript"><!--

$('#form').delegate('p.link a.js', 'click', function(event){
	event.preventDefault();
	$(this).parent().next('.content').slideToggle('fast');
});

$('#tab-html .slider').live('change', function(event) {
	$(this).closest('tr').next('tr').find('.slider-content:first').slideToggle('fast');
});

$('#tab-html').delegate('.machine-name', 'keyup blur', function(event){
	var block_id = $(this).closest('.vtabs-content').attr('rel');
	var short_name = getMachineName($(this).val(), '<?php echo $text_theme; ?> ' + block_id);
	
	$('#vtabs a[rel=' + block_id + '] b').text(short_name);
	$('#module.list tbody td.html_block_id select option[value=' + block_id + ']').text($(this).val());
});

$('#tab-themes').delegate('.machine-name', 'keyup blur', function(event){
	var theme_id = $(this).closest('.vtabs-content').attr('rel');
	var short_name = getMachineName($(this).val(), '<?php echo $text_theme; ?> ' + theme_id);
	
	$('.ttabs a[rel=' + theme_id + '] b').text(short_name);
	$('#tab-html .vtabs-content tbody tr.html-block-theme-id select option[value=' + theme_id + ']').text($(this).val());
});


function getMachineName(name, def) {
	if (name.length > 0) {
		if (name.length > 30) {
			name = name.substr(0, 30) + '...';
		}
	}
	
	return name;
}

var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '	<td class="left html_block_id"><select name="html_block_module[' + module_row + '][html_block_id]">';
	html += '	<option value=""><?php echo $text_select; ?></option>';
	$('#vtabs a').each(function(i, el){
	html += '	<option value="' + $(el).attr('rel') + '">' + $(el).text() + '</option>';
	});
	html += '	</select></td>';
	html += '	<td class="left"><select name="html_block_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '	  <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '	</select></td>';
	html += '	<td class="left"><select name="html_block_module[' + module_row + '][position]">';
	html += '	  <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '	  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '	  <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '	  <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '	</select></td>';
	html += '	<td class="left"><select name="html_block_module[' + module_row + '][status]">';
	html += '	  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
	html += '	  <option value="0"><?php echo $text_disabled; ?></option>';
	html += '	</select></td>';
	html += '	<td class="right"><input type="text" name="html_block_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '	<td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}

var content_row = <?php echo $content_row; ?>;
var block_id = <?php echo $block_id; ?>;

function addBlock() {
	
	block_id++;
	
	var default_theme = '<div class="box">\n';
	default_theme +=	'	<div class="box-heading">[title]</div>\n';
	default_theme +=	'	<div class="box-content">\n';
	default_theme +=	'		[content]\n';
	default_theme +=	'	</div>\n';
	default_theme +=	'</div>\n';
	
	html  = '<div id="tab-content-' + content_row + '" class="vtabs-content" rel="' + block_id + '">';
	html += '	<div id="tabs-block-' + block_id + '" class="htabs">';
	html += '		<a href="#tabs-block-' + block_id + '-content"><?php echo $tab_data; ?></a>';
	html += '		<a href="#tabs-block-' + block_id + '-config"><?php echo $tab_config; ?></a>';
	html += '	</div>';
	html += '	<div id="tabs-block-' + block_id + '-content">';
	html += '		<div class="config">';
	html += '			<table class="form" cols="2">';
	html += '				<tbody>';
	html += '					<tr>';
	html += '						<td colspan="2"><span class="help id">ID:</span> ' + block_id + '</td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td><label for="html-block-' + block_id + '-machine-name"><?php echo $entry_block_title; ?></label></td>';
	html += '						<td><input class="machine-name" id="html-block-' + block_id + '-machine-name" type="text" name="html_block_' + block_id + '[machine_name]" value="" maxlength="127" size="50" /><p class="help"><?php echo $text_help_machine_name; ?> <b><?php echo $text_block; ?> ' + block_id + '</b></p></td>';
	html += '					</tr>';
	html += '					<tr class="parent html-block-theme">';
	html += '						<td><label for="html-block-' + block_id + '-theme"><?php echo $entry_use_theme; ?></label></td>';
	html += '						<td><input type="checkbox" class="slider" id="html-block-' + block_id + '-theme" name="html_block_' + block_id + '[theme]" /></td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td colspan="2" class="include">';
	html += '							<div class="slider-content  hide">';
	html += '								<table class="form">';
	html += '									<tbody>';
	html += '										<tr class="html-block-theme-id">';
	html += '											<td><label for="html-block-' + block_id + '-theme-id"><?php echo $entry_select_theme; ?></label></td>';
	html += '											<td>';
	html += '												<select id="html-block-' + block_id + '-theme-id" name="html_block_' + block_id + '[theme_id]" >';
	html += '													<option value="0"><?php echo $text_none; ?></option>';
	$('#ttabs a').each(function(i, el){
	html += '													<option value="' + $(el).attr('rel') + '">' + $(el).text() + '</option>';
	});																	
	html += '												</select>';
	html += '											</td>';
	html += '										</tr>';
	html += '									</tbody>';
	html += '								</table>';
	html += '							</div>';
	html += '						</td>';
	html += '					</tr>';
	html += '					<tr class="parent">';
	html += '						<td><label for="html-block-' + block_id + '-style"><?php echo $entry_style; ?></label></td>';
	html += '						<td><input id="html-block-' + block_id + '-style" type="checkbox" class="slider" name="html_block_' + block_id + '[style]" /></td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td colspan="2" class="include">';
	html += '							<div class="slider-content hide">';
	html += '								<table class="form">';
	html += '									<tbody>';
	html += '										<tr>';
	html += '											<td></td>';
	html += '											<td><textarea rows="10" cols="100" name="html_block_' + block_id + '[css]"></textarea></td>';
	html += '										</tr>';
	html += '									</tbody>';
	html += '								</table>';
	html += '							</div>';
	html += '						</td>';
	html += '					</tr>';
	html += '				</tbody>';
	html += '			</table>';
	html += '		</div>';
	html += '		<div id="language-' + content_row + '" class="htabs">';
	<?php foreach ($languages as $language) { ?>
	html += '			<a href="#tab-language-'+ content_row + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
	<?php } ?>
	html += '		</div>';
	<?php foreach ($languages as $language) { ?>
	html += '		<div id="tab-language-'+ content_row + '-<?php echo $language['language_id']; ?>" class="html-block-content-content">';
	html += '			<a class="button live-view"><?php echo $button_live_view; ?></a>';
	html += '			<table class="form">';
	html += '				<tbody>';
	html += '					<tr>';
	html += '						<td><label for="html-block-' + block_id + '-title-<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label></td>';
	html += '						<td><input type="text" id="html-block-' + block_id + '-title-<?php echo $language['language_id']; ?>" name="html_block_' + block_id + '[title][<?php echo $language['language_id']; ?>]" value="" /></td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td class="message"><label for="content-' + content_row + '-<?php echo $language['language_id']; ?>"><?php echo $entry_content; ?></label><br /><a class="js show-hide-editor help"><?php echo $text_disable_editor; ?></a></td>';
	html += '						<td>';
	html += '							<p class="link">';
	html += '								<a class="js"><?php echo $text_tokens; ?></a>';
	html += '							</p>';
	html += '							<div class="content hide" style="display: none;">';
	html += '								<table class="list">';
	html += '									<thead>';
	html += '		  		   						<tr>';
	html += '											<td class="left"><?php echo $column_token; ?></td>';
	html += '											<td class="left"><?php echo $column_value; ?></td>';
	html += '		  		   						</tr>';
	html += '									</thead>';
	html += '									<tbody>';
	html += '		  		   						<tr>';
	html += '											<td class="left" colspan="2"><span class="title"><?php echo $text_title_shop; ?></span></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[config::name]</td>';
	html += '											<td class="left"><?php echo $text_token_config_name; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[config::title]</td>';
	html += '											<td class="left"><?php echo $text_token_config_title; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[config::owner]</td>';
	html += '											<td class="left"><?php echo $text_token_config_owner; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[config::address]</td>';
	html += '											<td class="left"><?php echo $text_token_config_address; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[config::email]</td>';
	html += '											<td class="left"><?php echo $text_token_config_email; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[config::telephone]</td>';
	html += '											<td class="left"><?php echo $text_token_config_telephone; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[config::fax]</td>';
	html += '											<td class="left"><?php echo $text_token_config_fax; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left" colspan="2"><span class="title"><?php echo $text_title_customer; ?></span> <sup>[1]</sup></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[customer::firstname]</td>';
	html += '											<td class="left"><?php echo $text_token_customer_firstname; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[customer::lastname]</td>';
	html += '											<td class="left"><?php echo $text_token_customer_lastname; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[customer::email]</td>';
	html += '											<td class="left"><?php echo $text_token_customer_email; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[customer::telephone]</td>';
	html += '											<td class="left"><?php echo $text_token_customer_telephone; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[customer::fax]</td>';
	html += '											<td class="left"><?php echo $text_token_customer_fax; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[customer::reward]</td>';
	html += '											<td class="left"><?php echo $text_token_customer_reward; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left" colspan="2"><span class="title"><?php echo $text_title_over; ?></span></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[currency::code]</td>';
	html += '											<td class="left"><?php echo $text_token_currency_code; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[currency::title]</td>';
	html += '											<td class="left"><?php echo $text_token_currency_title; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[language::code]</td>';
	html += '											<td class="left"><?php echo $text_token_language_code; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[language::name]</td>';
	html += '											<td class="left"><?php echo $text_token_language_name; ?></td>';
	html += '		  		   						</tr>';
	html += '		  		   						<tr>';
	html += '											<td class="left">[block::ID]</td>';
	html += '											<td class="left"><?php echo $text_token_block; ?></td>';
	html += '		  		   						</tr>';
	html += '									</tbody>';
	html += '								</table>';
	html += '								<p><sup>[1]</sup> <span class="help"><?php echo $text_help_tokens_customer; ?></span></p>';
	html += '							</div>';
	html += '							<textarea class="enabled" rows="19" cols="130" name="html_block_' + block_id + '[content][<?php echo $language['language_id']; ?>]" id="content-' + content_row + '-<?php echo $language['language_id']; ?>"></textarea><p class="help"><?php echo $text_php_help . '.<br />' . $text_php_help_editor; ?></p><input type="hidden" name="html_block_' + block_id + '[editor][<?php echo $language['language_id']; ?>]" value="1" />';
	html += '						</td>';
	html += '					</tr>';
	html += '				</tbody>';
	html += '			</table>';
	html += '		</div>';
	<?php } ?>
	html += '	</div>';
	html += '	<div id="tabs-block-' + block_id + '-config">';
	html += '		<div class="config">';
	html += '			<table class="form">';
	html += '				<tbody>';
	html += '					<tr>';
	html += '						<td><label for="html-block-' + block_id + '-use-php"><?php echo $entry_php; ?></label></td>';
	html += '						<td><input id="html-block-' + block_id + '-use-php" type="checkbox" name="html_block_' + block_id + '[use_php]"   />';
	html += '						</td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td><label for="html-block-' + block_id + '-use-cache"><?php echo $entry_cache; ?></label></td>';
	html += '						<td><input id="html-block-' + block_id + '-use-cache" type="checkbox" name="html_block_' + block_id + '[use_cache]" />';
	html += '						</td>';
	html += '					</tr>';
	html += '					<tr>';
	html += '						<td><?php echo $entry_store; ?></td>';
	html += '						<td>';
	html += '							<div class="scrollbox">';
	<?php $class = 'even'; ?>
	<?php foreach ($stores as $store) { ?>
	<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '								<div class="<?php echo $class; ?>">';
	html += '								<input type="checkbox" name="html_block_' + block_id + '[store][]" value="<?php echo $store['store_id']; ?>" /> <?php echo $store['name']; ?>';
	
	html += '								</div>';
	<?php } ?>
	html += '							</div>';
	html += '						</td>';
	html += '					</tr>';
	html += '				</tbody>';
	html += '			</table>';
	html += '		</div>';
	html += '	</div>';
	html += '</div>';
	
	$('#tab-html').append(html);
	
	<?php foreach ($languages as $language) { ?>
		addCkeditor('content-' + content_row + '-<?php echo $language['language_id']; ?>');
	<?php } ?>
	
	$('#language-' + content_row + ' a').tabs();
	
	$('#content-add').before('<a href="#tab-content-' + content_row + '" id="content-' + content_row + '" rel="' + block_id + '"><b class="b-name"><?php echo $text_block; ?> ' + block_id + '</b>&nbsp;<img src="view/image/delete.png" alt="" onclick="removeBlock(' + content_row + ');  return false; " /></a>');
	
	$('.vtabs a').tabs();
	
	$('#tabs-block-' + block_id + ' a').tabs();
	
	$('#content-' + content_row).trigger('click');
	
	$('#module.list tbody td.html_block_id select').append('<option value="' + block_id + '"><?php echo $text_block; ?> ' + block_id + '</option>');
	
	content_row++;
	
}

function removeBlock(content_row) {
	
	var error = false;
	
	$('#module.list tbody td.html_block_id select').each(function(i, el){
		if ($(el).val() == $('#content-' + content_row).attr('rel')) {
			error = true;
			module = $(el).parents('tbody');
		}
	});
	
	if (error) {
	
		if (confirm('<?php echo $text_confirm_remove; ?>')) {
			$(module).remove();
		} else {
			return;
		}
	
	}
		
	$('#module.list tbody td.html_block_id select').each(function(i, el){
		$('option[value=' + $('#content-' + content_row).attr('rel') + ']', el).remove();
	});
	
	$('.vtabs a:first').trigger('click');
	$('#content-' + content_row).remove();
	$('#tab-content-' + content_row).remove();
	 
}

var theme_row = <?php echo $theme_row; ?>;
var theme_id = <?php echo $theme_id; ?>;

function addTheme() {
	
	theme_id++;
	
	var default_theme = '<div class="box">\n';
	default_theme +=	'	<div class="box-heading">[title]</div>\n';
	default_theme +=	'	<div class="box-content">\n';
	default_theme +=	'		[content]\n';
	default_theme +=	'	</div>\n';
	default_theme +=	'</div>\n';
	
	html  = '<div id="tab-theme-' + theme_row + '" class="vtabs-content" rel="' + theme_id + '">';
	html += '	<table class="form no-margin-bottom" cols="2">';
	html += '		<tbody>';
	html += '			<tr>';
	html += '				<td><label for="theme-' + theme_id + '-machine-name"><?php echo $entry_theme_title; ?></label></td>';
	html += '				<td><input class="machine-name" id="theme-' + theme_id + '-machine-name" type="text" name="html_block_theme[' + theme_id + '][machine_name]" value="" maxlength="127" size="50" /><p class="help"><?php echo $text_help_machine_name; ?> <b><?php echo $text_theme; ?> ' + theme_id + '</b></p></td>';
	html += '			</tr>';
	html += '			<tr>';
	html += '				<td></td>';
	html += '				<td>';
	html += '					<p class="link">';
	html += '						<a class="js"><?php echo $text_tokens; ?></a>';
	html += '					</p>';
	html += '					<div class="content" style="display: none;">';
	html += '						<table class="list">';
	html += '							<thead>';
	html += '								<tr>';
	html += '									<td class="left"><?php echo $column_token; ?></td>';
	html += '  									<td class="left"><?php echo $column_value; ?></td>';
	html += '								</tr>';
	html += '							</thead>';
	html += '							<tbody>';
	html += '								<tr>';
	html += '  									<td class="left">[title]</td>';
	html += '  									<td class="left"><?php echo $text_replace_title; ?></td>';
	html += '								</tr>';
	html += '								<tr>';
	html += '  									<td class="left">[content]</td>';
	html += '  									<td class="left"><?php echo $text_replace_content; ?></td>';
	html += '								</tr>';
	html += '							</tbody>';
	html += '						</table>';
	html += '					</div>';
	html += '					<textarea rows="10" cols="100" name="html_block_theme[' + theme_id + '][template]">' + default_theme + '</textarea><p class="help"><?php echo $text_php_help; ?></p>';
	html += '				</td>';
	html += '			</tr>';
	html += '		</tbody>';
	html += '	</tabel>';
	html += '</div>';
	
	$('#tab-themes').append(html);
	
	$('#theme-add').before('<a href="#tab-theme-' + theme_row + '" id="theme-' + theme_row + '" rel="' + theme_id + '"><b class="b-name"><?php echo $text_theme; ?> ' + theme_id + '</b>&nbsp;<img src="view/image/delete.png" alt="" onclick="removeTheme(' + theme_row + ');  return false; " /></a>');
	
	$('#ttabs a').tabs();
	
	$('#theme-' + theme_row).trigger('click');
	
	$('#tab-html tr .html-block-theme-id select').append('<option value="' + theme_id + '"><?php echo $text_theme; ?> ' + theme_id + '</option>');
	
	theme_row++;
	
}

function removeTheme(theme_row) {
	
	var error = false;
	var theme_id = $('#theme-' + theme_row).attr('rel');
	
	$('#tab-html tr .html-block-theme-id select').each(function(i, el){
		console.log($(this).closest('.form.config').closest('.form.config').find('.html-block-theme input'));
		if ($(el).val() == theme_id && $(this).closest('.form.config').find('.html-block-theme input').is(':checked')) {
			error = true;
		}
	});
	
	if (error && !confirm('<?php echo $text_confirm_remove_theme; ?>')) {
		return;
	}
		
	$('#tab-html tr .html-block-theme-id select').each(function(i, el){
		$('option[value=' + theme_id + ']', el).remove();
	});
	
	$('.ttabs a:first').trigger('click');
	$('#theme-' + theme_row).remove();
	$('#tab-theme-' + theme_row).remove();
	 
}

//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#vtabs a').tabs();
$('.ttabs a').tabs();
//--></script>
<script type="text/javascript"><!--
<?php $content_row = 1; ?>
<?php foreach ($html_block_content as $block_id => $content) { ?>
$('#language-<?php echo $content_row; ?> a').tabs();
$('#tabs-block-<?php echo $block_id; ?> a').tabs();
<?php $content_row++; ?>
<?php } ?> 
//--></script> 
<?php echo $footer; ?>