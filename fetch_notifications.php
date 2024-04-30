<?php
include 'db_connection.php';

if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'get_notifications') {
        $currentmonth = date('Y-m');
        // Fetch notifications
        $sql = "SELECT * FROM payment WHERE balance > 50000 AND DATE_FORMAT(payment_date, '%Y-%m') = '$currentmonth'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<p>Store: " . $row["store_name"] . " - Balance: " . $row["balance"] . "</p>";
            }
            echo '<a herf="#" style="cursor:pointer;-webkit-user-select:none;font-size:12px;color:blue;">send massage</a>';
        } else {
            echo "No notifications";
        }
    }
}

$connection->close();
