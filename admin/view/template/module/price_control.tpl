<?php echo $header; ?>
<style>
	.categories_list {
		width: 30%;
		border: 1px solid rgb(240, 189, 143);
		padding: 5px 10px;
		margin:0 5px;
		float:left;
	}
	
	.form_actions {
		padding: 5px 15px;
		border: 1px solid rgb(231, 231, 231);
		width: 100px;
		float:left;
	}
	
	.form_actions label {
		display:block;
	}
	
	.action_input_field {
		margin: 0 5px 0 10px;
	}
	
	.field_units {
		margin: 5px 138px;
	}
	
	.field_units label {
		display:block;
	}
	
	.pricecontrol_submit_button {
		margin-left: 10px;
		height: 30px;
		width: 135px;
		float: left;
	}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
	  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_close; ?></a></div>
    </div>
    <div class="content">
	<?php if ($categories): ?>
      	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          	<div class="categories_list">
				<h2>Выберите категории</h2>
				<?php echo $categories; ?>
			</div>
			<div class="categories_list" style="width:60%;">
				<h2>Параметры</h2>
				<div class="form_actions">
					<label><input checked type="radio" name="action" value="addict" />Прибавить</label>
					<label><input type="radio" name="action" value="deduct" />Вычесть</label>
					<label><input type="radio" name="action" value="multiply" />Умножить на</label>
					<label><input type="radio" name="action" value="divide" />Разделить на</label>						
				</div>
				<input required class="action_input_field" type="text" name="num" placeholder="Введите число" />
				<div class="field_units">
					<label><input checked type="radio" name="unit" value="percent" />%</label>
					<label><input type="radio" name="unit" value="number" />число</label>
				</div>
				 <button class="pricecontrol_submit_button" type="submit">Выполнить</button>
			</div>
		</form>      
	<?php endif; ?>
    </div>
  </div>
</div>

<?php echo $footer; ?>