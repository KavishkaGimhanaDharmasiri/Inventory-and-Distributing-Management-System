<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/common/email_sms.php");
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
if (!isset($_SESSION['index_visit'])) {
  acess_denie();
  exit();
} else {
  $_SESSION['email_valid_visit'] = true;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the email from the POST data
  $email = $_POST['email'];
  $telephone = $_POST['telenumber'];

  // Validate the user credentials
  $query = "SELECT * FROM users WHERE email='$email' OR telphone_no='$telephone'";
  $result = mysqli_query($connection, $query);

  if ($result && mysqli_num_rows($result) == 1) {
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
    $body = "Vertify Account\n\nEnter Below Vertification Code for Continue Change Password \n\nVertification code is :  " . $code . "\n\nDo Not Share with Others\nThank You...!\n\nRegards,\nLotus Electicals (PVT)LTD";

    $modifiedNumber = '94' . substr($telephone, 1);
    if ($email == null || $email == "" || $telephone != "") {
      sendsms($modifiedNumber, $message);
    } elseif ($telephone == null || $telephone == "" || $email != "") {
      sendmail($Subject, $body, $user, $firstname);
      $modifiedNumber = '94' . substr($telephone, 1);
    }
    echo 'window.alert("Field cannot be empty.")';

    // Redirect to the OTP validation page
    header("Location:/common/otp_validation.php");
    //exit();
  } else {
    // Display an error message if credentials are invalid
    $error_message = "Invalid Email Address.";
  }
}
function generateRandomCode($length = 5)
{
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
  <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1, user-scalable=no">
  <title>Email Vertification</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/style/mobile.css">
  <link rel="stylesheet" type="text/css" href="/style/style.css">
  <style>
    h3 {
      text-align: center;
      color: #333;
    }
  </style>
</head>

<body>

  <!-- Simulate a smartphone / tablet -->
  <div class="mobile-container">

    <!-- Top Navigation Menu -->
    <div class="topnav">
      <a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>
    </div>

    <div class="container">
      <h3>Validation</h3>

      <?php
      // Display error message if set
      if (isset($error_message)) {
        echo '<div class="alert alert-danger">' . $error_message . '</div>';
      }
      ?>

      <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="z-index:9999;" id="emailForm">

        <div class="form-group">

          <label for="email_lab" id="email_lab">E-Mail Address </label>
          <input type="email" name="email" id="email_field" class="form-control" placeholder="noreply@email.com" style="margin-bottom: 2%;">
          <label for="telephone" id="telephone_lab" style="display: none;">Telephone Number </label>
          <input type="text" name="telenumber" id="telephone_field" class="form-control" placeholder="07XXXXXXXXX" style="margin-bottom: 2%;display: none;" maxlength="10">
          <label onclick="toggleCustomfields()" style="color:green;font-size:14px;margin-top:0%;font-weight:bold;margin-left: 0;margin-right: 0;cursor:pointer;" id="show_telephone">Try Another Way</label>
          <label onclick="togglelablefields()" style="color:green;font-size:14px;margin-top:0%;font-weight:bold;margin-left: 0;margin-right: 0;display:none;cursor:pointer;" id="show_email">Try Another Way</label>
        </div>

        <button type="submit" style="margin-top:2%;">Get Code</button>
      </form>

    </div>
  </div>

  <script>
    function back() {
      window.history.back();
    }

    document.getElementById('emailForm').addEventListener('submit', function(event) {
      var emailField = document.getElementById('email_field');
      var telephone_Field = document.getElementById('telephone_field');

      if (emailField.value.trim() === "" || telephone_Field.value.trim() === "") {
        event.preventDefault();
        window.alert("Field cannot be empty.");
      }

    });

    function toggleCustomfields() {
      var email_label = document.getElementById('email_lab');
      var email_Field = document.getElementById('email_field');
      var telephone_label = document.getElementById('telephone_lab');
      var telephone_Field = document.getElementById('telephone_field');
      var show_telephone = document.getElementById('show_telephone');
      var show_email = document.getElementById('show_email');

      email_label.style.display = 'none';
      email_Field.style.display = 'none';
      email_Field.value = "";
      show_telephone.style.display = 'none';
      show_email.style.display = 'block';

      telephone_label.style.display = 'block';
      telephone_Field.style.display = 'block';


    }

    function togglelablefields() {
      var email_label = document.getElementById('email_lab');
      var email_Field = document.getElementById('email_field');
      var telephone_label = document.getElementById('telephone_lab');
      var telephone_Field = document.getElementById('telephone_field');
      var show_telephone = document.getElementById('show_telephone');
      var show_email = document.getElementById('show_email');

      telephone_Field = "";
      show_email.style.display = 'none';
      telephone_label.style.display = 'none';
      telephone_Field.style.display = 'none';
      email_label.style.display = 'block';
      email_Field.style.display = 'block';
      show_telephone.style.display = 'block';





    }
  </script>

</body>

</html>