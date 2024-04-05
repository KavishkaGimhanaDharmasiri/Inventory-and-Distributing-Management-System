<?php

require 'vendor/autoload.php';
require('vendor/phpmailer/phpmailer/src/Exception.php');
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

// Assuming you have obtained $user_id, $firstname, and $password after user registration

// Sender's email address
$sender_email = "prolinkpc02@gmail.com";

// Recipient's email address (user's email)
$user_email = "prolinkpc2@gmail.com"; // Assuming $email contains the user's email address

// Create a PHPMailer instance
$mail = new PHPMailer;

// Set the mailer to use SMTP
$mail->isSMTP();

// Enable SMTP debugging (remove for production)
$mail->SMTPDebug = 2;

// Set the SMTP server
$mail->Host = 'smtp.gmail.com';

// Set SMTP authentication
$mail->SMTPAuth = true;

// SMTP username and password
$mail->Username = 'prolinkpc02@gmail.com';
$mail->Password = 'prolinkpc02@gmail@1234567890';

// Enable TLS encryption
$mail->SMTPSecure = 'tls';

// Set the port
$mail->Port = 587;

// Set the sender and recipient addresses
$mail->setFrom($sender_email, 'YourSite');
$mail->addAddress($user_email, "");

// Set the email subject and body
$mail->Subject = 'Welcome to YourSite';
$mail->Body = "Dear \n\n"
            . "Thank you for registering with YourSite.\n"
            . "Your username is:"
            . "Your generated password is\n\n"
            . "Please keep your login details secure.\n\n"
            . "Best regards,\nYourSite Team";

// Send the email
if (!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
?>
