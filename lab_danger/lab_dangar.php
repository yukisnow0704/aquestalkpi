<?php
require_once ( 'PHPMailerAutoload.php' );

//GPIOの設定
$firepin=11;
$gaspin=19;
$soundpin=8;
$touchpin=26;
$peoplepin=6;
$callpin=20;

wiringPiSetupGpio();

pinMode($firepin, 0);
pinMode($gaspin, 0);
pinMode($soundpin, 0);
pinMode($touchpin, 0);
pinMode($peoplepin, 0);
pinMode($callpin, 0);

//管理用
$isFunction=false;

//テキストの初期化、その他設定
$text='';
$stack=0;
$ip = '133.88.121.124';

//MongoDBのテーブル及びその他設定
$mongo = new Mongo($ip.":27017");
$db = $mongo->selectDB("sample1");
$col = $db->selectCollection("senser");
$people = $db->selectCollection("people");
$fs = new MongoGridFS($db);

//メール配信機能
function send_mail($subject, $body, $photo) {

    $fromname = "工藤研究室緊急配信システム";
    $from = "sist.kudolab@gmail.com";
    $smtp_user = "sist.kudolab@gmail.com";
    $smtp_password = "kudo0401";
    
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;
    $mail->CharSet = 'utf-8';
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->IsHTML(false);
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_password; 
    $mail->SetFrom($smtp_user);
    $mail->From = $from;
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    if($photo)
        $mail->addAttachment('out.jpg');
    $mail->AddAddress('yukisnow0704@gmail.com');
    $mail->AddAddress('lilac-stars@ezweb.ne.jp');

    if( !$mail -> Send() ){
        $message  = "Message was not sent<br/ >";
        $message .= "Mailer Error: " . $mailer->ErrorInfo;
        return false;
    } else {
        $message  = "Message has been sent";
        return ture;
    }

}

function db_array($outKey, $outImageKey, $name) {

    $doc = array( 
            'name' => $name,
            'time' => date('Y-m-d H:i:s'),
            'data_id' => $outKey,
            'data_name' => 'out.wav',
            'img_id' => $outImageKey,
            'img_name' => 'out.jpg',
    );
    
    return $doc;

}

while (true) {
    while($isFunction == false) {
        if(digitalRead($firepin) == 1){
            sleep(1);
            if(digitalRead($firepin) == 1){
                $name = 'fire';
                $text .= '火が出ているようです。';
                $subject = "火炎";
                $body = "火炎センサーが感知しています。添付画像を確認してください";

                $isFunction = true;

                exec('arecord -D plughw:1,0 -t wav -f dat -d 3 out.wav');
                exec('fswebcam -d v4l2:/dev/video0 out.jpg');
                exec('sh /home/pi/usbreset.sh');

                //メールの配信
                send_mail($subject, $body, true);
                
                //DBに格納
                $outKey = $fs->put('out.wav');
                $outImageKey = $fs->put('out.jpg');
                $doc = db_array($outKey, $outImageKey, $name);
                $col->insert($doc);

            }
        }

        if(digitalRead($gaspin) == 0){
            sleep(1);
            if(digitalRead($gaspin) == 0){
                $name = 'gas';
                $text .= '可燃性のガスが発生しているようです。';
                $subject = "ガス";
                $body = "ガスセンサーが感知しています。添付画像を確認してください";

                $isFunction = true;

                exec('arecord -D plughw:1,0 -t wav -f dat -d 3 out.wav');
                exec('fswebcam -d v4l2:/dev/video0 out.jpg');
                exec('sh /home/pi/usbreset.sh');

                //メールの配信
                send_mail($subject, $body, true);

                //音声を取得して配信                                
                $outKey = $fs->put('out.wav');
                $outImageKey = $fs->put('out.jpg');
                $doc = db_array($outKey, $outImageKey, $name);
                $col->insert($doc);

            }
        }

        if (digitalRead($touchpin) == 1) {
            $time = 0;
            print($time);
            exec("/home/pi/aquestalkpi/AquesTalkPi 20秒音を取得、静かに！ | aplay -D plughw:3,0");
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
                if(digitalRead($soundpin) == 1){
                    $name = 'sound';
                    $text = 'サウンドセンサーが稼動しました。危険を感知しています。';
                    $subject = "サウンド";
                    $body = "サウンドセンサーが感知しています。添付画像を確認してください";

                    $isFunction = true;

                    exec('arecord -D plughw:1,0 -t wav -f dat -d 3 out.wav');
                    exec('fswebcam -d v4l2:/dev/video0 out.jpg');
                    exec('sh /home/pi/usbreset.sh');

                    //メールの配信
                    send_mail($subject, $body, true);
                    
                    //音声を取得して配信
                    $outKey = $fs->put('out.wav');
                    $outImageKey = $fs->put('out.jpg');
                    $doc = db_array($outKey, $outImageKey, $name);
                    $col->insert($doc);

                    $time = 200000;
                }
            }
            if($isFunction == false && $time == 200000)
                    exec("/home/pi/aquestalkpi/AquesTalkPi サウンドセンサーの起動を停止します。 | aplay -D plughw:3,0");        

        }

        if(digitalRead($peoplepin) == 1) {
            sleep(1);
            if(digitalRead($peoplepin) == 1){

                    exec('fswebcam -d v4l2:/dev/video0 out.jpg');
                    $outKey = $fs->put('out.jpg');
                    exec('sh /home/pi/usbreset.sh');
                    $doc = array( 
                            'name' => 'people',
                            'date' => date('Y-m-d H:i:s'),
                            'data_id' => $outKey,
                            'data_name' => 'out.jpg',
                    );
                    $people->insert($doc);
                    $stack = 0;
            }
        }

        if(digitalRead($callpin) == 1){
            $text .= '集合メールを発信しました';
            $subject = "集合";
            $body = "集合メールが発信されました。研究室へ集まりましょう！";

            $isFunction = true;

            //メールの配信
            $result = send_mail($subject, $body, false);
            if (!$result)
                $text = "できませんでした";

        }

    }

    if ($isFunction) {
            $messagePath = "/home/pi/aquestalkpi/AquesTalkPi " . $text . " | aplay -D plughw:3,0";
            exec($messagePath);

            $text = '';
            $isFunction = false;
    }

}