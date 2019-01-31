<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>
    <div class="box">
      <div class="heading">
        <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      </div>
    </div>
    <div class="content">
        <div class="help"><?php echo $l->get('text_license_help') ?></div>
        <div class="help">dmitriy@simpleopencart.com</div>
        <form action="<?php echo $action_main; ?>" method="post" enctype="multipart/form-data" id="form">
          <h3><?php echo $domain ?></h3>
          <h3><?php echo $l->get('entry_license') ?></h3>
          <div>
            <input type="text" name="simple_license" size="100">&nbsp;<a onclick="$('form#form').submit()" class="button"><span><?php echo $l->get('button_save'); ?></span></a>
          </div>
        </form>
    </div>
</div>
<?php echo $footer; ?>