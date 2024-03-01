<?php
require_once 'HTTP/Request2.php';
$request = new HTTP_Request2();
$request->setUrl('https://e12dd2.api.infobip.com/sms/2/text/advanced');
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setConfig(array(
    'follow_redirects' => TRUE
));
$request->setHeader(array(
    'Authorization' => 'App 96125b9d6a742dadf5b1c9981a76d025-c96892a9-f218-4c8f-96c0-cd88b98e8133',
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
));
$request->setBody('{"messages":[{"destinations":[{"to":"94742349343"},{"to":"94789106900"}],"from":"ServiceSMS","text":"Hello,\\n\\nThis is a test message from Infobip. Have a nice day!"}]}');
try {
    $response = $request->send();
    if ($response->getStatus() == 200) {
        echo $response->getBody();
    }
    else {
        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
        $response->getReasonPhrase();
    }
}
catch(HTTP_Request2_Exception $e) {
    echo 'Error: ' . $e->getMessage();
}