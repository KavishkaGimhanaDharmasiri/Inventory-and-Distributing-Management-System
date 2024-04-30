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
    function toggleProfilePanel() {
        var profilePanel = document.getElementById('profilePanel');
        profilePanel.style.display = (profilePanel.style.display === 'block') ? 'none' : 'block';
        //fetchUserData()
    }

    function changePassword() {
        // Implement the logic to change the password here
        window.location.href = 'chng_pass.php';
    }