<?php
include("db_connection.php");

// Fetch orders with associated payment details
$sql = "SELECT YEAR(payment_date) AS year, MONTHNAME(payment_date) AS month, 
               ord_id, store_name, total, payment_method, payment_date, payment_amout
        FROM payment
        ORDER BY year DESC, MONTH(payment_date) DESC";

$result = $connection->query($sql);

$ordersData = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $year = $row['year'];
        $month = $row['month'];

        if (!isset($ordersData[$year])) {
            $ordersData[$year] = array();
        }

        if (!isset($ordersData[$year][$month])) {
            $ordersData[$year][$month] = array();
        }

        $ordersData[$year][$month][] = array(
            'ord_id' => $row['ord_id'],
            'store_name' => $row['store_name'],
            'total' => $row['total'],
            'payment_method' => $row['payment_method'],
            'payment_date' => $row['payment_date'],
            'payment_amount' => $row['payment_amout']
        );
    }
}

$conn->close();

// Output JSON response
header('Content-Type: application/json');
echo json_encode($ordersData);

?>
