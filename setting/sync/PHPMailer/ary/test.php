<?php
require '../PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = "ssl://smtp.gmail.com";
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->Username = "arykurniadi@gmail.com";
$mail->Password = "sevenfold1725$$";
$mail->setFrom('arykurniadi@gmail.com', 'First Last');
$mail->addReplyTo('replyto@example.com', 'First Last');
$mail->addAddress('dokumentasi.nusastyle@gmail.com', 'John Doe');
$mail->Subject = 'PHPMailer SMTP test';
// $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
$mail->msgHTML('testing');
$mail->AltBody = 'This is a plain-text message body';
$mail->addAttachment('images/phpmailer_mini.png');

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}

?>