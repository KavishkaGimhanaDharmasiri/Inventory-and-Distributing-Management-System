<?php
session_start();
    // Include your database connection file
    include("db_connection.php");
    $customerQuery = "SELECT sto_name FROM customers";
    $customerResult = mysqli_query($connection, $customerQuery);
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
                <input type="text" id="dateInput" value="Select Date" onclick="showDatePicker()" style="margin-bottom: 4px; font-family: Calibri; font-size: 11pt; padding-bottom: 10px; padding-top:10px;">
                <input type="date" id="actualDateInput" name="actualDateInput" style="display: none;font-family: Calibri; font-size: 11pt; padding-bottom: 7px; padding-top:7px;" value="Select Date">
            </div>

            <div class="form-group">
                <label for="sto_name">Store name</label>
                <select name="customer" id="customer" required>
    <option value="">Select Customer</option>
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
<select name="payment_method" id="payment_method"  onchange="toggleCustomPaymentFields()">
    <option value="">Select Report Type</option>
    <option value="cash">Sales Receipt</option>
    <option value="check">Sales Summary</option>
    <option value="credit">Customer Data</option>
    <option value="custom">Return summery</option>
</select>
</div>

            <button type="submit">Generate Report</button>
            <br>
            <button type="reset">Clear</button>
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
        </script>
</body>
</html>