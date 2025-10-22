<?php
session_start(); // Start the session

// Database connection parameters
$host = 'localhost'; // Change if your DB is hosted elsewhere
$username = 'root'; // Replace with your database username
$password = '1234'; // Replace with your database password
$database = 'bazaar'; // Replace with your database name

try {
    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and validate payment data from POST request
    if (isset($_POST['product_id'], $_POST['payment_id'], $_POST['amount'], $_POST['seller_email'], $_POST['buyer_email'])) {
        $product_id = (int)$_POST['product_id']; // Ensure product_id is an integer
        $payment_id = $_POST['payment_id']; // Payment ID as string
        $amount = (float)$_POST['amount']; // Ensure amount is a float
        $seller_email = $_POST['seller_email']; // Seller's email
        $buyer_email = $_POST['buyer_email']; // Buyer's email
        $stmt = $conn->prepare("SELECT product_count FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($current_product_count);
$stmt->fetch();
$stmt->close();

if ($current_product_count === null || $current_product_count <= 0) {
    throw new Exception("Product not found or out of stock.");
}

// Subtract 1 from the current product count
$new_product_count = $current_product_count - 1;

// Update the product count in the database
$stmt = $conn->prepare("UPDATE products SET product_count = ? WHERE id = ?");
$stmt->bind_param("ii", $new_product_count, $product_id);
if (!$stmt->execute()) {
    throw new Exception("Error updating product count: " . $stmt->error);
}
$stmt->close();

      

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO payments (product_id, payment_id, amount, seller, buyer) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("isdss", $product_id, $payment_id, $amount, $seller_email, $buyer_email);

        if ($stmt->execute()) {
            echo "Payment saved successfully!";
        } else {
            throw new Exception("Error saving payment: " . $stmt->error);
        }

        $stmt->close();
    } else {
        echo "Invalid payment data!";
    }
} catch (Exception $e) {
    // Log error to a file (you can customize the path)
    error_log($e->getMessage(), 3, '/var/log/myapp_errors.log');
    echo "An error occurred while processing your request.";
} finally {
    // Close connection
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
?>
