<?php
// Start or resume the session
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id'])) {
    acess_denie();
    exit();
} else {
    $_SESSION['transaction_settle'] = true;
}
$route_id = $_SESSION['route_id'];
$customerQuery = "SELECT sto_name FROM customers WHERE route_id=$route_id";
$customerResult = mysqli_query($connection, $customerQuery);

$dateQuery = "SELECT distinct(DATE_FORMAT(payment_date, '%Y-%m')) AS formatted_date FROM payment WHERE route_id = $route_id";

$dateResult = mysqli_query($connection, $dateQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $date = $_POST['date'];
        $storename = $_POST['customers'];
        $remain_balance = $_SESSION['rembalance'];
        $settle_amount = $_POST['settle_amount'];
        $total = $_SESSION['totalbalance'];

        $balance = $total - $settle_amount;

        $query = "UPDATE payment SET balance= $total WHERE store_name = $storename AND DATE_FORMAT(payment_date,'Y-m')='$data' AND route_id = $route_id";
        $result = mysqli_query($connection, $query);
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/mobile.css" />
    <link rel="stylesheet" href="/style/style.css" />

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
        <div class="container" id="order-form">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="customer"><b>Customer Name</b></label>
                <select name="customers" id="customers" required>
                    <option value=""><b>Select Customer</b></option>
                    <?php
                    if (isset($_SESSION['selected_store'])) {
                        echo "<option value='{$_SESSION['selected_store']}' selected>{$_SESSION['selected_store']}</option>";
                    }
                    while ($customerRow = mysqli_fetch_assoc($customerResult)) {
                        $selected = ($_SESSION['selected_store'] == $customerRow['sto_name']) ? 'selected' : '';
                        echo "<option value='{$customerRow['sto_name']}' $selected>{$customerRow['sto_name']}</option>";
                    }
                    ?>
                </select>
                <br><br>
                <label for="date"><b>Select Date that Sales Order Made On</b></label>
                <select name="date" id="date" required>
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
                <input type="number" name="amount" id="amount" placeholder="Rs." oninput="calculateBalance()">
                <br><br>
                <label for="settle_amount"><b>Balance Remains to Settle:</b> <span id="balanceRemains">Rs.0.00</span></label>
                <br><br>
                <button type="submit" name="add_sales_person">Update Details</button>
            </form>
        </div>
    </div>

    <script>
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
                        remainBalanceLabel.textContent = "Rs." + data.balance;
                        <?php $_SESSION['totalbalance'] = $data['balance']; ?>
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
            <?php $_SESSION['rembalance'] = $balance; ?>

            balanceRemainsLabel.textContent = "Rs." + balance.toFixed(2);
        }

        function back() {
            window.history.back();
        }
    </script>



</body>

</html>