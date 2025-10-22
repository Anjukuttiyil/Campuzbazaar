<?php
require('fpdf.php'); // Ensure you have the FPDF library included

// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    die("You need to log in to download payment details.");
}

if (isset($_GET['id'])) {
    $payment_id = $_GET['id'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "1234";
    $dbname = "bazaar";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch payment details
    $stmt = $conn->prepare("SELECT product_id, amount, seller, buyer FROM payments WHERE payment_id = ?");
    $stmt->bind_param("s", $payment_id);
    $stmt->execute();
    $stmt->bind_result($product_id, $amount, $seller, $buyer);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Payment Details', 0, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'Transaction ID: ' . $payment_id);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Product ID: ' . $product_id);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Amount: $' . number_format($amount, 2));
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Seller: ' . $seller);
    $pdf->Ln();
    $pdf->Cell(40, 10, 'Buyer: ' . $buyer);
    $pdf->Ln();

    // Output PDF to browser
    $pdf->Output('D', 'payment_details_' . $payment_id . '.pdf'); // 'D' for download
} else {
    echo "Invalid payment ID.";
}
?>
