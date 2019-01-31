<!-- Quick Checkout v4.0.5 by Dreamvention.com module/quickcheckout.tpl -->
<style>
<?php if($settings['general']['block_style'] == 'block') { ?>
#quickcheckout #step_2 .text-input label,
#quickcheckout #step_2 .select-input label,
#quickcheckout #step_2 .password-input label,
#quickcheckout #step_3 .text-input label,
#quickcheckout #step_3 .password-input label,
#quickcheckout #step_3 .select-input label{
	width:80%;
}
#quickcheckout #step_2  .box-content > div,
#quickcheckout #step_3  .box-content > div{
	margin-top:5px;
}
#quickcheckout #step_2 .text-input input[type=text],
#quickcheckout #step_2 .password-input input[type=password],
#quickcheckout #step_2 .select-input select,
#quickcheckout #step_3 .text-input input[type=text],
#quickcheckout #step_3 .password-input input[type=password],
#quickcheckout #step_3 .select-input select{
	width: 100%;
}

#quickcheckout #step_2 .radio-input ul,
#quickcheckout #step_3 .radio-input ul{
	margin-left:0px;}
<?php } ?>
<?php if(isset($settings['general']['max_width'])) { ?>
#quickcheckout { max-width: <?php echo $settings['general']['max_width']; ?>px; 
margin: 0 auto;
}
<?php } ?>

<?php if(isset($settings['general']['checkout_style'])) {
echo $settings['general']['checkout_style'];
} ?>
.blocks{
	display:none}
#step_1{
	display:block}
</style>

<div id="quickcheckout">
<div class="wait"><span class="preloader"></span></div>
<div class="processing-payment"><span class="preloader"></span><span class="text">Processing payment... Please wait</span></div>
  <div class="wrap">
  	<?php if ($logo) { ?>
	  <div id="qc_logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
	<?php } ?>
    <div class="block-title"><?php echo $heading_title; ?> <span id="timer"></span></div>
    <div class="block-content">
      <?php echo (isset($text_empty_cart)) ? $text_empty_cart : ''; ?>
      <div class="aqc-column aqc-column-0">
        <?php 
        $i = 1;
        foreach($settings['step'] as $key => $step){
        ?>
        <div id="step_<?php echo $i; ?>" data-sort="<?php echo $step['sort_order']; ?>" data-row="<?php echo $step['row']; ?>" data-column="<?php echo $step['column']; ?>" data-width="<?php echo $step['width']; ?>" class="blocks">
          <?php $view = 'get_'.$key.'_view'; 
             echo $$view;
             ?>
        </div>
        <?php $i++; 
        }?>
      </div>

      <div id="qc_left" class="aqc-column aqc-column-1" style="width:<?php echo $settings['general']['column_width'][1]; ?>%"></div>
      <div id="qc_right" style="width:<?php echo $settings['general']['column_width'][4]; ?>%; float:left">
      	<div class="aqc-column aqc-column-2" style="width:<?php echo ($settings['general']['column_width'][4]) ? ($settings['general']['column_width'][2]/ $settings['general']['column_width'][4])*100 : 0; ?>%"></div>
      	<div class="aqc-column aqc-column-3" style="width:<?php echo ($settings['general']['column_width'][4])?($settings['general']['column_width'][3]/ $settings['general']['column_width'][4])*100 : 0; ?>%"></div>
      	<div class="aqc-column aqc-column-4" style="width:100%"></div>
  	  </div>
      <br class="clear" />
    </div>
	<div id="debug_block"></div>
  </div>
</div>
<script><!--

if (!window.console) window.console = {};
if (!window.console.log) window.console.log = function (){ } ;
$('#quickcheckout .preloader').spin('huge');
$('.aqc-column > div').tsort({attr:'data-row'});
$('.aqc-column > div').each(function(){
	$(this).appendTo('.aqc-column-' + $(this).attr('data-column'));
		$('.wait').hide();		
		})

$('.min-order').show(300, function(){
	 $('.wait').hide();
})
$(document).ready(function(){
<?php if($settings['general']['only_quickcheckout']){?>	
		
		$('body > div').hide()
		$('#quickcheckout').prependTo("body")
<?php } ?>	  

	$('.blocks').fadeIn("slow", function(){
		debug_update()
		
	});
})


/* 	Core refresh functions
*
*	1 Full Checkout refresh (level 1)
*	2 Payment address + Shipping address + Shipping method + Payment method + Confirm (level 2)
*	3 Shipping address + Shipping method + Payment method + Confirm (level 3)
*	4 Shipping method + Payment method + Confirm (level 4)
*	5 Payment method + Confirm (level 5)
*	6 Confirm (level 6)
*	0 Session (level 0)
*/

function refreshCheckout(Step, func){

	console.log('refreshCheckout level:'+Step)
	updateSettings(function(){
		if(Step == 0){
			if (typeof func == "function") func();
				
		}else{
			if(Step <= 1){ 
			//refreshAllSteps()
			refreshStep(1, function(){
				console.log(' - All steps refreshed')
			})
			}
			if(Step <= 2){ 
				refreshStep(2, function(){
					console.log(' - step_2 refreshed')
				})
			}
			if(Step <= 3){ 
				refreshStep(3, function(){
					console.log(' - step_3 refreshed')
				})
			}
			if(Step <= 4){ 
				refreshStep(4, function(){
					console.log(' - step_4 refreshed')
				})
			}
			if(Step <= 5){ 
				refreshStep(5, function(){
					console.log(' - step_5 refreshed')
				})
			}
			if(Step <= 6){ 
				refreshStep(6, function(){
					console.log(' - step_6 refreshed')
				})	
			}
			if(Step <= 7){ 
				refreshStep(7, function(){	
					console.log(' - step_7 refreshed')
				})
			}
			if(Step <= 8){ 
				refreshStep(8, function(){
					if (typeof func == "function") func();
					console.log(' - step_8 refreshed') 
				})
			}
		}

	})

}

function refreshStep(step_number, func){

	$.ajax({
		url: 'index.php?route=module/quickcheckout/refresh_step'+step_number,
		type: 'post',
		data: $('#quickcheckout input[type=\'text\'], #quickcheckout input[type=\'password\'], #quickcheckout input[type=\'checkbox\'], #quickcheckout input[type=\'radio\']:checked, #quickcheckout select, #quickcheckout textarea'),
		dataType: 'html',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(html) {
			$('#step_'+step_number).html(html)
			if (typeof func == "function") func(); 
			debug_update()
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
function updateSettings(func){
	console.log(' - updateSettings')
	$.ajax({
		url: 'index.php?route=module/quickcheckout/update_settings',
		type: 'post',
		data: $('#quickcheckout input[type=\'text\'], #quickcheckout input[type=\'password\'], #quickcheckout input[type=\'checkbox\']:checked, #quickcheckout input[type=\'radio\']:checked, #quickcheckout select,  #quickcheckout textarea'),
		dataType: 'json',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(json) {
			console.log(json);
			if (typeof func == "function") func();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
function refreshAllSteps(func){
	$.ajax({
		url: 'index.php?route=module/quickcheckout/refresh',
		type: 'post',
		data: $('#quickcheckout input[type=\'text\'], #quickcheckout input[type=\'password\'], #quickcheckout input[type=\'checkbox\']:checked, #quickcheckout input[type=\'radio\']:checked, #quickcheckout select,  #quickcheckout textarea'),
		dataType: 'html',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(html) {
			$('#quickcheckout').html(html)
			if (typeof func == "function") func();
			$('#quickcheckout').show()
			debug_update()
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
/*
*	Login
*/
$(document).on('click', '#button_login', function() {
	$.ajax({
		url: 'index.php?route=module/quickcheckout/login_validate',
		type: 'post',
		data: $('#quickcheckout #step_1 #option_login input'),
		dataType: 'json',
		beforeSend: function() {
			$('#button_login').attr('disabled', true);
			
		},	
		complete: function() {
			$('#button_login').attr('disabled', false);
			
		},				
		success: function(json) {
			console.log(json)
			$('.warning, .error').remove();
			
			if ("error" in json) {
				$('#quickcheckout > .wrap').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
				
				$('.warning').fadeIn('slow');
			} else if(json['reload'])   {
				 location.reload()
			} else {
				refreshAllSteps()
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

$(document).on('click', '#button_login_popup', function() {
	$.ajax({
		url: 'index.php?route=module/quickcheckout/login_validate',
		type: 'post',
		data: $('#option_login_popup input'),
		dataType: 'json',
		beforeSend: function() {
			
			$('#button_login_popup').attr('disabled', true);
			
		},	
		complete: function() {
			$('#button_login_popup').attr('disabled', false);
			
		},				
		success: function(json) {
			console.log(json)
			$('.warning, .error').remove();
			
			if ("error" in json) {
				$('#option_login_popup').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');
				
				$('.warning').fadeIn('slow');
			} else if(json['reload'])   {
				 location.reload()
			} else {
				refreshAllSteps()
				$('#option_login_popup_wrap').fadeOut('slow')
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
/*
*	Registration button click
*/
$(document).on('click', '#qc_confirm_order', function(event) {
	console.log('qc_confirm_order -> click') 
	refreshCheckout(0, function(){
		validateAllFields(function(){
			confirmOrderQC(function(){
				$('.processing-payment').show()
				triggerPayment()	
			})	
		})
	})
	event.stopImmediatePropagation()
});

function triggerPayment(){
	console.log('triggerPayment') ;
	var href = $("<?php echo (!empty($settings['general']['trigger'])) ? $settings['general']['trigger'] : '#confirm_payment .button, #confirm_payment .btn, #confirm_payment input[type=submit]' ; ?>").attr('href');
	if(href != '' && href != undefined) {
        console.log('clicked')
        document.location.href = href;
			
	}else{
		console.log('clicked')
		$("<?php echo (!empty($settings['general']['trigger'])) ? $settings['general']['trigger'] : '#confirm_payment .button, #confirm_payment .btn, #confirm_payment input[type=submit]' ; ?>").trigger("click", function(){

		})
	}
}

/* 	Validation
*
*	Validate all fields in the checkout
*/
function validateAllFields(func){
	console.log('validateAllFields')
	$.ajax({
		url: 'index.php?route=module/quickcheckout/validate_all_fields',
		type: 'post',
		data:  $('#quickcheckout input[data-require=require], #quickcheckout select[data-require=require],#quickcheckout textarea[data-require=require]'),
		dataType: 'json',
		beforeSend: function() {
		},
		complete: function() {
		},
		success: function(json) {
			console.log(json)
			$('.error, .warning').remove()
			$('.highlight-error').removeClass('highlight-error')
			var error = false;
			if("error" in json){
				if ($('#payment_address').is(':visible')  && json['error']['payment_address']) {
					$.each(json['error']['payment_address'], function(key, value){
						console.log(key, value);
						$('#payment_address_wrap [name=\'payment_address\['+key+'\]\']').parents('[class*=-input]').addClass('highlight-error').after('<div class="error">' + value + '</div>');
					});
					error = true;
				}
				if ($('#shipping_address').is(':visible') && json['error']['shipping_address'] ) {
					$.each(json['error']['shipping_address'], function(key, value){
						console.log(key, value);
						$('#shipping_address_wrap [name=\'shipping_address\['+key+'\]\']').parents('[class*=-input]').addClass('highlight-error').after('<div class="error">' + value + '</div>');
					});
					error = true;
				}
				
				if ($('#shipping_method_wrap').is(':visible') && json['error']['shipping_method'] ) {
					$.each(json['error']['shipping_method'], function(key, value){
						console.log(key, value);
						$('#shipping_method_wrap ').prepend('<div class="error">' + value + '</div>');
					});
					error = true;
				}
				
				if ($('#payment_method_wrap').is(':visible') && json['error']['payment_method'] ) {
					$.each(json['error']['payment_method'], function(key, value){
						console.log(key, value);
						$('#payment_method_wrap ').prepend('<div class="error">' + value + '</div>');
					});
					error = true;
				}
				
				if ($('#confirm_wrap').is(':visible') && json['error']['confirm'] ) {
					error = true;
					$.each(json['error']['confirm'], function(key, value){
						if(key == 'error_warning'){
							$.each(json['error']['confirm']['error_warning'], function(key, value){
								$('#confirm_wrap .checkout-product').prepend('<div class="error">' + value + '</div>');
							});
						}else{
						console.log(key, value);
						$('#confirm_wrap [name=\'confirm\['+key+'\]\']').parents('[class*=-input]').addClass('highlight-error').after('<div class="error">' + value + '</div>');
						}
					});
					
				}
			}
			if(error == false){
				if (typeof func == "function") func(); 
			}else{
				$('html,body').animate({
				scrollTop: $(".error").offset().top-60},
				'slow');	
			}
				
			
			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

/* 	
*	Validate changed field in checkout
*/
function validateField(fieldId, func){
	console.log('validateField')
	if($('#'+fieldId).attr('data-require') == 'require'){
		$.ajax({
			url: 'index.php?route=module/quickcheckout/validate_field',
			type: 'post',
			data:  $('#quickcheckout input') + '&field='+$('#'+fieldId).attr('name')+'&value='+$('#'+fieldId).val(),
			dataType: 'html',
			beforeSend: function() {
				
			},
			complete: function() {
				
			},
			success: function(html) {
				$('#'+fieldId).parents('[class*=-input]').removeClass('highlight-error').next('.error').remove()
				if(html  != "" && html.length > 6){
					$('#'+fieldId).parents('[class*=-input]').addClass('highlight-error').after('<div class="error">'+html+'</div>')
				}
				if (typeof func == "function") func(); 
				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

/* 	
*	Validate Checkboxes and radio buttons
*/
function validateCheckbox(fieldId, func){
	console.log('validateCheckbox:' + 'field='+$('#'+fieldId).attr('name')+'&value='+$('#'+fieldId).val()) 
	if($('#'+fieldId).attr('data-require') == 'require'){
		
		$.ajax({
			url: 'index.php?route=module/quickcheckout/validate_field',
			type: 'post',
			data:  'field='+$('#'+fieldId).attr('name')+'&value='+$('#'+fieldId).val(),
			dataType: 'html',
			beforeSend: function() {

			},
			complete: function() {
				
			},
			success: function(html) {
				$('#'+fieldId).parents('[class*=-input]').next('.error').remove()
				if(html  != "" && html.length > 6){
					$('#'+fieldId).parents('[class*=-input]').after('<div class="error">'+html+'</div>')
				}
				if (typeof func == "function") func(); 
				
			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

/* 	
*	Confirm Order
*/
function confirmOrderQC(func){
	console.log('confirmOrderQC') 
	$.ajax({
		url: 'index.php?route=module/quickcheckout/confirm_order',
		type: 'post',
		data:   $('#quickcheckout input[type=\'text\'], #quickcheckout input[type=\'password\'], #quickcheckout input[type=\'checkbox\']:checked, #quickcheckout input[type=\'radio\']:checked, #quickcheckout select'),
		dataType: 'html',
		beforeSend: function() {
			
		},
		complete: function() {
			
		},
		success: function(html) {
			console.log(html) 
			refreshStep(2, function(){
				refreshStep(3, function(){
					if (typeof func == "function") func();
				});
			});	
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}



/*	
*	Actions
*/
$(document).on('focus', 'input[name=\'payment_address[password]\']', function() {
	var input_field = $(this);
	setTimeout(function () {
		input_field.on('change', function() {
			$('input[name=\'payment_address[confirm]\']').next('.error').remove()
		});
	}, 100)
});

$(document).on('click', '#quickcheckout input[name="payment_address[shipping]"]', function(event) {			
<?php if(!$settings['general']['uniform']){?>	
	if ($(this).val() == 1) {
		$(this).val(0) 
	} else {
		$(this).val(1)
	}
<?php } ?>
	refreshCheckout(3)
	event.stopImmediatePropagation()
});

/*
*	Change values of text or select(dropdown)
*/

$(document).on('focus', '#quickcheckout input[type=text], #quickcheckout input[type=password], #quickcheckout select, #quickcheckout textarea', function(event) {
	var input_field = $(this);
	 setTimeout(function () {
        input_field.on('change', function(e) {
			var dataRefresh = $(this).attr('data-refresh');

			validateField( $(this).attr('id') )
			
			if(dataRefresh){refreshCheckout(dataRefresh)}
				
			e.stopImmediatePropagation()
		});
    }, 100)

	event.stopImmediatePropagation()
});

$(document).on('click', '#quickcheckout .qc-quantity span', function(event){											
    if($(this).hasClass('increase')){	   
   		$(this).parent().children('input').val(parseInt($(this).parent().children('input').val())+1)
    }else{
    	$(this).parent().children('input').val(parseInt($(this).parent().children('input').val())-1)		
    }
    if($(this).parent().children('input').val() != 0){
    	refreshCheckout(4)
    }else{
    	refreshAllSteps()
    }
	
	event.stopImmediatePropagation()
})

$(document).on('click', '#quickcheckout #confirm_coupon', function(event){	
	$.ajax({
		url: 'index.php?route=module/quickcheckout/validate_coupon',
		type: 'post',
		data: $('#quickcheckout #coupon'),
		dataType: 'json',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(json) {
			
			$('#quickcheckout #step_6 .qc-checkout-product .error').remove();
			if(json['error']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="error" >' + json['error'] + '</div>');
			}
			$('#quickcheckout #step_6 .qc-checkout-product .success').remove();
			if(json['success']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="success" >' + json['success'] + '</div>');
				refreshCheckout(3)
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});		
	event.stopImmediatePropagation()
})

$(document).on('click', '#quickcheckout #confirm_voucher', function(event){	
	$.ajax({
		url: 'index.php?route=module/quickcheckout/validate_voucher',
		type: 'post',
		data: $('#quickcheckout #voucher'),
		dataType: 'json',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(json) {
			$('#quickcheckout #step_6 .qc-checkout-product .error').remove();
			
			if(json['error']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="error" >' + json['error'] + '</div>');
			}
			$('#quickcheckout #step_6 .qc-checkout-product .success').remove();
			if(json['success']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="success" >' + json['success'] + '</div>');
				refreshCheckout(3)
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
	event.stopImmediatePropagation()
})

$(document).on('click', '#quickcheckout #confirm_reward', function(event){	
	$.ajax({
		url: 'index.php?route=module/quickcheckout/validate_reward',
		type: 'post',
		data: $('#quickcheckout #reward'),
		dataType: 'json',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(json) {
			$('#quickcheckout #step_6 .qc-checkout-product .error').remove();
			if(json['error']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="error" >' + json['error'] + '</div>');
			}
			$('#quickcheckout #step_6 .qc-checkout-product .success').remove();
			if(json['success']){
				$('#quickcheckout #step_6 .qc-checkout-product').prepend('<div class="success" >' + json['success'] + '</div>');
				refreshCheckout(3)
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
	event.stopImmediatePropagation()
})

/*
*	Change values of checkbox or radio or select(click)
*/


$(document).on('click', '#quickcheckout  input[type=checkbox]', function(event) {
	console.log('#quickcheckout  input[type=checkbox]') 										
<?php if(!$settings['general']['uniform']){?>	
	if ($(this).val() == 1) {
		$(this).val(0) 
	} else {
		$(this).val(1)
	}
<?php } ?>
	validateCheckbox( $(this).attr('id'))
	refreshCheckout($(this).attr('data-refresh'))
	event.stopImmediatePropagation()
});
$(document).on('click', '#quickcheckout  input[type=radio]', function(event) {
	console.log(' #quickcheckout  input[type=radio]') 										
	validateCheckbox( $(this).attr('id'))
	refreshCheckout($(this).attr('data-refresh'))
	event.stopImmediatePropagation()
});


<?php if($settings['general']['debug']){?>	
	var count = 0;
	var timer = $.timer(function() {
		$('#timer').html(++count);
	});
	
	timer.set({ time : 100, autostart : false });
<?php } ?>

$(document).ajaxStart(function(){
    $(".wait").show();
    $('#qc_confirm_order').attr('disabled', true);
	<?php if($settings['general']['debug']){?>	
	timer.reset();
	timer.play();
	<?php } ?>
})
$(document).ajaxStop(function(){
    $(".wait").hide();
    $('.processing-payment').hide()
    $('#qc_confirm_order').attr('disabled', false);
	<?php if($settings['general']['debug']){?>	
	timer.pause();
	<?php } ?>
});
function debug_update(){
	<?php if($settings['general']['debug']){?>	
		console.log('refreshAllSteps debug');
		$.ajax({
		url: 'index.php?route=module/quickcheckout/debug',
		type: 'post',
		data: $('#quickcheckout input[type=\'text\'], #quickcheckout input[type=\'password\'], #quickcheckout input[type=\'checkbox\']:checked, #quickcheckout input[type=\'radio\']:checked, #quickcheckout select,  #quickcheckout textarea'),
		dataType: 'html',
		beforeSend: function() {
			
		},
		complete: function() {
				
		},
		success: function(html) {
			$('#quickcheckout #debug_block').html(html)
		},
		error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	<?php } ?>
}

//Only quickcheckout


// Stop bubbling up live functions
$(document).on('focus', '#quickcheckout input, #quickcheckout select, #quickcheckout textarea', function(event) {
	$(this).bind('change', function(e) {
		e.stopImmediatePropagation()
	})
	event.stopImmediatePropagation()
})
// Stop bubbling up live functions
$(document).on('click', '#quickcheckout input, #quickcheckout select, #quickcheckout textarea', function(event) {
	event.stopImmediatePropagation()
})


$(document).on('click', '#quickcheckout .button-toggle', function(event){
	console.log('click debug' + $('#quickcheckout_debug .debug-content').hasClass('hide'));
	if ($('#quickcheckout_debug .debug-content').hasClass('hide')) {
		$('#quickcheckout_debug .debug-content').removeClass('hide')
	}else{
		$('#quickcheckout_debug .debug-content').addClass('hide')	
	}
	event.stopImmediatePropagation()
})

//switchery
//var elems = Array.prototype.slice.call(document.querySelectorAll('.styled'));

// elems.forEach(function(html) {
//   var switchery = new Switchery(html);
// });
//--></script>
