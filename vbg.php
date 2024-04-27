<?php 
require_once('tcpdf/tcpdf.php');

function generatePDF() {
    // Create new PDF document
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Order Details');
    $pdf->SetSubject('Order Details');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Add content to the PDF
    $content = '
        <h1>Order Details</h1>
        <p>This is the content of the PDF.</p>
        <!-- Add more content as needed -->
    ';

    $pdf->writeHTML($content, true, false, true, false, '');

    // Output PDF as a file or inline in the browser
    $pdf->Output('order_details.pdf', 'D'); // D for download, I for inline
}
generatePDF()

?>