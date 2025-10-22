<?php
session_start(); // Start the session

// Database credentials
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "bazaar";

$updateStatus = null;
$product = null;

if (!isset($_SESSION['email'])) {
    die("You need to log in to update a product.");
}

$userEmail = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);
    
    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch product details
    $stmt = $conn->prepare("SELECT name, description, price, category, image FROM products WHERE id = ? AND email = ?");
    $stmt->bind_param("is", $productId, $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Product not found or you do not have permission to edit it.");
    }

    $product = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['productId']);
    $productName = htmlspecialchars($_POST['productName']);
    $productDescription = htmlspecialchars($_POST['productDescription']);
    $productPrice = floatval($_POST['productPrice']);
    $productCategory = htmlspecialchars($_POST['productCategory']);
    
    // Initialize with existing image if no new image is uploaded
    $uploadFile = isset($product['image']) ? $product['image'] : '';

    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['productImage']['name']);
        
        if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadFile)) {
            $updateStatus = 'fail';
        } else {
            $updateStatus = 'success';
        }
    } else {
        $updateStatus = 'success';
    }

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update product details
    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ? AND email = ?");
    $stmt->bind_param("ssdssis", $productName, $productDescription, $productPrice, $productCategory, $uploadFile, $productId, $userEmail);

    if ($stmt->execute()) {
        $updateStatus = 'success';
        header("Location: update-product.php?id=" . $productId . "&status=" . $updateStatus);
    } else {
        $updateStatus = 'fail';
    }

    $stmt->close();
    $conn->close();
    
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - CampusBazaaR</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.16/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #2C3E50;
            padding-top: 80px;
        }
        .navbar {
            background: linear-gradient(135deg, #2C3E50, #3498DB);
            padding: 1rem 2rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
        }
        .container {
            max-width: 600px;  /* Compress form container width */
            width: 100%;
            margin: 3rem auto;
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }
        .page-title {
            color: #2C3E50;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            font-size: 1rem;
        }
        .btn {
            background-color: #3498DB;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            width: 100%;
            margin-top: 1.5rem;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .product-image-preview {
            max-width: 150px;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#"><i class="fas fa-store mr-2"></i>CampusBazaaR</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="sellmenu.html"><i class="fas fa-home mr-1"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html"><i class="fas fa-info-circle mr-1"></i>About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h2 class="page-title">Update Product</h2>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'success'): ?>
                <div class="alert alert-success">
                    Product updated successfully!
                </div>
            <?php elseif ($_GET['status'] === 'fail'): ?>
                <div class="alert alert-danger">
                    Error updating product.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form action="update-product.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="productId" value="<?php echo htmlspecialchars($_GET['id']); ?>">
            
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" id="productName" name="productName" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="productDescription">Description</label>
                <textarea id="productDescription" name="productDescription" class="form-control" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="productPrice">Price</label>
                <input type="number" id="productPrice" name="productPrice" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required step="0.01">
            </div>

            <div class="form-group">
                <label for="productCategory">Category</label>
                <input type="text" id="productCategory" name="productCategory" class="form-control" value="<?php echo htmlspecialchars($product['category']); ?>" required>
            </div>

            <div class="form-group">
                <label for="productImage">Upload New Image</label>
                <input type="file" id="productImage" name="productImage" class="form-control">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="product-image-preview">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn">Update Product</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
