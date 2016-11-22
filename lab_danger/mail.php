<?php
require_once ( 'PHPMailerAutoload.php' );
$subject = "タイトル";
$body = "メール本文";
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
$mail->Host = "ssl:smtp.gmail.com";
$mail->Port = 465;
$mail->IsHTML(false);
$mail->Username = $smtp_user;
$mail->Password = $smtp_password; 
$mail->SetFrom($smtp_user);
$mail->From     = $from;
$mail->Subject = $subject;
$mail->Body = $body;
$mail->AddAddress('yukisnow0704@gmail.com');

if( !$mail -> Send() ){
    $message  = "Message was not sent<br/ >";
    $message .= "Mailer Error: " . $mailer->ErrorInfo;
} else {
    $message  = "Message has been sent";
}

echo $message;