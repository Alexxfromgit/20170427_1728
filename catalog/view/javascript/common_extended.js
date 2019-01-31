var scrollbar_style = function() {
	$('#cart_dialog_div').mCustomScrollbar({
		theme:"dark-thick",
		scrollButtons:{
			enable: true
		}, 
		advanced:{
			updateOnContentResize: true
		}
	});
	setTimeout(scroll_top_top, 500); // "duct tape" here!!!
}

var scroll_top_top = function() {
	$('.mCSB_container').css('top', '0px');
	$('#cart_dialog_div').mCustomScrollbar("scrollTo","top");
}

 var closeDialogWindowOnOverlayClick = function(event){
     var closeButton = $(".ui-dialog:visible").find(".ui-dialog-titlebar-close");
     closeButton.trigger("click");
     $(".ui-widget-overlay").unbind("click", closeDialogWindowOnOverlayClick);
 }

var cartDialog = function(dialog_buttons, page_title) {
	
	var is_opened = true;
	
	if ($('#ui-dialog-title-cart_dialog_div').length > 0) {
		$('#ui-dialog-title-cart_dialog_div').html(page_title);
	};
	
	$( "#cart_dialog_div" ).dialog({
		dialogClass: 'smoothness_prefix',					  
		resizable: false, 
		show: {effect: 'fold', duration: 200},
		hide: {effect: 'fade', duration: 500},
		height: cart_popup_height,
		width: cart_popup_width,
		modal: true,
		resizable: false,
		create: function(event, ui) {
			$(event.target).parent().css('position', 'fixed');
		},
		resizeStop: function(event, ui) {
			var position = [(Math.floor(ui.position.left) - $(window).scrollLeft()),
							 (Math.floor(ui.position.top) - $(window).scrollTop())];
			$(event.target).parent().css('position', 'fixed');
			$(dlg).dialog('option','position',position);
			
		},
		open: function(event, ui) {
			is_opened = false;
			scrollbar_style();
			$('.mCSB_container').css('top', '0px');
			$('#ui-dialog-title-cart_dialog_div').html(page_title);
			$(".ui-widget-overlay").bind("click", closeDialogWindowOnOverlayClick);
			addIconsToCart();
		},
		buttons: dialog_buttons
	});
	
	if (is_opened) {
		scrollbar_style();
		addIconsToCart();
	}
	hide_ajax_loader();
};

var addIconsToCart = function() {
	if (show_icon_cart == true) {
		var btn1 = $('.ui-dialog-buttonpane').find('button').eq(0);
			if (btn1.length > 0) 
				{
					btn1.button({
						icons: {
							primary: 'ui-icon-cart'
						}
					});
				}
		
	}
	if (show_icon_checkout == true) {
		var btn2 = $('.ui-dialog-buttonpane').find('button').eq(1);
			if (btn2.length > 0) 
				{
					btn2.button({
						icons: {
							primary: 'ui-icon-check'
						}
					});
				}
	}
	
	if (show_icon_continue_shopping) {
		var btn3 = $('.ui-dialog-buttonpane').find('button.button_continue_shopping_text');
			if (btn3.length > 0) 
				{
					btn3.button({
						icons: {
							primary: 'ui-icon-arrowreturn-1-e'
						}
					});
				}
	}
}



var getCartData = function() {
	jQuery.ajax({
		url: "index.php?route=module/cart&popup=1",
		success: function(data) {
			$('#cart_dialog_div').html(data);
			getCartData_page_title = $('#cart_dialog_div .heading').text();
			$('#cart_dialog_div .heading').hide();
			
			var dialog_buttons = new Array;
			var btn = {};
			
			if ($('#cart_dialog_div .checkout').length > 0) 
				{
					$('#cart_dialog_div .checkout > a').each(function() {
						var url = $(this).attr('href');
						var btn = {
							text: $(this).text(),
							click: function() { window.location = url;	}
						};
						dialog_buttons.push(btn);
					});
					$('#cart_dialog_div .checkout').hide();
				}
				
			if ($('#cart_dialog_div #multi_lang_cart_close_btn').length > 0) 
				{
					var button_continue_shopping_text = $('#cart_dialog_div #multi_lang_cart_close_btn').text();
				}
			else if (button_continue_shopping) 
				{
					var button_continue_shopping_text = button_continue_shopping;
				}
			else 
				{
					button_continue_shopping_text = false;
				}
				
			if (button_continue_shopping_text) 
				{
					btn = {
							text: button_continue_shopping_text,
							'class': 'button_continue_shopping_text',
							click: function() { $('#cart_dialog_div').dialog('close'); }
					};
					dialog_buttons.push(btn);
				}
				
			cartDialog(dialog_buttons, getCartData_page_title);
		}, 
		error: function(error) {
			console.log('error: ' + error);
		}
	});
};

$(document).on('click', '#cart_dialog_div .remove img', function() {
	show_ajax_loader();
	setTimeout('getCartData()', 500);
});

var newAddToCartSuccessCallback = function(origCallback, origData){
	return function(json, textStatus, jqXHR) {
		if (typeof origCallback === "function") {
		
			$('.success, .warning, .attention, information, .error').remove();
			
			if (json['redirect'] && typeof origData == 'string') {
				hide_ajax_loader();
				location = json['redirect'];
			}
			
			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
					}
				}
			} 

			
			if (json['success']) {
				
				if ($('#cart_dialog_div').length == 0) {
					$('body').append('<div id="cart_dialog_div"></div>');
				}
				
				$('#cart-total').html(json['total']);
				
				getCartData();
			}
		}
	};
};

(function(){
    var jqAjax = $.ajax;
    $.ajax = function(settings){
        if (settings.url === 'index.php?route=checkout/cart/add') 
			{
				settings.success 	= new newAddToCartSuccessCallback(settings.success, settings.data);				
				settings.beforSend 	= new show_ajax_loader();
			}
		jqAjax(settings);
		
    };
	
})();

$(document).ready(function() {
	if (disable_default_small_cart) {
		$('#cart > .heading a').die();
		$(document).on('click', '#cart > .heading a', function() {
			show_ajax_loader();
			if ($('#cart_dialog_div').length == 0) {
				$('body').append('<div id="cart_dialog_div"></div>');
			}
			getCartData();
		});
	}
});

var getCartData_page_title;

var show_ajax_loader = function() {
	if ($('#perm_ajax_loader').length == 0 ) {
		$( "body" ).append('<div id="perm_ajax_loader" style="width: 160px; height: 24px; padding: 3px; box-shadow: 0 0 5px rgba(0,0,0,0.2); position: fixed; top: 0; left: 50%; margin-left: -80px; margin-top: 3px; z-index: 9999; background: #fff; border-radius: 2px;"><img src="catalog/view/javascript/ajax_loader.gif"></div>'); 
	}
}

var hide_ajax_loader = function() {  $('#perm_ajax_loader').fadeOut('slow', function() { $(this).remove(); }) };

function loadScript(url, callback)
{
    // adding the script tag to the head as suggested before
   var head = document.getElementsByTagName('head')[0];
   var script = document.createElement('script');
   script.type = 'text/javascript';
   script.src = url;

   if (callback) {
	   // then bind the event to the callback function 
	   // there are several events for cross browser compatibility
	   script.onreadystatechange = callback;
	   script.onload = callback;
   }

   // fire the loading
   head.appendChild(script);
}