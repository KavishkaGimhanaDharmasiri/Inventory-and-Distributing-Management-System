<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include("db_connection.php");

    // Get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the user credentials
    $query = "select * from login where username = '$username' and password = '$password'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Check if a matching record is found
        if (mysqli_num_rows($result) == 1) {
            // Fetch user data
            $row = mysqli_fetch_assoc($result);

            // Set session variables
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["state"]=$row["state"];
           $_SESSION['route_id']=$row["route_id"];

            // Redirect to a secure page after successful login
            header("Location: option.php");
            exit();
        } else {
            // Display an error message if credentials are invalid
            $error_message = "Invalid username or password.";
        }
    } else {
        // Display an error message for database query issues
        $error_message = "Database query failed.";
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
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style type="text/css">
        .fodpass{
            color: #4caf50;
        }
        .fodpass:hover{
            color:darkgreen;
        }
        @import url("https://fonts.googleapis.com/css?family=Roboto:400,400i,700");
body {
  font-family: Roboto, sans-serif;
  margin: 0;
  height: 100vh;
  display: grid;
  align-items: center;
  justify-items: center;
}
.card {
  background: #fff;
  border-radius: 4px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  width: 450px;
  display: flex;
  border-radius: 25px;
  position: relative;
  border: 1px solid #45a049;
  position: sticky;
}
.card h2 {
  margin: 0;
}
.card .title {
  padding: 1rem;
  text-align: right;
  color: green;
  font-weight: bold;
  font-size: 12px;
}
.card .desc {
  font-size: 14px;
  max-width: 450px;
  margin-left: 20px;
  margin-top: 20px;
}
.card .actions {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  align-items: center;
  
}
.card svg {
  width: 85px;
  height: 85px;
  margin: 0 auto;
}

.img-avatar {
  width: 80px;
  height: 80px;
  position: absolute;
  border-radius: 50%;
  border: 6px solid white;
  background-image: linear-gradient(-60deg, #16a085 0%, #f4d03f 100%);
  top: 15px;
  left: 100px;
}
input {
    
.card-text {
  display: grid;
  grid-template-columns: 1fr 2fr;
}

.title-total {
  padding: 20px;
}

path {
  fill: white;
}

.img-portada {
  width: 100%;
}

.portada {
  width: 100%;
  height: 100%;
  border-top-left-radius: 20px;
  border-bottom-left-radius: 20px;
  background-image: url("wires_6302557.jpg");
  background-position: bottom center;
  background-size: cover;
}



    </style>
</head>
<body>

        <div class="card">
  <div class="img-avatar">
    <svg viewBox="0 0 100 100">

  </svg>
  </div>
  <div class="card-text">
    <div class="portada">
    
    </div>
    <div class="title-total">   
      <div class="title"><h2>Login</h2></div>
  
  <div class="desc">
    <form method="POST" action="<?php $_PHP_SELF ?>">
            <div class="form-group">
                <?php
        // Display error message if set
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" required style="width: 230px;">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required style="width: 230px;">
            </div>
<br>
            <button type="submit" style="width: 230px;">Login</button>
            <br>
            <button type="button" style=" background-color:transparent; " class="fodpass" onclick="redirect()">Fogot Password</button>
        </form></div>
 </div>
 
  </div>
  
 
  
</div>

    <script type="text/javascript">
        function redirect() {
    window.location.href = 'PasswordValidation.php';
  }
    </script>
</body>
</html>