<?php

$buttonpin=19;
$var;

wiringPiSetupGpio();

pinMode($buttonpin, 0);

while (true) {
	$var = digitalRead($buttonpin);
	print($var);
}