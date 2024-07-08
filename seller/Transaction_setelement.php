<?php
// Start or resume the session
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

// Check if the user has proper access
if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id']) || $_SESSION["state"] != 'seller') {
    acess_denie();
    exit();
} else {
    $_SESSION['transaction_settle'] = true;
}

$route_id = $_SESSION['route_id'];

// Prepare and execute customer query
$customerQuery = "SELECT sto_name FROM customers WHERE route_id=?";
$stmt = $connection->prepare($customerQuery);
$stmt->bind_param("i", $route_id);
$stmt->execute();
$customerResult = $stmt->get_result();

// Prepare and execute date query
$dateQuery = "SELECT DISTINCT DATE_FORMAT(payment_date, '%Y-%m') AS formatted_date FROM payment WHERE route_id = ?";
$stmt = $connection->prepare($dateQuery);
$stmt->bind_param("i", $route_id);
$stmt->execute();
$dateResult = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $date = $_POST['dateselect'];
        $storename = $_POST['customers'];
        $settle_amount = $_POST['settle_amount'];
        $curentdate = date('Y-m-d');
        $ord_id = $_SESSION['settleord_id'];

        // Fetch remaining balance and other details
        $selectTpNumber = "SELECT u.email, c.sto_tep_number, p.balance, p.payment_date 
                           FROM customers c 
                           LEFT JOIN payment p ON p.user_id = c.user_id 
						    LEFT JOIN users u ON c.user_id = u.user_id  
                           WHERE DATE_FORMAT(payment_date, '%Y-%m') = ? 
                           AND p.store_name = ? 
                           AND c.route_id = ?";
        $stmt = $connection->prepare($selectTpNumber);

        // Check if the statement preparation was successful
        if (!$stmt) {
            die('Error preparing statement: ' . $connection->error);
        }

        $stmt->bind_param("ssi", $date, $storename, $route_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $roq = $result->fetch_assoc()) {
            $user_email = $roq['email'];
            $rem_balance = $roq['balance'];
            $payDate = $roq['payment_date'];
            $sto_tep = $roq['sto_tep_number'];
            $modifiedNumber = '94' . substr($sto_tep, 0);
            $new_balance = $_SESSION['settlebalance'];

            try {
                $connection->begin_transaction();
                $query = "UPDATE payment SET balance = ? WHERE store_name = ? AND DATE_FORMAT(payment_date, '%Y-%m') = ? AND route_id = ?";
                $stmt = $connection->prepare($query);

                // Check if the statement preparation was successful
                if (!$stmt) {
                    die('Error preparing statement: ' . $connection->error);
                }

                $stmt->bind_param("dssi", $new_balance, $storename, $date, $route_id);
                $stmt->execute();


                $query1 = "INSERT INTO settlement(ord_id, settle_date, settle_amout, balance) VALUES(?,?,?,?)";
                $stmt1 = $connection->prepare($query1);

                // Check if the statement preparation was successful
                if (!$stmt1) {
                    die('Error preparing statement: ' . $connection->error);
                }

                $stmt1->bind_param("isdd", $ord_id, $curentdate, $settle_amount, $new_balance);
                $stmt1->execute();


                $connection->commit();

                echo '<script>alert("Amount was settled");</script>';
                $Subject = "Transaction settlement";
                $sbody = "\nDear Customer,\n\nThe Purchase that $storename made on $payDate is Total Balance is Rs. $rem_balance And You have Paid Rs $settle_amount on $curentdate.\n Your Remaining Balance is : Rs. $new_balance on $curentdate.\n\nThank You!...\n\nRegards,\nLotus Electicals (PVT)LTD.";
                $ebody = "<br>Dear Customer,<br><br>The Purchase that $storename made on $payDate is Total Balance is Rs. $rem_balance And You have Paid Rs $settle_amount on $curentdate. <br>Your Remaining Balance is : Rs. $new_balance on $curentdate.<br><br>Thank You!...<br><br>Regards,<br>Lotus Electicals (PVT)LTD.";

                $smsbody = urlencode($sbody);
                sendsms($modifiedNumber, $smsbody); //send sms


                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                sendmail($Subject, $ebody, $user_email, $storename); //send mail



                echo '<script>alert("Message sent Successfully");</script>';
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } catch (Exception $e) {
                $connection->rollback();
                echo '<script>alert("' . $e->getMessage() . '");</script>';
            }
        }
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <title>Balance Settlement</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/mobile.css" />
    <link rel="stylesheet" href="/style/style.css" />
</head>

<body>
    <div class="mobile-container">
        <div class="topnav">
            <a href="javascript:void(0)" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" onclick="back()" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">settlement</span></a>
        </div>
        <div class="container" id="order-form">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="customer"><b>Customer Name</b></label>
                <select name="customers" id="customers" required>
                    <option value=""><b>Select Customer</b></option>
                    <?php while ($customerRow = $customerResult->fetch_assoc()) {
                        $selected = ($_SESSION['selected_store'] == $customerRow['sto_name']) ? 'selected' : '';
                        echo "<option value='{$customerRow['sto_name']}' $selected>{$customerRow['sto_name']}</option>";
                    } ?>
                </select>
                <br><br>
                <label for="date"><b>Select Date that Sales Order Made On</b></label>
                <select name="dateselect" id="date" required>
                    <option value=""><b>Select Date</b></option>
                    <?php while ($dateRow = $dateResult->fetch_assoc()) {
                        $select = ($_SESSION['paydate_date'] == $dateRow['formatted_date']) ? 'selected' : '';
                        echo "<option value='{$dateRow['formatted_date']}' $select>{$dateRow['formatted_date']}</option>";
                    } ?>
                </select>
                <br><br>
                <label for="rem_bal" style="color:indianred;"><b>Remaining balance:</b> <span id="remainBalance">Rs.0.00</span></label>
                <br><br>
                <label for="settle_amount"><b>Amount Need to Settle:</b></label>
                <input type="text" name="settle_amount" id="amount" placeholder="Rs." oninput="calculateBalance(),validateNumber(this)">
                <br><br>
                <label for="settle_amount" style="color:indianred;"><b>Balance Remains to Settle:</b> <span id="balanceRemains">Rs.0.00</span></label>
                <br><br>
                <button type="submit" name="submit">Update Details</button>
                <button type="reset" style="background-color: transparent;color:green;margin-bottom:0%;">Clear</button>
            </form>
        </div>
    </div>
    <script>
        function back() {
            window.history.back();
        }

        function validateNumber(input) {
            input.value = input.value.replace(/\D/g, ''); // Remove any non-numeric characters
        }

        document.addEventListener("DOMContentLoaded", function() {
            const customersSelect = document.getElementById("customers");
            const dateSelect = document.getElementById("date");
            const remainBalanceLabel = document.getElementById("remainBalance");
            const balanceRemainsLabel = document.getElementById("balanceRemains");

            customersSelect.addEventListener("change", updateBalance);
            dateSelect.addEventListener("change", updateBalance);

            function updateBalance() {
                const selectedStore = customersSelect.value;
                const selectedDate = dateSelect.value;
                fetch(`getRemainingBalance.php?store=${selectedStore}&date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        remainBalanceLabel.textContent = "Rs." + (data.balance > 0 ? data.balance : 0).toFixed(2);
                    })
                    .catch(error => {
                        alert('Error fetching remaining balance: ' + error);
                    });
            }
        });

        function calculateBalance() {
            var paymentAmountInput = document.getElementById('amount');
            var remainBalanceLabel = document.getElementById("remainBalance");
            var balanceRemainsLabel = document.getElementById("balanceRemains");

            var paymentAmount = parseFloat(paymentAmountInput.value) || 0;
            var remainBalance = parseFloat(remainBalanceLabel.textContent.replace("Rs.", "").trim()) || 0;

            var balance = remainBalance - paymentAmount;
            var minusBalance = balance;

            //   balanceRemainsLabel.textContent = "Rs." + balance.toFixed(2);

            if (balance < 0) {
                balance = Math.abs(balance).toFixed(2) + " (Pre Payment)"; //setbalace as .00 points
            } else {
                balance = balance.toFixed(2);
            }

            balanceRemainsLabel.textContent = balance;


            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'set_variables.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('settlepaymentAmount=' + paymentAmount + '&settlebalance=' + minusBalance);
        }
    </script>
</body>

</html>