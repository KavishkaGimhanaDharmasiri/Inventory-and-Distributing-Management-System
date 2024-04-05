<?php
// Include your database connection file
include("db_connection.php");

// Retrieve selected payment method and payment amount from the AJAX request
$selectedPaymentMethod = $_POST['payment_method'] ?? '';
$paymentAmount = $_POST['payment_amount'] ?? 0;

// Calculate total amount based on the selected payment method and return it
$totalAmount = 0;

foreach ($orderDetails as $order) {
    $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category'], $connection);

    switch ($selectedPaymentMethod) {
        case 'cash':
            $totalAmount += $order['count'] * $prices['cashPrice'];
            break;
        case 'check':
            $totalAmount += $order['count'] * $prices['checkPrice'];
            break;
        case 'credit':
            $totalAmount += $order['count'] * $prices['creditPrice'];
            break;
    }
   
}
return  $totalAmount;
// Return the total amount as JSON
echo json_encode(['total' => $totalAmount]);
?>
