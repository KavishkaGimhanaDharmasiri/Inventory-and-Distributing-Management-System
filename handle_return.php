<?php
session_start();
require_once "db_connection.php";
require_once('den_fun.php');
require 'notification_area.php';
// Include your database connection file

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id'])) {
    acess_denie();
    exit();
} else {
    $_SESSION['Return_Item_visit'] = true;
}

$route_id = $_SESSION['route_id'];
$customerQuery = "SELECT sto_name FROM customers WHERE route_id=$route_id";
$customerResult = mysqli_query($connection, $customerQuery);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize returnItems array to avoid error
    $returnItems = [];

    if (isset($_POST['return_items'])) {
        $returnItemsJSON = $_POST['return_items'];
        // Decode JSON data
        $returnItems = json_decode($returnItemsJSON, true);
    }

    if (isset($_SESSION['selected_store'])) {
        $selectedStore = $_SESSION['selected_store'];
    } else {
        $selectedStore = $_POST["customers"];
    }
    // Decode JSON data

    $localTime = date('Y-m-d h:i');
    try {

        $pdo->beginTransaction();

        $insert_return = "insert into `returns`(route_id, store_name, return_date) values(:route_id,:store_name,:return_date)";
        $stmt2 = $pdo->prepare($insert_return);
        $stmt2->bindParam(':route_id', $route_id);
        $stmt2->bindParam(':store_name', $selectedStore);
        $stmt2->bindParam(':return_date', $localTime);
        $stmt2->execute();
        $return_id = $pdo->lastInsertId();

        foreach ($returnItems as $item) {
            echo '<script>alert("alert 1");</script>';
            $productName = $item['productName'];
            $productCount = $item['productCount'];
            $productId = $item['product_Id'];
            echo '<script>alert("alert");</script>';
            // Assuming you have sanitized your inputs to prevent SQL injection
            //    $productName = mysqli_real_escape_string($connection, $productName);
            //   $productCount = mysqli_real_escape_string($connection, $productCount);
            //$product_id=$_SESSION['product_id'] ;
            // Insert data into the return_item table

            $query = "INSERT INTO return_item (return_id, product_id, product_name,count) VALUES (:return_id, :product_id, :product_name, :count)";
            $stmt1 = $pdo->prepare($query);
            $stmt1->bindParam(':return_id', $return_id);
            $stmt1->bindParam(':product_id', $productId);
            $stmt1->bindParam(':product_name', $productName);
            $stmt1->bindParam(':count', $productCount);
            $stmt1->execute();
        }
        $pdo->commit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo '<script>alert("' . $e->getMessage() . '");</script>';
    }

    /* $insert_return = "INSERT INTO `returns`(route_id,store_name,return_date) VALUES($route_id,'$selectedStore',$localTime')";
    mysqli_query($connection, $insert_return);

    // Insert each returned item into the database
    foreach ($returnItems as $item) {
        $productName = $item['productName'];
        $productCount = $item['productCount'];

        // Assuming you have sanitized your inputs to prevent SQL injection
        $productName = mysqli_real_escape_string($connection, $productName);
        $productCount = mysqli_real_escape_string($connection, $productCount);

        // Insert data into the return_item table
        $query = "INSERT INTO return_item (remain_cat, sub_cat, count) VALUES ('$productName', '', '$productCount')";
        mysqli_query($connection, $query);
    }*/
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="mobile.css">
    <style type="text/css">
        .order-form {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow-y: auto;
            overflow: visible;
            margin-top: 5%;
        }

        .suggestion {
            padding: 5px;
            cursor: pointer;
            font-weight: bold;
            color: black;
        }

        .suggestion:hover {
            background-color: #C5FFBE;
        }

        tr,
        td {
            padding: 8px;
        }
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">
            <?php
            topnavigation();
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
            </a>
        </div>
        <div class="order-form">
            <form id="messageForm" action="handle_return.php" method="POST">
                <label for="customer"><b>Customer Name<b></label>
                <select name="customers" id="customers">
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
                <br>
                <br>
                <label for="product"><b>Return Product Name<b></label>
                <input type="text" class="form-control" id="product_count" placeholder="Type Return Product..">


                <div id="suggestedResults" class="list-group"></div>
                <label for="product"><b>Return Product Name<b></label>
                <input type="number" class="form-control" id="pcount" placeholder="Enter count Of Return..">
                <br>
                <button type="button" id="Add_list" name="Add_list">Add to List</button>
                <br>


                <!-- Table to display selected products -->
                <table id="productTable" class="table" style="display: none;">
                    <thead>
                        <tr>
                            <th style="width: 90%; text-align:left;" id="leftth">Product Name</th>
                            <th style="width: 20%;" id="rightth"> Count</th>
                        </tr>
                    </thead>
                    <tbody id="productList">
                        <!-- Selected products will be displayed here -->
                    </tbody>
                </table>
                <br>
                <br>
                <button type="submit" id="confirm" name="confirm" style="display: none;">Confirm Return</button>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "150px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }
        $(document).ready(function() {
            var typingTimer;
            var doneTypingInterval = 500; // Adjust the delay (in milliseconds) as needed

            $('#product_count').on('keyup', function() {
                clearTimeout(typingTimer);
                var input = $(this).val();
                if (input.length > 0) {
                    typingTimer = setTimeout(function() {
                        $.ajax({
                            url: 'suggest.php',
                            type: 'POST',
                            data: {
                                input: input
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#suggestedResults').empty();
                                $.each(response, function(index, value) {
                                    var mainCategory = value.split('(')[1].split(')')[0];
                                    var subCategory = value.split('(')[0].trim();
                                    $('#suggestedResults').append('<div class="suggestion" data-maincategory="' + mainCategory + '" data-subcategory="' + subCategory + '">' + value + '</div>');
                                });
                            }
                        });
                    }, doneTypingInterval);
                } else {
                    $('#suggestedResults').empty();
                }
            });

            $(document).on('click', '.suggestion', function() {
                var selectedText = $(this).text();
                var selectedMainCategory = $(this).data('maincategory');
                var selectedSubCategory = $(this).data('subcategory');
                $('#product_count').val(selectedSubCategory);
                $('#suggestedResults').empty();
            });

            $('#Add_list').on('click', function() {
                document.getElementById('productTable').style.display = 'block';
                document.getElementById('confirm').style.display = 'block';
                var selectedProduct = $('#product_count').val();
                var productCount = $('#pcount').val(); // Assuming product count is also entered in the same input field
                if (selectedProduct.trim() !== '' && productCount.trim() !== '') {
                    $('#productList').append('<tr><td>' + selectedProduct + '</td><td>' + productCount + '</td></tr>');
                    $('#product_count').val('');
                }
            });
        });
    </script>

</body>

</html>