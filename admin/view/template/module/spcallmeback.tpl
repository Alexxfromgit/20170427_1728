<?php echo $header; ?>

<!--script id="is-feedback-widget" src="http://callmebackopencart.ideascale.com/userimages/accounts/91/914768/panel-25160-feedback-widget.js" defer="defer"></script-->

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if (isset($success) && $success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="$('[name=close]').val(0); $('#form').submit();" class="button"><?php echo $button_apply; ?></a><a onclick="$('[name=reset]').val(1); $('#form').submit();" class="button"><?php echo $button_reset; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <input type="hidden" value="1" name="close">
        <input type="hidden" value="0" name="reset">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $text_email; ?></td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" />
              <?php if ($error_email) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required"></span><?php echo $text_button_position; ?></td>
            <td><select name="button_position">
                  <?php foreach ($button_positions as $_button_position) { ?>
                  <?php if ($_button_position['id'] == $button_position) { ?>
                    <option value="<?php echo $_button_position['id']; ?>" selected="selected"><?php echo $_button_position['name']; ?></option>
                  <?php } else { ?>
                    <option value="<?php echo $_button_position['id']; ?>"><?php echo $_button_position['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>          
            </td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $text_button_caption; ?></td>
            <td><input type="text" name="button_caption" value="<?php echo $button_caption; ?>" />
              <?php if ($error_button_caption) { ?>
              <span class="error"><?php echo $error_button_caption; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $text_button_color; ?></td>
            <td><input type="text" name="button_color" value="<?php echo $button_color; ?>" />
              <?php if ($error_button_color) { ?>
              <span class="error"><?php echo $error_button_color; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $text_button_background; ?></td>
            <td><input type="text" name="button_background" value="<?php echo $button_background; ?>" />
              <?php if ($error_button_background) { ?>
              <span class="error"><?php echo $error_button_background; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $text_form_caption; ?></td>
            <td><input type="text" name="form_caption" value="<?php echo $form_caption; ?>" />
              <?php if ($error_form_caption) { ?>
              <span class="error"><?php echo $error_form_caption; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $text_form_subcaption; ?></td>
            <td><textarea name="form_subcaption" cols="60" rows="10"><?php echo $form_subcaption; ?></textarea>
              <?php if ($error_form_subcaption) { ?>
              <span class="error"><?php echo $error_form_subcaption; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $text_form_button_caption; ?></td>
            <td><input type="text" name="form_button_caption" value="<?php echo $form_button_caption; ?>" />
              <?php if ($error_form_button_caption) { ?>
              <span class="error"><?php echo $error_form_button_caption; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $text_error_missing_value; ?></td>
            <td><input type="text" name="error_missing_value" value="<?php echo $error_missing_value; ?>" />
              <?php if ($error_error_missing_value) { ?>
              <span class="error"><?php echo $error_error_missing_value; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td></td>
            <td><h1><?php echo $text_form_fields; ?></h1><?php echo $text_hiding_tips; ?>
            </td>
          </tr>
          <tr>
            <td style="border: 0; padding-bottom: 0"><span class="required">*</span> <?php echo $text_name_name; ?></td>
            <td style="border: 0; padding-bottom: 0"><input type="text" size="40" name="name_name" value="<?php echo $name_name; ?>" />
              <?php if ($error_name_name) { ?>
              <span class="error"><?php echo $error_name_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="padding-top: 0"></td>
            <td style="padding-top: 0"><?php echo $text_order; ?>:&nbsp;<input name="name_order" value="<?php echo $name_order; ?>" size="2" />
              &nbsp;<?php echo $text_required; ?>&nbsp;&nbsp;<input name="name_required" type="checkbox" <?php if ($name_required == 'on') echo 'checked'; ?> />
              <?php if ($error_name_order) { ?>
              <span class="error"><?php echo $error_name_order; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="border: 0; padding-bottom: 0"><span class="required">*</span> <?php echo $text_name_phone; ?></td>
            <td style="border: 0; padding-bottom: 0"><input type="text" size="40" name="name_phone" value="<?php echo $name_phone; ?>" />
              <?php if ($error_name_phone) { ?>
              <span class="error"><?php echo $error_name_phone; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="padding-top: 0"></td>
            <td style="padding-top: 0"><?php echo $text_order; ?>:&nbsp;<input name="phone_order" value="<?php echo $phone_order; ?>" size="2" />
              &nbsp;<?php echo $text_required; ?>&nbsp;&nbsp;<input name="phone_required" type="checkbox" <?php if ($phone_required == 'on') echo 'checked'; ?> />
              <?php if ($error_phone_order) { ?>
              <span class="error"><?php echo $error_phone_order; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="border: 0; padding-bottom: 0"><span class="required">*</span> <?php echo $text_name_time; ?></td>
            <td style="border: 0; padding-bottom: 0"><input type="text" size="40" name="name_time" value="<?php echo $name_time; ?>" />
              <?php if ($error_name_time) { ?>
              <span class="error"><?php echo $error_name_time; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="padding-top: 0"></td>
            <td style="padding-top: 0"><?php echo $text_time_from_label; ?>:&nbsp;<input name="time_from_label" value="<?php echo $time_from_label; ?>" size="10" />
              <?php echo $text_time_to_label; ?>:&nbsp;<input name="time_to_label" value="<?php echo $time_to_label; ?>" size="10" />
              <?php if ($error_time_order) { ?>
              <span class="error"><?php echo $error_time_order; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="padding-top: 0"></td>
            <td style="padding-top: 0"><?php echo $text_order; ?>:&nbsp;<input name="time_order" value="<?php echo $time_order; ?>" size="2" />
              &nbsp;<?php echo $text_required; ?>&nbsp;&nbsp;<input name="time_required" type="checkbox" <?php if ($time_required == 'on') echo 'checked'; ?> />
              <?php if ($error_time_order) { ?>
              <span class="error"><?php echo $error_time_order; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="border: 0; padding-bottom: 0"><span class="required">*</span> <?php echo $text_name_email; ?></td>
            <td style="border: 0; padding-bottom: 0"><input type="text" size="40" name="name_email" value="<?php echo $name_email; ?>" />
              <?php if ($error_name_email) { ?>
              <span class="error"><?php echo $error_name_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="padding-top: 0"></td>
            <td style="padding-top: 0"><?php echo $text_order; ?>:&nbsp;<input name="email_order" value="<?php echo $email_order; ?>" size="2" />
              &nbsp;<?php echo $text_required; ?>&nbsp;&nbsp;<input name="email_required" type="checkbox" <?php if ($email_required == 'on') echo 'checked'; ?> />
              <?php if ($error_email_order) { ?>
              <span class="error"><?php echo $error_email_order; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="border: 0; padding-bottom: 0"><span class="required">*</span> <?php echo $text_name_comments; ?></td>
            <td style="border: 0; padding-bottom: 0"><input type="text" size="40" name="name_comments" value="<?php echo $name_comments; ?>" />
              <?php if ($error_name_comments) { ?>
              <span class="error"><?php echo $error_name_comments; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="padding-top: 0"></td>
            <td style="padding-top: 0"><?php echo $text_order; ?>:&nbsp;<input name="comments_order" value="<?php echo $comments_order; ?>" size="2" />
              &nbsp;<?php echo $text_required; ?>&nbsp;&nbsp;<input name="comments_required" type="checkbox" <?php if ($comments_required == 'on') echo 'checked'; ?> />
              <?php if ($error_comments_order) { ?>
              <span class="error"><?php echo $error_comments_order; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="border: 0; padding-bottom: 0"><span class="required">*</span> <?php echo $text_name_additional; ?> 1</td>
            <td style="border: 0; padding-bottom: 0"><input type="text" size="40" name="name_additional1" value="<?php echo $name_additional1; ?>" />
              <?php if ($error_name_additional1) { ?>
              <span class="error"><?php echo $error_name_additional1; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="padding-top: 0"></td>
            <td style="padding-top: 0"><?php echo $text_order; ?>:&nbsp;<input name="additional1_order" value="<?php echo $additional1_order; ?>" size="2" />
              &nbsp;<?php echo $text_required; ?>&nbsp;&nbsp;<input name="additional1_required" type="checkbox" <?php if ($additional1_required == 'on') echo 'checked'; ?> />
              <?php if ($error_additional1_order) { ?>
              <span class="error"><?php echo $error_additional1_order; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="border: 0; padding-bottom: 0"><span class="required">*</span> <?php echo $text_name_additional; ?> 2</td>
            <td style="border: 0; padding-bottom: 0"><input type="text" size="40" name="name_additional2" value="<?php echo $name_additional2; ?>" />
              <?php if ($error_name_additional2) { ?>
              <span class="error"><?php echo $error_name_additional2; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="padding-top: 0"></td>
            <td style="padding-top: 0"><?php echo $text_order; ?>:&nbsp;<input name="additional2_order" value="<?php echo $additional2_order; ?>" size="2" />
              &nbsp;<?php echo $text_required; ?>&nbsp;&nbsp;<input name="additional2_required" type="checkbox" <?php if ($additional2_required == 'on') echo 'checked'; ?> />
              <?php if ($error_additional2_order) { ?>
              <span class="error"><?php echo $error_additional2_order; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td style="border: 0; padding-bottom: 0"><span class="required"></span> <?php echo $text_name_use_captcha; ?></td>
            <td style="border: 0; padding-bottom: 0"><input type="checkbox" name="use_captcha" <?php if ($use_captcha == 'on'): ?>checked="checked"<? endif ?> />
            </td>
          </tr>
        </table>
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
          <tbody id="module-row<?php echo $module_row; ?>">
            <tr>
              <td class="left"><select name="spcallmeback_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="spcallmeback_module[<?php echo $module_row; ?>][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $text_content_top; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $text_column_left; ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $text_column_right; ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="spcallmeback_module[<?php echo $module_row; ?>][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="spcallmeback_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
              <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $module_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="spcallmeback_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="spcallmeback_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="spcallmeback_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="spcallmeback_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script> 
<?php echo $footer; ?>