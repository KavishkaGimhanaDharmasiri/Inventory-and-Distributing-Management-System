<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db_connection.php");
require_once('fpdf/fpdf.php');

$orderDetails = $_SESSION['order_details'] ?? [];
$totalAmount = $_SESSION['totalAmount'];
$selectedPaymentMethod = $_SESSION['selected_payment_method'];
date_default_timezone_set('Asia/Colombo');

function getPaymentMethodPriced($mainCategory, $subCategory, $connection)
{
    $query = "SELECT cashPrice, checkPrice, creditPrice FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}

function generateDetailedOrderReceipt($orderDetails, $totalAmount, $selectedPaymentMethod, $connection)
{
    $storename = $_SESSION['selected_store'];
    $totalAmount = $_SESSION['totalAmount'];
    $paymentAmout = $_SESSION['paymentAmount'];
    $balance = $_SESSION['balance'];

    $login_fname = $_SESSION["user_log_fname"];
    $login_lname = $_SESSION["user_log_lname"];

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Output order details in tabular format
    $pdf->Cell(190, 10, 'Lotus Electricals (PVT) LTD', 0, 1, 'C');
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(190, 10, 'Sales Receipt', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 10, 'Date and Time: ' . date('Y-F-d   h:i:a'), 0, 1, 'L');
    $pdf->Cell(190, 10, 'Sales In Charge: Mr.' . $login_fname . ' ' . $login_lname, 0, 1, 'L');
    $pdf->Cell(190, 10, 'Customer Name: ' . $storename, 0, 1, 'L');
    $pdf->Ln();

    $pdf->Cell(47.5, 10, 'Item Name', 1, 0, 'C');
    $pdf->Cell(47.5, 10, 'Item Price', 1, 0, 'C');
    $pdf->Cell(47.5, 10, 'Count', 1, 0, 'C');
    $pdf->Cell(47.5, 10, 'Subtotal', 1, 1, 'C');

    foreach ($orderDetails as $order) {
        $prices = getPaymentMethodPriced($order['main_category'], $order['sub_category'], $connection);

        switch ($selectedPaymentMethod) {
            case 'cash':
                $unitprice = $prices['cashPrice'];
                $subtotal = $order['count'] * $prices['cashPrice'];
                break;
            case 'check':
                $unitprice = $prices['checkPrice'];
                $subtotal = $order['count'] * $prices['checkPrice'];
                break;
            case 'credit':
                $unitprice = $prices['creditPrice'];
                $subtotal = $order['count'] * $prices['creditPrice'];
                break;

            case 'custom':
                $_SESSION['selected_payment_method'] = 'cash';
                $customPaymentAmount100 = isset($_POST['custom_range_100']) ? $_POST['custom_range_100'] : '';
                $customPaymentAmount500 = isset($_POST['custom_range_500']) ? $_POST['custom_range_500'] : '';
                $customPaymentAmount1500 = isset($_POST['custom_range_1500']) ? $_POST['custom_range_1500'] : '';

                // Debug lines: Print custom payment amounts

                // Validate and sanitize the custom payment amount
                $customPaymentAmount100 = filter_var($customPaymentAmount100, FILTER_VALIDATE_FLOAT);
                $customPaymentAmount500 = filter_var($customPaymentAmount500, FILTER_VALIDATE_FLOAT);
                $customPaymentAmount1500 = filter_var($customPaymentAmount1500, FILTER_VALIDATE_FLOAT);

                // Debug lines: Print validated custom payment amounts


                // Determine the appropriate subcategory price range
                $priceRange = determinePriceRange($prices['cashPrice']);

                // Debug line: Print price range


                switch ($priceRange) {
                    case '100-500':
                        $unitprice = $prices['cashPrice'] - $customPaymentAmount100;
                        $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount100);
                        break;
                    case '500-1500':
                        $unitprice = $prices['cashPrice'] - $customPaymentAmount500;
                        $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount500);
                        break;
                    case '1500-5000':
                        $unitprice = $prices['cashPrice'] - $customPaymentAmount1500;
                        $subtotal = $order['count'] * ($prices['cashPrice'] - $customPaymentAmount1500);
                        break;
                    default:
                        $unitprice = $prices['cashPrice'];
                        $subtotal = $order['count'] * $prices['cashPrice'];
                        break;
                }
                break;
            default:
                $unitprice = 0;
                $subtotal = 0;
        }

        $totalAmount += $subtotal;

        $pdf->Cell(47.5, 10, $order['sub_category'], 1, 0, 'C');
        $pdf->Cell(47.5, 10, $unitprice, 1, 0, 'C');
        $pdf->Cell(47.5, 10, $order['count'], 1, 0, 'C');
        $pdf->Cell(47.5, 10, $subtotal, 1, 1, 'C');
    }

    $pdf->Ln();
    $pdf->Cell(190, 10, 'Total Amount: Rs. ' . $_SESSION['totalAmount'], 0, 1, 'L');
    $pdf->Cell(190, 10, 'Payment Method: ' . $_SESSION['selected_payment_method'], 0, 1, 'L');
    $pdf->Cell(190, 10, 'Payment Amount: Rs. ' . $paymentAmout, 0, 1, 'L');
    $pdf->Cell(190, 10, 'Balance: Rs. ' . $balance, 0, 1, 'L');

    // Save the PDF to a file (modify the filename as needed)
    $pdfFileName = $storename . '_order_receipt.pdf';
    $pdf->Output($pdfFileName, 'D');
}

generateDetailedOrderReceipt($orderDetails, $totalAmount, $selectedPaymentMethod, $connection);
