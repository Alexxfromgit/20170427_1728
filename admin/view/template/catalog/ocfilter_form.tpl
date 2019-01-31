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
      <h1><img src="view/image/filter.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
      </div>
    </div>
    <div class="content">
      <form id="form" action="<?php echo $action; ?>" method="post">
        <div id="tabs" class="ocfilter-htabs">
          <a href="#tab-general"><?php echo $tab_general; ?></a>
          <a href="#tab-other"><?php echo $tab_other; ?></a>
          <a href="#tab-values"><?php echo $tab_values; ?></a>
        </div>
        <div id="tab-general" class="ocfilter-htab">
          <table class="form">
            <tr>
              <td><?php echo $entry_name; ?></td>
              <td>
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="ocfilter_option_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo (isset($name[$language['language_id']]) ? $name[$language['language_id']]['name'] : ''); ?>" size="50" />&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_category; ?></td>
              <td>
                <input type="text" name="search" value="" placeholder="<?php echo $text_search_category; ?>" style="width: 340px;" />
                <div class="scrollbox" id="categories">
                  <?php foreach ($categories as $category) { ?>
                  <label class="level-<?php echo $category['level']; ?>"><?php echo str_repeat('<i>&mdash;</i>', $category['level']); ?><input type="checkbox" name="category_id[]" value="<?php echo $category['category_id']; ?>"<?php echo (in_array($category['category_id'], $option_categories) ? ' checked="checked"' : ''); ?> /><?php echo $category['name']; ?></label>
                  <?php } ?>
                </div>
                <div class="select-all"><label><input onclick="$('#categories input').prop('checked', this.checked);" type="checkbox" value="" /> Выбрать все</label></div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_type; ?></td>
              <td>
                <select name="type" class="with-subfield" data-subfield="type">
                  <?php foreach ($types as $key => $value) { ?>
                  <?php if ($type == $key) { ?>
                  <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
								<div class="subfield type sf-checkbox sf-radio sf-select<?php echo (($type == 'checkbox' || $type == 'radio' || $type == 'select') ? ' visible' : ''); ?>">
                	<label><span><?php echo $entry_grouping; ?></span>&nbsp; <input type="text" name="grouping" size="3" value="<?php echo $grouping; ?>" /></label>
								</div>
								<div class="subfield type sf-checkbox sf-radio<?php echo (($type == 'checkbox' || $type == 'radio') ? ' visible' : ''); ?>">
                	<label><span><?php echo $entry_selectbox; ?></span><input type="checkbox" name="selectbox" value="1"<?php echo ($selectbox ? ' checked="checked"' : ''); ?> /></label>
								</div>
								<div class="subfield type sf-checkbox sf-radio<?php echo (($type == 'checkbox' || $type == 'radio') ? ' visible' : ''); ?>">
                	<label><span><?php echo $entry_is_color; ?></span><input type="checkbox" name="color" value="1"<?php echo ($color ? ' checked="checked"' : ''); ?> /></label>
								</div>
								<div class="subfield type sf-checkbox sf-radio<?php echo (($type == 'checkbox' || $type == 'radio') ? ' visible' : ''); ?>">
                	<label><span><?php echo $entry_is_image; ?></span><input type="checkbox" name="image" value="1"<?php echo ($image ? ' checked="checked"' : ''); ?> /></label>
								</div>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td>
                <?php if ($status) { ?>
								<input type="checkbox" name="status" checked="checked" value="1" />
                <?php } else { ?>
								<input type="checkbox" name="status" value="1" />
                <?php } ?>
              </td>
            </tr>
          </table>
        </div>
        <div id="tab-other" class="ocfilter-htab">
          <table class="form">
            <tr>
              <td><?php echo $entry_postfix; ?></td>
              <td>
                <?php foreach ($languages as $language) { ?>
                <input type="text" name="ocfilter_option_description[<?php echo $language['language_id']; ?>][postfix]" value="<?php echo (isset($name[$language['language_id']]) ? $name[$language['language_id']]['postfix'] : ''); ?>" size="10" />&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td>
                <?php foreach ($languages as $language) { ?>
                <textarea name="ocfilter_option_description[<?php echo $language['language_id']; ?>][description]" rows="3" cols="50" style="resize: none;"><?php echo (isset($name[$language['language_id']]) ? $name[$language['language_id']]['description'] : ''); ?></textarea>&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td>
                <div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $option_store)) { ?>
                    <input type="checkbox" name="option_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="option_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $option_store)) { ?>
                    <input type="checkbox" name="option_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="option_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
              </td>
            </tr>
          </table>
        </div>
        <div id="tab-values" class="ocfilter-htab">
          <div class="header"><a onclick="ocfilter.form.addValue();" class="button"><?php echo $text_add_value; ?></a></div>
          <ul id="sortable">
            <?php foreach ($ocfilter_option_values as $key => $value) { ?>
            <li>
              <div class="handler"></div>
              <a class="delete" onclick="ocfilter.form.deleteValue($(this));">Delete</a>
              <div class="fields">
              	<input type="hidden" name="ocfilter_option_value[update][<?php echo $value['value_id']; ?>][image]" value="<?php echo $value['image']; ?>" />
                <input type="hidden" name="ocfilter_option_value[update][<?php echo $value['value_id']; ?>][color]" value="<?php echo $value['color']; ?>" />
              	<input type="hidden" name="ocfilter_option_value[update][<?php echo $value['value_id']; ?>][sort_order]" value="<?php echo $value['sort_order']; ?>" />
                <?php foreach ($languages as $language) { ?>
                <label><input type="text" class="value-name" name="ocfilter_option_value[update][<?php echo $value['value_id']; ?>][language][<?php echo $language['language_id']; ?>][name]" value="<?php echo (isset($value['language'][$language['language_id']]) ? $value['language'][$language['language_id']]['name'] : ''); ?>" />&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>
                <?php } ?>
              </div>
							<a href="#" class="color-handler<?php echo ($color ? ' visible' : ''); ?>" title="<?php echo $text_select_color; ?>"<?php echo ($value['color'] ? ' style="background: #' . $value['color'] . ';"' : ''); ?>></a>
							<a href="#" class="image-handler<?php echo ($image ? ' visible' : '') . ($value['image'] ? ' inserted' : ''); ?>" title="<?php echo $text_browse_image; ?>"><img src="<?php echo $value['thumb']; ?>" alt="" /></a>
            </li>
            <?php } ?>
          </ul>
          <div class="bottom"><a onclick="ocfilter.form.addValue();" class="button"><?php echo $text_add_value; ?></a></div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--

ocfilter.php.color = <?php echo $color; ?>;
ocfilter.php.image = <?php echo $image; ?>;
ocfilter.php.no_image = '<?php echo $no_image; ?>';

ocfilter.php.text_image_manager = '<?php echo $text_image_manager; ?>';
ocfilter.php.text_select_color = '<?php echo $text_select_color; ?>';
ocfilter.php.text_browse_image = '<?php echo $text_browse_image; ?>';

ocfilter.php.languages = [];

<?php foreach ($languages as $language) { ?>
ocfilter.php.languages.push({
	'language_id': <?php echo $language['language_id']; ?>,
	'name': '<?php echo $language['name']; ?>',
  'image': '<?php echo $language['image']; ?>'
});
<?php } ?>
//--></script>
<?php echo $footer; ?>