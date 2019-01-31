<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
	<head>
		<title><?php print $title; ?></title>
		<?php foreach ($styles as $style) { ?>
		<link rel="<?php print $style['rel']; ?>" type="text/css" href="<?php print $style['href']; ?>" media="<?php print $style['media']; ?>" />
		<?php } ?>
		<style type="text/css">
			body {
				padding: 0;
				margin: 0;
				background: #F7F7F7;
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 11px;
				padding: 10px 20px;
				
			}
			#container {
				height: 340px;
				width: 100%;
			}
			<?php if (isset($css)) print $css; ?>
		</style>
	</head>
	<body>
		<div id="container">
			<?php print $message; ?>
		</div>
	</body>
</html>