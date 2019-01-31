<?php if (!$ajax && !$popup && !$as_module) { ?>
<?php 
$simple_page = 'simplecheckout';
$heading_title .= $display_weight ? '&nbsp;(<span id="weight">'. $weight . '</span>)' : '';
include $simple_header;
?>
<style>
    <?php if ($left_column_width) { ?>
        .simplecheckout-left-column {
            width: <?php echo $left_column_width ?>%;
        }
    <?php } ?>
    <?php if ($right_column_width) { ?>
        .simplecheckout-right-column {
            width: <?php echo $right_column_width ?>%;
        }
    <?php } ?>
</style>
<div class="simple-content">
<?php } ?>
    <?php if (!$ajax || ($ajax && $popup)) { ?>
    <script type="text/javascript">
        if (typeof simple !== "undefined") {
            simple.copyProperties({
                additionalParams: "<?php echo $additional_params ?>",
                additionalPath: "<?php echo $additional_path ?>",
                mainUrl: "<?php echo $action; ?>",
                mainContainer: "#simplecheckout_form",
                currentTheme: "<?php echo $current_theme ?>",
                loginBoxBefore: "<?php echo $login_type == 'flat' ? '#simplecheckout_customer .simplecheckout-block-content:first' : '' ?>",
                displayProceedText: <?php echo $display_proceed_text ? 1 : 0 ?>,
                scrollToError: <?php echo $scroll_to_error ? 1 : 0 ?>,
                scrollToPaymentForm: <?php echo $scroll_to_payment_form ? 1 : 0 ?>,
                useAutocomplete: <?php echo $use_autocomplete ? 1 : 0 ?>,
                useGoogleApi: <?php echo $use_google_api ? 1 : 0 ?>,
                errors: "<?php echo $errors ?>",
                javascriptCallback: function() {<?php echo $javascript_callback ?>}
            });
        } else {
            //console.log("check header.tpl")
        }
    </script>
    <?php } ?>
    <div id="simplecheckout_form" <?php echo $has_error ? 'data-error="true"' : '' ?>>
        <div class="simplecheckout">
            <script type="text/javascript">
                if (typeof simple !== "undefined") {
                    simple.copyProperties({
                        logged: "<?php echo $logged ?>"
                    });
                }
            </script>
            <?php if ($steps_count > 1) { ?>
                <div id="simplecheckout_step_menu">
                <?php for ($i=1;$i<=$steps_count;$i++) { ?><span class="simple-step" data-onclick="gotoStep" data-step="<?php echo $i; ?>"><?php echo $step_names[$i-1] ?></span><?php if ($i < $steps_count) { ?><span class="simple-step-delimiter" data-step="<?php echo $i+1; ?>"><img src="<?php echo $additional_path ?>catalog/view/image/next_gray.png"></span><?php } ?><?php } ?>
                </div>
            <?php } ?>
            <?php 
                $replace = array(
                    '{three_column}'     => '<div class="simplecheckout-three-column">',
                    '{/three_column}'    => '</div>',
                    '{left_column}'      => '<div class="simplecheckout-left-column">',
                    '{/left_column}'     => '</div>',
                    '{right_column}'     => '<div class="simplecheckout-right-column">',
                    '{/right_column}'    => '</div>',
                    '{step}'             => '<div class="simplecheckout-step">',
                    '{/step}'            => '</div>',
                    '{customer}'         => $simple_blocks['customer'],
                    '{payment_address}'  => $simple_blocks['payment_address'],
                    '{shipping_address}' => $simple_blocks['shipping_address'],
                    '{cart}'             => $simple_blocks['cart'],
                    '{shipping}'         => $simple_blocks['shipping'],
                    '{payment}'          => $simple_blocks['payment'],
                    '{agreement}'        => $simple_blocks['agreement'],
                    '{help}'             => $simple_blocks['help'],
                    '{summary}'          => $simple_blocks['summary'],
                    '{comment}'          => $simple_blocks['comment'],
                    '{payment_form}'     => !empty($simple_blocks['payment_form']) ? '<div class="simplecheckout-block" id="simplecheckout_payment_form">'.$simple_blocks['payment_form'].'</div>' : ''
                );
                
                $find = array(
                    '{three_column}',
                    '{/three_column}',
                    '{left_column}',
                    '{/left_column}',
                    '{right_column}',
                    '{/right_column}',
                    '{step}',
                    '{/step}',
                    '{customer}',
                    '{payment_address}',
                    '{shipping_address}',
                    '{cart}',
                    '{shipping}',
                    '{payment}',
                    '{agreement}',
                    '{help}',
                    '{summary}',
                    '{comment}',
                    '{payment_form}'                
                );  

                foreach ($simple_blocks as $key => $value) {
                    $key_clear = $key;
                    $key = '{'.$key.'}';
                    if (!array_key_exists($key, $replace)) {
                        $find[] = $key;
                        $replace[$key] = '<div class="simplecheckout-block" id="'.$key_clear.'">'.$value.'</div>';
                    }
                }
                
                echo trim(str_replace($find, $replace, $simple_template));
            ?>
            <div id="simplecheckout_bottom" style="width:100%;height:1px;clear:both;"></div>
            <div class="simplecheckout-proceed-payment" id="simplecheckout_proceed_payment" style="display:none;"><?php echo $text_proceed_payment ?></div>
            <?php if ($error_warning_agreement && $display_error) { ?>
                <div class="simplecheckout-warning-block" id="agreement_warning" data-error="true"><?php echo $error_warning_agreement ?></div>
            <?php } ?>
            <?php if (!$block_order) { ?>
                <div class="simplecheckout-button-block buttons" id="buttons">
                    <div class="simplecheckout-button-right">
                        <?php if ($display_agreement_checkbox) { ?><span id="agreement_checkbox"><label><input type="checkbox" name="agreement" value="1" <?php if ($agreement == 1) { ?>checked="checked"<?php } ?> /><?php echo $text_agreement; ?></label>&nbsp;</span><?php } ?>
                        <?php if ($steps_count > 1) { ?>
                        <a class="button button_oc btn" data-onclick="nextStep" id="simplecheckout_button_next"><span><?php echo $button_next; ?></span></a>
                        <?php } ?>
                        <a class="button button_oc btn" data-onclick="createOrder" id="simplecheckout_button_confirm"><span><?php echo $button_order; ?></span></a>
                    </div>
                    <div class="simplecheckout-button-left">
                        <?php if ($display_back_button) { ?>
                        <a class="button button_oc btn" data-onclick="backHistory" id="simplecheckout_button_back"><span><?php echo $button_back; ?></span></a>
                        <?php } ?>
                        <?php if ($steps_count > 1) { ?>
                        <a class="button button_oc btn" data-onclick="previousStep" id="simplecheckout_button_prev"><span><?php echo $button_prev; ?></span></a>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php if ($popup) { ?>
        <script type="text/javascript">
            if (typeof simplecheckout !== "undefined") {
                simplecheckout.init(true);
            }
        </script>
    <?php } ?>
<?php if (!$ajax && !$popup && !$as_module) { ?>
</div>
<?php include $simple_footer ?>
<?php } ?>