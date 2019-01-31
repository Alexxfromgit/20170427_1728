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
<form action="POST" id="stores-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_store; ?></td>
      <td>
	    <div class="scrollbox" style="height: 150px;">
          <?php $class = 'even'; ?>
          <div class="<?php echo $class; ?>">
            <?php if (in_array(0, $product_store)) { ?>
              <input type="checkbox" name="product_store[]" value="0" checked="checked" />
              <?php echo $text_default; ?>
            <?php } else { ?>
              <input type="checkbox" name="product_store[]" value="0" />
              <?php echo $text_default; ?>
            <?php } ?>
          </div>
          <?php foreach ($stores as $store) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($store['store_id'], $product_store)) { ?>
                <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                <?php echo $store['name']; ?>
              <?php } else { ?>
                <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                <?php echo $store['name']; ?>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
	  </td>
    </tr>
  </table>
</form>
</div>