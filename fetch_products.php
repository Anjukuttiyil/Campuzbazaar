<?php
session_start(); // Start the session

header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "bazaar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    $conn->close();
    exit();
}

$email = $_SESSION['email'];

// Fetch user ID based on email
$stmt = $conn->prepare("SELECT id FROM login WHERE email = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database query preparation failed: ' . $conn->error]);
    $conn->close();
    exit();
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();

// Get POST data
$category = isset($_POST['category']) ? $_POST['category'] : '';

// Prepare SQL query
$sql = "
    SELECT * 
    FROM products 
    WHERE email <> ? and product_count>0
";
if ($category) {
    $sql .= " AND category LIKE ?";  // Fixed the alias
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database query preparation failed: ' . $conn->error]);
    $conn->close();
    exit();
}

// Bind parameters and execute
if ($category) {
    $searchTerm = '%' . $category . '%';
    $stmt->bind_param("ss", $email, $searchTerm);
} else {
    $stmt->bind_param("s", $email);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch products
$products = array();
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Return the result
echo json_encode(['success' => true, 'products' => $products]);

$stmt->close();
$conn->close();
?>
