<?php
session_start(); // Start the session

if (!isset($_SESSION['email'])) {
    echo "You need to log in to remove a product.";
    exit;
}

$userEmail = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);

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

    // Fetch the product details to remove the image if it exists
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ? AND email = ?");
    $stmt->bind_param("is", $productId, $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Product not found or you do not have permission to remove it.";
        exit;
    }

    $product = $result->fetch_assoc();
    $stmt->close();

    // Delete the product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND email = ?");
    $stmt->bind_param("is", $productId, $userEmail);

    if ($stmt->execute()) {
        // Optionally, remove the product image
        if (file_exists($product['image'])) {
            unlink($product['image']);
        }
        echo "Product removed successfully!";
    } else {
        echo "Error removing product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
     header("Location:productlist.php?status=" . $updateStatus . "&id=" . $productId);

} else {
    echo "Invalid request.";
}
?>
