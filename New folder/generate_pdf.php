<?php
// Include your database connection file
include("db_connection.php");

// Retrieve order details and total amount from session
session_start();
$orderDetails = $_SESSION['order_details'] ?? [];
$totalAmount = $_SESSION['total_amount'] ?? 0;
$selectedPaymentMethod = $_SESSION['selected_payment_method'] ?? '';

// Your existing code to fetch main categories, get subcategories, and calculate total amount

// Include TCPDF library
require_once('tcpdf/tcpdf.php');

// Function to generate the sales receipt in PDF
function generateSalesReceipt($orderDetails, $totalAmount, $selectedPaymentMethod) {
    // Create a new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Sales Receipt');
    $pdf->SetSubject('Sales Receipt');
    $pdf->SetKeywords('Sales, Receipt, PDF');

    // Add a page
    $pdf->AddPage();

    // Add content to the PDF (modify this based on your specific requirements)
    $pdf->SetFont('helvetica', '', 12);
    
    // Output order details in tabular format
    $html = '<h1 style="text-align: center; text-decoration: underline;">Lotus Electricals (PVT)LTD Company</h1>';
$html .= '<br>';
$html .= '<br>';
$html .= '<h3>Date and Time: ' . date("Y-m-d h:i:s") . '</h3>';
$html .= '<br>';
$html .= '<br>';
$html .= '<h2>Sales Receipt</h2>';
$html .= '<p>Payment Method: ' . $selectedPaymentMethod . '</p>';
$html .= '<table border="1" style="width:100%; text-align:center;">';
$html .= '<tr><th>Main Category</th><th>Sub Category</th><th>Count</th><th>Total Amount</th></tr>';

foreach ($orderDetails as $order) {
    $html .= '<tr>';
    $html .= '<td>' . $order['main_category'] . '</td>';
    $html .= '<td>' . $order['sub_category'] . '</td>';
    $html .= '<td>' . $order['count'] . '</td>';
    $html .= '<td>' . $order['count'] * $totalAmount . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// Display total amount
$html .= '<p>Total Amount: ' . $totalAmount . '</p>';

    $pdf->writeHTML($html, true, false, true, false, '');

    // Save the PDF to a file (modify the filename as needed)
    $pdf->Output('sales_receipt.pdf', 'D');
    session_destroy();
}

// Generate the sales receipt in PDF format
generateSalesReceipt($orderDetails, $totalAmount, $selectedPaymentMethod);
?>
