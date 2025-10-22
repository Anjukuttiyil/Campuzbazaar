<?php
session_start();

if (!isset($_SESSION['email'])) {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit;
}

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

// Get the product ID from the query string
if (isset($_GET['id'])) {
    $itemId = intval($_GET['id']);
    $userEmail = $_SESSION['email'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_email = ?");
    $stmt->bind_param("is", $itemId, $userEmail);

    if ($stmt->execute()) {
        // Redirect to the cart page after successful removal
        header('Location: view-cart.php');
        exit;
    } else {
        echo "Error removing item from cart.";
    }

    $stmt->close();
} else {
    echo "No item ID provided.";
}

$conn->close();
?>
