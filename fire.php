<?php

$buttonpin=23;

wiringPiSetupGpio();

pinMode($buttonpin, 1);

while (true) {
	while ($isFunction == false) {
		if(digitalRead($buttonpin) == 1){
			$isFunction = true;
		}
	}

	if ($isFunction) {
		print('test');
		$isFunction = false;
	}
}