<?php
// test_email.php

// Include PHPMailer
require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// === CONFIGURE THESE SETTINGS ===
$SMTP_HOST = 'smtp.gmail.com';
$SMTP_PORT = 587;
$SMTP_USERNAME = '';
$SMTP_PASSWORD = ''; // not your Gmail password
$SMTP_SECURE = 'tls';


$FROM_EMAIL = 'florals.order@gmail.com';
$FROM_NAME  = 'Florals';
$TO_EMAIL   = ''; // where to send test
$TO_NAME    = 'Test User';
// =================================

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = $SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = $SMTP_USERNAME;
    $mail->Password   = $SMTP_PASSWORD;
    $mail->SMTPSecure = $SMTP_SECURE;
    $mail->Port       = $SMTP_PORT;

    // Recipients
    $mail->setFrom($FROM_EMAIL, $FROM_NAME);
    $mail->addAddress($TO_EMAIL, $TO_NAME);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'PHPMailer Test from Floral Shop';
    $mail->Body    = '<h2>Hello from your Floral Shop site!</h2><p>This is a test email using PHPMailer.</p>';
    $mail->AltBody = 'Hello from your Floral Shop site! This is a test email using PHPMailer.';

    $mail->send();
    echo "<p style='color:green;'>✅ Message has been sent successfully to {$TO_EMAIL}</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
}
