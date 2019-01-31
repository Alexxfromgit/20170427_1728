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
<form action="POST" id="category-form">
  <table class="form">
	<tr>
      <td><?php echo $entry_parent; ?></td>
      <td>
	    <input type="text" name="path" value="<?php echo $path; ?>" size="100" />
        <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
	  </td>
    </tr>
  </table>
</form>
</div>
<script type="text/javascript"><!--	
$(document).ready(function() {
	getParent();
});	
//--></script> 
