<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body{
            height: 100%;
        }
        .payment-form {
           background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            min-width: 320px;
            overflow-y: auto;
            overflow: visible;
            border: 2px solid #4caf50;
            border-radius: 15px;
            
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        select, input, button {
            width: calc(100% - 1px); /* Adjusted width to account for padding */
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 15px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 15px;
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
            padding: 8px;
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
        <form  action="" method="post">
        <h2>Order Details</h2>

<?php
include("db_connection.php");


//require_once('email_sms.php');

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
function getPaymentMethodPrices($mainCategory, $subCategory,$connection)

{
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
    echo "<tr><th>Product</th><th>Unit Price</th><th>Count</th><th>Subtotal</th></tr>";
    echo "</thead>";
    echo "<tbody id='order_details_body'>";

    $totalAmount = 0;
    $selectedPaymentMethod = $_POST['payment_method'] ?? '';
   // $selectedPayment = $_SESSION['selected_payment_method'] ?? '';

    //$_SESSION['selected_payment_method'];

   foreach ($orderDetails as $order) {
    $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category'],$connection);

    //echo "<pre>";
   // print_r($prices); // Debug line: Print prices array
   // $_SESSION['selected_payment_method']=$selectedPaymentMethod;

    switch ($selectedPaymentMethod) {
        case 'cash':
        $_SESSION['selected_payment_method']='cash';
        $unitprice=$prices['cashPrice'];
            $subtotal = $order['count'] * $prices['cashPrice'];
            break;
        case 'check':
         $_SESSION['selected_payment_method']='check';
         $unitprice=$prices['checkPrice'];
            $subtotal = $order['count'] * $prices['checkPrice'];
            break;
        case 'credit':
         $_SESSION['selected_payment_method']='credit';
         $unitprice=$prices['creditPrice'];
            $subtotal = $order['count'] * $prices['creditPrice'];
            break;
        case 'custom':
         $_SESSION['selected_payment_method']='cash';
            $customPaymentAmount100 = isset($_POST['custom_range_100']) ? $_POST['custom_range_100'] : '';
            $customPaymentAmount500 = isset($_POST['custom_range_500']) ? $_POST['custom_range_500'] : '';
            $customPaymentAmount1500 = isset($_POST['custom_range_1500']) ? $_POST['custom_range_1500'] : '';


            // Validate and sanitize the custom payment amount
            $customPaymentAmount100 = filter_var($customPaymentAmount100, FILTER_VALIDATE_FLOAT);
            $customPaymentAmount500 = filter_var($customPaymentAmount500, FILTER_VALIDATE_FLOAT);
            $customPaymentAmount1500 = filter_var($customPaymentAmount1500, FILTER_VALIDATE_FLOAT);

            // Determine the appropriate subcategory price range
            $priceRange = determinePriceRange($prices['cashPrice']);

            // Debug line: Print price range
            

            switch ($priceRange) {
                case '100-500':
                    $unitprice=$prices['cashPrice'] - $customPaymentAmount100;
                    $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount100);
                    break;
                case '500-1500':
                    $unitprice=$prices['cashPrice'] - $customPaymentAmount500;
                    $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount500);
                    break;
                case '1500-5000':
                    $unitprice=$prices['cashPrice'] - $customPaymentAmount1500;
                    $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount1500);
                    break;
                default:
                    $unitprice=$prices['cashPrice'];
                    $subtotal = $order['count'] * $prices['cashPrice'];
                    break;
            }
            break;
        default:
             $unitprice=0;
            $subtotal = 0;

    }

    $totalAmount += $subtotal;

        echo "<tr>";
       // echo "<td>{$order['main_category']}</td>";
        echo "<td>{$order['sub_category']}</td>";
        echo "<td>{$unitprice}</td>";
        echo "<td>{$order['count']}</td>";
        echo "<td>{$subtotal}</td>"; // Display Subtotal
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";

    echo "<h3 style='text-align: center; color:red;'>Total Amount : Rs. {$totalAmount}</h3>"; // Display total amount below the table
    echo "<hr>";
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

function insertdata(){
    include("db_connection.php");
    $orderDetails = $_SESSION['order_details'] ?? [];
    try {
        $pdo->beginTransaction();
    // Extract values from the form
        $route_id = $_SESSION["route_id"];
        $store_name = $_SESSION['selected_store'];
        $total = $_SESSION['totalAmount'];
        $payment_date = date('Y-m-d H:i:s'); // Adjusted the date format
        $payment_method = $_SESSION['selected_payment_method'];
        $payment_amout = $_SESSION['paymentAmount']; // Added a missing semicolon
        $pay_period = ($payment_method == 'credit') ? $_POST['credit_period'] : null;
        $balance = $_SESSION['balance'];

    // Your INSERT query for the 'payment' table
        $query1 = "INSERT INTO payment(route_id, store_name, total, payment_date, payment_method, pay_period, payment_amout, balance) 
               VALUES (:route_id, :store_name, :total, :payment_date, :payment_method, :pay_period, :payment_amout, :balance)";

    // Prepare and execute the query
    $stmt = $pdo->prepare($query1);
    $stmt->bindParam(':route_id', $route_id);
    $stmt->bindParam(':store_name', $store_name);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':payment_date', $payment_date);
    $stmt->bindParam(':payment_method', $payment_method);
    $stmt->bindParam(':pay_period', $pay_period);
    $stmt->bindParam(':payment_amout', $payment_amout);
    $stmt->bindParam(':balance', $balance); // Assuming balance starts at 0, adjust as needed
    $stmt->execute();

    // Get the last inserted ID (ord_id) from the 'payment' table
    $ord_id = $pdo->lastInsertId();

    foreach ($orderDetails as $orderDetail) {
        $mainCategory = $orderDetail['main_category'];
        $subCategory = $orderDetail['sub_category'];
        $count = $orderDetail['count'];

        // Your INSERT query for the 'orders' table
        $query2 = "INSERT INTO orders (ord_id, route_id, store_name, main_cat, sub_cat, order_count) 
                   VALUES (:ord_id, :route_id, :store_name, :main_cat, :sub_cat, :order_count)";

        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(':ord_id', $ord_id);
        $stmt2->bindParam(':route_id', $route_id);
        $stmt2->bindParam(':store_name', $store_name);
        $stmt2->bindParam(':main_cat', $mainCategory);
        $stmt2->bindParam(':sub_cat', $subCategory);
        $stmt2->bindParam(':order_count', $count);
        $stmt2->execute();
    }

    // Commit the transaction
    $pdo->commit();

} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
}

}

if (isset($_POST['generate_pdf'])) {

    insertdata();
        echo '<script>window.location.href = "vbs.php";</script>';

   /* ob_end_clean();
    require_once('vbs.php');
    ob_end_clean();
    generateDetailedOrderReceipt($orderDetails, $totalAmount, $selectedPaymentMethod,$connection);
    header("Location: divs.php");*/
        
    


    


    $telephone=$_SESSION['telephone'];
    $modifiedNumber = '94' . substr($telephone, 0);
    $email=$_SESSION['email'];
    $totalAmount = $_SESSION['totalAmount'];
    $paymentAmout=$_SESSION['paymentAmount'] ;
    $balance=$_SESSION['balance'];
    $select_store=$_SESSION['selected_store'];
    $Subject="Order Details";
    $body="\n\nDear Customer,\n\nThe Purchase that ".$select_store." make on ".$localTime." is Total Amount is : Rs." .$totalAmount. " And You have Paid Rs.". $paymentAmout." And Your Outstanding Balance is : Rs. ".$balance."\n\nThank You!...\n\nRegards,\nLotus Electicals (PVT)LTD.";

    //sendmail($Subject,$body,$_SESSION['email'],$_SESSION['firstname']);
    $smsbody = urlencode($body);
   //sendsms($modifiedNumber,$smsbody);
    

    exit(); // exit to prevent further execution of the script

    }
?>

  <form>

<h2>Payment Information</h2>
        
                
<label for="payment_method">Payment Method:</label>
<select name="payment_method" id="payment_method"  onchange="toggleCustomPaymentFields()">
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

            

            <label for="total_amount">Total Amount : Rs.</label>
            <input type="text" name="total_amount" id="total_amount" value="<?php echo isset($totalAmount) ? $totalAmount : ''; ?>" readonly>

           

            <label for="payment_amount">Payment Amount: Rs.</label>
            <input type="text" name="payment_amount" id="payment_amount" oninput="calculateBalance()">

            <label for="balance">Balance : Rs.</label>
            <input type="text" name="balance" id="balance" readonly>

            
             <button type="submit" name="generate_pdf" >Confirm Order</button>
        </form>
    </div>

 <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>   
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

/*function toggleCustomPaymentFields() {
            var customPaymentFields = document.getElementById('custom_payment_fields');
            customPaymentFields.style.display = customPaymentFields.style.display === 'none' ? 'block' : 'none';
}*/


function toggleCustomPaymentFields() {
    var paymentMethod = document.getElementById('payment_method');
    var customPaymentFields = document.getElementById('custom_payment_fields');

    if (paymentMethod.value === 'custom') {
        customPaymentFields.style.display = 'block';
    } else {
        customPaymentFields.style.display = 'none';
    }
}



</script>
</body>
</html>

<?php
// Close the database connection
mysqli_close($connection);
?>