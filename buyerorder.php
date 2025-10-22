<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "<h2>Please log in to view your orders.</h2>";
    exit;
}

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '1234';
$database = 'bazaar';

try {
    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Retrieve seller's email
    $seller_email = $_SESSION['email'];

    // Fetch ordered products for the logged-in seller
    $stmt = $conn->prepare("SELECT p.name, p.price, p.image,p.id, pay.payment_id, pay.amount,p.order_status, pay.created_at, pay.seller
                            FROM payments pay 
                            JOIN products p ON pay.product_id = p.id 
                            WHERE pay.buyer = ?");
    $stmt->bind_param("s", $seller_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $orders = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $orders = [];
    }

    $stmt->close();
} catch (Exception $e) {
    // Log error and show a message
    error_log($e->getMessage(), 3, '/var/log/myapp_errors.log');
    echo "An error occurred while fetching your orders.";
    exit;
} finally {
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders - CampusBazaaR</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #3498DB;
            --accent-color: #E74C3C;
            --background-color: #ECF0F1;
            --card-background: #FFFFFF;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--primary-color);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1rem 2rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
            margin: 0 0.5rem;
            position: relative;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: white;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .order-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .page-title {
            color: var(--primary-color);
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary-color), var(--accent-color));
            border-radius: 2px;
        }

        .order-card {
            background: var(--card-background);
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .order-content {
            display: flex;
            padding: 1.5rem;
            gap: 2rem;
        }

        .order-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .order-details {
            flex: 1;
        }

        .product-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .price {
            font-size: 1.2rem;
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .order-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .order-id {
            background-color: #f8f9fa;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-family: monospace;
        }

        .btn-group {
            margin-top: 1rem;
            display: flex;
            gap: 1rem;
        }

        .btn-contact, .btn-delivered {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-contact {
            background-color: var(--secondary-color);
            border: none;
        }

        .btn-contact:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        .btn-delivered {
            background-color: #27ae60;
            border: none;
        }

        .btn-delivered:hover {
            background-color: #219a52;
            transform: translateY(-2px);
        }

        .delivered-badge {
            background-color: #27ae60;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .empty-orders {
            text-align: center;
            padding: 3rem;
            background: var(--card-background);
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }

        .empty-orders i {
            font-size: 4rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .empty-orders p {
            font-size: 1.2rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .order-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .order-image {
                width: 200px;
                height: 200px;
                margin-bottom: 1rem;
            }

            .btn-group {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#"><i class="fas fa-store mr-2"></i>CampusBazaaR</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.html"><i class="fas fa-home mr-1"></i>Home</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fas fa-user mr-1"></i>Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html"><i class="fas fa-info-circle mr-1"></i>About</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="order-container">
        <h1 class="page-title">Your Orders</h1>
        
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-content">
                        <img src="<?php echo htmlspecialchars($order['image']); ?>" 
                             alt="<?php echo htmlspecialchars($order['name']); ?>" 
                             class="order-image">
                        <div class="order-details">
                            <h3 class="product-name"><?php echo htmlspecialchars($order['name']); ?></h3>
                            <div class="price">₹<?php echo number_format($order['amount'], 2); ?></div>
                            <div class="order-meta">
                                <p><i class="fas fa-hashtag mr-2"></i>Payment ID: <span class="order-id"><?php echo htmlspecialchars($order['payment_id']); ?></span></p>
                                <p><i class="far fa-calendar-alt mr-2"></i>Ordered on: <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></p>
                            </div>

                            <?php if ($order['order_status'] == 0): ?>
                                <div class="btn-group">
                                    <a href="message1.php?buyer_email=<?php echo urlencode($order['seller']); ?>&product_id=<?php echo $order['id']; ?>" 
                                       class="btn btn-primary btn-contact">
                                        <i class="fas fa-comment-dots"></i>
                                        Contact Buyer
                                    </a>
                                    <a href="mark-delivered.php?product_id=<?php echo urlencode($order['id']); ?>" 
                                       class="btn btn-delivered">
                                        <i class="fas fa-check-circle"></i>
                                        Mark as Delivered
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="delivered-badge">
                                    <i class="fas fa-check-circle"></i>
                                    Product Delivered
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-orders">
                <i class="fas fa-box-open"></i>
                <p>No orders found. Start shopping to see your orders here!</p>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>