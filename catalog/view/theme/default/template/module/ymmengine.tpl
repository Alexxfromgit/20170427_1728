<option value=""><?php echo $text_please_select; ?></option>
<?php foreach ($engines as $engine) { ?>
<option value="<?php echo $engine['id']; ?>"><?php echo $engine['engine']; ?></option>
<?php } ?>