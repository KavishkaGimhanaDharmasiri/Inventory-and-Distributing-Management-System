<?php
session_start();
include("db_connection.php");

require_once('tcpdf/tcpdf.php');

$orderDetails = $_SESSION['order_details'] ?? [];
$totalAmount = $_SESSION['total_amount'] ?? 0;
$selectedPaymentMethod =$_SESSION['selected_payment_method'];
$select_store=$_SESSION['selected_store'];

function getPaymentMethodPrices($mainCategory, $subCategory, $connection)
{
    $query = "SELECT cashPrice, checkPrice, creditPrice FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}

// Function to generate PDF receipt
    $pdf = new TCPDF();

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Company Name');
    $pdf->SetTitle('Sales Receipt');
    $pdf->SetSubject('Sales Receipt');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Output company name and date
   /* $pdf->Cell(0, 10, $select_store, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Date: ' . $date, 0, 1, 'C');*/

    // Output order details
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Order Details', 0, 1, 'L');

    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(40, 10, 'Main Category', 1);
    $pdf->Cell(40, 10, 'Sub Category', 1);
    $pdf->Cell(30, 10, 'Count', 1);
    $pdf->Cell(40, 10, 'Subtotal', 1);
    $pdf->Ln();

    $pdf->SetFont('helvetica', '', 12);
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
        }

        $pdf->Cell(40, 10, $order['main_category'], 1);
        $pdf->Cell(40, 10, $order['sub_category'], 1);
        $pdf->Cell(30, 10, $order['count'], 1);
        $pdf->Cell(40, 10, number_format($subtotal, 2), 1);
        $pdf->Ln();
    }

    // Output payment information
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Payment Information', 0, 1, 'L');

    $pdf->Cell(40, 10, 'Payment Method:', 1);
    $pdf->Cell(40, 10, $selectedPaymentMethod, 1);
    $pdf->Ln();

    $pdf->Cell(40, 10, 'Total Amount:', 1);
    $pdf->Cell(40, 10, number_format($totalAmount, 2), 1);
    $pdf->Ln();

    // Add logic to display payment amount and balance if applicable
    // ...

    // Save the PDF to a file or output it to the browser
    $pdf->Output('sales_receipt.pdf', 'I');

// Handle payment form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_payment"])) {
    // Retrieve selected payment method and calculate total amount
    $selectedPaymentMethod = $_POST['payment_method'] ?? '';
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

    // Call the function to generate PDF
    generatePDF($orderDetails, $totalAmount, $selectedPaymentMethod,"Lotus Electricals (ptv) ltd", date('Y-m-d'));
}
?>