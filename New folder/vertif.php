<?php
session_start();
require_once('email_sms.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include("db_connection.php");

    // Get the username and password from the form
    $email=$_POST['email'];

    // Validate the user credentials
    $query = "select email from users where email='$email'" ;
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Check if a matching record is found
        if (mysqli_num_rows($result) == 1) {
            // Fetch user data
            $row = mysqli_fetch_assoc($result);

            $code = generateRandomCode();

            $otp_query = "INSERT INTO otp (otp_date, email,otp_code) 
                             VALUES (date('Y-m-d H:i:s'), '$email',$code)";
    mysqli_query($conn, $otp_query);

            // Set session variables
            $_SESSION["username"] = $row["username"];
            $_SESSION["state"]=$row["email"];
            $user=$row["email"];

            

            $Subject="Password Change Request";
            $body="Vertify your Email address\n\n Eneter Below Vertification Code to Continue Change Password \n\nVertification code is :  ".$code."\n\nDo Not Share with Others\nThank You!...\n\nRegards,\nLotus Electicals (PVT)LTD";

            sendmail($Subject,$body,$user,$firstname);
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["requestotp"])) {
                $otpcode=$_POST['otptext'];
                if($code==$otpcode){
                     header("Location: chng_pass.php");
                }
                else{
                    $error_message = "Invalid OTP code.";
                }

            }

                // Redirect to a secure page after successful login
        } else {
            // Display an error message if credentials are invalid
            $error_message = "Invalid Mobile Number.";
        }
    } else {
        // Display an error message for database query issues
        $error_message = "Database query failed.";
    }

    // Close the database connection
    mysqli_close($connection);
}

function generateRandomCode($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $code .= $characters[$randomIndex];
    }

    return $code;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verrify</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Verify</h2>

        <?php
        // Display error message if set
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>

        <form method="POST" action="<?php $_PHP_SELF ?>">
            <div class="form-group">
                <label for="email" id="email">E-Mail Address </label>
                <input type="text" name="emailtext" id="emailtext" class="form-control" required>
                <label for="otp" id="otp"  hidden>OTP Code </label>
                <input type="text" name="otptext" id="otptext" class="form-control" required hidden>
            </div>

            <button type="button" onclick="toggle()" name="requestotp">Request OTP</button>
             <button type="submit" name="proceed" hidden>Proceed</button>
            <br>
            
        </form>
    </div>
    <script type="text/javascript">
        function toggle(){
            var email = document.getElementById('email');
        var emailInput = document.getElementById('emailtext');
        var otp = document.getElementById('otp');
        var otpInput = document.getElementById('otptext');


                email.style.display = 'none';
                emailInput.style.display = 'none';
                otp.style.display = 'block';
                otpInput.style.display = 'block';
    
                
            }
    </script>
</body>
</html>
