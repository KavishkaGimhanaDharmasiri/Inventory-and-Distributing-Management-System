<?php
// Assuming you have a database connection established

if (isset($_GET['ord_id'])) {
    $ord_id = $_GET['ord_id'];

    // Fetch PDF information from the database
    $pdf_query = "SELECT pdf_path FROM generated_pdfs WHERE ord_id = '$ord_id'";
    $pdf_result = mysqli_query($connection, $pdf_query);

    if ($pdf_result && $pdf_row = mysqli_fetch_assoc($pdf_result)) {
        $pdf_path = $pdf_row['pdf_path'];
        $pdf_full_path = $_SERVER['DOCUMENT_ROOT'] . '/pdf/' . $pdf_path; // Adjust the path based on your setup

        // Check if the file exists
        if (file_exists($pdf_full_path)) {
            // Set appropriate headers for PDF output
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="' . $pdf_path . '"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($pdf_full_path));
            header('Accept-Ranges: bytes');

            // Output the PDF content
            readfile($pdf_full_path);
            exit;
        } else {
            echo 'PDF file not found on the server.';
        }
    } else {
        echo 'PDF information not found for the selected order.';
    }
} else {
    echo 'Order ID not provided.';
}

// Close the database connection
mysqli_close($connection);
?>
