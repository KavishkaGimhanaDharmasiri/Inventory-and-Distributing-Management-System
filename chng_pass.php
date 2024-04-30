<?php
session_start();
include("db_connection.php");
require 'notification_area.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file

    $user_id = $_SESSION['user_id'];
    $username = $_SESSION["username"];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate that the two entered passwords match
    if ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the new password
        // $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $query = "UPDATE login SET password='$new_password' WHERE user_id=$user_id";
        $result = mysqli_query($connection, $query);

        if ($result) {
            // Password update successful
            // header('Location:divs.php');
            echo '<div id="overlay"></div><div id="successModal"><div class="gif"></div>
                    <button onclick="redirectToIndex()" class="sucess">OK</button>
                    </div>';
        } else {

            $error_message = "Password update failed. Please try again.";
        }
    }
    unset($_SESSION['code']);
    session_write_close();
    // Close the database connection
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" type="text/css" href="mobile.css">
    <style>
        .alertsucess {
            margin-top: 15px;
            padding: 10px;
            color: #fff;
            background-color: #4caf50;
            border: 1px solid #f5c6cb;
            border-radius: 15px;
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
            topnavigation();
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
            <h2>Change Password</h2>

            <?php
            // Display error message if set
            if (isset($error_message)) {
                echo '<div class="alert alert-danger">' . $error_message . '</div>';
            } else if (isset($sucess_message)) {
            }

            ?>

            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <?php

                    // Check if the 'username' key exists in the $_SESSION array
                    // If it exists, assign its value to the $username variable
                    $username = $_SESSION["username"];
                    // Output the input field with the username value
                    echo "<input type='text' name='username' class='form-control' required value='" . $username . "' readonly>";
                    ?>


                </div>
                <br>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="confirm_password">Re Enter Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit">Change Password</button>
                <br>
                <br>
                <button type="reset">Clear</button>
            </form>
        </div>
    </div>


    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }

        function showSuccess() {
            var overlay = document.getElementById('overlay');
            var successModal = document.getElementById('successModal');

            overlay.style.display = 'block';
            successModal.style.display = 'block';
        }

        function hideSuccess() {
            var overlay = document.getElementById('overlay');
            var successModal = document.getElementById('successModal');

            overlay.style.display = 'none';
            successModal.style.display = 'none';
        }

        function redirectToIndex() {
            hideSuccess();
            // Redirect to index.php
            window.location.href = 'option.php';
        }
    </script>

</body>

</html>