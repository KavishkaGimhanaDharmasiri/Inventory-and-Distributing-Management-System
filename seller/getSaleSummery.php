<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");

// Suppress error display and log them
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/error.log');

// Retrieve selected store name and sales order date from the request
$selectedDate = $_GET['date'];

// Query to retrieve remaining balance from the payment table
list($selectedYear, $selectedMonth) = explode('-', $selectedDate);
$route_id = $_SESSION['route_id'];

// Prepare and execute the SQL query
$sql = "SELECT sum(total) sum1, sum(payment_amout) as sum2, SUM(CASE WHEN balance >= 0 THEN balance ELSE 0 END) AS sum3 FROM payment WHERE route_id=? AND YEAR(payment_date) = ? AND MONTH(payment_date) = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, 'iss', $route_id, $selectedYear, $selectedMonth);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Fetch the remaining balance
    $row = mysqli_fetch_assoc($result);
    $sales = $row['sum1'];
    $income = $row['sum2'];
    $outstanding = $row['sum3'];


    // Return the remaining balance as JSON
    echo json_encode(['sum1' => $sales, 'sum2' => $income, 'sum3' => $outstanding]);
} else {
    // No record found, return error message
    echo json_encode(['error' => 'No balance found for the selected store and date']);
}

mysqli_close($connection);
