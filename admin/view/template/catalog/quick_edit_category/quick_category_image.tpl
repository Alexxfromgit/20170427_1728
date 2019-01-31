<div>
<table class="form">
  <tr>
	<td style="width: 50px;">
	  <?php if ($category_image['image']) { ?>
		<img src="<?php echo $category_image; ?>" />
	  <?php } else { ?>
		<img src="<?php echo $category_no_image; ?>" />
	  <?php } ?>
	</td>
    <td><b><?php echo $category_name; ?></b></td>
  </tr>
</table>
<form action="POST" id="image-form">
  <table class="form">
	<tr>
      <td><?php echo $entry_image; ?></td>
      <td valign="top">
	    <div class="image">
		  <img src="<?php echo $thumb; ?>" alt="" id="thumb" />
          <input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
          <br />
          <a onclick="image_upload('image', 'thumb');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
		</div>
	  </td>
    </tr>
  </table>
</form>
</div>
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