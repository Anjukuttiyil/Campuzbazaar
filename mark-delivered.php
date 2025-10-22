<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<h2>Please log in to update the order status.</h2>";
    exit;
}

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '1234';
$database = 'bazaar';

if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];
} else {
    echo "Invalid product ID provided.";
    exit;
}

try {
    // Create connection
    $conn = new mysqli($host, $username, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Start a transaction
    $conn->begin_transaction();

    // Update the product's order status to 'delivered' (1)
    $stmt1 = $conn->prepare("UPDATE products SET order_status = 1 WHERE id = ?");
    $stmt1->bind_param("i", $product_id);
    if (!$stmt1->execute()) {
        throw new Exception("Error updating order status.");
    }

    // Delete the messages related to this product
    $stmt2 = $conn->prepare("DELETE FROM messages WHERE product_id = ?");
    $stmt2->bind_param("i", $product_id);
    if (!$stmt2->execute()) {
        throw new Exception("Error deleting messages.");
    }

    // Retrieve the email of the seller associated with the product
    $stmt3 = $conn->prepare("SELECT email FROM products WHERE id = ?");
    $stmt3->bind_param("i", $product_id);
    $stmt3->execute();
    $stmt3->bind_result($seller_email);
    $stmt3->fetch();

    // Free the result set and close the statement
    $stmt3->free_result();
    $stmt3->close();

    // Check if seller email exists
    if (!$seller_email) {
        throw new Exception("No seller email found for the product.");
    }

    // Commit the transaction
    $conn->commit();

    // Redirect back to the rating page with the seller's email as a URL parameter
    header('Location: rating.php?seller_email=' . urlencode($seller_email));
    exit();

} catch (Exception $e) {
    // If any error occurs, roll back the transaction
    $conn->rollback();

    // Log the error message and display a generic message
    error_log($e->getMessage(), 3, '/var/log/myapp_errors.log');
    echo "An error occurred while updating the order status and deleting messages.";
    exit;
} finally {
    // Close the connection and prepared statements
    if (isset($stmt1)) { $stmt1->close(); }
    if (isset($stmt2)) { $stmt2->close(); }
    if (isset($conn) && $conn) { $conn->close(); }
}
?>
