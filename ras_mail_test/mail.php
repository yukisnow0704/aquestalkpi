<?php
require_once ( 'PHPMailerAutoload.php' );
$subject = "タイトル";
$body = "メール本文";
$fromname = "me";
$from = "yukisnow0704@gmail.com";
$smtp_user = "yukisnow0704@gmail.com";
$smtp_password = "1192snow";

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
$mail->AddAddress('yukisnow0704@gmail.com');

if( !$mail -> Send() ){
    $message  = "Message was not sent<br/ >";
    $message .= "Mailer Error: " . $mailer->ErrorInfo;
} else {
    $message  = "Message has been sent";
}

echo $message;