<div>
  <table class="form">
	<tr>
	  <td style="width: 50px;">
		<?php if ($product_image['image']) { ?>
		  <img src="<?php echo $product_image; ?>" />
		<?php } else { ?>
		  <img src="<?php echo $product_no_image; ?>" />
	    <?php } ?>
	  </td>
      <td><b><?php echo $product_name; ?></b></td>
    </tr>
  </table>
  <form action="POST" id="images-form">
    <table id="images" class="list">
      <thead>
        <tr>
          <td class="left"><?php echo $entry_image; ?></td>
          <td class="right"><?php echo $entry_sort_order; ?></td>
          <td></td>
        </tr>
      </thead>
      <?php $image_row = 0; ?>
      <?php foreach ($product_images as $product_image) { ?>
        <tbody id="image-row<?php echo $image_row; ?>">
          <tr>
            <td class="left">
			  <div class="image">
			    <img src="<?php echo $product_image['thumb']; ?>" alt="" id="thumb<?php echo $image_row; ?>" />
                <input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>" />
                <br />
                <a onclick="image_upload('image<?php echo $image_row; ?>', 'thumb<?php echo $image_row; ?>');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb<?php echo $image_row; ?>').attr('src', '<?php echo $no_image; ?>'); $('#image<?php echo $image_row; ?>').attr('value', '');"><?php echo $text_clear; ?></a>
			  </div>
			</td>
            <td class="right"><input type="text" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" size="2" /></td>
            <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
          </tr>
        </tbody>
        <?php $image_row++; ?>
      <?php } ?>
      <tfoot>
        <tr>
          <td colspan="2"></td>
          <td class="left"><a onclick="addImage();" class="button"><?php echo $button_add_image; ?></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>

<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><div class="image"><img src="<?php echo $no_image; ?>" alt="" id="thumb' + image_row + '" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="image' + image_row + '" /><br /><a onclick="image_upload(\'image' + image_row + '\', \'thumb' + image_row + '\');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$(\'#thumb' + image_row + '\').attr(\'src\', \'<?php echo $no_image; ?>\'); $(\'#image' + image_row + '\').attr(\'value\', \'\');"><?php echo $text_clear; ?></a></div></td>';
	html += '    <td class="right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" size="2" /></td>';
	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#images tfoot').before(html);
	
	image_row++;
	
	<?php if ($this->config->get('config_clicking_image') == 1) { ?>
	$('.image').click(function(){
		var job = $(this).find("a:contains('<?php echo $this->language->get('text_browse'); ?>')").attr('onclick');
		if (job.length != 0) eval(job);
	});
	$('.image').hover(function(){
		var link = $(this).find("a:contains('<?php echo $this->language->get('text_browse'); ?>')");
		if (link.length != 0) {
			$(this).css('cursor', 'pointer');
		}
	});
	<?php } ?> 
}
//--></script>
<?php if ($this->config->get('config_clicking_image') == 1) { ?>
<script type="text/javascript"><!--
$('.image').click(function(){
	var job = $(this).find("a:contains('<?php echo $this->language->get('text_browse'); ?>')").attr('onclick');
	if (job.length != 0) eval(job);
});
$('.image').hover(function(){
	var link = $(this).find("a:contains('<?php echo $this->language->get('text_browse'); ?>')");
	if (link.length != 0) {
		$(this).css('cursor', 'pointer');
	}
});
//--></script>
<?php } ?> 