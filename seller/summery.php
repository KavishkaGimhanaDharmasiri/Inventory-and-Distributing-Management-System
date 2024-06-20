<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION["state"])) {
    acess_denie();

    exit();
} else {
    $_SESSION['summery_visit'] = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <style>
        .box {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background: linear-gradient(270deg, #3cba68, #fefefe, #ffffff);
            background-size: 180% 180%;
            animation: gradient-animation 7s ease infinite;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        tr:nth-child(even) {
            background-color: transparent;
        }

        th,
        td {
            padding: 4px;
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
        }

        .main-category-name:hover {
            background-color: #45a049;
        }


        li {
            font-weight: bold;
            padding: 5px;
        }

        li:hover {
            background-color: #bdffa1;
        }

        input[type="checkbox"] {
            padding-top: 10px;
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
        $routeId = $_SESSION['route_id'];
        $currentMonthYear = date('Y-m');

        if ($_SESSION["state"] === "seller") {
            $sql = "SELECT DISTINCT(store_name),order_state FROM primary_orders WHERE order_type = 'sale' AND route_id=$routeId AND DATE_FORMAT(ord_date, '%Y-%m') = '$currentMonthYear'";
        }
        if ($_SESSION["state"] === "admin") {
            $sql = "SELECT DISTINCT(store_name),order_state FROM primary_orders WHERE order_type = 'sale' AND  DATE_FORMAT(ord_date, '%Y-%m') = '$currentMonthYear'";
        }
        //order_state = 'complete'

        $result = mysqli_query($connection, $sql);
        $sql1 = "SELECT sto_name FROM customers WHERE route_id=$routeId";
        $result1 = mysqli_query($connection, $sql1);

        if ($_SESSION["state"] === "seller") {
            $sql3 = "SELECT sum(total) sum1,sum(payment_amout) as sum2, sum(balance) as sum3 FROM payment WHERE route_id=$routeId AND DATE_FORMAT(payment_date, '$currentMonthYear')";
        }
        if ($_SESSION["state"] === "admin") {
            $sql3 = "SELECT sum(total) sum1,sum(payment_amout) as sum2, sum(balance) as sum3 FROM payment WHERE DATE_FORMAT(payment_date, '$currentMonthYear')";
        }
        $result3 = mysqli_query($connection, $sql3);

        echo '<div class="containers">';

        echo '<div class="box" style="margin-top:5%;">';
        echo "<h4>Order State </h4><br>";
        echo "<table>";

        while ($row = mysqli_fetch_assoc($result)) {


            $storeName = $row['store_name'];
            $storests = $row['order_state'];


            if ($storests === 'complete') {
                echo "<tr>";
                echo "<td><input type='checkbox' name='selected_stores' id='select_sto' checked readonly></td>";
                echo "<td><b>" . $storeName . "</td>";
                echo "<td><b>" . $storests . "</td>";
                echo "</tr>";
            } else {
                echo "<td><input type='checkbox' name='selected_stores' id='select_sto'  ></td>";
                echo "<td>" . $storeName . "</td>";
                echo "<td><b>Not Complete" . "</td>";
                echo "</tr>";
            }
        }

        echo "</table>";
        echo "</div>";
        echo '<br>';

        echo '<div class="box">';
        echo "<h4>Remaining Product Details</h4><br>";
        $sql = "";
        $result2 = "";
        if ($_SESSION["state"] === "seller") {
            $sql2 = "SELECT f.feed_id, f.route_id, f.feed_date, i.sub_cat, i.main_cat, i.count FROM feed f LEFT JOIN feed_item i ON i.feed_id = f.feed_id WHERE route_id = $routeId AND DATE_FORMAT(feed_date, '$currentMonthYear')";
        }
        if ($_SESSION["state"] === "admin") {
            $sql2 = "SELECT f.feed_id, f.route_id, f.feed_date, i.sub_cat, i.main_cat, i.count FROM feed f 
            LEFT JOIN feed_item i ON i.feed_id = f.feed_id WHERE DATE_FORMAT(feed_date, '$currentMonthYear')";
        }

        $result2 = mysqli_query($connection, $sql2);

        // Initialize an array to store main categories and their related sub products
        $mainCategories = array();
        if ($result2) {

            while ($row2 = mysqli_fetch_assoc($result2)) {
                $mainCat = $row2["main_cat"];
                $subCat = $row2["sub_cat"];
                $count = $row2["count"];

                // If main category already exists in the array, append the sub product
                if (array_key_exists($mainCat, $mainCategories)) {
                    // Check if the subcategory already exists, if yes, add the count
                    $found = false;
                    foreach ($mainCategories[$mainCat] as &$subProduct) {
                        if ($subProduct['sub_cat'] === $subCat) {
                            $subProduct['count'] += $count;
                            $found = true;
                            break;
                        }
                    }
                    // If subcategory not found, add it to the array
                    if (!$found) {
                        $mainCategories[$mainCat][] = array("sub_cat" => $subCat, "count" => $count);
                    }
                } else {
                    // If main category doesn't exist, create a new entry in the array
                    $mainCategories[$mainCat] = array(array("sub_cat" => $subCat, "count" => $count));
                }
            }
        }
        // Loop through main categories and display them with clickable functionality
        echo "<div class='main-categories'>";
        foreach ($mainCategories as $mainCat => $subProducts) {
            echo "<div class='main-category'>";
            echo '<h4 class="main-category-name" style="padding:8px; cursor:pointer; color:black;">' . $mainCat . '<i class="fa fa-angle-down" style="float:right;font-size:20px"></i></h4>';
            echo "<ul class='sub-categories' style='color:black;display: none;'>";
            foreach ($subProducts as $subProduct) {
                echo "<li>{$subProduct['sub_cat']} <label style='float:right;'>{$subProduct['count']}</label></li>";
            }
            echo "</ul>";
            echo "</div>";
        }
        echo "</div>";

        echo '</div>';

        echo '<br>';
        echo '<div class="box">';
        echo "<h4>Sales of (Current Month - $currentMonthYear)</h4><br>";
        echo "<table>";
        while ($row3 = mysqli_fetch_assoc($result3)) {
            echo "<tr>";
            echo "<td><b>Total Sales</td>";
            echo "<td><b>Rs." . $row3["sum1"] . ".00</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Total Income </td>";
            echo "<td><b>Rs." . $row3["sum2"] . ".00</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Total Outstanding</td>";
            echo "<td><b>Rs." . $row3["sum3"] . ".00</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "</div>";

        echo "</div>";

        // Close the database connection
        mysqli_close($connection);
        ?>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var mainCategoryNames = document.querySelectorAll(".main-category-name");
            mainCategoryNames.forEach(function(mainCategoryName) {
                mainCategoryName.addEventListener("click", function() {
                    var subCategories = this.nextElementSibling;
                    subCategories.style.display = subCategories.style.display === "none" ? "block" : "none";
                });
            });
        });

        function back() {
            window.history.back();
        }
    </script>

</body>

</html>