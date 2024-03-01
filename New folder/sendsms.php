<?php

// Your SMS API endpoint
$apiEndpoint = 'https://sms.send.lk/api/v3/';

// Your API token
$apiToken = '1741|B879pcBhCBiN6cJTwKDFUHS3rAnx2bqWfG1EgAzD ';

// Recipient's phone number
$phoneNumber = '0789106900';

// Message to be sent
$message = 'Hello, this is a test message! to send by message';

// Build the request payload
$data = array(
    'api_token' => $apiToken,
    'phone_number' => $phoneNumber,
    'message' => $message,
);

// Initialize cURL session
$ch = curl_init($apiEndpoint);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

curl_setopt($ch, CURLOPT_CAINFO, 'C:/wamp64/bin/cacert.pem');

// Execute the cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
} else {
    // Process the response as needed
    echo 'SMS sent successfully!';
}

// Close cURL session
curl_close($ch);

?>
