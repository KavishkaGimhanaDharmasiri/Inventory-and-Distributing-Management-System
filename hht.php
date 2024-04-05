<?php
session_start();
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>

       

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit">Login</button>
            <br>
            <br>
            <button type="button" style=" background-color:transparent; " class="fodpass" onclick="redirect()">Fogot Password</button>
        </form>
    </div>
    <script type="text/javascript">
        function redirect() {
    window.location.href = 'PasswordValidation.php';
  }
    </script>
</body>
</html>