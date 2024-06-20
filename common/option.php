<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['index_visit']) || !$_SESSION['index_visit'] || !isset($_SESSION["user_id"]) || !isset($_SESSION["state"])) {

  acess_denie();
  exit();
} else {
  unset($_SESSION['new_sale_order_visit']);
  unset($_SESSION['paymenr_visit']);

  $user_idn = $_SESSION["user_id"];

  $_SESSION['option_visit'] = true;
}
$query = "SELECT * FROM users WHERE user_id = '$user_idn'";
$result = mysqli_query($connection, $query);

$currentmonth = date('Y-m');
$route_id = $_SESSION['route_id'];
$sqlq = "SELECT * FROM payment WHERE balance > 0 
              AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) - INTERVAL DAY(CURDATE()) - 1 DAY 
              AND payment_date < DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY) 
              AND route_id = $route_id";
$results = $connection->query($sqlq);

$sqlpassword = "SELECT l.password,u.telphone_no FROM users u LEFT JOIN login l ON l.user_id=u.user_id  WHERE l.user_id= $user_idn";
$result2 = $connection->query($sqlpassword);

$notify = "SELECT not_content ,state, DATE_FORMAT(not_date, '%Y-%m') AS formatted_date FROM notification;";

$notfy_result = $connection->query($notify);

$sqlcustomer = "SELECT sto_name,route_id FROM customers WHERE user_id = '$user_idn'";
$resultcustomer = $connection->query($sqlcustomer);
$stoname = "";
$storoute = "";
while ($rowSrore = mysqli_fetch_assoc($resultcustomer)) {
  $stoname = $rowSrore['sto_name'];
  $storoute = $rowSrore['route_id'];
}

$sqlorder = "SELECT order_state FROM primary_orders p LEFT JOIN  customers c ON p.store_name=c.sto_name AND p.ord_id=c.route_id WHERE  p.route_id=$storoute AND p.store_name= '$stoname' AND p.order_type='customer' AND DATE_FORMAT(p.ord_date, '%Y-%m') = '$currentmonth';";
$resultorder = $connection->query($sqlorder);
$existing = false;
if ($resultorder) {
  if (mysqli_num_rows($resultorder) == 1) {
    $existing = true;
  }
}


$samepassword = false;

if ($row3 = mysqli_fetch_assoc($result2)) {
  $lastFiveDigits = substr((string)$row3['telphone_no'], -5);

  if ($row3['password'] === $lastFiveDigits) {
    $samepassword = true;
  } else {
    $samepassword = false;
  }
}

if ($result) {
  // Check if a matching record is found
  if (mysqli_num_rows($result) == 1) {
    // Fetch user data
    $row = mysqli_fetch_assoc($result);

    // Set session variables
    $_SESSION["user_log_fname"] = $row["firstName"];
    $_SESSION["user_log_lname"] = $row["LastName"];
    $_SESSION["user_log_email"] = $row["email"];
  }
}
unset($_SESSION['order_details']);
unset($_SESSION['totalAmount']);
unset($_SESSION['paymentAmount']);
unset($_SESSION['balance']);
unset($_SESSION['selected_payment_method']);
unset($_SESSION['selected_store']);
unset($_SESSION['process_payment']);
unset($_SESSION['items']);
//unset($_SESSION['sales_recipt_download']);
unset($_SESSION['send_massage']);

// End the session
session_write_close();

?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
  <title>Option</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/style/mobile.css">
  <link rel="stylesheet" type="text/css" href="/style/style.css">
  <link rel="stylesheet" type="text/css" href="/style/option.css">


</head>

<body>

  <!-- Simulate a smartphone / tablet -->
  <div class="mobile-container">

    <!-- Top Navigation Menu -->
    <div class="topnav">

      <?php
      // Generate back navigation link using HTTP_REFERER
      echo '<a href="javascript:void(0)" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
      ?>
      <div id="mySidepanel" class="sidepanel" style="height:100%;">
        <a href="javascript:void(0)" style="color:white;font-size:13px;margin-top:10px;" class="closebtn" onclick="closeNav()">&#10005;</a>
        <a href="about.php">Info</a>
        <a href="javascript:void(0)" onclick="toggleProfilePanel()">Profile</a>
        <a href="javascript:void(0)" onclick="opennot()">
          <?php
          if ($_SESSION["state"] === 'seller') {
            if ($results->num_rows > 0) {

              echo '<span class="badge" id="bagesd" style="right: 40px;"></span>';
            }
          }
          /*   }
            }
          }*/
          if ($samepassword == true) {
            echo '<span class="badge" id="bagesd" style="right: 40px;"></span>';
          }

          ?>Notification</a>
        <br>
        <br>
        <a href="javascript:void(0)" onclick="logout()">Logout</a>
      </div>
      <a href="javascript:void(0);" class="icon" onclick="openNav()" style=" background-color: transparent;">
        <i class=" fa fa-bars" style="background-color: transparent;"></i>
        <?php
        if ($_SESSION["state"] == 'seller') { //if seller login show bagage
          if ($results->num_rows > 0 || $samepassword == true) {
            echo '<span class="badge" id="bages"></span>';
          }
        }
        if (isset($_SESSION["state"])) {
          if ($samepassword == true) {
            echo '<span class="badge" id="bages"></span>';
          }
        }
        ?>
      </a>

      <div id="mynotification" class="notification">

        <div class="notification-panel" id="notificationPanel">
          <div class="contain">
            <a href style="font-size:12px;pointer-events: none;">Notification</a>
            <a href="javascript:void(0)" style="font-size:12px;cursor:pointer;" onclick="closenot()">&#10005;</a>
          </div>
          <?php

          if ($_SESSION["state"] === 'seller') {
            if ($results->num_rows > 0) {

              if ($notfy_result) {
                if ($rownot = mysqli_fetch_assoc($notfy_result)) {
                  $not_content = $rownot['not_content'];
                  $not_date = $rownot['formatted_date'];
                  $not_state = $rownot['state'];
                  $currentmonth = date('Y-m');
                  if ($currentmonth > $not_date  && $not_state == "no") {
                    echo '<div id="notificationContent" style="padding: 5px;">
            <div class="contain">
              <a href="javascript:void(0)" style="pointer-events: none;font-size:12px;font-weight:normal;">' . $not_content . '</a>
              <a href="javascript:void(0)" style="font-size:12px;" onclick="hidenotifi()">&#10005;</a>
            </div>
            <div class="contain">

               <a href="javascript:void(0)" onclick="showdetails(event)" id="view" style="font-size: 11px;cursor:pointer;color:blue;">View details</a>
    
              <a href="javascript:void(0)" onclick="sendSMS(event)" style="cursor:pointer;font-size:11px;color:blue;">Send Message</a>
            </div>
           
          </div>';
                    echo '<div id="notifications" style="display:none;"><a href="javascript:void(0)" style="font-size:12px;" onclick="hidenotifi()">&#10005;</a></div>';
                  }
                }
              }
            }
          }
          if (isset($_SESSION["state"])) {
            if ($samepassword === true) {
              echo '<div id="notificationContent2" style="padding: 5px;margin-top:5px;">
            <div class="contain">
              <a href="javascript:void(0)" style="pointer-events: none;font-size:12px;font-weight:normal;">Password at risk. still using same password as system generates</a>
              <a href="javascript:void(0)" style="font-size:12px;" onclick="hidenotifi2()">&#10005;</a>
            </div>
            <div class="contain">

              <a href="chng_pass.php" class="chngpass" style="font-size: 11px;cursor:pointer;color:blue;">Chnage Password</a>
              
            </div>

          </div>';
            }
          }
          ?>
        </div>
      </div>




      <div id="profilePanel" class="profile-panel" style="max-height: 320px;">



        <div class="">
          <lable onclick="profileclose()" style="margin-left:90%;">&#10005;</lable>
          <div class="our-team">
            <div class="picture">
              <img class="img-fluid" style="height: 100px; width: 100px;" src="https://picsum.photos/id/77/1631/1102">
            </div>
            <div class="team-content">
              <?php echo '<lable style="font-weight:bold;font-size:16px;">' . $_SESSION["user_log_fname"] . " " . $_SESSION["user_log_lname"] . "</lable><br><br><lable style='font-weight:bold;font-size:14px;'>" . $_SESSION["state"] . "</label><br><br><lable style='font-weight:bold;font-size:14px;'>" . $_SESSION["user_log_email"] . '</label><br><br>'; ?>
              <button type="button" onclick="changePassword()" style="border-bottom-right-radius: 0px;border-top-right-radius: 0px; border-top-right-radius: 15px; padding: 5px;margin:0px;" class="changePass">Change Password</button><br>
              <button type="button " onclick="logout()" style="background: indianred;color:white; border-bottom-right-radius: 0px;border-top-right-radius: 0px; padding: 5px; border: 1px solid indianred;margin-top: 8px;border-bottom-right-radius: 15px">Logout</button>
            </div>
          </div>

        </div>
      </div>

    </div>

    <div class="options-container">

      <a href="/seller/new_order.php" class="option" id="option1" style="display: none;">
        <div>Add Order</div>
      </a>
      <a href="/common/manage_orders.php" class="option" id="option2" style="display: none;">
        <div>View Orders</div>
      </a>
      <a href="/seller/add_wholesalecustomer.php" class="option" id="option3" style="display: none;">
        <div>Add Customer</div>
      </a>
      <a href="/common/report.php" class="option" id="option4" style="display: none;">
        <div>Generate Report</div>
      </a>
      <a href="/seller/summery.php" class="option" id="option5" style="display: none;">
        <div>Summary/Info</div>
      </a>
      <a href="/seller/handle_return.php" class="option" id="option10" style="display: none;">
        <div>Return Items</div>
      </a>
      <a href="/admin/Admin_feed.php" class="option" id="option6" style="display: none;">
        <div>Distribute Products</div>
      </a>
      <?php

      if ($existing == true) {
        echo '<a href="javascript:void(0)" onclick="showordernot()" class="option" id="option7" style="display: none;">
        <div>Pre Order</div>
      </a>';
      } else {
        echo '<a href="/customer/create_order.php" class="option" id="option7" style="display: none;">
        <div>Pre Order</div>
      </a>';
      }
      ?>

      <a href="/customer/order.php" class="option" id="option8" style="display: none;">
        <div>My Orders</div>
      </a>
      <a href="/admin/System_Manage.php" class="option" id="option9" style="display: none;">
        <div>System Manage</div>
      </a>
      <a href="/seller/Transaction_setelement.php" class="option" id="option11" style="display: none;">
        <div>Payment Settlement</div>
      </a>

    </div>


  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    function openNav() {
      document.getElementById("mySidepanel").style.width = "150px";
    }

    function closeNav() {
      document.getElementById("mySidepanel").style.width = "0px";
    }

    function showordernot() {

      window.alert("You Already Made An Order For This Month. Go to My Order to See Activity. Thank You... ");
    }

    function opennot() {
      document.getElementById("mynotification").style.width = "280px";
      document.getElementById("notificationPanel").style.display = "block";
      document.getElementById("mySidepanel").style.width = "0px";
      document.getElementById("bages").style.display = "none";
      document.getElementById("bagesd").style.display = "none";
    }

    function closenot() {
      document.getElementById("mynotification").style.width = "0px";
      //document.getElementById("notifications").style.display = "none";
    }

    function hidenotifi() {
      document.getElementById("notificationContent").style.display = "none";
      document.getElementById("notifications").style.display = "none";

    }

    function hidenotifi2() {
      document.getElementById("notificationContent2").style.display = "none";

    }
    document.addEventListener("DOMContentLoaded", function() {

      <?php if ($_SESSION["state"] === 'seller') : ?>
        document.getElementById("option1").style.display = "block";
        document.getElementById("option2").style.display = "block";
        document.getElementById("option3").style.display = "block";
        document.getElementById("option4").style.display = "block";
        document.getElementById("option5").style.display = "block";
        document.getElementById("option10").style.display = "block";
        document.getElementById("option11").style.display = "block";
      <?php elseif ($_SESSION["state"] === 'admin') : ?>
        document.getElementById("option2").style.display = "block";
        document.getElementById("option4").style.display = "block";
        document.getElementById("option5").style.display = "block";
        document.getElementById("option6").style.display = "block";
        document.getElementById("option9").style.display = "block";
        document.getElementById("option5").style.display = "block";

      <?php elseif ($_SESSION["state"] === 'wholeseller') : ?>
        document.getElementById("option7").style.display = "block";
        document.getElementById("option8").style.display = "block";
      <?php endif; ?>

    });

    function back() {
      window.history.back();
    }

    function toggleProfilePanel() {
      var profilePanel = document.getElementById('profilePanel');
      profilePanel.style.display = (profilePanel.style.display === 'block') ? 'none' : 'block';
      document.getElementById("mySidepanel").style.width = "0px";
      //fetchUserData()
    }

    function profileclose() {
      const profilePanel = document.getElementById('profilePanel').style.display = "none";
    }

    function changePassword() {
      // Implement the logic to change the password here
      window.location.href = "/common/chng_pass.php";
    }

    function logout() {
      // AJAX request to a PHP script that handles session logout
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Redirect to login.php after successful logout
          window.location.href = "/index.php";
        }
      };

      // Send a request to a PHP script that destroys the session
      xmlhttp.open("GET", "logout.php", true); //  logout.php is the script that destroys the session
      xmlhttp.send();
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

    function redirectToIndex() {
      hideSuccess();
      // Redirect to index.php
      window.location.href = "/index.php";
    }

    function sendSMS() {
      // Create an AJAX request
      var xhr = new XMLHttpRequest();

      // Define the PHP file and function to call
      var phpFile = "email_sms.php";
      var functionName = "sendremsms";

      // Prepare the data to send
      var data = new FormData();
      data.append('functionName', functionName);

      // Configure the AJAX request
      xhr.open("POST", phpFile, true);

      // Set the event handler to manage the response
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          // Do something with the response
          console.log(xhr.responseText);
        }
      };

      // Send the request
      xhr.send(data);
    }

    function changePassword() {
      // Implement the logic to change the password here
      window.location.href = 'chng_pass.php';
    }
    // When clicking on the notification bell
  </script>
  <script>
    function showdetails(event) {
      document.getElementById("notifications").style.display = "block";
      $.ajax({
        url: 'fetch_notifications.php', // Change this to your PHP script that fetches the notifications
        type: 'POST',
        data: {
          action: 'get_notifications'
        },
        success: function(data) {
          $('#notifications').html(data);
        },
        error: function(xhr, status, error) {
          console.error("AJAX error:", status, error);
          $('#notifications').html("An error occurred while fetching notifications.");
        }
      });
    }
  </script>

</body>

</html>