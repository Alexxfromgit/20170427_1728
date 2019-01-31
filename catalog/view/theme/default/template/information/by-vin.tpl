<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="request-content">
	<?php echo $content_top; ?>
 
	<div class="breadcrumb">
    <?php $i=1; foreach ($breadcrumbs as $breadcrumb) { ?> 
    <?php if($i == 2) :?>    
    	<?php echo $breadcrumb['separator']; ?><span><a href="index.php?route=information/by-vin"><?php echo $breadcrumb['text']; ?></a></span>
    <?php else :?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php endif; ?> 
    <?php $i++; } ?>
  </div>

	<h1><?php echo $heading_title; ?></h1>

	<form id="request-by-vin"> 
	
		<div class="fields">
			
			<div class="row">
				<label for="name">ФИО : <span>*</span></label>
				<input id="name" type="text" class="text req" name="name" />  
			</div>
			
			<div class="row">
				<label for="phone">Мобильный : <span>*</span></label>
				<input id="phone" type="text" class="text req" name="phone" placeholder="Например: +38 (050) 787 22 33" />  
			</div>
			
			<div class="row">
				<label for="email">Электронная почта : <span>*</span></label>
				<input id="email" type="email" class="text req" name="email" />  
			</div>	
			
			<div class="row">
				<label for="vin">Номер кузова (VIN) : <span>*</span></label>
				<input id="vin" type="text" class="text req" name="vin" />  
			</div>	
			
			<div class="row">
				<label for="mark">Марка : <span>*</span></label>
				<select name="mark" id="mark" class="req"> 
					<option value=""></option>
					<option value="acura">Acura</option>
					<option value="alfa-romeo">Alfa Romeo</option>
					<option value="aro">Aro</option>
					<option value="audi">Audi</option>
					<option value="austin">Austin</option>
					<option value="avia">Avia</option>
					<option value="bedford">Bedford</option>
					<option value="bmw">Bmw</option>
					<option value="buick">Buick</option>
					<option value="cadillac">Cadillac</option>
					<option value="chevrolet">Chevrolet</option>
					<option value="chrysler">Chrysler</option>
					<option value="citroen">Citroen</option>
					<option value="dacia">Dacia</option>
					<option value="daewoo">Daewoo</option>
					<option value="daf">Daf</option>
					<option value="daihatsu">Daihatsu</option>
					<option value="dodge">Dodge</option>
					<option value="ferrari">Ferrari</option>
					<option value="fiat">Fiat</option>
					<option value="ford">Ford</option>
					<option value="ford-usa">Ford Usa</option>
					<option value="fso">Fso</option>
					<option value="gaz">Gaz</option>
					<option value="geo">Geo</option>
					<option value="gmc">Gmc</option>
					<option value="honda">Honda</option>
					<option value="hummer">Hummer</option>
					<option value="hyundai">Hyundai</option>
					<option value="infiniti">Infiniti</option>
					<option value="innocenti">Innocenti</option>
					<option value="isuzu">Isuzu</option>
					<option value="iveco">Iveco</option>
					<option value="jaguar">Jaguar</option>
					<option value="jeep">Jeep</option>
					<option value="kia">Kia</option>
					<option value="lada">Lada</option>
					<option value="lamborghini">Lamborghini</option>
					<option value="lancia">Lancia</option>
					<option value="land-rover">Land Rover</option>
					<option value="ldv">Ldv</option>
					<option value="lexus">Lexus</option>
					<option value="lincoln">Lincoln</option>
					<option value="mazda">Mazda</option>
					<option value="mercedes-benz">Mercedes-Benz</option>
					<option value="mini">Mini</option>
					<option value="mitsubishi">Mitsubishi</option>
					<option value="moskvich">Moskvich</option>
					<option value="nissan">Nissan</option>
					<option value="oltcit">Oltcit</option>
					<option value="opel">Opel</option>
					<option value="peugeot">Peugeot</option>
					<option value="plymouth">Plymouth</option>
					<option value="pontiac">Pontiac</option>
					<option value="porsche">Porsche</option>
					<option value="renault">Renault</option>
					<option value="renault-trucks">Renault Trucks</option>
					<option value="rover">Rover</option>
					<option value="saab">Saab</option>
					<option value="seat">Seat</option>
					<option value="skoda">Skoda</option>
					<option value="smart">Smart</option>
					<option value="ssangyong">Ssangyong</option>
					<option value="subaru">Subaru</option>
					<option value="suzuki">Suzuki</option>
					<option value="talbot">Talbot</option>
					<option value="tata-telco">Tata (telco)</option>
					<option value="toyota">Toyota</option>
					<option value="trabant">Trabant</option>
					<option value="uaz">Uaz</option>
					<option value="volvo">Volvo</option>
					<option value="vw">Vw</option>
					<option value="wartburg">Wartburg</option>
					<option value="yugo">Yugo</option>
					<option value="zastava">Zastava</option>
					<option value="zaz">Zaz</option>
				</select>  
			</div>	  
				 
			<div class="row">
				<label for="model">Модель / Серия : <span>*</span></label>
				<input id="model" type="text" class="text req" name="model" />  
			</div>	
			
			<div class="row">
				<label for="engine">Двигатель (код) :</label>
				<input id="engine" type="text" class="text" name="engine" />  
			</div>		
			
			<div class="row">
				<label for="volume">Объем :</label>
				<input id="volume" type="text" class="text" name="volume" />  
			</div>	
			
			<div class="row">
				<label for="country">Страна изготовитель :</label>
				<input id="country" type="text" class="text" name="country" />  
			</div>
			
			<div class="row">
				<label for="month">Месяц / Год выпуска :</label>
				<input id="month" type="text" class="text" name="month" />  
			</div>
			
			<div class="row">
				<label for="gearshift">КПП (коробка переключения передач)<span>*</span></label>
				<select name="gearshift" id="gearshift">
					<option value=""></option>
					<option value="Механика">Механика</option>
					<option value="Автомат">Автомат</option>  
				</select>	  
			</div>	
			
			<div class="row">
				<label for="parts">Запчасти которые заказываются : <span>*</span></label>
				<textarea id="parts" class="text" name="parts"></textarea>  
			</div> 
	    
			<div class="warning">Заполните все необходимые и обязательные поля</div>
      <div class="success">Ваше письмо отправлено успешно. Наш консультант скоро свяжется с Вами</div>
	
	</div>		
	
	
	<div id="request-by-vin-text"> 
		<p>Если Вы не знаете номера запчасти или точную конфигурацию автомобиля, вы можете отправить запрос нашим специалистам. Для этого достаточно указать VIN номер вашего автомобиля, марку, модель, год выпуска, объем двигателя, тип кузова и подробно описать, какие запчасти вам нужны.</p>
    <p>Для корректного подбора автозапчастей для Вашего автомобиля, заполните, пожалуйста, форму запроса. Мобильный телефон и e-mail вводятся для того, чтобы наш менеджер мог связаться с Вами.</p>
 		<p><b>VIN</b> (Vehicle Identification Number) - Номер шасси (кузова, рамы), всегда состоит из 17 символов. VIN код указан в тех паспорте Вашего автомобиля. На изображении ниже приведен пример VIN кода, указанного в тех паспорте автомобиля:</p>
	  
	  <p><img src="/catalog/view/theme/default/image/vin_code.jpg" alt="vin code"></p>
	  
	  <p>Если VIN код указан не полностью или с ошибкой - по VIN запросу придет отказ. Опишите подробно запчасти заказываемые, вводя каждую запчасть отдельно в новой строке. В каждой строке должна быть только одна деталь!</p>
    <p><b>Правильно:</b> амортизатор передний правый.<br>
    <b>Неправильно:</b> амортизатор.</p>
	
	</div> 
	
	<div class="hr"></div>
		
	<div class="row">	  
		<input type="submit" class="submit" value="Отправить">
	</div>	
	
	
	</form>
	
	<script>
		
		$('#request-by-vin .success').hide();
		$('#request-by-vin .warning').hide();
		
		$('#request-by-vin').submit(function(e){
			
			e.preventDefault();
			
			$('#request-by-vin .success').fadeOut();
		  $('#request-by-vin .warning').fadeOut();			 
			 
			if( 
				   $('#request-by-vin [name="name"]').val() !== ''  &&
				   $('#request-by-vin [name="phone"]').val() !== '' &&  
				   $('#request-by-vin [name="email"]').val() !== '' && 
				   $('#request-by-vin [name="vin"]').val() !== '' && 
				   $('#request-by-vin [name="mark"]').val() !== '' &&
				   $('#request-by-vin [name="model"]').val() !== ''
			){ 
				$.ajax({
					type: 'post',
		      url: '/catalog/view/theme/default/template/information/send-request-by-vin.php',
					data: $('#request-by-vin').serialize(),
					success: function(data) {
						console.log('success');
						$('#request-by-vin .success').fadeIn();
					} 
				}); 
			} else {
				$('#request-by-vin .warning').fadeIn();
				setTimeout(function() { $('#request-by-vin .warning').fadeOut() }, 3000); 
			}
		 
		});
	</script>
 
	<?php echo $content_bottom; ?> 
</div>
<?php echo $footer; ?>