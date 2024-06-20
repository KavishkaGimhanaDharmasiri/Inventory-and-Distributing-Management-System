<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/email_sms.php");

if (!isset($_SESSION['new_sale_order_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['option_visit']) || $_SESSION["state"] != 'seller') {
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
    <title>Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <link rel="stylesheet" href="/style/divs.css">
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
            // Generate back navigation link using HTTP_REFERER
            echo '<a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
            ?>
        </div>

        <?php
        $query = "SELECT DISTINCT main_cat FROM product"; //silecting main product from product table
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Database query failed: " . mysqli_error($connection));
        }

        $selectedUserId = $_SESSION['selected_store_id'];
        $last_month_rem = "SELECT balance FROM payment WHERE balance < 0 AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) - INTERVAL DAY(CURDATE())-1 DAY
          AND payment_date < DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY) and user_id=$selectedUserId";
        $Rem_bal_result = mysqli_query($connection, $last_month_rem);


        function getSubcategories($mainCategory, $connection)
        {
            $query = "SELECT sub_cat FROM product WHERE main_cat = '$mainCategory'"; //selecting subcategory according to main category
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
            //getting different prices from product table
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
        echo '<form  action="payment.php" method="post">'; //redirect the form
        echo '<h3>Order Details</h3>';


        if (!empty($orderDetails)) {
            echo "<table>";
            echo "<thead>";
            echo '<tr><th id="leftth">Product</th><th>Price</th><th>Units</th><th id="rightth">Sub Total</th></tr>';
            echo "</thead>";
            echo "<tbody>";

            $totalAmount = 0;
            $selectedPaymentMethod =  $_SESSION['selected_payment_method'];
            // $selectedPayment = $_SESSION['selected_payment_method'] ?? '';

            //$_SESSION['selected_payment_method'];

            foreach ($orderDetails as $order) {
                $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category'], $connection);

                switch ($selectedPaymentMethod) {
                    case 'cash': //payment method cash
                        $unitprice = $prices['cashPrice'];
                        $subtotal = $order['count'] * $prices['cashPrice'];
                        break;
                    case 'check': //payment method check
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
                            case '100-500': //dicount price range 100-500
                                $unitprice = $prices['cashPrice'] - $customPaymentAmount100;
                                $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount100);
                                break;
                            case '500-1500': //dicount price range 500-1500
                                $unitprice = $prices['cashPrice'] - $customPaymentAmount500;
                                $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount500);
                                break;
                            case '1500-5000': //dicount price range 1500-5000
                                $unitprice = $prices['cashPrice'] - $customPaymentAmount1500;
                                $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount1500);
                                break;
                            default:
                                $unitprice = $prices['cashPrice']; //put no any value for defalt price
                                $subtotal = $order['count'] * $prices['cashPrice'];
                                break;
                        }
                        break;
                    default:
                        $unitprice = 0;
                        $subtotal = 0;
                }

                $totalAmount += $subtotal;
                // order table with details
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

        date_default_timezone_set('Asia/Colombo'); //time zone

        // Get the current local time
        $localTime = date('Y-F-d h:i:a');

        $query = "SELECT user_id FROM customers WHERE sto_name='$select_store'"; //get userid of selected store
        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Database query failed: " . mysqli_error($connection));
        }
        if ($result && mysqli_num_rows($result) == 1) {
            // Fetch user data
            $row = mysqli_fetch_assoc($result);

            $userid = $row["user_id"];
            $query1 = "SELECT * FROM users WHERE user_id='$userid'"; //select user according to user id
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

            echo '<div id="overlay"></div><div id="successModal"><div class="gif"></div>
            <a href="/common/option.php"><button type="button" class="sucess">OK</button></a>
            </div>';
        }

        ?>


        <h3>Payment Information</h3>

        <label for="total_amount" style="color: indianred;">Total Amount of the Bill : Rs.<?php echo isset($totalAmount) ? $totalAmount : ''; ?></label><br>

        <?php
        $rem_bal_of_La_Mo = 0.0;
        $fomatrem = 0.0;
        if ($Rem_bal_result) {
            if ($row = mysqli_fetch_assoc($Rem_bal_result)) {
                $rem_bal_of_La_Mo = $row['balance'];
                $remamout = $rem_bal_of_La_Mo;
                $fomatrem = number_format($remamout, 2, '.', ',');
            }
            echo '<label for="rem_amount" style="color: indianred;">Prepaid Amount of Last Month  : Rs.' . abs($fomatrem) . '</label><br>';
            $totalAmount = $totalAmount + $rem_bal_of_La_Mo;
            echo '<label for="new_total_amount" style="color: indianred;">New Total Amount of Bill : ' . $totalAmount . '</label><br>';
        }

        ?>
        <label for=" payment_amount">Payment Amount: Rs.</label>
        <span id="error-message" style="color: red; display: none;font-size:12px;">Please enter a valid Payment Amount..</span>
        <?php
        if (!isset($_SESSION['process_payment'])) {
            echo '<input type="text" name="payment_amount" id="payment_amount" min="0" oninput="calculateBalance(),validateAndToggleLink(event)" value="" style="color: red;font-size:14px;font-weight:bold;" placeholder="0.00">';
        } else {
            echo '<input type="text" name="payment_amount" id="payment_amount" min="0"  value="Once you click Download Sales Receipt This Can\'t be Reversed" style="color: red;font-size:14px;font-weight:bold;" disabled';
        }
        ?>

        <label for="balance" style="color: indianred;">Balance : Rs.</b><span id="remainBalance">Rs.0.00</span></label>

        <table>
            <tr style="background-color:white;">
                <th style="background-color:white;">
                    <a href="process_confirm.php" id="myLink" name="test_pdf" style="cursor: pointer; color:indianred; font-weight:bold;" onclick="markLinkAsClicked(event),handleLinkClick(event)">
                        <i class="fa fa-angle-double-down" style="font-size: 20px;"></i>&nbsp;&nbsp;Download Sales Receipt
                    </a>
            </tr>
        </table>
        <br>
        <button type="submit" id="confirmButton" name="confirm" style="display: block;" onclick="handleSubmit(event)">
            <i class="fa fa-check" style="font-size: 14px;"></i>&nbsp;&nbsp;Confirm Order
        </button>
        </form>
    </div>
    <script src=" https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function back() {
            window.history.back();
        }
        document.addEventListener('keydown', function(event) {
            if ((event.ctrlKey && event.key === 'r') || event.key === 'F5') {
                event.preventDefault();
            }
        });

        document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });

        document.addEventListener('submit', function(event) {
            event.preventDefault();
        }, true);


        let linkClicked = false;

        function markLinkAsClicked(event) {
            var paymentMethod = document.getElementById("payment_amount").value;
            if (paymentMethod === "") {
                event.preventDefault(); // Prevent link from being followed
                alert("Please enter amount in Total Amount field or if you don't make any Payment just leave 0");
            } else {
                linkClicked = true;
                document.getElementById("payment_amount").disabled = true;
                document.getElementById("payment_amount").value = "Once you click Download Sales Receipt This Can't be Reversed";
                history.replaceState(null, null, 'redirect.html');

                // Add new state for this navigation
                history.pushState(null, null, event.target.href);

                // Navigate to the new URL
                window.location.href = event.target.href; //replce history with redirect another page


            }
        }


        function handleSubmit(event) {
            if (!linkClicked) {
                event.preventDefault();
                alert('Please click the Download Sales Receipt Before Confirm Order.');
            }
        }

        document.addEventListener('DOMContentLoaded', () => { //validate the payment amount field only accept positive integer 
            const inputField = document.getElementById('payment_amount');
            const errorMessage = document.getElementById('error-message');

            inputField.addEventListener('input', () => {
                const value = inputField.value;
                if (value === '' || /^[0-9]+(\.[0-9]*)?$/.test(value)) {
                    inputField.style.borderBottomColor = 'green';
                    errorMessage.style.display = 'none';

                } else {
                    errorMessage.style.display = 'block';
                    inputField.style.borderBottomColor = 'red';
                }
            });
        });


        function validatePaymentMethod(event) {
            var paymentMethod = document.getElementById("payment_amount").value;
            if (paymentMethod === "") {
                event.preventDefault() // Prevent form submission
                alert("Please Enter Amount in total Field or if You don't make any payment just leave 0");
            }
        }


        function calculateBalance() { //calculate balance ontype on payment amount field
            var totalAmount = <?php echo isset($totalAmount) ? $totalAmount : 0; ?>;
            var paymentAmountInput = document.getElementById('payment_amount');
            var balanceElement = document.getElementById('remainBalance'); //set remainng balance

            var paymentAmount = parseFloat(paymentAmountInput.value) || 0;
            var balance = totalAmount - paymentAmount; //calculate balance

            var minusBalance = balance; //get minus balance

            if (balance < 0) {
                balance = Math.abs(balance).toFixed(2) + " (Pre Payment)"; //setbalace as .00 points
            } else {
                balance = balance.toFixed(2);
            }

            balanceElement.textContent = balance;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'set_session_variables.php', true); //setting total and other things to session
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Send the data
            xhr.send('totalAmount=' + totalAmount + '&paymentAmount=' + paymentAmount + '&balance=' + minusBalance);

        }

        document.addEventListener('DOMContentLoaded', function() { //disable editing payment amount field after submission
            // Disable form elements after submission
            document.getElementById('paymentform').addEventListener('submit', function() {
                var formElements = this.elements;
                for (var i = 0; i < formElements.length; i++) {
                    formElements[i].disabled = true;
                }
            });
        });
    </script>


</body>

</html>