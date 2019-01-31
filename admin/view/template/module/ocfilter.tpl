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
        <?php if ($installed) { ?>
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a onclick="$('#form').attr('action','<?php echo $apply; ?>').submit();" class="button"><?php echo $button_apply; ?></a>
				<?php } ?>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <div id="tabs" class="ocfilter-htabs">
        <?php if (!$installed) { ?>
				<a href="#tab-install"><?php echo $tab_install; ?></a>
        <?php } else { ?>
				<a href="#tab-general"><?php echo $tab_general; ?></a>
        <a href="#tab-option"><?php echo $tab_option; ?></a>
        <a href="#tab-price-filtering"><?php echo $tab_price_filtering; ?></a>
        <a href="#tab-other"><?php echo $tab_other; ?></a>
        <a href="#tab-modules"><?php echo $tab_modules; ?></a>
        <a href="#tab-copy"><?php echo $tab_copy; ?></a>
				<?php } ?>
      </div>
      <?php if (!$installed) { ?>
      <form action="<?php echo $install; ?>" method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" name="package[]" value="base" />
				<div id="tab-install" class="ocfilter-htab">
          <h2>Выберите те функции модуля, которые Вы будете использовать:</h2>
          <table class="form ocfilter-settings">
            <tr>
							<td>Фильтр товаров</td>
              <td><label class="checked"><input type="checkbox" checked="checked" disabled="disabled" /></label></td>
						</tr>
            <tr class="package-compare">
							<td>Сравнение товаров по опциям фильтра</td>
              <td><label><input name="package[]" type="checkbox" value="compare" /></label></td>
						</tr>
            <tr class="package-product_list_options">
							<td>Отображение опций фильтра в списке товаров<span class="help">(под описанием)</span></td>
              <td><label><input name="package[]" type="checkbox" value="product_list_options" /></label></td>
						</tr>
            <tr class="package-product_info_options">
							<td>Отображение опций фильтра в карточке товара<span class="help">(вместо атрибутов)</span></td>
              <td><label><input name="package[]" type="checkbox" value="product_info_options" /></label></td>
						</tr>
          </table>
          <h2>Проверьте файлы модуля:</h2>
          <table class="form ocfilter-settings">
						<?php foreach ($files as $file) { ?>
						<tr class="install-package package-<?php echo $file['package']; ?>">
              <td><?php echo $file['text']; ?></td>
              <td><input type="text" size="100%" value="<?php echo $file['path']; ?>" /></td>
						</tr>
						<?php } ?>
            <tr>
							<?php if ($validate_install) { ?>
              <td><a onclick="$('#form').submit();" class="button">Установить модуль</a></td><td></td>
							<?php } else { ?>
							<td><a onclick="$('#form').submit();" class="button">Обновить</a></td><td></td>
              <?php } ?>
						</tr>
					</table>
				</div>
			</form>
      <?php } else { ?>
			<form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general" class="ocfilter-htab">
          <table class="form ocfilter-settings">
						<tr class="notice"><td></td><td><?php echo $notice_status; ?></td></tr>
            <tr>
							<td><?php echo $entry_status; ?></td>
							<td><label<?php echo ($status ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][status]" value="1" <?php echo ($status ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_position; ?></td></tr>
            <tr>
              <td><?php echo $entry_position; ?></td>
              <td>
                <div class="position">
                  <?php foreach ($positions as $item) { ?>
                  <a class="<?php echo $item . ($position == $item ? ' selected' : ''); ?>" title="<?php echo ${'text_' . $item}; ?>"><?php echo ${'text_' . $item}; ?></a>
                  <?php } ?>
                  <input type="hidden" name="ocfilter_module[0][position]" value="<?php echo $position; ?>" />
                </div>
              </td>
            </tr>
            <tr class="notice"><td></td><td><?php echo $notice_sort_order; ?></td></tr>
            <tr>
							<td><?php echo $entry_sort_order; ?></td>
							<td><input type="text" name="ocfilter_module[0][sort_order]" value="<?php echo $sort_order; ?>" size="2" /></td>
      			</tr>
          </table>
        </div>
        <div id="tab-option" class="ocfilter-htab">
          <table class="form ocfilter-settings">
            <tr class="notice"><td></td><td><?php echo $notice_show_selected; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_selected; ?></td>
							<td><label<?php echo ($show_selected ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][show_selected]" value="1" <?php echo ($show_selected ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_button; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_button; ?></td>
							<td><label<?php echo ($show_button ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][show_button]" value="1" <?php echo ($show_button ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_price; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_price; ?></td>
							<td><label<?php echo ($show_price ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][show_price]" value="1" <?php echo ($show_price ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_counter; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_counter; ?></td>
							<td><label<?php echo ($show_counter ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][show_counter]" value="1" <?php echo ($show_counter ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_manufacturer; ?></td></tr>
            <tr>
              <td><?php echo $entry_manufacturer; ?></td>
              <td>
                <label<?php echo ($manufacturer ? ' class="checked"' : ''); ?>><input type="checkbox" class="with-subfield" name="ocfilter_module[0][manufacturer]" value="1" <?php echo ($manufacturer ? 'checked="checked" ' : ''); ?>data-subfield="manufacturer" /></label>
                <div class="subfield sf-manufacturer<?php echo ($manufacturer ? ' visible' : ''); ?>">
                  <label>
	                  <span><?php echo $entry_type; ?></span>
	                  <select name="ocfilter_module[0][manufacturer_type]">
	                    <?php foreach ($types as $item) { ?>
	                    <option value="<?php echo $item; ?>" <?php echo ($manufacturer_type == $item ? 'selected="selected" ' : ''); ?>><?php echo ucfirst($item); ?></option>
	                    <?php } ?>
	                  </select>
									</label>
                </div>
              </td>
            </tr>
            <tr class="notice"><td></td><td><?php echo $notice_stock_status; ?></td></tr>
            <tr>
              <td><?php echo $entry_stock_status; ?></td>
              <td>
                <label<?php echo ($stock_status ? ' class="checked"' : ''); ?>><input type="checkbox" class="with-subfield" name="ocfilter_module[0][stock_status]" value="1" <?php echo ($stock_status ? 'checked="checked" ' : ''); ?>data-subfield="stock-status" /></label>
								<div class="subfield sf-stock-status<?php echo ($stock_status ? ' visible' : ''); ?>">
									<label>
	                  <span><?php echo $entry_stock_status_method; ?></span>
		                <select name="ocfilter_module[0][stock_status_method]" class="with-subfield" data-subfield="stock-status-method">
		                  <?php if ($stock_status_method == 'quantity') { ?>
		                  <option value="quantity" selected="selected"><?php echo $text_stock_by_quantity; ?></option>
		                  <option value="stock_status_id"><?php echo $text_stock_by_status_id; ?></option>
		                  <?php } else { ?>
		                  <option value="quantity"><?php echo $text_stock_by_quantity; ?></option>
		                  <option value="stock_status_id" selected="selected"><?php echo $text_stock_by_status_id; ?></option>
		                  <?php } ?>
		                </select>
									</label>
									<div class="subfield stock-status-method sf-stock-status-id<?php echo ($stock_status_method == 'stock_status_id' ? ' visible' : ''); ?>">
										<label>
											<span><?php echo $entry_type; ?></span>
                      <select name="ocfilter_module[0][stock_status_type]">
		                    <?php foreach ($types as $item) { ?>
		                    <option value="<?php echo $item; ?>" <?php echo ($stock_status_type == $item ? 'selected="selected" ' : ''); ?>><?php echo ucfirst($item); ?></option>
		                    <?php } ?>
		                  </select>
										</label>
									</div>
									<div class="subfield stock-status-method sf-quantity<?php echo ($stock_status_method == 'quantity' ? ' visible' : ''); ?>">
										<label>
											<span><?php echo $entry_stock_out_value; ?></span>
											<input type="checkbox" name="ocfilter_module[0][stock_out_value]" value="1"<?php echo ($stock_out_value ? ' checked="checked" ' : ''); ?> />
										</label>
									</div>
								</div>
              </td>
            </tr>
          </table>
        </div>
        <div id="tab-price-filtering" class="ocfilter-htab">
          <table class="form ocfilter-settings">
            <tr class="notice"><td></td><td><?php echo $notice_price_type; ?></td></tr>
            <tr>
              <td><?php echo $entry_price_type; ?></td>
              <td>
                <select name="ocfilter_module[0][price_type]" class="with-subfield" data-subfield="price-type">
                  <?php foreach ($price_types as $key => $item) { ?>
                  <option value="<?php echo $key; ?>" <?php echo ($price_type == $key ? 'selected="selected" ' : ''); ?>><?php echo $item; ?></option>
                  <?php } ?>
                </select>
                <div class="subfield sf-links price-type<?php echo ($price_type == 'links' ? ' visible' : ''); ?>">
                  <label>
										<span><?php echo $entry_price_links_count; ?></span>
                    <input type="text" name="ocfilter_module[0][price_links_count]" value="<?php echo $price_links_count; ?>" size="2" />
									</label>
								</div>
              </td>
            </tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_diagram; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_diagram; ?></td>
							<td><label<?php echo ($show_diagram ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][show_diagram]" value="1" <?php echo ($show_diagram ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_manual_price; ?></td></tr>
            <tr>
							<td><?php echo $entry_manual_price; ?></td>
							<td><label<?php echo ($manual_price ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][manual_price]" value="1" <?php echo ($manual_price ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_consider_discount; ?></td></tr>
            <tr>
							<td><?php echo $entry_consider_discount; ?></td>
							<td><label<?php echo ($consider_discount ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][consider_discount]" value="1" <?php echo ($consider_discount ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_consider_special; ?></td></tr>
            <tr>
							<td><?php echo $entry_consider_special; ?></td>
							<td><label<?php echo ($consider_special ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][consider_special]" value="1" <?php echo ($consider_special ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
          </table>
        </div>
        <div id="tab-other" class="ocfilter-htab">
          <table class="form ocfilter-settings">
            <tr class="notice"><td></td><td><?php echo $notice_pco_show_type; ?></td></tr>
						<tr>
              <td><?php echo $entry_pco_show_type; ?></td>
              <td>
                <select name="ocfilter_module[0][pco_show_type]">
                  <?php if ($pco_show_type == 'inline') { ?>
                  <option value="inline" selected="selected"><?php echo $text_inline; ?></option>
                  <option value="list"><?php echo $text_list; ?></option>
                  <?php } else { ?>
                  <option value="inline"><?php echo $text_inline; ?></option>
                  <option value="list" selected="selected"><?php echo $text_list; ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr class="notice"><td></td><td><?php echo $notice_pco_show_limit; ?></td></tr>
            <tr>
							<td><?php echo $entry_pco_show_limit; ?></td>
							<td><input type="text" name="ocfilter_module[0][pco_show_limit]" value="<?php echo $pco_show_limit; ?>" size="4" /></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_options_limit; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_first_limit; ?></td>
							<td><input type="text" name="ocfilter_module[0][show_options_limit]" value="<?php echo $show_options_limit; ?>" size="4" />&nbsp;<?php echo $text_options; ?></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_show_values_limit; ?></td></tr>
            <tr>
							<td><?php echo $entry_show_first_limit; ?></td>
							<td><input type="text" name="ocfilter_module[0][show_values_limit]" value="<?php echo $show_values_limit; ?>" size="4" />&nbsp;<?php echo $text_values; ?></td>
						</tr>
            <tr class="notice"><td></td><td><?php echo $notice_hide_empty_values; ?></td></tr>
            <tr>
							<td><?php echo $entry_hide_empty_values; ?></td>
							<td><label<?php echo ($hide_empty_values ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][hide_empty_values]" value="1" <?php echo ($hide_empty_values ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr class="notice"><td></td><td><?php echo $notice_use_animation; ?></td></tr>
            <tr>
							<td><?php echo $entry_use_animation; ?></td>
							<td><label<?php echo ($use_animation ? ' class="checked"' : ''); ?>><input type="checkbox" name="ocfilter_module[0][use_animation]" value="1" <?php echo ($use_animation ? 'checked="checked" ' : ''); ?>/></label></td>
      			</tr>
            <tr>
							<td colspan="2"><a href="<?php echo $reinstall; ?>" class="button">Переустановить модуль</a></td>
      			</tr>
          </table>
        </div>
        <div id="tab-modules" class="ocfilter-htab">
          <table class="list ocfilter-settings">
      			<thead>
							<tr>
								<td width="1%" align="left"><?php echo $column_status; ?></td>
								<td><?php echo $column_heading_title; ?></td>
								<td><?php echo $column_layout; ?></td>
								<td><?php echo $column_position; ?></td>
								<td><?php echo $column_category; ?></td>
								<td><?php echo $column_options; ?></td>
								<td><?php echo $column_sort_order; ?></td>
                <td><a class="button" id="add-module"><?php echo $button_add_module; ?></a></td>
							</tr>
						</thead>
						<tbody id="modules">
       				<?php foreach ($modules as $key => $module) { ?>
							<?php $key++; ?>
							<tr>
								<td width="1%" align="left">
									<?php if ($module['status']) { ?>
									<input type="checkbox" name="ocfilter_module[<?php echo $key; ?>][status]" value="1" checked="checked" />
                  <?php } else { ?>
									<input type="checkbox" name="ocfilter_module[<?php echo $key; ?>][status]" value="1" />
                  <?php } ?>
								</td>
              	<td class="left"><input type="text" name="ocfilter_module[<?php echo $key; ?>][heading_title]" value="<?php echo $module['heading_title']; ?>" size="20" /></td>
								<td>
									<select name="ocfilter_module[<?php echo $key; ?>][layout_id]">
	                  <?php foreach ($layouts as $layout) { ?>
	                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
	                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
	                  <?php } else { ?>
	                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
	                  <?php } ?>
	                  <?php } ?>
									</select>
								</td>
								<td>
	                <div class="position">
	                  <?php foreach ($positions as $item) { ?>
	                  <a class="<?php echo $item . ($module['position'] == $item ? ' selected' : ''); ?>" title="<?php echo ${'text_' . $item}; ?>"><?php echo ${'text_' . $item}; ?></a>
	                  <?php } ?>
	                  <input type="hidden" name="ocfilter_module[<?php echo $key; ?>][position]" value="<?php echo $module['position']; ?>" />
	                </div>
								</td>
								<td>
									<select name="ocfilter_module[<?php echo $key; ?>][category_id]" class="ocfilter-categories" data-module-id="<?php echo $key; ?>">
	                  <?php foreach ($categories as $category) { ?>
	                  <?php if ($category['category_id'] == $module['category_id']) { ?>
	                  <option value="<?php echo $category['category_id']; ?>" class="level-<?php echo $category['level']; ?>" selected="selected"><?php echo $category['name']; ?></option>
	                  <?php } else { ?>
	                  <option value="<?php echo $category['category_id']; ?>" class="level-<?php echo $category['level']; ?>"><?php echo $category['name']; ?></option>
	                  <?php } ?>
	                  <?php } ?>
									</select>
								</td>
								<td>
                  <div class="switcher">
										<div class="selected"><?php echo $text_selected; ?> <strong><?php echo count($module['options_id']); ?></strong> <?php echo $text_options; ?></div>
										<div class="values">
                    	<div class="base"><label><input type="checkbox" name="ocfilter_module[<?php echo $key; ?>][show_price]" value="1"<?php echo ($module['show_price'] ? ' checked="checked"' : ''); ?> /><?php echo mb_substr($entry_show_price, 0, -1, 'UTF-8'); ?></label></div>
		                  <div class="base"><label><input type="checkbox" name="ocfilter_module[<?php echo $key; ?>][stock_status]" value="1"<?php echo ($module['stock_status'] ? ' checked="checked"' : ''); ?> /><?php echo mb_substr($entry_stock_status, 0, -1, 'UTF-8'); ?></label></div>
		                  <div class="base"><label><input type="checkbox" name="ocfilter_module[<?php echo $key; ?>][manufacturer]" value="1"<?php echo ($module['manufacturer'] ? ' checked="checked"' : ''); ?> /><?php echo mb_substr($entry_manufacturer, 0, -1, 'UTF-8'); ?></label></div>
		                  <?php foreach ($module['options'] as $_key => $option) { ?>
		                  <?php if (in_array($option['option_id'], $module['options_id'])) { ?>
		                  <div><label><input type="checkbox" name="ocfilter_module[<?php echo $key; ?>][options_id][]" value="<?php echo $option['option_id']; ?>" checked="checked" /><?php echo $option['name']; ?></label></div>
		                  <?php } else { ?>
		                  <div<?php echo ((!$option['status'] || $option['type'] == 'text') ? ' class="disabled"' : ''); ?>><label><input type="checkbox" name="ocfilter_module[<?php echo $key; ?>][options_id][]" value="<?php echo $option['option_id']; ?>"<?php echo ((!$option['status'] || $option['type'] == 'text') ? ' disabled="disabled"' : ''); ?> /><?php echo $option['name']; ?></label></div>
		                  <?php } ?>
		                  <?php } ?>
										</div>
									</div>
								</td>
              	<td class="right"><input type="text" name="ocfilter_module[<?php echo $key; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
								<td><a class="button remove"><?php echo $button_remove; ?></a></td>
							</tr>
							<tr class="advanced-features">
								<td colspan="8">
                  <label><b><?php echo $entry_show_diagram; ?></b><input type="checkbox" name="ocfilter_module[<?php echo $key; ?>][show_diagram]" value="1" <?php echo ($module['show_diagram'] ? 'checked="checked" ' : ''); ?> /></label>
                	<label><b><?php echo $entry_show_first_limit; ?></b><input type="text" name="ocfilter_module[<?php echo $key; ?>][show_options_limit]" value="<?php echo $module['show_options_limit']; ?>" size="4" />&nbsp;<?php echo $text_options; ?></label>
                	<label><b><?php echo $entry_show_first_limit; ?></b><input type="text" name="ocfilter_module[<?php echo $key; ?>][show_values_limit]" value="<?php echo $module['show_values_limit']; ?>" size="4" />&nbsp;<?php echo $text_values; ?></label>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
        <div id="tab-copy" class="ocfilter-htab">
          <table class="form ocfilter-settings">
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td>
                <div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>"><input type="checkbox" name="option_store[]" value="0" /> <?php echo $text_default; ?></div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>"><input type="checkbox" name="option_store[]" value="<?php echo $store['store_id']; ?>" /> <?php echo $store['name']; ?></div>
                  <?php } ?>
                </div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_type; ?></td>
              <td>
                <select name="type">
                  <?php foreach ($types as $item) { ?>
                  <option value="<?php echo $item; ?>"><?php echo ucfirst($item); ?></option>
                  <?php } ?>
                </select>
              </td>
            </tr>
            <tr><td colspan="2" align="right"><a id="copy-attributes" class="button"><span><?php echo $button_copy; ?></span></a></td></tr>
          </table>
        </div>
      </form>
			<?php } /* if module installed */ ?>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
	ocfilter.php.button_copy = '<?php echo $button_copy; ?>';
  ocfilter.php.button_remove = '<?php echo $button_remove; ?>';
	ocfilter.php.text_executed = '<?php echo $text_executed; ?>';
  ocfilter.php.text_ready = '<?php echo $text_ready; ?>';
  ocfilter.php.text_options = '<?php echo $text_options; ?>';
  ocfilter.php.text_values = '<?php echo $text_values; ?>';
  ocfilter.php.text_select = '<?php echo $text_select; ?>';
	ocfilter.php.text_selected = '<?php echo $text_selected; ?>';
  ocfilter.php.entry_show_diagram = '<?php echo $entry_show_diagram; ?>';
  ocfilter.php.entry_show_first_limit = '<?php echo $entry_show_first_limit; ?>';
  ocfilter.php.entry_show_price = '<?php echo mb_substr($entry_show_price, 0, -1, 'UTF-8'); ?>';
  ocfilter.php.entry_stock_status = '<?php echo mb_substr($entry_stock_status, 0, -1, 'UTF-8'); ?>';
  ocfilter.php.entry_manufacturer = '<?php echo mb_substr($entry_manufacturer, 0, -1, 'UTF-8'); ?>';

  ocfilter.php.layouts = [];

 	<?php foreach ($layouts as $layout) { ?>
  ocfilter.php.layouts.push({
		layout_id: <?php echo $layout['layout_id']; ?>,
		name: '<?php echo $layout['name']; ?>'
	});
  <?php } ?>

  ocfilter.php.categories = [];

 	<?php foreach ($categories as $category) { ?>
  ocfilter.php.categories.push({
		category_id: <?php echo $category['category_id']; ?>,
    level: <?php echo $category['level']; ?>,
		name: '<?php echo $category['name']; ?>'
	});
  <?php } ?>

  ocfilter.php.positions = [];

  <?php foreach ($positions as $item) { ?>
  ocfilter.php.positions.push({
		name: '<?php echo ${'text_' . $item}; ?>',
		position: '<?php echo $item; ?>'
	});
  <?php } ?>
//--></script>
<?php echo $footer; ?>