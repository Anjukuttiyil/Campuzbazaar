<?php
session_start();

// Initialize response array
$response = ['status' => 'error', 'message' => 'Something went wrong'];

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    $response['message'] = 'User email not found in session';
    echo json_encode($response);
    exit;
}

$userEmail = $_SESSION['email'];

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form inputs
    $productName = isset($_POST['productName']) ? htmlspecialchars(trim($_POST['productName'])) : '';
    $productDescription = isset($_POST['productDescription']) ? htmlspecialchars(trim($_POST['productDescription'])) : '';
    $productPrice = isset($_POST['productPrice']) ? filter_var($_POST['productPrice'], FILTER_VALIDATE_FLOAT) : 0.00;
    $productCategory = isset($_POST['productCategory']) ? htmlspecialchars(trim($_POST['productCategory'])) : '';

    // Validate required fields
    if (empty($productName) || empty($productDescription) || $productPrice === false || empty($productCategory)) {
        $response['message'] = 'Please fill in all required fields correctly';
        echo json_encode($response);
        exit;
    }

    // Handle file upload
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $fileSize = $_FILES['productImage']['size'];
        $fileType = $_FILES['productImage']['type'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        // Validate file size and type
        if ($fileSize > $maxFileSize) {
            $response['message'] = 'File size exceeds the 5MB limit';
            echo json_encode($response);
            exit;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($fileType, $allowedTypes)) {
            $response['message'] = 'Invalid file type. Only JPEG, PNG, and GIF allowed';
            echo json_encode($response);
            exit;
        }

        // Generate a unique file name and move the uploaded file
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Sanitize file name and append a unique prefix
        $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['productImage']['name']));
        $uploadFile = $uploadDir . uniqid('product_', true) . '-' . $fileName;

        if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadFile)) {
            $response['message'] = 'File upload failed';
            echo json_encode($response);
            exit;
        }

        // Insert the product data into the database
        $conn = new mysqli("localhost", "root", "1234", "bazaar");

        if ($conn->connect_error) {
            $response['message'] = 'Database connection failed';
            echo json_encode($response);
            exit;
        }

        // Prepare and execute the SQL query to insert the product
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, image, email, product_count) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $productCount = 1; // Example value for product_count (could be dynamic if needed)
        $stmt->bind_param("ssdssss", $productName, $productDescription, $productPrice, $productCategory, $uploadFile, $userEmail, $productCount);

        if ($stmt->execute()) {
            // If successful
            $response['status'] = 'success';
            $response['message'] = 'Product added successfully!';
        } else {
            $response['message'] = 'Database query failed: ' . $stmt->error;
        }

        // Close connection
        $stmt->close();
        $conn->close();
    } else {
        $response['message'] = 'No file uploaded or file upload error';
    }

} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
exit;
?>
