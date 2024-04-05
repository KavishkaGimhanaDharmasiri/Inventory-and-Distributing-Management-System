<?php

$apiUrl = 'https://e12dd4.api.infobip.com/sms/2/text/advanced';
$apiKey = '96125b9d6a742dadf5b1c9981a76d025-c96892a9-f218-4c8f-96c0-cd88b98e8133'; // Replace with your actual Infobip API key

$messages = [
    'messages' => [
        [
            'destinations' => [
                ['to' => '94742349343'],
                ['to' => '94789106900']
            ],
            'from' => 'ServiceSMS',
            'text' => 'Hello,\n\nThis is a test message from Infobip. Have a nice day!'
        ]
    ]
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: App ' . $apiKey,
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messages));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

curl_close($ch);

// Handle $response as needed, e.g., check for success or log errors
echo 'API Response: ' . $response;


// Check the HTTP response code to determine success or failure
if ($httpCode == 200) {
    echo 'SMS sent successfully!';
} else {
    echo 'Failed to send SMS. Response: ' . $response;
}
?>
