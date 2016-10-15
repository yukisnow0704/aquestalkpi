<?php

$buttonpin=23;
$isFunction=false;
$var;

wiringPiSetupGpio();

pinMode($buttonpin, 0);

while (true) {
	while ($isFunction == false) {
		$var = digitalRead($buttonpin);
		if($var == 1){
			$isFunction = true;
		}
	}

	if ($isFunction) {
		print('!!!fire!!!/n');
		$isFunction = false;
	}

}