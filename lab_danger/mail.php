<?php
require_once ( 'PHPMailerAutoload.php' );
$subject = "テストメール";
$body = "テストメールです。気がついたらメッセージを伊藤まで送ってください。";
$fromname = "me";
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
$mail->From     = $from;
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
$mail->AddAddress('sdvx.flower@ezweb.ne.jp');
$mail->AddAddress('serizawa.yuuki@ezweb.ne.jp');
$mail->AddAddress('koyamaryoma@gmail.com');
$mail->AddAddress('lilac-stars@ezweb.ne.jp');

if( !$mail -> Send() ){
    $message  = "Message was not sent<br/ >";
    $message .= "Mailer Error: " . $mailer->ErrorInfo;
} else {
    $message  = "Message has been sent";
}

echo $message;