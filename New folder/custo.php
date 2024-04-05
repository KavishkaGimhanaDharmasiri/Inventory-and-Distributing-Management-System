<?php

// API endpoint
$apiEndpoint = 'https://app.notify.lk/api/v1/send';

// Replace these values with your actual user ID, API key, and sender ID
$userId = '26551';
$apiKey = 'wHv9lFirIyeaEigL7WOG';
$senderId = 'NotifyDEMO';

// Get custom content from the form or any source
$number = '94742349343'; // Assuming you have a form field named 'number'
$message = 'message'; // Assuming you have a form field named 'message'

// Prepare the API URL with parameters
$apiUrl = "$apiEndpoint?user_id=$userId&api_key=$apiKey&sender_id=$senderId&to=$number&message=$message";

// Make the HTTP request
$response = file_get_contents($apiUrl);

// Output API response
//echo 'API Response: ' . $response;
?>
