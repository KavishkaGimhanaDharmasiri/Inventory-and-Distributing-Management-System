<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background: radial-gradient(circle, rgba(76,175,80,1) 0%, rgba(247,252,248,1) 0%, rgba(250,253,251,1) 23%, rgba(252,254,253,1) 36%, rgba(255,255,255,1) 47%, rgba(246,251,246,1) 59%, rgba(228,243,229,1) 68%, rgba(171,218,173,1) 100%, rgba(76,175,80,1) 100%);
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #4caf50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        p {
            margin-top: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        select, input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            align-items: center;
            justify-content: center;
        }

        button {
            bwidth: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            max-width: 200px;
        }

        button:hover {
            background-color: #45a049;
        }

        .hidden {
            display: none;
        }

        .submit_payment {
            display: flex;
            width: 200px;
        }

        .print {
            margin-left: 50%;
            width: 200px;
        }
    </style>
</head>
<body>

<?php
session_start();
include("db_connection.php");
$con = $connection;

$orderDetails = $_SESSION['order_details'] ?? [];

// Fetch the selected payment method from the form submission
$selectedPaymentMethod = $_POST['payment_type'] ?? '';

// Calculate total amount based on the selected payment method
$totalAmount = calculateTotalAmount($con, $orderDetails, $selectedPaymentMethod);

function calculateTotalAmount($con, $orderDetails, $paymentMethod)
{
    $totalAmount = 0;

    foreach ($orderDetails as $order) {
        if (is_array($order)) {
            // Perform logic to determine the price based on the payment method
            $price = getCreditPrice($con, $order, $paymentMethod);

            $subtotal = $order['count'] * $price;
            $totalAmount += $subtotal;
        }
    }

    return $totalAmount;
}

function getCreditPrice($con, $order, $method)
{
    $query = "SELECT $method FROM product WHERE main_cat = '{$order['main_category']}' AND sub_cat = '{$order['sub_category']}'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$method] ?? 0;
    } else {
        return 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Information</title>
    <!-- Add your CSS styles here -->
</head>
<body>

<h2>Sales Receipt</h2>

<?php if (!empty($orderDetails)) { ?>
    <table>
        <tr>
            <th>Main Category</th>
            <th>Sub Category</th>
            <th>Count</th>
            <th>Total Amount</th>
        </tr>
        <?php foreach ($orderDetails as $order) : ?>
            <tr>
                <td><?php echo $order['main_category']; ?></td>
                <td><?php echo $order['sub_category']; ?></td>
                <td><?php echo $order['count']; ?></td>
                <!-- Dynamically calculate the total amount based on the selected payment method -->
                <td><?php echo calculateTotalAmount($con, $order, $selectedPaymentMethod); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Display total amount -->
    <p id="totalAmountContainer">Total Amount: $<?php echo $totalAmount; ?></p>

    <!-- Display payment form with options for payment method, payment amount, etc. -->
    <h2>Payment Information</h2>
    <form method="POST" action="process_payment.php">
        <!-- Include hidden fields to pass data to the payment processing page -->
        <input type="hidden" name="total_amount" value="<?php echo $totalAmount; ?>">
        <!-- Add other hidden fields as needed -->

        <label for="payment_type">Payment Type:</label>
        <select name="payment_type" id="payment_type">
            <option value="cash">Cash</option>
            <option value="check">Check</option>
            <option value="credit">Credit</option>
        </select>

        <label for="payment_period" id="paymentPeriodLabel" class="hidden">Payment Period:</label>
        <input type="text" name="payment_period" id="payment_period" class="hidden">

        <label for="payment_amount">Payment Amount:</label>
        <input type="text" name="payment_amount" id="payment_amount">

        <!-- Calculate and display balance dynamically using JavaScript -->
        <label for="balance">Balance:</label>
        <input type="text" name="balance" id="balance" readonly>

        <button type="submit" name="submit_payment" class="submit_payment">Submit Payment</button>
    </form>
    <!-- Add a "Print" button -->
    <br>
    <br>
    <button onclick="generatePDF()" class="print">Print</button>
<?php } else { ?>
    <p>No order details found. Please go back and add items to your order.</p>
<?php } ?>

<script>
    document.getElementById('payment_type').addEventListener('change', function () {
        var paymentPeriodLabel = document.getElementById('paymentPeriodLabel');
        var paymentPeriodInput = document.getElementById('payment_period');

        // Check if the selected payment type is check or credit
        if (this.value === 'check' || this.value === 'credit') {
            paymentPeriodLabel.classList.remove('hidden');
            paymentPeriodInput.classList.remove('hidden');
        } else {
            paymentPeriodLabel.classList.add('hidden');
            paymentPeriodInput.classList.add('hidden');
        }

        // Update total amount based on the selected payment type using AJAX
        updateTotalAmount();
    });

    document.getElementById('payment_amount').addEventListener('input', function () {
        updateTotalAmount();
    });

    // Function to generate PDF
    function generatePDF() {
        window.location.href = 'generate_pdf.php';
    }

    // Function to update total amount based on selected payment type
    function updateTotalAmount() {
    var paymentType = document.getElementById('payment_type').value;
    var paymentAmount = parseFloat(document.getElementById('payment_amount').value) || 0;

    // Make an AJAX request to getPriceBasedOnPaymentMethod.php
    var xhr = new XMLHttpRequest();
   xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
        if (xhr.status === 200) {
            var price = parseFloat(xhr.responseText) || 0;
            var totalAmount = price * paymentAmount;
            document.getElementById('totalAmountContainer').innerHTML = "Total Amount: $" + totalAmount.toFixed(2);
        } else {
            console.error('Error fetching price:', xhr.status, xhr.statusText);
        }
    }
};


    // Send the selected payment type to the server-side script
    xhr.open('GET', 'path_to_getPriceBasedOnPaymentMethod.php?paymentType=' + paymentType, true);

    xhr.send();
}
</script>


</body>
</html>


</body>
</html>


</body>
</html>
