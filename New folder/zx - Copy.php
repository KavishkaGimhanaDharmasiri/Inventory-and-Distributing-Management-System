<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('vendor/phpmailer/phpmailer/src/Exception.php');
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

// Assuming you have obtained $user_id, $firstname, and $password after user registration

// Sender's email address (your Gmail address)
$sender_email = "prolinkpc02@gmail.com";

// Recipient's email address (user's email)
$user_email = "prolinkpc2@gmail.com"; // Assuming $email contains the user's email address

// Your Gmail credentials
$smtp_username = "prolinkpc02@gmail.com";
$smtp_password = "cvbf newu ycke qikf"; // Use the App Password if you generated one

// Create a PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = 0;

    // Set mailer to use SMTP
    $mail->isSMTP();

    // Specify the SMTP server
    $mail->Host = 'smtp.gmail.com';

    // Enable SMTP authentication
    $mail->SMTPAuth = true;

    // SMTP username and password
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;

    // Enable TLS encryption
    $mail->SMTPSecure = 'tls';

    // Set the port
    $mail->Port = 587;
    $firstname="this";

    // Set the sender and recipient addresses
    $mail->setFrom($sender_email, 'YourSite');
    $mail->addAddress($user_email, $firstname);

    // Set the email subject and body
    $mail->Subject = 'Welcome to YourSite';
    $mail->Body = "Dear $firstname,\n\n"
                . "Thank you for registering with YourSite.\n"
                . "Your username is: $firstname\n"
                . "Your generated password is: "
                . "Please keep your login details secure.\n\n"
                . "Best regards,\nYourSite Team";

    // Send the email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}
?>
