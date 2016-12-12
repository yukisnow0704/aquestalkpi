<?php

$callpin=20;
$var;

wiringPiSetupGpio();


pinMode($callpin, 0);

while (true) {
	$var = digitalRead($callpin);
	print($var);
}