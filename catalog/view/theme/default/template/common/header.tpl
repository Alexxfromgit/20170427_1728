<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php if ($stores) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php echo $google_analytics; ?>
<link href="http://technoride.com.ua/favicon.ico" rel="shortcut icon" />
<link href="http://technoride.com.ua/favicon.ico" rel="icon" type="image/x-icon" />
<?php echo '<script>var yaParams = {ip_adress: "'. $_SERVER['REMOTE_ADDR'] .'" };</script>'; ?>
</head>
<body>
<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a><br><center><a href="/" style="color:#111; font-weight:bold; text-decoration: none;">Интернет магазин автозапчастей</a></center></div>
  <?php } ?>
<div class="phone"><a href="tel:+380954010568">+38 (095) 401 05 68</a><br><a href="tel:+380684010568">+38 (068) 401 05 68</a><br><a href="tel:+380934010568">+38 (093) 401 05 68</a><br><span>Пн-Пт 9:00 до 19:00</span></div>
<div class="sear"><div class="ew"><a id="spcallmeback_btn_1" class="spcallmeback_raise_btn" href="#spcallmeback_1">Заказать звонок</a></div>
<input type="text" id="artnum" value="" maxlength="40" class="inpt" placeholder="Поиск по номеру запчасти"> 
<input type="submit" value="" class="sbut" onclick="TDMArtSearch()">

<script type="text/javascript">
function TDMArtSearch(){
	var art = $('#artnum').val();
	if(art!=''){
		art = art.replace(/[^a-zA-Z0-9.-]+/g, '');
		location = '/autoparts/search/'+art+'/';
	}
}
$('#artnum').keypress(function (e){
  if(e.which == 13){ TDMArtSearch(); return false;}
});
</script>


  <div style="padding-top:26px;">
	<a href="https://vk.com/technor1de" rel="external">
		<img src="/image/social/vk.png" title="ВКонтакте" alt="ВКонтакте">
	</a>
	<a href="https://www.facebook.com/Technoride" rel="external">
		<img src="/image/social/fb.png" title="Facebook" alt="Facebook">
	</a>
	<a href="https://twitter.com/technorideparts" rel="external">
		<img src="/image/social/tw.png" title="Twitter" alt="Twitter">
	</a>
	<a href="https://plus.google.com/u/0/communities/109425170866023597981" rel="external">
		<img src="/image/social/gp.png" title="Google+" alt="Google+">
	</a>
	<a href="http://ok.ru/group/52480910819479" rel="external">
		<img src="/image/social/ok.png" title="Одноклассники" alt="Одноклассники">
	</a>
	<a href="https://www.youtube.com/channel/UCfq0WGbnZusk50LavxQbzBg" rel="external">
		<img src="/image/social/yt.png" title="Youtube" alt="Youtube">
	</a>
  </div>
<script type="text/javascript">
   function externalLinks() {
    links = document.getElementsByTagName("a");
    for (i=0; i<links.length; i++) {
      link = links[i];
      if (link.getAttribute("href") && link.getAttribute("rel") == "external")
      link.target = "_blank";
    }
   }
   window.onload = externalLinks;
</script>


</div>
<div class="scrb"><a href="dostavka">Доставка</a> | <a href="oplata">Оплата</a> | <a href="kontakti">Контакты</a></div>

  <?php echo $cart; ?>
    <div id="welcome">
	<?php if (!$logged) { ?>
	<?php echo $text_welcome; ?>
	<?php } ?>
  
	<?php if ($logged) { ?>
	<?php echo $text_logged; ?>
	<?php } ?>
  </div>
  
  
</div>
<?php if ($categories) { ?>
<div id="menu">
  <ul>
    <?php foreach ($categories as $category) { ?>
    <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
      <?php if ($category['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
    </li>
    <?php } ?>
  </ul>
</div>
<?php } ?>
<div id="notification"></div>
