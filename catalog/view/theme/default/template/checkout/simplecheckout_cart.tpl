<div class="simplecheckout-block" id="simplecheckout_cart" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
<?php if ($display_header) { ?>
    <div class="checkout-heading"><?php echo $text_cart ?></div>
<?php } ?>
<?php if ($attention) { ?>
    <div class="simplecheckout-warning-block"><?php echo $attention; ?></div>
<?php } ?>    
<?php if ($error_warning) { ?>
    <div class="simplecheckout-warning-block"><?php echo $error_warning; ?></div>
<?php } ?>
    <table class="simplecheckout-cart">
        <colgroup>
            <col class="image">
            <col class="name">
            <col class="model">
            <col class="quantity">
            <col class="price">
            <col class="total">
            <col class="remove">
        </colgroup>
        <thead>
            <tr>
                <th class="image"><?php echo $column_image; ?></th>
                <th class="name"><?php echo $column_name; ?></th>
                <th class="model"><?php echo $column_model; ?></th>
                <th class="quantity"><?php echo $column_quantity; ?></th>
                <th class="price"><?php echo $column_price; ?></th>
                <th class="total"><?php echo $column_total; ?></th>
                <th class="remove"></th>        
            </tr>
        </thead>
    <tbody>
    <?php foreach ($products as $product) { ?>
        <?php if (!empty($product['recurring'])) { ?>
            <tr>
                <td class="simplecheckout-recurring-product" style="border:none;"><img src="<?php echo $additional_path ?>catalog/view/theme/default/image/reorder.png" alt="" title="" style="float:left;" />
                    <span style="float:left;line-height:18px; margin-left:10px;"> 
                    <strong><?php echo $text_recurring_item ?></strong>
                    <?php echo $product['profile_description'] ?>
                    </span>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td class="image">
                <?php if ($product['thumb']) { ?>
                    <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                <?php } ?>
            </td> 
            <td class="name">
                <?php if ($product['thumb']) { ?>
                    <div class="image">
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                    </div>
                <?php } ?>
                <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php if (!$product['stock'] && ($config_stock_warning || !$config_stock_checkout)) { ?>
                    <span class="product-warning">***</span>
                <?php } ?>
                <div class="options">
                <?php foreach ($product['option'] as $option) { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                <?php } ?>
                <?php if (!empty($product['recurring'])) { ?>
                - <small><?php echo $text_payment_profile ?>: <?php echo $product['profile_name'] ?></small>
                <?php } ?>
                </div>
                <?php if ($product['reward']) { ?>
                <small><?php echo $product['reward']; ?></small>
                <?php } ?>
            </td>
            <td class="model"><?php echo $product['model']; ?></td>
            <td class="quantity">
                <img data-onclick="decreaseProductQuantity" src='<?php echo $additional_path ?>catalog/view/image/minus.png'>
                <input type="text" data-onchange="changeProductQuantity" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" />
                <img data-onclick="increaseProductQuantity" src='<?php echo $additional_path ?>catalog/view/image/plus.png'>
            </td>
            <td class="price"><?php echo $product['price']; ?></td>
            <td class="total"><?php echo $product['total']; ?></td>
            <td class="remove">
                <img data-onclick="removeProduct" data-product-key="<?php echo $product['key'] ?>" src="<?php echo $additional_path ?>catalog/view/image/close.png" />
            </td>        
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher_info) { ?>
                <tr>
                    <td class="image"></td>  
                    <td class="name"><?php echo $voucher_info['description']; ?></td>
                    <td class="model"></td>
                    <td class="quantity">1</td>
                    <td class="price"><?php echo $voucher_info['amount']; ?></td>
                    <td class="total"><?php echo $voucher_info['amount']; ?></td>
                    <td class="remove">
                    <img data-onclick="removeGift" data-gift-key="<?php echo $voucher_info['key']; ?>" src="<?php echo $additional_path ?>catalog/view/image/close.png"  />
                    </td>
                </tr>
            <?php } ?>
    </tbody>
</table>
    
<?php foreach ($totals as $total) { ?>
    <div class="simplecheckout-cart-total" id="total_<?php echo $total['code']; ?>">
        <span><b><?php echo $total['title']; ?>:</b></span>
        <span class="simplecheckout-cart-total-value"><?php echo $total['text']; ?></span>
        <span class="simplecheckout-cart-total-remove">
            <?php if ($total['code'] == 'coupon') { ?>
            <img data-onclick="removeCoupon" src="<?php echo $additional_path ?>catalog/view/image/close.png" />
            <?php } ?>
            <?php if ($total['code'] == 'voucher') { ?>
            <img data-onclick="removeVoucher" src="<?php echo $additional_path ?>catalog/view/image/close.png" />
            <?php } ?>
            <?php if ($total['code'] == 'reward') { ?>
            <img data-onclick="removeReward" src="<?php echo $additional_path ?>catalog/view/image/close.png" />
            <?php } ?>
        </span>
    </div>
<?php } ?>
<?php if (isset($modules['coupon'])) { ?>
    <div class="simplecheckout-cart-total">
        <span class="inputs"><?php echo $entry_coupon; ?>&nbsp;<input type="text" data-onchange="reloadAll" name="coupon" value="<?php echo $coupon; ?>" /></span>
    </div>
<?php } ?>
<?php if (isset($modules['reward']) && $points > 0) { ?>
    <div class="simplecheckout-cart-total">
        <span class="inputs"><?php echo $entry_reward; ?>&nbsp;<input type="text" name="reward" data-onchange="reloadAll" value="<?php echo $reward; ?>" /></span>
    </div>
<?php } ?>
<?php if (isset($modules['voucher'])) { ?>
    <div class="simplecheckout-cart-total">
        <span class="inputs"><?php echo $entry_voucher; ?>&nbsp;<input type="text" name="voucher" data-onchange="reloadAll" value="<?php echo $voucher; ?>" /></span>
    </div>
<?php } ?>
<?php if (isset($modules['coupon']) || isset($modules['reward']) || isset($modules['voucher'])) { ?>
    <div class="simplecheckout-cart-total simplecheckout-cart-buttons">
        <span class="inputs buttons"><a id="simplecheckout_button_cart" data-onclick="reloadAll" class="button button_oc btn"><span><?php echo $button_update; ?></span></a></span>
    </div>
<?php } ?>
<input type="hidden" name="remove" value="" id="simplecheckout_remove">
<div style="display:none;" id="simplecheckout_cart_total"><?php echo $cart_total ?></div>
<?php if ($display_weight) { ?>
    <div style="display:none;" id="simplecheckout_cart_weight"><?php echo $weight ?></div>
<?php } ?>
<?php if (!$display_model) { ?>
    <style>
    .simplecheckout-cart col.model,
    .simplecheckout-cart th.model,
    .simplecheckout-cart td.model {
        display: none;
    }
    </style>
<?php } ?>
</div>