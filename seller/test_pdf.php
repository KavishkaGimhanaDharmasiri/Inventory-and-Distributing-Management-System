<?php
session_start();

if (!isset($_SESSION['payment_visit']) || $_SESSION["state"] != 'seller') {
    acess_denie();
    exit();
} else {
    $_SESSION['sales_recipt_download'] = true;
}
ob_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/tcpdf/tcpdf.php');
ob_end_clean();


error_reporting(E_ALL);
ini_set('display_errors', 1);


$orderDetails = $_SESSION['order_details'] ?? [];
$totalAmount = $_SESSION['totalAmount'];
$selectedPaymentMethod = $_SESSION['selected_payment_method'];

// Now call the function
date_default_timezone_set('Asia/Colombo');
if ($_SESSION["state"] == 'seller') {
    generateDetailedOrderReceipt($orderDetails, $totalAmount, $selectedPaymentMethod, $connection);
}

function getPaymentMethodPriced($mainCategory, $subCategory, $connection)
{
    $query = "SELECT cashPrice, checkPrice, creditPrice FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    return mysqli_fetch_assoc($result);
}

function determinePriceRange($price)
{
    if ($price >= 10 && $price <= 500) {
        return '100-500';
    } elseif ($price > 500 && $price <= 1500) {
        return '500-1500';
    } elseif ($price > 1500 && $price <= 5000) {
        return '1500-5000';
    } else {
        return 'other'; // Default case or handle other ranges as needed
    }
}



function generateDetailedOrderReceipt($orderDetails, $totalAmount, $selectedPaymentMethod, $connection)
{
    include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $storename = $_SESSION['selected_store'];
    $totalAmount = $_SESSION['totalAmount'];
    $paymentAmout = $_SESSION['paymentAmount'];
    $balance = $_SESSION['balance'];

    $login_fname = $_SESSION["user_log_fname"];
    $login_lname = $_SESSION["user_log_lname"];
    //$ord_id = $_SESSION['ord_id'];

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
    $html .= '<h2 style="text-align: center;">Sales Receipt</h2>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<h4>Date and Time: ' . date('Y-F-d   h:i:a') . '</h4>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<h4>Sales In Charge : Mr.' . $_SESSION["user_log_fname"] . ' ' . $_SESSION["user_log_lname"] . '</h4>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<br>';
    $html .= '<h4>Customer Name :' . $storename . ' </h4>';
    $html .= '<table border="1" style="width:100%; text-align:center;">';
    $html .= '<tr><th>Item Name</th><th>Item Price</th><th>Count</th><th>Subtotal</th></tr>';

    foreach ($orderDetails as $order) {
        $prices = getPaymentMethodPriced($order['main_category'], $order['sub_category'], $connection);

        //echo "<pre>";
        // print_r($prices); // Debug line: Print prices array
        // $_SESSION['selected_payment_method']=$selectedPaymentMethod;

        switch ($selectedPaymentMethod) {
            case 'cash':
                $_SESSION['selected_payment_method'] = 'cash';
                $unitprice = $prices['cashPrice'];
                $subtotal = $order['count'] * $prices['cashPrice'];
                break;
            case 'check':
                $_SESSION['selected_payment_method'] = 'check';
                $unitprice = $prices['checkPrice'];
                $subtotal = $order['count'] * $prices['checkPrice'];
                break;
            case 'credit':
                $_SESSION['selected_payment_method'] = 'credit';
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
        $html .= '<tr>';
        $html .= '<td>' . $order['sub_category'] . '</td>';
        $html .= '<td>' . $unitprice . '</td>';
        $html .= '<td>' . $order['count'] . '</td>';
        $html .= '<td>' . $subtotal . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Display total amount
    $html .= '<h4>Total Amount : Rs. ' . $_SESSION['totalAmount'] . '</h4>';
    $html .= '<br>';
    $html .= '<h4>Payment Method : ' . $_SESSION['selected_payment_method'] . '</h4>';
    $html .= '<br>';
    $html .= '<h4>Payment Amount : Rs. ' . $paymentAmout . '</h4>';
    $html .= '<br>';
    $html .= '<h4>Balance : Rs. ' . $balance . '</h4>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdfDirectory = $_SERVER['DOCUMENT_ROOT'] . 'pdf/';  // Use the server's document root
    $orderID = $_SESSION['ord_id'];  // Replace this with your actual order ID
    $pdfFileName = 'order_' . $orderID . '.pdf';  // Include order ID in the filename
    $pdfFilePath = $pdfDirectory . $pdfFileName;

    $pdf->Output($pdfFilePath, 'F');  // Save the PDF file on the server

    // Store the file name in the database
    $insertQuery = "INSERT INTO generated_pdfs (ord_id, pdf_path) VALUES ('$orderID', '$pdfFileName')";
    mysqli_query($connection, $insertQuery);

    // Output the PDF to the browser for download
    $pdf->Output($pdfFileName, 'D');
}
