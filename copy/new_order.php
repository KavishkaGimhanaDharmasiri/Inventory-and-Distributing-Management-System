<?php
// Remove or comment out the following line if session_start() is already called elsewhere
session_start();

// Include your database connection file
include("db_connection.php");

// Fetch main categories from the database
$query = "SELECT DISTINCT main_cat FROM product";
$result = mysqli_query($connection, $query);

$customerQuery = "SELECT sto_name FROM customers";
$customerResult = mysqli_query($connection, $customerQuery);

if (!$customerResult) {
    die("Database query failed: " . mysqli_error($connection));
}

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}

// Fetch subcategories for the selected main category
function getSubcategories($mainCategory, $connection)
{
    $query = "SELECT sub_cat FROM product WHERE main_cat = '$mainCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    $subcategories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $subcategories[] = [
            'sub_cat' => $row['sub_cat']
        ];
    }

    return $subcategories;
}

// Handle adding an order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_order"])) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_order"])) {
    // Check if $_SESSION['order_details'] is set
    $orderDetails = $_SESSION['order_details'] ?? [];

    // Insert the order details into the database (you need to implement this part)

    // Clear the temporary order details after confirming the order
   // unset($_SESSION['order_details']);

    // Get necessary data for redirection
    $mainCategory = $_POST['main_category'];
    $subcategories = $_POST['subcategories'] ?? '';
    $counts = $_POST['counts'] ?? '';
    $selectedStore = $_POST["customer"];
    $_SESSION['selected_store'] = $selectedStore;

    // Avoid output before header ?main_category=$mainCategory&subcategories=$subcategories&counts=$counts"
    ob_start();

    // Redirect to the payment page with necessary data
    header("Location: vm.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
          body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background: radial-gradient(circle, rgba(76,175,80,1) 0%, rgba(247,252,248,1) 0%, rgba(250,253,251,1) 23%, rgba(252,254,253,1) 36%, rgba(255,255,255,1) 47%, rgba(246,251,246,1) 59%, rgba(228,243,229,1) 68%, rgba(171,218,173,1) 100%, rgba(76,175,80,1) 100%);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-size: cover;
        }

        .order-form {
           background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
            overflow-y: auto;
            overflow: visible;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        select, input, button {
            width: calc(100% - 1px); /* Adjusted width to account for padding */
            padding: 10px;
            box-sizing: border-box;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .subcategory-container {
            margin-top: 15px;
        }

        /* Additional style for table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        /* Additional style for the Confirm Order button */
        .confirm-order-button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            margin-top: 15px;
        }

        .confirm-order-button:hover {
            background-color: #45a049;
        }
    </style>
    <title>New Order</title>
</head>
<body>
    <div class="order-form">
        <form method="POST" action="new_order.php">
            <label for="customer">Customer Name</label>
<select name="customer" id="customer" required>
    <option value="">Select Customer</option>
    <?php
    while ($customerRow = mysqli_fetch_assoc($customerResult)) {
        $selectedCustomer = (isset($_POST['customer']) && $_POST['customer'] == $customerRow['sto_name']) ? 'selected' : '';
        echo "<option value='{$customerRow['sto_name']}' $selectedCustomer>{$customerRow['sto_name']}</option>";
    }
    ?>
</select>

            <label for="main_category">Main Category</label>
            <select name="main_category" id="main_category">
                <option value="">Select Main Category</option>
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
                    echo "<label>Main Category:</label>";
                    echo "<span>$selectedMainCategory</span>";
                    echo "</div>";

                    foreach ($subcategories as $index => $subcategory) {
                        echo "<div>";
                        echo "<label for='count[$subcategory[sub_cat]]'>$subcategory[sub_cat]:</label>";
                        echo "<input type='number' name='counts[]' id='count[$subcategory[sub_cat]]' required>";
                        echo "<input type='hidden' name='subcategories[]' value='$subcategory[sub_cat]'>";
                        echo "</div>";
                    }
                }
                ?>
            </div>

            <button type="submit" name="add_order">Add Order</button>
            <button type="submit" name="clear_order">Clear Order</button>

            <!-- Display Confirm Order button if there are items in the order -->
            <?php
            if (!empty($_SESSION['order_details'])) {
                echo "<button class='confirm-order-button' type='submit' name='confirm_order'>Confirm Order</button>";
            }
            ?>
        </form>

        <!-- Display orders table -->
        <?php
        if (isset($_SESSION['order_details']) && !empty($_SESSION['order_details'])) {
            echo "<table>";
            echo "<thead>";
            echo "<tr><th>Main Category</th><th>Sub Category</th><th>Count</th></tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($_SESSION['order_details'] as $order) {
                echo "<tr>";
                echo "<td>{$order['main_category']}</td>";
                echo "<td>{$order['sub_category']}</td>";
                echo "<td>{$order['count']}</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }
        ?>
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

<?php
// Close the database connection
mysqli_close($connection);
?>
