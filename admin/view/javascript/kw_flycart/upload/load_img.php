<?php
	$dir =  '../../../../../image/cart/';
	$scan = scandir($dir);	

	for ($i = 0; $i<count($scan); $i++) {
		if ($scan[$i] != '.' && $scan[$i] != '..') {
			if (strpos($scan[$i], '.') !== false) {
				echo '<li><a href="javascript:void(0)" title="'.$scan[$i].'"  style="background-image:url('.$dir . $scan[$i].')"></a></li>';
			}
		}
	};
?>
