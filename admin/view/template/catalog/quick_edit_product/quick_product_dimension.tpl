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
<form action="POST" id="dimension-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_dimension; ?></td>
      <td>
	    <input type="text" name="length" value="<?php echo $length; ?>" size="10" />
        <input type="text" name="width" value="<?php echo $width; ?>" size="10" />
        <input type="text" name="height" value="<?php echo $height; ?>" size="10" />
	  </td>
    </tr>
    <tr>
      <td><?php echo $entry_length; ?></td>
      <td>
	    <select name="length_class_id">
          <?php foreach ($length_classes as $length_class) { ?>
            <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
              <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
            <?php } else { ?>
              <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
            <?php } ?>
          <?php } ?>
        </select>
	  </td>
    </tr>
  </table>
</form>
</div>