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
<form action="POST" id="download-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_download; ?></td>
      <td>
	    <div class="scrollbox" style="height: 250px;">
        <?php $class = 'odd'; ?>
        <?php foreach ($downloads as $download) { ?>
          <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
          <div class="<?php echo $class; ?>">
            <?php if (in_array($download['download_id'], $product_download)) { ?>
              <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
              <?php echo $download['name']; ?>
            <?php } else { ?>
              <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" />
              <?php echo $download['name']; ?>
            <?php } ?>
          </div>
        <?php } ?>
        </div>
	  </td>
    </tr>
  </table>
</form>
</div>