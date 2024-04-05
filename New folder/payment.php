<?php
// Include your database connection file
include("db_connection.php");

// Retrieve order details from the session
session_start();
$orderDetails = $_SESSION['order_details'] ?? [];

// Fetch main categories from the database (you might want to include this in your db_connection.php)
$query = "SELECT DISTINCT main_cat FROM product";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}

// Function to get subcategories for a main category
function getSubcategories($mainCategory, $connection)
{
    $query = "SELECT sub_cat FROM product WHERE main_cat = '$mainCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    $subcategories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $subcategories[] = $row['sub_cat'];
    }

    return $subcategories;
}

// Fetch payment method prices based on the user selection
function getPaymentMethodPrices($mainCategory, $subCategory, $connection)
{
    $query = "SELECT cashPrice, checkPrice, creditPrice FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}

// Handle payment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_payment"])) {
    // Retrieve selected payment method and calculate total amount
    $selectedPaymentMethod = $_POST['payment_method'] ?? '';
    $totalAmount = 0;

    foreach ($orderDetails as $order) {
        $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category'], $connection);

        switch ($selectedPaymentMethod) {
            case 'cash':
                $totalAmount += $order['count'] * $prices['cashPrice'];
                break;
            case 'check':
                $totalAmount += $order['count'] * $prices['checkPrice'];
                break;
            case 'credit':
                $totalAmount += $order['count'] * $prices['creditPrice'];
                break;
        }
    }
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
            height: 100%
        }

        .payment-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            height: 100%;
            max-width: 600px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        select, input, button {
            width: calc(100% - 22px); /* Adjusted width to account for padding */
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .order-details-table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .order-details-table th, .order-details-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-details-table th {
            background-color: #4caf50;
            color: #fff;
        }
    </style>
    <title>Payment Information</title>
</head>
<body>
    <div class="payment-form">
        <form method="POST" action="">
        <h2>Order Details</h2>
        <?php
        if (!empty($orderDetails)) {
            echo "<table class='order-details-table'>";
            echo "<thead>";
            echo "<tr><th>Main Category</th><th>Sub Category</th><th>Count</th></tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($orderDetails as $order) {
                echo "<tr>";
                echo "<td>{$order['main_category']}</td>";
                echo "<td>{$order['sub_category']}</td>";
                echo "<td>{$order['count']}</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No order details found. Please go back and add items to your order.</p>";
        }
        ?>

        <h2>Payment Information</h2>
        
                
<label for="payment_method">Payment Method:</label>
<select name="payment_method" id="payment_method" onchange="updateTotalAmount()">
    <option value="">Select Payment Method</option>
    <option value="cash">Cash</option>
    <option value="check">Check</option>
    <option value="credit">Credit</option>
</select>

            <label for="total_amount">Total Amount:</label>
            <input type="text" name="total_amount" id="total_amount" value="<?php echo isset($totalAmount) ? $totalAmount : ''; ?>" readonly>
            <label for="credit_period" id="credit_period_label" style="display: none;">Credit Period (in days):</label>
<input type="text" name="credit_period" id="credit_period" style="display: none;">


            <label for="payment_amount">Payment Amount:</label>
<input type="text" name="payment_amount" id="payment_amount" oninput="calculateBalance()">

            <label for="balance">Balance:</label>
            <input type="text" name="balance" id="balance" readonly>

            <button type="submit" name="submit_payment">Submit Payment</button>
            <button type="submit" name="">print Recipt</button>
        </form>
    </div>
    
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var paymentMethodSelect = document.getElementById('payment_method');
        var creditPeriodLabel = document.getElementById('credit_period_label');
        var creditPeriodInput = document.getElementById('credit_period');

        paymentMethodSelect.addEventListener('change', function () {
            if (paymentMethodSelect.value === 'credit') {
                creditPeriodLabel.style.display = 'block';
                creditPeriodInput.style.display = 'block';
            } else {
                creditPeriodLabel.style.display = 'none';
                creditPeriodInput.style.display = 'none';
            }
        });
    });
    function calculateBalance() {
        var totalAmount = <?php echo isset($totalAmount) ? $totalAmount : 0; ?>;
        var paymentAmountInput = document.getElementById('payment_amount');
        var balanceInput = document.getElementById('balance');

        var paymentAmount = parseFloat(paymentAmountInput.value) || 0;
        var balance = totalAmount - paymentAmount;

        balanceInput.value = balance.toFixed(2);
    }
    function updateTotalAmount() {
        var paymentMethodSelect = document.getElementById('payment_method');
        var totalAmountInput = document.getElementById('total_amount');

        var totalAmount = 0;

        <?php foreach ($orderDetails as $order): ?>
            <?php $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category'], $connection); ?>
            switch (paymentMethodSelect.value) {
                case 'cash':
                    totalAmount += <?php echo $order['count'] * $prices['cashPrice']; ?>;
                    break;
                case 'check':
                    totalAmount += <?php echo $order['count'] * $prices['checkPrice']; ?>;
                    break;
                case 'credit':
                    totalAmount += <?php echo $order['count'] * $prices['creditPrice']; ?>;
                    break;
            }
        <?php endforeach; ?>

        totalAmountInput.value = totalAmount.toFixed(2);
    }
</script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);
?>