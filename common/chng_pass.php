<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['index_visit']) || !isset($_SESSION["state"])) {
    acess_denie();
    exit();
} else {
    $_SESSION['email_valid_visit'] = true;
}



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
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/divs.css">
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
            // Generate back navigation link using HTTP_REFERER
            echo '<a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
            ?>


        </div>
        <div class="container">
            <h3 style="text-align: center;">Change Password</h3>

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
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Re Enter Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit">Change Password</button>
                <button type="reset" style="background-color: transparent;color:green;margin-top:none;">Clear</button>
            </form>
        </div>
    </div>


    <script>
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
            window.location.href = '/common/option.php';
        }

        function back() {
            window.history.back();
        }
    </script>

</body>

</html>