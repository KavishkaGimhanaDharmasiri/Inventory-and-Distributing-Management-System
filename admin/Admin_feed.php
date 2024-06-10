<?php
// Start or resume the session
session_start();

// Include your database connection file
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['option_visit']) || !$_SESSION['option_visit'] || $_SESSION["state"] != 'admin') {
    acess_denie();
    exit();
} else {
    $_SESSION['admin_feed_visit'] = true;
}

// Fetch main categories from the database
$query = "SELECT DISTINCT main_cat FROM product";
$result = mysqli_query($connection, $query);

// Check for database query failures
if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}

// Fetch routes from the database
$route_query = "SELECT * FROM route";
$result1 = mysqli_query($connection, $route_query);

// Check for database query failures
if (!$result1) {
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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_order"]) && $_SESSION["state"] == 'admin') {
    handleAddOrder($connection);
    // Process form submission and update order details 
}

// Handle confirming an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"])) {
    // Process form submission and redirect to payment page
    handleConfirmOrder($connection);
}

// Function to handle adding an order
function handleAddOrder($connection)
{
    $mainCategory = $_POST["main_category"];
    $subcategories = getSubcategories($mainCategory, $connection);
    $counts = $_POST["counts"];
    $route_id = $_POST["route"];
    $_SESSION['selectRoute'] = $route_id;
    // Temporary storage for the order details
    $orderDetails = $_SESSION['order_details'] ?? [];
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

    // Update the session variable with the order details
    $_SESSION['order_details'] = $orderDetails;

    // Reset main category to the default value
    $_POST["main_category"] = "";
}



// Function to handle confirming an order
function handleConfirmOrder($connection)
{
    // Check if order_details is set in session
    if (!isset($_SESSION['order_details']) || empty($_SESSION['order_details'])) {
        die("No order details found.");
    }

    date_default_timezone_set('Asia/Colombo');
    $localTime = date('Y-m-d');

    $routes_id = $_SESSION['selectRoute'];

    $feed_query = "INSERT INTO feed (route_id, feed_date)
    VALUES ($routes_id, '$localTime')";
    $result = mysqli_query($connection, $feed_query);

    // Check for database query failure
    if (!$result) {
        die("Feed insertion query failed: " . mysqli_error($connection));
    }

    // Get the feed_id of the inserted record
    $feed_id = mysqli_insert_id($connection);

    $orderDetails = $_SESSION['order_details'];

    foreach ($orderDetails as $orderDetail) {
        $mainCategory = $orderDetail['main_category'];
        $subCategory = $orderDetail['sub_category'];
        $count = $orderDetail['count'];

        $query2 = "INSERT INTO feed_item (feed_id, main_cat, sub_cat, count) 
                   VALUES ($feed_id, '$mainCategory', '$subCategory', $count)";
        $result2 = mysqli_query($connection, $query2);

        // Check for database query failure
        if (!$result2) {
            die("Feed item insertion query failed: " . mysqli_error($connection));
        }
        echo '<div id="overlay"></div><div id="successModal"><div class="gif"></div>
        <button onclick="redirectToIndex()" class="sucess">OK</button>
        </div>';
    }

    // Clear order details from session
    unset($_SESSION['order_details']);

    // Redirect to a success page or perform any other action

    mysqli_close($connection);
    exit();
}
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
            echo "<td><input type='checkbox' name='remove_order[]' value='$index' style='padding-top:5px;'></td>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/divs.css">
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
        <div class="order-form">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <label for="Route_name"><b>Route<b></label>
                <select name="route" id="route">
                    <option value="">Select Route</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result1)) {
                        $selected = (isset($_POST['route']) && $_POST['route'] == $row['route']) ? 'selected' : '';
                        echo "<option value='{$row['route_id']}' $selected>{$row['route']}</option>";
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
                        echo "<span>$selectedMainCategory</span>";
                        echo "</div>";
                        foreach ($subcategories as $index => $subcategory) {
                            echo "<div>";
                            echo "<label for='count[$subcategory[sub_cat]]'>$subcategory[sub_cat]</label>";
                            echo "<input type='number' name='counts[]' id='count[$subcategory[sub_cat]]' required>";
                            echo "<input type='hidden' name='subcategories[]' value='$subcategory[sub_cat]'>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>

                <button type="submit" name="add_order">Add Products</button>

                <?php displayOrderTable(); ?>
                <br>
                <!-- Display Confirm Order button if there are items in the order -->
                <?php
                if (!empty($_SESSION['order_details'])) {
                    echo "<button class='confirm-order-button' type='submit' name='confirm_order'><b>Confirm Order</button>";
                }
                ?>
                <?php
                if (!empty($_SESSION['order_details'])) {
                    echo "<button type='button' name='clear_order' style='color:green; background-color:transparent;margin-top:0%;'><b>Clear Order</button>";
                }
                ?>

                <!-- Confirm Order button -->

            </form>


        </div>
    </div>


    <script>
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

        function showSuccess() {
            var overlay = document.getElementById('overlay');
            var successModal = document.getElementById('successModal');

            overlay.style.display = 'block';
            successModal.style.display = 'block';
        }

        function hideSuccess() {
            var overlay = document.getElementById('overlay');
            var successModal = document.getElementById('successModal');

            overlay.style.display = 'none';
            successModal.style.display = 'none';
        }

        function redirectToIndex() {
            hideSuccess();
            // Redirect to index.php
            window.location.href = '/common/option.php';
        }

        function back() {
            window.history.back();
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener for checkboxes
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