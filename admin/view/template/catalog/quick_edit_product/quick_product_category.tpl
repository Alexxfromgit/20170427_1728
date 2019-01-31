<div>
<table class="form">
  <tr>
	<td style="width: 50px;">
	  <?php if ($product_image['image']) { ?>
		<img src="<?php echo $product_image; ?>" />
	  <?php } else { ?>
		<img src="<?php echo $product_no_image; ?>" />
	  <?php } ?>
	</td>
    <td><b><?php echo $product_name; ?></b></td>
  </tr>
</table>
<form action="POST" id="category-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_manufacturer; ?></td>
      <td>
	    <select name="manufacturer_id">
          <option value="0" selected="selected"><?php echo $text_none; ?></option>
          <?php foreach ($manufacturers as $manufacturer) { ?>
            <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
              <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
            <?php } else { ?>
              <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
            <?php } ?>
          <?php } ?>
        </select>
	  </td>
    </tr>
    <tr>
      <td><?php echo $entry_category; ?></td>
      <td>
	    <div class="scrollbox" style="height: 337px;">
          <?php $class = 'odd'; ?>
          <?php foreach ($categories as $category) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($category['category_id'], $product_category)) { ?>
                <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                <?php echo $category['name']; ?>
              <?php } else { ?>
                <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
                <?php echo $category['name']; ?>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
        <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a>
	  </td>
	</tr>
  </table>
</form>
</div>