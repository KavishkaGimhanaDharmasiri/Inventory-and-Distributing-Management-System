<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style type="text/css">
        body {
            height: 100%;
        }

        .container {
           background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            min-width: 320px;
            overflow-y: auto;
            overflow: visible;
            border: 2px solid #4caf50;
            border-radius: 15px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 15px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .alert {
            margin-top: 15px;
            padding: 10px;
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 15px;
        }
        a{
            text-decoration: none;
            color:white;
    

        }
        
        .form-control{
            margin-left: 0;
        }

        @media (max-width: 600px) {
            .option {
                width: 100%;
            }
        }
        hr{
            background-color: red;
            color: red;
            line-height: 2px;
        }
    </style>
    <title>Login</title>
</head>
<body>
    <div class="container">
        <h2>Customer Details</h2>

        <?php
        include("db_connection.php");
        //require_once('email_sms.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include("db_connection.php");

    // Extract data from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    $storename = $_POST['storename'];
    $storeregno = $_POST['storeregno'];
    $storeaddress = $_POST['storeaddress'];
    $location = $_POST['location'];

    $modifiedNumber = '94' . substr($telephone, 0);

    // Insert data into the customer table
    $customer_insert_query = "INSERT INTO users (firstName, LastName, telphone_no, Address, email) 
                             VALUES ('$firstname', '$lastname', '$telephone', '$address', '$email')";
    mysqli_query($connection, $customer_insert_query);

    // Get the user_id of the inserted customer
    $user_id = mysqli_insert_id($connection);

    // Insert data into the store table
    $store_insert_query = "INSERT INTO customers (user_id, sto_reg_no, sto_tep_number, sto_name, sto_loc) 
                           VALUES ('$user_id', '$storeregno', '$telephone', '$storename', '$location')";
    mysqli_query($connection, $store_insert_query);

    // Generate a random password (you may use a more secure method for production)
   // $password = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);

    $lastFiveDigits = substr((string)$telephone, -5);
    $route_id=$_SESSION['route_id'];
    // Insert data into the login table
    $login_insert_query = "INSERT INTO login (user_id, username, password, state, Active_state,route_id) 
                           VALUES ('$user_id', '$firstname', '$lastFiveDigits', 'wholesaler', NULL,$route_id)";
    mysqli_query($connection, $login_insert_query);

    $Subject='Welcome to Lotus Electicals (PVT)LTD';
    $body="\nDear $firstname,\n\n"
                . "Thank you for registering with YourSite.\n"
                . "Your username is: $firstname\n"
                . "Your generated password is: $lastFiveDigits\n"
                . "Please keep your login details secure.\n\n"
                . "<a href='http://lotushardware.atwebpages.com/chng_pass.php'>Change Password</a>\n\n"
                . "Best regards,\nLotus Electicals (PVT)LTD";

    // Set the email subject and body
 $headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 

//sendmail($Subject,$body,$email,$firstname);

//sending sms to customer
$message=$Subject.$body;
$smsbody = urlencode($message);

//sendsms($modifiedNumber,$smsbody);
 echo '<script>window.location.href = "divs.php";</script>'; 
    mysqli_close($connection);
}
       
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>
        <form method="POST" action="<?php $_PHP_SELF ?>">
            <!-- Customer Details Section -->
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="telephone">Telephone Number</label>
                <input type="text" name="telephone" class="form-control" required maxlength="10">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <hr> <!-- Separate Customer and Store Details -->

            <!-- Store Details Section -->
            <h2>Store Details</h2>
            <br>
            <div class="form-group">
                <label for="storename">Store Name</label>
                <input type="text" name="storename" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="storeregno">Store Reg. No.</label>
                <input type="text" name="storeregno" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="storeaddress">Store Address</label>
                <input type="text" name="storeaddress" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="location">Store Location</label>
                <input type="text" name="location" class="form-control" required>
            </div>

            <button type="submit">Add Wholesale Customer</button>
            <br><br>
            <button type="reset">Clear</button> <!-- Use type="reset" for clearing the form -->
        </form>
    </div>
</body>
</html>