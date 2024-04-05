<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Chat</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        .order-form {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            min-width: 450px;
            overflow-y: auto;
            overflow: visible;
            margin-top: 20%; 
        }
    </style>
</head>
<body>
    
    <form method="post" action="">
        <div class="order-form">
            <input type="text" class="form-control" id="receiver_user" placeholder="Type a username">
            <div id="suggestedResults" class="list-group"></div>
            <br>
            <input type="text" name="message" placeholder="Type your message..." style="height: 150px; border-top: 1px solid grren; "><br>
            <br><button type="submit">Send</button>
        </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        var typingTimer;
        var doneTypingInterval = 500;  // Adjust the delay (in milliseconds) as needed

        $('#receiver_user').on('keyup', function() {
            clearTimeout(typingTimer);
            var input = $(this).val();
            if (input.length > 0) {
                typingTimer = setTimeout(function() {
                    $.ajax({
                        url: 'suggest.php',
                        type: 'POST',
                        data: {input: input},
                        dataType: 'json',
                        success: function(response) {
                            $('#suggestedResults').empty();
                            $.each(response, function(index, value) {
                                $('#suggestedResults').append('<div class="suggestion">' + value + '</div>');
                            });
                        }
                    });
                }, doneTypingInterval);
            } else {
                $('#suggestedResults').empty();
            }
        });

        $(document).on('click', '.suggestion', function() {
            var selectedText = $(this).text();
            $('#receiver_user').val(selectedText);
            $('#suggestedResults').empty();
        });
    });
</script>

</body>
</html>
