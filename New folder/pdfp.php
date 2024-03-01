<?php 
session_start();
include("db_connection.php");
require_once('tcpdf/tcpdf.php');
$orderDetails = $_SESSION['order_details'] ?? [];
$totalAmount = $_SESSION['total_amount'] ?? 0;
$selectedPaymentMethod = $_SESSION['selected_payment_method'] ?? '';

// Function to generate PDF receipt
 $companyName="kmbklmfbkb";
 $date="jnkjkn";

    $connection;
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
    $pdf->Cell(0, 10, $companyName, 0, 1, 'C');
    $pdf->Cell(0, 10, 'Date: ' . $date, 0, 1, 'C');

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


    if (!empty($orderDetails)) {

    foreach ($orderDetails as $order) {
        $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category']);
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
        

        $totalAmount += $subtotal;
    }




    echo "</tbody>";
    echo "</table>";

    echo "<p>Total Amount: {$totalAmount}</p>"; // Display total amount below the table
} else {
    echo "<p>No order details found. Please go back and add items to your order.</p>";
}

    foreach ($orderDetails as $order) {
        $prices = getPaymentMethodPrices($order['main_category'], $order['sub_category']);
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


    function getPaymentMethodPrices($mainCategory, $subCategory)

{
    include("db_connection.php");
    $query = "SELECT cashPrice, checkPrice, creditPrice FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}
?>