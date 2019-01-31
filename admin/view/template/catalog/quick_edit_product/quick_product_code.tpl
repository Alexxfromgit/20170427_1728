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
  <form action="POST" id="code-form">
    <table class="form"> 
	  <tr>
        <td><?php echo $entry_sku; ?></td>
        <td><input type="text" name="sku" value="<?php echo $sku; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_upc; ?></td>
        <td><input type="text" name="upc" value="<?php echo $upc; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_ean; ?></td>
        <td><input type="text" name="ean" value="<?php echo $ean; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_jan; ?></td>
        <td><input type="text" name="jan" value="<?php echo $jan; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_isbn; ?></td>
        <td><input type="text" name="isbn" value="<?php echo $isbn; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_mpn; ?></td>
        <td><input type="text" name="mpn" value="<?php echo $mpn; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_location; ?></td>
        <td><input type="text" name="location" value="<?php echo $location; ?>" /></td>
      </tr>
    </table>
  </form>
</div>