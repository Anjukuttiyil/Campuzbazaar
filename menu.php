<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusBazaaR - Menu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #2ECC71;
            --dark-color: #2C3E50;
            --light-color: #F5F7FA;
            --gradient-start: #4A90E2;
            --gradient-end: #2ECC71;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--dark-color) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .container {
            margin-top: 100px;
            padding: 2rem;
        }

        .menu-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .menu-header h1 {
            color: var(--dark-color);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .menu-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .card-img-overlay {
            background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2rem;
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .card-body {
            padding: 2rem;
            text-align: center;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .card-text {
            color: #666;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn {
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--gradient-start), var(--gradient-end));
            border: none;
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, var(--gradient-end), var(--gradient-start));
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(74, 144, 226, 0.4);
        }

        .menu-card {
            position: relative;
            height: 100%;
        }

        .menu-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(45deg, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stats {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                margin-top: 80px;
                padding: 1rem;
            }
            
            .card {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a class="navbar-brand" href="#">
            <i class="fas fa-store mr-2"></i>CampusBazaaR
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-user mr-1"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html">
                        <i class="fas fa-info-circle mr-1"></i> About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="menu-header">
            <h1>Welcome to CampusBazaaR</h1>
            <p>Your one-stop marketplace for campus trading</p>
        </div>

        <div class="row">
            <!-- Sell Products Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card menu-card">
                    <div class="card-body">
                        <div class="menu-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <h5 class="card-title">Sell Products</h5>
                        <p class="card-text">List your items for sale and reach potential buyers across campus.</p>
                        <a href="sellmenu.html" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>Sell Now
                        </a>
                    </div>
                </div>
            </div>

            <!-- Buy Products Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card menu-card">
                    <div class="card-body">
                        <div class="menu-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h5 class="card-title">Buy Products</h5>
                        <p class="card-text">Browse through a wide selection of products from fellow students.</p>
                        <a href="buyproducts.html" class="btn btn-primary">
                            <i class="fas fa-search mr-2"></i>Browse Now
                        </a>
                    </div>
                </div>
            </div>

            <!-- My Cart Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card menu-card">
                    <div class="card-body">
                        <div class="menu-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h5 class="card-title">My Cart</h5>
                        <p class="card-text">View and manage items in your shopping cart before checkout.</p>
                        <a href="view-cart.php" class="btn btn-primary">
                            <i class="fas fa-cart-arrow-down mr-2"></i>View Cart
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transactions Card -->
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card menu-card">
                    <div class="card-body">
                        <div class="menu-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h5 class="card-title">Transactions</h5>
                        <p class="card-text">Track your purchase history and manage your transactions.</p>
                        <a href="transactions.php" class="btn btn-primary">
                            <i class="fas fa-history mr-2"></i>View History
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="stats">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Active Listings</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Happy Users</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">2000+</div>
                        <div class="stat-label">Completed Trades</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">4.8</div>
                        <div class="stat-label">User Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>