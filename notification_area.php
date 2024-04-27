<?php

function topnavigation()
{

    // If no referrer is set, provide a default back link
    echo '<a onclick="back()" class="back-link" style="float:left; font-size:18pt;;"><i class="fa fa-angle-left"></i></a>';

    echo '<a onclick="shownotf()" class="notification">
    <i class="fa fa-bell" style="color:black;"></i>
    <span class="badge" id="badges" style="margin-left: -6px;display:none;"></span>
  </a>';

    echo '<div class="notification-panel" style="
    display: none;
    position: fixed;
    top: 15%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding:10px;
    border: 1px solid #ccc;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    z-index: 1;
    width:80%;
    max-width: 400px;
  " id="notificationPanel">
  <table><tr><td>notification</td><td style="width:80%;margin:0;"><a onclick="closeintro()" style="text-align:right;font-size:15px; color:green;cursor:pointer;-webkit-user-select:none;font-weight:bold;">&#10005;</a></td></tr></table>
  
  <div id="notificationContent" style="background-color: #fff;border-radius: 10px;box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);font-size:12px;padding:10px;">Some of These Customers have outstanding balance remaining<table><tr><td><a class="view" style="cursor:pointer;-webkit-user-select:none;font-size:12px;color:blue;" >View detils</a></td><td><a href="#" onclick="sendSMS()" style="cursor:pointer;-webkit-user-select:none;font-size:12px;color:blue;">Send Message</a>
</td></tr></table></div>
 
</div>';
    echo ' <div id="notifications" style=" width:80%;background-color: #fff;border-radius: 10px;box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);font-size:12px;padding:10px;display:none;position: fixed;transform: translate(-50%, -50%);top: 26%;left: 50%;max-width:400px;"></div>';

    echo '<a href="javascript:void(0);" class="icon" onclick="openNav()">
    <i class="fa fa-bars"></i>
  </a>';
    echo '<div id="mySidepanel" class="sidepanel">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <a href="#" onclick="toggleProfilePanel()">Profile</a>
  <br>
  <a href="#">About</a>

  <a href="#">Contact</a>
   <a href="#" onclick="logout()">Logout</a>
  </div>';
    echo '<div id="profilePanel" class="profile-panel">

    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="our-team">
        <div class="picture">
          <img class="img-fluid" style="height: 100px; width: 100px;" src="https://picsum.photos/id/77/1631/1102">
        </div>';
    echo '<div class="team-content">';
    echo '<h4 class="name">' . $_SESSION["user_log_fname"] . " " . $_SESSION["user_log_lname"] . '</h4>
          <h4 class="title">' . $_SESSION["state"] . '</h4><h4 class="title">' . $_SESSION["user_log_email"] . '</h4>';
    echo '<button onclick="changePassword()" class="changePass">Change Password</button>';

    echo '<button type="submit"  onclick="logout()" name="logout" id="logout" style="background: transparent;color: green;" >Logout</button>';
    echo ' </div>';
    echo '</div>';

    echo '</div>';



    echo '</div>';
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    function logout() {
        // AJAX request to a PHP script that handles session logout
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Redirect to login.php after successful logout
                window.location.href = "index.php";
            }
        };

        // Send a request to a PHP script that destroys the session
        xmlhttp.open("GET", "logout.php", true); //  logout.php is the script that destroys the session
        xmlhttp.send();
    }

    function closeintro() {
        document.getElementById("notificationPanel").style.display = "none";

    }

    function closediv() {
        document.getElementById("notifications").style.display = "none";

    }

    function back() {
        window.history.back();
    }
    $(document).ready(function() {
        // Load notification count
        $.ajax({
            url: 'fetch_notifications.php', // Change this to your PHP script that fetches the notification count
            type: 'POST',
            data: {
                action: 'get_notification_count'
            },
            success: function(data) {
                document.getElementById('badges').style.display = 'block';
            }
        });

        // When clicking on the notification bell
        function shownotf() {
            // Show the notification panel
            $('.notification-panel').toggle();

        }

        $('.view').click(function() {
            document.getElementById("notificationPanel").style.display = "none";
            document.getElementById("notifications").style.display = "block";
            $.ajax({
                url: 'fetch_notifications.php', // Change this to your PHP script that fetches the notifications
                type: 'POST',
                data: {
                    action: 'get_notifications'
                },
                success: function(data) {
                    $('#notifications').html(data);
                }
            });
        }) // Load notifications

    });

    function sendSMS() {
        // Create an AJAX request
        var xhr = new XMLHttpRequest();

        // Define the PHP file and function to call
        var phpFile = "email_sms.php";
        var functionName = "sendsms";

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
</script>
<style>
    .notification {
        background-color: white;
        color: black;
        text-decoration: none;
        border-radius: 2px;
        margin-left: 70%;
    }

    .notification .badge {
        margin-bottom: 50%;
        top: 0px;
        right: 1px;
        height: 1px;
        width: 1px;
        padding: 0px 3px;
        border-radius: 50%;
        background-color: red;
        color: white;
        font-size: 5px;
    }

    .profile-icon {
        background-image: url('profile.png');
        position: absolute;
        top: 40px;
        right: 40px;
        height: 20px;
        width: 20px;
        margin-bottom: 400px;

    }

    .profile-panel {
        display: none;
        position: fixed;
        top: 45%;
        left: 85%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 10px;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        width: 200px;
        height: 320px;

    }


    .our-team {
        margin-bottom: 5px;
        background-color: transparent;
        text-align: center;
        overflow: hidden;
        position: relative;
        border-radius: 15px;
    }

    .our-team .picture {
        display: inline-block;
        height: 100px;
        width: 100px;
        margin-bottom: 10px;
        z-index: 1;
        position: relative;
    }

    .our-team .picture::before {
        content: "";
        width: 100%;
        height: 0;
        border-radius: 50%;
        background-color: #4caf50;
        position: absolute;
        bottom: 135%;
        right: 0;
        left: 0;
        opacity: 0.9;
        transform: scale(3);
        transition: all 0.3s linear 0s;
    }

    .our-team:hover .picture::before {
        height: 100%;
    }

    .our-team .picture::after {
        content: "";
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #4caf50;
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
    }

    .our-team .picture img {
        width: 100%;
        height: auto;
        border-radius: 50%;
        transform: scale(1);
        transition: all 0.9s ease 0s;
    }

    .our-team:hover .picture img {
        box-shadow: 0 0 0 14px #4caf50;
        transform: scale(0.7);
    }

    .our-team .title {
        display: block;
        font-size: 15px;
        color: #4e5052;
        text-transform: capitalize;
    }
</style>