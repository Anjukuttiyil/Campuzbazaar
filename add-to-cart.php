<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    $userEmail = $_SESSION['email'];

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

    // Check if the product is already in the cart
    $stmt = $conn->prepare("SELECT id FROM cart WHERE user_email = ? AND product_id = ?");
    $stmt->bind_param("si", $userEmail, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If product is already in cart, update the quantity
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_email = ? AND product_id = ?");
        $stmt->bind_param("si", $userEmail, $productId);
    } else {
        // If product is not in cart, add it
        $stmt = $conn->prepare("INSERT INTO cart (user_email, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("si", $userEmail, $productId);
    }

    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: view-cart.php");
    exit();
}
?>
