<?php
session_start();
require_once "db_connection.php";
require_once('den_fun.php');
// Include your database connection file

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id'])) {
    acess_denie();
    exit();
} else {
    $_SESSION['sale_order_visit'] = true;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="mobile.css">
    <style>
        .order-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            overflow-y: auto;
            overflow: visible;
            margin-top: 5%;

        }

        .order-date {
            background: linear-gradient(303deg, #20f96d, #eefeee, #53fe53);
            background-size: 180% 180%;
            animation: gradient-animation 6s ease infinite;
            border-radius: 8px;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            color: black;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <?php
            if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                // Generate back navigation link using HTTP_REFERER
                echo '<a href="' . $_SERVER['HTTP_REFERER'] . '" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
            } else {
                // If no referrer is set, provide a default back link
                echo '<a href="javascript:history.go(-1);" class="back-link" style="float:left; font-size:30px;"><i class="fa fa-angle-left"></i></a>';
            }
            ?>
            <div id="mySidepanel" class="sidepanel">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                <a href="#">About</a>
                <a href="#">Services</a>
                <a href="#">Clients</a>
                <a href="#">Contact</a>
            </div>

            <a href="javascript:void(0);" class="icon" onclick="openNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <?php
        include 'db_connection.php';
        $route_id = $_SESSION['route_id'];
        $query = "SELECT * FROM payment  WHERE route_id= $route_id ORDER BY ord_id DESC";
        $result = mysqli_query($connection, $query);

        // Check if there are any orders
        if (mysqli_num_rows($result) > 0) {
            echo '<input type="text" id="orderSearch" onkeyup="searchOrders()" placeholder="Search for Order Date Try Like This 2020-04-12..." style="margin-top:5%;>';
            echo '<div id="orderContainer">';
            while ($row = mysqli_fetch_assoc($result)) {
                // Display each order in a separate div
                echo '<div class="order-container" id="order_' . $row['ord_id'] . '">';
                echo '<div class="order-date" onclick="toggleOrderDetails(' . $row['ord_id'] . ')">' . $row['payment_date'] . '</div>';
                echo '<div class="order-details" id="order_details_' . $row['ord_id'] . '" style="display:none;">';
                echo '<h3>Order ID: ' . $row['ord_id'] . '</h3>';
                echo '<p>Route ID: ' . $row['route_id'] . '</p>';
                echo '<p>Store Name: ' . $row['store_name'] . '</p>';
                echo '<p>Total: Rs.' . $row['total'] . '</p>';
                echo '<p>Payment Date: ' . $row['payment_date'] . '</p>';
                echo '<p>Payment Method: ' . $row['payment_method'] . '</p>';
                echo '<p>Pay Period: ' . $row['pay_period'] . '</p>';
                echo '<p>Payment Amount: Rs.' . $row['payment_amout'] . '</p>';
                echo '<p>Balance: Rs.' . $row['balance'] . '</p>';
                $pdf_path = "pdf/order_" . $row['ord_id'] . ".pdf";
                if (file_exists($pdf_path)) {
                    echo '<p><a href="' . $pdf_path . '" download style="color:blue;float:right;">Download Sales Receipt</a></p><br>';
                } else {
                    echo '<p>No sales receipt available</p>';
                }
                echo '</div>'; // End of order-details
                echo '</div>'; // End of order-container
            }
            echo '</div>'; // End of orderContainer
        }
        ?>
    </div>


    <script>
        function toggleOrderDetails(orderId) {
            var orderDetails = document.getElementById('order_details_' + orderId);
            if (orderDetails.style.display === 'none') {
                orderDetails.style.display = 'block';
            } else {
                orderDetails.style.display = 'none';
            }
        }

        function searchOrders() {
            // Declare variables
            var input, filter, container, orders, orderDate, i, txtValue;
            input = document.getElementById('orderSearch');
            filter = input.value.toUpperCase();
            container = document.getElementById('orderContainer');
            orders = container.getElementsByClassName('order-container');

            // Loop through all orders, and hide those that don't match the search query
            for (i = 0; i < orders.length; i++) {
                orderDate = orders[i].getElementsByClassName('order-date')[0];
                txtValue = orderDate.textContent || orderDate.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    orders[i].style.display = '';
                } else {
                    orders[i].style.display = 'none';
                }
            }
        }

        function openNav() {
            document.getElementById("mySidepanel").style.width = "150px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }
    </script>

</body>

</html>