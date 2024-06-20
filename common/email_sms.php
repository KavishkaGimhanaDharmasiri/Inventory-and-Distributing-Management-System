<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/phpmailer/phpmailer/src/SMTP.php';


function sendmail($Subject, $body, $user, $firstname)
{

    $sender_email = "prolinkpc702@gmail.com";

    // Recipient's email address (user's email)
    $user_email = $user; // $email contains the user's email address

    // Your Gmail credentials
    $smtp_username = "prolinkpc702@gmail.com";
    $smtp_password = "ypxt zbdg hjyu ioyr"; // Use the App Password if you generated one

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
    $userId = '56835';
    $apiKey = 'IzqyTghtyukiGhaIBIIvA';
    $senderId = 'NotifyDEMO';


    // Get custom content from the form or any source
    //$number = $modifiedNumber; // Assuming you have a form field named 'number'
    //$message = $body; // Assuming you have a form field named 'message'

    // Prepare the API URL with parameters
    $apiUrl = "$apiEndpoint?user_id=$userId&api_key=$apiKey&sender_id=$senderId&to=$number&message=$message";

    // Make the HTTP request
    $response = file_get_contents($apiUrl);
}

function sendremsms()
{
    $route_id = $_SESSION['route_id'];
    include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
    $sqlq = "SELECT sto_tep_number,store_name,balance,payment_date from customers c left join payment p on c.user_id=p.user_id
        WHERE p.balance > 0
          AND p.payment_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) - INTERVAL DAY(CURDATE())-1 DAY
          AND p.payment_date < DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY) AND p.route_id=$route_id;";
    $results = $connection->query($sqlq);
    if ($results) {
        while ($rowSrore = mysqli_fetch_assoc($results)) {
            $sto_telephone = $rowSrore['sto_tep_number'];
            $sto_name = $rowSrore['store_name'];
            $sto_balance = $rowSrore['balance'];
            $sto_payment_date = $rowSrore['payment_date'];

            $message = "Order Made on $sto_payment_date by $sto_name have $sto_balance remaining to Settle during Last month.
            Please Settle the Above Amount As soon as Posible.\n\nThank You.\nLotus Electicals (PVT)LTD.";
            sendsms($sto_telephone, $message);
            sleep(1); //sending massage
        }

        try {
            $currentmonth = date('Y-m-d');
            $state = 'yes';
            $id = 1;
            $pdo->beginTransaction();
            $query = "UPDATE notification SET not_date=:not_date, state=:state  WHERE not_id=:not_id";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':not_date', $currentmonth);
            $stmt->bindParam(':state', $state);
            $stmt->bindParam(':not_id', $id);

            $stmt->execute();
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo '<script>alert(' . $e->getMessage() . ');</script>';
        }
    }
}
