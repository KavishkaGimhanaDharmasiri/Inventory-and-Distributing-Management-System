<?php
session_start();

// Include your database connection file
require_once($_SERVER['DOCUMENT_ROOT'] . '/common/email_sms.php');
include($_SERVER['DOCUMENT_ROOT'] . '/common/db_connection.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
if (!isset($_SESSION['email_valid_visit'])) {
    acess_denie();
    exit();
} else {
    $_SESSION['otp_send_visit'] = true;
}

$otpcode = $_SESSION['code'];
$user_id = $_SESSION['user_id'];
$firstname = $_SESSION["firstName"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the OTP from the POST data
    $otp = $_POST['otp'];

    // Get the stored email from the session
    $email = isset($_SESSION["email"]) ? $_SESSION["email"] : '';

    // Validate the OTP
    /*$otp_query = "SELECT otp_code FROM otp WHERE email='$email' AND otp_code='$otp'";
    $otp_result = mysqli_query($connection, $otp_query);*/

    if ($otpcode == $otp) {
        header("Location:/common/chng_pass.php");
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
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <style>
        h3 {
            text-align: center;
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
            <div id="mySidepanel" onclick="closeNav()" style="height:100%" ; class="sidepanel">
                <a href="#">About</a>
            </div>

            <a href="javascript:void(0);" class="icon" onclick="openNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <div class="container">
            <h3>OTP Validation</h3>

            <?php
            $otpcode = $_SESSION['code'];
            // Display error message if set
            if (isset($error_message)) {
                echo '<div class="alert alert-danger">' . $error_message . '</div>';
            }
            ?>

            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="otp">OTP Code </label>
                    <input type="text" name="otp" class="form-control" required maxlength="5" placeholder="xxxxx">
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>


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
    </script>

</body>

</html>