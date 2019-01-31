<link rel='stylesheet' href='https://apimgmtstorelinmtekiynqw.blob.core.windows.net/content/MediaLibrary/Widget/Tracking/styles/tracking.css' />
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($orders) { ?>
  <?php foreach ($orders as $order) { ?>
  <div class="order-list">
    <div class="order-id"><b><?php echo $text_order_id; ?></b> #<?php echo $order['order_id']; ?></div>
    <div class="order-status"><b><?php echo $text_status; ?></b> <?php echo $order['status']; ?></div>
    <div class="order-content">
      <div><b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br />
        <b><?php echo $text_products; ?></b> <?php echo $order['products']; ?></div>
      <div><b><?php echo $text_customer; ?></b> <?php echo $order['name']; ?><br />
        <b><?php echo $text_total; ?></b> <?php echo $order['total']; ?></div>
      <div class="order-info"><a href="<?php echo $order['href']; ?>"><img src="catalog/view/theme/default/image/info.png" alt="<?php echo $button_view; ?>" title="<?php echo $button_view; ?>" /></a>&nbsp;&nbsp;<a href="<?php echo $order['reorder']; ?>"><img src="catalog/view/theme/default/image/reorder.png" alt="<?php echo $button_reorder; ?>" title="<?php echo $button_reorder; ?>" /></a></div>
    </div>
  </div>
  <?php } ?>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php } ?>
	<div id="np-tracking" class="np-widget-hz np-w-br-5" style="min-height: 76px; width: 534px;"> 
		<div id="np-first-state"> 
			<div id="np-tracking-logo">
			</div> 
				<div id="np-title">
					<div class="np-h1">ВІДСТЕЖЕННЯ<br>ПОСИЛОК</div>
				</div> 
					<div id="np-input-container"> 
						<div id="np-clear-input">
						</div> 
							<input id="np-user-input" type="text" name="number" placeholder="Номер посилки"> 
					</div> 
						<div id="np-warning-message">
						</div> 
							<button id="np-submit-tracking" type="button"> 
							<span id="np-text-button-tracking">ВІДСТЕЖИТИ</span> 
							<div id="np-load-image-tracking">
							</div> 
							</button> 
						<div id="np-error-status">
						</div> 
		</div> 
		<div id="np-second-state"> 
			<div id="np-status-icon">
			</div> 
				<div id="np-status-message">
				</div> 
					<div class="np-track-mini-logo"> 
						<div class="np-line-right">
						</div> 
							<div class="np-line-left">
							</div> 
					</div> 
						<a href="#" id="np-more">Детальніше на сайті</a>
							<div id="np-return-button"> <span>Інша посилка</span> 
							</div> 
		</div> 
	</div>
	<script type='text/javascript' src='https://apimgmtstorelinmtekiynqw.blob.core.windows.net/content/MediaLibrary/Widget/Tracking/dist/track.min.js'></script>
	
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>