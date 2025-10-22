<?php
    session_start();  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - CampusBazaaR</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        .navbar {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
            color: #fff !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #fff !important;
            transform: translateY(-2px);
        }

        .container {
            margin-top: 100px;
            padding: 0 2rem;
        }

        .page-title {
            color: #1e3c72;
            font-weight: 600;
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: #2a5298;
            border-radius: 2px;
        }

        .cart-item-card {
            width: 100%;
            max-width: 300px;  /* Increased card size */
            margin: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .cart-item-card img {
            height: 180px;  /* Increased image size */
            width: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .cart-item-details {
            padding: 0.5rem;
        }

        .cart-item-actions {
            text-align: center;
            padding: 0.5rem;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-danger:hover, .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .cart-total {
            margin-top: 2rem;
            text-align: right;
            font-weight: 600;
            font-size: 1.2rem;
            color: #1e3c72;
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .modal-title {
            font-weight: 600;
        }

        .close {
            color: white;
        }

        /* Flexbox Layout for Cards (Horizontal Layout) */
        .cart-items-container {
            display: flex;
            flex-wrap: nowrap;  /* Prevent cards from wrapping */
            justify-content: flex-start;
            overflow-x: auto;  /* Allow horizontal scrolling */
        }

        @media (max-width: 768px) {
            .cart-item-card img {
                height: 150px;  /* Adjust image size for mobile */
            }

            .cart-items-container {
                justify-content: flex-start;
                padding: 1rem;
            }

            .cart-item-card {
                max-width: 250px;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
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

    <div class="container">
        <h2 class="page-title">Your Cart</h2>
        
        <?php
            if (!isset($_SESSION['email'])) {
                echo '<div class="col-12"><p class="text-center">You need to log in to view your cart.</p></div>';
                exit;
            }

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

            // Fetch cart items of the logged-in user
            $stmt = $conn->prepare("SELECT cart.id, products.name, products.price, cart.quantity, products.image FROM cart JOIN products ON cart.product_id = products.id WHERE cart.user_email = ?");
            $stmt->bind_param("s", $userEmail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $total = 0;
                echo '<div class="cart-items-container">';
                while ($row = $result->fetch_assoc()) {
                    $subtotal = $row['price'] * $row['quantity'];
                    $total += $subtotal;
                    echo '<div class="card cart-item-card">';
                    echo '  <img src="' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="Product Image">';
                    echo '  <div class="card-body cart-item-details">';
                    echo '    <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
                    echo '    <p><strong>Price:</strong> $' . number_format($row['price'], 2) . '</p>';
                    echo '    <p><strong>Quantity:</strong> ' . htmlspecialchars($row['quantity']) . '</p>';
                    echo '  </div>';
                    echo '  <div class="card-footer cart-item-actions">';
                    echo '    <a href="remove-from-cart.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-danger btn-sm">Remove</a>';
                    echo '    <a href="buy-now.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-success">Checkout</a>';
                    echo '  </div>';
                    echo '</div>';
                }
                echo '</div>';
                echo '<div class="cart-total">';
                echo '  <p><strong>Total:</strong> $' . number_format($total, 2) . '</p>';
                echo '</div>';
            } else {
                echo '<div class="empty-cart"><p>Your cart is empty.</p></div>';
            }

            $stmt->close();
            $conn->close();
        ?>
    </div>

    <!-- Modal for empty cart -->
    <div class="modal fade" id="noProductsModal" tabindex="-1" role="dialog" aria-labelledby="noProductsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noProductsModalLabel">Your Cart is Empty</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Your cart doesn't contain any products yet. Please add some items to your cart.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
