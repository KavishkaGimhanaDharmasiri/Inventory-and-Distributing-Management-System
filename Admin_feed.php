<?php
// Start or resume the session
session_start();

// Include your database connection file
include("db_connection.php");

if(!isset($_SESSION['option_visit']) || !$_SESSION['option_visit'] ){
    echo"acess Dinied";
    exit();

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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_order"])) {
    // Process form submission and update order details
    handleAddOrder($connection);
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
    $_SESSION['selectRoute']=$route_id;
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

    $routes_id=$_SESSION['selectRoute'];

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
        header('Location:divs.php');
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
            echo "<tr><th>Main Product</th><th>Sub Products</th><th>Count</th></tr>";
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


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include your meta tags, stylesheets, and title -->
    <meta charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1" >
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="divs.css">
     <style>
        .order-form {
          background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            min-width: 320px;
            overflow-y: auto;
            overflow: visible;
            border: 1px solid #4caf50;
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
            border-radius: 15px;
            border:1px solid #4caf50;   
        }
           
        button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 15px;
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
            border-radius: 15px;
            padding: 10px;
            cursor: pointer;
            margin-top: 15px;
        }

        .confirm-order-button:hover {
            background-color: #45a049;
        }
     #successModal {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 20px;
      background-color: white;
      color:#4CAF50 ;
      z-index: 1;
      border-radius: 15px;
      border: 2px solid #4CAF50;
      height: 150px;
      width: 200px;
    }

    .sucess{
      width: 50px;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 15px;
            cursor: pointer;
             width: calc(100% - 5px);
    }

.gif{
     background: url('gif4.gif') no-repeat center center;
   margin-left: 25%;
    align-content: center;
    height: 95px;
    width: 95px;
    margin-bottom: 20px;
}

    #overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
     // background-color: white;
      backdrop-filter: blur(9px);
      z-index: 1;
    }
    </style>
    <title>New Order</title>
</head>
<body>
    <!-- Your HTML and form structure -->
    <div class="order-form">
        <form method="POST" action="Admin_feed.php">

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

            <div class="subcategory-container" id="subcategory-container" style="height: 100%;">
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
            <!-- Display Confirm Order button if there are items in the order -->
            <?php
            if (!empty($_SESSION['order_details'])) {
                echo "<button class='confirm-order-button' type='submit' name='confirm_order'>Confirm Adding Products</button>";
                echo '<button type="button" name="clear_order" style="color:green; background-color:transparent; border:2px solid green">Clear Order</button>';
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
    window.location.href = 'option.php';
  }
    </script>
</body>
</html>