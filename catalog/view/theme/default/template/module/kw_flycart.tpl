<div id="fly_cart" class="<?php if($pselect == 'none') { ?>standart<?php } ?>">
	<?php if($type == 'module') { ?>
	<div class="box" id="flymod">
		<div class="box-heading"><span class="title_mod"><?php echo $heading_title; ?></span>
			<div id="fly_element" class="module">
				<span><?php echo $text_items; ?></span>
			</div>	
			<div class="clearfix"></div>
		</div>
		<div id="flymod_content" class="box-content">
			<?php if ($products || $vouchers) { ?>
			<div class="mini-cart-info">
				<table>
					<?php foreach ($products as $product) { ?>
					<tr>
						<td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><div><?php foreach ($product['option'] as $option) { ?> - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br /><?php } ?></div></td>
						<td class="total">
							<?php echo $product['total']; ?>
							<span class="remove"><a href="#" title="<?php echo $button_remove; ?>" data-key="<?php echo $product['key']; ?>"><svg version="1.1" id="remove" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="9.999px" height="9.999px" viewBox="-2 -2 9.999 9.999" style="enable-background:new -2 -2 9.999 9.999;" xml:space="preserve"><path style="fill:#2D2D2D;" d="M-1.868-0.186L1.317,3l-3.185,3.184c-0.177,0.179-0.177,0.465,0,0.643l1.04,1.04c0.179,0.176,0.465,0.176,0.644,0L3,4.684l3.184,3.184c0.176,0.176,0.465,0.176,0.643,0l1.04-1.04c0.176-0.178,0.176-0.464,0-0.643L4.684,3l3.184-3.185c0.176-0.177,0.176-0.464,0-0.644l-1.04-1.04c-0.178-0.177-0.464-0.177-0.643,0L3,1.317l-3.185-3.185c-0.178-0.177-0.464-0.177-0.644,0l-1.04,1.04C-2.044-0.65-2.044-0.362-1.868-0.186z"/></svg></a></span>						
							<br />
							<span class="quantity">
								<input type="text" class="quant" value="<?php echo $product['quantity']; ?>" data-key="<?php echo $product['key']; ?>" /><a class="apply icon-refresh" href="#"></a>
							</span>						
						</td>
					</tr>
					<?php } ?>
					<?php foreach ($vouchers as $voucher) { ?>
					<tr>
						<td class="name"><?php echo $voucher['description']; ?></td>
						<td class="total">
							<?php echo $product['total']; ?>
							<span class="remove"><a href="#" title="<?php echo $button_remove; ?>" data-key="<?php echo $product['key']; ?>"><svg version="1.1" id="remove" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="9.999px" height="9.999px" viewBox="-2 -2 9.999 9.999" style="enable-background:new -2 -2 9.999 9.999;" xml:space="preserve"><path style="fill:#2D2D2D;" d="M-1.868-0.186L1.317,3l-3.185,3.184c-0.177,0.179-0.177,0.465,0,0.643l1.04,1.04c0.179,0.176,0.465,0.176,0.644,0L3,4.684l3.184,3.184c0.176,0.176,0.465,0.176,0.643,0l1.04-1.04c0.176-0.178,0.176-0.464,0-0.643L4.684,3l3.184-3.185c0.176-0.177,0.176-0.464,0-0.644l-1.04-1.04c-0.178-0.177-0.464-0.177-0.643,0L3,1.317l-3.185-3.185c-0.178-0.177-0.464-0.177-0.644,0l-1.04,1.04C-2.044-0.65-2.044-0.362-1.868-0.186z"/></svg></a></span>						
							<br />
							<span class="quantity">
								<input type="text" class="quant" value="<?php echo $product['quantity']; ?>" data-key="<?php echo $product['key']; ?>" /><a class="apply icon-refresh" href="#"></a>
							</span>						
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="mini-cart-total">
				<table>
					<?php foreach ($totals as $total) { ?>
					<tr>
						<td class="label"><b><?php echo $total['title']; ?>:</b></td>
						<td><?php echo $total['text']; ?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php } else { ?>
			<div class="empty"><?php echo $text_empty; ?></div>
			<?php } ?>
			
			<div class="fly_modfoot">
				<button onclick="location.href='<?php echo $cart; ?>';" class="btn <?php echo $pbutton; ?>"><?php echo $text_cart; ?></button>
				<button onclick="location.href='<?php echo $checkout; ?>';" class="btn <?php echo $pbutton; ?>"><?php echo $text_checkout; ?></button>						
			</div>
		</div>
	</div>
	<?php } ?>

	<div id="fly_popup">
		<h2><?php echo $heading_title; ?></h2>
		<div id="fly_content">
			<?php if ($products || $vouchers) { ?>
			<div class="mini-cart-info">
				<table>
					<?php foreach ($products as $product) { ?>
					<tr>
						<td class="image"><?php if ($product['thumb']) { ?><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a><?php } ?></td>
						<td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a><div><?php foreach ($product['option'] as $option) { ?> - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br /><?php } ?></div></td>
						<td class="quantity">
							<input type="text" class="quant" value="<?php echo $product['quantity']; ?>" data-key="<?php echo $product['key']; ?>" /><a class="apply icon-refresh" href="#"></a>
						</td>
						<td class="total"><?php echo $product['total']; ?></td>
						<td class="remove"><a href="#" title="<?php echo $button_remove; ?>" data-key="<?php echo $product['key']; ?>"><svg version="1.1" id="remove" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="9.999px" height="9.999px" viewBox="-2 -2 9.999 9.999" style="enable-background:new -2 -2 9.999 9.999;" xml:space="preserve"><path style="fill:#2D2D2D;" d="M-1.868-0.186L1.317,3l-3.185,3.184c-0.177,0.179-0.177,0.465,0,0.643l1.04,1.04c0.179,0.176,0.465,0.176,0.644,0L3,4.684l3.184,3.184c0.176,0.176,0.465,0.176,0.643,0l1.04-1.04c0.176-0.178,0.176-0.464,0-0.643L4.684,3l3.184-3.185c0.176-0.177,0.176-0.464,0-0.644l-1.04-1.04c-0.178-0.177-0.464-0.177-0.643,0L3,1.317l-3.185-3.185c-0.178-0.177-0.464-0.177-0.644,0l-1.04,1.04C-2.044-0.65-2.044-0.362-1.868-0.186z"/></svg></a></td>
					</tr>
					<?php } ?>
					<?php foreach ($vouchers as $voucher) { ?>
					<tr>
						<td class="image"></td>
						<td class="name"><?php echo $voucher['description']; ?></td>
						<td class="quantity">
							<input type="text" class="quant" value="<?php echo $product['quantity']; ?>" data-key="<?php echo $product['key']; ?>" /><a class="apply icon-refresh" href="#"></a>
						</td>
						<td class="total"><?php echo $voucher['amount']; ?></td>
						<td class="remove"><a href="#" title="<?php echo $button_remove; ?>" data-key="<?php echo $product['key']; ?>"><svg version="1.1" id="remove" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="9.999px" height="9.999px" viewBox="-2 -2 9.999 9.999" style="enable-background:new -2 -2 9.999 9.999;" xml:space="preserve"><path style="fill:#2D2D2D;" d="M-1.868-0.186L1.317,3l-3.185,3.184c-0.177,0.179-0.177,0.465,0,0.643l1.04,1.04c0.179,0.176,0.465,0.176,0.644,0L3,4.684l3.184,3.184c0.176,0.176,0.465,0.176,0.643,0l1.04-1.04c0.176-0.178,0.176-0.464,0-0.643L4.684,3l3.184-3.185c0.176-0.177,0.176-0.464,0-0.644l-1.04-1.04c-0.178-0.177-0.464-0.177-0.643,0L3,1.317l-3.185-3.185c-0.178-0.177-0.464-0.177-0.644,0l-1.04,1.04C-2.044-0.65-2.044-0.362-1.868-0.186z"/></svg></a></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<div class="mini-cart-total">
				<table>
					<?php foreach ($totals as $total) { ?>
					<tr>
						<td><b><?php echo $total['title']; ?>:</b></td>
						<td class="right"><?php echo $total['text']; ?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php } else { ?>
			<div class="fly_empty"><?php echo $text_empty; ?></div>
			<?php } ?>

		</div>

		<div id="foot_popup">
			<div  class="btn-group">
				<button onclick="location.href='<?php echo $cart; ?>';" class="btn <?php echo $pbutton; ?>"><?php echo $text_cart; ?></button>
				<button onclick="location.href='<?php echo $checkout; ?>';" class="btn <?php echo $pbutton; ?>"><?php echo $text_checkout; ?></button>
			</div>
		</div>
	</div>
<?php if($type == 'fly') { ?>
	<div id="fly_element" class="fly_<?php echo $position; ?>">
		<span><?php echo $text_items; ?></span>
	</div>
<?php } ?>
	<div id="fly_options">
		<h2>Доступные варианты</h2>
		<div id="content_opt"></div>
		<div id="foot_opt"><input id="fly_button" class="btn <?php echo $pbutton; ?>" type="button" value="Купить"></div>
	</div>
</div>
<img src="/image/cart/No_Image.jpg" alt="" id="no_image" />

<?php if($type == 'fly') { ?>
<style>
	<?php if($position == 'top_right'){ ?>
.fly_top_right{right:<?php echo $offset_x; ?>;top:<?php echo $offset_y; ?>;}
	<?php } elseif($position == 'bottom_right'){ ?>
.fly_bottom_right{right:<?php echo $offset_x; ?>;bottom:<?php echo $offset_y; ?>;}
	<?php } elseif($position == 'top_left'){ ?>
.fly_top_left{left:<?php echo $offset_x; ?>;top:<?php echo $offset_y; ?>;}
	<?php } elseif($position == 'bottom_left'){ ?>
.fly_bottom_left{left:<?php echo $offset_x; ?>;bottom:<?php echo $offset_y; ?>;}
	<?php } ?>
	
<?php if($postype == 'absolute'){ ?>
#container{position:relative;}
<?php } ?>

#fly_element{height:<?php echo $fheight; ?>;width:<?php echo $fwidth; ?>;background:url(/image/cart/<?php echo $image; ?>) no-repeat 50% 50%;position:<?php echo $postype; ?>;}
#fly_element  > span{color:#<?php echo $tcolor; ?>;font-size:<?php echo $tsize; ?>;line-height:<?php echo $tsize; ?>;margin:<?php echo $tmtop; ?> <?php echo $tmright; ?> <?php echo $tmbottom; ?> <?php echo $tmleft; ?>;}
@-moz-document url-prefix() {
#fly_element  > span{line-height:<?php echo $tsize - 1; ?>px;}
}
@media (min-resolution: .001dpcm) {
_:-o-prefocus, #fly_element  > span{line-height:<?php echo $tsize - 1; ?>px;}
}
</style>
<?php } ?>

<style>
#fly_options h2,#fly_popup h2{background-color:#<?php echo $head_bgp; ?>;border-bottom:1px solid #<?php echo $bhead_bgp; ?>;color:#<?php echo $chead_bgp; ?>;}
#close{fill:#<?php echo $close_bg; ?>;}
#remove{fill:#<?php echo $remove_bg; ?>;height:13px;}
#fly_content,#content_opt{background-color:#<?php echo $color_bgp; ?>;}
#fly_cart .mini-cart-info td,#fly_cart .mini-cart-total td,#content_opt{color:#<?php echo $color; ?>}
#fly_content td{border-color:#<?php echo $border; ?>}
#fly_options .name a,#simplemodal-container .name a{color:#<?php echo $color_a; ?>}
#foot_popup,#foot_opt{background-color:#<?php echo $color_fgp; ?>;border-color:#<?php echo $color_fbgp; ?>;}
.fly_empty{color:#<?php echo $empty; ?>;}
#simplemodal-overlay{background-color:#<?php echo $overlay; ?>;}
</style>


<script>
	$(window).load(function() {
		if($('.product-info').length != 0){
			var prod = true;
			if($('#tab-related').length !=0){
				var cart = $('.box-product').find('.button');	
				pagesList(cart);
			}
			prodPage();
		} else {
			var prod = false;
			if($('.wishlist-info').length !== 0){
				var cart = $('.action').find('img');
			} else if($('.compare-info').length !== 0){
				var cart = $('.compare-info').find('[type=button]');
			} else {
				var cart = $('.cart').find('[type=button]');	
			}		
			pagesList(cart);
		}
	});
	
	var itemImg = 0;
	var scrollColor = '#<?php echo $scroll; ?>';
	var opt = true
	var scroll = false;
	var wish = false;
	var comp = false;

	function loader(){
		<?php if($type == 'module') { ?>
			<?php if($bselect == 'yes') { ?>
				$('#fly_content').load('index.php?route=module/kw_flycart #fly_content > *');
			<?php } ?>
			$('#flymod_content').load('index.php?route=module/kw_flycart #flymod_content > *');
		<?php } else {?>
			$('#fly_content').load('index.php?route=module/kw_flycart #fly_content > *');
		<?php } ?>	
	}
	

	$('.mini-cart-info .remove > a,.mini-cart-info .remove > img').live('click', function(){
		var key = $(this).data('key');
		<?php if($type == 'module') { ?>
		(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=module/kw_flycart&remove='+key : $('#flymod_content').load('index.php?route=module/kw_flycart&remove='+key + ' #flymod_content > *'),$('#fly_element').load('index.php?route=module/kw_flycart&remove='+key + ' #fly_element > *');
			<?php if($bselect == 'yes') { ?>
				(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=module/kw_flycart&remove='+key : $('#fly_content').load('index.php?route=module/kw_flycart&remove='+key + ' #fly_content > *'),$('#fly_element').load('index.php?route=module/kw_flycart&remove='+key + ' #fly_element > *');
			<?php } ?>
		<?php } else {?>
		(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=module/kw_flycart&remove='+key : $('#fly_content').load('index.php?route=module/kw_flycart&remove='+key + ' #fly_content > *'),$('#fly_element').load('index.php?route=module/kw_flycart&remove='+key + ' #fly_element > *');
		<?php } ?>	
		(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove='+key : $('#cart').load('index.php?route=module/cart&remove='+key + ' #cart > *');
			return false;
	});
	
	
<?php if($postype != 'fixed' or $type != 'fly'){ ?>
	scroll = true;
<?php } ?>
	
<?php if($flytype == 'frame') { ?>
	itemImg = 0;
<?php } ?>

<?php if($topions == 'none') { ?>
	opt = false;
<?php } ?>

<?php if($flytype == 'item_img') { ?>
	itemImg = 1;
<?php } ?>

<?php if($flytype == 'image') { ?>
	itemImg = 2;
	var imgFly = '<?php echo $flyimage; ?>';
	$.extend($.animFlyCart.defaults, {
		borderColor:'transparent',
		borderWidth:0,
		animDuration:1500
	});
<?php } ?>

<?php if($flytype == 'none') { ?>
	itemImg = 3;
<?php } else { ?>
	$.extend($.animFlyCart.defaults, {
<?php if($frselect == 'yes') { ?>
		borderColor:'#<?php echo $color_f; ?>',
		borderWidth:<?php echo $size_f; ?>,
<?php } else { ?>
		borderColor:'transparent',
		borderWidth:0,	
<?php } ?>
		animDuration:<?php echo $speed; ?>,
		radius:'<?php echo $radius; ?>',
<?php if($rtselect == 'none') { ?>
		rotate:0
<?php } ?>
	});
<?php } ?>

<?php if($bselect == 'yes') { ?>
	var bselect = true;
<?php } else { ?>
	var bselect = false;
<?php } ?>

<?php if($type == 'fly') { ?>
	//$('#notification').hide();
<?php } ?>



</script>
<!--[if lt IE 9]> 
<style>
#simplemodal-container{border: 1px solid #777;}
#simplemodal-container a.simplemodal-close{background:url(catalog/view/javascript/kw_flycart/img/close.png) no-repeat;}
.remove a{background:url(catalog/view/javascript/kw_flycart/img/remove.png) no-repeat 0 0;display:block;width:6px;height:6px;margin-top:4px;}
</style>
<script type="text/javascript">var co = this.co = $.fn.colorbox; var cv; $.fn.colorbox = function(v) { cv = v; return; }; $(document).ready(function() { $.fn.colorbox = co; $('.colorbox').colorbox(cv) });</script>
<![endif]--> 