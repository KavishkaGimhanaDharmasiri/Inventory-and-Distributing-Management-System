<?php
session_start();
include("db_connection.php");
require_once('den_fun.php');
require_once('notification_area.php');
if (!isset($_SESSION['index_visit']) ||  !isset($_SESSION['option_visit']) || !isset($_SESSION["route_id"]) || !isset($_SESSION["state"])) {

    acess_denie();
    exit();
} else {
    $_SESSION['view_order_visit'] = true;
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

            <?php
            topnavigation();
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
        // Your database connection
        include 'db_connection.php';

        // Assuming $route_id is already defined
        $route_id = 4; // Example route ID

        // Handle the search AND DATE_FORMAT(p.ord_date, '%Y-%m') ='$currentmonth'
        $search_query = "";
        $search_input = "";
        if (isset($_GET['search'])) {
            $search = $connection->real_escape_string($_GET['search']);
            $search_input = $search;
            $search_query = "AND p.store_name LIKE '%$search%'";
        }
        $currentmonth = date('Y-m');
        $sql = "SELECT MAX(p.ord_date) as ord_date, p.store_name, o.main_cat, o.sub_cat, SUM(o.order_count) AS total_count
        FROM orders o 
        LEFT JOIN primary_orders p ON o.ord_id = p.ord_id 
        WHERE p.route_id = $route_id AND DATE_FORMAT(p.ord_date, '%Y-%m') ='$currentmonth'
        $search_query
        GROUP BY p.store_name, o.main_cat, o.sub_cat
        ORDER BY p.store_name, ord_date DESC";

        $result = $connection->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $currentStore = "";
                $currentMainCat = "";
                $currentmonth = date('Y-m');
                echo '<input type="text" id="search" name="search" placeholder="Search by store name..." value="' . $search_input . '" style="margin-bottom: 10px;">';
                echo "<h4 style='color:red;'>Recent Customer Orders</h4>";
                echo '<div id="storeList" ></div>';
                echo '<div id="result" >';
                $previous_orders_displayed = false;
                while ($row = $result->fetch_assoc()) {
                    $sale_date = $row['ord_date']; // Assuming $row['payment_date'] contains '2024-02-20'
                    $year_month = date('Y-m', strtotime($sale_date));
                    if ($year_month == $currentmonth) {
                        // Start of a new store, close the previous div if not the first one
                        if ($currentStore != $row["store_name"]) {
                            if ($currentStore != "") {
                                echo "</div>"; // Close previous store's details
                            }
                            $currentStore = $row["store_name"];
                            echo '<div class="store">';

                            echo "<div class='store-name' onclick='showStore(\"$currentStore\")'>$currentStore<i class='fa fa-angle-down' style='float:right; font-size:20px; font-weight:bold;'></i></div>";
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
                            echo '<h4 style="color:red;">Previous Customer Orders</h4>';
                            $previous_orders_displayed = true; // Set the flag to true after displaying the header
                        }
                        // Start of a new store, close the previous div if not the first one
                        if ($currentStore != $row["store_name"]) {
                            if ($currentStore != "") {
                                echo "</div>"; // Close previous store's details
                            }
                            $currentStore = $row["store_name"];
                            echo '<div class="store">';

                            echo "<div class='store-name' onclick='showStore(\"$currentStore\")'>$currentStore<i class='fa fa-angle-down' style='float:right; font-size:20px; font-weight:bold;'></i></div>";
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
                    }
                }
                // Close the last div
                echo "</div>"; // Close last store's details
                echo "</div>"; // Close last store

            } else {
                echo "0 results";
            }
        } else {
            echo "Error: " . $connection->error;
        }

        ?>


    </div>

    <script src="navigation.js"></script>
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "150px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }

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
    </script>

</body>

</html>