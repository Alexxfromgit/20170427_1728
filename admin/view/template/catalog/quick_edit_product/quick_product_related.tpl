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
<form action="POST" id="related-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_relateds; ?></td>
      <td>
	    <div class="scrollbox" style="height: 450px;">
          <?php $class = 'odd'; ?>
          <?php foreach ($relateds as $related) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div class="<?php echo $class; ?>">
              <?php if (in_array($related['product_id'], $product_related)) { ?>
                <input type="checkbox" name="product_related[]" value="<?php echo $related['product_id']; ?>" checked="checked" />
                <?php echo $related['name']; ?>
              <?php } else { ?>
                <input type="checkbox" name="product_related[]" value="<?php echo $related['product_id']; ?>" />
                <?php echo $related['name']; ?>
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
