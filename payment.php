<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db_connection.php");
require_once('email_sms.php');
require_once('den_fun.php');

if (!isset($_SESSION['new_sale_order_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['option_visit'])) {
    acess_denie();
    exit();
} else {
    $_SESSION['payment_visit'] = true;
}

?>



<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="mobile.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="divs.css">
    <style>
        h3 {
            text-align: center;
            color: black;
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
                echo '<a href="new_order.php" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
            } else {
                // If no referrer is set, provide a default back link
                echo '<a href="new_order.php" class="back-link" style="float:left; font-size:30px;"><i class="fa fa-angle-left"></i></a>';
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

        <?php
        $query = "SELECT DISTINCT main_cat FROM product";
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Database query failed: " . mysqli_error($connection));
        }
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

        function getPaymentMethodPrices($mainCategory, $subCategory, $connection)

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
        $select_store = $_SESSION['selected_store'];

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
        echo '<div class="order-form ">';
        echo '<form  action="payment.php" method="post">';
        echo '<h3>Order Details</h3>';


        if (!empty($orderDetails)) {
            echo "<table>";
            echo "<thead>";
            echo '<tr><th id="leftth">Product</th><th>Item</th><th>Units</th><th id="rightth">Sub Total</th></tr>';
            echo "</thead>";
            echo "<tbody>";

            $totalAmount = 0;
            $selectedPaymentMethod =  $_SESSION['selected_payment_method'];
            // $selectedPayment = $_SESSION['selected_payment_method'] ?? '';

            //$_SESSION['selected_payment_method'];

            foreach ($orderDetails as $order) {
                $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category'], $connection);

                switch ($selectedPaymentMethod) {
                    case 'cash':
                        $unitprice = $prices['cashPrice'];
                        $subtotal = $order['count'] * $prices['cashPrice'];
                        break;
                    case 'check':
                        $unitprice = $prices['checkPrice'];
                        $subtotal = $order['count'] * $prices['checkPrice'];
                        break;
                    case 'credit':
                        $unitprice = $prices['creditPrice'];
                        $subtotal = $order['count'] * $prices['creditPrice'];
                        break;
                    case 'custom':

                        // Determine the appropriate subcategory price range
                        $priceRange = determinePriceRange($prices['cashPrice']);

                        // Debug line: Print price range
                        $customPaymentAmount100 = $_SESSION['customPaymentAmount100'];
                        $customPaymentAmount500 = $_SESSION['customPaymentAmount500'];
                        $customPaymentAmount1500 = $_SESSION['customPaymentAmount1500'];


                        switch ($priceRange) {
                            case '100-500':
                                $unitprice = $prices['cashPrice'] - $customPaymentAmount100;
                                $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount100);
                                break;
                            case '500-1500':
                                $unitprice = $prices['cashPrice'] - $customPaymentAmount500;
                                $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount500);
                                break;
                            case '1500-5000':
                                $unitprice = $prices['cashPrice'] - $customPaymentAmount1500;
                                $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount1500);
                                break;
                            default:
                                $unitprice = $prices['cashPrice'];
                                $subtotal = $order['count'] * $prices['cashPrice'];
                                break;
                        }
                        break;
                    default:
                        $unitprice = 0;
                        $subtotal = 0;
                }

                $totalAmount += $subtotal;

                // echo "<td>{$order['main_category']}</td>";
                echo "<td ><b>{$order['sub_category']}</td>";
                echo "<td style='text-align:center; padding:10px;'><b>{$unitprice}</td>";
                echo "<td style='text-align:center; padding:10px;'><b>{$order['count']}</td>";
                echo "<td style='text-align:center; padding:10px;'><b>{$subtotal}</td>"; // Display Subtotal
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";

            // echo "<h3 style='text-align: center; color:red;'>Total Amount : Rs. {$totalAmount}</h3>"; // Display total amount below the table
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

                $_SESSION['firstname'] = $row1["firstName"];
                $_SESSION['lastname'] = $row1["LastName"];
                $_SESSION['telephone'] = $row1["telphone_no"];
                $_SESSION['email'] = $row1["email"];
            }
        }



        if (isset($_POST['confirm'])) {

            if (!isset($_SESSION['sales_recipt_download'])) {
                echo '<script>alert("Download Sales Receipt Before Proceed Further");</script>';
            }
            echo '<div id="overlay"></div><div id="successModal"><div class="gif"></div>
            <a href="option.php"><button class="sucess">OK</button></a>
            </div>';
        }

        ?>


        <h3>Payment Information</h3>

        <label for="total_amount" style="color: indianred;">Total Amount : Rs.<?php echo isset($totalAmount) ? $totalAmount : ''; ?></label>


        <br>
        <label for=" payment_amount">Payment Amount: Rs.</label>
        <input type="text" name="payment_amount" id="payment_amount" oninput="calculateBalance()">

        <label for="balance" style="color: indianred;">Balance : Rs.</b><span id="remainBalance">Rs.0.00</span></label>

        <table>
            <tr style="background-color:white;">
                <th style="background-color:white;"><a href="process_confirm.php" id="myLink" name="test_pdf" style="cursor: pointer; color:red;font-weight:bold; ">Download Sales Receipt</a></th>
            </tr>
        </table>
        <br>
        <?php
        if (isset($_SESSION['sales_recipt_download'])) {
            echo '<button type="submit" name="confirm">Confirm Order</button>';
        }
        ?>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "150px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }

        function salericeipt() {
            window.location("sale_receipt.php");
        }


        function calculateBalance() {
            var totalAmount = <?php echo isset($totalAmount) ? $totalAmount : 0; ?>;
            var paymentAmountInput = document.getElementById('payment_amount');
            var balanceInput = document.getElementById('remainBalance');

            var paymentAmount = parseFloat(paymentAmountInput.value) || 0;
            var balance = totalAmount - paymentAmount;

            balanceInput.textContent = balance.toFixed(2);


            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'set_session_variables.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Send the data
            xhr.send('totalAmount=' + totalAmount + '&paymentAmount=' + paymentAmount + '&balance=' + balance);

        }


        document.addEventListener('DOMContentLoaded', function() {
            // Disable form elements after submission
            document.getElementById('paymentform').addEventListener('submit', function() {
                var formElements = this.elements;
                for (var i = 0; i < formElements.length; i++) {
                    formElements[i].disabled = true;
                }
            });
        });

        function redirectToIndex() {
            // Redirect to index.php
            window.location.href = 'option.php';
        }
    </script>


</body>

</html>