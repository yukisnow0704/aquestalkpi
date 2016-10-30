<?php

$firepin=11;
$gaspin=19;
$soundpin=8;
$touchpin=26;
$peoplepin=6;

$isFunction=false;

$text='';
$stack=0;

$mongo = new Mongo("192.168.1.58:27017");
$db = $mongo->selectDB("sample1");
$col = $db->selectCollection("senser");

wiringPiSetupGpio();

pinMode($firepin, 0);
pinMode($gaspin, 0);
pinMode($soundpin, 0);
pinMode($touchpin, 0);
pinMode($peoplepin, 0);


while (true) {
        while ($isFunction == false) {
                if(digitalRead($firepin) == 1){
                        sleep(1);
                        if(digitalRead($firepin) == 1){
                                $text .= '火が出ているようです。';
                                $isFunction = true;
                                $doc = array( 
                                        'name' => 'fire',
                                        'date' => date('Y-m-d H:i:s'),
                                );
                                $col->insert($doc);
                        }
                }

                if(digitalRead($gaspin) == 0){
                        sleep(1);
                        if(digitalRead($gaspin) == 0){
                                $text .= '可燃性のガスが発生しているようです。';
                                $isFunction = true;
                                $doc = array( 
                                        'name' => 'gas',
                                        'date' => date('Y-m-d H:i:s'),
                                );
                                $col->insert($doc);
                        }
                }

                while (digitalRead($touchpin) == 1) {
                        $time = 0;
                        print($time);
                        exec("/home/pi/aquestalkpi/AquesTalkPi 20秒程度サウンドセンサーが待機します。基本的には静かにお願いします。 | aplay -D plughw:2,0");
                        $doc = array( 
                                'name' => 'touch',
                                'date' => date('Y-m-d H:i:s'),
                        );
                        $col->insert($doc);

                        while ($time < 200000) {
                                usleep(1);
                                $time += 1;
                                if( $time%10000 == 0)
                                        print($time);
                                if(digitalRead($soundpin) == 0){
                                        $text = 'サウンドセンサーが稼動しました。危険を感知しています。';
                                        $isFunction = true;
                                        $time = 2000000;
                                        $isFunction = true;
                                        $doc = array( 
                                                'name' => 'sound',
                                                'date' => date('Y-m-d H:i:s'),
                                        );
                                        $col->insert($doc);
                                }
                        }
                        if($isFunction == false && $time == 200000)
                                exec("/home/pi/aquestalkpi/AquesTalkPi サウンドセンサーの起動を停止します。 | aplay -D plughw:2,0");        

                }

                if(digitalRead($peoplepin) == 1) {
                        sleep(1);
                        if(digitalRead($peoplepin) == 1){
                                $doc = array( 
                                        'name' => 'people',
                                        'date' => date('Y-m-d H:i:s'),
                                );
                                $col->insert($doc);
                                $stack = 0;
                                print('people it!!');
                        }
                }

        }

        if ($isFunction) {
                $messagePath = "/home/pi/aquestalkpi/AquesTalkPi " . $text . " | aplay -D plughw:2,0";
                exec($messagePath);

                $text = '';
                $isFunction = false;
        }

}
