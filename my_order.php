<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="mobile.css">
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

        h4:hover {
            background-color: #bdffa1;
            padding: 5px;


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
        <div class="containers">
            <?php
            session_start();
            include("db_connection.php");
            $user_id = $_SESSION["user_id"];
            $store_quary = "SELECT c.sto_name FROM customers c LEFT JOIN users u ON u.user_id=c.user_id WHERE u.user_id=$user_id";
            $store_quary_result = mysqli_query($connection, $store_quary);


            while ($rows = mysqli_fetch_assoc($store_quary_result)) {
                $_SESSION["my_store"] = $rows["sto_name"];
            }
            $store_name = $_SESSION["my_store"];

            // Select all orders with details from primary_orders and orders tables for the given store name
            $sql = "SELECT p.ord_id,p.order_state, p.ord_date, o.main_cat, o.sub_cat, o.order_count 
        FROM primary_orders p JOIN orders o ON p.ord_id = o.ord_id WHERE p.store_name = '$store_name' And order_type ='customer' AND p.order_state='pending' ORDER BY ord_date DESC";
            $result = mysqli_query($connection, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Initialize a variable to keep track of the current order date
                $current_date = null;
                echo '<div class="box" style="height:100%;background-color:#bdffa1">';
                echo "<h4>Order Made on: <span class='order-date'>{$_SESSION['pending']}</span> - Pending</h4>";
                echo "<table>";
                echo '<tr><th id="leftth">Product</th><th>item</th><th id="rightth">units</th></tr>';
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    $current_date = $row["ord_date"];
                    $_SESSION['pending'] = $row["ord_date"];

                    echo "<tr>";
                    echo "<td><b>" . $row["main_cat"] . "</td>";
                    echo "<td><b>" . $row["sub_cat"] . "</td>";
                    echo "<td><b>" . $row["order_count"] . "</td>";
                    echo "</tr>";
                }
            }
            echo '</table>';


            // Calculate and display the remaining time within 24 hours
            $current_time = strtotime(date("Y-m-d H:i:s"));
            $order_time = strtotime($current_date);
            $time_diff = $current_time - $order_time;
            $remaining_time = 24 * 60 * 60 - $time_diff;
            $remaining_hours = floor($remaining_time / 3600);
            $remaining_minutes = floor(($remaining_time % 3600) / 60);

            echo "<h5>Remaining Time: $remaining_hours hours $remaining_minutes minutes</h5>";

            // Place buttons outside the loop
            echo '<button type="submit" id="edit_order" name="edit_order" style="float:right; width:130px;">Edit Order</button>';
            echo '<button style="float:right; width:130px;margin-right:10px">Confirm</button>';

            echo "<br>";
            echo "<br>";
            echo "</div>";

            echo "<br>";


            echo '<div class="box">';


            $sql = "SELECT p.ord_id,p.order_state, p.ord_date, o.main_cat, o.sub_cat, o.order_count 
        FROM primary_orders p JOIN orders o ON p.ord_id = o.ord_id WHERE p.store_name = '$store_name' And order_type ='customer' AND p.order_state='complete' ORDER BY ord_date DESC ";
            $result = mysqli_query($connection, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Initialize a variable to keep track of the current order date
                $current_date = null;

                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    // Start a new table if the current order date is different from the previous one
                    if ($row["ord_date"] != $current_date) {
                        // Close the previous table if it exists
                        if ($current_date !== null) {
                            echo "</table>";
                        }
                        $current_date = $row["ord_date"];
                        echo "<h4>Order Made on: <span class='order-date'>$current_date </span>- {$row['order_state']}</h4>";
                        echo "<hr>";
                        echo "<table class='order-details' id='order-details-$current_date'>";
                        echo '<tr><th id="leftth">Product</th><th>item</th><th id="rightth">units</th></tr>';
                    }

                    // Output order details based on order state (pending or complete)
                    echo "<tr>";
                    echo "<td><b>" . $row["main_cat"] . "</td>";
                    echo "<td><b>" . $row["sub_cat"] . "</td>";
                    echo "<td><b>" . $row["order_count"] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";
                echo "</div>"; // Close container
            }

            ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }


        $(document).ready(function() {
            // Toggle order details table visibility when clicking on order date
            $(".order-date").click(function() {
                // Find the corresponding order details table and toggle its visibility
                var orderDate = $(this).text();
                $("#order-details-" + orderDate).toggle();
            });
        });
    </script>

</body>

</html>