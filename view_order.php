<?php
session_start();
include("db_connection.php");

// Fetch orders from the database
$query = "SELECT * FROM payment ORDER BY ord_id DESC";
$result = mysqli_query($connection, $query);

// Check if there are any orders
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Display each order in a separate div
        echo '<div class="order-container" id="order_' . $row['ord_id'] . '">';
        echo '<h3>Order ID: ' . $row['ord_id'] . '</h3>';
        echo '<p>Route ID: ' . $row['route_id'] . '</p>';
        echo '<p>Store Name: ' . $row['store_name'] . '</p>';
        echo '<p>Total: Rs.' . $row['total'] . '</p>';
        echo '<p>Payment Date: ' . $row['payment_date'] . '</p>';
        echo '<p>Payment Method: ' . $row['payment_method'] . '</p>';
        echo '<p>Pay Period: ' . $row['pay_period'] . '</p>';
        echo '<p>Payment Amount: Rs.' . $row['payment_amout'] . '</p>';
        echo '<p>Balance: Rs.' . $row['balance'] . '</p>';
        
        // Add a link to toggle the visibility of the div
        echo '<a href="javascript:void(0);" class="toggle-link" onclick="toggleVisibility(' . $row['ord_id'] . ');">Show More</a>';
        
        
        // Add a link to view the associated PDF
        $pdf_link = "view_pdf.php?ord_id=" . $row['ord_id'];
        echo '<p><a href="' . $pdf_link . '" target="_blank">View PDF</a></p>';
        
        echo '</div>';
    }
} else {
    // If there are no orders
    echo '<p>No orders found.</p>';
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background: radial-gradient(circle, rgba(76,175,80,1) 0%, rgba(247,252,248,1) 0%, rgba(250,253,251,1) 23%, rgba(252,254,253,1) 36%, rgba(255,255,255,1) 47%, rgba(246,251,246,1) 59%, rgba(228,243,229,1) 68%, rgba(171,218,173,1) 100%, rgba(76,175,80,1) 100%);
            padding: 0;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-size: cover;
            margin: 8px;
            display:inline;
        }

        .order-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            width: 250px;
            background-color: #f9f9f9;
            border-radius: 15px;
            font-size: 10pt;
            overflow: hidden; /* Add overflow property */
            max-height: 23%; /* Set max-height to reveal 60% initially */
            position: relative; /* Set position to relative */
            backdrop-filter: blur(15%);
        }

        .order-container h3 {
            color: #333;
        }

        .order-container p {
            margin: 12px;
        }

        .toggle-link {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 100%;
            text-align: center;
            transform: translateX(-50%);
            backdrop-filter: blur(10px);
            height: 25px;
            padding-bottom: 0px;

        }
        .order-container a {
            color: #0066cc;
            text-decoration: none;
        }

        .order-container a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <script>
        function toggleVisibility(ordId) {
            var orderDiv = document.getElementById('order_' + ordId);
            orderDiv.style.maxHeight = (orderDiv.style.maxHeight === '23%') ? '100%' : '23%';
        }
    </script>
</body>
</html>
