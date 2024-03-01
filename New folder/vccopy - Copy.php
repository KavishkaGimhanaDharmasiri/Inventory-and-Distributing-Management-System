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
// Retrieve order details from the session
session_start();


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// Include your database connection file
include("db_connection.php");
require_once('email_sms.php');

$login_fname= $_SESSION["user_log_fname"] ;
$login_lname=$_SESSION["user_log_lname"];




$orderDetails = $_SESSION['order_details'] ?? [];
//$totalAmount = $_SESSION['total_amount'] ?? 0;

$select_store=$_SESSION['selected_store'];

/*$totalAmount = $_SESSION['totalAmount'];
$paymentAmout=$_SESSION['paymentAmount'];
$balance=$_SESSION['balance'];*/
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
function getPaymentMethodPrices($mainCategory, $subCategory)

{
    include("db_connection.php");
    $query = "SELECT cashPrice, checkPrice, creditPrice FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}


$orderDetails = $_SESSION['order_details'] ?? [];
//$totalAmount = $_SESSION['total_amount'] ?? 0;
$select_store=$_SESSION['selected_store'];


function determinePriceRange($price)
{
    if ($price >= 10 && $price <= 500) {
        return '100-500';
    } elseif ($price > 500 && $price <= 1500) {
        return '500-1500';
    } elseif ($price > 1500 && $price <= 5000) {
        return '1500-5000';
    } else {
        return 'other'; // Default case or handle other ranges as needed
    }
}

if (!empty($orderDetails)) {
    echo "<table class='order-details-table'>";
    echo "<thead>";
    echo "<tr><th>Main Category</th><th>Sub Category</th><th>Count</th><th>Subtotal</th></tr>";
    echo "</thead>";
    echo "<tbody id='order_details_body'>";

    $totalAmount = 0;
    $selectedPaymentMethod = $_POST['payment_method'] ?? '';
   // $selectedPayment = $_SESSION['selected_payment_method'] ?? '';

    //$_SESSION['selected_payment_method'];

   foreach ($orderDetails as $order) {
    $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category']);

    //echo "<pre>";
   // print_r($prices); // Debug line: Print prices array
   // $_SESSION['selected_payment_method']=$selectedPaymentMethod;

    switch ($selectedPaymentMethod) {
        case 'cash':
        $_SESSION['selected_payment_method']='cash';
            $subtotal = $order['count'] * $prices['cashPrice'];
            break;
        case 'check':
         $_SESSION['selected_payment_method']='check';
            $subtotal = $order['count'] * $prices['checkPrice'];
            break;
        case 'credit':
         $_SESSION['selected_payment_method']='credit';
            $subtotal = $order['count'] * $prices['creditPrice'];
            break;
        case 'custom':
         $_SESSION['selected_payment_method']='cash';
            $customPaymentAmount100 = isset($_POST['custom_range_100']) ? $_POST['custom_range_100'] : '';
            $customPaymentAmount500 = isset($_POST['custom_range_500']) ? $_POST['custom_range_500'] : '';
            $customPaymentAmount1500 = isset($_POST['custom_range_1500']) ? $_POST['custom_range_1500'] : '';

            // Debug lines: Print custom payment amounts

            // Validate and sanitize the custom payment amount
            $customPaymentAmount100 = filter_var($customPaymentAmount100, FILTER_VALIDATE_FLOAT);
            $customPaymentAmount500 = filter_var($customPaymentAmount500, FILTER_VALIDATE_FLOAT);
            $customPaymentAmount1500 = filter_var($customPaymentAmount1500, FILTER_VALIDATE_FLOAT);

            // Debug lines: Print validated custom payment amounts
            

            // Determine the appropriate subcategory price range
            $priceRange = determinePriceRange($prices['cashPrice']);

            // Debug line: Print price range
            

            switch ($priceRange) {
                case '100-500':
                    $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount100);
                    break;
                case '500-1500':
                    $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount500);
                    break;
                case '1500-5000':
                    $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount1500);
                    break;
                default:
                    $subtotal = $order['count'] * $prices['cashPrice'];
                    break;
            }
            break;
        default:
            $subtotal = 0;
    }

    // Debug line: Print subtotal
    

    $totalAmount += $subtotal;

         

        echo "<tr>";
        echo "<td>{$order['main_category']}</td>";
        echo "<td>{$order['sub_category']}</td>";
        echo "<td>{$order['count']}</td>";
        echo "<td>{$subtotal}</td>"; // Display Subtotal
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

    echo "<p>Total Amount: {$totalAmount}</p>"; // Display total amount below the table
} else {
    echo "<p>No order details found. Please go back and add items to your order.</p>";
}





date_default_timezone_set('Asia/Colombo');

// Get the current local time
$localTime = date('Y-F-d h:i:a');

$query = "SELECT user_id FROM customers WHERE sto_name='$select_store'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}
if ($result && mysqli_num_rows($result) == 1) {
        // Fetch user data
        $row = mysqli_fetch_assoc($result);

        $userid = $row["user_id"];
        $query1 = "SELECT * FROM users WHERE user_id='$userid'";
        $result1 = mysqli_query($connection, $query1);

        if (!$result1) {
            die("Database query failed: " . mysqli_error($connection));
        }
        if ($result1 && mysqli_num_rows($result1) == 1) {
        // Fetch user data
            $row1 = mysqli_fetch_assoc($result1);

            $_SESSION['firstname']= $row1["firstName"];
            $_SESSION['lastname']= $row1["LastName"];
            $_SESSION['telephone'] = $row1["telphone_no"];
            $_SESSION['email'] = $row1["email"];

}
}

  

if (isset($_POST['generate_pdf'])) {
    $email=$_SESSION['email'];
    $totalAmount = $_SESSION['totalAmount'];
    $paymentAmout=$_SESSION['paymentAmount'] ;
    $balance=$_SESSION['balance'];
    $select_store=$_SESSION['selected_store'];
    
    $Subject="Order Details";
    $body="\n\nDear Customer,\n\nThe Purchase that ".$select_store." make on ".$localTime." is Total Amount is : Rs." .$totalAmount. " And You have Paid Rs.". $paymentAmout." And Your Outstanding Balance is : Rs. ".$balance."\n\nThank You!...\n\nRegards,\nLotus Electicals (PVT)LTD.";
      //  sendmail($Subject,$body,$_SESSION['email'],$_SESSION['firstname']);
       // sendsms($_SESSION['telephone'],$body);
        // Redirect to print.php*/
        header('Location: generatPdf.php');


    exit(); // Make sure to exit to prevent further execution of the script
    }

// Handle payment form submission

?>


<h2>Payment Information</h2>
        
                
<label for="payment_method">Payment Method:</label>
<select name="payment_method" id="payment_method" >
    <option value="">Select Payment Method</option>
    <option value="cash">Cash</option>
    <option value="check">Check</option>
    <option value="credit">Credit</option>
    <option value="custom">Custom Payment</option>
</select>

<div id="custom_payment_fields" style="display: none;">
                <label for="custom_range_100">Discount for Price Range Rs.10 to Rs.500</label>
                <input type="text" name="custom_range_100" id="custom_range_100">

                <label for="custom_range_500">Discount for Price Range Rs.500 to Rs.1500</label>
                <input type="text" name="custom_range_500" id="custom_range_500">

                <label for="custom_range_1500">Discount for Price Range Rs.1500 to Rs.5000</label>
                <input type="text" name="custom_range_1500" id="custom_range_1500">     

            </div>

             <label for="credit_period" id="credit_period_label" style="display: none;">Credit Period (in days):</label>
            <input type="text" name="credit_period" id="credit_period" style="display: none;">


<button type="submit" name="submit_payment">Confirm Payment Method</button>

            

            <label for="total_amount">Total Amount:</label>
            <input type="text" name="total_amount" id="total_amount" value="<?php echo isset($totalAmount) ? $totalAmount : ''; ?>" readonly>

           

            <label for="payment_amount">Payment Amount:</label>
            <input type="text" name="payment_amount" id="payment_amount" oninput="calculateBalance()">

            <label for="balance">Balance:</label>
            <input type="text" name="balance" id="balance" readonly>

            
            <button type="submit" name="generate_pdf">Confirm Order</button>

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


document.addEventListener('DOMContentLoaded', function () {
        var paymentMethodSelect = document.getElementById('payment_method');
        var range1 = document.getElementById('custom_payment_fields');


        paymentMethodSelect.addEventListener('change', function () {
           if(paymentMethodSelect.value ==='custom'){
                toggleCustomPaymentFields();
           } 
            
        });
    });




    document.addEventListener('DOMContentLoaded', function () {
        var paymentMethodSelect = document.getElementById('payment_method');
        var custompayment = document.getElementById('custom_payment');
       

        paymentMethodSelect.addEventListener('change', function () {
            if(paymentMethodSelect.value ==="Select Payment Method" || paymentMethodSelect.value ==='check' || paymentMethodSelect.value ==='credit' ){
                custompayment.style.display = 'none';
            }
            else if (paymentMethodSelect.value === 'cash') {
                custompayment.style.display = 'block';
                
            } else {
                custompayment.style.display = 'none';
                
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

        var xhr = new XMLHttpRequest();
    xhr.open('POST', 'set_session_variables.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    // Send the data
    xhr.send('totalAmount=' + totalAmount + '&paymentAmount=' + paymentAmount + '&balance=' + balance);

}






    function toggleCustomPaymentFields() {
            var customPaymentFields = document.getElementById('custom_payment_fields');
            customPaymentFields.style.display = customPaymentFields.style.display === 'none' ? 'block' : 'none';
        }

            
       
</script>


</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);
?>