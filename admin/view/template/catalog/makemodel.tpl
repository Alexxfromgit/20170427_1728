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
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table id="mmTable">
	  	<tr>
			<th><h1><?php echo $header_make; ?></h1></th>
			<th><h1><?php echo $header_model; ?></h1></th>
			<th><h1><?php echo $header_engine; ?></h1></th>
		</tr>
		<tr>
			<td>
				<form id="addmake">
				<label for="new-make"><?php echo $text_add_make; ?></label>
				<input type="text" name="new-make" />
				<input type="submit" value="<?php echo $text_add; ?>" />
				</form>
			</td>
			<td>
				<form id="addmodel">
				<label for="new-model"><?php echo $text_add_model; ?></label>
				<input type="text" name="new-model"/>
				<input type="submit" value="<?php echo $text_add; ?>" />
				</form>
			</td>
			<td>
				<form id="addengine">
				<label for="new-engine"><?php echo $text_add_engine; ?></label>
				<input type="text" name="new-engine"/>
				<input type="submit" value="<?php echo $text_add; ?>" />
				</form>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;">
			  <table cellpadding="0" cellspacing="0" border="0" class="display" id="makes">
				<thead>
					<th>ID</th>
					<th><?php echo $header_make; ?></th>
					<th><?php echo $header_action; ?></th>
				</thead>
				<tbody>
					<td colspan="10" class="dataTables_empty">Loading data from server</td>
				</tbody>
				<tfoot>
					<th>ID</th>
					<th><?php echo $header_make; ?></th>
					<th><?php echo $header_action; ?></th>
				</tfoot>
			  </table>
			</td>
			<td style="vertical-align: top;">
			  <table cellpadding="0" cellspacing="0" border="0" class="display" id="models">
				<thead>
					<th>ID</th>
					<th><?php echo $header_model; ?></th>
					<th><?php echo $header_action; ?></th>
				</thead>
				<tbody>
					<td colspan="10" class="dataTables_empty">Loading data from server</td>
				</tbody>
				<tfoot>
					<th>ID</th>
					<th><?php echo $header_model; ?></th>
					<th><?php echo $header_action; ?></th>
				</tfoot>
			  </table>
			</td>
			<td style="vertical-align: top;">
			  <table cellpadding="0" cellspacing="0" border="0" class="display" id="engines">
				<thead>
					<th>ID</th>
					<th><?php echo $header_engine; ?></th>
					<th><?php echo $header_action; ?></th>
				</thead>
				<tbody>
					<td colspan="10" class="dataTables_empty">Loading data from server</td>
				</tbody>
				<tfoot>
					<th>ID</th>
					<th><?php echo $header_engine; ?></th>
					<th><?php echo $header_action; ?></th>
				</tfoot>
			  </table>
			</td>
		</tr>
	  </table>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#makes').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
		"bLengthChange": false,
		"iDisplayLength": <?php echo $this->config->get('config_admin_limit'); ?>,
        "sAjaxSource": "<?php echo html_entity_decode($make_callback_url); ?>",
		"fnServerData": function( sSource, aoData, fnCallback ) {
			$.ajax( {
				"dataType": 'json',
				"type": "POST",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			} );
		},
		"fnDrawCallback": function(){
			$('td.makeEdit').editable(function(value, settings){
				$.post(
					"<?php echo html_entity_decode($edit_url); ?>",
					{ id: $(this).closest('tr').attr('id'), content: value },
					function(){ 
						$('#makes').dataTable().fnDraw();
					}
				);
			});	
		},
		"aoColumns": [
		null,
		{ "sClass": "makeEdit" },
		{ "mDataProp": null }
		],
		"aoColumnDefs":[
			{
				"fnRender": function( oObj ) {
					return '<a href="<?php echo html_entity_decode($make_delete_url); ?>&id=' + oObj.aData[0] + '" title="Delete" class="makedelete">Delete</a>';
				},
				"aTargets": [2]
			}
		]
    } );
	$('#models').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bLengthChange": false,
		"iDisplayLength": <?php echo $this->config->get('config_admin_limit'); ?>,
        "sAjaxSource": "<?php echo html_entity_decode($model_callback_url); ?>",
		"fnServerData": function( sSource, aoData, fnCallback ) {
			$.ajax( {
				"dataType": 'json',
				"type": "POST",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			} );
		},
		"fnDrawCallback": function(){
			$('td.modelEdit').editable(function(value, settings){
				$.post(
					"<?php echo html_entity_decode($edit_url); ?>",
					{ id: $(this).closest('tr').attr('id'), content: value },
					function(){ 
						$('#models').dataTable().fnDraw();
					}
				);
			});	
		},
		"aoColumns": [
		null,
		{ "sClass": "modelEdit" },
		{ "mDataProp": null }
		],
		"aoColumnDefs":[
			{
				"fnRender": function( oObj ) {
					return '<a href="<?php echo html_entity_decode($model_delete_url); ?>&id=' + oObj.aData[0] + '" title="Delete" class="modeldelete">Delete</a>';
				},
				"aTargets": [2]
			}
		]
    } );
	$('#engines').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "bLengthChange": false,
		"iDisplayLength": <?php echo $this->config->get('config_admin_limit'); ?>,
        "sAjaxSource": "<?php echo html_entity_decode($engine_callback_url); ?>",
		"fnServerData": function( sSource, aoData, fnCallback ) {
			$.ajax( {
				"dataType": 'json',
				"type": "POST",
				"url": sSource,
				"data": aoData,
				"success": fnCallback
			} );
		},
		"fnDrawCallback": function(){
			$('td.engineEdit').editable(function(value, settings){
				$.post(
					"<?php echo html_entity_decode($edit_url); ?>",
					{ id: $(this).closest('tr').attr('id'), content: value },
					function(){ 
						$('#engines').dataTable().fnDraw();
					}
				);
			});	
		},
		"aoColumns": [
		null,
		{ "sClass": "engineEdit" },
		{ "mDataProp": null }
		],
		"aoColumnDefs":[
			{
				"fnRender": function( oObj ) {
					return '<a href="<?php echo html_entity_decode($engine_delete_url); ?>&id=' + oObj.aData[0] + '" title="Delete" class="enginedelete">Delete</a>';
				},
				"aTargets": [2]
			}
		]
    } );
	$("#addmake").bind("submit", function(event){
		event.preventDefault();
		$.ajax({
			"dataType": 'json',
			"type": "POST",
			"url": "<?php echo html_entity_decode($make_insert_url); ?>",
			"data": $("#addmake").serialize(),
			"success": function(json){
				if(json['message'] == 'success'){
					$('#makes').dataTable().fnDraw();
					$("input[name=new-make]").val('');
				} else {
					alert(json['message']);
				}
			}
		});
	});
	$("#addmodel").bind("submit", function(event){
		event.preventDefault();
		$.ajax({
			"dataType": 'json',
			"type": "POST",
			"url": "<?php echo html_entity_decode($model_insert_url); ?>",
			"data": $("#addmodel").serialize(),
			"success": function(json){
				if(json['message'] == 'success'){
					$('#models').dataTable().fnDraw();
					$("input[name=new-model]").val('');
				} else {
					alert(json['message']);
				}
			}
		});
	});
	$("#addengine").bind("submit", function(event){
		event.preventDefault();
		$.ajax({
			"dataType": 'json',
			"type": "POST",
			"url": "<?php echo html_entity_decode($engine_insert_url); ?>",
			"data": $("#addengine").serialize(),
			"success": function(json){
				if(json['message'] == 'success'){
					$('#engines').dataTable().fnDraw();
					$("input[name=new-engine]").val('');
				} else {
					alert(json['message']);
				}
			}
		});
	});
} );
$(".makedelete").live("click", function(event){
	event.preventDefault();
	$.get($(this).attr('href'), function(json){
		if(json['message'] == 'success'){
			$('#makes').dataTable().fnDraw();
		} else {
			alert(json['message']);
		}
	}, "json");
});
$(".modeldelete").live("click", function(event){
	event.preventDefault();
	$.get($(this).attr('href'), function(json){
		if(json['message'] == 'success'){
			$('#models').dataTable().fnDraw();
		} else {
			alert(json['message']);
		}
	}, "json");
});
$(".enginedelete").live("click", function(event){
	event.preventDefault();
	$.get($(this).attr('href'), function(json){
		if(json['message'] == 'success'){
			$('#engines').dataTable().fnDraw();
		} else {
			alert(json['message']);
		}
	}, "json");
});
</script>
<?php echo $footer; ?>