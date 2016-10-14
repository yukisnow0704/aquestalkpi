<?php

$buttonpin=23;

wiringPiSetupGpio();

pinMode($buttonpin, 1);

while (true) {
	while (!$isFunction) {
		if(digitalRead($buttonpin) == 1){
			$isFunction = true;
		}
	}

	if ($isFunction) {
		print('test');
		$isFunction = false;
	}
}