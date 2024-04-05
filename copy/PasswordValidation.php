<?php
session_start();

// Include your database connection file
require_once('email_sms.php');
include("db_connection.php");

function generateRandomCode($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $randomIndex = mt_rand(0, strlen($characters) - 1);
        $code .= $characters[$randomIndex];
    }
    return $code;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the POST data
    $email = $_POST['email'];

    // Validate the user credentials
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        // Fetch user data
        $row = mysqli_fetch_assoc($result);
        $code = generateRandomCode();

        // Set session variables
        $_SESSION['code']=$code;
        $_SESSION["email"] = $row["email"];
        $_SESSION["firstName"] = $row["firstName"];
        $_SESSION['user_id']=$row["user_id"];
        $user=$row["email"];
        $firstname=$row["firstName"];

     $Subject="Password Change Request";
            $body="Vertify your Email address\n\n Enter Below Vertification Code to Continue Change Password \n\nVertification code is :  ".$code."\n\nDo Not Share with Others\nThank You...!\n\nRegards,\nLotus Electicals (PVT)LTD";

        // sendmail($Subject,$body,$user,$firstname);

        // Redirect to the OTP validation page
        header("Location: otp_validation.php");
        exit();
    } else {
        // Display an error message if credentials are invalid
        $error_message = "Invalid Email Address.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Validation</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Email Validation</h2>

        <?php
        // Display error message if set
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="email">E-Mail Address </label>
                <input type="text" name="email" class="form-control" required>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
