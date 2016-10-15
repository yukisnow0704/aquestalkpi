<?php

$buttonpin=8;
$isFunction=false;
$var;

wiringPiSetupGpio();

pinMode($buttonpin, 0);

while (true) {
	$var = digitalRead($buttonpin);
	print($var);

}