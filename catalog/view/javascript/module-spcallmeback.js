var finalPosLeft;
var fancyOrig;
$(document).ready(function() {
    
    if ($('.spcallmeback_raise_btn').length == 0)
    {
        alert(sp_manual_button_position)
        return;
    }
    
    //alert(1);
    $.fn.htmlInclusive = function() { return $('<div />').append($(this).clone()).html(); }
    
    finalPosLeft = sp_button_position == 1 ? 30 : $(window).width() - 30 - 350 - 84;
    fancyOrig = sp_button_position >= 3 ? ($('.spcallmeback_raise_btn').length > 1 ? null : $('.spcallmeback_raise_btn')) : $('#spcallmeback_btn_wrap_1');
        
    $('.spcallmeback_raise_btn').fancybox(
        {
                'transitionIn'        : 'elastic',
                'transitionOut'        : 'elastic',
                'speedIn'                   : 200,
                'speedOut'                   : 100,
                'overlayOpacity'            : 0,
                'autoDimensions'            : true,
                'width'                     : 350,
                'height'                    : 'auto',
                'hideOnOverlayClick'        : true,
                'opacity'                   : false,
                /*'finalPos'                  : {left: finalPosLeft},*/
                'orig'                      : fancyOrig,
                'onComplete'                : function(x) {
                    //alert(y);
                    $.fancybox.resize();
                    var btn = $('.spcallmeback_raise_btn');
                    if (btn != null)
                    {
                        //alert(btn.height());
                        //$("#fancybox-wrap").css({'top': (btn.position().top+40)+'px', 'bottom':'auto'});
                    }
                },
                'onCleanup'                  : function(x) {
                    if ($('form[itemid="1"]') && $('form[itemid="1"]').data('Zebra_Form'))
                        $('form[itemid="1"]').data('Zebra_Form').clear_errors();
                    enable_scroll();
                },
                'onStart'                   : function(selectedArray, selectedIndex) {
                    var obj = selectedArray[ selectedIndex ];
                    disable_scroll();
                    if (sp_button_position >= 3)
                        return true;
                    finalPosLeft = sp_button_position == 1 ? 30 : $(window).width() - 30 - 350 - 84;
                    /*alert($(obj).attr('id'));*/
                    return {'finalPos' : {'left': finalPosLeft}};
                }
        }
    );
    
    //initForm(1);
    
    /*$('.spcallmeback_close_btn').click(function(e) {  
        $.fancybox.close();  
    });*/
    
    /*setTimeout(function() { 
        $('#spcallmeback_btn_1').trigger('click');
    }, 500);*/
    
    
    //$('.spcallmeback_button a').click(function() {spCallmeback_click($(this)); });
}); 


$(window).resize(function(){ 
    if ($('#fancybox-wrap') && $('#fancybox-wrap').is(":visible"))
    {
        var $form = $('form[itemid="1"]').data('Zebra_Form');
        //alert($form);
        $form.clear_errors();
        
        //alert(1);
        finalPosLeft = sp_button_position == 1 ? 30 : $(window).width() - 30 - 350 - 84;
        $.fancybox.resize();
    }
}); 
// left: 37, up: 38, right: 39, down: 40,
// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
var keys = [37, 38, 39, 40];

function preventDefault(e) {
  e = e || window.event;
  if (e.preventDefault)
      e.preventDefault();
  e.returnValue = false;  
}

function keydown(e) {
    for (var i = keys.length; i--;) {
        if (e.keyCode === keys[i]) {
            preventDefault(e);
            return;
        }
    }
}

function wheel(e) {
  preventDefault(e);
}

function disable_scroll() {
  /*if (window.addEventListener) {
      window.addEventListener('DOMMouseScroll', wheel, false);
  }
  window.onmousewheel = document.onmousewheel = wheel;
  document.onkeydown = keydown;*/
  /*$('html').addClass('stop-scrolling2');
  $('body').addClass('stop-scrolling');*/
}

function enable_scroll() {
    /*if (window.removeEventListener) {
        window.removeEventListener('DOMMouseScroll', wheel, false);
    }
    window.onmousewheel = document.onmousewheel = document.onkeydown = null;  */
    /*$('html').removeClass('stop-scrolling2');
    $('body').removeClass('stop-scrolling');*/
}

function initForm(id)
{
    //alert($('form[itemid="' + id + '"]'));
    $('form[itemid="' + id + '"]').unbind("submit");
    $('form[itemid="' + id + '"]').submit(spCallmeback_form_submit);
        
}


function spCallmeback_click(btn)
{
    //$('#spcallmeback_' + btn.attr('instid')).css('display', 'block'); - один из методов
    //alert(btn.find("a"));
    /*width:400, height:300);*/
    //alert();
}

function spCallmeback_form_submit(e)
{
    //alert('submit');
    try
    {
        e.stopPropagation();
        var $form = $(this).data('Zebra_Form');
        if ($form)
        {
            if (!$form.validate())
                return false;
        }
        if (typeof($(this).attr('itemid')) != 'undefined')
        {
            spCallmeback_submit(e.data);
        }
    }
    catch(e)
    {
        alert('JS error');
        if (typeof $.fancybox.hideActivity == 'function') {
			$.fancybox.hideActivity();  
		}
        if (typeof $.fancybox.hideLoading == 'function') {
			$.fancybox.hideLoading();  
		}
        //throw e;
    }
    return false;
}

function spCallmeback_submit(id)
{
    var $form = $('form[itemid="1"]').data('Zebra_Form');
    var form = $('form[itemid="1"]');
    //alert($form);
    $form.clear_errors();
    
    var url = form.attr('action');
    //alert(url);
    
	if (typeof $.fancybox.showActivity == 'function') {
		$.fancybox.showActivity();  
	}
	if (typeof $.fancybox.showLoading == 'function') {
		$.fancybox.showLoading();  
	}
  
    $.ajax({
        type        : "POST",
        cache    : false,
        url        : url,
        data        : form.serializeArray(),
        success: function(data) {
	        if (typeof $.fancybox.hideActivity == 'function') {
				$.fancybox.hideActivity();  
			}
	        if (typeof $.fancybox.hideLoading == 'function') {
				$.fancybox.hideLoading();  
			}
            if (data.length > 2000)
            {
                //$('form[itemid="' + id + '"]').unbind("submit");
                $("#spcallmeback_1").html(data);
                $.fancybox.resize();
                //setTimeout(function() {initForm(id);}, 2000);
                
            }
            else
            {
                $.fancybox({
                    content: data, 
                    'orig'                      : fancyOrig,
                    'overlayOpacity' : 0, 'hideOnContentClick': true,
                    'onStart'                   : function(selectedArray, selectedIndex) {
                            if (sp_button_position >= 3)
                                return true;
                            finalPosLeft = sp_button_position == 1 ? 30 : $(window).width() - 30 - 350 - 84;
                            /*alert($(obj).attr('id'));*/
                            return {'finalPos' : {'left': finalPosLeft}};
                        }
                    }
                    );
            }
        }
    });
    
    /*setTimeout(function() { 
        $.fancybox.hideActivity();  
        $.fancybox({
            content: '<br /><br /><br />xxxxxxxxxxx sg df gfg', 
            'orig'                      : $('#spcallmeback_btn_1'),
            'finalPos'                  : {left: '30'},
            'overlayOpacity' : 0, 'hideOnContentClick': true});
    }, 1000);*/
    return false;
}

function spCallmeback_checkCaptcha(value)
{
    return true;
}
    