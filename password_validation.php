<?php
session_start();

require_once('email_sms.php');
include("db_connection.php");
require_once('den_fun.php');
if (!isset($_SESSION['index_visit'])) {
  acess_denie();
  exit();
} else {
  $_SESSION['email_valid_visit'] = true;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the email from the POST data
  $email = $_POST['email'];

  // Validate the user credentials
  $query = "SELECT * FROM users WHERE email='$email'";
  $result = mysqli_query($connection, $query);

  if ($result && mysqli_num_rows($result) > 1) {
    // Fetch user data
    $row = mysqli_fetch_assoc($result);
    $code = generateRandomCode();

    // Set session variables
    $_SESSION['code'] = $code;
    $_SESSION["email"] = $row["email"];
    $_SESSION["firstName"] = $row["firstName"];
    $_SESSION['user_id'] = $row["user_id"];
    $user = $row["email"];
    $firstname = $row["firstName"];

    $Subject = "Password Change Request";
    $body = "Vertify your Email address\n\nEnter Below Vertification Code to Continue Change Password \n\nVertification code is :  " . $code . "\n\nDo Not Share with Others\nThank You...!\n\nRegards,\nLotus Electicals (PVT)LTD";


    sendmail($Subject, $body, $user, $firstname);

    // Redirect to the OTP validation page
    header("Location: otp_validation.php");
    //exit();
  } else {
    // Display an error message if credentials are invalid
    $error_message = "Invalid Email Address.";
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1, user-scalable=no">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="mobile.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    h3 {
      text-align: center;
      color: #333;
    }
  </style>
</head>

<body>
  <div class="area" style="z-index:1;">
    <ul class="circles">

      <!-- Simulate a smartphone / tablet -->
      <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

          <?php
          if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            // Generate back navigation link using HTTP_REFERER
            echo '<a href="' . $_SERVER['HTTP_REFERER'] . '" class="back-link" style="float:left;font-size:20px; "><i class="fa fa-angle-left"></i></a>';
          } else {
            // If no referrer is set, provide a default back link
            echo '<a href="javascript:history.go(-1);" class="back-link" style="float:left; font-size:30px;"><i class="fa fa-angle-left"></i></a>';
          }
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

        </div>

        <div class="container">
          <h3>Email Validation</h3>

          <?php
          // Display error message if set
          if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
          }
          ?>

          <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="z-index:9999;">

            <div class="form-group">

              <label for="email">E-Mail Address </label>
              <input type="email" name="email" class="form-control" required placeholder="noreply@gmail.com">
            </div>

            <button type="submit">Get Code</button>
          </form>

        </div>



      </div>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
      <li></li>
    </ul>
  </div>


  <script>
    function openNav() {
      document.getElementById("mySidepanel").style.width = "150px";
    }

    function closeNav() {
      document.getElementById("mySidepanel").style.width = "0";
    }
  </script>

</body>

</html>