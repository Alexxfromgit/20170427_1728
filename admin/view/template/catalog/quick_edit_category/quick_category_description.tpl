<div>
<form action="POST" id="description-form">
  <table class="form">
	<tr>
      <td><?php echo $entry_keyword; ?></td>
      <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" size="103" /></td>
    </tr>
  </table>
  <div id="languages" class="htabs">
    <?php foreach ($languages as $language) { ?>
      <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
    <?php } ?>
  </div>
  <?php foreach ($languages as $language) { ?>
    <div id="language<?php echo $language['language_id']; ?>">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_name; ?></td>
          <td>
		    <input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" size="103" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" />
            <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
            <?php } ?>
		  </td>
        </tr>
        <tr>
          <td><?php echo $entry_meta_keyword; ?></td>
          <td><input type="text" name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" size="103" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?>" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_meta_description; ?></td>
          <td><textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" cols="100" rows="2"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
        </tr>
        <tr>
          <td><?php echo $entry_description; ?></td>
          <td><textarea name="category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea></td>
        </tr>
      </table>
    </div>
  <?php } ?>
</form>
</div>
<script type="text/javascript"><!--	
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$('#languages a').tabs(); 
//--></script> 