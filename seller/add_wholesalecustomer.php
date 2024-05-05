<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/email_sms.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id']) || !isset($_SESSION["state"])) {
    acess_denie();
    exit();
} else {
    $_SESSION['add_customer_visit'] = true;
}
$route_query = "SELECT * FROM route";
$result1 = mysqli_query($connection, $route_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    // Extract data from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];


    if ($_POST['email'] === null || $_POST['email'] === "") {
        $email = null;
    } else {
        $email = $_POST['email'];
    }

    $storename = $_POST['storename'];
    $storeregno = $_POST['storeregno'];
    $storeaddress = $_POST['storeaddress'];
    $location = $_POST['location'];
    try {


        $valid_query = "SELECT * FROM users WHERE  telphone_no= $telephone OR email='$email'";
        $result6 = mysqli_query($connection, $valid_query);

        if (mysqli_num_rows($result6) === 0) {
            echo '<script>alert("grrt");</script>';
            $pdo->beginTransaction();

            // Insert data into the customer table
            $customer_insert_query = "INSERT INTO users (firstName, LastName, telphone_no, Address, email) 
                         VALUES (:firstName, :LastName, :telphone_no, :Address, :email)";

            $stmt2 = $pdo->prepare($customer_insert_query);
            $stmt2->bindParam(':firstName', $firstname);
            $stmt2->bindParam(':LastName', $lastname);
            $stmt2->bindParam(':telphone_no', $telephone);
            $stmt2->bindParam(':Address', $address);
            $stmt2->bindParam(':email', $email);
            $stmt2->execute();

            $user_id = $pdo->lastInsertId();

            if ($_SESSION["state"] === 'seller') {
                $route_sale = $_SESSION['route_id'];
                $cus_state = "wholeseller";

                $store_insert_query = "INSERT INTO customers (user_id, route_id, sto_reg_no, sto_tep_number, sto_name, sto_loc) 
                           VALUES (:user_id, :route_id, :sto_reg_no, :sto_tep_number, :sto_name, :sto_loc)";

                $stmt3 = $pdo->prepare($store_insert_query);
                $stmt3->bindParam(':user_id', $user_id);
                $stmt3->bindParam(':route_id', $route_sale);
                $stmt3->bindParam(':sto_reg_no', $storeregno);
                $stmt3->bindParam(':sto_tep_number', $telephone);
                $stmt3->bindParam(':sto_name', $storename);
                $stmt3->bindParam(':sto_loc', $location);
                $stmt3->execute();
            }
            // Insert data into the store table
            $lastFiveDigits = substr((string)$telephone, -5);

            // Insert data into the login table
            $login_insert_query = "INSERT INTO login (user_id, username, password, state, Active_state,route_id) VALUES (:user_id, :username, :password, :state, :Active_state,:route_id)";


            $stmt4 = $pdo->prepare($login_insert_query);
            $stmt4->bindParam(':user_id', $user_id);
            $stmt4->bindParam(':username', $firstname);
            $stmt4->bindParam(':password', $lastFiveDigits);
            $stmt4->bindParam(':state', $cus_state);
            $stmt4->bindParam(':Active_state', NULL);
            $stmt4->bindParam(':route_id', $route_sale);
            $stmt4->execute();


            $pdo->commit();
            $modifiedNumber = '94' . substr($telephone, 1);

            $Subject = 'Welcome to Lotus Electicals (PVT)LTD';
            $body = "\nDear $firstname,\n\n"
                . "Thank you for registering with Lotus Electricals(PVT).LTD.\n"
                . "\nYour username is: $firstname\n"
                . "Your generated password is: $lastFiveDigits\n"
                . "\nPlease keep your login details secure.\n\n"
                . "Best regards,\nLotus Electicals (PVT)LTD";


            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            sendmail($Subject, $body, $email, $firstname);

            $message = $Subject . $body;
            $smsbody = urlencode($message);

            //sending sms to customer
            sendsms($modifiedNumber, $smsbody);
            echo '<div id="overlay"></div><div id="successModal"><div class="gif"></div>
                            <button onclick="redirectToIndex()" class="sucess">OK</button>
                            </div>';
        } else {
            echo '<div id="overlay"></div><div id="successModel">
        <div class="j">
        <p style="color: indianred; font-size: 13pt; font-weight: bold; font-family: Calibri; margin-top: 0px; text-align: center;">Customer is Already Availble under Entered Telephone Number or Email Address<br>Please Check Whether Entered Details are Correct.</p></div>
        <button onclick="redirectTonormal()" class="fail">OK</button>
        </div>';
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
    // Set the email subject and body


    if (isset($error_message)) {
        echo '<div class="alert alert-danger">' . $error_message . '</div>';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" href="/style/divs.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <style>
        #successModel {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: white;
            color: #4CAF50;
            z-index: 1;
            border-radius: 15px;
            border: 2px solid indianred;
            height: 150px;
            width: 210px;
        }

        .suces,
        .fail {
            width: 50px;
            padding: 10px;
            background-color: indianred;
            color: #fff;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            width: calc(100% - 5px);
        }

        .fail:hover {
            background-color: red;
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
        </div>
        <div class="container">
            <h2 id="customer_data">Customer Details</h2>
            <h2 id="sales_data" style="display: none;">Sales Person Details</h2>


            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="firstname"><b>First Name<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="firstname" class="form-control" required placeholder="e.g:Sandaruwan">
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="lastname" class="form-control" required placeholder="e.g:Perera">
                </div>
                <div class="form-group">
                    <label for="telephone">Telephone Number<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="number" name="telephone" class="form-control" required maxlength="11" placeholder="07XXXXXXXX">
                </div>
                <div class="form-group">
                    <label for="address">Address<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="address" class="form-control" required placeholder="e.g:No.23, Samagi Mawatha, Beliatta">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="example@noreply.com">
                </div>

                <hr id="sep_hr"> <!-- Separate Customer and Store Details -->

                <!-- Store Details Section -->
                <h2 id="sto_detail">Store Details</h2>
                <div class="form-group">
                    <label for="storename" id="storenamelable">Store Name<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="storename" id="storename" class="form-control" placeholder="Sadaruwan Hardware" required>
                </div>
                <div class="form-group">
                    <label for="storeregno" id="storeregnolable">Store Reg. No.<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="storeregno" id="storeregno" class="form-control" placeholder="LK-8956" required>
                </div>
                <div class="form-group">
                    <label for="storeaddress" id="storeaddresslable">Store Address<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="storeaddress" id="storeaddress" class="form-control" placeholder="e.g:No.54, Main Street, Beliatta" required>
                </div>
                <div class="form-group">
                    <label for="location" id="storelocnolable">Store Location<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="e.g:Hambantota" required>
                </div>

                <?php
                $userState = isset($_SESSION["state"]) ? $_SESSION["state"] : '';
                if ($userState === 'seller') : ?>
                    <button type="submit" name="add_sales_person">Add Sales Person</button>
                <?php endif; ?>
                <br>
                <button type="reset">Clear Data</button>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="divs.js"></script>
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "150px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }

        function back() {
            window.history.back();
        }

        function validateForm() {
            // Check if first name contains only letters
            var firstname = document.forms[0]["firstname"].value;
            if (!/^[A-Za-z]+$/.test(firstname)) {
                alert("Please enter only letters for First Name.");
                return false;
            }

            // Check if last name contains only letters
            var lastname = document.forms[0]["lastname"].value;
            if (!/^[A-Za-z]+$/.test(lastname)) {
                alert("Please enter only letters for Last Name.");
                return false;
            }

            var storename = document.forms[0]["storename"].value;
            if (!/^[A-Za-z\s]+$/.test(storename)) {
                alert("Please enter only letters and spaces for the store name field.");
                return false;
            }

            var location = document.forms[0]["location"].value;
            if (!/^[A-Za-z]+$/.test(location)) {
                alert("Please enter only letters for Location filel.");
                return false;
            }

            // Check if telephone number is valid
            var telephone = document.forms[0]["telephone"].value;
            if (!/^\d{10,11}$/.test(telephone)) {
                alert("Please enter a valid 10 or 11-digit phone number for Telephone Number.");
                return false;
            }

            return true; // Form is valid
        }

        function redirectTonormal() {
            hideSuccess();
            // Redirect to index.php
            window.location.href = 'add_wholesalecustomer.php';
        }

        function showSuccess() {
            var overlay = document.getElementById('overlay');
            var successModal = document.getElementById('successModel');

            overlay.style.display = 'block';
            successModal.style.display = 'block';
        }

        function hideSuccess() {
            var overlay = document.getElementById('overlay');
            var successModal = document.getElementById('successModel');

            overlay.style.display = 'none';
            successModal.style.display = 'none';
        }
    </script>

</body>

</html>