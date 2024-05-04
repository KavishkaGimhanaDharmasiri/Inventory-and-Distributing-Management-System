<?php
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
// Retrieve selected store name and sales order date from the request
$selectedStore = $_GET['store'];
$selectedDate = $_GET['date'];

// Query to retrieve remaining balance from the payment table
list($selectedYear, $selectedMonth) = explode('-', $selectedDate);

// Query to retrieve remaining balance based on year and month
$sql = "SELECT balance FROM payment WHERE store_name = '$selectedStore' AND YEAR(payment_date) = '$selectedYear' AND MONTH(payment_date) = '$selectedMonth'";

$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) > 0) {
    // Fetch the remaining balance
    $row = mysqli_fetch_assoc($result);
    $remainingBalance = $row['balance'];

    // Return the remaining balance as JSON
    echo json_encode(['balance' => $remainingBalance]);
} else {
    // No record found, return error message
    echo json_encode(['error' => 'No balance found for the selected store and date']);
}

mysqli_close($connection);
