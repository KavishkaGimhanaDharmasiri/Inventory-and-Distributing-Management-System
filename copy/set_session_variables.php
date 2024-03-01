<?php
session_start();

// Set session variables based on the Ajax request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['totalAmount'] = $_POST['totalAmount'];
    $_SESSION['paymentAmount'] = $_POST['paymentAmount'];
    $_SESSION['balance'] = $_POST['balance'];
    
    // Optionally, you can send a response back to the JavaScript if needed
    echo "Session variables set successfully.";
}
?>
