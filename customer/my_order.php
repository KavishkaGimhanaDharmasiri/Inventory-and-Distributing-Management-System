<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
if (!isset($_SESSION['index_visit']) || !isset($_SESSION['option_visit']) || $_SESSION["state"] != 'wholeseller') {
    acess_denie();
    exit();
} else {
    $_SESSION['create_order_visit'] = true;
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <title>My Orders</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">

    <style>
        .order-table {
            display: inline-block;
            margin-right: 20px;
            /* Adjust margin as needed */
        }

        /* Style for table headers */
        .order-table th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
        }

        /* Style for table rows */
        .order-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        h4 {
            margin-bottom: 0;
        }

        .order-details {
            display: none;
        }

        .order-date {
            cursor: pointer;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;


        }

        .box {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;

        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        h4 {
            text-align: left;
            color: black;
            margin-bottom: 0px;
            margin-top: 2px;
            padding: 10px;
            cursor: pointer;
        }

        .main-category-name {
            cursor: pointer;
        }


        li {
            font-weight: bold;
            padding: 5px;
        }

        li:hover {
            background-color: #3cba68;
        }

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
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <a href="javascript:void(0)" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" onclick="back()" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">My Orders</span></a>


        </div>
        <div class="containers" style="margin-top: 5%;">
            <?php
            $user_id = $_SESSION["user_id"];
            $store_quary = "SELECT c.sto_name FROM customers c LEFT JOIN users u ON u.user_id=c.user_id WHERE u.user_id=$user_id";
            $store_quary_result = mysqli_query($connection, $store_quary);


            while ($rows = mysqli_fetch_assoc($store_quary_result)) {
                $_SESSION["my_store"] = $rows["sto_name"];
            }
            $store_name = $_SESSION["my_store"];

            // Select all orders with details from primary_orders and orders tables for the given store name



            echo '<div class="box">';
            // Fetch pending orders
            $sql_pending = "SELECT p.ord_id, p.order_state, p.ord_date, o.main_cat, o.sub_cat, o.order_count 
                FROM primary_orders p 
                JOIN orders o ON p.ord_id = o.ord_id 
                WHERE p.store_name = '$store_name' AND order_type = 'customer' AND p.order_state = 'pending' 
                ORDER BY ord_date DESC";

            $result_pending = mysqli_query($connection, $sql_pending);

            // Initialize an array to store main categories and their related sub products for pending orders
            $mainCategories_pending = array();

            // Process pending orders
            while ($row_pending = mysqli_fetch_assoc($result_pending)) {
                $mainCat = $row_pending["main_cat"];
                $subCat = $row_pending["sub_cat"];
                $count = $row_pending["order_count"];
                $date = $row_pending["ord_date"];
                $_SESSION['date'] = $row_pending["ord_date"];;
                // If main category already exists in the array, append the sub product
                if (array_key_exists($mainCat, $mainCategories_pending)) {
                    // Check if the subcategory already exists, if yes, add the count
                    $found = false;
                    foreach ($mainCategories_pending[$mainCat] as &$subProduct) {
                        if ($subProduct['sub_cat'] === $subCat) {
                            $subProduct['order_count'] += $count;
                            $found = true;
                            break;
                        }
                    }
                    // If subcategory not found, add it to the array
                    if (!$found) {
                        $mainCategories_pending[$mainCat][] = array("sub_cat" => $subCat, "order_count" => $count);
                    }
                } else {
                    // If main category doesn't exist, create a new entry in the array
                    $mainCategories_pending[$mainCat] = array(array("sub_cat" => $subCat, "order_count" => $count));
                }
            }
            echo "<h4>Order Made on " . $_SESSION['date'] . " is Now on Pending";
            // Output pending orders
            echo "<div class='main-categories'>";
            foreach ($mainCategories_pending as $mainCat => $subProducts) {
                echo "<div class='main-category'>";
                echo '<h4 class="main-category-name" style="padding:8px; cursor:pointer; color:black;">' . $mainCat . '<i class="fa fa-angle-down" style="float:right;font-size:20px"></i></h4>';
                echo "<ul class='sub-categories' style='color:black;display: none;'>";
                foreach ($subProducts as $subProduct) {
                    echo "<li>{$subProduct['sub_cat']} <label style='float:right;'>{$subProduct['order_count']}</label></li>";
                }
                echo "</ul>";
                echo "</div>";
            }
            echo "</div>";
            echo '</div>'; // End of pending orders box
            echo "<br>";
            echo '<div class="box">';
            // Fetch complete orders
            $sql_complete = "SELECT p.ord_id, p.order_state, p.ord_date, o.main_cat, o.sub_cat, o.order_count 
                FROM primary_orders p 
                JOIN orders o ON p.ord_id = o.ord_id 
                WHERE p.store_name = '$store_name' AND order_type = 'customer' AND p.order_state = 'complete' 
                ORDER BY ord_date DESC";

            $result_complete = mysqli_query($connection, $sql_complete);

            // Initialize an array to store main categories and their related sub products for complete orders
            $mainCategories_complete = array();

            // Process complete orders
            while ($row_complete = mysqli_fetch_assoc($result_complete)) {
                $mainCat = $row_complete["main_cat"];
                $subCat = $row_complete["sub_cat"];
                $count = $row_complete["order_count"];
                $ordDate = $row_complete["ord_date"];

                // If main category already exists in the array, append the sub product
                if (array_key_exists($mainCat, $mainCategories_complete)) {
                    // Check if the subcategory already exists, if yes, add the count
                    $found = false;
                    foreach ($mainCategories_complete[$mainCat] as &$subProduct) {
                        if ($subProduct['sub_cat'] === $subCat) {
                            $subProduct['order_count'] += $count;
                            $found = true;
                            break;
                        }
                    }
                    // If subcategory not found, add it to the array
                    if (!$found) {
                        $mainCategories_complete[$mainCat][] = array("sub_cat" => $subCat, "order_count" => $count, "ord_date" => $ordDate);
                    }
                } else {
                    // If main category doesn't exist, create a new entry in the array
                    $mainCategories_complete[$mainCat] = array(array("sub_cat" => $subCat, "order_count" => $count, "ord_date" => $ordDate));
                }
            }

            // Output complete orders
            echo "<div class='main-categories'>";
            foreach ($mainCategories_complete as $mainCat => $subProducts) {
                echo "<div class='main-category'>";
                // Display the order date for this group of complete orders
                echo "<div class='store-name'>Order Made On {$subProducts[0]['ord_date']} was Completed<i class='fa fa-angle-down' style='float:right;font-size:20px'></i></div>";
                echo "<div class='order-details' style='display: none;'>";
                echo '<h4 class="main-category-name" style="padding:8px; cursor:pointer; color:red;">' . $mainCat . '<i class="fa fa-angle-down" style="float:right;font-size:20px;"></i></h4>';
                echo "<ul class='sub-categories' style='color:black;display: none;'>";
                foreach ($subProducts as $subProduct) {
                    echo "<li>{$subProduct['sub_cat']} <label style='float:right;'>{$subProduct['order_count']}</label></li>";
                }
                echo "</ul>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            echo '</div>'; // End of complete orders box
            // End of complete orders box


            ?>

            <!-- Include jQuery -->

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function back() {
            window.history.back();
        }
        document.addEventListener("DOMContentLoaded", function() {
            var mainCategoryNames = document.querySelectorAll(".main-category-name");
            mainCategoryNames.forEach(function(mainCategoryName) {
                mainCategoryName.addEventListener("click", function() {
                    var subCategories = this.nextElementSibling;
                    subCategories.style.display = subCategories.style.display === "none" ? "block" : "none";
                });
            });
        });

        $(document).ready(function() {
            $('.store-name').click(function() {
                $(this).next('.order-details').toggle();
            });
        });
    </script>

</body>

</html>