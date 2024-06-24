<?php
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");

// Suppress error display and log them
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/error.log');

// Retrieve selected store name and sales order date from the request
$selectedStore = $_GET['store'];
$selectedDate = $_GET['date'];

// Query to retrieve remaining balance from the payment table
list($selectedYear, $selectedMonth) = explode('-', $selectedDate);

// Prepare and execute the SQL query
$sql = "SELECT ord_id, balance FROM payment WHERE store_name = ? AND YEAR(payment_date) = ? AND MONTH(payment_date) = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, 'sii', $selectedStore, $selectedYear, $selectedMonth);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Fetch the remaining balance
    $row = mysqli_fetch_assoc($result);
    $remainingBalance = $row['balance'];
    $ord_id = $row['ord_id'];
    $_SESSION['settleord_id'] = $ord_id;

    // Return the remaining balance as JSON
    echo json_encode(['balance' => $remainingBalance]);
} else {
    // No record found, return error message
    echo json_encode(['error' => 'No balance found for the selected store and date']);
}

mysqli_close($connection);
