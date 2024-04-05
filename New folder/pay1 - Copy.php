<?php
session_start();
// Include your database connection file
include("db_connection.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_payment"])) {
    // Check if $_SESSION['order_details'] is set
    $orderDetails = $_SESSION['order_details'] ?? [];

    // Insert the order details into the database
    foreach ($orderDetails as $order) {
        $mainCategory = mysqli_real_escape_string($connection, $order['main_category']);
        $subCategory = mysqli_real_escape_string($connection, $order['sub_category']);
        $count = (int)$order['count'];

        // Fetch product details from the database
        $productQuery = "SELECT * FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
        $productResult = mysqli_query($connection, $productQuery);

        if (!$productResult) {
            die("Database query failed: " . mysqli_error($connection));
        }

        $product = mysqli_fetch_assoc($productResult);

        // Check if there is enough stock
        if ($product['stock'] >= $count) {
            // Update stock
            $newStock = $product['stock'] - $count;
            $updateStockQuery = "UPDATE product SET stock = $newStock WHERE product_id = {$product['product_id']}";
            $updateStockResult = mysqli_query($connection, $updateStockQuery);

            if (!$updateStockResult) {
                die("Database query failed: " . mysqli_error($connection));
            }

            // Insert sales record
            $creditPrice = $product['creditPrice'] * $count;
            $checkPrice = $product['checkPrice'] * $count;
            $cashPrice = $product['cashPrice'] * $count;

            $insertSalesQuery = "INSERT INTO sales (main_cat, sub_cat, count, credit_price, check_price, cash_price) VALUES ('$mainCategory', '$subCategory', $count, $creditPrice, $checkPrice, $cashPrice)";
            $insertSalesResult = mysqli_query($connection, $insertSalesQuery);

            if (!$insertSalesResult) {
                die("Database query failed: " . mysqli_error($connection));
            }
        } else {
            // Handle insufficient stock error
            echo "Error: Insufficient stock for product '$mainCategory - $subCategory'.";
            exit();
        }
    }

    // Clear the temporary order details after confirming the order
    unset($_SESSION['order_details']);

    // Redirect to a thank you page or any other appropriate page
    header("Location: thank_you.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            height: 100vh;
        }

        .payment-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <title>Payment</title>
</head>
<body>
    <div class="payment-form">
        <h2>Confirm Payment</h2>

        <?php
        // Display orders table
        if (isset($_SESSION['order_details']) && !empty($_SESSION['order_details'])) {
            echo "<table>";
            echo "<thead>";
            echo "<tr><th>Main Category</th><th>Sub Category</th><th>Count</th></tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($_SESSION['order_details'] as $order) {
                echo "<tr>";
                echo "<td>{$order['main_category']}</td>";
                echo "<td>{$order['sub_category']}</td>";
                echo "<td>{$order['count']}</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }
        ?>

        <form method="POST" action="pay1.php">
            <button type="submit" name="confirm_payment">Confirm Payment</button>
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);
?>
