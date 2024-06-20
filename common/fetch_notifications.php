<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");

if (!isset($_SESSION['route_id'])) {
    echo "Route ID is not set in session.";
    exit;
}

$route_id = $_SESSION['route_id'];

if (isset($_POST['action']) && $_POST['action'] === 'get_notifications') {
    // Fetch notifications
    $sql = "SELECT store_name, balance 
            FROM payment 
            WHERE balance > 0 
              AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) - INTERVAL DAY(CURDATE()) - 1 DAY 
              AND payment_date < DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY) 
              AND route_id = $route_id";

    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . htmlspecialchars($row["store_name"]) . " - Balance: " . htmlspecialchars($row["balance"]) . "</p>";
        }
        echo '<a href="javascript:void(0)" onclick="sendSMS(event)" style="cursor:pointer;font-size:11px;color:blue;">Send Message</a>';
    } else {
        echo "No notifications";
    }
} else {
    echo "Invalid action or action not set.";
}

$connection->close();
?>
<script>
    function sendSMS() {
        // Create an AJAX request
        var xhr = new XMLHttpRequest();

        // Define the PHP file and function to call
        var phpFile = "email_sms.php";
        var functionName = "sendremsms";

        // Prepare the data to send
        var data = new FormData();
        data.append('functionName', functionName);

        // Configure the AJAX request
        xhr.open("POST", phpFile, true);

        // Set the event handler to manage the response
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Do something with the response
                console.log(xhr.responseText);
            }
        };

        // Send the request
        xhr.send(data);
    }
</script>