<?php
session_start();

// Set session variables based on the Ajax request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['settlepaymentAmount'] = $_POST['settlepaymentAmount'];
    $_SESSION['settlebalance'] = $_POST['settlebalance'];

    // Optionally, you can send a response back to the JavaScript if needed
    echo "Session variables set successfully.";
}
