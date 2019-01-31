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
<form action="POST" id="weight-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_weight; ?></td>
      <td><input type="text" name="weight" value="<?php echo $weight; ?>" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_weight_class; ?></td>
      <td>
	    <select name="weight_class_id">
          <?php foreach ($weight_classes as $weight_class) { ?>
            <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
              <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
            <?php } else { ?>
              <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
            <?php } ?>
          <?php } ?>
        </select>
	  </td>
    </tr>
  </table>
</form>
</div>