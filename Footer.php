<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Loutos</title>

    <link rel="stylesheet" href="css/styledash.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>

    <section class="footer" id="footer">
        <div class="footer-box">
            <a href="#" class="logo">Lotus</a>
            <!-- <i class='bx bxs-florist'></i> -->
            <h5>Follow Us</h5>
            <div class="social">
                <a href="#"><i class='bx bxl-facebook'></i></a>
                <a href="#"><i class='bx bxl-twitter'></i></a>
                <a href="#"><i class='bx bxl-instagram'></i></a>
                <a href="#"><i class='bx bxl-youtube'></i></a>
            </div>
        </div>

        <div class="footer-box">
            <h2>Contact us</h2>
            <a href="#">034-2256124</a>
            <a href="#">034-2256356</a>
            <a href="#">NO.188,<br>Horana Road,<br>Handapangoda.</a>
        </div>
        <div class="footer-box">
            <h2>Usefull Links</h2>
            <a href="#">Payment & Tax</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">My Blog</a>
            <a href="#">Return Policy</a>
        </div>
        <div class="footer-box">
            <h2>Send Your Feedback</h2>
            
            
            <form action="" method="post" id="form">
                <div class="Feedback">
                <textarea name="message" rows="4" cols="50" class="Feedback" placeholder=" Enter your Feedbacks"></textarea>
                </div>
                 <button type="submit" name="send" class="fbtn"><i class='bx bx-arrow-back bx-rotate-180' ></i></button> 
            </form>
            
        </div>

    </section>

</body>

</html>

<?php
if(isset($_POST['send']))
{
    // Get the message from the form
    $message = $_POST['message'];
    
    // Connect to the database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'retail_website';

    $link = mysqli_connect($host, $username, $password, $database);

    // Check connection
    if(!$link){
        die('could not connect: ' . mysqli_error($link));
    }

   if ($message !== null && $message !== "") {
        $stmt = $link->prepare("INSERT INTO feedback (feedbacks) VALUES (?)");
    $stmt->bind_param("s", $message);

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        
    }
    } else {
        echo "Error: " . $stmt->error;
    } 
    
   

    // Prepare the SQL statement using a prepared statement
    

    // Close the connection
    mysqli_close($link);
} 
?>


<script>

     popupButton.addEventListener('click', function (e) {
          e.preventDefault();
          var popupEmail ='thank_you';

          var event_data = {
               event_name: "popup_sent",
               email: popupEmail,
               }

          window.top.UE.pageHit({'apiKey':put_api_key_of_your_app_here,
               'email': popupEmail,
               'event': event_data
               });  
          });
          document.getElementById('my_form').style.display = 'none';
          document.getElementById('thank_you').style.display = 'block';
     });
</script>