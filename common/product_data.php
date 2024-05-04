<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/fpdf/fpdf.php');

// Include database connection file
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");


$productQuery = "SELECT * FROM product";
$productresult = mysqli_query($connection, $productQuery);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', 'B', 16);

// Header
$pdf->Cell(0, 10, 'Product Details Report', 0, 1, 'C');

$mainCategories = array();

while ($row2 = mysqli_fetch_assoc($productresult)) {
    $mainCat = $row2["main_cat"];
    $subCat = $row2["sub_cat"];
    $credit = $row2["creditPrice"];
    $check = $row2["checkPrice"];
    $cash = $row2["cashPrice"];

    // If main category already exists in the array, append the sub product
    if (array_key_exists($mainCat, $mainCategories)) {
        $found = false;
        foreach ($mainCategories[$mainCat] as &$subProduct) {
            if ($subProduct['sub_cat'] === $subCat) {
                $subProduct['count']++; // Increment count
                $found = true;
                break;
            }
        }
        if (!$found) {
            $mainCategories[$mainCat][] = array("sub_cat" => $subCat, "cashPrice" => $cash, "checkPrice" => $check, "creditPrice" => $credit, "count" => 1);
        }
    } else {
        $mainCategories[$mainCat] = array(array("sub_cat" => $subCat, "cashPrice" => $cash, "checkPrice" => $check, "creditPrice" => $credit, "count" => 1));
    }
}

// Loop through main categories and display them
$pdf->SetFont('Arial', '', 12);

foreach ($mainCategories as $mainCat => $subProducts) {
    $pdf->SetFont('Arial', 'B', 12); // Set font to bold
    $pdf->Cell(0, 6, $mainCat, 0, 1, 'L');
    $pdf->SetFont('Arial', '', 12); // Reset font
    $pdf->Cell(80, 6, 'Product Name', 1);
    $pdf->Cell(30, 6, 'Cash Price', 1);
    $pdf->Cell(30, 6, 'Check Price', 1);
    $pdf->Cell(30, 6, 'Credit Price', 1);
    $pdf->Ln();

    foreach ($subProducts as $subProduct) {
        $pdf->Cell(80, 6, $subProduct['sub_cat'], 1);
        $pdf->Cell(30, 6, $subProduct['cashPrice'], 1);
        $pdf->Cell(30, 6, $subProduct['checkPrice'], 1);
        $pdf->Cell(30, 6, $subProduct['creditPrice'], 1);
        $pdf->Ln();
    }
    $pdf->Ln();
}

// Output PDF as download
$pdf->Output('customer_data_report.pdf', 'D');
