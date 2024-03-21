<?php
// Include your database connection code
include("db_connection.php");
$con = $connection;

// Fetch the payment type and other necessary data from the request
$paymentType = $_GET['paymentType'] ?? '';
$paymentAmount = $_GET['paymentAmount'] ?? 0;

// Validate and sanitize user input (example: ensure $paymentType is one of the allowed values)
$allowedPaymentTypes = ['cash', 'check', 'credit'];
if (!in_array($paymentType, $allowedPaymentTypes)) {
    // Return an error response
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid payment type']);
    exit;
}

// You need to implement the logic to get the price based on the payment type
// Example: Fetch the corresponding price from the database table

// For demonstration purposes, let's assume you have a 'product' table with columns cashPrice, checkPrice, creditPrice
// You should replace this with your actual database structure and query
$query = "SELECT $paymentType FROM product WHERE main_cat = 'your_main_category' AND sub_cat = 'your_sub_category'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Calculate the total amount based on the retrieved price and payment amount
    $price = $row[$paymentType] ?? 0;
    $totalAmount = $price * $paymentAmount;

    // Return the total amount as a response to the AJAX request
    echo json_encode(['totalAmount' => $totalAmount]);
} else {
    // Handle the case where the query doesn't return valid results
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => mysqli_error($con)]);
}

mysqli_close($con);
?>
