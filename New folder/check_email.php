<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include your database connection file
require_once('email_sms.php');
include("db_connection.php");

ob_end_flush();




function generateRandomCode($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $code .= $characters[$randomIndex];
    }

    return $code;
}


function sendVerificationEmail($email, $code, $user, $firstname) {
    $subject = "Password Change Request";
    $body = "Verify your Email address\n\n Enter Below Verification Code to Continue Change Password \n\nVerification code is :  " . $code . "\n\nDo Not Share with Others\nThank You!...\n\nRegards,\nLotus Electicals (PVT)LTD";

    return sendmail($subject, $body, $email, $firstname);
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the POST data
    $email = $_POST["email"];

    // Validate the email against the database
    $query = "SELECT email FROM users WHERE email='$email'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) == 1) {
      
        $response = array("isValid" => true);
       
    } else {
        // Email is not valid
        $response = array("isValid" => false);
    }

    // Send the JSON response
    header("Content-Type: application/json");
    echo json_encode($response);

    // Close the database connection
    mysqli_close($connection);

   if ($response["isValid"]) {
        // Generate verification code
        $code = generateRandomCode();

        // Send verification email
        $emailSent = sendVerificationEmail($email, $code, $user, $firstname);
error_log("Code: $code");
    error_log("Email Sent: " . ($emailSent ? 'Yes' : 'No'));
        // You can log or handle the result of the email sending here
        i
    }
}
?>
