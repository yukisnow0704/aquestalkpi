<?php

$firepin=11;
$gaspin=19;
$soundpin=8;
$touchpin=26;

$isFunction=false;

$text='';

$mongo = new Mongo("192.168.1.58:27017");
$fireTable = $mongo->selectDB("fire");
$gasTable = $mongo->selectDB("gas");
$soundTable = $mongo->selectDB("sound");

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
                                exec('arecord -D plughw:1,0 -t wav -f dat -d 3 out.wav');

                                $out = file_get_contents('out.wav');
                                $doc = array( 
                                        'name' => 'car',
                                        'data' => new date(),
                                        'sound' => new MongoBinData($out, MongoBinData::GENERIC)
                                );
                        }
                }

                if(digitalRead($gaspin) == 0){
                        sleep(1);
                        if(digitalRead($gaspin) == 0){
                                $text .= '可燃性のガスが発生しているようです。';
                                $isFunction = true;
                        }
                }

                while (digitalRead($touchpin) == 1) {
                        $time = 0;
                        print($time);
                        exec("/home/pi/aquestalkpi/AquesTalkPi 20秒程度サウンドセンサーが待機します。基本的には静かにお願いします。 | aplay -D plughw:2,0");      
                        while ($time < 200000) {
                                usleep(1);
                                $time += 1;
                                if( $time%10000 == 0)
                                        print($time);
                                if(digitalRead($soundpin) == 0){
                                        $text = 'サウンドセンサーが稼動しました。危険を感知しています。';
                                        $isFunction = true;
                                        $time = 2000000;
                                }
                        }
                        if($isFunction == false && $time == 200000)
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
