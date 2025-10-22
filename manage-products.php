<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.html');
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "1234", "bazaar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch payments and related product information
$query = "SELECT 
    p.id AS payment_id,
    p.payment_id AS transaction_id,
    p.amount,
    p.created_at AS payment_date,
    p.seller,
    p.buyer,
    pr.id AS product_id,
    pr.name AS product_name,
    pr.description,
    pr.price,
    pr.category,
    pr.image,
    pr.product_count,
    pr.order_status
FROM 
    payments p
    LEFT JOIN products pr ON p.seller = pr.email
ORDER BY 
    p.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusBazaaR - Transactions Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #2ECC71;
            --dark-color: #2C3E50;
            --light-color: #F5F7FA;
        }

        body {
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
        }

        .container {
            margin-top: 100px;
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background: rgba(74, 144, 226, 0.05);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .search-input {
            border-radius: 50px;
            padding: 1rem 1.5rem;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .details-row {
            background: #f8f9fa;
            display: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fas fa-store mr-2"></i>CampusBazaaR Admin
        </a>
        <div class="ml-auto">
            <a href="logout.php" class="btn btn-outline-danger">
                <i class="fas fa-sign-out-alt mr-1"></i>Logout
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header text-center mb-4">
            <h1>Transactions Dashboard</h1>
            <p class="text-muted">View and manage payment transactions</p>
        </div>

        <input type="text" id="searchInput" class="form-control search-input" 
               placeholder="Search by transaction ID, seller, or buyer...">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Transaction Details</th>
                                <th>Seller & Buyer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr class="transaction-row">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                                             class="product-image mr-3" alt="Product">
                                        <div>
                                            <strong><?php echo htmlspecialchars($row['product_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($row['category']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>ID: <?php echo htmlspecialchars($row['transaction_id']); ?></strong><br>
                                    <small class="text-muted">
                                        <?php echo date('M d, Y H:i', strtotime($row['payment_date'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div>Seller: <?php echo htmlspecialchars($row['seller']); ?></div>
                                    <div>Buyer: <?php echo htmlspecialchars($row['buyer']); ?></div>
                                </td>
                                <td>
                                    <strong>₹<?php echo number_format($row['amount'], 2); ?></strong>
                                </td>
                                <td>
                                    <span class="status-badge bg-<?php 
                                        echo $row['order_status'] == 'completed' ? 'success' : 
                                            ($row['order_status'] == 'pending' ? 'warning' : 'secondary'); 
                                        ?> text-white">
                                        <?php echo ucfirst(htmlspecialchars($row['order_status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary view-details" 
                                            data-transaction-id="<?php echo $row['payment_id']; ?>">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            <tr class="details-row" id="details-<?php echo $row['payment_id']; ?>">
                                <td colspan="6">
                                    <div class="p-3">
                                        <h6>Product Details</h6>
                                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <strong>Original Price:</strong> ₹<?php echo number_format($row['price'], 2); ?>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Available Count:</strong> <?php echo $row['product_count']; ?>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Search functionality
        $('#searchInput').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            $('.transaction-row').each(function() {
                const rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.includes(searchText));
                
                // Hide details row when filtering
                const transactionId = $(this).find('.view-details').data('transaction-id');
                $(`#details-${transactionId}`).hide();
            });
        });

        // Toggle details view
        $('.view-details').click(function() {
            const transactionId = $(this).data('transaction-id');
            $(`#details-${transactionId}`).toggle();
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>