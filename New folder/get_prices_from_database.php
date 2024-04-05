<?php
// Include your database connection file
include("db_connection.php");

// Fetch prices from the database
$query = "SELECT payment_method, price FROM prices_table";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}

$prices = [];
while ($row = mysqli_fetch_assoc($result)) {
    $prices[$row['payment_method']] = (float) $row['price'];
}

// Return prices as JSON
header('Content-Type: application/json');
echo json_encode($prices);

// Close the database connection
mysqli_close($connection);
?>
