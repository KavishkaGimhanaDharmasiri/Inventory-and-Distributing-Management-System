<?php
session_start();
include("db_connection.php");

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
           $sucess_message="Password Change Sucessfully.";
        } else {
            // Password update failed
            $error_message = "Password update failed. Please try again.";
        }
    }

    // Close the database connection
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style type="text/css">
        .alertsucess {
            margin-top: 15px;
            padding: 10px;
            color: #fff;
            background-color: #4caf50;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>

        <?php
        // Display error message if set
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
         else if (isset($sucess_message)) {
            echo '<div class="alertsucess">' . $sucess_message . '</div>';
        }

        ?>

        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="username">Username</label>
<?php

// Check if the 'username' key exists in the $_SESSION array
if (isset($_SESSION["user_log_fname"])) {
    // If it exists, assign its value to the $username variable
    $username=$_SESSION["user_log_fname"];
    // Output the input field with the username value
    echo "<input type='text' name='username' class='form-control' required value='" . $username . "' readonly>";
} else {
    // If the 'username' key doesn't exist, you can provide a default value or handle it accordingly
    echo "<input type='text' name='username' class='form-control' required value='Username not found. Enter Your Username'>";
}
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
            <br>
            <br>
            <button type="reset">Clear</button>
        </form>
    </div>
</body>
</html>
