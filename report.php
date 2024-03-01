<?php
session_start();
    // Include your database connection file
    include("db_connection.php");
    require('side_nav.php');
    $customerQuery = "SELECT sto_name FROM customers";
    $customerResult = mysqli_query($connection, $customerQuery);

// Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style type="text/css">
           select, input, button {
            width: calc(100% - 1px); /* Adjusted width to account for padding */
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 15px;
            border-radius: 15px;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            width: 20px; /* Adjust the width as needed */
            height: 20px; /* Adjust the height as needed */
        
        }
        body {
            height: 100%;
        }

        .order-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            width: 250px;
            background-color: #f9f9f9;
            border-radius: 15px;
            font-size: 10pt;
            overflow: hidden; /* Add overflow property */
            height: 23%; /* Set max-height to reveal 60% initially */
            position: relative; /* Set position to relative */
            backdrop-filter: blur(15%);
            margin-left: 15%;
        }

        .order-container h3 {
            color: #333;
        }

        .order-container p {
            margin: 12px;
        }

        .toggle-link {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 100%;
            text-align: center;
            transform: translateX(-50%);
            backdrop-filter: blur(10px);
            height: 25px;
            padding-bottom: 0px;

        }
        .order-container a {
            color: #0066cc;
            text-decoration: none;
        }

        .order-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Report Generation</h2>

        <?php
        // Display error message if set
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>

        <form method="POST" action="<?php $_PHP_SELF ?>">
            <div class="form-group">
                <h4 style="text-align: left; color:#4caf50;">Filter By</h4>
                <label for="date">Pick Date</label>
                <input type="text" id="dateInput" value="Select Date" onclick="showDatePicker()" style="margin-bottom: 4px; font-family: Calibri; font-size: 13pt; padding-bottom: 7px; padding-top:6px; margin-bottom: 15px;">
                <input type="date" id="actualDateInput" name="actualDateInput" style="display: none;font-family: Calibri; font-size: 13pt; padding-bottom: 7px; padding-top:6px; margin-bottom: 28px;" value="Select Date">
            </div>

            <div class="form-group">
                <label for="sto_name">Store name</label>
                <select name="customer" id="customer" required>
            <option value="" >Select Customer</option>
                        <?php
            while ($customerRow = mysqli_fetch_assoc($customerResult)) {
                $selectedCustomer = (isset($_POST['customer']) && $_POST['customer'] == $customerRow['sto_name']) ? 'selected' : '';
                echo "<option value='{$customerRow['sto_name']}' $selectedCustomer>{$customerRow['sto_name']}</option>";
            }
            ?> 
    </select>
            </div>
            <div class="form-group">
            <label for="report_type">Report Type</label>
        <select name="receiptType" id="receiptType">
            <option value="">Select Report Type</option>
            <option value="salesreceipt">Sales Receipt</option>
            <option value="salessummery">Sales Summary</option>
            <option value="customerdata">Customer Data</option>
            <option value="return">Return summery</option>
        </select>
        </div>

            <button type="submit">Generate Report</button>
            <br>
            <button type="reset">Clear</button>   
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["receiptType"]) && $_POST["receiptType"] == "salesreceipt") {
            $query = "SELECT * FROM payment ORDER BY ord_id DESC";
            $result = mysqli_query($connection, $query);

            // Check if there are any orders
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display each order in a separate div
                    echo '<div class="order-container" id="order_' . $row['ord_id'] . '">';
                    echo '<h3>Order ID: ' . $row['ord_id'] . '</h3>';
                    echo '<p>Route ID: ' . $row['route_id'] . '</p>';
                    echo '<p>Store Name: ' . $row['store_name'] . '</p>';
                    echo '<p>Total: Rs.' . $row['total'] . '</p>';
                    echo '<p>Payment Date: ' . $row['payment_date'] . '</p>';
                    echo '<p>Payment Method: ' . $row['payment_method'] . '</p>';
                    echo '<p>Pay Period: ' . $row['pay_period'] . '</p>';
                    echo '<p>Payment Amount: Rs.' . $row['payment_amout'] . '</p>';
                    echo '<p>Balance: Rs.' . $row['balance'] . '</p>';
                    
                    // Add a link to toggle the visibility of the div
                    echo '<a href="javascript:void(0);" class="toggle-link" onclick="toggleVisibility(' . $row['ord_id'] . ');">Show More</a>';
                    
                    
                    // Add a link to view the associated PDF
                    $pdf_link = "view_pdf.php?ord_id=" . $row['ord_id'];
                    echo '<p><a href="' . $pdf_link . '" target="_blank">View PDF</a></p>';
                    
                    echo '</div>';
                }
            } else {
                // If there are no orders
                echo '<p>No orders found.</p>';
            }

}
?>
 </form>
    </div>

    <script>
            function showDatePicker() {
                // Show the date input field
                document.getElementById('actualDateInput').style.display = 'block';
                // Remove the 'Select Date' value from the text input
                document.getElementById('dateInput').value = '';
                // Focus on the date input field to show the date picker
                document.getElementById('actualDateInput').focus();
                document.getElementById('dateInput').style.display = 'none';
            }

        function toggleVisibility(ordId) {
            var orderDiv = document.getElementById('order_' + ordId);
            orderDiv.style.maxHeight = (orderDiv.style.maxHeight === '23%') ? '100%' : '23%';
        }
        </script>
</body>
</html>