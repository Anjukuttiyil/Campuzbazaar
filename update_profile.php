<?php
// Start the session to check if the user is logged in
session_start();

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to update your profile.']);
    exit();
}

$email = $_SESSION['email'];

// Database configuration
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "bazaar";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the JSON data from the request body
$data = json_decode(file_get_contents('php://input'), true);

// Extract the form data
$name = $conn->real_escape_string($data['name']);
$address = $conn->real_escape_string($data['address']);
$phone_number = $conn->real_escape_string($data['phone_number']);

// Prepare the SQL query to update user profile data
$sql = "UPDATE userdata SET username='$name', address='$address', phone_number='$phone_number' WHERE email='$email'";

// Execute the query
if ($conn->query($sql) === TRUE) {
    // Success
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully!']);
} else {
    // Error
    echo json_encode(['success' => false, 'message' => 'Error updating profile: ' . $conn->error]);
}

// Close the database connection
$conn->close();
?>
