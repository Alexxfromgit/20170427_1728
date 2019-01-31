<div>
<table class="form">
  <tr>
	<td style="width: 50px;">
	  <?php if ($category_image['image']) { ?>
		<img src="<?php echo $category_image; ?>" />
	  <?php } else { ?>
		<img src="<?php echo $category_no_image; ?>" />
	  <?php } ?>
	</td>
    <td><b><?php echo $category_name; ?></b></td>
  </tr>
</table>
<form action="POST" id="stores-form">
  <table class="form">
	<tr>
      <td><?php echo $entry_store; ?></td>
      <td>
	    <div class="scrollbox">
          <?php $class = 'even'; ?>
          <div class="<?php echo $class; ?>">
            <?php if (in_array(0, $category_store)) { ?>
              <input type="checkbox" name="category_store[]" value="0" checked="checked" />
              <?php echo $text_default; ?>
            <?php } else { ?>
              <input type="checkbox" name="category_store[]" value="0" />
              <?php echo $text_default; ?>
            <?php } ?>
          </div>
          <?php foreach ($stores as $store) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($store['store_id'], $category_store)) { ?>
                <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                <?php echo $store['name']; ?>
              <?php } else { ?>
                <input type="checkbox" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
                <?php echo $store['name']; ?>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
	  </td>
    </tr>
	<tr>
      <td><?php echo $entry_top; ?></td>
      <td>
	    <?php if ($top) { ?>
          <input type="checkbox" name="top" value="1" checked="checked" />
        <?php } else { ?>
          <input type="checkbox" name="top" value="1" />
        <?php } ?>
	  </td>
    </tr>
    <tr>
      <td><?php echo $entry_column; ?></td>
      <td><input type="text" name="column" value="<?php echo $column; ?>" size="1" /></td>
    </tr>
	<tr>
      <td><?php echo $entry_sort_order; ?></td>
      <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
    </tr>
  </table>
</form>
</div>
