<style>
#payment_input select{
	width: inherit;
	}</style>
<?php if(isset($payment) && $payment != ''){?>
<div id="payment_wrap">
	<div class="box">
	    <div class="box-content">
			<div id="payment_input">
				<div id="confirm_payment"><?php echo $payment; ?></div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
