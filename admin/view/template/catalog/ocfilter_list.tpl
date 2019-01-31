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
      <h1><img src="view/image/ocfilter.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <div id="list-actions">
				  <div>
				    <strong><?php echo $text_selecteds; ?> (<span>0</span>):</strong>
				    <label><a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a></label>
				  </div>
				</div>
				<a onclick="location = '<?php echo $insert; ?>'" class="button"><?php echo $button_insert; ?></a>
			</div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" align="center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left" width="25%"><a href="<?php echo $sort_name; ?>" <?php echo ($sort == 'cod.name' ? 'class="' . strtolower($order) . '"' : ''); ?>><?php echo $column_name; ?></a></td>
              <td class="left" width="25%"><?php echo $column_values; ?></td>
              <td class="left"><?php echo $column_categories; ?></td>
              <td class="left"><?php echo $column_type; ?></td>
              <td class="right"><a href="<?php echo $sort_order; ?>" <?php echo ($sort == 'co.sort_order' ? 'class="' . strtolower($order) . '"' : ''); ?>><?php echo $column_sort_order; ?></a></td>
              <td class="right"><?php echo $column_status; ?></td>
              <td class="right" width="100"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td></td>
              <td>
                <select name="filter_category_id" class="ocfilter-categories">
                  <option value=""></option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['category_id'] == $filter_category_id) { ?>
                  <option value="<?php echo $category['category_id']; ?>" class="level-<?php echo $category['level']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>" class="level-<?php echo $category['level']; ?>"><?php echo $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </td>
              <td class="left">
                <select name="filter_type">
                  <option value=""></option>
                  <?php foreach ($types as $key => $type) { ?>
                  <?php if ($key == $filter_type) { ?>
                  <option value="<?php echo $key; ?>" selected="selected"><?php echo $type; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $key; ?>"><?php echo $type; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </td>
              <td></td>
              <td class="right">
                <select name="filter_status">
                  <option value=""></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td align="right"><a onclick="ocfilter.list.filter();" class="button"><?php echo $button_filter; ?></a></td>
            </tr>
            <?php if ($options) { ?>
            <?php foreach ($options as $option) { ?>
            <tr>
              <td align="center"><input type="checkbox" name="selected[]" value="<?php echo $option['option_id']; ?>" /></td>
              <td class="left"><input type="text" name="name" value="<?php echo $option['name']; ?>" size="60" class="edit" for="<?php echo $option['option_id']; ?>" /></td>
              <td class="left"><?php echo $option['values']; ?></td>
              <td class="left"><?php echo $option['categories']; ?></td>
              <td class="left">
                <select class="edit" name="type" for="<?php echo $option['option_id']; ?>">
                  <option value=""><?php echo $text_none; ?></option>
                  <?php foreach ($types as $key => $type) { ?>
                  <option value="<?php echo $key; ?>"<?php echo ($key == $option['type'] ? ' selected="selected"' : ''); ?>><?php echo ucfirst($type); ?></option>
                  <?php } ?>
                </select>
							</td>
              <td class="right"><input type="text" name="sort_order" value="<?php echo $option['sort_order']; ?>" size="6" class="edit" for="<?php echo $option['option_id']; ?>" style="text-align: right;" /></td>
              <td class="right">
								<?php if ($option['status']) { ?>
								<label><input type="checkbox" class="edit" name="status" value="1" for="<?php echo $option['option_id']; ?>" checked="checked" /><span><?php echo $text_enabled; ?></span></label>
								<?php } else { ?>
								<label><input type="checkbox" class="edit" name="status" value="1" for="<?php echo $option['option_id']; ?>" /><span><?php echo $text_disabled; ?></span></label>
								<?php } ?>
							</td>
              <td class="right">
                <?php foreach ($option['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr><td class="center" colspan="8"><?php echo $text_no_results; ?></td></tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--

ocfilter.php.filter_get = [];

<?php foreach ($filter_get as $key) { ?>
ocfilter.php.filter_get.push('<?php echo $key; ?>');
<?php } ?>

ocfilter.php.text_enabled = '<?php echo $text_enabled; ?>';
ocfilter.php.text_disabled = '<?php echo $text_disabled; ?>';

//--></script>
<?php echo $footer; ?>