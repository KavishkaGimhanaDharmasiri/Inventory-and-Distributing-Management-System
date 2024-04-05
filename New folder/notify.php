<?php

// Replace these values with your actual API key, sender ID, and API endpoint URL
$apiKey = 'wHv9lFirIyeaEigL7WOG';
$senderId = 'NotifyDEMO';
$apiEndpoint = 'https://app.notify.lk/api/v1/send';

// SMS content
$smsData = [
    'to' => '94742349343', // Replace with the destination number
    'message' => 'Hello, This is a test message from your application.'
];

// Initialize cURL session
$ch = curl_init($apiEndpoint);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey,
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
