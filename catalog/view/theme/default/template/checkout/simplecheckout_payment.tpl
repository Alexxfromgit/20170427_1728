<div class="simplecheckout-block" id="simplecheckout_payment" <?php echo $hide ? 'data-hide="true"' : '' ?> <?php echo $display_error && $has_error ? 'data-error="true"' : '' ?>>
    <?php if ($display_header) { ?>
        <div class="checkout-heading"><?php echo $text_checkout_payment_method ?></div>
    <?php } ?>
    <div class="simplecheckout-warning-block" <?php echo $display_error && $has_error_payment ? '' : 'style="display:none"' ?>><?php echo $error_payment ?></div> 
    <div class="simplecheckout-block-content">
        <?php if (!empty($payment_methods)) { ?>
            <table class="simplecheckout-methods-table">
                <?php foreach ($payment_methods as $payment_method) { ?>
                    <tr>
                        <td class="code">
                            <input type="radio" data-onchange="reloadAll" name="payment_method" value="<?php echo $payment_method['code']; ?>" <?php echo !empty($payment_method['dummy']) ? 'disabled="disabled"' : '' ?> <?php echo !empty($payment_method['dummy']) ? 'data-dummy="true"' : '' ?> id="<?php echo $payment_method['code']; ?>" <?php if ($payment_method['code'] == $code) { ?>checked="checked"<?php } ?> />
                        </td>
                        <td class="title">
                            <label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label>
                        </td>
                    </tr>
                    <?php if (!empty($payment_method['description'])) { ?>
                        <tr>
                            <td class="code">
                            </td>
                            <td class="title">
                                <label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['description']; ?></label>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php if ($payment_method['code'] == $code && !empty($rows)) { ?>
                        <tr>
                            <td colspan="2">
                                <?php foreach ($rows as $row) { ?>
                                  <?php echo $row ?>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </table>
            <input type="hidden" name="payment_method_current" value="<?php echo $code ?>" />
            <input type="hidden" name="payment_method_checked" value="<?php echo $checked_code ?>" />
        <?php } ?>
        <?php if (empty($payment_methods) && $address_empty && $display_address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $text_payment_address; ?></div>
        <?php } ?>
        <?php if (empty($payment_methods) && !$address_empty) { ?>
            <div class="simplecheckout-warning-text"><?php echo $error_no_payment; ?></div>
        <?php } ?>
    </div>
</div>