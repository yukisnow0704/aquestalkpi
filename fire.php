<?php

$buttonpin=23;
$isFunction=false;
$var;

wiringPiSetupGpio();

pinMode($buttonpin, 1);

while (true) {
	while ($isFunction == false) {
		$var = digitalRead($buttonpin);
		if($var == 1){
			print($var);
			$isFunction = true;
		}
	}

	if ($isFunction) {
		print('fire/n');
		$isFunction = false;
	}
}