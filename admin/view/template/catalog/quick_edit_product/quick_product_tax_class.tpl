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
<form action="POST" id="tax-class-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_tax_class; ?></td>
      <td>
	    <select name="tax_class_id">
          <option value="0"><?php echo $text_none; ?></option>
          <?php foreach ($tax_classes as $tax_class) { ?>
            <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
            <?php } else { ?>
              <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
            <?php } ?>
          <?php } ?>
        </select>
	  </td>
    </tr>
  </table>
</form>
</div>