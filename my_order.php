<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style type="text/css">
    .order-table {
        display: inline-block;
        margin-right: 20px; /* Adjust margin as needed */
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
    h4{
    	margin-bottom: 0;
    }

    /* Style for alternating row colors */
    table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }
	</style>
</head>
<body>
    <?php 
session_start();
include("db_connection.php");
$user_id=$_SESSION["user_id"];
$store_quary="SELECT c.sto_name FROM customers c LEFT JOIN users u ON u.user_id=c.user_id WHERE u.user_id=$user_id";
$store_quary_result = mysqli_query($connection, $store_quary);

while($rows = mysqli_fetch_assoc($store_quary_result)) {
   $_SESSION["my_store"] = $rows["sto_name"];
}
$store_name=$_SESSION["my_store"];


// Select all orders with details from primary_orders and orders tables for the given store name
$sql = "SELECT p.ord_id,p.order_state, p.ord_date, o.main_cat, o.sub_cat, o.order_count 
        FROM primary_orders p 
        JOIN orders o ON p.ord_id = o.ord_id 
        WHERE p.store_name = '$store_name' And order_type ='customer' ORDER BY ord_date DESC";
$result = mysqli_query($connection, $sql);   
if (mysqli_num_rows($result) > 0) {
    // Initialize a variable to keep track of the current order date
    $current_date = null;
    echo "<div class='container'>";
    // Output data of each row

    while($row = mysqli_fetch_assoc($result)) {
        
        // If the current order date is different from the previous one, start a new table
        if( $row["order_state"]==="pending"){
            $ord_id=$row["ord_id"];
        $_SESSION['ord_id']=$ord_id;
            if ($row["ord_date"] != $current_date) {
                // Close the previous table if it exists
                if ($current_date !== null) {
                    echo "</table>";
                }
                $current_date = $row["ord_date"];
                $_SESSION['ord_date']=$row["ord_date"];
                echo "<h4>Orders Made on $current_date</h4>";
                echo "<hr>";
                echo "<table border='1'>";
                echo "<tr><th>Check</th><th>Main Product</th><th>Sub Products</th></tr>";
            }

            echo "<tr>";
            echo "<td><b>" . $row["main_cat"] . "</td>";
            echo "<td><b>" . $row["sub_cat"] . "</td>";
            echo "<td><b>" . $row["order_count"] . "</td>";
            echo "</tr>";
        }
        else if( $row["order_state"]==="complete"){

            if ($row["ord_date"] != $current_date) {
                // Close the previous table if it exists
                if ($current_date !== null) {
                    echo "</table>";
                }
                $current_date = $row["ord_date"];
                
                echo "<h4>Orders Made on $current_date </h4>";
                echo "<table border='1'>";
                echo "<tr><th>Main Category</th><th>Sub Category</th><th>Count</th></tr>";
            }

            echo "<tr>";
            echo "<td>" . $row["main_cat"] . "</td>";
            echo "<td>" . $row["sub_cat"] . "</td>";
            echo "<td>" . $row["order_count"] . "</td>";
            echo "</tr>";
        }
    }
    // Close the last table
    echo "</table>";

    // Add buttons for editing and confirming orders
    // Add the "Edit" button to each row
 

    // Calculate and display the remaining time within 24 hours
    $current_time = strtotime(date("Y-m-d H:i:s"));
    $order_time = strtotime($_SESSION['ord_date']);
    $time_diff = $current_time - $order_time;
    $remaining_time = 24 * 60 * 60 - $time_diff;
    $remaining_hours = floor($remaining_time / 3600);
    $remaining_minutes = floor(($remaining_time % 3600) / 60);

    echo "<h5>Remaining Time: $remaining_hours hours $remaining_minutes minutes</h5>";
       echo '<button type="submit" id="edit_order" name="edit_order" style="float:right; width:150px;">Edit Order</button>';
echo "<br>";
echo "<br>";
    echo '<button style="float:right; width:150px;">Confirm</button>';
    echo "</div>";
} else {
    echo "No orders found for the store.";
}
if (isset($_POST['edit_order'])) {
    echo "hello";
    header("Location:create_order.php?edit_order=true&ord_id=$ord_id");
}


?>

</body>
</html>