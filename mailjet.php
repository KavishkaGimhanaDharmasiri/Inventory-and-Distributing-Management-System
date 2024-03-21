<?php

require 'mailjet-apiv3-php-master/src/Mailjet/Client.php'; // Adjust the path accordingly
require 'mailjet-apiv3-php-master/src/Mailjet/Resources.php'; // Adjust the path accordingly

use \Mailjet\Resources;

// Replace these with your Mailjet API and Secret keys
$apiKey = '253e541e315d48513de6aac065eafe0c';
$apiSecret = 'da03660dfb9669296677542e00ca823e';

$mailjet = new \Mailjet\Client($apiKey, $apiSecret, true, ['version' => 'v3.1']);

$email = [
    'Messages' => [
        [
            'From' => [
                'Email' => 'prolinkpc02@gmail.com',
                'Name' => 'Lotus'
            ],
            'To' => [
                [
                    'Email' => 'prolinkpc2@gmail.com',
                    'Name' => 'lotus'
                ]
            ],
            'Subject' => 'billing',
            'HTMLPart' => '<p>Hello, World!</p>'
        ]
    ]
];

try {
    $response = $mailjet->post(Resources::$Email, ['body' => $email]);

    if ($response->success()) {
        echo 'Email sent successfully!';
    } else {
        echo 'Error sending email. Details: ' . print_r($response->getData(), true);
    }
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
