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
<form action="POST" id="subtract-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_subtract; ?></td>
      <td>
	    <select name="subtract">
          <?php if ($subtract) { ?>
            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
            <option value="0"><?php echo $text_no; ?></option>
          <?php } else { ?>
            <option value="1"><?php echo $text_yes; ?></option>
            <option value="0" selected="selected"><?php echo $text_no; ?></option>
          <?php } ?>
        </select>
	  </td>
    </tr>
    <tr>
      <td><?php echo $entry_stock_status; ?></td>
      <td>
	    <select name="stock_status_id">
          <?php foreach ($stock_statuses as $stock_status) { ?>
            <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
              <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
            <?php } else { ?>
              <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
            <?php } ?>
          <?php } ?>
        </select>
	  </td>
    </tr>
    <tr>
      <td><?php echo $entry_shipping; ?></td>
      <td>
	    <?php if ($shipping) { ?>
          <input type="radio" name="shipping" value="1" checked="checked" />
          <?php echo $text_yes; ?>
          <input type="radio" name="shipping" value="0" />
           <?php echo $text_no; ?>
        <?php } else { ?>
          <input type="radio" name="shipping" value="1" />
          <?php echo $text_yes; ?>
          <input type="radio" name="shipping" value="0" checked="checked" />
          <?php echo $text_no; ?>
        <?php } ?>
	  </td>
    </tr>
  </table>
</form>
</div>