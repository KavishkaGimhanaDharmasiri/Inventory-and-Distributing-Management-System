<?php

// Replace these values with your actual API key, token, and API endpoint URL
$apiKey = 'NtWZBSWQU1OdNTYGqB4CEmNNAcxW7MSc';
$apiToken = '80g21707042579';
$apiEndpoint = 'https://cloud.websms.lk/api/v1/send';

// SMS content
$smsData = [
    'destination' => '94789106900', // Replace with the destination number
    'message' => 'Hello, This is a test message from your application.'
];
http://cloud.websms.lk/smsAPI?sendsms&apikey=NtWZBSWQU1OdNTYGqB4CEmNNAcxW7MSc&apitoken=80g21707042579&type=sms&from=Service SMS&to=0742349343&text=My+first+text&route=0

// Initialize cURL session
$ch = curl_init($apiEndpoint);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiToken,
]);

// Set HTTP method to POST
curl_setopt($ch, CURLOPT_POST, true);

// Convert data to JSON format
$smsJson = json_encode($smsData);

// Set POST data
curl_setopt($ch, CURLOPT_POSTFIELDS, $smsJson);

// Execute cURL session
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}

// Close cURL session
curl_close($ch);

// Output API response
echo 'API Response: ' . $response;

if ($httpCode == 200) {
    echo 'SMS sent successfully!';
} else {
    echo 'Failed to send SMS. Response: ' . $response;
}
?>
