<?php

$buttonpin=6;
$var;

wiringPiSetupGpio();

pinMode($buttonpin, 0);

while (true) {
	$var = digitalRead($buttonpin);
	print($var);
}