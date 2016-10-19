<?php

$firepin=11;
$gaspin=19;
$soundpin=8;
$touchpin=26;

$isFunction=false;

$text='';
$var;

wiringPiSetupGpio();

pinMode($firepin, 0);
pinMode($gaspin, 0);
pinMode($soundpin, 0);
pinMode($touchpin, 0);


while (true) {
        while ($isFunction == false) {
                $var = digitalRead($firepin);
                if($var == 1){
                        $text = 'fire,';
                        $isFunction = true;
                }
                $var = digitalRead($gaspin);
                if($var == 0){
                        $text .= 'gas,';
                        $isFunction = true;
                }
                $var = digitalRead($soundpin);
                if($var == 0){
                        $text = 'sound,';
                        $isFunction = true;
                }
                $var = digitalRead($touchpin);
                if($var == 1){
                        $text .= 'touch,';
                        $isFunction = true;
                }
        }

        if ($isFunction) {
                $messagePath = "/home/pi/aquestalkpi/AquesTalkPi " . $text . " | aplay -D plughw:2,0";
                exec($messagePath);

                $text = '';
                $isFunction = false;
        }

}

