<?php
session_start();
$_SESSION['index_visit'] = true;
// Include database connection file
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");

// Function to generate CSRF token
function generateCsrfToken()
{
    return bin2hex(random_bytes(32));
}

// Store CSRF token in session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCsrfToken();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    // Get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $active_state = "active";
    // Validate the user credentials using prepared statements
    $query = "SELECT * FROM login WHERE username = ? AND Active_state= ? ";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $active_state);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        // Check if a matching record is found
        if (mysqli_num_rows($result) == 1) {
            // Fetch user data
            $row = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $row['password'])) {
                // Set session variables
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["state"] = $row["state"];
                $_SESSION['route_id'] = $row["route_id"];

                // Redirect to a secure page after successful login
                header("Location:/common/option.php");
                exit();
            } else {
                // Display an error message if credentials are invalid
                $error_message = "Invalid username or password.";
            }
        } else {
            // Display an error message if credentials are invalid
            $error_message = "Invalid username or password.";
        }
    } else {
        // Display an error message for database query issues
        $error_message = "Database query failed.";
    }

    // Close the database connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" href="/images/tab_icon.png">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/index.css">
</head>

<body>
    <div class="card">
        <div class="img-avatar">
            <svg viewBox="0 0 100 100"></svg>
        </div>
        <div class="card-text">
            <div class="portada"></div>
            <div class="title-total">
                <div class="title">
                    <h2>Login</h2>
                </div>
                <div class="desc">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="form-group">
                            <?php
                            // Display error message if set
                            if (isset($error_message)) {
                                echo '<div class="alert alert-danger">' . $error_message . '</div><br>';
                            }
                            ?>
                            <label for="username" style="font-size: 11pt; font-weight: bold;">Username</label>
                            <input type="text" name="username" class="form-control" required style="width: 230px;" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <label for="password" style="font-size: 11pt; font-weight: bold;">Password</label>
                            <input type="password" name="password" class="form-control" required style="width: 230px;" placeholder="Password">
                        </div>
                        <button type="submit" style="width: 230px; margin-bottom: 0%;">Login</button>
                        <button type="button" style="background-color: transparent; margin-top: 1px;" class="fodpass" onclick="redirect()">Forgot Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function redirect() {
            window.location.href = '/common/password_validation.php';
        }
    </script>
</body>

</html>