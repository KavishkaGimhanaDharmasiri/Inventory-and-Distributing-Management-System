<?php
// Start or resume the session
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");


if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id'])) {
    acess_denie();
    exit();
} else {
    $_SESSION['new_sale_order_visit'] = true;
}


// Fetch main categories from the database
$query = "SELECT DISTINCT main_cat FROM feed_item";
$result = mysqli_query($connection, $query);
$route_id = $_SESSION['route_id'];
$customerQuery = "SELECT sto_name FROM customers WHERE route_id=$route_id";
$customerResult = mysqli_query($connection, $customerQuery);

// Check for database query failures
if (!$customerResult || !$result) {
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_order"])) {
    // Process form submission and update order details
    handleAddOrder($connection);

    // Redirect after processing form submission
    header("Location: new_order.php");
    exit();
}

// Handle confirming an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"])) {
    // Process form submission and redirect to payment page
    handleConfirmOrder($connection);

    $mainCategory = $_POST['main_category'];
    $subcategories = $_POST['subcategories'] ?? '';
    $counts = $_POST['counts'] ?? '';
    $selectedStore = $_POST["customers"];
    $_SESSION['selected_store'] = $selectedStore;
    $selectedPaymentMethod = $_POST['payment_method'] ?? '';
    $_SESSION['selected_payment_method'] = $selectedPaymentMethod;
    $pay_period = ($payment_method == 'credit') ? $_POST['credit_period'] : null;
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

    // Redirect after processing form submission
    header("Location: payment.php?main_category=$mainCategory&subcategories=$subcategories&counts=$counts&payment_method=$selectedPaymentMethod");
    exit();
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
    $_SESSION['selected_store'] = $selectedStore;

    // Check if there's already an order for the selected main category
    $existingOrderIndex = null;
    foreach ($orderDetails as $index => $order) {
        if ($order['main_category'] == $mainCategory) {
            $existingOrderIndex = $index;
            break;
        }
    }

    if ($existingOrderIndex !== null) {
        // Update the existing order for the selected main category
        foreach ($subcategories as $index => $subcategory) {
            // Check if the count is greater than 0 before updating the order details
            if ($counts[$index] > 0) {
                $orderDetails[$existingOrderIndex]['sub_category'] = $subcategory['sub_cat'];
                $orderDetails[$existingOrderIndex]['count'] = $counts[$index];
            }
        }
    } else {
        // Add a new order for the selected main category
        foreach ($subcategories as $index => $subcategory) {
            // Check if the count is greater than 0 before adding to the order details
            if ($counts[$index] > 0) {
                $orderDetails[] = [
                    'main_category' => $mainCategory,
                    'sub_category' => $subcategory['sub_cat'],
                    'count' => $counts[$index]
                ];
            }
        }
    }
    $_SESSION['selected_store'] = $selectedStore;
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
    $_SESSION['selected_store'] = $selectedStore;
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


    // Subtract order amount from existing amount in the database
    /* foreach ($orderDetails as $order) {
        $mainCategory = $order['main_category'];
        $subCategory = $order['sub_category'];
        $count = $order['count'];

        // Update feed_item table
        $query = "UPDATE feed_item 
                  SET count = count - $count 
                  WHERE main_cat = '$mainCategory' 
                  AND sub_cat = '$subCategory'";

        $result = mysqli_query($connection, $query);

        if (!$result) {
            // Error occurred while updating the database
            echo "Error: Unable to update product quantity.";
            return; // Exit function
        }
    }*/

    // Clear order details from session

    // Redirect or perform further actions
    // (You can redirect the user to another page or perform any additional actions here)
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                <a href="#" onclick="logout()">Logout</a>
            </div>

            <a href="javascript:void(0);" class="icon" style="font-size:20px;" onclick="openNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <div class="order-form" id="order-form">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">



                <label for="customer"><b>Customer Name<bb></label>
                <select name="customers" id="customers" required>
                    <option value=""><b>Select Customer<b></option>
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

                <label for="main_category"><b>Main Product</b></label>
                <select name="main_category" id="main_category">
                    <option value="">Select Main Product</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = (isset($_POST['main_category']) && $_POST['main_category'] == $row['main_cat']) ? 'selected' : '';
                        echo "<option value='{$row['main_cat']}' $selected>{$row['main_cat']}</option>";
                    }
                    ?>
                </select>

                <div class="subcategory-container" id="subcategory-container">
                    <?php
                    // Display subcategories based on the selected main category
                    if (isset($_POST['main_category'])) {
                        $selectedMainCategory = $_POST['main_category'];
                        $subcategories = getSubcategories($selectedMainCategory, $connection);

                        // Display the main category only once
                        echo "<div>";
                        echo "<label><b>Main Product</label>";
                        echo "<span>$selectedMainCategory</span>";
                        echo "</div>";
                        foreach ($subcategories as $index => $subcategory) {
                            echo "<div>";
                            echo "<label for='count[$subcategory[sub_cat]]' id='r'><b>$subcategory[sub_cat]</label>";
                            echo "<input type='number' name='counts[]' id='count[$subcategory[sub_cat]]' required>";
                            echo "<input type='hidden' name='subcategories[]' value='$subcategory[sub_cat]'>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>

                <button type="submit" name="add_order"><b>Add Order</button>

                <?php displayOrderTable(); ?>
                <br>
                <?php
                if (!empty($_SESSION['order_details'])) {
                    echo '<label for="payment_method"><b>Payment Method</label>';
                    echo '<select name="payment_method" id="payment_method" onchange="toggleCustomPaymentFields()" required>';
                    if (isset($_SESSION['selected_payment_method'])) {
                        echo "<option value='{$_SESSION['selected_payment_method']}' selected>{$_SESSION['selected_payment_method']}</option>";
                    }
                    echo '<option value="">Select Payment Method</option>
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="credit">Credit</option>
                <option value="customHi">Custom Payment</option>
                <option value="custom">Custom Discoun Payment</option>
                </select>
                <div id="custom_payment_fields" style="display: none;">
                    <label for="custom_range_100">Discount for Price Range Rs.10 to Rs.500</label>
                    <input type="number" name="custom_range_100" id="custom_range_100">

                    <label for="custom_range_500">Discount for Price Range Rs.500 to Rs.1500</label>
                    <input type="number" name="custom_range_500" id="custom_range_500">

                    <label for="custom_range_1500">Discount for Price Range Rs.1500 to Rs.5000</label>
                    <input type="number" name="custom_range_1500" id="custom_range_1500">

                </div>

                <label for="credit_period" id="credit_period_label" style="display: none;">Credit Period (in days):</label>
                <input type="text" name="credit_period" id="credit_period" style="display: none;">';
                }
                ?>


                <!-- Display Confirm Order button if there are items in the order -->
                <?php
                if (!empty($_SESSION['order_details'])) {
                    echo "<button type='submit' class='confirm-order-button' name='confirm_order'><b>Confirm Order</button>";
                }
                ?>
                <?php
                if (!empty($_SESSION['order_details'])) {
                    echo "<button type='button' name='clear_order' style='color:green; background-color:transparent; border:2px solid green'><b>Clear Order</button>";
                }
                ?>

                <!-- Confirm Order button -->

            </form>


        </div>
        <?php
        if (!isset($_SESSION["ad_state"])) {
            echo '<div style="border:1px solid green;font-weight:normal; color:green;background-color:#d9fcd2; " class="order-form" id="advertise">
            <p style="color: green; font-weight: normal;">Note<a onclick="closeintro()" style="float:right;font-size:15px; color:green; "><i class="fa fa-close" style="cursor:pointer;";></i></a><br><br>a). First select Customer Name from dropdown <br><br>b). Select Main product from dropdown. Items will be load automatically according to selected Main Product.<br><br>
            c).Add disired item count in each box below item then click Add order button the previously enterd details are shown in table below Add order Button. <br><br>
            d.)Again you can perform b). And c). steps to add multipel items to order. 
            <br><br>if You want remove any Items from order click check box and click Clear Order Button to remove that entry And You can Remove muliple Entry at time by checking multiple entry. </p>
        </div>';
        }
        if (isset($_SESSION["ad_state"])) {
            echo '<div style="border:1px solid green; color:green; display:none; " class="order-form" id="advertise">
            <p style="color: green;">Note<a onclick="closeintro()" style="float:right;font-size:15px; color:green;  "><i class="fa fa-close"></i></a><br><br>a). First select Customer Name from dropdown <br><br>b). Select Main product from dropdown. Items will be load automatically according to selected Main Product.<br><br>c).Add disired item count in each box below item then click Add order button the previously enterd details are shown in table below Add order Button. <br><br>d.)Again you can perform b). And c). steps to add multipel items to order. <br><br>if You want remove any Items from order click check box and click Clear Order Button to remove that entry And You can Remove muliple Entry at time by checking multiple entry. </p>
        </div>';
        }
        ?>

    </div>


    <script>
        function closeintro() {
            document.getElementById("advertise").style.display = "none";
            <?php $_SESSION["ad_state"] = "true"; ?>

        }

        function back() {
            window.history.back();
        }
        document.getElementById('main_category').addEventListener('change', function() {
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

        function toggleCustomPaymentFields() {
            var paymentMethod = document.getElementById('payment_method');
            var customPaymentFields = document.getElementById('custom_payment_fields');
            if (paymentMethod.value === 'custom') {
                customPaymentFields.style.display = 'block';
            } else {
                customPaymentFields.style.display = 'none';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
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
                            selectedIndexes.push(index);
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
                    xhr.send('selectedIndexes=' + JSON.stringify(selectedIndexes));

                });
            }


        });
    </script>

</body>

</html>