<?php
// Start or resume the session
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id']) || $_SESSION["state"] != 'seller') {
    acess_denie();
    exit();
} else {
    $_SESSION['transaction_settle'] = true;
}
$route_id = $_SESSION['route_id'];

$customerQuery = "SELECT sto_name FROM customers WHERE route_id=?";
$stmt = mysqli_prepare($connection, $customerQuery);
mysqli_stmt_bind_param($stmt, "i", $route_id);
mysqli_stmt_execute($stmt);
$customerResult = mysqli_stmt_get_result($stmt);


$dateQuery = "SELECT distinct(DATE_FORMAT(payment_date, '%Y-%m')) AS formatted_date FROM payment WHERE route_id = $route_id";

$dateResult = mysqli_query($connection, $dateQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $date = $_POST['dateselect'];
        $storename = $_POST['customers'];
        $settle_amount = $_POST['amount'];
        $curentdate = date('Y-m-d');


        $selectTpNumber = "SELECT c.sto_tep_number,p.balance,p.payment_date FROM customers c LEFT JOIN payment p on p.user_id=c.user_id WHERE DATE_FORMAT(payment_date, '%Y-%m')='$date' AND p.store_name='$storename' AND route_id=$route_id";
        $results = mysqli_query($connection, $selectTpNumber);

        if ($results) {
            if ($roq = mysqli_fetch_assoc($results)) {
                $rem_balance = $roq['balance'];
                $payDate = $roq['payment_date'];
                $sto_tep = $roq['sto_tep_number'];
                $modifiedNumber = '94' . substr($sto_tep, 0);
                $new_balance = $_SESSION['settlebalance'];

                try {
                    $pdo->beginTransaction();
                    $query = "UPDATE payment SET balance= :balance WHERE store_name = :store_name AND DATE_FORMAT(payment_date,'Y-m')= :payment_date AND route_id = :route_id";

                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':balance', $new_balance);
                    $stmt->bindParam(':store_name', $storename);
                    $stmt->bindParam(':payment_date', $date);
                    $stmt->bindParam(':route_id', $route_id);
                    $stmt->execute();
                    $pdo->commit();
                } catch (Exception $e) {
                    // Rollback the transaction in case of an error
                    $pdo->rollBack();
                    echo '<script>alert(' . $e->getMessage() . ');</script>';
                }


                echo '<script>alert("Amount was settled");</script>';
                $body = "\n\nDear Customer,\n\nThe Purchase that $select_store made on $payDate is Total Balance is Rs. $rem_balance And You have Paid Rs $settle_amount on $curentdate. Your Remaining Balance is : Rs. $new_balance on $curentdate.\n\nThank You!...\n\nRegards,\nLotus Electicals (PVT)LTD.";

                $smsbody = urlencode($body);
                // sendsms($modifiedNumber, $smsbody);
                echo '<script>alert("Massage sent Sucessfully");<script>';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/mobile.css" />
    <link rel="stylesheet" href="/style/style.css" />

</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>
        </div>
        <div class="container" id="order-form">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="customer"><b>Customer Name</b></label>
                <select name="customers" id="customers" required>
                    <option value=""><b>Select Customer</b></option>
                    <?php
                    while ($customerRow = mysqli_fetch_assoc($customerResult)) {
                        $selected = ($_SESSION['selected_store'] == $customerRow['sto_name']) ? 'selected' : '';
                        echo "<option value='{$customerRow['sto_name']}' $selected>{$customerRow['sto_name']}</option>";
                    }
                    ?>
                </select>
                <br><br>
                <label for="date"><b>Select Date that Sales Order Made On</b></label>
                <select id="date" name="dateselect" required>
                    <option value=""><b>Select Date</b></option>
                    <?php
                    while ($dateRow = mysqli_fetch_assoc($dateResult)) {
                        $select = ($_SESSION['paydate_date'] == $dateRow['formatted_date']) ? 'selected' : '';
                        echo "<option value='{$dateRow['formatted_date']}' $select>{$dateRow['formatted_date']}</option>";
                    }
                    ?>
                </select>
                <br><br>
                <label for="rem_bal" style="color:indianred;"><b>Remain balance:</b> <span id="remainBalance">Rs.0.00</span></label>
                <br><br>
                <label for="settle_amount"><b>Amount Need to Settle:</b></label>
                <input type="number" name="amount" id="amount" placeholder="Rs." oninput="calculateBalance()" required>
                <br><br>
                <label for="bal_amount"><b>Balance Remains to Settle:</b> <span id="balanceRemains">Rs.0.00</span></label>
                <br><br>
                <button type="submit" name="add_sales_person">Update Details</button>
                <button type="reset" style="background-color: transparent;color:green;margin-bottom:0%;">Clear</button>
            </form>
        </div>
    </div>

    <script>
        function back() {
            window.history.back();
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
                // Send asynchronous request to get remaining balance
                fetch(`getRemainingBalance.php?store=${selectedStore}&date=${selectedDate}`)

                    .then(response => response.json())
                    .then(data => {
                        if (data.balance > 0) {
                            remainBalanceLabel.textContent = "Rs." + data.balance;
                        } else {
                            remainBalanceLabel.textContent = "Rs.0.00";
                        }

                    })
                    .catch(error => {
                        alert('Error fetching remaining balance:' + error);
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

            balanceRemainsLabel.textContent = "Rs." + balance.toFixed(2);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'set_variables.php', true); //setting total and other things to session
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Send the data
            xhr.send('settlepaymentAmount=' + paymentAmount + '&settlebalance=' + balance);

        }
    </script>



</body>

</html>