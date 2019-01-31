<div class="box">
<div class="box-heading"><?php echo $heading_title; ?></div>
<div class="box-content">
<?php if (isset($position) && $position == 'column'){ ?>
<style type="text/css">
#scroller_<?php echo $module; ?> .jcarousel-skin-opencart .jcarousel-container-horizontal  {
padding: 0px 0px;
}
</style>	
<?php } ?>

					
<div id="scroller_<?php echo $module; ?>" class="scroller">
	<ul class="jcarousel-skin-opencart scroller">
		<?php foreach ($products as $product) { ?>
			<li>
				<div><?php if ($product['thumb']) { ?>
						<div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
					<?php } ?>
					<?php if ($show_title =='1') { ?>
						<div class="name">
						<a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
						</div>
					<?php } ?>
					<?php if ($show_price =='1') { ?>
						<?php if ($product['price']) { ?>
							<div class="price">
							<?php if (!$product['special']) { ?>
								<?php echo $product['price']; ?>
							<?php } else { ?>
								<span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
							<?php } ?>
							</div>
						<?php } ?>
					<?php } ?>
					<?php if (($product['rating']) && ($show_rate =='1')) { ?>
						<div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $product['rating']; ?>.png" alt="<?php echo $product['reviews']; ?>" />
						</br><?php echo $product['reviews']; ?>
						</div>
					<?php } ?>
					
					<?php if ($show_cart =='1') { ?>
						<div class="cart"><a class="button" onclick="addToCart('<?php echo $product['id']; ?>');"><span><?php echo $button_cart; ?></span></a>
						</div>
					<?php } ?>
				</div>		
			</li>
		<?php } ?>
	</ul>
</div>
<script type="text/javascript">

jQuery.easing['Effect']=function(p,t,b,c,d){
t /= d;
	t--;
	return -c * (t*t*t*t - 1) + b;
};

</script>


<script type="text/javascript"><!--
function mycarousel_initCallback(carousel)
{
	<?php if ($disableauto) {?>
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });
	<?php } ?>
	
	<?php if ($hoverpause) {?>
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });<?php } ?>
};
//--></script>

<script type="text/javascript"><!--
$('#scroller_<?php echo $module; ?> ul').jcarousel({
vertical: false,
initCallback: mycarousel_initCallback,
visible: <?php echo $visible; ?>,
scroll: <?php echo $scroll; ?>,
auto: <?php echo $autoscroll; ?>,
//easing: 'Effect',
animation: <?php echo $animationspeed; ?>,
<?php echo $type; ?>
});
//--></script>
</div>
</div>