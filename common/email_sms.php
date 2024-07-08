<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $functionName = $_POST['functionName'] ?? '';

    if ($functionName === 'sendremsms' && function_exists($functionName)) {
        $result = sendremsms();
        echo $result;
    } else {
        echo "Function $functionName does not exist.";
    }
}

function sendmail($Subject, $body, $user, $firstname)
{
    try {
        $apiKey = "xkeysib-b3awDcJm";  // Replace with your actual API key
        $url = "https://api.brevo.com/v3/smtp/email";

        // Email data
        $data = [
            "sender" => [
                "name" => "Lotus Electicals",
                "email" => "prolinkpc02@gmail.com"
            ],
            "to" => [
                [
                    "email" => $user,
                    "name" => $firstname
                ]
            ],
            "subject" => $Subject,
            "htmlContent" => "<html><head></head><body><p>$body</p></body></html>"
        ];

        // Initialize cURL
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            echo '<script> alert("Email could not be sent. No Internet Connection Found. Please Go Online");</script>';
        }

        // Close cURL
        curl_close($ch);

        //echo $response;
    } catch (Exception $e) {
        echo '<script> alert("Email could not be sent. No Internet Connection Found. Please Go Online");</script>';
    }
}

/*function sendsms($number, $message)
{
    try {
        $apiEndpoint = 'https://app.notify.lk/api/v1/send';

        // Replace these values with your actual user ID, API key, and sender ID
        $userId = '56835';
        $apiKey = 'IzqyTghIvA';
        $senderId = 'NotifyDEMO';


        // Get custom content from the form or any source
        //$number = $modifiedNumber; // Assuming you have a form field named 'number'
        //$message = $body; // Assuming you have a form field named 'message'

        // Prepare the API URL with parameters
        $apiUrl = "$apiEndpoint?user_id=$userId&api_key=$apiKey&sender_id=$senderId&to=$number&message=$message";

        // Make the HTTP request
        $response = file_get_contents($apiUrl);
    } catch (Exception $e) {
        echo $e;
        echo '<script> alert("Message could not be sent. No Internet Connection Found. Please Go Online");</script>';
    }
}*/

function sendremsms()
{
    date_default_timezone_set('Asia/Colombo');
    $currentDateTime = new DateTime(); // Get the current date and time

    $cur_date = $currentDateTime->format('Y-m-d');
    $route_id = $_SESSION['route_id'];
    include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
    $sqlq = "SELECT sto_tep_number,u.email,store_name,balance,payment_date from customers c left join payment p on c.user_id=p.user_id left join users u on c.user_id=u.user_id
        WHERE p.balance > 0
          AND p.payment_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) - INTERVAL DAY(CURDATE())-1 DAY
          AND p.payment_date < DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE()) DAY) AND p.route_id=$route_id";
    $results = $connection->query($sqlq);
    if ($results) {
        while ($rowSrore = mysqli_fetch_assoc($results)) {
            $sto_telephone = $rowSrore['sto_tep_number'];
            $sto_name = $rowSrore['store_name'];
            $sto_email = $rowSrore['email'];
            $sto_balance = $rowSrore['balance'];
            $sto_payment_date = $rowSrore['payment_date'];
            $modifiedNumber = '94' . substr($sto_telephone, 0);

            $Subject = "Playment Settlement";
            $message = "Dear $sto_name\n\nOrder Made on $sto_payment_date by $sto_name have $sto_balance remaining to Settle during Last month.
            Please Settle the Above Amount As soon as Posible.\n\nThank You.\nLotus Electicals (PVT)LTD.";

            $ebody = "Dear $sto_name<br><br>Order Made on $sto_payment_date by $sto_name have $sto_balance remaining to Settle during Last month.
            Please Settle the Above Amount As soon as Posible.<br><br>Thank You.<br>Lotus Electicals (PVT)LTD.";

            sendsms($modifiedNumber, $message); //send sms

            sendmail($Subject, $ebody, $sto_email, $sto_name); //send mail
        }
        echo '<script>alert("Massages were send Sucessfully;");</script>';

        try {
            $state = 'yes';
            $id = 1;
            $pdo->beginTransaction();
            $query = "UPDATE notification SET not_date=:not_date, state=:state  WHERE not_id=:not_id";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':not_date', $cur_date);
            $stmt->bindParam(':state', $state);
            $stmt->bindParam(':not_id', $id);

            $stmt->execute();
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo '<script>alert("State update failed");</script>';
        }
        return "SMS sent successfully!";
    }
}

function sendsms($number, $message) //alternative function
{

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://w13ymy.api.infobip.com/sms/2/text/advanced');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'messages' => [
            [
                'destinations' => [
                    ['to' => $number]
                ],
                'from' => 'Lotus Electricals(PVT).LTD',
                'text' => $message
            ]
        ]
    ]));

    $headers = [
        'Authorization: App 1ba827625-1b83ee3a9663',
        'Content-Type: application/json',
        'Accept: application/json'
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        // echo 'Response:' . $response;
    }

    curl_close($ch);
}
