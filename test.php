<?php 
session_start();
function sendsms($number,$message){
    $apiEndpoint = 'https://app.notify.lk/api/v1/send';

// Replace these values with your actual user ID, API key, and sender ID
$userId = '26551';
$apiKey = 'wHv9lFirIyeaEigL7WOG';
$senderId = 'NotifyDEMO';


// Get custom content from the form or any source
//$number = $modifiedNumber; // Assuming you have a form field named 'number'
//$message = $body; // Assuming you have a form field named 'message'

// Prepare the API URL with parameters
$apiUrl = "$apiEndpoint?user_id=$userId&api_key=$apiKey&sender_id=$senderId&to=$number&message=$message";

// Make the HTTP request
$response = file_get_contents($apiUrl);

}


$telephone=$_SESSION['telephone'];
$modifiedNumber = '94' . substr($telephone, 0); 
echo $modifiedNumber; 

$message="hi";
sendsms($modifiedNumber,$message);
?>