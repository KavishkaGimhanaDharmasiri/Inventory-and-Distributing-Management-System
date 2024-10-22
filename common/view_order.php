<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
if (!isset($_SESSION['index_visit']) ||  !isset($_SESSION['option_visit']) || !isset($_SESSION["state"])) {
    acess_denie();
    exit();
} else {
    $_SESSION['view_order_visit'] = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <title>Customer Orders</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <style>
        .store-name {
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

        .order-details {
            background-color: #fff;
            border-radius: 15px;
            padding: 10px;
            overflow-y: auto;
            overflow: visible;
            border-top-left-radius: 1px;
            border-top-right-radius: 1px;
            border-top: 1px solid white;
            color: black;
            -ms-user-select: none;
            -webkit-user-select: none;
            user-select: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

            <a href="javascript:void(0)" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" onclick="back()" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">customer orders</span></a>

        </div>
        <?php

        date_default_timezone_set('Asia/Colombo');
        $currentDateTime = new DateTime(); // Get the current date and time

        $cur_date = $currentDateTime->format('Y-m');
        // Assuming $route_id is already defined
        $route_id = $_SESSION["route_id"]; // Example route ID

        // Handle the search AND DATE_FORMAT(p.ord_date, '%Y-%m') ='$currentmonth'
        $search_query = "";
        $search_input = "";
        if (isset($_GET['search'])) {
            $search = $connection->real_escape_string($_GET['search']);
            $search_input = $search;
            $search_query = "AND p.store_name LIKE '%$search%'";
        }
        $currentmonth = $cur_date;
        $sql = "";
        $select_store = "";

        if ($_SESSION["state"] === 'seller') {
            $sql = "SELECT  MAX(p.ord_date) as ord_date,max(p.ord_id) as ord_id, p.store_name, o.main_cat, o.sub_cat, min(o.order_count) AS total_count
        FROM orders o 
        LEFT JOIN primary_orders p ON p.ord_id = o.ord_id 
        WHERE p.route_id = $route_id AND p.order_type='customer'
        GROUP BY p.store_name, o.main_cat, o.sub_cat
        ORDER BY ord_date DESC";
        } elseif ($_SESSION["state"] === 'admin') {
            $sql = "SELECT  MAX(p.ord_date) as ord_date,max(p.ord_id) as ord_id, p.store_name, o.main_cat, o.sub_cat, min(o.order_count) AS total_count
        FROM orders o 
        LEFT JOIN primary_orders p ON p.ord_id = o.ord_id 
        WHERE p.order_type='customer' 
        GROUP BY p.store_name, o.main_cat, o.sub_cat
        ORDER BY ord_date DESC;";
        } elseif ($_SESSION["state"] === 'wholeseller') {
            $user_id = $_SESSION['user_id'];
            $customerQuery = "SELECT sto_name FROM customers WHERE user_id=$user_id";
            $customerResult = mysqli_query($connection, $customerQuery);

            // Check for database query failures
            if ($customerResult) {
                if ($row1 = mysqli_fetch_assoc($customerResult)) {
                    $select_store = $row1['sto_name'];
                }
            }
            $sql = "SELECT  MAX(p.ord_date) as ord_date,max(p.ord_id) as ord_id, p.store_name, o.main_cat, o.sub_cat, min(o.order_count) AS total_count
        FROM orders o 
        LEFT JOIN primary_orders p ON p.ord_id = o.ord_id 
        WHERE p.route_id = $route_id AND p.order_type='customer' AND p.store_name='$select_store'
        GROUP BY p.store_name, o.main_cat, o.sub_cat
        ORDER BY ord_date DESC";
        }


        $result = $connection->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {

                $currentStore = "";
                $currentDate = "";
                $currentMainCat = "";
                $currentmonth = date('Y-m');
                if ($_SESSION["state"] === 'seller' || $_SESSION["state"] === 'admin') {
                    echo '<input type="text" id="search" name="search" placeholder="Search by store name..." value="' . $search_input . '" style="margin-bottom: 10px;margin-top:5%;background-color:transparent;">';
                } elseif ($_SESSION["state"] === 'wholeseller') {
                    echo '<input type="text" id="orderSearch" onkeyup="searchOrders()" placeholder="Search for Order Date Try Like This 2020-04-12..." style="margin-top:5%;background-color:transparent;">';
                }
                echo "<h4 style='color:red;'>Recent Customer Orders</h4>";
                echo '<div id="storeList" ></div>';
                echo '<div id="result" >';
                $previous_orders_displayed = false;
                while ($row = $result->fetch_assoc()) {
                    $sale_date = $row['ord_date']; // Assuming $row['payment_date'] contains '2024-02-20'
                    $year_month = date('Y-m', strtotime($sale_date));
                    if ($year_month == $currentmonth) {

                        // Start of a new store, close the previous div if not the first one
                        if ($currentStore != $row["ord_id"]) {

                            if ($currentStore != "") {
                                echo "</div>"; // Close previous store's details
                            }
                            $currentStore = $row["ord_id"];
                            echo '<div class="store">';

                            echo "<div class='store-name' onclick='showStore(\"$currentStore\")'>{$row["store_name"]} - $sale_date<i class='fa fa-angle-down' style='float:right; font-size:20px; font-weight:bold;'></i></div>";
                            echo "<div id='$currentStore' class='order-details' style='display: none;'>"; // Initially hide order details
                            // Reset current main category
                            $currentMainCat = "";
                        }

                        // Display main category if it's changed
                        if ($currentMainCat != $row["main_cat"]) {
                            echo "<h4>Product Name: " . $row["main_cat"] . "</h4>";
                            $currentMainCat = $row["main_cat"];
                        }
                        echo '<ul>';
                        // Display order details
                        echo "<li> " . $row["sub_cat"] . " - units : " . $row["total_count"] . "</li>";
                        echo '</ul>';
                    } else {
                        if (!$previous_orders_displayed) {
                            $preo = $row['ord_date'];
                            echo "</div>";
                            echo '<h4 style="color:red;">Previous Customer Orders</h4>';
                            $previous_orders_displayed = true; // Set the flag to true after displaying the header
                        }
                        if ($currentStore != $row["ord_id"]) {
                            if ($currentStore != "") {
                                $preo = $row['ord_date'];
                                echo "</div>"; // Close previous store's details
                            }
                            $currentStore = $row["ord_id"];
                            echo '<div class="store">';

                            echo "<div class='store-name' onclick='showStore(\"$currentStore\")'>{$row["store_name"]} - $sale_date<i class='fa fa-angle-down' style='float:right; font-size:20px; font-weight:bold;'></i></div>";
                            echo "<div id='$currentStore'  class='order-details' style='display: none;'>"; // Initially hide order details

                            // Reset current main category
                            $currentMainCat = "";
                        }

                        // Display main category if it's changed
                        if ($currentMainCat != $row["main_cat"]) {
                            echo "<h4>Product Name: " . $row["main_cat"] . "</h4>";
                            $currentMainCat = $row["main_cat"];
                        }
                        echo '<ul>';
                        // Display order details
                        echo "<li> " . $row["sub_cat"] . " - units : " . $row["total_count"] . "</li>";
                        echo '</ul>';
                    }
                }
                // Close the last div
                echo "</div>"; // Close last store's details
                echo "</div>"; // Close last store
            } else {
                echo '<div class="container"><h4 style="text-align:center;">No Result found </h4></div>';
            }
        } else {
            echo "Error: " . $connection->error;
        }

        ?>


    </div>

    <script>
        function showStore(storeName) {
            const details = document.getElementById(storeName);
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
        }

        document.getElementById("search").addEventListener("input", function() {
            const searchValue = this.value.toLowerCase();
            const storeNames = document.querySelectorAll('.store-name');

            storeNames.forEach(function(store) {
                const storeName = store.innerText.toLowerCase();
                if (storeName.indexOf(searchValue) > -1) {
                    store.style.display = "block";
                } else {
                    store.style.display = "none";
                    document.getElementById(store.innerText).style.display = "none";
                }
            });
        });

        function back() {
            window.history.back();
        }
    </script>

</body>

</html>