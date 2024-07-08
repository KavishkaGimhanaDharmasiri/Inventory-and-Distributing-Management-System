<?php
// Start or resume the session
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");


if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit'])  || $_SESSION["state"] != 'admin') {
    acess_denie();
    exit();
} else {
    $_SESSION['new_sale_order_visit'] = true;
}
$route_query = "SELECT * FROM route";
$result2 = mysqli_query($connection, $route_query);

$query1 = "SELECT DISTINCT main_cat FROM product";
$result1 = mysqli_query($connection, $query1);
// Check for database query failures
if (!$result1 || !$result2) {
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_order"])  && $_SESSION["state"] == 'admin') {
    // Process form submission and update order details
    handleAddOrder($connection);
    // Redirect after processing form submission
    header("Location: Admin_feed.php");
    exit();
}

// Handle confirming an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"])  && $_SESSION["state"] == 'admin') {
    // Process form submission and redirect to payment page
    handleConfirmOrder($connection);

    $mainCategory = $_POST['main_category'];
    $subcategories = $_POST['subcategories'] ?? '';
    $counts = $_POST['counts'] ?? '';

    // Redirect after processing form submission
    //exit();
}

// Close the database connection

// Function to handle adding an order
function handleAddOrder($connection)
{
    $route_id_name = $_POST["route"];

    list($selected_route_id, $selected_route_name) = explode('-', $route_id_name);
    $_SESSION['selectRouteName'] = $selected_route_name;

    if (isset($_POST["route"]) && !isset($_SESSION['pre_order'])) {
        date_default_timezone_set('Asia/Colombo');
        $currentDateTime = new DateTime(); // Get the current date and time
        $cur_date = $currentDateTime->format('Y-m');
        $sql = "SELECT o.main_cat, o.sub_cat, o.order_count AS count
    FROM orders o
    LEFT JOIN primary_orders p ON o.ord_id = p.ord_id
    WHERE p.order_type='customer' AND p.route_id=$selected_route_id
    AND DATE_FORMAT(p.ord_date, '%Y-%m')='$cur_date'";

        $result = $connection->query($sql);

        $order_details = [];

        // Check if there are results and process them
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $main_category = $row['main_cat'];
                $sub_category = $row['sub_cat'];
                $count = $row['count'];

                // Add to the session array
                $order_details[] = [
                    'main_category' => $main_category,
                    'sub_category' => $sub_category,
                    'count' => $count
                ];
            }
            $_SESSION['order_details'] = $order_details;
            $_SESSION['pre_order'] = true;
        }
    }

    $mainCategory = $_POST["main_category"];
    $subcategories = getSubcategories($mainCategory, $connection);
    $counts = $_POST["counts"];


    // Temporary storage for the order details
    $orderDetails = $_SESSION['order_details'] ?? [];

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
    include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
    $orderDetails = $_SESSION['order_details'] ?? [];

    // Get necessary data for redirection
    $mainCategory = $_POST['main_category'];
    $subcategories = $_POST['subcategories'] ?? '';
    $counts = $_POST['counts'] ?? '';
    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = new DateTime(); // Get the current date and time

    $cur_date = $currentDateTime->format('Y-m-d');

    $route_id_name = $_POST["route"];

    list($selected_route_id, $selected_route_name) = explode('-', $route_id_name);
    $_SESSION['selectRouteName'] = $selected_route_name;

    try {
        $pdo->beginTransaction();

        // Insert into feed table
        $feed_query = "INSERT INTO feed (route_id, feed_date) VALUES (:route_id, :feed_date)";
        $stmt3 = $pdo->prepare($feed_query);
        $stmt3->bindParam(':route_id', $selected_route_id);
        $stmt3->bindParam(':feed_date', $cur_date);

        if ($stmt3->execute()) {
            echo "<script>alert('Product allocated to Route Name: $selected_route_name is Successful.');</script>";
        }

        // Get the feed_id of the inserted record
        $feed_id = $pdo->lastInsertId();

        $orderDetails = $_SESSION['order_details'];

        foreach ($orderDetails as $orderDetail) {
            $mainCategory = $orderDetail['main_category'];
            $subCategory = $orderDetail['sub_category'];
            $count = $orderDetail['count'];

            // Insert into feed_item table
            $query2 = "INSERT INTO feed_item (feed_id, main_cat, sub_cat, count) 
                       VALUES (:feed_id, :main_cat, :sub_cat, :count)";
            $stmt = $pdo->prepare($query2);
            $stmt->bindParam(':feed_id', $feed_id);
            $stmt->bindParam(':main_cat', $mainCategory);
            $stmt->bindParam(':sub_cat', $subCategory);
            $stmt->bindParam(':count', $count);

            $stmt->execute();

            // Update product table
            $query3 = "UPDATE product SET count = count - :count WHERE main_cat = :main_cat AND sub_cat = :sub_cat";
            $stmt1 = $pdo->prepare($query3);
            $stmt1->bindParam(':count', $count);
            $stmt1->bindParam(':main_cat', $mainCategory);
            $stmt1->bindParam(':sub_cat', $subCategory);

            $stmt1->execute();
        }

        $pdo->commit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo '<script>alert("' . $e->getMessage() . '");</script>';
    }

    // Clear order details from session
    unset($_SESSION['order_details']);
    unset($_SESSION['selectRouteName']);
    unset($_SESSION['pre_order']);
    session_write_close();
    header("Location: Admin_feed.php");
    // Redirect to a success page or perform any other action
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
    <title>Distribute produccts</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/divs.css">
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


            <a href="javascript:void(0)" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" onclick="back()" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">distrubute products</span></a>
        </div>
        <div id="popup" class="popup" onclick="closePopup()">
            <div class="popup-content">
                <span class="close" style="font-size: 14px;" onclick="closePopup()">&#10005;</span>
                <label id="message" style="font-size: 14px;"></label>
            </div>
        </div>
        <div class="order-form" id="order-form">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="Route_name"><b>Route<b></label>
                <select name="route" id="route" required>
                    <option value="">Select Route</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result2)) {
                        $selected = ($_SESSION['selectRouteName'] == $row['route']) ? 'selected' : '';
                        echo "<option value='{$row['route_id']}-{$row['route']}' $selected>{$row['route']}</option>";
                    }

                    ?>
                </select>
                <label id="togal" style="color: indianred;text-align:center;cursor:pointer;" onclick="showmain()">Need to Add More product</label>
                <div id="showmaincat" style="display: none;">
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
                </div>

                <div class="subcategory-container" id="subcategory-container">
                </div>
                <button type="submit" name="add_order" id="orderButton"><i class="fa fa-plus" style="font-size: 14px;"></i>&nbsp;&nbsp;Add Items</button>

                <?php displayOrderTable(); ?>
                <br>

                <!-- Display Confirm Order button if there are items in the order -->
                <?php
                if (!empty($_SESSION['order_details'])) {
                    $route_name = $_SESSION['selectRouteName'];
                    echo "<button type='submit' class='confirm-order-button' name='confirm_order' onclick='showrouteMethod($route_name)>
                    <i class='fa fa-check' style='font-size: 14px;'></i>&nbsp;&nbsp;Confirm Order</button>";
                }
                ?>
                <?php
                if (!empty($_SESSION['order_details'])) {
                    echo "<button type='button' name='clear_order' style='color:green; background-color:transparent;'><i class='fa fa-minus'></i>&nbsp;&nbsp;Remove Items</button>";
                }
                ?>

                <!-- Confirm Order button -->

            </form>


        </div>
        <?php
        if (!isset($_SESSION["ad_state"])) {
            echo '<div style="border:1px solid green;font-weight:normal; color:green;background-color:#d9fcd2; " class="order-form" id="advertise">
            <p style="color: green; font-weight: normal; -webkit-user-select: none;">Note<a onclick="closeintro()" style="float:right;font-size:15px; color:green; "><i class="fa fa-close" style="cursor:pointer;"></i></a><br><br>
            a). First select Route name from dropdown <br><br>
            b). Click "Add Product" this will Automatically load Items to table according route based on Customer orders<br><br>
            c). If Need Add more items click "Need To Add More Product" this will show Main category to select desired Product.<br><br> 
            d). Fill the count And click "Add order" button the previously enterd details are shown in table below with previous detils<br><br>
            e). Again you can perform c). And d). steps to add multiple items to order. 
            <br><br>if You want remove any Items from order click check box and click "Remove Item" Button to remove that entry And You can Remove muliple Entry at time by checking multiple entry. </p>
        </div>';
        }
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/javascript/test1.js"></script>
    <script>
        function closeintro() {
            document.getElementById("advertise").style.display = "none";

        }

        function showmain() {
            const details = document.getElementById("showmaincat");
            details.style.display = details.style.display === 'none' ? 'block' : 'none';
        }

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

        function showrouteMethod(route_name) {
            var message = "Products are allocated Route Name :" + route_name + " is sucessfull.";
            showPopup(message);

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