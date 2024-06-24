<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/fpdf/fpdf.php');

// Fetch customer data
// Include database connection file
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");

$route_id = $_SESSION['route_id'];
$customer = $_SESSION['customer'];

// Define $customerQuery variable

$customerdataQuery = "";
if ($_SESSION["state"] === "seller") {
    if ($customer === "all") {
        $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id WHERE c.route_id=$route_id";
    } else {
        $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id WHERE sto_name='$customer' AND route_id=$route_id";
    }
}
if ($_SESSION["state"] === "admin") {
    if ($customer === "all") {
        $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id ";
    } else {
        $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id WHERE sto_name='$customer'";
    }
}

$customerResult = mysqli_query($connection, $customerdataQuery);

// Initialize PDF
$pdf = new FPDF('P', 'mm', 'A5');
$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', 'B', 16);

// Header
$pdf->Cell(0, 10, 'Customer Data Report', 0, 1, 'C');

// Content
while ($row = mysqli_fetch_assoc($customerResult)) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 6, 'Owner\'s Name: ' . $row['firstName'] . ' ' . $row['LastName'], 0, 1);
    $pdf->Cell(0, 6, 'Store Name: ' . $row['sto_name'], 0, 1);
    $pdf->Cell(0, 6, 'Registration No.: ' . $row['sto_reg_no'], 0, 1);
    $pdf->Cell(0, 6, 'Contact No.: ' . "+94" . $row['sto_tep_number'], 0, 1);
    $pdf->Cell(0, 6, 'Email Address: ' . $row['email'], 0, 1);
    $pdf->Cell(0, 6, 'Location: ' . $row['sto_loc'], 0, 1);
    $pdf->Ln();
    $pdf->SetLineWidth(0.2); // Set line width
    $pdf->SetDrawColor(0, 0, 0); // Set color (black)
    $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + $pdf->GetPageWidth() - 20, $pdf->GetY());
    $pdf->Ln(); // Line break
}

// Output PDF as download
$pdf->Output('customer_data_report.pdf', 'D');
