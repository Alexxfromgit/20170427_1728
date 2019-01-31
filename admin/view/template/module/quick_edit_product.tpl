<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a><a onclick="location = '<?php echo $categories; ?>';" class="button"><?php echo $button_categories; ?></a><a onclick="location = '<?php echo $product; ?>';" class="button"><?php echo $button_product; ?></a></div>
    </div>
	<div class="content">
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	    <div id="vtabs" class="vtabs"><a href="#tab-category"><?php echo $text_category; ?></a><a href="#tab-product"><?php echo $text_product; ?></a></div>
	      <div id="tab-category" class="vtabs-content">
			<table class="form">
			  <tr>
				<td><?php echo $entry_quick_edit_category; ?></td>
				<td>
				  <?php if ($config_quick_edit_category) { ?>
					<input type="checkbox" name="config_quick_edit_category" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_edit_category" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_quick_edit_category; ?></td>
			  </tr>
			  <tr>
				<td colspan="3"><?php echo $text_category_info; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_all_buttons; ?></td>
				<td>
				  <?php if ($config_category_quick_all_buttons) { ?>
					<input type="checkbox" name="config_category_quick_all_buttons" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_category_quick_all_buttons" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_category_all_buttons; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_general_data; ?></td>
			 	<td>
				  <?php if ($config_category_general_data) { ?>
					<input type="checkbox" name="config_category_general_data" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_category_general_data" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_category_general_data; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_category_parent; ?></td>
				<td>
				  <?php if ($config_category_parent) { ?>
					<input type="checkbox" name="config_category_parent" value="1" checked="checked" />
				  <?php } else { ?>
				 	<input type="checkbox" name="config_category_parent" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_category_parent; ?></td>
			  </tr>
			  <tr>
			    <td><?php echo $entry_category_filter; ?></td>
				<td>
				  <?php if ($config_category_filter) { ?>
					<input type="checkbox" name="config_category_filter" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_category_filter" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_category_filter; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_category_image; ?></td>
				<td>
				  <?php if ($config_category_image) { ?>
					<input type="checkbox" name="config_category_image" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_category_image" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_category_image; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_category_stores; ?></td>
				<td>
				  <?php if ($config_category_stores) { ?>
					<input type="checkbox" name="config_category_stores" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_category_stores" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_category_stores; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_category_design; ?></td>
				<td>
				  <?php if ($config_category_design) { ?>
					<input type="checkbox" name="config_category_design" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_category_design" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_category_design; ?></td>
			  </tr>
			</table>
		  </div>
		  <div id="tab-product" class="vtabs-content">
			<table class="form">
			  <tr>
				<td><?php echo $entry_quick_edit_product; ?></td>
				<td>
				  <?php if ($config_quick_edit_product) { ?>
					<input type="checkbox" name="config_quick_edit_product" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_edit_product" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_quick_edit_product; ?></td>
			  </tr>
			  <tr>
				<td colspan="3"><?php echo $text_product_info; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_all_buttons; ?></td>
				<td>
				  <?php if ($config_quick_all_buttons) { ?>
					<input type="checkbox" name="config_quick_all_buttons" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_all_buttons" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_all_buttons; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_general_data; ?></td>
				<td>
				  <?php if ($config_general_data) { ?>
					<input type="checkbox" name="config_general_data" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_general_data" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_general_data; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_categories; ?></td>
				<td>
				  <?php if ($config_manufacturer_categories) { ?>
					<input type="checkbox" name="config_manufacturer_categories" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_manufacturer_categories" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_categories; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_filter; ?></td>
				<td>
				  <?php if ($config_quick_filter) { ?>
					<input type="checkbox" name="config_quick_filter" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_filter" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_filter; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_related; ?></td>
				<td>
				  <?php if ($config_quick_related) { ?>
					<input type="checkbox" name="config_quick_related" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_related" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_related; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_code; ?></td>
				<td>
				  <?php if ($config_quick_code) { ?>
					<input type="checkbox" name="config_quick_code" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_code" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_code; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_tax_class; ?></td>
				<td>
				  <?php if ($config_quick_tax_class) { ?>
					<input type="checkbox" name="config_quick_tax_class" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_tax_class" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_tax_class; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_minimum; ?></td>
				<td>
				  <?php if ($config_quick_minimum) { ?>
					<input type="checkbox" name="config_quick_minimum" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_minimum" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_minimum; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_subtract; ?></td>
				<td>
				  <?php if ($config_quick_subtract) { ?>
					<input type="checkbox" name="config_quick_subtract" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_subtract" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_subtract; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_dimension; ?></td>
				<td>
				  <?php if ($config_quick_dimension) { ?>
					<input type="checkbox" name="config_quick_dimension" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_dimension" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_dimension; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_weight; ?></td>
				<td>
				  <?php if ($config_quick_weight) { ?>
					<input type="checkbox" name="config_quick_weight" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_weight" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_weight; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_date_sort; ?></td>
				<td>
				  <?php if ($config_quick_date_sort) { ?>
					<input type="checkbox" name="config_quick_date_sort" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_date_sort" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_date_sort; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_attribute; ?></td>
				<td>
				  <?php if ($config_quick_attribute) { ?>
					<input type="checkbox" name="config_quick_attribute" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_attribute" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_attribute; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_options; ?></td>
				<td>
				  <?php if ($config_quick_options) { ?>
					<input type="checkbox" name="config_quick_options" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_options" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_options; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_special; ?></td>
				<td>
				  <?php if ($config_quick_special) { ?>
					<input type="checkbox" name="config_quick_special" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_special" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_special; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_discount; ?></td>
				<td>
				  <?php if ($config_quick_discount) { ?>
					<input type="checkbox" name="config_quick_discount" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_discount" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_discount; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_images; ?></td>
				<td>
				  <?php if ($config_quick_images) { ?>
					<input type="checkbox" name="config_quick_images" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_images" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_images; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_stores; ?></td>
				<td>
				  <?php if ($config_quick_stores) { ?>
					<input type="checkbox" name="config_quick_stores" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_stores" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_stores; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_downloads; ?></td>
				<td>
				  <?php if ($config_quick_downloads) { ?>
					<input type="checkbox" name="config_quick_downloads" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_downloads" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_downloads; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_reward_points; ?></td>
				<td>
				  <?php if ($config_quick_reward_points) { ?>
					<input type="checkbox" name="config_quick_reward_points" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_reward_points" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_reward_points; ?></td>
			  </tr>
			  <tr>
				<td><?php echo $entry_product_design; ?></td>
				<td>
				  <?php if ($config_quick_design) { ?>
					<input type="checkbox" name="config_quick_design" value="1" checked="checked" />
				  <?php } else { ?>
					<input type="checkbox" name="config_quick_design" value="1" />
				  <?php } ?>
				</td>
				<td><?php echo $info_product_design; ?></td>
			  </tr>
			</table> 
	      </div>
       </div>
	</form>
  </div>
</div>
<script type="text/javascript"><!--
$('#vtabs a').tabs();
//--></script>
<?php echo $footer; ?>