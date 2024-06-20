<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/email_sms.php");

if (!isset($_SESSION['index_visit']) ||  !isset($_SESSION['option_visit']) || $_SESSION["state"] != 'admin') {

    acess_denie();
    exit();
} else {
    unset($_SESSION['new_sale_order_visit']);
    unset($_SESSION['paymenr_visit']);
    $user_idn = $_SESSION["user_id"];
    $_SESSION['option_visit'] = true;
}


$route_query = "SELECT * FROM route";
$result1 = mysqli_query($connection, $route_query);

if (!$result1) {
    echo ("route query failed: " . mysqli_error($connection));
}

if ($_SERVER["REQUEST_METHOD"] == "POST"  && $_SESSION["state"] == 'admin') {
    // Include your database connection file
    // Extract data from the form
    $firstname = $_POST['firstname'];
    $_SESSION['firstname'] = $firstname;
    $lastname = $_POST['lastname'];
    $_SESSION['lastname'] = $lastname;
    $telephone = $_POST['telephone'];
    $_SESSION['telephone'] = $telephone;
    $address = $_POST['address'];
    $_SESSION['address'] = $address;




    if ($_POST['email'] === null || $_POST['email'] === "") {
        $email = null;
    } else {
        $email = $_POST['email'];
        $_SESSION['email'] = $email;
    }

    $route_id = $_POST['route'];

    $_SESSION['sale_route'] = $route_id;


    // Insert data into the customer table
    try {
        $pdo->beginTransaction();

        $valid_query = "SELECT * FROM users WHERE  telphone_no= $telephone OR email='$email'";
        $result6 = mysqli_query($connection, $valid_query);


        if (mysqli_num_rows($result6) === 0) {

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
            $login_insert = mysqli_query($connection, $login_insert_query);
            $modifiedNumber = '94' . substr($telephone, 1);
            $pdo->commit();


            $Subject = 'Welcome to Lotus Electicals (PVT)LTD';
            $body = "\nDear $firstname,\n\n"
                . "Thank you for registering with Lotus Electicals (PVT)LTD.\n"
                . "Your username is: $firstname\n"
                . "Your generated password is: $lastFiveDigits\n"
                . "Please keep your login details secure.\nTo Easy Access to Services you can download The Application from here : https://www.mediafire.com/file/9msvx2fc25hragd/app-release.apk/file\n\n"
                . "Best regards,\nLotus Electicals (PVT)LTD";

            // Set the email subject and body
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            sendmail($Subject, $body, $email, $firstname);

            //sending sms to customer
            $message = $Subject . $body;
            $smsbody = urlencode($message);

            sendsms($modifiedNumber, $smsbody);

            unset($_SESSION['firstname']);
            unset($_SESSION['email']);
            unset($_SESSION['lastname']);
            unset($_SESSION['telephone']);
            unset($_SESSION['address']);
            session_write_close();

            echo '<div id="overlay"></div><div id="successModal"><div class="gif"></div>
                            <button onclick="redirectToIndex()" class="sucess">OK</button>
                            </div>';
            mysqli_close($connection);
        } else {
            echo '<div id="overlay"></div><div id="successModel">
        <div class="j">
        <p style="color: indianred; font-size: 13pt; font-weight: bold; font-family: Calibri; margin-top: 0px; text-align: center;">Customer is Already Availble under Entered Telephone Number or Email Address<br>Please Check Whether Entered Details are Correct.</p></div>
        <button onclick="redirectTonormal()" class="fail">OK</button>
        </div>';

            $_POST['firstname'] = $_SESSION['firstname'];
            $_POST['lastname'] = $_SESSION['lastname'];
            $_POST['telephone'] = $_SESSION['telephone'];
            $_POST['address'] = $_SESSION['address'];
            $_POST['email'] = $_SESSION['email'];
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
    <title>Add Sales Person</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/style.css">
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

        .suces {
            width: 50px;
            padding: 10px;
            background-color: indianred;
            color: #fff;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            width: calc(100% - 5px);
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
            <h3 id="customer_data" style="text-align: center;">Customer Details</h3>


            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateForm()">
                <!-- Customer Details Section -->
                <div class="form-group">
                    <label for="Route_name" id="Route_name" style="display: none;"><b>Route<b></label>
                    <select name="route" id="route" style="display: none;" required>
                        <option value="">Select Route</option>
                        <?php
                        while ($row = mysqli_fetch_assoc($result1)) {
                            $selected = (isset($_POST['route']) && $_POST['route'] == $row['route']) ? 'selected' : '';
                            echo "<option value='{$row['route_id']}' $selected>{$row['route']}</option>";
                        }
                        ?>

                    </select>
                </div>
                <div class="form-group">
                    <label for="firstname"><b>First Namer<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
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

                <!-- Add Sales Person Button -->

                <button type="submit" name="add_sales_person">Add Sales Person</button>
                <button type="reset" style="background-color: transparent;color:green;">Clear Data</button>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="/javascript/divs.js"></script>
    <script>
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


            // Check if telephone number is valid
            var telephone = document.forms[0]["telephone"].value;
            if (!/^\d{10,11}$/.test(telephone)) {
                alert("Please enter a valid 10 or 11-digit phone number for Telephone Number.");
                return false;
            }

            return true; // Form is valid
        }

        function back() {
            window.history.back();
        }
    </script>

</body>

</html>