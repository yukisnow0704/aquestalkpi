<?php

$buttonpin=8;
$isFunction=false;
$var;

wiringPiSetupGpio();

pinMode($buttonpin, 0);

while (true) {
	while ($isFunction == true) {
		$var = digitalRead($buttonpin);
		if($var == 1){
			$isFunction = true;
		}
	}

	if ($isFunction == false) {
		print('!!!fire!!!');
		$isFunction = false;
	}

}