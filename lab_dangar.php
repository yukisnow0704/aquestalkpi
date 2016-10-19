<?php

$firepin=11;
$gaspin=19;
$soundpin=8;
$touchpin=26;

$isFunction=false;

$text='';
$var;
$time = 0;

wiringPiSetupGpio();

pinMode($firepin, 0);
pinMode($gaspin, 0);
pinMode($soundpin, 0);
pinMode($touchpin, 0);


while (true) {
        while ($isFunction == false) {
                if(digitalRead($firepin) == 1){
                        sleep(1);
                        if(digitalRead($firepin) == 1){
                                $text .= '火が出ているようです。';
                                $isFunction = true;
                        }
                }

                if(digitalRead($gaspin) == 0){
                        sleep(1);
                        if(digitalRead($gaspin) == 1){
                                $text .= '可燃性のガスが発生しているようです。';
                                $isFunction = true;
                        }
                }

                while (digitalRead($touchpin) == 1) {
                        exec("/home/pi/aquestalkpi/AquesTalkPi 20秒間サウンドセンサーが待機します。基本的には静かにお願いします。 | aplay -D plughw:2,0");        
                        while ($time <= 20) {
                                sleep(1);
                                $time += 1;
                                if(digitalRead($soundpin) == 0){
                                        $text = 'サウンドセンサーが稼動しました。危険を感知しています。';
                                        $isFunction = true;
                                        $time = 20;
                                }
                        }
                        if($isFunction == false && $time == 20)
                        exec("/home/pi/aquestalkpi/AquesTalkPi サウンドセンサーの起動を停止します。 | aplay -D plughw:2,0");        

                }


        }

        if ($isFunction) {
                $messagePath = "/home/pi/aquestalkpi/AquesTalkPi " . $text . " | aplay -D plughw:2,0";
                exec($messagePath);

                $text = '';
                $isFunction = false;
        }

}
