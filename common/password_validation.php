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
  $email = $_POST['email'] ?? '';
  $telephone = $_POST['telenumber'] ?? '';

  // Validate the user credentials
  $query = "SELECT * FROM users WHERE email=? OR telphone_no=?";
  $stmt = $connection->prepare($query);
  $stmt->bind_param('ss', $email, $telephone);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && mysqli_num_rows($result) == 1) {
    // Fetch user data
    $row = mysqli_fetch_assoc($result);
    $code = generateRandomCode();

    // Set session variables
    $_SESSION['code'] = $code;
    $_SESSION["email"] = $row["email"];
    $_SESSION["firstName"] = $row["firstName"];
    $_SESSION['user_id'] = $row["user_id"];

    $Subject = "Password Change Request";
    $body = "Verify Account\n\nEnter Below Verification Code to Continue Changing Password \n\nVerification code is: $code\n\nDo Not Share with Others\nThank You...!\n\nRegards,\nLotus Electicals (PVT)LTD";
    $modifiedNumber = '94' . substr($telephone, 1);

    if (!empty($email)) {
      // sendmail($Subject, $body, $row["email"], $row["firstName"]);
    } elseif (!empty($telephone)) {
      // sendsms($modifiedNumber, $body);
    }

    // Redirect to the OTP validation page
    header("Location: /common/otp_validation.php");
    exit();
  } else {
    $error_message = "Invalid Email Address or Telephone Number.";
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
  <title>Email Verification</title>
  <link rel="icon" href="/images/tab_icon.png">
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

  <div class="mobile-container">

    <div class="topnav">
      <a href="javascript:void(0)" onclick="back()" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">validation</span></a>
    </div>

    <div class="container">
      <h3>Validation</h3>

      <?php
      if (isset($error_message)) {
        echo '<div class="alert alert-danger">' . $error_message . '</div>';
      }
      ?>

      <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="z-index:9999;" id="emailForm">

        <div class="form-group">
          <label for="email_lab" id="email_lab">E-Mail Address</label>
          <input type="email" name="email" id="email_field" class="form-control" placeholder="noreply@email.com" style="margin-bottom: 2%;">
          <label for="telephone" id="telephone_lab" style="display: none;">Telephone Number</label>
          <input type="text" name="telenumber" id="telephone_field" class="form-control" placeholder="07XXXXXXXXX" style="margin-bottom: 2%;display: none;" maxlength="10" oninput='validateNumber(this)'>
          <label onclick="toggleCustomFields()" style="color:green;font-size:14px;margin-top:0%;font-weight:bold;margin-left: 0;margin-right: 0;cursor:pointer;" id="show_telephone">Try Another Way</label>
          <label onclick="toggleLabelFields()" style="color:green;font-size:14px;margin-top:0%;font-weight:bold;margin-left: 0;margin-right: 0;display:none;cursor:pointer;" id="show_email">Try Another Way</label>
        </div>

        <button type="submit" style="margin-top:2%;">Get Code</button>
      </form>

    </div>
  </div>

  <script>
    function back() {
      window.history.back();
    }

    function validateNumber(input) {
      input.value = input.value.replace(/\D/g, ''); // Remove any non-numeric characters
    }

    document.getElementById('emailForm').addEventListener('submit', function(event) {
      var emailField = document.getElementById('email_field');
      var telephoneField = document.getElementById('telephone_field');

      if (emailField.value.trim() === "" && telephoneField.value.trim() === "") {
        event.preventDefault();
        window.alert("Field cannot be empty.");
      }
    });

    function toggleCustomFields() {
      document.getElementById('email_lab').style.display = 'none';
      document.getElementById('email_field').style.display = 'none';
      document.getElementById('email_field').value = "";
      document.getElementById('show_telephone').style.display = 'none';
      document.getElementById('show_email').style.display = 'block';
      document.getElementById('telephone_lab').style.display = 'block';
      document.getElementById('telephone_field').style.display = 'block';
    }

    function toggleLabelFields() {
      document.getElementById('telephone_lab').style.display = 'none';
      document.getElementById('telephone_field').style.display = 'none';
      document.getElementById('telephone_field').value = "";
      document.getElementById('show_email').style.display = 'none';
      document.getElementById('email_lab').style.display = 'block';
      document.getElementById('email_field').style.display = 'block';
      document.getElementById('show_telephone').style.display = 'block';
    }
  </script>

</body>

</html>