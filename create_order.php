<?php
session_start();

// Include your database connection file
include("db_connection.php");
require_once('den_fun.php');
if (!isset($_SESSION['index_visit']) || !isset($_SESSION['option_visit'])) {
    acess_denie();
    exit();
} else {
    $_SESSION['create_order_visit'] = true;
}


// Fetch main categories from the database
$query = "SELECT DISTINCT main_cat FROM product";
$result = mysqli_query($connection, $query);

$user_id = $_SESSION['user_id'];
$customerQuery = "SELECT sto_name FROM customers WHERE user_id=$user_id";
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
    header("Location: create_order.php");
    exit();
}

// Handle confirming an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"])) {
    handleConfirmOrder();

    $mainCategory = $_POST['main_category'];
    $subcategories = $_POST['subcategories'] ?? '';
    $counts = $_POST['counts'] ?? '';
    $selectedStore = $_POST["customer"];
    $_SESSION['selected_store'] = $selectedStore;

    exit();
}

// Edit order functionality
if (isset($_GET['edit_order']) && $_GET['edit_order'] == true && isset($_GET['ord_id'])) {
    // Retrieve order details from the database based on ord_id
    $ord_id = $_GET['ord_id'];
    $editQuery = "SELECT p.ord_id, p.ord_date, o.main_cat, o.sub_cat, o.order_count 
                  FROM primary_orders p 
                  JOIN orders o ON p.ord_id = o.ord_id 
                  WHERE p.ord_id = '$ord_id'";

    $editResult = mysqli_query($connection, $editQuery);

    // Check for database query failure
    if (!$editResult) {
        die("Database query failed: " . mysqli_error($connection));
    }

    // Fetch and store order details for pre-filling the form fields
    $orderDetails = [];
    while ($row = mysqli_fetch_assoc($editResult)) {
        $orderDetails[] = $row;
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

    $selectedStore = $_POST["customer"];
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

    // Update the session variable with the order details
    $_SESSION['order_details'] = $orderDetails;

    // Reset main category to the default value
    $_POST["main_category"] = "";
}

// Function to handle confirming an order
function handleConfirmOrder()
{
    include('db_connection.php');
    $orderDetails = $_SESSION['order_details'] ?? [];
    $route_id = $_SESSION['route_id'];
    // Get necessary data for redirection
    $mainCategory = $_POST['main_category'];
    $subcategories = $_POST['subcategories'] ?? '';
    $counts = $_POST['counts'] ?? '';
    $selectedStore = $_POST["customer"];
    $_SESSION['selected_store'] = $selectedStore;

    try {
        $pdo->beginTransaction();
        $ord_type = "customer";
        $ord_state = "pending";
        $ord_date = date('Y-m-d H:i:s');
        $query3 = "INSERT INTO primary_orders(route_id,ord_date,store_name,order_type,order_state)
     VALUES(:route_id, :ord_date, :store_name, :order_type, :state)";

        $stmt1 = $pdo->prepare($query3);
        $stmt1->bindParam(':route_id', $route_id);
        $stmt1->bindParam(':ord_date', $ord_date);
        $stmt1->bindParam(':store_name', $selectedStore);
        $stmt1->bindParam(':order_type', $ord_type);
        $stmt1->bindParam(':state', $ord_state);
        $stmt1->execute();

        $ord_id = $pdo->lastInsertId();

        foreach ($orderDetails as $orderDetail) {
            $mainCategory = $orderDetail['main_category'];
            $subCategory = $orderDetail['sub_category'];
            $count = $orderDetail['count'];

            $query2 = "INSERT INTO orders (ord_id, main_cat, sub_cat, order_count) 
                   VALUES (:ord_id, :main_cat, :sub_cat, :order_count)";


            $stmt2 = $pdo->prepare($query2);
            $stmt2->bindParam(':ord_id', $ord_id);
            $stmt2->bindParam(':main_cat', $mainCategory);
            $stmt2->bindParam(':sub_cat', $subCategory);
            $stmt2->bindParam(':order_count', $count);
            $stmt2->execute();
        }
        // Commit the transaction
        $pdo->commit();
        echo '<div  style="top: 0;left: 0;width: 100%;height: 100%;backdrop-filter: blur(9px);z-index: 1;"></div><div id="successModal" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);padding: 20px;background-color: white;color:#4CAF50 ;z-index: 1;border-radius: 15px;border: 2px solid #4CAF50;height: 150px;width: 200px;"><div class="gif" style="';
        echo "background: url('gif4.gif') no-repeat center center;";
        echo 'margin-left: 25%;align-content: center;height: 95px;width: 95px;margin-bottom: 20px;"></div><button onclick="redirectToIManage()" class="sucess" style="width: 50px;padding: 10px;background-color: #4caf50;color: #fff;border: none;border-radius: 15px;cursor: pointer;width: calc(100% - 5px);margin-bottom: 5px;">OK</button></div>';
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}

// Function to display the orders table
function displayOrderTable()
{
    if (isset($_SESSION['order_details']) && !empty($_SESSION['order_details'])) {
        echo "<table>";
        echo "<thead>";
        echo "<tr><th>Check</th><th>Main Product</th><th>Sub Products</th><th>Count</th></tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($_SESSION['order_details'] as $order) {
            echo "<tr>";
            echo "<td><input type='checkbox' name='username' class='form-control'></td>";
            echo "<td>{$order['main_category']}</td>";
            echo "<td>{$order['sub_category']}</td>";
            echo "<td>{$order['count']}</td>";
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="mobile.css">
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <?php
            if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                // Generate back navigation link using HTTP_REFERER
                echo '<a href="' . $_SERVER['HTTP_REFERER'] . '" class="back-link" style="float:left;font-size:20px; "><i class="fa fa-angle-left"></i></a>';
            } else {
                // If no referrer is set, provide a default back link
                echo '<a href="javascript:history.go(-1);" class="back-link" style="float:left; font-size:30px;"><i class="fa fa-angle-left"></i></a>';
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
        <div class="order-form">
            <form method="POST" action="create_order.php">

                <label for="customer"><b>Customer Name</b></label>
                <select name="customer" id="customer">
                    <option value="">Select Customer</option>
                    <?php
                    while ($customerRow = mysqli_fetch_assoc($customerResult)) {
                        $selectedCustomer = (isset($_POST['customer']) && $_POST['customer'] == $customerRow['sto_name']) ? 'selected' : '';
                        echo "<option value='{$customerRow['sto_name']}' $selectedCustomer selected>{$customerRow['sto_name']}</option>";
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

                <div class="subcategory-container" id="subcategory-container" style="height: 100%; ">
                    <?php
                    // Display subcategories based on the selected main category
                    if (isset($_POST['main_category'])) {
                        $selectedMainCategory = $_POST['main_category'];
                        $subcategories = getSubcategories($selectedMainCategory, $connection);

                        // Display the main category only once
                        echo "<div>";
                        echo "<label>Main Category:</label>";
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

                <button type="submit" name="add_order"><b>Add Order</button>

                <?php displayOrderTable(); ?>
                <!-- Display Confirm Order button if there are items in the order -->
                <?php
                if (!empty($_SESSION['order_details'])) {;
                    echo '<tr><th><input type="checkbox" required></th><th><p class="tooltip" style="cursor:pointer; text-align: center;">Are you sure about comfirming this order.</th><tr><span class="tooltiptext">The Order that You Make, is pending order until 24 hours, It may Automatically confirmed to system after 24 hours. Withing 24 hours You can make necessary changes.for more details go for My Orders section</span></p>';
                }
                echo "<b><button class='confirm-order-button' type='submit' name='confirm_order'><b>Confirm Order</button>";
                echo '<b><button type="button" name="clear_order" style="color:green; background-color:transparent; border:2px solid green"><b>Clear Order</button>';

                ?>
            </form>


        </div>
    </div>


    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }
        document.getElementById('main_category').addEventListener('change', function() {
            var mainCategory = this.value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('subcategory-container').innerHTML = this.responseText;
                }
            };
            xhttp.open('GET', 'get_subcategories.php?main_category=' + mainCategory, true);
            xhttp.send();
        });

        function showmassage() {
            document.write("more details");
        }
    </script>

</body>

</html>