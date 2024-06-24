<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php"); // Include your database connection script

if (isset($_POST['store_name'])) {
    $store_name = $_POST['store_name'];
    $current_month = date('Y-m');

    $query = "SELECT * FROM primary_orders WHERE store_name = '$store_name' AND DATE_FORMAT(ord_date, '%Y-%m') = '$current_month' AND order_type = 'sale'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['order_exists' => true]);
    } else {
        echo json_encode(['order_exists' => false]);
    }
} else {
    echo "data is not set";
}
