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

$route_id = $_SESSION['route_id'];
$dateQuery = "SELECT DISTINCT DATE_FORMAT(payment_date, '%Y-%m') AS formatted_date FROM payment";
$stmt = $connection->prepare($dateQuery);
//$stmt->bind_param("i", $route_id);
$stmt->execute();
$dateResult = $stmt->get_result();
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <title>Summery</title>
    <link rel="icon" href="/images/tab_icon.png">
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
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            border-bottom: 1px solid green;

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

            <a href="javascript:void(0)" onclick="back()" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">summery</span></a>

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
            $sql3 = "SELECT r.route, sum(total) sum1,sum(payment_amout) as sum2, SUM(CASE WHEN balance >= 0 THEN balance ELSE 0 END) AS sum3 FROM payment p left join route r on p.route_id=r.route_id WHERE p.route_id=$routeId AND DATE_FORMAT(payment_date, '%Y-%m') = '$currentMonthYear'";
        }
        if ($_SESSION["state"] === "admin") {
            $sql3 = "SELECT r.route, sum(total) sum1,sum(payment_amout) as sum2, SUM(CASE WHEN balance >= 0 THEN balance ELSE 0 END) AS sum3 FROM payment p left join route r on p.route_id=r.route_id GROUP BY p.route_id";
        }
        $result3 = mysqli_query($connection, $sql3);

        echo '<div class="containers">';
        if ($_SESSION["state"] === "seller") {
            echo '<div class="box" style="margin-top:5%;">';
            echo '<h4 style="text-align:center;text-decoration:underline 2px;">Order State(Order Completed Customers)</h4><br>';
            echo "<table>";
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {


                    $storeName = $row['store_name'];
                    $storests = $row['order_state'];


                    if ($storests === 'complete') {
                        echo "<tr style='padding:0;margin:0;'>";
                        echo "<td style='padding:0;margin:0;'><input type='checkbox' name='selected_stores' id='select_sto' checked readonly></td>";
                        echo "<td style='padding:0;margin:0;'><b>&nbsp;&nbsp;" . $storeName . "</td>";
                        echo "<td style='padding:0;margin:0;'><b>&nbsp;&nbsp;" . $storests . "</td>";
                        echo "</tr>";
                    }
                }
            } else {
                echo "<label style='color:indianred;text-align:center;'>No Details Found</label>";
            }

            echo "</table>";
            echo "</div>";
        }
        echo '<br>';

        echo '<div class="box">';
        echo '<h4 style="text-align:center;text-decoration:underline 2px;">Remaining Product Details</h4><br>';
        $sql = "";
        $result2 = "";
        if ($_SESSION["state"] === "seller") {
            $sql2 = "SELECT r.route, f.feed_id, f.route_id, f.feed_date, i.sub_cat, i.main_cat, i.count FROM feed f LEFT JOIN feed_item i ON i.feed_id = f.feed_id LEFT JOIN route r ON f.route_id = r.route_id WHERE f.route_id = $routeId AND DATE_FORMAT(feed_date, '%Y-%m') =  '$currentMonthYear'";
        }
        if ($_SESSION["state"] === "admin") {
            $sql2 = "SELECT r.route, f.feed_id, f.route_id, f.feed_date, i.sub_cat, i.main_cat, i.count FROM feed f LEFT JOIN feed_item i ON i.feed_id = f.feed_id LEFT JOIN route r ON f.route_id = r.route_id WHERE DATE_FORMAT(f.feed_date, '%Y-%m') = '$currentMonthYear'";
        }

        $result2 = mysqli_query($connection, $sql2);
        $routename = "";
        // Initialize an array to store main categories and their related sub products
        $mainCategories = array();
        $routes = array();
        if ($result2) {
            if (mysqli_num_rows($result2) > 0) {

                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $routeName = $row2["route"];
                    $mainCat = $row2["main_cat"];
                    $subCat = $row2["sub_cat"];
                    $count = $row2["count"];

                    // Ensure the route exists in the array
                    if (!array_key_exists($routeName, $routes)) {
                        $routes[$routeName] = array();
                    }

                    // Ensure the main category exists for the route
                    if (!array_key_exists($mainCat, $routes[$routeName])) {
                        $routes[$routeName][$mainCat] = array();
                    }

                    // Ensure the subcategory exists for the main category
                    $found = false;
                    foreach ($routes[$routeName][$mainCat] as &$subProduct) {
                        if ($subProduct['sub_cat'] === $subCat) {
                            $subProduct['count'] += $count;
                            $found = true;
                            break;
                        }
                    }

                    // If subcategory not found, add it to the array
                    if (!$found) {
                        $routes[$routeName][$mainCat][] = array("sub_cat" => $subCat, "count" => $count);
                    }
                }

                // Loop through routes, main categories, and subcategories to display them
                foreach ($routes as $routeName => $mainCategories) {
                    echo "<div class='route'>";
                    echo "<label style='color:indianred;'>Route Name : $routeName</label>";

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
                }
            } else {
                echo "<label style='color:indianred;text-align:center;'>No Details Found</label>";
            }

            echo '</div>';
        }
        echo '<br>';
        echo '<div class="box">';
        if ($_SESSION["state"] === "seller") {
            echo '<tabel><tr><td><h4 style="text-align:center;text-decoration:underline 2px;">Sales Summery</h4></td><td>
                     <select name="dateselect" id="date" required style="background-color:transparent;font-weight:bold;" required>
                        <option value="">Select Month</option>';
            while ($dateRow = $dateResult->fetch_assoc()) {
                if ($currentMonthYear == $dateRow['formatted_date']) {
                    echo "<option value='{$dateRow['formatted_date']}' $select selected><b>Current Month</option>";
                }
                echo "<option value='{$dateRow['formatted_date']}' $select><b>{$dateRow['formatted_date']}</option>";
            }
            echo "</select></td></tr></tabel>";
        }


        if ($result3) {
            if ($_SESSION["state"] === "admin")
                echo '<h4 style="text-align:center;text-decoration:underline 2px;">Sales of Current month&nbsp;&nbsp;&nbsp;(' . $currentMonthYear . ')</h4>';
            while ($row3 = mysqli_fetch_assoc($result3)) {

                if ($row3['route'] != null || $row3['route'] != "") {
                    if ($_SESSION["state"] === "admin") {

                        echo "<table>";
                        echo "<tr>";
                        echo "<td style='color:indianred'><b>Route Name : {$row3['route']}</td>";
                        echo "<td><b></td>";
                        echo "</tr>";
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
                        echo "<tr>";
                        echo "</tr>";
                    }
                    if ($_SESSION["state"] === "seller") {
                        echo "<table>";
                        echo "<tr>";
                        echo "<td><b>Total Sales</td>";
                        echo "<td><b><span id='sales'>Rs." . $row3["sum1"] . ".00 </span></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><b>Total Income </td>";
                        echo "<td><b><span id='income'>Rs." . $row3["sum2"] . ".00</span></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td><b>Total Outstanding</td>";
                        echo "<td><b><span id='outstanding'>Rs." . $row3["sum3"] . ".00</span></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "</tr>";
                    }
                }
            }
        } else {
            echo "<label style='color:indianred;text-align:center;'>No Details Found</label>";
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

        document.addEventListener("DOMContentLoaded", function() {
            const dateSelect = document.getElementById("date");
            const salesLabel = document.getElementById("sales");
            const incomeLabel = document.getElementById("income");
            const outstandingLabel = document.getElementById("outstanding");

            dateSelect.addEventListener("change", updateBalance);

            function updateBalance() {
                const selectedDate = dateSelect.value;
                fetch(`getSaleSummery.php?date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        salesLabel.textContent = "Rs." + (data.sum1 > 0 ? data.sum1 : 0).toFixed(2);
                        incomeLabel.textContent = "Rs." + (data.sum2 > 0 ? data.sum2 : 0).toFixed(2);
                        outstandingLabel.textContent = "Rs." + (data.sum3 > 0 ? data.sum3 : 0).toFixed(2);
                    })
                    .catch(error => {
                        alert('Error fetching remaining balance: ' + error);
                    });
            }
        });
    </script>

</body>

</html>