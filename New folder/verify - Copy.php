<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Verify</h2>

        <?php
        
        // Display error message if set
        if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }
        ?>

         <form method="POST" action="<?php $_PHP_SELF ?>">
            <div class="form-group">
                <label for="email" id="email">E-Mail Address </label>
<input type="text" name="emailtext" id="emailtext" class="form-control" required>

                <label for="otp" id="otp" style="display:none;">OTP Code </label>
                <input type="text" name="otptext" id="otptext" class="form-control" required style="display:none;">
            </div>

            <button type="button" onclick="toggle()" id="requestotp">Request OTP</button>
            <button type="submit" name="proceed" id="proceed" style="display:none;">Proceed</button>
            <br>
        </form>
    </div>
    <script type="text/javascript">
        function toggle() {
    var emailLabel = document.getElementById("email");
    var emailInput = document.getElementById("emailtext");
    var otpLabel = document.getElementById("otp");
    var otpInput = document.getElementById("otptext");

    // Check if the email input is not empty
    if (emailInput.value.trim() !== "") {
        // Perform AJAX request to check the email with the server
        checkEmailAvailability(emailInput.value, function (isValid) {
            if (isValid) {
                // Email is correct, hide email fields
                emailLabel.style.display = "none";
                emailInput.style.display = "none";
                
                // Show OTP fields
                otpLabel.style.display = "block";
                otpInput.style.display = "block";
                
                // Hide the "Request OTP" button
                document.getElementById("requestotp").style.display = "none";
                
                // Show the "Proceed" button
                document.getElementById("proceed").style.display = "block";
            } else {
                // Invalid email, show error message
                alert("Invalid Email Address");
            }
        });
    } else {
        // Email is empty, show error message
        alert("Please enter an Email Address");
    }
}

function checkEmailAvailability(email, callback) {
    // You can use XMLHttpRequest or fetch API for AJAX requests
    var xhr = new XMLHttpRequest();
    
    // Set up the request
    xhr.open("POST", "check_email.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    // Define the callback function to handle the response
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Parse the response as JSON
            var response = JSON.parse(xhr.responseText);

            // Call the callback function with the result
            callback(response.isValid);
        }
    };

    // Send the request with the email data
    xhr.send("email=" + encodeURIComponent(email));
}

    </script>
</body>
</html>
