<?php
session_start();
include("db_connection.php");
if (isset($_SESSION['order_details']['main_category'])) {
    $mainCategory = $_SESSION['order_details']['main_category'];
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_payment"])) {
    // Extract data from the form
    $totalAmount = $_POST['total_amount'];
    $mainCategory = $_POST['main_category'];
    $subcategories = explode(',', $_POST['subcategories']);
    $counts = explode(',', $_POST['counts']);

    // Extract payment details
    $paymentType = $_POST['payment_type'];
    $paymentPeriod = $_POST['payment_period'];
    $paymentAmount = $_POST['payment_amount'];
    $balance = $_POST['balance'];

    // Insert payment data into the database
    $query = "INSERT INTO payment (store_name, total, payment_date, $paymentType, pay_period, balance) VALUES (?, ?, NOW(), ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "sdsdd", $mainCategory, $totalAmount, $paymentAmount, $paymentPeriod, $balance);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Other necessary actions can be performed here

    // Redirect to a thank you page or any other page as needed
    header("Location: thank_you.php");
    exit();
}
} else {
    // Handle the case where 'main_category' is not set, perhaps redirect or show an error
    echo "Main category is not set in the session.";
    exit(); // Stop further execution
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_payment"])) {
    // Extract data from the form
    $totalAmount = $_POST['total_amount'];
    $mainCategory = $_POST['main_category'];
    $subcategories = explode(',', $_POST['subcategories']);
    $counts = explode(',', $_POST['counts']);

    // Extract payment details
    $paymentType = $_POST['payment_type'];
    $paymentPeriod = $_POST['payment_period'];
    $paymentAmount = $_POST['payment_amount'];
    $balance = $_POST['balance'];

    // Insert payment data into the database
    $query = "INSERT INTO payment (store_name, total, payment_date, $paymentType, pay_period, balance) VALUES (?, ?, NOW(), ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "sdsdd", $mainCategory, $totalAmount, $paymentAmount, $paymentPeriod, $balance);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Other necessary actions can be performed here

    // Redirect to a thank you page or any other page as needed
    header("Location: thank_you.php");
    exit();
}
?>
