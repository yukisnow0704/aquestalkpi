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

while (true) {
    while($isFunction == false) {
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
        }

        if(digitalRead($peoplepin) == 1) {
            sleep(1);
            if(digitalRead($peoplepin) == 1){

                    exec('fswebcam -d v4l2:/dev/video0 out.jpg');
                    $outKey = $fs->put('out.jpg');
                    exec('sh /home/pi/usbreset.sh');
                    $stack = 0;
            }
        }

    }

    if ($isFunction) {
            $messagePath = "/home/pi/aquestalkpi/AquesTalkPi " . $text . " | aplay -D plughw:3,0";
            exec($messagePath);

            $text = '';
            $isFunction = false;
    }

}