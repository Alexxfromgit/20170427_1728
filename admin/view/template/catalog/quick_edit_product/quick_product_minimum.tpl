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
<form action="POST" id="minimum-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_minimum; ?></td>
      <td><input type="text" name="minimum" value="<?php echo $minimum; ?>" size="2" /></td>
    </tr>
  </table>
</form>
</div>