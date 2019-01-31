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
<form action="POST" id="special-form">
  <table id="special" class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $entry_customer_group; ?></td>
        <td class="right"><?php echo $entry_priority; ?></td>
        <td class="right"><?php echo $entry_price; ?></td>
        <td class="left"><?php echo $entry_date_start; ?></td>
        <td class="left"><?php echo $entry_date_end; ?></td>
        <td></td>
      </tr>
    </thead>
    <?php $special_row = 0; ?>
    <?php foreach ($product_specials as $product_special) { ?>
      <tbody id="special-row<?php echo $special_row; ?>">
        <tr>
          <td class="left">
		    <select name="product_special[<?php echo $special_row; ?>][customer_group_id]">
              <?php foreach ($customer_groups as $customer_group) { ?>
                <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
              <?php } ?>
            </select>
		  </td>
          <td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
          <td class="right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td>
          <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="date" /></td>
          <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="date" /></td>
          <td class="left"><a onclick="$('#special-row<?php echo $special_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
        </tr>
      </tbody>
      <?php $special_row++; ?>
    <?php } ?>
    <tfoot>
      <tr>
        <td colspan="5"></td>
        <td class="left"><a onclick="addSpecial();" class="button"><?php echo $button_add_special; ?></a></td>
      </tr>
    </tfoot>
  </table>
</form> 
</div>  
<script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;
$('#special-form .date').datepicker({dateFormat: 'yy-mm-dd'});

function addSpecial() {
	html  = '<tbody id="special-row' + special_row + '">';
	html += '  <tr>'; 
    html += '    <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';		
    html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
    html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
	html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
	html += '    <td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
    html += '</tbody>';
	
	$('#special tfoot').before(html);
 
	$('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});
	
	special_row++;
}
//--></script> 