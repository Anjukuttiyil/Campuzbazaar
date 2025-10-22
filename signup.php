<?php
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "bazaar";

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate input
    $requiredFields = ['name', 'address', 'email', 'phone_number', 'course', 'branch', 'batch', 'admission_number', 'password'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required.']);
            exit;
        }
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM login WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists.']);
        exit;
    }

    // Check if admission number already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM userdata WHERE admission_number = ?");
    $stmt->execute([$data['admission_number']]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Admission number already exists.']);
        exit;
    }

    // Hash the password
    $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

    // Insert into login table
    $stmt = $pdo->prepare("INSERT INTO login (email, password_hash, user_type) VALUES (?, ?, 'user')");
    $stmt->execute([$data['email'], $passwordHash]);

    // Insert into userdata table
    $stmt = $pdo->prepare("INSERT INTO userdata (email, username, address, phone_number, course, branch, batch, admission_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$data['email'], $data['name'], $data['address'], $data['phone_number'], $data['course'], $data['branch'], $data['batch'], $data['admission_number']]);

    // Success
    echo json_encode(['success' => true, 'message' => 'Sign up successful.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
