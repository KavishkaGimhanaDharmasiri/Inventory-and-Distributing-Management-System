<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/fpdf/fpdf.php');
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit'])) {
    acess_denie();
    exit();
} else {
    $_SESSION['comparison_report_visit'] = true;
}

// Connect to the database
$route_id = $_SESSION['route_id'];
// Fetch total sales amounts for each month of the current year
$currentYear = date('Y');
$previousYear = $currentYear - 1;
$currentYearQuery = "";
$previousYearQuery = "";
if ($_SESSION["state"] === "seller") {
    $currentYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales FROM payment WHERE YEAR(payment_date) = $currentYear AND route_id=  $route_id GROUP BY MONTH(payment_date)";

    // Fetch total sales amounts for each month of the previous year
    $previousYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales FROM payment WHERE YEAR(payment_date) = $previousYear AND route_id=  $route_id GROUP BY MONTH(payment_date)";
}
if ($_SESSION["state"] === "admin") {
    $currentYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales FROM payment WHERE YEAR(payment_date) = $currentYear GROUP BY MONTH(payment_date)";
    // Fetch total sales amounts for each month of the previous year
    $previousYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales FROM payment WHERE YEAR(payment_date) = $previousYear GROUP BY MONTH(payment_date)";
}
$currentYearResult = mysqli_query($connection, $currentYearQuery);
$previousYearResult = mysqli_query($connection, $previousYearQuery);

// Initialize arrays to store monthly sales amounts for the current and previous years
$currentYearSales = array_fill(1, 12, 0); // Initialize with 0 for each month
$previousYearSales = array_fill(1, 12, 0);

// Populate arrays with fetched data
while ($row = mysqli_fetch_assoc($currentYearResult)) {
    $currentYearSales[$row['month']] = $row['total_sales'];
}
while ($row = mysqli_fetch_assoc($previousYearResult)) {
    $previousYearSales[$row['month']] = $row['total_sales'];
}

// Calculate percentage increase/decrease for each month compared to the previous year
$percentageChanges = array();
foreach ($currentYearSales as $month => $currentSales) {
    $previousSales = $previousYearSales[$month];
    if ($previousSales != 0) {
        $percentageChange = (($currentSales - $previousSales) / $previousSales) * 100;
    } else {
        $percentageChange = ($currentSales != 0) ? 100 : 0;
    }
    $percentageChanges[$month] = $percentageChange;
}

// Create a new PDF instance
$pdf = new FPDF();
$pdf->AddPage();

// Set font for the title
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Sales Comparison of Years', 0, 1, 'C');

// Set font for the table headers and data
$pdf->SetFont('Arial', 'B', 12);

// Table headers
$pdf->Cell(40, 10, 'Month', 1);
$pdf->Cell(40, 10, $currentYear . ' Sales(.LKR)', 1);
$pdf->Cell(50, 10, 'Percentage of Change', 1);
$pdf->Cell(40, 10, $previousYear . ' Sales(.LKR)', 1);
$pdf->Ln();

// Loop through months
for ($i = 1; $i <= 12; $i++) {
    // Calculate month name
    $monthName = date('F', mktime(0, 0, 0, $i, 1));

    // Table data
    $pdf->Cell(40, 10, $monthName, 1);
    $pdf->Cell(40, 10, $currentYearSales[$i] . ".00", 1);
    if ($currentYearSales[$i] > $previousYearSales[$i]) {
        $pdf->Cell(50, 10, number_format($percentageChanges[$i], 2) . '%', 1, 0, 'C');
    } else {
        $pdf->Cell(50, 10, number_format($percentageChanges[$i], 2) . '%', 1, 0, 'C');
    }
    $pdf->Cell(40, 10, $previousYearSales[$i] . ".00", 1);
    $pdf->Ln();
}

// Add note
$pdf->SetFont('Arial', '', 10);
//$pdf->Cell(0, 10, '*Remember All sales Amounts in LKR.', 0, 1);

// Output the PDF (force download)
$pdf->Output('sales_comparisons.pdf', 'D');
