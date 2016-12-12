<?php

$callpin=20;
$calloutput=3;
$var;

wiringPiSetupGpio();


pinMode($callpin, 0);
pinMode($calloutput, 1);

while (true) {
	$var = digitalRead($callpin);
	print($var);
}