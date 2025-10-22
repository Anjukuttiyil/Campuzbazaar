<?php
session_start(); 

if (!isset($_SESSION['email'])) {
    echo "<h2>Please log in to view your orders.</h2>";
    exit;
}

$host = 'localhost';
$username = 'root';
$password = '1234';
$database = 'bazaar';

try {
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    $seller_email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT p.name, p.price, p.image, p.id, p.order_status, pay.payment_id, pay.amount, pay.created_at, pay.buyer
                            FROM payments pay
                            JOIN products p ON pay.product_id = p.id
                            WHERE pay.seller = ?");
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
    <title>Seller Orders | CampusBazaaR</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a90e2;
        }
        
        /* Navbar Styles */
        .navbar {
            background: var(--primary-color) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #fff !important;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            transition: color 0.3s ease;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
        }
        .nav-link:hover {
            color: #fff !important;
        }
        
        /* Container Spacing */
        .main-container {
            margin-top: 100px;
            margin-bottom: 2rem;
        }
        
        /* Card Styles */
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .order-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Stats Section */
        .stats-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Product Image */
        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        /* Status Badge */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        /* Button Styles */
        .btn-contact {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-contact:hover {
            background-color: #357abd;
            transform: translateY(-2px);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.8rem 1rem;
            }
            .stats-card {
                margin-bottom: 1rem;
            }
            .main-container {
                margin-top: 80px;
            }
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fas fa-store-alt mr-2"></i>
            CampusBazaaR
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="menu.php"><i class="fas fa-home mr-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php"><i class="fas fa-user mr-1"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html"><i class="fas fa-info-circle mr-1"></i> About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container main-container">
        <!-- Stats Section -->
        <?php if (!empty($orders)): ?>
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stats-card p-4 text-center">
                    <div class="stats-value text-primary"><?php echo count($orders); ?></div>
                    <div class="stats-label">Total Orders</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card p-4 text-center">
                    <div class="stats-value text-success">
                        <?php echo count(array_filter($orders, function($order) { return $order['order_status'] == 1; })); ?>
                    </div>
                    <div class="stats-label">Delivered</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card p-4 text-center">
                    <div class="stats-value text-info">
                        ₹<?php echo number_format(array_sum(array_column($orders, 'amount')), 2); ?>
                    </div>
                    <div class="stats-label">Total Revenue</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Orders Section -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 mb-0 font-weight-bold">Your Orders</h2>
                    <span class="text-muted"><?php echo date('F j, Y'); ?></span>
                </div>

                <?php if (!empty($orders)): ?>
                <div class="row">
                    <?php foreach ($orders as $order): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card order-card h-100">
                            <img src="<?php echo htmlspecialchars($order['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($order['name']); ?>"
                                 class="product-img">
                            <div class="card-body">
                                <h5 class="card-title font-weight-bold mb-3">
                                    <?php echo htmlspecialchars($order['name']); ?>
                                </h5>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 mb-0 text-success">
                                        ₹<?php echo number_format($order['amount'], 2); ?>
                                    </span>
                                    <span class="status-badge <?php echo $order['order_status'] == 1 ? 'bg-success' : 'bg-primary'; ?> text-white">
                                        <?php echo $order['order_status'] == 1 ? 'Delivered' : 'In Progress'; ?>
                                    </span>
                                </div>
                                <div class="text-muted small">
                                    <p class="mb-1"><strong>Payment ID:</strong> <?php echo htmlspecialchars($order['payment_id']); ?></p>
                                    <p class="mb-1"><strong>Order Date:</strong> <?php echo date('M j, Y', strtotime($order['created_at'])); ?></p>
                                    <p class="mb-3"><strong>Time:</strong> <?php echo date('g:i A', strtotime($order['created_at'])); ?></p>
                                </div>
                                <?php if ($order['order_status'] != 1): ?>
                                <a href="messages.php?buyer_email=<?php echo urlencode($order['buyer']); ?>&product_id=<?php echo $order['id']; ?>"
                                   class="btn btn-contact btn-block">
                                    <i class="fas fa-comment-alt mr-2"></i>Contact Buyer
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <div class="display-4 mb-3">📦</div>
                    <p class="h5 text-muted mb-2">No orders found</p>
                    <p class="text-muted">Your orders will appear here once customers start purchasing</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>