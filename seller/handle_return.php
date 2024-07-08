<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
// Include your database connection file

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id']) || $_SESSION["state"] != 'seller') {
    acess_denie();
    exit();
} else {
    $_SESSION['Return_Item_visit'] = true;
}

date_default_timezone_set('Asia/Colombo');
// Get the current date and time
$currentDateTime = new DateTime();

$cur_date = $currentDateTime->format('Y-m');

$route_id = $_SESSION['route_id'];
$customerQuery = "SELECT sto_name FROM customers WHERE route_id=$route_id";
$customerResult = mysqli_query($connection, $customerQuery);

if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = array();
}

function isDuplicateItem($store, $name, $count)
{
    foreach ($_SESSION['items'] as $item) {
        if ($item['store'] === $store && $item['name'] === $name && $item['count'] === $count) {
            return true;
        }
    }
    return false;
}
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Add_list"]) && $_SESSION["state"] == 'seller') {

    $selectedStore = $_POST["customers"];
    $_SESSION['selected_store'] = $selectedStore;
    $selectedname = $_POST["product_count"];
    $selectedcount = $_POST["pcount"];
    if (isset($_SESSION['product_id'])) {
        $product_id = $_SESSION['product_id'];

        // Add item details to session
        if (!isDuplicateItem($selectedStore, $selectedname, $selectedcount)) {
            $_SESSION['items'][] = array(
                'id' => $product_id,
                'store' => $selectedStore,
                'name' => $selectedname,
                'count' => $selectedcount
            );
        }
    }

    // Generate a new token for the next form submission
}
// $_POST["customers"] = $_SESSION['selected_stores'];
// Decode JSON data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm"]) && $_SESSION["state"] == 'seller') {
    $selectedStore = $_POST["customers"];
    $selectedcount = $_POST["pcount"];
    $product_id = $_SESSION['product_id'];
    $localTime = $cur_date;
    try {

        $pdo->beginTransaction();

        $insert_return = "insert into `returns`(route_id, store_name, return_date) values(:route_id,:store_name,:return_date)";
        $stmt2 = $pdo->prepare($insert_return);
        $stmt2->bindParam(':route_id', $route_id);
        $stmt2->bindParam(':store_name', $selectedStore);
        $stmt2->bindParam(':return_date', $localTime);
        $stmt2->execute();
        $return_id = $pdo->lastInsertId();

        foreach ($_SESSION['items'] as $item) {
            $productid = $item['id'];
            $productName = $item['name'];
            $productCount = $item['count'];
            $query = "INSERT INTO return_item (return_id, product_id, product_name,count) VALUES (:return_id, :product_id, :product_name, :count)";
            $stmt1 = $pdo->prepare($query);
            $stmt1->bindParam(':return_id', $return_id);
            $stmt1->bindParam(':product_id', $productid);
            $stmt1->bindParam(':product_name', $productName);
            $stmt1->bindParam(':count', $productCount);
            $stmt1->execute();
        }
        $pdo->commit();
        echo '<script>alert("Items Returned Sucessfully");</script>';
        unset($_SESSION['items']);
        // End the session
        session_write_close();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo '<script>alert("' . $e->getMessage() . '");</script>';
    }

    /*

        // Assuming you have sanitized your inputs to prevent SQL injection
        $productName = mysqli_real_escape_string($connection, $productName);
        $productCount = mysqli_real_escape_string($connection, $productCount);

        // Insert data into the return_item table
        $query = "INSERT INTO return_item (remain_cat, sub_cat, count) VALUES ('$productName', '', '$productCount')";
        mysqli_query($connection, $query);
    }*/
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["resetitem"])) {
    unset($_SESSION['items']);
    // End the session
    session_write_close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <title>Item Returns</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
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
            <a href="javascript:void(0)" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" onclick="back()" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">return products</span></a>


            </a>
        </div>
        <div class="order-form">
            <form id="messageForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
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
                <label for="product"><b>Return Product Name<b></label>
                <input type="text" class="form-control" id="product_count" name="product_count" placeholder="Type Return Product..">


                <div id="suggestedResults" class="list-group"></div><br>
                <label for="product"><b>Product Count<b></label>
                <input type="number" class="form-control" id="pcount" name="pcount" placeholder="Enter count Of Return..">
                <br>
                <button type="submit" id="Add_list" name="Add_list" onclick="checkemptyness(event)">Add to List</button>
                <br>


                <!-- Table to display selected products -->


                <?php

                if (isset($_SESSION['items']) && count($_SESSION['items']) > 0) {
                    echo '<table>
                    <thead>
                        <tr>
                            <th id="leftth">Product Name</th>
                            <th id="rightth">Count</th>
                        </tr>
                    </thead>
                    <tbody>';
                    foreach ($_SESSION['items'] as $item) {
                        echo "<tr>";
                        echo "<td ><b>" . htmlspecialchars($item['name']) . "</td>";
                        echo "<td style='text-align:center;'><b>" . htmlspecialchars($item['count']) . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
                </tbody>
                </table>
                <br>
                <?php
                if (!empty($_SESSION['items'])) {
                    echo '<button type="submit" id="confirm" name="confirm" style="">Confirm Return</button>';
                }
                ?>
                <?php
                if (!empty($_SESSION['items'])) {
                    echo '<button type="submit" id="resetitem" name="resetitem" style="background-color:transparent;color:green;margin-top:0px;">Clear</button>';
                }
                ?>
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function back() {
            window.history.back();
        }

        function checkemptyness(event) {
            var customerField = document.getElementById('customers');
            var product_Field = document.getElementById('product_count');
            var product_Field = document.getElementById('pcount');

            if (customerField.value.trim() === "" || product_Field.value.trim() === "" || product_Field.value.trim() === "") {
                event.preventDefault();
                window.alert("Field cannot be empty.");
            }

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

            /*  $('#Add_list').on('click', function() {
                  document.getElementById('productTable').style.display = 'block';
                  document.getElementById('confirm').style.display = 'block';
                  var selectedProduct = $('#product_count').val();
                  var productCount = $('#pcount').val(); // Assuming product count is also entered in the same input field
                  if (selectedProduct.trim() !== '' && productCount.trim() !== '') {
                      $('#productList').append('<tr><td>' + selectedProduct + '</td><td>' + productCount + '</td></tr>');
                      $('#product_count').val('');
                  }
              });*/
        });
    </script>

</body>

</html>