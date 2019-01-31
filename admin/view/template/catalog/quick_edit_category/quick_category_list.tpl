<?php echo $header; ?>
<div id="content">
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
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
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $repair; ?>" class="button"><?php echo $button_repair; ?></a><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').submit();" class="button"><?php echo $button_delete; ?></a><a href="<?php echo $setting; ?>" class="button"><?php echo $button_setting; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list category">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php echo $column_name; ?></td>
              <td class="right"><?php echo $column_sort_order; ?></td>
			  <td class="center"><?php echo $column_status; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($categories) { ?>
              <?php foreach ($categories as $category) { ?>
                <tr>
                  <td style="text-align: center;">
				    <?php if ($category['selected']) { ?>
					  <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
					<?php } else { ?>
					  <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" />
					<?php } ?>
				  </td>
				  <td class="left"><?php echo $category['name']; ?></td> 
				  <td align="right"><?php echo $category['sort_order']; ?></td>
				  <td align="center" width="100"><a class="ajax-status" id="status-<?php echo $category['category_id']; ?>"><?php echo $category['status']; ?></a></td>
				  <td class="right" width="200">
				    <?php if ($this->config->get('config_category_quick_all_buttons') == 0) { ?>
					  <?php if ($this->config->get('config_category_general_data') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&category_description&category_id=<?php echo $category['category_id'];?>" class="description_dialog button_general_data tooltip" title="<?php echo $text_description; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_category_parent') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&category_parent&category_id=<?php echo $category['category_id'];?>" class="category_dialog button_category tooltip" title="<?php echo $text_parent; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_category_filter') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&category_filter&category_id=<?php echo $category['category_id'];?>" class="filter_dialog button_filters tooltip" title="<?php echo $text_filter; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_category_image') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&category_img&category_id=<?php echo $category['category_id'];?>" class="image_dialog button_images tooltip" title="<?php echo $text_image; ?>" /></a>
				      <?php } ?>
					  <?php if ($this->config->get('config_category_stores') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&category_stores&category_id=<?php echo $category['category_id'];?>" class="stores_dialog button_stores tooltip" title="<?php echo $text_stores; ?>" /></a>
					  <?php } ?>
					  <?php if ($this->config->get('config_category_design') == 1) { ?>
					    <a style="text-decoration: none;" href="<?php echo $link;?>&category_design&category_id=<?php echo $category['category_id'];?>" class="design_dialog button_design tooltip" title="<?php echo $text_design; ?>" /></a>
					  <?php } ?>
					<?php } ?>
					<?php foreach ($category['action'] as $action) { ?>					  
					  <a class="button_edites tooltip" href="<?php echo $action['href']; ?>" title="<?php echo $action['text']; ?>"></a>
					<?php } ?>
				  </td>
				</tr>
              <?php } ?>
            <?php } else { ?>
              <tr>
                <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.ajax-status').click(function() {
	var object_id=$(this).attr('id');
	$.ajax({
		url: 'index.php?route=catalog/category_quick/changeStatus&token=<?php echo $token; ?>',
		type: 'get',
		data: {object_id:object_id},
		dataType: 'html',
		success: function(html) {
			if(html!=''){				
				$('#'+object_id).html(html);
			}
		}
	});
});
$("a.tooltip, img.tooltip, div.tooltip").tooltip({
	track: true, 
    delay: 0, 
    showURL: false, 
    showBody: " - ", 
    fade: 250 
});
//--></script>
<script type="text/javascript"><!--
$('a.description_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_description; ?>',
			modal: true,
			resizable: true,
			width: 1000,
			height: 770,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					for ( inst in CKEDITOR.instances ){
						CKEDITOR.instances[inst].updateElement();
					}
					$.post(link, $('#description-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.category_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_parent; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 300,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#category-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.filter_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_filter; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 700,
			buttons: {
				"<?php echo $button_save; ?>": function() {
					$.post(link, $('#filter-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				"<?php echo $button_cancel; ?>": function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.image_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_image; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 380,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#image-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.stores_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_stores; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 550,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#stores-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
$('a.design_dialog').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    $('<div id="open-dialog" class="hidden"></div>').appendTo('body')
    .load(link,null, function(){
        $(this).dialog({
			title: '<?php echo $text_design; ?>',
			modal: true,
			resizable: true,
			width: 900,
			height: 300,
			buttons: {
				'<?php echo $button_save; ?>': function() {
					$.post(link, $('#design-form').serialize(), function(response){
					})
                $(this).dialog("close"); $(this).remove();
				},
				'<?php echo $button_cancel; ?>': function(){
					$(this).dialog("close"); $(this).remove();
				},
			},
			close: function(){
				$(this).remove();
			}
		});
    });
});
//--></script>
<script type="text/javascript"><!--
function getParent() {
	$('input[name=\'path\']').autocomplete({
		delay: 500,
		source: function(request, response) {		
			$.ajax({
				url: 'index.php?route=catalog/category_quick/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {
					json.unshift({
						'category_id':  0,
						'name':  '<?php echo $text_none; ?>'
					});
					
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.category_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'path\']').val(ui.item.label);
			$('input[name=\'parent_id\']').val(ui.item.value);
			
			return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
}
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script type="text/javascript"><!--
function getFilters() {
	$('input[name=\'filter\']').autocomplete({
		delay: 500,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
				dataType: 'json',
				success: function(json) {		
					response($.map(json, function(item) {
						return {
							label: item.name,
							value: item.filter_id
						}
					}));
				}
			});
		}, 
		select: function(event, ui) {
			$('#category-filter' + ui.item.value).remove();
			
			$('#category-filter').append('<div id="category-filter' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="category_filter[]" value="' + ui.item.value + '" /></div>');

			$('#category-filter div:odd').attr('class', 'odd');
			$('#category-filter div:even').attr('class', 'even');
					
			return false;
		},
		focus: function(event, ui) {
		  return false;
	   }
	});

	$('#category-filter div img').live('click', function() {
		$(this).parent().remove();
		
		$('#category-filter div:odd').attr('class', 'odd');
		$('#category-filter div:even').attr('class', 'even');	
	});
}
//--></script>
<?php echo $footer; ?>