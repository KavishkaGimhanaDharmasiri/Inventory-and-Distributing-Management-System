<?php
// Start or resume the session
session_start();
require_once('den_fun.php');
require_once('seq.php');
// Include your database connection file

if(!isset($_SESSION['option_visit']) || !$_SESSION['option_visit'] || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id'])){
acess_denie();
    exit();

}
else{
   $_SESSION['new_sale_order_visit']=true; 
}

include("db_connection.php");
// Fetch main categories from the database
$query = "SELECT DISTINCT main_cat FROM feed_item";
$result = mysqli_query($connection, $query);
$route_id=$_SESSION['route_id'];
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

    // Redirect after processing form submission
    header("Location: payment.php?main_category=$mainCategory&subcategories=$subcategories&counts=$counts");
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


/*function handleAddOrder($connection)
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
        // Check if there is sufficient product available
        $sufficientProduct = checkSufficientProduct($mainCategory, $subcategories, $counts, $connection);

        if ($sufficientProduct) {
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
        } else {
            // Product not available, show error message
            echo "Error: Unable to Add Items To Order. Causes ◼   Available Product Quantity is not Sufficient◼   Product is Not Avaialble for Your Route.Contact Adminstrator"; 
        }
    }

    // Update the session variable with the order details
    $_SESSION['order_details'] = $orderDetails;

    // Reset main category to the default value
    $_POST["main_category"] = "";
}

// Function to check if there is sufficient product available
function checkSufficientProduct($mainCategory, $subcategories, $counts, $connection)
{
    $routeId = $_SESSION['route_id'];
    $currentYear = date('Y');
    $currentMonth = date('m');

    $query = "SELECT * FROM feed_item 
              JOIN feed ON feed_item.feed_id = feed.feed_id 
              WHERE feed.route_id = $routeId 
              AND YEAR(feed.feed_date) = $currentYear 
              AND MONTH(feed.feed_date) = $currentMonth";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        // Error occurred while executing the query
        return false;
    }

    $availableProducts = array();

    // Fetch available products
    while ($row = mysqli_fetch_assoc($result)) {
        $availableProducts[$row['sub_cat']] = $row['count'];
    }

    // Check if there's sufficient product available for the new order
    foreach ($subcategories as $index => $subcategory) {
        if (!isset($availableProducts[$subcategory['sub_cat']]) || $availableProducts[$subcategory['sub_cat']] < $counts[$index]) {
            return false; 
        }
    }

    return true;
}*/


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
            echo '<tr><th id="leftth">Check</th><th>Main Product</th><th>Sub Products</th><th id="rightth">Count</th></tr>';
            echo "</thead>";
            echo "<tbody>";

            foreach ($_SESSION['order_details'] as $order) {
                echo "<tr>";
                echo "<td><input type='checkbox' name='username' class='form-control'></td>";
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
<html lang="en">
<head>
    <!-- Include your meta tags, stylesheets, and title -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="new_order.css">
    <link rel="stylesheet" type="text/css" href="seqnav.css">
     <style>
           .order-form {
          background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            min-width: 450px;
            overflow-y: auto;
            overflow: visible;
            margin-top: 20%; 
}
 ul.breadcrumb {
          list-style: none;
          background-color: transparent;
          position: absolute;
          top: 15px;
          left: 50%;
          text-decoration: none;
    color: #4caf50;
        }
        ul.breadcrumb li {
          display: inline;
          font-size: 12pt;
          text-decoration: none;
    color: #4caf50;
        }
        ul.breadcrumb li+li:before {
          padding: 8px;
          color: #4caf50;
          content: "▮";
        }
        ul.breadcrumb li a {
            text-decoration: none;
            color: #4caf50;
          color: #4caf50;
          text-decoration: none;
        }
        ul.breadcrumb li a:hover {
          color: green;
          text-decoration: none;
          margin: 0;
        }: fixed;
            </style>
    <title>New Order</title>
</head>
<body>
<?php 
sequence();
?>
    <!-- Your HTML and form structure -->
    <div class="order-form" id="order-form">
        <form method="POST" action="new_order.php">

    

            <label for="customer"><b>Customer Name<bb></label>
            <select name="customers" id="customers" >
                <option value=""><b>Select Customer<b></option>
                <?php
                if (isset($_SESSION['selected_store'])){ 
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

            <div class="subcategory-container" id="subcategory-container" style="height: 100%;">
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
            <!-- Display Confirm Order button if there are items in the order -->
            <?php
            if (!empty($_SESSION['order_details'])) {
                echo "<button class='confirm-order-button' type='submit' name='confirm_order'><b>Confirm Order</button>";
                echo '<button type="button" name="clear_order" style="color:green; background-color:transparent; border:2px solid green"><b>Clear Order</button>';
            }
            ?>
        </form>

        
    </div>
     <script>
        document.getElementById('main_category').addEventListener('change', function () {
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
    </script>
</body>
</html>