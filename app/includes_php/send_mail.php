<?php

$mail             = new PHPMailer();

$mail->IsHTML(true);
$mail->Body = $body;

$mail->IsSMTP(); // telling the class to use SMTP
//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                             // 1 = errors and messages
                                             // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the server
$mail->Host       = "mail.simpleconvention.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Username   = "server@simpleconvention.com";  // GMAIL username
$mail->Password   = "crazyphpgod99";            // GMAIL password

$mail->SetFrom('server@simpleconvention.com', 'Server');
$mail->AddReplyTo("server@simpleconvention.com","Server");
$mail->Subject    = $subject;
$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

if (isset($attachment)) {
    $mail->AddAttachment($attachment);
}

if (isset($address)) {
    $mail->AddAddress($address, "");
}

if (isset($email_bcc_recipients)) {
    foreach ($email_bcc_recipients as $recipient) :
        $mail->AddBCC($recipient['email'], $recipient['first_name'] . ' ' . $recipient['last_name']);
    endforeach;
}

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
}