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
<form action="POST" id="filter-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_filters; ?></td>
      <td><input type="text" name="filter" value="" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	    <div id="product-filter" class="scrollbox" style="height: 400px;">
          <?php $class = 'odd'; ?>
          <?php foreach ($product_filters as $product_filter) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div id="product-filter<?php echo $product_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $product_filter['name']; ?><img src="view/image/delete.png" alt="" />
              <input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
            </div>
          <?php } ?>
        </div>
	  </td>
    </tr>
  </table>
</form>
</div>
<script type="text/javascript"><!--	
$(document).ready(function() {
	getFilters();
});	
//--></script> 