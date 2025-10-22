<?php
session_start();
if (!isset($_SESSION['email']))  {
    header('Location: login.html');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusBazaaR - Admin Dashboard</title>
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
            height: 100%;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
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

        .menu-icon {
            font-size: 3.5rem;
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
            <i class="fas fa-store mr-2"></i>CampusBazaaR Admin
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
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="menu-header">
            <h1>Admin Dashboard</h1>
            <p>Manage users and products on CampusBazaaR</p>
        </div>

        <div class="row justify-content-center">
            <!-- Manage Users Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="menu-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h5 class="card-title">Manage Users</h5>
                        <p class="card-text">Add, edit, or remove user accounts. Monitor user activity and manage permissions.</p>
                        <a href="manage-users.php" class="btn btn-primary">
                            <i class="fas fa-user-shield mr-2"></i>Manage Users
                        </a>
                    </div>
                </div>
            </div>

            <!-- Manage Products Card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="menu-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h5 class="card-title">Manage Products</h5>
                        <p class="card-text">Review, approve, or remove product listings. Monitor marketplace activity.</p>
                        <a href="manage-products.php" class="btn btn-primary">
                            <i class="fas fa-cogs mr-2"></i>Manage Products
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
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Total Users</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Active Listings</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Daily Transactions</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">System Uptime</div>
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