<option value=""><?php echo $text_please_select; ?></option>
<?php foreach ($models as $model) { ?>
<option value="<?php echo $model['id']; ?>"><?php echo $model['model']; ?></option>
<?php } ?>