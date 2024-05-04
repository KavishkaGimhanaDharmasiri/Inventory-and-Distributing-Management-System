<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/src/SMTP.php';


function sendmail($Subject, $body, $user, $firstname)
{

    $sender_email = "prolinkpc02@gmail.com";

    // Recipient's email address (user's email)
    $user_email = $user; // $email contains the user's email address

    // Your Gmail credentials
    $smtp_username = "prolinkpc02@gmail.com";
    $smtp_password = "ypxt zbdg wigk bbkc"; // Use the App Password if you generated one

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

        // Set the sender and recipient addresses
        $mail->setFrom($sender_email, 'Lotus Electicals');
        $mail->addAddress($user_email, $firstname);



        // Set the email subject and body
        $mail->Subject = $Subject;
        $mail->Body = $body;

        $mail->send();
    } catch (Exception $e) {
        echo $e;
        echo '<script> alert("Message could not be sent. No Internet Connection Found. Please Go Online");</script>';
    }
}

function sendsms($number, $message)
{
    $apiEndpoint = 'https://app.notify.lk/api/v1/send';

    // Replace these values with your actual user ID, API key, and sender ID
    $userId = '26835';
    $apiKey = 'IzqyTBbXpilTmaIBIIvA';
    $senderId = 'NotifyDEMO';


    // Get custom content from the form or any source
    //$number = $modifiedNumber; // Assuming you have a form field named 'number'
    //$message = $body; // Assuming you have a form field named 'message'

    // Prepare the API URL with parameters
    $apiUrl = "$apiEndpoint?user_id=$userId&api_key=$apiKey&sender_id=$senderId&to=$number&message=$message";

    // Make the HTTP request
    $response = file_get_contents($apiUrl);
}
function generateRandomCode($length = 5)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $code .= $characters[$randomIndex];
    }
    return $code;
}
