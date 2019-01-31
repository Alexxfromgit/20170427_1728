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
      <h1><img src="view/image/backup.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_import; ?></span></a><a onclick="redirectDownload()" class="button"><span><?php echo $button_export; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td colspan="2"><?php echo $entry_description; ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_unique_id; ?><br /><span class="help"><?php echo $help_unique_id; ?></span></td>
            <td>
              <select name="unique_id">
                <option value = "product_id" selected="selected"><?php echo $entry_product_id; ?></option>
                <option value = "model"><?php echo $entry_model; ?></option>
                <option value = "sku"><?php echo $entry_sku; ?></option>
                <option value = "mpn"><?php echo $entry_mpn; ?></option>
                <option value = "upc"><?php echo $entry_upc; ?></option>
                <option value = "ean"><?php echo $entry_ean; ?></option>
                <option value = "isbn"><?php echo $entry_isbn; ?></option>
              </select>
            </td>
          <tr>
          <tr>
            <td><?php echo $entry_empty_data; ?><br /><span class="help"><?php echo $help_empty_data; ?></span></td>
            <td>
              <select name="empty">
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <option value="1"><?php echo $text_yes; ?></option>
              </select>
            </td>
          </tr>
          <tr>
            <td width="25%"><?php echo $entry_restore; ?></td>
            <td><input type="file" name="upload" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
function redirectDownload() {
  var url = '<?php echo htmlspecialchars_decode($export); ?>&unique_id='+$('select[name=unique_id]').val();
  window.location = url;
}
</script>
<?php echo $footer; ?>