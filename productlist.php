<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Products - CampusBazaaR</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #2C3E50;
            --secondary-color: #3498DB;
            --accent-color: #E74C3C;
            --success-color: #2ECC71;
            --warning-color: #F1C40F;
            --danger-color: #E74C3C;
            --background-color: #F7F9FC;
            --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--primary-color);
            min-height: 100vh;
            padding-top: 80px;
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
            font-weight: 500;
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

        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 2rem;
            border: none;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-info {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .product-category {
            display: inline-block;
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .card-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-action {
            flex: 1;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-update {
            background-color: var(--warning-color);
            border: none;
            color: var(--primary-color);
        }

        .btn-update:hover {
            background-color: #d4ac0d;
            transform: translateY(-2px);
        }

        .btn-remove {
            background-color: var(--danger-color);
            border: none;
            color: white;
        }

        .btn-remove:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .empty-products {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            margin: 2rem auto;
            max-width: 600px;
        }

        .empty-products i {
            font-size: 4rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .empty-products p {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 1.5rem;
        }

        .btn-add-product {
            background: linear-gradient(135deg, var(--secondary-color), var(--success-color));
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add-product:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
            color: white;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .product-card {
                margin: 1rem 0;
            }

            .card-actions {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
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
        <h1 class="page-title">Your Products</h1>
        <div class="row">
            <?php
            

            if (!isset($_SESSION['email'])) {
                echo '<div class="col-12">
                        <div class="empty-products">
                            <i class="fas fa-user-lock"></i>
                            <p>You need to log in to view your products.</p>
                            <a href="login.php" class="btn-add-product">
                                <i class="fas fa-sign-in-alt"></i>
                                Login Now
                            </a>
                        </div>
                      </div>';
                exit;
            }

            $userEmail = $_SESSION['email'];
            $servername = "localhost";
            $username = "root";
            $password = "1234";
            $dbname = "bazaar";

            try {
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    throw new Exception("Connection failed: " . $conn->connect_error);
                }

                $stmt = $conn->prepare("SELECT id, name, price, category, image FROM products WHERE email = ? and product_count!=0");
                $stmt->bind_param("s", $userEmail);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4">
                                <div class="product-card">
                                    <img src="' . htmlspecialchars($row['image']) . '" class="product-image" alt="' . htmlspecialchars($row['name']) . '">
                                    <div class="product-info">
                                        <h5 class="product-title">' . htmlspecialchars($row['name']) . '</h5>
                                        <span class="product-category">
                                            <i class="fas fa-tag mr-1"></i>' . htmlspecialchars($row['category']) . '
                                        </span>
                                        <div class="product-price">₹' . number_format($row['price'], 2) . '</div>
                                        <div class="card-actions">
                                            <a href="update-product.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-action btn-update">
                                                <i class="fas fa-edit"></i>
                                                Update
                                            </a>
                                            <a href="remove-product.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-action btn-remove">
                                                <i class="fas fa-trash-alt"></i>
                                                Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    echo '<div class="col-12">
                            <div class="empty-products">
                                <i class="fas fa-box-open"></i>
                                <p>You haven\'t added any products yet.</p>
                                <a href="addproduct.html" class="btn-add-product">
                                    <i class="fas fa-plus-circle"></i>
                                    Add Your First Product
                                </a>
                            </div>
                          </div>';
                }

                $stmt->close();
                $conn->close();
            } catch (Exception $e) {
                echo '<div class="col-12">
                        <div class="empty-products">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>An error occurred while fetching your products. Please try again later.</p>
                        </div>
                      </div>';
            }
            ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>