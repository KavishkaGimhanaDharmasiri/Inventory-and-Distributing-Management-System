<?php
// Start or resume the session
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");


if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id']) || $_SESSION["state"] != 'seller') {
    acess_denie();
    exit();
} else {
    $_SESSION['new_sale_order_visit'] = true;
}

$route_id = $_SESSION['route_id'];
// Fetch main categories from the database
$query1 = "SELECT DISTINCT main_cat FROM feed_item f left join feed e on f.feed_id=e.feed_id where e.route_id=$route_id and f.count>0";
$result1 = mysqli_query($connection, $query1);

$customerQuery = "SELECT user_id,sto_name FROM customers WHERE route_id=$route_id";
$customerResult = mysqli_query($connection, $customerQuery);

// Check for database query failures
if (!$customerResult || !$result1) {
    die("Database query failed: " . mysqli_error($connection));
}

// Fetch subcategories for the selected main category
function getSubcategories($mainCategory, $connection)
{
    $query = "SELECT sub_cat FROM product WHERE main_cat = '$mainCategory'";
    $result = mysqli_query($connection, $query);

    // Check for database query failure
    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    $subcategories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $subcategories[] = ['sub_cat' => $row['sub_cat']];
    }

    return $subcategories;
}

// Handle adding an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_order"])  && $_SESSION["state"] == 'seller') {
    // Process form submission and update order details
    handleAddOrder($connection);

    // Redirect after processing form submission
    header("Location: new_order.php");
    exit();
}

// Handle confirming an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"]) && isset($_POST['payment_method'])  && $_SESSION["state"] == 'seller') {
    if ($_POST['payment_method'] === "") {
        echo '<script>alert("Please Select Payment Method");</script>';
    } else {
        // Process form submission and redirect to payment page
        handleConfirmOrder($connection);
    }
}

// Close the database connection
mysqli_close($connection);

// Function to handle adding an order
function handleAddOrder($connection)
{
    $mainCategory = $_POST["main_category"];
    $subcategories = getSubcategories($mainCategory, $connection);
    $counts = $_POST["counts"];

    // Temporary storage for the order details
    $orderDetails = $_SESSION['order_details'] ?? [];
    $selectedStore = $_POST["customers"];

    list($select_user_id, $selected_user_name) = explode('-', $selectedStore);

    $_SESSION['selected_store'] = $selected_user_name;
    $_SESSION['selected_store_id'] = $select_user_id;


    // Check if there's already an order for the selected main category and subcategory
    foreach ($subcategories as $index => $subcategory) {
        $subcategoryExists = false;
        if ($counts[$index] > 0) {
            foreach ($orderDetails as &$order) {
                if ($order['main_category'] == $mainCategory && $order['sub_category'] == $subcategory['sub_cat']) {
                    $order['count'] += $counts[$index];
                    $subcategoryExists = true;
                    break;
                }
            }

            // If subcategory doesn't exist, add a new order
            if (!$subcategoryExists) {
                $orderDetails[] = [
                    'main_category' => $mainCategory,
                    'sub_category' => $subcategory['sub_cat'],
                    'count' => $counts[$index]
                ];
            }
        }
    }

    // Update the session variable with the order details
    $_SESSION['order_details'] = $orderDetails;

    // Reset main category to the default value
    $_POST["main_category"] = "";
    displayOrderTable();
}



// Function to handle confirming an order
function handleConfirmOrder($connection)
{
    $orderDetails = $_SESSION['order_details'] ?? [];

    // Get necessary data for redirection
    $mainCategory = $_POST['main_category'];
    $subcategories = $_POST['subcategories'] ?? '';
    $counts = $_POST['counts'] ?? '';
    $selectedStore = $_POST["customers"];

    list($select_user_id, $selected_user_name) = explode('-', $selectedStore);

    $_SESSION['selected_store'] = $selected_user_name;
    $_SESSION['selected_store_id'] = $select_user_id;


    $selectedPaymentMethod = $_POST['payment_method'] ?? '';
    $_SESSION['selected_payment_method'] = $selectedPaymentMethod;
    $pay_period = ($selectedPaymentMethod == 'credit') ? $_POST['credit_period'] : null;
    $_SESSION['pay_period'] = $pay_period;


    $selectedPaymentMethod = $_POST['payment_method'] ?? '';
    $customPaymentAmount100 = isset($_POST['custom_range_100']) ? $_POST['custom_range_100'] : '';
    $customPaymentAmount500 = isset($_POST['custom_range_500']) ? $_POST['custom_range_500'] : '';
    $customPaymentAmount1500 = isset($_POST['custom_range_1500']) ? $_POST['custom_range_1500'] : '';

    // Validate and sanitize the custom payment amount
    $customPaymentAmount100 = filter_var($customPaymentAmount100, FILTER_VALIDATE_FLOAT);
    $customPaymentAmount500 = filter_var($customPaymentAmount500, FILTER_VALIDATE_FLOAT);
    $customPaymentAmount1500 = filter_var($customPaymentAmount1500, FILTER_VALIDATE_FLOAT);

    $_SESSION['customPaymentAmount100'] = $customPaymentAmount100;
    $_SESSION['customPaymentAmount500'] = $customPaymentAmount500;
    $_SESSION['customPaymentAmount1500'] = $customPaymentAmount1500;
    header("Location: payment.php?main_category=$mainCategory&subcategories=$subcategories&counts=$counts&payment_method=$selectedPaymentMethod");
    exit();
}
// Function to display the orders table
function displayOrderTable()
{
    if (isset($_SESSION['order_details']) && !empty($_SESSION['order_details'])) {
        echo "<table>";
        echo "<thead>";
        echo '<tr><th id="leftth">Check</th><th>Products</th><th>Item</th><th id="rightth">Units</th></tr>';
        echo "</thead>";
        echo "<tbody>";

        foreach ($_SESSION['order_details'] as $index => $order) {
            echo "<tr>";
            echo "<td><input type='checkbox' name='remove_order[]' value='$index'></td>";
            echo "<td><b>{$order['main_category']}</td>";
            echo "<td><b>{$order['sub_category']}</td>";
            echo "<td><b>{$order['count']}</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>New Order</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <style>
        .subcategory-container {
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">


            <a href="javascript:void(0)" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" onclick="back()" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">new order</span></a>
        </div>
        <div id="popup" class="popup" onclick="closePopup()">
            <div class="popup-content">
                <span class="close" style="font-size: 14px;" onclick="closePopup()">&#10005;</span>
                <label id="message" style="font-size: 14px;"></label>
            </div>
        </div>
        <div class="order-form" id="order-form">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <label for="customer"><b>Customer Name<bb></label>
                <select name="customers" id="customers" required>
                    <option value=""><b>Select Customer<b></option>
                    <?php
                    while ($customerRow = mysqli_fetch_assoc($customerResult)) { //setting query result to customers
                        $selected = ($_SESSION['selected_store'] == $customerRow['sto_name']) ? 'selected' : '';
                        echo "<option value='{$customerRow['user_id']}-{$customerRow['sto_name']}' $selected>{$customerRow['sto_name']}</option>";
                    }
                    ?>
                </select>

                <label for="main_category"><b>Main Product</b></label>
                <select name="main_category" id="main_category">
                    <option value="">Select Main Product</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result1)) { //setting query result to cmain product
                        $selected = (isset($_POST['main_category']) && $_POST['main_category'] == $row['main_cat']) ? 'selected' : '';
                        echo "<option value='{$row['main_cat']}' $selected>{$row['main_cat']}</option>";
                    }
                    ?>
                </select>

                <div class="subcategory-container" id="subcategory-container">
                </div>
                <?php
                if (!isset($_SESSION['process_payment'])) {
                    echo '<button type="submit" name="add_order" id="orderButton" onclick="validatecustomer(event)"  id="orderButton"><i class="fa fa-plus" style="font-size: 14px;" ></i>&nbsp;&nbsp;Add Items</button>';
                } else {
                    echo '<button type="button"  onclick="redirectconfirm(event)"><i class="fa fa-plus" style="font-size: 14px;"></i>&nbsp;&nbsp;Add Items</button>';
                }
                ?>

                <?php displayOrderTable(); ?>
                <br>
                <?php
                if (!empty($_SESSION['order_details'])) {
                    echo '<label for="payment_method"><b>Payment Method</label>'; //payment method selection
                    echo '<select name="payment_method" id="payment_method" onchange="toggleCustomPaymentFields()" >';
                    if (isset($_SESSION['selected_payment_method'])) {
                        echo "<option value='{$_SESSION['selected_payment_method']}' selected>{$_SESSION['selected_payment_method']}</option>";
                    }
                    echo '<option value="">Select Payment Method</option>
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="credit">Credit</option>
                <option value="custom">Custom Discoun Payment</option>
                </select>
                <div id="custom_payment_fields" style="display: none;">
                    <label for="custom_range_100" style="font-size:15px;">■&nbsp;Discount for Price Range Rs.10 to Rs.500</label>
                    <input type="number" name="custom_range_100" id="custom_range_100">

                    <label for="custom_range_500" style="font-size:15px;">■&nbspDiscount for Price Range Rs.500 to Rs.1500</label>
                    <input type="number" name="custom_range_500" id="custom_range_500">

                    <label for="custom_range_1500"style="font-size:15px;">■&nbspDiscount for Price Range Rs.1500 to Rs.5000</label>
                    <input type="number" name="custom_range_1500" id="custom_range_1500">

                </div>

                <label for="credit_period" id="credit_period_label" style="display: none;">Credit Period (in days):</label>
                <input type="text" name="credit_period" id="credit_period" style="display: none;">';
                }
                ?>


                <!-- Display Confirm Order button if there are items in the order -->
                <?php
                if (isset($_SESSION['process_payment']) && !empty($_SESSION['order_details'])) {
                    echo "<button type='button' class='confirm-order-button' name='confirm_order' onclick='redirectconfirm(event)'>
                        <i class='fa fa-check' style='font-size: 14px;'></i>&nbsp;&nbsp;Confirm Order</button>";
                } else if (!empty($_SESSION['order_details'])) {
                    echo "<button type='submit' class='confirm-order-button' name='confirm_order' onclick='validatePaymentMethod(event)'>
                    <i class='fa fa-check' style='font-size: 14px;'></i>&nbsp;&nbsp;Confirm Order</button>";
                }
                ?>
                <?php
                if (!isset($_SESSION['process_payment']) && !empty($_SESSION['order_details'])) {
                    echo "<button type='button' name='clear_order' style='color:green; background-color:transparent;'><i class='fa fa-minus'></i>&nbsp;&nbsp;Remove Items</button>";
                } else {
                    echo "<button type='button'  style='color:green; background-color:transparent;' onclick='redirectconfirm(event)'><i class='fa fa-minus'></i>&nbsp;&nbsp;Remove Items</button>";
                }
                ?>

                <!-- Confirm Order button -->

            </form>


        </div>
        <?php
        if (!isset($_SESSION["ad_state"])) {
            echo '<div style="border:1px solid green;font-weight:normal; color:green;background-color:#d9fcd2; " class="order-form" id="advertise">
            <p style="color: green; font-weight: normal; -webkit-user-select: none;">Note<a onclick="closeintro()" style="float:right;font-size:15px; color:green; "><i class="fa fa-close" style="cursor:pointer;"></i></a><br><br>a). First select Customer Name from dropdown <br><br>b). Select Main product from dropdown. Items will be load automatically according to selected Main Product.<br><br>
            c).Add disired item count in each box below item then click Add order button the previously enterd details are shown in table below Add order Button. <br><br>
            d.)Again you can perform b). And c). steps to add multiple items to order. 
            <br><br>if You want remove any Items from order click check box and click Remove Item Button to remove that entry And You can Remove muliple Entry at time by checking multiple entry. </p>
        </div>';
        }
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/javascript/test1.js"></script>
    <script>
        function closeintro() {
            document.getElementById("advertise").style.display = "none";
            <?php $_SESSION["ad_state"] = true; ?>

        }

        /*  function validateNumber(input) {
              input.value = input.value.replace(/\D/g, ''); // Remove any non-numeric characters
          }*/
        function validateNumber(input, availableCount) {
            const value = parseInt(input.value);
            const errorSpan = document.getElementById('error_' + input.id.split('[')[1].split(']')[0]);

            if (isNaN(value) || value <= 0) {
                errorSpan.textContent = 'Please enter a valid number.';
                input.value = '';
                document.getElementById('orderButton').disabled = true;
            } else if (value > availableCount) {
                errorSpan.textContent = `The available count for this subcategory is ${availableCount}.`;
                document.getElementById('orderButton').disabled = true;
            } else {
                errorSpan.textContent = '';
                document.getElementById('orderButton').disabled = false;
            }
        }


        document.addEventListener('DOMContentLoaded', function() {
            var form = document.querySelector('form'); // Adjust the selector if you have multiple forms

            form.addEventListener('submit', function(event) {
                var inputs = form.querySelectorAll('input[name="counts[]"]');
                for (var i = 0; i < inputs.length; i++) {
                    if (!/^\d+$/.test(inputs[i].value)) {
                        alert('Please enter a valid integer for all count fields.');
                        event.preventDefault();
                        return false;
                    }
                }
                return true;
            });
        });

        document.getElementById('customers').addEventListener('change', function() {
            var storeName = this.value;

            if (storeName) {
                $.ajax({
                    url: 'check_order.php',
                    type: 'POST',
                    data: {
                        store_name: storeName
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.order_exists) {
                            alert('An order has already been placed for this customer in the current month.');
                            document.getElementById('orderButton').disabled = true;
                        } else {
                            document.getElementById('orderButton').disabled = false;
                        }
                    }
                });
            } else {
                document.getElementById('orderButton').disabled = true;
            }
        });

        function validatecustomer(event) { //valiadte customer select before form submission
            var customer = document.getElementById("customers").value;
            if (customer === "") {
                event.preventDefault();
                var message = "Please Select Customer First";
                showPopup(message); // Prevent form submission
                // alert();
            }
        }

        function validatePaymentMethod(event) { // validate payment method selection before sublitting the form
            var paymentMethod = document.getElementById("payment_method").value;
            if (paymentMethod === "") {
                event.preventDefault(); // Prevent form submission
                var message = "Please Select Payment Method";
                showPopup(message);
                // alert();
            }
        }

        function redirectconfirm(event) {
            window.location.href = "redirect.html";
        }

        function back() { //back link
            window.history.back();
        }
        document.getElementById('main_category').addEventListener('change', function() { //load subcategoried from file using request
            var mainCategory = this.value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('subcategory-container').innerHTML = this.responseText;
                }
            };
            xhttp.open('GET', '/common/get_subcategories.php?main_category=' + mainCategory, true);
            xhttp.send();
        });

        function toggleCustomPaymentFields() { //showing and hiding payment fields according to selected payment method
            var paymentMethod = document.getElementById('payment_method');
            var customPaymentFields = document.getElementById('custom_payment_fields');
            if (paymentMethod.value === 'custom') {
                customPaymentFields.style.display = 'block';
            } else {
                customPaymentFields.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', function() { //specialy for payment method "credit" handling with period
            var paymentMethodSelect = document.getElementById('payment_method');
            var creditPeriodLabel = document.getElementById('credit_period_label');
            var creditPeriodInput = document.getElementById('credit_period');
            paymentMethodSelect.addEventListener('change', function() {
                if (paymentMethodSelect.value === 'credit') {
                    creditPeriodLabel.style.display = 'block';
                    creditPeriodInput.style.display = 'block';
                } else {
                    creditPeriodLabel.style.display = 'none';
                    creditPeriodInput.style.display = 'none';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            var paymentMethodSelect = document.getElementById('payment_method');
            var range1 = document.getElementById('custom_payment_fields');
            paymentMethodSelect.addEventListener('change', function() {
                if (paymentMethodSelect.value === 'custom') {
                    toggleCustomPaymentFields();
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            var paymentMethodSelect = document.getElementById('payment_method');
            var custompayment = document.getElementById('custom_payment');
            paymentMethodSelect.addEventListener('change', function() {
                if (paymentMethodSelect.value === "Select Payment Method" || paymentMethodSelect.value === 'check' || paymentMethodSelect.value === 'credit') {
                    custompayment.style.display = 'none';
                } else if (paymentMethodSelect.value === 'cash') {
                    custompayment.style.display = 'block';
                } else {
                    custompayment.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var removeButtons = document.querySelectorAll('input[name="remove_order[]"]');
            removeButtons.forEach(function(button) {
                button.addEventListener('change', function() {
                    if (this.checked) {
                        var rowIndex = this.value;
                    }
                });
            });

            var clearOrderButton = document.querySelector('button[name="clear_order"]');
            if (clearOrderButton) {
                clearOrderButton.addEventListener('click', function() {

                    var selectedIndexes = [];

                    removeButtons.forEach(function(button, index) {
                        if (button.checked) {
                            selectedIndexes.push(index); //add selected index to the array
                        }
                    });

                    var orderDetails = <?php echo json_encode($_SESSION['order_details']); ?>;
                    selectedIndexes.reverse().forEach(function(index) {
                        orderDetails.splice(index, 1);
                    });

                    var checkedRows = document.querySelectorAll('input[name="remove_order[]"]:checked');
                    checkedRows.forEach(function(row) {
                        var rowToRemove = row.closest('tr');
                        rowToRemove.parentNode.removeChild(rowToRemove);
                    });

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/common/update_order_details.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                console.log(xhr.responseText);
                            } else {
                                console.error('Error:', xhr.status);
                            }
                        }
                    };
                    xhr.send('selectedIndexes=' + JSON.stringify(selectedIndexes)); //send request

                });
            }
        });
    </script>


</body>

</html>