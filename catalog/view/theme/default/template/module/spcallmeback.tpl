<!--[if gte IE 8]> 
<style>                
.spcallmeback_button span, .spcallmeback_button a {
    padding-left:2px;
    padding-right:2px;
}
</style>                
<![endif]-->     

<style>
<?php if ($button_position == 2): ?>
<?php endif ?>
</style>                

<script type="text/javascript">
    var sp_manual_button_position = '<?php echo str_replace("'", "\\'", $text_manual_button_position) ?>';
    var sp_button_position = <?php echo $button_position ?>;
<?php if ($button_position == 1): ?>
<?php endif ?>
<?php if ($button_position == 2): ?>
<?php endif ?>
</script>                

<?php if ($button_position == 1 || $button_position == 2): ?>
    <div id="spcallmeback_btn_wrap_1" instid="1" class="spcallmeback_btn spcallmeback_button <?php echo $btn_class; ?>">
        <a id="spcallmeback_btn_1" class="spcallmeback_raise_btn" onclick="yaCounter33380443.reachGoal('Zakaz_zvonoka'); return true;" href="#spcallmeback_1" style="color: <?php echo $button_color; ?>; background-color: <?php echo $button_background; ?>"><?php echo $button_caption; ?></a>
    </div>
<?php elseif ($button_position == 3): ?>
    <div class="spcallmeback_btn spcallmeback_button <?php echo $btn_class; ?>">
        <a class="spcallmeback_sidebutton_inside spcallmeback_raise_btn" id="spcallmeback_btn_1" href="#spcallmeback_1" style="color: <?php echo $button_color; ?>; background-color: <?php echo $button_background; ?>"><?php echo $button_caption; ?></a>
    </div>
<?php endif ?>
    <div style="display: none;">
        <div class="spcallmeback_wrap" id="spcallmeback_1">
            <h4><?php echo $form_caption; ?></h4>
            <div><?php echo $form_subcaption; ?></div>
            <br />
            
            <?php echo $form; ?>
            
            <!--form class="spcallmeback_form" id="spcallmeback_form_1" itemid="1">
                <?php /*echo $form;*/ ?>
                <div style="vertical-align: bottom; height: 2em;">
                    <input class="spcallmeback_submit" type="submit" onclick="yaCounter33380443.reachGoal('Zvonok_ok'); return true;" value="Отправить заявку" />
                    <span class="spcallmeback_close_btn">Закрыть окно</span>
                </div>
                <div class="clear"></div>
            </form-->
        </div>
        <div id="spcallmeback_src_1">
        </div>
    </div>
