<?php
session_start();

// Include your database connection file
require_once('email_sms.php');
include("db_connection.php");

$otpcode=$_SESSION['code'];
$user_id=$_SESSION['user_id'];
$firstname=$_SESSION["firstName"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the OTP from the POST data
    $otp = $_POST['otp'];

    // Get the stored email from the session
    $email = isset($_SESSION["email"]) ? $_SESSION["email"] : '';

    // Validate the OTP
    /*$otp_query = "SELECT otp_code FROM otp WHERE email='$email' AND otp_code='$otp'";
    $otp_result = mysqli_query($connection, $otp_query);*/

    if ($otpcode==$otp) {
        header("Location: chng_pass.php");
        exit();
    } else {
        // Display an error message if OTP is invalid
        $error_message = "Invalid OTP code.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Validation</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>OTP Validation</h2>

        <?php
        $otpcode=$_SESSION['code'];
        // Display error message if set
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="otp">OTP Code </label>
                <input type="text" name="otp" class="form-control" required>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
