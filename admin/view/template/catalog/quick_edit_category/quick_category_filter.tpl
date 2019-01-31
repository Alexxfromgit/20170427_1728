<div>
<table class="form">
  <tr>
	<td style="width: 50px;">
	  <?php if ($category_image['image']) { ?>
		<img src="<?php echo $category_image; ?>" />
	  <?php } else { ?>
		<img src="<?php echo $category_no_image; ?>" />
	  <?php } ?>
	</td>
    <td><b><?php echo $category_name; ?></b></td>
  </tr>
</table>
<form action="POST" id="filter-form">
  <table class="form">
	<tr>
      <td><?php echo $entry_filter; ?></td>
      <td><input type="text" name="filter" value="" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
	    <div id="category-filter" class="scrollbox" style="height:400px;">
          <?php $class = 'odd'; ?>
          <?php foreach ($category_filters as $category_filter) { ?>
            <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
            <div id="category-filter<?php echo $category_filter['filter_id']; ?>" class="<?php echo $class; ?>"><?php echo $category_filter['name']; ?><img src="view/image/delete.png" alt="" />
              <input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
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
