<?php echo $header; ?>
<div id="content">
<div class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
  <?php } ?>
</div>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left">KeyWord(s) or Sentence </td>
            <td class="left">Link / ToolTip Text</td>
            <td class="left">Target</td>
            <td class="left">Tooltip</td>
			<td></td>
          </tr>
        </thead>
        <?php $link_row = 0; ?>
        <?php if (isset($autolinks)) {foreach ($autolinks as $autolink) { ?>
        <tbody id="link-row<?php echo $link_row; ?>">
          <tr>
            <td class="left"><input type="text" name="autolinks[<?php echo $link_row; ?>][keyword]" value="<?php echo $autolink['keyword']; ?>" size="70" /></td>
            <td class="left"><input type="text" name="autolinks[<?php echo $link_row; ?>][link]" value="<?php echo $autolink['link']; ?>" size="100" /></td>
            <td class="left">
				<select name="autolinks[<?php echo $link_row; ?>][target]">
                                <option value="" <?php if ($autolink['target'] == '') echo 'selected="selected"'; ?>></option>
                                <option value="_blank" <?php if ($autolink['target'] == '_blank') echo 'selected="selected"'; ?>>_blank</option>
                                <option value="_self" <?php if ($autolink['target'] == '_self') echo 'selected="selected"'; ?>>_self</option>
                                <option value="_parent" <?php if ($autolink['target'] == '_parent') echo 'selected="selected"'; ?>>_parent</option>
                                <option value="_top" <?php if ($autolink['target'] == '_top') echo 'selected="selected"'; ?>>_top</option>
                </select></td>
			<td class="left">
				<?php if (isset($autolink['tooltip'])) { ?>
                <input type="checkbox" name="autolinks[<?php echo $link_row; ?>][tooltip]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="autolinks[<?php echo $link_row; ?>][tooltip]" value="1" />
                <?php } ?></td>
            <td class="left"><a onclick="$('#link-row<?php echo $link_row; ?>').remove();" class="button">Remove</a></td>
          </tr>
        </tbody>
        <?php $link_row++; ?>
        <?php } }?>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="left"><a onclick="addModule();" class="button">Add Link</a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var link_row = <?php echo $link_row; ?>;

function addModule() {	
	html  = '<tbody id="link-row' + link_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="autolinks[' + link_row + '][keyword]" value="" size="70" /></td>';
	html += '    <td class="left"><input type="text" name="autolinks[' + link_row + '][link]" value="http://" size="100" /></td>';
	html += '     <td class="left">';
	html += '			<select name="autolinks[' + link_row + '][target]">';
    html += '                            <option value=""></option>';
    html += '                            <option value="_blank">_blank</option>';
    html += '                            <option value="_self">_self</option>';
    html += '                            <option value="_parent">_parent</option>';
    html += '                            <option value="_top">_top</option>';
    html += '            </select></td>';
    html += '    <td class="left"><input type="checkbox" name="autolinks[' + link_row + '][tooltip]" value="1" /></td>';
	html += '    <td class="left"><a onclick="$(\'#link-row' + link_row + '\').remove();" class="button">Remove</a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	link_row++;
}
//--></script>
<?php echo $footer; ?>