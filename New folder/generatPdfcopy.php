<?php
// Include your database connection file
include("db_connection.php");
//require_once('email_sms.php');

// Retrieve order details and total amount from session
session_start();
$orderDetails = $_SESSION['order_details'] ?? [];

//print_r($orderDetails);
$totalAmount = $_SESSION['totalAmount'];
$paymentAmout=$_SESSION['paymentAmount'] ;
$balance=$_SESSION['balance'];
$selectedPaymentMethod =$_SESSION['selected_payment_method'];
$storename=$_SESSION['selected_store'];

// Function to get payment method prices
function getPaymentMethodPrices($mainCategory, $subCategory, $connection)
{
    $query = "SELECT cashPrice, checkPrice, creditPrice FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}
date_default_timezone_set('Asia/Colombo');
// Include TCPDF library
require_once('tcpdf/tcpdf.php');

// Function to generate the detailed order receipt in PDF
function generateDetailedOrderReceipt($orderDetails, $totalAmount, $selectedPaymentMethod, $connection)
{
    // Create a new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$storename=$_SESSION['selected_store'];
$totalAmount = $_SESSION['totalAmount'];
$paymentAmout=$_SESSION['paymentAmount'] ;
$balance=$_SESSION['balance'];

$login_fname= $_SESSION["user_log_fname"] ;
$login_lname=$_SESSION["user_log_lname"];
    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Lotus Electricals (PVT) LTD');
    $pdf->SetTitle('Detailed Order Receipt');
    $pdf->SetSubject('Detailed Order Receipt');
    $pdf->SetKeywords('Order, Receipt, PDF');

    // Add a page
    $pdf->AddPage();

    // Add content to the PDF (modify this based on your specific requirements)
    $pdf->SetFont('helvetica', '', 12);

    // Output order details in tabular format
    $html = '<h1 style="text-align: center;">Lotus Electricals (PVT) LTD</h1>';
    $html .= '<hr>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<h3>Date and Time: ' . date('Y-F-d   h:i:a') . '</h3>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<h2>Order Details</h2>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<h4>Sales In Charge : Mr.'.$_SESSION["user_log_fname"].' '.$_SESSION["user_log_lname"].' </h4>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<h4>Customer Name :'. $storename.' </h4>';
    $html .= '<table border="1" style="width:100%; text-align:center;">';
    $html .= '<tr><th>Main Category</th><th>Sub Category</th><th>Count</th><th>Subtotal</th></tr>';

    foreach ($orderDetails as $order) {
        $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category'], $connection);
        $subtotal = 0;

        switch ($selectedPaymentMethod) {
            case 'cash':
                $subtotal = $order['count'] * $prices['cashPrice'];
                break;
            case 'check':
                $subtotal = $order['count'] * $prices['checkPrice'];
                break;
            case 'credit':
                $subtotal = $order['count'] * $prices['creditPrice'];
                break;
            default:
                $subtotal = 0;
        }

        $html .= '<tr>';
        $html .= '<td>' . $order['main_category'] . '</td>';
        $html .= '<td>' . $order['sub_category'] . '</td>';
        $html .= '<td>' . $order['count'] . '</td>';
        $html .= '<td>' . $subtotal . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Display total amount
    $html .= '<p>Payment Method : ' .$selectedPaymentMethod. '</p>';
    $html .= '<br>';
    $html .= '<p>Total Amount : Rs. ' . $totalAmount . '</p>';
    $html .= '<br>';
    $html .= '<p>Payment Amount : Rs. ' . $paymentAmout . '</p>';
    $html .= '<br>';
    $html .= '<p>Balance : Rs. ' . $balance . '</p>';

    $pdf->writeHTML($html, true, false, true, false, '');

    // Save the PDF to a file (modify the filename as needed)
    $pdfFileName = 'detailed_order_receipt.pdf';
    $pdf->Output($pdfFileName, 'D');


session_destroy();
exit();

}


// Generate the detailed order receipt in PDF format
//generateDetailedOrderReceipt($orderDetails, $totalAmount, $selectedPaymentMethod, $connection);



?>


