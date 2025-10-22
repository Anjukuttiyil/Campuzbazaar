<?php
header('Content-Type: application/json');

$apiKey = 'rzp_test_uGeI4kIzxIZHZA'; // Your Razorpay API key
$apiSecret = 'YOUR_API_SECRET'; // Your Razorpay API secret

// Retrieve POST data
$productId = $_POST['product_id'];
$productName = $_POST['product_name'];
$productPrice = $_POST['product_price'];

// Initialize Razorpay API
require('vendor/autoload.php'); // Make sure to include the Razorpay SDK
use Razorpay\Api\Api;

$api = new Api($apiKey, $apiSecret);

try {
    // Create a new order
    $order = $api->order->create([
        'amount' => $productPrice * 100, // Amount in paise
        'currency' => 'INR',
        'receipt' => 'order_rcptid_' . time()
    ]);

    $response = [
        'success' => true,
        'order_id' => $order->id,
        'amount' => $order->amount
    ];
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
?>
