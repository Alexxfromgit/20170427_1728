<?php echo $header; ?>
<div id="content">
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a><a href="<?php echo $setting; ?>" class="button"><?php echo $button_setting; ?></a></div>
    </div>
    <div class="contentes">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $column_image; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?>
				<div class="info-name tooltip" title="<?php echo $text_info_name; ?>"></div></td>
			  <td class="left"><?php if ($sort == 'p2c.category_id') { ?>
				<a href="<?php echo $sort_category; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_category; ?></a>
				<?php } else { ?>
				<a href="<?php echo $sort_category; ?>"><?php echo $column_category; ?></a>
			    <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="center"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="center" style="width: 80px;"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" size="50" /></td>
			  <td align="left">
			    <select name="filter_category" style="max-width: 250px;" onchange="filter();">
				  <option value="*"></option>
				  <?php foreach ($categories as $category) { ?>
                    <?php if ($category['category_id']==$filter_category) { ?>
                      <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option> 
                    <?php } ?>
                  <?php } ?>
				</select>
              </td>
              <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" size="30" /></td>
              <td align="right"><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" size="15"/></td>
              <td align="right"><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" style="text-align: right;"  size="12" /></td>
              <td align="center">
			    <select name="filter_status" onchange="filter();">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                    <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
			  </td>
              <td align="center"><a onclick="filter();" class="button_filter tooltip" title="<?php echo $button_filter; ?>"></a></td>
            </tr>
              <?php if ($products) { ?>
				<?php foreach ($products as $product) { ?>
				<tr>
				  <td style="text-align: center;"><?php if ($product['selected']) { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
					<?php } ?></td>
				  <td class="center" rel="<?php echo $link;?>&edit_image&product_id=<?php echo $product['product_id'];?>">
                    <div>
					  <a class="edit-image">
					    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" id="thumb<?php echo $product['product_id'];?>"/>
					  </a>
					  <input type="hidden" name="image" value="<?php echo $product['image']; ?>" id="image<?php echo $product['product_id'];?>" />
					</div></td>
				  <td class="left">
				    <span class="ajax-edit-left" id="name-<?php echo $product['product_id']; ?>" value="<?php echo $product['product_id']; ?>">
					  <input type="text" value="<?php echo $product['name']; ?>" id="name-<?php echo $product['product_id']; ?>" class="input-edit-left" size="44">
					  <a onclick="save_name(<?php echo $product['product_id']; ?>)" class="button-save-name"></a>
					</span>
					<span><?php echo $product['name']; ?></span></td>
				  <td class="left">
					<?php foreach ($categories as $category) { ?>
					  <?php if (in_array($category['category_id'], $product['category'])) { ?>
						<?php echo $category['name'];?><br>
					  <?php } ?> 
					<?php } ?>
				  </td>
				  <td class="left">
				    <span class="ajax-edit-left" id="model-<?php echo $product['product_id']; ?>" value="<?php echo $product['product_id']; ?>">
					  <input type="text" value="<?php echo $product['model']; ?>" id="model-<?php echo $product['product_id']; ?>" class="input-edit-left" size="24">
					  <a onclick="save_model(<?php echo $product['product_id']; ?>)" class="button-save-left"></a>
					</span>
					<span><?php echo $product['model']; ?></span></td>	
				  <td align="right" class="prices">
				    <?php if ($product['special']) { ?>
					  <div class="sale tooltip" title="<?php echo $product['special']; ?>"></div>
					<?php } ?>
					<span class="ajax-edit-right" id="price-<?php echo $product['product_id']; ?>" value="<?php echo $product['product_id']; ?>">
					  <input type="text" value="<?php echo $product['price']; ?>" class="input-edit-right" size="15">
					  <a onclick="save_price(<?php echo $product['product_id']; ?>)" class="button-save-right"></a>
					</span>
					<span><?php echo $product['price']; ?></span></td>
				  <td  align="right">
					<span class="ajax-edit-right" id="quantity-<?php echo $product['product_id']; ?>" value="<?php echo $product['product_id']; ?>">
					  <input type="text" value="<?php echo $product['quantity']; ?>" class="input-edit-right" size="12">
					  <a onclick="save_quantity(<?php echo $product['product_id']; ?>)" class="button-save-right"></a>
					</span>
					<?php if ($product['quantity'] <= 0) { ?>
					<span style="color: #FF0000;"><b><?php echo $product['quantity']; ?></b></span>
					<?php } elseif ($product['quantity'] <= 5) { ?>
					<span style="color: #FFA500;"><b><?php echo $product['quantity']; ?></b></span>
					<?php } elseif ($product['quantity'] <= 10) { ?>
					<span style="color: #FF00C0;"><b><?php echo $product['quantity']; ?></b></span>
					<?php } else { ?>
					<span style="color: #008000;"><?php echo $product['quantity']; ?></span>
					<?php } ?></td>
				  <td align="center" width="100"><a class="ajax-status" id="status-<?php echo $product['product_id']; ?>"><?php echo $product['status']; ?></a></td>
				  <td class="center">
				    <?php foreach ($product['action'] as $action) { ?>
					  <div class="button-action">
					    <a class="button_edites tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"></a>
						<a class="button_view tooltip" href="<?php echo HTTP_CATALOG;?>?route=product/product&product_id=<?php echo $product['product_id'];?>" target="_blank" title="<?php echo $text_view; ?>"></a>
					  </div>
					<?php } ?>
				  </td>
				</tr>
				<?php if ($this->config->get('config_quick_all_buttons') == 0) { ?>
				<tr class="no">
				  <td colspan="9">
				    <div class="quick-button-icon">
					  <?php if ($this->config->get('config_general_data') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_description&product_id=<?php echo $product['product_id'];?>" class="description_dialog button_general_data tooltip" title="<?php echo $text_description; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_manufacturer_categories') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_category&product_id=<?php echo $product['product_id'];?>" class="category_dialog button_category tooltip" title="<?php echo $text_categories; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_filter') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_filter&product_id=<?php echo $product['product_id'];?>" class="filter_dialog button_filters tooltip" title="<?php echo $text_filter; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_related') == 1) { ?>
					    <?php if ($product['related']) { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_related&product_id=<?php echo $product['product_id'];?>" class="related_dialog button_related tooltip" title="<?php echo $text_related; ?>" /></a>
					    <?php } else { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_related&product_id=<?php echo $product['product_id'];?>" class="related_dialog button_related_none tooltip" title="<?php echo $text_related; ?>" /></a>
					    <?php } ?>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_code') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_code&product_id=<?php echo $product['product_id'];?>" class="code_dialog button_code tooltip" title="<?php echo $text_code; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_tax_class') == 1) { ?>
					    <?php if ($product['tax_class_id']) { ?>					  
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_tax_class&product_id=<?php echo $product['product_id'];?>" class="tax_class_dialog button_tax tooltip" title="<?php echo $text_tax_class; ?>" /></a>
					    <?php } else { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_tax_class&product_id=<?php echo $product['product_id'];?>" class="tax_class_dialog button_tax_none tooltip" title="<?php echo $text_tax_class; ?>" /></a>
					    <?php } ?>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_minimum') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_minimum&product_id=<?php echo $product['product_id'];?>" class="minimum_dialog button_minimum tooltip" title="<?php echo $text_minimum; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_subtract') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_subtract&product_id=<?php echo $product['product_id'];?>" class="subtract_dialog button_subtract tooltip" title="<?php echo $text_subtract; ?>" /></a> 
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_dimension') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_dimension&product_id=<?php echo $product['product_id'];?>" class="dimension_dialog button_dimension tooltip" title="<?php echo $text_dimension; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_weight') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_weight&product_id=<?php echo $product['product_id'];?>" class="weight_dialog button_weight tooltip" title="<?php echo $text_weight; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_date_sort') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_date_sort&product_id=<?php echo $product['product_id'];?>" class="date_sort_dialog button_date_available tooltip" title="<?php echo $text_date_sort; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_attribute') == 1) { ?>
					    <?php if ($product['attribute']) { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_attribute&product_id=<?php echo $product['product_id'];?>" class="attribute_dialog button_attribute tooltip" title="<?php echo $text_attribute; ?>" /></a>
					    <?php } else { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_attribute&product_id=<?php echo $product['product_id'];?>" class="attribute_dialog button_attribute_none tooltip" title="<?php echo $text_attribute; ?>" /></a>
					    <?php } ?>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_options') == 1) { ?>
					    <?php if ($product['options']) { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_option&product_id=<?php echo $product['product_id'];?>" class="option_dialog button_options tooltip" title="<?php echo $text_option; ?>" /></a>
					    <?php } else { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_option&product_id=<?php echo $product['product_id'];?>" class="option_dialog button_options_none tooltip" title="<?php echo $text_option; ?>" /></a>
					    <?php } ?>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_special') == 1) { ?>
					    <?php if ($product['special']) { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&special_price&product_id=<?php echo $product['product_id'];?>" class="special_dialog button_special tooltip" title="<?php echo $text_special; ?>" /></a>
					    <?php } else { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&special_price&product_id=<?php echo $product['product_id'];?>" class="special_dialog button_special_none tooltip" title="<?php echo $text_special; ?>" /></a>
					    <?php } ?>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_discount') == 1) { ?>
					    <?php if ($product['discount']) { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&discount_price&product_id=<?php echo $product['product_id'];?>" class="discount_dialog button_discount tooltip" title="<?php echo $text_discount; ?>" /></a>
					    <?php } else { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&discount_price&product_id=<?php echo $product['product_id'];?>" class="discount_dialog button_discount_none tooltip" title="<?php echo $text_discount; ?>" /></a>
					    <?php } ?>	
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_images') == 1) { ?>
					    <?php if ($product['images']) { ?>		
						  <a style="text-decoration: none;" href="<?php echo $link;?>&product_img&product_id=<?php echo $product['product_id'];?>" class="images_dialog button_images tooltip" title="<?php echo $text_images_product; ?>" /></a>
					    <?php } else { ?>
						 <a style="text-decoration: none;" href="<?php echo $link;?>&product_img&product_id=<?php echo $product['product_id'];?>" class="images_dialog button_images_none tooltip" title="<?php echo $text_images_product; ?>" /></a>
					    <?php } ?>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_stores') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_stores&product_id=<?php echo $product['product_id'];?>" class="stores_dialog button_stores tooltip" title="<?php echo $text_stores; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_downloads') == 1) { ?>
					    <?php if ($product['downloads']) { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_downloads&product_id=<?php echo $product['product_id'];?>" class="download_dialog button_downloads tooltip" title="<?php echo $text_downloads; ?>" /></a>
					    <?php } else { ?>
					      <a style="text-decoration: none;" href="<?php echo $link;?>&product_downloads&product_id=<?php echo $product['product_id'];?>" class="download_dialog button_downloads_none tooltip" title="<?php echo $text_downloads; ?>" /></a>
					    <?php } ?>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_reward_points') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_reward_points&product_id=<?php echo $product['product_id'];?>" class="reward_points_dialog button_reward tooltip" title="<?php echo $text_reward_points; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_quick_design') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&product_design&product_id=<?php echo $product['product_id'];?>" class="design_dialog button_design tooltip" title="<?php echo $text_design; ?>" /></a>
					  <?php } ?>
					</div>				
				  </td>
				</tr>
				<tr class="hr">
				  <td colspan="9"></td>
				</tr>
				<?php } ?>
				<?php } ?>
				<?php } else { ?>
				<tr>
				  <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
				</tr>
              <?php } ?>
          </tbody>
		  <tfoot>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $column_image; ?></td>
              <td class="left"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?>
				<div class="info-name tooltip" title="<?php echo $text_info_name; ?>"></div></td>
			  <td class="left"><?php if ($sort == 'p2c.category_id') { ?>
				<a href="<?php echo $sort_category; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_category; ?></a>
				<?php } else { ?>
				<a href="<?php echo $sort_category; ?>"><?php echo $column_category; ?></a>
			    <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="center"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="center" style="width: 80px;"><?php echo $column_action; ?></td>
            </tr>
          </tfoot>
        </table>
      </form>
	  <div class="pagination"><?php echo $pagination; ?></div>
    </div>
	<div class="foot_heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/product_quick&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_category = $('select[name=\'filter_category\']').attr('value');
    		
    if (filter_category != '*') {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
	
	var filter_price = $('input[name=\'filter_price\']').attr('value');
	
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
	
	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
	
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	

	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product_quick/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product_quick/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>  
<script type="text/javascript"><!--
function save() {
	var saved = $().val();
}
function save_name(id) {
    var input_name = $('#name-'+id+' input');
    var name = $(input_name).val();
    $(name).css('cursor','progress');
    $.ajax({
        url: 'index.php?route=catalog/product_quick/changeName&product_id='+id+'&name='+name+'&token=<?php echo $token; ?>',
        dataType: 'html',
        data: {},
        success: function(name) { 
            $('#name-'+id).next().html(name);
			close_input(id);
        }
    });
    $(input_name).css('cursor','default');
}
$(document).ready(function() {
    $('.ajax-edit-left, .ajax-edit-right').each(function(index, wrapper) {
        $(this).siblings().click(function() {
            $(wrapper).show();
            $(wrapper).siblings().hide();
        })
    });
})
function save_model(id) {
    var input_model = $('#model-'+id+' input');
    var model = $(input_model).val();
    $(model).css('cursor','progress');
    $.ajax({
        url: 'index.php?route=catalog/product_quick/changeModel&product_id='+id+'&model='+model+'&token=<?php echo $token; ?>',
        dataType: 'html',
        data: {},
        success: function(model) { 
            $('#model-'+id).next().html(model);
			close_input(id);
        }
    });
    $(input_model).css('cursor','default');
}
function save_quantity(id) {
    var input_quantity = $('#quantity-'+id+' input');
    var quantity = $(input_quantity).val();
    $(quantity).css('cursor','progress');
    $.ajax({
        url: 'index.php?route=catalog/product_quick/changeQuantity&product_id='+id+'&quantity='+quantity+'&token=<?php echo $token; ?>',
        dataType: 'html',
        data: {},
        success: function(quantity) { 
            $('#quantity-'+id).next().html(quantity);
			close_input(id);
        }
    });
    $(input_quantity).css('cursor','default');
}
function save_price(id) {
    var input_price = $('#price-'+id+' input');
    var price = $(input_price).val();
    $(price).css('cursor','progress');
    $.ajax({
        url: 'index.php?route=catalog/product_quick/changePrice&product_id='+id+'&price='+price+'&token=<?php echo $token; ?>',
        dataType: 'html',
        data: {},
        success: function(price) { 
            $('#price-'+id).next().html(price);
			close_input(id);
        }
    });
    $(input_price).css('cursor','default');
}
function close_input(id) {
    $('.ajax-edit input').blur();
    $('#model-'+id).siblings().show();
    $('#model-'+id).hide();
	$('#quantity-'+id).siblings().show();
    $('#quantity-'+id).hide();
	$('#price-'+id).siblings().show();
    $('#price-'+id).hide();
	$('#name-'+id).siblings().show();
    $('#name-'+id).hide();
}
$('.ajax-status').click(function() {
	var object_id=$(this).attr('id');
	$.ajax({
		url: 'index.php?route=catalog/product_quick/changeStatus&token=<?php echo $token; ?>',
		type: 'get',
		data: {object_id:object_id},
		dataType: 'html',
		success: function(html) {
			if(html!=''){				
				$('#'+object_id).html(html);
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('a.edit-image').live('click', function(){
	var thumb = $(this).children().attr('id');
    var field = $(this).next().attr('id');
	$('#dialog').remove();
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/images&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
						var td = $('#' + field).parents('td')
						td.addClass('updated');
						$.get(td.attr('rel')+'&image='+encodeURIComponent($('#' + field).attr('value')), function(){
						    td.removeClass('updated');
						})
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: true
	});
});
$('a.description_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_description; ?>',
			modal: true,
			resizable: true,
			width: 1000,
			height: 830,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					for ( inst in CKEDITOR.instances ){
						CKEDITOR.instances[inst].updateElement();
					}
					$.post(link, $('#description-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.category_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_categories; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 650,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#category-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.filter_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_filter; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 700,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#filter-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.related_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_related; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 700,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#related-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.code_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_code; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 600,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#code-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.tax_class_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_tax_class; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 300,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#tax-class-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.minimum_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_minimum; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 300,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#minimum-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.subtract_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_subtracts; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 400,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#subtract-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.dimension_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_dimensions; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 350,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#dimension-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.weight_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_weights; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 350,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#weight-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.date_sort_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_date_sorts; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 350,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#date-sort-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.special_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_special; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 400,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#special-form').serialize(), function(response){
				})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.discount_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_discount; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 500,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#discount-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.attribute_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_attribute; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 700,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#attribute-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.option_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_option; ?>',
			modal: true,
			resizable: true,
			width: 1200,
			height: 700,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#option-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.images_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_images_product; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 770,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#images-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.stores_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_stores; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 400,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#stores-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.download_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_downloads; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 500,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#download-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.reward_points_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_reward_points; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 500,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#reward-points-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.design_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_design; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 500,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#design-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
//--></script>
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';
		
		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});
function attributeautocomplete(attribute_row) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').catcomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {	
					response($.map(json, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
      		return false;
   		}
	});
}

$('#attribute tbody').each(function(index, element) {
	attributeautocomplete(index);
});
function getFilters() {
	$('input[name=\'filter\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.filter_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#product-filter' + ui.item.value).remove();
			
			$('#product-filter').append('<div id="product-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="product_filter[]" value="' + ui.item.value + '" /></div>');

			$('#product-filter div:odd').attr('class', 'odd');
			$('#product-filter div:even').attr('class', 'even');
					
			return false;
		},
		focus: function(event, ui) {
		  return false;
	   }
	});
	$('#product-filter div img').live('click', function() {
		$(this).parent().remove();
		
		$('#product-filter div:odd').attr('class', 'odd');
		$('#product-filter div:even').attr('class', 'even');	
	});
}
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: true
	});
};
//--></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>  
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs(); 
$('#vtab-option a').tabs();
//--></script> 
<script type="text/javascript"><!--
$("a.tooltip, img.tooltip, div.tooltip").tooltip({
	track: true, 
    delay: 0, 
    showURL: false, 
    showBody: " - ", 
    fade: 250 
});
//--></script>
<?php echo $footer; ?>