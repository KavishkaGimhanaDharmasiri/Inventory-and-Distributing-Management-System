<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit'])) {

    exit();
} else {
    $_SESSION['sale_order_visit'] = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Orders</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/mobile.css">
    <link rel="stylesheet" href="/style/style.css">
    <style>
        .order-date {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            overflow-y: auto;
            overflow: visible;
            margin-top: 1%;
            border-bottom-left-radius: 1px;
            border-bottom-right-radius: 1px;
            font-weight: bold;
            background: linear-gradient(301deg, #3cba68, #fefefe, #ffffff);
            background-size: 180% 180%;
            animation: gradient-animation 7s ease infinite;
            color: black;
            cursor: pointer;
        }



        .order-info {
            display: flex;
            flex-wrap: wrap;
        }

        .order-info h3 {
            flex-basis: 100%;
            margin-top: 0;
        }

        .order-info p {
            flex-basis: 50%;
            margin: 0;
        }

        .order-details {
            background-color: #fff;
            border-radius: 15px;
            padding: 10px;
            overflow-y: auto;
            overflow: visible;
            border-top-left-radius: 1px;
            border-top-right-radius: 1px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: black;
            -ms-user-select: none;
            -webkit-user-select: none;
            user-select: none;


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
            // Generate back navigation link using HTTP_REFERER
            echo '<a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
            ?>


        </div>
        <?php
        $route_id = $_SESSION['route_id'];
        $query = "";
        if ($_SESSION["state"] === 'seller') {
            $query = "SELECT * FROM payment where route_id=$route_id ORDER BY ord_id DESC";
        } elseif ($_SESSION["state"] === 'wholeseller') {
            $user_id = $_SESSION['user_id'];
            $customerQuery = "SELECT sto_name FROM customers WHERE user_id=$user_id";
            $customerResult = mysqli_query($connection, $customerQuery);
            if ($customerResult) {
                if ($customerRow = mysqli_fetch_assoc($customerResult)) {
                    $customerName = $customerRow['sto_name'];
                }
            }
            $query = "SELECT * FROM payment where route_id=$route_id AND store_name='$customerName' ORDER BY ord_id DESC";
        } elseif ($_SESSION["state"] === 'admin') {
            $query = "SELECT * FROM payment ORDER BY ord_id DESC";
        }

        $result = mysqli_query($connection, $query);
        $currentmonth = date('Y-m');
        // Check if there are any orders
        if (mysqli_num_rows($result) > 0) {
            echo '<input type="text" id="orderSearch" onkeyup="searchOrders()" placeholder="Search for Order Date Try Like This 2020-04-12..." style="margin-top:5%;background-color:transparent;">';
            echo '<div id="orderContainer">';
            echo '<h4 style="color:red;">Recent Sale Orders</h4>';
            $previous_orders_displayed = false;
            while ($row = mysqli_fetch_assoc($result)) {
                $payment_date = $row['payment_date']; // Assuming $row['payment_date'] contains '2024-02-20'
                $year_month = date('Y-m', strtotime($payment_date));

                if ($year_month == $currentmonth) {
                    // Display each order in a separate div
                    echo '<div class="order-container" id="order_' . $row['ord_id'] . '">';
                    echo '<div class="order-date" onclick="toggleOrderDetails(' . $row['ord_id'] . ')">Sale Order Made On - ' . $row['payment_date'] . '<i class="fa fa-angle-down" style="float:right; font-size:20px; font-weight:bold;"></i></div>';
                    echo '<div class="order-details" id="order_details_' . $row['ord_id'] . '" style="display:none;">';
                    echo '<h4>Order ID: ' . $row['ord_id'] . '</h4>';

                    echo '<p>Store Name: ' . $row['store_name'] . '</p>';
                    echo '<p>Total: Rs.' . $row['total'] . '</p>';
                    echo '<p>Payment Date: ' . $row['payment_date'] . '</p>';
                    echo '<p>Payment Method: ' . $row['payment_method'] . '</p>';
                    if ($row['payment_method'] == "credit") {
                        echo '<p>Pay Period: ' . $row['pay_period'] . '</p>';
                    }
                    echo '<p>Payment Amount: Rs.' . $row['payment_amout'] . '</p>';
                    if ($row['balance'] < 0) {
                        echo '<p>Balance: Rs.' . $row['balance'] . '(Prepaid Amount)</p>';
                    } else {
                        echo '<p>Balance: Rs.' . $row['balance'] . '</p>';
                    }
                    $pdf_path = $_SERVER['DOCUMENT_ROOT'] . "/pdf/order_" . $row['ord_id'] . ".pdf";
                    if (file_exists($pdf_path)) {
                        echo '<p><a href="' . $pdf_path . '" download style="color:blue;float:right;">Download Sales Receipt</a></p><br>';
                    } else {
                        echo '<p style="color:red;float:right;">No sales receipt available</p><br><br>';
                    }

                    echo '</div>'; // End of order-details
                    echo '</div>'; // End of order-container
                } else {
                    if (!$previous_orders_displayed) {
                        echo '<h4 style="color:red;">Previous Sale Orders</h4>';
                        $previous_orders_displayed = true; // Set the flag to true after displaying the header
                    }
                    // Display each order in a separate div
                    echo '<div class="order-container" id="order_' . $row['ord_id'] . '">';
                    echo '<div class="order-date" onclick="toggleOrderDetails(' . $row['ord_id'] . ')">Sale Order Made On - ' . $row['payment_date'] . '<i class="fa fa-angle-down" style="float:right; font-size:20px; font-weight:bold;"></i></div>';
                    echo '<div class="order-details" id="order_details_' . $row['ord_id'] . '" style="display:none;">';
                    echo '<h4>Order ID: ' . $row['ord_id'] . '</h4>';

                    echo '<p>Store Name: ' . $row['store_name'] . '</p>';
                    echo '<p>Total: Rs.' . $row['total'] . '</p>';
                    echo '<p>Payment Date: ' . $row['payment_date'] . '</p>';
                    echo '<p>Payment Method: ' . $row['payment_method'] . '</p>';
                    if ($row['payment_method'] == "credit") {
                        echo '<p>Pay Period: ' . $row['pay_period'] . '</p>';
                    }
                    echo '<p>Payment Amount: Rs.' . $row['payment_amout'] . '</p>';
                    if ($row['balance'] < 0) {
                        echo '<p>Balance: Rs.' . $row['balance'] . '(Prepaid Amount)</p>';
                    } else {
                        echo '<p>Balance: Rs.' . $row['balance'] . '</p>';
                    }

                    $pdf_path = $_SERVER['DOCUMENT_ROOT'] . "/pdf/order_" . $row['ord_id'] . ".pdf";
                    if (file_exists($pdf_path)) {
                        echo '<p><a href="' . $pdf_path . '" download style="color:blue;float:right;">Download Sales Receipt</a></p><br>';
                    } else {
                        echo '<p style="color:red;float:right;">No sales receipt available</p><br><br>';
                    }

                    echo '</div>'; // End of order-details
                    echo '</div>'; // End of order-container

                }
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

        function back() {
            window.history.back();
        }
    </script>

</body>

</html>