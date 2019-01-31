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
<div class="box">
  <div class="heading">
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
            <td><?php echo $entry_universal; ?></td>
            <td>
                <select name="ymmfilter_uni_default">
                <?php if ($ymmfilter_uni_default) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select>
            </td>
        </tr>
        <tr>
          <td><?php echo $entry_model; ?></td>
          <td>
            <select name="ymmfilter_model">
                <?php if ($ymmfilter_model) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_engine; ?></td>
          <td>
            <select name="ymmfilter_engine">
                <?php if ($ymmfilter_engine) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_year; ?></td>
          <td>
            <select name="ymmfilter_year">
                <?php if ($ymmfilter_year) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_remember; ?></td>
          <td>
            <select name="ymmfilter_remember">
                <?php if ($ymmfilter_remember) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_search_universal; ?></td>
          <td>
            <select name="ymmfilter_search_universal">
                <?php if ($ymmfilter_search_universal) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
            <td><?php echo $entry_year_sort; ?></td>
            <td>
                <select name="ymmfilter_year_sort">
                <?php if($ymmfilter_year_sort == 'desc') { ?>
                <option value="asc">Ascending</option>
                <option value="desc" selected="selected">Descending</option>
                <?php } else { ?>
                <option value="asc" selected="selected">Ascending</option>
                <option value="desc">Descending</option>
                <?php } ?>
                </select>
            </td>
        </tr>
      </table>
      <table id="module" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_layout; ?></td>
            <td class="left"><?php echo $entry_destination; ?></td>
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
            <td class="left"><select name="ymmfilter_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
            <td>
                <select name="ymmfilter_module[<?php echo $module_row; ?>][destination]">
                    <?php if (isset($module['destination']) && $module['destination'] == 'home') { ?>
                    <option value="reload"><?php echo $text_destination_reload; ?></option>
                    <option value="home" selected="selected"><?php echo $text_destination_home; ?></option>
                    <option value="search"><?php echo $text_destination_search; ?></option>
                    <?php } elseif (isset($module['destination']) && $module['destination'] == 'search') { ?>
                    <option value="reload"><?php echo $text_destination_reload; ?></option>
                    <option value="home"><?php echo $text_destination_home; ?></option>
                    <option value="search" selected="selected"><?php echo $text_destination_search; ?></option>
                    <?php } else { ?>
                    <option value="reload" selected="selected"><?php echo $text_destination_reload; ?></option>
                    <option value="home"><?php echo $text_destination_home; ?></option>
                    <option value="search"><?php echo $text_destination_search; ?></option> 
                    <?php } ?>
                </select>
            </td>
            <td class="left"><select name="ymmfilter_module[<?php echo $module_row; ?>][position]">
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
              </select>
            </td>
            <td class="left"><select name="ymmfilter_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </td>
            <td class="right">
                <input type="text" name="ymmfilter_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" />
            </td>
            <td class="left">
                <a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button">
                    <span><?php echo $button_remove; ?></span>
                </a>
            </td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="5"></td>
            <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
var module_row = <?php echo $module_row; ?>;

function addModule() {  
    html  = '<tbody id="module-row' + module_row + '">';
    html += '  <tr>';
    html += '    <td class="left"><select name="ymmfilter_module[' + module_row + '][layout_id]">';
    <?php foreach ($layouts as $layout) { ?>
    html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td>';
    html += '      <select name="ymmfilter_module[' + module_row + '][destination]">';
    html += '        <option value="reload" selected="selected"><?php echo $text_destination_reload; ?></option>';
    html += '        <option value="home"><?php echo $text_destination_home; ?></option>';
    html += '        <option value="search"><?php echo $text_destination_search; ?></option>'; 
    html += '      </select>';
    html += '    </td>';
    html += '    <td class="left"><select name="ymmfilter_module[' + module_row + '][position]">';
    html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
    html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
    html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
    html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
    html += '    </select></td>';
    html += '    <td class="left"><select name="ymmfilter_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
    html += '    <td class="right"><input type="text" name="ymmfilter_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
    html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
    html += '  </tr>';
    html += '</tbody>';
    
    $('#module tfoot').before(html);
    
    module_row++;
}
</script>
<?php echo $footer; ?>