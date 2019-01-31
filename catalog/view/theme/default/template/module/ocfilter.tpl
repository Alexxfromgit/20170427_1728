<?php if ($options) { ?>
<div class="box ocfilter ocfilter-<?php echo $position; ?>" id="ocfilter-<?php echo $module; ?>">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="relative-wrapper">
			<div class="button-float" id="button-float-<?php echo $module; ?>"><a href="#" id="button-submit-<?php echo $module; ?>"><?php echo $button_select; ?></a></div>
		</div>
    <form action="">
      <?php if ($selecteds) { # Selected options ?>
      <div class="selected-options">
        <?php foreach ($selecteds as $option) { ?>
        <div class="ocfilter-option">
          <span><?php echo $option['name']; ?>:</span>
          <?php foreach ($option['values'] as $value) { ?>
          <a rel="nofollow" href="<?php echo $value['href']; ?>" class="cancel-small"><span>&times;</span><?php echo $value['name']; ?></a>
          <?php } ?>
        </div>
        <?php } ?>
				<?php $count = count($selecteds); $selected = $selecteds; $first = array_shift($selected); ?>
        <?php if ($count > 1 || count($first['values']) > 1) { ?>
        <a rel="nofollow" href="<?php echo $link; ?>" class="cancel-large"><span>&times;</span><?php echo $text_cancel_all; ?></a>
        <?php } ?>
      </div>
      <?php } ?>

      <?php if ($show_price) { # Price filtering ?>
      <div class="ocfilter-option">
        <div class="option-name">
          <?php if ($price_type == 'links' && $price_links) { ?>
					<?php echo $text_price; ?>
					<?php } else { ?>
					<?php echo $text_price; ?>&nbsp;<?php echo $symbol_left; ?><span id="price-from-<?php echo $module; ?>"><?php echo $min_price_get; ?></span>&nbsp;-&nbsp;<span id="price-to-<?php echo $module; ?>"><?php echo $max_price_get; ?></span><?php echo $symbol_right; ?>
					<?php } ?>
				</div>

        <?php if ($manual_price) { ?>
				<div class="fields-price" id="fields-price-<?php echo $module; ?>">
					<dd>&nbsp;</dd>
					<?php echo $symbol_left; ?>&nbsp;<input name="price[min]" value="<?php echo $min_price_get; ?>" type="text" size="6" id="min-price-value-<?php echo $module; ?>" class="enabled" />&nbsp;-&nbsp;<input name="price[max]" value="<?php echo $max_price_get; ?>" type="text" size="6" id="max-price-value-<?php echo $module; ?>" class="enabled" />&nbsp;<?php echo $symbol_right; ?>
				</div>
				<?php } ?>

        <div class="option-values">
          <?php if ($price_type == 'slide' || $price_type == 'links-slide') { ?>
					<div id="scale-price-<?php echo $module; ?>" class="scale scale-<?php echo $module; ?> ocf-target">
						<div class="trackbar" onSelectStart="return false;">
							<div class="handler-block left-block"><div class="trackbar-handler left-handler" onDragStart="return false;"></div></div>
							<div class="handler-block right-block"><div class="trackbar-handler right-handler" onDragStart="return false;"></div></div>
            </div>
					</div>
          <?php } ?>

					<?php if ($price_type == 'links' && $price_links) { ?>
					<div class="price-links">
						<?php foreach ($price_links as $key => $item) { ?>
						<?php if ($item['count']) { ?>
						<a href="<?php echo $item['href']; ?>"<?php echo ($item['selected'] ? ' class="selected"' : ''); ?>><?php echo $symbol_left; ?><?php echo $item['from']; ?>&nbsp;-&nbsp;<?php echo $item['to']; ?><?php echo $symbol_right; ?>&nbsp;(<?php echo $item['count']; ?>)</a>
            <?php } else { ?>
						<span class="disabled"><?php echo $symbol_left; ?><?php echo $item['from']; ?>&nbsp;-&nbsp;<?php echo $item['to']; ?><?php echo $symbol_right; ?>&nbsp;(<?php echo $item['count']; ?>)</span>
            <?php } ?>
						<?php } ?>
					</div>
					<?php } ?>

					<?php if ($diagram) { ?>
          <div class="price-diagram">
						<div class="diagram-field">
							<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="40px">
								<?php foreach ($diagram['circles'] as $circle) { ?>
								<?php if ($circle['count']) { ?>
								<circle cx="<?php echo $circle['x']; ?>" cy="<?php echo $circle['y']; ?>" r="1" fill="#037a9c" stroke="#037a9c" stroke-width="1" />
								<?php } else { ?>
								<circle cx="<?php echo $circle['x']; ?>" cy="<?php echo $circle['y']; ?>" r="1" fill="#BF0000" stroke="#BF0000" stroke-width="1" />
								<?php } ?>
								<?php } ?>
								<path fill="#037a9c" stroke="#037a9c" d="<?php echo $diagram['path']; ?>" stroke-width="1" opacity="0.25" stroke-opacity="1" />
							</svg>
						</div>
          </div>
					<?php } ?>

					<?php if ($price_type == 'links-slide' && $price_links) { ?>
					<div class="trackbar-scale">
						<?php foreach ($price_links as $key => $item) { ?>
						<?php if ($item['selected']) { ?>
						<div style="width:<?php echo $item['width']; ?>%;"<?php echo ($item['last'] ? ' class="last"' : ''); ?>>
							<span class="from" style="margin-left:-<?php echo $item['strlen']*2.5; ?>px;"><?php echo $item['from']; ?></span>
							<?php if ($item['last']) { ?>
							<span class="to" style="margin-right:-<?php echo $item['strlen']*2; ?>px;"><?php echo $item['to']; ?></span>
							<?php } ?>
						</div>
						<?php } else { ?>
						<a href="<?php echo $item['count'] ? $item['href'] : '#'; ?>" style="width:<?php echo $item['width']; ?>%;"<?php echo ($item['last'] ? ' class="last"' : ''); ?><?php echo !$item['count'] ? ' onclick="return false;"' : ''; ?>>
							<span class="from" style="margin-left:-<?php echo $item['strlen']*2.5; ?>px;"><?php echo $item['from']; ?></span>
							<?php if ($item['last']) { ?>
							<span class="to" style="margin-right:-<?php echo $item['strlen']*2; ?>px;"><?php echo $item['to']; ?></span>
							<?php } ?>
						</a>
						<?php } ?>
						<?php } ?>
					</div>
					<?php } ?>
        </div>
      </div>
      <?php } # Price filtering end ?>

      <?php foreach ($options as $option) { ?>

			<?php if ($show_options_limit && $show_options_limit == $option['index'] - 1) { ?>
			<?php if ($show_options) { ?>
			<a class="show-hidden-options active" title="<?php echo $text_hide; ?>"><span></span></a>
      <div id="hidden-options-<?php echo $module; ?>" class="hidden-options visible">
			<?php } else { ?>
			<a class="show-hidden-options" title="<?php echo $text_show_all; ?>"><span></span></a>
      <div id="hidden-options-<?php echo $module; ?>" class="hidden-options">
      <?php } ?>
			<?php } ?>

				<div class="ocfilter-option" id="option-<?php echo $option['option_id'] . $module; ?>">
	        <div class="option-name">
	          <?php echo $option['name']; ?>

						<?php if ($option['type'] == 'slide' || $option['type'] == 'slide_dual') { ?>
            <span id="left-value-<?php echo $option['option_id'] . $module; ?>"><?php echo $option['slide_value_min_get']; ?></span>
						<?php if ($option['type'] == 'slide_dual') { ?>
						-&nbsp;<span id="right-value-<?php echo $option['option_id'] . $module; ?>""><?php echo $option['slide_value_max_get']; ?></span>
						<?php } ?>
            <?php echo $option['postfix']; ?>
            <?php } ?>

            <?php if ($option['description']) { ?>
						<b>i</b>
						<?php } ?>
	        </div>

	        <div class="option-values">
            <?php if ($option['selectbox'] && ($option['type'] == 'radio' || $option['type'] == 'checkbox')) { # Selectbox wrapper start ?>
            <div class="ocfilter-selectbox<?php echo (isset($selecteds[$option['option_id']]) ? ' selected' : ''); ?>">
              <div class="selecteds ocf-target">
								<?php if (isset($selecteds[$option['option_id']])) { ?>
								<?php foreach ($selecteds[$option['option_id']]['values'] as $value) { ?>
                <span id="sb-v-<?php echo $value['id']; ?>"><?php echo $value['name']; ?></span>
								<?php } ?>
              	<?php } else { ?>
              	<i><?php echo $text_any; ?></i>
              	<?php } ?>
              </div>
							<div class="selectbox-values">
            <?php } ?>

						<?php if ($option['type'] == 'select') { # Select type start ?>

						<select name="option[<?php echo $option['option_id']; ?>]" class="ocf-target<?php echo ($option['selected'] ? ' selected' : ''); ?>">
							<?php foreach ($option['values'] as $value) { ?>
	            <?php if ($value['selected']) { ?>
	            <option value="<?php echo $value['params']; ?>" id="v-<?php echo $value['id']; ?>" selected="selected"><?php echo $value['name']; ?></option>
	            <?php } elseif ($value['count']) { ?>
	            <option value="<?php echo $value['params']; ?>" id="v-<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
	            <?php } else { ?>
							<option value="<?php echo $value['params']; ?>" id="v-<?php echo $value['id']; ?>" disabled="disabled"><?php echo $value['name']; ?></option>
							<?php } ?>
	            <?php } ?>
	          </select>

            <?php } elseif ($option['type'] == 'slide' || $option['type'] == 'slide_dual') { # Slide type start ?>

						<div id="scale-<?php echo $option['option_id'] . $module; ?>" class="scale scale-<?php echo $module; ?> ocf-target">
							<div class="trackbar" onSelectStart="return false;">
								<div class="handler-block left-block"><div class="trackbar-handler left-handler" onDragStart="return false;"></div></div>
								<div class="handler-block right-block"><div class="trackbar-handler right-handler" onDragStart="return false;"></div></div>
							</div>
						</div>

	          <?php } elseif ($option['type'] == 'radio' || $option['type'] == 'checkbox') { # Radio and Checkbox types start ?>

	          <?php foreach ($option['values'] as $key => $value) { ?>

						<?php if ($show_values_limit && $show_values_limit == $key) { ?>
						<a class="show-hidden-values" title="<?php echo $text_show_all; ?>"><?php echo $text_show_all; ?></a>
			      <div class="hidden-values">
						<?php } ?>

	            <?php if ($option['color']) { ?>
							<div class="color" style="background-color: #<?php echo $value['color']; ?>;"></div>
							<?php } ?>

	            <?php if ($option['image']) { ?>
							<div class="image" style="background-image: url(<?php echo $value['image']; ?>);"></div>
							<?php } ?>

							<?php if ($value['selected']) { ?>
		          <label id="v-<?php echo $value['id']; ?>" class="selected"><input type="<?php echo $option['type']; ?>" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" checked="checked" class="ocf-target" /><?php echo $value['name']; ?><?php if ($show_counter) { ?><small></small><?php } ?></label>
							<?php } elseif ($value['count']) { ?>
		          <label id="v-<?php echo $value['id']; ?>"><input type="<?php echo $option['type']; ?>" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" class="ocf-target" /><?php echo $value['name']; ?><?php if ($show_counter) { ?><small><?php echo $value['count']; ?></small><?php } ?></label>
							<?php } else { ?>
		          <label id="v-<?php echo $value['id']; ?>" class="disabled"><input type="<?php echo $option['type']; ?>" name="option[<?php echo $option['option_id']; ?>]" value="<?php echo $value['params']; ?>" disabled="disabled" class="ocf-target" /><?php echo $value['name']; ?><?php if ($show_counter) { ?><small>0</small><?php } ?></label>
		          <?php } ?>

            <?php if ($show_values_limit && $show_values_limit < count($option['values']) && $key + 1 == count($option['values'])) { ?>
      			</div>
						<?php } ?>

	          <?php } ?>

	          <?php } # End type switcher ?>

            <?php if ($option['selectbox'] && ($option['type'] == 'radio' || $option['type'] == 'checkbox')) { # Selectbox wrapper end ?>
             	</div>
						</div>
            <?php } ?>
	        </div>

	        <?php if ($option['description']) { ?>
	        <div class="option-description"><div><?php echo $option['description']; ?></div><dd></dd></div>
	        <?php } ?>
	      </div>

      <?php if ($show_options_limit && $option['index'] == count($options)) { # Closing the hidden options box ?>
			</div>
			<?php } ?>

    	<?php } # End "foreach $options" ?>
    </form>
  </div>
  <?php if ($use_animation) { ?>
  <style type="text/css">
  /*=Transition */
  .ocfilter .fields-price,
  .ocfilter .button-float,
  .ocfilter-option .option-description
  {
  	-moz-transition: all 300ms ease 0s;
    -webkit-transition: all 300ms ease 0s;
    -o-transition: all 300ms ease 0s;
    -ms-transition: all 300ms ease 0s;
    transition: all 300ms ease 0s;
  }
  </style>
  <?php } ?>
	<script type="text/javascript"><!--
		var options = {
      element: {
			  priceScale     : $('#scale-price-<?php echo $module; ?>'), // Slide scale element
			  priceMin       : $('#min-price-value-<?php echo $module; ?>'),  // Price min field
			  priceMax       : $('#max-price-value-<?php echo $module; ?>'),  // Price max field
			  priceFrom      : $('#price-from-<?php echo $module; ?>'),// Price from elemenet
			  priceTo        : $('#price-to-<?php echo $module; ?>'),  // Price to elemenet
			  submitButton   : $('#button-submit-<?php echo $module; ?>'),
			  floatButton    : $('#button-float-<?php echo $module; ?>'),
        scales         : $('.scale-<?php echo $module; ?>')
			},
      php: {
				module       : <?php echo $module; ?>,
				minPrice     : <?php echo $min_price; ?>,
				maxPrice     : <?php echo $max_price; ?>,
				minPriceValue: <?php echo $min_price_get; ?>,
				maxPriceValue: <?php echo $max_price_get; ?>,
		    showButton   : <?php echo $show_button; ?>,
		    showPrice    : <?php echo $show_price; ?>,
		    showCounter  : <?php echo $show_counter; ?>,
				manualPrice  : <?php echo $manual_price; ?>,
		    total        : <?php echo $total; ?>,
        link         : '<?php echo $link; ?>',
		    path         : '<?php echo $path; ?>',
		    params       : '<?php echo $params; ?>',
		    index        : '<?php echo $index; ?>'
		  },
      text: {
		    show_all: '<?php echo $text_show_all; ?>',
		    hide    : '<?php echo $text_hide; ?>',
		    load    : '<?php echo $text_load; ?>',
				any     : '<?php echo $text_any; ?>',
		    select  : '<?php echo $button_select; ?>'
		  },
			sliders      : []
		};

		<?php foreach ($options as $option_id => $option) { ?>
		<?php if ($option['type'] == 'slide' || $option['type'] == 'slide_dual') { ?>
		options.sliders.push({
      callback: {
        option_id : <?php echo $option_id; ?>,
        left      : $('#left-value-<?php echo $option_id . $module; ?>'),
        right     : $('#right-value-<?php echo $option_id . $module; ?>')
      },
			dual		: <?php echo $option['type'] == 'slide_dual' ? 'true' : 'false'; ?>,
      fixed   : <?php echo (int)max(strlen(substr(strstr($option['slide_value_min'], '.'), 1)), strlen(substr(strstr($option['slide_value_max'], '.'), 1))); ?>,
      scale		: $('#scale-<?php echo $option_id . $module; ?>'),
			min			: <?php echo $option['slide_value_min']; ?>,
			minVal	: <?php echo $option['slide_value_min_get']; ?>,
			max			: <?php echo $option['slide_value_max']; ?>,
			maxVal	: <?php echo ($option['type'] == 'slide_dual' ? $option['slide_value_max_get'] : $option['slide_value_max']); ?>
		});
		<?php } ?>
		<?php } ?>

    $('#ocfilter-<?php echo $module; ?>').ocfilter(options);
	//--></script>
</div>
<?php } ?>