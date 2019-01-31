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
<form action="POST" id="reward-points-form">
  <table class="form">
    <tr>
      <td><?php echo $entry_points; ?></td>
      <td><input type="text" name="points" value="<?php echo $points; ?>" /></td>
    </tr>
  </table>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $entry_customer_group; ?></td>
        <td class="right"><?php echo $entry_reward; ?></td>
      </tr>
    </thead>
    <?php foreach ($customer_groups as $customer_group) { ?>
      <tbody>
        <tr>
          <td class="left"><?php echo $customer_group['name']; ?></td>
          <td class="right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" /></td>
        </tr>
      </tbody>
    <?php } ?>
  </table>
</form>
</div>