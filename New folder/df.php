<?php
// Include your database connection file
include("db_connection.php");

// Retrieve order details and total amount from session
session_start();
$orderDetails = $_SESSION['order_details'] ?? [];
$totalAmount = $_SESSION['total_amount'] ?? 0;
$selectedPaymentMethod = $_SESSION['selected_payment_method'] ?? '';
var_dump($_SESSION['selected_payment_method'] ?? '');
echo $selectedPaymentMethod;
echo $totalAmount;
?>