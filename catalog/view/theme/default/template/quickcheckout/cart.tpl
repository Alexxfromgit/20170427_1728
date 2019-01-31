<!-- Quick Checkout v4.2 by Dreamvention.com quickcheckout/cart.tpl -->
<style>
.qc.qc-popup {
  width: <?php echo $settings['general']['cart_image_size']['width']; ?>px;
  height: <?php echo $settings['general']['cart_image_size']['height']; ?>px;
}
</style>
<div id="cart_wrap">
  <div class="box box-border">
    <div class="box-heading <?php if (!$data['display']) {  echo 'qc-hide';  } ?>">
      <span class="wrap"><span class="qc-icon-cart"></span></span> 
      <span class="text"><?php echo $data['title']; ?></span>
    </div>
  
  <div class="qc-checkout-product <?php echo (!$data['display']) ? 'qc-hide' : ''; ?>" >
    <?php if(isset($error)){ ?>
      <?php foreach ($error as $error_message){ ?>
        <div class="error"><?php echo $error_message; ?></div>
      <?php } ?>
    <?php } ?>

    <table class="qc-table qc-cart">
      <thead>
        <tr>
          <td class="qc-image <?php echo (!$data['columns']['image'])?  'qc-hide' :""; ?>"><?php echo $column_image; ?>:</td>
          <td class="qc-name <?php echo (!$data['columns']['name'])?  'qc-hide' :""; ?>"><?php echo $column_name; ?>:</td>
          <td class="qc-model <?php echo (!$data['columns']['model'])?  'qc-hide' :""; ?>"><?php echo $column_model; ?>:</td>
          <td class="qc-quantity <?php echo (!$data['columns']['quantity'])?  'qc-hide' :""; ?>"><?php echo $column_quantity; ?>:</td>
          <td class="qc-price  <?php echo (!$data['columns']['price'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'qc-hide' :""; ?> "><?php echo $column_price; ?>:</td>
          <td class="qc-total <?php  echo (!$data['columns']['total'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'qc-hide' :""; ?>"><?php echo $column_total; ?>:</td>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr <?php echo(!$product['stock']) ? 'class="stock"' : '' ;?>>
          <td class="qc-image <?php echo (!$data['columns']['image'])?  'qc-hide' : '' ?> ">
            <a rel="qc-popup" data-help='<img src="<?php echo $product['image']; ?>" />'  href="<?php echo $product['href']; ?>">
              <img src="<?php echo $product['thumb']; ?>" />
            </a>
            <i rel="tooltip" data-help="'.$field['tooltip'] .'"></i>
          </td>
          <td class="qc-name  <?php echo (!$data['columns']['name'])?  'qc-hide' : '' ?> ">
            <a href="<?php echo $product['href']; ?>" <?php echo (!$data['columns']['image'])?  'rel="popup" data-help=\'<img src="'.$product['image'].'"/>\'' : '' ?>> 
              
              <?php echo $product['name']; ?> <?php echo (!$product['stock'])? '<span class="out-of-stock">***</span>' : '' ?>
            </a>
            <?php foreach ($product['option'] as $option) { ?>
              <div> &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small> </div>
            <?php } ?>
            <div class="qc-name-model <?php echo (!$data['columns']['model'])?  'qc-hide' : '' ?>"><span class="title"><?php echo $column_model; ?>:</span> <span class="text"><?php echo $product['model']; ?></span></div>
            <div class="qc-name-price <?php echo (!$data['columns']['price'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'qc-hide' : ''; ?>"><span class="title"><?php echo $column_price; ?>:</span> <span class="text"><?php echo $product['price']; ?></span></div>
          </td>
          <td class="qc-model <?php echo (!$data['columns']['model'])?  'qc-hide' : '' ?> "><?php echo $product['model']; ?></td>
          <td class="qc-quantity  <?php echo (!$data['columns']['quantity'])?  'qc-hide' : '' ?> ">
            <span class="qc-icon-minus-circle decrease" data-product="<?php echo $product['product_id']; ?>"></span>
            <input type="text" value="<?php echo $product['quantity']; ?>" class="qc-product-qantity" name="cart[<?php echo $product['product_id']; ?>]"  data-refresh="2"/>
            <span class="qc-icon-plus-circle increase" data-product="<?php echo $product['product_id']; ?>"></span></td>
          <td class="qc-price <?php echo (!$data['columns']['price'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'qc-hide' : ''; ?> "><?php echo $product['price']; ?></td>
          <td class="qc-total <?php echo (!$data['columns']['total'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'qc-hide' : ''; ?> "><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $vouchers) { ?>
        <tr>
          <td class="qc-name <?php echo (!$data['columns']['image'])?  'qc-hide' : '' ?> "></td>
          <td class="qc-name <?php echo (!$data['columns']['name'])?  'qc-hide' : '' ?> "><?php echo $vouchers['description']; ?></td>
          <td class="qc-model <?php echo (!$data['columns']['model'])?  'qc-hide' : '' ?> "></td>
          <td class="qc-quantity <?php echo (!$data['columns']['quantity'])?  'qc-hide' : '' ?> ">1</td>
          <td class="qc-price <?php echo (!$data['columns']['price'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'qc-hide' : ''; ?> "><?php echo $vouchers['amount']; ?></td>
          <td class="qc-total <?php echo (!$data['columns']['total'] || ($this->config->get('config_customer_price') && !$this->customer->isLogged()))?  'qc-hide' : '' ?> "><?php echo $vouchers['amount']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <div class="qc-table qc-options">
        <div class="qc-row qc-coupon <?php if(!$coupon_status || !$data['option']['coupon']['display']){ echo 'qc-hide';} ?>">
          <div class="qc-col qc-text" ><?php echo $text_use_coupon; ?>
          </div><div class="qc-col qc-total"><input type="text" value="<?php echo (isset($coupon))?  $coupon : ''; ?>" name="coupon" id="coupon" placeholder="<?php echo $text_use_coupon; ?>" />
          <span class="qc-icon-check" id="confirm_coupon"></span></div>
        </div>
        <div class="qc-row qc-voucher <?php if(!$voucher_status || !$data['option']['voucher']['display']){ echo 'qc-hide';} ?>">
          <div class="qc-col qc-text" ><?php echo $text_use_voucher; ?>
          </div><div class="qc-col qc-total"><input type="text" value="<?php echo (isset($voucher))?  $voucher : ''; ?>" name="voucher" id="voucher" placeholder="<?php echo $text_use_voucher; ?>"/>
            <span class="qc-icon-check" id="confirm_voucher"></span></div>
        </div>
        <div class="qc-row qc-reward <?php if(!$reward_status || !$data['option']['reward']['display']){ echo 'hide';} ?>">
          <div class="qc-col qc-text" ><?php echo $text_use_reward; ?>
          </div><div class="qc-col qc-total "><input type="text" value="<?php echo (isset($reward))?  $reward : ''; ?>" name="reward" id="reward" placeholder="<?php echo $text_use_reward; ?>"/>
            <span class="qc-icon-check" id="confirm_reward"></span></div>
        </div>
    </div>
    <div class="qc-table qc-summary <?php if($this->config->get('config_customer_price') && !$this->customer->isLogged()){ echo 'qc-hide';}?>">
        <?php foreach ($totals as $total) { ?>
        <div class="qc-row qc-totals">
          <div class="qc-col qc-text" ><?php echo $total['title']; ?>
          </div><div class="qc-col qc-total"><?php echo $total['text']; ?></div>
        </div>
        <?php } ?>
    </div>

  </div>
  <div class="clear"></div>
  </div>
</div>
<script><!--
$(function(){
	if($.isFunction($.fn.uniform)){
		$(" .styled, input:radio.styled").uniform().removeClass('styled');
	}
	if($.isFunction($.fn.colorbox)){
		$('.colorbox').colorbox({
			width: 640,
			height: 480
		});
	}
	if($.isFunction($.fn.fancybox)){
		$('.fancybox').fancybox({
			width: 640,
			height: 480
		});
	}
});
//--></script>
